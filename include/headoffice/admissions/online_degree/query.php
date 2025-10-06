<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=>	'deg_id'
								,'where'		=>	array( 
															 'deg_name'		=>	cleanvars($_POST['deg_name'])
															,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'is_deleted'	=>	'0'	
														)
								,'return_type' 	=> 'count' 
							); 
	if($dblms->getRows(DEGREE, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$values = array(
							 'deg_status'			=>	cleanvars($_POST['deg_status'])
							,'deg_name'				=>	cleanvars($_POST['deg_name'])
							,'deg_shortdetail'		=>	cleanvars($_POST['deg_shortdetail'])
							,'deg_detail'			=>	cleanvars($_POST['deg_detail'])
							,'id_degtype'			=>	cleanvars($_POST['id_degtype'])
							,'deg_semester'			=>	cleanvars($_POST['deg_semester'])
							,'deg_feepersemester'	=>	cleanvars($_POST['deg_feepersemester'])
							,'deg_metakeyword'		=>	cleanvars($_POST['deg_metakeyword'])
							,'deg_metadescription'	=>	cleanvars($_POST['deg_metadescription'])
							,'id_faculty'			=>	cleanvars($_POST['id_faculty'])
							,'deg_startdate'		=>	cleanvars($_POST['deg_startdate'])
							,'deg_enddate'			=>	cleanvars($_POST['deg_enddate'])
							,'deg_video'			=>	cleanvars($_POST['deg_video'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
		); 
		$sqllms = $dblms->insert(DEGREE, $values);
		if($sqllms) { 
			$latestID  = $dblms->lastestid();
			foreach ($_POST['id_curs'] as $curstype => $value):
				foreach ($value as $cat => $courses) {
					$values = array(
										 'id_deg'		=>	$latestID
										,'id_curstype'	=>	$curstype
										,'id_cat'		=>	$cat
										,'id_curs'		=>	implode(',',$courses)
									); 
					$sqllms = $dblms->insert(DEGREE_DETAIL, $values);
				}
			endforeach;
			if(!empty($_FILES['deg_icon']['name'])):
				$path_parts 	= pathinfo($_FILES["deg_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/'.$rootDir.moduleName().'/'.'icon/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array('deg_icon' => $img_fileName);
					$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					move_uploaded_file($_FILES['deg_icon']['tmp_name'],$originalImage);
				}
			endif;
			if(!empty($_FILES['deg_photo']['name'])):
				$path_parts 	= pathinfo($_FILES["deg_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/'.$rootDir.moduleName().'/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array('deg_photo' => $img_fileName);
					$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
					move_uploaded_file($_FILES['deg_photo']['tmp_name'],$originalImage);
				}
			endif;
			// REMARKS
			sendRemark(moduleName(false).' Updated', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=>	'deg_id"'
								,'where'		=>	array( 
															 'deg_name'		=>	cleanvars($_POST['deg_name'])
															,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'is_deleted'	=>	'0'	
														)
								,'not_equal' 	=>	array( 
															'deg_id'		=>	cleanvars(LMS_EDIT_ID)
														)					
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(DEGREE, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{	
		$values = array(
							 'deg_status'			=>	cleanvars($_POST['deg_status'])
							,'deg_name'				=>	cleanvars($_POST['deg_name'])
							,'id_cat'				=>	cleanvars($_POST['id_cat'])
							,'deg_shortdetail'		=>	cleanvars($_POST['deg_shortdetail'])
							,'deg_detail'			=>	cleanvars($_POST['deg_detail'])
							,'id_degtype'			=>	cleanvars($_POST['id_degtype'])
							,'deg_semester'			=>	cleanvars($_POST['deg_semester'])
							,'deg_feepersemester'	=>	cleanvars($_POST['deg_feepersemester'])
							,'deg_metakeyword'		=>	cleanvars($_POST['deg_metakeyword'])
							,'deg_metadescription'	=>	cleanvars($_POST['deg_metadescription'])
							,'id_faculty'			=>	cleanvars($_POST['id_faculty'])
							,'deg_startdate'		=>	cleanvars($_POST['deg_startdate'])
							,'deg_enddate'			=>	cleanvars($_POST['deg_enddate'])
							,'deg_video'			=>	cleanvars($_POST['deg_video'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(DEGREE , $values , "WHERE deg_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID  =	LMS_EDIT_ID;
			$dblms->querylms("DELETE FROM ".DEGREE_DETAIL." WHERE id_deg=".$latestID);
			foreach ($_POST['id_curs'] as $curstype => $value):
				foreach ($value as $cat => $courses) {
					$values = array(
										 'id_deg'		=>	$latestID
										,'id_curstype'	=>	$curstype
										,'id_cat'		=>	$cat
										,'id_curs'		=>	implode(',',$courses)
					); 
					$sqllms		=	$dblms->insert(DEGREE_DETAIL, $values);
				}
			endforeach;
			if(!empty($_FILES['deg_icon']['name'])):
				$path_parts 	= pathinfo($_FILES["deg_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/'.$rootDir.moduleName().'/icon/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array('deg_icon' => $img_fileName);
					$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					move_uploaded_file($_FILES['deg_icon']['tmp_name'],$originalImage);
				}
			endif;
			if(!empty($_FILES['deg_photo']['name'])):
				$path_parts 	= pathinfo($_FILES["deg_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/'.$rootDir.moduleName().'/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID.".".($extension);
					$dataImage = array('deg_photo' => $img_fileName);
					$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
					move_uploaded_file($_FILES['deg_photo']['tmp_name'],$originalImage);
				}
			endif;
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:".moduleName().".php", true, 301);
			exit();
		}
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
	$sqlDel = $dblms->Update(DEGREE, $values , "WHERE deg_id  = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>