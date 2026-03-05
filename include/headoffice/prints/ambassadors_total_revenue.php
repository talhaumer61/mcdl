<?php
if (!isset($_POST['date']) || empty($_POST['date'])) {
    $view = $_GET['view'] ? $_GET['view'] : '';
    header("Location: reports.php?view=" . $view);
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

$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
$searchBy .= " AND ec.id_org != '0' AND ec.id_org IS NOT NULL AND ec.id_org != '' ";

$condition = array(
    'select'    => 'ec.id_type, 
                    COUNT(ec.secs_id) AS total_learnings, 
                    SUM(cc.total_amount) AS total_revenue,
                    SUM((cc.total_amount * sa.org_profit_percentage) / 100) AS total_incentive',
    'join'      => 'INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                    INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0 AND cc.status = 1
                    INNER JOIN '.SKILL_AMBASSADOR.' sa ON sa.org_id = ec.id_org', 
    'where'     => array(
        'ec.is_deleted' => 0,
        'ec.secs_status' => 1
    ),
    'search_by' => $searchBy,
    'group_by'  => 'ec.id_type',
    'return_type' => 'all'
);

$db_data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

// Re-index database results
$indexed_data = [];
if($db_data){
    foreach ($db_data as $row) {
        $indexed_data[$row['id_type']] = $row;
    }
}

// 4. Generate Table
echo '
<table id="printResult">
    <thead>
        <tr>
            <th class="text-center">Learning Type</th>
            <th class="text-center">Number of Learnings</th>
            <th class="text-center">Total Revenue</th>
            <th class="text-center">Incentives Paid</th>
        </tr>
    </thead>
    <tbody>';

        $grandTotal     = 0;
        $learningCount  = 0;
        $incentiveTotal = 0;

        foreach (array_reverse($enroll_type) as $type) {
            $id      = $type['id'];
            $name    = $type['name'];
            
            $count     = $indexed_data[$id]['total_learnings'] ?? 0;
            $revenue   = $indexed_data[$id]['total_revenue']   ?? 0;
            $incentive = $indexed_data[$id]['total_incentive'] ?? 0;

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

        echo '
        <tr class="text-center">
            <th>TOTAL</th>
            <th>'.number_format($learningCount).'</th>
            <th>'.number_format($grandTotal).'</th>
            <th>'.number_format($incentiveTotal).'</th>
        </tr>
    </tbody>
</table>';

?>