<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

require_once("include/db.classes/courses.php");
$coursecls = new courses();

include "include/header.php";
if($_GET['tab'] == 'manage_course'){
    include_once("include/teacher/".moduleName().".php");
}else{
    include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/".moduleName().".php");
}
include_once("include/footer.php");
?>