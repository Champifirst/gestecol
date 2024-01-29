/*================================ LISTE ETABLISSEMENT ========================================*/
let id_school = 0;
if (localStorage.getItem('id_school') != null) {
    id_school = localStorage.getItem('id_school');
}
ListeSchool(id_school); 


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
    var name_school = $('#name_school').val();
    $('#session').html("");
    $('#session').append('<option value="0">Selectionner une session</option>');
    $('#cycle').html("");
    $('#cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');
    if (name_school != "0") {
        ShowSession(name_school);
    }else{
        toastr["error"]("Selectionnez l'établissement ", "Attention");
    }
}

function ShowSession(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#session').html("");
    $('#session').append('<option value="0">Selectionner une session</option>');
    $('#cycle').html("");
    $('#cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');

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
                $('#session').append(option);
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
    $('#cycle').html("");
    $('#cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');
    var session = $('#session').val();
    var name_school = $('#name_school').val();
    if (name_school != "0" && session != "0" && session != null && name_school != null ) {
        ShowCycle(name_school, session);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (session == "0" || session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }
    }
}

function ShowCycle(id_school, name_session){
    let url = $('meta[name=app-url]').attr("content") + "/cycle/all/"+id_school+"/"+name_session;
    $('#cycle').html("");
    $('#cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');

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
                $('#cycle').append(option);
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
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');
    var session = $('#session').val();
    var cycle = $('#cycle').val();
    var name_school = $('#name_school').val();
    if (name_school != "0" && session != "0" && session != null && name_school != null && cycle != "0" && cycle != null ) {
        ShowClass(name_school, session, cycle);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (session == "0" || session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(cycle == "0" || cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }
    }
}

function ShowClass(id_school, name_session, cycle){
    let url = $('meta[name=app-url]').attr("content") + "/class/all/"+id_school+"/"+name_session+"/"+cycle;
    $('#classe').html("");
    $('#classe').append('<option value="0">Selectionner un cycle</option>');

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
                $('#classe').append(option);
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

function reset_form() {
    // logo
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}

/*****  RESET FORM *****/


/*****  ELEVES *****/
$('#from_import_student').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/importStudent";

    $('#btn-log').prop('disabled', true);
    $("#success").hide();
    $("#errors").hide();

    const photo = document.getElementById("file").files[0];
    const formData = new FormData();

    var name_school = $('#name_school').val();
    var session = $('#session').val();
    var cycle = $('#cycle').val();
    var classe = $('#classe').val();
    var inscription = $('#inscription').val();
    var file = $('#file').val();
    var user_id = localStorage.getItem('id_user');

    if (file == "" || name_school == "0" || session == "0" || cycle == "0" || classe == "0" ) {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("name_school", name_school);
        formData.append("session", session);
        formData.append("cycle", cycle);
        formData.append("classe", classe);
        formData.append("inscription", inscription);

        formData.append("file", photo);
        formData.append("user_id", user_id);

        $.ajax({
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            timeout: 600000,
            data: formData,
            enctype: 'multipart/form-data',
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