<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Styles -->
		@include('partials.styles')
		@yield('styles')

		<title>Request Password | SchedulerPro</title>
    </head>

    <body class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 col-sm-8 col-lg-4 col-md-offset-4 col-sm-offset-2 col-lg-offset-4">
                    <div id="login-form-container">
                        <div class="login-form-header">
                            <h3 class="text-center site-logo">Scheduler<span>Pro</span></h3>
                        </div>

                        <div class="login-form-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="pass_r">
                                     <form method="POST" action="{{ URL::to('/request_reset') }}">
                                        {!! csrf_field() !!}
                                        @include('errors.form_errors')

                                        <div class="login-box">
                                            <input type="text" name="email">
                                            <label class="placeholder">Email</label>
                                        </div>

                                        @if (!empty($user->security_question))
                                        <div class="form-group" id="pass_q">
                                            <p><span>Security_Question:</span> {{ $user->security_question->question }}</p>
                                        </div>

                                        <div class="login-box">
                                            <input type="text" name="security_question_answer">
                                            <label class="placeholder">Answer</label>
                                        </div>
                                        @endif

                                        <div class="login-btn">
                                            <input type="submit" name="submit" value="SUBMIT">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        @include('partials.scripts')
        @yield('scripts')
    </body>
</html>