<!DOCTYPE html>
<html lang="en">
  <head>
    <title>SM@RTSCHOOL | YEAR </title>
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
                  <h2>Enregistrer une année scolaire</h2>
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
                            <label class="col-form-label col-md-3 col-sm-3 label-align ">Date de début</label>
                            <div class="form-group row">
                              <div class="col-md-12 col-sm-12">
                                <input type="date" id="date_start" name="date_start" required="required" class="form-control">
                              </div>
                            </div>
                        </div>
                        <div class='col-sm-5'>
                          <label class="col-form-label col-md-3 col-sm-3 label-align ">Date de fin</label>
                          <div class="form-group row">
                              
                              <div class="col-md-12 col-sm-12">
                                <input type="date" id="date_end" name="date_end" required="required" class="form-control">
                              </div>
                            </div>
                        </div>
                        <div class='col-sm-2'>
                            <div class="form-group">
                                <button type="submit" id="btn-log" class="btn btn-secondary" style="color:white; cursor: pointer;">Enregistrer
                                  <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                      </div>
                    </form>
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

    <script src="<?= base_url()?>/function/year/save.js"></script>
    <?= $this->include('components/js.php') ?>
  </body>
</html>
