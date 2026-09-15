<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms = new dblms();

$condition  =   [
                    'select'       =>  'org_link_from, org_link_to',
                    'where'        =>   [
                                            'is_deleted'    => 0,
                                            'org_id'        => cleanvars($_GET['org_id']),
                                        ],
                    'return_type'  =>  'single'
                ];
$row = $dblms->getRows(SKILL_AMBASSADOR, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Change Expiry Date</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="org_id" value="'.cleanvars($_GET['org_id']).'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label class="form-label">Link Expiry <span class="text-danger">*</span></label>
                        <input type="text" name="org_referral_link_expiry" class="form-control" value="'.date('Y-m-d', strtotime($row['org_link_from'])).' to '.date('Y-m-d', strtotime($row['org_link_to'])).'" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-secondary btn-sm" name="submit_change_expiry_date"><i class="ri-edit-circle-line align-bottom me-1"></i>Change Expiry Date</button>
                </div>
            </div>
        </form>
    </div>
</div>';
