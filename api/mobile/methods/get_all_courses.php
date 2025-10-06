<?php
if($data_arr['method_name'] == "get_all_courses") {
	
	$page			= $data_arr['page'] ?? 1;
	$limit			= $data_arr['limit'] ?? 10;

	if($page){
		$start = ($page - 1) * $limit;
	} else {
		$start = 0;
	}
		
	$shortcourses = array();

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
						,'order_by'     =>  ' FIELD(c.curs_type_status, "2", "4", "3", "5", "1"), c.curs_id DESC'
						,'return_type'	=>	'count'
						);					
	$count = $dblms->getRows(COURSES.' c', $condition);

	$lastpage = ceil($count / $limit);   //lastpage = total pages // items per page, rounded up

	$condition['limit'] = "$start,$limit";   // ✅ only LIMIT here
	$condition['return_type'] = 'all';
	
	$COURSES = $dblms->getRows(COURSES.' c', $condition);

	if($COURSES){
		foreach ($COURSES AS $key => $val) {
			// GET DISCOUNT
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
		$rowjson['success']			= 1;
		$rowjson['MSG'] 			= 'Updated Courses List';
	} else {		
		$rowjson['success']			= 0;
		$rowjson['MSG'] 			= 'No Courses Found';
	}
	$rowjson['courses_list']	= $shortcourses;
}