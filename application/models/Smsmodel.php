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
  // $url="https://sms.zestwings.com/smpp.sms?username=Virtual01&password=371675&to=91$phone&from=SkilEx&text=$msg";
  $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$phone&authkey=301243AX0Pp4EOQCn5db82c4f&route=4&sender=SKILEX&message=$notes&country=91",
      // CURLOPT_URL => $url,

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


 function send_notification($head,$message,$gcm_key,$mobile_type,$user_type){


   if($user_type=='5'){
     include_once  'assets/notification/Firebase_customer.php';
     include_once 'assets/notification/Push.php';
   }else if($user_type=='4'){
     include_once  'assets/notification/Firebase_person.php';
     include_once 'assets/notification/Push.php';
   }else if($user_type=='3'){
     include_once  'assets/notification/Firebase_provider.php';
     include_once 'assets/notification/Push.php';
   }else{

   }

     $push = null;
     $push = new Push(
         $head,
         $message,
         null
       );


     $passphrase = 'hs123';
     $loction ='assets/notification/skilex.pem';
     $payload = '{
           "aps": {
             "alert": {
               "body": "'.$message.'",
               "title": "'.$head.'"
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
              $firebase->send(array($gcm_key),$mPushNotification,$user_type);

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
               $result = fwrite($fp, $msg, strlen($msg));
              fclose($fp);

            }
 }


 function push_notification_android($head,$message,$gcm_key,$mobile_type,$user_type){

     //API URL of FCM
     $url = 'https://fcm.googleapis.com/fcm/send';

     /*api_key available in:
     Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
     // $api_key = 'AAAAuoTcq58:APA91bEyV2z6t4yhSgEpIrNWSO_NFsEp5-5dPwpnQd0BMyxwYEjIXHvyHqzgNsY29bpq2l23nK9FUSxVbWlW96XxL3Ua6oHdCsCcy7Z8XpMXr74orBo3t1zwmF18xxtsqJnsV7SZKizt';
     $api_key='AAAAuoTcq58:APA91bEyV2z6t4yhSgEpIrNWSO_NFsEp5-5dPwpnQd0BMyxwYEjIXHvyHqzgNsY29bpq2l23nK9FUSxVbWlW96XxL3Ua6oHdCsCcy7Z8XpMXr74orBo3t1zwmF18xxtsqJnsV7SZKizt';

     $fields = array (
         'registration_ids' => array (
                 $gcm_key
         ),
         'data' => array (
                 "message" => $message
         )
     );

     //header includes Content type and api key
     $headers = array(
         'Content-Type:application/json',
         'Authorization:key='.$api_key
     );

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
     $result = curl_exec($ch);
     if ($result === FALSE) {
         die('FCM Send Error: ' . curl_error($ch));
     }
     curl_close($ch);

 }


 function push_notification_checking($head,$message,$gcm_key,$mobile_type,$user_type){
   // $url = "https://fcm.googleapis.com/fcm/send";
   //   $token = $gcm_key;
   //   if($user_type=='3'){
   //     $serverKey='AAAAuoTcq58:APA91bEyV2z6t4yhSgEpIrNWSO_NFsEp5-5dPwpnQd0BMyxwYEjIXHvyHqzgNsY29bpq2l23nK9FUSxVbWlW96XxL3Ua6oHdCsCcy7Z8XpMXr74orBo3t1zwmF18xxtsqJnsV7SZKizt';
   //   }else if($user_type=='4'){
   //     $serverKey='AAAAhjEIRDs:APA91bHRuky-KETpCqjNytc4VKwQb73qCqJyahfoaMo-eY_CSqU0SUlOShqSncpWeU_-AvWaZrP5rMyjuLnRWGL_gZXUD9__HV0pEjcr346-TBCsIA8oPQBTco8b2sXWdD_Hj6uwhc73';
   //   }else{
   //     $serverKey='AAAAKxxpzT0:APA91bE-Rr1H9AvMrV7dvIB4r9yAMtYbGCfo7E3k26dRjZL6sh-OR0BSxNZ-vrEuW1aq8O9DZZLOQ2ZEXYNiXtaZFji9LQPTvar0KHzg7Qvri-qiD99X-trbHl6Mea_KYVZ2_Yhw8Qqc';
   //
   //   }
   //
   //  $title = "Notification title";
   //  $body = $message;
   //  $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
   //  $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
   //  $json = json_encode($arrayToSend);
   //  $headers = array();
   //  $headers[] = 'Content-Type: application/json';
   //  $headers[] = 'Authorization: key='. $serverKey;
   //  $ch = curl_init();
   //  curl_setopt($ch, CURLOPT_URL, $url);
   //  curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
   //  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
   //  curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
   //  //Send the request
   //  $res = curl_exec($ch);
   //  //Close request
   //  if ($res === FALSE) {
   //  die('FCM Send Error: ' . curl_error($ch));
   //  }
   //  curl_close($ch);

   $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
   $token=array($gcm_key);
   $fields = array(
        'registration_ids' => $gcm_key,
        'priority' => 10,
        'data'=>$message,
        'notification' => array('title' => 'high', 'body' => "skilex" ,'sound'=>'Default'),
    );
    $headers = array(
        'Authorization:key=' .'AAAAuoTcq58:APA91bEyV2z6t4yhSgEpIrNWSO_NFsEp5-5dPwpnQd0BMyxwYEjIXHvyHqzgNsY29bpq2l23nK9FUSxVbWlW96XxL3Ua6oHdCsCcy7Z8XpMXr74orBo3t1zwmF18xxtsqJnsV7SZKizt' ,
        'Content-Type:application/json'
    );

    // Open connection
    $ch = curl_init('https://fcm.googleapis.com/fcm/send');
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);
    return $result;
 }



    function check_notify($head,$message,$gcm_key,$mobile_type,$user_type){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array (
                'to' => $gcm_key,
                 'priority' => 'high',
                'notification' => array (
                        "body" => $message,
                        "title" => "Skilex_new",
                        "icon" => "myicon"
                )
        );
        $fields = json_encode ( $fields );
        if($user_type=='3'){
          $headers = array (
                  'Authorization: key=' . "AAAAuoTcq58:APA91bEyV2z6t4yhSgEpIrNWSO_NFsEp5-5dPwpnQd0BMyxwYEjIXHvyHqzgNsY29bpq2l23nK9FUSxVbWlW96XxL3Ua6oHdCsCcy7Z8XpMXr74orBo3t1zwmF18xxtsqJnsV7SZKizt",
                  'Content-Type: application/json'
          );
        }else if($user_type=='5'){
          $headers = array (
                  'Authorization: key=' . "AAAAKxxpzT0:APA91bE-Rr1H9AvMrV7dvIB4r9yAMtYbGCfo7E3k26dRjZL6sh-OR0BSxNZ-vrEuW1aq8O9DZZLOQ2ZEXYNiXtaZFji9LQPTvar0KHzg7Qvri-qiD99X-trbHl6Mea_KYVZ2_Yhw8Qqc",
                  'Content-Type: application/json'
          );
        }else{
          $headers = array (
                  'Authorization: key=' . "AAAAhjEIRDs:APA91bHRuky-KETpCqjNytc4VKwQb73qCqJyahfoaMo-eY_CSqU0SUlOShqSncpWeU_-AvWaZrP5rMyjuLnRWGL_gZXUD9__HV0pEjcr346-TBCsIA8oPQBTco8b2sXWdD_Hj6uwhc73",
                  'Content-Type: application/json'
          );
        }

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        $rew = curl_exec ( $ch );
        curl_close ( $ch );





    }







}
?>
