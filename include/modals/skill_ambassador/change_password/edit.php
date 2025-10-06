<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header bg-danger p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="adm_id" value="'.cleanvars($_GET['adm_id']).'">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="text" name="adm_userpass" class="form-control" required="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-warning btn-sm" name="submit_change_password"><i class="ri-edit-circle-line align-bottom me-1"></i>Change Password</button>
                </div>
            </div>
        </form>
    </div>
</div>';
