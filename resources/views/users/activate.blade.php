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

		<title>Activate Account | SchedulerPro</title>
    </head>

    <body class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 col-sm-8  col-md-offset-4 col-sm-offset-2">
                    <div id="activation-form-container">
                        <div class="login-form-header">
                            <h3 class="text-center site-logo">Scheduler<span>Pro</span></h3>
                        </div>

                        <div class="login-form-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                     <form method="POST" action="{{ URL::to('/users/activate') }}" id="activate_account">
                                        {!! csrf_field() !!}
                                        @include('errors.form_errors')

                                        <div class="login-box">
                                            <label class="placeholder">Name</label>
                                            <input type="text" name="name" value="{{ $user->name }}">
                                        </div>

                                        <div class="login-box">
                                            <label class="placeholder">New Password</label>
                                            <input type="password" name="password" id="new_password">

                                            <img
                                                src="{{ URL('storage/eye-close.png') }}"
                                                id="toggle"
                                                onclick="showHide();" />

                                            <script type="text/javascript">
                                                const password = document.getElementById("new_password");
                                                const toggle = document.getElementById("toggle");

                                                function showHide() {
                                                    if (password.type == "password") {
                                                        password.setAttribute("type", "text");
                                                        document.getElementById("toggle").src =
                                                            "{{ URL('storage/eye-open.png') }}";
                                                    } else {
                                                        password.setAttribute("type", "password");
                                                        document.getElementById("toggle").src =
                                                            "{{ URL('storage/eye-close.png') }}";
                                                    }
                                                }
                                            </script>
                                        </div>

                                        <div class="login-box">
                                            <label class="placeholder">Confirm New Password</label>
                                            <input type="password" name="password_confirmation">
                                        </div>

                                        <div class="login-box">
                                            <label class="placeholder">Security Question</label>

                                            <div class="select2-wrapper">
                                                <select name="security_question_id" id="select_width">
                                                    <option selected disabled>Select a question to answer</option>
                                                    @foreach ($questions as $question)
                                                    <option value="{{ $question->id }}" id="activate_account">{{ $question->question }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="login-box">
                                            <label class="placeholder">Your Answer</label>
                                            <input type="text" class="form-control" name="security_question_answer">
                                        </div>

                                        <div class="login-btn">
                                            <input type="submit" name="submit" value="ACTIVATE ACCOUNT" id="activate_btn">
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