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

function getSchool(){
    $('#count_student').html("");
    $('#count_student').append('<option value="0">Selectionner une session</option>');
    $('#count_enseignant').html("");
    $('#count_enseignant').append('<option value="0">Selectionner un cycle</option>');

    var name_school = $('#school').val();
    if (name_school != "0") {
        ShowSession(name_school);
    }else{
        toastr["error"]("Selectionnez l'établissement ", "Attention");
    }
}


function ShowSession(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#count_student').html("");
    $('#count_student').append('<option value="0">Selectionner une session</option>');
    $('#count_enseignant').html("");
    $('#count_enseignant').append('<option value="0">Selectionner un cycle</option>');

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucune session trouvée.", "Alerte");
                }else{
                   let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].session_id+'">'+data[i].code_session.toUpperCase()+': '+data[i].name_session.toUpperCase()+'</option>'
                    }
                    $('#count_student').append(option); 
                }
                
            }else{
                toastr["warning"]("Aucune session trouvée.", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}


function getSession(){
    $('#count_enseignant').html("");
    $('#count_enseignant').append('<option value="0">Selectionner un cycle</option>');

    var name_session = $('#count_student').val();
    var name_school = $('#school').val();
    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null ) {
        ShowCycle(name_school, name_session);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }
    }
}

function ShowCycle(id_school, name_session){
    let url = $('meta[name=app-url]').attr("content") + "/cycle/all/"+id_school+"/"+name_session;
    $('#count_enseignant').html("");
    $('#count_enseignant').append('<option value="0">Selectionner un cycle</option>');

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucun cycle trouvé", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].cycle_id+'">'+data[i].name_cycle.toUpperCase()+'</option>'
                    }
                    $('#count_enseignant').append(option);
                }
            }else{
                toastr["warning"]("Aucun cycle trouvé", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}


function charger_fiche(){
    var name_school = $('#school').val();
    var name_cycle = $('#count_enseignant').val();
    var name_session = $('#count_student').val();

    $('#contain_body').html("");
    $("#bloc_btn").html("");

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null) {
        // affichage de la fiche des classes
        let url = $('meta[name=app-url]').attr("content") + "class/getClassSchoolYear/"+name_school+"/"+name_session+"/"+name_cycle+"";
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                
                if (json !== undefined) {
                    let data = json.data;
                    
                    let tr = '';
                    if (data.length != 0) {
                        for (let i = 0; i < data.length; i++) {
                            //-- listing enseignant
                            let option = '<option value="0">Selectionner un enseignant</option>';
                            let enseignants = json.enseginants;
                            let name_enseignant = data[i].name_enseignant.toUpperCase();
                            let id_enseignant = data[i].id_enseignant;
                            let selected = '';
                            for (let e = 0; e < enseignants.length; e++) {
                                enseignant = enseignants[e];
                                if (enseignant.teacher_id == id_enseignant) {
                                    selected = 'selected';
                                }else{
                                    selected = '';
                                }
                                option += '<option value="'+enseignant.teacher_id+'" '+selected+'>'+enseignant.name+' '+enseignant.surname+'</option>';
                            }

                            let setlect_teacher = '<select disabled class="form-control select_teacher" name="name_teacher[]" required="required">'+
                            option+
                            '</select>';

                           // boucler les lignes du tableau

                           let supprimer = '';
                           let modifier = '';
                            if (type_user == "admin") {
                                supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + data[i].class_id + ','+(i)+');"><span class="fa fa-times"></span></button>';
                                modifier = '<button class="btn btn-success btn-rounded btn-sm btn-select" onClick="handleSelect(' + data[i].class_id + ', ' + data[i].id_enseignant + ', ' + name_school + ');"><span class="fa fa-pencil"> Modifier</span></button>';
                            }
                           tr += '<tr>'+
                           '<td>'+(i+1)+'<input type="number" name="id_class[]" value="'+data[i].class_id+'" hidden></td>'+
                           '<td>'+data[i].code_class.toUpperCase()+'</td>'+
                           '<td>'+data[i].name_class.toUpperCase()+'</td>'+
                           '<td>'+setlect_teacher+'</td>'+
                           '<td>'+data[i].nombre_eleve+'</td>'+
                           '<td>'+supprimer+' '+modifier+'</td>'
                           '</tr>';
                        }
                        $('#contain_body').append(tr);
                        // btn submit
                        let btn_submit = '<div class="form-group text-center">'+
                        '<div class="col-md-6 offset-md-3 mt-4">'+
                            '<button type="reset" class="btn btn-danger">Annuler</button>'+
                            '<button type="submit" class="btn btn-success" id="btn-log">Enregistrer</button>'+
                        '</div>'+
                        '</div>';
                        $("#bloc_btn").append(btn_submit);

                    }else{
                        toastr["warning"]("Aucune classe trouvée.", "Alerte");
                    }
                }else{
                    toastr["warning"]("Aucune classe trouvée.", "Alerte");
                }
            },
            error: function (data) {
                console.log(data.responseJSON);
                $('#btn-log').prop('disabled', false);
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });

    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }
    }
    
}

function handleSelect(class_id, id_enseignant, name_school) {
    if (id_enseignant == 0) {
        toastr["error"]("Oups, veuillez compléter la classe avant", "Erreur");
    } else {
        select_data(class_id, id_enseignant, name_school);
    }
}


/***** DELETE CLASSE *******/
function delete_row(id, ligne){
    let url = $('meta[name=app-url]').attr("content") + "/class/delete/"+id+"";
    var table = $('#datatable-buttons').DataTable();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    
    swalWithBootstrapButtons.fire({
        title: 'Attention !!!',
        text: "Êtes-vous sûr de vouloir supprimer cette classe ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // suppression
            $.ajax({
                type: "GET",
                url: url,
                cache: false,
                headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                success: function (data) {
                    if (data.success == true) {
                        // suppression de la ligne
                        table.row(ligne).remove();
                        // reorganise
                        let nbre_ligne = parseInt($('#datatable-buttons tr').length) - 1;
                    
                        for (var i = 0; i <= (nbre_ligne - 1); i++) {
                            var cellule = table.cell(i, 0);
                            if (cellule && typeof cellule.data === 'function') {
                                cellule.data(String(i + 1)).draw();
                            } else {
                                console.error("Cellule invalide à l'index", i);
                            }
                        }
                    
                        swalWithBootstrapButtons.fire(
                            'Suppression réussie',
                            'La classe a été supprimée',
                            'success'
                        )
                    }
                    else if((data.success == false)){
                    swalWithBootstrapButtons.fire(
                        'Erreur',
                            data.msg,
                            'error'
                        )
                    }

                },
                error: function (data) {
                    swalWithBootstrapButtons.fire(
                        'Erreur',
                        'L\'opération a échouer.',
                        'error'
                    )
                }
            });
            
        } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Annuler',
                'L\'opération de suppression a été annulée.',
                'error'
            )
        }
    })
}


function select_data(class_id,teacher_id,id_school){
    let url = $('meta[name=app-url]').attr("content") +"/class/getOnclasse/"+class_id+"/"+teacher_id+"/"+id_school+"";
    let timerInterval;
    const swalloptions = {
        title: 'Veuillez patienter !',
        html: `Terminer dans quelques instant.`,
        timerProgressBar: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }
    const swalPromise = Swal.fire(swalloptions);
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function (json) {
            if (json.success == true) {
                let data = json.data;
               
            }else if((json.success == false)){
                toastr["error"](json.msg, "Erreur");
            }
            Swal.close();
        },
        error: function (data) {
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            Swal.close();
        }
    });
    swalPromise.then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer');
        }
    });
}  

// nombre d'eleves
// function listeNombreEleve(){
//     let url = $('meta[name=app-url]').attr("content") + "/student/ListeNombreEleve";
//     $.ajax({
//         url: url,
//         method: "GET",
//         dataType: 'json',
//         headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
//         success: function(json){
//             if (json !== undefined) {
//                 let option = '<option value="0">Nom défini</option>';
//                 for (let i = 0; i < json.length; i++) {
//                     option += '<option value="'+json[i].count+'">'+json[i].count+'</option>';
//                 }
//                 $("#count_student").append(option);
//             }else{
//                 toastr["warning"]("Accun élèves enregistrés", "Alerte");
//             }
//         },
//         error: function(response){
//             toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
//         }
//     });
// }

// // nombre d'enseignants
// function listeNombreEnseignant(){
//     let url = $('meta[name=app-url]').attr("content") + "/student/listeNombreEnseignant";
//     $.ajax({
//         url: url,
//         method: "GET",
//         dataType: 'json',
//         headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
//         success: function(json){
//             if (json !== undefined) {
//                 let option = '<option value="0">Nom défini</option>';
//                 for (let i = 0; i < json.length; i++) {
//                     option += '<option value="'+json[i].count+'">'+json[i].count+'</option>';
//                 }
//                 $("#count_student").append(option);
//             }else{
//                 toastr["warning"]("Accun enseignant enregistrés", "Alerte");
//             }
//         },
//         error: function(response){
//             toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
//         }
//     });
// }


// // liste des salles de classe
// function ListeSalleClass(id_school, count_eleve, count_enseignant){

// }



