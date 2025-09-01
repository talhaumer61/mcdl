<?php
if($data_arr['method_name'] == "get_diploma_detials") {
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
	} 