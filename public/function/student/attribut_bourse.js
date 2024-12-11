/*************  LISTE ECOLE ***********/
let id_school = 0;
if (localStorage.getItem('id_school') != null) {
    id_school = localStorage.getItem('id_school');
}
listeSchool(id_school);
listeBourse();

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
                $("#name_school").append(option);
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
    $('#name_session').html("");
    $('#name_session').append('<option value="0">Selectionner une section</option>');
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');
    
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
    $('#name_session').append('<option value="0">Selectionner une section</option>');
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');

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
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');

    var name_session = $('#name_session').val();
    var name_school = $('#name_school').val();
    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null ) {
        ShowCycle(name_school, name_session);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la section ", "Attention");
        }
    }
}

function ShowCycle(id_school, name_session){
    let url = $('meta[name=app-url]').attr("content") + "/cycle/all/"+id_school+"/"+name_session;
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');

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
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');

    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null ) {
        ShowClass(name_school, name_cycle, name_session);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la section ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }
    }
}

function ShowClass(name_school, name_cycle, name_session){
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');
    
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

function getClass(){
    $('#btn-log').prop('disabled', false);
    $('#student').html("");
    $('#student').append('<option value="0">Selectionner un élève </option>');

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/student/AllStudent/"+name_classe+"";
    $("#printf").html("");

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || user_id == "") {
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                console.log(json);
                let option = '';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].student_id+'">'+json[i].matricule.toUpperCase()+' : '+json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase()+'</option>';
                }
                $("#student").append(option);
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
}

// liste bourses
function listeBourse(){
    let url = $('meta[name=app-url]').attr("content") + "/scolarite/List_bourse";
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            if (json !== undefined) {
                let option = '<option value="0">Selectionner une bourse</option>';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].bourse_id+'">'+json[i].name.toUpperCase()+' | MT: '+json[i].amount+' FCFA</option>';
                }
                $("#name_bourse").append(option);
            }else{
                toastr["warning"]("Auccunes bourses enregistrées", "Alerte");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*****  SAVE INSCRPTION *****/
$('#from_attribuer_bourse').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/GiveBourse";

    $('#btn-log').prop('disabled', true);

    var name_school = $("#name_school").val();
    var name_session = $("#name_session").val();
    var name_cycle = $("#name_cycle").val();
    var name_classe = $("#name_classe").val();
    var student = $("#student").val();
    var name_bourse = $("#name_bourse").val();
    var user_id = localStorage.getItem('id_user');

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || student == "0" || name_bourse == "0" || user_id === undefined) {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Veuillez renseigner toutes les informations obligatoires ", "Attention");
    } else {
        // add data form
        var data_form = $('#from_attribuer_bourse')[0];
        const formData = new FormData(data_form);
        formData.append("id_user", user_id);

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

