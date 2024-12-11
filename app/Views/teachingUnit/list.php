<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | MATIERES </title>
    
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
                            <h3>Liste des matières</h3>
                        </div>

                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <!-- filtre -->
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div>
                                                <label for="school">Etablissement scolaire</label>
                                                <select class="form-control" name="name_school" id="name_school" required="required" onchange="getSchool()">
                                                    <option value="0">Selectionner un établissement</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label for="session">Session</label>
                                            <div>
                                                <select class="form-control" name="name_session" id="name_session" required="required" onchange="getSession()">
                                                    <option value="0">Selectionner une session</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label for="cycle">Cycles</label>
                                            <div>
                                                <select class="form-control" name="name_cycle" id="name_cycle" required="required" onchange="getCycle()">
                                                    <option value="0">Selectionner un cycle</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label for="classe">Classe</label>
                                            <div>
                                                <select class="form-control" name="name_classe" id="name_classe" required="required" onchange="getClass()">
                                                    <option value="0">Selectionner une classe</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="mt-5">
                                        <div class="card-box table-responsive">
                                            <table id="datatable-buttons" class="table table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Nom de la matière</th>
                                                <th>Coefficient</th>
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
            <!-- /page content -->

           <!-- footer locate to componenents -->
            <?= $this->include('components/footer.php') ?>
        </div>
    </div>

     <!-- Modal edit -->
     <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modifier une matière</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form_update" class="form-horizontal form-label-left">
            <div class="modal-body">

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Etablissement scolaire <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <select class="form-control" name="name_school_edit" id="name_school_edit" required="required" onchange="getSchoolUpdate()" style="width: 100%;">
                        <option value="0">Selectionner un établissement</option>
                    </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Session <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <select class="form-control" name="name_session_edit" id="name_session_edit" required="required" onchange="getSessionUpdate()" style="width: 100%;">
                    </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Cycle <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <select class="form-control" name="name_cycle_edit" id="name_cycle_edit" required="required" onchange="getCycleUpdate()" style="width: 100%;">
                    </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Classe <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <select class="form-control" name="name_classe_edit" id="name_classe_edit" required="required" style="width: 100%;">
                    </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Code <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control text-uppercase" id="code_edit" required >
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Nom <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control text-uppercase" id="nom_edit" required >
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Coefficient <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" min="0" class="form-control" id="coeff_edit" required >
                </div>
              </div>

              <input type="number" name="teaching_id" id="teaching_id" hidden>
              <input type="number" name="ligne_teaching" id="ligne_teaching" hidden>
            </div>
            <div class="modal-footer text-center">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler et fermer</button>
              <button type="submit" id="btn-log-update" class="btn btn-success">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <script>
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();

        $("#name_school_edit").select2();
        $("#name_session_edit").select2();
        $("#name_cycle_edit").select2();
        $("#name_classe_edit").select2();
    </script>
    <!-- For Invoice  -->
    <script src="<?= base_url()?>/function/teaching_unit/list.js"></script>
    <?= $this->include('components/js.php') ?>
    
</body>

</html>