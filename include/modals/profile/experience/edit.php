<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
require_once("../../../functions/login_func.php");
checkCpanelLMSALogin();
$condition = array ( 
                         'select' 	    =>  'organization,designation,jobfield,jobdetail,date_start,date_end,salary_start,salary_end'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_employee'          => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(EMPLOYEE_EXPERIENCE, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" id="edit_id" value="'.LMS_VIEW.'"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Organization <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="organization" id="organization" value="'.$row['organization'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Job Field <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="jobfield" id="jobfield" value="'.$row['jobfield'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="designation" id="designation" value="'.$row['designation'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Job Detail</label>
                        <input class="form-control" type="text" name="jobdetail" id="jobdetail" value="'.$row['jobdetail'].'">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="text" name="date_start" id="date_start" value="'.date("Y-m-d", strtotime($row['date_start'])).'" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">End Date</label>
                        <input type="text" name="date_end" id="date_end" value="'.date("Y-m-d", strtotime($row['date_end'])).'" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Start Salary</label>
                        <input class="form-control" type="number" name="salary_start" id="salary_start" value="'.$row['salary_start'].'">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">End Salary</label>
                        <input class="form-control" type="number" name="salary_end" id="salary_end" value="'.$row['salary_end'].'">
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