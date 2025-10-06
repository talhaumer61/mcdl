<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_dept'])) {
    $condition = array(
                         'select'       =>  'emply_id, emply_name'
                        ,'where'        =>  array(
                                                     'id_dept'          => cleanvars($_POST['id_dept'])
                                                    ,'emply_status'     => 1
                                                    ,'id_type'          => 1
                                                    ,'is_deleted'       => 0
                                                )
                        ,'order_by'     =>  'emply_name ASC'
                        ,'return_type'  =>  'all'
    );
    $employees = $dblms->getRows(EMPLOYEES, $condition);
    echo'
    <script src="assets/js/app.js"></script>
    <select class="form-control" data-choices required name="id_emply" id="id_emply">';
    if($employees){
        echo'<option value="">Choose one</option>';
        foreach($employees as $row) {
            echo'<option value="'.$row['emply_id'].'">'.$row['emply_name'].'</option>';
        }
    }else{
        echo'<option value="">No Record Found</option>';
    }
    echo'</select>';
}
include_once ('../headoffice/teacherlogin/script.php');
?>