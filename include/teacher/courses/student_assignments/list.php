<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (a.caption LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'sa.*, a.id_week, a.caption, date_start, date_end, s.std_name'
                    ,'join'         =>  'INNER JOIN '.COURSES_ASSIGNMENTS.' a ON a.id = sa.id_assignment
                                         INNER JOIN '.STUDENTS.' s ON s.std_id = sa.id_std'
                    ,'where'        =>  array(
                                                 'a.status'             =>	1
                                                ,'a.is_deleted'         =>	0
                                                ,'a.id_curs'            =>  cleanvars(CURS_ID)
                                                // ,'a.id_teacher'         =>  cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                               // ,'a.academic_session'   =>  cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  'sa.id DESC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(COURSES_ASSIGNMENTS_STUDENTS.' sa', $condition);
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
$prev        = $page - 1;
$next        = $page + 1;
$lastpage    = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
$lpm1        = $lastpage - 1;

$condition['order_by']      = "sa.id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(COURSES_ASSIGNMENTS_STUDENTS.' sa', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table table-nowrap mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40" class="text-center">Sr.</th>
                    <th>Student</th>
                    <th>Assignment</th>
                    <th width="100" class="text-center">Submited Date</th>
                    <th width="100" class="text-center">'.get_CourseWise($curs['curs_wise']).'</th>
                    <th width="50" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowslist as $row) {
                    $srno++;
                    echo '
                    <tr style="vertical-align: middle;">
                        <td class="text-center">'.$srno.'</td>
                        <td>'.$row['std_name'].'</td>
                        <td>'.html_entity_decode($row['caption']).'</td>
                        <td class="text-center">'.date('d M, Y',strtotime($row['submit_date'])).'</td>
                        <td class="text-center">'.get_CourseWise($curs['curs_wise']).' '.$row['id_week'].'</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">';
                                    if($row['student_file']){
                                        echo'<li><a href="'.WEBSITE_URL.'uploads/files/'.LMS_VIEW.'/'.$row['student_file'].'" class="dropdown-item" target="_blank"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View File</a></li>';
                                        echo'<li><a href="'.WEBSITE_URL.'uploads/files/'.LMS_VIEW.'/'.$row['student_file'].'" class="dropdown-item" download="'.$row['student_file'].'"><i class="ri-download-cloud-2-fill align-bottom me-2 text-muted"></i> Download</a></li>';
                                    }
                                    echo'
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