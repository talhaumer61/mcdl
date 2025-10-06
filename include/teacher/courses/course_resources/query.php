<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select'		=>	"id"
							,'where' 		=>	array( 
														 'file_name'		=>	cleanvars($_POST['file_name'])
														,'id_type'			=>	cleanvars($_POST['id_type'])
														,'id_curs'			=>	cleanvars(CURS_ID)
														,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														//,'academic_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'
													)
							,'return_type'	=>	'count' 
						); 
	if($dblms->getRows(COURSES_DOWNLOADS, $condition)) {		
		sessionMsg('Error', 'Record Already Exist.', 'danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'status'				=>	cleanvars($_POST['status'])
							,'id_type'				=>	cleanvars($_POST['id_type'])
							,'file_name'			=>	cleanvars($_POST['file_name'])
							,'open_with'			=>	cleanvars($_POST['open_with'])
							,'embedcode'			=>	cleanvars($_POST['embedcode'])
							,'url'					=>	cleanvars($_POST['url'])
							,'detail'				=>	cleanvars($_POST['detail'])
							,'id_curs'				=>	cleanvars(CURS_ID)
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
						//	,'academic_session'     =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_DOWNLOADS, $values);

		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();

			// FILE
			if(!empty($_FILES['file']['name'])) {
				$path_parts 	= pathinfo($_FILES["file"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'svg', 'pdf','ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx', 'rar', 'zip'))) {
					$img_dir 		=	'uploads/files/'.LMS_VIEW.'/';
        			$fileSize 		=	formatSizeUnits($_FILES['file']['size']);
					$originalFile	=	$img_dir.to_seo_url(cleanvars($_POST['file_name'])).'-'.$latestID.".".($extension);
					$fileName		=	to_seo_url(cleanvars($_POST['file_name'])).'-'.$latestID.".".($extension);
					$dataFile		=	array(
												 'file'			=>	$fileName
												,'file_size'	=>	$fileSize
											);
					$sqllmsUpdateFile = $dblms->Update(COURSES_DOWNLOADS, $dataFile, "WHERE id = '".$latestID."'");
					unset($sqllmsUpdateFile);
					$mode = '0644';
					move_uploaded_file($_FILES['file']['tmp_name'],$originalFile);
					chmod ($originalFile, octdec($mode));
				}
			}
			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select'		=>	"id"
							,'where' 		=>	array( 
														 'file_name'		=>	cleanvars($_POST['file_name'])
														,'id_type'			=>	cleanvars($_POST['id_type'])
														,'id_curs'			=>	cleanvars(CURS_ID)
														,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														//,'academic_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'
													)
							//,'search_by'	=>	" AND id !='".cleanvars(LMS_EDIT_ID)."' "
							,'return_type'	=>	'count'
						);
	if($dblms->getRows(COURSES_ASSIGNMENTS, $condition)) {
		sessionMsg('Error', 'Record Already Exist.', 'danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'status'				=>	cleanvars($_POST['status'])
							,'id_type'				=>	cleanvars($_POST['id_type'])
							,'file_name'			=>	cleanvars($_POST['file_name'])
							,'open_with'			=>	cleanvars($_POST['open_with'])
							,'embedcode'			=>	cleanvars($_POST['embedcode'])
							,'url'					=>	cleanvars($_POST['url'])
							,'detail'				=>	cleanvars($_POST['detail'])
							,'id_curs'				=>	cleanvars(CURS_ID)
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							//,'academic_session'     =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_DOWNLOADS, $values, "WHERE id = '".cleanvars(LMS_EDIT_ID)."'");

		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;

			// FILE
			if(!empty($_FILES['file']['name'])) {
				$path_parts 	= pathinfo($_FILES["file"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'svg', 'pdf','ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx', 'rar', 'zip'))) {
					$img_dir 		=	'uploads/files/'.LMS_VIEW.'/';
        			$fileSize 		=	formatSizeUnits($_FILES['file']['size']);
					$originalFile	=	$img_dir.to_seo_url(cleanvars($_POST['file_name'])).'-'.$latestID.".".($extension);
					$fileName		=	to_seo_url(cleanvars($_POST['file_name'])).'-'.$latestID.".".($extension);
					$dataFile		=	array(
												 'file'			=>	$fileName
												,'file_size'	=>	$fileSize
											);
					$sqllmsUpdateFile = $dblms->Update(COURSES_DOWNLOADS, $dataFile, "WHERE id = '".$latestID."'");
					unset($sqllmsUpdateFile);
					$mode = '0644';
					move_uploaded_file($_FILES['file']['tmp_name'],$originalFile);
					chmod ($originalFile, octdec($mode));
				}
			}
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
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
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(COURSES_DOWNLOADS , $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>