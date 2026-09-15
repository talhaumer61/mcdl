<?php
if($data_arr['method_name'] == "my_enrolled_completed_courses_list") { 		
    $enrolled_courses	=   array();	
    $completed_courses  =   array();	
    $conditions = array ( 
                                'select'        =>	'c.curs_name, c.curs_photo, c.curs_id, c.curs_href, ec.secs_id, ec.id_mas, ec.id_ad_prg
                                                ,COUNT(DISTINCT cl.lesson_id) AS lesson_count
                                                ,COUNT(DISTINCT cl.id_week) AS curs_duration
                                                ,COUNT(DISTINCT ca.id) AS assignment_count
                                                ,COUNT(DISTINCT cq.quiz_id) AS quiz_count
                                                ,COUNT(DISTINCT lt.track_id) AS track_count'
                            ,'join'         =>	'INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                                                LEFT JOIN '.COURSES_LESSONS.' AS cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                                LEFT JOIN '.COURSES_ASSIGNMENTS.' AS ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                                                LEFT JOIN '.QUIZ.' AS cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
                                                LEFT JOIN '.LECTURE_TRACKING.' AS lt ON lt.id_curs = c.curs_id AND lt.id_std = '.cleanvars($data_arr['user_id']).' AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg'
                            ,'where' 		=>	array( 
                                                            'ec.is_deleted'    => '0'
                                                        ,'ec.secs_status'   => '1' 
                                                        ,'ec.id_ad_prg'     => '0' 
                                                        ,'ec.id_mas'        => '0' 
                                                        ,'ec.id_std' 	    => cleanvars($data_arr['user_id']) 
                                                    )
                            ,'group_by'	    =>	'c.curs_id'
                            ,'return_type'	=>	'all'
                        ); 
    $ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);

    foreach($ENROLLED_COURSES AS $key => $val) :

        // CHECK FILE EXIST
        $photo      = SITE_URL.'uploads/images/default_curs.jpg';
        $file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
        if (check_file_exists($file_url)) {
            $photo = $file_url;
        }

        // OBTAINED PERCENTAGE
        $obt_percenteage = intval((($val['track_count'] / ($val['lesson_count'] + $val['assignment_count'] + $val['quiz_count'])) * 100));
        
        $dataCourse['curs_photo']				= $photo;
        $dataCourse['curs_name'] 				= $val['curs_name'];
        $dataCourse['curs_id'] 					= $val['curs_id'];
        $dataCourse['type'] 					= 3;
        $dataCourse['id_mas'] 					= $val['id_mas'];
        $dataCourse['id_ad_prg'] 				= $val['id_ad_prg'];
        $dataCourse['curs_total_percentage'] 	= 100;
        $dataCourse['curs_obtain_percentage'] 	= intval($obt_percenteage >= 100 ? 100 : $obt_percenteage);
        $dataCourse['curs_duration'] 			= $val['curs_duration']. ' Week';
        
        if($obt_percenteage >= 100){
            array_push($completed_courses,$dataCourse);
        }else{
            array_push($enrolled_courses,$dataCourse);
        }
    endforeach;

    $rowjson['enrolled_courses']		= $enrolled_courses;
    $rowjson['completed_courses']		= $completed_courses;
} 