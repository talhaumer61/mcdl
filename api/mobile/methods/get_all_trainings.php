<?php
if($data_arr['method_name'] == "get_all_trainings") {
	
	$page			= $data_arr['page'] ?? 1;
	$limit			= $data_arr['limit'] ?? 10;

	if($page){
		$start = ($page - 1) * $limit;
	} else {
		$start = 0;
	}
		
	$trainings_array = array();

	// TRAININGS
	$condition = array ( 
                         'select'       =>	'c.curs_id, c.curs_name, c.curs_photo, c.curs_type_status, c.curs_hours, a.id_type, a.admoff_type, a.admoff_amount, a.admoff_amount_in_usd'
                        ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.admoff_degree = c.curs_id AND a.admoff_type = 4'
                        ,'where'        =>	array( 
                                                     'c.curs_status'    => '1' 
                                                    ,'c.is_deleted'     => '0'
                                                )
                        ,'group_by'     =>  'c.curs_id'
                        ,'order_by'     =>  'c.curs_id DESC'
                        ,'return_type'	=>	'count'
                    );
    $count = $dblms->getRows(COURSES.' c', $condition);

	$lastpage = ceil($count / $limit);   //lastpage = total pages // items per page, rounded up

	$condition['limit'] = "$start,$limit";   // ✅ only LIMIT here
	$condition['return_type'] = 'all';
	
	$TRAININGS = $dblms->getRows(COURSES.' c', $condition);

	if($TRAININGS){
		foreach ($TRAININGS as $course) {
	        
	        // CHECK FILE EXIST
	        $photo = SITE_URL.'uploads/images/default_curs.jpg';
	        if (isset($course['curs_photo']) && !empty($course['curs_photo'])) {
	            $photo = SITE_URL.'uploads/images/courses/'.$course['curs_photo'];
	        }
	
	        $condition = array ( 
	                                 'select'       =>	'd.discount_id, dd.discount, dd.discount_type'
	                                ,'join'         =>  'INNER JOIN '.DISCOUNT_DETAIL.' dd ON d.discount_id = dd.id_setup AND dd.id_curs = "'.$course['curs_id'].'"'
	                                ,'where'        =>	array( 
	                                                             'd.discount_status' 	=> '1' 
	                                                            ,'d.is_deleted' 	    => '0'
	                                                        )
	                                ,'search_by'    =>  ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE '
	                                ,'return_type'	=>	'single'
	                            );
	        $DISCOUNT = $dblms->getRows(DISCOUNT.' d ', $condition);
	
	        // COURSE DATA
	        $valTraining['training_id']           =   intval($course['curs_id']);
	        $valTraining['training_name']         =   html_entity_decode($course['curs_name']);
	        $valTraining['training_photo']        =   $photo;
	        $valTraining['training_type']         =   $course['id_type'];
	        $valTraining['trainings_hours']       =   $course['curs_hours'].' Hour'.($course['curs_hours'] > 1 ? 's' : '');
	        $valTraining['offering_type']         =   $course['admoff_type'];
	        $valTraining['offering_amount']       =   $course['admoff_amount'];
	        $valTraining['offering_amount_usd']   =   $course['admoff_amount_in_usd'];
	
	        // DISCOUNT
	        if($DISCOUNT){
	            if ($course['curs_type_status'] != '1' && !empty($DISCOUNT['discount_id'])) {
	                $discount_type  = $DISCOUNT['discount_type'];
	                $discount_value = $DISCOUNT['discount'];
	            }
	        }        
	        $valTraining['discount_type']          =   ($discount_type ?? "0");
	        $valTraining['discount_value']         =   ($discount_value ?? "0");
	
	        array_push($trainings_array, $valTraining);
	    }

		$rowjson['success']			= 1;
		$rowjson['MSG'] 			= 'Updated Trainings List';
	} else {		
		$rowjson['success']			= 0;
		$rowjson['MSG'] 			= 'No Trainings Found';
	}
	$rowjson['trainings_list']	= $trainings_array;
} 