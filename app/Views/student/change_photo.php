<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | STUDENT </title>

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

            <div class="page-title">
              <div class="title_left">
                <h4>Changer les photos des élèves</h4>
              </div>
            </div>

            <div class="clearfix"></div>

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
                    <form id="from_photo" enctype="multipart/form-data">
                        <div class="row">
                            <!-- filtre -->
                            <div class="col-sm-12 row">

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div>
                                        <label for="">Etablissement scolaire</label>
                                        <select class="form-control" name="name_school" id="name_school" onchange="getSchool()">
                                            <option value="0">Selectionner une école</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label for="">Session</label>
                                    <div>
                                        <select class="form-control" name="name_session" id="name_session" onchange="getSession()">
                                            <option value="0">Selectionner une session</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label for="">Cycle</label>
                                    <div>
                                        <select class="form-control" name="name_cycle" id="name_cycle" onchange="getCycle()">
                                        <option value="0">Selectionner un cycle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label for="">Classe</label>
                                    <div>
                                        <select class="form-control" name="name_classe" id="name_classe" onchange="getClass()">
                                        <option value="0">Selectionner une classe</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 mt-3">
                                <div class="card-box table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered mt-5" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Matricule</th>
                                            <th>Noms & Prénoms</th>
                                            <th>Sexe</th>
                                            <th>Date naissance</th>
                                            <th>Lieu Naissance</th>
                                            <th>Nouvelle photo</th>
                                        </tr>
                                        </thead>


                                        <tbody>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                <div class="ln_solid" id="bloc_btn">
                                    <!-- btn submit -->
                                </div>
                            </div>
                        </div>
                    </form>
                  </div>
              </div>

            </div>

          </div>
        </div>

        <!-- footer locate to componenents -->
        <?= $this->include('components/footer.php') ?>

      </div>
    </div>
    <script>
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
    </script>
    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/student/change_photo.js"></script>
    <?= $this->include('components/js.php') ?>
    
  </body>
</html>
