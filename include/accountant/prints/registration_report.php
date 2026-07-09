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


$searchBy = " AND s.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

$condition = array(
    'select'       => 's.std_id, s.std_name, s.std_gender, s.std_address_1, s.date_added as signup_date,
                       a.adm_email, a.adm_phone, 
                       c.curs_name as course_name',
    'join'         => 'INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                       INNER JOIN '.ENROLLED_COURSES.' e ON s.std_id = e.id_std
                       INNER JOIN '.COURSES.' c ON e.id_curs = c.curs_id',
    'where'        => array(
                        's.is_deleted' => 0
                    ),
    'search_by'    => $searchBy,
    'order_by'     => 's.std_id ASC', 
    'return_type'  => 'all'
);

$rows = $dblms->getRows(STUDENTS.' s', $condition);
$student_counts = [];
if($rows){
    foreach($rows as $r){
        $student_counts[$r['std_id']] = ($student_counts[$r['std_id']] ?? 0) + 1;
    }
}
echo'

    <!-- TABLE -->
    <table id="printResult">
        ';
        if (!empty($rows)) {
        echo '
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Contact No</th>
                <th>Course/Training</th>
                <th>Gender</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>';
            $sr = 0;
            $displayed_students = []; 
            $totalEnrollments = 0;

                foreach ($rows as $row) {
                    $totalEnrollments++;
                    $std_id = $row['std_id'];
                    $is_first_row = !in_array($std_id, $displayed_students);
                    
                    echo '<tr>';
                    
                    if ($is_first_row) {
                        $sr++;
                        $rowspan = $student_counts[$std_id];
                        echo '<td rowspan="'.$rowspan.'" class="text-center">'.$sr.'</td>';
                        echo '<td rowspan="'.$rowspan.'" class="signup-info">'.$row['std_name'].'</td>';
                        echo '<td rowspan="'.$rowspan.'">'.$row['adm_email'].'</td>';
                        echo '<td rowspan="'.$rowspan.'">'.$row['adm_phone'].'</td>';
                        
                        $displayed_students[] = $std_id;
                    }
                    echo '<td>'.$row['course_name'].'</td>';

                    if ($is_first_row) {
                        echo '<td rowspan="'.$rowspan.'" class="text-center">'.($row['std_gender'] ? get_gendertypes($row['std_gender']) : '-').'</td>';
                        echo '<td rowspan="'.$rowspan.'">'.$row['std_address_1'].'</td>';
                    }

                    echo '</tr>';
                }
                echo '
                <tr style="background-color: #eee; font-weight: bold;">
                    <td colspan="2" class="text-end">TOTAL STUDENTS:</td>
                    <td class="text-center">'.$sr.'</td>
                    <td class="text-end">TOTAL ENROLLMENTS:</td>
                    <td colspan="3" class="text-left">'.$totalEnrollments.' Courses</td>
                </tr>';
            } else {
                echo '
                <tr>
                    <th class="text-center text-danger">
                        No record found
                    </th>
                </tr>';
            }
            echo'
        </tbody>
    </table>


';
?>