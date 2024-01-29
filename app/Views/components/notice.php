<!-- preloader -->
<div id="preoloder_notice">
    <div id="loading" style="height: 100px">
        <div class="d-flex justify-content-center align-items-center">
            <div class="prifix_loading_box"> <span></span> <span></span> <span></span> <span></span> <span></span> </div>
        </div>
    </div>
</div>


<!-- top tiles -->
<div id="contain_notice">
    
</div>
<!-- /top tiles -->

<script>
    getYear();
    function getYear(){
        let url = $('meta[name=app-url]').attr("content") + "/years/actif";
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                if (json !== undefined) {
                    if (json.success == false) {
                        toastr["error"](json.msg, "Erreur");
                    }else{
                        let notice = '<div class="row" style="display: flex; justify-content: center; border: solid 2px #73879c" >'+
                        '<div class="tile_count">'+
                            '<div class="col-md-12 tile_stats_count">'+
                                '<center><span class="count_top text-danger font-weight-bold" style="font-size: 1em;"><i class="fa fa-warning"></i> Cette notice n\'est pas à négliger, toute opération que vous effectuer <br> sont pour le compte de cette année scolaire.</span></center>'+
                                '<center><div class="count" style="font-size: 30px" id="annee"> </div></center>'+
                                '<center><span class="text-success font-weight-bold" style="font-size: 1.2em;"> <b id="etablissement"></b> </span></center>'+
                            '</div>'+
                        '</div>'+
                        '</div>';

                        $("#contain_notice").append(notice);
                        $("#annee").html(json.data.name_year.toUpperCase());
                        // charger la div et faire disparaitre le preoloder
                        $("#preoloder_notice").hide();
                    }
                }else{
                    toastr["warning"]("Veuillez vous reconnecter", "Alerte");
                }
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }

    if (localStorage.getItem('id_school') != null) {
        let id_school = localStorage.getItem('id_school');
        getSchoolSelect(id_school);
    }

    function getSchoolSelect(id_school){
        let url = $('meta[name=app-url]').attr("content") + "/school/select/"+id_school+"";
        $.ajax({
            url: url,
            method: "GET",
            dataType: 'json',
            headers: {"Authorization": "Bearer " +localStorage.getItem('token')},
            success: function(json){
                if (json !== undefined) {
                    if (json.success == false) {
                        toastr[json.code](json.msg, "Erreur");
                    }else{
                        $("#etablissement").html("ETABLISSEMENT: "+json.data.name.toUpperCase());
                    }
                }else{
                    toastr["warning"]("Veuillez vous reconnecter", "Alerte");
                }
            },
            error: function(response){
                toastr["error"]("Oousp La connexion au serveur a été perdu", "Erreur");
            }
        });
    }
</script>