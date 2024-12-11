$('#form_save_bourse').on('submit', function (e) {
    event.preventDefault();

    let url = $('meta[name=app-url]').attr("content") + "/scolarite/Enregistrer_bourse";

    $('#btn-log').prop('disabled', true);

    var nomBourse     = $("#nomBourse").val();
    var montant_bourse = $("#montant_bourse").val();
    var description_bourse= $("#description_bourse").val();

    if (nomBourse == "" || montant_bourse == "" || description_bourse == "") {
        $('#btn-log').prop('disabled', false);
        toastr["error"]("Remplissez toutes les informations ", "Attention");
    } else {
        // add data form
        var data_form = $('#form_save_bourse')[0];
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
                    $("#nomBourse").val("");
                    $("#montant_bourse").val("");
                    $("#description_bourse").val("");
                    $("#staticBackdrop").modal('show', false);
                    listBourse();
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
listBourse();

function listBourse(){
    let url = $('meta[name=app-url]').attr("content") + "/scolarite/List_bourse";
    $("#table_body").html("");

    $.ajax({
        url: url,
        method: "GET",
        dataType: 'json',
        headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
        success: function(data){
            if (data !== undefined) {
                let ligne ='';
                for (let i = 0; i < data.length; i++) {
                    ligne += '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+data[i].name.toUpperCase()+'</td>'+
                    '<td>'+data[i].amount+' FCFA</td>'+
                    '<td> '+ data[i].description +'</td>'+
                '</tr>';
                }
                $("#table_body").append(ligne);

            }else{
                toastr["error"]("Nous n'avons pas pu recupérer la liste des bourses", "Erreur");
            }
        },
        error: function (data) {
            console.log(data.responseJSON);
            toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        }
    });
}
