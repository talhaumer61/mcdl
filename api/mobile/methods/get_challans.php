<?php

if ($data_arr['method_name'] == "get_challans") {
    $challans = [];
    if(isset($data_arr['std_id']) && $data_arr['std_id'] != ''){
        $std_id = $data_arr['std_id'];
        $conditions = array ( 
                         'select' 		=>	'challan_id, challan_no, issue_date, paid_date, total_amount, paid_amount, currency_code, status'
                        ,'where' 		=>	array( 
                                                     'is_deleted'    => '0'
                                                    ,'id_std'        => cleanvars($std_id) 
                                                ) 
                        ,'order_by'     =>	'challan_id DESC'
                        ,'return_type'	=>	'all'
                        ); 
        $CHALLANS = $dblms->getRows(CHALLANS, $conditions);
        if ($CHALLANS) {
            foreach ($CHALLANS as $row) {
                foreach ($payments as $pay) {
                    if ($pay['id'] == $row['status']) {
                        $row['status'] = $pay['name'];
                        break;
                    }
                } 
                $challans[] = array(
                    'challan_id'        => $row['challan_id'],
                    'challan_no'        => $row['challan_no'],
                    'issue_date'        => $row['issue_date'],
                    'paid_date'         => $row['paid_date'] == '0000-00-00' ? '' : $row['paid_date'],
                    'total_amount'      => $row['total_amount'],
                    'paid_amount'       => $row['paid_amount'],
                    'currency_code'     => $row['currency_code'],
                    'status'            => $row['status']
                );
            }
            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Challans fetched successfully.';
        } else {
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'No challans found.';
        }
    }
    else{
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Student ID is required.';
    }
    $rowjson['challans_list'] = $challans;
}
