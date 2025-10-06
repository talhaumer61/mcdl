<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (file_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  '*'
                    ,'where'        =>  array(
                                                 'is_deleted'           =>	0
                                                ,'id_curs'				=>	cleanvars(CURS_ID)
                                              //  ,'academic_session'     =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                ,'id_lesson'            =>	'0'
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  'id DESC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(COURSES_DOWNLOADS, $condition);
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

$condition['order_by']      = "id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(COURSES_DOWNLOADS, $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40" class="text-center">Sr.</th>
                    <th>Name</th>
                    <th width="150" class="text-center">Type</th>
                    <th width="70" class="text-center">Status</th>
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
                        <td>'.$row['file_name'].'</td>
                        <td class="text-center">'.get_CourseResources($row['id_type']).'</td>
                        <td class="text-center">'.get_status($row['status']).'</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">';
                                    if($row['file']){
                                        echo'<li><a href="uploads/files/'.LMS_VIEW.'/'.$row['file'].'" class="dropdown-item" target="_blank"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View File</a></li>';
                                        echo'<li><a href="uploads/files/'.LMS_VIEW.'/'.$row['file'].'" class="dropdown-item" download="'.$row['file'].'"><i class="ri-download-cloud-2-fill align-bottom me-2 text-muted"></i> Download</a></li>';
                                    }
                                    if($row['url']){
                                        echo'<li><a href="'.$row['url'].'" class="dropdown-item" target="_blank"><i class="ri-links-fill align-bottom me-2 text-muted"></i> Go to Link</a></li>';
                                    }
                                    if($row['embedcode']){
                                        echo'<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view_video.php?view_id='.$row['id'].'&'.$redirection.'\');"><i class="ri-youtube-fill align-bottom me-2 text-muted"></i> View Video</a></li>';
                                    }
                                    if($row['detail']){
                                        echo'<li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view.php?view_id='.$row['id'].'&'.$redirection.'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Detail</a></li>';
                                    }
                                    echo'
                                    <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/edit.php?edit_id='.$row['id'].'&'.$redirection.'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                    <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['id'].'&'.$redirection.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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