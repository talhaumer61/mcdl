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
	}else{
		$adm_status = ($_POST['org_status'] == 1 ? $_POST['org_status'] : 2);

		if($_POST['org_type'] == 2){
			$adm_type = 5;
			$allow_add_members = 2;
			$parent_org = $_POST['parent_org'];
		} else {
			$adm_type = 4;
			$allow_add_members = $_POST['allow_add_members'];
			$parent_org = 0;
		}

		$values	= 	[
						 'adm_status'			=> cleanvars($adm_status)
						,'adm_type'				=> cleanvars($adm_type)
						,'adm_logintype'		=> 8
						,'adm_fullname'			=> cleanvars($_POST['org_name'])
						,'adm_username'			=> cleanvars($_POST['adm_username'])
						,'adm_email'			=> cleanvars($_POST['org_email'])
						,'adm_phone'			=> cleanvars($_POST['org_phone'])
						,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'			=> date('Y-m-d G:i:s')
		];
		if (isset($_POST['adm_userpass']) && !empty($_POST['adm_userpass'])) {
			$passArray 				= get_PasswordVerify($_POST['adm_userpass']);
			$hashPassword 			= $passArray['hashPassword'];
			$salt 					= $passArray['salt'];
			$values['adm_userpass'] = $hashPassword;
			$values['adm_salt'] 	= $salt;
		}
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
						 'org_status'				=> cleanvars($_POST['org_status'])
						,'org_type'					=> cleanvars($_POST['org_type'])
						,'allow_add_members'		=> cleanvars($allow_add_members)
						,'parent_org'				=> cleanvars($parent_org)
						,'id_loginid'				=> $latestID
						,'org_name'					=> cleanvars($_POST['org_name'])
						,'org_reg'					=> cleanvars($_POST['org_reg'])
						,'org_percentage'			=> cleanvars($_POST['org_percentage'])
						,'org_profit_percentage'	=> cleanvars($_POST['org_profit_percentage'])
						,'org_phone'				=> cleanvars($_POST['org_phone'])
						,'org_telephone'			=> cleanvars($_POST['org_telephone'])
						,'org_whatsapp'				=> cleanvars($_POST['org_whatsapp'])
						,'org_email'				=> cleanvars($_POST['org_email'])
						,'org_address'				=> cleanvars($_POST['org_address'])
						,'org_referral_link'		=> cleanvars($_POST['org_referral_link'])
						,'org_link_from'			=> date('Y-m-d', strtotime($date_from))
						,'org_link_to'				=> date('Y-m-d', strtotime($date_to))
						,'id_added'					=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'				=> date('Y-m-d G:i:s')
		];
		if(!empty($_FILES['org_photo']['name'])) {
			$values['org_photo'] 	= cleanvars($img_fileName);
		}
		$sqllms = $dblms->insert(SKILL_AMBASSADOR, $values);
		
		if($sqllms) {
			$latestID = $dblms->lastestid();

			// send mail
			get_SendMail([
				'sender'        => SMTP_EMAIL,
				'senderName'    => SITE_NAME,
				'receiver'      => cleanvars($_POST['org_email']),
				'receiverName'  => cleanvars($_POST['org_name']),
				'subject'       => "Welcome to ".TITLE_HEADER_WEB." as a skill ambassador, Your Journey Begins Here!",
				'body'          => '
					<p>
						We are excited to have you join our community! This email contains all the essential information to get you started with '.SITE_NAME_WEB.'.
						<br>
						your unique registration number is <b class="text-primary">'.$valQuery['new_org_reg'].'</b>
						<br>
						<br>
						<b>Warm regards,</b>
						<br>
						Support Team
						<br>
						<br>
						'.SMTP_EMAIL.'
						<br>
						'.SITE_NAME_WEB.' <b>('.TITLE_HEADER_WEB.')</b>
						<br>
						<b>Minhaj University Lahore</b>
					</p>
				',
				'tokken'        => SMTP_TOKEN,
			], 'send-mail');

			// on approval
			if ($_POST['org_status'] == 1 && isset($_POST['adm_userpass']) && !empty($_POST['adm_userpass'])) {
				// send mail
				get_SendMail([
					'sender'        => SMTP_EMAIL,
					'senderName'    => SITE_NAME,
					'receiver'      => cleanvars($_POST['org_email']),
					'receiverName'  => cleanvars($_POST['org_name']),
					'subject'       => "Welcome to ".TITLE_HEADER_WEB." as a skill ambassador, Your Journey Begins Here!",
					'body'          => '
						<p>
							We are excited to welcome you to the <b>Skill Ambassador</b> by the '.SITE_NAME_WEB.', Minhaj University Lahore.
							<br>
							Here are your login details to get started:
							<br>
							<b>Username:</b> <i class="text-primary">'.$_POST['adm_username'].'</i>
							<br>
							<b>Password:</b> <i class="text-primary">'.$_POST['adm_userpass'].'</i>
							<br>
							Login here: <i class="text-primary"><a href="'.SITE_URL.'" target="_blank">'.SITE_URL.'</a></i>
							<br>
							<br>
							<b>As a Skill Ambassador, you can:</b>
							<br>
							Earn <b>20% profit</b> on payments made by students you refer.
							<br>
							Thank you for joining us in making learning accessible and impactful. Let\'s grow together!
							<br>
							If you have any quetions or need support, feel free to contat us at <b>'.SMTP_EMAIL.'</b>
							<br>
							<br>
							<b>Warm regards,</b>
							<br>
							'.SITE_NAME_WEB.' <b>('.TITLE_HEADER_WEB.')</b>
							<br>
							<b>Minhaj University Lahore</b>
						</p>
					',
					'tokken'        => SMTP_TOKEN,
				], 'send-mail');
			}

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
	} else {
		$adm_status = ($_POST['org_status'] == 1 ? $_POST['org_status'] : 2);

		if($_POST['org_type'] == 2){
			$adm_type = 5;
			$allow_add_members = 2;
			$parent_org = $_POST['parent_org'];
		} else {
			$adm_type = 4;
			$allow_add_members = $_POST['allow_add_members'];
			$parent_org = 0;
		}

		$values	= 	[
						 'adm_status'			=> cleanvars($adm_status)
						,'adm_type'				=> cleanvars($adm_type)
						,'adm_fullname'			=> cleanvars($_POST['org_name'])
						,'adm_phone'			=> cleanvars($_POST['org_phone'])
						,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=> date('Y-m-d G:i:s')
					];
		// on approval
		if ($_POST['org_status'] == 1 && isset($_POST['adm_userpass']) && !empty($_POST['adm_userpass'])) {	
			$passArray 				= get_PasswordVerify($_POST['adm_userpass']);
			$hashPassword 			= $passArray['hashPassword'];
			$salt 					= $passArray['salt'];
			$values['adm_userpass'] = $hashPassword;
			$values['adm_salt'] 	= $salt;
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
						 'org_status'				=> cleanvars($_POST['org_status'])
						,'org_type'					=> cleanvars($_POST['org_type'])
						,'allow_add_members'		=> cleanvars($allow_add_members)
						,'parent_org'				=> cleanvars($parent_org)
						,'org_name'					=> cleanvars($_POST['org_name'])
						,'org_percentage'			=> cleanvars($_POST['org_percentage'])
						,'org_profit_percentage'	=> cleanvars($_POST['org_profit_percentage'])
						,'org_phone'				=> cleanvars($_POST['org_phone'])
						,'org_telephone'			=> cleanvars($_POST['org_telephone'])
						,'org_whatsapp'				=> cleanvars($_POST['org_whatsapp'])
						,'org_address'				=> cleanvars($_POST['org_address'])
						,'org_link_from'			=> date('Y-m-d', strtotime($date_from))
						,'org_link_to'				=> date('Y-m-d', strtotime($date_to))
						,'id_modify'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'				=> date('Y-m-d G:i:s')
					];		
		if(!empty($_FILES['org_photo']['name'])) {
			$values['org_photo'] 	= cleanvars($img_fileName);
		}
		$sqllms = $dblms->Update(SKILL_AMBASSADOR,$values,"WHERE org_id = '".cleanvars(LMS_EDIT_ID)."'");

		if($sqllms) {
			$latestID = LMS_EDIT_ID;

			// on approval
			if ($_POST['org_status'] == 1 && isset($_POST['adm_userpass']) && !empty($_POST['adm_userpass'])) {	
				// send mail
				get_SendMail([
					'sender'        => SMTP_EMAIL,
					'senderName'    => SITE_NAME,
					'receiver'      => cleanvars($_POST['org_email']),
					'receiverName'  => cleanvars($_POST['org_name']),
					'subject'       => "Welcome to ".TITLE_HEADER_WEB." as a skill ambassador, Your Journey Begins Here!",
					'body'          => '
						<p>
							We are excited to welcome you to the <b>Skill Ambassador</b> by the '.SITE_NAME_WEB.', Minhaj University Lahore.
							<br>
							Here are your login details to get started:
							<br>
							<b>Username:</b> <i class="text-primary">'.$_POST['adm_username'].'</i>
							<br>
							<b>Password:</b> <i class="text-primary">'.$_POST['adm_userpass'].'</i>
							<br>
							Login here: <i class="text-primary"><a href="'.SITE_URL.'" target="_blank">'.SITE_URL.'</a></i>
							<br>
							<br>
							<b>As a Skill Ambassador, you can:</b>
							<br>
							Earn <b>20% profit</b> on payments made by students you refer.
							<br>
							<br>
							Thank you for joining us in making learning accessible and impactful. Let\'s grow together!
							<br>
							If you have any quetions or need support, feel free to contat us at <b>'.SMTP_EMAIL.'</b>
							<br>
							<br>
							<b>Warm regards,</b>
							<br>
							'.SITE_NAME_WEB.' <b>('.TITLE_HEADER_WEB.')</b>
							<br>
							<b>Minhaj University Lahore</b>
						</p>
					',
					'tokken'        => SMTP_TOKEN,
				], 'send-mail');
			}

			// on rejection
			if ($_POST['org_status'] == 3) {
				// send mail
				get_SendMail([
					'sender'        => SMTP_EMAIL,
					'senderName'    => SITE_NAME,
					'receiver'      => cleanvars($_POST['org_email']),
					'receiverName'  => cleanvars($_POST['org_name']),
					'subject'       => "Update on Your Skill Ambassador Application (Case Rejection)",
					'body'          => '
						<p>
							Dear '.$_POST['org_email'].',
							<br>
							<br>
							Thank you for applying to be a Skill Ambassador with '.TITLE_HEADER_WEB.'. Your application is currently under review, and we will inform you later.
							<br>
							If you have any questions, feel free to contact us at <b>'.SMTP_EMAIL.'</b>
							<br>
							<br>
							<b>Best regards,</b>
							<br>
							'.SITE_NAME_WEB.' <b>('.TITLE_HEADER_WEB.')</b>
							<br>
							<b>Minhaj University Lahore</b>
						</p>
					',
					'tokken'        => SMTP_TOKEN,
				], 'send-mail');
			}

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
					 'adm_userpass'			=> cleanvars($passArray['hashPassword'])
					,'adm_salt'				=> cleanvars($passArray['salt'])
					,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					,'date_modify'			=> date('Y-m-d G:i:s')
				];
	$sqllms = $dblms->Update(ADMINS,$values,"WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");
	if($sqllms) { 
		$latestID = $_POST['adm_id'];
		// REMARKS
		sendRemark(moduleName(false).' Updated', '2', $latestID);
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}

// CHANGE EXPIRY DATE
if(isset($_POST['submit_change_expiry_date'])) {
	$dateArray 	= explode(' to ', $_POST['org_referral_link_expiry']);
	$date_from 	= $dateArray[0];
	$date_to 	= $dateArray[1];
	$values	= 	[
					 'org_link_from'	=> date('Y-m-d', strtotime($date_from))
					,'org_link_to'		=> date('Y-m-d', strtotime($date_to))
					,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					,'date_modify'		=> date('Y-m-d G:i:s')
				];
	$sqllms = $dblms->Update(SKILL_AMBASSADOR,$values,"WHERE org_id = '".cleanvars($_POST['org_id'])."'");
	if($sqllms) { 
		$latestID = cleanvars($_POST['org_id']);
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