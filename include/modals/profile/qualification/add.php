<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
include "../../../functions/login_func.php";
$dblms = new dblms();
checkCpanelLMSALogin();

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Degree Level <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_degree" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_edulevel() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Program Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="program" id="program" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Major Subjects <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="subjects" id="subjects" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Institute/Borad <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="institute" id="institute" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Grade/GPA <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="grade" id="grade" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="year" id="year" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Result Card</label>
                        <input class="form-control" type="file" name="resultcard" id="resultcard" accept=".pdf, .doc, .docx, .png, .jpg, .jpeg">
                        <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 1MB.</p>
                        <span class="text-danger fw-bold" style="font-size: 12px;">(pdf, doc, docx, png, jpg, jpeg)</span>
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
include('../../../teacher/profile/qualification/script.php');
?>