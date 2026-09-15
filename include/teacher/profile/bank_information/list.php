<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (bank_account_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                        'select'       =>  'bank_id,bank_status,bank_account_name,bank_account_no,bank_account_iban_no,bank_name,bank_branch_name,bank_branch_code'
                        ,'where' 	    =>  array   (  
                                                         'is_deleted'       => 0
                                                        ,'id_emply'         => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    )
                        ,'search_by'    =>  ''.$search_query.''
                        ,'order_by'     =>  'bank_id DESC'
                        ,'return_type'  =>  'count'
                    );
$count = $dblms->getRows(BANK_INFORMATION, $condition);
echo'
<div class="row justify-content-end">
    <div class="col-3">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <input type="hidden" name="view" value="'.LMS_VIEW.'">
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

$condition['order_by']      = "bank_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(BANK_INFORMATION, $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="10">Sr.</th>
                    <th>Name</th>
                    <th class="text-center" width="150">AC/IBAN Number</th>
                    <th class="text-center" width="150">Bank Name</th>
                    <th class="text-center" width="150">Branch Name (Code)</th>
                    <th class="text-center" width="50">Status</th>
                    <th class="text-center" width="35">Action</th>
                </tr>
            </thead>
            <tbody>';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowslist as $row) {
                    $srno++;
                    echo '
                    <tr style="vertical-align: middle;">
                        <td class="text-center">'.$srno.'</td>
                        <td>'.moduleName($row['bank_account_name']).'</td>
                        <td class="text-center">
                            <span onclick="copyToClipboard(\''.$row['bank_account_no'].'\');" style="color: blue; cursor: pointer;">'.$row['bank_account_no'].'</span>
                             / 
                            <span onclick="copyToClipboard(\''.$row['bank_account_iban_no'].'\');" style="color: blue; cursor: pointer;">'.$row['bank_account_iban_no'].'</span>
                        </td>
                        <td class="text-center">'.get_bank($row['bank_name']).'</td>
                        <td class="text-center">'.moduleName($row['bank_branch_name']).' ('.$row['bank_branch_code'].')'.'</td>
                        <td class="text-center">'.get_status($row['bank_status']).'</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                    <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/'.moduleName().'/'.LMS_VIEW.'/edit.php?bank_id='.$row['bank_id'].'&view='.LMS_VIEW.'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                    <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['bank_id'].'&view='.LMS_VIEW.'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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