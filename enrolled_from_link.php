<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
include "include/header.php";
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1 && LMS_VIEW == 'enrolled_list' && !empty(LMS_EDIT_ID)){
    include("include/skill_ambassador/".moduleName().".php");
} else {
    include("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/".moduleName().".php");
}
include_once("include/footer.php");
?>