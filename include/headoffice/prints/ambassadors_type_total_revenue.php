<?php
// 1. Redirect if date or type is missing
if (!isset($_POST['date']) || empty($_POST['date']) || empty($_POST['type'])) {
    $view = $_GET['view'] ? $_GET['view'] : '';
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
$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
// Filter: Must have an ambassador/org linked
$searchBy .= " AND ec.id_org != '0' AND ec.id_org IS NOT NULL AND ec.id_org != '' ";

$condition = array(
    'select'    => 'c.curs_name, 
                    COUNT(ec.secs_id) AS total_learnings, 
                    SUM(cc.total_amount) AS total_revenue,
                    SUM((cc.total_amount * sa.org_profit_percentage) / 100) AS total_incentive',
    'join'      => 'INNER JOIN '.CHALLANS.' cc ON FIND_IN_SET(ec.secs_id, cc.id_enroll) AND cc.is_deleted = 0 AND cc.status = 1
                    INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id AND c.is_deleted = 0 AND c.curs_status = 1
                    INNER JOIN '.SKILL_AMBASSADOR.' sa ON sa.org_id = ec.id_org',
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

echo '
<table id="printResult">
    <thead>
        <tr class="text-center">
            <th>'.$enrollType[$type].' Name</th>
            <th>Total Students</th>
            <th>Total Revenue</th>
            <th>Incentives Paid</th>
        </tr>
    </thead>
    <tbody>';

$grandTotal     = 0;
$learningCount  = 0;
$incentiveTotal = 0;

if (empty($training_data)) {
    echo '<tr><td colspan="4" class="text-center text-danger fw-bold py-3">No data found!</td></tr>';
} else {
    foreach ($training_data as $row) {
        $name      = $row['curs_name'];
        $count     = $row['total_learnings'];
        $revenue   = $row['total_revenue'];
        $incentive = $row['total_incentive'];

        $grandTotal     += $revenue;
        $learningCount  += $count;
        $incentiveTotal += $incentive;

        echo '
        <tr class="text-center">
            <td class="fw-bold">'.$name.'</td>
            <td>'.$count.'</td>
            <td>'.number_format($revenue).'</td>
            <td>'.number_format($incentive).'</td>
        </tr>';
    }
}

echo '
        <tr class="text-center">
            <th>Total</th>
            <th>'.number_format($learningCount).'</th>
            <th>'.number_format($grandTotal).'</th>
            <th>'.number_format($incentiveTotal).'</th>
        </tr>
    </tbody>
</table>';
?>