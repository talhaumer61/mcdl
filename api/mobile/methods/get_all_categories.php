<?php
if($data_arr['method_name'] == "get_all_categories") { 
	
	$page   = $data_arr['page'] ?? 1;
	$limit  = $data_arr['limit'] ?? 15;

	if($page){
		$start = ($page - 1) * $limit;
	} else {
		$start = 0;
	}

	$categories = array();

	// COUNT TOTAL
	$condition = array ( 
		'select'       => 'cc.cat_id, cc.cat_name, cc.cat_icon',
		'join'         => 'INNER JOIN '.ADMISSION_OFFERING.' a ON a.id_cat = cc.cat_id AND a.admoff_type = 3',
		'where'        => array ( 
							'cc.is_deleted' => 0,
							'cc.cat_status' => 1
						),
		'order_by'     => 'cc.cat_id DESC',
		'group_by'     => 'a.id_cat',
		'return_type'  => 'count'
	); 
	$count = $dblms->getRows(COURSES_CATEGORIES.' cc', $condition);

	$lastpage = ceil($count / $limit);

	// FETCH WITH LIMIT
	$condition['order_by']    = 'cc.cat_id DESC';
	$condition['limit']       = "$start,$limit";
	$condition['return_type'] = 'all';

	$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES.' cc', $condition);

	if($COURSES_CATEGORIES){
		foreach ($COURSES_CATEGORIES AS $val) {

			// CHECK FILE EXIST
			$photo = SITE_URL.'uploads/default.png';
			if (!empty($val['cat_icon'])) {
				$photo = SITE_URL.'uploads/images/courses/categories/icons/'.$val['cat_icon'];
			}

			$cat['id']    = intval($val['cat_id']);
			$cat['name']  = html_entity_decode($val['cat_name']);
			$cat['icon']  = $photo;

			array_push($categories, $cat);
		}
		$rowjson['success']			= 1;
		$rowjson['MSG'] 			= 'Updated Course Categories List';
	} else {
		$rowjson['success']			= 0;
		$rowjson['MSG'] 			= 'No Course Categories Found';
	}

	$rowjson['categorieslist'] = $categories;
}
