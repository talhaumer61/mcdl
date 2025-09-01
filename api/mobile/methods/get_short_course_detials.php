<?php
if($data_arr['method_name'] == "get_short_course_detials") { 
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
	} 