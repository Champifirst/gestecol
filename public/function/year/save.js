/*****  RESET FORM *****/

function reset_form() {
    $('#date_start').val("");
    $('#date_end').val("");
}

/*****  RESET FORM *****/


/*****  ANNEE *****/
$('#from_year').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/years/insert";
    $('#btn-log').prop('disabled', true);
    const formData = new FormData();

    var date_start = $('#date_start').val();
    var date_end = $('#date_end').val();
    var user_id = localStorage.getItem('id_user');

    if (date_start == "" || date_end == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("date_start", date_start);
        formData.append("date_end", date_end);
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