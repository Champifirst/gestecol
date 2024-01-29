
/*************  LISTE ECOLE ***********/
$(document).ready(function () {
    let id_school = 0;
    if (localStorage.getItem('id_school') != null) {
        id_school = localStorage.getItem('id_school');
    }
    liste_ecole(id_school);
});


function liste_ecole(id_school) {
    let url = $('meta[name=app-url]').attr("content") + "/school/AllSchool/"+id_school+"";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            var tableau = new Array(json.length);
            for (var i = 0; i < json.length; i++) {
                let modifier = '<button class="btn btn-default btn-rounded btn-sm" onClick="select_data(' + json[i].school_id + ')"><span class="fa fa-pencil"></span></button>';
                let supprimer = '';
                if (type_user == "admin") {
                    supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + json[i].school_id + ');"><span class="fa fa-times"></span></button>';
                }
                let afficher = '<button class="btn btn-primary btn-rounded btn-sm" onClick="show_row(' + json[i].school_id + ');"><span class="fa fa-list"></span></button>';

                tableau[i] = new Array(8);
                tableau[i][0] = (i + 1);
                tableau[i][1] = ('<img src="../logoSchool/' + json[i].logo + '" width="100px" alt="">');
                tableau[i][2] = (json[i].code.toUpperCase());
                tableau[i][3] = (json[i].name.toUpperCase());
                tableau[i][4] = (json[i].responsable.toUpperCase());
                tableau[i][5] = ('<a href="' + json[i].email + '">' + json[i].email + '</a>');
                tableau[i][6] = json[i].phone;
                if (json[i].etat_school == 'actif') {
                    let status = '<button class="btn btn-success btn-rounded btn-sm" onClick="desactiver(' + json[i].school_id + ')">🔓</button>';
                    tableau[i][7] = (status + ' ' + afficher + ' ' + modifier + '  ' + supprimer);
                } else {
                    let status = '<button class="btn btn-default btn-rounded btn-sm" onClick="activer(' + json[i].school_id + ')">🔒</button>';
                    tableau[i][7] = (status + '  ' + '  ' + afficher + '  ' + modifier + '   ' + supprimer);
                }

            }

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


/*************  SUPPRIMER UN ETABLISSAMENT ***********/

function delete_row(id) {
    let url = $('meta[name=app-url]').attr("content") + "/school/delete/"+id+"";
    $('.btn-supprimer').click(function() {
        var ligneEleve = $(this).closest('tr');
        var numLigne = ligneEleve.find('td:first').text();

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        
        swalWithBootstrapButtons.fire({
            title: 'Attention !!!',
            text: "Êtes-vous sûr de vouloir supprimer cet élève ?",
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
                            ligneEleve.remove();
                            // reorganise
                            let nbre_ligne = parseInt($('#datatable-buttons tr').length) - 1;
                            for (var i = 0; i <= (nbre_ligne - 1); i++) {
                                var cellule = table.cell(i, 0);
                                cellule.data(i + 1).draw();
                            }
                            swalWithBootstrapButtons.fire(
                                'Suppression réussir',
                                'L\'élève a été supprimer',
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
    });
    // $('#datatable-buttons tbody').on('click', 'tr', function () {
    //     var table = $('#customers2').DataTable();
    //     let id_ligne = table.row(this).index();
    //     alert(id_ligne);
    // //     $("#mb-remove-row").show();
    // //     destroye_status();
    // //     $("#id_univers").val('' + id);
    // //     $("#id_ligne").val('' + id_ligne);

    // });

}







































/*************  SELECTIONNER LES DONNEES D'UNE UNIVERSITE ***********/

function select_data(id_univers) {
    $("#wait").html('Patientez pendant le chargement ...');

    // send requete
    $.ajax({
        type: "GET",
        url: baseRul + "university/oneUniversity/" + id_univers,
        cache: false,
        success: function (data) {

            $("#mb-remove-row").hide();
            destroye_status();
            destroye();

            $("#wait").html('Première opération Terminer');

            $("#modal_basic_edit").modal('show', true);

            $("#id_univers_ligne").val(id_univers);

            $('#nom_eta').val(data.name);
            $('#nom_resp').val(data.manager);
            $('#slogan').val(data.slogan);
            $('#lieu').val(data.location);
            $('#date_crea').val(data.creation_date);
            $('#aprobation').val(data.approval);
            $('#name_image').val(data.logo);
            $('#url_site').val(data.url_site);
            $('#signataire2').val(data.signataire2);
            $('#signataire1').val(data.signataire1);
        },
        error: function (data) {
            $("#wait").html('Une Erreur pendant le processus');
        }
    });

}

/*************  ACTIVER UNE UNIVERSITER ***********/
function activer(id_univers) {

    $('#customers2 tbody').on('click', 'tr', function () {

        var table = $('#customers2').DataTable();

        let id_ligne = table.row(this).index();

        $("#mb-active-desactive").show();
        destroye();
        //destroye_reset();
        $("#id_univers").val('' + id_univers);
        $("#id_ligne").val('' + id_ligne);
        $("#operation").html(" Activer ");
        $("#choice").val("activer");

    });
}

/*************  DESACTIVER UNE UNIVERSITER ***********/
function desactiver(id_univers) {

    $('#customers2 tbody').on('click', 'tr', function () {

        var table = $('#customers2').DataTable();

        let id_ligne = table.row(this).index();

        $("#mb-active-desactive").show();
        destroye();
        //destroye_reset();
        $("#id_univers").val('' + id_univers);
        $("#id_ligne").val('' + id_ligne);
        $("#operation").html(" Desactiver ");
        $("#choice").val("desactiver");

    });
}

/************* CONFIRM ACTIVE AND DESACTIVER UNIVERSITY ***********/
function confirm_active_desactive() {
    let choice = $("#choice").val();
    let id_univers = $("#id_univers").val();
    let id_ligne = $("#id_ligne").val();


    let url = "";

    if (choice == "activer") {
        url = baseRul + "university/active_univers/" + id_univers + "";
    } else if (choice == "desactiver") {
        url = baseRul + "university/desactive_univers/" + id_univers + "";
    }

    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function (data) {
            $(".datatable").DataTable().destroy();
            liste_univers();
            destroye_status();
        },
        error: function (data) {
            $("#wait").html('Une erreur pendant le processus');
        }
    });
}

/*************  SHOW UNIVERSITY ***********/
function show_row(id_univers) {
    $("#wait").html('Patientez pendant le chargement ...');
    $("#bloc-logo").html("");
    $("#url_site_show").html("");
    // send requete
    $.ajax({
        type: "GET",
        url: baseRul + "university/oneUniversity/" + id_univers,
        cache: false,
        success: function (data) {

            $("#mb-remove-row").hide();
            destroye_status();
            //destroye_reset();

            $("#modal_basic_show").modal('show', true);
            $("#bloc-logo").append('<img src="Documents/logoUniversity/' + data.logo + '" width="150px" alt=" Logo de ' + data.name + '">')
            $("#name_show").html('' + data.name);
            $("#manager_show").html('' + data.manager);
            $("#slogan_show").html('' + data.slogan);
            $("#location_show").html('' + data.location);
            $("#creation_date_show").html('' + data.creation_date);
            $("#approval_show").html('' + data.approval);
            $("#signataire1_show").html('' + data.signataire1);
            $("#signataire2_show").html('' + data.signataire2);
            $("#url_site_show").append('<a href="' + data.url_site + '">' + data.url_site + '</a>');
            //$("#id_univers_edit").val(id_user);
        },
        error: function (data) {
            $("#wait").html('Une Erreur pendant le processus');
        }
    });
}

/*************  RESET FORM ***********/

function reset_form() {
    $('#nom_eta').val("");
    $('#nom_resp').val("");
    $('#slogan').val("");
    $('#lieu').val("");
    $('#date_crea').val("");
    $('#aprobation').val("");
    $('#name_image').val("");
    $('#url_site').val("http://");
    $('#signataire2').val("");
    $('#signataire1').val("");
}

/*************  EDIT UNIVERSITY ***********/
function update_univers() {
    event.preventDefault();

    let url = baseRul + "university/updateUnivers/" + $('#id_univers_ligne').val();

    $('#btn-log').prop('disabled', true);
    $("#success").hide();
    $("#errors").hide();


    const photo = document.getElementById("logo").files[0];
    const formData = new FormData();

    var nom_eta = $('#nom_eta').val();
    var nom_resp = $('#nom_resp').val();
    var slogan = $('#slogan').val();
    var lieu = $('#lieu').val();
    var date_crea = $('#date_crea').val();
    var aprobation = $('#aprobation').val();
    var logo = $('#logo').val();
    var url_site = $('#url_site').val();
    var signataire2 = $('#signataire2').val();
    var signataire1 = $('#signataire1').val();
    var user_id = localStorage.getItem('id_user');


    if (nom_eta == "" || nom_resp == "" || slogan == "" || lieu == "" || date_crea == "" || aprobation == "" || url_site == "" || signataire2 == "" || signataire1 == "") {
        $("#msgError").html('Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires');
        $("#errors").show();
        $("#success").hide();
        $('#btn-log').prop('disabled', false);

    } else {
        // add data form
        if (logo != '') {
            formData.append("logo", photo);
            formData.append("change_photo", true);
        } else {
            const file = new File([0], baseRes + 'logoUniversity/' + $('#name_image').val(), {
                type: "text/plain",
            });

            formData.append("logo", file);
            formData.append("change_photo", false);
        }
        formData.append("nom_eta", nom_eta);
        formData.append("nom_resp", nom_resp);
        formData.append("slogan", slogan);
        formData.append("lieu", lieu);
        formData.append("date_crea", date_crea);
        formData.append("aprobation", aprobation);
        formData.append("url_site", url_site);
        formData.append("signataire2", signataire2);
        formData.append("signataire1", signataire1);
        formData.append("user_id", user_id);

        $.ajax({
            url: url,
            type: "PUT",
            contentType: false,
            processData: false,
            timeout: 600000,
            data: formData,
            enctype: 'multipart/form-data',
            cache: false,
            success: function (data) {
                if (data.success == true) {
                    $("#msgSuc").html(data.msg);
                    $("#success").show();
                    $("#errors").hide();
                    // notification du dataTable
                    $(".datatable").DataTable().destroy();
                    liste_univers();
                    reset_form();
                    $('#btn-log').prop('disabled', false);
                    $("#modal_basic_edit").modal('hide', true);
                } else {
                    $("#msgError").html(data.msg);
                    $("#errors").show();
                    $("#success").hide();
                }
                $('#btn-log').prop('disabled', false);
            },
            error: function (data) {
                console.log(data.responseJSON);
                $("#msgError").html('Oousp La connexion au serveur a été perdue');
                $("#errors").show();
                $("#success").hide();
                $('#btn-log').prop('disabled', false);
            }
        });
    }

};
