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

/*****  RESET FORM *****/
function reset_form(){
    $('#number_trimestre').val("");
    $('#name_trimestre').val("");
    $('#name_cycle').html('');
    $('#name_cycle').append('<option value="0">Selectionner une cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');
    $('#name_session').html('');
    $('#name_session').append('<option value="0">Selectionner une session</option>');
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



/*****  TRIMESTRES *****/
$('#from_trimestre').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/trimestre/insert";

    $('#btn-log').prop('disabled', true);

    const formData = new FormData();

    var number_trimestre = $('#number_trimestre').val();
    var name_trimestre = $('#name_trimestre').val();
    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var name_session = $('#name_session').val();
    var user_id = localStorage.getItem('id_user');

    if (number_trimestre == "" || name_trimestre == "" || name_school == "0" || name_cycle == "0" || name_classe == "0" || name_session == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        // add data form
        formData.append("number_trimestre", number_trimestre);
        formData.append("name_trimestre", name_trimestre);
        formData.append("name_cycle", name_cycle);
        formData.append("name_classe", name_classe);
        formData.append("name_session", name_session);
        formData.append("name_school", name_school);
        formData.append("user_id", user_id);

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
                    toastr["success"]("Opération Réussir", "Réussite");
                } else {
                    toastr["error"]("Opération echouer", "Erreur");
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