<?php
    dict( ['is.no.page' => TRUE ] );
?>
<div class="flex-fill d-flex flex-column justify-content-center py-4">
      <div class="container-tight py-6">
        <div class="text-center mb-4">
          <a href="."><img src="./static/logo.jpg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="/login" method="post" autocomplete="off">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Login to your account</h2>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input tabindex="1" name="emailaddress" type="text" class="form-control" placeholder="Enter username" value="<?=$email?>">
            </div>

            <div class="mb-2">
              <label class="form-label">
                Password
               
              </label>
              <div class="input-group input-group-flat">
                <input name="password"  tabindex="2" type="password" class="form-control"  placeholder="Password"  autocomplete="off">
                <span class="input-group-text">
                  <a href="javascript:showPassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                  </a>
                </span>
              </div>
            </div>


            

            

            <div class="mb-2">
            <span class="form-label-description">
                  <a href="./forgot-password.html">I forgot password</a>
                </span>
              <label class="form-check">
                <input name="rememberme" type="checkbox" class="form-check-input"/>
                <span  class="form-check-label">Remember me on this device</span>
              </label>
            </div>

            <?php
                if($message!='') {
                    echo '<p class="text-center font-weight-bold text-red ">'.$message.'</p>';
                }
            ?>

            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
          </div>
          
          </div>
        </form>
        
      </div>
    </div>

    <script>
        function showPassword() {
            if($('input[name="password"]').attr("type")=="password") {
                $('input[name="password"]').attr("type","text");
            }
            else {
                $('input[name="password"]').attr("type","password");
            }

        }
        $(function(){
          $('[name=emailaddress]').focus();
        });
    </script>