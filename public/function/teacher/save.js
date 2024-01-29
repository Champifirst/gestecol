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


/*****  RESET FORM *****/
function reset_form() {
    $('#matricule').val("");
    $('#name').val("");
    $('#prenom').val("");
    $('#diplome').val("");
    $('#email').val("");
    $('#phone').val("");
    $('#salaire').val("");
    $('#name_school').val("");
    $('#type').val("");
    $('#login_ens').val("");
    $('#password').val("");
    // logo
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}

/*****  ENSEIGNANT *****/
$('#from_ensg').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/teacher/insert";

    $('#btn-log').prop('disabled', true);
    $("#success").hide();
    $("#errors").hide();

    const photo = document.getElementById("photo").files[0];
    const formData = new FormData();

    var matricule = $('#matricule').val();
    var name = $('#name').val();
    var prenom = $('#prenom').val();
    var diplome = $('#diplome').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var salaire = $('#salaire').val();
    var logo = $('#photo').val();
    var name_school = $('#name_school').val();
    var type = $('#type').val();
    var login = $('#login_ens').val();
    var sexe = $('#sexe').val();
    var password = $('#password').val();
    var user_id = localStorage.getItem('id_user');

    if (sexe == "0" || matricule == "" || name == "" || prenom == "" || diplome == "" || phone == "" || salaire == "" || name_school == "0" || login =="" || password == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("matricule", matricule);
        formData.append("name", name);
        formData.append("prenom", prenom);
        formData.append("diplome", diplome);
        formData.append("email", email);
        formData.append("phone", phone);
        formData.append("photo", photo);
        formData.append("salaire", salaire);
        formData.append("school_id", name_school);
        formData.append("type", type);
        formData.append("login", login);
        formData.append("password", password);
        formData.append("sexe", sexe);
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