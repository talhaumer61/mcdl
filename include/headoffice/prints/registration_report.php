<?php
if(!isset($_POST['date'])){
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

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {

    $parts = explode('to', $dateRange);

    $fromDate = !empty($parts[0]) ? $parts[0] : $fromDate;
    $toDate   = !empty($parts[1]) ? $parts[1] : $toDate;

    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$searchBy = " AND s.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";


$condition = array(
            'select'    => 's.std_id, s.std_name, s.std_gender, s.std_address_1, a.adm_email, a.adm_phone, GROUP_CONCAT(se.program) AS programs',
            'join'      => 'INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                            LEFT JOIN '.STUDENT_EDUCATIONS.' se ON s.std_id = se.id_std',
            'where'       => array(
                                's.std_status' => 1,
                                's.is_deleted' => 0
                            ),
            'search_by'   => $searchBy,
            'group_by' => 's.std_id',
            'return_type' => 'all'
        );

$students = $dblms->getRows(STUDENTS.' s', $condition);
echo'

    <!-- TABLE -->
    <table id="printResult">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
                <th class="text-center">Contact No</th>
                <th>Address</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Qualification</th>
            </tr>
        </thead>
        <tbody>';
            if (!empty($students)) {
                $sr = 0;
                foreach ($students as $key => $row) {
                    $sr++;
                    echo '
                    <tr>
                        <td>'.$sr.'</td>
                        <td>'.$row['std_name'].'</td>
                        <td>'.$row['adm_email'].'</td>
                        <td class="text-center">'.$row['adm_phone'].'</td>
                        <td>'.$row['std_address_1'].'</td>
                        <td class="text-center">'.($row['std_gender'] ? get_gendertypes($row['std_gender']) : '-').'</td>
                        <td '.(!empty($row['programs']) ? "":"class='text-center'").'>';
                            if (!empty($row['programs'])) {
                                $programs = explode(',', $row['programs']);
                                echo '<ul style="margin:0;padding-left:18px;text-align:left;">';
                                foreach ($programs as $p) {
                                    echo '<li>' . trim($p) . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '—';
                            }
                                echo'
                        </td>

                    </tr>';
                }
            } else {
                echo '
                <tr>
                    <h4 class="text-center text-danger">
                        No record found
                    </h4>
                </tr>';
            }
            echo'
        </tbody>
    </table>


';
?>