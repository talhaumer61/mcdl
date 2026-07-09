<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

require_once("../../db.classes/settings.php");
$settingcls = new settings();

$Curriencies    = $settingcls->get_currencies(" AND currency_status = '1'");
$Regions        = $settingcls->get_regions(" AND region_status = '1'");

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg ">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add Country</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="countries.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" name="country_name" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$_GET['ordering'].'" name="country_ordering" id="country_ordering" class="form-control" required="" readonly="">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Calling Code <span class="text-danger">*</span></label>
                        <input type="text" name="country_callingcode" id="country_callingcode" class="form-control" required="" >
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">ISO (2 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="country_iso2digit" id="country_iso2digit" class="form-control" required="" >
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">ISO (3 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="country_iso3digit" id="country_iso3digit" class="form-control" required="" >
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="country_latitude" id="country_latitude" class="form-control" required="" >
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="country_longitude" id="country_longitude" class="form-control" required="" >
                    </div>
                </div>

                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Timezone <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_timezone" required>
                            <option value=""> Choose one</option>';
                            foreach(get_timezonetypes() as $key => $tz):
                                echo'<option value="'.$key.'">'.$tz.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="id_currency" required>
                        <option value=""> Choose one</option>';
                        foreach($Curriencies as $currency):
                            echo'<option value="'.$currency['currency_id'].'">'.$currency['currency_name'].'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                </div>

                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Region <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_region" required>
                            <option value=""> Choose one</option>';
                            foreach($Regions as $region):
                                echo'<option value="'.$region['region_id'].'">'.$region['region_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="country_status" required>
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
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Country</button>
                </div>
            </div>
        </form>
    </div>
</div>';