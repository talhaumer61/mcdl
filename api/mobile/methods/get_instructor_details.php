<?php
if($data_arr['method_name'] == "get_instructor_details") { 
    $ins                = array();
    $allocated_courses  = array();

    if(empty($data_arr['instructor_id']) || $data_arr['instructor_id'] > 0){
        // EMPLOYEE DETAIL + counts
        $condition = array ( 
            'select'        => 'e.emply_id, e.emply_name, e.emply_gender, e.emply_photo, e.emply_phone, e.emply_email, d.designation_name, e.emply_experince, COUNT(DISTINCT t.id) AS allocated_courses_count, COUNT(DISTINCT ec.id_std) AS enrolled_student_count',
            'join'          => 'LEFT JOIN '.DESIGNATIONS.' d ON d.designation_id = e.id_designation
                                LEFT JOIN '.ALLOCATE_TEACHERS.' t ON FIND_IN_SET('.cleanvars($data_arr['instructor_id']).', t.id_teacher)
                                LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(t.id_curs, ec.id_curs)',
            'where'         => array(
                                'e.is_deleted'    => 0,
                                'e.emply_status'  => 1,
                                'e.emply_id'      => cleanvars($data_arr['instructor_id'])
                            ),
            'return_type'  => 'single'
        ); 
        $EMPLOYEES = $dblms->getRows(EMPLOYEES.' e', $condition);

        if($EMPLOYEES['emply_id']) {

            // ALLOCATED COURSES
            $condition = array ( 
                'select'       => 'c.curs_id, c.curs_name, c.curs_wise, c.duration, c.curs_photo,  c.best_seller, c.curs_type_status, COUNT(ec.secs_id) as TotalStd',
                'join'         => 'INNER JOIN '.ALLOCATE_TEACHERS.' ct ON c.curs_id = ct.id_curs AND FIND_IN_SET('.$EMPLOYEES['emply_id'].', ct.id_teacher)
                                LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)',
                'where'        => array(
                                    'c.is_deleted'  => 0,
                                    'c.curs_status' => 1
                                ),
                'group_by'     => 'c.curs_id',
                'order_by'     => 'c.curs_id DESC',
                'return_type'  => 'all'
            ); 
            $COURSES = $dblms->getRows(COURSES.' c', $condition);

            foreach ($COURSES AS $val) {
                $photo    = SITE_URL.'uploads/images/default_curs.jpg';
                if (!empty($val['curs_photo'])) {
                    $photo = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
                }

                $curs['course_id']   = intval($val['curs_id']);
                $curs['course_name'] = html_entity_decode($val['curs_name']);
                $curs['students']    = $val['TotalStd'];
                $curs['duration']    = $val['duration'].' '.get_CourseWise($val['curs_wise']).($val['duration'] > 1 ? 's' : '');
                $curs['photo']       = $photo;
                $curs['best_seller'] = (!empty($val['best_seller']) && $val['best_seller'] != null) ? $val['best_seller'] : '0';
                $curs['course_type'] =   $val['curs_type_status'];

                array_push($allocated_courses, $curs);
            }

            // PHOTO (with gender fallback)
            if(!empty($EMPLOYEES['emply_photo']) && $EMPLOYEES['emply_photo'] != null){
                $emply_photo = SITE_URL.'uploads/images/employees/'.$EMPLOYEES['emply_photo'];
            }
            elseif($EMPLOYEES['emply_gender'] == 2){
                $emply_photo = SITE_URL.'uploads/images/default_female.jpg';
            }
            else{
                $emply_photo = SITE_URL.'uploads/images/default_male.jpg';
            }
            // FINAL JSON
            $ins['instructor_id']           = intval($EMPLOYEES['emply_id']);
            $ins['instructor_name']         = $EMPLOYEES['emply_name'];
            $ins['designation']             = $EMPLOYEES['designation_name'];
            $ins['instructor_phone']        = $EMPLOYEES['emply_phone'];
            $ins['instructor_email']        = $EMPLOYEES['emply_email'];
            $ins['instructor_photo']        = $emply_photo;
            $ins['instructor_experince']    = $EMPLOYEES['emply_experince'];
            $ins['allocated_courses_count'] = $EMPLOYEES['allocated_courses_count'];
            $ins['enrolled_student_count']  = $EMPLOYEES['enrolled_student_count'];
            $ins['allocated_courses']       = $allocated_courses;

            // SUCCESS MSG
            $rowjson['success'] 		= 1;
            $rowjson['MSG'] 			= 'Instructor Detail Fetched Successfully';
        }
        else {
            $rowjson['success'] 		= 0;
            $rowjson['MSG'] 			= 'No Instructor Found';
        }
        
    }
    else {
        $rowjson['success'] 		= 0;
        $rowjson['MSG'] 			= 'Invalid Instructor ID';
    }
    $rowjson['instructor_detail'] = $ins;
}
?>
