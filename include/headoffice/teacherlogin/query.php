<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=> "adm_username"
								,'where'		=> array( 
															 'is_deleted'       => 0
															,'adm_logintype'    => 3
															,'adm_type'         => 3
															,'adn_username'		=> cleanvars($_POST['adm_username'])
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(ADMINS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: teacherlogin.php", true, 301);
		exit();
	}else{
		// PASSWORD
		$salt		= dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass		= $_POST['adm_userpass'];
		$password	= hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		
		$values = array(
							 'adm_status'		=> cleanvars($_POST['adm_status'])
							,'adm_logintype'	=> 3
							,'adm_type'			=> 3
							,'is_teacher'		=> 2
							,'adm_username'		=> cleanvars($_POST['adm_username'])
							,'adm_salt'			=> cleanvars($salt)
							,'adm_userpass'		=> cleanvars($password)
							,'adm_fullname'		=> cleanvars($_POST['adm_fullname'])
							,'adm_email'		=> cleanvars($_POST['adm_email'])
							,'adm_phone'		=> cleanvars($_POST['adm_phone'])
							,'id_dept'			=> cleanvars($_POST['id_dept'])
							,'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->insert(ADMINS, $values);
		if($sqllms) { 
			$latestID   =	$dblms->lastestid();

			// UPDATE EMPLOYEE WITH LOGIN ID
			$valuesData = array(
							'emply_loginid'	=> cleanvars($latestID)
						);
			$sqlUpdate = $dblms->Update(EMPLOYEES , $valuesData , "WHERE emply_id  = '".cleanvars($_POST['id_emply'])."'");

			// REMARKS
			sendRemark('Teacher Login Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: teacherlogin.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=> "adm_username"
								,'where'		=> array( 
															 'is_deleted'       => 0
															,'adm_logintype'    => 3
															,'adm_type'         => 3
															,'adn_username'		=> cleanvars($_POST['adm_username'])
														)
								,'not_equal' 	=>	array( 
															'adm_id'			=>	cleanvars($_POST['adm_id'])
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(ADMINS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: teacherlogin.php", true, 301);
		exit();
	}else{
		// PASSWORD
		$salt		= dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass		= $_POST['adm_userpass'];
		$password	= hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		
		$values = array(
							 'adm_status'		=> cleanvars($_POST['adm_status'])
							,'adm_salt'			=> cleanvars($salt)
							,'adm_userpass'		=> cleanvars($password)
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(ADMINS , $values , "WHERE adm_id  = '".cleanvars($_POST['adm_id'])."'");
		if($sqllms) { 			
			$latestID = $_POST['adm_id'];
			sendRemark('Teacher Login Updates ID:'.cleanvars($latestID), '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: teacherlogin.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(ADMINS , $values , "WHERE adm_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark('Teacher Login Deleted #:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: teacherlogin.php", true, 301);
		exit();
	}
}
?>