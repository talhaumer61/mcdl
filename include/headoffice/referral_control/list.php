<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (r.ref_remarks LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                     'select'       =>  'r.ref_id, r.ref_status, r.ref_remarks, r.ref_date_time_from, r.ref_date_time_to, r.ref_percentage,
                                         GROUP_CONCAT(DISTINCT a.adm_id) AS user_id,
                                         GROUP_CONCAT(a.adm_fullname) AS user_fullname,
                                         GROUP_CONCAT(a.adm_photo) AS user_photo,
                                         GROUP_CONCAT(a.is_teacher) AS user_teacher,
                                         GROUP_CONCAT(DISTINCT c.curs_name) AS curs_name'
                    ,'join'         =>  'LEFT JOIN '.ADMINS.' AS a ON FIND_IN_SET(a.adm_id, r.id_user)
                                         LEFT JOIN '.COURSES.' AS c ON FIND_IN_SET(c.curs_id, r.id_curs)'
                    ,'where' 	    =>  array( 
                                                'r.is_deleted'    => 0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'group_by'     =>  ' r.ref_id '
                    ,'return_type'  =>  'count'
                   ); 
$count = $dblms->getRows(REFERRAL_CONTROL.' AS r', $condition, $sql);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
            <div class="flex-shrink-0">
                <a href="?view=add" class="btn btn-primary btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(false).'</a>
            </div>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
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

        $condition['order_by'] = "r.ref_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(REFERRAL_CONTROL.' AS r', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>                        
                            <th width="40" class="text-center">Sr.</th>
                            <th>Students And Teachers</th>
                            <th>Courses</th>
                            <th width="50" class="text-center">Percentage</th>
                            <th width="200" class="text-center">Duration</th>
                            <th width="50" class="text-center">Status</th>
                            <th width="50" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = (($page == 1)?0:($page-1)*$Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <div class="avatar-group">';
                                        if (!empty($row['user_id'])) {
                                            $user_fullname   = explode(',', $row['user_fullname']);
                                            $user_photo      = explode(',', $row['user_photo']);
                                            $user_teacher    = explode(',', $row['user_teacher']);

                                            foreach (explode(',', $row['user_id']) AS $key => $user_val) {
                                                $user_photo_checked = ((!empty($user_photo[$key]) && file_exists('uploads/images/admin/'.$user_photo[$key])) ? 'uploads/images/admin/'.$user_photo[$key].'' : 'uploads/images/default_male.jpg');
                                                echo'
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="'.($user_teacher[$key] != 1?' (Teacher) ':' (Student) ').' '.$user_fullname[$key].'">';
                                                    if (empty($user_photo[$key])) {
                                                        echo'
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title rounded-circle bg-light text-primary">
                                                                '.get_FirstToCharOfName($user_fullname[$key]).'
                                                            </div>
                                                        </div>';
                                                    } else {
                                                        echo'
                                                        <img src="'.SITE_URL.$user_photo_checked.'" alt="" class="rounded-circle avatar-sm">';
                                                    }
                                                    echo'
                                                </a>';
                                            }
                                        }
                                        echo'
                                    </div>
                                </td>
                                <td>';
                                    if (!empty($row['curs_name'])) {
                                        foreach (explode(',', $row['curs_name']) AS $key => $curs_name) {
                                            echo'
                                            <span class="badge badge-gradient-success">'.$curs_name.'</span>';
                                        }
                                    } else {
                                        echo'
                                        Not Found';
                                    }
                                    echo'
                                </td>
                                <td class="text-center">'.$row['ref_percentage'].' %</td>
                                <td class="text-center">'.date('d M, Y', strtotime(cleanvars($row['ref_date_time_from']))).' to '.date('d M, Y', strtotime(cleanvars($row['ref_date_time_to']))).'</td>
                                <td class="text-center">'.get_status($row['ref_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" href="?edit_id='.$row['ref_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['ref_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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
        echo'
    </div>
</div>';
?>