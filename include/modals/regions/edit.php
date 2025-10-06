<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

require_once("../../db.classes/settings.php");
$settingcls = new settings();

$result = $settingcls->get_region($_GET['region_id']);


echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Region</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="regions.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="region_id" value="'.$result['region_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$result['region_ordering'].'" name="region_ordering" id="region_ordering" class="form-control" required="" readonly="">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$result['region_name'].'" type="text" name="region_name" placeholder="Enter Name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Code (Digit) <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$result['region_codedigit'].'" name="region_codedigit" type="number" placeholder="Enter Code (Digit)" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Code (Alpha) <span class="text-danger">*</span></label>
                        <input class="form-control" value="'.$result['region_codealpha'].'" name="region_codealpha" type="text" placeholder="Enter Code (Alpha)" required>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Parent Region  <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_parentregion" required>
                            <option value=""> Choose one</option>';
                            foreach(get_parentregiontypes() as $key => $parentregion):
                                echo'<option value="'.$key.'" '.($result['id_parentregion'] == $key ? 'selected' : '').'>'.$parentregion.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="region_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($result['region_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Region</button>
                </div>
            </div>
        </form>
    </div>
</div>';