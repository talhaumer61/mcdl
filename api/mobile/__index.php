<?php 

	//include("includes/connection.php");
 	//include("includes/function.php"); 	
 	include_once("includes/data/data.php");
 	include("language/app_language.php");

	include "includes/dbsetting/lms_vars_config.php";
	include "includes/dbsetting/classdbconection.php";
	include "includes/functions/functions.php";
	$dblms = new dblms();
	include "includes/functions/login_func.php";

 	date_default_timezone_set("Asia/Karachi");

 	error_reporting(0);

 	define("HOME_LIMIT"		, $settings_details['api_home_limit']);
 	
 	define("PACKAGE_NAME"	, $settings_details['package_name']);

 	
	$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

	$file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
	 
	function get_thumb($filename,$thumb_size) {	
		$protocol 	= strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

		$file_path 	= $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

		return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;
	}

if(isset($_POST)){ 

	$data_arr = json_decode(file_get_contents('php://input'), true);

	if($data_arr['method_name'] == "get_home") { 
		
		$categories		= array();
		$diplomas		= array();
		$instructors	= array();
		$onlinedegrees	= array();
		$shortcourses	= array();

		// CATEGORIES
		$condition = array ( 
							'select'       =>  'cc.cat_id, cc.cat_name'
							,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.id_cat = cc.cat_id AND a.admoff_type=3'
							,'where' 	      =>  array ( 
														'cc.is_deleted'    =>  0
														,'cc.cat_status'    =>  1
													)
							,'group_by'     =>  'a.id_cat'
							,'order_by'     =>  'RAND()'
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
			$dpl['type'] 			= 2;
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
								 'select'       =>  "emply_id,emply_photo,emply_name, emply_gender"
								,'where' 	    =>    array( 
																 'emply_status'    	=> 1
																,'emply_request'    => 1
																,'emply_status '    => 1
																,'is_deleted'    	=> 0
														)
								,'order_by'  	=>  'RAND() LIMIT 10'
								,'return_type'  =>  'all'
						); 
		$EMPLOYEES = $dblms->getRows(EMPLOYEES, $condition);

		foreach ($EMPLOYEES AS $key => $val) {

			// CHECK FILE EXIST
			if($val['emply_gender'] == '2'){
				$photo = SITE_URL.'uploads/images/default_female.jpg';
			}else{            
				$photo = SITE_URL.'uploads/images/default_male.jpg';
			}

			if(!empty($val['emply_photo'])){
				$file_url   = SITE_URL.'uploads/images/employees/'.$val['emply_photo'];
				if (check_file_exists($file_url)) {
					$photo = $file_url;
				}
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
			$deg['type'] 				= 1;
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
								 'select' 		=>	'c.curs_id, c.curs_name, c.curs_photo, COUNT(DISTINCT ec.secs_id) as TotalStd, COUNT(DISTINCT l.id_week) duration, a.admoff_amount'
								,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type=3
													LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
													LEFT JOIN '.COURSES_LESSONS.' l ON l.id_curs = c.curs_id AND l.is_deleted = 0 AND l.lesson_status = 1
													LEFT JOIN '.ALLOCATE_TEACHERS.' AS ca ON ca.id_curs=c.curs_id'
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
			array_push($shortcourses, $curs);
		}


		$rowjson['home']['category']		= $categories;
		$rowjson['home']['banners']			= $data['home']['banners'];
		$rowjson['home']['diplomas']		= $diplomas;
		$rowjson['home']['instructors']		= $instructors;
		$rowjson['home']['onlinedegrees']	= $onlinedegrees;
		$rowjson['home']['shortcourses']	= $shortcourses;
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "my_enrolled_completed_courses_list") { 
		
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
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_bookmarks") { 

		$bookmarks	=   array();

		$condition = array (
								'select' 		=>	'w.wl_id, w.id_curs, w.id_mas, w.id_ad_prg, c.curs_name, c.curs_photo, c.curs_href, mt.mas_name, mt.mas_photo, mt.mas_href, ap.program, p.prg_name, p.prg_photo, p.prg_href'
								,'join' 		=>	'LEFT JOIN '.COURSES.' c ON c.curs_id = w.id_curs
													LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = w.id_mas
													LEFT JOIN '.PROGRAMS.' p ON p.prg_id = w.id_ad_prg
													LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = w.id_ad_prg' 
								,'where' 		=>	array( 
															'w.id_std'    => cleanvars($data_arr['user_id']) 
														) 
								,'order_by'     =>  'w.wl_id DESC'
								,'return_type'	=>	'all'
							); 
		$WISHLIST = $dblms->getRows(WISHLIST.' w', $condition);

		if(!empty($WISHLIST)):

			foreach ($WISHLIST as $row) {
				$photo = SITE_URL.'uploads/images/default_curs.jpg';
				if($row['prg_name']){
					$type       = '1';
					$href       = 'degree-detail/'.$row['prg_href'];
					$idWish     = $row['id_ad_prg'];
					$name       = $row['prg_name'];
					$duration   = $row['prg_duration']. ' Year';
					$price   	= 'Rs. '.number_format($row['prg_total_package']);
					$file_url   = SITE_URL.'uploads/images/programs/'.$row['prg_photo'];
				}elseif($row['mas_name']){
					$type       = '2';
					$href       = 'master-track-detail/'.$row['mas_href'];
					$idWish     = $row['id_mas'];
					$name       = $row['mas_name'];
					$duration   = $row['mas_duration']. ' Month';
					$price   	= 'Rs. '.number_format($row['mas_amount']);
					$file_url   = SITE_URL.'uploads/images/admissions/master_track/'.$row['mas_photo'];
				}elseif($row['curs_name']){
					$type       = '3';
					$href       = 'courses/'.$row['curs_href'];
					$idWish     = $row['id_curs'];
					$name       = $row['curs_name'];
					$duration   = $row['curs_duration']. ' Week';
					$price   	= 'Rs. '.number_format($row['admoff_amount']);
					$file_url   = SITE_URL.'uploads/images/courses/'.$row['curs_photo'];
				}
				if (check_file_exists($file_url)) {
					$photo = $file_url;
				}

				$dataBookmark['id_user']	= $data_arr['user_id'];
				$dataBookmark['id_type'] 	= $type;
				$dataBookmark['id'] 		= $idWish;
				$dataBookmark['name'] 		= $name;
				$dataBookmark['category'] 	= get_offering_type($type);
				$dataBookmark['rating'] 	= "4.".rand(0, 9);
				$dataBookmark['duration'] 	= $duration;
				$dataBookmark['price'] 		= $price;
				$dataBookmark['photo'] 		= $photo;

				array_push($bookmarks, $dataBookmark);
			}
		endif;
		$rowjson['userbookmarks']	= $bookmarks;
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_instructor_detials") { 
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
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "search") { 
		$saerch_results	= array();

		$condition = array ( 
								'select' 		    =>	'ap.id, ap.program, p.prg_name, p.prg_photo, mt.mas_name, mt.mas_photo, mt.mas_id, c.curs_id, c.curs_name, c.curs_photo'
								,'join'             =>  'LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON (ap.id = ao.admoff_degree AND ap.is_deleted = 0)
														 LEFT JOIN '.MASTER_TRACK.' mt ON (mt.mas_id = ao.admoff_degree AND mt.is_deleted = 0)
														 LEFT JOIN '.COURSES.' c ON (c.curs_id = ao.admoff_degree AND c.is_deleted = 0)
														 LEFT JOIN '.PROGRAMS.' p ON (p.prg_id  = ap.id_prg AND p.is_deleted = 0)'
								,'where' 		    =>	array( 
															'ao.is_deleted' 	    => '0'
															)
								,'search_by'        =>  ' AND (ap.program LIKE "%'.$data_arr['query'].'%" 
																		OR 
															mt.mas_name LIKE "%'.$data_arr['query'].'%" 
																		OR 
															c.curs_name LIKE "%'.$data_arr['query'].'%")'
								,'order_by'     =>  'ao.admoff_id'
								,'return_type'	=>	'all'
							);
		$ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING.' ao', $condition);

		foreach($ADMISSION_OFFERING AS $key => $val):
			$photo = SITE_URL.'uploads/images/default_curs.jpg';
			if($val['prg_name']){
				$type       = '1';
				$id         = $val['id'];
				$name       = $val['prg_name'];
				$file_url   = SITE_URL.'uploads/images/programs/'.$val['prg_photo'];
			}elseif($val['mas_name']){
				$type       = '2';
				$id         = $val['mas_id'];
				$name       = $val['mas_name'];
				$file_url   = SITE_URL.'uploads/images/admissions/master_track/'.$val['mas_photo'];
			}elseif($val['curs_name']){
				$type       = '3';
				$id       	= $val['curs_id'];
				$name       = $val['curs_name'];
				$file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
			}
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}

			$dataResult['id']			= $id;
			$dataResult['type'] 		= $type;
			$dataResult['type_name']	= get_offering_type($type);
			$dataResult['name'] 		= html_entity_decode($name);
			$dataResult['photo'] 		= $photo;

			array_push($saerch_results, $dataResult);
		endforeach;

		$rowjson['saerch_results']		= $saerch_results;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "user_login") { 
		
		if(!empty($data_arr['username']) && !empty($data_arr['password'])) {

			$adm_username = $data_arr['username'];
			$adm_userpass = $data_arr['password'];

			$loginconditions = array ( 
										 'select' 		=>	'a.*, s.std_id, s.std_level, s.std_gender, s.id_org, e.emply_request'
										,'join' 		=>	'LEFT JOIN '.STUDENTS.' s ON s.std_loginid = a.adm_id AND s.std_status = 1 AND s.is_deleted = 0
															 LEFT JOIN '.EMPLOYEES.' e ON e.id_added = a.adm_id AND e.emply_status = 1 AND e.is_deleted = 0'
										,'where' 		=>	array( 
																	 'a.adm_status'		=> '1'
																	,'a.is_deleted' 	=> '0'
																)
										,'search_by'	=>	' AND a.is_teacher IN (1,3) AND (a.adm_username = "'.$adm_username.'" OR a.adm_email = "'.$adm_username.'" )'
										,'return_type'	=>	'single'
			); 		
			$row = $dblms->getRows(ADMINS.' a', $loginconditions);
			if ($row) {

				// PASSWORD HASHING
				$salt 		= $row['adm_salt'];
				$password 	= hash('sha256', $adm_userpass . $salt);			
				for ($round = 0; $round < 65536; $round++) {
					$password = hash('sha256', $password . $salt);
				}
	
				if($password == $row['adm_userpass']) { 
					$dataLog = array(
										 'login_type'		=> cleanvars($row['adm_logintype'])
										,'id_login_id'		=> cleanvars($row['adm_id'])
										,'user_name'		=> cleanvars($row['adm_username'])
										,'user_pass'		=> cleanvars($adm_userpass)
										,'email'			=> cleanvars($row['adm_email'])
										,'id_campus'		=> cleanvars($row['id_campus'])
										,'dated'			=> date("Y-m-d G:i:s")
					);	
					$sqllmslog = $dblms->Insert(LOGIN_HISTORY , $dataLog);

					// CHECK FILE EXIST
					if($row['std_gender'] == '2'){
						$photo = SITE_URL.'uploads/images/default_female.jpg';
					}else{            
						$photo = SITE_URL.'uploads/images/default_male.jpg';
					}

					if(!empty($row['adm_photo'])){
						$photo = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
					}
				
					$rowjson['success'] 		= 1;
					$rowjson['MSG'] 			= $app_lang['login_success'];

					// from admin
					$rowjson['user_id'] 		= $row['adm_id'];
					$rowjson['user_type'] 		= $row['adm_type'];
					$rowjson['user_username']	= $row['adm_username'];
					$rowjson['user_email']		= $row['adm_email'];
					$rowjson['user_fullname']	= $row['adm_fullname'];
					$rowjson['user_phone']		= $row['adm_phone'];
					$rowjson['user_photo'] 		= $photo;

					// from std
					$rowjson['std_id'] 			= $row['std_id'];
					$rowjson['std_level']		= $row['std_level'];
					$rowjson['std_gender']		= $row['std_gender'];
					$rowjson['is_teacher']		= $row['is_teacher'];

					// if std request to become emply
					if (!empty($row['emply_request'])) {
						$rowjson['emply_request']	= $row['emply_request'];
					}
					if (!empty($row['id_org'])) {
						$rowjson['std_org']		= $row['id_org'];
					}
				} else {
					$rowjson['success'] = 0;	
					$rowjson['MSG'] 	= $app_lang['invalid_password'];
				}
				
			} else {
				$rowjson['success'] = 0;	
				$rowjson['MSG'] 	= $app_lang['email_not_found'];
			}	
			
		} else {

			$rowjson['success'] = 0;	
			$rowjson['MSG'] 	= $app_lang['check_parameters'];
		}
		
		$set['MCDL_SYSTEM'] = $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "user_signup") { 

		$condition	=	array ( 
									'select'		=> "adm_username"
									,'where'		=> array( 
																'adm_username'		=> cleanvars($data_arr['username'])
																,'is_deleted'		=> '0'
															)
									,'return_type'	=> 'count' 
								); 
		if($dblms->getRows(ADMINS, $condition)) {
			$rowjson['success'] = 0;	
			$rowjson['MSG'] 	= $app_lang['username_exist'];
		} else {
			$array 				= get_PasswordVerify($data_arr['password']);
			$std_password 		= $array['hashPassword'];
			$salt 				= $array['salt'];	

			$values = array(
								 'adm_status'		=> 1
								,'adm_type'			=> 3
								,'adm_logintype'	=> 3
								,'is_teacher'		=> 1
								,'adm_fullname'		=> cleanvars($data_arr['fullame'])
								,'adm_username'		=> cleanvars($data_arr['username'])
								,'adm_email'		=> cleanvars($data_arr['email'])
								,'adm_userpass'		=> cleanvars($std_password)
								,'adm_salt'			=> cleanvars($salt)
								,'adm_photo'		=> 'default.png'
								,'date_added'		=> date('Y-m-d G:i:s')
			);	

			$sqllms = $dblms->insert(ADMINS, $values);

			if($sqllms) { 
				
				$latestID   =	$dblms->lastestid();

				$stdValues = array(
									 'std_loginid'			=> $latestID
									,'std_status'			=> 1
									,'std_level'			=> 1
									,'std_name'				=> cleanvars($data_arr['fullame'])
									,'date_added'			=> date('Y-m-d G:i:s')
									,'id_added'				=> $latestID
				);

				$sqllms = $dblms->insert(STUDENTS, $stdValues);

				$rowjson['success'] 		= 1;
				$rowjson['MSG'] 			= $app_lang['register_success'];
				$rowjson['user_id'] 		= strval($latestID);
				$rowjson['user_type'] 		= "3";
				$rowjson['user_name'] 		= $data_arr['username'];
				$rowjson['user_fullname'] 	= $data_arr['fullame'];
				$rowjson['user_photo'] 		= SITE_URL.'uploads/images/default_male.jpg';
			}
		}
		
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_all_diplomas") { 
		
		$diplomas = array();

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
			$dpl['type'] 			= 2;
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
		$rowjson['diplomaslist']	= $diplomas;
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_all_online_degrees") { 
		
		$onlinedegrees = array();

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
			$deg['type'] 				= 1;
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
		$rowjson['onlinedegreeslist']	= $onlinedegrees;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_all_shortcourses") { 
		
		$shortcourses = array();

		// SHORT COURSES
		$condition = array ( 
								 'select' 		=>	'c.curs_id, c.curs_name, c.curs_photo, COUNT(DISTINCT ec.secs_id) as TotalStd, COUNT(DISTINCT l.id_week) duration, a.admoff_amount'
								,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type=3
													LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
													LEFT JOIN '.COURSES_LESSONS.' l ON l.id_curs = c.curs_id AND l.is_deleted = 0 AND l.lesson_status = 1
													LEFT JOIN '.ALLOCATE_TEACHERS.' AS ca ON ca.id_curs=c.curs_id'
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
			array_push($shortcourses, $curs);
		}
		$rowjson['shortcourseslist']	= $shortcourses;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_all_categories") { 
		
		$categories = array();

		// CATEGORIES
		$condition = array ( 
							'select'       =>  'cc.cat_id, cc.cat_name, cc.cat_href, cc.cat_icon, COUNT(a.admoff_degree) as TotalCrs'
							,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.id_cat = cc.cat_id AND a.admoff_type=3'
							,'where' 	      =>  array ( 
														'cc.is_deleted'    =>  0
														,'cc.cat_status'    =>  1
													)
							,'group_by'     =>  'a.id_cat'
							,'order_by'     =>  'RAND()'
							,'limit'        =>  8
							,'return_type'  =>  'all'
						); 
		$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES.' cc', $condition);

		foreach ($COURSES_CATEGORIES AS $key => $val) {

			// CHECK FILE EXIST
			$photo      = SITE_URL.'uploads/images/default_curs.jpg';
			$file_url   = SITE_URL.'uploads/images/courses/categories/icons/'.$val['cat_icon'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}

			$cat['id'] 			= intval($val['cat_id']);
			$cat['name'] 		= html_entity_decode($val['cat_name']);
			$cat['icon'] 		= $photo;
			array_push($categories, $cat);
		}
		$rowjson['categorieslist']		= $categories;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_all_instructors") { 
		
		$instructors = array();

		// INSTRUCTORS
		$condition = array ( 
								 'select'       =>  "DISTINCT e.emply_id, e.emply_photo, e.emply_name, e.emply_gender"
								,'join'			=>	'INNER JOIN '.ALLOCATE_TEACHERS.' AS at ON FIND_IN_SET(e.emply_id, at.id_teacher)'
								,'where' 	    =>    array( 
																 'e.emply_status'   => 1
																,'e.emply_request'  => 1
																,'e.is_deleted'    	=> 0
														)
								,'order_by'  	=>  'RAND() LIMIT 10'
								,'return_type'  =>  'all'
						); 
		$EMPLOYEES = $dblms->getRows(EMPLOYEES.' AS e', $condition);

		foreach ($EMPLOYEES AS $key => $val) {

			// CHECK FILE EXIST
			if($val['emply_gender'] == '2'){
				$photo = SITE_URL.'uploads/images/default_female.jpg';
			}else{            
				$photo = SITE_URL.'uploads/images/default_male.jpg';
			}

			if(!empty($val['emply_photo'])){
				$file_url   = SITE_URL.'uploads/images/employees/'.$val['emply_photo'];
				if (check_file_exists($file_url)) {
					$photo = $file_url;
				}
			}

			$ins['id'] 				= intval($val['emply_id']);
			$ins['name'] 			= $val['emply_name'];
			$ins['photo'] 			= $photo;
			array_push($instructors, $ins);
		}
		$rowjson['instructorslist']		= $instructors;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_short_course_detials") { 
		$shortcoursesDetail 	= array();
		$courses_dtl 			= array();
		$ins_dtl 				= array();
		$syllabus 				= array();
		$alltopics 				= array();
		$curs_wise 				= array();
		$topic 					= array();
		$faqs 					= array();

		if (!empty($data_arr['user_id'])) {
			$wishSql 	= ', w.wl_id as ifWishlist';
			$wishJoin 	= 'LEFT JOIN '.WISHLIST.' w ON w.id_curs = c.curs_id AND w.id_ad_prg IS NULL AND w.id_mas IS NULL AND w.id_std = "'.cleanvars($data_arr['user_id']).'"';
		} else {
			$wishSql 	= '';
			$wishJoin 	= '';
		}
		$condition = array ( 
								 'select'       =>	'COUNT(DISTINCT ca.id) as TotalAssignments, c.curs_wise, d.dept_name, ao.admoff_amount, c.curs_photo, c.curs_detail, c.curs_id, c.curs_name, c.how_it_work, c.curs_about, c.what_you_learn, COUNT(DISTINCT l.lesson_id) TotalLesson,  COUNT(DISTINCT ec.secs_id) as TotalStd '.$wishSql.', COUNT(DISTINCT l.id_week) duration'
								,'join'         =>  'LEFT JOIN '.COURSES_LESSONS.' l ON l.id_curs = c.curs_id AND l.is_deleted = 0 AND l.lesson_status = 1
													LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(c.curs_id, ec.id_curs)
													'.$wishJoin.'						
													LEFT JOIN '.COURSES_ASSIGNMENTS.' ca on ca.id_curs = c.curs_id AND ca.is_deleted = 0 AND ca.status = 1 
													LEFT JOIN '.ADMISSION_OFFERING.' ao on ao.admoff_degree = c.curs_id AND ao.is_deleted = 0
													LEFT JOIN '.DEPARTMENTS.' d on d.dept_id = c.id_dept'
								,'where' 		    =>	array(
																 'c.curs_status' 	=> 1
																,'c.is_deleted' 	=> 0
																,'c.curs_id' 	 	=> cleanvars($data_arr['short_course_id'])
													)
								,'return_type'	=>	'single'
            				); 
		$COURSES = $dblms->getRows(COURSES.' c', $condition);

		// ALLOCATED TEACHERS
		$condition = array ( 
								'select'       =>  'a.adm_photo, e.emply_name, e.emply_id'
								,'join'         =>  'INNER JOIN '.ALLOCATE_TEACHERS.' alt on FIND_IN_SET(e.emply_id,alt.id_teacher)
													 INNER JOIN '.ADMINS.' a ON a.adm_id = e.emply_loginid'
								,'where' 		    =>  array( 
																 'alt.id_curs' 	   	=> $COURSES['curs_id']
																,'e.emply_status'  	=> '1'  
																,'e.is_deleted' 	=> '0'  
															)
								,'group_by'     =>  'e.emply_id'
								,'return_type'	=>  'all'
							);
		$allocated_teachers = $dblms->getRows(EMPLOYEES.' e', $condition);
		foreach ($allocated_teachers AS $key => $val) {

			// CHECK FILE EXIST
			if($val['emply_gender'] == '2'){
				$photo = SITE_URL.'uploads/images/default_female.jpg';
			}else{            
				$photo = SITE_URL.'uploads/images/default_male.jpg';
			}

			if(!empty($val['adm_photo'])){
				$file_url   = SITE_URL.'uploads/images/admin/'.$val['adm_photo'];
				if (check_file_exists($file_url)) {
					$photo = $file_url;
				}
			}

			$ins['id'] 				= intval($val['emply_id']);
			$ins['name'] 			= $val['emply_name'];
			$ins['photo'] 			= $photo;
			$ins['followers'] 		= '1.2k Followers';
			$ins['rating'] 			= '4.8';
			array_push($ins_dtl, $ins);
		}

		foreach (get_LessonWeeks() as $key => $value) {
			// COURSE LESSONS
			$condition = array ( 
									 'select' 		    =>	'lesson_topic, lesson_detail, lesson_content'
									,'where' 		    =>	array( 
																	 'id_week' 	     => $key
																	,'id_curs' 	     => $COURSES['curs_id']
																	,'lesson_status' => 1
																	,'is_deleted' 	 => 0
															)
									,'return_type'	=>	'all'
								);
			$get_lesson = $dblms->getRows(COURSES_LESSONS, $condition);
			//COURSE ASSIGNMENTS
			$condition = array ( 
									 'select' 		    =>	'caption, detail'
									,'where' 		    =>	array( 
															 		 'id_week' 	     => $key
																	,'id_curs' 	     => $COURSES['curs_id']
																	,'status'        => 1
																	,'is_deleted' 	 => 0
															)
														
									,'order_by'     => 'id ASC'
									,'return_type'	=>	'all'
								);
			$get_assignments = $dblms->getRows(COURSES_ASSIGNMENTS, $condition);

			if ($get_lesson || $get_assignments) {
				$topic['topic_type'] = get_CourseWise($COURSES['curs_wise']).' '.$key;	
				foreach ($get_lesson as $inkey => $val) {
					$lesson['title'] 			= html_entity_decode(html_entity_decode($val['lesson_topic']));
					$lesson['detail'] 			= html_entity_decode(html_entity_decode($val['lesson_detail']));
					$lesson['type'] 			= ($val['lesson_content'] == 3 ? 'Video and Reading' : get_topic_content($val['lesson_content']));

					array_push($alltopics, $lesson);
				}
				foreach ($get_assignments as $inkey => $val) {
					$assignment['title'] 		= html_entity_decode(html_entity_decode($val['caption']));
					$assignment['detail'] 		= html_entity_decode(html_entity_decode($val['detail']));
					$assignment['type'] 		= 'Assignment';

					array_push($alltopics, $assignment);
				}
				$topic['topics'] = $alltopics;
				array_push($syllabus, $topic);
			}
		}


		// FAQ'S
		$condition = array ( 
								 'select'       =>	'question,answer'
								,'where' 		=>	array( 
															 'id_curs' 	    => $COURSES['curs_id']
															,'status' 	 	=> 1
															,'is_deleted' 	=> 0
													)
								,'return_type'	=>	'all'
						); 
		$get_faq = $dblms->getRows(COURSES_FAQS, $condition);
		if ($get_faq) {
			foreach ($get_faq as $key => $val) {
				$allfaq['question'] 	= $val['question'];
				$allfaq['answer'] 		= html_entity_decode(html_entity_decode($val['answer']));

				array_push($faqs, $allfaq);
			}
		}

		// CHECK FILE EXIST
		$photo      = SITE_URL.'uploads/images/default_curs.jpg';
		$file_url   = SITE_URL.'uploads/images/courses/'.$COURSES['curs_photo'];
		if (check_file_exists($file_url)) {
			$photo = $file_url;
		}

		
		$shortcoursesDetail['id'] 						= intval($data_arr['short_course_id']);
		$shortcoursesDetail['curs_photo'] 				= $photo;
		$shortcoursesDetail['dept_name'] 				= html_entity_decode(html_entity_decode($COURSES['dept_name']));
		$shortcoursesDetail['curs_price'] 				= 'Rs. '.number_format($COURSES['admoff_amount']);
		$shortcoursesDetail['curs_name'] 				= $COURSES['curs_name'];
		$shortcoursesDetail['wish_list_flag'] 			= boolval((!empty($COURSES['ifWishlist'])?true:false));
		$shortcoursesDetail['total_students'] 			= $COURSES['TotalStd'].' Student';
		$shortcoursesDetail['curs_rating'] 				= '4.8';
		$shortcoursesDetail['curs_duration'] 			= $COURSES['duration']. ' Week';
		$shortcoursesDetail['total_lessons'] 			= $COURSES['TotalLesson'];
		$shortcoursesDetail['curs_about'] 				= html_entity_decode(html_entity_decode($COURSES['curs_detail']));
		$shortcoursesDetail['how_it_works'] 			= json_decode(html_entity_decode($COURSES['what_you_learn']), true);
		$shortcoursesDetail['instructor'] 				= $ins_dtl;
		$shortcoursesDetail['syllabus'] 				= $syllabus;
		$shortcoursesDetail['enrollment'] 				= html_entity_decode(html_entity_decode($data['enrollment']));
		$shortcoursesDetail['faqs'] 					= $faqs;


		$rowjson['short_course_detial']	= $shortcoursesDetail;
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "get_categories_courses") { 
		
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
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_diploma_detials") {
		$allEmply 		= array();
		$allCourses 	= array();
		$allFaq 		= array();

		if (!empty($data_arr['user_id'])) {
			$wishSql 	= ', w.wl_id as ifWishlist';
			$wishJoin 	= 'LEFT JOIN '.WISHLIST.' w ON w.id_mas = mt.mas_id AND w.id_ad_prg IS NULL AND w.id_std = '.cleanvars($data_arr['user_id']).'';
		} else {
			$wishSql 	= '';
			$wishJoin 	= '';
		}
		// MASTER TRACK CERTIFICATE DETAIL
		$condition = array ( 
								 'select'   =>	'mt.mas_photo, mt.mas_duration, mt.mas_detail, mt.mas_name, COUNT(DISTINCT ec.secs_id) as TotalStd '.$wishSql.', ao.admoff_amount, GROUP_CONCAT(DISTINCT mtd.id_curs) AS id_curs, mtc.mstcat_name'
								,'join'     =>  'INNER JOIN '.MASTER_TRACK_CATEGORIES.' mtc ON mtc.mstcat_id = mt.id_mstcat
												LEFT JOIN '.ENROLLED_COURSES.' ec ON FIND_IN_SET(mt.mas_id, ec.id_curs)
												LEFT JOIN '.MASTER_TRACK_DETAIL.' mtd ON mtd.id_mas = mt.mas_id
												LEFT JOIN '.ADMISSION_OFFERING.' ao on ao.admoff_degree = mt.mas_id AND ao.admoff_type = 2 AND ao.is_deleted = 0
												'.$wishJoin.''
								,'where' 		=>	array( 
														 	 'mt.mas_id'      	=> cleanvars($data_arr['diploma_id'])
															,'mt.is_deleted'    => '0'  
													)
								,'return_type'	=>	'single'
							); 
		$DIPLOMA = $dblms->getRows(MASTER_TRACK.' mt', $condition);

		$file_url = SITE_URL.'uploads/images/admissions/master_track/'.$DIPLOMA['mas_photo'];
		if (check_file_exists($file_url)) {
			$photo = $file_url;
		} else {
			$photo = SITE_URL.'uploads/images/default_curs.jpg';
		}

		$condition = array ( 
								 'select'       =>	'DISTINCT e.emply_name, e.emply_email, e.emply_photo'
								,'join'         =>  'INNER JOIN '.EMPLOYEES.' AS e ON FIND_IN_SET(e.emply_id, at.id_teacher) AND e.is_deleted = 0 AND e.emply_status = 1'
								,'where' 		=>	array()
								,'search_by'	=>	' at.id_curs IN ('.$DIPLOMA['id_curs'].')'
								,'return_type'	=>	'all'
							); 
		$EMPLOYEES = $dblms->getRows(ALLOCATE_TEACHERS.' AS at', $condition);
		foreach ($EMPLOYEES AS $key => $val) {
			$file_url = SITE_URL.'uploads/images/admin/'.$val['emply_photo'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			} else {
				$photo = SITE_URL.'uploads/images/default_curs.jpg';
			}

			$emply['emply_photo'] 	= $photo;
			$emply['emply_name'] 	= $val['emply_name'];
			$emply['emply_email'] 	= $val['emply_email'];
			$emply['emply_rating'] 	= '4.5';

			array_push($allEmply, $emply);
		}

		$condition = array ( 
								 'select'   	=>	' DISTINCT c.curs_name, c.curs_detail'
								,'where' 		=>	array( 
														 	 'c.curs_status'   => 1
															,'c.is_deleted'    => 0
													)
								,'search_by'	=>	' AND c.curs_id IN ('.$DIPLOMA['id_curs'].')'
								,'return_type'	=>	'all'
							); 
		$COURSES = $dblms->getRows(COURSES.' AS c', $condition);
		foreach ($COURSES AS $key => $val) {
			$courses['curs_name'] 		= $val['curs_name'];
			$courses['curs_detail'] 	= html_entity_decode(html_entity_decode($val['curs_detail']));

			array_push($allCourses, $courses);
		}

		$condition = array ( 
								 'select'   	=>	' DISTINCT q.question, q.answer'
								,'where' 		=>	array( 
														 	 'q.status'   => 1
															,'q.is_deleted'    => 0
													)
								,'search_by'	=>	' AND q.id_curs IN ('.$DIPLOMA['id_curs'].')'
								,'return_type'	=>	'all'
							); 
		$COURSES_FAQS = $dblms->getRows(COURSES_FAQS.' AS q', $condition);
		foreach ($COURSES_FAQS AS $key => $val) {
			$faq['question']				= html_entity_decode(html_entity_decode($val['question']));
			$faq['answer']					= html_entity_decode(html_entity_decode($val['answer']));

			array_push($allFaq, $faq);
		}



		$diploma_detial['mas_id'] 			= intval($data_arr['diploma_id']);
		$diploma_detial['mas_photo'] 		= $photo;
		$diploma_detial['mas_price'] 		= 'Rs. '.number_format($DIPLOMA['admoff_amount']);
		$diploma_detial['mas_name'] 		= html_entity_decode(html_entity_decode($DIPLOMA['mas_name']));
		$diploma_detial['mstcat_name'] 		= html_entity_decode(html_entity_decode($DIPLOMA['mstcat_name']));
		$diploma_detial['mas_student'] 		= $DIPLOMA['TotalStd'].' Student';
		$diploma_detial['mas_duration'] 	= $DIPLOMA['mas_duration'].' Months';
		$diploma_detial['mas_rating'] 		= '4.5';
		$diploma_detial['wish_list_flag'] 	= boolval((!empty($DIPLOMA['ifWishlist'])?true:false));
		$diploma_detial['mas_detail'] 		= html_entity_decode(html_entity_decode($DIPLOMA['mas_detail']));
		$diploma_detial['mas_emply'] 		= $allEmply;
		$diploma_detial['mas_courses'] 		= $allCourses;
		$diploma_detial['mas_testimonials'] = $data['testimonials'];
		$diploma_detial['mas_reviews'] 		= $data['reviewslist'];
		$diploma_detial['mas_faq'] 			= $allFaq;


	
		$rowjson['master_track_detail']		= $diploma_detial;
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "get_online_degree_detials") { 
		$allacademics 		= array();
		$academics 			= array();
		$careers 			= array();
		$core_courses		= array();
		$foundation_courses	= array();
		$elective_courses	= array();
		$admissions 		= array();
		// PROGRAM DETAIL
		$condition = array ( 
								 'select'       =>	'ap.id, p.prg_name, p.prg_photo,ap.programme_length , ap.detail, ap.prg_for, ap.apply_enroll, ap.cohorts_deadlines, ap.eligibility_criteria, ap.class_profile, p.prg_semesters, ap.payment_per_smester, p.prg_payments_per_semester, ap.total_payments, ap.total_package, p.prg_admission_fee, ap.examination_fee, ap.portal_fee, ap.library_fee, ap.library_mag_fee, ap.alumni_benefits, ap.career_outcomes'
								,'join'         =>  'INNER JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg'
								,'where' 		    =>	array( 
																 'ap.is_deleted'    =>  0  
																,'ap.id_prg'    	=>  cleanvars($data_arr['degree_id'])
														)
								,'return_type'	=>	'single'
						); 
		$DEGREE = $dblms->getRows(ADMISSION_PROGRAMS.' ap', $condition);	

		$admissions['Who_is_this_degree_for']		= html_entity_decode(html_entity_decode($DEGREE['prg_for']));
		$admissions['how_to_apply_and_enrol']		= html_entity_decode(html_entity_decode($DEGREE['apply_enroll']));
		$admissions['cohorts_and_deadlines']		= html_entity_decode(html_entity_decode($DEGREE['cohorts_deadlines']));
		$admissions['eligibility_criteria']			= html_entity_decode(html_entity_decode($DEGREE['eligibility_criteria']));
		$admissions['class_profile']				= html_entity_decode(html_entity_decode($DEGREE['class_profile']));

		// ACADEMICS
		$academics['programme_length'] = html_entity_decode(html_entity_decode($DEGREE['programme_length']));
        $condition = array ( 
								 'select'       =>	'id_curs, id_curstype'
								,'where'        =>	array( 
															'id_ad_prg'   =>  cleanvars($DEGREE['id'])
													)
								,'return_type'	=>	'all'
							); 
		$scheme = $dblms->getRows(PROGRAMS_STUDY_SCHEME, $condition);
		foreach ($scheme as $key => $value) {
			$allCurs 	= array();
			$condition 	= array ( 
									 'select' 		=>	'curs_code, curs_name'
									,'where' 		=>	array( 
																 'curs_status' 	=> 1
																,'is_deleted' 	=> 0
															)
									,'search_by'	=>	' AND curs_id IN ('.$value['id_curs'].')'
									,'return_type'	=>	'all'
								); 
			$COURSES 	= $dblms->getRows(COURSES, $condition, $sql);
			foreach ($COURSES as $inkey => $invalue) {
				$curs['curs_code'] 	= $invalue['curs_code'];
				$curs['curs_name'] 	= $invalue['curs_name'];

				if ($value['id_curstype']==1) {
					array_push($core_courses, $curs);
				} else if ($value['id_curstype']==2) {
					array_push($foundation_courses, $curs);
				} else {
					array_push($elective_courses, $curs);
				}
			}
		}
		$academics['core_courses'] 					= $core_courses;
		$academics['foundation_courses'] 			= $foundation_courses;
		$academics['elective_courses'] 				= $elective_courses;

		// FINANCE
		$tuition_fee['semesters'] 					= $DEGREE['prg_semesters'];
		$tuition_fee['fee_semester'] 				= $DEGREE['payment_per_smester'];
		$tuition_fee['payments_semester'] 			= $DEGREE['prg_payments_per_semester'];
		$tuition_fee['total_no_of_payments'] 		= $DEGREE['total_payments'].' x '.$DEGREE['payment_per_smester']/$DEGREE['prg_payments_per_semester'];
		$tuition_fee['total_package'] 				= $DEGREE['total_package'];
		$allfinance['tuition_fee'] 					= $tuition_fee;

		$additional_charges['addmission_fee'] 		= $DEGREE['prg_admission_fee'];
		$additional_charges['examination_fee'] 		= $DEGREE['examination_fee'];
		$additional_charges['portal_fee'] 			= $DEGREE['portal_fee'];
		$additional_charges['library_fee'] 			= $DEGREE['library_fee'];
		$additional_charges['student_crad_fee'] 	= $DEGREE['library_mag_fee'];
		$additional_charges['library_magzine_fee'] 	= '';
		$allfinance['additional_charges'] 			= $additional_charges;

		// CAREERS
		$allcareers['title'] 	= 'Career Outcomes';
		$allcareers['detail'] 	= html_entity_decode(html_entity_decode($DEGREE['career_outcomes']));
		array_push($careers, $allcareers);
		$allcareers['title'] 	= 'Aliumni Benefits';
		$allcareers['detail'] 	= html_entity_decode(html_entity_decode($DEGREE['alumni_benefits']));
		array_push($careers, $allcareers);


		// CHECK FILE EXIST
		$photo      = SITE_URL.'uploads/images/default_curs.jpg';
		$file_url   = SITE_URL.'uploads/images/programs/'.$DEGREE['prg_photo'];
		if (check_file_exists($file_url)) {
			$photo = $file_url;
		}

		$degreedetail['id'] 				= intval($data_arr['degree_id']);
		$degreedetail['deg_photo'] 			= $photo;
		$degreedetail['org_name'] 			= 'Minhaj University Lahore';
		$degreedetail['prg_name'] 			= $DEGREE['prg_name'];
		$degreedetail['overview'] 			= html_entity_decode(html_entity_decode($DEGREE['detail']));
		$degreedetail['admissions'] 		= $admissions;
		$degreedetail['academics'] 			= $academics;
		$degreedetail['financing'] 			= $allfinance;
		$degreedetail['careers'] 			= $careers;
		$degreedetail['careers'] 			= $careers;
		$degreedetail['reviews'] 			= $data['reviewslist'];


		$rowjson['degree_detial']		= $degreedetail;
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "get_faqs") {
		$allFaq = array();
		$condition = array ( 
								 'select'   	=>	' DISTINCT q.question, q.answer'
								,'where' 		=>	array( 
														 	 'q.status'   		=> 1
															,'q.is_deleted'    	=> 0
													)
								,'order_by'		=>	' RAND() LIMIT 5'
								,'return_type'	=>	'all'
							); 
		$COURSES_FAQS = $dblms->getRows(COURSES_FAQS.' AS q', $condition);
		foreach ($COURSES_FAQS AS $key => $val) {
			$faq['question']				= html_entity_decode(html_entity_decode($val['question']));
			$faq['answer']					= html_entity_decode(html_entity_decode($val['answer']));

			array_push($allFaq, $faq);
		}

		$rowjson['faqs']					= $allFaq;
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "get_about") {

		$rowjson['about']					= html_entity_decode(html_entity_decode(SITE_ABOUT));
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 
	
	elseif($data_arr['method_name'] == "add_to_bookmarks") {
		$wishList = array();

		if ($data_arr['wish_list_flag'] == true) {
			// ADD WISHLIST
			$conWish = array(
								 'select'       =>  'wl_id'
								,'where'        =>  array(
															'id_std'    =>   cleanvars($data_arr['user_id']) 
														)
								,'return_type'  =>  'count'
							);
			
			// CHECK WISHLIST
			$conEnroll = array(
								 'select'       =>  'secs_id'
								,'where'        =>  array(
															 'id_std'       =>   cleanvars($data_arr['user_id']) 
															,'is_deleted'   =>  '0'
														)
								,'return_type'  =>  'count'
							);
			// 1 = PROGRAM, 2 = MASTER TRACK, 3 = COURSE
			if ($data_arr['wish_list_type'] == 3){
				$conWish['where']['id_curs']        = cleanvars($data_arr['wish_list_id']);
				$conEnroll['where']['id_curs']      = cleanvars($data_arr['wish_list_id']);
			} else if ($data_arr['wish_list_type'] == 2){
				$conWish['where']['id_mas']         = cleanvars($data_arr['wish_list_id']);
				$conEnroll['where']['id_mas']       = cleanvars($data_arr['wish_list_id']);
			} else if ($data_arr['wish_list_type'] == 1){
				$conWish['where']['id_ad_prg']      = cleanvars($data_arr['wish_list_id']);
				$conEnroll['where']['id_ad_prg']    = cleanvars($data_arr['wish_list_id']);
			}

			$WISHLIST 			= $dblms->getRows(WISHLIST, $conWish);
			$ENROLLED_COURSES 	= $dblms->getRows(ENROLLED_COURSES, $conEnroll);
	
			if ($WISHLIST || $ENROLLED_COURSES) {
				if ($WISHLIST) {
					$wishList['success'] 	= intval(0);	
					$wishList['MSG'] 		= $app_lang['already_exist'];
				}
				if ($ENROLLED_COURSES){
					$wishList['success'] 	= intval(0);	
					$wishList['MSG'] 		= $app_lang['already_enrolled'];
				}
			} else {
				$values = array(
									'id_std'   => cleanvars($data_arr['user_id'])            
								);
								
				// 1 = PROGRAM, 2 = MASTER TRACK, 3 = COURSE
				if($data_arr['wish_list_type'] == 3){
					$values['id_curs'] = cleanvars($data_arr['wish_list_id']);
				} else if ($data_arr['wish_list_type'] == 2){
					// GET COURES
					$condition = array(
											 'select'       =>  'GROUP_CONCAT(id_curs) courses'
											,'where'        =>  array(
																		'id_mas'    =>   cleanvars($data_arr['wish_list_id'])
																	)
											,'return_type'  =>  'single'
										);
					$MASTER_TRACK_DETAIL = $dblms->getRows(MASTER_TRACK_DETAIL, $condition);
					// ALTER VALUES
					$values['id_curs']  = cleanvars($MASTER_TRACK_DETAIL["courses"]);
					$values['id_mas']   = cleanvars($data_arr['wish_list_id']);
				} else if ($data_arr['wish_list_type'] == 1){
					// GET COURES
					$condition = array(
											 'select'       =>  'GROUP_CONCAT(id_curs) courses'
											,'where'        =>  array(
																		'id_ad_prg'    =>   cleanvars($data_arr['wish_list_id'])
																	)
											,'return_type'  =>  'single'
										);
					$PROGRAMS_STUDY_SCHEME = $dblms->getRows(PROGRAMS_STUDY_SCHEME, $condition);
					// ALTER VALUES
					$values['id_curs']      = cleanvars($PROGRAMS_STUDY_SCHEME["courses"]);
					$values['id_ad_prg']    = cleanvars($data_arr['wish_list_id']);
				}
	
				$sqllms = $dblms->Insert(WISHLIST, $values);
				if ($sqllms) {
					$wishList['success'] 	= intval(1);	
					$wishList['MSG'] 		= $app_lang['add_success'];
				}
			}
		} else {			
			// DELETE FROM WISHLIST
			// 1 = PROGRAM, 2 = MASTER TRACK, 3 = COURSE
			if ($data_arr['wish_list_type'] == 3){
				$delSql = ' id_curs = '.cleanvars($data_arr['wish_list_id']).' AND id_std = '.cleanvars($data_arr['user_id']).' ';
			} else if ($data_arr['wish_list_type'] == 2){
				$delSql = ' id_mas = '.cleanvars($data_arr['wish_list_id']).' AND id_std = '.cleanvars($data_arr['user_id']).' ';
			} else if ($data_arr['wish_list_type'] == 1){
				$delSql = ' id_ad_prg = '.cleanvars($data_arr['wish_list_id']).' AND id_std = '.cleanvars($data_arr['user_id']).' ';
			} else {
				$delSql = '';
			}
			$sql = $dblms->querylms('DELETE FROM '.WISHLIST.' WHERE '.$delSql.'');
			if ($sql) {
				$wishList['success'] 	= intval(1);	
				$wishList['MSG'] 		= $app_lang['delete_success'];
			}
		}

		$rowjson							= $wishList;
		$set['MCDL_SYSTEM'] 				= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	} 

	elseif($data_arr['method_name'] == "get_learn_panel") {
		$my_course 						= array();

		$my_course['coursesinfo'] 		= $datalearn['coursesinfo'];
		$my_course['faqs'] 				= $datalearn['faqs'];
		$my_course['modules'] 		 	= $datalearn['modules'];

		$rowjson['my_course']			= $my_course;
		$set['MCDL_SYSTEM'] 			= $rowjson;

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	
	elseif($data_arr['method_name'] == "get_quiz") { 
		
		$rowjson['coursequiz']		= $data['coursequiz'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	elseif($data_arr['method_name'] == "get_splashbanners") { 
		
		$array = $data['splashbanners']['list'];
		$rowjson['splashbanners']	=  $array[array_rand($array, 1)];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 
	
	else if($data_arr['method_name'] == "client_communication") {	
		include_once('clients/client_communication.php');
	} else {
		$get_method = checkSignSalt($data_arr['data']);
	}	
}
?>