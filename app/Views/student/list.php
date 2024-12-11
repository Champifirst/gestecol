<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | STUDENT </title>

    <!-- CSS locate component -->
    <?= $this->include('components/css.php') ?>
    <style>
        .file-upload {
            background-color: #ffffff;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .file-upload-btn {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-upload-btn:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-upload-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-upload-content {
            display: none;
            text-align: center;
        }

        .file-upload-input {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-wrap {
            margin-top: 20px;
            border: 4px dashed #1FB264;
            position: relative;
        }

        .image-dropping,
        .image-upload-wrap:hover {
            background-color: #1FB264;
            border: 4px dashed #ffffff;
        }

        .image-title-wrap {
            padding: 0 15px 15px 15px;
            color: #222;
        }

        .drag-text {
            text-align: center;
        }

        .drag-text h3 {
            font-weight: 100;
            text-transform: uppercase;
            color: #15824B;
            padding: 60px 0;
        }

        .file-upload-image {
            max-height: 200px;
            max-width: 200px;
            margin: auto;
            padding: 20px;
        }

        .remove-image {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image:active {
            border: 0;
            transition: all .2s ease;
        }
    </style>
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
                <h4>Liste des élèves</h4>
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
                        <!-- filtre -->
                        <div class="col-sm-12 row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div>
                                    <label for="">Etablissement scolaire</label>
                                    <select class="form-control" name="name_school" id="name_school" onchange="getSchool()">
                                        <option value="0">Selectionner une école</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">Session</label>
                                <div>
                                    <select class="form-control" name="name_session" id="name_session" onchange="getSession()">
                                        <option value="0">Selectionner une session</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">Cycle</label>
                                <div>
                                    <select class="form-control" name="name_cycle" id="name_cycle" onchange="getCycle()">
                                    <option value="0">Selectionner un cycle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <label for="">Classe</label>
                                <div>
                                    <select class="form-control" name="name_classe" id="name_classe" onchange="getClass()">
                                    <option value="0">Selectionner une classe</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div id="printf" style="width: 100%">

                        </div>

                        <div class="col-sm-12 mt-3">
                          <div class="card-box table-responsive">
                          <table id="datatable-buttons" class="table table-bordered mt-5" style="width:100%">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Matricule</th>
                                <th>Noms & Prénoms</th>
                                <th>Sexe</th>
                                <th>Date naissance</th>
                                <th>Lieu Naissance</th>
                                <th>Parents</th>
                                <th>Contacts</th>
                                <th>Redouble</th>
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

        <!-- modal -->
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button> -->

        <div class="modal fade bs-example-modal-lg" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Modifier un élève</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="" id="from_student_update" method="post" novalidate>
                  <h5><span class="step_no p-3">1</span> Informations personnelles</h5>
                  <hr>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Nom<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <input class="form-control" data-validate-length-range="3" data-validate-words="1" name="name" id="name" placeholder="TANKOU" required="required" />
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Prénom <span class="required"></span></label>
                      <div class="col-md-9 col-sm-9">
                          <input class="form-control" name="surName" id="surName" placeholder="Rospain" />
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Date de naissance<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <input class="form-control" class='date' type="date" name="date" id="date" required='required'>
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Lieu de naissance <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <input class="form-control" data-validate-length-range="3" data-validate-words="1" name="placeBirth" id="placeBirth" placeholder="Bafoussam" required="required" />
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Sexe<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <select class="form-control" name="sexe" id="sexe" required='required' style="width: 100%;">
                              <!-- content -->
                          </select>
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align">Photo<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9">
                          <!-- photo -->
                            <div id="image-profil" class="text-center">

                            </div>
                          <!-- photo -->
                      </div>
                  </div>
                  <div class="field item form-group">
                      <label class="col-form-label col-md-3 col-sm-3  label-align"></label>
                      <div class="col-md-9 col-sm-9">
                          <!-- dropZone -->
                          <div class="file-upload">
                              <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Changer la photo</button>

                              <div class="image-upload-wrap">
                                  <input class="file-upload-input" type='file' name="logo" id="logo" onchange="readURL(this);" accept="image/*" />
                                  <div class="drag-text">
                                  <h3>Faites glisser et déposez une photo</h3>
                                  </div>
                              </div>
                              <div class="file-upload-content">
                                  <img class="file-upload-image" src="#" alt="your image" />
                                  <div class="image-title-wrap">
                                  <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                                  </div>
                              </div>
                          </div>
                          <!-- dropZone -->
                      </div>
                  </div>

                    <hr>
                    <h4><span class="step_no p-3">2</span> Informations sur les parents et tuteurs</h4>
                    <hr>

                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align"> Nom du parent<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <input class="form-control" data-validate-length-range="3" data-validate-words="1" name="nameParent" id="nameParent" placeholder="TANKOU" required="required" /> 
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align"> Prénom du parent</label>
                        <div class="col-md-9 col-sm-9">
                            <input class="form-control" name="surnameParent" id="surnameParent" placeholder="ANGEL" /> 
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Email du parent<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <input class="form-control" name="email_parent" id="email_parent" type="email" />
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align"> Profession du parent</label>
                        <div class="col-md-9 col-sm-9">
                            <input class="form-control" name="profession" id="profession" placeholder="Profession" /> 
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Contact du parent<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <input type="text" class="form-control" name="phone" id="phone" data-inputmask="'mask' : '***-***-***'">
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align"> Adresse du parent</label>
                        <div class="col-md-9 col-sm-9">
                            <input class="form-control" name="adresse_parent" id="adresse_parent" placeholder="Adresse" /> 
                        </div>
                    </div>
                    
                    <hr>
                    <h4><span class="step_no p-3">3</span> Informations suplémentaires</h4>
                    <hr>

                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Etablissment<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <select class="form-control" name="name_school_edit" id="name_school_edit" required='required' onchange="getSchoolUpdate()" style="width: 100%;">
                                
                            </select>
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Session<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <select class="form-control" name="name_session_edit" id="name_session_edit" required='required' onchange="getSessionUpdate()" style="width: 100%;">
                                
                            </select>
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Cycle<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <select class="form-control" name="cycle_edit" id="cycle_edit" required='required' onchange="getCycleUpdate()" style="width: 100%;">
                                
                            </select>
                        </div>
                    </div>
                    <div class="field item form-group">
                        <label class="col-form-label col-md-3 col-sm-3  label-align">Classe<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9">
                            <select class="form-control" name="classe_edit" id="classe_edit" required='required' style="width: 100%;">
                                
                            </select>
                        </div>
                    </div>

                    <input type="number" name="ligne_update" id="ligne_update" hidden>
                    <input type="number" name="idParent" id="idParent" hidden>
                    <input type="number" name="idStudent" id="idStudent" hidden>

                    <div class="ln_solid">
                        <div class="form-group text-center">
                            <div class="col-md-6 offset-md-3 mt-4">
                                <button type='reset' class="btn btn-danger">Annuler</button>
                                <button type='submit' class="btn btn-success" id="btn-log-update">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </form>
              </div>

            </div>
          </div>
        </div>
        <!-- footer locate to componenents -->
        <?= $this->include('components/footer.php') ?>

      </div>
    </div>
    <script>
        $("#name_school").select2();
        $("#name_session").select2();
        $("#name_cycle").select2();
        $("#name_classe").select2();
        // modal
        $("#sexe").select2();
        $("#name_school_edit").select2();
        $("#name_session_edit").select2();
        $("#classe_edit").select2();
        $("#cycle_edit").select2();
    </script>
    <!-- script locate to componenents -->
    <script src="<?= base_url()?>/function/constant.js"></script>
    <script src="<?= base_url()?>/function/student/list.js"></script>
    <?= $this->include('components/js.php') ?>
    <script>
        // add logo school
        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function(e) {
                $('.image-upload-wrap').hide();

                $('.file-upload-image').attr('src', e.target.result);
                $('.file-upload-content').show();

                $('.image-title').html(input.files[0].name);
                };

                reader.readAsDataURL(input.files[0]);

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function () {
                $('.image-upload-wrap').addClass('image-dropping');
            });
            $('.image-upload-wrap').bind('dragleave', function () {
                $('.image-upload-wrap').removeClass('image-dropping');
        });

    </script>
  </body>
</html>
