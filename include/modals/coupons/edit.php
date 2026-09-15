<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

require_once("../../db.classes/settings.php");
$settingcls = new settings();

$result = $settingcls->get_coupon(LMS_EDIT_ID);

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.$result['cpn_id'].'"/>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="cpn_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $result['cpn_status'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control flatpickr-input active" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.$result['cpn_start_date'].' to '.$result['cpn_end_date'].'" readonly="readonly">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="cpn_name" id="cpn_name" class="form-control" value="'.$result['cpn_name'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" name="cpn_code" id="cpn_code" class="form-control" value="'.$result['cpn_code'].'" required readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="cpn_type" required>
                            <option value=""> Choose one</option>';
                            foreach(get_coupon_type() as $key => $value):
                                echo'<option value="'.$key.'"  '.($key == $result['cpn_type'] ? 'selected' : '').'>'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Percentage / Amount <span class="text-danger">*</span></label>
                        <input type="number" name="cpn_percent_amount" id="cpn_percent_amount" class="form-control" value="'.$result['cpn_percent_amount'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ckeditor" name="cpn_detail" required>'.html_entity_decode(html_entity_decode($result['cpn_detail'])).'</textarea>
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
<script>
    document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
        CKEDITOR.replace(element);
    });
</script>