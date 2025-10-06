<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (cf.question LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                         'select' 	    =>  'cf.id, cf.status, cf.question'
                        ,'where' 	    =>  array(  
                                                     'cf.is_deleted'            => 0
                                                    ,'cf.id_curs'               =>  cleanvars(CURS_ID)
                                                  //  ,'cf.id_session'            => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'cf.id_campus'             => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'cf.id_teacher'            => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                        ,'search_by'    =>  ''.$search_query.''
                        ,'order_by'     =>  'cf.id DESC'
                        ,'return_type'  =>  'count' 
                    ); 
$count = $dblms->getRows(COURSES_FAQS.' cf', $condition);
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

$condition['order_by']      = "cf.id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(COURSES_FAQS.' cf', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table table-bordered table-nowrap mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40" class="text-center">Sr.</th>
                    <th>Question</th>
                    <th width="50" class="text-center">Status</th>
                    <th width="50" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowslist as $row) {
                    $srno++;
                    echo'
                    <tbody>
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.$row['question'].'</td>
                            <td class="text-center">'.get_status($row['status']).'</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                        <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/view.php?view_id='.$row['id'].'&'.$redirection.'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/edit.php?edit_id='.$row['id'].'&'.$redirection.'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['id'].'&'.$redirection.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>';
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