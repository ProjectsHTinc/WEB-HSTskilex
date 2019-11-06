<?php

Class Smsmodel extends CI_Model
{

 public function __construct()
  {
      parent::__construct();

  }



 function send_sms($phone,$notes)
 {

  $msg=urlencode($notes);
  $url="https://sms.zestwings.com/smpp.sms?username=Virtual01&password=371675&to=91$phone&from=SkilEx&text=$msg";
  $curl = curl_init();
      curl_setopt_array($curl, array(
      // CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$phone&authkey=301243AX0Pp4EOQCn5db82c4f&route=4&sender=SKILEX&message=$notes&country=91",
      CURLOPT_URL => $url,

      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
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
