<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="app-url" content="<?php echo base_url('/');?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DEVCODE | INSERTION NOTE</title>
    <!-- Bootstrap -->
   
  </head>

  <body>
    
        
        <!-- footer locate component -->
        <?= $this->include('components/footer.php') ?>

          <section class="login_content">
            <form action="<?php base_url() ?>/Selectstudent" id="from_login" method="post" novalidate>
              <h1>Renseignez les champs</h1>

              <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input type="text" class="form-control"  name="class" data-validate-length-range ="15" data-validate-words ="3" placeholder="classe" required="required" />
                  </div>
              </div>
              <br>

               <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input type="text" class="form-control"  name="matiere" data-validate-length-range ="40" data-validate-words ="10" placeholder="matiere" required="required" />
                  </div>
              </div>
              <br>

               <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input type="text" class="form-control"  name="sequence" data-validate-length-range ="10" data-validate-words ="2" placeholder="sequence" required="required" />
                  </div>
              </div>
              <br>

               <div class="field item">
                  <div class="col-md-12 col-sm-12">
                      <input type="text" class="form-control"  name="year" data-validate-length-range ="10" data-validate-words ="2" placeholder="annee" required="required" />
                  </div>
              </div>
              <br>
              
              <div>
                <<button type="submit" class="btn btn-primary btn-block">continuer</button>
              </div >
              

                <div>
                  <div class="flex justify-center">
                    <img src="<?= base_url() ?>/components/images/logo.png" alt="logo" width="160px">
                  </div>
                  <p>©<?= date("Y")?> Sm@rtSchool Tout Droit reservés. </p>
                </div>
            </form>
          </section>
        

  </body>
</html>
