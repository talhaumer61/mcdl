<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=> 'dept_id'
							,'where' 		=> array( 
														 'dept_name'	=>	cleanvars($_POST['dept_name'])
														,'is_deleted'	=>	'0'	
													)
							,'return_type' 	=> 'count'
						); 
	if($dblms->getRows(DEPARTMENTS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:departments.php", true, 301);
		exit();
	}else{
		$values = array(
							 'dept_status'		=>	cleanvars($_POST['dept_status'])
							,'dept_publish'		=>	cleanvars($_POST['dept_publish'])
							,'dept_ordering'	=>	cleanvars($_POST['dept_ordering'])
							,'dept_code'		=>	cleanvars($_POST['dept_code'])
							,'dept_name'		=>	cleanvars($_POST['dept_name'])
							,'dept_href'		=>	to_seo_url($_POST['dept_name'])
							,'dept_intro'		=>	cleanvars($_POST['dept_intro'])
							,'dept_keyword'		=>	cleanvars($_POST['dept_keyword'])
							,'dept_meta'		=>	cleanvars($_POST['dept_meta'])
							,'id_faculty'		=>	cleanvars($_POST['id_faculty'])
							,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=>	date('Y-m-d G:i:s')
						); 
		$sqllms	=	$dblms->insert(DEPARTMENTS, $values);

		if($sqllms){
			// LATEST ID
			$latestID = $dblms->lastestid();

			// ICON
			if(!empty($_FILES['dept_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["dept_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/departments/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'dept_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(DEPARTMENTS, $dataImage, "WHERE dept_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['dept_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['dept_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["dept_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/departments/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'dept_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(DEPARTMENTS, $dataImage, "WHERE dept_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['dept_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark("Department Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:departments.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=>	'dept_id'
							,'where' 		=>	array( 
														 'dept_name'	=>	cleanvars($_POST['dept_name'])
														,'is_deleted'	=>	'0'	
													)
							,'not_equal'	=>	array(
														'dept_id'		=>	cleanvars($_POST['dept_id'])
													)
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(DEPARTMENTS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:departments.php", true, 301);
		exit();
	}else{	
		$values = array(
							 'dept_status'		=>	cleanvars($_POST['dept_status'])
							,'dept_publish'		=>	cleanvars($_POST['dept_publish'])
							,'dept_ordering'	=>	cleanvars($_POST['dept_ordering'])
							,'dept_code'		=>	cleanvars($_POST['dept_code'])
							,'dept_name'		=>	cleanvars($_POST['dept_name'])
							,'dept_href'		=>	to_seo_url($_POST['dept_name'])
							,'dept_intro'		=>	cleanvars($_POST['dept_intro'])
							,'dept_keyword'		=>	cleanvars($_POST['dept_keyword'])
							,'dept_meta'		=>	cleanvars($_POST['dept_meta'])
							,'id_faculty'		=>	cleanvars($_POST['id_faculty'])
							,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(DEPARTMENTS, $values , "WHERE dept_id  = '".cleanvars($_POST['dept_id'])."'");
		if($sqllms) {			
			// LATEST ID
			$latestID = $_POST['dept_id'];

			// ICON
			if(!empty($_FILES['dept_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["dept_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/departments/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'dept_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(DEPARTMENTS, $dataImage, "WHERE dept_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['dept_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['dept_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["dept_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/departments/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['dept_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'dept_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(DEPARTMENTS, $dataImage, "WHERE dept_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['dept_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark("Department Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:departments.php", true, 301);
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
	$sqlDel = $dblms->Update(DEPARTMENTS, $values , "WHERE dept_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) {
		sendRemark("Department Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location: departments.php", true, 301);
		exit();
	}
}
?>