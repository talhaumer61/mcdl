<?php
if(empty($_POST['date']) || empty($_POST['pay_status'])){
    header("Location: reports.php?view=" .LMS_VIEW);
    exit();
}
$dateRange = $_POST['date'] ?? '';
$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');
if (!empty($dateRange)) {
    $parts = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$payStatus = $_POST['pay_status'] ?? '';
$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
if ($payStatus !== '') {
    $searchBy .= " AND cc.status = '{$payStatus}'";
}

$condition = array(
    'select'       => 'cc.challan_no, s.std_name, a.adm_email, a.adm_phone, 
                       c.curs_name, cd.amount as course_amount, ec.id_type, cc.challan_id, cc.paid_date',
    'join'         => 'INNER JOIN '.CHALLANS.' cc ON FIND_IN_SET(ec.secs_id, cc.id_enroll)
                       INNER JOIN '.CHALLAN_DETAIL.' cd ON (cd.id_challan = cc.challan_id AND cd.id_enroll = ec.secs_id)
                       INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id
                       INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                       INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id',
    'where'        => array('ec.is_deleted' => 0),
    'search_by'    => $searchBy . " ORDER BY cc.challan_no ASC", 
    'return_type'  => 'all'
);

$trainings = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

// --- GROUP DATA BY CHALLAN ---
$groupedData = [];
if($trainings){
    foreach ($trainings as $row) {
        $groupedData[$row['challan_no']][] = $row;
    }
}

echo '
<table id="printResult" border="1" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eeeeee;">
            <th>#</th>
            <th>Challan No</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Paid Date</th>
            <th>Contact No</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>';

    $sr = 1;
    $grandTotal = 0;
    $enrollType = array_column($enroll_type, 'name', 'id');

    if (!empty($groupedData)) {
        foreach ($groupedData as $challanNo => $rows) {
            $rowSpan = count($rows); // How many courses in this challan
            
            foreach ($rows as $index => $row) {
                $grandTotal += $row['course_amount'];
                
                echo '<tr>';
                
                // Only print these columns for the FIRST row of the group
                if ($index === 0) {
                    echo 
                    '<td rowspan="'.$rowSpan.'" style="vertical-align:middle; text-align:center;">'.$sr++.'</td>
                    <td rowspan="'.$rowSpan.'" style="vertical-align:middle;">'.$row['challan_no'].'</td>
                    <td rowspan="'.$rowSpan.'" style="vertical-align:middle;"><b>'.$row['std_name'].'</b></td>';
                }

                // These columns repeat for every course
                echo 
                '<td>'.$row['curs_name'].'</td>
                <td class="text-center">'.($enrollType[$row['id_type']] ?? '-').'</td>
                <td class="text-center">'.number_format($row['course_amount']).'</td>
                
                ';

                // These columns also stay grouped with the student
                if ($index === 0) {
                    echo '
                    <td class="text-center" rowspan="'.$rowSpan.'">'.($payStatus == 1 ? $row['paid_date'] : '-').'</td>
                    <td rowspan="'.$rowSpan.'" style="vertical-align:middle;">'.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'</td>
                    <td rowspan="'.$rowSpan.'" style="vertical-align:middle;">'.$row['adm_email'].'</td>
                    ';
                }

                echo '</tr>';
            }
        }
    } else {
        echo '<tr><td colspan="8" class="text-center">No records found</td></tr>';
    }

    echo '
        <tr style="font-weight:bold; background:#f5f5f5;">
            <td colspan="5" style="text-align:right;">TOTAL</td>
            <td colspan="4">'.number_format($grandTotal).'</td>
        </tr>
    </tbody>
</table>';
?>