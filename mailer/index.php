<?php
include "dbsetting/classdbconection.php";
include "functions/functions.php";
include "dbsetting/vars_config.php";
include "language/language.php";
include "assets/PHPMailer/PHPMailerAutoload.php";
$response = array();
include "include/mail.php";
echo json_encode($response);