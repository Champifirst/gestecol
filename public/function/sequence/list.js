
/*************  LISTE SEQUENCE ***********/
$(document).ready(function () {
    let id_school = 0;
    if (localStorage.getItem('id_school') != null) {
        id_school = localStorage.getItem('id_school');
    }
    liste_sequence(id_school);
});

function liste_sequence(id_school) {
    let url = $('meta[name=app-url]').attr("content") + "/sequence/all/"+id_school+"";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            var tableau = new Array(json.length);
            for (var i = 0; i < json.length; i++) {
                let modifier = '';
                // let modifier = '<button class="btn btn-default btn-rounded btn-sm btn-select" onClick="select_data(' + json[i].sequence_id + ','+i+')"><span class="fa fa-pencil"></span></button> ';
                let supprimer = '';
                if (type_user == "admin") {
                    supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + json[i].sequence_id + ','+(i)+');"><span class="fa fa-times"></span></button>';
                }

                tableau[i] = new Array(9);
                tableau[i][0] = (i+1);
                tableau[i][1] = (json[i].school.toUpperCase());
                tableau[i][2] = (json[i].session.toUpperCase());
                tableau[i][3] = (json[i].cycle.toUpperCase());
                tableau[i][4] = (json[i].class.toUpperCase());
                tableau[i][5] = (json[i].trimestre.toUpperCase());
                tableau[i][6] = (json[i].coded.toUpperCase());
                tableau[i][7] = (json[i].name.toUpperCase());
                tableau[i][8] = (modifier + '   ' + supprimer);
            }

            $('#datatable-buttons').DataTable().destroy();

            var handleDataTableButtons = function () {
                if ($("#datatable-buttons").length) {
                    $("#datatable-buttons").DataTable({
                        dom: "Blfrtip",
                        buttons: [
                            {
                                extend: "csv",
                                className: "btn btn-danger btn-sm mb-4"
                            },
                            {
                                extend: "excel",
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



/*************  SUPPRIMER UN TRIMESTRE ***********/
function delete_row(id, ligne) {
    let url = $('meta[name=app-url]').attr("content") + "/sequence/delete/"+id+"";
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
        text: "Êtes-vous sûr de vouloir supprimer cette séquence ?",
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
                            'Cette séquence a été supprimer',
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


/*************  ACTIVER UNE YEAR ***********/
function activer(id, ligne) {
    
    let url = $('meta[name=app-url]').attr("content") + "/years/active/"+id+"";

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    
    swalWithBootstrapButtons.fire({
        title: 'Attention !!!',
        text: "Êtes-vous sûr de vouloir activer cette année scolaire ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, activer',
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
                        liste_year()

                        swalWithBootstrapButtons.fire(
                            'Activation réussir',
                            'L\'année scolaire a été activer',
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
                'L\'opération d\'activation a été annulée.',
                'error'
            )
        }
    })
}

/************* DESACTIVER UNE UNIVERSITER ***********/
function desactiver(year_id) {
    toastr["error"]("Opération non permise, impossible de desactiver cette année scolaire", "Erreur");
}

function select_data(year_id, ligne){
    
    let url = $('meta[name=app-url]').attr("content") + "/years/one/"+year_id+"";
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function (json) {
            if (json.success == true) {
                $('#ligne_year').val(ligne);
                $("#date_start").val(json.data.start_year);
                $("#date_end").val(json.data.end_year);
                $("#year_id").val(json.data.year_id);
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

/*****  ANNEE *****/
$('#form_update').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/years/update";
    $('#btn-log').prop('disabled', true);
    const formData = new FormData();

    var date_start = $('#date_start').val();
    var date_end = $('#date_end').val();
    var year_id = $('#year_id').val();
    var ligne_year = $('#ligne_year').val();

    if (date_start == "" || date_end == "" || year_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");

    } else {
        // add data form
        formData.append("date_start", date_start);
        formData.append("date_end", date_end);
        formData.append("year_id", year_id);

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
                    $('#date_start').val("");
                    $('#date_end').val("");
                    $('#year_id').val("");
                    $('#ligne_year').val("");
                    toastr["success"](data.msg, "Réussite");
                    $("#staticBackdrop").modal("hide");
                    let year = data.data;
                    //
                    var table = $('#datatable-buttons').DataTable();

                    let rowData = table.row(ligne_year).data();
                    rowData[1] = year.name_year.toUpperCase();
                    rowData[2] = year.start_year;
                    rowData[3] = year.end_year;

                    table.row(ligne_year).data(rowData).draw();
                    $('#btn-log').prop('disabled', false);
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


