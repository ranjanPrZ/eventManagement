@extends('Emails.Layouts.Master')

@section('message_content')
<div style="text-align:center;">
  <p style="text-align:center;">
    <img style="width:150px; height: 70px;" src="{{url(asset('assets/images/DIE_KAISERIN_LOGO_white.png'))}}">
  </p>
</div>

<p style="color: #fff; text-align:center; font-family: Baskerville;">Liebe/r {{ ucfirst($attendee->first_name) }}, </P>

<div>  
  <p style="text-align:center; color: #fff;font-family: Baskerville;">eine wichtige Botschaft vom kaiserlichen Hof.</p>
  <p style="text-align:center; color: #fff;font-family: Baskerville;">Die Kaiserin beginnt ihre Regenschaft. </p><br>
  <p style="text-align:center;color: #fff;font-family: Baskerville;">Um die neue Serie “Die Kaiserin” zu zelebrieren, lädt Netflix dich und eine <br>
      Begleitung herzlich ein, in das Leben von Elisabeth und den kaiserlichen Hof <br>
      einzutauchen. 
  </p>
  <p style="text-align:center; color: #fff; font-family: Baskerville;">Es erwartet Dich eine exklusive Experience und die erste Folge von</p>
</div>
<p style="text-align:center;">
  <a style="cursor: pointer;" href="https://localhost:8081/event/2/register"><img style="width:60px; height:82px;" src="{{url(asset('assets/images/RSVP-Button.png'))}}"></a>.
</p>

@stop
