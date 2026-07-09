<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							'select' 	=> "blog_id",
							'where' 	=> array( 
													 'blog_name'		=>	cleanvars($_POST['blog_name'])
													,'is_deleted'	=>	'0'	
												),
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(BLOGS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{

		$values = array(
						 'blog_status'			=>	cleanvars($_POST['blog_status'])
						,'blog_href'			=>	cleanvars(to_seo_url($_POST['blog_name']))
						,'blog_name'			=>	cleanvars($_POST['blog_name'])
						,'blog_tags'			=>	cleanvars($_POST['blog_tags'])
						,'blog_date'			=>	date("Y-m-d", strtotime($_POST['blog_date']))
						,'blog_description'		=>	cleanvars($_POST['blog_description'])
						,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'			=>	date('Y-m-d G:i:s')
					   ); 
		$sqllms = $dblms->insert(BLOGS, $values);

		if($sqllms) { 
			$latestID  =	$dblms->lastestid();

			// BLOG PHOTO
			if(!empty($_FILES['blog_photo']['name'])) {

				$path_parts 	= pathinfo($_FILES["blog_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'gif'))) {
					$img_dir 		= 'uploads/images/blogs/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['blog_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['blog_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'blog_photo'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(BLOGS, $dataImage, "WHERE blog_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['blog_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}
			// REMARKS
			sendRemark(moduleName(false)." Added", '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {

	$condition	=	array ( 
							'select' 	=> "blog_id",
							'where' 	=> array( 
													 'blog_name'		=>	cleanvars($_POST['blog_name'])
													,'is_deleted'	=>	'0'	
												),
							'not_equal' 	=> array( 
													'blog_id'		=>	cleanvars($_POST['blog_id'])
												),					
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(BLOGS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$latestID  = $_POST['blog_id'];
		$values = array(
						 'blog_status'			=>	cleanvars($_POST['blog_status'])
						,'blog_href'			=>	cleanvars(to_seo_url($_POST['blog_name']))
						,'blog_name'			=>	cleanvars($_POST['blog_name'])
						,'blog_tags'			=>	cleanvars($_POST['blog_tags'])
						,'blog_date'			=>	date("Y-m-d", strtotime($_POST['blog_date']))
						,'blog_description'		=>	cleanvars($_POST['blog_description'])
						,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=>	date('Y-m-d G:i:s')
					   ); 
		$sqllms = $dblms->Update(BLOGS , $values , "WHERE blog_id  = '".cleanvars($latestID)."'");
		if($sqllms) { 

			// BLOG PHOTO
			if(!empty($_FILES['blog_photo']['name'])) {

				$path_parts 	= pathinfo($_FILES["blog_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'gif'))) {
					$img_dir 		= 'uploads/images/blogs/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['blog_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['blog_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'blog_photo'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(BLOGS, $dataImage, "WHERE blog_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['blog_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}
			// REMARKS			
			sendRemark(moduleName(false)." Updated", '2', $latestID);
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

	$sqlDel = $dblms->Update(BLOGS , $values , "WHERE blog_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) { 		
		sendRemark(moduleName(false)." Deleted", '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}
}