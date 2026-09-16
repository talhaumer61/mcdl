<?php
if (!isset($_POST['date']) || empty($_POST['date'])) {
    sessionMsg("Error", "You must enter date.", "danger");
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}

$dateRange = $_POST['date'];
$fromDate  = date('Y-m-01');
$toDate    = date('Y-m-t');

if (!empty($dateRange)) {
    $parts    = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

// Base date filter query on Challan Issue / Added Date
$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

// Filter: Pay Mode (Only applies if explicitly selected)
if (!empty($_POST['pay_mode'])) {
    $payMode   = (int)$_POST['pay_mode'];
    $searchBy .= " AND cc.pay_mode = '{$payMode}'";
}

// Filter: Status (Only applies if explicitly selected; otherwise shows ALL statuses in the date range)
if (isset($_POST['status']) && $_POST['status'] !== '') {
    $status    = (int)$_POST['status'];
    $searchBy .= " AND cc.status = '{$status}'";
}

// Condition to query individual Challan details
$condition = array(
    'select'      => 'cc.challan_id, cc.challan_no, cc.total_amount, cc.paid_amount, cc.status, cc.pay_mode, cc.issue_date, cc.date_added, s.std_name',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON s.std_id = cc.id_std',
    'where'       => array(
        'cc.is_deleted' => 0
    ),
    'search_by'   => $searchBy,
    'order_by'    => 'cc.challan_id DESC',
    'return_type' => 'all'
);

$db_data = $dblms->getRows(CHALLANS.' cc', $condition);

// Empty Record / No Data Found Case
if (empty($db_data) || $db_data === false) {
    echo '
    <div class="card">
        <div class="card-body text-center py-4 border border-dark">
            <h4 class="text-danger fw-bold m-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>No Record Found!</h4>
            <p class="text-muted mb-0 mt-1">No challan records match your selected filter criteria.</p>
        </div>
    </div>';
} else {
    echo '
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="printResult">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">Sr.</th>
                            <th class="text-center">Challan No</th>
                            <th>Student Name</th>
                            <th class="text-center">Issue Date</th>
                            <th class="text-center">Pay Mode</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>';

    $srno        = 0;
    $grandTotal  = 0;

    foreach ($db_data as $row) {
        $srno++;
        $amount      = !empty($row['total_amount']) ? $row['total_amount'] : 0;
        $grandTotal += $amount;

        // Payment status badge renderer
        $statusBadge = ($row['status'] == 1) 
            ? 'Paid' 
            : 'Pending';

        // Payment mode lookup
        $payModeText = ($row['status'] == 1) ? get_payMode($row['pay_mode']) : '-';

        echo '
        <tr>
            <td class="text-center">'.$srno.'</td>
            <td class="text-center fw-semibold">'.$row['challan_no'].'</td>
            <td>'.$row['std_name'].'</td>
            <td class="text-center">'.date('d M, Y', strtotime($row['issue_date'])).'</td>
            <td class="text-center">'.$payModeText.'</td>
            <td class="text-center">'.$statusBadge.'</td>
            <td class="text-center fw-bold">'.number_format($amount, 2).'</td>
        </tr>';
    }

    echo '
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6" class="text-end fw-bold">TOTAL AMOUNT:</th>
                            <th class="text-center fw-bold">'.number_format($grandTotal, 2).'</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>';
}
?>