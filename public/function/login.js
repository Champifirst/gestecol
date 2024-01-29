$("#msg").html("");
// close session
localStorage.clear();

$('#from_login').on('submit', function(e) {
    event.preventDefault();
    var formData = new FormData(this);
    let url = $('meta[name=app-url]').attr("content") + "/auth/auth-user";

    $.ajax({
        url: url,
        type: "POST",
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: function(data) { 
            if (data.success == true) {
                $("#password1").val("");
                $("#login").val("");

                let msg = '<div class="alert alert-success" style="text-align: center;" id="success">' +
                data.msg + '</div>';
                $("#msg").html("");
                $("#msg").append(msg);
                // initialiwe session
                localStorage.setItem('token', data.data.token);
                localStorage.setItem('id_user', data.data.id_user);
                localStorage.setItem('login', data.data.login);
                localStorage.setItem('type_user', data.data.type_user);
                localStorage.setItem('autorisation', data.autorisation);
                localStorage.setItem('fonctionnality', data.data.fonctionnality);
                
                if (data.data.type_user == "admin") {
                    window.location.href = "shool-choice-user";
                }else{
                    window.location.href = "Home";
                }
                
            } else {
                let msg = '<div class="alert alert-danger" style="text-align: center;" id="success">' +
                data.msg + '</div>';
                $("#msg").html("");
                $("#msg").append(msg); 
            } 
        },
        error: function(data) {
            console.log(data.responseJSON);
            let msg = '<div class="alert alert-danger" style="text-align: center;" id="success">Oousp Quelque chose a mal fonctionner</div>';
            $("#msg").html("");
            $("#msg").append(msg);
        }
    });
});