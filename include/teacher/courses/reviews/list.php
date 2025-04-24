<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (f.std_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'f.*'
                    ,'join'         =>  'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = f.id_enroll AND FIND_IN_SET('.CURS_ID.', ec.id_curs)'
                    ,'where'        =>  array(
                                                'f.cert_type'     =>	3
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  'f.feedback_id DESC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(STUDENT_FEEDBACK.' f', $condition);
echo'
<div class="row justify-content-end">
    <div class="col-3">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.CURS_ID.'">
            <input type="hidden" name="view" value="'.LMS_VIEW.'">
            <input type="hidden" name="tab" value="manage_course">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search_word" value="'.$search_word.'">
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

$condition['order_by']      = "f.feedback_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(STUDENT_FEEDBACK.' f', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40" class="text-center">Sr.</th>
                    <th>Student</th>
                    <th width="150" class="text-center">Date</th>
                    <th width="100" class="text-center">Action</th>
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
                        <td class="text-center">'.date('d M, Y', strtotime($row['date_generated'])).'</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                    <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view_summary.php?view_id='.$row['feedback_id'].'&'.$redirection.'\');"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Detail</a></li>
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