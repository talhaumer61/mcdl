<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
require_once("../../../functions/login_func.php");
checkCpanelLMSALogin();
$condition = array ( 
                        'select' 	    =>  'organization,designation,memno,startdate,enddate'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_employee'          => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars($_GET['mem_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(EMPLOYEE_MEMBERSHIPS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="mem_id" id="mem_id" value="'.cleanvars($_GET['mem_id']).'"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Organization <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="organization" id="organization" value="'.$row['organization'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Designation <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="designation" id="designation" value="'.$row['designation'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Membership No <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="memno" id="memno" value="'.$row['memno'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Start Date <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="startdate" id="startdate" required  value="'.date("Y-m-d",strtotime(cleanvars($row['startdate']))).'" data-provider="flatpickr" data-date-format="Y-m-d">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">End Date</label>
                        <input class="form-control" type="text" name="enddate" id="enddate"  value="'.date("Y-m-d",strtotime(cleanvars($row['enddate']))).'" data-provider="flatpickr" data-date-format="Y-m-d">
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