<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | LISTE DES NOTES </title>
    
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
                            <h3>Lister les notes</h3>
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
                                    <form method="POST" id="from_note">
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
                                        <div class="row mt-4">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div>
                                                    <label for="trimestre">Trimestre</label>
                                                    <select class="form-control" name="name_trimestre" id="name_trimestre" required="required" onchange="getTrimestre()">
                                                        <option value="0">Selectionner un trimestre</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <label for="sequence">Séquence</label>
                                                <div>
                                                    <select class="form-control" name="name_sequence" id="name_sequence" required="required" onchange="getSequence()">
                                                        <option value="0">Selectionner une séquence</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <label for="matiere">Matière</label>
                                                <div>
                                                    <select class="form-control" name="name_matiere" id="name_matiere" required="required">
                                                        <option value="0">Selectionner une matière</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <label for="matiere">.</label>
                                                <div>
                                                    <button type='button' class="btn btn-secondary col-12" onclick="charger_fiche_note()">Charger la fiche</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="mt-5">
                                            <table class="table table-bordered">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th scope="col" width="5%">#</th>
                                                        <th scope="col" class="text-center" width="20%">MATRICULE</th>
                                                        <th scope="col" class="text-center" width="30%">NOM & PRENOM</th>
                                                        <th scope="col" class="text-center" width="20%">NOTE</th>
                                                        <th scope="col" class="text-center" width="10%">COFF</th>
                                                        <th scope="col" class="text-center" width="20%">NOTE COFF</th>
                                                        <th scope="col" class="text-center" width="5%">STATUS</th>
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
        $("#name_trimestre").select2();
        $("#name_sequence").select2();
        $("#name_matiere").select2();
    </script>
    <script src="<?= base_url()?>/function/note/save.js"></script>
    <?= $this->include('components/js.php') ?>
    
</body>

</html>
