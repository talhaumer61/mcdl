<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

require_once("../../db.classes/settings.php");
$settingcls = new settings();
$Countries  = $settingcls->get_allcountry(" AND country_status = '1'");

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add City</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="cities.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">City Name <span class="text-danger">*</span></label>
                        <input type="text" name="city_name" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$_GET['ordering'].'" name="city_ordering" class="form-control" required="" readonly="">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="city_latitude" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="city_longitude" class="form-control" required />
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Code (Digit) <span class="text-danger">*</span></label>
                        <input type="number" name="city_codedigit" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code (Alpha) <span class="text-danger">*</span></label>
                        <input type="text" name="city_codealpha" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_country" onchange="getState(this.value)" required>
                            <option value=""> Choose one</option>';
                            foreach ($Countries as $country):
                                echo'<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2" id="state">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_state" onchange="getSubstate(this.value)" required>
                            <option value=""> Choose country first</option>';
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2" id="substate">
                        <label class="form-label">Substate <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_substate" onchange="getSubstate(this.value)" required>
                            <option value=""> Choose state first</option>';
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="city_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add City</button>
                </div>
            </div>
        </form>
    </div>
</div>';

