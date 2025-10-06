<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();
$conCat = array ( 
    'select'        =>  'cat_id, cat_status, cat_ordering, cat_name, cat_description, cat_meta_keywords, cat_meta_description, cat_code',
    'where' 	=> array( 
                            'cat_id' => $_GET['cat_id']
                        ), 
    'return_type'   =>  'single'
); 
$row = $dblms->getRows(PROGRAMS_CATEGORIES, $conCat);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Program Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="program_categories.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" name="cat_id" value="'.$row['cat_id'].'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cat_name" value="'.$row['cat_name'].'" required>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cat_code" value="'.$row['cat_code'].'" required>
                        
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" name="cat_ordering" value="'.$row['cat_ordering'].'" readonly type="number" required>
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
                        <textarea name="cat_description" id="ckeditor0" class="form-control" required>'.html_entity_decode($row['cat_description']).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cat_meta_keywords" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem value="'.$row['cat_meta_keywords'].'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="cat_meta_description" class="form-control" required>'.$row['cat_meta_description'].'</textarea>
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
                                <option value="'.$key.'" '.($row['cat_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Program Category</button>
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
