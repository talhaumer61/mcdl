<?php 
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/functions.php";
    echo '<script src="assets/js/app.js"></script>';
    if(isset($_POST['id_country'])):
        $condition = array ( 
                            'select' 	    => "state_id, state_name",
                            'where' 	    => array(
                                                        'id_country'    =>  $_POST['id_country']
                                                        ,'is_deleted'   => 0 
                                                        ,'state_status' =>  1
                                                    ), 
                            'order_by' 	    => 'state_id ASC', 
                            'return_type' 	=> 'all' 
                           ); 
        $states    =   $dblms->getRows(STATES, $condition);
        echo '
        <label class="form-label">State <span class="text-danger">*</span></label>
        <select class="form-control" data-choices name="id_state" onchange="getSubstate(this.value)" required>
            <option value=""> Choose one</option>';
            foreach($states as $state):
                echo '<option value="'.$state['state_id'].'">'.$state['state_name'].'</option>';
            endforeach;
            echo '
        </select>'; 
    endif;
?>