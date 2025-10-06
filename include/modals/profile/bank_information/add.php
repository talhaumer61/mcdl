<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Account Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_account_name" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="bank_name" required=""="">
                            <option value=""> Choose one</option>';
                            foreach(get_bank() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="bank_account_no" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">IBAN Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_account_iban_no" required="">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_branch_name" required="">
                    </div>
                    <div class="col">
                        <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="bank_branch_code" required="">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label" for="card-name">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="bank_status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label" for="card-name">Detail</label>
                        <textarea class="form-control" name="bank_account_detail"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>