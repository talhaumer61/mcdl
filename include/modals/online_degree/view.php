<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();
$condition = array ( 
                        'select' 	    =>  'deg_name,deg_photo,deg_shortdetail,deg_detail'
                        ,'where' 	    =>  array(  
                                                    'deg_id'            => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(DEGREE, $condition);

$coursescondition = array ( 
    'select'       =>  'curs_name, curs_code'
   ,'where'        =>  array(
                                'curs_status'  =>  1
                               ,'is_deleted'   =>  0
                       )
   ,'return_type'  =>  'single'
);

$condition = array ( 
    'select'       =>  'd.id_curs, cs.cat_name, cs.cat_id'
    ,'where'       => array(
                                 'id_deg'       => cleanvars($_GET['view_id'])
                            )
    ,'join'         => 'inner join '.COURSES_CATEGORIES.' cs on cs.cat_id=d.id_cat'
    ,'return_type'  =>  'all'
); 

echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Degree Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Name:</b></h6>
            <p>'.$row['deg_name'].'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Image:</b></h6>
            <figure class="figure">
                <img src="uploads/images/admissions/online_degree/photo/'.$row['deg_photo'].'" class="figure-img img-fluid rounded" alt="...">
            </figure>
            <hr>
            <hr>';
            foreach (get_degree_course_type() as $key => $value) {
                $condition['where']['id_curstype']=$key;
                $DEGREE_DETAIL = $dblms->getRows(DEGREE_DETAIL.' d', $condition);
                echo '<h6 class="text-muted text-uppercase fw-semibold"><b>'.$value.':</b></h6>
                        <ul class="list-group">';
                        if ($DEGREE_DETAIL) {
                            foreach ($DEGREE_DETAIL as $detail) {
                                foreach (explode(',',$detail['id_curs']) as $curs) {
                                    $coursescondition['where']['curs_id'] = $curs;
                                    $COURSES = $dblms->getRows(COURSES, $coursescondition);
                                    echo '<li class="list-group-item">'.$COURSES['curs_name'].' ('.$COURSES['curs_code'].') ( '.$detail['cat_name'].' )</li>';
                                }
                            }
                        }
                        else {
                            echo '<li class="list-group-item text-danger"><b>No Record Found</b></li>';
                        }
                echo '</ul>
                <hr>';
            }
            echo '
            <h6 class="text-muted text-uppercase fw-semibold"><b>Short Detail:</b></h6>
            <div>'.(!empty($row['deg_shortdetail']) ? html_entity_decode(html_entity_decode($row['deg_shortdetail'])) : 'Short Detail not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Detail:</b></h6>
            <div>'.(!empty($row['deg_detail']) ? html_entity_decode(html_entity_decode($row['deg_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
?>