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

		if($sqllms) { 

			// UPDATE PROFILE IMAGE
			if(!empty($_FILES['adm_photo']['name'])) {

				$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG'))) {
					$img_dir 		= 'uploads/images/admin/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'])).'-'.$adm_id.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'])).'-'.$adm_id.".".($extension);
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

			$_SESSION['userlogininfo']['LOGINMAIL'] 	=	$_POST['adm_email'];
			$_SESSION['userlogininfo']['LOGINPHONE'] 	=	$_POST['adm_phone'];
			$_SESSION['userlogininfo']['LOGINNAME'] 	=	$_POST['adm_fullname'];
			
			$remarks = 'Profile Setting Has Changed#:'.$adm_id;
			$values = array (
								"id_user"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"filename"	=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								,"action"	=>	'1'
								,"dated"	=>	date('Y-m-d G:i:s')
								,"ip"		=>	cleanvars($ip)
								,"remarks"	=>	cleanvars($remarks)
							);
			$sqllms  = $dblms->insert(LOGS, $values);
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: profile.php", true, 301);
			exit();
		}
	}
	//CHANGE PASSWORD
	if(isset($_POST['chnage_pass'])) { 
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
			$remarks = 'Change Password =: "'.cleanvars($_POST['cnfrm_pass']).'" details';
			$valuesLog = array(
								'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								,'action'		=>	'2'
								,'dated'		=> 	date('Y-m-d G:i:s')
								,'ip'			=>	cleanvars($ip)
								,'remarks'		=>	cleanvars($remarks)
								,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							);   
			$sqllmslog  = 	$dblms->insert(LOGS, $valuesLog);
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Password Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: profile.php", true, 301);
			exit();
		}
	}
?>