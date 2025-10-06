<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();

$condition = array ( 
                     'select'       =>  'mstcat_id, mstcat_status, mstcat_name, mstcat_description, mstcat_meta_keywords, mstcat_meta_description, mstcat_code'
                    ,'where'        =>  array( 
                                                'mstcat_id'    =>  LMS_EDIT_ID
                                            )
                    ,'return_type'  =>  'single'
                ); 
$row = $dblms->getRows(MASTER_TRACK_CATEGORIES, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" name="edit_id" value="'.LMS_EDIT_ID.'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="mstcat_name" value="'.$row['mstcat_name'].'" required>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="mstcat_code" value="'.$row['mstcat_code'].'" required>                        
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" required name="mstcat_status" data-choices>
                            <option value="">Choose one</option>
                            <option label="Select"></option>';
                            foreach(get_status() as $key => $status):
                                echo '<option value="'.$key.'" '.($row['mstcat_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Icon</label>
                        <input type="file" name="mstcat_icon" accept=".png, .jpg, .jpeg" class="form-control"/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image</label>
                        <input type="file" name="mstcat_image" accept=".png, .jpg, .jpeg" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="mstcat_description" id="ckeditor0" class="form-control" required>'.html_entity_decode($row['mstcat_description']).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mstcat_meta_keywords" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem value="'.$row['mstcat_meta_keywords'].'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="mstcat_meta_description" class="form-control" required>'.$row['mstcat_meta_description'].'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    CKEDITOR.replace("ckeditor0");
</script>
';
?>
