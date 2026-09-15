<?php 
//error_reporting(0);
 

#Admin Login
function adminUser($username, $password){
    
    global $mysqli;

    $sql = "SELECT id,username FROM tbl_admin where username = '".$username."' and password = '".md5($password)."'";       
    $result = mysqli_query($mysqli,$sql);
    $num_rows = mysqli_num_rows($result);
     
    if ($num_rows > 0){
        while ($row = mysqli_fetch_array($result)){
            echo $_SESSION['ADMIN_ID'] = $row['id'];
                        echo $_SESSION['ADMIN_USERNAME'] = $row['username'];
                                      
        return true; 
        }
    }
    
}


# Insert Data 
function Insert($table, $data){

    global $mysqli;
    //print_r($data);

    $fields = array_keys( $data );  
    $values = array_map( array($mysqli, 'real_escape_string'), array_values( $data ) );
    
   //echo "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');";
   //exit;  
    mysqli_query($mysqli, "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');") or die( mysqli_error($mysqli) );

}

// Update Data, Where clause is left optional
function Update($table_name, $form_data, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;
         
    // run and return the query result
    return mysqli_query($mysqli,$sql);
}

 
//Delete Data, the where clause is left optional incase the user wants to delete every row!
function Delete($table_name, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;
     
    // run and return the query result resource
    return mysqli_query($mysqli,$sql);
}

//Delete Data, the where clause is left optional incase the user wants to delete every row!
function LastID($table_name)
{   
    global $mysqli;
    return mysqli_insert_id($mysqli);
}


 
//GCM function
function Send_GCM_msg($registration_id,$data)
{
    $data1['data']=$data;
 
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    $registatoin_ids = array($registration_id);
     // $message = array($data);
   
         $fields = array(
             'registration_ids' => $registatoin_ids,
             'data' => $data1,
         );
  
         $headers = array(
             'Authorization: key='.APP_GCM_KEY.'',
             'Content-Type: application/json'
         );
         // Open connection
         $ch = curl_init();
  
         // Set the url, number of POST vars, POST data
         curl_setopt($ch, CURLOPT_URL, $url);
  
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
         // Disabling SSL Certificate support temporarly
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  
         // Execute post
         $result = curl_exec($ch);
         if ($result === FALSE) {
             die('Curl failed: ' . curl_error($ch));
         }
  
         // Close connection
         curl_close($ch);
       //echo $result;exit;
}


//Image compress
function compress_image($source_url, $destination_url, $quality) 
{

    $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
              $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
              $image = imagecreatefromgif($source_url);

        elseif ($info['mime'] == 'image/png')
              $image = imagecreatefrompng($source_url);

        imagejpeg($image, $destination_url, $quality);
        return $destination_url;
}

//Create Thumb Image
function create_thumb_image($target_folder ='',$thumb_folder = '', $thumb_width = '',$thumb_height = '')
 {  
     //folder path setup
         $target_path = $target_folder;
         $thumb_path = $thumb_folder;  
          

         $thumbnail = $thumb_path;
         $upload_image = $target_path;

            list($width,$height) = getimagesize($upload_image);
            $thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
            switch($file_ext){
                case 'jpg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'jpeg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'png':
                    $source = imagecreatefrompng($upload_image);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($upload_image);
                     break;
                default:
                    $source = imagecreatefromjpeg($upload_image);
            }
       imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width,$height);
            switch($file_ext){
                case 'jpg' || 'jpeg':
                    imagejpeg($thumb_create,$thumbnail,80);
                    break;
                case 'png':
                    imagepng($thumb_create,$thumbnail,80);
                    break;
                case 'gif':
                    imagegif($thumb_create,$thumbnail,80);
                     break;
                default:
                    imagejpeg($thumb_create,$thumbnail,80);
            }
   }

   function checkSignSalt($data_info){

        $key="MULCMS";

        $data_json = $data_info;

        $data_arr = json_decode(urldecode(base64_decode($data_json)),true);


        if($data_arr['sign'] == '' && $data_arr['salt'] == '' ){
            //$data['data'] = array("success" => -1, "MSG" => "Invalid sign salt.");
        
            $set['MCDL_SYSTEM'][] = array("success" => -1, "MSG" => "Invalid sign salt.");
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();

        }else{
            
            $data_arr['salt'];    
            
            $md5_salt=md5($key.$data_arr['salt']);

            if($data_arr['sign']!=$md5_salt){

                //$data['data'] = array("success" => -1, "MSG" => "Invalid sign salt.");
                $set['MCDL_SYSTEM'][] = array("success" => -1, "MSG" => "Invalid sign salt.");   
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
        }
        return $data_arr;
    }

function verify_envato_purchase_code($product_code)
{ 
  
$url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
$curl = curl_init($url);


$personal_token = "M8tF6z8lzZBBkmZt4xm3dU4lw7Rlbrwp";
$header = array();
$header[] = 'Authorization: Bearer '.$personal_token;
$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
$header[] = 'timeout: 20';
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

$envatoRes = curl_exec($curl);
curl_close($curl);
$envatoRes = json_decode($envatoRes);
 
 print_r($envatoRes);

 return $envatoRes;   


//echo $envatoRes->buyer;

/* if (isset($envatoRes->item->name)) {   
    $data = " - VERIFIED ({$envatoRes->item->name}) (Supported: {$result})";
} else {  
    $data= " FAILED";
} 

echo $data;
exit;*/
}


//--------------- Lead Status ------------------
$leadstatus = array (
						array('id'=>1, 'name'=>'New / Exisitng')		, 
						array('id'=>2, 'name'=>'Signed')			,
						array('id'=>3, 'name'=>'Unwilling / Filled')
				   );

function get_leadstatus($id) {
	$list = array (
					'1'		=> 'New / Exisitng'			, 
					'2' 	=> 'Signed'					, 
					'3' 	=> 'Unwilling / Filled'		
				);
	return $list[$id];
}

//--------------- Lead Source ----------
$leadsource = array (
					array('id'=>1, 'name'=>'Assigned by Company')	,
					array('id'=>2, 'name'=>'Self Generated')		
				   );

function get_leadsource($id) {
	$list = array (
							'1'	=> 'Assigned by Company'	,
							'2'	=> 'Self Generated'			
							);
	return $list[$id];
}
//--------------- cl_communication with ----------
$comwith = array (
					array('id'=>1, 'name'=>'Support Coordinator, Guardian/Relative/Participant')		,
					array('id'=>2, 'name'=>'Administrator')	,
					array('id'=>3, 'name'=>'Plan Manager')	,
					array('id'=>4, 'name'=>'Hospital')		,
					array('id'=>5, 'name'=>'Other')	
				   );

function get_comwith($id) {
	$list = array (
							'1'	=> 'Support Coordinator, Guardian/Relative/Participant'		,
							'2'	=> 'Administrator'		,
							'3'	=> 'Plan Manager'	,
							'4'	=> 'Hospital'	,
							'5'	=> 'Other'	
							);
	return $list[$id];
}

//--------------- States Names ------------------
$statesnames = array (
						array('id'=>1, 'name'=>'New South Wales')			, 
						array('id'=>2, 'name'=>'Victoria')					,
						array('id'=>3, 'name'=>'Queensland')				,
						array('id'=>4, 'name'=>'South Australia')			,
						array('id'=>5, 'name'=>'Western Australia')			,
						array('id'=>6, 'name'=>'Tasmania')					,
						array('id'=>7, 'name'=>'Northern Territory')		,
						array('id'=>8, 'name'=>'Australian Capital Territory')		
				   );

function get_statesnames($id) {
	$list = array (
					'1'		=> 'New South Wales'			, 
					'2' 	=> 'Victoria'					, 
					'3' 	=> 'Queensland'					, 
					'4' 	=> 'South Australia'			, 
					'5' 	=> 'Western Australia'			, 
					'6' 	=> 'Tasmania'					, 
					'7' 	=> 'Northern Territory'			, 
					'8' 	=> 'Australian Capital Territory'		
				);
	return $list[$id];
}


//--------------- Source of communication ----------
$sourceofcom = array (
					array('id'=>1, 'name'=>'Via face to face')						,
					array('id'=>2, 'name'=>'Via Zoom (any other online source)')	,
					array('id'=>3, 'name'=>'Via Phone')		,
					array('id'=>4, 'name'=>'Via Email')		,
					array('id'=>5, 'name'=>'Others')	
				   );

function get_sourceofcom($id) {
	$list = array (
							'1'	=> 'Via face to face'						,
							'2'	=> 'Via Zoom (any other online source)'		,
							'3'	=> 'Via Phone'	,
							'4'	=> 'Via Email'	,
							'5'	=> 'Others'	
							);
	return $list[$id];
}


function verify_data_on_server($product_id,$buyer_name,$purchase_code,$purchased_status,$admin_url,$package_name,$ios_bundle_identifier,$envato_buyer_email)
{  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://www.secureapp.viaviweb.in/verified_user.php");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('envato_product_id' => $product_id,'envato_buyer_name' => $buyer_name,'envato_purchase_code' => $purchase_code,'envato_purchased_status' => $purchased_status,'buyer_admin_url' => $admin_url,'package_name' => $package_name,'ios_bundle_identifier' => $ios_bundle_identifier,'envato_buyer_email' => $envato_buyer_email,)));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);

     
   if ($server_output == "success") { echo "done1"; } else { echo "fail!";}     
}
//---------Aactive / Inactive Status---------
function get_status($id) {
	$liststatus= array (
							'1' => 'Active', 
							'2' => 'Inactive');
	return $liststatus[$id];
}
//---------Aactive / Inactive Status---------
function get_admstatus($id) {
	$listadmstatus= array (
							'1' => 'Active', 
							'0' => 'Inactive');
	return $listadmstatus[$id];
}
//--------------- Absent or Present ----------
function get_absentpresent($id) { 

$listabsentpresent = array (
            '1'	=> 'Absent'		,	
            '2'	=> 'Present'				
         );
return $listabsentpresent[$id];
}
//--------------- Class Room types ----------
function get_classroomtypes($id) {

$listclassroomtypes	= array (
            '1' => 'Lecture Room'		, 
            '2' => 'Lab'				,
            '3' => 'Board Room'			,
            '4' => 'Auditorium'			,
            '5' => 'Conference Room'	,
            '6' => 'Center Room'		,
            '7' => 'Marquee 1'			,
            '8' => 'Marquee 2'		
          );
return $listclassroomtypes[$id];

}
//--------------- Theory  Practical----------
function get_theorypractical($id) {

$listtheorypractical = array (
        '1' => 'Theory'		,
        '2' => 'Practical'	
      );
return $listtheorypractical[$id];

}
//--------------- Degree Names ------------------
function get_degreename($id) {

    $listregtypes= array (
            '1' => 'Matric'			, 
            '2' => 'Intermediate'	, 
            '3' => 'Bachelor'		, 
            '4' => 'Master'			,
            '5' => 'M.Phil / MS'	,
            '6' => 'Others');
return $listregtypes[$id];

}
//--------------- Week Days ----------
$weekdays = array (
        'Monday'		,
        'Tuesday'		,
        'Wednesday'		,
        'Thursday'		,
        'Friday'		,
        'Saturday'
    );

$dayweekend = array (
        'Friday',
        'Saturday',
        'Sunday'
    );
//--------------- Fee Status ----------
function get_feestatus($id) {
	$listfeestatus= array (
							'2' => 'Paid'		, 
							'3' => 'Pending'	,
							'4' => 'Unpaid'	
						  );
	return $listfeestatus[$id];
}
//--------------- program Timing ----------
function get_programtiming($id) {

$listprogramtiming = array (
            '1'	=> 'Morning'			,	
            '2'	=> 'Weekend'			,	
            '3'	=> 'Both'				,
            '4'	=> 'Evening'
         );
return $listprogramtiming[$id];
}
//---------- Language Level ------------
function get_levels($id) {
    $listlevels = array (
            '1' => 'Beginner'		, 
            '2' => 'Intermediate'	, 
            '3' => 'Expert'	
        );
    return $listlevels[$id];
}
//--------------- Publish Type ------------------
function get_publishtype($id) {
    $listpublishtype = array (
            '1' => 'Book'		, 
            '2' => 'Article'	, 
            '3' => 'Report'	
        );
    return $listpublishtype[$id];
}
//--------------- Open With ----------
$fileopenwith = array('Adobe Acrobat Reader', 'MS Excel', 'MS Paint', 'MS Powerpoint', 'MS Word', 'WinRAR', 'WinZip');
//----------- Clean Variables ----------
function cleanvars($str){ 
	return is_array($str) ? array_map('cleanvars', $str) : str_replace("\\", "\\\\", htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES)); 
}
//----------------------------------------
function to_seo_url($str){
    // if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
       //  $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
     $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
     $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
     $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
     $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
     $str = trim($str, '-');
     return $str;
 }
//--------------- addOrdinalNumberSuffix ------------------
function addOrdinalNumberSuffix($num) {
	if (!in_array(($num % 100),array(11,12,13))){
		switch ($num % 10) {
	// Handle 1st, 2nd, 3rd
			case 1:  return $num.'st';
			case 2:  return $num.'nd';
			case 3:  return $num.'rd';
		}
	}
	return $num.'th';
}
//--------------- Get Uploaded file size ------------------
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
return $bytes;
}
