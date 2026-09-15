<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

$condition = array ( 
                          'select'       =>  "e.emply_id, e.emply_name, d.dept_name"
                        , 'join'     =>  'INNER JOIN '.DEPARTMENTS.' d ON d.dept_id  = e.id_dept'
                        , 'where' 	    =>    array( 
                                                          'e.is_deleted'       =>  0
                                                        , 'e.emply_status'     =>  1
                                                        , 'e.emply_request'    =>  1
                                                    )
                        , 'order_by'     =>  'e.emply_id ASC'
                        , 'return_type'  =>  'all' 
                    ); 
$EMPLOYEES = $dblms->getRows(EMPLOYEES.' e', $condition);

$condition = array ( 
                         'select'       =>  "id, id_curs, id_teacher, remarks"
                        ,'where' 	    =>    array( 
                                                        'id_curs'   =>  LMS_EDIT_ID
                                                    )
                        ,'order_by'     =>  'id ASC'
                        ,'return_type'  =>  'single'
                   ); 
$row = $dblms->getRows(ALLOCATE_TEACHERS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-links-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="id_curs" value="'.LMS_EDIT_ID.'"/>
            <input type="hidden" name="id" value="'.$row['id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Course Name</label>
                        <input class="form-control" value="'.$_GET['curs_name'].' - '.$_GET['curs_code'].'" readonly/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Teachers <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_teacher[]" data-choices data-choices-removeItem multiple required>
                            <option value="">Choose multiple</option>';
                            foreach($EMPLOYEES as $emply):
                                echo'<option value="'.$emply['emply_id'].'" '.(in_array($emply['emply_id'], explode( ',', $row['id_teacher'])) ? 'selected': '').'>'.$emply['emply_name'].'('.$emply['dept_name'].')</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control">'.$row['remarks'].'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="allocate_teachers"><i class="ri-links-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>