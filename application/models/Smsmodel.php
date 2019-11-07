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



 function notification_test(){
   $mobile_type=='1';
   $title="hi";
   $notes="testing";
   $gcm_key='cJrKPS9mGN0:APA91bEpgHMbMq2_Qq3DLCL7HzzGjUpQZ354fJ1s4GdT8qqvO7IcWooas1e6zP95U-aHK2k_rBDOtjKzbbocElzqOdUo3BJNoFaJBXfIGDuG7iugJvOAQyzeFuu-psPAvgIkI6Ojh0HP';
   require_once 'assets/notification/Firebase.php';
	 require_once 'assets/notification/Push.php';
			$push = null;
			$push = new Push(
					$title,
					$notes,
					null
				);


			$passphrase = 'hs123';
			$loction ='assets/notification/ensyfi.pem';
			$payload = '{
						"aps": {
							"alert": {
								"body": "'.$notes.'",
								"title": "'.$title.'"
							}
						}
					}';
          $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
          if ($mobile_type =='1')
            {
              //getting the push from push object
              $mPushNotification = $push->getPush();

              //creating firebase class object
              $firebase = new Firebase();
              $firebase->send(array($gcm_key),$mPushNotification);

            }
            if ($mobile_type =='2')
            {
              $ctx = stream_context_create();
              stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
              stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

              // Open a connection to the APNS server
              $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

              if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

                $msg = chr(0) . pack("n", 32) . pack("H*", str_replace(" ", "", $gcm_key)) . pack("n", strlen($payload)) . $payload;
                echo $result = fwrite($fp, $msg, strlen($msg));
                fclose($fp);

            }
 }





}
?>
