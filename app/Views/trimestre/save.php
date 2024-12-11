<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | TRIMESTRE </title>
    
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
                            <h3>Enregistrer les trimestres</h3>
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
                                    <form class="" id="from_trimestre" method="post" enctype="multipart/form-data" novalidate>
                                        
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Code de trimestre <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control text-uppercase" data-validate-length-range="3" data-validate-words="1" name="number_trimestre" id="number_trimestre" class='form-control' required="required" type="text" />
                                                <span class="fa fa-key form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Nom du trimestre<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control text-uppercase" name="name_trimestre" data-validate-length-range="3" data-validate-words="1" id="name_trimestre" class='form-control' required="required" type="text" />
                                                <span class="fa fa-list form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Etablissement<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_school" id="name_school" onchange="getSchool()">
                                                    <option value="0">Selectionner une école</option>
                                                </select>
                                                <span class="fa fa-bank form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Session<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_session" id="name_session" onchange="getSession()">
                                                    <option value="0">Selectionner une session</option>
                                                </select>
                                                <span class="fa fa-bank form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Cycle<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_cycle" id="name_cycle" onchange="getCycle()">
                                                    <option value="0">Selectionner une cycle</option>
                                                </select>
                                                <span class="fa fa-bank form-control-feedback right" aria-hidden="true"></span>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Classe<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="name_classe" id="name_classe">
                                                    <option value="0">Selectionner une classe</option>
                                                </select>
                                                <span class="fa fa-bank form-control-feedback right" aria-hidden="true"></span>
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
    <script> $("#name_school").select2()</script>
    <script> $("#name_cycle").select2()</script>
    <script> $("#name_classe").select2()</script>
    <script> $("#name_session").select2()</script>
    <script src="<?= base_url()?>/function/trimestre/save.js"></script>
    <?= $this->include('components/js.php') ?>
    <script>
        // initialize a validator instance from the "FormValidator" constructor.
        // A "<form>" element is optionally passed as an argument, but is not a must
        var validator = new FormValidator({
            "events": ['blur', 'input', 'change']
        }, document.forms[0]);
        // on form "submit" event
        document.forms[0].onsubmit = function(e) {
            var submit = true,
                validatorResult = validator.checkAll(this);
            console.log(validatorResult);
            return !!validatorResult.valid;
        };
        // on form "reset" event
        document.forms[0].onreset = function(e) {
            validator.reset();
        };
        // stuff related ONLY for this demo page:
        $('.toggleValidationTooltips').change(function() {
            validator.settings.alerts = !this.checked;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);

    </script>
</body>

</html>
