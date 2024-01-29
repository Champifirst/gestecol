/*================================ LISTE ETABLISSEMENT ========================================*/
$(document).ready(function () {
    let id_school = 0;
    if (localStorage.getItem('id_school') != null) {
        id_school = localStorage.getItem('id_school');
    }
    ListeSchool(id_school); 
});


function ListeSchool(id_school){
    let url = $('meta[name=app-url]').attr("content") + "school/SchoolFilter/"+id_school+"";
    $("#name_school").html("");
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            let option = '<option value="0">Selectionner une école</option>';
            for (let i = 0; i < data.length; i++) {
                option += '<option value="'+data[i].id+'">'+data[i].text+'</option>';
            }
            $("#name_school").append(option);
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function getSchool(){
    $('#name_session').html("");
    $('#name_session').append('<option value="0">Selectionner une session</option>');
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    var name_school = $('#name_school').val();
    if (name_school != "0") {
        ShowSession(name_school);
    }else{
        toastr["error"]("Selectionnez l'établissement ", "Attention");
    }
}


function ShowSession(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#name_session').html("");
    $('#name_session').append('<option value="0">Selectionner une session</option>');
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                let option ='';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].session_id+'">'+data[i].code_session.toUpperCase()+': '+data[i].name_session.toUpperCase()+'</option>'
                }
                $('#name_session').append(option);
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
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    var name_session = $('#name_session').val();
    var name_school = $('#name_school').val();
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
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                let option ='';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].cycle_id+'">'+data[i].name_cycle.toUpperCase()+'</option>'
                }
                $('#name_cycle').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}


function getCycle(){
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null ) {
        ShowClass(name_school, name_cycle, name_session);
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

function ShowClass(name_school, name_cycle, name_session){
    let url = $('meta[name=app-url]').attr("content") + "/class/all/"+name_school+"/"+name_session+"/"+name_cycle;

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            let data = json.data;
            if (data !== undefined) {
                let option ='';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].class_id+'">'+data[i].name.toUpperCase()+'</option>'
                }
                $('#name_classe').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*****  LIST STUDENT *****/
function getClass(){

    $('#btn-log').prop('disabled', true);

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/student/AllStudent/"+name_classe+"";
    $("#bloc_btn").html("");

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                console.log(json);
                var tableau = new Array(json.length);
                for (var i = 0; i < json.length; i++) {
                    let input_file = '<input type="file" name="photo_student[]" id="photo_student'+i+'">';
    
                    tableau[i] = new Array(8);
                    tableau[i][0] = (i + 1);
                    tableau[i][1] = ('<img src="../photoStudent/' + json[i].photo + '" width="100px" alt="">');
                    tableau[i][2] = (json[i].matricule.toUpperCase());
                    tableau[i][3] = (json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase());
                    tableau[i][4] = (json[i].sexe.toUpperCase());
                    tableau[i][5] = (json[i].date_of_birth);
                    tableau[i][6] = (json[i].birth_place+' <input type="number" name="student_id[]" value="'+json[i].student_id+'" hidden>');
                    tableau[i][7] = (input_file);
                }
    
                $('#datatable-buttons').DataTable().destroy();
    
                var handleDataTableButtons = function () {
                    if ($("#datatable-buttons").length) {
                        $("#datatable-buttons").DataTable({
                            dom: "Blfrtip",
                            buttons: [
                            ],
                            responsive: true,
                            aaData: tableau,
                            "scrollCollapse": true,
                            autoFill: true,
                            language: {
                                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
                            },
                        });
                    }
                };
        
                TableManageButtons = function () {
                    "use strict";
                    return {
                        init: function () {
                            handleDataTableButtons();
                        }
                    };
                }();
    
                TableManageButtons.init();

                if (json.length != 0) { 
                    // show print
                    // btn submit
                    let btn_submit = '<div class="form-group text-center">'+
                    '<div class="col-md-6 offset-md-3 mt-4">'+
                        '<button type="reset" class="btn btn-danger">Annuler</button>'+
                        '<button type="submit" class="btn btn-success" id="btn-log">Enregistrer</button>'+
                    '</div>'+
                    '</div>';
                    $("#bloc_btn").append(btn_submit);
                    toastr["success"]("Opération réussir", "Réussite");
                }
    
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
}


/*****  PHOTOS *****/
$('#from_photo').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/insertPhoto";

    $('#btn-log').prop('disabled', true);

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();

    if (name_school == "0" && name_session == "0" && name_session == null && name_school == null && name_cycle == "0" && name_cycle == null && name_classe == "0" && name_classe == null ) {
        $('#btn-log').prop('disabled', false);
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }
    } else {
        // add data form
        var data_form = $('#from_photo')[0];
        const formData = new FormData(data_form);

        $.ajax({
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            timeout: 600000,
            data: formData,
            cache: false,
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function (data) {
                if (data.success == true) {
                    toastr["success"](data.msg, "Réussite");
                    window.location.href="ChangePhoto";
                } else {
                    toastr["error"](data.msg, "Erreur");
                }
                $('#btn-log').prop('disabled', false);
            },
            error: function (data) {
                console.log(data.responseJSON);
                $('#btn-log').prop('disabled', false);
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
})  



