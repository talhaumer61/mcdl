<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select'		=>	"faculty_id"
							,'where' 		=>	array( 
														 'faculty_name'		=>	cleanvars($_POST['faculty_name'])
														,'is_deleted'		=>	'0'	
													)
							,'return_type'	=>	'count' 
						); 
	if($dblms->getRows(FACULTIES, $condition)) {		
		sessionMsg('Error', 'Record Already Exist.', 'danger');
		header("Location: faculties.php", true, 301);
		exit();
	}else{
		$values = array(
							 'faculty_status'		=>	cleanvars($_POST['faculty_status'])
							,'faculty_publish'		=>	cleanvars($_POST['faculty_publish'])
							,'faculty_ordering'		=>	cleanvars($_POST['faculty_ordering'])
							,'faculty_code'			=>	cleanvars($_POST['faculty_code'])
							,'faculty_name'			=>	cleanvars($_POST['faculty_name'])
							,'faculty_href'			=>	to_seo_url($_POST['faculty_name'])
							,'faculty_intro'		=>	cleanvars($_POST['faculty_intro'])
							,'faculty_keyword'		=>	cleanvars($_POST['faculty_keyword'])
							,'faculty_meta'			=>	cleanvars($_POST['faculty_meta'])
							,'faculty_email'		=>	cleanvars($_POST['faculty_email'])
							,'faculty_phone'		=>	cleanvars($_POST['faculty_phone'])
							,'faculty_address'		=>	cleanvars($_POST['faculty_address'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(FACULTIES, $values);

		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();

			// ICON
			if(!empty($_FILES['faculty_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["faculty_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/faculties/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'faculty_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(FACULTIES, $dataImage, "WHERE faculty_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['faculty_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// IMAGE
			if(!empty($_FILES['faculty_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["faculty_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/faculties/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'faculty_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(FACULTIES, $dataImage, "WHERE faculty_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['faculty_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// REMARKS
			sendRemark('Faculty Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: faculties.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							'select' 		=>	"faculty_id"
							,'where' 		=>	array( 
														'faculty_name'		=>	cleanvars($_POST['faculty_name'])
														,'is_deleted'		=>	'0'	
													)
							,'not_equal' 	=>	array( 
														'faculty_id'		=>	cleanvars($_POST['faculty_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(FACULTIES, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: faculties.php", true, 301);
		exit();
	}else{
		$values = array(
							 'faculty_status'		=>	cleanvars($_POST['faculty_status'])
							,'faculty_publish'		=>	cleanvars($_POST['faculty_publish'])
							,'faculty_ordering'		=>	cleanvars($_POST['faculty_ordering'])
							,'faculty_code'			=>	cleanvars($_POST['faculty_code'])
							,'faculty_name'			=>	cleanvars($_POST['faculty_name'])
							,'faculty_href'			=>	to_seo_url($_POST['faculty_name'])
							,'faculty_intro'		=>	cleanvars($_POST['faculty_intro'])
							,'faculty_keyword'		=>	cleanvars($_POST['faculty_keyword'])
							,'faculty_meta'			=>	cleanvars($_POST['faculty_meta'])
							,'faculty_email'		=>	cleanvars($_POST['faculty_email'])
							,'faculty_phone'		=>	cleanvars($_POST['faculty_phone'])
							,'faculty_address'		=>	cleanvars($_POST['faculty_address'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(FACULTIES, $values , "WHERE faculty_id  = '".cleanvars($_POST['faculty_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['faculty_id'];

			// CATEGORY ICON
			if(!empty($_FILES['faculty_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["faculty_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/faculties/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'faculty_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(FACULTIES, $dataImage, "WHERE faculty_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['faculty_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// CATEGORY IMAGE
			if(!empty($_FILES['faculty_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["faculty_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/faculties/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['faculty_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'faculty_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(FACULTIES, $dataImage, "WHERE faculty_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['faculty_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// REMARKS
			sendRemark('Faculty Updated ID:'.$latestID, '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: faculties.php", true, 301);
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
	$sqlDel = $dblms->Update(FACULTIES , $values , "WHERE faculty_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark('Faculty Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: faculties.php", true, 301);
		exit();
	}
}
?>