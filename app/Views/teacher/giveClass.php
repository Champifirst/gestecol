<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | ENSEIGNANT </title>
    
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
                            <h3>Attribuer les salles de classes</h3>
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
                                    <form method="POST" id="from_attribution_class">
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
                                                    <select class="form-control" name="name_cycle" id="name_cycle" required="required">
                                                        <option value="0">Selectionner un cycle</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <label for="session">.</label>
                                                <div>
                                                    <button type='button' class="btn btn-secondary col-12" onclick="charger_fiche()">Charger la fiche</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-5">
                                            <table class="table table-bordered">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th scope="col" width="5%">#</th>
                                                        <th scope="col" class="text-center" width="20%">CODE</th>
                                                        <th scope="col" class="text-center" width="20%">CLASSE</th>
                                                        <th scope="col" class="text-center" width="45%">ENSEIGNANT</th>
                                                        <th scope="col" class="text-center" width="15%">#</th>
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
                                    </form>
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
        $("#user_id").val(localStorage.getItem('id_user'));
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
    </script>
    <script src="<?= base_url()?>/function/teacher/giveClass.js"></script>
    <?= $this->include('components/js.php') ?>
    
</body>

</html>
