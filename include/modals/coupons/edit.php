<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  '*'
                    ,'where'        =>  array(
                                                 'is_deleted'     => 0
                                                ,'cpn_id'         => cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COUPONS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.$row['cpn_id'].'"/>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="cpn_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $row['cpn_status'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control flatpickr-input active" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.$row['cpn_start_date'].' to '.$row['cpn_end_date'].'" readonly="readonly">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="cpn_name" id="cpn_name" class="form-control" value="'.$row['cpn_name'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" name="cpn_code" id="cpn_code" class="form-control" value="'.$row['cpn_code'].'" required readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="cpn_type" required>
                            <option value=""> Choose one</option>';
                            foreach(get_coupon_type() as $key => $value):
                                echo'<option value="'.$key.'"  '.($key == $row['cpn_type'] ? 'selected' : '').'>'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Percentage / Amount <span class="text-danger">*</span></label>
                        <input type="number" name="cpn_percent_amount" id="cpn_percent_amount" class="form-control" value="'.$row['cpn_percent_amount'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Detail <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ckeditor" name="cpn_detail" required>'.html_entity_decode(html_entity_decode($row['cpn_detail'])).'</textarea>
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