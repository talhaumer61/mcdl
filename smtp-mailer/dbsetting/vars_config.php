<?php
error_reporting(0);
ob_start();
ob_clean();
session_start();
date_default_timezone_set("Asia/Karachi");
ini_set('memory_limit', '-1');

define('LMS_HOSTNAME'			, 'localhost');
define('LMS_NAME'				, 'mcdl_muldodl2025');
define('LMS_USERNAME'			, 'mcdl_dodl');
define('LMS_USERPASS'			, 'KSkTGFEhOD0asI#S');

define('MAIL_SEND'              , 'mail_sent');
define('MAIL_SEND_DETAIL'		, 'mail_sent_detail');

define('CONTROLER'			    , (!empty($_REQUEST['control'])         ? cleanvars($_REQUEST['control'])          : ''));
define('ZONE'			        , (!empty($_REQUEST['zone'])            ? cleanvars($_REQUEST['zone'])             : ''));
define('VIEW'			        , (!empty($_REQUEST['view'])            ? cleanvars($_REQUEST['view'])             : ''));
define('EDIT_ID'                , (!empty($_REQUEST['edit_id'])         ? cleanvars($_REQUEST['edit_id'])          : ''));
define("SERVER_URL"			    , "https://mcdl.mul.edu.pk/smtp-mailer/");
define('IP'				        , (!empty($_SERVER['REMOTE_ADDR'])      ? $_SERVER['REMOTE_ADDR']   : ''));



$page			                = (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : '';
$current_page	                = (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : 1;
$Limit			                = (isset($_REQUEST['Limit']) && $_REQUEST['Limit'] != '') ? $_REQUEST['Limit'] : '';
$do                             = '';
$redirection                    = '';


define('SENDER'				    , (!empty($_REQUEST['sender'])          ? cleanvars($_REQUEST['sender'])            : ''));
define('SENDER_NAME'			, (!empty($_REQUEST['senderName'])      ? cleanvars($_REQUEST['senderName'])        : ''));
define('RECEIVER'				, (!empty($_REQUEST['receiver'])        ? cleanvars($_REQUEST['receiver'])          : ''));
define('RECEIVER_NAME'			, (!empty($_REQUEST['receiverName'])    ? cleanvars($_REQUEST['receiverName'])      : ''));
define('CC'				        , (!empty($_REQUEST['cc'])              ? cleanvars($_REQUEST['cc'])                : ''));
define('CC_NAME'				, (!empty($_REQUEST['ccName'])          ? cleanvars($_REQUEST['ccName'])            : ''));
define('BCC'				    , (!empty($_REQUEST['bcc'])             ? cleanvars($_REQUEST['bcc'])               : ''));
define('BCC_NAME'				, (!empty($_REQUEST['bccName'])         ? cleanvars($_REQUEST['bccName'])           : ''));
define('SUBJECT'				, (!empty($_REQUEST['subject'])         ? cleanvars($_REQUEST['subject'])           : ''));
define('BODY'				    , (!empty($_REQUEST['body'])            ? cleanvars($_REQUEST['body'])              : ''));

// Security Tokken
define('TOKKEN'				    , (!empty($_REQUEST['tokken'])          ? cleanvars($_REQUEST['tokken'])           : ''));
define('MATCHING_TOKKEN'        , 'b08a9b259dc86a5fa2ab8f409614b38dbef1768edbab1d2a7281c2c963d5b5ed');

// vars_config.php (example) - SMTP configuration
define('SMTP_HOST'              , 'smtp.gmail.com');            // for gmail use smtp.gmail.com
define('SMTP_USER'              , 'noreply.dodl@mul.edu.pk');   // the mailbox you created
define('SMTP_PASS'              , '8MXEa&Qb<h=K4yJ=%');         // mailbox password
define('SMTP_PORT'              , 587);                         // 587 | 465
define('SMTP_SECURE'            , 'tls');                       // tls | ssl

// OAuth2 Configuration
define('CLIENT_ID'              , '117788691303-nhcl1f27aksvdepb6vui3tsgd58dbuob.apps.googleusercontent.com');
define('CLIENT_SECRET'          , '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');
define('REFRESH_TOKEN'          , '1//04QejoOd87zp3CgYIARAAGAQSNwF-L9Irh41CgCWEsqmbp4NOcauZ7oKL4ebn3nr_Aydpp_4r4FkV8w9_B8ScAzqeEs1_FrsH3Xk');


/*
{
    "web": {
        "client_id": "117788691303-nhcl1f27aksvdepb6vui3tsgd58dbuob.apps.googleusercontent.com",
        "project_id": "dodl-473311",
        "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        "token_uri": "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        "client_secret": "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",
        "redirect_uris": [
            "https://developers.google.com/oauthplayground"
        ]
    }
}


{
    "OAuth-2.0-Playground": {
        "authorization-code" : "4/0AVGzR1BrDpstFpO58uTvAdlJT2bcb3bFpP8IjVjE9C-DQnnFa1LmVEzuM87YH8IV0gSbdQ",
        "refresh-token": "1//045HnZ-cNNxC6CgYIARAAGAQSNwF-L9Ir0D7bKqm4HJail7l_Z-QR4N0-C3tEAw4Jz35aepwWby3jYUX8LB7RPrQwhYWbekTyo80"
    }
}
*/
?>