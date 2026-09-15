<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							'select' 		=>	"qns_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'qns_question'			=>	cleanvars($_POST['qns_question'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars(CURS_ID)
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(QUESTION_BANK, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'qns_status'				=>	cleanvars($_POST['qns_status'])
							,'qns_question'				=>	cleanvars($_POST['qns_question'])
							,'qns_level'				=>	cleanvars($_POST['qns_level'])
							,'qns_type'					=>	cleanvars($_POST['qns_type'])
							,'qns_marks'				=>	cleanvars($_POST['qns_marks'])
							,'id_lesson'				=>	cleanvars(implode(',',$_POST['id_lesson']))
							,'id_session'				=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'id_curs'					=>	cleanvars(CURS_ID)
							,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=>	date('Y-m-d G:i:s')
						);
        $sqllms	= $dblms->insert(QUESTION_BANK, $values);

		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();

			if (!empty($_POST['qns_type']) && $_POST['qns_type'] == 3) {
				foreach ($_POST['qns_option'] as $key => $value) {
					$is_true = (($key+1) == $_POST['is_true']  ? 1 : 0);
					$values = array(
										 'id_qns'			=>	cleanvars($latestID)
										,'qns_option'		=>	cleanvars($value)
										,'option_true'		=>	cleanvars($is_true)				
									); 
					$sqllms = $dblms->insert(QUESTION_BANK_DETAIL, $values);
				}
			}

			// ICON
			if(!empty($_FILES['qns_file']['name'])) {
				$path_parts 			= pathinfo($_FILES["qns_file"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'xlsx', 'xls', 'doc', 'docx', 'ppt', 'pptx', 'png', 'jpg', 'jpeg', 'rar', 'zip'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['qns_question'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['qns_question'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'qns_file' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(QUESTION_BANK, $dataImage, "WHERE qns_id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['qns_file']['tmp_name'],$originalImage);
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
							 'select' 		=>	"qns_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
													 	,'qns_question'			=>	cleanvars($_POST['qns_question'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars(CURS_ID)
													)
							,'not_equal' 	=>	array( 
														'qns_id'				=>	cleanvars(LMS_EDIT_ID)
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(QUESTION_BANK, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'qns_status'				=>	cleanvars($_POST['qns_status'])
							,'qns_question'				=>	cleanvars($_POST['qns_question'])
							,'qns_level'				=>	cleanvars($_POST['qns_level'])
							,'qns_type'					=>	cleanvars($_POST['qns_type'])
							,'qns_marks'				=>	cleanvars($_POST['qns_marks'])
							,'id_lesson'				=>	cleanvars(implode(',',$_POST['id_lesson']))
						   	,'id_modify'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'				=>	date('Y-m-d G:i:s')
					   );
		$sqllms = $dblms->Update(QUESTION_BANK, $values , "WHERE qns_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;
			
			// DELETE OLD DETAIL
			$sqllms	= $dblms->querylms('DELETE FROM '.QUESTION_BANK_DETAIL.' WHERE id_qns = '.cleanvars($latestID).'');

			// DETAIL INSERT
			if (!empty($_POST['qns_type']) && $_POST['qns_type'] == 3) {
				foreach ($_POST['qns_option'] as $key => $value) {
					$is_true = (($key+1) == $_POST['is_true']  ? 1 : 0);
					$values = array(
										 'id_qns'			=>	cleanvars($latestID)
										,'qns_option'		=>	cleanvars($value)
										,'option_true'		=>	cleanvars($is_true)				
									); 
					$sqllms = $dblms->insert(QUESTION_BANK_DETAIL, $values);
				}
			}

			// ICON
			if(!empty($_FILES['qns_file']['name'])) {
				$path_parts 			= pathinfo($_FILES["qns_file"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'xlsx', 'xls', 'doc', 'docx', 'ppt', 'pptx', 'png', 'jpg', 'jpeg', 'rar', 'zip'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['qns_question'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['qns_question'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'qns_file' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(QUESTION_BANK, $dataImage, "WHERE qns_id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['qns_file']['tmp_name'],$originalImage);
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
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	1
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(QUESTION_BANK , $values , "WHERE qns_id = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>