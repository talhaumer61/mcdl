<?php

$search_word = '';
$status      = '';
$pay_mode    = '';
$from_date   = '';
$to_date     = '';

$search_query = '';
$filters      = 'search&'.$redirection.'';

// Search Word Filter
if (!empty($_GET['search_word'])) {
    $search_word   = $_GET['search_word'];
    $search_query .= ' AND (ch.challan_no LIKE "%'.$search_word.'%" OR s.std_name LIKE "%'.$search_word.'%")';
    $filters      .= '&search_word='.$search_word;
}

// Status Filter
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $status        = $_GET['status'];
    $search_query .= ' AND ch.status = "'.intval($status).'"';
    $filters      .= '&status='.$status;
}

// Payment Mode Filter
if (isset($_GET['pay_mode']) && $_GET['pay_mode'] !== '') {
    $pay_mode      = $_GET['pay_mode'];
    $search_query .= ' AND ch.pay_mode = "'.intval($pay_mode).'"';
    $filters      .= '&pay_mode='.$pay_mode;
}

// Date Range Filter (Filters by Issue Date)
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from_date     = $_GET['from_date'];
    $to_date       = $_GET['to_date'];
    $search_query .= ' AND DATE(ch.issue_date) BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
    $filters      .= '&from_date='.$from_date.'&to_date='.$to_date;
} elseif (!empty($_GET['from_date'])) {
    $from_date     = $_GET['from_date'];
    $search_query .= ' AND DATE(ch.issue_date) >= "'.$from_date.'"';
    $filters      .= '&from_date='.$from_date;
} elseif (!empty($_GET['to_date'])) {
    $to_date       = $_GET['to_date'];
    $search_query .= ' AND DATE(ch.issue_date) <= "'.$to_date.'"';
    $filters      .= '&to_date='.$to_date;
}

// Base Condition
$condition = array(
    'select'      => 'ch.*, s.std_name, s.std_loginid',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std',
    'where'       => array(
        'ch.is_deleted' => 0
    ),
    'search_by'   => ''.$search_query.'',
    'order_by'    => 'ch.challan_id DESC',
    'return_type' => 'count'
);

$count = $dblms->getRows(CHALLANS.' ch', $condition, $sql);

// Challan Amount Statistics (Updated to support active filters)
$totalAmount   = 0;
$paidAmount    = 0;
$pendingAmount = 0;

// Total Amount
$conditionStats = array(
    'select'      => 'SUM(ch.total_amount) as amount',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std',
    'where'       => array('ch.is_deleted' => 0),
    'search_by'   => ''.$search_query.'',
    'return_type' => 'single'
);
$totalStats = $dblms->getRows(CHALLANS.' ch', $conditionStats);
$totalAmount = !empty($totalStats['amount']) ? $totalStats['amount'] : 0;

// Paid Amount
$conditionStats = array(
    'select'      => 'SUM(ch.paid_amount) as amount',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std',
    'where'       => array(
        'ch.is_deleted' => 0,
        'ch.status'     => 1
    ),
    'search_by'   => ''.$search_query.'',
    'return_type' => 'single'
);
$paidStats = $dblms->getRows(CHALLANS.' ch', $conditionStats);
$paidAmount = !empty($paidStats['amount']) ? $paidStats['amount'] : 0;

// Pending Amount
$conditionStats = array(
    'select'      => 'SUM(ch.total_amount) as amount',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std',
    'where'       => array(
        'ch.is_deleted' => 0,
        'ch.status'     => 2
    ),
    'search_by'   => ''.$search_query.'',
    'return_type' => 'single'
);
$pendingStats = $dblms->getRows(CHALLANS.' ch', $conditionStats);
$pendingAmount = !empty($pendingStats['amount']) ? $pendingStats['amount'] : 0;

// Fetch options array for Pay Mode dropdown dynamically
$payModesList = get_payMode();

echo '
<div class="card">
    <div class="card-body pb-0">
        <div class="row g-3">
            <!-- Total Amount -->
            <div class="col-xl-4 col-md-6">
                <div class="card mb-0 h-100 border-0" style="border-left: 4px solid #405189 !important; border-radius: 12px; background-color: #eef2ff; box-shadow: 0 3px 10px rgba(64, 81, 137, 0.10) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-uppercase fw-semibold fs-12 text-primary">Total Amount</span>
                                <h3 class="mb-1 mt-2 fw-bold text-dark">'.number_format($totalAmount, 2).'</h3>
                                <p class="text-muted mb-0 fs-13">
                                    <i class="ri-file-list-3-line me-1"></i>
                                    Total challan amount
                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle bg-primary text-white shadow-sm">
                                        <i class="ri-wallet-3-line fs-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid Amount -->
            <div class="col-xl-4 col-md-6">
                <div class="card mb-0 h-100 border-0" style="border-left: 4px solid #0ab39c !important; border-radius: 12px; background-color: #eafaf6; box-shadow: 0 3px 10px rgba(10, 179, 156, 0.10) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="text-uppercase fw-semibold fs-12 text-success">
                                        <i class="ri-check-line me-1"></i> Paid Amount
                                    </span>
                                </div>
                                <h3 class="mb-1 mt-2 fw-bold text-success">'.number_format($paidAmount, 2).'</h3>
                                <p class="text-muted mb-0 fs-13">
                                    <i class="ri-bank-card-line me-1"></i>
                                    Amount received
                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle bg-success text-white shadow-sm">
                                        <i class="ri-checkbox-circle-line fs-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Amount -->
            <div class="col-xl-4 col-md-6">
                <div class="card mb-0 h-100 border-0" style="border-left: 4px solid #f7b84b !important; border-radius: 12px; background-color: #fff6e5; box-shadow: 0 3px 10px rgba(247, 184, 75, 0.12) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="text-uppercase fw-semibold fs-12 text-warning">
                                        <i class="ri-time-line me-1"></i> Pending Amount
                                    </span>
                                </div>
                                <h3 class="mb-1 mt-2 fw-bold text-warning">'.number_format($pendingAmount, 2).'</h3>
                                <p class="text-muted mb-0 fs-13">
                                    <i class="ri-error-warning-line me-1"></i>
                                    Amount to be collected
                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="avatar-lg">
                                    <div class="avatar-title rounded-circle bg-warning text-white shadow-sm">
                                        <i class="ri-time-line fs-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-header mt-3">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Form -->
        <form class="form-horizontal mb-3" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <div class="row g-2 justify-content-end">
                <!-- Status Filter -->
                <div class="col-md-2 col-sm-6">
                    <select class="form-select" data-choices name="status">
                        <option value="">All</option>
                        <option value="1" '.($status === "1" ? "selected" : "").'>Paid</option>
                        <option value="2" '.($status === "2" ? "selected" : "").'>Pending</option>
                    </select>
                </div>

                <!-- Payment Mode Filter -->
                <div class="col-md-2 col-sm-6">
                    <select class="form-select" data-choices name="pay_mode">
                        <option value="">All Pay Modes</option>';
                        foreach ($payModesList as $modeKey => $modeName) {
                            echo '<option value="'.$modeKey.'" '.($pay_mode === (string)$modeKey ? "selected" : "").'>'.$modeName.'</option>';
                        }
                        echo '
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-md-2 col-sm-6">
                    <input type="date" class="form-control" name="from_date" value="'.$from_date.'" placeholder="From Date">
                </div>

                <!-- To Date -->
                <div class="col-md-2 col-sm-6">
                    <input type="date" class="form-control" name="to_date" value="'.$to_date.'" placeholder="To Date">
                </div>

                <!-- Search Input & Submit -->
                <div class="col-md-3 col-sm-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." name="search_word" value="'.$search_word.'">
                        <button type="submit" class="btn btn-primary" name="search"><i class="ri-search-2-line"></i> Filter</button>
                        <a href="'.moduleName().'.php" class="btn btn-soft-secondary" title="Reset Filters"><i class="ri-refresh-line"></i></a>
                    </div>
                </div>
            </div>
        </form>';

        if ($page == 0 || empty($page)) { $page = 1; }
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);
        $lpm1       = $lastpage - 1;

        $condition['order_by'] = "ch.challan_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(CHALLANS.' ch', $condition);

        if ($rowsList) {
            echo '
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
                            <th width="110" class="text-center">Paid Date</th>
                            <th width="110" class="text-center">Payment Mode</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="60" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            if (!in_array($row['std_loginid'], [8900])):
                                $srno++;
                                echo '
                                <tr style="vertical-align: middle;">
                                    <td class="text-center">'.$srno.'</td>
                                    <td class="text-center">'.$row['challan_no'].'</td>
                                    <td>'.$row['std_name'].'</td>
                                    <td class="text-center">'.($row['total_amount'] == 0 ? '<span class="badge badge-soft-success">Free</span>' : $row['currency_code'].' '.$row['total_amount']).'</td>
                                    <td class="text-center">'.date('d M, Y', strtotime($row['issue_date'])).'</td>
                                    <td class="text-center">'.date('d M, Y', strtotime($row['due_date'])).'</td>
                                    <td class="text-center">'.($row['paid_date'] != "0000-00-00" && !empty($row['paid_date']) ? date('d M, Y', strtotime($row['paid_date'])) : '-').'</td>
                                    <td class="text-center">'.($row['status'] == 1 ? get_payMode($row['pay_mode']) : '-').'</td>
                                    <td class="text-center">'.get_payments($row['status']).'</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                                <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/challans/view.php?challan_id='.$row['challan_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>';
                                                
                                                if (in_array($row['status'], [1, 3])) {
                                                    echo '<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/challans/remarks.php?challan_id='.$row['challan_id'].'\');"><i class="ri-file-info-fill align-bottom me-2 text-muted"></i> Remarks</a></li>';
                                                }
                                                if ($row['status'] == '2') {
                                                    echo '<li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/challans/edit.php?challan_id='.$row['challan_id'].'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>';
                                                    echo '<li><a class="dropdown-item text-success" onclick="showAjaxModalZoom(\'include/modals/challans/update.php?challan_id='.$row['challan_id'].'&status=1\');"><i class="ri-send-plane-fill align-bottom me-2"></i> Pay Challan</a></li>';
                                                    echo '<li><a class="dropdown-item text-danger" onclick="showAjaxModalZoom(\'include/modals/challans/update.php?challan_id='.$row['challan_id'].'&status=3\');"><i class="ri-close-circle-line align-bottom me-2"></i> Reject & Close</a></li>';
                                                }
                                                echo '
                                                <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['challan_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>';
                            endif;
                        }
                        echo '
                    </tbody>
                </table>';        
                include_once('include/pagination.php');
                echo '
            </div>';
        } else {
            echo '
            <div class="noresult" style="display: block">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                    <h5 class="mt-2">Sorry! No Record Found</h5>
                </div>
            </div>';
        }
        echo '
    </div>
</div>';
?>