<?php
    require_once("../dbsetting/lms_vars_config.php");
    require_once("../dbsetting/classdbconection.php");
    require_once("../functions/functions.php");
    $dblms = new dblms();
    require_once("../functions/login_func.php");
    checkCpanelLMSALogin();
    if (isset($_POST['deg_name'])) {
        $condition	=	array ( 
								'select' 	=> "deg_id",
								'where' 	=> array( 
														 'deg_name'		=>	cleanvars($_POST['deg_name'])
														,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'	=>	'0'	
													),
								'return_type' 	=> 'count' 
							  ); 
		if($dblms->getRows(DEGREE, $condition)) {
			echo'deg_already_exist';
		}else{
			$values = array(
							 	 'deg_status'			=>	cleanvars($_POST['deg_status'])
								,'deg_draft'			=>	0
							 	,'deg_name'				=>	cleanvars($_POST['deg_name'])
							 	,'id_dept'				=>	cleanvars($_POST['id_dept'])
							 	,'deg_shortdetail'		=>	cleanvars($_POST['deg_shortdetail'])
							 	,'deg_detail'			=>	cleanvars($_POST['deg_detail'])
							 	,'id_degtype'			=>	cleanvars($_POST['id_degtype'])
							 	,'deg_semester'			=>	cleanvars($_POST['deg_semester'])
							 	,'deg_feepersemester'	=>	cleanvars($_POST['deg_feepersemester'])
							 	,'deg_metakeyword'		=>	cleanvars($_POST['deg_metakeyword'])
							 	,'deg_metadescription'	=>	cleanvars($_POST['deg_metadescription'])
								,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
								,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
								,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_added'			=>	date('Y-m-d H:i:s')
			); 
			$sqllms		=	$dblms->insert(DEGREE, $values);
			if($sqllms) { 
				$latestID  =	$dblms->lastestid();
				foreach (get_degree_course_type() as $key => $value):
					$id_curstype 	= cleanvars($_POST[to_seo_url($value).'_id']);
					$id_curs 		= implode(',',$_POST[to_seo_url($value)]);
					$values = array(
										 'id_deg'		=>	$latestID
										,'id_curstype'	=>	$id_curstype
										,'id_curs'		=>	$id_curs
					); 
					$sqllms		=	$dblms->insert(DEGREE_DETAIL, $values);
				endforeach;

				if(!empty($_FILES['deg_icon']['name'])):
					$path_parts 	= pathinfo($_FILES["deg_icon"]["name"]);
					$extension 		= strtolower($path_parts['extension']);
					if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
						$img_dir 		= '../../uploads/images/'.LMS_VIEW.'icon/';
						$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID."_icon_.".($extension);
						$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID."_icon_.".($extension);
						$dataImage = array(
											'deg_icon'		=> $img_fileName, 
											);
						$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
						unset($sqllmsUpdateCNIC);
						move_uploaded_file($_FILES['deg_icon']['tmp_name'],$originalImage);
					}
				endif;
				if(!empty($_FILES['deg_photo']['name'])):
					$path_parts 	= pathinfo($_FILES["deg_photo"]["name"]);
					$extension 		= strtolower($path_parts['extension']);
					if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
						$img_dir 		= '../../uploads/images/'.LMS_VIEW.'photo/';
						$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID."_photo_.".($extension);
						$img_fileName	= to_seo_url(cleanvars($_POST['deg_name'])).'-'.$latestID."_photo_.".($extension);
						$dataImage = array(
											'deg_photo'		=> $img_fileName, 
											);
						$sqllmsUpdateCNIC = $dblms->Update(DEGREE, $dataImage, "WHERE deg_id = '".$latestID."'");
						move_uploaded_file($_FILES['deg_photo']['tmp_name'],$originalImage);
					}
				endif;
				
				echo'deg_added_to_draft';
			}
		}
    }
?>