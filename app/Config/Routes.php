<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::login');
$routes->get('add-user', 'Home::addUser');
$routes->get('Home', 'Home::home');
$routes->get('Home2', 'Home::home2');
$routes->get('Licence', 'Home::licence');
$routes->get("log-out-user", "AuthController::logOut");

$routes->get("shool-choice-user", "Home::choiceSchool");

$routes->get('Note', 'Home::note');

/*
 * --------------------------------------------------------------------
 * Route Definitions Users
 * --------------------------------------------------------------------
*/
$routes->group("user", function($routes){
    // URL - /user
    $routes->get("/", "UserController::index");
    // URL - /user/add-user
    $routes->match(["get", "post"], "add-user", "UserController::addUser");
    // URL - /user/attach-user
    $routes->match(["get", "post"], "attach-user", "UserController::attachUserFonct", ['filter' => 'authFilter']);
    // URL - /user/list-all-user
    $routes->match(["get", "post"], "list-all-user", "UserController::listAllUser", ['filter' => 'authFilter']);
    // URL - /user/list-user-actif
    $routes->match(["get", "post"], "list-user-actif", "UserController::listUserActif", ['filter' => 'authFilter']);
    // URL - /user/list-user-inactif
    $routes->match(["get", "post"], "list-user-inactif", "UserController::listUserInactif", ['filter' => 'authFilter']);
    // URL - /user/list-user-delete
    $routes->match(["get", "post"], "list-user-delete", "UserController::listUserDelete", ['filter' => 'authFilter']);
    // URL - /user/list-user-not-delete
    $routes->match(["get", "post"], "list-user-not-delete", "UserController::listUserNotDelete", ['filter' => 'authFilter']);
    // URL - /user/attach-user-fonct
    $routes->match(["get", "post"], "attach-user-fonct/(:any)/(:any)", "UserController::attachUserFonct/$1/$2", ['filter' => 'authFilter']);
    // $routes->get("user/route-1", "AnyController::method1", ["filter" => "auth"]);
});

/*
 * --------------------------------------------------------------------
 * Route Definitions Auth
 * --------------------------------------------------------------------
*/
$routes->group("auth", function($routes){
    // URL - /auth/auth-user
    $routes->match(["get", "post"], "auth-user", "AuthController::authentification");
});

/*
 * --------------------------------------------------------------------
 * Route Definitions Statistique
 * --------------------------------------------------------------------
*/
$routes->group("statistique", function($routes){
    // URL - /statistique/acceuil1
    $routes->match(["get", "post"], "acceuil1/(:any)", "StatistiqueController::statistique1/$1");
    $routes->match(["get", "post"], "diagramme_effectif/(:any)", "StatistiqueController::Diagramme_effectif/$1");
});

/*
 * --------------------------------------------------------------------
 * route for schoool
 * --------------------------------------------------------------------
 */
$routes->group("school", function($routes){
    // VIEW - school/save
    $routes->get('save', 'Home::save');
    // VIEW - school/liste
    $routes->get('list', 'Home::liste'); 
    // URL - /school/insert
    $routes->match(["get", "post"], "insert", "SchoolController::insertschool", ['filter' => 'authFilter']);
    // URL - /school/update
    $routes->match(["get", "post"], "update", "SchoolController::updateschool", ['filter' => 'authFilter']);
    // URL - /school/select
    $routes->match(["get", "post"], "select/(:any)", "SchoolController::selectschool/$1", ['filter' => 'authFilter']);
    // URL - /school/delete
    $routes->match(["get", "post"], "delete/(:any)", "SchoolController::deleteschool/$1", ['filter' => 'authFilter']);
    // URL - /school/allSchool
    $routes->match(["get", "post"], "AllSchool/(:any)", "SchoolController::liste_school/$1", ['filter' => 'authFilter']);
    // URL - /school/schoolFilter
    $routes->match(["get", "post"], "SchoolFilter/(:any)", "SchoolController::school_filter/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for scolarité
 * --------------------------------------------------------------------
 */
$routes->group("scolarite", function($routes){
    // VIEW - scolarite/save
    $routes->get('save_inscription', 'ScolariteController::save_inscription');
    // VIEW - scolarite/liste
    $routes->get('save_pension', 'ScolariteController::save_pension'); 
    // URL - /scolarite/Payer_inscription
    $routes->match(["get", "post"], "Payer_inscription", "ScolariteController::payer_inscription", ['filter' => 'authFilter']);
    // URL - /scolarite/Payer_scolarite
    $routes->match(["get", "post"], "Payer_scolarite", "ScolariteController::payer_scolarite", ['filter' => 'authFilter']);
    
});

/*
 * --------------------------------------------------------------------
 * route for student
 * --------------------------------------------------------------------
 */
$routes->group("student", function($routes){
    // VIEW - student/save
    $routes->get('save', 'StudentController::save');
    // VIEW - student/liste
    $routes->get('list', 'StudentController::liste'); 
    // VIEW - student/changephoto
    $routes->get('ChangePhoto', 'StudentController::change_photo');
    // VIEW - student/Importer
    $routes->get('Importer', 'StudentController::importer_liste');
    // URL - /student/photo
    $routes->match(["get", "post"], "insertPhoto", "StudentController::insert_photo", ['filter' => 'authFilter']); 
    // URL - /student/importStudent
    $routes->match(["get", "post"], "importStudent", "StudentController::import_student", ['filter' => 'authFilter']); 
    // URL - /student/getOne
    $routes->match(["get", "post"], "getOne/(:any)/(:any)/(:any)/(:any)/(:any)", "StudentController::get_one_student/$1/$2/$3/$4/$5", ['filter' => 'authFilter']); 
    // URL - /student/insert
    $routes->match(["get", "post"], "insert", "StudentController::insertstudent", ['filter' => 'authFilter']);
    // URL - /student/update
    $routes->match(["get", "post"], "update", "StudentController::updatestudent", ['filter' => 'authFilter']);
    // URL - /student/delete
    $routes->match(["get", "post"], "delete/(:any)", "StudentController::delete_user/$1", ['filter' => 'authFilter']);
    // URL - /student/allStudent
    $routes->match(["get", "post"], "AllStudent/(:any)", "StudentController::liste_student/$1", ['filter' => 'authFilter']);
    // URL - /student/allStudentSchool/id_school
    $routes->match(["get", "post"], "AllStudentSchool/(:any)", "StudentController::liste_student_school/$1", ['filter' => 'authFilter']);
    // URL - /student/studentFilter
    $routes->match(["get", "post"], "StudentFilter", "StudentController::student_filter", ['filter' => 'authFilter']);
    // URL - /student/PrintListClass
    $routes->match(["get", "post"], "PrintListClass/(:any)/(:any)/(:any)/(:any)", "StudentController::print_list_class/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintListClassRedouble
    $routes->match(["get", "post"], "PrintListClassRedouble/(:any)/(:any)/(:any)/(:any)", "StudentController::print_list_redouble/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintListClassRedouble
    $routes->match(["get", "post"], "PrintListClassNew/(:any)/(:any)/(:any)/(:any)", "StudentController::print_list_new/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFichePress
    $routes->match(["get", "post"], "PrintFichePress/(:any)/(:any)/(:any)/(:any)", "StudentController::print_fiche_pres/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheDecharge
    $routes->match(["get", "post"], "PrintFicheDecharge/(:any)/(:any)/(:any)/(:any)", "StudentController::print_fiche_decharge/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheInscription
    $routes->match(["get", "post"], "PrintFicheInscription/(:any)/(:any)/(:any)/(:any)", "StudentController::print_fiche_inscrit/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheNotInscription
    $routes->match(["get", "post"], "PrintFicheNotInscription/(:any)/(:any)/(:any)/(:any)", "StudentController::print_fiche_not_inscrit/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheCertificat
    $routes->match(["get", "post"], "PrintFicheCertificat/(:any)/(:any)/(:any)/(:any)", "CertificatController::print_certificat_class/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintCarteScolaire
    $routes->match(["get", "post"], "PrintCarteScolaire/(:any)/(:any)/(:any)/(:any)", "PrintCarteControl::print_carte_class/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheCertificatOne
    $routes->match(["get", "post"], "PrintFicheCertificatOne/(:any)/(:any)/(:any)/(:any)/(:any)", "CertificatController::print_certificat_class_one/$1/$2/$3/$4/$5", ['filter' => 'authFilter']);
    // URL - /student/PrintFicheCertificatOne
    $routes->match(["get", "post"], "PrintOneCarteScolaire/(:any)/(:any)/(:any)/(:any)/(:any)", "PrintCarteControl::print_carte_class_one/$1/$2/$3/$4/$5", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for document
 * --------------------------------------------------------------------
 */
$routes->group("document", function($routes){
    // VIEW - document/save
    $routes->get('save', 'DocumentController::save');
    // VIEW - document/liste
    $routes->get('list', 'DocumentController::liste'); 
    // URL - /document/insert
    $routes->match(["get", "post"], "insert", "DocumentController::insertdocument", ['filter' => 'authFilter']);
    // URL - /document/update
    $routes->match(["get", "post"], "update", "DocumentController::updatedocument", ['filter' => 'authFilter']);
    // URL - /document/delete
    $routes->match(["get", "post"], "delete/(:any)", "DocumentController::deletedocument/$1", ['filter' => 'authFilter']);
    // URL - /document/all
    $routes->match(["get", "post"], "all/(:any)/(:any)", "DocumentController::allDocument/$1/$2", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for history
 * --------------------------------------------------------------------
 */
$routes->group("history", function($routes){
    // URL - /history/listeView
    $routes->match(["get", "post"], "listView", "HistoryController::liste");
    // URL - /history/list
    $routes->match(["get", "post"], "list", "HistoryController::listeMyHistory", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for class
 * --------------------------------------------------------------------
 */
$routes->group("class", function($routes){
    // VIEW - class/save
    $routes->get('save', 'ClassController::save');
    // VIEW - class/liste
    $routes->get('list', 'ClassController::liste'); 
    // URL - /class/insert
    $routes->match(["get", "post"], "insert", "ClassController::insertclass", ['filter' => 'authFilter']);
    // URL - /class/update
    $routes->match(["get", "post"], "update", "ClassController::updateclass", ['filter' => 'authFilter']);
    // URL - /class/delete
    $routes->match(["get", "post"], "delete/(:any)", "ClassController::deleteclass/$1", ['filter' => 'authFilter']);
    // URL - /class/all
    $routes->match(["get", "post"], "all/(:any)/(:any)/(:any)", "ClassController::allClass/$1/$2/$3", ['filter' => 'authFilter']);
    
});

/*
 * --------------------------------------------------------------------
 * route for teacher
 * --------------------------------------------------------------------
 */
$routes->group("teacher", function($routes){
    // VIEW - teacher/save
    $routes->get('save', 'TeacherController::save');
    // VIEW - teacher/importer
    $routes->get('Importer', 'TeacherController::importer');
    // VIEW - teacher/liste
    $routes->get('list', 'TeacherController::liste'); 
    // VIEW - teacher/giveClass
    $routes->get('giveClass', 'TeacherController::GiveClass'); 
    // URL - /teacher/imorter teacher
    $routes->match(["get", "post"], "Importer_teacher", "TeacherController::impoter_eacher", ['filter' => 'authFilter']);
    // URL - /teacher/insert
    $routes->match(["get", "post"], "insert", "TeacherController::insertteacher", ['filter' => 'authFilter']);
    // URL - /teacher/all
    $routes->match(["get", "post"], "all/(:any)/(:any)", "TeacherController::allTeacher/$1/$2", ['filter' => 'authFilter']);
     // URL - /teacher/all
     $routes->match(["get", "post"], "getTeacherSchoolClass/(:any)/(:any)/(:any)", "TeacherController::GetTeacherSchoolClass/$1/$2/$3", ['filter' => 'authFilter']);
    // URL - /teacher/update
    $routes->match(["get", "post"], "update", "TeacherController::updateteacher", ['filter' => 'authFilter']);
    // URL - /teacher/delete
    $routes->match(["get", "post"], "delete/(:any)", "TeacherController::deleteteacher/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintListSchool
    $routes->match(["get", "post"], "PrintListSchool/(:any)", "TeacherController::print_listSchool/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintListTeacherVaccataire
    $routes->match(["get", "post"], "PrintListTeacherVaccataire/(:any)", "TeacherController::PrintListTeacherVaccataire/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintListTeacherPermanent
    $routes->match(["get", "post"], "PrintListTeacherPermanent/(:any)", "TeacherController::PrintListTeacherPermanent/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintContrat
    $routes->match(["get", "post"], "PrintContrat/(:any)", "TeacherController::PrintContrat/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintOneContrat
    $routes->match(["get", "post"], "PrintOneContrat/(:any)/(:any)/(:any)", "TeacherController::printOneContrat/$1/$2/$3", ['filter' => 'authFilter']);
    // URL - /teacher/PrintFichePaie
    $routes->match(["get", "post"], "PrintFichePaie/(:any)", "TeacherController::PrintFichePaie/$1", ['filter' => 'authFilter']);
    // URL - /teacher/PrintFicheDecharge
    $routes->match(["get", "post"], "PrintFicheDecharge/(:any)", "TeacherController::printFicheDecharge/$1", ['filter' => 'authFilter']);
    // URL - /teacher/Attribution_class 
    $routes->match(["get", "post"], "Attribution_class", "TeacherController::attribution_class", ['filter' => 'authFilter']);
    // URL - /teacher/salary 
    $routes->match(["get", "post"], "salary", "TeacherController::salaire_personnel");
    // URL - /teacher/save_salaire 
    $routes->match(["get", "post"], "save_salaire", "TeacherController::Save_salaire", ['filter' => 'authFilter']);
    // URL - /teacher/findAllPersonnelBySchool 
    $routes->match(["get", "post"], "FindAllPersonnelBySchool/(:any)", "TeacherController::findAllPersonnelBySchool/$1", ['filter' => 'authFilter']);
    // URL - /teacher/findAllPersonnelBySchool 
    $routes->match(["get", "post"], "HistoriqueSalaire", "TeacherController::historiqueSalaire");
    // URL - /teacher/Payer_salaire 
    $routes->match(["get", "post"], "Payer_salaire", "TeacherController::payer_salaire", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for teaching unit
 * --------------------------------------------------------------------
 */
$routes->group("teaching_unit", function($routes){
    // VIEW - teaching_unit/save
    $routes->get('save', 'TeachingUnitController::save');
    // VIEW - teaching_unit/liste
    $routes->get('list', 'TeachingUnitController::liste'); 
    // URL - /teaching_unit/insert
    $routes->match(["get", "post"], "insert", "TeachingUnitController::insertteaching", ['filter' => 'authFilter']);
    // URL - /teaching_unit/all
    $routes->match(["get", "post"], "all/(:any)", "TeachingUnitController::allTeachingUnit/$1", ['filter' => 'authFilter']);
    // URL - /teaching_unit/update
    $routes->match(["get", "post"], "update", "TeachingUnitController::updateTeachingUnit", ['filter' => 'authFilter']);
    // URL - /teaching_unit/delete
    $routes->match(["get", "post"], "delete/(:any)", "TeachingUnitController::deleteteachingUnit/$1", ['filter' => 'authFilter']);
    // URL - /teaching_unit/getOne
    $routes->match(["get", "post"], "one/(:any)", "TeachingUnitController::GetOne/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for year
 * --------------------------------------------------------------------
 */
$routes->group("years", function($routes){
    // VIEW - year/save
    $routes->get('save', 'YearController::save');
    // VIEW - year/liste
    $routes->get('list', 'YearController::liste'); 
    // URL - /year/insert
    $routes->match(["get", "post"], "insert", "YearController::insertyear", ['filter' => 'authFilter']);
    // URL - /year/actif
    $routes->match(["get", "post"], "actif", "YearController::yearActif", ['filter' => 'authFilter']);
    // URL - /year/update
    $routes->match(["get", "post"], "update", "YearController::updateyear", ['filter' => 'authFilter']);
    // URL - /year/delete
    $routes->match(["get", "post"], "delete/(:any)", "YearController::deleteyear/$1", ['filter' => 'authFilter']);
    // URL - /year/active
    $routes->match(["get", "post"], "active/(:any)", "YearController::activeyear/$1", ['filter' => 'authFilter']);
    // URL - /year/all
    $routes->match(["get", "post"], "all", "YearController::allYear", ['filter' => 'authFilter']);
    // URL - /year/one
    $routes->match(["get", "post"], "one/(:any)", "YearController::oneYear/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for sequence
 * --------------------------------------------------------------------
 */
 $routes->group("sequence", function($routes){
    // VIEW - sequence/save
    $routes->get('save', 'SequenceController::save');
    // VIEW - sequence/liste
    $routes->get('list', 'SequenceController::liste'); 
    // URL - /sequence/insert
    $routes->match(["get", "post"], "insert", "SequenceController::insertsequence", ['filter' => 'authFilter']);
    // URL - /sequence/all
    $routes->match(["get", "post"], "all/(:any)", "SequenceController::allsequence/$1", ['filter' => 'authFilter']);
    // URL - /sequence/all-filter
    $routes->match(["get", "post"], "all-filter/(:any)/(:any)/(:any)/(:any)/(:any)", "SequenceController::allFiltersequence/$1/$2/$3/$4/$5", ['filter' => 'authFilter']);
    // URL - /sequence/update
    $routes->match(["get", "post"], "update", "SequenceController::updatesequence", ['filter' => 'authFilter']);
    // URL - /sequence/delete
    $routes->match(["get", "post"], "delete/(:any)", "SequenceController::deletesequence/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for cycle
 * --------------------------------------------------------------------
 */
$routes->group("cycle", function($routes){
    // VIEW - cycle/save
    $routes->get('save', 'CycleController::save');
    // VIEW - cycle/liste
    $routes->get('list', 'CycleController::liste'); 
    // URL - /cycle/insert
    $routes->match(["get", "post"], "insert", "CycleController::insertcycle", ['filter' => 'authFilter']);
    // URL - /cycle/update
    $routes->match(["get", "post"], "update", "CycleController::updatecycle", ['filter' => 'authFilter']);
    // URL - /cycle/delete
    $routes->match(["get", "post"], "delete/(:any)", "CycleController::deletecycle/$1", ['filter' => 'authFilter']);
    // URL - /cycle/all
    $routes->match(["get", "post"], "all/(:any)/(:any)", "CycleController::allCycle/$1/$2", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for session
 * --------------------------------------------------------------------
 */
$routes->group("session", function($routes){
    // VIEW - session/save
    $routes->get('save', 'SessionController::save');
    // VIEW - session/liste
    $routes->get('list', 'SessionController::liste'); 
    // URL - /session/insert
    $routes->match(["get", "post"], "insert", "SessionController::insertsession", ['filter' => 'authFilter']);
    // URL - /session/all
    $routes->match(["get", "post"], "all/(:any)", "SessionController::allsession/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for serie
 * --------------------------------------------------------------------
 */
$routes->group("serie", function($routes){
    // VIEW - serie/save
    $routes->get('save', 'SerieController::save');
    // VIEW - serie/liste
    $routes->get('list', 'SerieController::liste'); 
    // URL - /serie/insert
    $routes->match(["get", "post"], "insert", "SerieController::insertserie", ['filter' => 'authFilter']);
    // URL - /serie/all
    $routes->match(["get", "post"], "all/(:any)", "SerieController::allSerie/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for trimestre
 * --------------------------------------------------------------------
 */
 $routes->group("trimestre", function($routes){
    // VIEW - trimestre/save
    $routes->get('save', 'TrimestreController::save');
    // VIEW - trimestre/liste
    $routes->get('list', 'TrimestreController::liste'); 
    // URL - /trimestre/insert
    $routes->match(["get", "post"], "insert", "TrimestreController::inserttrimestre", ['filter' => 'authFilter']);
    // URL - /trimestre/update
    $routes->match(["get", "post"], "update", "TrimestreController::updatetrimestre", ['filter' => 'authFilter']);
    // URL - /trimestre/all
    $routes->match(["get", "post"], "all/(:any)", "TrimestreController::alltrimestre/$1", ['filter' => 'authFilter']);
    // URL - /trimestre/all-filter
    $routes->match(["get", "post"], "all-filter/(:any)/(:any)/(:any)/(:any)", "TrimestreController::alltrimestreFilter/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /trimestre/delete
    $routes->match(["get", "post"], "delete/(:any)", "TrimestreController::deletetrimestre/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for matiere
 * --------------------------------------------------------------------
 */
 $routes->group("teachingunit", function($routes){
    // VIEW - teachingunit/save
    $routes->get('save', 'TeachingUnitController::save');
    // VIEW - teachingunit/liste
    $routes->get('list', 'TeachingUnitController::liste'); 
    // URL - /teachingunit/insert
    $routes->match(["get", "post"], "insert", "TeachingUnitController::insertteaching", ['filter' => 'authFilter']);
    // URL - /teachingunit/update
    $routes->match(["get", "post"], "update", "TeachingUnitController::updateteaching", ['filter' => 'authFilter']);
    // URL - /teachingunit/all
    $routes->match(["get", "post"], "all/(:any)", "TeachingUnitController::allteaching/$1/$2/$3/$4", ['filter' => 'authFilter']);
    // URL - /teachingunit/delete
    $routes->match(["get", "post"], "delete/(:any)", "TeachingUnitController::deleteteaching/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for enseignant
 * --------------------------------------------------------------------
 */
 $routes->group("teacher", function($routes){
    // VIEW - teacher/save
    $routes->get('save', 'TeacherController::save');
    // VIEW - teacher/liste
    $routes->get('list', 'TeacherController::liste'); 
    // URL - /teachert/insert
    $routes->match(["get", "post"], "insert", "TeacherController::insertteacher", ['filter' => 'authFilter']);
    // URL - /teacher/update
    $routes->match(["get", "post"], "update", "TeacherUnitController::updateteacher", ['filter' => 'authFilter']);
    // URL - /teacher/all
    $routes->match(["get", "post"], "all/(:any)", "TeacherController::allteacher/$1", ['filter' => 'authFilter']);
    // URL - /teachinger/delete
    $routes->match(["get", "post"], "delete/(:any)", "TeachingUnitController::deleteteacher/$1", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * route for note
 * --------------------------------------------------------------------
 */

 $routes->group("note", function($routes){
    // VIEW - note/save
    $routes->get('save', 'NoteController::save');
    // VIEW - note/liste
    $routes->get('list', 'NoteController::liste'); 
    // URL - /note/insert
    $routes->match(["get", "post"], "insert", "NoteController::insertnote", ['filter' => 'authFilter']);
    // URL - /note/getByTeachingUnit
    $routes->match(["get", "post"], "getnoteByTeachingUnit/(:any)/(:any)/(:any)", "NoteController::GetnoteByTeachingUnit/$1/$2/$3", ['filter' => 'authFilter']);
    // URL - /note/update
    $routes->match(["get", "post"], "update", "NoteController::updatenote", ['filter' => 'authFilter']);
    // URL - /note/delete
    $routes->match(["get", "post"], "Selectnote", "NoteController::listenote", ['filter' => 'authFilter']);
});


/*
 * --------------------------------------------------------------------
 * route for payment
 * --------------------------------------------------------------------
 */
$routes->group("payment", function($routes){
    // VIEW - payment/save
    $routes->get('save', 'PaymentController::save');
    // VIEW - payment/liste
    $routes->get('list', 'PaymentController::liste'); 
    // URL - /payment/insert
    $routes->match(["get", "post"], "insert", "PaymentController::insertpayment", ['filter' => 'authFilter']);
    // URL - /payment/update
    $routes->match(["get", "post"], "update", "PaymentController::updatepayment", ['filter' => 'authFilter']);
    // URL - /payment/all
    $routes->match(["get", "post"], "all/(:any)", "PaymentController::allpayment/$1/$2/$3/$4/$5", ['filter' => 'authFilter']);
});


/*
 * --------------------------------------------------------------------
 * Route Definitions Fonctionnality
 * --------------------------------------------------------------------
*/
$routes->group("fonctionnality", function($routes){
    // URL - /fonctionnality/default
    $routes->match(["get", "post"], "default", "FonctionnlityController::defaultFonctionnality", ['filter' => 'authFilter']);
    // URL - /fonctionnality/add-fonctionnality
    $routes->match(["get", "post"], "add-fonctionnality", "FonctionnlityController::addFonctionnality", ['filter' => 'authFilter']);
    // URL - /fonctionnality/attach-fonctionnality/coded_parent/coded_child
    $routes->match(["get", "post"], "attach-fonctionnality/(:any)/(:any)", "FonctionnlityController::atachFonct/$1/$2", ['filter' => 'authFilter']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
