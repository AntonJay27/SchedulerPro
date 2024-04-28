@extends('layouts.app')
@section('title')
MyAccount
@endsection
@include('bootstrap.icons')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="row">
        <div class="page_title">
            <span>MyAccount</span>
        </div>
    </div>

    <div class="page-body" id="resource-container">
        <div class="row">
            <div class="col-md-4 col-sm-8 col-xs-12 col-md-offset-4 col-sm-offset-2">
                <form method="POST" action="{{ URL::to('/my_account') }}" class="activate-account">
                    {!! csrf_field() !!}
                    @include('errors.form_errors')

                    <div class="input-box">
                        <label class="placeholder">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}">
                    </div>

                    <div class="input-box">
                        <label class="placeholder">Security Question</label>
                        <div class="select2-wrapper">
                            <select name="security_question_id" id="select_width">
                                <option selected disabled>Select a question to answer</option>
                                @foreach ($questions as $question)
                                <option value="{{ $question->id }}"
                                    @if ($user->security_question_id == $question->id) selected @endif>{{ $question->question }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-box">
                        <label class="placeholder">Your Answer</label>
                        <input type="text" name="security_question_answer" value="{{ $user->security_question_answer }}">
                    </div>

                    <div>
                        <div class="input-box">
                            <label class="placeholder">Current Password</label>
                            <input type="password" name="old_password" id="current_password">
                        
                            <img
                                src="{{ URL('storage/eye-close.png') }}"
                                id="toggle"
                                onclick="showHide();" />

                            <script type="text/javascript">
                                const password = document.getElementById("current_password");
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

                        <div class="input-box">
                            <label class="placeholder">New Password</label>
                            <input type="password" name="password">
                        </div>

                        <div class="input-box">
                            <label class="placeholder">Confirm New Password</label>
                            <input type="password" name="password_confirmation">
                        </div>
                    </div>

                    <div class="login-btn">
                        <input type="submit" name="submit" value="UPDATE ACCOUNT" id="update_btn">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection