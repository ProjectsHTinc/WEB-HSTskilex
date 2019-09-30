<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomermodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('smsmodel');
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

	 function sendNotification($gcm_key,$title,$Message,$mobiletype)
	{

		if ($mobiletype =='1'){

		    require_once 'assets/notification/Firebase.php';
            require_once 'assets/notification/Push.php';

            $device_token = explode(",", $gcm_key);
            $push = null;

        //first check if the push has an image with it
		    $push = new Push(
					$title,
					$Message,
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
		    $loction ='assets/pushcert.pem';

			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
			stream_context_set_option($ctx, 'ssl', 'hs123', $passphrase);

			// Open a connection to the APNS server
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => array(
					'body' => $Message,
					'action-loc-key' => 'Skilex',
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

    function get_all_tax_commission(){
      $select="SELECT * FROM tax_commission WHERE id='1'";
      $result = $this->db->query($select);
  		$res = $result->result();
      foreach($res as $rows){
        $sgst=$rows->sgst;
        $cgst=$rows->cgst;
        $internal_commission=$rows->internal_commission;
        $external_commission=$rows->external_commission;

      }
    }



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
    $message_details = "Your SkilEx Verification code is: ".$OTP." \n\n\n 0q8GrbcslWk";
		$this->sendSMS($phone_no,$message_details);
		$response = array("status" => "success", "msg" => "Mobile OTP","msg_en"=>"","msg_ta"=>"","user_master_id"=>$user_master_id, "phone_no"=>$phone_no, "otp"=>$OTP);
		return $response;
	}

//-------------------- Mobile Check End -------------------//

  //-------------------- guest login -------------------//


  function guest_login($unique_number,$device_token,$mobiletype,$user_stat){
    $query="INSERT INTO notification_master (user_master_id,mobile_key,mobile_type,user_stat,created_at) VALUES('$unique_number','$device_token','$mobiletype','$user_stat',NOW())";
    $res_query = $this->db->query($query);
    if($res_query){
      	$response = array("status" => "success", "msg" => "Success","msg_en"=>"","msg_ta"=>"");
    }else{
      	$response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
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

			$response = array("status" => "success", "msg" => "Login Successfully","msg_en"=>"","msg_ta"=>"","userData" => $userData);
			return $response;
		} else {
			$response = array("status" => "error", "msg" => "Invalid login","msg_en"=>"","msg_ta"=>"");
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
		$response = array("status" => "success", "msg" => "Email Verify Status", "user_master_id"=>$user_master_id, "email_verify_satus"=>$email_verify,"msg_en"=>"","msg_ta"=>"");
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


		$response = array("status" => "success", "msg" => "Email Verification Sent","msg_en"=>"","msg_ta"=>"");
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

		$update_sql = "UPDATE customer_details SET full_name ='$full_name', gender ='$gender' WHERE user_master_id ='$user_master_id'";
		$update_result = $this->db->query($update_sql);

		$response = array("status" => "success", "msg" => "Profile Updated","msg_en"=>"","msg_ta"=>"");
		return $response;
	}

//-------------------- Profile Update End -------------------//

//-------------------- Profile Pic Update -------------------//
	 function Profile_pic_upload($user_master_id,$profileFileName)
	{
            $update_sql= "UPDATE customer_details SET profile_pic='$profileFileName' WHERE user_master_id='$user_master_id'";
			$update_result = $this->db->query($update_sql);
			$picture_url = base_url().'assets/customers/'.$profileFileName;

			$response = array("status" => "success", "msg" => "Profile Picture Updated","picture_url" =>$picture_url,"msg_en"=>"","msg_ta"=>"");
			return $response;
	}
//-------------------- Profile Pic Update End -------------------//



  function user_info($user_master_id){
    $select="SELECT * FROM login_users as lu LEFT JOIN customer_details as cd ON lu.id=cd.user_master_id WHERE lu.id='$user_master_id'";
    $res = $this->db->query($select);
    if($res->num_rows()==1){
      foreach($res->result()  as $rows){}
        $profile=$rows->profile_pic;
        if(empty($profile)){
          $pic="";
        }else{
            $pic=base_url().'assets/customers/'.$profile;
        }
        $user_info=array(
          "phone_no"=>$rows->phone_no,
          "email"=>$rows->email,
          "full_name"=>$rows->full_name,
          "gender"=>$rows->gender,
          "profile_pic"=>$pic,
        );
        $response=array("status"=>"success","msg"=>"User information","user_details"=>$user_info,"msg_en"=>"","msg_ta"=>"");

    }else{
        $response=array("status"=>"error","msg"=>"No User information found","msg_en"=>"User details not found!","msg_ta"=>"பயனர் விபரங்கள் கிடைக்கவில்லை!");
    }
    return $response;
  }


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
          $response = array("status" => "success", "msg" => "View banner list","banners"=>$banData,"msg_en"=>"","msg_ta"=>"");

    }else{
            $response = array("status" => "error", "msg" => "banner not found","msg_en"=>"","msg_ta"=>"");
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
			     	$response = array("status" => "success", "msg" => "View Category","categories"=>$catData,"msg_en"=>"","msg_ta"=>"");

			}else{
			        $response = array("status" => "error", "msg" => "Category not found","msg_en"=>"Categories not found!","msg_ta"=>"பிரிவுகள் கிடைக்கவில்லை!");
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
			     	$response = array("status" => "success", "msg" => "View Sub Category","sub_categories"=>$subcatData,"msg_en"=>"","msg_ta"=>"");

			}else{
			        $response = array("status" => "error", "msg" => "Sub Category not found","msg_en"=>"Sub-categories not found!","msg_ta"=>"துணைப்பிரிவுகள் கிடைக்கவில்லை!");
			}

			return $response;
	}
//-------------------- Sub Category End -------------------//

//-------------------- Search Service  -------------------//

    function search_service($service_txt,$service_txt_ta,$user_master_id){
       $query="SELECT *  FROM services WHERE service_name LIKE '%$service_txt%' or service_ta_name LIKE '%$service_txt%' and status='Active'";
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
            $response = array("status" => "success", "msg" => "View Services","services"=>$subcatData,"msg_en"=>"","msg_ta"=>"");

      }else{
              $response = array("status" => "error", "msg" => "Services not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
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
			     	$response = array("status" => "success", "msg" => "View Services","services"=>$subcatData,"msg_en"=>"","msg_ta"=>"");

			}else{
			        $response = array("status" => "error", "msg" => "Services not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
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

            $response = array("status" => "success", "msg" => "Service Details","service_details"=>$subcatData,"msg_en"=>"","msg_ta"=>"");

      }else{
              $response = array("status" => "error", "msg" => "Services not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
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


          $response = array("status" => "success", "msg" => "Service added to cart","cart_total"=>$cart_count,"msg_en"=>"","msg_ta"=>"");
        }else{
          $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
        }
      }else{
        $response = array("status" => "error", "msg" => "Service Already in cart","msg_en"=>"Already added to cart","msg_ta"=>"கார்ட்டில் சேர்க்கப்பட்டுவிட்டது!");
      }

        return $response;
    }
//-------------------- Add Services Cart  -------------------//


//-------------------- Remove Services Cart  -------------------//


    function remove_service_to_cart($cart_id){
      $query="DELETE  FROM order_cart WHERE id='$cart_id'";
      $query_result = $this->db->query($query);
      if($query_result){
        $response = array("status" => "success", "msg" => "Service removed from cart","msg_en"=>"Service removed from cart","msg_ta"=>"சேவை கார்ட்டிலிருந்து நீக்கப்பட்டது ");
      }else{
        $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
      }
        return $response;
    }
//-------------------- Remove Services Cart  -------------------//


//-------------------- Clear all Services Cart  -------------------//

  function clear_cart($user_master_id){
    $query="DELETE  FROM order_cart WHERE user_master_id='$user_master_id'";
    $query_result = $this->db->query($query);
    if($query_result){
      $response = array("status" => "success", "msg" => "All Service removed from cart","msg_en"=>"","msg_ta"=>"");
    }else{
      $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
    }
      return $response;
  }

  //--------------------  Clear all Services Cart  -------------------//

//-------------------- Cart list -------------------//


  function view_cart_summary($user_master_id){
    $query="SELECT oc.id as cart_id,s.service_name,s.service_ta_name,s.service_pic,oc.status,oc.user_master_id,s.rate_card,s.is_advance_payment,s.advance_amount FROM order_cart as oc left join main_category as mc on oc.category_id=mc.id left join sub_category as sc on oc.sub_category_id=sc.id left join services as s on oc.service_id=s.id where oc.user_master_id='$user_master_id' and oc.status='Pending' order by s.advance_amount desc";
    $res = $this->db->query($query);
    if($res->num_rows()==0){
      $response = array("status" => "error", "msg" => "Cart is Empty","msg_en"=>"","msg_ta"=>"");
    }else{

      $total="SELECT sum(s.rate_card)  as grand_total FROM order_cart as oc left join main_category as mc on oc.category_id=mc.id left join sub_category as sc on oc.sub_category_id=sc.id left join services as s on oc.service_id=s.id where oc.user_master_id='$user_master_id' and oc.status='Pending' order by s.advance_amount desc";
      $res_total = $this->db->query($total);
      $result_total=$res_total->result();
      foreach($result_total as $rows_total){}
      $grand_total=$rows_total->grand_total;
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
        $response = array("status" => "success", "msg" => "Cart list found","cart_list"=>$cart_list,"grand_total"=>$grand_total,"msg_en"=>"","msg_ta"=>"");

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
      $response = array("status" => "success", "msg" => "View Timeslot","service_time_slot"=>$view_time_slot,"msg_en"=>"","msg_ta"=>"");
     } else {
       $response = array("status" => "error", "msg" => "Service timeslot not found","msg_en"=>"Service time not found!","msg_ta"=>"சேவை நேரம் கிடைக்கவில்லை!");
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

             $response = array("status" => "success", "msg" => "Service done","service_details"=>$service_details,"msg_en"=>"","msg_ta"=>"");
         }else{
           $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
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
              $response = array("status" => "success", "msg" => "Service done","service_details"=>$service_details,"msg_en"=>"","msg_ta"=>"");
          }else{
            $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
          }
          return $response;


    }else{

      // No service Found

      $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
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
                $response = array("status" => "success", "msg" => "Advance paid Successfully","msg_en"=>"","msg_ta"=>"");
              }else{
                $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
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
                $response = array("status" => "success", "msg" => "Advance paid Successfully","msg_en"=>"","msg_ta"=>"");
              }else{
                $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
              }
            }
      }else{
           $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }
       return $response;


    }


//-------------------- Service Advance  payment-------------------//

//-------------------- Service Order status-------------------//


  function service_order_status($user_master_id,$service_order_id){
      $query="SELECT * FROM service_orders as so WHERE id='$service_order_id' AND customer_id='$user_master_id'";
      $res=$this->db->query($query);
      if($res->num_rows()==1){
        foreach($res->result() as $rows){}
          $order_status=$rows->status;
          $response = array("status" => "success", "msg" => "Service status","order_status"=>$order_status,"msg_en"=>"","msg_ta"=>"");
      }else{
        $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");

      }
      return $response;

  }

//-------------------- Service Order status-------------------//



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
          $contact_person_name=$rows->contact_person_name;
          $contact_person_number=$rows->contact_person_number;
          $result = explode(",", $service_latlon);
          $lat=$result[0];
          $long= $result[1];

          if($advance_check=='N'){
              $response = array("status" => "error", "msg" => "Service Advance not Paid","msg_en"=>"","msg_ta"=>"");
          }else{

            // $get_last_service_provider_id="SELECT spd.id as last_id,so.* FROM service_orders as so left join service_provider_details as spd on spd.id=so.serv_prov_id where so.serv_prov_id!=0 and spd.serv_prov_verify_status='Approved' ORDER BY so.id,so.serv_prov_id LIMIT 1";
              $get_last_service_provider_id="SELECT spd.id as last_id,so.* FROM service_orders as so left join service_provider_details as spd on spd.id=so.serv_prov_id where so.serv_prov_id!=0  and (so.status='Paid' OR so.status='Completed') ORDER BY so.id desc LIMIT 1";



            $result_last_sp_id=$this->db->query($get_last_service_provider_id);
            $res_sp_id=$result_last_sp_id->result();
            if(empty($res_sp_id)){

               $first_id="SELECT ns.mobile_key,ns.mobile_type,spps.user_master_id,spd.owner_full_name,lu.phone_no FROM serv_prov_pers_skills as spps
              left join service_provider_details as spd on spd.user_master_id=spps.user_master_id
              left join login_users as lu on lu.id=spd.user_master_id
              left join vendor_status as vs on vs.serv_pro_id=lu.id
              LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
              WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' and lu.status='Active'
              GROUP by spps.user_master_id order by spps.id asc LIMIT 1";

              $ex_next_id=$this->db->query($first_id);
              $res_next_ip=$ex_next_id->result();
              foreach($res_next_ip as $rows_id_next){}
               $Phoneno=$rows_id_next->phone_no;

              $full_name=$rows_id_next->owner_full_name;
              $sp_user_master_id=$rows_id_next->user_master_id;

             $check_order_history="SELECT * FROM service_order_history WHERE service_order_id='$service_id' and serv_prov_id='$sp_user_master_id'";
              $res_order_history=$this->db->query($check_order_history);

              if($res_order_history->num_rows()==0){
                $title="Order";
                $gcm_key=$rows_id_next->mobile_key;
                $mobiletype=$rows_id_next->mobile_type;
                $Message="Hi $full_name You Received order from Customer $contact_person_name: $contact_person_number";
                //$this->smsmodel->send_sms($phone,$notes);
                $this->sendSMS($Phoneno,$Message);
                ///$this->sendNotification($gcm_key,$title,$Message,$mobiletype);
                $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Pending' AND service_order_id='$service_id'";
                $res_expried=$this->db->query($update_exper);

                $request_insert_query="INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('$service_id','$sp_user_master_id','Requested',NOW(),'$user_master_id')";
                $res_quest=$this->db->query($request_insert_query);
                if($res_quest){
                  $response = array("status" => "success", "msg" => "Waiting for Service Provider to Accept","msg_en"=>"","msg_ta"=>"");
                }else{
                  $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
                }
              }else{
                  $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
              }




            }else{
              foreach($res_sp_id as $rows_last_sp_id){}
              $last_sp_id=$rows_last_sp_id->last_id;
             $next_id=$display_minute+$last_sp_id;
             if($display_minute==1){
               $limit=1;
             }else if($display_minute==2){
               $limit="1,1";
             }else if($display_minute==3){
               $limit="2,1";
             }else{
               $limit=0;
             }

              // $get_sp_id="SELECT ns.mobile_key,ns.mobile_type,spd.owner_full_name,lu.phone_no,spps.user_master_id,vs.id, ( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
              // COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
              // SIN( RADIANS( serv_lat ) ) ) ) AS distance,vs.status FROM serv_prov_pers_skills AS spps
              // LEFT JOIN login_users AS lu ON lu.id=spps.user_master_id AND lu.user_type=3
              // LEFT JOIN service_provider_details AS spd ON spd.user_master_id=lu.id
              // LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
              // LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
              // WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND FIND_IN_SET(spps.user_master_id , '$next_id') GROUP BY spps.user_master_id HAVING
              // distance < 50 ORDER BY distance LIMIT 0 , 50";


             $get_sp_id="SELECT spd.id,ns.mobile_key,ns.mobile_type,spps.user_master_id,spd.owner_full_name,lu.phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
              COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
              SIN( RADIANS( serv_lat ) ) ) ) AS distance,vs.status FROM serv_prov_pers_skills as spps
              left join service_provider_details as spd on spd.user_master_id=spps.user_master_id
              left join login_users as lu on lu.id=spd.user_master_id
              left join vendor_status as vs on vs.serv_pro_id=lu.id
              LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
              WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' and lu.status='Active'
              and spd.user_master_id!='$last_sp_id' and spd.id BETWEEN $next_id and $next_id
              GROUP by spps.user_master_id order by spd.id asc";


              $ex_next_id=$this->db->query($get_sp_id);
              if($ex_next_id->num_rows()==0){
                $response = array("status" => "error", "msg" => "Hitback","msg_en"=>"","msg_ta"=>"");
              }else{
                $res_next_ip=$ex_next_id->result();
                foreach($res_next_ip as $rows_id_next){}
                $Phoneno=$rows_id_next->phone_no;
                $full_name=$rows_id_next->owner_full_name;
                $sp_user_master_id=$rows_id_next->user_master_id;
                $title="Order";
                $gcm_key=$rows_id_next->mobile_key;
                $mobiletype=$rows_id_next->mobile_type;
                $Message="Hi $full_name You Received order from Customer $contact_person_name: $contact_person_number";
                //$this->smsmodel->send_sms($phone,$notes);
                $this->sendSMS($Phoneno,$Message);
                ///$this->sendNotification($gcm_key,$title,$Message,$mobiletype);
                $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Requested' AND service_order_id='$service_id'";
                $res_expried=$this->db->query($update_exper);

                $request_insert_query="INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('$service_id','$sp_user_master_id','Requested',NOW(),'$user_master_id')";
                $res_quest=$this->db->query($request_insert_query);
                if($res_quest){
                  $response = array("status" => "success", "msg" => "Waiting for Service Provider to Accept","msg_en"=>"","msg_ta"=>"");
                }else{
                  $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
                }

              }

            }
        }
           return $response;
      }else{
        $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }
       return $response;

    }


//-------------------- Service Provider allocation -------------------//


//-------------------- Service Pending and offers lists -------------------//


    function service_pending_and_offers_list($user_master_id){
      $query_offer="SELECT * FROM offer_master WHERE status='Active' ORDER BY id DESC";
      $res_offer = $this->db->query($query_offer);
      if($res_offer->num_rows()==0){
        	$response_offer = array("status" => "error", "msg" => "No Offers found","msg_en"=>"","msg_ta"=>"");
      }else{
        $offer_result = $res_offer->result();
        foreach($offer_result as $rows_offers){
          $offer_list[]=array(
            "id"=>$rows_offers->id,
            "offer_title"=>$rows_offers->offer_title,
            "offer_code"=>$rows_offers->offer_code,
            "offer_percent"=>$rows_offers->offer_percent,
            "max_offer_amount"=>$rows_offers->max_offer_amount,
            "offer_description"=>$rows_offers->offer_description,

          );

        }
      }


      $response=array("status"=>"success","msg"=>"Service and offer list","offer_response"=>$offer_list,"msg_en"=>"","msg_ta"=>"");


      return $response;

    }

//-------------------- Service Pending and offers lists -------------------//

//-------------------- Requested Service  -------------------//


    function requested_services($user_master_id){
      $service_query="SELECT so.status as order_status,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.* FROM service_orders  AS so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        WHERE so.status='Pending' AND customer_id='$user_master_id'
        ORDER BY so.id DESC";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
          $service_list[]=array(
            "service_order_id"=>$rows_service->id,
            "main_category"=>$rows_service->main_cat_name,
            "main_category_ta"=>$rows_service->main_cat_ta_name,
            "sub_category"=>$rows_service->sub_cat_name,
            "sub_category_ta"=>$rows_service->sub_cat_ta_name,
            "service_name"=>$rows_service->service_name,
            "service_ta_name"=>$rows_service->service_ta_name,
            "contact_person_name"=>$rows_service->contact_person_name,
            "service_address"=>$rows_service->service_address,
            "order_date"=>$rows_service->order_date,
            "time_slot"=>$time_slot,
            "advance_payment_status"=>$rows_service->advance_payment_status,
            "advance_amount_paid"=>$rows_service->advance_amount_paid,
            "order_status"=>$rows_service->order_status,


          );
            $response = array("status" => "success", "msg" => "Service found",'service_list'=>$service_list,"msg_en"=>"","msg_ta"=>"");

        }
      }



      return $response;

    }

//-------------------- Requested Service   -------------------//


//-------------------- Service Ongoing -------------------//


    function ongoing_services($user_master_id){
      $service_query="SELECT so.status as order_status,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.* FROM service_orders  AS so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        WHERE so.status!='Pending' AND so.status!='Completed' AND so.status!='Rejected' AND so.status!='Paid' AND so.status!='Cancelled' AND customer_id='$user_master_id'
        ORDER BY so.id DESC";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
          $service_list[]=array(
            "service_order_id"=>$rows_service->id,
            "main_category"=>$rows_service->main_cat_name,
            "main_category_ta"=>$rows_service->main_cat_ta_name,
            "sub_category"=>$rows_service->sub_cat_name,
            "sub_category_ta"=>$rows_service->sub_cat_ta_name,
            "service_name"=>$rows_service->service_name,
            "service_ta_name"=>$rows_service->service_ta_name,
            "contact_person_name"=>$rows_service->contact_person_name,
            "service_address"=>$rows_service->service_address,
            "order_date"=>$rows_service->order_date,
            "time_slot"=>$time_slot,
            "order_status"=>$rows_service->order_status,
          );
            $response = array("status" => "success", "msg" => "Service found",'service_list'=>$service_list,"msg_en"=>"","msg_ta"=>"");

        }
      }



      return $response;

    }

//-------------------- Service Ongoing  -------------------//


//-------------------- Service History -------------------//


    function service_history($user_master_id){
      $service_query="SELECT ifnull(srv.rating,'0') as rating,ifnull(srv.review,'-') as review,so.status AS order_status,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,
      s.service_name,s.service_ta_name,st.from_time,st.to_time,so.*
      FROM service_orders  AS so
      LEFT JOIN services AS s ON s.id=so.service_id
      LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
      LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
      LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
      left join service_reviews as srv on srv.service_order_id=so.id
      WHERE  so.customer_id='$user_master_id' AND (so.status='Paid' OR so.status='Cancelled' OR so.status='Completed') ORDER BY so.id DESC";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
          $service_list[]=array(
            "service_order_id"=>$rows_service->id,
            "main_category"=>$rows_service->main_cat_name,
            "main_category_ta"=>$rows_service->main_cat_ta_name,
            "sub_category"=>$rows_service->sub_cat_name,
            "sub_category_ta"=>$rows_service->sub_cat_ta_name,
            "service_name"=>$rows_service->service_name,
            "service_ta_name"=>$rows_service->service_ta_name,
            "contact_person_name"=>$rows_service->contact_person_name,
            "service_address"=>$rows_service->service_address,
            "order_date"=>$rows_service->order_date,
            "time_slot"=>$time_slot,
            "rating"=>$rows_service->rating,
            "review"=>$rows_service->review,
            "order_status"=>$rows_service->order_status,
          );
            $response = array("status" => "success", "msg" => "Service found",'service_list'=>$service_list,"msg_en"=>"","msg_ta"=>"");

        }
      }



      return $response;

    }

//-------------------- Service History  -------------------//


//-------------------- Service Order details -------------------//


    function service_order_details($service_order_id){
      $service_query="SELECT so.status as order_status,IFNULL(so.serv_pers_id,'') as person_id,IFNULL(lu.phone_no,'') as phone_no,IFNULL(spp.profile_pic,'') as profile_pic,IFNULL(spp.full_name,'') AS full_name,IFNULL(spd.owner_full_name,'') AS
       owner_full_name,st.from_time,st.to_time,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,
(SELECT SUM( ad_service_rate_card) FROM service_order_additional AS soa WHERE service_order_id='$service_order_id' ) AS ad_serv_rate,so.* FROM service_orders  AS so
LEFT JOIN services AS s ON s.id=so.service_id LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN service_provider_details AS spd ON spd.user_master_id=so.serv_prov_id LEFT JOIN service_person_details AS spp ON spp.user_master_id=so.serv_pers_id LEFT JOIN login_users AS lu ON lu.id=so.serv_pers_id
 WHERE so.id='$service_order_id'";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"","msg_ta"=>"");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){  }
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
           $profic=$rows_service->profile_pic;
           if(empty($profic)){
             $pic="";
           }else{
            $pic= base_url().'assets/person/'.$profic;

           }
          $service_list=array(
            "service_order_id"=>$rows_service->id,
            "main_category"=>$rows_service->main_cat_name,
            "main_category_ta"=>$rows_service->main_cat_ta_name,
            "sub_category"=>$rows_service->sub_cat_name,
            "sub_category_ta"=>$rows_service->sub_cat_ta_name,
            "service_name"=>$rows_service->service_name,
            "service_ta_name"=>$rows_service->service_ta_name,
            "contact_person_name"=>$rows_service->contact_person_name,
            "contact_person_number"=>$rows_service->contact_person_number,
            "service_address"=>$rows_service->service_address,
            "order_date"=>$rows_service->order_date,
            "time_slot"=>$time_slot,
            "provider_name"=>$rows_service->owner_full_name,
            "person_name"=>$rows_service->full_name,
            "person_id"=>$rows_service->person_id,
            "person_number"=>$rows_service->phone_no,
            "pic"=>$pic,
            "estimated_cost"=>$rows_service->ad_serv_rate+$rows_service->service_rate_card,
            "order_status"=>$rows_service->order_status,

          );
            $response = array("status" => "success", "msg" => "Service found",'service_list'=>$service_list,"msg_en"=>"","msg_ta"=>"");


      }



      return $response;

    }

//-------------------- Service order details  -------------------//

//-------------------- Service order Summary details  -------------------//


    function service_order_summary($user_master_id,$service_order_id){
      // $service_query="SELECT IFNULL(lu.phone_no,'') as phone_no,IFNULL(spp.full_name,'') AS full_name,IFNULL(spd.owner_full_name,'') AS owner_full_name,st.from_time,st.to_time,s.service_name,s.service_ta_name,
      // (SELECT SUM( ad_service_rate_card) FROM service_order_additional AS soa WHERE service_order_id='$service_order_id' ) AS ad_serv_rate,
      // (SELECT count( service_order_id) FROM service_order_additional AS soa WHERE service_order_id='$service_order_id' ) AS count_add,IFNULL(spa.paid_advance_amount,'') as paid_advance_amount,IFNULL(spa.service_amount,' ') as service_amount,IFNULL(spa.ad_service_amount,'') as ad_service_amount,spa.sgst_amount,spa.cgst_amount,INULL(spa.total_amount,'') as total_amount,IFNULL(spa.coupon_id,'') as coupon_id,INULL(spa.discount_amt,'') as discount_amt,spa.status,spa.id as payment_id,so.* FROM service_orders  AS so
      // LEFT JOIN services AS s ON s.id=so.service_id
      // LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
      // LEFT JOIN service_provider_details AS spd ON spd.user_master_id=so.serv_prov_id
      // LEFT JOIN service_person_details AS spp ON spp.user_master_id=so.serv_pers_id
      // LEFT JOIN login_users AS lu ON lu.id=so.serv_pers_id
      // LEFT JOIN service_payments AS spa ON spa.service_order_id=so.id
      // WHERE so.id='$service_order_id' AND so.customer_id='$user_master_id'";

        $service_query="SELECT IFNULL(lu.phone_no,'') as phone_no,IFNULL(spp.full_name,'') AS full_name,IFNULL(spd.owner_full_name,'') AS owner_full_name,st.from_time,st.to_time,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,
        s.service_name,s.service_ta_name,IFNULL((SELECT SUM( ad_service_rate_card) FROM service_order_additional AS soa WHERE service_order_id='$service_order_id'),'') as ad_serv_rate,
        (SELECT count( service_order_id) FROM service_order_additional AS soa WHERE service_order_id='$service_order_id' ) AS count_add,IFNULL(spa.paid_advance_amount,'') as paid_advance_amount,IFNULL(spa.service_amount,' ') as service_amount,IFNULL(spa.ad_service_amount,'') as ad_service_amount,spa.sgst_amount,spa.cgst_amount,IFNULL(spa.total_service_amount,'') as total_service_amount,IFNULL(spa.net_service_amount,'') as net_service_amount,IFNULL(spa.payable_amount,'') as payable_amount,IFNULL(spa.coupon_id,'') as coupon_id,IFNULL(om.offer_code,'') as offer_code,IFNULL(om.offer_percent,'') as offer_percent,IFNULL(spa.discount_amt,'') as discount_amt,spa.status,spa.id as payment_id,so.* FROM service_orders  AS so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        LEFT JOIN service_provider_details AS spd ON spd.user_master_id=so.serv_prov_id
        LEFT JOIN service_person_details AS spp ON spp.user_master_id=so.serv_pers_id
        LEFT JOIN login_users AS lu ON lu.id=so.serv_pers_id
        LEFT JOIN service_payments AS spa ON spa.service_order_id=so.id
        LEFT JOIN offer_master AS om ON spa.coupon_id=om.id
        WHERE so.id='$service_order_id' AND so.customer_id='$user_master_id'";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found");
      }else{
        $service_result=$res_service->result();

        foreach($service_result as $rows_service){
          $payment_id=$rows_service->payment_id;
          $tim=time();
          $order_id=$tim.'-'.$user_master_id.'-'.$service_order_id.'-'.$payment_id;
          $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;

          $service_list=array(
            "service_order_id"=>$rows_service->id,
            "main_category"=>$rows_service->main_cat_name,
            "main_category_ta"=>$rows_service->main_cat_ta_name,
            "sub_category"=>$rows_service->sub_cat_name,
            "sub_category_ta"=>$rows_service->sub_cat_ta_name,
            "service_name"=>$rows_service->service_name,
            "service_ta_name"=>$rows_service->service_ta_name,
            "contact_person_name"=>$rows_service->contact_person_name,
            "contact_person_number"=>$rows_service->contact_person_number,
            "order_date"=>$rows_service->order_date,
            "time_slot"=>$time_slot,
            "provider_name"=>$rows_service->owner_full_name,
            "person_name"=>$rows_service->full_name,
            "person_number"=>$rows_service->phone_no,
            "service_start_time"=>$rows_service->start_datetime,
            "onhold_datetime"=>$rows_service->onhold_datetime,
            "service_end_time"=>$rows_service->finish_datetime,
            "additional_service"=>$rows_service->count_add,
            "material_notes"=>$rows_service->material_notes,
            "comments"=>$rows_service->comments,
            "paid_advance_amt"=>$rows_service->paid_advance_amount,
            "service_amount"=>$rows_service->service_amount,
            "additional_service_amt"=>$rows_service->ad_service_amount,
            "coupon_id"=>$rows_service->coupon_id,
            "coupon_code"=>$rows_service->offer_code,
            "offer_percent"=>$rows_service->offer_percent,
            "discount_amt"=>$rows_service->discount_amt,
            "total_service_cost"=>$rows_service->total_service_amount,
            "net_service_amount"=>$rows_service->net_service_amount,

          );
            $response = array("status" => "success", "msg" => "Service found",'service_list'=>$service_list,'order_id'=>$order_id,"msg_en"=>"","msg_ta"=>"");

        }
      }



           return $response;

    }


//----------------------Service order bills---------------//

  function service_order_bills($user_master_id,$service_order_id){

    $select="SELECT * FROM service_order_bills WHERE service_order_id='$service_order_id'";
       $res_offer = $this->db->query($select);
       if($res_offer->num_rows()==0){
           $response = array("status" => "error", "msg" => "No  Bills found","msg_en"=>"Bills unavailable!","msg_ta"=>"ரசிதுகள் கிடைக்கவில்லை!");
       }else{
         $offer_result = $res_offer->result();
         foreach($offer_result as $rows){}
           $file=$rows->file_name;
           if(empty($file)){
             $pic="";
           }else{
            $pic= base_url().'assets/bills/'.$file;

           }
           $service_bill[]=array(
             "id"=>$rows->id,
             "file_bill"=>$pic,
           );


          $response = array("status" => "success", "msg" => "Service Bill Found","service_bill"=>$service_bill,"msg_en"=>"","msg_ta"=>"");



       }
       return $response;

  }


  //----------------------Service order bills---------------//

//-------------------- Service order Summary details  -------------------//



    function list_reason_for_cancel($user_master_id){

      $select="SELECT * FROM cancel_master WHERE user_type=5";
         $res_offer = $this->db->query($select);
         if($res_offer->num_rows()==0){
             $response = array("status" => "error", "msg" => "No  Service found","msg_en"=>"","msg_ta"=>"");
         }else{
           $offer_result = $res_offer->result();
           foreach($offer_result as $rows){
             $cancel_list[]=array(
               "id"=>$rows->id,
               "cancel_reason"=>$rows->reasons,
             );
            }

            $response = array("status" => "success", "msg" => "Service Cancelled","reason_list"=>$cancel_list,"msg_en"=>"","msg_ta"=>"");



         }
         return $response;
    }


//-------------------- Cancel  Service order    -------------------//


        function cancel_service_order($user_master_id,$service_order_id,$cancel_id,$comments){
         $select="SELECT s.id,s.serv_prov_id,s.serv_pers_id,s.customer_id,s.status,lu.phone_no FROM  service_orders as s LEFT JOIN  login_users as lu on lu.id=s.customer_id WHERE s.id='$service_order_id' AND s.customer_id='$user_master_id'";
            $res_offer = $this->db->query($select);
            if($res_offer->num_rows()==0){
                $response = array("status" => "error", "msg" => "No  Service found","msg_en"=>"","msg_ta"=>"");
            }else{
              $offer_result = $res_offer->result();
              foreach($offer_result as $rows_service){ }
               $id=$rows_service->id;
              $Phoneno=$rows_service->phone_no;
              $Message="Thank you.Your order has been Cancelled";
              $this->sendSMS($Phoneno,$Message);
              $insert="INSERT INTO cancel_history (cancel_master_id,user_master_id,service_order_id,comments,status,created_at,created_by) VALUES ('$cancel_id','$user_master_id','$service_order_id','$comments','Cancelled',NOW(),'$user_master_id')";
              $res_insert = $this->db->query($insert);
              $update="UPDATE service_orders SET status='Cancelled',updated_at=NOW(),updated_by='$user_master_id' WHERE id='$id'";
              $res_update = $this->db->query($update);
              if($res_update){
                  $response = array("status" => "success", "msg" => "Service Cancelled successfully","msg_en"=>"","msg_ta"=>"");
              }else{
                $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
              }


            }
            return $response;


        }

//-------------------- Cancel  Service order    -------------------//

//-------------------- Addtional Service order  details  -------------------//


      function view_addtional_service($user_master_id,$service_order_id){
            $select="SELECT s.id,s.service_name,s.service_ta_name,s.rate_card,s.service_pic,s.rate_card_details,s.rate_card_details_ta FROM  service_order_additional AS soa LEFT JOIN services AS s ON soa.service_id=s.id WHERE service_order_id='$service_order_id'";
            $res_offer = $this->db->query($select);
            if($res_offer->num_rows()==0){
                $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"","msg_ta"=>"");
            }else{
              $offer_result = $res_offer->result();
              foreach($offer_result as $rows_service){
                $service_pic = $rows_service->service_pic;
                if ($service_pic != ''){
                  $service_pic_url = base_url().'assets/category/'.$service_pic;
                }else {
                   $service_pic_url = '';
                }
                $service_list[]=array(
                  "id"=>$rows_service->id,
                  "service_name"=>$rows_service->service_name,
                  "service_ta_name"=>$rows_service->service_ta_name,
                  "rate_card"=>$rows_service->rate_card,
                  "rate_card_details"=>$rows_service->rate_card_details,
                  "rate_card_details_ta"=>$rows_service->rate_card_details_ta,
                  "service_pic"=>$service_pic_url,
                );

              }

              $response = array("status" => "success", "msg" => "service found",'service_list'=>$service_list,"msg_en"=>"","msg_ta"=>"");
            }
            return $response;


        }

  //-------------------- Addtional Service order  details  -------------------//


//-------------------- Service Coupon list  -------------------//


      function service_coupon_list($user_master_id){
        $query_offer="SELECT * FROM offer_master WHERE status='Active' ORDER BY id DESC";
            $res_offer = $this->db->query($query_offer);
            if($res_offer->num_rows()==0){
              	$response = array("status" => "error", "msg" => "No Offers found","msg_en"=>"Offers unavailable!","msg_ta"=>"சலுகைகள் கிடைக்கவில்லை!");
            }else{
              $offer_result = $res_offer->result();
              foreach($offer_result as $rows_offers){
                $offer_list[]=array(
                  "id"=>$rows_offers->id,
                  "offer_title"=>$rows_offers->offer_title,
                  "offer_code"=>$rows_offers->offer_code,
                  "offer_percent"=>$rows_offers->offer_percent,
                  "max_offer_amount"=>$rows_offers->max_offer_amount,
                  "offer_description"=>$rows_offers->offer_description,

                );

              }

              $response = array("status" => "success", "msg" => "Offers found",'offer_details'=>$offer_list,"msg_en"=>"","msg_ta"=>"");
            }
            return $response;


      }

//-------------------- Service Coupon list  -------------------//

//-------------------- Apply Coupon to Service Order  -------------------//

  function apply_coupon_to_order($user_master_id,$coupon_id,$service_order_id){
      $query="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $res_query = $this->db->query($query);
      if($res_query->num_rows()!=0){
          $result_service=  $res_query->result();
          $query_coup="SELECT * FROM offer_master WHERE id='$coupon_id' AND status='Active'";
          $res_query_copun = $this->db->query($query_coup);
          if($res_query_copun->num_rows()==1){
              $result_coupon=  $res_query_copun->result();
              foreach($result_coupon as $rows_coupon){}
              foreach($result_service as $rows_service){}
                $payment_id=$rows_service->id;
                $advance=$rows_service->paid_advance_amount;
                $total= $rows_service->total_service_amount;

                $coupm_amt=($rows_coupon->offer_percent / 100) * $total;
                $max_amt=$rows_coupon->max_offer_amount;
                $dis_cp=$total-$coupm_amt;
                if($coupm_amt > $max_amt){
                  $final_total=$total-$max_amt;
                  $payable=$final_total-$advance;
                  $update="UPDATE service_payments SET coupon_id='$coupon_id',discount_amt='$max_amt',net_service_amount='$final_total',payable_amount='$payable' WHERE service_order_id='$service_order_id'";

                }else{
                  $final_total=$total-$coupm_amt;
                  $payable=$final_total-$advance;
                  $update="UPDATE service_payments SET coupon_id='$coupon_id',discount_amt='$coupm_amt',net_service_amount='$final_total',payable_amount='$payable' WHERE service_order_id='$service_order_id'";
                }

                	$update_result = $this->db->query($update);
                  if($update_result){
                    $response = array("status" => "success", "msg" => "You saved $rows_coupon->offer_percent","msg_en"=>"","msg_ta"=>"");
                  }else{
                    $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
                  }



          }else{
            	$response = array("status" => "error", "msg" => "Coupon Invalid","msg_en"=>"Invaild code!","msg_ta"=>"தவறான குறியீடு!");
          }


      }else{
        $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");

      }
      	return $response;


    }
//--------------------  Apply Coupon to Service Order  -------------------//


//--------------------  Remove Coupon to Service Order  -------------------//

    function remove_coupon_from_order($user_master_id,$service_order_id){
      $query="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $res_query = $this->db->query($query);
      if($res_query->num_rows()!=0){
        $res_select=  $res_query->result();
        foreach($res_select as $rows_service){}
          $payment_id=$rows_service->id;
          $advance=$rows_service->paid_advance_amount;
          $total= $rows_service->total_service_amount;
          $payable=$total-$advance;
          $update="UPDATE service_payments SET coupon_id='0',discount_amt='0',net_service_amount='$total',payable_amount='$payable' WHERE service_order_id='$service_order_id' ";
          $update_result = $this->db->query($update);
          if($update_result){
            $response = array("status" => "success", "msg" => "Coupon removed Successfully","msg_en"=>"","msg_ta"=>"");
          }else{
            $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
          }

      }else{
        $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
      }
      	return $response;

    }
//--------------------  Remove Coupon to Service Order  -------------------//


//-------------------- Payment to Service Order  -------------------//

function proceed_for_payment($user_master_id,$service_order_id){
      $query="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $res_query = $this->db->query($query);
      if($res_query->num_rows()!=0){
          $result_service=  $res_query->result();
              foreach($result_service as $rows_service){}
                $payment_id=$rows_service->id;
                $payable=$rows_service->payable_amount;
                $advance=$rows_service->paid_advance_amount;
                $total_service_amount=$rows_service->total_service_amount;
                $net_amount=$rows_service->net_service_amount;
                if($net_amount=='0.00'){
                $net_service_amount=$total_service_amount;
                }else{
                  $net_service_amount= $rows_service->net_service_amount;
                }


                $select_tax="SELECT * FROM tax_commission WHERE id='1'";
                $result_tax = $this->db->query($select_tax);
            		$res_tax = $result_tax->result();
                foreach($res_tax as $rows_tax){  }
                  $sgst=$rows_tax->sgst/100;
                  $cgst=$rows_tax->cgst/100;
                  $total_gst=$sgst+$cgst;
                  $internal_commission=$rows_tax->internal_commission/100;
                  $external_commission=$rows_tax->external_commission/100;

                // echo $net_service_amount;exit;
                $providrt_amt=$external_commission* $net_service_amount;
                $skilex_amount=$internal_commission*$net_service_amount;
                $gst=$total_gst*$skilex_amount;
                $gst_amount=$total_gst*$skilex_amount/2;
                $skile_net_amount=$skilex_amount-$gst;
                $payable=$net_service_amount-$advance;
                $update="UPDATE service_payments SET net_service_amount='$net_service_amount',payable_amount='$payable',skilex_amount='$skilex_amount',service_provider_amount='$providrt_amt',sgst_amount='$gst_amount',cgst_amount='$gst_amount',skilex_tax_amount='$gst',serv_pro_net_amount='$providrt_amt',skilex_net_amount='$skile_net_amount',updated_at=NOW() WHERE service_order_id='$service_order_id'";
                	$update_result = $this->db->query($update);
                  if($update_result){
                    $tim=time();
                    $order_id=$tim.'-'.$user_master_id.'-'.$service_order_id.'-'.$payment_id;
                    $pay_details=array(
                      "order_id"=>$order_id,
                      "payable_amount"=>$payable,
                    );
                    $response = array("status" => "success", "msg" => "Proceed for Payment","payment_details"=>$pay_details,"msg_en"=>"","msg_ta"=>"");
                  }else{
                    $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
                  }

      }else{
        $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");

      }
      	return $response;


    }
//--------------------  Payment  to Service Order  -------------------//



//--------------------  Service Person Tracking  -------------------//


        function service_person_tracking($user_master_id,$person_id){
          $select="SELECT * FROM vendor_status WHERE serv_pro_id='$person_id' AND online_status='Online'";
          $res = $this->db->query($select);
           if($res->num_rows()==1){
             $result = $res->result();
             foreach($result as $rows){}
             $tracking_info=array(
               "lat"=>$rows->serv_lat,
               "lon"=>$rows->serv_lon,
             );
            $response = array("status" => "success", "msg" => "Tracking found","track_info"=>$tracking_info);
           } else {
             $response = array("status" => "error", "msg" => "No Tracking found","msg_en"=>"","msg_ta"=>"");
           }

           return $response;


        }

//--------------------  Service Person Tracking  -------------------//


//--------------------  Pay By cash  -------------------//

    function pay_by_cash($user_master_id,$service_id,$payment_id,$amount){

      $select="SELECT * FROM service_orders as so WHERE so.id='$service_id'";
      $res = $this->db->query($select);
      if($res->num_rows()==1){
        $result = $res->result();
        foreach($result as $rows){}
        $update="UPDATE service_orders SET status='Paid' WHERE id='$service_id'";
        $res_update = $this->db->query($update);
        $update_pay="UPDATE service_payments SET status='Paid',offline_amount=offline_amount+'$amount' WHERE id='$payment_id'";
        $res_pay = $this->db->query($update_pay);
        $insert="INSERT INTO service_payment_history (service_order_id,service_payment_id,payment_type,notes,status,created_at,created_by) VALUES ('$service_id','$payment_id','Offline','Netamount','Success',NOW(),'$user_master_id')";
        $res_ins = $this->db->query($insert);
        if($res_ins){
           $response = array("status" => "success", "msg" => "Thank you for Payment","msg_en"=>"","msg_ta"=>"");
        }else{
          $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
        }

      } else {
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"","msg_ta"=>"");
      }

      return $response;


    }
//--------------------  Pay By cash  -------------------//




//-------------------- Service Reviews Add -------------------//
	 function Service_reviewsadd($user_master_id,$service_order_id,$ratings,$reviews)
	{
		$insert_sql = "INSERT INTO service_reviews (service_order_id, customer_id, rating, review, status,created_at,created_by) VALUES
					('". $service_order_id . "','". $user_master_id . "','". $ratings . "', '". $reviews . "','Pending', now(),'". $user_master_id . "')";
		$insert_result = $this->db->query($insert_sql);
		$response = array("status" => "success", "msg" => "Review Added","msg_en"=>"","msg_ta"=>"");
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
			$response = array("status" => "success", "msg" => "View Reviews List","services_reviewlist"=>$review_list,"msg_en"=>"","msg_ta"=>"");
		 } else {
			 $response = array("status" => "error", "msg" => "Service order Reviews not found","msg_en"=>"","msg_ta"=>"");
		 }

		 return $response;
	}
//-------------------- Service Reviews Add End -------------------//


}

?>
