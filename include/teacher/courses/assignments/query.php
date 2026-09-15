<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select'		=>	"id"
							,'where' 		=>	array( 
														 'caption'			=>	cleanvars($_POST['caption'])
														,'id_curs'			=>	cleanvars(CURS_ID)
														,'academic_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'
													)
							,'return_type'	=>	'count' 
						); 
	if($dblms->getRows(COURSES_ASSIGNMENTS, $condition)) {		
		sessionMsg('Error', 'Record Already Exist.', 'danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'status'				=>	cleanvars($_POST['status'])
							,'is_midterm'			=>	cleanvars($_POST['is_midterm'])
							,'id_week'				=>	cleanvars($_POST['id_week'])
							,'caption'				=>	cleanvars($_POST['caption'])
							,'total_marks'			=>	cleanvars($_POST['total_marks'])
							,'passing_marks'		=>	cleanvars($_POST['passing_marks'])
							,'date_start'			=>	date('Y-m-d' , strtotime($_POST['date_start']))
							,'date_end'				=>	date('Y-m-d' , strtotime($_POST['date_end']))
							,'detail'				=>	cleanvars($_POST['detail'])
							,'id_curs'				=>	cleanvars(CURS_ID)
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'     =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_ASSIGNMENTS, $values);

		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();

			// FILE
			if(!empty($_FILES['fileattach']['name'])) {
				$path_parts 	= pathinfo($_FILES["fileattach"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg', 'pdf','ppt', 'docx', 'xls', 'xlsx'))) {
					$img_dir 		= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['caption'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['caption'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'fileattach'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_ASSIGNMENTS, $dataImage, "WHERE id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['fileattach']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
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
							 'select'		=>	"id"
							,'where' 		=>	array( 
														 'caption'			=>	cleanvars($_POST['caption'])
														,'id_curs'			=>	cleanvars(CURS_ID)
														,'academic_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'
													)
							,'not_equal'	=>	array(
														'id'				=>	cleanvars(LMS_EDIT_ID)
													)
							,'return_type'	=>	'count' 
						); 
	if($dblms->getRows(COURSES_ASSIGNMENTS, $condition)) {
		sessionMsg('Error', 'Record Already Exist.', 'danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'status'				=>	cleanvars($_POST['status'])
							,'is_midterm'			=>	cleanvars($_POST['is_midterm'])
							,'id_week'				=>	cleanvars($_POST['id_week'])
							,'caption'				=>	cleanvars($_POST['caption'])
							,'total_marks'			=>	cleanvars($_POST['total_marks'])
							,'passing_marks'		=>	cleanvars($_POST['passing_marks'])
							,'date_start'			=>	date('Y-m-d' , strtotime($_POST['date_start']))
							,'date_end'				=>	date('Y-m-d' , strtotime($_POST['date_end']))
							,'detail'				=>	cleanvars($_POST['detail'])
							,'id_curs'				=>	cleanvars(CURS_ID)
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'     =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_ASSIGNMENTS, $values, "WHERE id = '".cleanvars(LMS_EDIT_ID)."'");

		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;

			// FILE
			if(!empty($_FILES['fileattach']['name'])) {
				$path_parts 	= pathinfo($_FILES["fileattach"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'svg', 'pdf','ppt', 'docx', 'xls', 'xlsx'))) {
					$img_dir 		= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['caption'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['caption'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'fileattach'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_ASSIGNMENTS, $dataImage, "WHERE id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['fileattach']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
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
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(COURSES_ASSIGNMENTS , $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>