<?php
if($data_arr['method_name'] == "get_instructor_details") { 
    $ins                = array();
    $allocated_courses  = array();

    // EMPLOYEE DETAIL + counts
    $condition = array ( 
        'select'       => 'e.emply_id, e.emply_name, e.emply_gender, e.emply_photo, 
                           e.emply_phone, e.emply_email, 
                           d.designation_name, e.emply_experince,
                           COUNT(DISTINCT t.id) AS allocated_courses_count, 
                           COUNT(DISTINCT ec.id_std) AS enroled_student_count',
        'join'         => 'LEFT JOIN '.DESIGNATIONS.' d ON d.designation_id = e.id_designation
                           LEFT JOIN '.ALLOCATE_TEACHERS.' t ON FIND_IN_SET('.cleanvars($data_arr['instructor_id']).', t.id_teacher)
                           LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(t.id_curs, ec.id_curs)',
        'where'        => array(
                            'e.is_deleted'    => 0,
                            'e.emply_status'  => 1,
                            'e.emply_id'      => cleanvars($data_arr['instructor_id'])
                         ),
        'return_type'  => 'single'
    ); 
    $EMPLOYEES = $dblms->getRows(EMPLOYEES.' e', $condition);

    if($EMPLOYEES){

        // ALLOCATED COURSES
        $condition = array ( 
            'select'       => 'c.curs_id, c.curs_name, c.curs_wise, c.duration, 
                               c.curs_icon, c.curs_rating, c.curs_photo, c.curs_href, 
                               cc.cat_name, c.best_seller, c.curs_type_status,
                               COUNT(ec.secs_id) as TotalStd',
            'join'         => 'INNER JOIN '.ALLOCATE_TEACHERS.' ct ON c.curs_id = ct.id_curs 
                                    AND FIND_IN_SET('.$EMPLOYEES['emply_id'].', ct.id_teacher)
                               INNER JOIN '.COURSES_CATEGORIES.' cc ON c.id_cat=cc.cat_id
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
            $file_url = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
            if (check_file_exists($file_url)) {
                $photo = $file_url;
            }

            $curs['id']           = intval($val['curs_id']);
            $curs['name']         = html_entity_decode($val['curs_name']);
            $curs['category']     = $val['cat_name'];
            $curs['rating']       = $val['curs_rating'];
            $curs['students']     = $val['TotalStd'];
            $curs['duration']     = $val['duration'];
            $curs['photo']        = $photo;
            $curs['best_seller']  = $val['best_seller'];

            array_push($allocated_courses, $curs);
        }

        // EMPLOYEE EXPERIENCE
        $condition = array ( 
            'select'       => 'jobfield, organization, designation, date_start, date_end',
            'where'        => array(
                                'is_deleted'   => 0,
                                'status'       => 1,
                                'id_employee'  => $EMPLOYEES['emply_id']
                             ),
            'return_type'  => 'all'
        ); 
        $emp_experience = $dblms->getRows(EMPLOYEE_EXPERIENCE, $condition);

        // PHOTO (with gender fallback)
        $emply_photo = ($EMPLOYEES['emply_gender'] == 'Female')
            ? SITE_URL.'uploads/images/default_female.jpg'
            : SITE_URL.'uploads/images/default_male.jpg';

        if(!empty($EMPLOYEES['emply_photo'])){
            $file_url = SITE_URL.'uploads/images/employees/'.$EMPLOYEES['emply_photo'];
            if (check_file_exists($file_url)) {
                $emply_photo = $file_url;
            }
        }

        // FINAL JSON
        $ins['id']                     = intval($EMPLOYEES['emply_id']);
        $ins['emply_name']             = $EMPLOYEES['emply_name'];
        $ins['designation']            = $EMPLOYEES['designation_name'];
        $ins['phone']                  = $EMPLOYEES['emply_phone'];
        $ins['email']                  = $EMPLOYEES['emply_email'];
        $ins['photo']                  = $emply_photo;
        $ins['emply_experince']        = $EMPLOYEES['emply_experince'];
        $ins['allocated_courses_count']= $EMPLOYEES['allocated_courses_count'];
        $ins['enroled_student_count']  = $EMPLOYEES['enroled_student_count'];
        $ins['allocated_courses']      = $allocated_courses;
        $ins['experience']             = $emp_experience;
    }

    $rowjson['instructor_detail'] = $ins;
}
?>
