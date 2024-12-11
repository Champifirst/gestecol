<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | SCOLARITE </title>

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
                <h4>Bourses d'études</h4>
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
                  <div class="p-2">
                    <button type="button" onclick="openModalSave()" class="btn btn-success"><i class="fa fa-list"></i> Ajouter une bourse</button>
                  </div>
                </div>
                <form id="save_montant_scolarite" method="POST">
                    <div class="x_content">
                        <div class="row">
                            
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered mt-5" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Montant</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_body">
                                            
                                        </tbody>
                                    </table>
                                </div>
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

    <!-- Modal save -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Enregistrer une bourse</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form_save_bourse" class="form-horizontal form-label-left">
            <div class="modal-body">

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align"> Nom <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9">
                        <input class="form-control" name="nomBourse" id="nomBourse" placeholder="" required="required" /> 
                    </div>
                </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Montant <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                  <input type="number" min="0" id="montant_bourse" name="montant_bourse" required="required" class="form-control">
                </div>
              </div>
              <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Description <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 ">
                    <textarea name="description_bourse" id="description_bourse" cols="30" required="required" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler et fermer</button>
              <button type="submit" id="btn-log" class="btn btn-success">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <script>
        $("#user_id").val(localStorage.getItem('id_user'));
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        function openModalSave(){
            $("#staticBackdrop").modal('show', true);
        }
    </script>
    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/scolarite/bourse_scolaire.js"></script>
    <?= $this->include('components/js.php') ?>

  </body>
</html>
