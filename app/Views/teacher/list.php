<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SM@RTSCHOOL | ENSEIGNANT </title>

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
                <h4>Liste du personnel</h4>
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
                                    <select class="form-control" name="name_school" id="name_school">
                                        <option value="0">Selectionner une école</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div>
                                    <label for="">Type de personnel</label>
                                    <select class="form-control" name="type_ens" id="type_ens">
                                        <option value="enseignant">Enseignant</option>
                                        <option value="0">Tout le personnel</option>
                                        <option value="chauffeur">Chauffeur</option>
                                        <option value="gardien">Gardien</option>
                                        <option value="directeur">Directeur</option>
                                        <option value="entretien">Agent d'entretien</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">.</label>
                                <div>
                                    <button type='button' class="btn btn-secondary col-12" onclick="charger_liste_teachear()">Charger la liste</button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="printf" style="width: 100%">

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
                                <th>Dilplome</th>
                                <th>Contats</th>
                                <th>Type</th>
                                <th>Salaire</th>
                                <th>Classe</th>
                                <th>Action</th>
                              </tr>
                            </thead>

                            <tbody>
                                
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
    <script>
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
        $("#type_ens").select2();
    </script>
    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/teacher/list.js"></script>
    <?= $this->include('components/js.php') ?>

  </body>
</html>
