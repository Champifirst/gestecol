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

/*****  LIST HISTORIQUE DE PAYEMENT *****/
function charger_historique_teachear(){

    $('#btn-log').prop('disabled', true);

    var name_school = $('#name_school').val();
    var type_ens = $('#type_ens').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/teacher/all/"+name_school+"/"+type_ens+"";
    $("#printf").html("");

    if (name_school == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(response){
                let json = response.data;
                console.log(json);
                var tableau = new Array(json.length);
                for (var i = 0; i < json.length; i++) {
                    let modifier = '<button class="btn btn-default btn-rounded btn-sm btn-select" onClick="select_data(' + json[i].teacher_id + ','+i+')"><span class="fa fa-pencil"></span></button> ';
                    let contrat = '<button class="btn btn-info btn-rounded btn-sm btn-select" onClick="contrat(' + json[i].teacher_id + ',\''+type_ens+'\','+name_school+')">Contrat</button> ';
                    let fiche_paie = '<button class="btn btn-dark btn-rounded btn-sm btn-select" onClick="fiche_paie(' + json[i].teacher_id + ','+i+')">Fiche paie</button> ';
                    let afficher = '<button class="btn btn-warning btn-rounded btn-sm btn-select" onClick="afficher(' + json[i].teacher_id + ','+i+')">Afficher</button> ';
                    let supprimer = '';
                    if (type_user == "admin") {
                        supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + json[i].teacher_id + ','+(i)+');"><span class="fa fa-times"></span></button>';
                    }
    
                    tableau[i] = new Array(10);
                    tableau[i][0] = (i + 1);
                    tableau[i][1] = ('<img src="../photoTeacher/' + json[i].photo + '" width="100px" alt="">');
                    tableau[i][2] = (json[i].matricule.toUpperCase());
                    tableau[i][3] = (json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase());
                    tableau[i][4] = (json[i].diplome.toUpperCase());
                    tableau[i][5] = (json[i].contact);
                    tableau[i][6] = (json[i].type_ens);
                    tableau[i][7] = (json[i].salaire);
                    tableau[i][8] = (json[i].classe);
                    tableau[i][9] = (contrat+' '+fiche_paie+' '+afficher+' '+modifier+' '+supprimer);
                }
    
                $('#datatable-buttons').DataTable().destroy();
    
                var handleDataTableButtons = function () {
                    if ($("#datatable-buttons").length) {
                        $("#datatable-buttons").DataTable({
                            dom: "Blfrtip",
                            buttons: [
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

                if (json.length != 0) {
                    if (type_ens == "enseignant") {
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListTeacher('+name_school+')"><i class="fa fa-print"></i> Liste des enseignants</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListTeacherVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListTeacherPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContrat('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaie('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDecharge('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }else if (type_ens == "chauffeur") {
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListChauffeur('+name_school+')"><i class="fa fa-print"></i> Liste des chauffeurs</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListChauffeurVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListChauffeurPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContratChauffeur('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaieChauffeur('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDechargeChauffeur('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }else if (type_ens == "gardien") {
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListGardien('+name_school+')"><i class="fa fa-print"></i> Liste des gardiens</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListGardienVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListGardienPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContratGardien('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaieGardien('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDechargeGardien('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }else if (type_ens == "directeur") {
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListDirecteur('+name_school+')"><i class="fa fa-print"></i> Liste des directeurs</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListDirecteurVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListDirecteurPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContratDirecteur('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaieDirecteur('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDechargeDirecteur('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }else if(type_ens == "entretien"){
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListEntretient('+name_school+')"><i class="fa fa-print"></i> Liste des agents d\'entretien</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListEntretientVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListEntretientPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContratEntretient('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaieEntretient('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDechargeEntretient('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }else if (type_ens == "0") {
                        let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                        '<!-- btn impression -->'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printListPersonnel('+name_school+')"><i class="fa fa-print"></i> Liste du personnel</button>'+
                        '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="printListPersonnelVaccataire('+name_school+')"><i class="fa fa-print"></i> Liste des vaccataires</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printListPersonnelPermanent('+name_school+')"><i class="fa fa-print"></i> Liste des permanents</button>'+
                        '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="printContratPersonnel('+name_school+')"><i class="fa fa-print"></i> Contrat de travail</button>'+
                        '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="printFichePaiePersonnel('+name_school+')"><i class="fa fa-print"></i> Fiche de paie</button>'+
                        '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="printFicheDechargePersonnel('+name_school+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                        '</div>';
                        $("#printf").append(bloc);
                    }

                    // show print
                    
                }
    
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
}

function printListTeacher(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintListSchool/"+id_school+"";

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

function printListTeacherVaccataire(id_school){
    
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintListTeacherVaccataire/"+id_school+"";

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

function printListTeacherPermanent(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintListTeacherPermanent/"+id_school+"";

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

function printContrat(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintContrat/"+id_school+"";

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

function printFicheDecharge(id_school){
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintFicheDecharge/"+id_school+"";

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

function printFichePaie(id_school){
    PrintFichePaie
    /*
    *
    *
    * 
    */
}

function contrat(teacher_id, type_ens, name_school){
    let url = $('meta[name=app-url]').attr("content") + "/teacher/PrintOneContrat/"+teacher_id+"/"+type_ens+"/"+name_school+"";

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


