<script>
  if (localStorage.getItem('autorisation') != "true") {
      window.location.href="/";
  }
</script>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>DEVCODE | ACCEUIL</title>
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
                      <select class="form-control" name="ecole_diagramme" id="ecole_diagramme" style="width: 100%" onchange="getSchoolDiagramme(0)">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <select class="form-control" name="cycle_diagramme" id="cycle_diagramme" style="width: 100%" onchange="getCycleDiagramme()">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <button type="button" class="btn btn-dark col-12">IMPRIMER</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12" style="height: 500px;">
                  <div id="chartContainer3" class="demo-placeholder" style="height: 100%;">

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
                      <select class="form-control" name="ecole_diagramme" id="ecole_diagramme2" style="width: 100%" onchange="getSchoolDiagramme(0)">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <select class="form-control" name="cycle_diagramme" id="cycle_diagramme2" style="width: 100%" onchange="getCycleDiagramme()">
                          <!-- option -->
                      </select>
                    </div>
                    <div id="reportrange" class="pull-right col-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 50%">
                      <button type="button" class="btn btn-dark col-12">IMPRIMER</button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12" style="height: 500px;">
                  <div id="chartContainer4" class="demo-placeholder" style="height: 100%;">

                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <!-- second diagramme -->

          <br />

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
    <script src="<?= base_url()?>/function/diagrammeEtatFinancier.js"></script>
  
  </body>
</html>
