<?php
if($data_arr['method_name'] == "get_home") {
    $categories		= array();
    $diplomas		= array();
    $instructors	= array();
    $onlinedegrees	= array();
    $shortcourses	= array();
    $trainings_array= array();

    // CATEGORIES
    $condition = array ( 
                         'select'       =>  'cc.cat_id, cc.cat_name'
                        ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.id_cat = cc.cat_id AND a.admoff_type=3'
                        ,'where'        =>  array ( 
                                                    'cc.is_deleted'    =>  0
                                                    ,'cc.cat_status'    =>  1
                                                )
                        ,'group_by'     =>  'a.id_cat'
                        ,'order_by'     =>  'cc.cat_id DESC'
                        ,'limit'        =>  8
                        ,'return_type'  =>  'all'
                    ); 
    $COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES.' cc', $condition);

    foreach ($COURSES_CATEGORIES AS $key => $val) {

        $cat['id'] 			= intval($val['cat_id']);
        $cat['name'] 		= html_entity_decode($val['cat_name']);
        array_push($categories, $cat);
    }

    // DIPLOMAS
    $condition = array ( 
                             'select'       =>	'mt.mas_id, mt.mas_duration, mt.mas_photo, mt.mas_name, COUNT(DISTINCT mtd.id_curs) TotalCourses, COUNT(DISTINCT ec.secs_id) TotalStd, mt.mas_amount, a.admoff_amount, ec.id_curs'
                            ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = mt.mas_id AND a.admoff_type=2
                                                LEFT JOIN '.MASTER_TRACK_DETAIL.' mtd ON mtd.id_mas = mt.mas_id
                                                LEFT JOIN '.ENROLLED_COURSES.' AS ec ON ec.id_mas = mt.mas_id'
                            ,'where'        =>	array( 
                                                         'mt.mas_status'    =>  1
                                                        ,'mt.is_deleted'    =>  0 
                                                    ) 
                            ,'group_by'  	=>	'mt.mas_id'
                            ,'return_type'  =>	'all'
                        );
    $MASTER_TRACK = $dblms->getRows(MASTER_TRACK.' mt', $condition);

    foreach ($MASTER_TRACK AS $key => $val) {

        // CHECK FILE EXIST
        $photo      = SITE_URL.'uploads/images/default_curs.jpg';
        $file_url   = SITE_URL.'uploads/images/admissions/master_track/'.$val['mas_photo'];
        if (check_file_exists($file_url)) {
            $photo = $file_url;
        }

        $dpl['id'] 				= intval($val['mas_id']);
        $dpl['type'] 			= "2";
        $dpl['name'] 			= html_entity_decode($val['mas_name']);
        $dpl['offeredby'] 		= 'Minhaj University Lahore';
        $dpl['courses'] 		= $val['TotalCourses'].' Course';
        $dpl['rating'] 			= "4.".rand(0, 9);
        $dpl['students'] 		= $val['TotalStd'];
        $dpl['duration'] 		= $val['mas_duration'].' Month';
        $dpl['price'] 			= 'Rs. '.number_format($val['mas_amount']);
        $dpl['discountprice'] 	= '';
        $dpl['photo'] 			= $photo;
        array_push($diplomas, $dpl);
    }

    // INSTRUCTORS
    $condition = array ( 
                             'select'       =>  "e.emply_id, e.emply_photo, e.emply_name, e.emply_gender, d.designation_name"
                            ,'join'         =>  'LEFT JOIN '.DESIGNATIONS.' d ON d.designation_id = e.id_designation AND d.is_deleted = 0 AND d.designation_status = 1'
                            ,'where' 	    =>  array( 
                                                         'e.emply_status'       => 1
                                                        ,'e.emply_request'      => 1
                                                        ,'e.is_deleted'         => 0
                                                    )
                            ,'order_by'  	=>  'e.emply_id DESC'
                            ,'limit'        =>  8
                            ,'return_type'  =>  'all'
                    ); 
    $EMPLOYEES = $dblms->getRows(EMPLOYEES.' e', $condition);

    foreach ($EMPLOYEES AS $key => $val) {
        // CHECK FILE EXIST
        $photo = SITE_URL.'uploads/images/default_'.($val['emply_gender'] == '2' ? 'female' : 'male').'.jpg';
        if(isset($val['emply_photo']) && !empty($val['emply_photo'])){
            $photo = SITE_URL.'uploads/images/employees/'.$val['emply_photo'];
        }
        $ins['id'] 				= intval($val['emply_id']);
        $ins['name'] 			= $val['emply_name'];
        $ins['photo'] 			= $photo;
        array_push($instructors, $ins);
    }

    // ONLINE DEGREES
    $condition = array ( 
                             'select' 		=>	'p.prg_id, p.prg_name, ap.id, ap.program, p.prg_duration, p.prg_photo, a.admoff_amount, COUNT(DISTINCT ec.secs_id) TotalStd'
                            ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = ap.id AND a.admoff_type = 1
                                                INNER JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg
                                                LEFT JOIN '.ENROLLED_COURSES.' AS ec ON ec.id_ad_prg = a.admoff_degree'
                            ,'where' 		    =>	array( 
                                                             'ap.status'      =>  1 
                                                            ,'ap.is_deleted'  =>  0 
                                                    )
                            ,'group_by'     =>  'ap.id'
                            ,'order_by'     =>  'ap.id DESC'
                            ,'return_type'	=>	'all'
                        ); 
    $ADMISSION_PROGRAMS = $dblms->getRows(ADMISSION_PROGRAMS.' ap', $condition);

    foreach ($ADMISSION_PROGRAMS AS $key => $val) {

        // CHECK FILE EXIST
        $photo      = 	SITE_URL.'uploads/images/default_curs.jpg';
        $file_url	=	SITE_URL.'uploads/images/programs/'.$val['prg_photo'];
        if (check_file_exists($file_url)) {
            $photo = $file_url;
        }
        $deg['id'] 					= intval($val['prg_id']);
        $deg['type'] 				= "1";
        $deg['name'] 				= html_entity_decode($val['prg_name']);
        $deg['offeredby'] 			= 'Minhaj University Lahore';
        $deg['courses'] 			= '10 Courses';
        $deg['rating'] 				= "4.".rand(0, 9);
        $deg['students'] 			= $val['TotalStd'];
        $deg['duration'] 			= $val['prg_duration'];
        $deg['price'] 				= 'Rs. '.number_format($val['admoff_amount']);
        $deg['discountprice'] 		= '';
        $deg['photo'] 				= $photo;
        array_push($onlinedegrees, $deg);
    }

    // SHORT COURSES
    $condition = array ( 
                         'select'       =>	'c.curs_type_status, c.curs_id, c.curs_wise, c.duration, c.curs_name, c.curs_photo, a.admoff_type, a.admoff_amount, a.admoff_amount_in_usd, att.id_teacher, e.emply_name'
                        ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type=3
                                             LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
                                             LEFT JOIN '.ALLOCATE_TEACHERS.' att ON att.id_curs = c.curs_id
                                             LEFT JOIN '.EMPLOYEES.' e ON e.emply_id = att.id_teacher'
                        ,'where' 		    =>	array( 
                                                     'c.curs_status' 	    => '1' 
                                                    ,'c.is_deleted' 	    => '0'
                                                )
                        ,'group_by'     =>  'c.curs_id'
                        ,'limit'        =>  10
                        ,'order_by'     =>  ' FIELD(c.curs_type_status, "2", "4", "3", "5", "1"), RAND()'
                        ,'return_type'	=>	'all'
                        );
    $COURSES = $dblms->getRows(COURSES.' c', $condition);

    foreach ($COURSES AS $key => $val) {

        $condition = array ( 
                                 'select'       =>	'd.discount_id, dd.discount, dd.discount_type'
                                ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' AS dd ON d.discount_id = dd.id_setup AND dd.id_curs = "'.$val['curs_id'].'"'
                                ,'where'        =>	array( 
                                                             'd.discount_status' 	=> '1' 
                                                            ,'d.is_deleted' 	    => '0'
                                                        )
                                ,'search_by'    =>  ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE'
                                ,'return_type'	=>	'single'
                            );
        $DISCOUNT = $dblms->getRows(DISCOUNT.' AS d ', $condition);        

        // CHECK FILE EXIST
        $photo = SITE_URL.'uploads/images/default_curs.jpg';
        if (isset($val['curs_photo']) && !empty($val['curs_photo'])) {
            $photo = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
        }

        // COURSE DATA
        $curs['course_id']              =   intval($val['curs_id']);
        $curs['course_name']            =   html_entity_decode($val['curs_name']);
        $curs['course_photo']           =   $photo;
        $curs['offering_type']          =   $val['admoff_type'];
        $curs['course_type']            =   $val['curs_type_status'];
        $curs['course_duration']        =   $val['duration'].' '.get_CourseWise($val['curs_wise']).($val['duration'] > 1 ? 's' : '');
        $curs['offering_amount']        =   $val['admoff_amount'];
        $curs['offering_amount_usd']    =   $val['admoff_amount_in_usd'];

        // INSTRUCTOR        
        $curs['instructor_name']        =   $val['emply_name'] ?? '';

        if($DISCOUNT){
            if ($val['curs_type_status'] != '1' && !empty($DISCOUNT['discount_id'])) {
                $discount_type  = $DISCOUNT['discount_type'];
                $discount_value = $DISCOUNT['discount'];
            }
        }        
        $curs['discount_type']          =   ($discount_type ?? "0");
        $curs['discount_value']         =   ($discount_value ?? "0");

        array_push($shortcourses, $curs);
    }

    // TRAININGS
    $condition = array ( 
                         'select'       =>	'c.curs_id, c.curs_name, c.curs_photo, c.curs_type_status, c.curs_hours, a.id_type, a.admoff_type, a.admoff_amount, a.admoff_amount_in_usd'
                        ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type = 4'
                        ,'where'        =>	array( 
                                                     'c.curs_status'    => '1' 
                                                    ,'c.is_deleted'     => '0'
                                                )
                        ,'group_by'     =>  'c.curs_id'
                        ,'order_by'     =>  'c.curs_id DESC'
                        ,'limit'        =>  10
                        ,'return_type'	=>	'all'
                    );
    $TRAININGS = $dblms->getRows(COURSES.' c', $condition);

    foreach ($TRAININGS as $course) {
        
        // CHECK FILE EXIST
        $photo = SITE_URL.'uploads/images/default_curs.jpg';
        if (isset($course['curs_photo']) && !empty($course['curs_photo'])) {
            $photo = SITE_URL.'uploads/images/courses/'.$course['curs_photo'];
        }

        $condition = array ( 
                                 'select'       =>	'd.discount_id, dd.discount, dd.discount_type'
                                ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' dd ON d.discount_id = dd.id_setup AND dd.id_curs = "'.$course['curs_id'].'"'
                                ,'where'        =>	array( 
                                                             'd.discount_status' 	=> '1' 
                                                            ,'d.is_deleted' 	    => '0'
                                                        )
                                ,'search_by'    =>  ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE '
                                ,'return_type'	=>	'single'
                            );
        $DISCOUNT = $dblms->getRows(DISCOUNT.' d ', $condition);

        // COURSE DATA
        $valTraining['training_id']           =   intval($course['curs_id']);
        $valTraining['training_name']         =   html_entity_decode($course['curs_name']);
        $valTraining['training_photo']        =   $photo;
        $valTraining['training_type']         =   $course['id_type'];
        $valTraining['trainings_hours']       =   $course['curs_hours'].' Hour'.($course['curs_hours'] > 1 ? 's' : '');
        $valTraining['offering_type']         =   $course['admoff_type'];
        $valTraining['offering_amount']       =   $course['admoff_amount'];
        $valTraining['offering_amount_usd']   =   $course['admoff_amount_in_usd'];

        // DISCOUNT
        if($DISCOUNT){
            if ($course['curs_type_status'] != '1' && !empty($DISCOUNT['discount_id'])) {
                $discount_type  = $DISCOUNT['discount_type'];
                $discount_value = $DISCOUNT['discount'];
            }
        }        
        $valTraining['discount_type']          =   ($discount_type ?? "0");
        $valTraining['discount_value']         =   ($discount_value ?? "0");

        array_push($trainings_array, $valTraining);
    }

    $rowjson['category']		= $categories;
    $rowjson['diplomas']		= $diplomas;
    $rowjson['instructors']		= $instructors;
    $rowjson['onlinedegrees']	= $onlinedegrees;
    $rowjson['shortcourses']	= $shortcourses;
    $rowjson['trainings']	    = $trainings_array;
} 