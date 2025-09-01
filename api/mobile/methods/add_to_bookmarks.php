<?php
if($data_arr['method_name'] == "add_to_bookmarks") {
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
	} 