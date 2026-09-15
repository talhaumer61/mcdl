<?php
if ($data_arr['method_name'] == "get_learning_content") {

    if (!empty($data_arr['std_id']) && !empty($data_arr['enroll_id'])) {

        $std_id    = cleanvars($data_arr['std_id']);
        $std_name    = cleanvars($data_arr['std_name']);
        $user_email    = cleanvars($data_arr['user_email']);
        $course_id = cleanvars($data_arr['item_id']);
        $enroll_id = cleanvars($data_arr['enroll_id']);
        $is_cerificate_available = '0';
        $course_detail = [];
        $enrollment_detail = [];
        $next_redirection = [];

        // --- MAIN COURSE QUERY
        $condition = array (
            'select' => 'c.curs_id, c.curs_name, c.curs_code, c.curs_photo, c.curs_href, c.curs_wise,
                         ec.id_curs, ec.id_type, ec.id_mas, ec.id_ad_prg, ec.id_std, ec.secs_id,
                         COUNT(DISTINCT cl.lesson_id) AS lesson_count,
                         COUNT(DISTINCT ca.id) AS assignment_count,
                         COUNT(DISTINCT cq.quiz_id) AS quiz_count,
                         COUNT(DISTINCT lt.track_id) AS track_count',
            'join' => 'INNER JOIN ' . COURSES . ' AS c 
                        ON FIND_IN_SET(c.curs_id, ec.id_curs)
                        AND c.curs_id = "'.cleanvars($course_id).'" 
                        AND c.curs_status = 1 
                        AND c.is_deleted = 0
                       LEFT JOIN ' . COURSES_LESSONS . ' AS cl 
                        ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                       LEFT JOIN ' . COURSES_ASSIGNMENTS . ' AS ca 
                        ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                       LEFT JOIN ' . QUIZ . ' AS cq 
                        ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 
                        AND cq.is_deleted = 0 AND cq.is_publish = 1
                       LEFT JOIN ' . LECTURE_TRACKING . ' AS lt 
                        ON lt.id_curs = c.curs_id 
                        AND lt.id_std = ' . $std_id . ' 
                        AND lt.is_completed = 2 
                        AND lt.is_deleted = 0 
                        AND lt.id_mas = ec.id_mas 
                        AND lt.id_ad_prg = ec.id_ad_prg',
            'where' => array(
                                'ec.secs_status' => 1,
                                'ec.is_deleted'  => 0,
                                'ec.secs_id'     => $enroll_id
                            ),
            'return_type' => 'single'
        );

        $COURSE = $dblms->getRows(ENROLLED_COURSES . ' AS ec', $condition);

        if ($COURSE) {
            $Total = $COURSE['lesson_count'] + $COURSE['assignment_count'] + $COURSE['quiz_count'];
            $Obtain = $COURSE['track_count'];
            $percent = ($Total > 0) ? (($Obtain / $Total) * 100) : 0;
            $percent = ($percent >= 100) ? 100 : intval($percent);

            // Next Redirection Details
            $condition = array(
                                'select'       => 'id_type',
                                'where'        => array(
                                    'is_deleted'   => 0,
                                    'admoff_degree'=> $COURSE['id_curs']
                                ),
                                'return_type'  => 'single'
                    );
            $learn_type = $dblms->getRows(ADMISSION_OFFERING, $condition);

            $is_cert_available = ($percent == 100 && isset($learn_type['id_type']) && $learn_type['id_type'] != 3)?'1':'0';

            // 🔹 Determine Redirection Method
            $type = $COURSE['id_type'];
            if ($type == '3' || $type == '4') {
                // For Courses, provide redirection details
                $next_items = [];
                // Find next incomplete lesson
                $con_lesson = array(
                                'select'        => 'cl.lesson_id as id, cl.id_week', 
                                'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $COURSE['id_curs'] . ' AND lt.id_lecture = cl.lesson_id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                'where'         => array(
                                                    'cl.lesson_status'  => 1, 
                                                    'cl.is_deleted'     => 0, 
                                                    'cl.id_curs'        => $COURSE['id_curs']
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
                                'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $COURSE['id_curs'] . ' AND lt.id_assignment = ca.id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                'where'         => array(
                                                        'ca.status'     => 1, 
                                                        'ca.is_deleted' => 0, 
                                                        'ca.id_curs'    => $COURSE['id_curs']
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
                                'join'          => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt ON (lt.id_curs = ' . $COURSE['id_curs'] . ' AND lt.id_quiz = q.quiz_id AND lt.id_std = ' . cleanvars($std_id) . ')', 
                                'where'         => array(
                                                        'q.quiz_status' => 1, 
                                                        'q.is_deleted'  => 0, 'q.is_publish' => 1, 
                                                        'q.id_curs'     => $COURSE['id_curs']
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
                    $next_redirection = [
                        'id' => $next_items[0]['id'],
                        'type' => $next_items[0]['type'] 
                    ];
                } else {
                    // Course completed: find the latest (last) item
                    $last_items = [];
                    // === LAST LESSON ===
                    $con_lesson = array(
                                        'select'       => 'lesson_id as id, id_week',
                                        'where'        => array(
                                            'id_curs'       => $COURSE['id_curs'],
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
                            'id_curs'    => $COURSE['id_curs'],
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
                            'id_curs'      => $COURSE['id_curs'],
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
                        $next_redirection = [
                            'id'        => $last_items[0]['id'],
                            'type'      => $last_items[0]['type']
                        ];
                    } else {
                        $next_redirection = [
                            'id'        => '0',
                            'type'      => 'lesson'
                        ]; // Course has no content
                    }
                }
            }
            else{
                $next_redirection = [
                    'id' => '',
                    'type' => '',
                ];
            }
            $course_detail = [
                'course_name'      => $COURSE['curs_name'],
                'progess'          => "$percent"   
            ];

            $enrollment_detail = [
                'id_mas'           => $COURSE['id_mas'],
                'id_ad_prg'        => $COURSE['id_ad_prg'],
            ];
            // ALREADY GENERATED CERTIFICATE
            $conditions = array ( 
                                    'select'       =>	'gc.cert_id, gc.id_enroll, gc.id_std, gc.std_name, gc.cert_type, gc.cert_name, gc.curs_hours, gc.date_generated'
                                    ,'where' 		=>	array( 
                                                                'gc.is_deleted'     => '0'
                                                                ,'gc.id_std' 	    => cleanvars($std_id) 
                                                                ,'gc.id_enroll'     => cleanvars($enroll_id) 
                                                            )
                                    ,'return_type'	=>	'count'
                                );
            $generateCert = $dblms->getRows(GENERATED_CERTIFICATES.' gc', $conditions);
            if(!$generateCert){
                $Total      = $COURSE['lesson_count'] + $COURSE['assignment_count'] + $COURSE['quiz_count'];
                $Obtain     = $COURSE['track_count'];
                $percent    = (($Obtain / $Total) * 100);
                $percent    = ($percent >= '100' ? '100' : $percent);

                if($percent == '100'){
                    $is_cerificate_available = '1';
                    // NAME, TYPE
                    if ($COURSE['id_type'] == 3){
                        $cert_type  = '3';
                        $type_name  = 'Certificate Course';
                        $cert_name  = $COURSE['curs_name'];
                    } else if ($COURSE['id_type'] == 4){
                        $cert_type  = '4';
                        $type_name  = 'e-Training';
                        $cert_name  = $COURSE['curs_name'];
                    }

                    // IF MAIL SENT ALREADY
                    $conditions = array ( 
                                            'select'        =>	'rm.datetime_mailed'
                                            ,'where' 		=>	array( 
                                                                        'rm.id_std'         => cleanvars($std_id)
                                                                        ,'rm.id_enroll'     => cleanvars($enroll_id)
                                                                        ,'rm.type'          => 1
                                                                    )
                                            ,'order_by'     =>	'rm.datetime_mailed DESC'
                                            ,'return_type'	=>	'single'
                                        );
                    $REMINDER_MAILS_LOGS = $dblms->getRows(REMINDER_MAILS_LOGS.' rm', $conditions);
                    if(!$REMINDER_MAILS_LOGS){
                        // INSERT IN GENERATED CERTIFICATES
                        $values = array(
                                            'type'              => 1
                                            ,'id_enroll'        => cleanvars($enroll_id)
                                            ,'id_std'			=> cleanvars($std_id)
                                            ,'std_name'			=> cleanvars($std_name)
                                            ,'std_email'        => cleanvars($user_email)
                                            ,'cert_type'        => cleanvars($cert_type)
                                            ,'cert_name'        => cleanvars($cert_name)
                                            ,'datetime_mailed'  => date('Y-m-d G:i:s')
                                        );     
                        $sqlInsert = $dblms->insert(REMINDER_MAILS_LOGS, $values);
                        if($sqlInsert){
                            get_SendMail([
                                'sender'        => SMTP_EMAIL,
                                'senderName'    => SITE_NAME,
                                'receiver'      => $user_email,
                                'receiverName'  => $std_name,
                                'subject'       => "Congratulations! You have Completed (".$cert_name." - ".$type_name.") on ".TITLE_HEADER."",
                                'body'          => '
                                                    <div style="background-color: #f9f9f9; padding: 20px; font-family: Arial, sans-serif; color: #333;">
                                                        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                                            <h2 style="color: #0056b3; font-weight: bold; margin-top: 20px;">Dear <strong>'.ucwords(strtolower($std_name)).'</strong>,</h2>
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                <span style="color: #28a745; font-weight: bold;">Congratulations</span> on successfully completing <strong>'.$cert_name.' - '.$type_name.'</strong>! We are immensely proud of your hard work and dedication.
                                                            </p>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                Your completion certificate is now available for download.
                                                                <br>
                                                                To access it, kindly share your feedback on your LMS, and you can get your shareable certificate.
                                                            </p>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                We encourage you to share your achievement with your network and continue your learning journey with us.
                                                                <br>
                                                                <strong>Explore more courses</strong> to enhance your skills and grow further.
                                                            </p>                                        
                                                            <p style="font-size: 16px; font-style: italic;">
                                                                Best wishes for your future endeavors!
                                                            </p>                                        
                                                            <p style="color: red; font-weight: bold;">
                                                                Note: Make sure you log-in to your account.
                                                            </p>
                                                            <div style="text-align: center; margin: 20px 0;">
                                                                <a href="'.SITE_URL.'certificate-print/'.$enroll_id.'" style="display: inline-block; padding: 15px 25px; background-color: #17a2b8; color: #fff; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                                                    <i class="fa fa-graduation-cap" style="margin-right: 8px;"></i> Get Your Certificate
                                                                </a>
                                                            </div>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                <b>Warm regards,</b>
                                                                <br>
                                                                Support Team
                                                                <br>
                                                                '.SMTP_EMAIL.'
                                                                <br>
                                                                '.SITE_NAME.' <strong>('.TITLE_HEADER.')</strong>
                                                                <br>
                                                                <b>Minhaj University Lahore</b>
                                                            </p>
                                                        </div>
                                                    </div>',
                                'tokken'    => SMTP_TOKEN,
                            ], 'send-mail');
                        }
                    } else {
                        $last_mail_date = date('Y-m-d', strtotime($REMINDER_MAILS_LOGS['datetime_mailed']. ' + 15 days'));
                        if(date('Y-m-d') > $last_mail_date){
                            get_SendMail([
                                'sender'        => SMTP_EMAIL,
                                'senderName'    => SITE_NAME,
                                'receiver'      => $user_email,
                                'receiverName'  => $std_name,
                                'subject'       => "Congratulations! You have Completed (".$cert_name." - ".$type_name.") on ".TITLE_HEADER."",
                                'body'          => '
                                                    <div style="background-color: #f9f9f9; padding: 20px; font-family: Arial, sans-serif; color: #333;">
                                                        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                                            <h2 style="color: #0056b3; font-weight: bold; margin-top: 20px;">Dear <strong>'.ucwords(strtolower($std_name)).'</strong>,</h2>
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                <span style="color: #28a745; font-weight: bold;">Congratulations</span> on successfully completing <strong>'.$cert_name.' - '.$type_name.'</strong>! We are immensely proud of your hard work and dedication.
                                                            </p>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                Your completion certificate is now available for download.
                                                                <br>
                                                                To access it, kindly share your feedback on your LMS, and you can get your shareable certificate.
                                                            </p>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                We encourage you to share your achievement with your network and continue your learning journey with us.
                                                                <br>
                                                                <strong>Explore more courses</strong> to enhance your skills and grow further.
                                                            </p>                                        
                                                            <p style="font-size: 16px; font-style: italic;">
                                                                Best wishes for your future endeavors!
                                                            </p>                                        
                                                            <p style="color: red; font-weight: bold;">
                                                                Note: Make sure you log-in to your account.
                                                            </p>
                                                            <div style="text-align: center; margin: 20px 0;">
                                                                <a href="'.SITE_URL.'certificate-print/'.$enroll_id.'" style="display: inline-block; padding: 15px 25px; background-color: #17a2b8; color: #fff; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                                                    <i class="fa fa-graduation-cap" style="margin-right: 8px;"></i> Get Your Certificate
                                                                </a>
                                                            </div>                                        
                                                            <p style="font-size: 16px; line-height: 1.8;">
                                                                <b>Warm regards,</b>
                                                                <br>
                                                                Support Team
                                                                <br>
                                                                '.SMTP_EMAIL.'
                                                                <br>
                                                                '.SITE_NAME.' <strong>('.TITLE_HEADER.')</strong>
                                                                <br>
                                                                <b>Minhaj University Lahore</b>
                                                            </p>
                                                        </div>
                                                    </div>',
                                'tokken'    => SMTP_TOKEN,
                            ], 'send-mail');
                        }
                    }
                }
            }
            else{
                $is_cerificate_available = '1';
            }
            $condition = array ( 
                     'select'       =>	'cl.id_week, wt.caption'
                    ,'join'         =>  'LEFT JOIN '.COURSES_WEEK_TITLE.' wt ON wt.id_week = cl.id_week AND wt.id_curs = cl.id_curs AND wt.is_deleted = 0 AND wt.status = 1'
                    ,'where'        =>	array( 
                                                 'cl.id_curs'      => $course_id
                                                ,'cl.is_deleted'   => '0'  
                                            )
                    ,'group_by'     =>	'cl.id_week'
                    ,'return_type'	=>	'all'
                  );
            $weeks = $dblms->getRows(COURSES_LESSONS.' cl', $condition);
            $course_content = [];

            if (!empty($weeks)) {
                foreach ($weeks as $week) {
                    $week_id   = $week['id_week'];
                    $weekTitle = get_CourseWise($COURSE['curs_wise']).' '.$week['id_week'].''.($week['caption'] ? ' - '.$week['caption'] : '');

                    // LESSONS
                    $condition = array ( 
                        'select'        => 'cl.lesson_id, cl.lesson_topic, cl.lesson_content, lt.is_completed',
                        'join'         => 'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.$course_id.' AND lt.id_lecture = cl.lesson_id AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$COURSE['id_mas'].' AND lt.id_ad_prg = '.$COURSE['id_ad_prg'].')',
                        'where'         => array( 
                                            'cl.id_week'    => $week_id,
                                            'cl.id_curs'    => $course_id,
                                            'cl.is_deleted' => '0'  
                                        ),
                        'return_type'   => 'count'
                    );
                    $lessonCount = $dblms->getRows(COURSES_LESSONS.' cl', $condition);
                    $condition['return_type'] = 'all';
                    $COURSE_LESSONS = $dblms->getRows(COURSES_LESSONS. ' cl', $condition);

                    // ASSIGNMENTS
                    $condition = array ( 
                        'select'        => 'a.id, a.caption, lt.is_completed',
                        'join'         => 'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.$course_id.' AND lt.id_assignment = a.id AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$COURSE['id_mas'].' AND lt.id_ad_prg = '.$COURSE['id_ad_prg'].')',
                        'where'         => array( 
                                            'a.id_week'    => $week_id,
                                            'a.id_curs'    => $course_id,
                                            'a.is_deleted' => '0',
                                            'a.status'     => '1'  
                                        ),
                        'order_by'      => 'a.id ASC',
                        'return_type'   => 'count'
                    );
                    $assignCount = $dblms->getRows(COURSES_ASSIGNMENTS. ' a', $condition);
                    $condition['return_type'] = 'all';
                    $COURSE_ASSIGNMENTS = $dblms->getRows(COURSES_ASSIGNMENTS.' a', $condition);

                    // QUIZZES
                    $condition = array ( 
                        'select'        => 'q.quiz_id, q.quiz_title, lt.is_completed',
                        'join'         => 'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.$course_id.' AND lt.id_quiz = q.quiz_id AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$COURSE['id_mas'].' AND lt.id_ad_prg = '.$COURSE['id_ad_prg'].')',
                        'where'         => array( 
                                            'q.id_week'     => $week_id,
                                            'q.id_curs'     => $course_id,
                                            'q.is_deleted'  => '0',
                                            'q.quiz_status' => '1',
                                            'q.is_publish'  => '1'
                                        ),
                        'order_by'      => 'q.quiz_id ASC',
                        'return_type'   => 'count'
                    );
                    $quizCount = $dblms->getRows(QUIZ. ' q', $condition);
                    $condition['return_type'] = 'all';
                    $QUIZ = $dblms->getRows(QUIZ. ' q', $condition);

                    // Build week array
                    $lessonArr = [];
                    if (!empty($COURSE_LESSONS)) {
                        foreach ($COURSE_LESSONS as $idx => $lesson) {
                            $lessonArr[] = [
                                'topic'         => !empty($lesson['lesson_topic']) ? html_entity_decode(html_entity_decode($lesson['lesson_topic'])) : '',
                                'content_type_id'   => !empty($lesson['lesson_content']) ? $lesson['lesson_content'] : '',
                                'content_type_name'  => !empty($lesson['lesson_content']) ? get_topic_content($lesson['lesson_content']) : '',
                                'is_completed' => !empty($lesson['is_completed']) ? $lesson['is_completed'] : '0',
                                'redirection'   => [
                                                        'id '          => !empty($lesson['lesson_id']) ? $lesson['lesson_id'] : '',
                                                        'type'         => 'lesson',
                                                    ]
                            ];
                        }
                    }

                    $assignArr = [];
                    if (!empty($COURSE_ASSIGNMENTS)) {
                        foreach ($COURSE_ASSIGNMENTS as $idx => $as) {
                            $assignArr[] = [
                                'caption'       => !empty($as['caption']) ? $as['caption'] : '',
                                'is_completed'  => !empty($as['is_completed']) ? $as['is_completed'] : '0',
                                'redirection'   => [
                                                        'id '          => !empty($as['id']) ? $as['id'] : '',
                                                        'type'         => 'assignments',
                                                    ]
                            ];
                        }
                    }

                    $quizArr = [];
                    if (!empty($QUIZ)) {
                        foreach ($QUIZ as $idx => $quiz) {
                            $quizArr[] = [
                                'title'   => !empty($quiz['quiz_title']) ? $quiz['quiz_title'] : '',
                                'is_completed'  => !empty($quiz['is_completed']) ? $quiz['is_completed'] : '0',
                                'redirection'   => [
                                                        'id '          => !empty($quiz['quiz_id']) ? $quiz['quiz_id'] : '',
                                                        'type'         => 'quiz',
                                                    ]
                            ];
                        }
                    }

                    $course_content[] = [
                        'section_title'     => !empty($weekTitle)   ? $weekTitle : '',
                        'lesson_count'      => !empty($lessonCount) ? "$lessonCount" : '0',
                        'assignment_count'  => !empty($assignCount) ? "$assignCount" : '0',
                        'quiz_count'        => !empty($quizCount)   ? "$quizCount" : '0',
                        'lessons'           => $lessonArr,
                        'assignments'       => $assignArr,
                        'quizzes'           => $quizArr
                    ];

                }
            }

            $rowjson['success'] = 1;
            $rowjson['MSG']     = 'Data fetched successfully.';

        } else {
            $rowjson['success'] = 0;
            $rowjson['MSG']     = 'Invalid enrollment ID or course not found.';
        }

    } else {
        $rowjson['success'] = 0;
        $rowjson['MSG']     = 'Student ID and Enrollment ID are required.';
    }

    $rowjson['course_detail']           = $course_detail;    
    $rowjson['enrollment_detail']       = $enrollment_detail;
    $rowjson['course_content']          = $course_content;
    $rowjson['next_redirection']     = $next_redirection;
    $rowjson['is_cerificate_available'] = $is_cerificate_available;
}
?>
