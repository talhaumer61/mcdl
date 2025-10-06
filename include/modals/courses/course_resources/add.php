<?php
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Resourse Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required id="id_type" name="id_type">
                            <option value="">Choose one</option>';
                            foreach(get_CourseResources() as $key => $value):
                                echo'<option value="'.$key.'">'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="status">
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $value):
                                echo'<option value="'.$key.'">'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div id="resources_form">
                    <h5 class="card-body text-center bg-body border rounded-2 mt-3"> Select Resource Type</h5>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Resource</button>
                </div>
            </div>
        </form>
    </div>
</div>';
include_once ('../../../teacher/courses/course_resources/script.php');
?>
