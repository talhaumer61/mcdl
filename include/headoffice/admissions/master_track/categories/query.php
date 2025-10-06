<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select'		=>	"mstcat_id"
							,'where'		=>	array( 
														 'mstcat_name'	=>	cleanvars($_POST['mstcat_name'])
														,'is_deleted'	=>	'0'	
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(MASTER_TRACK_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$values = array(
							 'mstcat_status'			=>	cleanvars($_POST['mstcat_status'])
							,'mstcat_code'				=>	cleanvars($_POST['mstcat_code'])
							,'mstcat_href'				=>	to_seo_url($_POST['mstcat_name'])
							,'mstcat_name'				=>	cleanvars($_POST['mstcat_name'])
							,'mstcat_description'		=>	cleanvars($_POST['mstcat_description'])
							,'mstcat_meta_keywords'		=>	cleanvars($_POST['mstcat_meta_keywords'])
							,'mstcat_meta_description'	=>	cleanvars($_POST['mstcat_meta_description'])
							,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->insert(MASTER_TRACK_CATEGORIES, $values);

		if($sqllms) { 
			$latestID = $dblms->lastestid();

			// FILES INDEX
			$files = array(
				 'mstcat_icon' 
				,'mstcat_image'
			);		

			// FILES UPLOAD
			foreach($files as $Fkey => $Fval):
				if(!empty($_FILES[$Fval]['name'])):
					$UFiles			= $_FILES[$Fval]['name'];
					$path_parts		= pathinfo($UFiles);
					$extension		= strtolower($path_parts['extension']);

					if(in_array($extension , array('jpeg','jpg','png','pdf', 'doc', 'docx'))):
						// PATH & NAME
						if($Fval == 'mstcat_icon'){
							$img_dir		= 'uploads/images/admissions/master_track/categories/icons/';
							$img_fileName	= to_seo_url(cleanvars($_POST['mstcat_name'])).'-'.$latestID.'-'.$Fkey.".".($extension);
							$originalImage	= $img_dir.$img_fileName;
						}
						elseif($Fval == 'mstcat_image'){
							$img_dir		= 'uploads/images/admissions/master_track/categories/';
							$img_fileName	= to_seo_url(cleanvars($_POST['mstcat_name'])).'-'.$latestID.'-'.$Fkey.".".($extension);
							$originalImage	= $img_dir.$img_fileName;
						}
						// UPDATE & MOVE
						$dataImage 			= array( $Fval => $img_fileName );
						$sqlUpdateImg 		= $dblms->Update(MASTER_TRACK_CATEGORIES, $dataImage, "WHERE mstcat_id = '".$latestID."'");
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

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select'		=>	"mstcat_id"
							,'where'		=>	array( 
														 'mstcat_name'	=>	cleanvars($_POST['mstcat_name'])
														,'is_deleted'	=>	'0'	
													)
							,'not_equal' 	=>	array( 
														'mstcat_id'		=>	LMS_EDIT_ID
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(MASTER_TRACK_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{	
		$values = array(
							 'mstcat_status'			=>	cleanvars($_POST['mstcat_status'])
							,'mstcat_code'				=>	cleanvars($_POST['mstcat_code'])
							,'mstcat_href'				=>	to_seo_url($_POST['mstcat_name'])
							,'mstcat_name'				=>	cleanvars($_POST['mstcat_name'])
							,'mstcat_description'		=>	cleanvars($_POST['mstcat_description'])
							,'mstcat_meta_keywords'		=>	cleanvars($_POST['mstcat_meta_keywords'])
							,'mstcat_meta_description'	=>	cleanvars($_POST['mstcat_meta_description'])
							,'id_modify'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'				=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(MASTER_TRACK_CATEGORIES , $values , "WHERE mstcat_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID  = LMS_EDIT_ID;

			// FILES INDEX
			$files = array(
				 'mstcat_icon' 
				,'mstcat_image'
			);		

		   	// FILES UPLOAD
		   	foreach($files as $Fkey => $Fval):
			   	if(!empty($_FILES[$Fval]['name'])):
				   $UFiles			= $_FILES[$Fval]['name'];
				   $path_parts		= pathinfo($UFiles);
				   $extension		= strtolower($path_parts['extension']);

					if(in_array($extension , array('jpeg','jpg','png','pdf', 'doc', 'docx'))):
						// PATH & NAME
						if($Fval == 'mstcat_icon'){
							$img_dir		= 'uploads/images/admissions/master_track/categories/icons/';
							$img_fileName	= to_seo_url(cleanvars($_POST['mstcat_name'])).'-'.$latestID.'-'.$Fkey.".".($extension);
							$originalImage	= $img_dir.$img_fileName;
						}
						elseif($Fval == 'mstcat_image'){
							$img_dir		= 'uploads/images/admissions/master_track/categories/';
							$img_fileName	= to_seo_url(cleanvars($_POST['mstcat_name'])).'-'.$latestID.'-'.$Fkey.".".($extension);
							$originalImage	= $img_dir.$img_fileName;
						}
						// UPDATE & MOVE
						$dataImage 			= array( $Fval => $img_fileName );
						$sqlUpdateImg 		= $dblms->Update(MASTER_TRACK_CATEGORIES, $dataImage, "WHERE mstcat_id = '".$latestID."'");
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

	$sqlDel = $dblms->Update(MASTER_TRACK_CATEGORIES, $values , "WHERE mstcat_id  = '".cleanvars($latestID)."'");

	if($sqlDel) { 		
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "danger");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>