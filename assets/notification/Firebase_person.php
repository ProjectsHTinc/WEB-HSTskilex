<?php

class Firebase {

    public function send($registration_ids,$messages) {
        // 'vibrate' available in GCM, but not in FCM

        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $messages,
        );
        return $this->sendPushNotification($fields);
    }

/*
* This function will make the actuall curl request to firebase server
* and then the message is sent
*/

    private function sendPushNotification($fields) {

        $server_key='AAAAhjEIRDs:APA91bHRuky-KETpCqjNytc4VKwQb73qCqJyahfoaMo-eY_CSqU0SUlOShqSncpWeU_-AvWaZrP5rMyjuLnRWGL_gZXUD9__HV0pEjcr346-TBCsIA8oPQBTco8b2sXWdD_Hj6uwhc73';
        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';

        //building headers for the request
        $headers = array(
            'Authorization:key='.$server_key,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);

        //and return the result
        return $result;
    }
}
