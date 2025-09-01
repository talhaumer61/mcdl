<?php
if($data_arr['method_name'] == "get_about") {

		$rowjson['about']					= html_entity_decode(html_entity_decode(SITE_ABOUT));
		
	} 