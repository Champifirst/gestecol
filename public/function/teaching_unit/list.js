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
                tableau[i][3] = (json[i].coefficient);
                tableau[i][4] = (modifier + '   ' + supprimer);

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

/*************  SUPPRIMER UNE MATIERE ***********/
function delete_row(id, ligne) {
    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/delete/"+id+"";
    var table = $('#datatable-buttons').DataTable();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    
    swalWithBootstrapButtons.fire({
        title: 'Attention !!!',
        text: "Êtes-vous sûr de vouloir supprimer cette matière ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // suppression
            $.ajax({
                type: "GET",
                url: url,
                cache: false,
                headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                success: function (data) {
                    if (data.success == true) {
                        // suppression de la ligne
                        table.row(ligne).remove();
                        // reorganise
                        let nbre_ligne = parseInt($('#datatable-buttons tr').length) - 1;
                        for (var i = 0; i <= (nbre_ligne - 1); i++) {
                            var cellule = table.cell(i, 0);
                            cellule.data(i + 1).draw();
                        }
                        swalWithBootstrapButtons.fire(
                            'Suppression réussir',
                            'La matière a été supprimer',
                            'success'
                        )
                    }else if((data.success == false)){
                        swalWithBootstrapButtons.fire(
                            'Erreur',
                            data.msg,
                            'error'
                        )
                    }

                },
                error: function (data) {
                    swalWithBootstrapButtons.fire(
                        'Erreur',
                        'L\'opération a échouer.',
                        'error'
                    )
                }
            });
            
        } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Annuler',
                'L\'opération de suppression a été annulée.',
                'error'
            )
        }
    })

}


function select_data(teaching_id, ligne){
    $("#name_school_edit").html("");
    $("#name_session_edit").html("");
    $("#name_cycle_edit").html("");
    $("#name_classe_edit").html("");

    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/one/"+teaching_id+"";
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function (json) {
            if (json.success == true) {
                $('#ligne_teaching').val(ligne);
                $("#teaching_id").val(teaching_id);
                $("#code_edit").val(json.data.code);
                $("#nom_edit").val(json.data.name);
                $("#coeff_edit").val(json.data.coefficient);
                //-- school
                let id_school = json.data.school_id;
                let id_school_active = 0;
                if (localStorage.getItem('id_school') != null) {
                    id_school_active = localStorage.getItem('id_school');
                }
                let url2 = $('meta[name=app-url]').attr("content") + "/school/AllSchool/"+id_school_active+"";
                $.ajax({
                    url: url2,
                    method: "GET",
                    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                    success: function(list_school) {
                        console.log(list_school);
                        let option = '';
                        let selected_school = "";
                        for (let i = 0; i < list_school.length; i++) {
                            if (id_school == list_school[i].school_id) {
                                selected_school = "selected";
                            }else{
                                selected_school = "";
                            }
                            option += '<option value="'+list_school[i].school_id+'" '+selected_school+'>'+list_school[i].name.toUpperCase()+'</option>';
                        }
                        $("#name_school_edit").append(option);
                    },
                    error: function(xhr, status, error) {
                      console.error(error);
                      toastr["error"]("Nous n'avons pas pu récupérer la liste des écoles.", "Erreur");
                    }
                });
                //-- session
                let id_session = json.data.session_id;
                let url3 = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
                $.ajax({
                    url: url3,
                    method: "GET",
                    dataType: 'json',
                    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                    success: function(list_session){
                        if (list_session !== undefined) {
                            let option ='<option value="0">Selectionner une session</option>';
                            let selected_session = "";
                            for (let i = 0; i < list_session.length; i++) {
                                if (id_session == list_session[i].session_id) {
                                    selected_session = "selected";
                                }else{
                                    selected_session = "";
                                }
                                option += '<option value="'+list_session[i].session_id+'" '+selected_session+'>'+list_session[i].code_session.toUpperCase()+': '+list_session[i].name_session.toUpperCase()+'</option>'
                            }
                            $('#name_session_edit').append(option);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseJSON);
                        $('#btn-log').prop('disabled', false);
                        toastr["error"]("Nous n'avons pas pu récupérer la liste des sessions", "Erreur");
                    }
                });
                //-- cycle
                let id_cycle = json.data.cycle_id;
                let url4 = $('meta[name=app-url]').attr("content") + "/cycle/all/"+id_school+"/"+id_session;
                $.ajax({
                    url: url4,
                    method: "GET",
                    dataType: 'json',
                    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                    success: function(list_cycle){
                        if (list_cycle !== undefined) {
                            let option ='<option value="0">Selectionner un cycle</option>';
                            let selected_cycle = '';
                            for (let i = 0; i < list_cycle.length; i++) {
                                if (id_cycle == list_cycle[i].cycle_id) {
                                    selected_cycle = "selected";
                                }else{
                                    selected_cycle = "";
                                }
                                option += '<option value="'+list_cycle[i].cycle_id+'" '+selected_cycle+'>'+list_cycle[i].name_cycle.toUpperCase()+'</option>'
                            }
                            $('#name_cycle_edit').append(option);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseJSON);
                        $('#btn-log').prop('disabled', false);
                        toastr["error"]("Nous n'avons pas pu récupérer la liste des cycle", "Erreur");
                    }
                });
                //-- class
                let id_class = json.data.class_id;
                let url5 = $('meta[name=app-url]').attr("content") + "/class/all/"+id_school+"/"+id_session+"/"+id_cycle;

                $.ajax({
                    url: url5,
                    method: "GET",
                    dataType: 'json',
                    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                    success: function(donnees){
                        let list_class = donnees.data;
                        if (list_class !== undefined) {
                            let option ='<option value="0">Selectionner une classe</option>';
                            let selected_classe = '';
                            for (let i = 0; i < list_class.length; i++) {
                                if (id_class == list_class[i].class_id) {
                                    selected_classe = "selected";
                                }else{
                                    selected_classe = "";
                                }
                                option += '<option value="'+list_class[i].class_id+'" '+selected_classe+'>'+list_class[i].name.toUpperCase()+'</option>'
                            }
                            $('#name_classe_edit').append(option);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseJSON);
                        $('#btn-log').prop('disabled', false);
                        toastr["error"]("Nous n'avons pas pu récupérer la liste des classes", "Erreur");
                    }
                });

                $("#staticBackdrop").modal("show", true);
            }else if((json.success == false)){
                toastr["error"](json.msg, "Erreur");
            }
        },
        error: function (data) {
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
    
}

/******* UPDATE DATA ******/
function getSchoolUpdate(){
    var name_school = $('#name_school_edit').val();
    $('#name_session_edit').html("");
    $('#name_session_edit').append('<option value="0">Selectionner une session</option>');
    $('#cycle_edit').html("");
    $('#cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe_edit').html("");
    $('#name_classe_edit').append('<option value="0">Selectionner une classe</option>');
    if (name_school != "0") {
        ShowSessionUpdate(name_school);
    }else{
        toastr["error"]("Selectionnez l'établissement ", "Attention");
    }
}

function ShowSessionUpdate(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/session/all/"+id_school+"";
    $('#name_session_edit').html("");
    $('#name_session_edit').append('<option value="0">Selectionner une session</option>');
    $('#name_cycle_edit').html("");
    $('#name_cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe_edit').html("");
    $('#name_classe_edit').append('<option value="0">Selectionner une classe</option>');

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
                $('#name_session_edit').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function getSessionUpdate(){
    $('#name_cycle_edit').html("");
    $('#name_cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#name_classe_edit').html("");
    $('#name_classe_edit').append('<option value="0">Selectionner une classe</option>');
    var session = $('#name_session_edit').val();
    var name_school = $('#name_school_edit').val();
    if (name_school != "0" && session != "0" && session != null && name_school != null ) {
        ShowCycleUpdate(name_school, session);
    }else{
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (session == "0" || session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }
    }
}

function ShowCycleUpdate(id_school, name_session){
    let url = $('meta[name=app-url]').attr("content") + "/cycle/all/"+id_school+"/"+name_session;
    $('#namecycle_edit').html("");
    $('#namecycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#nameclasse_edit').html("");
    $('#nameclasse_edit').append('<option value="0">Selectionner une classe</option>');

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
                $('#name_cycle_edit').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function getCycleUpdate(){
    $('#name_classe_edit').html("");
    $('#name_classe_edit').append('<option value="0">Selectionner une classe</option>');
    var session = $('#name_session_edit').val();
    var cycle = $('#name_cycle_edit').val();
    var name_school = $('#name_school_edit').val();
    if (name_school != "0" && session != "0" && session != null && name_school != null && cycle != "0" && cycle != null ) {
        ShowClassUpdate(name_school, session, cycle);
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

function ShowClassUpdate(id_school, name_session, cycle){
    let url = $('meta[name=app-url]').attr("content") + "/class/all/"+id_school+"/"+name_session+"/"+cycle;
    $('#name_classe_edit').html("");
    $('#name_classe_edit').append('<option value="0">Selectionner une classe</option>');

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
                $('#name_classe_edit').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

function reset_form(){
    $('#code_edit').val("");
    $('#nom_edit').val("");
    $('#coeff_edit').val("");
}

/*****  UPDATE DATA *****/
$('#form_update').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/teaching_unit/update";
    const formData = new FormData();
    $('#btn-log-update').prop('disabled', true);

    var ligne_teaching = $('#ligne_teaching').val();
    var teaching_id = $('#teaching_id').val();
    var code = $('#code_edit').val();
    var nom = $('#nom_edit').val();
    var coeff = $('#coeff_edit').val();
    var name_school = $('#name_school_edit').val();
    var session = $('#name_session_edit').val();
    var cycle = $('#name_cycle_edit').val();
    var classe = $('#name_classe_edit').val();
    var user_id = localStorage.getItem('id_user');
    
    if (nom == "" || code == "" || coeff == 0 || name_school == "0" || session == "0" || cycle == "0" || classe == "0" ) {
        $('#btn-log-update').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        formData.append("code", code);
        formData.append("name", nom);
        formData.append("coefficient", coeff);
        formData.append("school", name_school);
        formData.append("session", session);
        formData.append("cycle", cycle);
        formData.append("classe", classe);
        formData.append("user_id", user_id);
        formData.append("teachingunit_id", teaching_id);

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
                    toastr["success"](data.msg, "Réussite");
                    $('#btn-log-update').prop('disabled', false);
                    $("#staticBackdrop").modal("hide");
                    let teaching = data.data;
                    //
                    var table = $('#datatable-buttons').DataTable();

                    let rowData = table.row(ligne_teaching).data();
                    rowData[1] = teaching.code.toUpperCase();
                    rowData[2] = teaching.name.toUpperCase();
                    rowData[3] = teaching.coefficient;

                    table.row(ligne_teaching).data(rowData).draw();
                } else {
                    toastr["error"](data.msg, "Erreur");
                }
                $('#btn-log-update').prop('disabled', false);
            },
            error: function (data) {
                console.log(data.responseJSON);
                $('#btn-log-update').prop('disabled', false);
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
})


