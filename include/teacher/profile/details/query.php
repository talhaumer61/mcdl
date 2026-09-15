<?php
// EDIT PASSWORD
if(isset($_POST['submit_edit_password'])) {
	$pass =  cleanvars($_POST['adm_userpass']);
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$password = hash('sha256', $pass . $salt);
	for ($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	} 

	$values = array( 'adm_userpass' => $password ,'adm_salt' =>	$salt );

	$sqllms = $dblms->Update(ADMINS, $values , "WHERE adm_id  = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'");
	
	if($sqllms) { 
		// LATEST ID
		$latestID = $_POST['adm_id'];
		// REMARKS
		sendRemark('Employee Password Updated', '2', $latestID);
		sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}

// EDIT PROFILE
if(isset($_POST['submit_edit_profile'])) {
	$values 		= array(
								 'emply_name'				=>	cleanvars($_POST['emply_name'])
								,'emply_fathername'			=>	cleanvars($_POST['emply_fathername'])
								,'emply_cnic'				=>	cleanvars($_POST['emply_cnic'])
								,'emply_extension'			=>	cleanvars($_POST['emply_extension'])
								,'emply_phone'				=>	cleanvars($_POST['emply_phone'])
								,'emply_mobile'				=>	cleanvars($_POST['emply_mobile'])
								,'emply_gender'				=>	cleanvars($_POST['emply_gender'])
								,'emply_dob'				=>	cleanvars(date("Y-m-d", strtotime($_POST['emply_dob'])))
								,'emply_email'				=>	cleanvars($_POST['emply_email'])
								,'emply_officialemail'		=>	cleanvars($_POST['emply_officialemail'])
								,'emply_permanent_address'	=>	cleanvars($_POST['emply_permanent_address'])
								,'emply_postal_address'		=>	cleanvars($_POST['emply_postal_address'])
								,'id_modify'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'				=>	date('Y-m-d H:i:s')
							);
	$sqllms = $dblms->Update(EMPLOYEES, $values , "WHERE emply_id = '".cleanvars($_SESSION['userlogininfo']['EMPLYID'])."'");
	$values 		= array(
								 'adm_fullname'				=>	cleanvars($_POST['emply_name'])
								,'adm_email'				=>	cleanvars($_POST['emply_email'])
								,'adm_phone'				=>	cleanvars($_POST['emply_phone'])
							);
	$sqllms = $dblms->Update(ADMINS, $values , "WHERE adm_id  = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'");

	$values = array(
                         'std_dob'   			=> cleanvars(date("Y-m-d", strtotime($_POST['emply_dob'])))
                        ,'std_name'  			=> cleanvars($_POST['emply_name'])
                        ,'std_address_1'		=> cleanvars($_POST['emply_permanent_address'])
                        ,'std_address_2'		=> cleanvars($_POST['emply_postal_address'])
                        ,'std_gender'         	=> cleanvars($_POST['emply_gender'])
                        ,'id_modify'            => cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
                        ,'date_modify'          => date('Y-m-d G:i:s')
					);
	$sqllms = $dblms->Update(STUDENTS, $values , "WHERE std_loginid  = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'");
	
	if($sqllms) { 
		// PHOTO
		if(!empty($_FILES['emply_photo']['name'])) {
			$path_parts 	= pathinfo($_FILES["emply_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG'))) {
				$img_dirAdm 		= 'uploads/images/admin/';
				$img_dirEmply		= 'uploads/images/employees/';
				$img_fileName		= to_seo_url(cleanvars($_POST['emply_name'])).'-'.$_SESSION['userlogininfo']['LOGINIDA'].".".($extension);
				$originalImageAdm	= $img_dirAdm.$img_fileName;
				$originalImageEmply	= $img_dirEmply.$img_fileName;
				$dataImage = array(
									'adm_photo'	=>	$img_fileName, 
								  );
				$sqlAdmin = $dblms->Update(ADMINS, $dataImage, "WHERE adm_id = '".$_SESSION['userlogininfo']['LOGINIDA']."'");	
				unset($sqlAdmin);
				$dataImage = array(
									'emply_photo'	=>	$img_fileName, 
								  );
				$sqlEmply = $dblms->Update(EMPLOYEES, $dataImage, "WHERE emply_id = '".$_SESSION['userlogininfo']['EMPLYID']."'");
				unset($sqlEmply);
				$mode = '0644';
				if (move_uploaded_file($_FILES['emply_photo']['tmp_name'],$originalImageAdm)){
					copy($originalImageAdm, $originalImageEmply);
					$_SESSION['userlogininfo']['LOGINPHOTO'] = SITE_URL.'uploads/images/admin/'.$img_fileName;
				}
				chmod ($originalImageAdm, octdec($mode));
				chmod ($originalImageEmply, octdec($mode));
			}
		}

		// REMARKS
		sendRemark('Employee Profile Updated', '2', $latestID);
		sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>