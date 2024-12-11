<style>
    .disable-clic{
        pointer-events: none;
        color: #999;
        opacity: 0.7;
    }
    .hide-option{
        visibility: hidden;
    }
    .show-option{
        visibility: visible;
    }
</style>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <img src="<?= base_url() ?>/components/images/logo.png" alt="Logo devCode" style="width: 50px" class="img-circle profile_img">
        <!-- <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>ERP</span></a> -->
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <div class="profile_pic">
        <img src="<?= base_url() ?>components/images/logo.jpg" alt="Logo Etablissement" class="img-circle profile_img">
        </div>
        <div class="profile_info">
        <span>Bienvenue,</span>
        <h2 id="login"> </h2>
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
        <h3>Options</h3>
        <ul class="nav side-menu">
            <li>
                <a><i class="fa fa-home"></i> Acceuil <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                <li><a href="<?= base_url()?>/Home">Vue global</a></li>
               <!-- <li><a href="<?= base_url()?>/Home2">Etat Financier</a></li> -->
                </ul>
            </li>
            <!-- <li><a href="<?= base_url()?>/Home"><i class="fa fa-home"></i> Acceuil </a></li> -->
            
            <li class="hibeTeach"><a><i class="fa fa-users"></i> Elèves <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/student/save">Enregister</a></li>
                    <li><a href="<?= base_url()?>/student/list">Lister</a></li>
                    <li><a href="<?= base_url()?>/student/ChangePhoto">Photos</a></li>
                    <li><a href="<?= base_url()?>/student/Importer">Importer</a></li>
                    <li><a href="<?= base_url()?>/student/GiveMatiere">Attribuer les matières</a></li>
                    <li><a href="<?= base_url()?>/student/GiveBourses">Attribuer les bourses</a></li>
                </ul>
            </li>
            <li class="hibeTeach"><a><i class="fa fa-money"></i> Scolarité <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/scolarite/save_inscription">Inscription</a></li>
                    <li><a href="<?= base_url()?>/scolarite/save_pension">Pension</a></li>
                    <li><a href="<?= base_url()?>/scolarite/Montant_scolarite">Montant Scolarité</a></li>
                    <li><a href="<?= base_url()?>/scolarite/Statistique_scolarite">Statistiques</a></li>
                    <li><a href="<?= base_url()?>/scolarite/historique_paiement">Historique paiement</a></li>
                    <li><a href="<?= base_url()?>/scolarite/save_bourse">Bourses</a></li>
                </ul>
            </li>
            <li class="hibeTeach"><a><i class="fa fa-users"></i> Enseignants <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/teacher/save">Enregistrer les enseignants</a></li>
                    <li><a href="<?= base_url()?>/teacher/Importer">Importer</a></li>
                    <li><a href="<?= base_url()?>/teacher/list">Lister les enseignants</a></li>
                    <li><a href="<?= base_url()?>/teacher/giveClass">Attribuer une classe</a></li>
                    <li><a href="<?= base_url()?>/teacher/giveSubjet">Attribuer les matières</a></li>
                    <!-- <li><a href="<?= base_url()?>/teacher/salary">Salaires</a></li> -->
                    <!-- <li><a href="<?= base_url()?>/teacher/HistoriqueSalaire">Historique de paiement</a></li> -->
                    <!-- <li><a href="<?= base_url()?>/teacher/readRapport">Ecrire mon rapport</a></li> -->
                </ul>
            </li>
            <li><a><i class="fa fa-list"></i> Notes <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/note/save">Enregister les notes</a></li>
                    <li><a href="<?= base_url()?>/note/list">Lister les notes</a></li>
                    <li class="noteAccess"><a href="<?= base_url()?>/trimestre/save">Enregistrer les Trimestres</a></li>
                    <li class="noteAccess"><a href="<?= base_url()?>/trimestre/list">Lister les Trimestres</a></li>
                    <li class="noteAccess"><a href="<?= base_url()?>/sequence/save">Enregistrer les Séquences</a></li>
                    <li class="noteAccess"><a href="<?= base_url()?>/sequence/list">Lister les Séquences</a></li>
                </ul>
            </li>
            <li class="hibeTeach"><a><i class="fa fa-list"></i> Bulletin <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/note/imprimer_bulletin">Imprimer les bulletins</a></li>
                </ul>
            </li>
            <li class="hibeTeach"><a><i class="fa fa-users"></i> Matières <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/teaching_unit/save">Enregister</a></li>
                    <li><a href="<?= base_url()?>/teaching_unit/list">Lister</a></li>
                </ul>
            </li>
            <!--<li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="general_elements.html">General Elements</a></li>
                <li><a href="media_gallery.html">Media Gallery</a></li>
                <li><a href="typography.html">Typography</a></li>
                <li><a href="icons.html">Icons</a></li>
                <li><a href="glyphicons.html">Glyphicons</a></li>
                <li><a href="widgets.html">Widgets</a></li>
                <li><a href="invoice.html">Invoice</a></li>
                <li><a href="inbox.html">Inbox</a></li>
                <li><a href="calendar.html">Calendar</a></li>
            </ul>
            </li>
            <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="tables.html">Tables</a></li>
                <li><a href="tables_dynamic.html">Table Dynamic</a></li>
            </ul>
            </li>
            <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="chartjs.html">Chart JS</a></li>
                <li><a href="chartjs2.html">Chart JS2</a></li>
                <li><a href="morisjs.html">Moris JS</a></li>
                <li><a href="echarts.html">ECharts</a></li>
                <li><a href="other_charts.html">Other Charts</a></li>
            </ul>
            </li>
            <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                <li><a href="fixed_footer.html">Fixed Footer</a></li>
            </ul>
            </li> -->
        </ul>
        </div>
        <div class="menu_section">
        <h3>Configurations</h3>
        <ul class="nav side-menu">
            <li class="" id="001E"><a><i class="fa fa-bank"></i> Ecole <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/school/save">Enregistrer</a></li>
                    <li><a href="<?= base_url()?>/school/list">Lister</a></li>
                </ul>
            </li>
            <script>
                if (localStorage.getItem('type_user') == "teacher") {
                    $('#001E').hide();
                }
            </script>
            <li class="" id="001"><a><i class="fa fa-institution"></i> Classe <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/serie/save">Enregistrer les Séries</a></li>
                    <li><a href="<?= base_url()?>/session/save">Enregistrer les Sessions</a></li>
                    <li><a href="<?= base_url()?>/cycle/save">Enregistrer les Cycles</a></li>
                    <li><a href="<?= base_url()?>/class/save">Enregistrer les Classes</a></li>
                    <li><a href="<?= base_url()?>/class/list">Lister les Classes</a></li>
                </ul>
            </li>
            <li class="" id="close_year"><a><i class="fa fa-close"></i> Cloturer l'année <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/student/deliberation">Délibération</a></li>
                    <li><a href="<?= base_url()?>/student/basculeNextYear">Basculer à l'année suivante.</a></li>
                </ul>
            </li>
            <script>
                if (localStorage.getItem('type_user') == "admin" && localStorage.getItem('type_user') == "super_admin") {
                    $('#close_year').hide();
                }
            </script>
            <li class="" id="001A"><a><i class="fa fa-table"></i> Année Académique <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a id="addyear" href="<?= base_url()?>/years/save">Enregistrer</a></li>
                    <li><a href="<?= base_url()?>/years/list">Lister</a></li>
                </ul>
            </li>
            <!-- admin option -->
            <!-- <li id="users" class=""><a><i class="fa fa-ioxhost"></i> Utilisateurs <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="<?= base_url()?>/user/save">Enregistrer</a></li>
                    <li><a href="<?= base_url()?>/user/save">Lister</a></li>
                </ul>
            </li> -->

            <li><a href="<?= base_url()?>/history/listView"><i class="fa fa-history"></i> Historiques de session </a></li>
            <!--<li><a><i class="fa fa-group"></i> Employées <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="saveEnterprise.html">Enregistrer</a></li>
                <li><a href="listEnterprise.html">Listing</a></li>
            </ul>
            </li> -->
            <!-- <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="e_commerce.html">E-commerce</a></li>
                <li><a href="projects.html">Projects</a></li>
                <li><a href="project_detail.html">Project Detail</a></li>
                <li><a href="contacts.html">Contacts</a></li>
                <li><a href="profile.html">Profile</a></li>
            </ul>
            </li>
            <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="page_403.html">403 Error</a></li>
                <li><a href="page_404.html">404 Error</a></li>
                <li><a href="page_500.html">500 Error</a></li>
                <li><a href="plain_page.html">Plain Page</a></li>
                <li><a href="login.html">Login Page</a></li>
                <li><a href="pricing_tables.html">Pricing Tables</a></li>
            </ul>
            </li>
            <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="#level1_1">Level One</a>
                <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    <li class="sub_menu"><a href="level2.html">Level Two</a>
                    </li>
                    <li><a href="#level2_1">Level Two</a>
                    </li>
                    <li><a href="#level2_2">Level Two</a>
                    </li>
                    </ul>
                </li>
                <li><a href="#level1_2">Level One</a>
                </li>
            </ul>
            </li>                  
            <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li> -->
        </ul>
        </div>

    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
        <!-- <a data-toggle="tooltip" data-placement="top" title="Paramètre">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a> -->
        <a data-toggle="tooltip" data-placement="top" title="Déconnexion" href="<?= base_url() ?>log-out-user">
            <span class="fa fa-sign-out" aria-hidden="true"></span>
        </a>
    </div>
    <!-- /menu footer buttons -->
    </div>
</div>

<script>
    // initialisation de la session
    let token = localStorage.getItem('token');
    let id_user = localStorage.getItem('id_user');
    let login = localStorage.getItem('login');
    let type_user = localStorage.getItem('type_user');
    let fonctionnality = localStorage.getItem('fonctionnality');
    
    $("#login").html(localStorage.getItem('login'));
    if (type_user == "teacher"){
        $('.hibeTeach').hide(); 
        $('#001E').hide();
        $('#close_year').hide();
        $('#001A').hide();
        $('#001').hide();
        $('.noteAccess').hide();
    }
    if (fonctionnality != "") {
        // Management of rigts
        // data_fonctionnality = 
        alert(fonctionnality);
        if (type_user == "admin") {
            $("#users").removeClass("hide-option").addClass("show-option");
            document.getElementById('addyear').classList.add("hide-option");
            //document.getElementById('addyear').classList.add("show-option");
            // $("#addyear").removeClass("show-option");
        } else if (type_user == "SuperUser") {
            $("#users").removeClass("hide-option").addClass("show-option");
        } else if (type_user == "teacher"){
            $('.hibeTeach').hide(); 
            alert('rr');

        }
    }else{
        /*if (type_user == "admin") {
            document.getElementById('addyear').classList.add("hide-option");
            document.getElementById('001E').classList.add("hide-option");
        } else if (type_user == "SuperUser") {
            $("#users").removeClass("hide-option").addClass("show-option");
            $("#001E").removeClass("hide-option").addClass("show-option");
        }*/
        // sorry no authorisation
        $(document).ready(function(){
            function show_toast(){
                //toastr["warning"]("Cher "+login+" vous n'avez auccun droit actif", "Alerte");
            }
            setInterval(show_toast(), 5000);
        })
        
    }


</script>