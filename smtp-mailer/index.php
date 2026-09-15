<?php
include "dbsetting/classdbconection.php";
include "functions/functions.php";
include "dbsetting/vars_config.php";
$dblms = new dblms();
$response = array();
if (CONTROLER == 'mail-list' || CONTROLER == 'mail-detail') {
    include "include/header.php";
    include "include/mail.php";
    include "include/footer.php";
} else {
    switch (CONTROLER):
        case 'send-mail':
            include "include/mail/mail.php";
        break;
        default:
            array_push($response, array(
                'status'  => boolval(false),
                'msg'     => 'path-not-defiend',
            ));
        break;
    endswitch;
    echo json_encode($response);
}