<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DEVCODE | SEQUENCE </title>

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
            <div class="clearfix"></div>

            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                <h2>Liste des séquences</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                          <div class="card-box table-responsive">
                            <table id="datatable-buttons" class="table table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Etablissement</th>
                                  <th>Session</th>
                                  <th>Cycle</th>
                                  <th>Classe</th>
                                  <th>Trimestre</th>
                                  <th>Code</th>
                                  <th>Séquence</th>
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

    <!-- modal -->
    

    <!-- Modal edit -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modifier la séquence</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form_update" class="form-horizontal form-label-left">
            <div class="modal-body">

              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Code de la séquence <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 ">
                  <input type="text" id="number_sequence" name="number_sequence" required="required" class="form-control">
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Nom de la séquence <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 ">
                  <input type="text" id="name_sequence" name="name_sequence" required="required" class="form-control">
                </div>
              </div>
              
              <input type="number" name="sequence_id" id="sequence_id" hidden>
              <input type="number" name="ligne_sequencer" id="ligne_sequencer" hidden>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler et fermer</button>
              <button type="submit" id="btn-log" class="btn btn-success">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>



    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/sequence/list.js"></script>
    <?= $this->include('components/js.php') ?>

  </body>
</html>
