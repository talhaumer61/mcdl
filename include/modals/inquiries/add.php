<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$ym = date('ym');
$prefix = "INQ-$ym-";
$sqlQuery = $dblms->querylms("
    SELECT 
        CONCAT(
            '$prefix',
            IFNULL(
                LPAD(
                    MAX(CAST(SUBSTRING(inquiry_no, -4) AS UNSIGNED)) + 1,
                    4,
                    '0'
                ),
                '0001'
            )
        ) AS next_inquiry_no
    FROM ".INQUIRIES."
    WHERE inquiry_no LIKE '$prefix%'
");
$valQuery = mysqli_fetch_array($sqlQuery);
$inquiry_no = $valQuery['next_inquiry_no'];

echo'
<script src="assets/js/app.js"></script>

<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title">
                <i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form autocomplete="off" method="post" class="form-validate">
            <div class="modal-body">
                <div class="row">

                    <!-- FORM NUMBER -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inquiry Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="inquiry_no" id="inquiry_no" value="'.$inquiry_no.'" readonly>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="status" required>
                            <option value="">Choose one</option>';
                            foreach (get_inquiry_status() as $key => $status) {
                                echo '<option value="'.$key.'">'.$status.'</option>';
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
                                echo '<option value="'.$key.'">'.$type.'</option>';
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
                                echo '<option value="'.$key.'">'.$source.'</option>';
                            }
                            echo'
                        </select>
                    </div>

                    <!-- FULL NAME -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>

                    <!-- FATHER NAME -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Father Name</label>
                        <input type="text" name="fathername" class="form-control">
                    </div>

                    <!-- PHONE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <!-- DATE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inquiry Date</label>
                        <input type="text" class="form-control flatpickr-input active" name="dated" data-provider="flatpickr" data-date-format="Y-m-d" required readonly="readonly">
                    </div>

                    <!-- REMARKS -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" rows="4" name="remarks"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</button>
            </div>
        </form>
    </div>
</div>';
?>