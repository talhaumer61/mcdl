<?php
$redirection    = 'edit&id_type='.$_SESSION['id_type'].'&id='.$_GET['id'].'&view='.LMS_VIEW.'';
$search_word    = '';
$search_by      = '';
$filters        = 'search&'.$redirection.'';
if (!empty($_GET['search_word'])) {
    $search_word     = cleanvars($_GET['search_word']);
    $search_by      .= 'AND (s.std_name LIKE "%'.$search_word.'%" OR a.adm_email LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                    'select'        =>  'ec.secs_id, ec.secs_status, ec.id_std, ec.id_curs, s.std_name, a.adm_photo, a.adm_email
                                        ,COUNT(DISTINCT cl.lesson_id) as lesson_count
                                        ,COUNT(DISTINCT ca.id) as assignment_count
                                        ,COUNT(DISTINCT cq.quiz_id) as quiz_count
                                        ,COUNT(DISTINCT lt.track_id) as track_count'
                    ,'join'         =>  'INNER JOIN '.COURSES.' c ON FIND_IN_SET(c.curs_id, ec.id_curs) AND c.is_deleted = 0
                                         INNER JOIN '.STUDENTS.' s ON s.std_id = ec.id_std AND s.std_status = 1 AND s.is_deleted = 0
                                         INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid AND a.adm_status = 1 AND a.is_deleted = 0
                                         LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                         LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                                         LEFT JOIN '.QUIZ.' cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
                                         LEFT JOIN '.LECTURE_TRACKING.' lt ON lt.id_curs = c.curs_id AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg AND lt.id_std = ec.id_std'
                    ,'where' 	    =>  array( 
                                                 'ec.secs_status'   => 1
                                                ,'ec.is_deleted'    => 0
                                                ,'ec.id_mas'        => 0
                                                ,'ec.id_ad_prg'     => 0
                                                ,'c.curs_id'        => cleanvars($_GET['id'])
                                            )
                    ,'search_by'    =>  ''.$search_by.''
                    ,'group_by'     =>  'ec.id_std'
                    ,'order_by'     =>  'ec.secs_id DESC'
                    ,'return_type'  =>  'count' 
                   );
$count = $dblms->getRows(ENROLLED_COURSES .' ec', $condition, $sql);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(LMS_VIEW).' List</h5>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <input type="hidden" name="edit" value="">
                    <input type="hidden" name="id_type" value="'.$_GET['id_type'].'">
                    <input type="hidden" name="id" value="'.$_GET['id'].'">
                    <input type="hidden" name="view" value="'.LMS_VIEW.'">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search_word" value="'.$search_word.'">
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

        $condition['order_by'] = "ec.secs_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(ENROLLED_COURSES.' ec', $condition, $sql);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr style="vertical-align: middle;">
                            <th width="40" class="text-center">Sr.</th>
                            <th>Student</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="30%" class="text-center">Progress</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            $adm_photo = ((!empty($row['adm_photo']) && file_exists('uploads/images/admin/'.$row['adm_photo'])) ? 'uploads/images/admin/'.$row['adm_photo'].'' : 'uploads/default.png');
                            
                            // PERCENTEAGE
                            $Total      = $row['lesson_count'] + $row['assignment_count'] + $row['quiz_count'];
                            $Obtain     = $row['track_count'];
                            $percent    = (($Obtain / $Total) * 100);
                            $percent    = intval($percent >= '100' ? '100' : $percent);
                            $remaining  = 100 - $percent;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded p-1">
                                                    <img src="'.$adm_photo.'" alt="" class="img-fluid d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-1">'.$row['std_name'].'</h5>
                                                <p class="mb-0 fw-medium text-primary">'.$row['adm_email'].'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td class="text-center">'.get_leave($row['secs_status']).'</td>
                                <td>
                                    <div class="card bg-light overflow-hidden">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><b class="text-success">'.$percent.'%</b> Completed</h6>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h6 class="mb-0">'.$remaining.'% left</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress bg-soft-success rounded-0">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: '.intval($percent).'%" aria-valuenow="'.intval($percent).'" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
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
        echo'
    </div>
</div>';
?>