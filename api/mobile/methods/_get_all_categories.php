<?php
if($data_arr['method_name'] == "get_all_categories") { 
		
		$categories = array();

		// CATEGORIES
		$condition = array ( 
							'select'       =>  'cc.cat_id, cc.cat_name, cc.cat_icon'
							,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' a ON a.id_cat = cc.cat_id AND a.admoff_type=3'
							,'where' 	      =>  array ( 
														'cc.is_deleted'    =>  0
														,'cc.cat_status'    =>  1
													)
							,'group_by'     =>  'a.id_cat'
							,'order_by'     =>  'RAND()'
							,'limit'        =>  8
							,'return_type'  =>  'all'
						); 
		$COURSES_CATEGORIES = $dblms->getRows(COURSES_CATEGORIES.' cc', $condition);

		foreach ($COURSES_CATEGORIES AS $key => $val) {

			// CHECK FILE EXIST
			$photo      = SITE_URL.'uploads/default.png';
			if (!empty($val['cat_icon'])) {
				$photo = SITE_URL.'uploads/images/courses/categories/icons/'.$val['cat_icon'];
			}

			$cat['id'] 			= intval($val['cat_id']);
			$cat['name'] 		= html_entity_decode($val['cat_name']);
			$cat['icon'] 		= $photo;
			array_push($categories, $cat);
		}
		$rowjson['categorieslist']		= $categories;

	} 