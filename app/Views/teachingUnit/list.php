<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SM@RTSCHOOL | MATIERES </title>
    
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
    
    <script>
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
    </script>
    <!-- For Invoice  -->
    <script src="<?= base_url()?>/function/teaching_unit/list.js"></script>
    <?= $this->include('components/js.php') ?>
    
</body>

</html>