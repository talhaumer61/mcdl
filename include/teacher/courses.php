<?php
// COURSE
$curs = $coursecls->get_course($_GET['id']);

if($curs['curs_id'] != ''){
    $condition = array(
                         'select'       =>  'qa.id'
                        ,'join'         =>  'INNER JOIN '.ADMINS.' a ON a.adm_id = qa.id_added AND a.adm_status = 1 AND a.is_deleted = 0'
                        ,'where'        =>  array(
                                                     'qa.id_curs'       =>   cleanvars($_GET['id'])
                                                    ,'qa.read_status'   =>   2
                                                    ,'qa.type'          =>   1
                                                    ,'qa.is_deleted'    =>   0
                                                )
                        ,'return_type'  =>  'count'
                    );
    $unRepliedMsj = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition);

    // MENU
    $courseMenu = array(
         'course_info'          => array( 'title' => 'Course Info'              , 'view' => 'course_info'           ,   'icon' => 'ri-book-open-line')
        ,'week_title'           => array( 'title' => ''.get_CourseWise($curs['curs_wise']).' Title'                 , 'view' => 'week_title'              ,   'icon' => ' ri-input-cursor-move')
        ,'lesson_plan'          => array( 'title' => 'Lesson Plan'              , 'view' => 'lesson_plan'           ,   'icon' => 'ri-calendar-todo-line')
        ,'discussion_board'     => array( 'title' => 'Discussion Board'         , 'view' => 'discussion_board'      ,   'icon' => 'ri-question-answer-line')
        ,'assignments'          => array( 'title' => 'Assignments'              , 'view' => 'assignments'           ,   'icon' => 'ri-survey-line')
        ,'student_assignments'  => array( 'title' => 'Student Assignments'      , 'view' => 'student_assignments'   ,   'icon' => 'ri-file-word-2-line')
        ,'question_bank'        => array( 'title' => 'Question Bank'            , 'view' => 'question_bank'         ,   'icon' => 'ri-questionnaire-line')
        ,'quiz'                 => array( 'title' => 'Quiz'                     , 'view' => 'quiz'                  ,   'icon' => 'ri-newspaper-line')
        ,'announcements'        => array( 'title' => 'Announcements'            , 'view' => 'announcements'         ,   'icon' => 'bx bxs-megaphone')
        ,'course_resources'     => array( 'title' => 'Course Resources'         , 'view' => 'course_resources'      ,   'icon' => 'ri-file-copy-2-line')
        ,'faqs'                 => array( 'title' => 'FAQs'                     , 'view' => 'faqs'                  ,   'icon' => 'ri-question-line')
        ,'glossary'             => array( 'title' => 'Glossary'                 , 'view' => 'glossary'              ,   'icon' => 'ri-book-mark-line')
        ,'reviews'              => array( 'title' => 'Reviews / Feedback'       , 'view' => 'reviews'               ,   'icon' => 'ri-double-quotes-l')
        ,'question_answers'     => array( 'title' => 'Question Answers'         , 'view' => 'question_answers'      ,   'icon' => 'ri-chat-1-line')
    );
    
    // COURSE ID WITHIN THE MODULE
    define('CURS_ID' , $curs['curs_id']);
    // URL VARIABLE TO MANAGE REDIRECTION WITHIN MODULE
    $redirection = 'edit&id_type='.$_SESSION['id_type'].'&id='.CURS_ID.'&tab=manage_course&view=course_info';

    if($curs['duration'] != 0){
        $week_ids   = $curs['LessonWeek'].',';
        $week_ids  .= $curs['AssignmentWeek'].',';
        $week_ids  .= $curs['QuizWeek'];
        $week_ids   = rtrim($week_ids, ',');
        $week_ids   = ltrim($week_ids, ',');
        $week_ids   = explode(',', $week_ids);
        $week_ids   = array_unique($week_ids);
        $week_count = count($week_ids);
        
        $percent    = (($week_count / $curs['duration']) * 100);
        $percent    = ($percent >= '100' ? '100' : $percent);
    
        $remaining  = $curs['duration'] - $week_count;
    }
    if(LMS_VIEW){
        // URL VARIABLE TO MANAGE REDIRECTION WITHIN MODULE
        $redirection = 'edit&id_type='.$_SESSION['id_type'].'&id='.CURS_ID.'&tab=manage_course&view='.LMS_VIEW.'';
        echo'
        <title>'.moduleName(false).' - '.TITLE_HEADER.'</title> 
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">'.moduleName().'</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item"><a href="'.moduleName().'.php?id_type='.$_SESSION['id_type'].'" class="text-primary">'.moduleName(false).'</a></li>
                                    <li class="breadcrumb-item"><a href="'.moduleName().'.php?'.$redirection.'" class="text-primary">'.moduleName(LMS_VIEW).'</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ri-menu-4-fill align-bottom me-1"></i>Course Menu</h5>
                            </div>
                            <div class="card-body p-0"> 
                                <ul class="list-group">';
                                    foreach ($courseMenu as $key => $value) :
                                        if ($key == 'course_referral' || LMS_VIEW == 'course_referral') {
                                            if ($REFERRAL_CONTROL) {
                                                echo'<li class="list-group-item"><a href="'.moduleName().'.php?edit&id_type='.$_SESSION['id_type'].'&id='.CURS_ID.'&tab=manage_course&view='.$key.'" class="text-'.(LMS_VIEW == $key ? 'danger' : 'dark').'"><i class="'.$value['icon'].' align-middle lh-1 me-2"></i>'.$value['title'].'</a></li>';
                                            }
                                        } else {
                                            echo'
                                            <li class="list-group-item"><a href="'.moduleName().'.php?edit&id_type='.$_SESSION['id_type'].'&id='.CURS_ID.'&tab=manage_course&view='.$key.'" class="text-'.(LMS_VIEW == $key ? 'danger' : 'dark').'"><i class="'.$value['icon'].' align-middle lh-1 me-2"></i>'.$value['title'].'</a>';
                                                if($unRepliedMsj && $key == 'question_answers'){
                                                    echo'
                                                    <span class="ms-2 badge rounded-pill bg-success">'.$unRepliedMsj.'<span class="visually-hidden">unread messages</span></span>';
                                                }
                                                echo'
                                            </li>';
                                        }
                                    endforeach;
                                    echo'
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                    <h3 class="text-dark">'.(($curs['curs_code']) ? $curs['curs_code'].': ': '').$curs['curs_name'].'</h3>';
                        foreach ($courseMenu as $key => $value) :
                            if(LMS_VIEW == $key){
                                $iconPg = (isset($_GET['add']) ? 'ri-add-circle-line' : (LMS_EDIT_ID ? 'ri-edit-circle-line' : $courseMenu[$key]['icon']));
                                include_once (moduleName().'/'.$key.'.php');
                            }
                        endforeach;
                        echo'
                    </div>
                </div>
            </div>
        </div>';
    } else {
        header('Location: '.moduleName().'.php?'.$redirection);
    }
}else{
    header('Location: dashboard.php');
}
