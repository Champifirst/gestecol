// select 2
$("#cycle_diagramme").select2();
$("#ecole_diagramme").select2();
$("#ecole_achive").select2();
$("#ecole_licence").select2();
$("#ecole_diagrammeEffectif").select2();


$(document).ready(function () {
    liste_history();
});

/*************  LISTE HISTORY ***********/
function liste_history() {
    let url = $('meta[name=app-url]').attr("content") + "/history/list";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            if (json !== undefined) {
                for (var i = 0; i < json.length; i++) {
                    if (json.length < 20) {
                        let ligne = '<li>'+
                        '<p>'+
                        '<input type="checkbox" class="flat"> '+json[i].action+' ('+json[i].entiter+')<br> status: '+json[i].status+', heure: '+json[i].date_heure+'</p>'+
                    '</li>';
                        $("#historiques_ativity").append(ligne);
                    }
                }

                if (json.length > 20) {
                    let bloc = '<a href="'+BASE_URL+'/history/listView">'+
                    '<button type="button" class="btn btn-info btn-sm col-12">Afficher toute l\'historique</button>'+
                   '</a>';
                    $("#see_all_history").append(bloc);
                }

            }else{
                toastr["error"]("Désoler nous n'avons pas pu trouver vos historique", "Erreur");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*************  LISTE ECOLE ***********/
$(document).ready(function () {
    let id_school = 0;
    if (localStorage.getItem('id_school') != null) {
        id_school = localStorage.getItem('id_school');
    }
    liste_ecole(id_school);
    statistique(id_school);
});


function liste_ecole(id_school) {
    let url = $('meta[name=app-url]').attr("content") + "/school/AllSchool/"+id_school+"";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            
            let option = '';

            for (var i = 0; i < json.length; i++) {
                option += '<option value="' + json[i].school_id + '">Ecole : '+json[i].name.toUpperCase()+'</option>';
            }
            ShowSession( json[0].school_id);
            $("#ecole_diagramme").append(option);
            $("#ecole_licence").append(option);
            $("#ecole_achive").append(option);
            $("#ecole_diagrammeEffectif").append(option);
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

// getSchoolDiagramme
function getSchoolDiagramme(id_school){
    if (id_school == 0) {
        let identifiant = $("#ecole_diagramme").val();
        ShowSession(identifiant);
    }else{
        ShowSession(id_school);
    }
}

// getCycleDiagramme
function getCycleDiagramme(){

}



// liste session
function ShowSession(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#cycle_diagramme').html("");

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                let option ='';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].session_id+'">Cycle : '+data[i].name_session.toUpperCase()+'</option>'
                }
                $('#cycle_diagramme').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

// statistique de la page
function statistique(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/statistique/acceuil1/"+id_school+"";
    
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                console.log(data);
                $("#all_personnel").html(data.personnel);
                $("#all_student").html(data.student);
                $("#all_school").html(data.school);
                $("#all_scolarite").html(data.scolarite);
                $("#all_salaire").html(data.salaire);
                $("#all_connexion").html(data.connected);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}
