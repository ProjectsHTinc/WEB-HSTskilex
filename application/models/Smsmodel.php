<?php

Class Smsmodel extends CI_Model
{

 public function __construct()
  {
      parent::__construct();

  }



 function send_sms($phone,$notes)
 {

   $curl = curl_init();
   curl_setopt_array($curl, array(
     CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms?country=91",
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => "",
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => '{
                 "sender": "SKILEX",
                 "route": "4",
                 "country": "91",
                 "sms": [
                 {
                   "message": "'.urlencode($notes).'",
                   "to": [
                   "'.$phone.'"
                   ]
                 }
                 ]
               }',
     CURLOPT_SSL_VERIFYHOST => 0,
     CURLOPT_SSL_VERIFYPEER => 0,
     CURLOPT_HTTPHEADER => array(
       "authkey: 191431AStibz285a4f14b4",
       "content-type: application/json"
     ),
   ));

   $response = curl_exec($curl);
   $err = curl_error($curl);

   curl_close($curl);

   if ($err) {
     echo "cURL Error #:" . $err;
   } else {
     // echo $response;
   }


 }





}
?>
