<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition		=	 array ( 
									 'select' 		=> 'id'
									,'where' 		=> array( 
																 'is_deleted'	=> '0'
																,'email'  		=> cleanvars($_POST['emply_email'])
															)
									,'return_type' 	=> 'count'
								);
	if($dblms->getRows(INTERNS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$year = date('Y');
		$prefix = "INT-$year-";

		$sqlQuery = $dblms->querylms("
			SELECT 
				CONCAT(
					'$prefix',
					LPAD(
						IFNULL(
							MAX(CAST(SUBSTRING(ref_no, -5) AS UNSIGNED)),
							0
						) + 1,
						5,
						'0'
					)
				) AS next_ref_no
			FROM ".INTERNS."
			WHERE is_deleted = 0
			AND ref_no LIKE '$prefix%'
		");
		$valQuery = mysqli_fetch_array($sqlQuery);

		// FINAL RESULT
		$ref_no = $valQuery['next_ref_no'];

		$values = array(
			'ref_no'     			=> $ref_no,
			'full_name'     		=> cleanvars($_POST['full_name']),
			'father_name'   		=> cleanvars($_POST['father_name']),
			'gender' 				=> cleanvars($_POST['gender']),
			'highest_qualification' => cleanvars($_POST['highest_qualification']),
			'remarks'       		=> cleanvars($_POST['remarks']),

			'status'        		=> cleanvars($_POST['status']),
			'id_role'       		=> cleanvars($_POST['id_role']),
			'applied_date'   		=> date("Y-m-d", strtotime($_POST['applied_date'])),
			'selection_date' 		=> !empty($_POST['selection_date']) ? date("Y-m-d", strtotime($_POST['selection_date'])) : null,
			'joining_date'   		=> !empty($_POST['joining_date']) ? date("Y-m-d", strtotime($_POST['joining_date'])) : null,
			'leaving_date'   		=> !empty($_POST['leaving_date']) ? date("Y-m-d", strtotime($_POST['leaving_date'])) : null,

			'cnic'          		=> cleanvars($_POST['cnic']),
			'phone'         		=> cleanvars($_POST['phone']),
			'email'         		=> cleanvars($_POST['emply_email']),
			'address'       		=> cleanvars($_POST['address']),

			'id_added'      		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
			'date_added'    		=> date('Y-m-d H:i:s')
		);
		$sqllms	= $dblms->insert(INTERNS, $values);

		if($sqllms){
			// LATEST ID
			$latestID = $dblms->lastestid();
			
			// HREF
			$sqlEmpHref = $dblms->Update(INTERNS, ['href' => to_seo_url($_POST['full_name'].'-'.date('y').'-'.$latestID)], "WHERE id = '".$latestID."'");

			// PHOTO
			if(!empty($_FILES['photo']['name'])) {
				$path_parts 			= pathinfo($_FILES["photo"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 			= 'uploads/images/interns/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['full_name'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['full_name'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'photo' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(INTERNS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['photo']['tmp_name'],$originalImage);
					}
				}
			}

			// REMARKS
			sendRemark("Intern Added", '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=>	'id'
							,'where' 		=>	array( 
														 'is_deleted'	=> '0'
														,'email'  		=> cleanvars($_POST['emply_email'])
													)
							,'not_equal'	=>	array(
														'id'		=>	cleanvars(LMS_EDIT_ID)
													)
							,'return_type' 	=>	'count'
						);
	if($dblms->getRows(INTERNS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: interns.php?id=".cleanvars(LMS_EDIT_ID)."", true, 301);
		exit();
	} else {
		$values = array(
			'full_name'     		=> cleanvars($_POST['full_name']),
			'father_name'   		=> cleanvars($_POST['father_name']),
			'gender' 				=> cleanvars($_POST['gender']),
			'highest_qualification' => cleanvars($_POST['highest_qualification']),
			'remarks'       		=> cleanvars($_POST['remarks']),

			'status'        		=> cleanvars($_POST['status']),
			'id_role'       		=> cleanvars($_POST['id_role']),
			'applied_date'   		=> date("Y-m-d", strtotime($_POST['applied_date'])),
			'selection_date' 		=> !empty($_POST['selection_date']) ? date("Y-m-d", strtotime($_POST['selection_date'])) : null,
			'joining_date'   		=> !empty($_POST['joining_date']) ? date("Y-m-d", strtotime($_POST['joining_date'])) : null,
			'leaving_date'   		=> !empty($_POST['leaving_date']) ? date("Y-m-d", strtotime($_POST['leaving_date'])) : null,

			'cnic'          		=> cleanvars($_POST['cnic']),
			'phone'         		=> cleanvars($_POST['phone']),
			'email'         		=> cleanvars($_POST['emply_email']),
			'address'       		=> cleanvars($_POST['address']),
			'id_modify'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
			'date_modify'			=>	date('Y-m-d G:i:s'),
		);
		$sqllms = $dblms->Update(INTERNS, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		
		if($sqllms) {			
			// LATEST ID
			$latestID = LMS_EDIT_ID;

			// ICON
			if(!empty($_FILES['photo']['name'])) {
				$path_parts 			= pathinfo($_FILES["photo"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 			= 'uploads/images/interns/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['full_name'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['full_name'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'photo' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(INTERNS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['photo']['tmp_name'],$originalImage);
					}
				}
			}

			// REMARKS
			sendRemark("Intern Updated", '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars(LMS_IP)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(INTERNS, $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) {
		sendRemark("Intern Deleted", '3', $_GET['deleteid']);
		sessionMsg("success", "Record Successfully Deleted.", "success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}

// ADD DOCUMENT
if (isset($_POST['submit_add_document'])) {

    /* ------------------------------------
     * REQUIRED FIELD CHECK
     * ------------------------------------ */
    if (
        empty($_POST['status']) ||
        empty($_POST['doc_type']) ||
        empty($_POST['doc_title']) ||
        empty($_POST['id_intern']) ||
        empty($_FILES['doc_file']['name'])
    ) {
        sessionMsg("Error", "All required fields are mandatory.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    /* ------------------------------------
     * FILE VALIDATION
     * ------------------------------------ */
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $maxSize = 300 * 1024; // 300 KB

    $fileInfo  = pathinfo($_FILES['doc_file']['name']);
    $extension = strtolower($fileInfo['extension']);

    if (!in_array($extension, $allowedExtensions)) {
        sessionMsg("Error", "Invalid file type. Allowed: JPG, PNG, PDF.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if ($_FILES['doc_file']['size'] > $maxSize) {
        sessionMsg("Error", "File size must not exceed 300 KB.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    /* ------------------------------------
     * FILE UPLOAD
     * ------------------------------------ */
    $uploadDir = 'uploads/images/intern_documents/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $safeTitle = to_seo_url($_POST['doc_title']);
    $fileName  = $safeTitle.'-'.time().'.'.$extension;
    $fullPath  = $uploadDir.$fileName;

    if (!move_uploaded_file($_FILES['doc_file']['tmp_name'], $fullPath)) {
        sessionMsg("Error", "File upload failed.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    /* ------------------------------------
     * INSERT INTO DATABASE
     * ------------------------------------ */
    $values = [
        'status'     => cleanvars($_POST['status']),
        'id_intern'  => cleanvars($_POST['id_intern']),
        'doc_title'  => cleanvars($_POST['doc_title']),
        'doc_type'   => cleanvars($_POST['doc_type']),
        'doc_file'   => cleanvars($fileName),
        'id_added'   => cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
        'date_added' => date('Y-m-d H:i:s'),
    ];

    $insert = $dblms->insert(INTERN_DOCS, $values);

    if ($insert) {
		$latestID = $dblms->lastestid();
        sendRemark("Intern Document Added", '1', $latestID);
        sessionMsg("Success", "Document added successfully.", "success");
    } else {
        sessionMsg("Error", "Database error occurred.", "error");
    }
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

// EDIT DOCUMENT
if (isset($_POST['submit_edit_document'])) {

    /* ------------------------------------
     * REQUIRED FIELD CHECK
     * ------------------------------------ */
    if (
        empty($_POST['status']) ||
        empty($_POST['doc_type']) ||
        empty($_POST['doc_title']) ||
        empty($_POST['edit_id'])
    ) {
        sessionMsg("Error", "Required fields missing.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    $edit_id = cleanvars($_POST['edit_id']);

    /* ------------------------------------
     * GET OLD RECORD
     * ------------------------------------ */
    $oldData = $dblms->getRows(INTERN_DOCS, [
        'select' => 'doc_file',
        'where'  => [
            'id' => $edit_id,
            'is_deleted' => 0
        ],
        'return_type' => 'single'
    ]);

    if (!$oldData) {
        sessionMsg("Error", "Record not found.", "error");
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    /* ------------------------------------
     * FILE HANDLING (OPTIONAL)
     * ------------------------------------ */
    $fileName = $oldData['doc_file']; // default old file

    if (!empty($_FILES['doc_file']['name'])) {

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 300 * 1024;

        $fileInfo  = pathinfo($_FILES['doc_file']['name']);
        $extension = strtolower($fileInfo['extension']);

        if (!in_array($extension, $allowedExtensions)) {
            sessionMsg("Error", "Invalid file type.", "error");
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($_FILES['doc_file']['size'] > $maxSize) {
            sessionMsg("Error", "File size must not exceed 300 KB.", "error");
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        }

        /* upload new file */
        $uploadDir = 'uploads/images/intern_documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $safeTitle = to_seo_url($_POST['doc_title']);
        $fileName  = $safeTitle.'-'.time().'.'.$extension;
        $fullPath  = $uploadDir.$fileName;

        if (!move_uploaded_file($_FILES['doc_file']['tmp_name'], $fullPath)) {
            sessionMsg("Error", "File upload failed.", "error");
            header("Location: ".$_SERVER['HTTP_REFERER']);
            exit();
        }

        /* delete old file */
        $oldFilePath = $uploadDir.$oldData['doc_file'];
        if (!empty($oldData['doc_file']) && file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    /* ------------------------------------
     * UPDATE DB
     * ------------------------------------ */
    $values = [
        'status'     => cleanvars($_POST['status']),
        'doc_type'   => cleanvars($_POST['doc_type']),
        'doc_title'  => cleanvars($_POST['doc_title']),
        'doc_file'   => cleanvars($fileName),
        'id_modify'  => cleanvars($_SESSION['userlogininfo']['LOGINIDA']),
        'date_modify'=> date('Y-m-d H:i:s')
    ];

    $update = $dblms->Update(INTERN_DOCS, $values, "WHERE id = '".$edit_id."'");

    if ($update) {
        sendRemark("Intern Document Updated ID: ".$edit_id, '2', $edit_id);
        sessionMsg("Success", "Document updated successfully.", "success");
    } else {
        sessionMsg("Error", "Update failed.", "error");
    }

    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

// DELETE DOCUMENT
if(isset($_GET['deleteid_doc'])) {
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars(LMS_IP)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(INTERN_DOCS, $values , "WHERE id = '".cleanvars($_GET['deleteid_doc'])."'");

	if($sqlDel) {
		sendRemark("Intern Document Deleted", '3', $_GET['deleteid_doc']);
		sessionMsg("success", "Record Successfully Deleted.", "success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}