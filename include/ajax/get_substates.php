<?php 
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/functions.php";
    echo '<script src="assets/js/app.js"></script>';
    if(isset($_POST['id_state'])):
        echo '
        <label class="form-label">Substate <span class="text-danger">*</span></label>
        <select class="form-control" data-choices name="id_substate" required>
        <option value="">Select</option>';
        $sqllms	= $dblms->querylms("SELECT substate_id, substate_name 
                            FROM ".SUB_STATES."
                            WHERE id_state = '".$_POST['id_state']."'
                            AND id_deleted = '0' AND substate_status = '1'
                            ORDER BY substate_id ASC");
                            
        while($rowvalues = mysqli_fetch_array($sqllms)):
            echo '<option value="'.$rowvalues['substate_id'].'">'.$rowvalues['substate_name'].'</option>';
        endwhile;
    endif;
?>