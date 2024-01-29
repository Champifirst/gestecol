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
    var class_id = $('#name_classe').val();
    liste_teaching_unit(class_id);
}


function liste_teaching_unit(class_id) {
    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/all/"+class_id+"";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            var tableau = new Array(json.length);
            for (var i = 0; i < json.length; i++) {
                let modifier = '<button class="btn btn-default btn-rounded btn-sm btn-select" onClick="select_data(' + json[i].teachingunit_id + ','+i+')"><span class="fa fa-pencil"></span></button> ';
                let supprimer = '';
                if (type_user == "admin") {
                    supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + json[i].teachingunit_id + ','+(i)+');"><span class="fa fa-times"></span></button>';
                }

                tableau[i] = new Array(5);
                tableau[i][0] = (i + 1);
                tableau[i][1] = (json[i].code.toUpperCase());
                tableau[i][2] = (json[i].name.toUpperCase());
                tableau[i][3] = (modifier + '   ' + supprimer);

            }

            $('#datatable-buttons').DataTable().destroy();

            var handleDataTableButtons = function () {
                if ($("#datatable-buttons").length) {
                    $("#datatable-buttons").DataTable({
                        dom: "Blfrtip",
                        buttons: [
                            {
                                extend: "copy",
                                className: "btn btn-info btn-sm mb-4"
                            },
                            {
                                extend: "csv",
                                className: "btn btn-danger btn-sm mb-4"
                            },
                            {
                                extend: "excel",
                                className: "btn btn-info btn-sm mb-4"
                            },
                            {
                                extend: "pdfHtml5",
                                className: "btn btn-danger btn-sm mb-4"
                            },
                            {
                                extend: "print",
                                className: "btn btn-info btn-sm mb-4"
                            },
                        ],
                        responsive: true,
                        aaData: tableau,
                        "scrollCollapse": true,
                        autoFill: true,
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
                        },
                    });
                }
            };
    
            TableManageButtons = function () {
                "use strict";
                return {
                    init: function () {
                        handleDataTableButtons();
                    }
                };
            }();

            TableManageButtons.init();

        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}
