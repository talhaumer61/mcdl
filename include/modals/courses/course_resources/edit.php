<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
$dblms = new dblms();

include "../../../db.classes/courses.php";
$coursecls = new courses();
$result    = $coursecls->get_lessondownload(LMS_EDIT_ID);

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.LMS_EDIT_ID.'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Resourse Type <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required id="id_type" name="id_type">
                            <option value="">Choose one</option>';
                            foreach(get_CourseResources() as $key => $value):
                                echo'<option value="'.$key.'" '.($result['id_type'] == $key ? 'selected' : '').'>'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="status">
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $value):
                                echo'<option value="'.$key.'" '.($result['status'] == $key ? 'selected' : '').'>'.$value.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div id="resources_form">
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="file_name" value="'.$result['file_name'].'" required/>
                        </div>';
                        if($result['id_type'] == 1 || $result['id_type'] == 5){
                            echo'
                            <div class="col mb-2">
                                <label class="form-label">Open With <span class="text-danger">*</span></label>
                                <select class="form-control" data-choices required id="open_with" name="open_with">
                                    <option value="">Choose one</option>';
                                    foreach($fileopenwith as $key => $value):
                                        echo'<option value="'.$value.'" '.($result['open_with'] == $value ? 'selected' : '').'>'.$value.'</option>';
                                    endforeach;
                                    echo'
                                </select>
                            </div>';
                        }
                        echo'
                    </div>';
                    if($result['id_type'] == 1 || $result['id_type'] == 5){
                        echo'
                        <div class="row">
                            <div class="col mb-2">
                                <label class="form-label">Attach File <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg, .rar, .zip" name="file" id="fileInput">
                                <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
                                <div class="text-primary mt-2">Upload valid files. Only <span class="text-danger fw-bold">pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg, rar, zip</span> are allowed.</div>
                            </div>
                        </div>';
                    }
                    if($result['id_type'] == 2){
                        echo'      
                        <div class="row">
                            <div class="col mb-2">
                                <label class="form-label">Embed Video Link <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="embedcode">'.$result['embedcode'].'</textarea>
                            </div>
                        </div>';
                    }
                    if($result['id_type'] == 3 || $result['id_type'] == 4){
                        echo'      
                        <div class="row">
                            <div class="col mb-2">
                                <label class="form-label">'.($result['id_type'] == 3 ? 'Drive Link' : 'URL').' <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="url">'.$result['url'].'</textarea>
                            </div>
                        </div>';
                    }
                    echo'
                    <div class="row">
                        <div class="col mb-2">
                            <label class="form-label">Detail <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="detail">'.$result['detail'].'</textarea>
                        </div>
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
