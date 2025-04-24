<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (c.curs_name LIKE "%'.$search_word.'%" || mt.mas_name LIKE "%'.$search_word.'%" || ap.program LIKE "%'.$search_word.'%" )';
    $filters        .= '&search_word='.$search_word.'';
}
$condition = array ( 
                    'select'        =>  'ao.admoff_id, ao.admoff_status, ao.admoff_type, ao.id_type, ao.admoff_startdate, ao.admoff_enddate, ao.admoff_amount, ao.admoff_amount_in_usd, ap.program, c.curs_name , mt.mas_name'
                    ,'join'         =>  'LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = ao.admoff_degree
                                         LEFT JOIN '.COURSES.' c ON c.curs_id = ao.admoff_degree
                                         LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = ao.admoff_degree'
                    ,'where' 	    =>  array( 
                                                'ao.is_deleted'    => 0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'return_type'  =>  'count' 
                   ); 
$count = $dblms->getRows(ADMISSION_OFFERING . ' ao', $condition);
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
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1       = $lastpage - 1;        

        $condition['order_by'] = "ao.admoff_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(ADMISSION_OFFERING.' ao', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>                        
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th width="100" class="text-center">PKR</th>
                            <th width="100" class="text-center">USD</th>
                            <th width="100" class="text-center">Start Date</th>
                            <th width="100" class="text-center">End Date</th>
                            <th width="100" class="text-center">Learning</th>
                            <th width="50" class="text-center">Status</th>
                            <th width="50" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            if ($row['admoff_type'] == 1) {
                                $name = $row['program'];
                            } elseif ($row['admoff_type'] == 2) {
                                $name = $row['mas_name'];
                            }else {
                                $name = $row['curs_name'];
                            }
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-2">'.$name.'</h5>
                                                <p class="text-muted mb-0">'.get_enroll_type($row['admoff_type']).'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>';
                                if ($row['id_type'] == 2) {
                                    echo'    
                                    <td class="text-center" colspan="4"></td>
                                    <td class="text-center">'.get_LeanerType($row['id_type']).'</td>';
                                } else {
                                    echo'
                                    <td class="text-center">'.$row['admoff_amount'].'</td>
                                    <td class="text-center">'.$row['admoff_amount_in_usd'].'</td>
                                    <td class="text-center">'.($row['admoff_startdate'] != '0000-00-00' ? date("d M, y",strtotime($row['admoff_startdate'])) : '<span class="text-danger">Not Set</span>').'</td>
                                    <td class="text-center">'.($row['admoff_enddate'] != '0000-00-00' ? date("d M, y",strtotime($row['admoff_enddate'])) : '<span class="text-danger">Not Set</span>').'</td>
                                    <td class="text-center">'.get_LeanerType($row['id_type']).'</td>';
                                }
                                echo'                                
                                <td class="text-center">'.get_status($row['admoff_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" href="?edit_id='.$row['admoff_id'].'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['admoff_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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