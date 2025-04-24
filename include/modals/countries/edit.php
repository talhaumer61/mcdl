<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'country_id, country_name, country_ordering, country_iso2digit, country_iso3digit,country_callingcode,country_latitude,country_longitude,id_timezone,id_currency, id_region, country_status'
                    ,'where'        =>  array(
                                                 'is_deleted'     => 0
                                                ,'country_id'         => cleanvars($_GET['country_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COUNTRIES , $condition);

$currencyCondition = array ( 
                                'select' 	=> "currency_id, currency_name",
                                'where' 	=> array( 
                                                        'is_deleted'        => 0 
                                                        ,'currency_status'  =>  1
                                                    ), 
                                'return_type' 	=> 'all' 
                            ); 
$Curriencies    =   $dblms->getRows(CURRENCIES, $currencyCondition);
$regionCondition = array ( 
                            'select' 	=> "region_id, region_name",
                            'where' 	=> array( 
                                                    'is_deleted'        => 0 
                                                    ,'region_status'  =>  1
                                                ), 
                            'return_type' 	=> 'all' 
                        ); 
$Regions    =   $dblms->getRows(REGIONS, $regionCondition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Country</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="countries.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="country_id" value="'.$row['country_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" name="country_name" value="'.$row['country_name'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$row['country_ordering'].'" name="country_ordering" id="country_ordering" class="form-control" required="" readonly="">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Calling Code <span class="text-danger">*</span></label>
                        <input type="text" name="country_callingcode" value="'.$row['country_callingcode'].'" id="country_callingcode" class="form-control" required="" >
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">ISO (2 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="country_iso2digit" value="'.$row['country_iso2digit'].'" id="country_iso2digit" class="form-control" required="" >
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">ISO (3 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="country_iso3digit" value="'.$row['country_iso3digit'].'" id="country_iso3digit" class="form-control" required="" >
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="country_latitude" value="'.$row['country_latitude'].'" id="country_latitude" class="form-control" required="" >
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="country_longitude" value="'.$row['country_longitude'].'" id="country_longitude" class="form-control" required="" >
                    </div>
                </div>

                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Timezone <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_timezone" required>
                            <option value=""> Choose one</option>';
                            foreach(get_timezonetypes() as $key => $tz):
                                echo'<option value="'.$key.'" '.($row['id_timezone'] == $key ? 'selected' : '').'>'.$tz.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="id_currency" required>
                        <option value=""> Choose one</option>';
                        foreach($Curriencies as $currency):
                            echo'<option value="'.$currency['currency_id'].'" '.($row['id_currency'] == $currency['currency_id'] ? 'selected' : '').'>'.$currency['currency_name'].'</option>';
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
                                echo'<option value="'.$region['region_id'].'" '.($row['id_region'] == $region['region_id'] ? 'selected' : '').'>'.$region['region_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="country_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($row['country_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Country</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>