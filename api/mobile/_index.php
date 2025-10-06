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

//------------------------------------------------

	if($data_arr['method_name'] == "get_home") { 
		
		$rowjson['home']		= $data['home'];
		$set['MCDL_SYSTEM'] 	= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} else if($data_arr['method_name'] == "get_categories") { 
		
		$rowjson['catgeotylist']	= $data['category']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "user_login") { 
		
		if($data_arr['username'] != 'shahzad.ahmad@mul.edu.pk' || $data_arr['password'] != '123456789') {
			
			$rowjson['success'] = 0;	
			$rowjson['MSG'] 	= $app_lang['email_not_found'];
			
		} else {
		
			$rowjson['success'] 		= 1;
			$rowjson['MSG'] 			= $app_lang['login_success'];
			$rowjson['user_id'] 		= 1;
			$rowjson['user_type'] 		= 3;
			$rowjson['user_name'] 		= 'shahzad.ahmad@mul.edu.pk';
			$rowjson['user_password'] 	= '123456789';
			$rowjson['user_fullname'] 	= 'Shahzad Ahmad';
			$rowjson['user_photo'] 		= "https://mcdl.mul.edu.pk/uploads/images/shahzad-ahmad.jpg";
		}
		
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_categorycourses") { 
		
		$rowjson['categorycourses']	= $data['categorycourses']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_instructors") { 
		
		$rowjson['instructorslist']	= $data['instructors']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_instructorcourses") { 
		
		$rowjson['instructorcourses']	= $data['instructorcourses']['list'];
		$set['MCDL_SYSTEM'] 			= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_bookmarks") { 
		
		$rowjson['userbookmarks']	= $data['bookmarks']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_shortcourses") { 
		
		$rowjson['shortcourseslist']= $data['shortcourses']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_diplomas") { 
		
		$rowjson['diplomaslist']	= $data['diplomas']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_singlediplomadetail") { 
		
		$rowjson['signlediploma']	= $data['signlediploma']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_singlecoursedetail") { 
		
		$rowjson['signlecourse']	= $data['signlecourse']['list'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_mycourse") { 
		
		$rowjson['mycourse']		= $data['mycourse'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} 



	// CREATED BY MY
	elseif($data_arr['method_name'] == "get_my_ongoing_courses") { 
		$conditions = array ( 
								 'select'        =>	'c.curs_name, c.curs_photo, c.curs_id, c.curs_href, ec.secs_id, ec.id_mas, ec.id_ad_prg
													,COUNT(DISTINCT cl.lesson_id) AS lesson_count
													,COUNT(DISTINCT ca.id) AS assignment_count
													,COUNT(DISTINCT cq.quiz_id) AS quiz_count
													,COUNT(DISTINCT lt.track_id) AS track_count'
								,'join'         =>	'INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
													LEFT JOIN '.COURSES_LESSONS.' AS cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
													LEFT JOIN '.COURSES_ASSIGNMENTS.' AS ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
													LEFT JOIN '.QUIZ.' AS cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
													LEFT JOIN '.LECTURE_TRACKING.' AS lt ON lt.id_curs = c.curs_id AND lt.id_std = '.cleanvars($data_arr['id_std']).' AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg'
								,'where' 		=>	array( 
															 'ec.is_deleted'    => '0'
															,'ec.secs_status'   => '1' 
															,'ec.id_ad_prg'     => '0' 
															,'ec.id_mas'        => '0' 
															,'ec.id_std' 	    => cleanvars($data_arr['id_std']) 
														)
								,'group_by'	    =>	'c.curs_id'
								,'return_type'	=>	'all'
							); 
		$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);
		$array = array();
		foreach($ENROLLED_COURSES AS $key => $val) {
			$countA = count($array);

			$photo      = SITE_URL.'uploads/images/default_curs.jpg';
			$file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}

			$array[$countA]['curs_photo'] 					= $photo;
			$array[$countA]['curs_name'] 					= $val['curs_name'];
			$array[$countA]['curs_id'] 						= $val['curs_id'];
			$array[$countA]['curs_href'] 					= $val['curs_href'];
			$array[$countA]['secs_id'] 						= $val['secs_id'];
			$array[$countA]['id_mas'] 						= $val['id_mas'];
			$array[$countA]['id_ad_prg'] 					= $val['id_ad_prg'];
			$array[$countA]['curs_total_percentage'] 		= 100;
			$array[$countA]['curs_obtain_percentage'] 		= intval(((($val['lesson_count']+$val['assignment_count']+$val['quiz_count'])/$val['track_count'])*100));
		}
		$rowjson['my_ongoing_courses']		= $array;
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	elseif($data_arr['method_name'] == "get_my_completed_courses") { 
		$conditions = array ( 
								 'select'        =>	'c.curs_name, c.curs_photo, c.curs_id, c.curs_href, ec.secs_id, ec.id_mas, ec.id_ad_prg
													,COUNT(DISTINCT cl.lesson_id) AS lesson_count
													,COUNT(DISTINCT ca.id) AS assignment_count
													,COUNT(DISTINCT cq.quiz_id) AS quiz_count
													,COUNT(DISTINCT lt.track_id) AS track_count'
								,'join'         =>	'INNER JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
													LEFT JOIN '.COURSES_LESSONS.' AS cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
													LEFT JOIN '.COURSES_ASSIGNMENTS.' AS ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
													LEFT JOIN '.QUIZ.' AS cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1
													LEFT JOIN '.LECTURE_TRACKING.' AS lt ON lt.id_curs = c.curs_id AND lt.id_std = '.cleanvars($data_arr['id_std']).' AND lt.is_completed = 2 AND lt.is_deleted = 0 AND lt.id_mas = ec.id_mas AND lt.id_ad_prg = ec.id_ad_prg'
								,'where' 		=>	array( 
															 'ec.is_deleted'    => '0'
															,'ec.secs_status'   => '1' 
															,'ec.id_ad_prg'     => '0' 
															,'ec.id_mas'        => '0' 
															,'ec.id_std' 	    => cleanvars($data_arr['id_std']) 
														)
								,'group_by'	    =>	'c.curs_id'
								,'return_type'	=>	'all'
							); 
		$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);
		$array = array();
		foreach($ENROLLED_COURSES AS $key => $val) {
			$countA = count($array);

			$photo      = SITE_URL.'uploads/images/default_curs.jpg';
			$file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}

			$array[$countA]['curs_photo'] 					= $photo;
			$array[$countA]['curs_name'] 					= $val['curs_name'];
			$array[$countA]['curs_id'] 						= $val['curs_id'];
			$array[$countA]['curs_href'] 					= $val['curs_href'];
			$array[$countA]['secs_id'] 						= $val['secs_id'];
			$array[$countA]['id_mas'] 						= $val['id_mas'];
			$array[$countA]['id_ad_prg'] 					= $val['id_ad_prg'];
			$array[$countA]['curs_total_percentage'] 		= 100;
			$array[$countA]['curs_obtain_percentage'] 		= 100;
		}
		$rowjson['my_completed_courses']		= $array;
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	elseif($data_arr['method_name'] == "get_categories_courses") { 
		$condition = array ( 
								'select'        =>  'curs_id,curs_status,curs_wise,curs_name,curs_icon,curs_photo,curs_code,curs_keyword,cat_name,faculty_name,dept_name'
								,'where' 	    =>  array( 
															 'c.is_deleted'    	=> 0
															,'c.curs_status'    => 1
															,'c.id_cat'    		=> cleanvars($data_arr['id_cat'])
														)
								,'join'         =>  '
														INNER JOIN '.COURSES_CATEGORIES.' cs ON id_cat = cat_id
														INNER JOIN '.FACULTIES.' ON id_faculty = faculty_id
														INNER JOIN '.DEPARTMENTS.' ON id_dept = dept_id
														INNER JOIN '.ADMISSION_OFFERING.' ON id_dept = dept_id
													'
								,'return_type'  =>  'all' 
							);
		$COURSES = $dblms->getRows(COURSES .' c', $condition);
		$array = array();
		foreach($COURSES AS $key => $val) {
			$countA = count($array);
			$photo      = SITE_URL.'uploads/images/default_curs.jpg';
			$file_url   = SITE_URL.'uploads/images/courses/'.$val['curs_photo'];
			if (check_file_exists($file_url)) {
				$photo = $file_url;
			}
			$array[$countA]['curs_id'] 			= $val['curs_id'];
			$array[$countA]['curs_photo'] 		= $photo;
			$array[$countA]['curs_icon'] 		= $val['curs_icon'];
			$array[$countA]['curs_wise'] 		= get_CourseWise($val['curs_wise']);
			$array[$countA]['curs_name'] 		= $val['curs_name'];
			$array[$countA]['curs_code'] 		= $val['curs_code'];
			$array[$countA]['curs_keyword'] 	= $val['curs_keyword'];
			$array[$countA]['cat_name'] 		= $val['cat_name'];
			$array[$countA]['faculty_name'] 	= $val['faculty_name'];
			$array[$countA]['dept_name'] 		= $val['dept_name'];
		}
		$rowjson['categories_courses']		= $array;
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	// CREATED BY MY END



	elseif($data_arr['method_name'] == "get_quiz") { 
		
		$rowjson['coursequiz']		= $data['coursequiz'];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} elseif($data_arr['method_name'] == "get_splashbanners") { 
		
		$array = $data['splashbanners']['list'];
		$rowjson['splashbanners']	=  $array[array_rand($array, 1)];
		$set['MCDL_SYSTEM'] 		= $rowjson;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	} else if($data_arr['method_name'] == "client_communication") {
	//-----------------------------------------------
		include_once('clients/client_communication.php');	
	//--------------------------------------------------------------
	} else {
		$get_method = checkSignSalt($data_arr['data']);
	}	
}
?>