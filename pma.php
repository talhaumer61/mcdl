<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
include "include/header.php";
if($_SESSION['userlogininfo']['LOGINIDA'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1 && $_SESSION['userlogininfo']['LOGINAFOR'] == 1){
    include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/".moduleName().".php");
}else{    
    header("Location: dashboard.php", true, 301);
}
include_once("include/footer.php");
?>