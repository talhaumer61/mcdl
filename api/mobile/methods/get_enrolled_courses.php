<?php
if ($data_arr['method_name'] == "get_enrolled_courses") {

	$courses_list = [];
	if(isset($data_arr['std_id']) && $data_arr['std_id'] != '') {
		// Valid Student ID
		$std_id = $data_arr['std_id'];
		$conditions = array ( 
                         'select'       =>	'c.curs_name, c.curs_photo, c.curs_id, ec.secs_id, ec.id_mas, ec.id_ad_prg
                                            ,COUNT(DISTINCT cl.lesson_id) AS lesson_count
                                            ,COUNT(DISTINCT ca.id) AS assignment_count
                                            ,COUNT(DISTINCT cq.quiz_id) AS quiz_count
                                            ,COUNT(DISTINCT lt.track_id) AS track_count'
                        ,'join'         =>	'INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs AND c.curs_status = 1 AND c.is_deleted = 0
                                             LEFT JOIN '.COURSES_LESSONS.' AS cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                             LEFT JOIN '.COURSES_ASSIGNMENTS.' AS ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                                             LEFT JOIN '.QUIZ.' AS cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
                                             LEFT JOIN '.LECTURE_TRACKING.' AS lt ON lt.id_curs = c.curs_id AND lt.id_std = '.cleanvars($std_id).' AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg'
                        ,'where' 		=>	array( 
                                                     'ec.is_deleted'    => '0'
                                                    ,'ec.secs_status'   => '1'
                                                    ,'ec.id_type'       => '3'
                                                    ,'ec.id_ad_prg'     => '0'
                                                    ,'ec.id_mas'        => '0'
                                                    ,'ec.id_std' 	    => cleanvars($std_id) 
                                                )
                        ,'group_by'	    =>	'c.curs_id'
                        ,'return_type'	=>	'all'
                    ); 
		$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions, $sql);
		if($ENROLLED_COURSES){
			foreach ($ENROLLED_COURSES as $course) :
				// CHECK FILE EXIST
                $photo = SITE_URL.'uploads/images/default_curs.jpg';
                if (!empty($course['curs_photo'])) {
                    $photo = SITE_URL.'uploads/images/courses/'.$course['curs_photo'];
                }
                
                $arrays = array();
                // OPEN LESSON
                $con = array ( 
                                 'select'       =>	'cl.lesson_id as id, cl.id_week, lt.is_completed'
                                ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON ( lt.id_curs = '.$course['curs_id'].' AND lt.id_lecture = cl.lesson_id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$course['id_mas'].' AND lt.id_ad_prg = '.$course['id_ad_prg'].')' 
                                ,'where' 	    =>	array( 
                                                             'cl.lesson_status' => 1
                                                            ,'cl.is_deleted'    => 0
                                                            ,'cl.id_curs' 	    => cleanvars($course['curs_id']) 
                                                        ) 
                                ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                ,'order_by'     =>	'cl.id_week, cl.id_lecture, cl.lesson_id ASC LIMIT 1'
                                ,'return_type'  =>	'single'
                            ); 
                $COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS.' cl', $con);
                if($COURSES_LESSONS){
                    array_push($COURSES_LESSONS, 'lesson');
                    array_push($arrays, $COURSES_LESSONS);
                }

                // OPEN ASSIGNMENT
                $con = array ( 
                                 'select'       =>	'ca.id as id, ca.id_week, lt.is_completed'
                                ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.cleanvars($course['curs_id']).' AND lt.id_assignment = ca.id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$course['id_mas'].' AND lt.id_ad_prg = '.$course['id_ad_prg'].')' 
                                ,'where' 	    =>	array( 
                                                             'ca.status'        => 1
                                                            ,'ca.is_deleted'    => 0
                                                            ,'ca.id_curs' 	    => cleanvars($course['curs_id']) 
                                                        ) 
                                ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                ,'order_by'     =>	'ca.id_week ASC LIMIT 1'
                                ,'return_type'  =>	'single'
                            ); 
                $COURSES_ASSIGNMENTS = $dblms->getRows(COURSES_ASSIGNMENTS.' ca', $con);
                if($COURSES_ASSIGNMENTS){
                    array_push($COURSES_ASSIGNMENTS, 'assignments');
                    array_push($arrays, $COURSES_ASSIGNMENTS);
                }

                // OPEN QUIZ
                $con = array ( 
                                 'select'       =>	'q.quiz_id as id, q.id_week, lt.is_completed'
                                ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' lt ON (lt.id_quiz = q.quiz_id AND lt.id_curs = '.$course['curs_id'].' AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$course['id_mas'].' AND lt.id_ad_prg = '.$course['id_ad_prg'].')'
                                ,'where' 	    =>	array( 
                                                             'q.quiz_status'    => 1
                                                            ,'q.is_deleted'     => 0
                                                            ,'q.is_publish'     => 1
                                                            ,'q.id_curs'        => cleanvars($course['curs_id']) 
                                                        ) 
                                ,'search_by'    =>	' AND ( lt.is_completed IS NULL OR lt.is_completed = "" OR lt.is_completed = 1 )'
                                ,'order_by'     =>	'q.id_week ASC, q.quiz_id ASC'
                                ,'return_type'  =>	'single'
                            ); 
                $QUIZ = $dblms->getRows(QUIZ.' q', $con, $sql);
                if($QUIZ){
                    array_push($QUIZ, 'quiz');
                    array_push($arrays, $QUIZ);
                }

                // Initialize variables to keep track of the minimum id_week and its index
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
                $redirection = [];
                if ($minIdWeekIndex != -1) {
                    $redirection[] = array (
                        'type'      => end($arrays[$minIdWeekIndex]),
                        'id'        => $arrays[$minIdWeekIndex]['id'],
                        'id_enroll' => $course['secs_id']
                    );
                } else {
                    $redirection[] = array (
                        'type'      => 'lesson',
                        'id'        => '0',
                        'id_enroll' => $course['secs_id']
                    );
                }
                
                // PERCENTAGE
                $Total      = $course['lesson_count'] + $course['assignment_count'] + $course['quiz_count'];
                $Obtain     = $course['track_count'];
                $pecent     = (($Obtain/$Total)*100);
                $percent    = ($pecent >= '100' ? '100' : $pecent);

                $courses_list[] = array(
                     'course_id'            => $course['curs_id']
                    ,'course_name'          => $course['curs_name']
                    ,'course_photo'         => $photo
                    ,'lesson_count'         => $course['lesson_count']
                    ,'assignment_count'     => $course['assignment_count']
                    ,'quiz_count'           => $course['quiz_count']
                    ,'completed_count'      => $course['track_count']
                    ,'percentage'           => number_format((float)$percent, 2, '.', '')
                    ,'redirection'          => $redirection
                );
			endforeach;
			$rowjson['success'] = 1;
			$rowjson['MSG']     = 'Enrolled courses fetched successfully.';
		}
		else{
			$rowjson['success'] = 0;
			$rowjson['MSG']     = 'No enrolled courses found.';
		}
	} else {
		$rowjson['success'] = 0;
		$rowjson['MSG']     = 'Student ID is required.';
	}
	$rowjson['courses_list'] = $courses_list;	
}