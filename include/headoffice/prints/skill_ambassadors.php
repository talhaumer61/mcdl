<?php
if(!isset($_POST['date']) || empty($_POST['date'])) {
    $view = !empty($_GET['view']) ? $_GET['view'] : '';
    header("Location: reports.php?view=" . $view);
    exit();
}
echo '
<style>
@page {
        size: A4 landscape;
        margin: 10mm;
    }
</style>
';
$dateRange = $_POST['date'] ?? '';

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {
    $parts = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

$conditions = array(
                    'select' => 'sa.org_name, sa.org_telephone, sa.org_email, sa.org_city, sa.org_profit_percentage, sa.org_link_to, s.std_name, ec.secs_id, cc.status as challan_status, cc.total_amount as raw_amount',
                    'join'  => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                                INNER JOIN '.SKILL_AMBASSADOR.' sa ON sa.org_id = ec.id_org
                                INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                                INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0 AND cc.status = 1',
                    'where' => array(
                        'ec.is_deleted' => 0,
                        'ec.secs_status'=> 1
                    ),
                    'search_by' => $searchBy,
                    'group_by'  => 'ec.secs_id',
                    'return_type' => 'all'
                );

$report = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);

// Group By Ambassador
$grouped = [];
if($report){
    foreach ($report as $r) {
        $grouped[$r['org_name']][] = $r;
    }
}

if (empty($grouped)) {
    echo '
    <table border="1" style="width:100%; border-collapse:collapse; text-align:center; font-family:sans-serif;">
        <tr>
            <td><h4 class="text-danger">No record found!</h4></td>
        </tr>
    </table>';
} else {

echo '
<table id="printResult">
    <thead>
        <tr class="text-center">
            <th>Skill Ambassador Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>City</th>
            <th>Total Sign-ups</th>
            <th>Total Enrollments</th>
            <th>Paid Revenue</th>
            <th>Incentive Paid</th>
            <th>Referal Link Expiry</th>
        </tr>
    </thead>
    <tbody>';

$grandRevenue = 0;
$grandIncentive = 0;

foreach ($grouped as $ambName => $rows) {

    $students = [];
    $totalEnrollments = 0;
    $totalRevenue = 0;
    $percentage = 0;

    foreach ($rows as $r) {

        $students[$r['std_name']] = true; // unique student
        $totalEnrollments++;

        $percentage = $r['org_profit_percentage'] ?? 0;

        if ($r['challan_status'] == 1) { // Paid only
            $totalRevenue += $r['raw_amount'];
        }
    }

    $totalSignups = count($students);
    $incentive = ($totalRevenue * $percentage) / 100;

    $grandRevenue += $totalRevenue;
    $grandIncentive += $incentive;

    echo '
        <tr class="text-center">
            <td>'.$ambName.'</td>
            <td>'.($rows[0]['org_telephone'] ? $rows[0]['org_telephone'] : '-' ).'</td>
            <td>'.($rows[0]['org_email'] ? $rows[0]['org_email'] : '-' ).'</td>
            <td>'.($rows[0]['org_city'] ? $rows[0]['org_city'] : '-' ).'</td>
            <td>'.$totalSignups.'</td>
            <td>'.$totalEnrollments.'</td>
            <td>'.number_format($totalRevenue).'</td>
            <td>'.number_format($incentive).'</td>
            <td>'.$rows[0]['org_link_to'].'</td>
        </tr>
          ';
}

    echo '
        <tr class="text-center">
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>'.number_format($grandRevenue).'</th>
            <th>'.number_format($grandIncentive).'</th>
            <th></th>
        </tr>
    </tbody>
</table>';
}
?>