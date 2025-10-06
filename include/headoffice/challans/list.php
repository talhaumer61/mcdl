<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (ch.challan_no LIKE "%'.$search_word.'%" OR s.std_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'ch.*, s.std_name, s.std_loginid'
                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std'
                    ,'where'        =>  array(
                                                'ch.is_deleted'  => 0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  'ch.challan_id DESC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(CHALLANS.' ch', $condition, $sql);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
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

        $condition['order_by'] = "ch.challan_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(CHALLANS.' ch', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">Sr.</th>
                            <th width="100" class="text-center">Challan No</th>
                            <th>Name</th>
                            <th width="110" class="text-center">Total Amount</th>
                            <th width="110" class="text-center">Issue Date</th>
                            <th width="110" class="text-center">Due Date</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="60" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            if(!in_array($row['std_loginid'], [8900])):
                                $srno++;
                                echo '
                                <tr style="vertical-align: middle;">
                                    <td class="text-center">'.$srno.'</td>
                                    <td class="text-center">'.$row['challan_no'].'</td>
                                    <td>'.$row['std_name'].'</td>
                                    <td class="text-center">'.($row['total_amount'] == 0 ? '<span class="badge badge-soft-success">Free</span>' : $row['currency_code'].' '.$row['total_amount']).'</td>
                                    <td class="text-center">'.date('d M, Y',strtotime($row['issue_date'])).'</td>
                                    <td class="text-center">'.date('d M, Y',strtotime($row['due_date'])).'</td>
                                    <td class="text-center">'.get_payments($row['status']).'</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                                <!--<li><a class="dropdown-item text-primary" href="challan_print.php?challan_no='.$row['challan_no'].'" target="_blank"><i class="ri-printer-fill align-bottom me-2"></i> Print</a></li>-->
                                                <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/challans/view.php?challan_id='.$row['challan_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';
                                                if($row['status'] == '2'){
                                                    echo'<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/challans/edit.php?challan_id='.$row['challan_id'].'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>';
                                                }
                                                /*
                                                if($row['status'] == '2'){
                                                    echo'
                                                    <li><a class="dropdown-item text-success" onclick="showAjaxModalZoom(\'include/modals/challans/update.php?challan_id='.$row['challan_id'].'&status=1\');"><i class="ri-send-plane-fill align-bottom me-2"></i> Pay Challan</a></li>
                                                    <li><a class="dropdown-item text-danger" onclick="showAjaxModalZoom(\'include/modals/challans/update.php?challan_id='.$row['challan_id'].'&status=3\');"><i class="ri-close-circle-line align-bottom me-2"></i> Reject & Close</a></li>';
                                                }*/
                                                echo'
                                                <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['challan_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>';
                            endif;
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