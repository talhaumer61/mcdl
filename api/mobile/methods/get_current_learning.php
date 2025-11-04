<?php
if ($data_arr['method_name'] == "get_current_learning") {

    if (!empty($data_arr['std_id']) && !empty($data_arr['enroll_id']) && !empty($data_arr['content_id']) && !empty($data_arr['redirection_type']) ) {

        $std_id     = cleanvars($data_arr['std_id']);
        $enroll_id  = cleanvars($data_arr['enroll_id']);
        $content_id  = cleanvars($data_arr['content_id']);
        $item_id  = cleanvars($data_arr['item_id']);
        $id_mas     = !empty($data_arr['id_mas']) ? cleanvars($data_arr['id_mas']) : 0;
        $id_ad_prg  = !empty($data_arr['id_ad_prg']) ? cleanvars($data_arr['id_ad_prg']) : 0;
        $redirection_type = cleanvars($data_arr['redirection_type']);
        $next_redirection = [];
        $assignment_detail = [];

        switch ($redirection_type) {
            case 'lesson':
                
                // --- LESSON DETAILS (from your lesson section) ---
                $con = array(
                            'select' => 'l.lesson_id, l.id_week, l.id_lecture, l.lesson_topic, l.lesson_content, 
                                        l.lesson_video_code, l.lesson_detail, l.lesson_reading_detail, 
                                        lt.is_completed, lt.my_note_pad, lt.std_review',
                            'join' => 'LEFT JOIN ' . LECTURE_TRACKING . ' AS lt 
                                        ON (lt.id_curs = ' . $item_id . ' 
                                        AND lt.id_lecture = l.lesson_id 
                                        AND lt.id_std = ' . $std_id . ' 
                                        AND lt.id_mas = ' . $id_mas . ' 
                                        AND lt.id_ad_prg = ' . $id_ad_prg . ')',
                            'where' => array(
                                                'l.lesson_status'   => 1,
                                                'l.is_deleted'      => 0,
                                                'l.lesson_id'       => $content_id
                                            ),
                            'return_type' => 'single'
                            );
                $lesson_data = $dblms->getRows(COURSES_LESSONS . ' AS l', $con);

                if ($lesson_data) {

                    // --- RELATED DOWNLOADS (COURSES_DOWNLOADS) ---
                    $cond = array (
                                    'select' => 'id, file_name, url, file',
                                    'where'  => array(
                                                        'id_curs'    => $item_id,
                                                        'id_lesson'  => $content_id,
                                                        'is_deleted' => 0,
                                                        'status'     => 1
                                                    ),
                                    'order_by' => 'id ASC',
                                    'return_type' => 'all'
                                );
                    $downloads = $dblms->getRows(COURSES_DOWNLOADS, $cond);

                    $downloads_list = [];
                    if (!empty($downloads)) {
                        foreach ($downloads as $res) {
                            $downloads_list[] = [
                                                    'id'        => $res['id'],
                                                    'file_name' => $res['file_name'],
                                                    'file'      => $res['res_file'] ? SITE_URL . 'uploads/files/course_resources/' . $res['res_file'] : '',
                                                    'url'       => $res['url']
                                                ];
                        }
                    }

                    // // --- ANNOUNCEMENTS (COURSES_ANNOUNCEMENTS) ---
                    $cond_announce = array(
                                            'select'    => 'announcement_topic, announcement_detail, date_added',
                                            'where'     => array(
                                                                'id_curs' => $item_id,
                                                                'is_deleted' => 0,
                                                                'announcement_status' => 1
                                                            ),
                                            'order_by'  => 'announcement_id DESC',
                                            'return_type'   => 'all'
                                        );
                    $announcements = $dblms->getRows(COURSES_ANNOUNCEMENTS, $cond_announce);

                    $announcements_list = [];
                    if (!empty($announcements)) {
                        foreach ($announcements as $a) {
                            $announcements_list[] = [
                                                        'title'         => $a['announcement_topic'],
                                                        'detail'        => html_entity_decode(html_entity_decode($a['announcement_detail'])),
                                                        'date_added'    => $a['date_added']
                                                    ];
                        }
                    }

                    // // --- DISCUSSIONS (COURSES_DISCUSSION) ---
                    $con = array(
                                    'select'        =>  'cd.discussion_id, cd.discussion_subject, cd.discussion_detail, cd.id_lecture, cds.dst_detail, a.adm_photo, a.adm_fullname, e.emply_gender'
                                    ,'join'         =>  'INNER JOIN '.ADMINS.' a on a.adm_id = cd.id_added
                                                            LEFT JOIN '.EMPLOYEES.' e on e.emply_id = cd.id_teacher
                                                            LEFT JOIN '.COURSES_DISCUSSIONSTUDENTS.' cds on cds.id_discussion = cd.discussion_id AND cds.id_std = '.cleanvars($std_id).''
                                    ,'where'        =>  array(
                                                                'cd.id_curs'               =>  cleanvars($item_id)
                                                                ,'cd.discussion_status'     =>  1
                                                                ,'cd.is_deleted'            =>  0
                                                            )
                                    ,'search_by'    =>  ' AND FIND_IN_SET('.$lesson_data['id_lecture'].',cd.id_lecture)'
                                    ,'return_type'  =>  'all'
                                );
                    $COURSES_DISCUSSION = $dblms->getRows(COURSES_DISCUSSION.' cd',$con, $sql);

                    $discussion_list = [];
                    if (!empty($COURSES_DISCUSSION)) {
                        foreach ($COURSES_DISCUSSION as $d) {
                            $discussion_list[] = [
                                'discussion_id'         => $d['discussion_id'],
                                'discussion_subject'    => $d['discussion_subject'],
                                'discussion_detail'     => html_entity_decode(html_entity_decode($d['discussion_detail'])),
                                'submitted'             => !empty($d['dst_detail']) ? '1' : '0',
                            ];
                        }
                    }

                    //  Next Redirection
                    $condition = array(
                                        'select'       => 'id_type',
                                        'where'        => array(
                                            'is_deleted'   => 0,
                                            'admoff_degree'=> $item_id
                                        ),
                                        'return_type'  => 'single'
                            );
                    $learn_type = $dblms->getRows(ADMISSION_OFFERING, $condition);

                    $is_cert_available = ($percent == 100 && isset($learn_type['id_type']) && $learn_type['id_type'] != 3)?'1':'0';

                    // 🔹 Determine Redirection Method
                    if(isset($item_id)){
                        $arrays = array();
                        // OPEN LESSON
                        $con    = array ( 
                                            'select'   =>	'cl.lesson_id as id, cl.lesson_topic as title, cl.id_week, lt.is_completed'
                                            ,'join'     =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON ( lt.id_curs = '.$item_id.' AND lt.id_lecture = cl.lesson_id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')' 
                                            ,'where' 	=>	array( 
                                                                        'cl.lesson_status' => 1
                                                                    ,'cl.is_deleted'    => 0
                                                                    ,'cl.id_curs' 	    => cleanvars($item_id) 
                                                                ) 
                                            ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                            ,'order_by'     =>	'cl.id_week, cl.id_lecture, cl.lesson_id ASC LIMIT 1'
                                            ,'return_type'  =>	'single'
                                        ); 
                        $NEXT_COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS.' cl', $con);
                        if($NEXT_COURSES_LESSONS){
                            array_push($NEXT_COURSES_LESSONS, 'lesson');
                            array_push($arrays, $NEXT_COURSES_LESSONS);
                        }

                        // OPEN ASSIGNMENT
                        $con    = array ( 
                                            'select'   =>	'ca.id as id, ca.id_week, ca.caption as title, lt.is_completed'
                                            ,'join'     =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.cleanvars($item_id).' AND lt.id_assignment = ca.id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')' 
                                            ,'where' 	=>	array( 
                                                                        'ca.status'        => 1
                                                                    ,'ca.is_deleted'    => 0
                                                                    ,'ca.id_curs' 	    => cleanvars($item_id) 
                                                                ) 
                                            ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                            ,'order_by'     =>	'ca.id_week ASC LIMIT 1'
                                            ,'return_type'  =>	'single'
                                        ); 
                        $NEXT_COURSES_ASSIGNMENTS = $dblms->getRows(COURSES_ASSIGNMENTS.' ca', $con);
                        if($NEXT_COURSES_ASSIGNMENTS){
                            array_push($NEXT_COURSES_ASSIGNMENTS, 'assignments');
                            array_push($arrays, $NEXT_COURSES_ASSIGNMENTS);
                        }

                        // OPEN QUIZ
                        $con    = array ( 
                                            'select'   =>	'q.quiz_id as id, q.id_week, q.quiz_title as title, lt.is_completed'
                                            ,'join'     =>	'LEFT JOIN '.LECTURE_TRACKING.' lt ON (lt.id_quiz = q.quiz_id AND lt.id_curs = '.$item_id.' AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')'
                                            ,'where' 	=>	array( 
                                                                        'q.quiz_status'    => 1
                                                                    ,'q.is_deleted'     => 0
                                                                    ,'q.is_publish'     => 1
                                                                    ,'q.id_curs'        => cleanvars($item_id) 
                                                            ) 
                                            ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                            ,'order_by'     =>	'q.id_week ASC, q.quiz_id ASC'
                                            ,'return_type'  =>	'single'
                                        ); 
                        $NEXT_QUIZ = $dblms->getRows(QUIZ.' q', $con, $sql);
                        if($NEXT_QUIZ){
                            array_push($NEXT_QUIZ, 'quiz');
                            array_push($arrays, $NEXT_QUIZ);
                        }
                        $minIdWeek = PHP_INT_MAX;
                        $minIdWeekIndex = -1;

                        // Loop through each array
                        foreach ($arrays as $index => $array) {
                            // Check if the current array has the lowest id_week
                            if (isset($array['id_week']) && $array['id_week'] < $minIdWeek) {
                                // Update the minimum id_week and its index
                                $minIdWeek = $array['id_week'];
                                $minIdWeekIndex = $index;
                            }
                        }

                        // Output the array with the lowest id_week AND modify redirection
                        if ($minIdWeekIndex != -1) {
                            $type = end($arrays[$minIdWeekIndex]);
                            $name = $arrays[$minIdWeekIndex]['title'];
                            $next_id = $arrays[$minIdWeekIndex]['id'];
                            $next_redirection = [
                                'id' => $next_id,
                                'type' => $type,
                            ];
                        } else { 
                            $next_redirection = [
                                'id' => '0',
                                'type' => 'lesson',
                            ];
                        }
                    }
                    else{
                        $next_redirection = [
                            'id' => '',
                            'type' => '',
                        ];
                    }

                    // // --- FINAL RESPONSE DATA ---
                    $rowjson['success'] = 1;
                    $rowjson['MSG']     = 'Lesson content fetched successfully.';

                    // Lesson Info
                    $rowjson['current_lesson']  = [
                        'lesson_id'         => $lesson_data['lesson_id'],
                        'lesson_topic'      => $lesson_data['lesson_topic'],
                        'lesson_video'      => $lesson_data['lesson_video_code'],
                        'lesson_week'       => $lesson_data['id_week'],
                        'content_type_id'   => !empty($lesson_data['lesson_content']) ? $lesson_data['lesson_content'] : '',
                        'content_type_name' => !empty($lesson_data['lesson_content']) ? get_topic_content($lesson_data['lesson_content']) : '',
                        'lesson_reading'    => html_entity_decode(html_entity_decode($lesson_data['lesson_reading_detail'])),
                        'is_completed'      => $lesson_data['is_completed'] ?? '0',
                    ];

                    // Tabs Info
                    $rowjson['tabs'] = [
                        'description'       => html_entity_decode(html_entity_decode($lesson_data['lesson_detail'])),
                        'resources'         => $downloads_list,
                        'announcements'     => $announcements_list,
                        'discussions'       => $discussion_list,
                        'note_book'         => html_entity_decode(html_entity_decode($lesson_data['my_note_pad'])),
                        'review'            => html_entity_decode(html_entity_decode($lesson_data['std_review']))
                    ];
                    // Next Redirection Info
                    $rowjson['next_redirection'] = $next_redirection;
                } else {
                    $rowjson['success'] = 0;
                    $rowjson['MSG']     = 'Lesson not found.';
                }
                break;
            case 'assignments':
                //COURSES ASSIGNMENTS
                $con = array(
                                'select'       => 'ca.id,ca.caption,ca.detail,ca.fileattach,ca.date_start,ca.date_end,e.emply_name,cas.student_file,ca.id_week,ca.id_curs,lt.is_completed,cas.student_reply, cas.student_file, cas.submit_date'
                                ,'join'         => 'LEFT JOIN '.EMPLOYEES.' e ON e.emply_id = ca.id_teacher 
                                                    LEFT JOIN '.COURSES_ASSIGNMENTS_STUDENTS.' cas ON ca.id = cas.id_assignment
                                                    LEFT JOIN '.LECTURE_TRACKING.' lt ON (lt.id_curs = '.$item_id.' AND lt.id_assignment = ca.id AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')'
                                ,'where'        => array(
                                                            'ca.id_curs'       => cleanvars($item_id)
                                                            ,'ca.status'        => 1
                                                            ,'ca.is_deleted'    => 0
                                                            ,'ca.id'            => cleanvars($content_id)
                                                        )
                                ,'return_type'  => 'single'
                );
                $COURSES_ASSIGNMENTS = $dblms->getRows(COURSES_ASSIGNMENTS.' ca',$con, $sql);
                if ($COURSES_ASSIGNMENTS['id']) {
                    $extension  = pathinfo($COURSES_ASSIGNMENTS['fileattach'],PATHINFO_EXTENSION);
                    $path       = !empty($COURSES_ASSIGNMENTS['fileattach']) ? SITE_URL.'uploads/files/course_assignments/'.$COURSES_ASSIGNMENTS['fileattach'] : '' ;

                    $assignment_detail['id'] = $COURSES_ASSIGNMENTS['id'];
                    $assignment_detail['caption'] = $COURSES_ASSIGNMENTS['caption'] ? $COURSES_ASSIGNMENTS['caption'] : '';
                    $assignment_detail['detail'] = $COURSES_ASSIGNMENTS['detail'] ? html_entity_decode(html_entity_decode($COURSES_ASSIGNMENTS['detail'])) : '';
                    $assignment_detail['extension'] = $extension ;
                    $assignment_detail['filepath'] = $path ;
                    if($COURSES_ASSIGNMENTS['is_completed'] == 2 ){
                        $assignment_detail['student_submission'] = [
                            'file_path' => $COURSES_ASSIGNMENTS['student_file'] ? SITE_URL.'uploads/files/student_assignments/'.$COURSES_ASSIGNMENTS['student_file']: '',
                            'student_reply' => html_entity_decode(html_entity_decode($COURSES_ASSIGNMENTS['student_reply'])),
                            'submit_date' => $COURSES_ASSIGNMENTS['submit_date']
                        ];
                    }
                    else{
                        $assignment_detail['student_submission'] = [
                            'file_path' => '',
                            'student_reply' => '',
                            'submit_date' => ''
                        ];
                    }
                    $assignment_detail['is_completed'] = $COURSES_ASSIGNMENTS['is_completed'] ?? '0';

                    $rowjson['success']     = 1;
                    $rowjson['MSG']         = 'Assignment Fetched Successfully.';
                    $rowjson['assignment_detail'] = $assignment_detail ;
                }
                else{
                    $rowjson['success']     = 0;
                    $rowjson['MSG']         = 'No assignment found.';
                }
                break;
            
            default:
                # code...
                break;
        }

    } else {
        $rowjson['success']     = 0;
        $rowjson['MSG']         = 'Student ID, Enrollment ID, and Lesson ID are required.';
    }
    
}
?>
