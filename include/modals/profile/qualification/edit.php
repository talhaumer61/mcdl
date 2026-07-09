<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
require_once("../../../functions/login_func.php");
$dblms  = new dblms();
checkCpanelLMSALogin();

$condition = array ( 
                         'select' 	    =>  'id_degree,program,subjects,institute,grade,year,resultcard'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_employee'          => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(EMPLOYEE_EDUCATIONS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" id="edit_id" value="'.LMS_EDIT_ID.'"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Degree Level <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_degree" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_edulevel() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $row['id_degree'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Program Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="program" id="program" value="'.$row['program'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Major Subjects <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="subjects" id="subjects" value="'.$row['subjects'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Institute/Borad <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="institute" id="institute" value="'.$row['institute'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Grade/GPA <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="grade" id="grade" value="'.$row['grade'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="year" id="year" value="'.$row['year'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Result Card</label>
                        <input class="form-control" type="file" name="resultcard" id="resultcard" accept=".pdf, .doc, .docx, .png, .jpg, .jpeg">
                        <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 1MB.</p>
                        <span class="text-danger fw-bold" style="font-size: 12px;">(pdf, doc, docx, png, jpg, jpeg)</span>
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
include('../../../teacher/profile/qualification/script.php');
?>