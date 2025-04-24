<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'id_degree'			=>	cleanvars($_POST['id_degree'])
														,'program'				=>	cleanvars($_POST['program'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_EDUCATIONS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'				=>	1
									,'id_degree'			=>	cleanvars($_POST['id_degree'])
									,'program'				=>	cleanvars($_POST['program'])
									,'subjects'				=>	cleanvars($_POST['subjects'])
									,'institute'			=>	cleanvars($_POST['institute'])
									,'grade'				=>	cleanvars($_POST['grade'])
									,'Year'					=>	cleanvars($_POST['year'])
									,'resultcard'			=>	cleanvars($_POST['resultcard'])
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
								);
		$sqllms	= $dblms->insert(EMPLOYEE_EDUCATIONS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// ICON
			if(!empty($_FILES['resultcard']['name'])) {
				$path_parts 			= pathinfo($_FILES["resultcard"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['program'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['program'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'resultcard' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEE_EDUCATIONS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['resultcard']['tmp_name'],$originalImage);
					}
				}
			}
			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'id_degree'			=>	cleanvars($_POST['id_degree'])
														,'program'				=>	cleanvars($_POST['program'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'id'					=>	cleanvars(LMS_EDIT_ID)
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_EDUCATIONS, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'id_degree'			=>	cleanvars($_POST['id_degree'])
									,'program'				=>	cleanvars($_POST['program'])
									,'subjects'				=>	cleanvars($_POST['subjects'])
									,'institute'			=>	cleanvars($_POST['institute'])
									,'grade'				=>	cleanvars($_POST['grade'])
									,'year'					=>	cleanvars($_POST['year'])
									,'resultcard'			=>	cleanvars($_POST['resultcard'])
								);
		$sqllms = $dblms->Update(EMPLOYEE_EDUCATIONS, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;
			// ICON
			if(!empty($_FILES['resultcard']['name'])) {
				$path_parts 			= pathinfo($_FILES["resultcard"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'png', 'jpg'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['program'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['program'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'resultcard' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEE_EDUCATIONS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['resultcard']['tmp_name'],$originalImage);
					}
				}
			}
			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];

	$values = array(
						'is_deleted'	=>	1 
					);   
	$sqlDel = $dblms->Update(EMPLOYEE_EDUCATIONS, $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>