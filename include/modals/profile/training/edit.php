<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
require_once("../../../functions/login_func.php");
checkCpanelLMSALogin();
$condition = array ( 
                        'select' 	    =>  'jobfield,course,organization,address,date_start,date_end'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_employee'          => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars($_GET['training_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(EMPLOYEE_TRAININGS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="training_id" id="training_id" value="'.cleanvars($_GET['training_id']).'"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Job Field <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="jobfield" id="jobfield" value="'.$row['jobfield'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Course <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="course" id="course" value="'.$row['course'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Start Date <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="date_start" id="date_start" value="'.date("Y-m-d",strtotime(cleanvars($row['date_start']))).'" data-provider="flatpickr" data-date-format="Y-m-d" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">End Date <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="date_end" id="date_end" value="'.date("Y-m-d",strtotime(cleanvars($row['date_end']))).'" data-provider="flatpickr" data-date-format="Y-m-d" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Organization <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="organization" id="organization" value="'.$row['course'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Address</label>
                        <textarea class="form-control" type="text" name="address" id="address">'.html_entity_decode(html_entity_decode($row['course'])).'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>