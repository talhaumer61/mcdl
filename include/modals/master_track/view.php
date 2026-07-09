<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();

$condition = array ( 
                         'select' 	    =>  'mas_name, mas_photo, mas_shortdetail, mas_detail, mas_prg_detail, id_skills'
                        ,'where' 	    =>  array(  
                                                    'mas_id'    => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(MASTER_TRACK, $condition);

// COURSES
$condition = array ( 
                         'select' 	    =>  'curs_name'
                        ,'join'         =>  'INNER JOIN '.COURSES.' ON id_curs = curs_id'
                        ,'where' 	    =>  array(  
                                                    'id_mas'    => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'all' 
                    ); 
$courses = $dblms->getRows(MASTER_TRACK_DETAIL, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Master Track Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Name:</b></h6>
            <p>'.$row['mas_name'].'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Image:</b></h6>
            <figure class="figure">
                <img src="uploads/images/admissions/master_track/'.$row['mas_photo'].'" class="figure-img img-fluid rounded" alt="...">
            </figure>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Skills:</b></h6>
            <ul class="list-group">';
                foreach ($courses as $course) {
                    echo '<li class="list-group-item">'.$course['curs_name'].'</li>';
                }
                echo'
            </ul>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Short Detail:</b></h6>
            <div>'.(!empty($row['mas_shortdetail']) ? html_entity_decode(html_entity_decode($row['mas_shortdetail'])) : 'Short Detail not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Detail:</b></h6>
            <div>'.(!empty($row['mas_detail']) ? html_entity_decode(html_entity_decode($row['mas_detail'])) : 'Detail not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Program Detail:</b></h6>
            <div>'.(!empty($row['mas_prg_detail']) ? html_entity_decode(html_entity_decode($row['mas_prg_detail'])) : 'Program Detail not added').'</div>
        </div>
    </div>
</div>';
?>