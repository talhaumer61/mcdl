<?php 
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/functions.php";

require_once("../db.classes/settings.php");
$settingcls = new settings();



    echo '<script src="assets/js/app.js"></script>';
    if(isset($_POST['id_country'])):
        $states  = $settingcls->get_states(" AND state_status = '1' AND id_country = '".$_POST['id_country']."'");
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
