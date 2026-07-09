<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

require_once("../../db.classes/settings.php");
$settingcls = new settings();

$result     = $settingcls->get_city($_GET['city_id']);
$Countries  = $settingcls->get_allcountry(" AND country_status = '1'");
$States     = $settingcls->get_states(" AND state_status = '1' AND id_country = '".$result['id_country']."'");
$subStates  = $settingcls->get_substates(" AND substate_status = '1' AND id_state = '".$result['id_state']."'");

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit City</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="cities.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="city_id" value="'.$result['city_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">City Name <span class="text-danger">*</span></label>
                        <input type="text" name="city_name" value="'.$result['city_name'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$result['city_ordering'].'" name="city_ordering" class="form-control" required="" readonly="">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="city_latitude" value="'.$result['city_latitude'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="city_longitude" value="'.$result['city_longitude'].'" class="form-control" required />
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Code (Digit) <span class="text-danger">*</span></label>
                        <input type="number" name="city_codedigit" value="'.$result['city_codedigit'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code (Alpha) <span class="text-danger">*</span></label>
                        <input type="text" name="city_codealpha" value="'.$result['city_codealpha'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_country" onchange="getState(this.value)" required>
                            <option value=""> Choose one</option>';
                            foreach ($Countries as $country):
                                echo'<option value="'.$country['country_id'].'" '.($result['id_country'] == $country['country_id'] ? 'selected' : '').'>'.$country['country_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2" id="state">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_state" onchange="getSubstate(this.value)" required>
                            <option value=""> Choose one</option>';
                            foreach ($States as $state):
                                echo'<option value="'.$state['state_id'].'" '.($result['id_state'] == $state['state_id'] ? 'selected' : '').'>'.$state['state_name'].'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2" id="substate">
                        <label class="form-label">Substate <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_substate"  required>
                            <option value=""> Choose one</option>';
                            foreach ($subStates as $substate):
                                echo'<option value="'.$substate['substate_id'].'" '.($result['id_substate'] == $substate['substate_id'] ? 'selected' : '').'>'.$substate['substate_name'].'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="city_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($result['city_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit City</button>
                </div>
            </div>
        </form>
    </div>
</div>';
