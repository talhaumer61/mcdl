<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();

require_once("../../db.classes/courses.php");
$coursecls = new courses();

$result = $coursecls->get_department($_GET['dept_id']);
// FACULTIES
$Faculties = $coursecls->get_faculties(1, '');

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="departments.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" value="'.$result['dept_id'].'" name="dept_id" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" value="'.$result['dept_name'].'" name="dept_name" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$result['dept_code'].'" name="dept_code"  type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$result['dept_ordering'].'" name="dept_ordering" readonly type="number" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Faculty <span class="text-danger">*</span></label>
                        <select class="form-control" required name="id_faculty" data-choices>
                            <option value="">Choose one</option>';
                            foreach($Faculties as $faculty):
                                echo'<option value="'.$faculty['faculty_id'].'" '.($faculty['faculty_id'] == $result['id_faculty'] ? 'selected' : '').'>'.$faculty['faculty_name'].' - '.$faculty['faculty_code'].'</option>';
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
                        <input type="text" class="form-control" name="dept_keyword" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem value="'.$result['dept_keyword'].'"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Introduction <span class="text-danger">*</span></label>
                        <textarea name="dept_intro" id="ckeditor0" class="form-control" required>'.html_entity_decode($result['dept_intro']).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="dept_meta" class="form-control" required>'.$result['dept_meta'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Publish <span class="text-danger">*</span></label>
                        <select class="form-control" required name="dept_publish" data-choices>
                            <option value="">Choose one</option>';
                            foreach(get_is_publish() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $result['dept_publish'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" required name="dept_status" data-choices>
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $result['dept_status'] ? 'selected' : '').'>'.$status.'</option>';
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
</script>';
