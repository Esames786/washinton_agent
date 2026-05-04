@extends('layouts.order')

@section('template_title')
    Login
@endsection

@section('content')
<script src="https://www.google.com/recaptcha/api.js" 
async defer></script>
    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    @if(session('flash_message'))
        <div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>  {{session('flash_message')}}</div>
    @endif
    <div class="page">
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="text-white">
                                <div class="card-body">
                                    <h2 class="display-4 mb-2 font-weight-bold error-text text-center"><strong>Verification</strong>
                                    </h2>
                                    <h4 class="text-white-80 mb-7 text-center">Verification</h4>
                                    <form action="{{ route('code_verify') }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <input type="hidden" name="password" value="{{ $password }}">
                                        <div class="row">
                                            <div class="col-9 d-block mx-auto">
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-check"></i>
                                                        </div>
                                                    </div>
                                                    <input class="form-control" name="verified"
                                                           placeholder="Verification code" type="text">
                                                </div>
                                                <!--<div class="form-group">-->
                                                <!--    <label class="custom-control custom-checkbox">-->
                                                <!--        <input type="checkbox" class="custom-control-input"/>-->
                                                <!--    </label>-->
                                                <!--</div>-->
                                                <div class="col-sm-12 mb-2 p-0">
                                                    <div class="g-recaptcha" id="feedback-recaptcha" 
                                                         data-sitekey="6LeoLjknAAAAAMG7lg4VsHVuD17VTKVAt0rNElXa">
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-warning btn-block px-4" id="loginBtn"><i
                                                        class="fe fe-send"></i> Verify
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="{{ route('resend_verify') }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <input type="hidden" name="password" value="{{ $password }}">
                                        <div class="pt-4 text-center">
                                            <div class="font-weight-normal fs-16">
                                                <button
                                                    class="btn-link font-weight-normal text-white-80" type="submit">
                                                    Resend Verification
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-flex align-items-center">
                        <img src="{{ url('assets/images/png/login.png') }}" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
<script type="text/javascript">
     var onloadCallback = function() {
       grecaptcha.render('feedback-recaptcha', {
         'sitekey' : '6LeoLjknAAAAAE1OyJALGEBVvZB3xZXX-CqaqLvK'
       });
     };
     $("#loginBtn").click(function(e){
        var response = grecaptcha.getResponse();
        $("#feedback-recaptcha").parent('.col-sm-12').siblings('.text-danger').remove();
        if(response.length == 0) 
        { 
            e.preventDefault();
            $("#feedback-recaptcha").parent('.col-sm-12').after('<div class="text-danger col-sm-12 p-0 mb-2">Please check recaptcha, if you are not a robot!</div>');
        }
     })
</script>
@endsection

