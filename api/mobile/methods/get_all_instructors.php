<?php

if($data_arr['method_name'] == "get_all_instructors") { 
		
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

	} 