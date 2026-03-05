<?php
// 1. Redirect if date or type is missing
if (!isset($_POST['date']) || empty($_POST['date']) || empty($_POST['type'])) {
    $view = $_GET['view']  ? LMS_VIEW : '';
    header("Location: reports.php?view=" . $view);
    exit();
}

// 2. Processing Inputs
$dateRange = $_POST['date'];
$type      = $_POST['type'];
$fromDate  = date('Y-m-01');
$toDate    = date('Y-m-t');

if (!empty($dateRange)) {
    $parts    = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

// 3. Database Query
$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

$condition = array(
    'select'    => 'c.curs_name, COUNT(ec.secs_id) AS total_learnings, SUM(cd.amount) AS total_revenue',
    'join'      => 'INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                    INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0 AND cc.status = 1
                    INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id AND c.is_deleted = 0 AND c.curs_status = 1',
    'where'     => array(
                        'ec.id_type'      => $type,
                        'ec.secs_status'  => 1,
                        'ec.is_deleted'   => 0
                    ),
    'search_by' => $searchBy,
    'group_by'  => 'ec.id_curs',
    'return_type' => 'all'
);

$training_data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);
$enrollType = array_column($enroll_type, 'name', 'id');

// 4. Generate Table
echo '
<table id="printResult">
    <thead>
        <tr>
            <th class="text-center" width="40%">'.$enrollType[$type].' Name</th>
            <th class="text-center">Number of Students</th>
            <th class="text-center">Total Revenue</th>
        </tr>
    </thead>
    <tbody>';

$grandTotal    = 0;
$learningCount = 0;


if (empty($training_data)) {
    echo '<tr><td colspan="3" class="text-center py-3">No revenue recorded for this type in the selected dates.</td></tr>';
} else {
    foreach ($training_data as $row) {
        $name    = $row['curs_name'];
        $count   = $row['total_learnings'];
        $revenue = $row['total_revenue'];

        $grandTotal    += $revenue;
        $learningCount += $count;

        echo '
        <tr>
            <td class="text-center">'.$name.'</td>
            <td class="text-center">'.$count.'</td>
            <td class="text-center">'.number_format($revenue).'</td>
        </tr>';
    }
}

echo '
        <tr>
            <th class="text-center">TOTAL</th>
            <th class="text-center">'.number_format($learningCount).'</th>
            <th class="text-center">'.number_format($grandTotal).'</th>
        </tr>
    </tbody>
</table>';
?>