<?php
require_once("../dbsetting/lms_vars_config.php");
require_once("../dbsetting/classdbconection.php");
require_once("../functions/functions.php");
$dblms = new dblms();
require_once("../functions/login_func.php");
checkCpanelLMSALogin();

$type = cleanvars($_GET['type']);

$options = '<option value="">All</option>';

$condition = array(
    'select'       => 'c.curs_id, c.curs_name',
    'join'         => 'INNER JOIN '.ADMISSION_OFFERING.' ao ON c.curs_id = ao.admoff_degree AND ao.admoff_type = '.$type,
    'where'        => array(
                        'c.curs_status' => 1,
                        'c.is_deleted'  => 0
                    ),
    'order_by'     => 'c.curs_name ASC',
    'return_type'  => 'all'
);

$courses = $dblms->getRows(COURSES. ' c', $condition);

if($courses){
    foreach ($courses as $course) {
        $options .= '<option value="'.$course['curs_id'].'">'.$course['curs_name'].'</option>';
    }
}

echo $options; 
?>