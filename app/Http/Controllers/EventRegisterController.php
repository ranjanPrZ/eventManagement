<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Jobs\SendAttendeeInviteJob;
use JavaScript;
use Log;
use Auth;
use App\Rules\Passcheck;
use Validator;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Message;
use App\Models\OrderItem;
use App\Services\Order as OrderService;


class EventRegisterController extends MyBaseController
{
    /**
     * Show the check-in page
     *
     * @param $event_id
     * @return \Illuminate\View\View
     */
    public function showCheckIn($event_id)
    {
        $data = [
            'user' => Auth::user(),
        ];

        return view('ManageEvent.EventRegister', $data);
    }

    /**
     * Updates the current user
     *
     * @param Request $request
     * @return mixed
     */
    public function postRegisterUser(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'ticket_id'  => 'required|exists:tickets,id,account_id,' . \Auth::user()->account_id,
            'email'      => 'email|required',
        ];

        $messages = [
            'ticket_id.exists'   => trans("Controllers.ticket_not_exists_error"),
            'ticket_id.required' => trans("Controllers.ticket_field_required_error"),
        ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status'   => 'error',
        //         'messages' => $validator->messages()->toArray(),
        //     ]);
        // }
        $event_id = 3;
        $ticket_id = 3;
        $event = Event::findOrFail($event_id);
        $ticket_price = 0;
        $attendee_first_name = $request->get('first_name');
        $attendee_last_name = $request->get('last_name');
        $attendee_email = $request->get('email');
        $email_attendee = $request->get('email_ticket');

        DB::beginTransaction();

        try {

            /*
             * Create the order
             */
            $order = new Order();
            $order->first_name = $attendee_first_name;
            $order->last_name = $attendee_last_name;
            $order->email = $attendee_email;
            $order->order_status_id = config('attendize.order.registered');
            $order->amount = $ticket_price;
            $order->account_id = Auth::user()->account_id;
            $order->event_id = $event_id;

            // Calculating grand total including tax
            $orderService = new OrderService($ticket_price, 0, $event);
            $orderService->calculateFinalCosts();
            $order->taxamt = $orderService->getTaxAmount();

            if ($orderService->getGrandTotal() == 0) {
                $order->is_payment_received = 1;
            }

            $order->save();

            /*
             * Update qty sold
             */
            $ticket = Ticket::scope()->find($ticket_id);
            Log::debug('--------------------');
            Log::debug(OrderStatus::scope()->latest()->get());
            // if($ticket > 0){
                $ticket->increment('quantity_sold');
                $ticket->increment('sales_volume', $ticket_price);
            // }

            /*
             * Insert order item
             */
            $orderItem = new OrderItem();
            $orderItem->title = $ticket->title;
            $orderItem->quantity = 1;
            $orderItem->order_id = $order->id;
            $orderItem->unit_price = $ticket_price;
            $orderItem->save();

            /*
             * Update the event stats
             */
            $event_stats = new EventStats();
            $event_stats->updateTicketsSoldCount($event_id, 1);
            $event_stats->updateTicketRevenue($ticket_id, $ticket_price);

            /*
             * Create the attendee
             */
            $attendee = new Attendee();
            $attendee->first_name = $attendee_first_name;
            $attendee->last_name = $attendee_last_name;
            $attendee->email = $attendee_email;
            $attendee->event_id = $event_id;
            $attendee->order_id = $order->id;
            $attendee->ticket_id = $ticket_id;
            $attendee->account_id = Auth::user()->account_id;
            $attendee->reference_index = 1;
            $attendee->save();


            // if ($email_attendee == '1') {
                SendAttendeeInviteJob::dispatch($attendee);
            // }
            Log::debug($attendee);

            session()->flash('message', trans("Controllers.attendee_successfully_invited"));

            DB::commit();



            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showEventAttendees', [
                    'event_id' => $event_id,
                ]),
            ]);

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error'  => trans("Controllers.attendee_exception")
            ]);
        }

    }
}
