<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />

      <!-- Styles -->
      @include('partials.styles') @yield('styles')

      <title>Sign In | SchedulerPro</title>
   </head>

   <body class="login-page">
      <div class="container">
         <div class="row">
            <div
               class="col-xs-12 col-md-4 col-sm-8 col-lg-4 col-md-offset-4 col-sm-offset-2 col-lg-offset-4">
               <div id="login-form-container">
                  <div class="login-form-header">
                     <h3 class="text-center site-logo">Scheduler<span>Pro</span></h3>
                  </div>

                  <div class="login-form-body">
                     <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <form method="POST" action="{{ URL::to('login') }}">
                              {!! csrf_field() !!}
                              @include('errors.form_errors')
                              
                              <div class="login-box">
                                 <input
                                    type="password"
                                    name="password"
                                    id="password" />
                              <label class="placeholder">Password</label>

                                 <img
                                    src="{{ URL('public/storage/eye-close.png') }}"
                                    id="toggle"
                                    onclick="showHide();" />

                                 <script type="text/javascript">
                                    const password =
                                       document.getElementById("password");
                                    const toggle =
                                       document.getElementById("toggle");

                                    function showHide() {
                                       if (password.type == "password") {
                                          password.setAttribute("type", "text");
                                          document.getElementById(
                                             "toggle"
                                          ).src =
                                             "{{ URL('public/storage/eye-open.png') }}";
                                       } else {
                                          password.setAttribute(
                                             "type",
                                             "password"
                                          );
                                          document.getElementById(
                                             "toggle"
                                          ).src =
                                             "{{ URL('public/storage/eye-close.png') }}";
                                       }
                                    }
                                 </script>
                              </div>

                              <div class="login-btn">
                                 <input
                                    type="submit"
                                    name="submit"
                                    value="LOGIN"
                                 />
                              </div>

                              <div class="forgot-btn">
                                 <a href="<?php echo config('app.url'); ?>request_reset" class="forgot-pass">Forgot Password?</a>
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
      @include('partials.scripts') @yield('scripts')
   </body>
</html>
