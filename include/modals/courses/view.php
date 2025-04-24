<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();
$condition = array ( 
                        'select' 	    =>  'curs_name,curs_wise,id_cat,duration,curs_photo,curs_type,curs_skills,curs_detail,curs_references,curs_keyword,curs_meta'
                        ,'where' 	    =>  array(  
                                                    'curs_id'            => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES, $condition);

$condition = array ( 
                        'select' 	    =>  'cat_id, cat_name'
                        ,'where' 	    =>  array(  
                                                    'is_deleted'    =>  0
                                                    ,'cat_status'   =>  1
                                                )
                        ,'search_by'    =>  ' AND cat_id IN ('.$row['id_cat'].')'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Course Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 100px);">
        <div class="offcanvas-body">
            <h6 class="text-muted text-uppercase fw-semibold"><b>Name:</b></h6>
            <p>'.$row['curs_name'].'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Categories:</b></h6>';
            if($COURSES_CATEGORIES){
                foreach ($COURSES_CATEGORIES as $cat) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.$cat['cat_name'].'</span>';
                }
            }else{
                echo'<div>Record not added</div>';
            }
            echo'
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Duration:</b></h6>
            <p>'.$row['duration'].' '.get_CourseWise($row['curs_wise']).'</p>
            <hr>';
            if(!empty($row['curs_photo']) && file_exists('uploads/images/courses/'.$row['curs_photo'])){
                echo'
                <h6 class="text-muted text-uppercase fw-semibold"><b>Image:</b></h6>
                <figure class="figure">
                    <img src="uploads/images/courses/'.$row['curs_photo'].'" class="figure-img img-fluid rounded" alt="...">
                </figure>
                <hr>';
            }
            echo'
            <h6 class="text-muted text-uppercase fw-semibold"><b>Course Type:</b></h6>
            <p>'.get_curs_type($row['curs_type']).'</p>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Skills:</b></h6>';
            if($row['curs_skills']){
                foreach (explode(',',$row['curs_skills']) as $value) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.$value.'</span>';
                }
            }else{
                echo'<div>Record not added</div>';
            }
            echo '
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Keywords:</b></h6>';
            if(!empty($row['curs_keyword'])){
                foreach (explode(',',$row['curs_keyword']) as $value) {
                    echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.$value.'</span>';
                }
            }else{
                echo'<div>Record not added</div>';
            }
            echo'
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Meta Description:</b></h6>
            <div>'.(!empty($row['curs_meta']) ? html_entity_decode(html_entity_decode($row['curs_meta'])) : 'Meta Description not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Refrences:</b></h6>
            <div>'.(!empty($row['curs_references']) ? html_entity_decode(html_entity_decode($row['curs_references'])) : 'Refrences not added').'</div>
            <hr>
            <h6 class="text-muted text-uppercase fw-semibold"><b>Detail:</b></h6>
            <div>'.(!empty($row['curs_detail']) ? html_entity_decode(html_entity_decode($row['curs_detail'])) : 'Detail not added').'</div>
        </div>
    </div>
</div>';
?>