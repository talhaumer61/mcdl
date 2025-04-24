<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'state_id, state_status, state_ordering, state_name, state_codedigit, state_codealpha, state_latitude, state_longitude, id_country'
                    ,'where'        =>  array(
                                                 'is_deleted'     => 0
                                                ,'state_id'         => cleanvars($_GET['state_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(STATES , $condition);

 $condition = array ( 
                        'select'        =>  'country_id, country_name',
                        'where'         =>  array(
                                                    'country_status'    =>  1
                                                    ,'is_deleted'       =>  0
                                                ), 
                        'order_by' 		=>  'country_name',
                        'return_type'   =>  'all'
                    ); 
$Countries = $dblms->getRows(COUNTRIES, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit State</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="states.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="state_id" value="'.$row['state_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">State Name <span class="text-danger">*</span></label>
                        <input type="text" name="state_name" value="'.$row['state_name'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$row['state_ordering'].'" name="state_ordering" class="form-control" required="" readonly="">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Code (Digit) <span class="text-danger">*</span></label>
                        <input type="number" name="state_codedigit" value="'.$row['state_codedigit'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Code (Alpha) <span class="text-danger">*</span></label>
                        <input type="text" name="state_codealpha" value="'.$row['state_codealpha'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="state_latitude" value="'.$row['state_latitude'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="state_longitude" value="'.$row['state_longitude'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_country" required>
                            <option value=""> Choose one</option>';
                            foreach ($Countries as $country):
                                echo'<option value="'.$country['country_id'].'" '.($row['id_country'] == $country['country_id'] ? 'selected' : '').'>'.$country['country_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="state_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($row['state_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit State</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>