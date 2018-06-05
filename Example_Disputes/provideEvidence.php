<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

$disputeId = $_SESSION['disputeId'];
$accessToken = $_SESSION['accessToken'];

$ch = curl_init();
$url = "https://api.sandbox.paypal.com/v1/customer/disputes/$disputeId/provide-evidence";

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/PayPal_PDT_Templates/cert/cacert.pem');
curl_setopt($ch, CURLOPT_USERPWD, 'AcnHhNKHn0m8RtnZF2oQ0f8-S6iLSNgJwRijnKKagRbVOQbiLSMXe9HO_GCBa3nkpsj4o4R9xErBrGFc:EESZteGYA0cOpLTgpY4jo8VvYkaEDk8aCnVxgVlB2cJvYLtdFbLyjztKDAggc4_D_zuc-wuTDDgPrFbN');

//Change headers to include access token
$headers = array(
		'Content-Type: multipart/related; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
		"Authorization: Bearer $accessToken"
);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

//Lets curl know we are using 'post' and not 'get' (false = 'get')
curl_setopt($ch, CURLOPT_POST, TRUE);

//Keep connection open until we get data back
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//Data block

$tracking1['carrier_name'] = "DHL";
$tracking1['tracking_number'] = "123456";


$evidence1['tracking_info'] = array($tracking1);

$parts1['evidence_type'] = 'PROOF_OF_FULFILLMENT';
$parts1['evidence_info'] = $evidence1;
$parts1['notes'] = 'Test';

$fileData = $parts1;
//$fileData .= ';type=application/json';
// $fileData = 'input=' . $fileData;

// echo '[' . $fileData . ']';


//File block

$file = new CURLFile('index.jpg', 'image/jpeg');

//$file = 'file1=' . json_encode($file);
$file = 'file1=@index.jpg';

$finalPostFields['input'] = $fileData;
$finalPostFields['input'] .=';type=application/json';
$finalPostFields['file1'] = $file;

$req = json_encode($finalPostFields);
//$req = $fileData.$file;

echo "<pre>";
print_r($req);
echo "</pre>";

curl_setopt($ch, CURLOPT_POSTFIELDS, $req);


$response = curl_exec($ch);
echo '<br>cURL errors: '.curl_error($ch).'<br>';
echo $response;

//parse json into object
$outArray = json_decode($response, true);

echo "<pre>";
print_r($outArray);
echo "</pre>";

echo "<a href='showDisputeDetails.php'>Go Back</a>";


$arrayList[] = "my";
array_push($arrayList, "this");
array_push($arrayList, "that");

$myList['input']= $arrayList;
echo "<pre>";
print_r($myList);
echo "</pre>";
echo json_encode($myList);