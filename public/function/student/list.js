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

/******* SELECT DATA ******/
function select_data(id_student, id_session, id_cycle, id_class, id_school, ligne){
    let url = $('meta[name=app-url]').attr("content") +"/student/getOne/"+id_student+"/"+id_session+"/"+id_cycle+"/"+id_class+"/"+id_school+"";
    let timerInterval;
    const swalloptions = {
        title: 'Veuillez patienter !',
        html: `Terminer dans quelques instant.`,
        timerProgressBar: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }
    const swalPromise = Swal.fire(swalloptions);
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function (json) {
            if (json.success == true) {
                let data = json.data;
                
                $('#ligne_update').val(ligne);
                $('#idParent').val(data.id_parent);
                $('#idStudent').val(data.student_id);
                $('#name').val(data.name.toUpperCase());
                $("#surName").val(data.surname.toUpperCase());
                //--
                $("#date").val(data.place_birth);
                $("#placeBirth").val(data.date_birth.toUpperCase());
                //--
                $("#sexe").html("");
                $("#name_school_edit").html("");
                $('#name_session_edit').html("");
                $('#cycle_edit').html("");
                $('#classe_edit').html("");
                $("#image-profil").html("");

                let option_sexe = '';
                let selected_m  = '';
                let selected_f  = '';
                if (data.sexe == "M") {
                    selected_m = "selected";
                }else if (data.sexe == "F") {
                    selected_f = "selected";
                }
                option_sexe += '<option value="M" '+selected_m+'>Masculin</option>';
                option_sexe += '<option value="F" '+selected_f+'>Féminin</option>';
                $("#sexe").append(option_sexe);
                //--    
                //--
                let img = '<img src="'+BASE_URL+'/photoStudent/'+data.picture+'" alt="Photo d\'étudiant" width="100px" title="Photo d\'étudiant">';
                $("#image-profil").append(img);
                //--
                $("#nameParent").val(data.name_parent.toUpperCase());
                $("#surnameParent").val(data.surname_parent.toUpperCase());
                $("#email_parent").val(data.email_parent);
                $("#profession").val(data.parent_occupation);
                $("#phone").val(data.tel_parent);
                $("#adresse_parent").val(data.adresse_parent);
                //-- school
                let id_school = data.id_school;
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
                        let option = '<option value="0">Selectionner une école</option>';
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
                let id_session = data.id_session;
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
                let id_cycle = data.id_cycle;
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
                            $('#cycle_edit').append(option);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseJSON);
                        $('#btn-log').prop('disabled', false);
                        toastr["error"]("Nous n'avons pas pu récupérer la liste des cycle", "Erreur");
                    }
                });
                
                //-- class
                let id_class = data.id_class;
                let url5 = $('meta[name=app-url]').attr("content") + "/class/all/"+data.id_school+"/"+data.id_session+"/"+data.id_cycle;

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
                            $('#classe_edit').append(option);
                        }
                    },
                    error: function (data) {
                        console.log(data.responseJSON);
                        $('#btn-log').prop('disabled', false);
                        toastr["error"]("Nous n'avons pas pu récupérer la liste des classes", "Erreur");
                    }
                });
                
                
                $("#bs-example-modal-lg").modal("show", true);
            }else if((json.success == false)){
                toastr["error"](json.msg, "Erreur");
            }
            Swal.close();
        },
        error: function (data) {
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            Swal.close();
        }
    });
    swalPromise.then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer');
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
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');
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
    $('#cycle_edit').html("");
    $('#cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');

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
    $('#cycle_edit').html("");
    $('#cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');
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
    $('#cycle_edit').html("");
    $('#cycle_edit').append('<option value="0">Selectionner un cycle</option>');
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');

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
                $('#cycle_edit').append(option);
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
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');
    var session = $('#name_session_edit').val();
    var cycle = $('#cycle_edit').val();
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
    $('#classe_edit').html("");
    $('#classe_edit').append('<option value="0">Selectionner une classe</option>');

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
                $('#classe_edit').append(option);
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            $('#btn-log').prop('disabled', false);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}

/*****  RESET FORM *****/

function reset_form() {
    $('#name').val("");
    $('#surName').val("");
    $('#date').val("");
    $('#placeBirth').val("");
    $('#image-profil').html("");
    $('#nameParent').val("");
    $('#surnameParent').val("");
    $('#email_parent').val("");
    $('#profession').val("");
    $('#phone').val("");
    $('#adresse_parent').val("");
    // logo
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}

/*****  UPDATE DATA *****/
$('#from_student_update').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/student/update";

    $('#btn-log-update').prop('disabled', true);

    const photo = document.getElementById("logo").files[0];
    const formData = new FormData();

    var ligne_update = $('#ligne_update').val();
    var idParent = $('#idParent').val();
    var idStudent = $('#idStudent').val();

    var name = $('#name').val();
    var surName = $('#surName').val();
    var date = $('#date').val();
    var placeBirth = $('#placeBirth').val();
    var sexe = $('#sexe').val();
    var nameParent = $('#nameParent').val();
    var surnameParent = $('#surnameParent').val();
    var email_parent = $('#email_parent').val();
    var profession = $('#profession').val();
    var phone = $('#phone').val();
    var adresse_parent = $('#adresse_parent').val();
    var name_school = $('#name_school_edit').val();
    var session = $('#name_session_edit').val();
    var cycle = $('#cycle_edit').val();
    var classe = $('#classe_edit').val();
    var user_id = localStorage.getItem('id_user');
    
    if (name == "" || date == "" || placeBirth == "" || sexe == "0" || nameParent == "" || phone == "" || name_school == "0" || session == "0" || cycle == "0" || classe == "0" ) {
        
        $('#btn-log-update').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        // add data form
        if (photo) {
            if (photo instanceof File) {
                const extensionsValides = [".jpg", ".jpeg", ".png"];
                const extension = photo.name.substring(photo.name.lastIndexOf(".")).toLowerCase();
                if (extensionsValides.includes(extension)) {
                    formData.append("logo", photo);
                }else{
                    toastr["error"]("S'il vous plaît, veuillez sélectionner un fichier image valide.", "Erreur");
                }
            }
        }
        formData.append("name", name);
        formData.append("surName", surName);
        formData.append("date", date);
        formData.append("placeBirth", placeBirth);
        formData.append("sexe", sexe);
        formData.append("nameParent", nameParent);
        formData.append("surnameParent", surnameParent);
        formData.append("email_parent", email_parent);
        formData.append("profession", profession);
        formData.append("phone", phone);
        formData.append("adresse_parent", adresse_parent);
        formData.append("name_school", name_school);
        formData.append("session", session);
        formData.append("cycle", cycle);
        formData.append("classe", classe);
        formData.append("user_id", user_id);

        formData.append("idStudent", idStudent);
        formData.append("idParent", idParent);

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
                    $("#bs-example-modal-lg").modal("hide");
                    let student = data.data;
                    //
                    var table = $('#datatable-buttons').DataTable();

                    let rowData = table.row(ligne_update).data();
                    let img = '<img src="'+BASE_URL+'/photoStudent/' + student.photo + '" width="100px" alt="">';
                    rowData[1] = img;
                    rowData[2] = student.matricule;
                    rowData[3] = student.name.toUpperCase()+' '+student.surname.toUpperCase();
                    rowData[4] = student.sexe;
                    rowData[5] = student.date_birth;
                    rowData[6] = student.place_birth;
                    rowData[7] = student.name_parent.toUpperCase()+' '+student.surname_parent.toUpperCase();
                    rowData[8] = student.tel_parent;

                    table.row(ligne_update).data(rowData).draw();
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

/***** DELETE STUDENT *******/
function delete_row(id, ligne){
    let url = $('meta[name=app-url]').attr("content") + "/student/delete/"+id+"";
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
        text: "Êtes-vous sûr de vouloir supprimer cette élève ?",
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
    $("#printf").html("");

    if (name_school == "0" || name_session == "0" || name_cycle == "0" || name_classe == "0" || user_id == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Informations Invalides, il se pourrait que vous n\'avez pas tout renseigner les champs obligatoires", "Erreur");
    } else {
        let timerInterval;
        const swalloptions = {
            title: 'Veuillez patienter !',
            html: `Terminer dans quelques instant.`,
            timerProgressBar: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }
        const swalPromise = Swal.fire(swalloptions);

        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                console.log(json);
                var tableau = new Array(json.length);
                for (var i = 0; i < json.length; i++) {
                    let modifier = '<button class="btn btn-success btn-rounded btn-sm btn-select" onClick="select_data(' + json[i].student_id + ',' +name_session+ ',' +name_cycle+ ',' +name_classe+ ',' +name_school+ ','+i+')"><span class="fa fa-pencil"> Modifier</span></button> ';
                    let certificat = '<button class="btn btn-info btn-rounded btn-sm btn-select" onClick="certificatOne(' + json[i].student_id +','+name_school+','+name_session+','+name_cycle+','+name_classe+')">Certificat</button> ';
                    let bulletin = '<button class="btn btn-dark btn-rounded btn-sm btn-select" onClick="bulletin(' + json[i].student_id + ','+i+')">Bulletin</button> ';
                    let recu = '<button class="btn btn-warning btn-rounded btn-sm btn-select" onClick="recu(' + json[i].student_id + ','+i+')">Recu</button> ';
                    // let carte = '<button class="btn btn-info btn-rounded btn-sm btn-select" onClick="carteOne(' + json[i].student_id +','+name_school+','+name_session+','+name_cycle+','+name_classe+')">Carte</button> ';
                    let carte = '<button class="btn btn-info btn-rounded btn-sm btn-select" diseable>Carte</button> ';
                    let photo = '<button class="btn btn-success btn-rounded btn-sm btn-select" onClick="photo(' + json[i].student_id + ','+i+')">4X4</button> ';
                    let supprimer = '';
                    if (type_user == "admin") {
                        supprimer = '<button class="btn btn-danger btn-rounded btn-sm btn-supprimer" onClick="delete_row(' + json[i].student_id + ','+(i)+');"><span class="fa fa-times"></span></button>';
                    }
    
                    tableau[i] = new Array(11);
                    tableau[i][0] = (i + 1);
                    tableau[i][1] = ('<img src="'+BASE_URL+'/photoStudent/' + json[i].photo + '" width="100px" alt="">');
                    tableau[i][2] = (json[i].matricule.toUpperCase());
                    tableau[i][3] = (json[i].name.toUpperCase()+' '+json[i].surname.toUpperCase());
                    tableau[i][4] = (json[i].sexe.toUpperCase());
                    tableau[i][5] = (json[i].date_of_birth);
                    tableau[i][6] = (json[i].birth_place);
                    tableau[i][7] = (json[i].name_parent.toUpperCase()+' '+json[i].surnameParent.toUpperCase());
                    tableau[i][8] = (json[i].contactParent);
                    tableau[i][9] = (json[i].redouble.toUpperCase());
                    tableau[i][10] = (certificat+' '+bulletin+' '+modifier+' '+carte+' '+supprimer);
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
                                url: BASE_URL+"components/src/json/fr-FR.json"
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
                    // show print
                    let bloc = '<div class="mt-4 btn-group mr-2 ml-2 text-center">'+
                    '<!-- btn impression -->'+
                    '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="getPrintList('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Liste des élèves</button>'+
                    '<button type="button" class="mr-1 btn btn-danger btn-sm mb-4" onclick="getPrintListRedouble('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Liste des rédoublant</button>'+
                    '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="getPrintListNew('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Nouveaux élèves</button>'+
                    '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="getPrintFichePress('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Fiche de présence</button>'+
                    '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4" onclick="getPrintFicheDecharge('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Fiche de décharge</button>'+
                    '<button type="button" class="mr-1 btn btn-info btn-sm mb-4" onclick="getPrintFicheInscrit('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Elèves Inscrit</button>'+
                    '<button type="button" class="mr-1 btn btn-secondary btn-sm mb-4" onclick="getPrintFicheNotInscrit('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Elèves non Inscrit</button>'+
                    '<button type="button" class="mr-1 btn btn-success btn-sm mb-4" onclick="getPrintFicheCertificat('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print" ></i> Certificat de scolarité</button>'+
                    '<button type="button" class="mr-1 btn btn-warning btn-sm mb-4 text-white" onclick="getBordereaux('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print" ></i> Bordereaux de notes</button>'+
                    // '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4"  onclick="getPrintFicheCarte('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Carte scolaire</button>'+
                    '<button type="button" class="mr-1 btn btn-primary btn-sm mb-4"><i class="fa fa-print"></i> Carte scolaire</button>'+
                    '<button type="button" class="mr-1 btn btn-dark btn-sm mb-4" onclick="getAllBulletin('+name_school+','+name_session+','+name_cycle+','+name_classe+')"><i class="fa fa-print"></i> Bulletin de note</button>'+
                    '</div>';
                    $("#printf").append(bloc);
                }
                Swal.close();
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
                Swal.close();
            }
        });
        swalPromise.then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer');
            }
        });
    }
}

function getPrintList(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintListClass/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintListRedouble(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintListClassRedouble/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintListNew(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintListClassNew/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintFichePress(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFichePress/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintFicheDecharge(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFicheDecharge/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintFicheInscrit(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFicheInscription/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintFicheNotInscrit(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFicheNotInscription/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function getPrintFicheCertificat(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFicheCertificat/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function certificatOne(id_student, name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintFicheCertificatOne/"+id_student+"/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;
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

function getPrintFicheCarte(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintCarteScolaire/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

function carteOne(id_student, name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintOneCarteScolaire/"+id_student+"/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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



function getBordereaux(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintAllBordereaux/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

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

getAllBulletin

function getAllBulletin(name_school, name_session, name_cycle, name_classe){
    let url = $('meta[name=app-url]').attr("content") + "/student/PrintAllBulletin/"+name_school+"/"+name_session+"/"+name_cycle+"/"+name_classe;

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(json){
            console.log(json);
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

