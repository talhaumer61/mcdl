<?php
if ($data_arr['method_name'] == "get_next_redirection") {

    if( !empty($data_arr['std_id']) && !empty($data_arr['item_id'])  ){

        $std_id     = cleanvars($data_arr['std_id']);
        $course_id  = cleanvars($data_arr['item_id']);
        $id_mas     = cleanvars($data_arr['id_mas']);
        $id_ad_prg  = cleanvars($data_arr['id_ad_prg']);
        $next_redirection = [];

        if(isset($course_id)){
            $conitions = array ( 
                                'select'        =>	'ec.secs_id'
                                ,'where' 	    =>	array( 
                                                         'ec.secs_status'   => 1
                                                        ,'ec.is_deleted'    => 0
                                                        ,'ec.id_std' 	    => cleanvars($std_id) 
                                                        ,'ec.id_curs'       => cleanvars($course_id) 
                                                        ,'ec.id_mas' 	    => cleanvars($id_mas) 
                                                        ,'ec.id_ad_prg'     => cleanvars($id_ad_prg) 
                                                    )
                                ,'return_type'  =>	'single'
                            ); 
            $ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conitions);
            if($ENROLLED_COURSES){
                $arrays = array();
                // OPEN LESSON
                $con    = array ( 
                                    'select'        =>	'cl.lesson_id as id, cl.lesson_topic as title, cl.id_week, lt.is_completed'
                                    ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON ( lt.id_curs = '.$course_id.' AND lt.id_lecture = cl.lesson_id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')' 
                                    ,'where' 	    =>	array( 
                                                            'cl.lesson_status'  => 1
                                                            ,'cl.is_deleted'    => 0
                                                            ,'cl.id_curs' 	    => cleanvars($course_id) 
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
                                    'select'        =>	'ca.id as id, ca.id_week, ca.caption as title, lt.is_completed'
                                    ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' AS lt ON (lt.id_curs = '.cleanvars($course_id).' AND lt.id_assignment = ca.id AND lt.id_std = '.cleanvars($std_id).' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')' 
                                    ,'where' 	    =>	array( 
                                                            'ca.status'         => 1
                                                            ,'ca.is_deleted'    => 0
                                                            ,'ca.id_curs' 	    => cleanvars($course_id) 
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
                                    'select'        =>	'q.quiz_id as id, q.id_week, q.quiz_title as title, lt.is_completed'
                                    ,'join'         =>	'LEFT JOIN '.LECTURE_TRACKING.' lt ON (lt.id_quiz = q.quiz_id AND lt.id_curs = '.$course_id.' AND lt.id_std = '.$std_id.' AND lt.id_mas = '.$id_mas.' AND lt.id_ad_prg = '.$id_ad_prg.')'
                                    ,'where' 	    =>	array( 
                                                            'q.quiz_status'     => 1
                                                            ,'q.is_deleted'     => 0
                                                            ,'q.is_publish'     => 1
                                                            ,'q.id_curs'        => cleanvars($course_id) 
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
                $minIdWeek      = PHP_INT_MAX;
                $minIdWeekIndex = -1;

                // Loop through each array
                foreach ($arrays as $index => $array) {
                    // Check if the current array has the lowest id_week
                    if (isset($array['id_week']) && $array['id_week'] < $minIdWeek) {
                        // Update the minimum id_week and its index
                        $minIdWeek      = $array['id_week'];
                        $minIdWeekIndex = $index;
                    }
                }

                // Output the array with the lowest id_week AND modify redirection
                if ($minIdWeekIndex != -1) {
                    $type       = end($arrays[$minIdWeekIndex]);
                    $name       = $arrays[$minIdWeekIndex]['title'];
                    $next_id    = $arrays[$minIdWeekIndex]['id'];
                    $next_redirection = [
                        'id'    => $next_id,
                        'type'  => $type,
                    ];

                    $rowjson['success'] = 1;
                    $rowjson['MSG']     = "Next redirection found";
                } else {
                    $rowjson['success'] = 0;
                    $rowjson['MSG']     = "No next redirection available"; 
                    $next_redirection = [
                        'id'    => '0',
                        'type'  => 'lesson',
                    ];
                }
            } else {                
                $rowjson['success'] = 0;
                $rowjson['MSG'] = "no enrollment found";
                $next_redirection = [
                    'id' => '',
                    'type' => '',
                ];
            }
        }
        else{
            $rowjson['success'] = 0;
            $rowjson['MSG']     = "Course ID is missing";
            $next_redirection = [
                'id'    => '',
                'type'  => '',
            ];
        }
    }
    else{
        $rowjson['success'] = 0;
        $rowjson['MSG'] = "Required parameters are missing";
        $next_redirection = [
            'id' => '',
            'type' => '',
        ];
    }
    $rowjson['next_redirection'] = $next_redirection;
}