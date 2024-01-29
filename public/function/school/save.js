/*****  RESET FORM *****/

function reset_form() {
    $('#name').val("");
    $('#code').val("");
    $('#date').val("");
    $('#responsable').val("");
    $('#email').val("");
    $('#phone1').val("");
    $('#phone2').val("");
    // logo
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
    
    $('#color1').val("");
    $('#color2').val("");
}

/*****  RESET FORM *****/


/*****  ETABLISSEMENT *****/
$('#from_school').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/school/insert";

    $('#btn-log').prop('disabled', true);
    $("#success").hide();
    $("#errors").hide();

    const photo = document.getElementById("logo").files[0];
    const formData = new FormData();

    var name = $('#name').val();
    var code = $('#code').val();
    var date = $('#date').val();
    var responsable = $('#responsable').val();
    var email = $('#email').val();
    var phone1 = $('#phone1').val();
    var phone2 = $('#phone2').val();
    var logo = $('#logo').val();
    var color1 = $('#color1').val();
    var color2 = $('#color2').val();
    var matricule = $('#matricule').val();
    var user_id = localStorage.getItem('id_user');

    if (matricule == "" || name == "" || code == "" || date == "" || responsable == "" || email == "0" || phone1 == "" || phone2 == "" || logo == "" || color1 == "" || color2 == "" ) {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        // add data form
        formData.append("name_school", name);
        formData.append("coded_school", code);
        formData.append("create_at_school", date);
        formData.append("responsable", responsable);
        formData.append("email", email);
        formData.append("phone1", phone1);
        formData.append("phone2", phone2);
        formData.append("logo", photo);
        formData.append("color1", color1);
        formData.append("color2", color2);
        formData.append("user_id", user_id);
        formData.append("matricule", matricule);

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