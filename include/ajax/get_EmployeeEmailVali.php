<?php
if (isset($_POST['emply_email']) && !empty($_POST['emply_email'])) {
    include "../dbsetting/lms_vars_config.php";
    include "../dbsetting/classdbconection.php";
    $dblms = new dblms();
    include "../functions/login_func.php";
    include "../functions/functions.php";

    $condition = array ( 
                             'select'       =>  "adm_id"
                            ,'where' 	    =>  array( 
                                                         'is_deleted'       => '0'
                                                        ,'adm_email'        => cleanvars($_POST['emply_email'])
                                                    )
                            ,'return_type'  =>  'count' 
                        );
    if ($dblms->getRows(ADMINS, $condition)) {
        echo '0';
    } else {
        echo '1';
    }
}
?>