<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();
$conCat = array ( 
                     'select'       =>  ' dept_id
                                        , dept_status
                                        , dept_publish
                                        , dept_ordering
                                        , dept_code
                                        , dept_name
                                        , dept_intro
                                        , dept_meta
                                        , dept_keyword
                                        , id_faculty
                                        , dept_icon
                                        , dept_photo'
                    ,'where' 	    =>  array( 
                                            'dept_id' => $_GET['dept_id']
                                        )
                    ,'return_type'  =>  'single'
                ); 
$row = $dblms->getRows(DEPARTMENTS, $conCat);

// FACULTIES
$condition = array ( 
                         'select'       =>  'faculty_id, faculty_name, faculty_code'
                        ,'where'        =>  array(
                                                     'faculty_status'   =>  '1'
                                                    ,'is_deleted'       =>  '0'
                                                )
                        ,'order_by'     =>  'faculty_name'
                        ,'return_type'  =>  'all'
                    ); 
$Faculties = $dblms->getRows(FACULTIES, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="departments.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" value="'.$row['dept_id'].'" name="dept_id" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" value="'.$row['dept_name'].'" name="dept_name" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$row['dept_code'].'" name="dept_code"  type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$row['dept_ordering'].'" name="dept_ordering" readonly type="number" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Faculty <span class="text-danger">*</span></label>
                        <select class="form-control" required name="id_faculty" data-choices>
                            <option value="">Choose one</option>';
                            foreach($Faculties as $faculty):
                                echo'<option value="'.$faculty['faculty_id'].'" '.($faculty['faculty_id'] == $row['id_faculty'] ? 'selected' : '').'>'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Icon</label>
                        <input type="file" name="dept_icon" accept="image/*" class="form-control"/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image</label>
                        <input type="file" name="dept_photo" accept="image/*" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>                        
                        <input type="text" class="form-control" name="dept_keyword" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem value="'.$row['dept_keyword'].'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Introduction <span class="text-danger">*</span></label>
                        <textarea name="dept_intro" id="ckeditor0" class="form-control" required>'.html_entity_decode($row['dept_intro']).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="dept_meta" class="form-control" required>'.$row['dept_meta'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Publish <span class="text-danger">*</span></label>
                        <select class="form-control" required name="dept_publish" data-choices>
                            <option value="">Choose one</option>';
                            foreach(get_is_publish() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $row['dept_publish'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" required name="dept_status" data-choices>
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $row['dept_status'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Department</button>
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
