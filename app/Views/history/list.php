<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | HISTORY </title>

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
                <h4>Vos historiques de session</h4>
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
                        <div class="col-sm-12">
                          <div class="card-box table-responsive">
                          <table id="datatable-buttons" class="table table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Date & Heure</th>
                                <th>Action</th>
                                <th>Entité</th>
                                <th>Status</th>
                                <th>Client</th>
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

    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/history/list.js"></script>
    <?= $this->include('components/js.php') ?>

  </body>
</html>
