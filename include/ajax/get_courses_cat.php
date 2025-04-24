<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_cat'])) {
    // COURSES
    $condition = array ( 
                             'select'       =>  'curs_id, curs_name, curs_code'
                            ,'where'        =>  array(
                                                         'id_cat'       => cleanvars($_POST['id_cat'])
                                                        ,'curs_status'  =>  '1'
                                                        ,'is_deleted'   =>  '0'
                                                    )
                            ,'order_by'     =>  'curs_name'
                            ,'return_type'  =>  'all'
                        );
    $COURSES = $dblms->getRows(COURSES, $condition);
    echo'
    <script src="assets/js/app.js"></script>
    <label class="form-label">Courses <span class="text-danger">*</span></label>
    <select class="form-control" data-choices required name="'.(isset($_POST['name'])?$_POST['name'].'"':'id_cur[]" multiple').'>';
    if($COURSES){
        echo'<option value="">Choose</option>';
        foreach($COURSES as $row) {
            echo'<option value="'.$row['curs_id'].'" '.(isset($_POST['admoff_degree']) && ($row['curs_id'] == $_POST['admoff_degree']) ? 'selected':'').'>'.$row['curs_name'].'</option>';
        }
    }else{
        echo'<option value="">No Record Found</option>';
    }
    echo'</select>';
}
?>