<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= " AND (lesson_topic LIKE '%".$search_word."%')";
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'lesson_id, lesson_ordering, lesson_status, id_week, id_lecture, lesson_topic, lesson_content, lesson_video_code, lesson_video_code_vimeo'
                    ,'where'        =>  array(
                                                 'is_deleted'           =>  0
                                                ,'id_curs'				=>  cleanvars(CURS_ID)
                                              //  ,'id_session'           =>  cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                ,'id_campus'            =>  cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'      => 'id_week ASC, id_lecture ASC, lesson_ordering ASC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(COURSES_LESSONS, $condition);
echo'
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

$condition['order_by']      = "id_week ASC, id_lecture ASC, lesson_ordering ASC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(COURSES_LESSONS, $condition);
if ($rowslist) {
    $grouped = [];
    foreach ($rowslist as $row) {
        $grouped[$row['id_week']][$row['id_lecture']][] = $row;
    }
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
    <div class="table-card">';
        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
        foreach ($grouped as $week_id => $lectures) {
            echo '
            <div class="card mb-3">
                <div class="card-header alert-primary">
                    <h5 class="card-title mb-0 flex-grow-1">'.get_CourseWise($curs['curs_wise']).' ' . get_LessonWeeks($week_id) . '</h5>
                </div>
                <div class="card-body border">';
                    foreach ($lectures as $lecture_id => $lessons) {

                        echo '
                        <div class="pb-2 fw-semibold fs-6">
                            ' . get_LessonLectures($lecture_id) . '
                        </div>';

                        foreach ($lessons as $row) {
                            echo '
                            <div class="d-flex align-items-center justify-content-between mx-4 mb-3 p-2 border rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge badge-soft-primary">'.$row['lesson_ordering'].'</span>
                                    <span class="fw-medium">
                                        '.html_entity_decode(html_entity_decode($row['lesson_topic'])).'
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-2">

                                    <span class="badge rounded-pill bg-success-subtle text-success">
                                        '.get_status($row['lesson_status']).'
                                    </span>

                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">';
                                            if ($row['lesson_video_code_vimeo'] != '') {
                                                echo'<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view_video_vimeo.php?view_id='.$row['lesson_id'].'&'.$redirection.'\');"><i class="ri-vimeo-fill align-bottom me-2 text-muted"></i> Watch Video</a></li>';
                                            }
                                            if ($row['lesson_video_code'] != '') {
                                                echo'<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view_video.php?view_id='.$row['lesson_id'].'&'.$redirection.'\');"><i class="ri-youtube-fill align-bottom me-2 text-muted"></i> Watch Video</a></li>';
                                            }
                                            echo'
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view.php?view_id='.$row['lesson_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li><a class="dropdown-item" href="?edit_id='.$row['lesson_id'].'&'.$redirection.'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['lesson_id'].'&'.$redirection.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                    echo'
                </div>
            </div>';
        }              
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
            <p class="text-muted">We\'ve searched '.$count.' Record and We did not find any for you search.</p>
        </div>
    </div>';
}
?>