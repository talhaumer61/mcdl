<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="emply_id value="'.cleanvars($_GET['emply_id']).'">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Naw Password <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="adm_userpass" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit_password"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Password</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>