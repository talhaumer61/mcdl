<?php
if ($data_arr['method_name'] == "my_ongoing_learnings") {

    $ongoing_learnings = [
        'degrees'  => [],
        'master_tracks'    => [],
        'courses'          => [],
        'e_trainings'      => []
    ];

    if (isset($data_arr['std_id']) && $data_arr['std_id'] != '') {
        $std_id = cleanvars($data_arr['std_id']);

        // --- Fetch Enrolled Courses ---
        $condition = array(
            'select'        => 'c.curs_name, c.curs_photo, mt.mas_name, mt.mas_photo, 
                                p.prg_photo, ap.program, ec.secs_id, ec.id_curs, 
                                ec.id_mas, ec.id_ad_prg, ec.id_type,
                                COUNT(DISTINCT cl.lesson_id) AS lesson_count,
                                COUNT(DISTINCT ca.id) AS assignment_count,
                                COUNT(DISTINCT cq.quiz_id) AS quiz_count,
                                COUNT(DISTINCT lt.track_id) AS track_count',
            'join'          => 'LEFT JOIN ' . CHALLANS . ' ch ON FIND_IN_SET(ec.secs_id,ch.id_enroll) AND ch.is_deleted = 0
                                LEFT JOIN ' . COURSES . ' c ON c.curs_id = ec.id_curs AND c.curs_status = 1 AND c.is_deleted = 0
                                LEFT JOIN ' . MASTER_TRACK . ' mt ON mt.mas_id = ec.id_mas AND mt.mas_status = 1 AND mt.is_deleted = 0
                                LEFT JOIN ' . ADMISSION_PROGRAMS . ' ap ON ap.id = ec.id_ad_prg
                                LEFT JOIN ' . PROGRAMS . ' p ON p.prg_id = ap.id_prg
                                LEFT JOIN ' . COURSES_LESSONS . ' AS cl ON FIND_IN_SET(cl.id_curs, ec.id_curs) AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                LEFT JOIN ' . COURSES_ASSIGNMENTS . ' AS ca ON FIND_IN_SET(ca.id_curs, ec.id_curs) AND ca.status = 1 AND ca.is_deleted = 0
                                LEFT JOIN ' . QUIZ . ' AS cq ON FIND_IN_SET(cq.id_curs, ec.id_curs) AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
                                LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON FIND_IN_SET(lt.id_curs, ec.id_curs) 
                                    AND lt.id_std = ' . $std_id . ' 
                                    AND lt.is_completed = 2 
                                    AND lt.is_deleted = 0 
                                    AND lt.id_mas = ec.id_mas 
                                    AND lt.id_ad_prg = ec.id_ad_prg',
            'where'         => array(
                                    'ec.is_deleted'  => '0',
                                    'ec.secs_status' => '1',
                                    'ec.id_std'      => $std_id
            ),
            'group_by'      => 'ec.secs_id',
            'order_by'      => 'ec.secs_id DESC',
            'return_type'   => 'all'
            );

        $ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES . ' ec', $condition);

        if ($ENROLLED_COURSES) {
            foreach ($ENROLLED_COURSES as $row) {
                $name = '';
                $id = '';
                $photo = SITE_URL . 'uploads/images/default_curs.jpg';

                // Determine item name and image
                if ($row['id_type'] == 1) { // Degree
                    $id = $row['id_ad_prg'];
                    $name = $row['program'];
                    if (!empty($row['prg_photo'])) {
                        $photo = SITE_URL . 'uploads/images/programs/' . $row['prg_photo'];
                    }
                } elseif ($row['id_type'] == 2) { // Master Track
                    $id = $row['id_mas'];
                    $name = $row['mas_name'];
                    if (!empty($row['mas_photo'])) {
                        $photo = SITE_URL . 'uploads/images/admissions/master_track/' . $row['mas_photo'];
                    }
                } elseif ($row['id_type'] == 3 || $row['id_type'] == 4) { // Course or eTraining
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

                    // Skip completed (100%) items
                    if ($percent >= 100) continue;

                    // 🔹 Redirection Logic 
                    $redirection_details = [];
                    $type = $row['id_type'];

                    if ($type == '3' || $type == '4') { // Courses / eTrainings
                        $next_items = [];

                        // Next lesson
                        $con_lesson = array(
                            'select'      => 'cl.lesson_id as id, cl.id_week',
                            'join'        => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt 
                                              ON (lt.id_curs = ' . $row['id_curs'] . ' 
                                              AND lt.id_lecture = cl.lesson_id 
                                              AND lt.id_std = ' . $std_id . ')',
                            'where'       => array(
                                                'cl.lesson_status' => 1,
                                                'cl.is_deleted'    => 0,
                                                'cl.id_curs'       => $row['id_curs']
                            ),
                            'search_by'   => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)',
                            'order_by'    => 'cl.id_week ASC, cl.lesson_id ASC LIMIT 1',
                            'return_type' => 'single'
                        );
                        if ($next = $dblms->getRows(COURSES_LESSONS . ' cl', $con_lesson)) {
                            $next['type'] = 'lesson';
                            $next_items[] = $next;
                        }

                        // Next assignment
                        $con_asgn = array(
                            'select'      => 'ca.id as id, ca.id_week',
                            'join'        => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt 
                                              ON (lt.id_curs = ' . $row['id_curs'] . ' 
                                              AND lt.id_assignment = ca.id 
                                              AND lt.id_std = ' . $std_id . ')',
                            'where'       => array(
                                'ca.status'     => 1,
                                'ca.is_deleted' => 0,
                                'ca.id_curs'    => $row['id_curs']
                            ),
                            'search_by'   => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)',
                            'order_by'    => 'ca.id_week ASC, ca.id ASC LIMIT 1',
                            'return_type' => 'single'
                        );
                        if ($next = $dblms->getRows(COURSES_ASSIGNMENTS . ' ca', $con_asgn)) {
                            $next['type'] = 'assignments';
                            $next_items[] = $next;
                        }

                        // Next quiz
                        $con_quiz = array(
                            'select'      => 'q.quiz_id as id, q.id_week',
                            'join'        => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt 
                                              ON (lt.id_curs = ' . $row['id_curs'] . ' 
                                              AND lt.id_quiz = q.quiz_id 
                                              AND lt.id_std = ' . $std_id . ')',
                            'where'       => array(
                                'q.quiz_status' => 1,
                                'q.is_deleted'  => 0,
                                'q.is_publish'  => 1,
                                'q.id_curs'     => $row['id_curs']
                            ),
                            'search_by'   => 'AND (lt.is_completed IS NULL OR lt.is_completed != 2)',
                            'order_by'    => 'q.id_week ASC, q.quiz_id ASC LIMIT 1',
                            'return_type' => 'single'
                        );
                        if ($next = $dblms->getRows(QUIZ . ' q', $con_quiz)) {
                            $next['type'] = 'quiz';
                            $next_items[] = $next;
                        }

                        if (!empty($next_items)) {
                            usort($next_items, fn($a, $b) => $a['id_week'] <=> $b['id_week']);
                            $redirection_details = [
                                'id'            => $next_items[0]['id'],
                                'type'          => $next_items[0]['type'],
                                'id_enroll'     => $row['secs_id']
                            ];
                        }
                    }

                    // --- Group item by type ---
                    $item_data = [
                        'item_id'               => $id,
                        'item_name'             => $name,
                        'item_type_id'          => ''.$row['id_type'].'',
                        'item_type_name'        => get_offering_type($row['id_type']),
                        'photo'                 => $photo,
                        'progress'              => ''.$percent.'',
                        'redirection'           => $redirection_details
                    ];

                    switch ($row['id_type']) {
                        case 1: 
                            $ongoing_learnings['degrees'][] = $item_data; 
                            break;
                        case 2: 
                            $ongoing_learnings['master_tracks'][]   = $item_data; 
                            break;
                        case 3: 
                            $ongoing_learnings['courses'][]         = $item_data; 
                            break;
                        case 4: 
                            $ongoing_learnings['e_trainings'][]     = $item_data; 
                            break;
                    }
                }
            }
        }

        $rowjson['success'] = 1;
        $rowjson['MSG'] = 'Ongoing learnings fetched successfully.';
    } else {
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Student ID is required.';
    }

    $rowjson['ongoing_learnings'] = $ongoing_learnings;
}
