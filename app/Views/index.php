<script>
  if (localStorage.getItem('autorisation') != "true") {
      window.location.href="/";
  }
</script>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>SM@RTSCHOOL | ACCEUIL</title>
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
          <!-- top tiles -->
          <div class="row" style="display: inline-block;" >
            <div class="tile_count">
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total Personnel</span>
                <div class="count" id="all_personnel">...</div>
                <span class="count_bottom"><i class="green">4 </i> Categorie</span>
              </div>
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total élèves</span>
                <div class="count" id="all_student">...</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>2 </i> Francophone / Anglophone</span>
              </div>
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-home"></i> Total écoles</span>
                <div class="count green" id="all_school">...</div>
                <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
              </div>
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-dollar"></i> Scolarité</span>
                <div class="count" id="all_scolarite">...</div>
                <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i> </i> Inscription plus frais scolaire</span>
              </div>
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-dollar"></i> Salaire</span>
                <div class="count" id="all_salaire">...</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i> </i> Tout le personnel</span>
              </div>
              <div class="col-md-2 col-sm-4  tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total Connexion</span>
                <div class="count" id="all_connexion">...</div>
                <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i></i> Nombre d'utilisateur connectés</span>
              </div>
            </div>
          </div>
          <!-- /top tiles -->

          <!-- first diagramme -->
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="dashboard_graph">
                <div class="row x_title">
                  <div class="col-md-6">
                    <h3><small>Effectifs des élèves par salle de classe</small></h3>
                  </div>
                  <div class="col-md-6 row">
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <select class="form-control" name="ecole_diagramme" id="ecole_diagramme" style="width: 100%" onchange="onChangeDiagramme()">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <button type="button" class="btn btn-dark col-12">IMPRIMER</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12" style="height: 500px;">
                  <div id="chartContainer" class="demo-placeholder" style="height: 100%;">

                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <!-- first diagramme -->

          <br />

          <!-- second diagramme -->
          <div class="row">
            <div class="col-md-12 col-sm-12 ">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3><small>Effectifs des élèves redoublants par salle de classe</small></h3>
                  </div>
                  <div class="col-md-6 row">
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <select class="form-control" name="ecole_diagrammeEffectif" id="ecole_diagrammeEffectif" style="width: 100%" onchange="onChangeDiagramme()">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <button type="button" class="btn btn-dark col-12">IMPRIMER</button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12" style="height: 500px;">
                  <div id="chartContainer2" class="demo-placeholder" style="height: 100%;">

                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <!-- second diagramme -->

          <br />

          <div class="row">


            <div class="col-md-4 col-sm-4 ">
              <div class="x_panel tile">
                <div class="x_title">
                  <h2>Effectif par année</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="reportrange" class="pull-center col-12" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <select class="form-control" name="ecole_achive" id="ecole_achive" style="width: 100%" onchange="getEcoleDiagramme()">
                        <!-- option -->
                    </select>
                  </div>
                  <hr>
                  <h4>Archive des années académiques</h4>
                  <div class="widget_summary">
                    <div class="w_left w_25">
                      <span>2023/2024</span>
                    </div>
                    <div class="w_center w_55">
                      <div class="progress">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 66%;">
                          <span class="sr-only">100%</span>
                        </div>
                      </div>
                    </div>
                    <div class="w_right w_20">
                      <span>523</span>
                    </div>
                    <div class="clearfix"></div>
                  </div>

                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-4 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2> Licences activées <small>En cour</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <div id="reportrange" class="pull-center col-12" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                      <select class="form-control" name="ecole_licence" id="ecole_licence" style="width: 100%" onchange="getEcoleLicence()">
                          
                      </select>
                    </div>
                    <hr>
                    <ul class="list-unstyled timeline widget">

                      <li>
                        <div class="block">
                          <div class="block_content">
                            <h2 class="title">
                                              <a>Licence 001 année 2023 / 2024</a>
                                          </h2>
                            <div class="byline">
                              <span>08-11-2023</span> by <a>Takam ange</a>
                            </div>
                            <p class="excerpt">Cette licence a une durée de 12 mois à compter du 08-11-2023.</a>
                            </p>
                          </div>
                        </div>
                      </li>
                      
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- Start to do list -->
            <div class="col-md-4 col-sm-4 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Historique de session <small>active</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="">
                    <ul class="to_do" id="historiques_ativity">
                      <!-- li content -->
                    </ul>
                  </div>
                  <hr>
                  <div id="see_all_history">
                    
                  </div>
                </div>
              </div>
            </div>
                <!-- End to do list -->
    
          </div>

        </div>
        <!-- /page content -->

        <!-- footer locate to componenents -->
        <?= $this->include('components/footer.php') ?>

      </div>
    </div>

    <!-- fonction  -->
    <script src="<?= base_url()?>/function/constant.js"></script>
    <script src="<?= base_url()?>/function/acceuil.js"></script>

    <!-- script locate to componenents -->
    <?= $this->include('components/js.php') ?>
    <!-- chart components -->
    <script src="<?= base_url()?>/function/diagrammeAcceuil.js"></script>
  
  </body>
</html>
