<?php
// 1. Redirect if date is missing
if (!isset($_POST['date']) || empty($_POST['date'])) {
    $view = $_POST['view'] ?? (defined('LMS_VIEW') ? LMS_VIEW : '');
    header("Location: reports.php?view=" . $view);
    exit();
}

// 2. Date Processing
$dateRange = $_POST['date'];
$fromDate  = date('Y-m-01');
$toDate    = date('Y-m-t');

if (!empty($dateRange)) {
    $parts    = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

// 3. Database Query
$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

$condition = array(
    'select'    => 'ec.id_type, COUNT(ec.secs_id) AS total_learnings, SUM(cd.amount) AS total_revenue',
    'join'      => 'INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                    INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0 AND cc.status = 1',
    'where'     => array(
        'ec.is_deleted' => 0,
        'ec.secs_status' => 1
    ),
    'search_by' => $searchBy,
    'group_by'  => 'ec.id_type',
    'return_type' => 'all'
);

$db_data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

// Re-index database results by id_type for easy lookup
$indexed_data = [];
foreach ($db_data as $row) {
    $indexed_data[$row['id_type']] = $row;
}

// 4. Generate Table based on ALL enrollment types
echo '
<table id="printResult">
    <thead>
        <tr>
            <th class="text-center">Learning Type</th>
            <th class="text-center">Number of Learnings</th>
            <th class="text-center">Total Revenue</th>
        </tr>
    </thead>
    <tbody>';

$grandTotal    = 0;
$learningCount = 0;

// Loop through the master enrollment types array (provided globally)
foreach (array_reverse($enroll_type) as $type) {
    $id   = $type['id'];
    $name = $type['name'];
    
    // Check if we have DB records for this type, otherwise default to 0
    $count   = $indexed_data[$id]['total_learnings'] ?? 0;
    $revenue = $indexed_data[$id]['total_revenue'] ?? 0;

    $grandTotal    += $revenue;
    $learningCount += $count;

        echo '
        <tr>
            <td class="text-center">'.$name.'</td>
            <td class="text-center">'.$count.'</td>
            <td class="text-center">'.number_format($revenue).'</td>
        </tr>
        ';
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