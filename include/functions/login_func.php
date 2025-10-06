<?php
// DODL LOGIN_FUNC
session_start();
// ADMIN LOGIN CHECK
function checkCpanelLMSALogin() {
	// !SESSION ID, REDIRECT TO LOGIN
	if(!isset($_SESSION['userlogininfo']['LOGINIDA'])) {
		header("Location: login.php");
		exit;
	}
	// LOGOUT
	if(isset($_GET['logout'])) {
		panelLMSALogout();
	}
}

// LOGIN FUNCTION
function cpanelLMSAuserLogin() {
	
	require_once ("include/dbsetting/lms_vars_config.php");
	require_once ("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();

	if (isset($_COOKIE['SWITCHTOINSTRUCTOR']) && !empty($_COOKIE['SWITCHTOINSTRUCTOR'])):
		$decrypt_username 	= get_dataHashing($_COOKIE['SWITCHTOINSTRUCTOR'],false);
		
		$d_cond = array ( 
							 'select' 		=>	'user_pass'
							,'where' 		=>	array( 
														'user_name' => $decrypt_username 
												) 
							,'not_equal'	=>	array(
														'user_pass' => ''
													)	
							,'order_by'	    =>	'id DESC'
							,'return_type'	=>	'single'
		); 			
		$e_row = $dblms->getRows(LOGIN_HISTORY, $d_cond);
	endif;	

	$adm_username   = ((isset($_POST['login_id']) && !empty($_POST['login_id']))? cleanvars($_POST['login_id']): cleanvars($decrypt_username));
	$adm_userpass  	= ((isset($_POST['login_pass']) && !empty($_POST['login_pass']))? cleanvars($_POST['login_pass']): cleanvars($e_row['user_pass']));

	$errorMessage 	= (empty($adm_username))?'You must enter your User Name'		:'';
	$errorMessage 	= (empty($adm_userpass))?'You must enter the User Password'		:'';

	// CHECK USERNAME, PASSWORD NOT EMPTY
	if (!empty($adm_username) && !empty($adm_userpass)) {

		// CHECK USERNAME, PASSWORD EXISTS
		$loginconditions = array ( 
									 'select' 		=>	'a.*, e.emply_id, e.emply_gender, o.org_id, o.org_type, o.allow_add_members, o.parent_org, o.org_percentage, o.org_profit_percentage, o.org_referral_link, o.org_link_to'
									,'join' 		=>	'LEFT JOIN '.EMPLOYEES.' e ON e.emply_loginid = a.adm_id AND e.emply_status = 1 AND e.is_deleted = 0
														 LEFT JOIN '.SKILL_AMBASSADOR.' o ON o.id_loginid = a.adm_id AND o.org_status = 1 AND o.is_deleted = 0'
									,'where' 		=>	array( 
																 'a.adm_status'		=> '1'
																,'a.is_deleted'		=> '0'
																,'a.adm_username' 	=> $adm_username
															)
									,'not_equal'	=>	array( 
																'a.is_teacher'		=> '1'
															)
									,'return_type'	=>	'single'
								); 
		$row = $dblms->getRows(ADMINS.' a', $loginconditions);
		// IF EXISTS
		if (!empty($row)) {
			
			// PASSWORD HASHING
			$salt 		= $row['adm_salt'];
			$password 	= hash('sha256', $adm_userpass . $salt);			
			for ($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}

			if($password == $row['adm_userpass']) { 

				// MAKE LOGIN HISTORY
				$dataLog = array(
									 'login_type'		=> cleanvars($row['adm_logintype'])
									,'id_login_id'		=> cleanvars($row['adm_id'])
									,'user_name'		=> cleanvars($adm_username)
									,'user_pass'		=> cleanvars($adm_userpass)
									,'dated'			=> date("Y-m-d G:i:s")
								);
				$sqllmslog  = $dblms->Insert(LOGIN_HISTORY , $dataLog);

				// CHECK ADMIN IMAGE EXIST
				if($row['emply_gender'] == '2'){
					$adm_photo = SITE_URL.'uploads/images/default_female.jpg';
				}else{            
					$adm_photo = SITE_URL.'uploads/images/default_male.jpg';
				}
				if(!empty($row['adm_photo'])){
					$file_url = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
					if (check_file_exists($file_url)) {
						$adm_photo = $file_url;
					}
				}
					
				// Login time when the admin login
				$userlogininfo = array();
					$userlogininfo['LOGINIDA'] 			=	$row['adm_id'];
					$userlogininfo['LOGINTYPE'] 		=	$row['adm_type'];
					$userlogininfo['LOGINAFOR'] 		=	$row['adm_logintype'];
					$userlogininfo['LOGINUSER'] 		=	$row['adm_username'];
					$userlogininfo['LOGINEMAIL'] 		=	$row['adm_email'];
					$userlogininfo['LOGINPHONE'] 		=	$row['adm_phone'];
					$userlogininfo['LOGINNAME'] 		=	$row['adm_fullname'];
					$userlogininfo['LOGINPHOTO'] 		=	$adm_photo;
					$userlogininfo['LOGINCAMPUS'] 		=	$row['id_campus'];
					$userlogininfo['EMPLYID'] 			=	$row['emply_id'];
					$userlogininfo['EMPLYGENDER']		=	$row['emply_gender'];
					$userlogininfo['LOGINISTEACHER']	=	$row['is_teacher']; // 1=student, 2=teacher, 3=both

					// if org
					if ($row['adm_logintype'] == 8) {
						$userlogininfo['LOGINORGANIZATIONID']				=	$row['org_id'];
						$userlogininfo['LOGINORGANIZATIONPERCENTAGE']		=	$row['org_percentage'];
						$userlogininfo['LOGINORGANIZATIONPROFITPERCENTAGE']	=	$row['org_profit_percentage'];
						$userlogininfo['LOGINORGANIZATIONLINK']				=	$row['org_referral_link'];
						$userlogininfo['LOGINORGANIZATIONLINKEXPIRYTO']		=	$row['org_link_to'];
						$userlogininfo['LOGINORGANIZATIONTYPE']				=	$row['org_type'];
						$userlogininfo['LOGINORGANIZATIONADDMEMBERS']		=	$row['allow_add_members'];
						$userlogininfo['LOGINORGANIZATIONPARENT']			=	$row['parent_org'];
					}
				$_SESSION['userlogininfo'] 				=	$userlogininfo;
				$_SESSION['SHOWNOTIFICATION'] 			= 1;
				$_SESSION['NOTIFICATION']				= 1;

				// ROLES IN ARRAY
				$rightdata = array();
				$roleconditions = array ( 
											 'select' 		=>	'*'
											,'where' 		=>	array( 
																		'id_adm' => cleanvars($row['adm_id'])
																	)
											,'order_by'		=>	'right_type ASC'
											,'return_type' 	=>	'all' 
										); 
				$Roles = $dblms->getRows(ADMIN_ROLES, $roleconditions);
				foreach($Roles as $valueroles) {
					$rightdata[] = 	array (
											 'right_name' 	=> $valueroles['right_name']
											,'add' 			=> $valueroles['added']
											,'edit'			=> $valueroles['updated']
											,'delete' 		=> $valueroles['deleted']
											,'view'			=> $valueroles['view']
											,'type'			=> $valueroles['right_type']
										);
				}
				$_SESSION['userroles'] = $rightdata;

				$remarks = 'Login to Software';
				$dataLogs = array(
									 'id_user'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'filename'		=> strstr(basename($_SERVER['REQUEST_URI']),'.php', true)
									,'action'		=> '4'
									,'dated'		=> date("Y-m-d G:i:s")
									,'ip'			=> cleanvars($ip)
									,'remarks'		=> cleanvars($remarks)
									,'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
								);
				$sqllmslogs  = $dblms->Insert(LOGS , $dataLogs);
				
				// UNSET COOKIE MAIN
				$e_url = 'mul.edu.pk';
				setcookie('SWITCHTOINSTRUCTOR','',time()-86400,'/',$e_url);

				$_SESSION['msg']['title'] 	= 'Successfully';
				$_SESSION['msg']['text'] 	= 'Login Successfully.';
				$_SESSION['msg']['type'] 	= 'success';
				header("Location: dashboard.php");
				exit();
			} else {
				$errorMessage = '<p class="text-danger">Invalid User  Password.</p>';
			}
			
		} else {
			$errorMessage = '<p class="text-danger">Invalid User Name or Password.</p>';
		}		
	}
	return $errorMessage;
	//mysql_close($link);
}

// LOGOUT FUNCTION
function panelLMSALogout() {
	if (isset($_SESSION['userlogininfo']['LOGINIDA'])) {
		unset($_SESSION['userlogininfo']);
		unset($_SESSION['userroles']);
		session_destroy();
	}
	header("Location: login.php");
	exit;
}
?>