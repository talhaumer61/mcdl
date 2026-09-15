<?php
$search_by = ' AND id_org = '.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].'';
// IF SUB MEMBERS EXISTS
if($_SESSION['userlogininfo']['LOGINTYPE'] == 4 || $_SESSION['userlogininfo']['LOGINTYPE'] == 1){
    // Step 1: Get Sub-Members
    $condition  = [ 
        'select' => 'GROUP_CONCAT(o.org_id) as sub_members',
        'join'   => 'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid AND a.adm_status = 1 AND a.is_deleted = 0',
        'where'  => [
            'a.adm_logintype'   => 8,
            'o.org_status'      => 1,
            'o.is_deleted'      => 0,
            'o.parent_org'      => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
        ],
        'return_type'  => 'single',
    ]; 
    $SUB_MEMBERS = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition, $sql);

    $subMemberIds = $SUB_MEMBERS['sub_members'] ?? '';
    $ambassadorId = cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']);
    $ambassadorProfit = floatval($_SESSION['userlogininfo']['LOGINORGANIZATIONPROFITPERCENTAGE']);

    // Step 2: Calculate Ambassador's Own Referrals Earning
    $condition = [
        'select' => 'SUM(c.paid_amount * '.($ambassadorProfit / 100).') AS own_earning',
        'join'   => 'INNER JOIN '.CHALLANS.' AS c ON c.id_std = s.std_id',
        'where'  => [
            's.id_org'     => $ambassadorId,
            's.is_deleted' => 0,
            'c.status'     => 1,
        ],
        'return_type' => 'single',
    ];
    $ownEarning = $dblms->getRows(STUDENTS.' AS s', $condition, $sql);
    $ownEarningAmount = floatval($ownEarning['own_earning'] ?? 0);

    // Step 3: Loop through each Sub-Member to calculate earnings
    $subMembersTotalEarning = 0;
    $commissionFromSubMembers = 0;

    if (!empty($subMemberIds)) {
        $subMemberIdsArray = explode(',', $subMemberIds);

        foreach ($subMemberIdsArray as $subMemberId) {
            $subMemberId = cleanvars($subMemberId);

            // Fetch sub-member profit percentage
            $condition = [
                'select' => 'org_profit_percentage',
                'where'  => ['org_id' => $subMemberId],
                'return_type' => 'single',
            ];
            $subMemberData = $dblms->getRows(SKILL_AMBASSADOR, $condition, $sql);
            $subProfit = floatval($subMemberData['org_profit_percentage'] ?? 0);

            // Get total paid challans for students referred by this sub-member
            $condition = [
                'select' => 'SUM(c.paid_amount) AS total_paid',
                'join'   => 'INNER JOIN '.CHALLANS.' AS c ON c.id_std = s.std_id',
                'where'  => [
                    's.id_org'     => $subMemberId,
                    's.is_deleted' => 0,
                    'c.status'     => 1,
                ],
                'return_type' => 'single',
            ];
            $earningData = $dblms->getRows(STUDENTS.' AS s', $condition, $sql);
            $totalPaid = floatval($earningData['total_paid'] ?? 0);

            // Sub-member earning
            $subEarning = $totalPaid * ($subProfit / 100);
            $subMembersTotalEarning += $subEarning;

            // Ambassador gets the difference
            $diffPercentage = $ambassadorProfit - $subProfit;
            if ($diffPercentage > 0) {
                $commissionFromSubMembers += $totalPaid * ($diffPercentage / 100);
            }
        }
    }

    // Step 4: Final Calculation
    $totalAmbassadorEarning = $ownEarningAmount + $commissionFromSubMembers;


}

// USER COUNT
$condition  =   [
                    'select'        =>  'std_id',
                    'where'         =>  [
                                            'is_deleted'    => 0,
                                        ],
                    'search_by'     =>  ''.$search_by.'',
                    'return_type'   =>  'count',
                ];
$STUDENTS = $dblms->getRows(STUDENTS,$condition, $sql);

// ENROLLMENTS COUNT
$condition  = [
                'select'        =>  'secs_id',
                'where'         =>  [
                                        'secs_status'   => 1,
                                        'is_deleted'    => 0,
                                    ],
                'search_by'     =>  ''.$search_by.'',
                'return_type'   =>  'count',
];
$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES,$condition);