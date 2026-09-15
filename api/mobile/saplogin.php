<?php
error_reporting(E_ALL);

//This is the root url endpoint where you will make the API call.
$host = 'https://103.141.229.20:9010/sap/opu/odata/sap/ZAKH_SRV/userInfoSet?$format=json&$filter=(Universityregistration eq \'70000088\')';

//Provide your username or access token.
$user_name = 'BACK_USER';

//Provide your password or access token.
$password = 'Sap@1234567';

//Initiate cURL request
$ch = curl_init($host);

// Set the header by creating the basic authentication
$headers = array(
'Content-Type: application/json',
'Authorization: Basic '. base64_encode("$user_name:$password")
);
//Set the headers that we want our cURL client to use.
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Set the RETURNTRANSFER as true so that output will come as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Execute the cURL request.
$response = curl_exec($ch);

//Check if any errors occured.
if(curl_errno($ch)){
// throw the an Exception.
throw new Exception(curl_error($ch));
}

curl_close($ch);

//get the response.
echo $response;

?>