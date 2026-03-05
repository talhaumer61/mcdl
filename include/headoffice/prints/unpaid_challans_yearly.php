<?php
$year = $_POST['year'] ?? '';
$searchBy = " AND cc.status = '2'";

if(!empty($year)){
    $searchBy .= " AND YEAR(ec.date_added) = '$year'";
}

$condition = array(
                    'select'    => 'YEAR(cd.date_added) AS yr, ec.id_type, SUM(cd.amount) AS total_unpaid',
                    'join'      => 'INNER JOIN '.CHALLAN_DETAIL.' cd ON cd.id_enroll = ec.secs_id AND cd.is_deleted = 0
                                    INNER JOIN '.CHALLANS.' cc ON cd.id_challan = cc.challan_id AND cc.is_deleted = 0',
                    'where'     =>  array(
                                        'ec.is_deleted' => 0,
                                    ),

                    'search_by'   => $searchBy,
                    'group_by'    => 'yr, ec.id_type',
                    'return_type' => 'all'
                );

$data = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

$enrollType = array_column($enroll_type, 'name', 'id'); // id => name
$types = array_reverse(array_keys($enrollType));

$pivot = [];
$years = [];

if(!empty($data)){
    foreach($data as $row){
        $yr   = $row['yr'];
        $type = $row['id_type'];

        $years[$yr] = $yr;
        $pivot[$yr][$type] = $row['total_unpaid'];
    }
}

echo '
<table id="printResult">
    <thead>
        <tr class="text-center">
            <th>Year</th>';
            foreach($types as $t){
                echo '<th>'.$enrollType[$t].'</th>';
            }
            echo '
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
';

if(empty($years)){
    echo '
        <tr>
            <td colspan="'.(count($types) + 2).'" style="text-align:center;font-weight:bold;">
                No data found!
            </td>
        </tr>
    ';
} else {

    $colTotals = array_fill_keys($types, 0);
    $grandTotal = 0;

    foreach($years as $yr){

        echo '
        <tr class="text-center">
            <td class="bold">'.$yr.'</td>';

        $rowTotal = 0;

        foreach($types as $t){
            $val = $pivot[$yr][$t] ?? 0;
            $rowTotal += $val;
            $colTotals[$t] += $val;
            $grandTotal += $val;

            echo '<td>'.number_format($val).'</td>';
        }

        echo '
            <td class="bold">'.number_format($rowTotal).'</td>
        </tr>';
    }

    // TOTAL ROW
    echo '
        <tr class="text-center" style="background:#f5f5f5;font-weight:bold;">
            <td>TOTAL</td>';
            foreach($types as $t){
                echo '<td>'.number_format($colTotals[$t]).'</td>';
            }
            echo '
            <td>'.number_format($grandTotal).'</td>
        </tr>
    ';
}

echo '
    </tbody>
</table>
';
?>
