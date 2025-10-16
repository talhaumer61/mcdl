<?php
if($data_arr['method_name'] == "get_course_details") { 
    $course     = array();
    $course_id  = intval($data_arr['course_id'] ?? 0);
    $stdID      = cleanvars($data_arr['student_id']) ?? 0;

    if ($course_id > 0) {    
        // COURSE INFO
        $condition = array ( 
                            'select'       =>	'ci.objectives, ci.outcomes, ci.outlines, c.curs_skills, c.curs_about, c.what_you_learn, c.how_it_work, f.faculty_name, d.dept_name
                                                ,ao.id_type, ao.admoff_type, ao.admoff_amount, ao.admoff_amount_in_usd
                                                ,c.curs_id, c.curs_name, c.curs_wise, c.curs_hours, c.duration, c.curs_video 
                                                ,c.id_level, c.curs_type_status
                                                ,GROUP_CONCAT(DISTINCT lg.lang_name) as languages'
                            ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' ao on ao.admoff_degree = c.curs_id AND ao.admoff_status = 1 AND ao.is_deleted = 0 AND ao.admoff_type = 3
                                                 INNER JOIN '.DEPARTMENTS.' d ON d.dept_id = c.id_dept AND d.is_deleted = 0
                                                 INNER JOIN '.LANGUAGES.' lg ON FIND_IN_SET(lg.lang_id,c.id_lang)
                                                 INNER JOIN '.FACULTIES.' f ON f.faculty_id = c.id_faculty
                                                 INNER JOIN '.COURSES_INFO.' ci ON ci.id_curs = c.curs_id'
                            ,'where' 		    =>	array( 
                                                    'c.curs_id' 	   => $course_id
                                                    ,'c.curs_status' 	 => '1'  
                                                    ,'c.is_deleted' 	 => '0'  
                                                )
                            ,'return_type'	=>	'single'
                    );
        if(isset($stdID) && $stdID > 0) {
            $condition['select'] .= ',ec2.secs_id as ifEnrolled, w.wl_id as ifWishlist';
            $condition['join'] .= ' LEFT JOIN '.ENROLLED_COURSES.' ec2 ON ec2.id_curs = c.curs_id AND ec2.id_ad_prg = 0 AND ec2.id_mas = 0 AND ec2.id_std = '.$stdID.'
                                LEFT JOIN '.WISHLIST.' w ON w.id_curs = c.curs_id AND w.id_ad_prg IS NULL AND w.id_mas IS NULL AND w.id_std = '.$stdID.'';
        }

        $COURSE = $dblms->getRows(COURSES.' c', $condition);

        if ($COURSE['curs_id']) {
            // Get all weeks of lessons
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
                    $weekTitle = get_CourseWise($COURSE['curs_wise']).' '.$week['id_week'].' '.($week['caption'] ? '- '.$week['caption'] : '');

                    // LESSONS
                    $condition = array ( 
                        'select'       => 'lesson_topic, lesson_content',
                        'where'        => array( 
                                            'id_week'    => $week_id,
                                            'id_curs'    => $course_id,
                                            'is_deleted' => '0'  
                                        ),
                        'return_type'  => 'count'
                    );
                    $lessonCount = $dblms->getRows(COURSES_LESSONS, $condition);
                    $condition['return_type'] = 'all';
                    $COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS, $condition);

                    // ASSIGNMENTS
                    $condition = array ( 
                        'select'       => 'caption',
                        'where'        => array( 
                                            'id_week'    => $week_id,
                                            'id_curs'    => $course_id,
                                            'is_deleted' => '0',
                                            'status'     => '1'  
                                        ),
                        'order_by'     => 'id ASC',
                        'return_type'  => 'count'
                    );
                    $assignCount = $dblms->getRows(COURSES_ASSIGNMENTS, $condition);
                    $condition['return_type'] = 'all';
                    $COURSES_ASSIGNMENTS = $dblms->getRows(COURSES_ASSIGNMENTS, $condition);

                    // QUIZZES
                    $condition = array ( 
                        'select'       => 'quiz_title',
                        'where'        => array( 
                                            'id_week'     => $week_id,
                                            'id_curs'     => $course_id,
                                            'is_deleted'  => '0',
                                            'quiz_status' => '1',
                                            'is_publish'  => '1'
                                        ),
                        'order_by'     => 'quiz_id ASC',
                        'return_type'  => 'count'
                    );
                    $quizCount = $dblms->getRows(QUIZ, $condition);
                    $condition['return_type'] = 'all';
                    $QUIZ = $dblms->getRows(QUIZ, $condition);

                    // Build week array
                    $lessonArr = [];
                    if (!empty($COURSES_LESSONS)) {
                        foreach ($COURSES_LESSONS as $idx => $lesson) {
                            $lessonArr[] = [
                                'topic'         => !empty($lesson['lesson_topic']) ? html_entity_decode(html_entity_decode($lesson['lesson_topic'])) : '',
                                'content_type'  => !empty($lesson['lesson_content']) ? get_topic_content($lesson['lesson_content']) : ''
                            ];
                        }
                    }

                    $assignArr = [];
                    if (!empty($COURSES_ASSIGNMENTS)) {
                        foreach ($COURSES_ASSIGNMENTS as $idx => $as) {
                            $assignArr[] = [
                                'caption'       => !empty($as['caption']) ? $as['caption'] : ''
                            ];
                        }
                    }

                    $quizArr = [];
                    if (!empty($QUIZ)) {
                        foreach ($QUIZ as $idx => $quiz) {
                            $quizArr[] = [
                                'title'   => !empty($quiz['quiz_title']) ? $quiz['quiz_title'] : ''
                            ];
                        }
                    }

                    $course_content[] = [
                        'section_title'     => !empty($weekTitle) ? $weekTitle : '',
                        'lesson_count'      => !empty($lessonCount) ? $lessonCount : '0',
                        'assignment_count'  => !empty($assignCount) ? $assignCount : '0',
                        'quiz_count'        => !empty($quizCount) ? $quizCount : '0',
                        'lessons'           => $lessonArr,
                        'assignments'       => $assignArr,
                        'quizzes'           => $quizArr
                    ];

                }
            }

            // Fetch FAQs for this course
            $condition = array ( 
                'select'       => 'question, answer',
                'where'        => array( 
                                    'id_curs'     => $course_id,   // use $course_id from GET
                                    'is_deleted'  => '0'  
                                ),
                'return_type'  => 'all'
            ); 
            $get_faq = $dblms->getRows(COURSES_FAQS, $condition);

            if (!empty($get_faq)) {
                foreach ($get_faq as $valFaq) {
                    $faqArr[] = [
                        'question'  => html_entity_decode(html_entity_decode($valFaq['question'])) ?? '',
                        'answer'    => html_entity_decode(html_entity_decode($valFaq['answer'])) ?? ''
                    ];
                }
            }
            
            // INSTRUCTORS
            $condition = array ( 
                'select'        =>  'a.adm_photo, e.emply_name, e.emply_gender, e.emply_id, e.emply_photo, d.dept_name, des.designation_name, COUNT(alt2.id_curs) as t_curs',
                'join'          =>  'INNER JOIN '.ALLOCATE_TEACHERS.' alt2 on FIND_IN_SET(e.emply_id, alt2.id_teacher)
                                     LEFT JOIN '.ADMINS.' a ON a.adm_id = e.emply_loginid
                                     LEFT JOIN '.DEPARTMENTS.' d on e.id_dept = d.dept_id
                                     LEFT JOIN '.DESIGNATIONS.' des on e.id_designation = des.designation_id',
                'where'         =>  array( 
                                            'alt2.id_curs'      => $course_id,
                                            'e.emply_status'    => '1',
                                            'e.is_deleted'      => '0'
                                        ),
                'group_by'      =>  'e.emply_id',
                'return_type'   =>  'all'
            );
            $allocated_teachers = $dblms->getRows(EMPLOYEES.' e', $condition);

            $instructors = [];
            if (!empty($allocated_teachers)) {
                foreach ($allocated_teachers as $teacher) {
                    if (!empty($teacher['adm_photo'])) {
                        $photo = SITE_URL . 'uploads/images/' . $teacher['adm_photo'];
                    } elseif (!empty($teacher['emply_photo'])) {
                        $photo = SITE_URL . 'uploads/images/employees/' . $teacher['emply_photo'];
                    } else {
                        $photo = ($teacher['emply_gender'] == 2) ? SITE_URL . 'uploads/images/default_female.jpg' : SITE_URL . 'uploads/images/default_male.jpg';
                    }

                    // Prepare cleaned instructor entry
                    $instructors[] = [
                        'instructor_id'     => $teacher['emply_id'] ?? '',
                        'instructor_name'   => $teacher['emply_name'] ?? '',
                        'instructor_gender' => $teacher['emply_gender'] ?? '',
                        'department_name'   => $teacher['dept_name'] ?? '',
                        'designation_name'  => $teacher['designation_name'] ?? '',
                        'total_courses'     => $teacher['t_curs'] ?? '',
                        'photo'             => $photo, // ✅ final photo only
                    ];
                }
            }

            // GET DISCOUNT
            $condition = array ( 
                                    'select'       =>	'd.discount_id, dd.discount, dd.discount_type'
                                    ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' AS dd ON d.discount_id = dd.id_setup AND dd.id_curs = "'.$COURSE['curs_id'].'"'
                                    ,'where'        =>	array( 
                                                                'd.discount_status' 	=> '1' 
                                                                ,'d.is_deleted' 	    => '0'
                                                            )
                                    ,'search_by'    =>  ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE'
                                    ,'return_type'	=>	'single'
                                );
            $DISCOUNT = $dblms->getRows(DISCOUNT.' AS d ', $condition);
            $discount= array();
            if($DISCOUNT){
                if ($COURSE['curs_type_status'] != '1' && !empty($DISCOUNT['discount_id'])) {
                    $discount['discount_type']  = $DISCOUNT['discount_type'];
                    $discount['discount_value'] = $DISCOUNT['discount'];
                }
            }  

            $course = array(
                // basic
                'course_id'         => $COURSE['curs_id'] ?? null,
                'course_name'       => $COURSE['curs_name'] ?? '',
                'course_hours'      => $COURSE['curs_hours'].' Hour'.($COURSE['curs_hours'] > 1 ? 's' : ''),
                'course_duration'   => $COURSE['duration'].' '.get_CourseWise($COURSE['curs_wise']).($COURSE['duration'] > 1 ? 's' : ''),
                'intro_video'       => $COURSE['curs_video'] ?? '',
                'course_level'      => get_course_level($COURSE['id_level'] ?? 0),
                'course_language'   => $COURSE['languages'] ?? '',
                'course_type'       => $COURSE['curs_type_status']?? '',
                'course_department' => html_entity_decode($COURSE['dept_name']) ?? '',
                'course_faculty'    => html_entity_decode($COURSE['faculty_name']) ?? '',
                'if_enrolled'       => $COURSE['ifEnrolled'] ?? '',
                'if_wishlist'       => $COURSE['ifWishlist'] ?? '',

                // discount
                'discount'          => $discount ?? [],

                // offering
                'admission_offering'=> array(
                    'id_type'            => $COURSE['id_type'] ?? null,
                    'admoff_type'        => $COURSE['admoff_type'] ?? null,
                    'admoff_amount'      => $COURSE['admoff_amount'] ?? null,
                    'admoff_amount_in_usd'=> $COURSE['admoff_amount_in_usd'] ?? null
                ),

                // detailed
                'what_you_learn'    => !empty($COURSE['what_you_learn']) 
                                        ? json_decode(html_entity_decode($COURSE['what_you_learn']), true) 
                                        : [],
                'curs_skills'       => !empty($COURSE['curs_skills']) 
                                        ? array_map('trim', explode(',', $COURSE['curs_skills'])) 
                                        : [],
                'curs_about'        => html_entity_decode(html_entity_decode($COURSE['curs_about'] ?? '')),
                'objectives'        => html_entity_decode(html_entity_decode($COURSE['objectives'] ?? '')),
                'outcomes'          => html_entity_decode(html_entity_decode($COURSE['outcomes'] ?? '')),
                'outlines'          => html_entity_decode(html_entity_decode($COURSE['outlines'] ?? '')),
                'how_it_work'       => html_entity_decode(html_entity_decode($COURSE['how_it_work'] ?? '')),
                'course_content'    => $course_content  ?? [],

                'enrollment'        => array(
                    'title'    => "Start Learning Today",
                    'benefits' => array(
                        "Holistic Learning Path",
                        "Practice Tracker for Skill Building",
                        "Shareable Certificates",
                        "Self-Paced Learning Option",
                        "Course Videos & Readings",
                        "Practice Quizzes",
                        "Graded Assignments with Peer Feedback",
                        "Graded Quizzes with Feedback",
                        "Graded Assignments"
                    )
                ),

                'instructors'       => $instructors ?: [],
                'faqs'              => $faqArr ?: []
            );

            $rowjson['success'] 		= 1;
            $rowjson['MSG'] 			= 'Course Details Fetched Successfully';

        }
        else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'No Course Found';
        }
    }
    else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Course ID';
    }
    $rowjson['course_detail']   = $course;
}
?>
