<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add Faculty</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="faculties.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="faculty_name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Code <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_code"  type="text" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Ordering <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_ordering" value="'.$_GET['ordering'].'" readonly type="number" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Email <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_email"  type="email" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Phone <span class="text-danger">*</span></label>
                        <input class="form-control" name="faculty_phone" id="cleave-whatsapp" type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Icon <span class="text-danger">*</span></label>
                        <input type="file" name="faculty_icon" accept="image/*" class="form-control" required/>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" name="faculty_photo" accept="image/*" class="form-control" required/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Introduction <span class="text-danger">*</span></label>
                        <textarea name="faculty_intro" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="faculty_address" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="faculty_keyword" id="choices-text-remove-button" data-choices data-choices-limit="5" data-choices-removeItem/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea name="faculty_meta" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Publish <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="faculty_publish" data-placeholder="Select">
                            <option value="">Choose one</option>';
                            $statuses = get_is_publish();
                            foreach($statuses as $key => $status):
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices required name="faculty_status" data-placeholder="Select">
                            <option value="">Choose one</option>';
                            $statuses = get_status();
                            foreach($statuses as $key => $status):
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Faculty</button>
                </div>
            </div>
        </form>
    </div>
</div>';