<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=> 'prg_id'
							,'where' 		=> array( 
														 'prg_name'	=>	cleanvars($_POST['prg_name'])
														,'is_deleted'	=>	'0'	
													)
							,'return_type' 	=> 'count'
						); 
	if($dblms->getRows(PROGRAMS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "dangerr");
		header("Location: programs.php", true, 301);
		exit();
	}else{
		$values = array(
							 'prg_status'		=>	cleanvars($_POST['prg_status'])
							,'prg_publish'		=>	cleanvars($_POST['prg_publish'])
							,'prg_ordering'		=>	cleanvars($_POST['prg_ordering'])
							,'prg_code'			=>	cleanvars($_POST['prg_code'])
							,'prg_name'			=>	cleanvars($_POST['prg_name'])
							,'prg_shortname'	=>	cleanvars($_POST['prg_shortname'])
							,'prg_href'			=>	to_seo_url($_POST['prg_name'])
							,'prg_intro'		=>	cleanvars($_POST['prg_intro'])
							,'prg_meta'			=>	cleanvars($_POST['prg_meta'])
							,'prg_keyword'		=>	cleanvars($_POST['prg_keyword'])
							,'prg_semesters'	=>	cleanvars($_POST['prg_semesters'])
							,'prg_credithours'	=>	cleanvars($_POST['prg_credithours'])
							,'prg_duration'		=>	cleanvars($_POST['prg_duration'])
							,'prg_detail'		=>	cleanvars($_POST['prg_detail'])
							,'prg_remarks'		=>	cleanvars($_POST['prg_remarks'])
							,'id_dept'			=>	cleanvars($_POST['id_dept'])
							,'id_faculty'		=>	cleanvars($_POST['id_faculty'])
							,'id_cat'			=>	cleanvars($_POST['id_cat'])
							,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=>	date('Y-m-d G:i:s')
						); 
		$sqllms	=	$dblms->insert(PROGRAMS, $values);

		if($sqllms){
			// LATEST ID
			$latestID = $dblms->lastestid();

			// ICON
			if(!empty($_FILES['prg_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["prg_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'prg_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS, $dataImage, "WHERE prg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['prg_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['prg_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["prg_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'prg_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS, $dataImage, "WHERE prg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['prg_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark("Program Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: programs.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=> 'prg_id'
							,'where' 		=>	array( 
														 'prg_name'		=>	cleanvars($_POST['prg_name'])
														,'is_deleted'	=>	'0'	
													)
							,'not_equal'	=>	array(
														'prg_id'		=>	cleanvars($_POST['prg_id'])
													)
							,'return_type' 	=> 'count'
						); 
	if($dblms->getRows(PROGRAMS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "dangerr");
		header("Location: programs.php", true, 301);
		exit();
	}else{
		$values = array(
							 'prg_status'		=>	cleanvars($_POST['prg_status'])
							,'prg_publish'		=>	cleanvars($_POST['prg_publish'])
							,'prg_ordering'		=>	cleanvars($_POST['prg_ordering'])
							,'prg_code'			=>	cleanvars($_POST['prg_code'])
							,'prg_name'			=>	cleanvars($_POST['prg_name'])
							,'prg_shortname'	=>	cleanvars($_POST['prg_shortname'])
							,'prg_href'			=>	to_seo_url($_POST['prg_name'])
							,'prg_intro'		=>	cleanvars($_POST['prg_intro'])
							,'prg_meta'			=>	cleanvars($_POST['prg_meta'])
							,'prg_keyword'		=>	cleanvars($_POST['prg_keyword'])
							,'prg_semesters'	=>	cleanvars($_POST['prg_semesters'])
							,'prg_credithours'	=>	cleanvars($_POST['prg_credithours'])
							,'prg_duration'		=>	cleanvars($_POST['prg_duration'])
							,'prg_detail'		=>	cleanvars($_POST['prg_detail'])
							,'prg_remarks'		=>	cleanvars($_POST['prg_remarks'])
							,'id_dept'			=>	cleanvars($_POST['id_dept'])
							,'id_faculty'		=>	cleanvars($_POST['id_faculty'])
							,'id_cat'			=>	cleanvars($_POST['id_cat'])
							,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(PROGRAMS, $values , "WHERE prg_id  = '".cleanvars($_POST['prg_id'])."'");
		if($sqllms) {			
			// LATEST ID
			$latestID = $_POST['prg_id'];

			// ICON
			if(!empty($_FILES['prg_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["prg_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'prg_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS, $dataImage, "WHERE prg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['prg_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['prg_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["prg_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['prg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'prg_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS, $dataImage, "WHERE prg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['prg_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark("Program Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: programs.php", true, 301);
			exit();
		}
	}	
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {	
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(PROGRAMS, $values , "WHERE prg_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) {
		sendRemark("Program Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location: programs.php", true, 301);
		exit();
	}
}
?>