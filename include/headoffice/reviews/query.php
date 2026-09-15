<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=> "rev_id"
								,'where'		=> array( 
															 'rev_name'			=> cleanvars($_POST['rev_name'])
															,'is_deleted'		=> '0'
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(REVIEWS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'rev_status'		=> cleanvars($_POST['rev_status'])
							,'rev_name'			=> cleanvars($_POST['rev_name'])
							,'rev_detail'		=> cleanvars($_POST['rev_detail'])
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->insert(REVIEWS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			
			// REV_PHOTO
			if(!empty($_FILES['rev_photo']['name'])) {
				$path_parts	= pathinfo($_FILES["rev_photo"]["name"]);
				$extension	= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg', 'jpg', 'png'))) {
					$img_dir 		= 'uploads/images/reviews/';
					$img_fileName	= to_seo_url(cleanvars($_POST['rev_name'])).'-'.$latestID.".".($extension);
					$originalImage	= $img_dir.$img_fileName;
					$dataImage = array(
										'rev_photo'		=>	$img_fileName, 
									);
					$sqlUpdatePhoto = $dblms->Update(REVIEWS, $dataImage, "WHERE rev_id = '".$latestID."'");
					unset($sqlUpdatePhoto);
					$mode = '0644';
					move_uploaded_file($_FILES['rev_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REV_VIDEO
			if (!empty($_FILES['rev_video']['name'])) {
				$path_parts = pathinfo($_FILES["rev_video"]["name"]);
				$extension  = strtolower($path_parts['extension']);

				// ✅ Allow only video formats
				$allowed = array('mp4', 'mov', 'avi', 'mkv', 'webm');

				if (in_array($extension, $allowed)) {
					$video_dir      = 'uploads/videos/reviews/';
					$video_fileName = to_seo_url(cleanvars($_POST['rev_name'])) . '-' . $latestID . '.' . $extension;
					$originalVideo  = $video_dir . $video_fileName;

					$dataVideo = array(
						'rev_video' => $video_fileName,
					);

					$sqlUpdateVideo = $dblms->Update(REVIEWS, $dataVideo, "WHERE rev_id = '" . $latestID . "'");
					unset($sqlUpdateVideo);

					$mode = '0644';
					move_uploaded_file($_FILES['rev_video']['tmp_name'], $originalVideo);
					chmod($originalVideo, octdec($mode));
				}
			}

			
			sendRemark(moduleName(false).' Added', '1', ''.$latestID.'');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=> "rev_id"
								,'where'		=> array( 
															 'rev_name'		=> cleanvars($_POST['rev_name'])
															,'is_deleted'	=> '0'
														)
								,'not_equal' 	=>	array( 
															'rev_id'		=>	cleanvars(LMS_EDIT_ID)
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(REVIEWS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'rev_status'		=> cleanvars($_POST['rev_status'])
							,'rev_name'			=> cleanvars($_POST['rev_name'])
							,'rev_detail'		=> cleanvars($_POST['rev_detail'])
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(REVIEWS , $values , "WHERE rev_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID =	LMS_EDIT_ID;			
			
			// REV_PHOTO
			if(!empty($_FILES['rev_photo']['name'])) {
				$path_parts	= pathinfo($_FILES["rev_photo"]["name"]);
				$extension	= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg', 'jpg', 'png'))) {
					$img_dir 		= 'uploads/images/reviews/';
					$img_fileName	= to_seo_url(cleanvars($_POST['rev_name'])).'-'.$latestID.".".($extension);
					$originalImage	= $img_dir.$img_fileName;
					$dataImage = array(
										'rev_photo'		=>	$img_fileName, 
									);
					$sqlUpdatePhoto = $dblms->Update(REVIEWS, $dataImage, "WHERE rev_id = '".$latestID."'");
					unset($sqlUpdatePhoto);
					$mode = '0644';
					move_uploaded_file($_FILES['rev_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REV_VIDEO
			if (!empty($_FILES['rev_video']['name'])) {
				$path_parts = pathinfo($_FILES["rev_video"]["name"]);
				$extension  = strtolower($path_parts['extension']);

				// ✅ Allow only video formats
				$allowed = array('mp4', 'mov', 'avi', 'mkv', 'webm');

				if (in_array($extension, $allowed)) {
					$video_dir      = 'uploads/videos/reviews/';
					$video_fileName = to_seo_url(cleanvars($_POST['rev_name'])) . '-' . $latestID . '.' . $extension;
					$originalVideo  = $video_dir . $video_fileName;

					$dataVideo = array(
						'rev_video' => $video_fileName,
					);

					$sqlUpdateVideo = $dblms->Update(REVIEWS, $dataVideo, "WHERE rev_id = '" . $latestID . "'");
					unset($sqlUpdateVideo);

					$mode = '0644';
					move_uploaded_file($_FILES['rev_video']['tmp_name'], $originalVideo);
					chmod($originalVideo, octdec($mode));
				}
			}

			sendRemark(moduleName(false).' Updated', '2', ''.$latestID.'');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php", true, 301);
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
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(REVIEWS , $values , "WHERE rev_id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', ''.$latestID.'');
		sessionMsg('Successfully', 'Record Successfully Deleted.', 'danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>