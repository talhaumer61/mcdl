<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'message'
                    ,'where'        =>  array(
                                                'id'    =>  cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(QUESTION_ANSWERS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.LMS_EDIT_ID.'"/>
            <input type="hidden" name="id_std" value="'.$_GET['id_std'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" required>'.$row['message'].'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Save</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>