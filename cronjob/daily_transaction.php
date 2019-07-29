<?php
date_default_timezone_set('Asia/Kolkata');
$current_time = date("h:i A", time());

$con = @mysql_connect("localhost","root","O+E7vVgBr#{}");
//$con = @mysql_connect("localhost","root","");
if ($con) {
		mysql_select_db('skilex_development');
    } else {
		die("Connection failed");
}

//#################### Email ####################//

	 function sendMail($to,$subject,$email_message)
	{
		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: Heyla App<hello@heylaapp.com>' . "\r\n";
		mail($to,$subject,$email_message,$headers);
	}

//#################### Email End ####################//


//#################### Notification ####################//

	 function sendNotification($gcm_key,$Title,$Message,$mobiletype)
	{
		if ($mobiletype =='1'){

		    require_once 'assets/notification/Firebase.php';
            require_once 'assets/notification/Push.php'; 
            
            $device_token = explode(",", $gcm_key);
            $push = null; 
        
//        //first check if the push has an image with it
		    $push = new Push(
					$Title,
					$Message,
					'http://heylaapp.com/assets/notification/images/events.jpg'
				);

// 			//if the push don't have an image give null in place of image
 			// $push = new Push(
 			// 		'HEYLA',
 			// 		'Hi Testing from maran',
 			// 		null
 			// 	);

    		//getting the push from push object
    		$mPushNotification = $push->getPush(); 
    
    		//creating firebase class object 
    		$firebase = new Firebase(); 

    	foreach($device_token as $token) {
    		 $firebase->send(array($token),$mPushNotification);
    	}

		} else {
            
			$device_token = explode(",", $gcm_key);
			$passphrase = 'hs123';
		    $loction ='assets/notification/heylaapp.pem';
		   
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			// Open a connection to the APNS server
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => array(
					'body' => $Message,
					'action-loc-key' => 'Heyla App',
				),
				'badge' => 2,
				'sound' => 'assets/notification/oven.caf',
				);
			
			$payload = json_encode($body);

			foreach($device_token as $token) {
			
				// Build the binary notification
    			$msg = chr(0) . pack("n", 32) . pack("H*", str_replace(" ", "", $token)) . pack("n", strlen($payload)) . $payload;
        		$result = fwrite($fp, $msg, strlen($msg));
			}
							
				fclose($fp);
		}
	}

//#################### Notification End ####################//


//#################### SMS ####################//

	 function sendSMS($Phoneno,$Message)
	{
        //Your authentication key
        $authKey = "191431AStibz285a4f14b4";
        
        //Multiple mobiles numbers separated by comma
        $mobileNumber = "$Phoneno";
        
        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "HEYLAA";
        
        //Your message to send, Add URL encoding here.
        $message = urlencode($Message);
        
        //Define route 
        $route = "transactional";
        
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );
        
        //API URL
        $url="https://control.msg91.com/api/sendhttp.php";
        
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));
        
        
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        
        //get response
        $output = curl_exec($ch);
        
        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }
        
        curl_close($ch);
	}

//#################### SMS End ####################//

//#################### Daily Transaction Details ####################//
        $sQuery = "SELECT
						so.serv_prov_id,
						so.order_date,
						COUNT(so.serv_prov_id) AS service_per_day,
						SUM(sp.net_service_amount) AS service_total_amt,
						SUM(sp.serv_pro_net_amount) AS serv_prov_comm_amt,
						SUM(sp.skilex_net_amount) AS skilex_comm_amt,
						SUM(sp.skilex_tax_amount) AS tax_able_amt,
						SUM(sp.online_amount) AS online_trans_amt,
						SUM(sp.offline_amount) AS offline_trans_amt,
						SUM(sp.online_amount * 0.2) AS online_skile_com_amt,
						SUM(sp.online_amount * 0.8) AS online_sp_com_amt,
						SUM(sp.offline_amount * 0.2) AS offline_skile_com_amt,
						SUM(sp.offline_amount * 0.8) AS offline_sp_com_amt,
						(SUM(sp.online_amount * 0.8) - SUM(sp.offline_amount * 0.2)) AS pay_to_serv
					FROM
						service_orders AS so
					LEFT JOIN service_payments AS sp
					ON
						so.id = sp.service_order_id
					LEFT JOIN service_payment_history AS sphh
					ON
						sphh.payment_order_id = sp.id
					WHERE
						so.order_date = CURDATE() AND sp.status = 'Paid'
					GROUP BY
						so.serv_prov_id";
					
        $objRs = mysql_query($sQuery) or die("Could not select Query");
		
		if (mysql_num_rows($objRs)> 0)
        	{
        		while ($row = mysql_fetch_array($objRs))
        		{
					$serv_prov_id =  $order_id = trim($row['serv_prov_id']);
					$service_per_day =  $order_id = trim($row['service_per_day']);
					$order_date =  $order_id = trim($row['order_date']);
					$service_total_amt =  $order_id = trim($row['service_total_amt']);
					$serv_prov_comm_amt =  $order_id = trim($row['serv_prov_comm_amt']);
					$skilex_comm_amt =  $order_id = trim($row['skilex_comm_amt']);
					$tax_able_amt =  $order_id = trim($row['tax_able_amt']);
					$online_trans_amt =  $order_id = trim($row['online_trans_amt']);
					$offline_trans_amt =  $order_id = trim($row['offline_trans_amt']);
					$online_skile_com_amt =  $order_id = trim($row['online_skile_com_amt']);
					$online_sp_com_amt =  $order_id = trim($row['online_sp_com_amt']);
					$offline_skile_com_amt =  $order_id = trim($row['offline_skile_com_amt']);
					$offline_sp_com_amt =  $order_id = trim($row['offline_sp_com_amt']);
					$pay_to_serv =  $order_id = trim($row['pay_to_serv']);
				
				
				$insQuery = "INSERT INTO daily_payment_transaction(
								serv_prov_id,
								total_service_per_day,
								service_date,
								serv_total_amount,
								serv_prov_commission_amt,
								skilex_commission_amt,
								online_transaction_amt,
								offline_transaction_amt,
								online_skilex_commission,
								offline_skilex_commission,
								online_serv_prov_commission,
								offline_serv_prov_commission,
								taxable_amount,
								pay_to_serv_prov,
								skilex_closing_status,
								serv_prov_closing_status,
								created_at
							)
							VALUES(
								'$serv_prov_id',
								'$service_per_day',
								'$order_date',
								'$service_total_amt',
								'$serv_prov_comm_amt',
								'$skilex_comm_amt',
								'$online_trans_amt',
								'$offline_trans_amt',
								'$online_skile_com_amt',
								'$online_skile_com_amt',
								'$online_sp_com_amt',
								'$online_sp_com_amt',
								'$tax_able_amt',
								'$pay_to_serv',
								'Nopay',
								'Unpaid',
								NOW())";
				$insobjRs  = mysql_query($insQuery) or die("Could not select Query");
				}
        	}
//#################### Daily Transaction Details end ####################//
?>