<?php
if (!isset($_POST['date']) || empty($_POST['date']) || empty($_POST['id_curs'])) {
    sessionMsg("Error", "You must enter date.", "danger");
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}

$dateRange = $_POST['date'];
$id_curs   = $_POST['id_curs'];
$fromDate  = date('Y-m-01');
$toDate    = date('Y-m-t');

if (!empty($dateRange)) {
    $parts    = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

$condition = array(
    'select'    => 's.std_name, s.std_address_1, a.adm_email, a.adm_phone, ec.secs_id, cc.total_amount AS paid_amount, c.curs_name',
    'join'      => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ec.id_std
                    INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid
                    INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                    INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                    INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0 AND cc.status = 1',
    'where'     => array(
        'ec.id_curs'     => $id_curs,
        'ec.secs_status' => 1,
        'ec.is_deleted'  => 0
    ),
    'search_by' => $searchBy,
    'order_by'  => 's.std_name ASC',
    'return_type' => 'all'
);

$student_data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

if (empty($student_data)) {
    echo '
    <table>
        <tbody>
            <tr>
                <td class="text-center py-4">
                    <h4 class="text-danger fw-bold m-0">No Record Found!</h4>
                </td>
            </tr>
        </tbody>
    </table>';
} else {
    $courseHeading = $student_data[0]['curs_name'];

    echo '
    <div class="mt-4">
        <h3 class="text-center fw-bold text-uppercase mb-3">
            ' . $courseHeading . '
        </h3>
        
        <table id="printResult">
            <thead class="table-light">
                <tr class="text-center">
                    <th width="5%">#</th>
                    <th width="25%">Student Name</th>
                    <th width="20%">Email</th>
                    <th width="15%">Contact No</th>
                    <th width="25%">Address</th>
                    <th width="10%">Amount</th>
                </tr>
            </thead>
            <tbody>';

    $grandTotal    = 0;
    $studentCount  = count($student_data);

    foreach ($student_data as $key => $row) {
        $grandTotal += $row['paid_amount'];
        $sr = $key + 1;

        echo '
        <tr class="text-center">
            <td>' . $sr . '</td>
            <td class="text-start">' . $row['std_name'] . '</td>
            <td>' . ($row['adm_email'] ? $row['adm_email'] : "-") . '</td>
            <td>' . ($row['adm_phone'] ? $row['adm_phone'] : "-") . '</td>
            <td class="text-start">' . ($row['std_address_1'] ? $row['std_address_1'] : "-") . '</td>
            <td>' . number_format($row['paid_amount']) . '</td>
        </tr>';
    }

    echo '
            <tr class="fw-bold text-center bg-light">
                <td colspan="2">TOTAL STUDENTS: ' . $studentCount . '</td>
                <td colspan="3" class="text-end">GRAND TOTAL:</td>
                <td>' . number_format($grandTotal) . '</td>
            </tr>
        </tbody>
    </table>
    </div>';
}
?>