<?php

$json       = file_get_contents('php://input');

// Log file
$logFile = $logDir . '/callback_' . date('Y-m-d') . '.txt';

// Get all request headers
if (function_exists('getallheaders')) {
    $headers = getallheaders();
} else {
    // Fallback for non-Apache servers
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') === 0) {
            $name = str_replace('_', '-', substr($key, 5));
            $headers[$name] = $value;
        }
    }
}

$log = [
    'Date'          => date('Y-m-d H:i:s'),
    'Method'        => $_SERVER['REQUEST_METHOD'] ?? '',
    'URI'           => $_SERVER['REQUEST_URI'] ?? '',
    'IP'            => $_SERVER['REMOTE_ADDR'] ?? '',
    'User-Agent'    => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'Headers'       => $headers,
    'Raw Request'   => $json,
];

file_put_contents(
    $logFile,
    "\n==================================================\n" .
    json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n",
    FILE_APPEND | LOCK_EX
);

if (empty($json)) {
    http_response_code(400);
    echo json_encode([
        'status' => 400,
        'description' => 'No data received.'
    ]);
    exit();
}

$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'status' => 400,
        'description' => 'Invalid JSON.'
    ]);
    exit();
}

if($data['response']['code'] == 200) {

    $logd = [
        'code'          => $data['response']['code'],
        'description'   => $data['response']['description'],
        'paymentstatus' => $data['transaction']['paymentstatus'],
        'amount'        => $data['transaction']['amount'],
        'txnid'         => $data['transaction']['paymentmsgid'],
        'orderid'       => $data['merchant']['orderid'],
        'ChallanNo'     => $data['customer']['challanno'],
    ];


    $datalog = array (
                              'status'              => 1
                            , 'txn'                 => $data['transaction']['paymentmsgid']
                            , 'paidamount'          => $data['transaction']['amount']
                            , 'paiddatetime'		=> date('Y-m-d H:i:s')
                            , 'payload_response'	=> json_encode($data)
                     );
    $qryUpdate = $dblms->Update("cms_qrchallans", $datalog, "WHERE billno = '" . $data['transaction']['billnumber'] . "' ORDER BY id DESC LIMIT 1");

    // GET CHALLAN INFO
    $conditions = array (
                                  'select' 		=>	'*'
                                , 'where' 		=>	array(
                                                                  'is_deleted'    =>  '0'
                                                                , 'challan_no'	=>	cleanvars($data['customer']['challanno'])
                                                         )
                                , 'return_type'	=>	'single'
                        );
    $row = $dblms->getRows(CHALLANS, $conditions);

    // UPDATE CHALLAN
    $values = array(
                             'status'			=> 1
                            ,'paid_amount'		=> cleanvars($data['transaction']['amount'])
                            ,'paid_date'		=> date('Y-m-d')
                            //,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                            ,'date_modify'		=> date('Y-m-d G:i:s')
                    );
    $sqllms = $dblms->Update(CHALLANS, $values , "WHERE challan_id  = '".cleanvars($row['challan_id'])."' AND challan_no = '".cleanvars($data['customer']['challanno'])."'");

    $valuesc = array(
                              'secs_status'			=> '1'
                            , 'date_modify'			=> date('Y-m-d G:i:s')
                    );
    $sqllms = $dblms->Update(ENROLLED_COURSES, $valuesc , "WHERE secs_id IN (".cleanvars($row['id_enroll']).") ");


    file_put_contents(
        $logFile,
        "\n==================================================\n" .
        json_encode($logd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n",
        FILE_APPEND | LOCK_EX
    );

}