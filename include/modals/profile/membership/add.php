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
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Organization <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="organization" id="organization" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Designation <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="designation" id="designation" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Membership No <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="memno" id="memno" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Start Date <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="startdate" id="startdate" required data-provider="flatpickr" data-date-format="Y-m-d">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">End Date</label>
                        <input class="form-control" type="text" name="enddate" id="enddate" data-provider="flatpickr" data-date-format="Y-m-d">
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