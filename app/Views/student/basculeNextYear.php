<!DOCTYPE html>
<html lang="en">
  <head>
    <title>DEVCODE | YEAR </title>
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

        <div class="page-title">
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Basculer les élèves à l'année suivante.</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li>
                      <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="container">
                    <form id="from_year">
                        <div class="row">
                            <div class='col-sm-5'>
                                <label class="col-form-label col-md-3 col-sm-3 label-align ">Année cloturer</label>
                                <div class="form-group row">
                                <div class="col-md-12 col-sm-12">
                                    <input type="date" id="date_start" name="date_start" required="required" class="form-control">
                                </div>
                                </div>
                            </div>
                            <div class='col-sm-5'>
                            <label class="col-form-label col-md-3 col-sm-3 label-align ">Nouvelle Année</label>
                            <div class="form-group row">
                                
                                <div class="col-md-12 col-sm-12">
                                    <input type="date" id="date_end" name="date_end" required="required" class="form-control">
                                </div>
                                </div>
                            </div>
                            <div class='col-sm-2'>
                                <div class="form-group">
                                    <button type="submit" id="btn-log" class="btn btn-secondary" style="color:white; cursor: pointer;">Générer les stats
                                    <i class="fa fa-arrow-circle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <h2>Visualiser les statistiques.</h2>

                    <div class="mt-5">
                        <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col" class="text-center" width="20%">CODE</th>
                                    <th scope="col" class="text-center" width="20%">CLASSE</th>
                                    <th scope="col" class="text-center" width="10%">ADMIS</th>
                                    <th scope="col" class="text-center" width="10%">ECHOUEE</th>
                                    <th scope="col" class="text-center" width="10%">MOY GENE</th>
                                    <th scope="col" class="text-center" width="10%">MOY PRE</th>
                                    <th scope="col" class="text-center" width="10%">MOY DER</th>
                                </tr>
                            </thead>
                            <tbody id="contain_body">
                            <!-- tr -->
                            </tbody>
                        </table>
                    </div>
                    <input type="number" name="user_id" id="user_id" min="0" hidden>
                    <div class="ln_solid" id="bloc_btn">
                        <!-- btn submit -->
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

    <script src="<?= base_url()?>/function/student/basculeYearNext.js"></script>
    <?= $this->include('components/js.php') ?>
  </body>
</html>
