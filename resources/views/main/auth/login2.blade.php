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
                <form action="{{ route('getlogin2') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <div class="text-white">
                                    <div class="card-body">
                                        <h2 class="display-4 mb-2 font-weight-bold error-text text-center">
                                            <strong>Login</strong></h2>
                                        <h4 class="text-white-80 mb-7 text-center">Sign In to your account</h4>
                                        <div class="row">
                                            <div class="col-9 d-block mx-auto">
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-user"></i>
                                                        </div>
                                                    </div>
                                                    <input id="email" type="email"
                                                           class="form-control"
                                                           name="email" value="" required autofocus>
                                                </div>
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input id="password" type="password" class="form-control"
                                                           name="password" required>
                                                </div>
                                                <div class="col-sm-12 mb-2 p-0">
                                                    <div class="g-recaptcha" id="feedback-recaptcha" 
                                                         data-sitekey="6LeoLjknAAAAAMG7lg4VsHVuD17VTKVAt0rNElXa">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" id="loginBtn"
                                                                class="btn  btn-secondary btn-block px-4">
                                                            Login
                                                        </button>
                                                      
                                                    </div>
                                                    {{--<div class="col-12 text-center">--}}
                                                        {{--<a href="/password/reset"--}}
                                                           {{--class="btn btn-link box-shadow-0 px-0 text-white-80">Forgot--}}
                                                            {{--password?</a>--}}
                                                    {{--</div>--}}
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="text-center pt-4">--}}
                                            {{--<div class="font-weight-normal fs-16">You Don't have an account <a--}}
                                                    {{--class="btn-link font-weight-normal text-white-80" href="#">Register--}}
                                                    {{--Here</a>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="text-center pt-4">
                                            <div class="font-weight-normal fs-16" style="color:#b0c4c8;">
                                                New agent or carrier?
                                                <a class="font-weight-bold" href="{{ route('register') }}"
                                                   style="color:#8fc445;">Register Here</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-md-flex align-items-center">
                            <img src="assets/images/png/login.png" alt="img">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('extraScript')

<script src="{{ url('assets/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script>
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $(document).ready(function(){
        $.ajax({
           url:"{{ url('/logoutAllAccounts') }}",
           type:"GET",
           dataType:"json",
           success:function(res)
           {
               console.log(res);
           }
        });
    });
</script>

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

