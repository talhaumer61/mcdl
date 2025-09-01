<?php
if($data_arr['method_name'] == "get_all_diplomas") { 
		
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

	} 