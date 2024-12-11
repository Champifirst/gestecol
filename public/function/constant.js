BASE_URL = "http://localhost:8080/";

getAllStudentSchool();

function getAllStudentSchool(){

let url = $('meta[name=app-url]').attr("content") + "/student/AllStudentSchool/1";

$.ajax({
    url: url,
    method: "GET",
    dataType: 'json',
    headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
    success: function(json){
        let data = json.data;
        console.log(json);

        let option = '<option value="0">--- RECHERCHER UN ELEVE PAR LE NOM, PRENOM, MATRICULE ---</option>';
        for (let i = 0; i < data.length; i++) {
            option += '<option value="'+data[i].student_id+'">'+data[i].matricule.toUpperCase()+' : '+data[i].name+' '+ data[i].surname +'</option>';
        }
        $("#search_all_student_school").append(option);
        
        Swal.close();
    },
    error: function(response){
        toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
        Swal.close();
    }
});

}

function getShowStudentChoix(){
    let student_id = $("#search_all_student_school").val();
    //window.location.href = "Profile_student/"+student_id;
    const newUrl = BASE_URL+'Profile_student/'+student_id;
    window.location.href = newUrl;
}