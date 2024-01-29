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

// getSchool session
function getSchool(){
    let id_school = $("#name_school").val();
    if (id_school != 0 && id_school != null) {
        let url = $('meta[name=app-url]').attr("content") + "session/all/"+id_school+"";
        $("#name_session").html("");
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(data){
                let option = '<option value="0">Selectionner une session</option>';
                for (let i = 0; i < data.length; i++) {
                    option += '<option value="'+data[i].session_id+'">'+data[i].code_session.toUpperCase()+': '+data[i].name_session.toUpperCase()+'</option>';
                }
                $("#name_session").append(option);
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
}

/*****  RESET FORM *****/
function reset_form(){
    $('#name_cycle').val("");
    $('#number_cycle').val("");
}

/*****  CYCLE *****/
$('#from_cycle').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/cycle/insert";

    $('#btn-log').prop('disabled', true);

    const formData = new FormData();

    var name_cycle = $('#name_cycle').val();
    var number_cycle = $('#number_cycle').val();
    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var user_id = localStorage.getItem('id_user');

    if (name_cycle == "" || number_cycle == "" || name_school == "0" || user_id == "" || name_session == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("name_cycle", name_cycle);
        formData.append("number_cycle", number_cycle);
        formData.append("name_school", name_school);
        formData.append("name_session", name_session);
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