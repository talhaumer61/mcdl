<?php 
error_reporting(0);
ob_start();
session_start();
 	
 	header("Content-Type: text/html;charset=UTF-8");
	
		if($_SERVER['HTTP_HOST']=="localhost" or $_SERVER['HTTP_HOST']=="192.168.1.125") {	
			//local 
			DEFINE ('DB_USER'		, 'neotericschools_mcdl24');
			DEFINE ('DB_PASSWORD'	, 'rMgOyiA]}dT2');
			DEFINE ('DB_HOST'		, 'localhost'); //host name depends on server
			DEFINE ('DB_NAME'		, 'neotericschools_mcdl2024'); 
		
		} else {
			//live server configuration
			DEFINE ('DB_USER'		, 'neotericschools_mcdl24');
			DEFINE ('DB_PASSWORD'	, 'rMgOyiA]}dT2');
			DEFINE ('DB_HOST'		, 'localhost'); //host name depends on server
			DEFINE ('DB_NAME'		, 'neotericschools_mcdl2024');
		}

	
		$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

		if ($mysqli->connect_errno)  {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		mysqli_query($mysqli,"SET NAMES 'utf8'");	 

		define('SETTINGS'			, 'cms_apisettings');
		define('ASSEST_URL'			, 'https://mcdl.mul.edu.pk');


		//Settings
		$setting_qry		= "SELECT * FROM ".SETTINGS." where id='1'";
		$setting_result		= mysqli_query($mysqli,$setting_qry);
		$settings_details	= mysqli_fetch_assoc($setting_result);

		//define("APP_FCM_KEY",$settings_details['app_fcm_key']);

		define("APP_NAME"				, $settings_details['app_name']);
		define("APP_LOGO"				, $settings_details['app_logo']);
		define("APP_FROM_EMAIL" 		, $settings_details['email_from']);

		define("ONESIGNAL_APP_ID"		, $settings_details['onesignal_app_id']);
		define("ONESIGNAL_REST_KEY" 	, $settings_details['onesignal_rest_key']);

		define("API_PAGE_LIMIT"			, $settings_details['api_latest_limit']);
		define("API_CAT_ORDER_BY"		, $settings_details['api_cat_order_by']);
		define("API_CAT_POST_ORDER_BY"	, $settings_details['api_cat_post_order_by']);
	

		$ip	  = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '';
