<?php
if($data_arr['method_name'] == "get_learn_panel") {
		$my_course 						= array();

		$my_course['coursesinfo'] 		= $datalearn['coursesinfo'];
		$my_course['faqs'] 				= $datalearn['faqs'];
		$my_course['modules'] 		 	= $datalearn['modules'];

		$rowjson['my_course']			= $my_course;
	}