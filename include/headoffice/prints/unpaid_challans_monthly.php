<?php
if (!isset($_POST['year_month']) || empty($_POST['year_month'])) {
    $view = $_POST['view']  ? LMS_VIEW : '';
    header("Location: reports.php?view=" . $view);
    exit();
}
$year_month = $_POST['year_month'] ?? ''; 
$searchBy   = " AND cc.status = 2";
$year = date('Y');
$month = date('m');

if(!empty($year_month)){
    $year  = date('Y', strtotime($year_month));
    $month = date('m', strtotime($year_month));

    $searchBy .= " AND YEAR(cc.date_added) = '$year'";
    $searchBy .= " AND MONTH(cc.date_added) = '$month'";
}


$condition = array(
    'select' => 'YEAR(cd.date_added) AS yr, MONTH(cd.date_added) AS mn, ec.id_type, SUM(cd.amount) AS total_unpaid',

    'join'  => 'INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0',
    'where' => array(
                    'ec.is_deleted' => 0,
                ),

    'search_by'   => $searchBy,
    'group_by'    => 'yr, mn, ec.id_type',
    'return_type' => 'all'
);

$data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);
$enrollType = array_column($enroll_type, 'name', 'id');
$types = array_reverse(array_keys($enrollType));

$months = [
    1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",
    7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"
];

$pivot = [];
$periods = [];

foreach($data as $row){
    $key = $row['yr'].'-'.$row['mn'];
    $periods[$key] = $key;
    $pivot[$key][$row['id_type']] = $row['total_unpaid'];
}

krsort($periods); // latest first

echo '<table id="printResult">
        <thead>
            <tr class="text-center">
                <th>'.$year.'</th>';

                foreach($types as $t){
                    echo '<th>'.$enrollType[$t].'</th>';
                }
            echo '<th>TOTAL</th>
            </tr>
        </thead>
        <tbody>';

            if(empty($periods)){
                echo '<tr><td colspan="'.(count($types)+2).'" class="text-center fw-bold">No data found!</td></tr>';
            } 
            else {

            $colTotals = array_fill_keys($types,0);
            $grandTotal = 0;

            foreach($periods as $key){
                list($yr,$mn) = explode('-', $key);
                $label = $months[(int)$mn];

                echo "<tr class='text-center'>
                <td class='fw-bold'>$label</td>";

                $rowTotal = 0;

                foreach($types as $t){
                    $val = $pivot[$key][$t] ?? 0;
                    $rowTotal += $val;
                    $colTotals[$t] += $val;
                    $grandTotal += $val;

                    echo '<td>'.number_format($val).'</td>';
                }

                echo '<td class="fw-bold">'.number_format($rowTotal).'</td></tr>';
            }

            echo '<tr class="fw-bold bg-light text-center">
                <td>TOTAL</td>';

            foreach($types as $t){
                echo '<td>'.number_format($colTotals[$t]).'</td>';
            }

            echo '
            <td>'.number_format($grandTotal).'</td></tr>';
}

        echo '
        </tbody>
</table>';
?>
