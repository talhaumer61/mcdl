<?php
// ADD ADMIN PROFILE
if(isset($_POST['submit_profile'])) {

	$adm_id =	$_SESSION['userlogininfo']['LOGINIDA'];

	$values = array(
						 'adm_fullname'				=>	cleanvars($_POST['adm_fullname'])
						,'adm_email'				=>	cleanvars($_POST['adm_email'])
						,'adm_phone'				=>	cleanvars($_POST['adm_phone'])
						,'id_modify'				=>	$adm_id
						,'date_modify'				=>	date('Y-m-d G:i:s')
					);
	$sqllms = $dblms->Update(ADMINS , $values , "WHERE adm_id  = '".$adm_id."'");

	$values = array(
						 'org_name'					=>	cleanvars($_POST['adm_fullname'])
						,'org_email'				=>	cleanvars($_POST['adm_email'])
						,'org_phone'				=>	cleanvars($_POST['adm_phone'])
						,'org_telephone'			=>	cleanvars($_POST['org_telephone'])
						,'org_whatsapp'				=>	cleanvars($_POST['org_whatsapp'])
						,'org_city'					=>	cleanvars($_POST['org_city'])
						,'org_address'				=>	cleanvars($_POST['org_address'])
						,'id_modify'				=>	$adm_id
						,'date_modify'				=>	date('Y-m-d G:i:s')
					);
	$sqllms = $dblms->Update(SKILL_AMBASSADOR , $values , "WHERE org_id  = '".$_SESSION['userlogininfo']['LOGINORGANIZATIONID']."'");

	if($sqllms) { 

		// UPDATE CV
		if(!empty($_FILES['cv_file']['name'])) {
			$path_parts 	= pathinfo($_FILES["cv_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'pdf'))) {
				$img_dir 		= 'uploads/files/organization_cv/';
				$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname']).'-'.$_POST['org_reg']).'-'.$_SESSION['userlogininfo']['LOGINORGANIZATIONID'].".".($extension);
				$originalImage	= $img_dir.$img_fileName;
				$dataImage = array(
									'cv_file'	=>	$img_fileName, 
									);
				$sqllmsUpdateCNIC = $dblms->Update(SKILL_AMBASSADOR, $dataImage, "WHERE org_id = '".$_SESSION['userlogininfo']['LOGINORGANIZATIONID']."'");
				unset($sqllmsUpdateCNIC);
				$mode = '0644';
				if (move_uploaded_file($_FILES['cv_file']['tmp_name'],$originalImage))
				{					
					$_SESSION['SHOWNOTIFICATION']	= 1;
				}
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPDATE PROFILE IMAGE
		if(!empty($_FILES['adm_photo']['name'])) {

			$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG'))) {
				$img_dir 		= 'uploads/images/admin/';
				$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'])).'-'.$adm_id.".".($extension);
				$originalImage	= $img_dir.$img_fileName;
				$dataImage = array(
									'adm_photo'	=>	$img_fileName, 
									);
				$sqllmsUpdateCNIC = $dblms->Update(ADMINS, $dataImage, "WHERE adm_id = '".$adm_id."'");
				unset($sqllmsUpdateCNIC);
				$mode = '0644';
				if (move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage))
				{
					$_SESSION['userlogininfo']['LOGINPHOTO']	=	SITE_URL.'/uploads/images/admin/'.$img_fileName;
				}
				chmod ($originalImage, octdec($mode));
			}
		}

		$_SESSION['userlogininfo']['LOGINEMAIL'] 	=	$_POST['adm_email'];
		$_SESSION['userlogininfo']['LOGINPHONE'] 	=	$_POST['adm_phone'];
		$_SESSION['userlogininfo']['LOGINNAME'] 	=	$_POST['adm_fullname'];
		
		sendRemark('Profile info has changed', '2', $adm_id);
		sessionMsg('Success', 'Record Successfully Updated.', 'success');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		sessionMsg('Error', 'Something went wrong.', 'danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}

// BANK DETAILS
if(isset($_POST['bank_details'])) {
	$condition	=	array ( 
								 'select'		=>	'id'
								,'where'		=>	array( 
															 'account_number'	=>	cleanvars($_POST['account_number'])
															,'is_deleted'		=>	'0'
														)
								,'not_equal' 	=>	array( 
															'id'				=>	cleanvars($_POST['id'])
														)
								,'return_type' 	=>	'count'  
							); 
	if($dblms->getRows(SA_BANK_DETAILS, $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'			=> 1
							,'id_bank'			=> cleanvars($_POST['id_bank'])
							,'account_title'	=> cleanvars($_POST['account_title'])
							,'account_number'	=> cleanvars($_POST['account_number'])
							,'account_iban'		=> cleanvars($_POST['account_iban'])
							,'id_org'			=> cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID'])

							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						); 
		if(empty($_POST['id'])){
			$values['id_added'] = cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
			$values['date_added'] = date('Y-m-d G:i:s');
			$sqllms = $dblms->insert(SA_BANK_DETAILS, $values);
			if($sqllms){
				$latestID = $dblms->lastestid();
				sendRemark(moduleName(false).' - Bank detais Added', '1', $latestID);
				sessionMsg('Successfully', 'Record Successfully Added.', 'success');
				header("Location: ".moduleName().".php", true, 301);
				exit();
			}
		} else {			
			$values['id_modify'] = cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
			$values['date_modify'] = date('Y-m-d G:i:s');
			$sqllms = $dblms->Update(SA_BANK_DETAILS , $values , "WHERE id  = '".cleanvars($_POST['id'])."'");
			if($sqllms){
				$latestID = $_POST['id'];
				sendRemark(moduleName(false).' - Bank detais Updated', '2', $latestID);
				sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
				header("Location: ".moduleName().".php", true, 301);
				exit();
			}
		}
	}
}

// EDU DETAILS
if (isset($_POST['academic_details'])) {

    // Encode multi-fields
    $certification_json = json_encode(array_filter($_POST['certifications'] ?? []));
    $skills_json        = json_encode(array_filter($_POST['skills'] ?? []));

    $values = array(
        'status'           => 1,
        'name'             => cleanvars($_POST['name']),
        'qualification'    => cleanvars($_POST['qualification']),
        'field_expertise'  => cleanvars($_POST['field_expertise']),
        'experience'       => (int) $_POST['experience'],
        'certification'    => $certification_json,
        'skill_strength'   => $skills_json,
        'city'             => cleanvars($_POST['city']),
        'id_org'           => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID']),
        'id_modify'        => cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
        'date_modify'      => date('Y-m-d G:i:s')
    );

    // If new record
    if (empty($_POST['id'])) {
        $values['id_added']   = cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
        $values['date_added'] = date('Y-m-d G:i:s');

        $sqllms = $dblms->insert(SA_EDU_DETAILS, $values);

        if ($sqllms) {
            $latestID = $dblms->lastestid();
            sendRemark(moduleName(false) . ' - Academic Details Added', '1', $latestID);
            sessionMsg('Successfully', 'Record Successfully Added.', 'success');
            header("Location: " . moduleName() . ".php", true, 301);
            exit();
        }

    } else { // Update existing
        $sqllms = $dblms->Update(SA_EDU_DETAILS, $values, "WHERE id = '" . cleanvars($_POST['id']) . "'");

        if ($sqllms) {
            $latestID = cleanvars($_POST['id']);
            sendRemark(moduleName(false) . ' - Academic Details Updated', '2', $latestID);
            sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
            header("Location: " . moduleName() . ".php", true, 301);
            exit();
        }
    }
}

//CHANGE PASSWORD
if(isset($_POST['chnage_pass'])) { 
	$adm_id =	$_SESSION['userlogininfo']['LOGINIDA'];
	
	//HASHING
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$pass = $_POST['cnfrm_pass'];
	$password = hash('sha256', $pass . $salt);
	for ($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	}

	$values = array(
						 'adm_salt'		=>	cleanvars($salt)
						,'adm_userpass'	=>	cleanvars($password)
						,'id_modify'	=> 	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'	=>	date('Y-m-d G:i:s')
				);   
	$sqllms = $dblms->Update(ADMINS , $values , "WHERE adm_id  = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'");	

	if($sqllms) {		
		sendRemark('Password has changed', '2', $adm_id);
		sessionMsg('Success', 'Password Successfully Updated.', 'success');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {		
		sessionMsg('Error', 'Something went wrong.', 'danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>