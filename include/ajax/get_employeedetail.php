<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_emply'])){
    $condition = array(
                         'select'       =>  'emply_id, emply_name, emply_phone, emply_email'
                        ,'where'        =>  array(
                                                     'emply_status'     => 1
                                                    ,'id_type'          => 1
                                                    ,'is_deleted'       => 0
                                                    ,'emply_id'         => cleanvars($_POST['id_emply'])
                                                )
                        ,'return_type'  =>  'single'
    );
    $valEmp = $dblms->getRows(EMPLOYEES, $condition);

    if($valEmp){
        echo'
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="adm_fullname" value="'.$valEmp['emply_name'].'" required readonly/>
            </div>
            <div class="col mb-2">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="adm_email" value="'.$valEmp['emply_email'].'" required readonly/>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="adm_phone" value="'.$valEmp['emply_phone'].'" readonly/>
            </div>
            <div class="col mb-2">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="adm_username" value="'.$valEmp['emply_email'].'" required readonly/>
            </div>
        </div>';
    }
}
?>