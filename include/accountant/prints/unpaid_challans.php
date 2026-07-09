<?php
$dateRange = $_GET['date'] ?? '';

// Defaults
$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {

    // split by "to"
    $parts = explode('to', $dateRange);

    // assign if available
    $fromDate = !empty($parts[0]) ? $parts[0] : $fromDate;
    $toDate   = !empty($parts[1]) ? $parts[1] : $toDate;

    // if only one date selected, use same date
    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

// Fix reverse range
if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";


$condition = array(
                'select'      => 'c.curs_name, s.std_name,  cc.challan_no, cc.total_amount, a.adm_email, a.adm_phone',
                'join'        => 'INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id
                                 INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                                 INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                                 INNER JOIN '.CHALLANS.' cc ON FIND_IN_SET(cc.id_enroll, ec.secs_id) AND cc.status = 2',
                'where'       => array(
                                    'ec.is_deleted'  => 0
                                ),
                'search_by'   => $searchBy,
                'return_type' => 'all'
);

$trainings = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

echo '
<table id="printResult">
    <thead>
        <tr>
            <th>#</th>
            <th>Challan No</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Amount</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>';

    $sr = 1;
    $grandTotal = 0;
    foreach ($trainings as $row) {
         $grandTotal += $row['total_amount'];
        echo '
        <tr>
            <td>'.$sr++.'</td>
            <td>'.$row['challan_no'].'</td>
            <td>'.$row['std_name'].'</td>
            <td>'.$row['curs_name'].'</td>
            <td>'.$row['total_amount'].'</td>
            <td>'.$row['adm_email'].'</td>
            <td '.(!empty($row['adm_phone']) ? $row['adm_phone'] : "class='text-center'").'>'.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'</td>
        </tr>';
    }
    echo '
        <tr style="font-weight:bold; background:#f5f5f5;">
            <td colspan="2" class="text-center">TOTAL</td>
            <td colspan="2"></td>
            <td class="text-end">'.number_format($grandTotal).'</td>
            <td colspan="2"></td>
        </tr>';
echo '
    </tbody>
</table>';

?>