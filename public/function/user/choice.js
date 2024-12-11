/*************  LISTE ECOLE ***********/
liste_ecole();

function liste_ecole() {
    let url = $('meta[name=app-url]').attr("content") + "/school/AllSchool/0";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            
            let bloc ='';
            let count_invalide = 0;

            for (var i = 0; i < json.length; i++) {
                if (json[i].motif == 'error') {
                  count_invalide++;
                  message = 'La licence de l\'établissement '+json[i].name.toUpperCase()+'  est expirée.';
                  toastr["error"](message, "Erreur");
                }

                bloc += '<div class="col-md-55">'+
                '<div class="thumbnail" style="border-radius: 30px; border: 2px solid #139300;">'+
                  '<div class="image view view-first">'+
                    '<img style="width: 100%; display: block;" src="'+BASE_URL+'/logoSchool/' + json[i].logo + '" alt="logo" />'+
                    '<div class="mask no-caption">'+
                      '<div class="tools tools-bottom">'+
                        '<a href="#" onclick="getSchool(' + json[i].school_id + ',\''+ json[i].motif +'\')">Connexion <i class="fa fa-sign-out"></i></a>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                  '<div class="caption mb-2">'+
                    '<p class="text-center"><strong>'+json[i].code.toUpperCase()+': '+json[i].name.toUpperCase()+'</strong>'+
                    '</p>'+
                    '<p class="text-center"><a href="#" onclick="getSchool(' + json[i].school_id + ',\''+ json[i].motif +'\')" class="btn">Connexion <i class="fa fa-sign-out"></i></a></p>'+
                  '</div>'+
                '</div>'+
              '</div>';
            }

            let button_continous = '';
            if (count_invalide == 0) {
              button_continous = '<a href="Home"><button type="submit" class="btn btn-secondary" id="btn-log">Continuer la Connexion</button>';
            }

            let contain = '<div class="container body">'+
            '<div class="main_container p-5">'+
      
              '<!-- page content -->'+
              '<div class="right_col" role="main" style ="background-color: transparent; border:none">'+
                '<div class="" style ="background-color: transparent; border: none;">'+
                  '<div class="clearfix"></div>'+
      
                  '<div class="row" style ="background-color: transparent; border: none;">'+
                    '<div class="col-md-12" style ="background-color: transparent; border: none;">'+
                      '<div class="x_panel" style ="background-color: transparent; border: none;">'+
                        '<div class="text-center">'+
                            '<h1><b style="text-transform: uppercase; color: white">Choisir un établissement pour continuer la connexion</b></h1>'+
                          '<div class="clearfix"></div>'+
                        '</div>'+
                        '<div class="x_content mt-4">'+
      
                          '<div class="row" style="justify-content: center; flex: auto;" id="contain-bloc">'+
      
                            '<!-- content -->'+
      
                          '</div>'+
      
                          '<div class="ln_solid">'+
                                '<div class="form-group text-center">'+
                                    '<div class="col-md-6 offset-md-3 mt-4">'+
                                        '<a href="/"><button type="submit" class="btn btn-danger" id="btn-log">Retouner à la page de connexion </button></a>'+
                                        // button_continous+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
      
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</div>'+
      
                '<div>'+
                  '<div class="pull-center text-white" style="text-align: center; font-size: 1.2em; font-weight: bold;">'+
                  'DevCode - Ecole intelligente. Contactez-Nous: +237 659 373 726 / +237 656 141 969 Power By <a href="#">devCode</a>'+
                  '</div>'+
                  '<div class="clearfix"></div>'+
                '</div>'+
            '</div>'+
          '</div>';

            $("#loading").hide();
            $("#contenue").append(contain);
            $("#contain-bloc").append(bloc);
            
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            $("#loading").hide();
        }
    });
}

function getSchool(id_school, motif){
  if (motif == 'error') {
    toastr["error"]("La licence de cet établissement est expirée.", "Erreur");
    window.location.href = "Licence";
  }else{
    localStorage.setItem('id_school', id_school);
    window.location.href = "Home";
  }
  
}