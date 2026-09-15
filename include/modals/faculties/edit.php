<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");

$dblms  = new dblms();

require_once("../../db.classes/courses.php");
$coursecls = new courses();

$result = $coursecls->get_faculty($_GET['faculty_id']);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Faculty</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="faculties.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="faculty_id" value="'.$result['faculty_id'].'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="faculty_name" value="'.$result['faculty_name'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="faculty_code" value="'.$result['faculty_code'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_ordering" value="'.$result['faculty_ordering'].'" readonly type="number" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Email <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_email"  type="email" value="'.$result['faculty_email'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Phone <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_phone" id="cleave-whatsapp" type="text" value="'.$result['faculty_phone'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Icon </label>
                        <input type="file" name="faculty_icon" accept="image/*" class="form-control"/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image </label>
                        <input type="file" name="faculty_photo" accept="image/*" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Introduction <span class="text-danger">*</span></label>
                        <textarea name="faculty_intro" class="form-control" required>'.$result['faculty_intro'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="faculty_address" class="form-control" required>'.$result['faculty_address'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="faculty_keyword" value="'.$result['faculty_keyword'].'" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="faculty_meta" class="form-control" required>'.$result['faculty_meta'].'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Publish <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="faculty_publish" data-placeholder="Select">
                            <option value="">Choose one</option>';
                            foreach(get_is_publish() as $key => $status):
                                echo'<option value="'.$key.'" '.($result['faculty_publish'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="faculty_status" data-placeholder="Select">
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($result['faculty_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Faculty</button>
                </div>
            </div>
        </form>
    </div>
</div>';