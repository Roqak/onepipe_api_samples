<?php
$baseURL = 'https://api.onepipe.io';
$api_key = "";
$secret_key = "";
$accountNumber = "";
$bankCode="";
$firstname = "";
$surname="";
$email="";
$mobile_no="";
// generate unique transaction reference
function generate_txn_ref( ) {
    global $gen_txn_ref;

 $characters = array(
 "A","B","C","D","E","F","G","H","J","K","L","M",
 "N","P","Q","R","S","T","U","V","W","X","Y","Z",
 "1","2","3","4","5","6","7","8","9");
 
 $keys = array();
 while(count($keys) < 6) {//10 xters
     $x = mt_rand(0, count($characters)-1);
     if(!in_array($x, $keys)) {
        $keys[] = $x;
     }
 }
foreach($keys as $key){
    $random_chars .= $characters[$key];
 }

$gen_txn_ref = "CICO_".$random_chars;
return $gen_txn_ref;

 };
    
$ref = generate_txn_ref();
$signature = md5($ref.";".$secret_key);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $baseURL."/v1/lookup/accountinfo",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => '{
    "request_ref":"'.$ref.'",
      "transaction": {
          "account_number": "'.$accountNumber.'",
          "bank_code": "'.$bankCode.'",
          "customer":{
              "customer_ref": "'.$accountNumber.'",
              "firstname": "'.$firstname.'",
                "surname": "'.$surname.'",
              "email": "'.$email.'",
              "mobile_no": "'.$mobile_no.'"
          }
    }
  }',
  CURLOPT_HTTPHEADER => array(
    "Accept: */*",
    "Accept-Encoding: gzip, deflate",
    "Authorization: Bearer ".$api_key,
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Signature: ".$signature,
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  print_r($response);
}