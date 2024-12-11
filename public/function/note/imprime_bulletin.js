$(document).ready(function () {
    $('#student').prop('disabled', false);
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


function getClass(){
    $('#btn-log').prop('disabled', false);

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/student/AllStudent/"+name_classe+"";
    $("#printf").html("");

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || user_id == "") {
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                console.log(json);
                let option = '';
                for (let i = 0; i < json.length; i++) {
                    option += '<option value="'+json[i].student_id+'">'+json[i].matricule.toUpperCase()+' : '+json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase()+'</option>';
                }
                $("#student").append(option);
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
        ShowTrimestre(name_school, name_cycle, name_session, name_classe);
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
    $('#name_sequence').append('<option value="0">Selectionner une séquence</option> <option value="1">Trimestriel</option>');
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



function getPrintType(){
    var imprime_pour = $('#imprime_pour').val();
    if(imprime_pour == 'all') {
        $('#student').prop('disabled', true);
    } else{
        $('#student').prop('disabled', false);
    }
}


/***************** Print Bulletin *************************/

$("#from_trimestre").on("submit", function(event){
    event.preventDefault();

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var name_trimestre = $('#name_trimestre').val();  
    var name_sequence = parseInt($('#name_sequence').val());
    var imprime_pour = $("#imprime_pour").val();
    var student_id = $('#student').val();

    if(name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || name_trimestre == "0" || name_sequence == "0" || imprime_pour == ""){
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    }else {  

        let url = $('meta[name=app-url]').attr("content") + "/student/PrintAllBulletin/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe+"/"+name_trimestre+"/" +name_sequence+"/"+imprime_pour+"/"+student_id;

        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                if (json !== undefined) {
                    if (json.success == true) {
                        toastr["success"]("Impréssion réussir", "Réussite");
                        Object.assign(document.createElement("a"), {
                            target: "_blanck",
                            href: '../'+json.name_file
                        }).click();
                    }else{
                        toastr["error"]("L'impression a échouer", "Erreur");
                    }
                }
            },
            error: function (data) {
                console.log(data.responseJSON);
                $('#btn-log').prop('disabled', false);
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        
        });
    }

});

