<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();

include "../../db.classes/courses.php";
$coursecls  = new courses();

$result = $coursecls->get_coursecategory($_GET['cat_id']);

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.LMS_VIEW.' Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" name="cat_id" value="'.$result['cat_id'].'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cat_name" value="'.$result['cat_name'].'" required>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cat_code" value="'.$result['cat_code'].'" required>
                        
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" name="cat_ordering" value="'.$result['cat_ordering'].'" readonly type="number" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Icon</label>
                        <input type="file" name="cat_icon" accept="image/*" class="form-control"/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image</label>
                        <input type="file" name="cat_image" accept="image/*" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="cat_description" id="ckeditor0" class="form-control" required>'.html_entity_decode($result['cat_description']).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cat_meta_keywords" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem value="'.$result['cat_meta_keywords'].'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="cat_meta_description" class="form-control" required>'.$result['cat_meta_description'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" required name="cat_status" data-choices>
                            <option value="">Choose one</option>
                            <option label="Select"></option>';
                            $statuses = get_status();
                            foreach($statuses as $key => $status):
                                echo '
                                <option value="'.$key.'" '.($result['cat_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.LMS_VIEW.' Category</button>
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
