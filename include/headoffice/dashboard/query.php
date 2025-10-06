<?php
// COURSES - TRAINING COUNT
$condition  = [
                     'select'       =>  'SUM(CASE WHEN ct.id_type = 1 THEN 1 ELSE 0 END) AS course,
                                         SUM(CASE WHEN ct.id_type = 2 THEN 1 ELSE 0 END) AS etraning'
                    ,'join'         =>  'INNER JOIN '.COURSES_CATEGORIES.' AS ct ON c.id_cat = ct.cat_id'
                    ,'where'        =>  [
                                                'c.curs_status'   => 1,
                                                'c.is_deleted'    => 0,
                                        ]
                    ,'return_type'  =>  'single'
];
$COURSES = $dblms->getRows(COURSES.' AS c',$condition, $sql);
// MASTER_TRACK COUNT
$condition  = [
                     'select'       =>  'COUNT(mas_id) AS mas_count'
                    ,'where'        =>  [
                                                'mas_status'    => 1,
                                                'is_deleted'    => 0,
                                        ]
                    ,'return_type'  =>  'single'
];
$MASTER_TRACK = $dblms->getRows(MASTER_TRACK,$condition, $sql);
// PROGRAMS COUNT
$condition  = [
                     'select'       =>  'COUNT(prg_id) AS prg_count'
                    ,'where'        =>  [
                                                'prg_status'    => 1,
                                                'is_deleted'    => 0,
                                        ]
                    ,'return_type'  =>  'single'
];
$PROGRAMS = $dblms->getRows(PROGRAMS,$condition);
// LEARNERS COUNT
$condition  = [
    'select'        => 'SUM(CASE WHEN ec.id_type = 1 THEN 1 ELSE 0 END) AS prg,
                        SUM(CASE WHEN ec.id_type = 2 THEN 1 ELSE 0 END) AS mas,
                        SUM(CASE WHEN ec.id_type = 3 THEN 1 ELSE 0 END) AS curs,
                        SUM(CASE WHEN ec.id_type = 4 THEN 1 ELSE 0 END) AS training,
                        COUNT(ec.secs_id) AS total,
                        SUM(CASE WHEN s.id_org != 0 THEN 1 ELSE 0 END) AS ref_enrollments',
    'join'          => 'LEFT JOIN '.STUDENTS.' s ON s.std_id = ec.id_std AND s.is_deleted = 0',
    'where'         =>  [
                            'ec.secs_status' => 1,
                            'ec.is_deleted' => 0,
                        ],
    'return_type'   => 'single'
];
$LEARNERS = $dblms->getRows(ENROLLED_COURSES . ' ec', $condition);

// ENROLLED_COURSES COUNT
$conditions = array ( 
     'select'       =>	'c.curs_id, c.curs_name, ec.id_type, COUNT(ec.secs_id) as std_count'
    ,'join'         =>	'INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs AND c.curs_status = 1 AND c.is_deleted = 0'
    ,'where' 		=>	array( 
                                'ec.is_deleted'    => '0'
                            )
    ,'search_by'    =>  ' AND ec.id_type IN (3,4)'
    ,'group_by'     =>	'ec.id_curs'
    ,'limit'        =>	' 15'
    ,'return_type'	=>	'all'
); 
$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions, $sql);

// Start Enrollment AND Completion Metrics
$condition  = [
    'select'       =>  'DISTINCT cert_id'
   ,'where'        =>  [
                           'is_deleted'    => 0,
                       ]
   ,'return_type'  =>  'count'
];
$Curs_Completion = $dblms->getRows(GENERATED_CERTIFICATES,$condition);

//
$condition  = [
     'select'       =>  'DISTINCT COUNT(c.curs_id) AS curs_count, c.curs_id, c.curs_name, SUM(ao.admoff_amount) AS curs_total_revenue
                         ,SUM(CASE WHEN cf.status = 1 THEN cf.without_discount_amount ELSE 0 END ) AS curs_total_earnings'
    ,'join'         =>  'INNER JOIN '.COURSES.' AS c ON c.curs_id = ec.id_curs
                         INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ec.id_curs = ao.admoff_degree
                         INNER JOIN '.CHALLANS.' AS cf ON FIND_IN_SET(ec.secs_id, cf.id_enroll)'
    ,'where'        =>  [
                           'ec.secs_status'   => 1,
                           'ec.is_deleted'    => 0,
                       ]
   ,'group_by'      =>  ' c.curs_id '
   ,'return_type'   =>  'all'
];
$ENROLLED_COURSES_REVENUE = $dblms->getRows(ENROLLED_COURSES.' AS ec ',$condition);
$curs_total_revenue = 0;
$curs_total_earnings = 0;
foreach ($ENROLLED_COURSES_REVENUE as $key => $value) {
    $curs_total_revenue += $value['curs_total_revenue'];
    $curs_total_earnings += $value['curs_total_earnings'];
}
$Curs_Conversation = intval($curs_total_revenue * 100 / $curs_total_earnings);
// End Enrollment AND Completion Metrics
$condition  = [
     'select'       =>  'SUM( CASE WHEN s.std_gender = 1 THEN 1 ELSE 0 END ) AS male_count
                        ,SUM( CASE WHEN s.std_gender = 2 THEN 1 ELSE 0 END ) AS female_count
                        ,SUM( CASE WHEN s.std_gender = 3 THEN 1 ELSE 0 END ) AS other_count
                        ,SUM( CASE WHEN s.id_org != 0 THEN 1 ELSE 0 END ) AS ref_signup
                        ,COUNT(s.std_id) AS total_signup'
    ,'join'         =>  'INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid AND a.adm_status = 1 AND a.is_deleted = 0'
    ,'where'        =>  [
                          's.std_status'     => 1,
                          's.is_deleted'     => 0,
                        ]
    ,'return_type'   =>  'single'
];
$STUDENTS_GENDER = $dblms->getRows(STUDENTS.' s',$condition);