<?php
// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestId = $_GET['deleteid'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(TEACHER_INTEREST , $values , "WHERE id  = '".cleanvars($latestId)."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', cleanvars($latestId));
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>