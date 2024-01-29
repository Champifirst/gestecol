/*************  LISTE ECOLE ***********/
let id_school = 0;
if (localStorage.getItem('id_school') != null) {
    id_school = localStorage.getItem('id_school');
}
listeSchool(id_school);

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
                $("#school").append(option);
            }else{
                toastr["warning"]("Auccune école enregistrées", "Alerte");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*****  LIST TEACHERS *****/
function charger_liste_teachear(){

    $("#personnel").html('');
    var name_school = $('#school').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/teacher/FindAllPersonnelBySchool/"+name_school;
    $("#printf").html("");

    if (name_school == "0" || user_id == "") {
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(response){
                if (response.success == true) {
                    let json = response.data;
                    console.log(json);
                    let option = '';
                    for (let i = 0; i < json.length; i++) {
                        option += '<option value="'+json[i].teacher_id+'">'+json[i].matricule.toUpperCase()+' : '+json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase()+'</option>';
                    }
                    $("#personnel").append(option);
                }else{
                    toastr["error"](response.msg, "Erreur");
                }
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
}


/*****  SAVE SALAIRE *****/
$('#from_pay_salaire').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/teacher/Payer_salaire";

    $('#btn-log').prop('disabled', true);

    var name_school = $("#school").val();
    var personnel   = $("#personnel").val();
    var salaire     = $("#salaire").val();
    var montant_lettre= $("#montant_lettre").val();

    if (name_school == "0" || name_school == null || personnel == "0" || personnel == null || salaire == "" || montant_lettre == "") {
        $('#btn-log').prop('disabled', false);
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (personnel == "0" || personnel == null) {
            toastr["error"]("Selectionnez le personnel ", "Attention");
        }else if(salaire == ""){
            toastr["error"]("Veuillez entrer un montant en chiffre pour le salaire ", "Attention");
        }else if(montant_lettre == ""){
            toastr["error"]("Veuillez entrer un montant en lettre pour le salaire ", "Attention");
        }
    } else {
        // add data form
        var data_form = $('#from_pay_salaire')[0];
        const formData = new FormData(data_form);

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
                    $("#salaire").val("");
                    $("#montant_lettre").val("");
                    // afficher le recus
                    Object.assign(document.createElement("a"), {
                        target: "_blanck",
                        href: '../'+data.name_file
                    }).click();
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