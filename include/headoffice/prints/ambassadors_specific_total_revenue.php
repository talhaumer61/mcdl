<?php
// 1. Redirect if date or course ID is missing
if (!isset($_POST['date']) || empty($_POST['date']) || empty($_POST['id_curs'])) {
    $view = $_GET['view'] ? $_GET['view'] : '';
    header("Location: reports.php?view=" . $view);
    exit();
}

// 2. Processing Inputs
$dateRange = $_POST['date'];
$id_curs   = $_POST['id_curs'];
$fromDate  = date('Y-m-01');
$toDate    = date('Y-m-t');

if (!empty($dateRange)) {
    $parts    = explode('to', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : (!empty($parts[0]) ? $parts[0] : $toDate);
}

// 3. Database Query
$searchBy = " AND cc.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
// Filter: Ensure enrollment is linked to an ambassador
$searchBy .= " AND ec.id_org != '0' AND ec.id_org IS NOT NULL AND ec.id_org != '' ";

$condition = array(
    'select'    => 's.std_name, s.std_address_1, a.adm_email, a.adm_phone, ec.secs_id, 
                    cc.total_amount AS paid_amount, c.curs_name,
                    ((cc.total_amount * sa.org_profit_percentage) / 100) AS individual_incentive',
    'join'      => 'INNER JOIN '.STUDENTS.' s ON s.std_id = ec.id_std
                    INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid
                    INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                    INNER JOIN '.CHALLANS.' cc ON FIND_IN_SET(ec.secs_id, cc.id_enroll) AND cc.is_deleted = 0 AND cc.status = 1
                    INNER JOIN '.SKILL_AMBASSADOR.' sa ON sa.org_id = ec.id_org',
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

// 4. Generate Table
echo '
    <table id="printResult">
        <thead class="table-light">
            <tr class="text-center">
                <th>Course Name</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Amount</th>
                <th>Incentives Paid</th>
            </tr>
        </thead>
        <tbody>';

            $grandTotal     = 0;
            $grandIncentive = 0;
            $studentCount   = count($student_data);
            $rowCount       = ($studentCount > 0) ? $studentCount : 1;

            if (empty($student_data)) {
                echo '<tr><td colspan="7" class="text-center py-3 text-danger">No ambassador-linked data found for this course!</td></tr>';
            } else {
                foreach ($student_data as $key => $row) {
                    $grandTotal     += $row['paid_amount'];
                    $grandIncentive += $row['individual_incentive'];

                    echo '<tr class="text-center">';
                        
                        // Rowspan Course Name
                        if ($key === 0) {
                            echo '<td rowspan="'.$rowCount.'" class="fw-bold align-content-start">'.$row['curs_name'].'</td>';
                        }

                        echo '
                            <td>'.$row['std_name'].'</td>
                            <td>'.($row['adm_email'] ?: "-").'</td>
                            <td>'.($row['adm_phone'] ?: "-").'</td>
                            <td>'.($row['std_address_1'] ?: "-").'</td>
                            <td>'.number_format($row['paid_amount']).'</td>
                            <td>'.number_format($row['individual_incentive']).'</td>
                        </tr>';
                }
            }

            echo '
            <tr class="text-center fw-bold">
                <th>TOTAL</th>
                <th>'.$studentCount.'</th>
                <th colspan="3"></th>
                <th>'.number_format($grandTotal).'</th>
                <th>'.number_format($grandIncentive).'</th>
            </tr>
        </tbody>
    </table>
';
?>