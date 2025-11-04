<?php
if ($data_arr['method_name'] == "add_to_wishlist") {
	if(isset($data_arr['std_id']) && $data_arr['std_id'] > 0){
		// CHECK USER EXISTANCE
		$conStd = array(
                             'select'       =>  'std_id'
                            ,'where'        =>  array(
                                                         'std_id'		=>	cleanvars($data_arr['std_id']) 
														,'std_status'	=>	1
														,'is_deleted'	=>	0
                                                    )
                            ,'return_type'  =>  'count'
                        );
		if($dblms->getRows(STUDENTS, $conStd)){
			// ADD TO WISHLIST
			if($data_arr['is_added'] == true){
				// CHECK WISHLIST
				$conWish = array(
									'select'       =>  'wl_id'
									,'where'        =>  array(
																'id_std'    =>	cleanvars($data_arr['std_id']) 
															)
									,'return_type'  =>  'count'
								);
				// CHECK ENROLLMENT
				$conEnroll = array(
									'select'       =>  'secs_id'
									,'where'        =>  array(
																 'id_std'       =>   cleanvars($data_arr['std_id']) 
																,'is_deleted'   =>  '0'
															)
									,'search_by'    =>  ' AND secs_status IN (1,2)'
									,'return_type'  =>  'count'
								);
				// 1 = PROGRAM, 2 = MASTER TRACK, 3 = COURSE, 4 = e-Trainings
				if($data_arr['type'] == 1){
					$conWish['where']['id_ad_prg']      = cleanvars($data_arr["id"]);
					$conEnroll['where']['id_ad_prg']    = cleanvars($data_arr["id"]);
				}        
				elseif($data_arr['type'] == 2){
					$conWish['where']['id_mas']         = cleanvars($data_arr["id"]);
					$conEnroll['where']['id_mas']       = cleanvars($data_arr["id"]);
				}
				else if($data_arr['type'] == 3 || $data_arr['type'] == 4){
					$conWish['where']['id_curs']        = cleanvars($data_arr["id"]);
					$conEnroll['where']['id_curs']      = cleanvars($data_arr["id"]);
				}

				if ($dblms->getRows(WISHLIST, $conWish)) {
					$rowjson['success'] 		= 0;
					$rowjson['MSG'] 			= 'Already in wishlist';
				} elseif ($dblms->getRows(ENROLLED_COURSES, $conEnroll)) {
					$rowjson['success'] 		= 0;
					$rowjson['MSG'] 			= 'Already enrolled';
				} else {
					$values = array(
										 'id_std'   => cleanvars($data_arr['std_id'])     
										,'id_type'  => cleanvars($data_arr["type"])
									);
					// 1 = PROGRAM, 2 = MASTER TRACK, 3|4 = COURSE|TRAINING
					if($data_arr['type'] == 1){
						// GET COURES
						$condition = array(
												 'select'       =>  'GROUP_CONCAT(id_curs) courses'
												,'where'        =>  array(
																			'id_ad_prg'    =>	cleanvars($data_arr["id"])
																		)
												,'return_type'  =>  'single'
											);
						$PROGRAMS_STUDY_SCHEME = $dblms->getRows(PROGRAMS_STUDY_SCHEME, $condition);
						// ALTER VALUES
						$values['id_curs']      = cleanvars($PROGRAMS_STUDY_SCHEME["courses"]);
						$values['id_ad_prg']    = cleanvars($data_arr["id"]);
					}
					elseif($data_arr['type'] == 2){
						// GET COURES
						$condition = array(
												 'select'       =>  'GROUP_CONCAT(id_curs) courses'
												,'where'        =>  array(
																			'id_mas'    =>   cleanvars($data_arr["id"])
																		)
												,'return_type'  =>  'single'
											);
						$MASTER_TRACK_DETAIL = $dblms->getRows(MASTER_TRACK_DETAIL, $condition);
						// ALTER VALUES
						$values['id_curs']  = cleanvars($MASTER_TRACK_DETAIL["courses"]);
						$values['id_mas']   = cleanvars($data_arr["id"]);
					}
					elseif($data_arr['type'] == 3 || $data_arr['type'] == 4){
						$values['id_curs'] = cleanvars($data_arr["id"]);
					}

					$sqllms = $dblms->Insert(WISHLIST, $values);
					if ($sqllms) {
						$latestID = $dblms->lastestid();				
						$rowjson['success'] 		= 1;
						$rowjson['MSG'] 			= 'Successfully added to wishlist';
					} else {
						$rowjson['success'] 		= 0;
						$rowjson['MSG'] 			= 'Something went wrong';
					}
				}
			}
			// REMOVE FROM WISHLIST
			else if ($data_arr['is_added'] == false){
				// CHECK WISHLIST
				$conWish = array(
									 'select'       =>  'wl_id'
									,'where'        =>  array(
																'id_std'	=>   cleanvars($data_arr['std_id'])
															)
									,'return_type'  =>  'count'
								);
				// filter on behalf of type
				if($data_arr['type'] == 1){
					$conWish['where']['id_ad_prg']      = cleanvars($data_arr["id"]);
				}        
				elseif($data_arr['type'] == 2){
					$conWish['where']['id_mas']         = cleanvars($data_arr["id"]);
				}
				else if($data_arr['type'] == 3 || $data_arr['type'] == 4){
					$conWish['where']['id_curs']        = cleanvars($data_arr["id"]);
				}
				if ($dblms->getRows(WISHLIST, $conWish)){
					$conWish['return_type'] = 'single';
					$WISHLIST = $dblms->getRows(WISHLIST, $conWish);

					$sqlDel = $dblms->querylms('DELETE FROM '.WISHLIST.' WHERE wl_id = "'.cleanvars($WISHLIST['wl_id']).'"');
					if($sqlDel){
						$rowjson['success']	= 1;
						$rowjson['MSG']		= 'Successfully removed from wishlist';
					} else {						
						$rowjson['success']	= 0;
						$rowjson['MSG']		= 'Something went wrong';
					}
				} else {
					$rowjson['success']	= 0;
					$rowjson['MSG']		= 'Record not found in wishlist';
				}
			} else {				
				$rowjson['success']	= 0;
				$rowjson['MSG']		= 'Specify add or remove';
			}
		} else {			
			$rowjson['success']			= 0;
			$rowjson['MSG']				= 'Student does not exist';
		}
    } else {		
        $rowjson['success']			= 0;
        $rowjson['MSG']				= 'User not login';
    }
}