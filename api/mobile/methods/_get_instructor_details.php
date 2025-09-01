<?php
if($data_arr['method_name'] == "get_instructor_details") { 
    $ins 				= array();
    $allocated_courses 	= array();
    // INSTRUCTORS
    $condition = array ( 
                                'select'       =>  'e.emply_photo, e.emply_name, e.emply_gender, d.designation_name, e.emply_experince, COUNT(DISTINCT t.id) AS allocated_courses_count, COUNT(DISTINCT ec.id_std) AS enroled_student_count'
                            ,'join'       	=>  'LEFT JOIN '.DESIGNATIONS.' AS d ON d.designation_id = e.id_designation
                                                    LEFT JOIN '.ALLOCATE_TEACHERS.' AS t ON FIND_IN_SET('.cleanvars($data_arr['instructor_id']).', t.id_teacher)
                                                    LEFT JOIN '.ENROLLED_COURSES.' as ec ON FIND_IN_SET(t.id_curs,ec.id_curs)'
                            ,'where' 	    =>  array( 
                                                                'e.emply_status'   => 1
                                                            ,'e.is_deleted'    	=> 0
                                                            ,'e.emply_id'    	=> cleanvars($data_arr['instructor_id'])
                                                            
                                                )
                            ,'return_type'  =>  'single'
                        ); 
    $EMPLOYEES = $dblms->getRows(EMPLOYEES.' AS e', $condition);

    // CHECK FILE EXIST
    if(!empty($EMPLOYEES['emply_photo'])){
        $file_url   				= SITE_URL.'uploads/images/employees/'.$EMPLOYEES['emply_photo'];
        if (check_file_exists($file_url)) {
            $emply_photo 					= $file_url;
        }
    } else {
        if ($EMPLOYEES['emply_gender'] == '2'){
            $emply_photo = SITE_URL.'uploads/images/default_female.jpg';
        } else {            
            $emply_photo = SITE_URL.'uploads/images/default_male.jpg';
        }
    }

    // SHORT COURSES
    $condition = array ( 
                                'select' 		=>	'c.curs_id, c.curs_name, c.curs_photo, COUNT(DISTINCT ec.secs_id) as TotalStd, COUNT(DISTINCT l.id_week) duration, a.admoff_amount'
                            ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type=3
                                                    LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
                                                    LEFT JOIN '.COURSES_LESSONS.' l ON l.id_curs = c.curs_id AND l.is_deleted = 0 AND l.lesson_status = 1
                                                    LEFT JOIN '.ALLOCATE_TEACHERS.' AS ca ON ca.id_curs = c.curs_id AND FIND_IN_SET('.cleanvars($data_arr['instructor_id']).', ca.id_teacher)'
                            ,'where' 		    =>	array( 
                                                                'c.curs_status' 	    => '1' 
                                                            ,'c.is_deleted' 	    => '0' 
                                                    )
                            ,'group_by'     =>  'c.curs_id'
                            ,'order_by'     =>  'c.curs_id DESC'
                            ,'return_type'	=>	'all'
                        ); 
    $COURSES = $dblms->getRows(COURSES.' c', $condition);

    foreach ($COURSES AS $key => $val) {

        // CHECK FILE EXIST
        $photo      = SITE_URL.'uploads/images/default_curs.jpg';
        $file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
        if (check_file_exists($file_url)) {
            $photo = $file_url;
        }

        $curs['id'] 				= intval($val['curs_id']);
        $curs['type'] 				= 3;
        $curs['name'] 				= html_entity_decode($val['curs_name']);
        $curs['offeredby'] 			= 'Minhaj University Lahore';
        $curs['rating'] 			= "4.".rand(0, 9);
        $curs['students'] 			= $val['TotalStd'];
        $curs['duration'] 			= $val['duration']. ' Week';
        $curs['price'] 				= 'Rs. '.number_format($val['admoff_amount']);
        $curs['discountprice'] 		= '';
        $curs['photo'] 				= $photo;

        array_push($allocated_courses, $curs);
    }

    $ins['id'] 						= intval($data_arr['instructor_id']);
    $ins['emply_name'] 				= $EMPLOYEES['emply_name'];
    $ins['designation_name'] 		= $EMPLOYEES['designation_name'];
    $ins['photo'] 					= $emply_photo;
    $ins['linkedin'] 				= '#';
    $ins['facebook'] 				= '#';
    $ins['website'] 				= '#';
    $ins['allocated_courses_count'] = $EMPLOYEES['allocated_courses_count'];
    $ins['emply_experince'] 		= $EMPLOYEES['emply_experince'];
    $ins['enroled_student_count'] 	= $EMPLOYEES['enroled_student_count'];
    $ins['rating'] 					= "4.".rand(0, 9);
    $ins['allocated_courses'] 		= $allocated_courses;
    $ins['reviewslist'] 			= $data['reviewslist'];


    $rowjson['instructor_detial']	= $ins;
} 