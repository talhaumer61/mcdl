<?php 
if(isset($_GET['edit_id'])) {
    require_once("../../../dbsetting/lms_vars_config.php");
    require_once("../../../dbsetting/classdbconection.php");
    require_once("../../../functions/functions.php");
    require_once("../../../functions/login_func.php");
    $dblms = new dblms();

    require_once("../../../db.classes/courses.php");
    $coursecls = new courses();
    $result = $coursecls->get_courseweekdetail($_GET['edit_id']);

    echo '
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit ' . moduleName(LMS_VIEW) . '</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="' . LMS_EDIT_ID . '"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">' . get_CourseWise($_GET['curs_wise']) . ' <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="id_week">
                            <option value="">Choose one</option>';
    foreach (get_LessonWeeks() as $key => $value):
        echo '<option value="' . $key . '" ' . ($key == $result['id_week'] ? 'selected' : '') . '>' . $value . '</option>';
    endforeach;
    echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="status">
                            <option value="">Choose one</option>';
    foreach (get_status() as $key => $status):
        echo '<option value="' . $key . '" ' . ($result['status'] == $key ? 'selected' : '') . '>' . $status . '</option>';
    endforeach;
    echo '
                        </select>
                    </div>
                </div>
                <div class="row">                
                    <div class="col mb-2">
                        <label class="form-label">Caption <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="caption" value="' . $result['caption'] . '" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Detail</label>
                        <textarea name="detail" class="form-control" rows="5">' . $result['detail'] . '</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit ' . moduleName(LMS_VIEW) . '</button>
                </div>
            </div>
        </form>
    </div>
</div>';
}