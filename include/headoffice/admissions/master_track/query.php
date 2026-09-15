<?php
// INSERT RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=>	'mas_id'
								,'where'		=>	array( 
															 'mas_name'		=>	cleanvars($_POST['mas_name'])
															,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'is_deleted'	=>	'0'	
														)
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(MASTER_TRACK, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$values = array(
							 'mas_status'			=>	cleanvars($_POST['mas_status'])
							,'mas_name'				=>	cleanvars($_POST['mas_name'])
							,'mas_href'				=>	to_seo_url($_POST['mas_name'])
							,'mas_shortdetail'		=>	cleanvars($_POST['mas_shortdetail'])
							,'mas_detail'			=>	cleanvars($_POST['mas_detail'])
							,'mas_prg_detail'		=>	cleanvars($_POST['mas_prg_detail'])
							,'mas_video'			=>	cleanvars($_POST['mas_video'])
							,'mas_duration'			=>	cleanvars($_POST['mas_duration'])
							,'mas_metakeyword'		=>	cleanvars($_POST['mas_metakeyword'])
							,'mas_metadescription'	=>	cleanvars($_POST['mas_metadescription'])
							,'id_skills'			=>	cleanvars(implode(",",$_POST['id_skills']))
							,'id_mstcat'			=>	cleanvars($_POST['id_mstcat'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
		);
		$sqllms = $dblms->insert(MASTER_TRACK, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();

			foreach ($_POST['id_curs'] as $key => $value) {
				$values = array(
									 'id_mas'			=> cleanvars($latestID)
									,'id_category'		=> cleanvars($_POST['id_cat'][$key])
									,'id_curs'			=> cleanvars($value)
								);
				$sqllms = $dblms->insert(MASTER_TRACK_DETAIL, $values);
			}

			// FILES INDEX
			$files = array(
				 'mas_icon' 
				,'mas_photo'
			);

			// FILES UPLOAD
			foreach($files as $Fkey => $Fval):
				if(!empty($_FILES[$Fval]['name'])):
					$UFiles				= $_FILES[$Fval]['name'];
					$path_parts			= pathinfo($UFiles);
					$extension			= strtolower($path_parts['extension']);

					if(in_array($extension , array('jpeg','jpg','png','pdf', 'doc', 'docx'))):
						// PATH & NAME
						if($Fval == 'mas_icon'){							
							$img_dir	= 'uploads/images/'.$rootDir.moduleName().'/icon/';
						}
						elseif($Fval == 'mas_photo'){							
							$img_dir	= 'uploads/images/'.$rootDir.moduleName().'/';
						}
						$img_fileName	= to_seo_url(cleanvars($_POST['mas_name'])).'-'.$latestID.".".($extension);
						$originalImage	= $img_dir.$img_fileName;
						$dataImage		= array( $Fval => $img_fileName );
						$sqlUpdateImg	= $dblms->Update(MASTER_TRACK, $dataImage, "WHERE mas_id = '".$latestID."'");
						if ($sqlUpdateImg) {
							move_uploaded_file($_FILES[$Fval]['tmp_name'],$originalImage);
						}
					endif;
				endif;
			endforeach;

			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=>	'mas_id'
								,'where'		=>	array( 
															 'mas_name'		=>	cleanvars($_POST['mas_name'])
															,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'is_deleted'	=>	'0'	
														)
								,'not_equal' 	=>	array( 
															'mas_id'		=>	cleanvars(LMS_EDIT_ID)
														)				
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(MASTER_TRACK, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$values = array(
							 'mas_status'			=>	cleanvars($_POST['mas_status'])
							,'mas_name'				=>	cleanvars($_POST['mas_name'])
							,'mas_href'				=>	to_seo_url($_POST['mas_name'])
							,'mas_shortdetail'		=>	cleanvars($_POST['mas_shortdetail'])
							,'mas_detail'			=>	cleanvars($_POST['mas_detail'])
							,'mas_prg_detail'		=>	cleanvars($_POST['mas_prg_detail'])
							,'mas_video'			=>	cleanvars($_POST['mas_video'])
							,'mas_duration'			=>	cleanvars($_POST['mas_duration'])
							,'mas_metakeyword'		=>	cleanvars($_POST['mas_metakeyword'])
							,'mas_metadescription'	=>	cleanvars($_POST['mas_metadescription'])
							,'id_skills'			=>	cleanvars(implode(",",$_POST['id_skills']))
							,'id_mstcat'			=>	cleanvars($_POST['id_mstcat'])
							,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(MASTER_TRACK , $values , "WHERE mas_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID  =	LMS_EDIT_ID;
			// DELETE OLD RECORD
			$dblms->querylms("DELETE FROM ".MASTER_TRACK_DETAIL." WHERE id_mas=".$latestID);

			foreach ($_POST['id_curs'] as $key => $value) {
				$values = array(
									 'id_mas'			=> cleanvars($latestID)
									,'id_category'		=> cleanvars($_POST['id_cat'][$key])
									,'id_curs'			=> cleanvars($value)
								);
				$sqllms = $dblms->insert(MASTER_TRACK_DETAIL, $values);
			}

			// FILES INDEX
			$files = array(
				 'mas_icon' 
				,'mas_photo'
			);		

			// FILES UPLOAD
			foreach($files as $Fkey => $Fval):
				if(!empty($_FILES[$Fval]['name'])):
					$UFiles				= $_FILES[$Fval]['name'];
					$path_parts			= pathinfo($UFiles);
					$extension			= strtolower($path_parts['extension']);

					if(in_array($extension , array('jpeg','jpg','png','pdf', 'doc', 'docx'))):
						// PATH & NAME
						if($Fval == 'mas_icon'){							
							$img_dir	= 'uploads/images/'.$rootDir.moduleName().'/icon/';
						}
						elseif($Fval == 'mas_photo'){							
							$img_dir	= 'uploads/images/'.$rootDir.moduleName().'/';
						}
						$img_fileName	= to_seo_url(cleanvars($_POST['mas_name'])).'-'.$latestID.".".($extension);
						$originalImage	= $img_dir.$img_fileName;
						$dataImage		= array( $Fval => $img_fileName );
						$sqlUpdateImg	= $dblms->Update(MASTER_TRACK, $dataImage, "WHERE mas_id = '".$latestID."'");
						if ($sqlUpdateImg) {
							move_uploaded_file($_FILES[$Fval]['tmp_name'],$originalImage);
						}
					endif;
				endif;
			endforeach;

			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
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
	$sqlDel = $dblms->Update(MASTER_TRACK, $values , "WHERE mas_id  = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "Success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>