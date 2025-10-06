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
                        <select class="form-control" data-choices name="status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == 1 ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices id="type" name="type" required>
                            <option value=""> Choose one</option>';
                            foreach(get_QnsType() as $keyType => $valType):
                                if($keyType != '2'){
                                    echo'<option value="'.$keyType.'">'.$valType.'</option>';
                                }
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Question <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="question" name="question" required></textarea>
                    </div>
                </div>
                <div class="row" id="multipleCh" style="display: none;">
                    <div class="col mb-2">
                        <label class="form-label">Options</label>
                        <table class="table table-bordered table-nowrap align-middle">
                            <tr>
                                <th width="40" class="text-center">Sr.</th>
                                <th class="text-center">Options</th>
                            </tr>';
                            for ($i=1; $i<=5 ; $i++) { 
                                echo'
                                <tr>
                                    <td class="text-center">'.$i.'</td>
                                    <td class="text-center"><input class="form-control" name="options['.$i.']"></td>
                                </tr>';
                            }
                            echo'
                        </table>  
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
require_once ("../../headoffice/feedback_questions/script.php");
?>