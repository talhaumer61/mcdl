<?php
$search_word    = '';
$search_by      = '';
$filters        = 'search&'.$redirection.'';
if (!empty($_GET['search_word'])) {
    $search_word     = cleanvars($_GET['search_word']);
    $search_by      .= 'AND (c.curs_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}
$condition = array (
                    'select'        =>  'c.curs_id, c.curs_status, c.curs_type_status, c.curs_wise, c.curs_name, c.curs_icon, c.curs_photo, c.curs_code, c.curs_keyword, f.faculty_name, d.dept_name'
                    ,'where' 	    =>  array( 
                                                'c.is_deleted'    => 0
                                            )
                    ,'join'         =>  'INNER JOIN '.FACULTIES.' f ON f.faculty_id = c.id_faculty
                                         INNER JOIN '.DEPARTMENTS.' d ON d.dept_id = c.id_dept
                                         INNER JOIN '.COURSES_CATEGORIES.' cc ON FIND_IN_SET(cc.cat_id, c.id_cat) AND cc.id_type = '.$_SESSION['id_type'].''
                    ,'search_by'    =>  ''.$search_by.''
                    ,'group_by'     =>  'c.curs_id'
                    ,'order_by'     =>  'c.curs_id DESC'
                    ,'return_type'  =>  'count' 
                   );
$count = $dblms->getRows(COURSES .' c', $condition);
echo '
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.$pageTitle.' List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" href="'.moduleName().'.php?add&'.$redirection.'"><i class="ri-add-circle-line align-bottom me-1"></i>'.$pageTitle.'</a>
            </div>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <input type="hidden" name="id_type" value="'.$_SESSION['id_type'].'">
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

        $condition['order_by'] = "c.curs_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(COURSES.' c', $condition, $sql);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr style="vertical-align: middle;">
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th>Syllabus</th>
                            <th>Faculty</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="60" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            $curs_icon = ((!empty($row['curs_icon']) && file_exists('uploads/images/courses/icons/'.$row['curs_icon'])) ? 'uploads/images/courses/icons/'.$row['curs_icon'].'' : 'uploads/default.png');
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded p-1">
                                                    <img src="'.$curs_icon.'" alt="" class="img-fluid d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-2">
                                                    <a onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/view_summary.php?view_id='.$row['curs_id'].'\');" href="javascript:;">'.$row['curs_name'].'</a>
                                                </h5>
                                                <p class="text-muted mb-0">'.get_curs_status($row['curs_type_status']).'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td>'.get_CourseWise($row['curs_wise']).'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-1">
                                                    <a class="text-dark">'.$row['faculty_name'].'</a>
                                                </h5>
                                                <p class="text-muted mb-0">Department : <span class="fw-medium">'.$row['dept_name'].'</span></p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td class="text-center">'.get_status($row['curs_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/allocate_teachers/edit.php?edit_id='.$row['curs_id'].'&curs_name='.$row['curs_name'].'&curs_code='.$row['curs_code'].'&view=allocate_teachers\');"><i class="ri-links-line align-bottom me-2 text-muted"></i> Allocate Teachers</a></li>
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/view_summary.php?view_id='.$row['curs_id'].'\');"><i class="mdi mdi-eye align-bottom me-2 text-muted"></i> View Summary</a></li>
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.moduleName().'/view.php?view_id='.$row['curs_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="mdi mdi-eye align-bottom me-2 text-muted"></i> View Detail</a></li>
                                            <li><a class="dropdown-item" href="'.moduleName().'.php?edit&'.$redirection.'&id='.$row['curs_id'].'&tab=manage_course"><i class="ri-list-settings-line align-bottom me-2 text-muted"></i> Manage Course</a></li>
                                            <li><a class="dropdown-item" href="'.moduleName().'.php?edit&'.$redirection.'&id='.$row['curs_id'].'&view=enrolled_students"><i class="ri-group-2-line align-bottom me-2 text-muted"></i> Enrolled Students</a></li>
                                            <li><a class="dropdown-item" href="'.moduleName().'.php?edit&'.$redirection.'&id='.$row['curs_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['curs_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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