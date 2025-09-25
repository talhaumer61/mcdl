<?php
	if ($data_arr['method_name'] == "get_all_instructors") { 
		
		$page   = $data_arr['page'] ?? 1;
		$limit  = $data_arr['limit'] ?? 10;

		if ($page) {
			$start = ($page - 1) * $limit;
		} else {
			$start = 0;
		}

		$instructors = array();

		// COUNT TOTAL
		$condition = array ( 
			'select'       => "DISTINCT e.emply_id, e.emply_photo, e.emply_name, e.emply_gender",
			'join'         => 'INNER JOIN '.ALLOCATE_TEACHERS.' AS at ON FIND_IN_SET(e.emply_id, at.id_teacher)',
			'where'        => array( 
				'e.emply_status'  => 1,
				'e.emply_request' => 1,
				'e.is_deleted'    => 0
			),
			'return_type'  => 'count'
		); 
		$count = $dblms->getRows(EMPLOYEES.' AS e', $condition);

		$lastpage = ceil($count / $limit);

		// FETCH WITH LIMIT
		$condition['order_by']    = "e.emply_id DESC";
		$condition['limit']       = "$start,$limit";
		$condition['return_type'] = "all";

		$EMPLOYEES = $dblms->getRows(EMPLOYEES.' AS e', $condition);

	if($EMPLOYEES) {

		foreach ($EMPLOYEES as $val) {

			// Default gender photo
			if (!empty($val['emply_photo'])) {
				$photo = SITE_URL.'uploads/images/employees/'.$val['emply_photo'];
			} 
			elseif ($val['emply_gender'] == '2') {
				$photo = SITE_URL.'uploads/images/default_female.jpg';
			} 
			else {            
				$photo = SITE_URL.'uploads/images/default_male.jpg';
			}

			$ins['id']    = intval($val['emply_id']);
			$ins['name']  = $val['emply_name'] ?? '';
			$ins['photo'] = $photo;

			array_push($instructors, $ins);
		}
		$rowjson['success'] 		= 1;
        $rowjson['MSG'] 			= 'Instructors List Fetched Successfully';
	} else {
		$rowjson['success'] 		= 0;
		$rowjson['MSG'] 			= 'No Instructors Found';
	}
		$rowjson['instructors'] = $instructors;
	}