<?php
//first api call(Authenticate)
// $url = "https://demo.saralgsp.com/authentication/Authenticate";

// // init the resource
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// $headers = array(
//    "ClientId: 43cc5941-2be5-4e2f-9a09-2536f0e29fbf",
//    "ClientSecret: Fsx2IecwjFtWEsk0fxqEKs18jMjCN62v",
// );

// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
// //for debug only!
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// $resp = curl_exec($curl);
// curl_close($curl);

// $data = json_decode($resp);
// $authenticationToken = $data->authenticationToken;
// $subscriptionId = $data->subscriptionId;
// $authenticationValidTillDateTime = $data->authenticationValidTillDateTime;
// $isValid = $data->isValid;
date_default_timezone_set("Asia/Kolkata");
echo date('Y-m-d H:i:s');
echo "<br>";
echo $authdate = date('Y-m-d H:i:s',strtotime('7/19/2022 3:48:22 PM'));
//echo $authdate = date('Y-m-d H:i:s',strtotime('19-07-2022 15:57:51'));
exit;
date_default_timezone_set("Asia/Kolkata");
$date = '2022-07-07 17:49:23';
$datetime1 = new DateTime();
echo $datetime1->format('Y-m-d H:i:s');
$datetime2 = new DateTime($date);

if ($datetime1 > $datetime2) {
    echo 'datetime1 greater than datetime2';
}

if ($datetime1 < $datetime2) {
    echo 'datetime1 lesser than datetime2';
}

if ($datetime1 == $datetime2) {
    echo 'datetime2 is equal than datetime1';
}
 
?>