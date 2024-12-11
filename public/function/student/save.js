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
    $('#classe').append('<option value="0">Selectionner une classe</option>');
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
    $('#classe').append('<option value="0">Selectionner une classe</option>');

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
    $('#classe').append('<option value="0">Selectionner une classe</option>');
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
    $('#classe').append('<option value="0">Selectionner une classe</option>');

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
    $('#classe').append('<option value="0">Selectionner une classe</option>');
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
    $('#classe').append('<option value="0">Selectionner une classe</option>');

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

function getClasse(){
    var session = $('#session').val();
    var cycle = $('#cycle').val();
    var classe = $('#classe').val();
    var name_school = $('#name_school').val();

    if (name_school != "0" && session != "0" && session != null && name_school != null && cycle != "0" && cycle != null && classe != null && classe != null) {
        ShowUnit(classe);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (session == "0" || session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(cycle == "0" || cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if(classe == null || classe == "0"){
            toastr["error"]("Selectionnez la classe ", "Attention");
        }
    }
}
/*****  SHOW SUBJECTS *****/

function ShowUnit(classe) {
    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/ByClass/"+classe;
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " + localStorage.getItem('token')},
        success: function(json) {
            let data = json; // Pas besoin de `json.data` si `json` est déjà un tableau
            if (data !== undefined && data.length > 0) {
                let option = '<option value="all">Toutes les matières</option>';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="' + data[i].teachingunit_id + '">' + data[i].name.toUpperCase() + '</option>';
                    }
                $('#subjet').html(option); // Remplir le select avec les options
            } else {
                $('#subject-container').hide(); // Masquer le conteneur si aucune matière n'est disponible
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

// COLLECTE SUBJECT
function submitSubjects() {
    // let url = 
    var name_school = $('#name_school').val();
    var session = $('#session').val();
    var cycle = $('#cycle').val();
    var classe = $('#classe').val();
    var user_id = localStorage.getItem('id_user');
    let selectedSubjects = [];

    const selectElement = document.getElementById('subjet');
  
    // Initialiser un tableau pour stocker les valeurs sélectionnées
    let selectedValues = [];
    
    // Parcourir les options
    for (let option of selectElement.options) {
        // Vérifier si l'option est sélectionnée
        if (option.selected) {
        selectedValues.push(option.value); // Ajouter la valeur au tableau
        }
    }
    
    const formData = new FormData();
    formData.append('name_school', name_school);
    formData.append('session', session);
    formData.append('cycle', cycle);
    formData.append('class', classe);
    formData.append('user_id', user_id);
    formData.append('subjects', JSON.stringify(selectedValues));

    console.log(formData, selectedValues);

    // // Envoyer les données via AJAX
    // $.ajax({
    //     url: '/path/to/your/server/endpoint', // Remplacez par l'URL de votre serveur
    //     type: 'POST',
    //     data: {
    //         subjects: selectedSubjects,
    //         // Vous pouvez ajouter d'autres données à envoyer si nécessaire
    //     },
    //     success: function(response) {
    //         // Gérer la réponse du serveur
    //         console.log('Données envoyées avec succès:', response);
    //     },
    //     error: function(xhr, status, error) {
    //         // Gérer les erreurs
    //         console.error('Erreur lors de l\'envoi des données:', error);
    //     }
    // });
}


/*****  RESET FORM *****/

function reset_form() {
    $('#name').val("");
    $('#surName').val("");
    $('#date').val("");
    $('#placeBirth').val("");
    $('#sexe').val("");
    $('#nameParent').val("");
    $('#surnameParent').val("");
    $('#email_parent').val("");
    $('#profession').val("");
    $('#phone').val("");
    $('#adresse_parent').val("");
    $('#subject').val("");
    // $('#session').val("");
    // $('#cycle').val("");
    // $('#classe').val("");
    $('#inscription').val("");
    // logo
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}

/*****  RESET FORM *****/


/*****  ELEVES *****/
$('#from_student').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/insert";

    $('#btn-log').prop('disabled', true);
    $("#success").hide();
    $("#errors").hide();

    const photo = document.getElementById("logo").files[0];
    const formData = new FormData();

    var name = $('#name').val();
    var surName = $('#surName').val();
    var date = $('#date').val();
    var placeBirth = $('#placeBirth').val();
    var sexe = $('#sexe').val();
    var nameParent = $('#nameParent').val();
    var surnameParent = $('#surnameParent').val();
    var email_parent = $('#email_parent').val();
    var profession = $('#profession').val();
    var phone = $('#phone').val();
    var adresse_parent = $('#adresse_parent').val();
    var name_school = $('#name_school').val();
    var session = $('#session').val();
    var cycle = $('#cycle').val();
    var classe = $('#classe').val();
    var inscription = $('#inscription').val();
    var user_id = localStorage.getItem('id_user');

    const selectElement = document.getElementById('subjet');
    let selectedValues = [];
    for (let option of selectElement.options) {
        if (option.selected) {
        selectedValues.push(option.value);
        }
    }

    if (name == "" || date == "" || placeBirth == "" || sexe == "0" || nameParent == "" || phone == "" || logo == "" || name_school == "0" || session == "0" || cycle == "0" || classe == "0" ) {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("name", name);
        formData.append("surName", surName);
        formData.append("date", date);
        formData.append("placeBirth", placeBirth);
        formData.append("sexe", sexe);
        formData.append("nameParent", nameParent);
        formData.append("surnameParent", surnameParent);
        formData.append("email_parent", email_parent);
        formData.append("profession", profession);
        formData.append("phone", phone);
        formData.append("adresse_parent", adresse_parent);
        formData.append("name_school", name_school);
        formData.append("session", session);
        formData.append("cycle", cycle);
        formData.append("classe", classe);
        formData.append("inscription", inscription);
        formData.append('subjects', JSON.stringify(selectedValues));
        
        formData.append("logo", photo);
        formData.append("user_id", user_id);

        console.log(formData);
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