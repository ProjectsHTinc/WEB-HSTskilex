<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apispersonmodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


//#################### Email ####################//

	public function sendMail($email,$subject,$email_message)
	{
		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: Webmaster<hello@happysanz.com>' . "\r\n";
		mail($email,$subject,$email_message,$headers);
	}

//#################### Email End ####################//


//#################### SMS ####################//

	public function sendSMS($Phoneno,$Message)
	{
        //Your authentication key
        $authKey = "191431AStibz285a4f14b4";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = "$Phoneno";

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "SKILEX";

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


//#################### Notification ####################//

	public function sendNotification($gcm_key,$title,$message,$mobiletype)
	{

		if ($mobiletype =='1'){

		    require_once 'assets/notification/Firebase.php';
            require_once 'assets/notification/Push.php';

            $device_token = explode(",", $gcm_key);
            $push = null;

        //first check if the push has an image with it
		    $push = new Push(
					$title,
					$message,
					null
				);

// 			//if the push don't have an image give null in place of image
// 			 $push = new Push(
// 			 		'HEYLA',
// 		     		'Hi Testing from maran',
// 			 		'http://heylaapp.com/assets/notification/images/event.png'
// 			 	);

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
		    $loction ='assets/notification/happysanz.pem';

			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

			// Open a connection to the APNS server
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => array(
					'body' => $message,
					'action-loc-key' => 'EDU App',
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


//#################### Dashboard ####################//

	public function Dashboard($user_master_id)
	{
		$assigned_count = "SELECT * FROM service_orders WHERE serv_pers_id = '".$user_master_id."' AND status = 'Assigned'";
		$assigned_count_res = $this->db->query($assigned_count);
		$assigned_orders_count = $assigned_count_res->num_rows();
		
		$ongoing_count = "SELECT * FROM service_orders WHERE serv_pers_id = '".$user_master_id."' AND (status = 'Initiated' OR status = 'Started' OR status = 'Ongoing')";
		$ongoing_count_res = $this->db->query($ongoing_count);
		$ongoing_orders_count = $ongoing_count_res->num_rows();
		
		//$finished_count = "SELECT * FROM service_orders WHERE serv_pers_id = '".$user_master_id."' AND status = 'Completed'";
		//$finished_count_res = $this->db->query($finished_count);
		//$finished_orders_count = $finished_count_res->num_rows();
		
		//$canceled_count = "SELECT * FROM service_orders WHERE serv_pers_id = '".$user_master_id."' AND status = 'Canceled'";
		//$canceled_count_res = $this->db->query($canceled_count);
		//$canceled_orders_count = $canceled_count_res->num_rows();
		
		$dashboardData  = array(
				"serv_assigned_count" => $assigned_orders_count,
				"serv_ongoing_count" => $ongoing_orders_count,
			);
		$response = array("status" => "success", "msg" => "Dashboard Datas","dashboardData"=>$dashboardData);
		return $response;
	}

//#################### Dashboard End ####################//


//#################### Mobile Check ####################//

	public function Mobile_check($phone_no)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no ='".$phone_no."' AND user_type = '4' AND status='Active'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		$digits = 6;
		$OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			
		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $user_master_id = $rows->id;
			}
			
			$update_sql = "UPDATE login_users SET otp = '".$OTP."', updated_at=NOW() WHERE id ='".$user_master_id."'";
			$update_result = $this->db->query($update_sql);
			
			$message_details = "Dear Customer your OTP :".$OTP;
			$this->sendSMS($phone_no,$message_details);
			$response = array("status" => "success", "msg" => "Mobile OTP", "user_master_id"=>$user_master_id, "phone_no"=>$phone_no, "otp"=>$OTP);
		
		} else {
			 $response = array("status" => "error", "msg" => "Invalid login");
		}
		
		return $response;
	}

//#################### Mobile Check End ####################//

//#################### Login ####################//

	public function Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no = '".$phone_no."' AND otp = '".$otp."' AND user_type = '4' AND status='Active'";
		$sql_result = $this->db->query($sql);

		if($sql_result->num_rows()>0)
		{
			$update_sql = "UPDATE login_users SET mobile_verify ='Y' WHERE id='$user_master_id'";
			$update_result = $this->db->query($update_sql);
			
			$gcmQuery = "SELECT * FROM notification_master WHERE mobile_key like '%" .$device_token. "%' AND user_master_id = '".$user_master_id."' LIMIT 1";
			$gcm_result = $this->db->query($gcmQuery);
			$gcm_ress = $gcm_result->result();
			if($gcm_result->num_rows()==0)
			{
				 $sQuery = "INSERT INTO notification_master (user_master_id,mobile_key,mobile_type) VALUES ('". $user_master_id . "','". $device_token . "','". $mobiletype . "')";
				 $update_gcm = $this->db->query($sQuery);
			}
						
			$user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.user_type, B.full_name, B.gender, B.profile_pic, B.address FROM login_users A, service_person_details B WHERE A.id = B.user_master_id AND A.id = '".$user_master_id."'";
			$user_result = $this->db->query($user_sql);
			if($user_result->num_rows()>0)
			{			
				foreach ($user_result->result() as $rows)
				{
						$user_master_id = $rows->user_master_id;
						$full_name = $rows->full_name;
						$phone_no = $rows->phone_no;
						$mobile_verify = $rows->mobile_verify;
						$email = $rows->email;
						$email_verify = $rows->email_verify;
						$gender = $rows->gender;
						$profile_pic = $rows->profile_pic;
						if ($profile_pic!=''){
							$profile_pic_url = base_url().'assets/persons/'.$profile_pic;
						} else {
							$profile_pic_url = "";
						}
					  	$address = $rows->address;
					  	$user_type = $rows->user_type;
				}
			}
			
			$userData  = array(
					"user_master_id" => $user_master_id,
					"full_name" => $full_name,
					"phone_no" => $phone_no,
					"mobile_verify" => $mobile_verify,
					"email" => $email,
					"email_verify" => $email_verify,
					"gender" => $gender,
					"profile_pic" => $profile_pic_url,
					"address" => $address,
					"user_type" => $user_type
				);

			$response = array("status" => "success", "msg" => "Login Successfully", "userData" => $userData);
			return $response;
		} else {
			$response = array("status" => "error", "msg" => "Invalid login");
			return $response;
		}
	}

//#################### Main Login End ####################//

//#################### Email Verify status ####################//

	public function Email_verifystatus($user_master_id)
	{
		$sql = "SELECT * FROM login_users WHERE id ='".$user_master_id."' AND user_type = '5' AND status='Active'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $email_verify = $rows->email_verify;
			}
		}
		$response = array("status" => "success", "msg" => "Email Verify Status", "user_master_id"=>$user_master_id, "email_verify_satus"=>$email_verify);
		return $response;
	}

//#################### Email Verify status End ####################//


//#################### Email Verify status ####################//

	public function Email_verification($user_master_id)
	{
		$sql = "SELECT * FROM login_users WHERE id ='".$user_master_id."' AND user_type = '5' AND status='Active'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $email_id = $rows->email;
			}
		}
		$enc_user_master_id = base64_encode($user_master_id);

		$subject = "SKILEX - Verification Email";
		$email_message = 'Please Click the Verification link. <a href="'. base_url().'home/email_verfication/'.$enc_user_master_id.'" target="_blank" style="background-color: #478ECC; font-size:15px; font-weight: bold; padding: 10px; text-decoration: none; color: #fff; border-radius: 5px;">Verify Your Email</a><br><br><br>';
		$this->sendMail($email_id,$subject,$email_message);
		
		
		$response = array("status" => "success", "msg" => "Email Verification Sent");
		return $response;
	}

//#################### Email Verify status End ####################//

//#################### Profile Update ####################//

	public function Profile_update($user_master_id,$full_name,$gender,$address,$city,$state,$zip,$edu_qualification,$language_known)
	{
		$update_sql= "UPDATE service_person_details SET full_name='$full_name',gender='$gender',address='$address',city='$city',state='$state',zip='$zip',edu_qualification='$edu_qualification',language_known='$language_known',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
		$update_result = $this->db->query($update_sql);
			
		$response = array("status" => "success", "msg" => "Profile Updated");
		return $response;
	}

//#################### Profile Update End ####################//

//#################### Profile Pic Update ####################//
	public function Profile_pic_upload($user_master_id,$profileFileName)
	{
            $update_sql= "UPDATE service_person_details SET profile_pic='$profileFileName' WHERE user_master_id='$user_master_id'";
			$update_result = $this->db->query($update_sql);
			$picture_url = base_url().'assets/persons/'.$profileFileName;

			$response = array("status" => "success", "msg" => "Profile Picture Updated","picture_url" =>$picture_url);
			return $response;
	}
//#################### Profile Pic Update End ####################//

//#################### List Aassigned services ####################//

	public function List_assigned_services($user_master_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.status,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.time_range,
					F.owner_full_name AS service_provider
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_provider_details F
				WHERE
					 A.serv_pers_id = '".$user_master_id."' AND A.status = 'Assigned' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_prov_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "list_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### List Aassigned services End ####################//


}

?>
