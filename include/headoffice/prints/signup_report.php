<?php
if( empty($_POST['date']) || !isset($_POST['date']) || $_POST['date'] == '') {
    sessionMsg("Error", "You must enter a date.", "danger");
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
                            LEFT JOIN '.STUDENT_EDUCATIONS.' se ON s.std_id = se.id_std AND se.status = 1 AND se.is_deleted = 0',
            'where'       => array(
                                's.std_status' => 1,
                                's.is_deleted' => 0
                            ),
            'search_by'   => $searchBy,
            'group_by' => 's.std_id',
            'return_type' => 'all'
        );

$students = $dblms->getRows(STUDENTS.' s', $condition);
if (!empty($students)) {

    echo '
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

            $sr = 0;
            foreach ($students as $row) {
            $sr++;

            echo '
            <tr>
                <td>'.$sr.'</td>
                <td>'.$row['std_name'].'</td>
                <td>'.$row['adm_email'].'</td>
                <td class="text-center">'.$row['adm_phone'].'</td>
                <td>'.$row['std_address_1'].'</td>
                <td class="text-center">'.($row['std_gender'] ? get_gendertypes($row['std_gender']) : '-').'</td>
                <td '.(!empty($row['programs']) ? "" : "class='text-center'").'>';

                if (!empty($row['programs'])) {

                    $programs = explode(',', $row['programs']);

                    echo '<ol style="margin:0;padding-left:18px;">';

                    foreach ($programs as $p) {
                        echo '<li>'.trim($p).'</li>';
                    }

                    echo '</ol>';

                } else {
                    echo '---';
                }

                echo '
                </td>
            </tr>';
        }

        echo '
        </tbody>
    </table>';

} else {

    echo '
    <table id="printResult">
        <tr>
            <td>
                <h4 class="text-center text-danger">No Record Found</h4>
            </td>
        </tr>
    </table>';

}
?>