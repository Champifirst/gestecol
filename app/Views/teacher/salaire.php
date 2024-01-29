<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SM@RTSCHOOL | ENSEIGNANT </title>
    
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
                            <h3>Enregistrer les salaires</h3>
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
                                    <form class="" id="from_pay_salaire" method="post" novalidate>
                                        
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etablissement<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="school" id="school" onchange="charger_liste_teachear()"> 
                                                    <!-- content school -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Employés<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="personnel" id="personnel">
                                                    <!-- content personnel -->
                                                    <option value="0">Choisir un employé</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Montant en chiffre<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="number" min="0" class="form-control" name="salaire" id="salaire">
                                                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Montant en lettre<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control" name="montant_lettre" id="montant_lettre">
                                                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <input type="number" name="user_id" id="user_id" min="0" hidden>
                                        <div class="ln_solid">
                                            <div class="form-group text-center">
                                                <div class="col-md-6 offset-md-3 mt-4">
                                                    <button type='reset' class="btn btn-danger">Annuler</button>
                                                    <button type='submit' class="btn btn-success" id="btn-log">Enregistrer</button>
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
            <!-- /page content -->

           <!-- footer locate to componenents -->
            <?= $this->include('components/footer.php') ?>
        </div>
    </div>
    
    <!-- script locate to componenents -->
    <script>
        $("#user_id").val(localStorage.getItem('id_user'));
        $("#school").select2();
        $("#personnel").select2();
    </script>
    <?= $this->include('components/js.php') ?>
    <script src="<?= base_url()?>/function/teacher/salaire.js"></script>

</body>

</html>
