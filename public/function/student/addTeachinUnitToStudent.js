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


/*****  LIST STUDENT *****/
function getClass(){

    $('#btn-log').prop('disabled', true);

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var user_id = $('#user_id').val();
    let url = $('meta[name=app-url]').attr("content") + "/student/AllStudent/"+name_classe+"";
    $("#bloc_btn").html("");

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        window.studentSubjects = {
            initializeSelectors: function() {
                const selectors = document.querySelectorAll('.subject-select');
                
                selectors.forEach(selector => {
                    const studentId = selector.dataset.studentId;
                    selector.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const subjectId = this.value;
                        const subjectName = selectedOption.text;
                        
                        if (subjectId && !studentSubjects.isSubjectSelected(studentId, subjectId)) {
                            studentSubjects.addSubjectTag(studentId, subjectId, subjectName);
                            studentSubjects.updateSubjectCount(studentId);
                        }
                        
                        this.value = '';
                    });
                });
            },

            isSubjectSelected: function(studentId, subjectId) {
                const container = document.querySelector(`.visualisation-container-${studentId} .selected-subjects`);
                return container.querySelector(`.subject-tag[data-subject-id="${subjectId}"]`) !== null;
            },

            addSubjectTag: function(studentId, subjectId, subjectName) {
                const container = document.querySelector(`.visualisation-container-${studentId} .selected-subjects`);
                const tag = document.createElement('div');
                tag.className = 'subject-tag';
                tag.dataset.subjectId = subjectId;
                tag.innerHTML = `
                    ${subjectName}
                    <button type="button" class="remove-btn" onclick="studentSubjects.removeSubject(${studentId}, '${subjectId}')">&times;</button>
                    <input type="hidden" name="subjects[${studentId}][]" value="${subjectId}">
                    <input type="hidden" name="matiereStudent[${studentId}][]" value="${subjectName}">
                `;
                container.appendChild(tag);
            },

            removeSubject: function(studentId, subjectId) {
                const container = document.querySelector(`.visualisation-container-${studentId} .selected-subjects`);
                const tag = container.querySelector(`.subject-tag[data-subject-id="${subjectId}"]`);
                if (tag) {
                    tag.remove();
                    studentSubjects.updateSubjectCount(studentId);
                }
            },

            updateSubjectCount: function(studentId) {
                const container = document.querySelector(`.visualisation-container-${studentId} .selected-subjects`);
                const countElement = document.querySelector(`.visualisation-container-${studentId} .subject-count`);
                const count = container.querySelectorAll('.subject-tag').length;
                countElement.textContent = count;
            }
        };

        $.ajax({
            url: $('meta[name=app-url]').attr("content") + "/teaching_unit/all/"+name_classe+"",
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(matieres){
                $.ajax({
                    url: url,
                    method: "GET",
                    dataType: 'json',
                    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
                    success: function(json){
                        console.log(json);
                        var tableau = new Array(json.length);
                        
                        for (var i = 0; i < json.length; i++) {
                            let option = '<option value="0">Toute les matieres</option>';
                            if(matieres !== undefined){
                                for (let j = 0; j < matieres.length; j++) {
                                    option += '<option value="'+matieres[j].teachingunit_id+'">'+matieres[j].name.toUpperCase()+'</option>';
                                }
                            }

                            let visualisation = `<div class="visualisation-container-${json[i].student_id}">
                                                    <strong class="mb-2">Matières sélectionnées (<span class="subject-count">0</span>)</strong>
                                                    <div class="selected-subjects d-flex flex-wrap"></div>
                                                </div>
                                                <input type="number" name="student_id[]" value="${json[i].student_id}" hidden>`;

                            let select_matiere = `<select class="form-select subject-select" data-student-id="${json[i].student_id}">
                                                    <option value="" selected disabled>Sélectionnez une matière</option>
                                                    ${matieres.map(matiere => 
                                                        `<option value="${matiere.teachingunit_id}">${matiere.name.toUpperCase()}</option>`
                                                    ).join('')}
                                                         
                                                    </select>`;
                
                            tableau[i] = new Array(7);
                            tableau[i][0] = (i + 1);
                            tableau[i][1] = ('<img src="../photoStudent/' + json[i].photo + '" width="100px" alt="">');
                            tableau[i][2] = (json[i].matricule.toUpperCase());
                            tableau[i][3] = (json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase());
                            tableau[i][4] = (json[i].sexe.toUpperCase());
                            tableau[i][5] = (visualisation);
                            tableau[i][6] = (select_matiere);
                            
                        }
            
                        $('#datatable-buttons').DataTable().destroy();
                        var handleDataTableButtons = function () {
                            if ($("#datatable-buttons").length) {
                                $("#datatable-buttons").DataTable({
                                    dom: "Blfrtip",
                                    buttons: [],
                                    responsive: true,
                                    aaData: tableau,
                                    "scrollCollapse": true,
                                    autoFill: true,
                                    language: {
                                        url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
                                    },
                                    "drawCallback": function( settings ) {
                                        // Initialiser les sélecteurs après le rendu du tableau
                                        studentSubjects.initializeSelectors();
                                    }
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
                            let btn_submit = '<div class="form-group text-center">'+
                            '<div class="col-md-6 offset-md-3 mt-4">'+
                                '<button type="reset" class="btn btn-danger">Annuler</button>'+
                                '<button type="submit" class="btn btn-success" id="btn-log">Enregistrer</button>'+
                            '</div>'+
                            '</div>';
                            $("#bloc_btn").append(btn_submit);
                            toastr["success"]("Opération réussir", "Réussite");
                        }
                    },
                    error: function(response){
                        toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
                    }
                });
            },
            error: function(response){
                toastr["error"]("Oousp nous n'avons pas pu recuperer la liste des matieres", "Erreur");
                return [];
            }
        });
    }
}


/*****  PHOTOS *****/
$('#from_matiere').on('submit', function (e) {
    e.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/insertMatieres";

    $('#btn-log').prop('disabled', true);

    var name_school = $('#name_school').val();
    var name_session = $('#name_session').val();
    var name_cycle = $('#name_cycle').val();
    var name_classe = $('#name_classe').val();
    var user_id = localStorage.getItem('id_user');

    if (name_school == "0" && name_session == "0" && name_session == null && name_school == null && name_cycle == "0" && name_cycle == null && name_classe == "0" && name_classe == null ) {
        $('#btn-log').prop('disabled', false);
        if (name_school == "0" || name_school == null) {
            toastr["error"]("Selectionnez l'établissement ", "Attention");
        }else if (name_session == "0" || name_session == null) {
            toastr["error"]("Selectionnez la session ", "Attention");
        }else if(name_cycle == "0" || name_cycle == null){
            toastr["error"]("Selectionnez le cycle ", "Attention");
        }else if (name_classe == "0" || name_classe == null) {
            toastr["error"]("Selectionnez une classe ", "Attention");
        }
    } else {
        // add data form
        var data_form = $('#from_photo')[0];
        const formData = new FormData(this);
        formData.append("user_id", user_id);

        console.log(formData);

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
                    window.location.href="GiveMatiere";
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



