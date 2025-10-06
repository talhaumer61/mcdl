<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select' 		=> "cat_id"
								,'where' 		=> array( 
															 'cat_name'		=>	cleanvars($_POST['cat_name'])
															,'is_deleted'	=>	'0'	
														)
								,'return_type' 	=> 'count' 
							); 
	if($dblms->getRows(COURSES_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{

		$values = array(
							 'cat_status'			=>	cleanvars($_POST['cat_status'])
							,'id_type'				=>	cleanvars($_SESSION['id_type'])
							,'cat_code'				=>	cleanvars($_POST['cat_code'])
							,'cat_href'				=>	to_seo_url($_POST['cat_name'])
							,'cat_ordering'			=>	cleanvars($_POST['cat_ordering'])
							,'cat_name'				=>	cleanvars($_POST['cat_name'])
							,'cat_description'		=>	cleanvars($_POST['cat_description'])
							,'cat_meta_keywords'	=>	cleanvars($_POST['cat_meta_keywords'])
							,'cat_meta_description'	=>	cleanvars($_POST['cat_meta_description'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->insert(COURSES_CATEGORIES, $values);

		if($sqllms) { 
			$latestID = $dblms->lastestid();

			// CATEGORY ICON
			if(!empty($_FILES['cat_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["cat_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/categories/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// CATEGORY IMAGE
			if(!empty($_FILES['cat_image']['name'])) {
				$path_parts 	= pathinfo($_FILES["cat_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/categories/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_image'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark('Course Category Added', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select' 		=> "cat_id"
								,'where' 		=> array( 
															 'cat_name'		=>	cleanvars($_POST['cat_name'])
															,'is_deleted'	=>	'0'	
														)
								,'not_equal' 	=> array( 
															'cat_id'		=>	cleanvars($_POST['cat_id'])
														)					
								,'return_type' 	=> 'count'
							);
	if($dblms->getRows(COURSES_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'cat_status'			=>	cleanvars($_POST['cat_status'])
							,'cat_code'				=>	cleanvars($_POST['cat_code'])
							,'cat_href'				=>	to_seo_url($_POST['cat_name'])
							,'cat_ordering'			=>	cleanvars($_POST['cat_ordering'])
							,'cat_name'				=>	cleanvars($_POST['cat_name'])
							,'cat_description'		=>	cleanvars($_POST['cat_description'])
							,'cat_meta_keywords'	=>	cleanvars($_POST['cat_meta_keywords'])
							,'cat_meta_description'	=>	cleanvars($_POST['cat_meta_description'])
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(COURSES_CATEGORIES , $values , "WHERE cat_id  = '".cleanvars($_POST['cat_id'])."'");
		if($sqllms) { 
			$latestID = $_POST['cat_id'];

			// CATEGORY ICON
			if(!empty($_FILES['cat_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["cat_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/categories/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// CATEGORY IMAGE
			if(!empty($_FILES['cat_image']['name'])) {
				$path_parts 	= pathinfo($_FILES["cat_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/categories/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_image'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// REMARKS
			sendRemark('Course Category Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:".moduleName().".php?".$redirection."", true, 301);
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

	$sqlDel = $dblms->Update(COURSES_CATEGORIES, $values , "WHERE cat_id  = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark('Course Category Deleted', '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location:".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>