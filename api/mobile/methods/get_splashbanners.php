<?php
if($data_arr['method_name'] == "get_splashbanners") { 
		
		$array = $data['splashbanners']['list'];
		$rowjson['splashbanners']	=  $array[array_rand($array, 1)];

	} 