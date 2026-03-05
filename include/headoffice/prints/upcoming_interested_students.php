<?php
if(!isset($_POST['date']) || !isset($_POST['enroll_type']) ) {
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

$type = $_POST['enroll_type'] ?? '';
$dateRange = $_POST['date'] ?? '';

$searchBy = "";

if(!empty($dateRange)){
    $parts = explode('to', $dateRange);

    $fromDate = trim($parts[0]);
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $fromDate;

    $searchBy .= " AND ic.date_posted BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
}

if(!empty($type)){
    $searchBy .= " AND ic.type = '$type'";
}

$condition = array ( 
                         'select'       =>  'ic.*, c.curs_name, c.curs_photo'
                        ,'join'         =>  'INNER JOIN '.COURSES.' c ON c.curs_id = ic.id_interest'
                        ,'where' 	    =>  array( 
                                                     'ic.status'    => 1
                                                )
                        ,'search_by'    =>  $searchBy
                        ,'return_type'  =>  'all' 
                    ); 
$STUDENT_INTERESTED_COURSES = $dblms->getRows(STUDENT_INTERESTED_COURSES.' ic', $condition,$sql);

if (!empty($STUDENT_INTERESTED_COURSES)) {

    echo '
    <table id="printResult">
        <thead>
            <tr class="text-center">
                <th width="40">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>City</th>
                <th>Course</th>
            </tr>
        </thead>
        <tbody>';

    $sr = 0;
    $totalStudents = count($STUDENT_INTERESTED_COURSES);

    foreach ($STUDENT_INTERESTED_COURSES as $row) {

        $sr++;

        echo '<tr class="text-center">';
        echo '<td>'.$sr.'</td>';

        echo '
            <td>'.$row['name'].'</td>
            <td>'.(!empty($row['email']) ? $row['email'] : "-").'</td>
            <td>'.(!empty($row['city']) ? $row['city'] : "-").'</td>
            <td>'.(!empty($row['curs_name']) ? $row['curs_name'] : "-").'</td>
        </tr>';
    }
    echo '
        <tr >
            <th></th>
            <th class="text-center">TOTAL</th>
            <th colspan="3">'.$totalStudents.'</th>
        </tr>';

    echo '
        </tbody>
    </table>';
}
else {

    echo '
    <table>
        <tr>
            <td class="text-center text-danger fw-bold">
                No Record Found!
            </td>
        </tr>
    </table>';
}
?>