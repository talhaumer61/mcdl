<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_offering_type'])) {
    $id =  $_POST['id_offering_type'];
    echo '
    <script src="assets/js/app.js"></script>';
    if ($id == 1) {
        // PROGRAM CATEGORIES
        $condition = array ( 
                                 'select'       =>  'cat_id, cat_name'
                                ,'where'        =>  array(
                                                            'cat_status'  =>  '1'
                                                            ,'is_deleted'   =>  '0'
                                                        )
                                ,'order_by'     =>  'cat_name'
                                ,'return_type'  =>  'all'
                            ); 
        $PROGRAMS_CATEGORIES = $dblms->getRows(PROGRAMS_CATEGORIES, $condition);        
        echo'
        <div class="row">
            <div class="col">
                <label class="form-label">Program Categories <span class="text-danger">*</span></label>
                <select class="form-control" data-choices required onchange="get_program(this.value,\'admoff_degree\')" name="id_cat">';
                    if($PROGRAMS_CATEGORIES){
                            echo'<option value="">Choose one</option>';
                        foreach($PROGRAMS_CATEGORIES as $row) {
                            echo'<option value="'.$row['cat_id'].'" '.(($row['cat_id'] == $_POST['id_cat']) ? 'selected':'').'>'.$row['cat_name'].'</option>';
                        }
                    }else{
                        echo'<option value="">No Record Found</option>';
                    }
                    echo'   
                </select>
            </div>
            <div class="col" id="curs"></div>
        </div>
        <script>';
            if (!empty($_POST['admoff_degree'])) {
                echo '$(document).ready(function(){get_program('.$_POST['id_cat'].',\'admoff_degree\','.$_POST['admoff_degree'].')})';
            }
            echo '
            function get_program(id_cat,name,admoff_degree=0){
                $.ajax({
                    url : "include/ajax/get_program.php",
                    type : "POST",
                    data : {id_cat,name,admoff_degree},
                    success : function(response){
                        $("#curs").html(response);
                    }
                })
            }
        </script>';
    } elseif ($id == 2) {
        // Master Track
        $condition = array ( 
                                 'select'       =>  'mstcat_id, mstcat_name'
                                ,'where'        =>  array(
                                                            'mstcat_status'  =>  '1'
                                                            ,'is_deleted'   =>  '0'
                                                        )
                                ,'order_by'     =>  'mstcat_name'
                                ,'return_type'  =>  'all'
                            ); 
        $MASTER_TRACK_CATEGORY = $dblms->getRows(MASTER_TRACK_CATEGORIES, $condition);
        echo'
        <div class="row">
            <div class="col">
                <label class="form-label">Master Track <span class="text-danger">*</span></label>
                <select class="form-control" data-choices required onchange="get_master_track(this.value,\'admoff_degree\')" name="id_cat">';
                    if ($MASTER_TRACK_CATEGORY) {
                        echo'
                        <option value="">Choose one</option>';
                        foreach($MASTER_TRACK_CATEGORY as $row) {
                            echo'
                            <option value="'.$row['mstcat_id'].'" '.(($row['mstcat_id'] == $_POST['id_cat']) ? 'selected':'').'>'.$row['mstcat_name'].'</option>';
                        }
                    } else {
                        echo'
                        <option value="">No Record Found</option>';
                    }
                    echo'   
                </select>
            </div>
            <div class="col" id="curs"></div>
        </div>
        <script>';
            if (!empty($_POST['admoff_degree'])) {
                echo '$(document).ready(function(){get_master_track('.$_POST['id_cat'].',\'admoff_degree\','.$_POST['admoff_degree'].')})';
            }
            echo '
            function get_master_track(id_mstcat,name,admoff_degree=0){
                $.ajax({
                    url : "include/ajax/get_master_track.php",
                    type : "POST",
                    data : {id_mstcat,name,admoff_degree},
                    success : function(response){
                        $("#curs").html(response);
                    }
                })
            }
        </script>';
    } elseif ($id == 3) {
        // Course
        $condition = array ( 
                                 'select'       =>  'cat_id, cat_name'
                                ,'where'        =>  array(
                                                             'cat_status'   =>  '1'
                                                            ,'id_type'      =>  '1'
                                                            ,'is_deleted'   =>  '0'
                                                        )
                                ,'order_by'     =>  'cat_name'
                                ,'return_type'  =>  'all'
                            ); 
        $CATEGORIES = $dblms->getRows(COURSES_CATEGORIES, $condition);
        
        echo'
        <div class="row">
            <div class="col">
                <label class="form-label">Courses Category <span class="text-danger">*</span></label>
                <select class="form-control" data-choices required onchange="get_courses(this.value,\'admoff_degree\')" name="id_cat">';
                    if($CATEGORIES){
                        echo'
                        <option value="">Choose one</option>';
                        foreach($CATEGORIES as $row) {
                            echo'
                            <option value="'.$row['cat_id'].'" '.(($row['cat_id'] == $_POST['id_cat']) ? 'selected':'').'>'.$row['cat_name'].'</option>';
                        }
                    } else {
                        echo'
                        <option value="">No Record Found</option>';
                    }
                    echo'   
                </select>
            </div>
            <div class="col" id="curs"></div>
        </div>
        <script>';
            if (!empty($_POST['admoff_degree'])) {
                echo '$(document).ready(function(){get_courses('.$_POST['id_cat'].',\'admoff_degree\','.$_POST['admoff_degree'].')})';
            }
            echo '
            function get_courses(id_cat,name,admoff_degree=0){
               $.ajax({
                    url : "include/ajax/get_courses_cat.php",
                    type : "POST",
                    data : {id_cat,name,admoff_degree},
                    success : function(response){
                        $("#curs").html(response);
                    }
              })
            }
        </script>';
    } elseif ($id == 4) {
        // e-training
        $condition = array ( 
                                 'select'       =>  'cat_id, cat_name'
                                ,'where'        =>  array(
                                                             'cat_status'   =>  '1'
                                                            ,'is_deleted'   =>  '0'
                                                            ,'id_type'      =>  '2'
                                                        )
                                ,'order_by'     =>  'cat_name'
                                ,'return_type'  =>  'all'
                            ); 
        $CATEGORIES = $dblms->getRows(COURSES_CATEGORIES, $condition);        
        echo'
        <div class="row">
            <div class="col">
                <label class="form-label">Courses Category <span class="text-danger">*</span></label>
                <select class="form-control" data-choices required onchange="get_courses(this.value,\'admoff_degree\')" name="id_cat">';
                    if($CATEGORIES){
                        echo'
                        <option value="">Choose one</option>';
                        foreach($CATEGORIES as $row) {
                            echo'
                            <option value="'.$row['cat_id'].'" '.(($row['cat_id'] == $_POST['id_cat']) ? 'selected':'').'>'.$row['cat_name'].'</option>';
                        }
                    } else {
                        echo'
                        <option value="">No Record Found</option>';
                    }
                    echo'   
                </select>
            </div>
            <div class="col" id="curs"></div>
        </div>
        <script>';
            if (!empty($_POST['admoff_degree'])) {
                echo '$(document).ready(function(){get_courses('.$_POST['id_cat'].',\'admoff_degree\','.$_POST['admoff_degree'].')})';
            }
            echo '
            function get_courses(id_cat,name,admoff_degree=0){
               $.ajax({
                    url : "include/ajax/get_courses_cat.php",
                    type : "POST",
                    data : {id_cat,name,admoff_degree},
                    success : function(response){
                        $("#curs").html(response);
                    }
              })
            }
        </script>';
    }
}
?>