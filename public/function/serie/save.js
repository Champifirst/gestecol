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
    $('#name_serie').val("");
    $('#number_serie').val("");
}

/*****  SERIE *****/
$('#from_serie').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/serie/insert";

    $('#btn-log').prop('disabled', true);

    const formData = new FormData();

    var name_serie = $('#name_serie').val();
    var number_serie = $('#number_serie').val();
    var name_school = $('#name_school').val();
    var user_id = localStorage.getItem('id_user');

    if (name_serie == "" || number_serie == "" || name_school == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("name_serie", name_serie);
        formData.append("number_serie", number_serie);
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