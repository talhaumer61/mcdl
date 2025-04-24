<?php 
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
include "../functions/functions.php";
$dblms = new dblms();

echo '<script src="assets/js/app.js"></script>';
if($_POST['method'] == '_GET_SKILL_AMBASSADOR'){
    if($_POST['org_type'] == 2){
        $condition  =   [ 
                            'select'        =>  'o.org_id, o.org_name, o.org_reg',
                            'where' 	    =>  [
                                                    'o.org_type'            => 1,
                                                    'o.org_status'          => 1,
                                                    'o.allow_add_members'   => 1,
                                                    'o.is_deleted'          => 0,
                                                ],
                            'not_equal'     =>  [
                                                    'o.org_id'              =>  $_POST['edit_id']
                                                ],
                            'return_type'   =>  'all',
        ]; 
        $SKILL_AMBASSADOR = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition);
        if($SKILL_AMBASSADOR){
            echo '
            <label class="form-label">Organizations <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="parent_org" required>
                <option value=""> Choose one</option>';
                foreach($SKILL_AMBASSADOR as $valOrg):
                    echo '<option value="'.$valOrg['org_id'].'">'.$valOrg['org_name'].' ('.$valOrg['org_reg'].')</option>';
                endforeach;
                echo '
            </select>'; 
        } else {
            echo '
            <label class="form-label">Organizations <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="parent_org" required>
                <option value=""> No Record Found...</option>
            </select>'; 
        }
    } else if ($_POST['org_type'] == 1){        
        echo '
        <label class="form-label">Allow Sub Members <span class="text-danger">*</span></label>
        <select class="form-control" data-choices name="allow_add_members" required>
            <option value=""> Choose one</option>';
            foreach(get_YesNoStatus() as $key => $value):
                echo '<option value="'.$key.'">'.$value.'</option>';
            endforeach;
            echo '
        </select>'; 
    }
}
?>