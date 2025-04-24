<?php
// INSERT RECORD
if(isset($_POST['submit_add'])) {
	$condition 	=	[
						 'select'		=>	'org_id'
						,'where'		=>	[
												 'org_name'		=>	cleanvars($_POST['org_name'])
												,'is_deleted'	=>	'0'
											]
						,'return_type' 	=>	'count'
	]; 
	if($dblms->getRows(SKILL_AMBASSADOR, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	} else {
		$passArray 		= get_PasswordVerify($_POST['adm_userpass']);
		$hashPassword 	= $passArray['hashPassword'];
		$salt 			= $passArray['salt'];
		
		$values	= 	[
						 'adm_status'			=> 2 // pending
						,'adm_type'				=> 5 // type - sub member
						,'adm_logintype'		=> 8 // panel - organization
						,'adm_fullname'			=> cleanvars($_POST['org_name'])
						,'adm_username'			=> cleanvars($_POST['adm_username'])
						,'adm_userpass'			=> $hashPassword
						,'adm_salt'				=> $salt
						,'adm_email'			=> cleanvars($_POST['org_email'])
						,'adm_phone'			=> cleanvars($_POST['org_phone'])
						,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'			=> date('Y-m-d G:i:s')
		];
		$sqllms = $dblms->insert(ADMINS, $values);
		
		$latestID = $dblms->lastestid();

		$img_fileName 	= '';
		if(!empty($_FILES['org_photo']['name'])) {
			$path_parts 				= pathinfo($_FILES["org_photo"]["name"]);
			$extension 					= strtolower($path_parts['extension']);
			if(in_array($extension,array('jpeg','jpg','png','svg'))) {
				$img_dir 				= 'uploads/images/organization/';
				$img_fileName			= to_seo_url(cleanvars($_POST['org_name'])).'-'.$latestID.".".($extension);
				$originalImage			= $img_dir.$img_fileName;
				$dataImage 				= [ 'adm_photo' => $img_fileName ];
				$sqllmsUpdateCNIC 		= $dblms->Update(ADMINS, $dataImage, "WHERE adm_id = '".$latestID."'");
				unset($sqllmsUpdateCNIC);
				$mode = '0644';
				move_uploaded_file($_FILES['org_photo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		$dateArray 	= explode(' to ', $_POST['org_referral_link_expiry']);
		$date_from 	= $dateArray[0];
		$date_to 	= $dateArray[1];
		$values	= 	[
						'org_status'			=> 2, // pending
						'org_type'				=> 2, // type - sub member
						'allow_add_members'		=> 2, // no
						'parent_org'			=> cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
						'id_loginid'			=> $latestID,
						'org_name'				=> cleanvars($_POST['org_name']),
						'org_reg'				=> cleanvars($_POST['org_reg']),
						'org_percentage'		=> cleanvars($_POST['org_percentage']),
						'org_profit_percentage'	=> cleanvars($_POST['org_profit_percentage']),
						'org_phone'				=> cleanvars($_POST['org_phone']),
						'org_telephone'			=> cleanvars($_POST['org_telephone']),
						'org_whatsapp'			=> cleanvars($_POST['org_whatsapp']),
						'org_email'				=> cleanvars($_POST['org_email']),
						'org_address'			=> cleanvars($_POST['org_address']),
						'org_referral_link'		=> cleanvars($_POST['org_referral_link']),
						'org_link_from'			=> date('Y-m-d', strtotime($date_from)),
						'org_link_to'			=> date('Y-m-d', strtotime($date_to)),
						'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
						'date_added'			=> date('Y-m-d G:i:s'),
		];
		if(!empty($_FILES['org_photo']['name'])) {
			$values['org_photo'] 	= cleanvars($img_fileName);
		}
		$sqllms = $dblms->insert(SKILL_AMBASSADOR, $values);
		
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	[
						 'select'		=>	'org_id'
						,'where'		=>	[
												 'org_name'		=>	cleanvars($_POST['org_name'])
												,'is_deleted'	=>	'0'
											]
						,'not_equal' 	=>	[
												'org_id'		=>	cleanvars(LMS_EDIT_ID)
											]
						,'return_type' 	=>	'count' 
	]; 
	if($dblms->getRows(SKILL_AMBASSADOR, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$values	= 	[
						 'adm_fullname'			=> cleanvars($_POST['org_name'])
						,'adm_phone'			=> cleanvars($_POST['org_phone'])
						,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=> date('Y-m-d G:i:s')
					];
		if (!empty($_POST['adm_userpass'])) {
			$passArray 					= get_PasswordVerify($_POST['adm_userpass']);
			$hashPassword 				= $passArray['hashPassword'];
			$salt 						= $passArray['salt'];	
			$values['adm_userpass'] 	= $hashPassword;
			$values['adm_salt'] 		= $salt;
		}
		$sqllms = $dblms->Update(ADMINS,$values,"WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");

		$img_fileName = '';
		if(!empty($_FILES['org_photo']['name'])) {
			$path_parts 				= pathinfo($_FILES["org_photo"]["name"]);
			$extension 					= strtolower($path_parts['extension']);
			if(in_array($extension,array('jpeg','jpg','png','svg'))) {
				$img_dir 				= 'uploads/images/organization/';
				$img_fileName			= to_seo_url(cleanvars($_POST['org_name'])).'-'.cleanvars($_POST['adm_id']).".".($extension);
				$originalImage			= $img_dir.$img_fileName;
				$dataImage 				= [ 'adm_photo' => $img_fileName ];
				$sqllmsUpdateCNIC 		= $dblms->Update(ADMINS, $dataImage, "WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");
				unset($sqllmsUpdateCNIC);
				$mode = '0644';
				move_uploaded_file($_FILES['org_photo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		$dateArray 	= explode(' to ', $_POST['org_referral_link_expiry']);
		$date_from 	= $dateArray[0];
		$date_to 	= $dateArray[1];
		$values	= 	[
						 'org_name'					=> cleanvars($_POST['org_name'])
						,'org_percentage'			=> cleanvars($_POST['org_percentage'])
						,'org_profit_percentage'	=> cleanvars($_POST['org_profit_percentage'])
						,'org_phone'				=> cleanvars($_POST['org_phone'])
						,'org_telephone'			=> cleanvars($_POST['org_telephone'])
						,'org_whatsapp'				=> cleanvars($_POST['org_whatsapp'])
						,'org_address'				=> cleanvars($_POST['org_address'])
						,'id_modify'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'				=> date('Y-m-d G:i:s')
					];
		
		if(!empty($_FILES['org_photo']['name'])) {
			$values['org_photo'] 	= cleanvars($img_fileName);
		}
		$sqllms = $dblms->Update(SKILL_AMBASSADOR,$values,"WHERE org_id = '".cleanvars(LMS_EDIT_ID)."'");

		if($sqllms) { 
			$latestID = LMS_EDIT_ID;
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// CHANGE PASSWORD
if(isset($_POST['submit_change_password'])) {
	$passArray 	= get_PasswordVerify($_POST['adm_userpass']);
	$values	= 	[
					'adm_userpass'			=> cleanvars($passArray['hashPassword']),
					'adm_salt'				=> cleanvars($passArray['salt']),
					'id_modify'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
					'date_modify'			=> date('Y-m-d G:i:s'),
				];
	$sqllms 	= $dblms->Update(ADMINS,$values,"WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");
	if($sqllms) { 
		$latestID = $_POST['adm_id'];
		// REMARKS
		sendRemark(moduleName(false).' Updated', '2', $latestID);
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(SKILL_AMBASSADOR, $values , "WHERE org_id = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "Success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>