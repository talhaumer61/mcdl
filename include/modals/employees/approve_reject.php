<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$title  = ($_GET['emply_request'] == '1' ? 'Approve' : 'Reject');
$modal  = ($_GET['emply_request'] == '1' ? 'success' : 'danger');
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-'.$modal.' p-3">
            <h5 class="modal-title"><i class="ri-send-plane-fill align-bottom me-1"></i>'.$title.' Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="emply_id" value="'.$_GET['emply_id'].'" />
            <input type="hidden" name="emply_name" value="'.$_GET['emply_name'].'" />
            <input type="hidden" name="emply_request" value="'.$_GET['emply_request'].'" />
            <input type="hidden" name="adm_id" value="'.$_GET['adm_id'].'" />
            <div class="modal-body">
                <h5 class="fs-15 pt-3 pb-3">Do you realy want to '.$title.' <span class="text-'.$modal.'">'.$_GET['emply_name'].'</span> as Instructor?</h5>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-'.$modal.' btn-sm" name="approve_reject"><i class="ri-send-plane-fill align-bottom me-1"></i>'.$title.'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>