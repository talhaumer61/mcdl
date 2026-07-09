<?php 	
include("includes/data/data.php");
include "../../include/dbsetting/lms_vars_config.php";
include "../../include/dbsetting/classdbconection.php";
include "../../include/functions/functions.php";
$dblms = new dblms();

$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 
$file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

if(isset($_POST)){ 

	$data_arr = json_decode(file_get_contents('php://input'), true);

	if (!isset($data_arr['method_name']) || empty($data_arr['method_name'])) {		
		$rowjson['success'] = 0;	
		$rowjson['MSG'] 	= 'No method name provided';
	} else {
		$method = strtolower(trim($data_arr['method_name']));
		$methodFile = __DIR__ . "/methods/{$method}.php";

		if (file_exists($methodFile)) {
			include $methodFile;
		} else {
			$rowjson['success'] = 0;	
			$rowjson['MSG'] 	= 'No such method exists';
		}
	}

	// response
	$response['MCDL_SYSTEM'] = $rowjson;
	header('Content-Type: application/json; charset=utf-8');
	echo str_replace('\\/', '/', json_encode($response,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	exit();	
}
?>