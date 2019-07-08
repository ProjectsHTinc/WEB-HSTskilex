<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomermodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


//#################### Email ####################//

	 function sendMail($email,$subject,$email_message)
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

	 function sendSMS($Phoneno,$Message)
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

	 function sendNotification($gcm_key,$title,$message,$mobiletype)
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

//#################### Mobile Check ####################//

	 function Mobile_check($phone_no)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no ='".$phone_no."' AND user_type = '5' AND status='Active'";
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
		} else {
			 $insert_sql = "INSERT INTO login_users (phone_no, otp, user_type, mobile_verify, email_verify, document_verify, status) VALUES ('". $phone_no . "','". $OTP . "','5','N','N','N','Active')";
             $insert_result = $this->db->query($insert_sql);
			 $user_master_id = $this->db->insert_id();

			 $insert_query = "INSERT INTO customer_details (user_master_id, status) VALUES ('". $user_master_id . "','Active')";
             $insert_result = $this->db->query($insert_query);
		}
		$message_details = "Dear Customer your OTP :".$OTP;
		$this->sendSMS($phone_no,$message_details);
		$response = array("status" => "success", "msg" => "Mobile OTP", "user_master_id"=>$user_master_id, "phone_no"=>$phone_no, "otp"=>$OTP);
		return $response;
	}

//#################### Mobile Check End ####################//

//#################### Login ####################//

	 function Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no = '".$phone_no."' AND otp = '".$otp."' AND user_type = '5' AND status='Active'";
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

			$user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.user_type, B.full_name, B.gender, B.profile_pic, B.address FROM login_users A, customer_details B WHERE A.id = B.user_master_id AND A.id = '".$user_master_id."'";
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
							$profile_pic_url = base_url().'assets/customers/'.$profile_pic;
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

	 function Email_verifystatus($user_master_id)
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

	 function Email_verification($user_master_id)
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

	 function Profile_update($user_master_id,$full_name,$gender,$address,$email)
	{
		$sql = "SELECT * FROM login_users WHERE id ='".$user_master_id."'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $email_verify = $rows->email_verify;
				  $old_email = $rows->email;
			}
		}

		if ($email != $old_email){
			$update_sql = "UPDATE login_users SET email ='$email', email_verify = 'N' WHERE id ='$user_master_id'";
			$update_result = $this->db->query($update_sql);
		}

		$update_sql = "UPDATE customer_details SET full_name ='$full_name', gender ='$gender', address ='$address' WHERE user_master_id ='$user_master_id'";
		$update_result = $this->db->query($update_sql);

		$response = array("status" => "success", "msg" => "Profile Updated");
		return $response;
	}

//#################### Profile Update End ####################//

//#################### Profile Pic Update ####################//
	 function Profile_pic_upload($user_master_id,$profileFileName)
	{
            $update_sql= "UPDATE customer_details SET profile_pic='$profileFileName' WHERE user_master_id='$user_master_id'";
			$update_result = $this->db->query($update_sql);
			$picture_url = base_url().'assets/customer/'.$profileFileName;

			$response = array("status" => "success", "msg" => "Profile Picture Updated","picture_url" =>$picture_url);
			return $response;
	}
//#################### Profile Pic Update End ####################//

//#################### Main Category ####################//
	 function View_maincategory($user_master_id)
	{
			$query = "SELECT id,main_cat_name,main_cat_ta_name,cat_pic from main_category WHERE status = 'Active'";
			$res = $this->db->query($query);

			 if($res->num_rows()>0){
			    foreach ($res->result() as $rows)
				{
					$cat_pic = $rows->cat_pic;
					if ($cat_pic != ''){
						$cat_pic_url = base_url().'assets/category/'.$cat_pic;
					}else {
						 $cat_pic_url = '';
					}

					$catData[]  = array(
							"cat_id" => $rows->id,
							"cat_name" => $rows->main_cat_name,
							"cat_ta_name" => $rows->main_cat_ta_name,
							"cat_pic_url" => $cat_pic_url
					);
				}
			     	$response = array("status" => "success", "msg" => "View Category","categories"=>$catData);

			}else{
			        $response = array("status" => "error", "msg" => "Category not found");
			}

			return $response;
	}
//#################### Main Category End ####################//

//#################### Sub Category ####################//
	 function View_subcategory($main_cat_id)
	{
			$query = "SELECT id,sub_cat_name,sub_cat_ta_name,sub_cat_pic from sub_category WHERE main_cat_id = '$main_cat_id' AND status = 'Active'";
			$res = $this->db->query($query);

			 if($res->num_rows()>0){
			    foreach ($res->result() as $rows)
				{
					$sub_cat_pic = $rows->sub_cat_pic;
					if ($sub_cat_pic != ''){
						$sub_cat_pic_url = base_url().'assets/category/'.$sub_cat_pic;
					}else {
						 $sub_cat_pic_url = '';
					}
					$subcatData[]  = array(
							"main_cat_id" => $main_cat_id,
							"sub_cat_id" => $rows->id,
							"sub_cat_name" => $rows->sub_cat_name,
							"sub_cat_ta_name" => $rows->sub_cat_ta_name,
							"sub_cat_pic_url" => $sub_cat_pic_url
					);
				}
			     	$response = array("status" => "success", "msg" => "View Sub Category","sub_categories"=>$subcatData);

			}else{
			        $response = array("status" => "error", "msg" => "Sub Category not found");
			}

			return $response;
	}
//#################### Sub Category End ####################//

//#################### Services List ####################//
	 function Services_list($main_cat_id,$sub_cat_id)
	{
			$query = "SELECT * from services WHERE main_cat_id = '$main_cat_id' AND sub_cat_id = '$sub_cat_id' AND status = 'Active'";
			$res = $this->db->query($query);

			 if($res->num_rows()>0){
			    foreach ($res->result() as $rows)
				{
					$service_pic = $rows->service_pic;
					if ($service_pic != ''){
						$service_pic_url = base_url().'assets/category/'.$service_pic;
					}else {
						 $service_pic_url = '';
					}
					$subcatData[]  = array(
							"service_id" => $rows->id,
							"main_cat_id" => $rows->main_cat_id,
							"sub_cat_id" => $rows->sub_cat_id,
							"service_name" => $rows->service_name,
							"service_ta_name" => $rows->service_ta_name,
							"service_pic_url" => $service_pic_url
					);
				}
			     	$response = array("status" => "success", "msg" => "View Services","services"=>$subcatData);

			}else{
			        $response = array("status" => "error", "msg" => "Services not found");
			}

			return $response;
	}
//#################### Services List End ####################//
//#################### Services Details ####################//

    function service_details($service_id){
      $query = "SELECT * from services WHERE id = '$service_id'  AND status = 'Active'";
      $res = $this->db->query($query);

       if($res->num_rows()>0){
          foreach ($res->result() as $rows)
        {}
          $service_pic = $rows->service_pic;
          if ($service_pic != ''){
            $service_pic_url = base_url().'assets/category/'.$service_pic;
          }else {
             $service_pic_url = '';
          }
          $subcatData  = array(
              "service_id" => $rows->id,
              "main_cat_id" => $rows->main_cat_id,
              "sub_cat_id" => $rows->sub_cat_id,
              "service_name" => $rows->service_name,
              "service_ta_name" => $rows->service_ta_name,
              "service_pic_url" => $service_pic_url,
              "is_advance_payment"=>$rows->is_advance_payment,
              "advance_amount" => $rows->advance_amount,
              "rate_card"=>$rows->rate_card,
              "rate_card_details" => $rows->rate_card_details,
              "rate_card_details_ta" => $rows->rate_card_details_ta,
              "inclusions" => $rows->inclusions,
              "inclusions_ta" => $rows->inclusions_ta,
              "exclusions"=>$rows->exclusions,
              "exclusions_ta" => $rows->exclusions_ta,
              "service_procedure" => $rows->service_procedure,
              "service_procedure_ta"=>$rows->service_procedure_ta,
              "others" => $rows->others,
              "others_ta"=>$rows->others_ta

          );

            $response = array("status" => "success", "msg" => "Service Details","service_details"=>$subcatData);

      }else{
              $response = array("status" => "error", "msg" => "Services not found");
      }

      return $response;

   }

//#################### Services Details  ####################//



//#################### Service Order ####################//
	 function Book_service($customer_id,$contact_person,$main_cat_id,$sub_cat_id,$service_id,$order_date,$order_timeslot,$service_latlon,$service_location,$service_address)
	{
		 $insert_sql = "INSERT INTO service_orders (customer_id, contact_person, main_cat_id, sub_cat_id, service_id,order_date, order_timeslot, service_latlon, service_location, service_address, status, created_at,created_by) VALUES
						('". $customer_id . "','". $contact_person . "','". $main_cat_id . "', '". $sub_cat_id . "','". $service_id . "','". $order_date . "','". $order_timeslot . "','". $service_latlon . "','". $service_location . "', '". $service_address . "','Booked', now(),'". $customer_id . "')";
		 $insert_result = $this->db->query($insert_sql);
		$response = array("status" => "success", "msg" => "Service Booked");
		return $response;
	}
//#################### Service Order End ####################//

//#################### Service Order List ####################//
	 function Service_order_list ($user_master_id)
	{
		$query = "SELECT * from service_orders WHERE customer_id = '$user_master_id'";
		$res = $this->db->query($query);

		 if($res->num_rows()>0){
			 $order_list = $res->result();
			$response = array("status" => "success", "msg" => "View Order List","services_orderlist"=>$order_list);
		 } else {
			 $response = array("status" => "error", "msg" => "Service order list not found");
		 }

		return $response;
	}
//#################### Service Order List End ####################//


//#################### Service Reviews Add ####################//
	 function Service_reviewsadd($user_master_id,$service_order_id,$ratings,$reviews)
	{
		$insert_sql = "INSERT INTO service_reviews (service_order_id, customer_id, rating, review, status,created_at,created_by) VALUES
					('". $service_order_id . "','". $user_master_id . "','". $ratings . "', '". $reviews . "','Pending', now(),'". $user_master_id . "')";
		$insert_result = $this->db->query($insert_sql);
		$response = array("status" => "success", "msg" => "Review Added");
		return $response;
	}
//#################### Service Reviews Add End ####################//


//#################### Service Reviews Add ####################//
	 function Service_reviewslist($service_order_id)
	{
		$query = "SELECT * from service_reviews WHERE service_order_id = '$service_order_id'";
		$res = $this->db->query($query);

		 if($res->num_rows()>0){
			 $review_list = $res->result();
			$response = array("status" => "success", "msg" => "View Reviews List","services_reviewlist"=>$review_list);
		 } else {
			 $response = array("status" => "error", "msg" => "Service order Reviews not found");
		 }

		 return $response;
	}
//#################### Service Reviews Add End ####################//


}

?>
