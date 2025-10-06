<?php
include "include/dbsetting/lms_vars_config.php";
include "include/dbsetting/classdbconection.php";
include "include/functions/functions.php";
$dblms = new dblms();
include "include/functions/login_func.php";
checkCpanelLMSALogin();

require_once("include/db.classes/settings.php");
$settingcls = new settings();


include("include/header.php");
include("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/cities.php");
include("include/footer.php");
?>