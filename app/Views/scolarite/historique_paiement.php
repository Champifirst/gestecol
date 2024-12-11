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
                <h4>Historique de paiement</h4>
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
                                <th>Date paiement</th>
                                <th>Montant chiffre</th>
                                <th>Montant lettre</th>
                                <th>Mode paiement</th>
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

        <!-- modal -->
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button> -->

        <div class="modal fade bs-example-modal-lg" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Modifier un paiement</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="" id="from_paiement_update" method="post" novalidate>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Nom<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <input type="number" min="0" class="form-control" name="inscription" id="inscription">
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Prénom <span class="required"></span></label>
                      <div class="col-md-9 col-sm-9">
                           <input type="text" class="form-control" name="montant_lettre" id="montant_lettre">
                      </div>
                  </div>

                    <input type="number" name="ligne_update" id="ligne_update" hidden>
                    <input type="number" name="idPaiement" id="idPaiement" hidden>

                    <div class="ln_solid">
                        <div class="form-group text-center">
                            <div class="col-md-6 offset-md-3 mt-4">
                                <button type='reset' class="btn btn-danger">Annuler</button>
                                <button type='submit' class="btn btn-success" id="btn-log-update">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </form>
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
    <script src="<?= base_url()?>/function/constant.js"></script>
    <script src="<?= base_url()?>/function/scolarite/historique_paiement.js"></script>
    <?= $this->include('components/js.php') ?>
   
  </body>
</html>
