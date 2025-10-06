<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$condition = array(
                     'select'       =>  'ch.*'
                    ,'where'        =>  array(
                                                 'ch.is_deleted'    => 0
                                                ,'ch.challan_id'    => cleanvars($_GET['challan_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(CHALLANS.' ch', $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark"><i class="ri-pencil-fill align-bottom me-1"></i>Edit Challan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="challan_id" value="'.$row['challan_id'].'" />
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Challan No</label>
                        <input class="form-control" type="text" value="'.$row['challan_no'].'" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Due Date</label>
                        <input type="text" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="Y-m-d" name="due_date" value="'.$row['due_date'].'" data-mindate="'.$row['due_date'].'" readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-send-plane-fill align-bottom me-1"></i>Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>