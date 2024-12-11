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
                            <h3>Attribuer les bourses</h3>
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
                                    <form class="" id="from_attribuer_bourse" method="post" novalidate>
                                        
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etablissement<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_school" id="name_school" onchange="getSchool()"> 
                                                    <!-- content school -->
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Section<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_session" id="name_session" onchange="getSession()">
                                                    <option value="0">Selectionner une section</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Cycle<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_cycle" id="name_cycle" onchange="getCycle()">
                                                    <option value="0">Selectionner un cycle</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Classe<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_classe" id="name_classe" onchange="getClass()">
                                                    <option value="0">Selectionner une classe</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Élèves<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="student" id="student">
                                                    <!-- content personnel -->
                                                    <option value="0">Choisir un élève</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Bourse<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_bourse" id="name_bourse"> 
                                                    <!-- content bourse -->
                                                </select>
                                            </div>
                                        </div>  
                                       
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
        $("#name_school").select2();
        $("#student").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
        $("#name_bourse").select2();
    </script>
    <?= $this->include('components/js.php') ?>
    <script src="<?= base_url()?>/function/student/attribut_bourse.js"></script>

</body>

</html>
