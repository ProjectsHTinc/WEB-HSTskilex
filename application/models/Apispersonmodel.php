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

		$digits = 4;
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

	public function Profile_update($user_master_id,$full_name,$gender,$email)
	{
		$update_sql= "UPDATE service_person_details SET full_name='$full_name',gender='$gender', updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
		$update_result = $this->db->query($update_sql);

		$response = array("status" => "success", "msg" => "Profile Updated");
		return $response;
	}


// public function Profile_update($user_master_id,$full_name,$gender,$address,$city,$state,$zip,$edu_qualification,$language_known)
// 	{
// 		$update_sql= "UPDATE service_person_details SET full_name='$full_name',gender='$gender',address='$address',city='$city',state='$state',zip='$zip',edu_qualification='$edu_qualification',language_known='$language_known',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
// 		$update_result = $this->db->query($update_sql);

// 		$response = array("status" => "success", "msg" => "Profile Updated");
// 		return $response;
// 	}

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


function user_info($user_master_id){
    $select="SELECT * FROM login_users as lu LEFT JOIN service_person_details as cd ON lu.id=cd.user_master_id WHERE lu.id='$user_master_id'";
    $res = $this->db->query($select);
    if($res->num_rows()==1){
      foreach($res->result()  as $rows){}
        $profile=$rows->profile_pic;
        if(empty($profile)){
          $pic="";
        }else{
            $pic=base_url().'assets/persons/'.$profile;
        }
        $user_info=array(
          "phone_no"=>$rows->phone_no,
          "email"=>$rows->email,
          "full_name"=>$rows->full_name,
          "gender"=>$rows->gender,
          "profile_pic"=>$pic,
        );
        $response=array("status"=>"success","msg"=>"User information","user_details"=>$user_info,"msg"=>"","msg_ta"=>"");

    }else{
        $response=array("status"=>"error","msg"=>"No User information found","msg"=>"User details not found!","msg_ta"=>"பயனர் விபரங்கள் கிடைக்கவில்லை!");
    }
    return $response;
  }


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
					E.from_time,
					E.to_time,
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

//#################### Detailed Assigned services ####################//

	public function Detail_assigned_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.serv_pers_id,
					A.service_location,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time,
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND A.status = 'Assigned' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id
           AND A.`order_timeslot` = E.id AND A.serv_pers_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "detail_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### Assigned detailed services End ####################//

//#################### Initiate services ####################//
	public function Initiate_services($user_master_id,$service_order_id)
	{
            $update_sql = "UPDATE service_orders SET status = 'Initiated', iniate_datetime =NOW() ,updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
			$update_result = $this->db->query($update_sql);

			$sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','Initiated',NOW(),'". $user_master_id . "')";
			$ins_query = $this->db->query($sQuery);


		$sQuery = "SELECT * FROM service_orders WHERE id ='".$service_order_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_id = $rows->customer_id;
					$contact_person_name = $rows->contact_person_name;
					$contact_person_number = $rows->contact_person_number;
				}
		}

		$sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$customer_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_mobile_key = $rows->mobile_key;
					$customer_mobile_type = $rows->mobile_type;
				}
		}


		//$title = "Service Request Initiated";
		$message_details = "SKILEX - Service Request Initiated";

		$this->sendSMS($contact_person_number,$message_details);

		//$this->sendNotification($customer_mobile_key,$title,$message_details,$customer_mobile_type)

			$response = array("status" => "success", "msg" => "Service Order Initiated");
			return $response;
	}
//#################### Initiat services End ####################//


########### List Ongoing services ####################//

	public function List_ongoing_services($user_master_id)
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
					E.from_time,
					E.to_time,
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.serv_pers_id = '".$user_master_id."' AND (A.status = 'Initiated' OR A.status = 'Started' OR A.status = 'Ongoing') AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_pers_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "list_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### List Ongoing services End ####################//


########### Detailed Initiated  services ####################//

	public function Detail_initiated_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					A.service_address,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					A.serv_pers_id,
					F.full_name AS service_person,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time,
					A.status
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND A.status = 'Initiated' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_pers_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "detail_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### Detailed Initiated  services End ####################//




########### Initiated detailed services ####################//

	public function Service_process($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					A.service_address,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					A.serv_pers_id,
					F.owner_full_name AS service_provider,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time,
					A.status
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_provider_details F
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND A.status = 'Initiated' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id
           AND A.serv_prov_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "detail_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### Initiated detailed services End ####################//



//#################### Request otp ####################//
	public function Request_otp($user_master_id,$service_order_id)
	{
		$sql = "SELECT * FROM service_orders WHERE id ='".$service_order_id."' AND serv_pers_id = '".$user_master_id."'";
		$user_result = $this->db->query($sql);

		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				   $contact_person_number = $rows->contact_person_number;
			}

			$digits = 4;
			$OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

			$update_sql = "UPDATE service_orders SET service_otp = '".$OTP."', updated_at=NOW() WHERE id ='".$service_order_id."'";
			$update_result = $this->db->query($update_sql);

			$update_sql = "UPDATE service_orders SET status = 'Started', start_datetime =NOW() ,updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
			$update_result = $this->db->query($update_sql);

			$sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','Started',NOW(),'". $user_master_id . "')";
			$ins_query = $this->db->query($sQuery);

			 $message_details = "Dear Customer - Service OTP :".$OTP;
			$this->sendSMS($contact_person_number,$message_details);

			$response = array("status" => "success", "msg" => "OTP send");
		} else {
			$response = array("status" => "error", "msg" => "Something Wrong");
		}

		return $response;
	}
//#################### Request otp End ####################//



//#################### Start services ####################//
	public function Start_services($user_master_id,$service_order_id,$service_otp)
	{
		$sql = "SELECT * FROM service_orders WHERE id ='".$service_order_id."' AND serv_pers_id = '".$user_master_id."' AND service_otp = '".$service_otp."'";
		$user_result = $this->db->query($sql);

		if($user_result->num_rows()>0)
		{
			$update_sql = "UPDATE service_orders SET status = 'Ongoing', start_datetime =NOW() ,updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
			$update_result = $this->db->query($update_sql);

			$sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','Ongoing',NOW(),'". $user_master_id . "')";
			$ins_query = $this->db->query($sQuery);

			$sQuery = "SELECT * FROM service_orders WHERE id ='".$service_order_id."'";
			$user_result = $this->db->query($sQuery);
			if($user_result->num_rows()>0)
			{
					foreach ($user_result->result() as $rows)
					{
						$customer_id = $rows->customer_id;
						$contact_person_name = $rows->contact_person_name;
						$contact_person_number = $rows->contact_person_number;
					}
			}

			$sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$customer_id."'";
			$user_result = $this->db->query($sQuery);
			if($user_result->num_rows()>0)
			{
					foreach ($user_result->result() as $rows)
					{
						$customer_mobile_key = $rows->mobile_key;
						$customer_mobile_type = $rows->mobile_type;
					}
			}


			//$title = "Service Request Ongoing";
			$message_details = "SKILEX - Service Request Ongoing";

			$this->sendSMS($contact_person_number,$message_details);

			//$this->sendNotification($customer_mobile_key,$title,$message_details,$customer_mobile_type)

			$response = array("status" => "success", "msg" => "Service Started");
		} else {
			$response = array("status" => "error", "msg" => "Something Wrong");
		}

		return $response;
	}
//#################### Start services End ####################//





//#################### Detailed Ongoing  services ####################//

	public function Detail_ongoing_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					A.serv_pers_id,
					F.owner_full_name AS service_provider,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time,
					A.status,
					A.start_datetime,
					A.material_notes
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_provider_details F
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND (A.status = 'Started' OR A.status = 'Ongoing') AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id
           AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_prov_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		$addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '".$service_order_id."' AND status = 'Active'";
		$addtional_serv_res = $this->db->query($addtional_serv);
		$addtional_serv_count = $addtional_serv_res->num_rows();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "detail_services_order"=>$service_result, "addtional_services_count"=>$addtional_serv_count);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### Ongoing detailed services End ####################//


//#################### Person Category list ####################//

	public function Category_list($user_master_id)
	{
		$sQuery = "SELECT
					A.main_cat_id,
					B.main_cat_name,
					B.main_cat_ta_name,
				FROM
					serv_prov_pers_skills A,
					main_category B,
				WHERE
					A.user_master_id = '".$user_master_id."' AND A.main_cat_id = B.id AND A.status = 'Active'";
		$ser_result = $this->db->query($sQuery);

		$category_result = $ser_result->result();
		$category_count = $ser_result->num_rows();

		if($ser_result->num_rows()>0)
		{
			$response = array("status" => "success", "msg" => "Category list", "category_count" => $category_count, "category_list"=>$category_result);
		} else {
			$response = array("status" => "error", "msg" => "Services Not Found");
		}

		return $response;
	}

//#################### Person Category list End ####################//

//#################### Sub Category list ####################//

	public function Sub_category_list($category_id)
	{
		$sQuery = "SELECT * FROM sub_category WHERE main_cat_id = '$category_id' AND status='Active'";
		$cat_result = $this->db->query($sQuery);

		$category_result = $cat_result->result();
		$category_count = $cat_result->num_rows();

		if($cat_result->num_rows()>0)
		{
			$response = array("status" => "success", "msg" => "Sub Category list", "sub_category_count" => $category_count, "sub_category_list"=>$category_result);
		} else {
			$response = array("status" => "error", "msg" => "Category Not Found");
		}

		return $response;
	}

//#################### Sub Category list End ####################//

//#################### Services list ####################//

public function Services_list($category_id,$sub_category_id)
	{
		$sQuery = "SELECT
					A.main_cat_id,
					B.main_cat_name,
					B.main_cat_ta_name,
					A.sub_cat_id,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					A.id AS service_id,
					A.service_name,
					A.service_ta_name,
					A.service_pic
				FROM
					services A,
					main_category B,
					sub_category C
				WHERE
					A.main_cat_id = '$category_id' AND A.sub_cat_id = '$sub_category_id' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.status = 'Active'";
		$ser_result = $this->db->query($sQuery);

		$services_result = $ser_result->result();
		$services_count = $ser_result->num_rows();

		if($ser_result->num_rows()>0)
		{
			$response = array("status" => "success", "msg" => "Services list", "service_count" => $services_count, "service_list"=>$services_result);
		} else {
			$response = array("status" => "error", "msg" => "Services Not Found");
		}

		return $response;
	}

	/* public function Services_list($user_master_id)
	{
		$sQuery = "SELECT
					A.main_cat_id,
					B.main_cat_name,
					B.main_cat_ta_name,
					A.sub_cat_id,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					A.id AS service_id,
					D.service_name,
					D.service_ta_name,
					D.rate_card,
					D.service_pic
				FROM
					serv_prov_pers_skills A,
					main_category B,
					sub_category C,
					services D
				WHERE
					A.user_master_id = '".$user_master_id."' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.`service_id` = D.id AND A.status = 'Active'";
		$ser_result = $this->db->query($sQuery);

		$services_result = $ser_result->result();
		$services_count = $ser_result->num_rows();

		if($ser_result->num_rows()>0)
		{
			$response = array("status" => "success", "msg" => "Services list", "service_count" => $services_count, "service_list"=>$services_result);
		} else {
			$response = array("status" => "error", "msg" => "Services Not Found");
		}

		return $response;
	} */

//#################### Services list End ####################//

//#################### Add addtional Services ####################//

	public function Add_addtional_services($user_master_id,$service_order_id,$service_id,$ad_service_rate_card)
	{
		$sQuery = "INSERT INTO service_order_additional (service_order_id,service_id,ad_service_rate_card,status,created_at,created_by) VALUES ('". $service_order_id . "','". $service_id . "','". $ad_service_rate_card . "','Active',NOW(),'". $user_master_id . "')";
		$ins_query = $this->db->query($sQuery);

		if($ins_query){
				$response=array("status" => "success","msg" => "Services Added Sucessfully!..");
           }else{
				$response=array("status" => "error","msg" => "Something Wrong");
           }

		return $response;
	}

//#################### Add addtional Services End ####################//


//#################### Additional service list orders ####################//

	public function List_addtional_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
						A.id,
						A.`ad_service_rate_card`,
						B.service_name,
						B.service_ta_name,
						C.main_cat_name,
						C.main_cat_ta_name,
						D.sub_cat_name,
						D.sub_cat_ta_name
					FROM
						service_order_additional A,
						services B,
						main_category C,
						sub_category D
					WHERE
						A.service_order_id = '".$service_order_id."' AND A.service_id = B.id AND B.main_cat_id = C.id AND B.sub_cat_id = D.id";
		$serv_result = $this->db->query($sQuery);

		$service_result = $serv_result->result();
		$service_count = $serv_result->num_rows();

		if($serv_result->num_rows()>0)
		{
			$response = array("status" => "success", "msg" => "Addtional Service list", "service_count" => $service_count, "service_list"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Services Not Found");
		}

		return $response;
	}

//#################### Additional service orders End ####################//

//#################### Remove Additional service remove ####################//

	public function Remove_addtional_services($user_master_id,$order_additional_id)
	{
		$sQuery = "DELETE FROM `service_order_additional` WHERE id = '".$order_additional_id."'";
		$serv_result = $this->db->query($sQuery);

		if($serv_result)
		{
			$response = array("status" => "success", "msg" => "Additional Service Removed");
		} else {
			$response = array("status" => "error", "msg" => "Services Not Found");
		}

		return $response;
	}

//#################### Remove Additional service remove End ####################//


//#################### Upload service bills ####################//
	public function Upload_service_bills($user_master_id,$service_order_id,$documentFileName)
	{
		$sQuery = "INSERT INTO service_order_bills(service_order_id,serv_pers_id,file_name,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','". $documentFileName . "',NOW(),'". $user_master_id . "')";
		$ins_query = $this->db->query($sQuery);
		$last_insert_id = $this->db->insert_id();
		$document_url = base_url().'assets/bills/'.$documentFileName;

		$response = array("status" => "success", "msg" => "Service Bill Uploaded");
		return $response;
	}
//#################### Upload service bills End ####################//



//#################### Service bills list ####################//

	public function List_service_bills($user_master_id,$service_order_id)
	{
		 $bill_url = base_url().'assets/bills/';

		$sQuery = "SELECT * FROM service_order_bills WHERE service_order_id = '".$service_order_id."' AND serv_pers_id ='".$user_master_id."'";
		$doc_result = $this->db->query($sQuery);


		if($doc_result->num_rows()!=0)
		{
				foreach ($doc_result->result() as $rows)
				{
					 $id = $rows->id;
					 $bill_copy = $rows->file_name;

					$data[]  = array(
						"id" => $id,
						"bill_copy" => $bill_copy,
						"bill_copy_url" => $bill_url.$bill_copy
					);
				}
			$response = array("status" => "success", "msg" => "Bill list", "bill_copy_result"=>$data);
		} else {
			$response = array("status" => "error", "msg" => "Bills Not Found");
		}
		return $response;

	}

//#################### Service bills list End ####################//


//#################### Update ongoing services ####################//

	public function Update_ongoing_services($user_master_id,$service_order_id,$material_notes)
	{
		$update_sql = "UPDATE service_orders SET material_notes = '".$material_notes."', updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
		$update_result = $this->db->query($update_sql);

		if($update_result)
		{
			$response = array("status" => "success", "msg" => "Service Order Updated");
		} else {
			$response = array("status" => "error", "msg" => "Something Wrong");
		}

		return $response;
	}

//#################### Additional service remove End ####################//


//#################### Cancel service Resons ####################//

	public function Cancel_service_reasons($user_type)
	{
		$sQuery = "SELECT id, reasons FROM cancel_master WHERE user_type ='".$user_type."'";
		$res_result = $this->db->query($sQuery);
		$reason_result = $res_result->result();


		if($res_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Cancel Service Reasons", "list_reasons"=>$reason_result);
		} else {
			$response = array("status" => "error", "msg" => "Reasons Not found");
		}
		return $response;
	}

//#################### Cancel service Resons End ####################//


//#################### Cancel services ####################//

	public function Cancel_services($user_master_id,$service_order_id,$cancel_master_id,$comments)
	{
		$update_sql = "UPDATE service_orders SET status = 'Canceled', updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
		$update_result = $this->db->query($update_sql);

		$sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','Canceled',NOW(),'". $user_master_id . "')";
		$ins_query = $this->db->query($sQuery);

		$sQuery = "INSERT INTO cancel_history (cancel_master_id,user_master_id,service_order_id,comments,created_at,created_by) VALUES ('". $cancel_master_id . "','". $user_master_id . "','". $service_order_id . "','". $comments . "',NOW(),'". $user_master_id . "')";
		$ins_query = $this->db->query($sQuery);


		$sQuery = "SELECT * FROM service_orders WHERE id ='".$service_order_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_id = $rows->customer_id;
					$contact_person_name = $rows->contact_person_name;
					$contact_person_number = $rows->contact_person_number;
				}
		}

		$sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$customer_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_mobile_key = $rows->mobile_key;
					$customer_mobile_type = $rows->mobile_type;
				}
		}


		//$title = "Service Request Canceled";
		$message_details = "SKILEX - Service Request Canceled";

		$this->sendSMS($contact_person_number,$message_details);

		//$this->sendNotification($customer_mobile_key,$title,$message_details,$customer_mobile_type)

		if($update_result){
				$response=array("status" => "success","msg" => "Cancel Services");
           }else{
				$response=array("status" => "error");
           }

		return $response;
	}

//#################### Cancel services End ####################//


//#################### List canceled services ####################//

	public function List_canceled_services($user_master_id)
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
					E.from_time,
					E.to_time
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E
				WHERE
					 A.serv_pers_id = '".$user_master_id."' AND A.status = 'Canceled' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "list_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### List canceled services End ####################//



//#################### Detail canceled services ####################//

	public function Detail_canceled_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time

				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND A.status = 'Canceled' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		$reason_query = "SELECT
						A.id,C.reasons,A.`comments`,B.id as cancel_user_id,D.id as role_id,D.role_name
					FROM
						cancel_history A,
						login_users B,
						cancel_master C,
						user_role D
					WHERE
						A.`service_order_id` = '".$service_order_id."' AND A.`user_master_id` = B.id AND B.id AND A.`cancel_master_id` = C.id AND B.user_type = D.id";
		$reason_res = $this->db->query($reason_query);
		$reason_result = $reason_res->result();
			if($reason_res->num_rows()>0)
			{
				foreach ($reason_res->result() as $rows)
				{
					 $role_id = $rows->role_id;
					 $cancel_user_id = $rows->cancel_user_id;
				}
			}

		if ($role_id == '3'){
			$usrQuery = "SELECT owner_full_name AS name FROM service_provider_details WHERE user_master_id = '".$cancel_user_id."' LIMIT 1";
		} else if ($role_id == '4'){
			$usrQuery = "SELECT full_name AS name FROM service_person_details WHERE user_master_id = '".$cancel_user_id."' LIMIT 1";
		}
		else {
			$usrQuery = "SELECT full_name AS name FROM customer_details WHERE user_master_id = '".$cancel_user_id."' LIMIT 1";
		}
			$usr_ress = $this->db->query($usrQuery);
			$usr_result = $usr_ress->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order Details", "detail_services_order"=>$service_result, "cancel_reason"=>$reason_result, "canceld_by"=>$usr_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order Not found");
		}
		return $response;
	}

//#################### Detail canceled services End ####################//




//#################### Complete services ####################//

	public function Complete_services($user_master_id,$service_order_id)
	{

	$sQuery = "SELECT * FROM service_orders WHERE id = '".$service_order_id."'";

		$query_res = $this->db->query($sQuery);

		if($query_res->num_rows()>0)
		{
				foreach ($query_res->result() as $rows)
				{
					 $advance_payment_status = $rows->advance_payment_status;
					 $advance_amount_paid = $rows->advance_amount_paid;
					 $service_rate_card = $rows->service_rate_card;
				}

		$sQuery = "SELECT SUM(ad_service_rate_card) AS add_service_amount FROM service_order_additional WHERE service_order_id = '".$service_order_id."'";
		$query_res = $this->db->query($sQuery);
			if($query_res->num_rows()>0)
			{
					foreach ($query_res->result() as $rows)
					{
						 $add_service_amount = $rows->add_service_amount;
					}
			} else {
					$add_service_amount = '0.00';
			}

		 $total_amount  = $service_rate_card+$add_service_amount;


		$sQuery = "SELECT * FROM service_payments WHERE service_order_id = '".$service_order_id."'";
		$query_res = $this->db->query($sQuery);
			if($query_res->num_rows()>0) {
				$sQuery = "UPDATE service_payments SET service_amount ='". $service_rate_card . "', ad_service_amount='". $add_service_amount . "',total_service_amount  ='". $total_amount . "', net_service_amount = '". $total_amount . "', status = 'Pending',  updated_by  = '".$user_master_id."', updated_at =NOW() WHERE service_order_id ='".$service_order_id."'";

				$update_result = $this->db->query($sQuery);

			} else {

				$sQuery = "INSERT INTO service_payments (service_order_id,service_amount,ad_service_amount,total_service_amount,status,created_at,created_by) VALUES ('". $service_order_id . "','". $service_rate_card . "','". $add_service_amount . "','". $total_amount . "','Pending',NOW(),'". $user_master_id . "')";
				$ins_query = $this->db->query($sQuery);
			}

		$sQuery = "UPDATE service_orders SET status = 'Completed', finish_datetime =NOW(), updated_by  = '".$user_master_id."', updated_at =NOW() WHERE id ='".$service_order_id."'";
		$update_result = $this->db->query($sQuery);

		$sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('". $service_order_id . "','". $user_master_id . "','Completed',NOW(),'". $user_master_id . "')";
		$ins_query = $this->db->query($sQuery);


		$sQuery = "SELECT * FROM service_orders WHERE id ='".$service_order_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_id = $rows->customer_id;
					$contact_person_name = $rows->contact_person_name;
					$contact_person_number = $rows->contact_person_number;
				}
		}

		$sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$customer_id."'";
		$user_result = $this->db->query($sQuery);
		if($user_result->num_rows()>0)
		{
				foreach ($user_result->result() as $rows)
				{
					$customer_mobile_key = $rows->mobile_key;
					$customer_mobile_type = $rows->mobile_type;
				}
		}


		$title = "Service Request Completed";
		$message_details = "SKILEX - Service Request Completed";

	$this->sendSMS($contact_person_number,$message_details);

		//$this->sendNotification($customer_mobile_key,$title,$message_details,$customer_mobile_type)


			$response=array("status" => "success","msg" => "Completed Services");
	   }else{
			$response=array("status" => "error","msg" => "Something Wrong");
	   }

		return $response;
	}

//#################### Complete services End ####################//



//#################### List completed services ####################//

	public function List_completed_services($user_master_id)
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
					E.from_time,
					E.to_time,
					F.owner_full_name AS service_provider,
					G.status AS Payment_status
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_provider_details F,
					service_payments G
				WHERE
					 A.serv_pers_id = '".$user_master_id."' AND A.status = 'Completed' OR A.Status = 'Paid' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_prov_id = F.user_master_id AND A.id=G.service_order_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "list_services_order"=>$service_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### List completed services End ####################//


//#################### Detail completed services ####################//

	public function Detail_completed_services($user_master_id,$service_order_id)
	{
		$sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%W %M %e %Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
					A.serv_pers_id,
					F.full_name AS service_person,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time,
					A.status,
					A.start_datetime,
					A.finish_datetime,
					A.material_notes,
					H.owner_full_name AS service_provider
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F,
					service_provider_details H
				WHERE
					 A.id = '".$service_order_id."' AND A.serv_pers_id = '".$user_master_id."' AND A.status = 'Completed' OR A.status= 'Paid' AND A.`main_cat_id` = B.id AND A.`sub_cat_id` = C.id AND A.`service_id` = D.id AND A.`order_timeslot` = E.id AND A.serv_prov_id = H.user_master_id AND A.serv_pers_id = F.user_master_id";
		$serv_result = $this->db->query($sQuery);
		$service_result = $serv_result->result();

		$addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '".$service_order_id."' AND status = 'Active'";
		$addtional_serv_res = $this->db->query($addtional_serv);
		$addtional_serv_count = $addtional_serv_res->num_rows();

		$trans_query = "SELECT * FROM service_payments WHERE service_order_id = '".$service_order_id."'";
		$trans_res = $this->db->query($trans_query);
		$trans_result = $trans_res->result();

		if($serv_result->num_rows()>0) {
			$response = array("status" => "success", "msg" => "Service Order List", "detail_services_order"=>$service_result, "addtional_services_count"=>$addtional_serv_count, "transaction_details"=>$trans_result);
		} else {
			$response = array("status" => "error", "msg" => "Service Order List Not found");
		}
		return $response;
	}

//#################### Detail completed services End ####################//

//#################### Service Person Tracking ####################//

	public function Add_tracking($user_master_id,$lat_1,$lon_1,$location,$lat_2,$lon_2,$miles_distance_bw,$service_order_id)
	{
		$sQuery = "INSERT INTO serv_pers_tracking (user_master_id,lat_1,lon_1,location,lat_2,lon_2,miles_distance_bw,service_order_id,created_at) VALUES ('". $user_master_id . "','". $lat_1 . "','". $lon_1 . "','". $location . "','". $lat_2 . "','". $lon_2 . "','". $miles_distance_bw . "','". $service_order_id . "',NOW())";
		$ins_query = $this->db->query($sQuery);

		if($ins_query){
				$response=array("status" => "success","msg" => "Tracking Details Added");
           }else{
				$response=array("status" => "error","msg" => "Something Wrong");

           }
		return $response;
	}

//#################### Cancel services End ####################//

}

?>
