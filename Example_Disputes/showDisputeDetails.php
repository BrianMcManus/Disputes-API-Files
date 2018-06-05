<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

$disputeId = $_SESSION['disputeId'];
$accessToken = $_SESSION['accessToken'];

$ch = curl_init();
$url = "https://api.sandbox.paypal.com/v1/customer/disputes/$disputeId";

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/PayPal_PDT_Templates/cert/cacert.pem');
curl_setopt($ch, CURLOPT_USERPWD, 'AcnHhNKHn0m8RtnZF2oQ0f8-S6iLSNgJwRijnKKagRbVOQbiLSMXe9HO_GCBa3nkpsj4o4R9xErBrGFc:EESZteGYA0cOpLTgpY4jo8VvYkaEDk8aCnVxgVlB2cJvYLtdFbLyjztKDAggc4_D_zuc-wuTDDgPrFbN');

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

//Works fine with all status
echo "<a href='acceptClaim.php'>Accept Claim</a><br>";

//This works but needs to be in ENQUIRY stage
echo "<a href='makeOffer.php'>Make Offer</a><br>";

//This works but needs to be in ENQUIRY stage
echo "<a href='sendMessage.php'>Send Message</a><br>";

//This works but needs to be in ENQUIRY stage and moves to UNDER REVIEW stage
echo "<a href='escalateToClaim.php'>Escalate To Claim</a><br>";

//This is working but needs to be in UNDER REVIEW status
echo "<a href='settleDispute.php'>Settle Dispute</a><br>";

//This works but needs to be in RESOLVED stage 
echo "<a href='appealDispute.php'>Appeal Dispute</a><br>";

//This should work but must be in either WAITING_FOR_BUYER_RESPONSE stage or WAITING_FOR_SELLER_RESPONSE
echo "<a href='provideEvidence.php'>Provide Evidence</a><br>";

//Not sure which status this should be in
echo "<a href='requireEvidence.php'>Require Evidence</a><br>";




?>