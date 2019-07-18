<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomermodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


//-------------------- Email -------------------//

	 function sendMail($email,$subject,$email_message)
	{
		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: Webmaster<hello@happysanz.com>' . "\r\n";
		mail($email,$subject,$email_message,$headers);
	}

//-------------------- Email End -------------------//


//-------------------- SMS -------------------//

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

//-------------------- SMS End -------------------//


//-------------------- Notification -------------------//

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

//-------------------- Notification End -------------------//

//-------------------- Mobile Check -------------------//

	 function Mobile_check($phone_no)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no ='".$phone_no."' AND user_type = '5' AND status='Active'";
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

//-------------------- Mobile Check End -------------------//

  //-------------------- guest login -------------------//


  function guest_login($unique_number,$device_token,$mobiletype,$user_stat){
    $query="INSERT INTO notification_master (user_master_id,mobile_key,mobile_type,user_stat,created_at) VALUES('$unique_number','$device_token','$mobiletype','$user_stat',NOW())";
    $res_query = $this->db->query($query);
    if($res_query){
      	$response = array("status" => "success", "msg" => "Success");
    }else{
      	$response = array("status" => "error", "msg" => "Something went wrong");
    }
    	return $response;


  }



    //-------------------- guest login -------------------//


//-------------------- Login -------------------//

	 function Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype,$unique_number)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no = '".$phone_no."' AND otp = '".$otp."' AND user_type = '5' AND status='Active'";
		$sql_result = $this->db->query($sql);

		if($sql_result->num_rows()>0)
		{
		  $update_sql = "UPDATE login_users SET mobile_verify ='Y' WHERE id='$user_master_id'";
			$update_result = $this->db->query($update_sql);


      $update_unique_number="UPDATE notification_master SET user_master_id='$user_master_id',user_stat='Register' WHERE user_master_id='$unique_number'";
      $update_unique_number_result = $this->db->query($update_unique_number);



			$gcmQuery = "SELECT * FROM notification_master WHERE mobile_key like '%" .$device_token. "%' AND user_master_id = '".$user_master_id."' LIMIT 1";
			$gcm_result = $this->db->query($gcmQuery);
			$gcm_ress = $gcm_result->result();
			if($gcm_result->num_rows()==0)
			{
				 $sQuery = "INSERT INTO notification_master (user_master_id,mobile_key,mobile_type) VALUES ('". $user_master_id . "','". $device_token . "','". $mobiletype . "')";
				 $update_gcm = $this->db->query($sQuery);
			}

			$user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.user_type, B.full_name, B.gender, B.profile_pic FROM login_users A, customer_details B WHERE A.id = B.user_master_id AND A.id = '".$user_master_id."'";
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
					"user_type" => $user_type
				);

			$response = array("status" => "success", "msg" => "Login Successfully", "userData" => $userData);
			return $response;
		} else {
			$response = array("status" => "error", "msg" => "Invalid login");
			return $response;
		}
	}

//-------------------- Main Login End -------------------//

//-------------------- Email Verify status -------------------//

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

//-------------------- Email Verify status End -------------------//


//-------------------- Email Verify status -------------------//

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

//-------------------- Email Verify status End -------------------//

//-------------------- Profile Update -------------------//

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

//-------------------- Profile Update End -------------------//

//-------------------- Profile Pic Update -------------------//
	 function Profile_pic_upload($user_master_id,$profileFileName)
	{
            $update_sql= "UPDATE customer_details SET profile_pic='$profileFileName' WHERE user_master_id='$user_master_id'";
			$update_result = $this->db->query($update_sql);
			$picture_url = base_url().'assets/customer/'.$profileFileName;

			$response = array("status" => "success", "msg" => "Profile Picture Updated","picture_url" =>$picture_url);
			return $response;
	}
//-------------------- Profile Pic Update End -------------------//


  function view_banner_list($user_master_id){
    $query = "SELECT * from banners WHERE status = 'Active'";
    $res = $this->db->query($query);

     if($res->num_rows()>0){
        foreach ($res->result() as $rows)
      {
        $cat_pic = $rows->banner_img;
        if ($cat_pic != ''){
          $ban_pic_url = base_url().'assets/banners/'.$cat_pic;
        }else {
           $cat_pic_url = '';
        }

        $banData[]  = array(
            "id" => $rows->id,
            "banner_img" => $ban_pic_url
        );
      }
          $response = array("status" => "success", "msg" => "View banner list","banners"=>$banData);

    }else{
            $response = array("status" => "error", "msg" => "banner not found");
    }

    return $response;
  }





//-------------------- Main Category -------------------//
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
//-------------------- Main Category End -------------------//

//-------------------- Sub Category -------------------//
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
//-------------------- Sub Category End -------------------//

//-------------------- Search Service  -------------------//

    function search_service($service_txt,$service_txt_ta,$user_master_id){
      // $query = "SELECT * from services WHERE main_cat_id = '$main_cat_id' AND sub_cat_id = '$sub_cat_id' AND status = 'Active'";

      if($service_txt_ta==''){
         $query="SELECT *  FROM services WHERE service_name LIKE '%$service_txt%' and status='Active'";
      }else{
         $query="SELECT *  FROM services WHERE service_ta_name LIKE '%$service_txt_ta%' and status='Active'";
      }

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
              "service_pic_url" => $service_pic_url,
          );
        }
            $response = array("status" => "success", "msg" => "View Services","services"=>$subcatData);

      }else{
              $response = array("status" => "error", "msg" => "Services not found");
      }

      return $response;
    }
//-------------------- Search Service  -------------------//

//-------------------- Services List -------------------//
	 function Services_list($main_cat_id,$sub_cat_id,$user_master_id)
	{
			// $query = "SELECT * from services WHERE main_cat_id = '$main_cat_id' AND sub_cat_id = '$sub_cat_id' AND status = 'Active'";
      $query="SELECT  IFNULL(oc.user_master_id,0) AS selected,s.* FROM services  as s  left join order_cart as oc on oc.service_id=s.id  and oc.user_master_id='$user_master_id' where s.main_cat_id='$main_cat_id' and s.sub_cat_id='$sub_cat_id' AND s.status = 'Active' GROUP by s.id";
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
							"service_pic_url" => $service_pic_url,
              "selected" => $rows->selected,

					);
				}
			     	$response = array("status" => "success", "msg" => "View Services","services"=>$subcatData);

			}else{
			        $response = array("status" => "error", "msg" => "Services not found");
			}

			return $response;
	}
//-------------------- Services List End -------------------//
//-------------------- Services Details -------------------//

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

//-------------------- Services Details  -------------------//
//-------------------- Add Services Cart  -------------------//


    function add_service_to_cart($user_master_id,$category_id,$sub_category_id,$service_id){
      $check_service="SELECT * FROM order_cart WHERE service_id='$service_id' AND user_master_id='$user_master_id'";
      $check_res= $this->db->query($check_service);
      if($check_res->num_rows()==0){
        $insert="INSERT INTO order_cart(user_master_id,category_id,sub_category_id,service_id,status,created_by,created_at) VALUES('$user_master_id','$category_id','$sub_category_id','$service_id','Pending','$user_master_id',NOW())";
        $insert_result = $this->db->query($insert);
        if($insert_result){
          $get_total_count="SELECT count(*) as service_count,sum(s.rate_card) as total_amt FROM order_cart as oc left join  services as s on s.id=oc.service_id WHERE oc.user_master_id='$user_master_id'";
            $cnt_query = $this->db->query($get_total_count);
            $result=$cnt_query->result();
            foreach($result as $rows){}
              $cart_count=array(
                "service_count" => $rows->service_count,
                "total_amt" => $rows->total_amt,
              );


          $response = array("status" => "success", "msg" => "Service added to cart","cart_total"=>$cart_count);
        }else{
          $response = array("status" => "error", "msg" => "Something went wrong");
        }
      }else{
        $response = array("status" => "error", "msg" => "Service Already in cart");
      }

        return $response;
    }
//-------------------- Add Services Cart  -------------------//


//-------------------- Remove Services Cart  -------------------//


    function remove_service_to_cart($cart_id){
      $query="DELETE  FROM order_cart WHERE id='$cart_id'";
      $query_result = $this->db->query($query);
      if($query_result){
        $response = array("status" => "success", "msg" => "Service removed from cart");
      }else{
        $response = array("status" => "error", "msg" => "Something went wrong");
      }
        return $response;
    }
//-------------------- Remove Services Cart  -------------------//


//-------------------- Clear all Services Cart  -------------------//

  function clear_cart($user_master_id){
    $query="DELETE  FROM order_cart WHERE user_master_id='$user_master_id'";
    $query_result = $this->db->query($query);
    if($query_result){
      $response = array("status" => "success", "msg" => "All Service removed from cart");
    }else{
      $response = array("status" => "error", "msg" => "Something went wrong");
    }
      return $response;
  }

  //--------------------  Clear all Services Cart  -------------------//

//-------------------- Cart list -------------------//


  function view_cart_summary($user_master_id){
    $query="SELECT oc.id as cart_id,s.service_name,s.service_ta_name,s.service_pic,oc.status,oc.user_master_id,s.rate_card,s.is_advance_payment,s.advance_amount FROM order_cart as oc left join main_category as mc on oc.category_id=mc.id left join sub_category as sc on oc.sub_category_id=sc.id left join services as s on oc.service_id=s.id where oc.user_master_id='$user_master_id' and oc.status='Pending' order by s.rate_card desc";
    $res = $this->db->query($query);
    if($res->num_rows()==0){
      $response = array("status" => "error", "msg" => "Cart is Empty");
    }else{
      $result=$res->result();
      foreach($result as $rows){
        $service_pic = $rows->service_pic;
        if ($service_pic != ''){
          $service_pic_url = base_url().'assets/category/'.$service_pic;
        }else {
           $service_pic_url = '';
        }
        $cart_list[]=array(
          "cart_id" => $rows->cart_id,
          "service_name" => $rows->service_name,
          "service_ta_name" => $rows->service_ta_name,
          "service_picture" => $service_pic_url,
          "rate_card" => $rows->rate_card,
          "is_advance_payment" => $rows->is_advance_payment,
          "advance_amount" => $rows->advance_amount,
          "status" => $rows->status,
        );
      }
        $response = array("status" => "success", "msg" => "Cart list found","cart_list"=>$cart_list);

    }
      return $response;

  }

  //-------------------- Cart list -------------------//

//-------------------- Time slot -------------------//

  function view_time_slot($user_master_id,$service_date){
    // $query = "SELECT * from service_timeslot WHERE status = 'Active'";
      $cur_date=date("d-M-Y");
      $serv_date = date("d-M-Y", strtotime($service_date));
      if ($serv_date != $cur_date){
        $query="SELECT id,DATE_FORMAT(from_time, '%h:%i %p') as from_time,DATE_FORMAT(to_time, '%h:%i %p') as to_time  FROM service_timeslot  WHERE  status='Active'";
      }else{
        $query="SELECT id,DATE_FORMAT(from_time, '%h:%i %p') as from_time,DATE_FORMAT(to_time, '%h:%i %p') as to_time  FROM service_timeslot  WHERE from_time >= (NOW() + INTERVAL 1 HOUR) and status='Active'";
      }
    $res = $this->db->query($query);
     if($res->num_rows()>0){
       $order_list = $res->result();
       foreach ($order_list as $rows) {
         $time_slot=$rows->from_time.'-'.$rows->to_time;
         $view_time_slot[]= array(
           'timeslot_id' => $rows->id,
           'time_range' =>$time_slot
         );
       }
      $response = array("status" => "success", "msg" => "View Timeslot","service_time_slot"=>$view_time_slot);
     } else {
       $response = array("status" => "error", "msg" => "Service order list not found");
     }

    return $response;
  }


  //-------------------- Time slot -------------------//



//-------------------- Before booking -------------------//



  function proceed_to_book_order($user_master_id,$contact_person_name,$contact_person_number,$service_latlon,$service_location,$service_address,$order_date,$order_timeslot){
    $serv_date = date("Y-m-d", strtotime($order_date));
    $check_cart="SELECT oc.category_id,oc.sub_category_id,oc.service_id,s.rate_card,s.advance_amount FROM order_cart as oc left join services as s on oc.service_id=s.id
    WHERE oc.user_master_id='$user_master_id' AND oc.status='Pending' order by s.advance_amount desc";
    $res = $this->db->query($check_cart);
    $result_no=$res->num_rows();

    // Single Service Select

    if($result_no==1){
      $result=$res->result();
      foreach($result as $rows){}
        $f_cat_id=$rows->category_id;
        $f_sub_cat_id=$rows->sub_category_id;
        $f_serv_id=$rows->service_id;
        $f_rate_card=$rows->rate_card;
        $last_ser_id= $rows->service_id;
        $ser_rate_card=$rows->rate_card;
        $advance_amount=$rows->advance_amount;
        if($advance_amount=='0.00'){
        $adva_status='NA';
        }else{
          $adva_status='N';
        }

        $insert_service="INSERT INTO service_orders(customer_id,contact_person_name,contact_person_number,main_cat_id,sub_cat_id,service_id,order_date,order_timeslot,service_latlon,service_location,service_address,advance_amount_paid,advance_payment_status,service_rate_card,status,created_at,created_by) VALUES('$user_master_id','$contact_person_name','$contact_person_number','$f_cat_id','$f_sub_cat_id','$last_ser_id','$serv_date','$order_timeslot','$service_latlon','$service_location','$service_address','$advance_amount','$adva_status','$ser_rate_card','Pending',NOW(),'$user_master_id')";
      $res_service = $this->db->query($insert_service);
         $last_id=$this->db->insert_id();
         if($res_service){
            $tim=time();
            $order_id=$tim.'-'.$user_master_id.'-'.$last_id;
           $service_details=array(
             "order_id"=>$order_id,
             "advance_amount"=>$advance_amount,
             "advance_payment_status"=>$adva_status,
           );

           $delete_cart="DELETE FROM order_cart WHERE user_master_id='$user_master_id' AND status='Pending'";
           $res_delete = $this->db->query($delete_cart);

             $response = array("status" => "success", "msg" => "Service done","service_details"=>$service_details);
         }else{
             $response = array("status" => "error", "msg" => "Something Went wrong");
         }
         return $response;

         // Multiple Service select

    }else if($result_no>1){
      $result=$res->result();
      foreach($result as $rows){
        $f_cat_id=$rows->category_id;
        $f_sub_cat_id=$rows->sub_category_id;
        $f_serv_id[]=$rows->service_id;
        $f_rate_card[]=$rows->rate_card;
        if ($rows === reset($result)) {
           $last_ser_id= $rows->service_id;
           $ser_rate_card=$rows->rate_card;
           $advance_amount=$rows->advance_amount;
          if($advance_amount=='0.00'){
            $adva_status='NA';
          }else{
              $adva_status='N';
          }

            $insert_service="INSERT INTO service_orders(customer_id,contact_person_name,contact_person_number,main_cat_id,sub_cat_id,service_id,order_date,order_timeslot,service_latlon,service_location,service_address,advance_amount_paid,advance_payment_status,service_rate_card,status,created_at,created_by) VALUES('$user_master_id','$contact_person_name','$contact_person_number','$f_cat_id','$f_sub_cat_id','$last_ser_id','$serv_date','$order_timeslot','$service_latlon','$service_location','$service_address','$advance_amount','$adva_status','$ser_rate_card','Pending',NOW(),'$user_master_id')";
             $res_service = $this->db->query($insert_service);
             $last_id=$this->db->insert_id();

       }
      }

       $last_cnt=$result_no;
         $count=$result_no-1;
          for($i=1;$i<$last_cnt;$i++){
             $ad_ser= $f_serv_id[$i];
             $rate_cc=$f_rate_card[$i];
             $insert_add_service="INSERT INTO service_order_additional (service_order_id,service_id,ad_service_rate_card,status) VALUES('$last_id','$ad_ser','$rate_cc','Pending')";
            $res_add_service = $this->db->query($insert_add_service);

          }
          if($res_add_service){
            $tim=time();
            $order_id=$tim.'-'.$user_master_id.'-'.$last_id;
            $service_details=array(
              "order_id"=>$order_id,
              "advance_amount"=>$advance_amount,
              "advance_payment_status"=>$adva_status,
            );


            $delete_cart="DELETE FROM order_cart WHERE user_master_id='$user_master_id' AND status='Pending'";
            $res_delete = $this->db->query($delete_cart);
              $response = array("status" => "success", "msg" => "Service done","service_details"=>$service_details);
          }else{
              $response = array("status" => "error", "msg" => "Something Went wrong");
          }
          return $response;


    }else{

      // No service Found

      $response = array("status" => "error", "msg" => "Something Went wrong");
      return $response;
    }


  }


  //-------------------- Service Advance  payment-------------------//


    function service_advance_payment($user_master_id,$service_id){
      $select="SELECT * from service_orders WHERE id='$service_id' AND customer_id='$user_master_id'";
      $res = $this->db->query($select);
      if($res->num_rows()==1){
              $result=$res->result();
              foreach($result as $rows){}
            $advance_amt=$rows->advance_amount_paid;
            $update="UPDATE service_orders SET advance_payment_status='Y' WHERE id='$service_id'";
            $res_update = $this->db->query($update);

            $check_service_payment="SELECT * FROM service_payments WHERE service_order_id='$service_id'";
            $res_sp=$this->db->query($check_service_payment);
            if($res_sp->num_rows()==0){

              $insert_sp="INSERT INTO service_payments (service_order_id,paid_advance_amount,status) VALUES ('$service_id','$advance_amt','Pending')";
              $res_sph=$this->db->query($insert_sp);
              $last_id=$this->db->insert_id();

              // INSERT into service payment history
              $insert_sph="INSERT INTO service_payment_history (service_order_id,service_payment_id,payment_type,payment_order_id,ccavenue_track_id,notes,status,created_at,created_by) VALUES ('$service_id','$last_id','Online','123','123','Advance','Success',NOW(),'$user_master_id')";
              $res_sph=$this->db->query($insert_sph);
              if($res_sph){
                $response = array("status" => "success", "msg" => "Advance paid Successfully");
              }else{
                $response = array("status" => "error", "msg" => "Service not found");
              }
            }else{
              $result_sp=$res_sp->result();
              foreach($result_sp as $rows_sp){}
              $service_payment_id=$rows_sp->id;


              //Update in  service_payments
              $update_sp="UPDATE service_payments SET paid_advance_amount='$advance_amt' WHERE service_order_id='$service_id'";
              $res_sp=$this->db->query($update_sp);


              // INSERT into service payment history
              $insert_sph="INSERT INTO service_payment_history (service_order_id,service_payment_id,payment_type,payment_order_id,ccavenue_track_id,notes,status,created_at,created_by) VALUES ('$service_id','$service_payment_id','Online','123','123','Advance','Success',NOW(),'$user_master_id')";
              $res_sph=$this->db->query($insert_sph);
              if($res_sph){
                $response = array("status" => "success", "msg" => "Advance paid Successfully");
              }else{
                $response = array("status" => "error", "msg" => "Service not found");
              }
            }
      }else{
           $response = array("status" => "error", "msg" => "Service not found");
      }
       return $response;


    }


//-------------------- Service Advance  payment-------------------//


//-------------------- Service Provider allocation -------------------//


    function service_provider_allocation($user_master_id,$service_id,$display_minute){
      $query="SELECT * FROM service_orders WHERE id='$service_id' AND customer_id='$user_master_id' AND status='Pending'";
      $result = $this->db->query($query);
      if($result->num_rows()==1){
          $res=$result->result();
          foreach($res as $rows){}
          $advance_check=$rows->advance_payment_status;
          $selected_service_id=$rows->service_id;
          $selected_main_cat_id=$rows->main_cat_id;
          $service_latlon=$rows->service_latlon;
          $result = explode(",", $service_latlon);
          $lat=$result[0];
          $long= $result[1];

          if($advance_check=='N'){
              $response = array("status" => "error", "msg" => "Service Advance not Paid");
          }else{

            $get_last_service_provider_id="SELECT * FROM service_orders where serv_prov_id!=0 ORDER BY id,serv_prov_id LIMIT 1";
            $result_last_sp_id=$this->db->query($get_last_service_provider_id);
            $res_sp_id=$result_last_sp_id->result();
            foreach($res_sp_id as $rows_last_sp_id){}
            $last_sp_id=$rows_last_sp_id->serv_prov_id;
            $next_id=$last_sp_id+$display_minute;

            $get_sp_id="SELECT lu.phone_no,spps.user_master_id,vs.id, ( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
            COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
            SIN( RADIANS( serv_lat ) ) ) ) AS distance,vs.status FROM serv_prov_pers_skills AS spps
            LEFT JOIN login_users AS lu ON lu.id=spps.user_master_id AND lu.user_type=3
            LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
            WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND FIND_IN_SET(spps.user_master_id , '$next_id') HAVING
            distance < 25 ORDER BY distance LIMIT 0 , 50";
            $ex_next_id=$this->db->query($get_sp_id);
            if($ex_next_id->num_rows()==0){
              $response = array("status" => "error", "msg" => "Hitback");
            }else{
              $res_next_ip=$ex_next_id->result();

              print_r($res_next_ip);
              $response = array("status" => "success", "msg" => "Service Provider found");
            }

        }
           return $response;
      }else{
        $response = array("status" => "error", "msg" => "Service not found");
      }
       return $response;

    }


//-------------------- Service Provider allocation -------------------//





//-------------------- Service Reviews Add -------------------//
	 function Service_reviewsadd($user_master_id,$service_order_id,$ratings,$reviews)
	{
		$insert_sql = "INSERT INTO service_reviews (service_order_id, customer_id, rating, review, status,created_at,created_by) VALUES
					('". $service_order_id . "','". $user_master_id . "','". $ratings . "', '". $reviews . "','Pending', now(),'". $user_master_id . "')";
		$insert_result = $this->db->query($insert_sql);
		$response = array("status" => "success", "msg" => "Review Added");
		return $response;
	}
//-------------------- Service Reviews Add End -------------------//


//-------------------- Service Reviews Add -------------------//
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
//-------------------- Service Reviews Add End -------------------//


}

?>
