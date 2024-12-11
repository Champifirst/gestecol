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
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');

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

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucune session trouvée.", "Alerte");
                }else{
                   let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].session_id+'">'+data[i].code_session.toUpperCase()+': '+data[i].name_session.toUpperCase()+'</option>'
                    }
                    $('#name_session').append(option); 
                }
                
            }else{
                toastr["warning"]("Aucune session trouvée.", "Alerte");
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

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucun cycle trouvé", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].cycle_id+'">'+data[i].name_cycle.toUpperCase()+'</option>'
                    }
                    $('#name_cycle').append(option);
                }
            }else{
                toastr["warning"]("Aucun cycle trouvé", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function charger_fiche(){
    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();

    $('#contain_body').html("");
    $("#bloc_btn").html("");

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null) {
        // affichage de la fiche des classes
        let url = $('meta[name=app-url]').attr("content") + "teacher/getTeacherSchoolClass/"+name_school+"/"+name_session+"/"+name_cycle+"";
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                
                if (json !== undefined) {
                    let data = json.data;
                    
                    let tr = '';
                    if (data.length != 0) {
                        for (let i = 0; i < data.length; i++) {
                            //-- listing enseignant
                            let option = '<option value="0">Selectionner un enseignant</option>';
                            let enseignants = json.enseginants;
                            let name_enseignant = data[i].name_enseignant.toUpperCase();
                            let id_enseignant = data[i].id_enseignant;
                            let selected = '';
                            for (let e = 0; e < enseignants.length; e++) {
                                enseignant = enseignants[e];
                                if (enseignant.teacher_id == id_enseignant) {
                                    selected = 'selected';
                                }else{
                                    selected = '';
                                }
                                option += '<option value="'+enseignant.teacher_id+'" '+selected+'>'+enseignant.name+' '+enseignant.surname+'</option>';
                            }

                            let deliberation = '<input class="form-control" type="number" name="deliberation[]" min="0">';

                           // boucler les lignes du tableau
                           tr += '<tr>'+
                           '<td>'+(i+1)+'<input type="number" name="id_class[]" value="'+data[i].class_id+'" hidden></td>'+
                           '<td>'+data[i].code_class.toUpperCase()+'</td>'+
                           '<td>'+data[i].name_class.toUpperCase()+'</td>'+
                           '<td>'+deliberation+'</td>'+
                           '<td>'+(i+1)+'</td>'+
                           '</tr>';
                        }
                        $('#contain_body').append(tr);
                        // btn submit
                        let btn_submit = '<div class="form-group text-center">'+
                        '<div class="col-md-6 offset-md-3 mt-4">'+
                            '<button type="reset" class="btn btn-danger">Annuler</button>'+
                            '<button type="submit" class="btn btn-success" id="btn-log">Enregistrer</button>'+
                        '</div>'+
                        '</div>';
                        $("#bloc_btn").append(btn_submit);

                    }else{
                        toastr["warning"]("Aucune classe trouvée.", "Alerte");
                    }
                }else{
                    toastr["warning"]("Aucune classe trouvée.", "Alerte");
                }
            },
            error: function (data) {
                console.log(data.responseJSON);
                $('#btn-log').prop('disabled', false);
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });

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


/*****  ATTRIBUTION DES SALLES DE CLASSES *****/
$('#from_attribution_class').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/teacher/Attribution_class";

    $('#btn-log').prop('disabled', true);

    var name_school = $("#name_school").val();
    var name_session = $("#name_session").val();
    var name_cycle = $("#name_cycle").val();

    if (name_school == "0" && name_session == "0" && name_session == null && name_school == null && name_cycle == "0" && name_cycle == null) {
        $('#btn-log').prop('disabled', false);
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }
    } else {
        // add data form
        var data_form = $('#from_attribution_class')[0];
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
                    window.location.href="giveClass";
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