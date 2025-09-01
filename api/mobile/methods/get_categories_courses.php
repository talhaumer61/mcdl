<?php
if($data_arr['method_name'] == "get_categories_courses") { 
		
		$courses = array();

		$condition = array ( 
							'select' 		=>	'c.curs_id, c.curs_name, c.curs_photo, cc.cat_name, COUNT(DISTINCT l.id_week) duration, a.admoff_amount'
							,'join'         =>  'INNER JOIN '.COURSES_CATEGORIES.' cc ON cc.cat_id = c.id_cat
												 INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type=3
												 LEFT JOIN '.COURSES_LESSONS.' l ON l.id_curs = c.curs_id AND l.is_deleted = 0 AND l.lesson_status = 1'
							,'where' 		    =>	array( 
															 'c.curs_status'	=> '1' 
															,'c.is_deleted'		=> '0' 
															,'c.id_cat'			=> cleanvars($data_arr['id_cat'])
													)
							,'group_by'     =>  'c.curs_id'
							,'order_by'     =>  'c.curs_id DESC'
							,'return_type'	=>	'all'
						); 
		$COURSES = $dblms->getRows(COURSES .' c', $condition, $sql);

		foreach($COURSES AS $key => $val) {
			$countA = count($array);
			$photo      = SITE_URL.'uploads/images/default_curs.jpg';
			$file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}
			$dataCourse['curs_id'] 			= $val['curs_id'];
			$dataCourse['type'] 			= 3;
			$dataCourse['curs_photo'] 		= $photo;
			$dataCourse['curs_name'] 		= $val['curs_name'];
			$dataCourse['cat_name'] 		= $val['cat_name'];
			$dataCourse['duration'] 		= $val['duration']. ' Week';
			$dataCourse['rating'] 			= "4.".rand(0, 9);
			$dataCourse['price'] 			= 'Rs. '.number_format($val['admoff_amount']);
			array_push($courses, $dataCourse);
		}

		$rowjson['categories_courses']	= $courses;
	} 