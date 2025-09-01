<?php
if($data_arr['method_name'] == "get_all_online_degrees") { 
		
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

	} 