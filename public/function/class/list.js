$("#school").select2();
$("#count_student").select2();
$("#count_enseignant").select2();


/*************  LISTE ECOLE ***********/
$(document).ready(function () {
    let id_school = 0;
    if (localStorage.getItem('id_school') != null) {
        id_school = localStorage.getItem('id_school');
    }
    listeSchool(id_school);
});

// liste ecole
function listeSchool(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/school/AllSchool/"+id_school+"";
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            if (json !== undefined) {
                let option = '<option value="0">Selectionner une école</option>';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].school_id+'">'+json[i].name+' | code '+json[i].code+'</option>';
                }
                $("#school").append(option);
            }else{
                toastr["warning"]("Auccune école enregistrées", "Alerte");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

// nombre d'eleves
function listeNombreEleve(){
    let url = $('meta[name=app-url]').attr("content") + "/student/ListeNombreEleve";
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            if (json !== undefined) {
                let option = '<option value="0">Nom défini</option>';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].count+'">'+json[i].count+'</option>';
                }
                $("#count_student").append(option);
            }else{
                toastr["warning"]("Accun élèves enregistrés", "Alerte");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

// nombre d'enseignants
function listeNombreEnseignant(){
    let url = $('meta[name=app-url]').attr("content") + "/student/listeNombreEnseignant";
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            if (json !== undefined) {
                let option = '<option value="0">Nom défini</option>';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].count+'">'+json[i].count+'</option>';
                }
                $("#count_student").append(option);
            }else{
                toastr["warning"]("Accun enseignant enregistrés", "Alerte");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}


// liste des salles de classe
function ListeSalleClass(id_school, count_eleve, count_enseignant){

}



