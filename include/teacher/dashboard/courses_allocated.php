<?php
$courseMenu = array(
     'course_info'          => array( 'title' => 'Course Info'              , 'view' => 'course_info'           ,   'icon' => 'ri-book-open-line'           ,   'color' =>  'success')
    ,'lesson_plan'          => array( 'title' => 'Lesson Plan'              , 'view' => 'lesson_plan'           ,   'icon' => 'ri-calendar-todo-line'       ,   'color' =>  'info')
    ,'course_resources'     => array( 'title' => 'Course Resources'         , 'view' => 'course_resources'      ,   'icon' => 'ri-file-copy-2-line'         ,   'color' =>  'primary')
    ,'faqs'                 => array( 'title' => 'FAQs'                     , 'view' => 'faqs'                  ,   'icon' => 'ri-question-line'            ,   'color' =>  'warning')
    ,'discussion_board'     => array( 'title' => 'Discussion Board'         , 'view' => 'discussion_board'      ,   'icon' => 'ri-question-answer-line'     ,   'color' =>  'danger')
    ,'assignments'          => array( 'title' => 'Assignments'              , 'view' => 'assignments'           ,   'icon' => 'ri-survey-line'              ,   'color' =>  'dark')
    ,'question_bank'        => array( 'title' => 'Question Bank'            , 'view' => 'question_bank'         ,   'icon' => 'ri-questionnaire-line'       ,   'color' =>  'secondary')
    ,'announcements'        => array( 'title' => 'Announcements'            , 'view' => 'announcements'         ,   'icon' => 'bx bxs-megaphone'            ,   'color' =>  'success')
);
$condition = array ( 
                     'select' 	    =>  'c.curs_id, c.curs_status, c.curs_name, c.curs_icon, c.curs_photo, c.curs_code'
                    ,'join'         =>  'INNER JOIN '.COURSES.' c ON c.curs_id = atc.id_curs'
                    ,'where' 	    =>  array( 
                                                 'c.is_deleted'       =>  0
                                                ,'c.curs_status'      =>  1
                                            )
                    ,'search_by'    =>  'AND FIND_IN_SET('.$_SESSION['userlogininfo']['EMPLYID'].', atc.id_teacher)'
                    ,'order_by'     =>  'c.curs_id DESC'
                    ,'return_type'  =>  'all' 
                ); 
$courses = $dblms->getRows(ALLOCATE_TEACHERS.' atc', $condition, $sql);
echo'
<div class="card mb-5">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>Courses</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">';
            if($courses){
                foreach ($courses as $curs) {
                    echo'
                    <div class="col-12" >
                        <div class="card mb-3">
                            <div class="card-header alert-dark">
                                <h5 class="mb-0">'.$curs['curs_code'].' - '.$curs['curs_name'].'</h5>
                            </div>
                            <div class="card-body border">
                                <div class="row">';
                                    foreach ($courseMenu as $key => $value) {
                                        echo'                                    
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="courses.php?id='.$curs['curs_id'].'&view='.$key.'">
                                                <i class="'.$value['icon'].' text-'.$value['color'].'" style="font-size: 2.5rem;"></i>
                                                <span>'.$value['title'].'</span>
                                            </a>
                                        </div>';
                                    }
                                    echo'
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }else {
                echo'
                <div class="noresult" style="display: block">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">Sorry! No Allocated Courses Found.</h5>
                        <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                    </div>
                </div>';
            }
            echo'
        </div>
    </div>
</div>';
?>