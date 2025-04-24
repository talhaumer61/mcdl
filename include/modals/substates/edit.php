<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'substate_id, substate_name, substate_ordering, substate_latitude, substate_longitude, id_state, id_country, substate_status'
                    ,'where'        =>  array(
                                                 'is_deleted'     => 0
                                                ,'substate_id'    => cleanvars($_GET['substate_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(SUB_STATES , $condition);

$countryCondition = array ( 
                            'select' 	=> "country_id, country_name",
                            'where' 	=> array( 
                                                    'is_deleted'        => 0 
                                                    ,'country_status'   =>  1
                                                ), 
                            'return_type' 	=> 'all' 
                            ); 
$Countries    =   $dblms->getRows(COUNTRIES, $countryCondition);
$stateCondition = array ( 
                            'select' 	=> "state_id, state_name",
                            'where' 	=> array( 
                                                        'is_deleted'    =>  0 
                                                    ,'state_status'     =>  1
                                                    ,'id_country'       =>  $row['id_country']
                                                ), 
                            'return_type' 	=> 'all' 
                        ); 
$States    =   $dblms->getRows(STATES, $stateCondition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Substate</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="substates.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="substate_id" value="'.$row['substate_id'].'"/>
            <div class="modal-body">
                 <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">State Name <span class="text-danger">*</span></label>
                        <input type="text" name="substate_name" value="'.$row['substate_name'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$row['substate_ordering'].'" name="substate_ordering" class="form-control" required="" readonly="">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_country" onchange="getState(this.value)" required>
                            <option value=""> Choose one</option>';
                            foreach ($Countries as $country):
                                echo'<option value="'.$country['country_id'].'" '.($row['id_country'] == $country['country_id'] ? 'selected' : '').'>'.$country['country_name'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2" id="state">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="id_state" required>
                            <option value=""> Choose one</option>';
                            foreach($States as $state):
                                echo'<option value="'.$state['state_id'].'" '.($row['id_state'] == $state['state_id'] ? 'selected' : '').'>'.$state['state_name'].'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="substate_latitude" value="'.$row['substate_latitude'].'" class="form-control" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="substate_longitude" value="'.$row['substate_longitude'].'" class="form-control" required />
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="substate_status" required>
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.($row['substate_status'] == $key ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Substate</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>