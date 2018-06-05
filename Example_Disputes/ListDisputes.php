<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

$ch = curl_init();
$url = 'https://api.sandbox.paypal.com/v1/oauth2/token';
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/PayPal_PDT_Templates/cert/cacert.pem');
curl_setopt($ch, CURLOPT_USERPWD, 'AcnHhNKHn0m8RtnZF2oQ0f8-S6iLSNgJwRijnKKagRbVOQbiLSMXe9HO_GCBa3nkpsj4o4R9xErBrGFc:EESZteGYA0cOpLTgpY4jo8VvYkaEDk8aCnVxgVlB2cJvYLtdFbLyjztKDAggc4_D_zuc-wuTDDgPrFbN'); 



//Get Access Token
$headers = array(
		'Accept: application/json',
		'Content-Type: application/x-www-form-urlencoded',
		'Accept-Language: en_US'
		);

curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

//Lets curl know we are using 'post' and not 'get' (false = 'get')
curl_setopt($ch, CURLOPT_POST, TRUE);

//Keep connection open until we get data back
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$vars['grant_type'] = 'client_credentials';


$req = http_build_query($vars);

//print_r($req);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);


$response = curl_exec($ch);


//parse json into object
$outArray = json_decode($response, true);

//Get access token from response
$accessToken = $outArray['access_token'];

$_SESSION['accessToken'] = $accessToken;

//print_r($outArray);


// //List Disputes

//Clear Array
$vars = [];

//Define new endpoint
$url = 'https://api.sandbox.paypal.com/v1/customer/disputes';
curl_setopt($ch, CURLOPT_URL, $url);

//Change headers to include access token
$headers = array(
		'Accept: application/json',
		'Content-Type: application/json',
		"Authorization: Bearer $accessToken"
);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

//Lets curl know we are using 'get' and not 'post' (false = 'get')
curl_setopt($ch, CURLOPT_POST, FALSE);


$response = curl_exec($ch);

//parse json into object
$outArray = json_decode($response, true);

echo "<pre>";
print_r($outArray);
echo "</pre>";

//echo $outArray['items'][0]['dispute_id'];



if(isset($outArray['items'][0]['dispute_id']))
{
	$_SESSION['disputeId'] = $outArray['items'][0]['dispute_id'];
	echo "<a href='showDisputeDetails.php'>More Info</a>";
}
else 
{
	echo"Sorry no disputes available at this time.";	
}