<?php
unset($_SESSION['MULTIPLECHOICEQUESTION']);
unset($_SESSION['MULTIPLECHOICEQUESTION_TOTAL_MARKS']);
unset($_SESSION['SHORTQUESTION']);
unset($_SESSION['SHORTQUESTION_TOTAL_MARKS']);
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (q.quiz_title LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'q.quiz_id, q.quiz_status, q.quiz_title, q.id_week, q.quiz_no_qns, q.quiz_time, q.quiz_totalmarks, q.quiz_passingmarks, q.is_publish
                                        ,SUM(CASE WHEN qs.quiz_qns_type = 3 THEN 1 ELSE 0 END) as countMultipleChoice
                                        ,SUM(CASE WHEN qs.quiz_qns_type = 1 THEN 1 ELSE 0 END) as countShort'
                    ,'join'         =>  'INNER JOIN '.QUIZ_QUESTIONS.' AS qs ON qs.id_quiz = q.quiz_id'
                    ,'where'        =>  array(  
                                                     'q.is_deleted'         => 0
                                                   // ,'q.id_session'         => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'q.id_campus'          => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'q.id_curs'            => cleanvars(CURS_ID)
                                                )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'group_by'     =>  'q.quiz_id'
                    ,'order_by'     =>  'q.id_week ASC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(QUIZ.' AS q', $condition);
echo'
<style type="text/css">
        .table-responsive {
            overflow: visible; /* Allow dropdown to be fully visible */
        }
        .table td {
            position: relative; /* Ensures dropdown aligns correctly */
        }
        .dropdown-menu {
            z-index: 9999;
            position: absolute;
            right: 0;
            top: 100%;
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
        .dropdown.show .dropdown-menu {
            display: block;
        }
    </style>
<div class="row justify-content-end">
    <div class="col-3">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.CURS_ID.'">
            <input type="hidden" name="view" value="'.LMS_VIEW.'">
            <input type="hidden" name="tab" value="manage_course">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." name="search_word" value="'.$search_word.'">
                <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-2-line"></i></button>
            </div>
        </form>
    </div>
</div>';       
if ($page == 0 || empty($page)) { $page = 1; }
$prev       = $page - 1;
$next       = $page + 1;
$lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
$lpm1       = $lastpage - 1;

$condition['order_by']      = "q.id_week ASC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(QUIZ.' AS q', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="10">Sr.</th>
                    <th>Question</th>
                    <th class="text-center" width="50">Quiz Time</th>
                    <th width="100" class="text-center">'.get_CourseWise($curs['curs_wise']).'</th>
                    <th class="text-center" width="50">Total Question</th>
                    <th class="text-center" width="50">Total Marks</th>
                    <th class="text-center" width="50">Passing Marks</th>
                    <th width="35">Publish</th>
                    <th width="35">Status</th>
                    <th width="35">Action</th>
                </tr>
            </thead>
            <tbody>';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowslist as $row) {
                    $srno++;
                    echo'
                    <tr style="vertical-align: middle;">
                        <td class="text-center">'.$srno.'</td>
                        <td>'.moduleName($row['quiz_title']).'</td>
                        <td class="text-center">'.$row['quiz_time'].' Minutes</td>
                        <td class="text-center">'.get_CourseWise($curs['curs_wise']).' '.get_LessonWeeks($row['id_week']).'</td>
                        <td class="text-center">'.$row['quiz_no_qns'].'</td>
                        <td class="text-center">'.$row['quiz_totalmarks'].'</td>
                        <td class="text-center">'.$row['quiz_passingmarks'].'</td>
                        <td class="text-center">'.get_is_publish($row['is_publish']).'</td>
                        <td class="text-center">'.get_status($row['quiz_status']).'</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">';
                                    if ($row['is_publish'] != 1) {
                                        echo'
                                        <li><a class="dropdown-item" href="'.moduleName().'.php?edit_id='.$row['quiz_id'].'&'.$redirection.'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['quiz_id'].'&'.$redirection.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>';
                                    }
                                    if ($row['countShort'] != 0) {
                                        echo'
                                        <li><a class="dropdown-item" href="'.moduleName().'.php?edit_id='.$row['quiz_id'].'&'.$redirection.'&marks=add"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Add Marks</a></li>';
                                    }
                                    echo'
                                    <li><a class="dropdown-item" href="'.moduleName().'.php?edit_id='.$row['quiz_id'].'&'.$redirection.'&marks=view"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Marks</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>';                
        include_once('include/pagination.php');
        echo'
    </div>';
} else {
    echo'
    <div class="noresult" style="display: block">
        <div class="text-center">
            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
            </lord-icon>
            <h5 class="mt-2">Sorry! No Record Found</h5>
            <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
        </div>
    </div>';
}
?>