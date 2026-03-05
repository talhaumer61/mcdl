<?php
if(!isset($_POST['date']) || !isset($_POST['id_org'])){
    header("Location: reports.php?view=" . LMS_VIEW);
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
$id_org    = $_POST['id_org'] ?? '';

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {
    $parts = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
if($id_org){
    $searchBy .= " AND ec.id_org = '$id_org' ";
}

$conditions = array(
                    'select' => 'sa.org_name, sa.org_profit_percentage, s.std_name, s.city_name, a.adm_email, a.adm_phone,c.curs_name,ec.secs_id, cc.status as challan_status,cc.total_amount as raw_amount,COUNT(DISTINCT cl.lesson_id) AS lesson_count,COUNT(DISTINCT ca.id) AS assignment_count,COUNT(DISTINCT q.quiz_id) AS quiz_count,COUNT(DISTINCT lt.track_id) AS track_count',
                    'join'  => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                                INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                                INNER JOIN '.SKILL_AMBASSADOR.' sa ON sa.org_id = ec.id_org
                                LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                                LEFT JOIN '.CHALLANS.' cc ON FIND_IN_SET(ec.secs_id, cc.id_enroll)
                                LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = ec.id_curs AND cl.lesson_status=1 AND cl.is_deleted=0
                                LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = ec.id_curs AND ca.status=1 AND ca.is_deleted=0
                                LEFT JOIN '.QUIZ.' q ON q.id_curs = ec.id_curs AND q.quiz_status=1 AND q.is_deleted=0
                                LEFT JOIN '.LECTURE_TRACKING.' lt ON lt.id_curs = ec.id_curs AND lt.id_std = ec.id_std AND lt.is_completed = 2',
                    'where' => array(
                                    'ec.is_deleted' => 0,
                                    'ec.secs_status'=> 1
                                ),
                    'search_by' => $searchBy,
                    'group_by'  => 'ec.secs_id',
                    'return_type' => 'all'
                );

$report = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);

$grouped = [];
if($report){
    foreach ($report as $r) {
        $grouped[$r['org_name']][$r['std_name']][] = $r;
    }
}

if (empty($grouped)) {
    echo '
    <table>
        <tr>
            <h4 class="fw-bold text-danger">
                No record found!
            </h4>
        </tr>
    ';
} else {
    echo '
    <table id="printResult">
        <thead>
            <tr>
                <th>Ambassador</th>
                <th>Sign-ups</th>
                <th>Enrollments</th>
                <th>Student Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th class="text-center">City</th>
                <th>Course / Training</th>
                <th>Progress</th>
                <th class="text-center">Unpaid Challans Amount</th>
                <th>Revenue</th>
                <th>Incentive</th>
            </tr>
        </thead>
        <tbody>';

        $grandTotalRevenue = 0;
        $grandTotalUnpaid = 0;
        $grandTotalIncentive = 0;

        foreach ($grouped as $ambName => $students) {
            
            $ambRowspan = 0;
            $ambTotalRevenue = 0;
            $ambTotalUnpaid = 0;
            $ambPercentage = 0;
            $studentCount = count($students);

            foreach ($students as $stdName => $courses) {
                $ambRowspan += count($courses);
                foreach ($courses as $c) {
                    $ambPercentage = $c['org_profit_percentage'] ?? 0;
                    
                    if ($c['challan_status'] == 1) {
                        $ambTotalRevenue += $c['raw_amount'];
                    } elseif ($c['challan_status'] == 2) {
                        $ambTotalUnpaid += $c['raw_amount'];
                    }
                }
            }

            $totalEnrollments = $ambRowspan;
            $ambIncentive = ($ambTotalRevenue * $ambPercentage) / 100;
            
            $grandTotalRevenue += $ambTotalRevenue;
            $grandTotalUnpaid += $ambTotalUnpaid;
            $grandTotalIncentive += $ambIncentive;

            $firstAmb = true;
            foreach ($students as $stdName => $courses) {
                $stdRowspan = count($courses);
                $firstStd = true;

                foreach ($courses as $c) {
                    $totalItems = $c['lesson_count'] + $c['assignment_count'] + $c['quiz_count'];
                    $percent    = ($totalItems > 0) ? round(($c['track_count'] / $totalItems) * 100) : 0;
                    if ($percent > 100) $percent = 100;

                    echo '
                    <tr>';
                    
                    if ($firstAmb) {
                        echo '<td rowspan="'.$ambRowspan.'" class="text-center fw-bold align-content-start">'.$ambName.'</td>';
                        echo '
                            <td rowspan="'.$ambRowspan.'" class="text-center align-content-start">
                                '.$studentCount.'
                            </td>
                            <td rowspan="'.$ambRowspan.'" class="text-center align-content-start">
                                '.$totalEnrollments.'
                            </td>
                            ';
                    }
                    if ($firstStd) {
                        echo '
                            <td rowspan="'.$stdRowspan.'" style="vertical-align:middle;">'.$stdName.'</td>
                            <td rowspan="'.$stdRowspan.'">'.$c['adm_phone'].'</td>
                            <td rowspan="'.$stdRowspan.'">'.$c['adm_email'].'</td>
                            <td rowspan="'.$stdRowspan.'" style="vertical-align:middle;" class="text-center">'.$c['city_name'].'</td>';
                        $firstStd = false;
                    }
                        echo '
                            <td>'.$c['curs_name'].'</td>
                            <td class="text-center">'.$percent.'%</td>';
                    if ($firstAmb) {
                        echo '
                            <td rowspan="'.$ambRowspan.'" class="text-center fw-bold align-content-start">'.number_format($ambTotalUnpaid).'</td>
                            <td rowspan="'.$ambRowspan.'" class="text-center fw-bold align-content-start">'.number_format($ambTotalRevenue).'</td>
                            <td rowspan="'.$ambRowspan.'" class="text-center fw-bold align-content-start">'.number_format($ambIncentive).'</td>';
                        $firstAmb = false;
                    }
                    echo '
                    </tr>';
                }
            }
        }

        echo '
        </tbody>
        <tfoot>
            <tr>
                <th colspan="9" class="text-end">Grand Totals:</th>
                <th class="text-center">'.number_format($grandTotalUnpaid).'</th>
                <th class="text-center">'.number_format($grandTotalRevenue).'</th>
                <th class="text-center">'.number_format($grandTotalIncentive).'</th>
            </tr>
        </tfoot>
    </table>
    ';
}   
?>