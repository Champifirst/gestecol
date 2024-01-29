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

function getSchool(){
    $('#name_session').html("");
    $('#name_session').append('<option value="0">Selectionner une session</option>');
    $('#name_cycle').html("");
    $('#name_cycle').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');

    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

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

    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

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
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');

    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

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

    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

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


function getCycle(){
    $('#name_classe').html("");
    $('#name_classe').append('<option value="0">Selectionner une classe</option>');

    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

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
    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

    let url = $('meta[name=app-url]').attr("content") + "/class/all/"+name_school+"/"+name_session+"/"+name_cycle;

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            let data = json.data;
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucune classe trouvée.", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].class_id+'">'+data[i].name.toUpperCase()+'</option>'
                    }
                    $('#name_classe').append(option);
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
}


function getClass(){
    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();
    var name_classe = $('#name_classe').val();

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null && name_classe != "0" && name_classe != null ) {
        ShowTrimestre(name_school, name_cycle, name_session, name_classe);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }
    }
}

function ShowTrimestre(name_school, name_cycle, name_session, name_classe){
    $('#name_trimestre').html("");
    $('#name_trimestre').append('<option value="0">Selectionner un trimestre</option>');
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');
    
    let url = $('meta[name=app-url]').attr("content") + "/trimestre/all-filter/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            let data = json.data;
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Auccun trimestre trouvé", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].trimestre_id+'">'+data[i].name.toUpperCase()+'</option>'
                    }
                    $('#name_trimestre').append(option);
                }
            }else{
                toastr["warning"]("Auccun trimestre trouvé", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });

}

function getTrimestre(){
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();
    var name_classe = $('#name_classe').val();
    var name_trimestre = $('#name_trimestre').val();

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null && name_classe != "0" && name_classe != null && name_trimestre != "0" && name_trimestre != null ) {
        ShowSequence(name_school, name_cycle, name_session, name_classe, name_trimestre);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }else if (name_trimestre == "0" || name_trimestre == null) {
            toastr["error"]("Selectionnez un trimestre ", "Attention");
        }
    }
}


function ShowSequence(name_school, name_cycle, name_session, name_classe, name_trimestre){
    $('#name_sequence').html("");
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option>');
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

    let url = $('meta[name=app-url]').attr("content") + "/sequence/all-filter/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe+"/"+name_trimestre;

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            let data = json.data;
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Auccune séquence trouvée", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].sequence_id+'">'+data[i].name.toUpperCase()+'</option>'
                    }
                    $('#name_sequence').append(option);
                }
            }else{
                toastr["warning"]("Auccune séquence trouvée", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}


function getSequence(){
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');

    var name_school = $('#name_school').val();
    var name_cycle = $('#name_cycle').val();
    var name_session = $('#name_session').val();
    var name_classe = $('#name_classe').val();
    var name_trimestre = $('#name_trimestre').val();
    var name_sequence = $('#name_sequence').val();

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null && name_classe != "0" && name_classe != null && name_trimestre != "0" && name_trimestre != null && name_sequence != "0" && name_sequence != null ) {
        ShowMatiere(name_classe);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }else if (name_trimestre == "0" || name_trimestre == null) {
            toastr["error"]("Selectionnez un trimestre ", "Attention");
        }else if (name_sequence == "0" || name_sequence == null) {
            toastr["error"]("Selectionnez une séquence ", "Attention");
        }
    }
}

function ShowMatiere(name_classe){
    $('#name_matiere').html("");
    $('#name_matiere').append('<option value="0">Selectionner une matière</option>');
    
    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/all/"+name_classe+"";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                if (data.length == 0) {
                    toastr["warning"]("Aucune matière trouvée", "Alerte");
                }else{
                    let option ='';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="'+data[i].teachingunit_id+'">'+data[i].name.toUpperCase()+'</option>'
                    }
                    $('#name_matiere').append(option);
                }
            }else{
                toastr["warning"]("Aucune matière trouvée", "Alerte");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });

}

function charger_fiche_note(){
    var name_school = $("#name_school").val();
    var name_session = $("#name_session").val();
    var name_cycle = $("#name_cycle").val();
    var name_classe = $("#name_classe").val();
    var name_trimestre = $("#name_trimestre").val();
    var name_sequence = $("#name_sequence").val();
    var name_matiere = $("#name_matiere").val();
    $('#contain_body').html("");
    $("#bloc_btn").html("");

    if (name_school != "0" && name_session != "0" && name_session != null && name_school != null && name_cycle != "0" && name_cycle != null && name_classe != "0" && name_classe != null && name_trimestre != "0" && name_trimestre != null && name_sequence != "0" && name_sequence != null && name_matiere != "0" && name_matiere != null ) {
        // affichage de la fiche de note
        let url = $('meta[name=app-url]').attr("content") + "/note/getnoteByTeachingUnit/"+name_classe+"/"+name_matiere+"/"+name_sequence+"";
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(data){
                if (data !== undefined) {
                    if (data.length != 0) {
                        for (let i = 0; i < data.length; i++) {
                            let note = "";
                            let note_coff = "";
                            if (data[i].note != -1) {
                                note = data[i].note;
                                note_coff = parseFloat(note)*parseFloat(data[i].coefficient);
                            }
                            let td_status = '';
                            if (data[i].close == "false") {
                                td_status = '<td><button type="button" class="btn btn-success btn-rounded btn-sm">🔓</button></td>';
                            }else{
                                td_status = '<td><button type="button" class="btn btn-default btn-rounded btn-sm btn-actif">🔒</button></td>';
                            }
                            
                            let ligne = '<tr id="TRow">'+
                            '<td scope="row">'+(i+1)+'</td>'+
                            '<td>'+data[i].matricule.toUpperCase()+'</td>'+
                            '<td>'+data[i].name.toUpperCase()+''+data[i].surname.toUpperCase()+'</td>'+
                            '<td><input type="number" min="0" step="0.00" class="form-control note" name="note[]" value="'+note+'" onchange="changeNote(this, '+data[i].coefficient+')"></td>'+
                            '<td>'+data[i].coefficient+'</td>'+
                            '<td><input type="text" min="0" class="form-control" name="note_coff" value="'+note_coff+'" disabled="" ><input type="number" name="student_id[]" id="student_id" value="'+data[i].id_student+'" min="0" hidden></td>'+
                            td_status+
                            '</tr>';
                            $('#contain_body').append(ligne);
                        }
                        // btn submit
                        let btn_submit = '<div class="form-group text-center">'+
                        '<div class="col-md-6 offset-md-3 mt-4">'+
                            '<button type="reset" class="btn btn-danger">Annuler</button>'+
                            '<button type="submit" class="btn btn-success" id="btn-log">Enregistrer</button>'+
                        '</div>'+
                        '</div>';
                        $("#bloc_btn").append(btn_submit);
                        toastr["success"]("Opération réussir", "Réussite");
                    }else{
                        toastr["warning"]("Aucun élève trouvé.", "Alerte");
                    }
                }else{
                    toastr["warning"]("Aucun élèves trouvé", "Alerte");
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
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }else if (name_trimestre == "0" || name_trimestre == null) {
            toastr["error"]("Selectionnez un trimestre ", "Attention");
        }else if (name_sequence == "0" || name_sequence == null) {
            toastr["error"]("Selectionnez une séquence ", "Attention");
        }
    }
    
}

function changeNote(v, coff){
    /*Detail Calculation Each Row*/
    var index = $(v).parent().parent().index();
    document.getElementsByName("note_coff")[index].value = "";
    
    var note = document.getElementsByClassName("note")[index].value;
    var note_coff = note*coff;
    if (note == "") {
        note_coff = "";
    }
    document.getElementsByName("note_coff")[index].value = note_coff;
}

/*****  NOTE *****/
$('#from_note').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/note/insert";

    $('#btn-log').prop('disabled', true);

    var name_school = $("#name_school").val();
    var name_session = $("#name_session").val();
    var name_cycle = $("#name_cycle").val();
    var name_classe = $("#name_classe").val();
    var name_trimestre = $("#name_trimestre").val();
    var name_sequence = $("#name_sequence").val();
    var name_matiere = $("#name_matiere").val();

    if (name_school == "0" && name_session == "0" && name_session == null && name_school == null && name_cycle == "0" && name_cycle == null && name_classe == "0" && name_classe == null && name_trimestre == "0" && name_trimestre == null && name_sequence == "0" && name_sequence == null && name_matiere == "0" && name_matiere == null ) {
        $('#btn-log').prop('disabled', false);
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }else if (name_trimestre == "0" || name_trimestre == null) {
            toastr["error"]("Selectionnez un trimestre ", "Attention");
        }else if (name_sequence == "0" || name_sequence == null) {
            toastr["error"]("Selectionnez une séquence ", "Attention");
        }
    } else {
        // add data form
        var data_form = $('#from_note')[0];
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
                    window.location.href="save";
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