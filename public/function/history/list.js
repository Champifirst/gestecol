/*************  LISTE HISTORY ***********/
$(document).ready(function () {
    liste_history();
});
function liste_history() {
    let url = $('meta[name=app-url]').attr("content") + "/history/list";

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
            if (json !== undefined) {
                var tableau = new Array(json.length);
                for (var i = 0; i < json.length; i++) {
                    tableau[i] = new Array(6);
                    tableau[i][0] = (i + 1);
                    tableau[i][1] = (json[i].date_heure);
                    tableau[i][2] = (json[i].action);
                    tableau[i][3] = (json[i].entiter);
                    tableau[i][4] = (json[i].status);
                    tableau[i][5] = (json[i].client);
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
            }else{
                toastr["error"]("Désoler nous n'avons pas pu trouver vos historique", "Erreur");
            }
        },
        error: function(response){
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}