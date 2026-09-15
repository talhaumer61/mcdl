<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="not_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control flatpickr-input active" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" readonly="readonly">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="not_title" id="not_title" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ckeditor" name="not_description" required></textarea>
                    </div>
                </div>
                <!-- Notification Display Location -->
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Show Notification To <span class="text-danger">*</span></label>
                        <div>';
                            foreach (get_display_location() as $key => $value) {
                                echo'
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="display_location[]" id="display_location'.$key.'" value="'.$key.'">
                                    <label class="form-check-label" for="display_location'.$key.'">'.$value.'</label>
                                </div>';
                            }
                            echo'
                        </div>
                    </div>
                </div>
                <!-- Notification Audience -->
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Show Notification To <span class="text-danger">*</span></label>
                        <div>';
                            foreach (get_display_audience() as $key => $value) {
                                echo'
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="display_audience[]" id="display_audience'.$key.'" value="'.$key.'">
                                    <label class="form-check-label" for="display_audience'.$key.'">'.$value.'</label>
                                </div>';
                            }
                            echo'
                        </div>
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
<script>
    document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
        CKEDITOR.replace(element);
    });
</script>