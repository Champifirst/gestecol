<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="app-url" content="<?php echo base_url('/');?>">
    <link rel="icon" href="<?= base_url() ?>/components/images/logo.png" type="image/ico" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SM@RTSCHOOL | CONNEXION</title>
    <!-- Bootstrap -->
    <link href="<?= base_url() ?>/components/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= base_url() ?>/components/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?= base_url() ?>/components/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?= base_url() ?>/components/vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?= base_url() ?>/components/build/css/custom.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>/components/docs/animBack/index.css" rel="stylesheet">

  </head>

  <body id="large-header" class="large-header">
    <canvas id="demo-canvas"></canvas>
    
    <div class="bloc-log">
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form card"> 
          <!-- messages -->
          <div id="msg">
              
          </div>
          
          <section class="login_content">
            <form action="javascript:void(0)" id="from_login" method="post" novalidate>
              <h1>Se connecter</h1>

              <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input type="text" class="form-control" data-validate-length-range="6" data-validate-words="1" name="login" id="login" placeholder="Login" required="required" />
                  </div>
              </div>
              <br>
              <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input class="form-control" type="password" id="password1" name="password" data-validate-length-range="6" data-validate-words="1" required  placeholder="Password" />
                      <span style="position: absolute;right:15px;top:7px;" onclick="hideshow()" >
                          <i id="slash" class="fa fa-eye-slash"></i>
                          <i id="eye" class="fa fa-eye"></i>
                        </span>
                  </div>
              </div>
              <div>
                <a class="reset_pass" href="">Mot de Passe Oublier?</a>
              </div>
              <div >
                <button style="margin-top: 30px;" class="btn btn-success col-md-12">Connexion</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br />

                <div>
                  <div class="flex justify-center">
                    <img src="<?= base_url() ?>/components/images/logo.png" alt="logo" width="160px">
                  </div>
                  <p>©<?= date("Y")?> Sm@rtSchool Tout Droit reservés. </p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>

    </div>

    <script src="<?= base_url() ?>/components/docs/animBack/TweenMax.min.js"></script>
    <script src="<?= base_url() ?>/components/docs/animBack/index.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/components/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>/components/vendors/validator/multifield.js"></script>
    <script src="<?= base_url() ?>/components/vendors/validator/validator.js"></script>
    <script src="<?= base_url() ?>/function/login.js"></script>

    <!-- Script Validators -->
    <script>
      function hideshow(){
        var password = document.getElementById("password1");
        var slash = document.getElementById("slash");
        var eye = document.getElementById("eye");
        
        if(password.type === 'password'){
          password.type = "text";
          slash.style.display = "block";
          eye.style.display = "none";
        }
        else{
          password.type = "password";
          slash.style.display = "none";
          eye.style.display = "block";
        }

      }
    </script>

    <script>
        var validator = new FormValidator({
            "events": ['blur', 'input', 'change']
        }, document.forms[0]);
        // on form "submit" event
        document.forms[0].onsubmit = function(e) {
            var submit = true,
                validatorResult = validator.checkAll(this);
            console.log(validatorResult);
            return !!validatorResult.valid;
        };
        // on form "reset" event
        document.forms[0].onreset = function(e) {
            validator.reset();
            window.location.href = "index.html";
        };
        // stuff related ONLY for this demo page:
        $('.toggleValidationTooltips').change(function() {
            validator.settings.alerts = !this.checked;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);

        // $('#from_login').on('submit', function(e) {
        //     event.preventDefault();
        //     var formData = new FormData(this);

        //     window.location.href = "index.html";
        // });
    </script>

  </body>
</html>
