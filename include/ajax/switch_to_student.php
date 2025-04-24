<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
include "../functions/functions.php";
include "../functions/login_func.php";
$dblms = new dblms();
if(!empty($_SESSION['userlogininfo']['LOGINUSER'])) {
	$encrypt_username 	= get_dataHashing($_SESSION['userlogininfo']['LOGINUSER'],true);
	$e_url              = 'mul.edu.pk';
	setcookie('SWITCHTOSTUDENT','',time()-86400,'/',$e_url);
	setcookie('SWITCHTOSTUDENT',$encrypt_username,time()+86400,'/',$e_url);
	if (isset($_SESSION['userlogininfo']['LOGINIDA'])) {
		unset($_SESSION['userlogininfo']);
		session_destroy();
	}
}
?>