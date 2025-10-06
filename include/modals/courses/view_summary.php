<?php 
require_once ("../../dbsetting/lms_vars_config.php");
require_once ("../../dbsetting/classdbconection.php");
require_once ("../../functions/functions.php");
require_once ("../../functions/login_func.php");
$dblms = new dblms();

// COURSE INFO
$condition = array ( 
                     'select'       =>	'c.curs_id,c.curs_name, c.curs_code, c.duration, c.curs_wise, c.id_level
                                        ,COUNT(DISTINCT ec.secs_id) as TotalStd
                                        ,GROUP_CONCAT(DISTINCT cl.id_week) as LessonWeek
                                        ,GROUP_CONCAT(DISTINCT ca.id_week) as AssignmentWeek
                                        ,GROUP_CONCAT(DISTINCT cq.id_week) as QuizWeek
                                        ,GROUP_CONCAT(DISTINCT e.emply_name) as employees'
                    ,'join'         =>  'LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
                                         LEFT JOIN '.ALLOCATE_TEACHERS.' clt on clt.id_curs = c.curs_id
                                         LEFT JOIN '.EMPLOYEES.' e on FIND_IN_SET(e.emply_id, clt.id_teacher)
                                         LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                         LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                                         LEFT JOIN '.QUIZ.' cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1'
                    ,'where' 		 =>	array( 
                                                 'c.curs_id'        => cleanvars($_GET['view_id'])
                                                ,'c.is_deleted'     => '0'
                                            )
                    ,'return_type'	=>	'single'
            ); 
$row = $dblms->getRows(COURSES.' c', $condition);

if($row['duration'] != 0){
    $week_ids   = $row['LessonWeek'].',';
    $week_ids  .= $row['AssignmentWeek'].',';
    $week_ids  .= $row['QuizWeek'];
    $week_ids   = rtrim($week_ids, ',');
    $week_ids   = ltrim($week_ids, ',');
    $week_ids   = explode(',', $week_ids);
    $week_ids   = array_unique($week_ids);
    $week_count = count($week_ids);
    
    $percent    = (($week_count / $row['duration']) * 100);
    $percent    = ($percent >= '100' ? '100' : $percent);

    $remaining  = $row['duration'] - $week_count;
}
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-eye-line align-bottom me-1"></i>'.moduleName(false).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="id_curs" value="'.$_GET['view_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <table class="table table-bordered border">
                            <tbody>
                                <tr>
                                    <th>Course Code</th>
                                    <td><span class="badge badge-soft-primary">'.$row['curs_code'].'</span></td>
                                    <th>Duration</th>
                                    <td><span class="badge badge-soft-'.($row['duration'] != 0 ? 'primary' : 'danger').'">'.($row['duration'] != 0 ? $row['duration'].' '.get_CourseWise($row['curs_wise']) : 'Update in Course').'</span></td>
                                    <th>Enrolled Students</th>
                                    <td><span class="badge badge-soft-primary">'.$row['TotalStd'].'</span></td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td colspan="5">'.$row['curs_name'].'</td>
                                </tr>
                                <tr>
                                    <th>Teachers</th>
                                    <td colspan="5">';
                                        if(!empty($row['employees'])) {
                                            foreach (explode(',', $row['employees']) as $key => $emply) :
                                                echo '<span class="badge bg-secondary rounded-pill me-2 mb-2">' . $emply . '</span>';
                                            endforeach;
                                        }
                                        echo'
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="6">
                                        '.($row['duration'] == 0 ? '<h6 class="text-danger text-center">Admin Need to update course duration for percentage</h6>' : '').'
                                        <div class="card bg-light overflow-hidden">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0"><b class="text-success">'.intval($percent).'%</b> Completed</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <h6 class="mb-0">'.$remaining.' '.get_CourseWise($row['curs_wise']).' left</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress bg-soft-success rounded-0">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: '.intval($percent).'%" aria-valuenow="'.intval($percent).'" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr style="vertical-align: middle;">
                                    <th>'.get_CourseWise($row['curs_wise']).'</th>
                                    <th width="100" class="text-center">Lessons</th>
                                    <th width="100" class="text-center">Assignments</th>
                                    <th width="100" class="text-center">Quiz</th>
                                </tr>
                            </thead>
                            <tbody>';
                                $srno = 0;
                                $TotalLesson = 0;
                                $TotalAssignmnt = 0;
                                $TotalQuiz = 0;
                                foreach (get_LessonWeeks() as $key => $value) {
                                    $condition = array ( 
                                                         'select'       =>	'c.curs_wise
                                                                            ,COUNT(DISTINCT cl.lesson_id) as TotalLesson
                                                                            ,COUNT(DISTINCT ca.id) as TotalAssignmnt
                                                                            ,COUNT(DISTINCT cq.quiz_id) as TotalQuiz'
                                                        ,'join'         =>  'LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0 AND cl.id_week = '.$value.'
                                                                             LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0 AND ca.id_week = '.$value.'
                                                                             LEFT JOIN '.QUIZ.' cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1 AND cq.id_week = '.$value.' '
                                                        ,'where'        =>	array( 
                                                                                     'c.curs_id'        => cleanvars($_GET['view_id'])
                                                                                    ,'c.is_deleted'     => '0'
                                                                                )
                                                        ,'return_type'	=>	'single'
                                                ); 
                                    $view_summary = $dblms->getRows(COURSES.' c', $condition, $sql);

                                    if($view_summary){
                                        $totalCount = $view_summary['TotalLesson'] + $view_summary['TotalAssignmnt'] + $view_summary['TotalQuiz'];
                                        if($totalCount > 0){
                                            $srno++;
                                            echo'
                                            <tr style="vertical-align: middle;">
                                                <td>'.get_CourseWise($row['curs_wise']).' '.$value.'</td>
                                                <td width="100" class="text-center">'.$view_summary['TotalLesson'].'</td>
                                                <td width="100" class="text-center">'.$view_summary['TotalAssignmnt'].'</td>
                                                <td width="100" class="text-center">'.$view_summary['TotalQuiz'].'</td>
                                            </tr>';
                                            $TotalLesson += $view_summary['TotalLesson'];
                                            $TotalAssignmnt += $view_summary['TotalAssignmnt'];
                                            $TotalQuiz += $view_summary['TotalQuiz'];
                                        }
                                    }
                                }
                                if($srno == 0){
                                    echo'<tr><td colspan="4" class="text-center text-danger">No Record Found...!</td></tr>';
                                }else{                                    
                                    echo' </tbody>
                                <thead class="table-light">
                                    <tr style="vertical-align: middle;">
                                        <th class="text-end">Total</th>
                                        <th width="100" class="text-center">'.$TotalLesson.'</th>
                                        <th width="100" class="text-center">'.$TotalAssignmnt.'</th>
                                        <th width="100" class="text-center">'.$TotalQuiz.'</th>
                                    </tr>
                                    </thead>';
                                }
                                echo'
                           
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>