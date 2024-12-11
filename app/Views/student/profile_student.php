<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Profile élève </title>

    <!-- CSS locate component -->
    <?= $this->include('components/css.php') ?>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container"> 

        <!-- sideBar locate component -->
        <?= $this->include('components/sideBar.php') ?>

        <!-- nqvBar locate component -->
        <?= $this->include('components/navBar.php') ?>

        <!-- page content -->
        <div class="right_col" role="main">

          <!-- notice -->
          <?= $this->include('components/notice.php') ?>
          <!-- notice -->

          <div class="">

            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                      <div class="col-sm-12">
                        <?php if (isset($data)) {
                        ?>
                          <img src="<?= base_url()."/".$data["image"] ?>" width="150px;" alt="Image">
                          <div class="mt-4">
                            <h2><b>Matricule :</b> <?= $data["matricule"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Nom :</b> <?= $data["nom"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Prénom :</b> <?= $data["prenom"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Sexe :</b> <?= $data["sexe"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Session :</b> <?= $data["session"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Cycle :</b> <?= $data["cycle"] ?></h2>
                          </div>
                          <div class="mt-4">
                            <h2><b>Classe :</b> <?= $data["classe"] ?></h2>
                          </div>
                        <?php
                        }  
                        ?>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

          </div>
        </div>

        <!-- footer locate to componenents -->
        <?= $this->include('components/footer.php') ?>

      </div>
    </div>

    <!-- script locate to componenents -->
    <?= $this->include('components/js.php') ?>

  </body>
</html>
