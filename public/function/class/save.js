/*================================ LISTE ETABLISSEMENT ========================================*/
$("#id_cycle").select2();
$("#name_session").select2();
$("#name_serie").select2();
$("#name_school").select2();

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
    $('#id_cycle').html("");
    $('#id_cycle').append('<option value="0">Selectionner un cycle</option>');
    var name_school = $('#name_school').val();
    if (name_school != "0") {
        ShowSession(name_school);
        getShowSerie(name_school);
    }else{
        toastr["error"]("Selectionnez l'établissement ", "Attention");
    }
}

function ShowSession(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#name_session').html("");
    $('#name_session').append('<option value="0">Selectionner une session</option>');
    $('#id_cycle').html("");
    $('#id_cycle').append('<option value="0">Selectionner un cycle</option>');

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
    $('#id_cycle').html("");
    $('#id_cycle').append('<option value="0">Selectionner un cycle</option>');

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
                $('#id_cycle').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function getShowSerie(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/serie/all/"+id_school+"";
    $('#name_serie').html("");
    $('#name_serie').append('<option value="0">Selectionner une serie</option>');

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                let option ='';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].code_serie+'">'+data[i].code_serie.toUpperCase()+': '+data[i].name_serie.toUpperCase()+'</option>'
                }
                $('#name_serie').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*****  RESET FORM *****/
function reset_form(){
    $('#name_class').val("");
    $('#number_class').val("");
}

/*****  CLASSE *****/
$('#from_class').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/class/insert";

    $('#btn-log').prop('disabled', true);

    const formData = new FormData();

    var name_class = $('#name_class').val();
    var number_class = $('#number_class').val();
    var name_school = $('#name_school').val();
    var id_cycle = $('#id_cycle').val();

    var name_serie = $('#name_serie').val();
    var name_session = $('#name_session').val();
    var numero_class = $('#num_class').val();
    
    var user_id = localStorage.getItem('id_user');

    if (name_class == "" || number_class == "" || name_school == "0" || user_id == "" || id_cycle == "0" || name_serie == "0" || name_session == "0") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("name_class", name_class);
        formData.append("number_class", number_class);
        formData.append("name_school", name_school);
        formData.append("user_id", user_id);
        formData.append("id_cycle", id_cycle);

        formData.append("name_serie", name_serie);
        formData.append("name_session", name_session);
        formData.append("numero_class", numero_class);

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
                    reset_form();
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