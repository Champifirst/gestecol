<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | CLASS </title>

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
                <h4>Liste des salles de classes</h4>
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
                    <div class="row">
                        <!-- filtre -->
                        <div class="col-sm-12 row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div>
                                    <label for="">Etablissement scolaire</label>
                                    <select class="form-control" name="school" id="school" onchange="getSchool()">
                                        <!-- school -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">Sessions</label>
                                <div>
                                    <select class="form-control" name="count_student" id="count_student" onchange="getSession()">
                                      <option value="0">Choissez une session</option>
                                        <!-- school -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">Cycles</label>
                                <div>
                                    <select class="form-control" name="count_enseignant" id="count_enseignant">
                                      <option value="0">Choissez un cyle</option>
                                    <!-- school -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label for="session">.</label>
                              <div>
                                  <button type='button' class="btn btn-secondary col-12" onclick="charger_fiche()">Charger la fiche</button>
                              </div>
                            </div>

                        </div>


                        <div class="col-sm-12">
                          <div class="card-box table-responsive">
                          <table id="datatable-buttons" class="table table-bordered mt-5" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Enseignant(e) Titulaire</th>
                                <th>Nombre d'élèves</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody id="contain_body">
                              <!-- tr -->
                            </tbody>
                          </table>
                        </div>
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
    <script src="<?= base_url()?>/function/class/list.js"></script>
    <?= $this->include('components/js.php') ?>

  </body>
</html>
