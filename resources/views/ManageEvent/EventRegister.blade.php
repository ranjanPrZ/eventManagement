<!doctype html>
<html>
<head>
    <title>
        Create Laboom Account
    </title>

    {!! Html::script('vendor/vue/dist/vue.min.js') !!}
    {!! Html::script('vendor/vue-resource/dist/vue-resource.min.js') !!}

    {!! Html::style('assets/stylesheet/application.css') !!}
    {!! Html::style('assets/stylesheet/check_in.css') !!}
    {!! Html::script('vendor/jquery/dist/jquery.min.js') !!}

    @include('Shared/Layouts/ViewJavascript')
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <style>
        .text-color { color: #fff; }
        input[type="text"] { 
            border-radius: 10px;
            padding: 23px;
        }
        .submit {
            margin-top: 20px;
            background-color: cadetblue;
            border-radius: 10px;
            padding: 15px 20px; 
        }
        #form_section{
            background: url({{url(asset('assets/images/horizontal-bg.png'))}}) no-repeat;
        }
    </style>
</head>
<body style="background-size:100%; background-color:#fff; line-height: 0px; margin: 0px; padding: 0px;">
    <section style="margin-bottom: 0px; padding-top:0px;">
        <div style="margin-left:0px; padding:0px;">
            <img src="{{url(asset('assets/images/image-first.png'))}}" style="width: 100%;height:100%;">
                <img style="max-width: 110px;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;font-size: 100%;line-height: 1.6; position: relative; margin-top: -1965px; left: 46%" src="{{url(asset('assets/images/Netflix_Logo_RGB.png'))}}" />
            </img>
        </div>
        <div style="width:100%;height: 100%; padding:50px 0px;" id="form_section">
                {!! Form::model($user, array('url' => route('postRegisterUser'), 'class' => 'ajax closeModalAfter')) !!}
                    <div id="register_form" style="width: 40%; margin: 0px auto;">
                        <div class="row" style="display: flex;">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('first_name', trans("User.first_name"), array('class'=>'control-label required text-color')) !!}
                                    {!!  Form::text('first_name', old('first_name'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('last_name', trans("User.last_name"), array('class'=>'control-label required text-color')) !!}
                                    {!!  Form::text('last_name', old('last_name'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('email', trans("User.email"), array('class'=>'control-label required text-color')) !!}
                                    {!!  Form::text('email', old('email'),
                                                array(
                                                'class'=>'form-control field-radius'
                                                ))  !!}
                                </div>
                            </div>
                        </div
                    </div>
                    <div style="text-align: center;">
                        {!! Form::submit('Submit', ['class' => 'btn btn-success submit']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
    </section>
<script>
Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
</script>

@include("Shared.Partials.LangScript")
{!! Html::script('vendor/qrcode-scan/llqrcode.js') !!}
{!! Html::script('assets/javascript/check_in.js') !!}
</body>
</html>