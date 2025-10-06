<?php
if ($data_arr['method_name'] == "get_dashboard") {

    // 🔹 Initialize the main data structure for the response
    $dashboard_data = [
        'student_data'               => [],
        'total_enrollments'          => '0',
        'total_transactions'         => '0',
        'total_wishlist'             => '0'
    ];

    if (isset($data_arr['std_id']) && $data_arr['std_id'] != '') {
        $std_id = $data_arr['std_id'];

        // --- Fetch Student Data ---
        $condition = [
            'select'      => 's.std_name, a.adm_email, a.adm_photo',
            'join'        => 'INNER JOIN ' . ADMINS . ' a ON a.adm_id = s.std_loginid AND a.is_deleted = 0 AND a.adm_status = 1',
            'where'       => ['s.is_deleted' => '0', 's.std_status' => '1', 's.std_id' => cleanvars($std_id)],
            'return_type' => 'single'
        ];
        $student = $dblms->getRows(STUDENTS . ' s', $condition);
        $dashboard_data['student_data'] = [
            'std_name'  => $student ? $student['std_name'] : '',
            'std_email' => $student ? $student['adm_email'] : '',
            'std_photo' => ($student && !empty($student['adm_photo'])) ? SITE_URL . 'uploads/images/admin/' . $student['adm_photo'] : SITE_URL . 'uploads/images/default_user.png'
        ];

        // --- Fetch Counts ---
        // === TOTAL ENROLLMENTS ===
        $con_total_enrollments = array(
            'where'       => array(
                'is_deleted' => 0,
                'secs_status'=> 1,
                'id_std'     => cleanvars($std_id)
            ),
            'return_type' => 'count'
        );
        $total_enrollments = $dblms->getRows(ENROLLED_COURSES, $con_total_enrollments);
        $dashboard_data['total_enrollments'] = !empty($total_enrollments) ? ''.$total_enrollments.'' : '0';


        // === TOTAL TRANSACTIONS ===
        $con_total_transactions = array(
            'where'       => array(
                'is_deleted'   => 0,
                'trans_status' => 1,
                'id_std'       => cleanvars($std_id)
            ),
            'return_type' => 'count'
        );
        $total_transactions = $dblms->getRows(TRANSACTION, $con_total_transactions);
        $dashboard_data['total_transactions'] = !empty($total_transactions) ? ''.$total_transactions.'' : 0;


        // === TOTAL WISHLIST ===
        $con_total_wishlist = array(
            'where'       => array(
                'id_std' => cleanvars($std_id)
            ),
            'return_type' => 'count'
        );
        $total_wishlist = $dblms->getRows(WISHLIST, $con_total_wishlist);
        $dashboard_data['total_wishlist'] = !empty($total_wishlist) ? ''.$total_wishlist.'' : 0;


        // --- Fetch Enrolled Courses Details ---
        $condition = [
            'select'        => 'c.curs_name, c.curs_photo, mt.mas_name, 
                                mt.mas_photo, p.prg_photo, ap.program, 
                                ec.secs_id, ec.id_curs, ec.id_mas, ec.id_ad_prg, ec.id_type,
                                COUNT(DISTINCT cl.lesson_id) AS lesson_count,
                                COUNT(DISTINCT ca.id) AS assignment_count,
                                COUNT(DISTINCT cq.quiz_id) AS quiz_count,
                                COUNT(DISTINCT lt.track_id) AS track_count',
            'join'          => 'INNER JOIN ' . CHALLANS . ' ch ON FIND_IN_SET(ec.secs_id,ch.id_enroll) AND ch.is_deleted = 0
                                LEFT JOIN ' . COURSES . ' c ON c.curs_id = ec.id_curs AND c.curs_status = 1 AND c.is_deleted = 0
                                LEFT JOIN ' . MASTER_TRACK . ' mt ON mt.mas_id = ec.id_mas AND mt.mas_status = 1 AND mt.is_deleted = 0
                                LEFT JOIN ' . ADMISSION_PROGRAMS . ' ap ON ap.id = ec.id_ad_prg
                                LEFT JOIN ' . PROGRAMS . ' p ON p.prg_id = ap.id_prg
                                LEFT JOIN ' . COURSES_LESSONS . ' AS cl ON FIND_IN_SET(cl.id_curs, ec.id_curs) AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                LEFT JOIN ' . COURSES_ASSIGNMENTS . ' AS ca ON FIND_IN_SET(ca.id_curs, ec.id_curs) AND ca.status = 1 AND ca.is_deleted = 0
                                LEFT JOIN ' . QUIZ . ' AS cq ON FIND_IN_SET(cq.id_curs, ec.id_curs) AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
                                LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON FIND_IN_SET(lt.id_curs, ec.id_curs) AND lt.id_std = ' . cleanvars($std_id) . ' AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg',
            'where'         => array(
                                        'ec.is_deleted'     => '0', 
                                        'ec.secs_status'    => '1', 
                                        'ec.id_std'         => cleanvars($std_id)
                                ),
            'group_by'      => 'ec.secs_id',
            'order_by'      => 'ec.secs_id DESC',
            'return_type'   => 'all'
        ];
        $ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES . ' ec', $condition);

        if ($ENROLLED_COURSES) {
            foreach ($ENROLLED_COURSES as $row) {
                $name = '';
                $id = '';
                $photo = SITE_URL . 'uploads/images/default_curs.jpg';
                $redirection_details = [];

                // 🔹 Determine item name and photo
                if ($row['id_type'] == 1) {
                    $id = $row['id_ad_prg'];
                    $name = $row['program'];
                    if (!empty($row['prg_photo'])) {
                        $photo = SITE_URL . 'uploads/images/programs/' . $row['prg_photo'];
                    }
                } elseif ($row['id_type'] == 2) {
                    $id = $row['id_mas'];
                    $name = $row['mas_name'];
                    if (!empty($row['mas_photo'])) {
                        $photo = SITE_URL . 'uploads/images/admissions/master_track/' . $row['mas_photo'];
                    }
                } elseif ($row['id_type'] == 3 || $row['id_type'] == 4) {
                    $id = $row['id_curs'];
                    $name = $row['curs_name'];
                    if (!empty($row['curs_photo'])) {
                        $photo = SITE_URL . 'uploads/images/courses/' . $row['curs_photo'];
                    }
                }

                if ($name != '') {
                    $Total = $row['lesson_count'] + $row['assignment_count'] + $row['quiz_count'];
                    $Obtain = $row['track_count'];
                    $percent = ($Total > 0) ? (($Obtain / $Total) * 100) : 0;
                    $percent = ($percent >= 100) ? 100 : intval($percent);

                   $condition = array(
                                        'select'       => 'id_type',
                                        'where'        => array(
                                            'is_deleted'   => 0,
                                            'admoff_degree'=> $row['id_curs']
                                        ),
                                        'return_type'  => 'single'
                            );
                    $learn_type = $dblms->getRows(ADMISSION_OFFERING, $condition);

                    $is_cert_available = ($percent == 100 && isset($learn_type['id_type']) && $learn_type['id_type'] != 3)?'1':'0';

                    // 🔹 Determine Redirection Method
                    $type = $row['id_type'];
                    if ($type == '3' || $type == '4') {
                        // For Courses, provide redirection details
                        $next_items = [];
                        // Find next incomplete lesson
                        $con_lesson = array(
                                        'select'        => 'cl.lesson_id as id, cl.id_week', 
                                        'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $row['id_curs'] . ' AND lt.id_lecture = cl.lesson_id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                        'where'         => array(
                                                            'cl.lesson_status'  => 1, 
                                                            'cl.is_deleted'     => 0, 
                                                            'cl.id_curs'        => $row['id_curs']
                                            ), 
                                        'search_by'     => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)', 
                                        'order_by'      => 'cl.id_week ASC, cl.lesson_id ASC LIMIT 1', 
                                        'return_type'   => 'single'
                                    );
                        if ($next = $dblms->getRows(COURSES_LESSONS . ' cl', $con_lesson)) {
                            $next['type'] = 'lesson';
                            $next_items[] = $next;
                        }
                        // Find next incomplete assignment
                        $con_asgn   = array(
                                        'select'        => 'ca.id as id, ca.id_week', 
                                        'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $row['id_curs'] . ' AND lt.id_assignment = ca.id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                        'where'         => array(
                                                                'ca.status'     => 1, 
                                                                'ca.is_deleted' => 0, 
                                                                'ca.id_curs'    => $row['id_curs']
                                            ), 
                                        'search_by'     => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)', 
                                        'order_by'      => 'ca.id_week ASC, ca.id ASC LIMIT 1', 
                                        'return_type'   => 'single'
                                );
                        if ($next = $dblms->getRows(COURSES_ASSIGNMENTS . ' ca', $con_asgn)) {
                            $next['type'] = 'assignments';
                            $next_items[] = $next;
                        }
                        // Find next incomplete quiz
                        $con_quiz = array(
                                        'select'        => 'q.quiz_id as id, q.id_week', 
                                        'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $row['id_curs'] . ' AND lt.id_quiz = q.quiz_id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                        'where'         => array(
                                                                'q.quiz_status' => 1, 
                                                                'q.is_deleted'  => 0, 'q.is_publish' => 1, 
                                                                'q.id_curs'     => $row['id_curs']
                                            ), 
                                        'search_by'     => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)', 
                                        'order_by'      => 'q.id_week ASC, q.quiz_id ASC LIMIT 1', 
                                        'return_type'   => 'single'
                                );
                        if ($next = $dblms->getRows(QUIZ . ' q', $con_quiz)) {
                            $next['type'] = 'quiz';
                            $next_items[] = $next;
                        }

                        if (!empty($next_items)) {
                            // Course in progress: find the earliest next item
                            usort($next_items, fn($a, $b) => $a['id_week'] <=> $b['id_week']);
                            $redirection_details = [
                                'type' => $next_items[0]['type'], 
                                'id' => $next_items[0]['id'],
                                'id_enroll' => $row['secs_id']
                            ];
                        } else {
                            // Course completed: find the latest (last) item
                            $last_items = [];
                            // === LAST LESSON ===
                            $con_lesson = array(
                                                'select'       => 'lesson_id as id, id_week',
                                                'where'        => array(
                                                    'id_curs'       => $row['id_curs'],
                                                    'lesson_status' => 1,
                                                    'is_deleted'    => 0
                                                ),
                                                'order_by'     => 'id_week DESC, lesson_id DESC LIMIT 1',
                                                'return_type'  => 'single'
                            );
                            if ($last = $dblms->getRows(COURSES_LESSONS, $con_lesson)) {
                                $last['type'] = 'lesson';
                                $last_items[] = $last;
                            }


                            // === LAST ASSIGNMENT ===
                            $con_assignment = array(
                                'select'       => 'id, id_week',
                                'where'        => array(
                                    'id_curs'    => $row['id_curs'],
                                    'status'     => 1,
                                    'is_deleted' => 0
                                ),
                                'order_by'     => 'id_week DESC, id DESC LIMIT 1',
                                'return_type'  => 'single'
                            );
                            if ($last = $dblms->getRows(COURSES_ASSIGNMENTS, $con_assignment)) {
                                $last['type'] = 'assignments';
                                $last_items[] = $last;
                            }


                            // === LAST QUIZ ===
                            $con_quiz = array(
                                'select'       => 'quiz_id as id, id_week',
                                'where'        => array(
                                    'id_curs'      => $row['id_curs'],
                                    'quiz_status'  => 1,
                                    'is_deleted'   => 0,
                                    'is_publish'   => 1
                                ),
                                'order_by'     => 'id_week DESC, quiz_id DESC LIMIT 1',
                                'return_type'  => 'single'
                            );
                            if ($last = $dblms->getRows(QUIZ, $con_quiz)) {
                                $last['type'] = 'quiz';
                                $last_items[] = $last;
                            }


                            if (!empty($last_items) ) {
                                usort($last_items, fn($a, $b) => $b['id_week'] <=> $a['id_week']);
                                $redirection_details = [
                                    'type'      => $last_items[0]['type'], 
                                    'id'        => $last_items[0]['id'],
                                    'id_enroll' => $row['secs_id']
                                ];
                            } else {
                                $redirection_details = [
                                    'type'      => 'lesson',
                                    'id'        => '0',
                                    'id_enroll' => $row['secs_id']
                                ]; // Course has no content
                            }
                        }
                    }
                    else{
                        $redirection_details = [
                            'type' => '',
                            'id' => '',
                            'id_enroll' => $row['secs_id']
                        ];
                    }
                    

                    $dashboard_data['my_learnings'][] = [
                        'item_id'               => $id,
                        'item_name'             => $name,
                        'item_type_id'          => ''.$row['id_type'].'',
                        'item_type_name'        => get_offering_type($row['id_type']),
                        'photo'                 => $photo,
                        'progress'              => ''.$percent.'%',
                        'certificate_available' => $is_cert_available,
                        'redirection'           => $redirection_details
                    ];
                }
            }
        }

        $rowjson['success'] = 1;
        $rowjson['MSG'] = 'Dashboard data fetched successfully.';
    } else {
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Student ID is required.';
    }
    $rowjson['dashboard_data'] = $dashboard_data;
}
