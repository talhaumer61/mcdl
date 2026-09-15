<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  '*'
                    ,'where'        =>  array(
                                                 'is_deleted'   => 0
                                                ,'id'           => cleanvars(LMS_EDIT_ID)
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(INQUIRIES, $condition);
echo'
<script src="assets/js/app.js"></script>

<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.$row['id'].'"/>
            <div class="modal-body">
                <div class="row">

                    <!-- FORM NUMBER -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inquiry Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="inquiry_no" id="inquiry_no" value="'.$row['inquiry_no'].'" readonly>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="status" required>
                            <option value="">Choose one</option>';
                            foreach (get_inquiry_status() as $key => $status) {
                                echo '<option value="'.$key.'" '.($key == $row['status'] ? 'selected' : '').'>'.$status.'</option>';
                            }
                            echo'
                        </select>
                    </div>

                    <!-- TYPE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="type" required>
                            <option value="">Choose one</option>';
                            foreach (get_inquiry_type() as $key => $type) {
                                echo '<option value="'.$key.'" '.($key == $row['type'] ? 'selected' : '').'>'.$type.'</option>';
                            }
                            echo'
                        </select>
                    </div>

                    <!-- SOURCE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Source <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="source" required>
                            <option value="">Choose one</option>';
                            foreach (get_inquiry_source() as $key => $source) {
                                echo '<option value="'.$key.'" '.($key == $row['source'] ? 'selected' : '').'>'.$source.'</option>';
                            }
                            echo'
                        </select>
                    </div>

                    <!-- FULL NAME -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" value="'.$row['fullname'].'" required>
                    </div>

                    <!-- FATHER NAME -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Father Name</label>
                        <input type="text" name="fathername" class="form-control" value="'.$row['fathername'].'">
                    </div>

                    <!-- PHONE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="'.$row['phone'].'" required>
                    </div>

                    <!-- DATE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inquiry Date</label>
                        <input type="text" class="form-control flatpickr-input active" name="dated" data-provider="flatpickr" data-date-format="Y-m-d" value="'.date('Y-m-d', strtotime($row['dated'])).'" required readonly="readonly">
                    </div>

                    <!-- REMARKS -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" rows="4" name="remarks">'.$row['remarks'].'</textarea>
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