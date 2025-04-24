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
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Language <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="language_name" id="language_name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Speaking <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="speaking" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Listenting <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="listenting" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Writing <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="writing" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Reading <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="reading" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
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