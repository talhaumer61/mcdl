<?php
include "include/dbsetting/lms_vars_config.php";
include "include/dbsetting/classdbconection.php";
include "include/functions/functions.php";
$dblms = new dblms();
include "include/functions/login_func.php";
checkCpanelLMSALogin();
include("include/header.php");
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1 && LMS_VIEW == 'dashboard' && !empty(LMS_EDIT_ID)){
    include("include/skill_ambassador/dashboard.php");
} else {
    include("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/dashboard.php");
}
include("include/footer.php");
?>