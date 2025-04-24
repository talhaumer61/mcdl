<?php
$search_by = ' AND id_org = '.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].'';
// IF SUB MEMBERS EXISTS
if($_SESSION['userlogininfo']['LOGINTYPE'] == 4 || $_SESSION['userlogininfo']['LOGINTYPE'] == 1){
    $condition  =   [ 
                        'select'        =>  'GROUP_CONCAT(o.org_id) as sub_members',
                        'join'          =>  'INNER JOIN '.ADMINS.' AS a ON a.adm_id = o.id_loginid AND a.adm_status = 1 AND a.is_deleted = 0',
                        'where' 	    =>  [
                                                'a.adm_logintype'   => 8,
                                                'o.org_status'      => 1,
                                                'o.is_deleted'      => 0,
                                                'o.parent_org'      => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
                                            ],
                        'return_type'  =>  'single',
    ]; 
    $SUB_MEMBERS = $dblms->getRows(SKILL_AMBASSADOR.' AS o', $condition, $sql);
    if($SUB_MEMBERS['sub_members'] != ''){
       $search_by = ' AND (id_org = '.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].' OR id_org IN ('.$SUB_MEMBERS['sub_members'].'))';
    }
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