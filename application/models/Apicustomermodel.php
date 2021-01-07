<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomermodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->model('smsmodel');
        $this->load->model('apicustomermodel');
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



    //-------------------- Version check -------------------//


    function version_check($version_code){
      if($version_code >= 3){
          $response = array("status" => "success","version_code"=>$version_code);
      }else{
        $response = array("status" => "error","version_code"=>$version_code);
      }
    	return $response;
    }

  //-------------------- Version check -------------------//



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
          $preferred_lang_id=$rows->preferred_lang_id;
			}
      $text='SKILEXC0';
			$update_sql = "UPDATE login_users SET otp = '".$OTP."', updated_at=NOW(),referral_code='$text$user_master_id' WHERE id ='".$user_master_id."'";
			$update_result = $this->db->query($update_sql);
		} else {
			 $insert_sql = "INSERT INTO login_users (phone_no, otp, user_type, mobile_verify, email_verify, document_verify, status) VALUES ('". $phone_no . "','". $OTP . "','5','N','N','N','Active')";
             $insert_result = $this->db->query($insert_sql);
			 $user_master_id = $this->db->insert_id();

			 $insert_query = "INSERT INTO customer_details (user_master_id, status) VALUES ('". $user_master_id . "','Active')";
       $insert_result = $this->db->query($insert_query);

     $get_prefer="SELECT * FROM login_users where id='$user_master_id'";
     $result_pre=$this->db->query($get_prefer);
     foreach($result_pre->result() as $row_preferred){}
     $preferred_lang_id=$row_preferred->preferred_lang_id;

     $text='SKILEXC0';
     $update_sql = "UPDATE login_users SET  updated_at=NOW(),referral_code='$text$user_master_id' WHERE id ='".$user_master_id."'";
     $update_result = $this->db->query($update_sql);

		}
    if($preferred_lang_id=='1'){
        $notes = "Your SkilEx Verification code is: ".$OTP."  GHTaEcbz16c";
    }else{
        $notes = "Your SkilEx Verification code is: ".$OTP."  GHTaEcbz16c";
    }

    $phone=$phone_no;
    $this->smsmodel->send_sms($phone,$notes);
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

	 function Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype,$unique_number,$referral_code)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no = '".$phone_no."' AND otp = '".$otp."' AND user_type = '5' AND status='Active'";
		$sql_result = $this->db->query($sql);

		if($sql_result->num_rows()>0)
		{
		  $update_sql = "UPDATE login_users SET mobile_verify ='Y' WHERE id='$user_master_id'";
			$update_result = $this->db->query($update_sql);


      $update_unique_number="UPDATE notification_master SET user_master_id='$user_master_id',user_stat='Register' WHERE user_master_id='$unique_number'";
      $update_unique_number_result = $this->db->query($update_unique_number);

      if(empty($referral_code)){

      }else{
          $this->add_referral_code($user_master_id,$referral_code);
      }


			$gcmQuery = "SELECT * FROM notification_master WHERE mobile_key like '%" .$device_token. "%' AND user_master_id = '".$user_master_id."' LIMIT 1";
			$gcm_result = $this->db->query($gcmQuery);
			$gcm_ress = $gcm_result->result();
			if($gcm_result->num_rows()==0)
			{
				 $sQuery = "INSERT INTO notification_master (user_master_id,mobile_key,mobile_type,created_at) VALUES ('". $user_master_id . "','". $device_token . "','". $mobiletype . "',NOW())";
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

############### Adding referral Code ##################################
function add_referral_code($user_master_id,$referral_code){
  if($referral_code=='SKILEX100'){
     $check="SELECT * FROM login_users WHERE id='$user_master_id'";
     $re_check=$this->db->query($check);
     foreach($re_check->result() as $row_checks){}
       $referral_status=$row_checks->referral_status;
       if($referral_status=='0'){
         $get_point='100';
         $adding_history="INSERT INTO referral_history (user_master_id,user_points,referral_code,referral_master_id,referral_points,created_at,created_by) VALUES ('$user_master_id','$get_point','$referral_code','0','$get_point',NOW(),'$user_master_id')";
         $res_adding_history=$this->db->query($adding_history);
         $update_status="UPDATE login_users SET referral_status='1' WHERE id='$user_master_id'";
         $excute=$this->db->query($update_status);

         $query_user_master="SELECT * FROM user_points WHERE user_master_id='$user_master_id'";
         $re_query_user_master=$this->db->query($query_user_master);
         if($re_query_user_master->num_rows()==0){
           $user_referral_query="INSERT INTO user_points (user_master_id,total_points,points_to_claim,status,created_at,created_by) VALUES ('$user_master_id','$get_point','$get_point','Active',NOW(),'$user_master_id')";
         }else{
           $user_referral_query="UPDATE user_points SET total_points=total_points+'$get_point',points_to_claim=points_to_claim+'$get_point',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
         }
         $excute_user=$this->db->query($user_referral_query);
         if($excute_user){
            $response = array("status" => "success");
         }else{
              $response = array("status" => "error");
         }


       }else{
          $response = array("status" => "error");
       }
  }else{

    $check_referral="SELECT * FROM login_users where referral_code='$referral_code'";
    $re_referral=$this->db->query($check_referral);
    if($re_referral->num_rows()!=0){
      $output = str_split($referral_code, 7);
      $referral_user_id=$output[1];
      $check="SELECT * FROM login_users WHERE id='$user_master_id'";
      $re_check=$this->db->query($check);
      foreach($re_check->result() as $row_checks){}
        $referral_status=$row_checks->referral_status;
        if($referral_status=='0'){

          $update_status="UPDATE login_users SET referral_status='1' WHERE id='$user_master_id'";
          $excute=$this->db->query($update_status);

          $master="SELECT * FROM referral_master where id='1'";
          $res_master=$this->db->query($master);
          foreach($res_master->result() as $rows_master_point){}
          $get_point=$rows_master_point->referral_points;


          $adding_history="INSERT INTO referral_history (user_master_id,user_points,referral_code,referral_master_id,referral_points,created_at,created_by) VALUES ('$user_master_id','$get_point','$referral_code','$referral_user_id','$get_point',NOW(),'$user_master_id')";
          $res_adding_history=$this->db->query($adding_history);


          $check_referral_master="SELECT * FROM user_points WHERE user_master_id='$referral_user_id'";
          $re_referral_master=$this->db->query($check_referral_master);
          if($re_referral_master->num_rows()==0){
            $master_referral_query="INSERT INTO user_points (user_master_id,total_points,points_to_claim,status,created_at,created_by) VALUES ('$referral_user_id','$get_point','$get_point','Active',NOW(),'$referral_user_id')";
          }else{
            $master_referral_query="UPDATE user_points SET total_points=total_points+'$get_point',points_to_claim=points_to_claim+'$get_point',updated_at=NOW(),updated_by='$referral_user_id' WHERE user_master_id='$referral_user_id'";
          }
          $excute=$this->db->query($master_referral_query);



          $query_user_master="SELECT * FROM user_points WHERE user_master_id='$user_master_id'";
          $re_query_user_master=$this->db->query($query_user_master);
          if($re_query_user_master->num_rows()==0){
            $user_referral_query="INSERT INTO user_points (user_master_id,total_points,points_to_claim,status,created_at,created_by) VALUES ('$user_master_id','$get_point','$get_point','Active',NOW(),'$user_master_id')";
          }else{
            $user_referral_query="UPDATE user_points SET total_points=total_points+'$get_point',points_to_claim=points_to_claim+'$get_point',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
          }
          $excute_user=$this->db->query($user_referral_query);
          if($excute_user){
              $response = array("status" => "success");
          }else{
              $response = array("status" => "error");
          }
        }else{
          $response = array("status" => "error");
        }
    }else{
      $response = array("status" => "error");
    }


  }

     return $response;

}
############### Adding referral Code ##################################


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


############### User language update###############
  function user_lang_update($user_master_id,$lang_id){
    $update="UPDATE login_users SET preferred_lang_id='$lang_id' WHERE id='$user_master_id'";
    $res = $this->db->query($update);
    if($res){
      $response=array("status"=>"success","msg_en"=>"Language update successfully","msg_ta"=>"Language Update successfully");
    }else{
      $response = array("status" => "error","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");

    }
      return $response;
  }
############### User language update###############

############### User points and referral code###############

  function user_points_referral_code($user_master_id){

    $query="SELECT lu.referral_code,ifnull(up.points_to_claim,'0') as points_to_claim FROM login_users as lu left join user_points as up on up.user_master_id=lu.id  where lu.id='$user_master_id'";
    $res = $this->db->query($query);

     if($res->num_rows()>0){
        foreach ($res->result() as $rows)
      {
        $user_points  = array(
            "referral_code" => $rows->referral_code,
            "points_to_claim" => $rows->points_to_claim
        );
      }
          $response = array("status" => "success", "msg" => "View points ","points_code"=>$user_points,"msg_en"=>"","msg_ta"=>"");

    }else{
            $response = array("status" => "error", "msg" => "points not found","msg_en"=>"","msg_ta"=>"");
    }

    return $response;

  }
  ############### User points and referral code###############


  ############### check  points to claim ###############

  function check_to_claim_points($user_master_id){
    $check="SELECT * FROM referral_master WHERE id='1'";
    $res = $this->db->query($check);
    foreach($res->result() as $row_referral){}
    $minimum_points=$row_referral->minimum_points_to_claim;
    $division_points=$row_referral->division_points;
    $select="SELECT * FROM user_points where user_master_id='$user_master_id'";
    $re_select = $this->db->query($select);
    if($re_select->num_rows()==0){
      $response=array("status"=>"error","msg"=>"You cannot claim amount is low","msg_en"=>"","msg_ta"=>"உங்கள் புள்ளி குறைவாக உள்ளது. எனவே நீங்கள் உரிமை கோர முடியாது");
    }else{
      foreach($re_select->result() as $rows_points){}
      $user_points_to_claim=$rows_points->points_to_claim;
      if($user_points_to_claim >= $minimum_points){
        $exact_amt=round($user_points_to_claim/$division_points);
        $response=array("status"=>"success","msg"=>"Can Claim","amount_to_be_claim"=>$exact_amt,"msg_en"=>"","msg_ta"=>"");
      }else{
          $response=array("status"=>"error","msg"=>"You cannot claim point is low","msg_en"=>"","msg_ta"=>"உங்கள் புள்ளி குறைவாக உள்ளது. எனவே நீங்கள் உரிமை கோர முடியாது");
      }
    }
    return $response;

  }


  ############### check  points to claim ###############


  ############### Confrim to claim ###############

  function confirm_to_claim($user_master_id){
    $check="SELECT * FROM referral_master WHERE id='1'";
    $res = $this->db->query($check);
    foreach($res->result() as $row_referral){}
    $minimum_points=$row_referral->minimum_points_to_claim;
    $division_points=$row_referral->division_points;
    $select="SELECT * FROM user_points where user_master_id='$user_master_id'";
    $re_select = $this->db->query($select);
    if($re_select->num_rows()==0){
      $response=array("status"=>"error","msg"=>"You cannot claim amount is low","msg_en"=>"","msg_ta"=>"உங்கள் புள்ளி குறைவாக உள்ளது. எனவே நீங்கள் உரிமை கோர முடியாது");
    }else{
      foreach($re_select->result() as $rows_points){}
      $user_points_to_claim=$rows_points->points_to_claim;
      if($user_points_to_claim >= $minimum_points){
        $exact_amt=$user_points_to_claim/$division_points;

         $update_user_points="UPDATE user_points SET points_to_claim=points_to_claim-points_to_claim,claimed_points=claimed_points+points_to_claim,earned_amount=earned_amount+'$exact_amt',updated_at=NOW() WHERE user_master_id='$user_master_id'";
         $re_update = $this->db->query($update_user_points);


         $check_wallet="SELECT * FROM user_wallet WHERE user_master_id='$user_master_id'";
         $res_wallet=$this->db->query($check_wallet);
         if($res_wallet->num_rows()==0){
            $query_wallet="INSERT INTO user_wallet(user_master_id,amt_in_wallet,total_amt_in_wallet,status,updated_at,updated_by) VALUES('$user_master_id','$exact_amt','$exact_amt','Active',NOW(),'$user_master_id')";
         }else{
           $query_wallet="UPDATE user_wallet SET amt_in_wallet=amt_in_wallet+'$exact_amt',total_amt_in_wallet=total_amt_in_wallet+'$exact_amt',updated_at=NOW() WHERE user_master_id='$user_master_id'";
         }
         $res_wallet_query=$this->db->query($query_wallet);
         $wallet_history="INSERT INTO wallet_history (user_master_id,transaction_amt,status,notes,created_at,created_by) VALUES ('$user_master_id','$exact_amt','Reedem','Earned from points',NOW(),'$user_master_id')";
         $ex_wallet_history=$this->db->query($wallet_history);
         if($ex_wallet_history){
             $response=array("status"=>"success","msg_en"=>"Amount added in wallet","msg_ta"=>"Amount added in wallet","msg"=>"Amount added in wallet!");
         }else{
             $response = array("status" => "error","msg"=>"Oops! Something went wrong!","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
         }
      }else{
          $response=array("status"=>"error","msg"=>"You cannot claim point is low","msg_en"=>"","msg_ta"=>"உங்கள் புள்ளி குறைவாக உள்ளது. எனவே நீங்கள் உரிமை கோர முடியாது");
      }
    }

    return $response;

  }

  ############### Confim to claim ###############


  ############### Check wallet balance and history ###############

  function check_wallet_balance_and_history($user_master_id){

    $query_wallet="SELECT * FROM user_wallet where user_master_id='$user_master_id'";
    $re_query_wallet=$this->db->query($query_wallet);
    if($re_query_wallet->num_rows()==0){
      $wallet_balance="0";
    }else{
      foreach($re_query_wallet->result() as $row_wallet_balance){}
      $wallet_balance=$row_wallet_balance->amt_in_wallet;
    }


    $query_wallet_history="SELECT DATE_FORMAT(created_at,'%d-%m-%Y') as created_date,TIME_FORMAT(created_at, '%h:%i %p') as created_time,transaction_amt,status,notes,user_master_id,id FROM wallet_history where user_master_id='$user_master_id' order by id desc";
    $re_wallet_history=$this->db->query($query_wallet_history);
    if($re_wallet_history->num_rows()==0){
      $res_wallet=array("status"=>"error","msg_en"=>"no history found","msg_ta"=>"no history found","msg"=>"error");
    }else{
      foreach($re_wallet_history->result() as $rows_history){
        $wallet_array[]=array(
            "id" => $rows_history->id,
            "transaction_amt" => $rows_history->transaction_amt,
            "status"=>$rows_history->status,
            "created_date"=>$rows_history->created_date,
            "created_time"=>$rows_history->created_time,
            "notes"=>$rows_history->notes
        );
      }
    $res_wallet=array("status"=>"success","msg_en"=>"wallet history found","msg_ta"=>"wallet history found","wallet_data"=>$wallet_array);
    }
    $response=array("status"=>"success","wallet_balance"=>$wallet_balance,"result_wallet"=>$res_wallet);
    return $response;

  }

  ############### Check wallet balance and history ###############

  ############### Top Trending services ###############

  function top_trending_services($user_master_id){

    $get_count="SELECT * FROM tax_commission where id='1'";
    $res_count = $this->db->query($get_count);
    foreach($res_count->result() as $rows_count){}
    $trending_count=$rows_count->trending_count;

    $query="SELECT so.service_id,s.id,count(so.service_id) as service_count,s.service_name,s.service_ta_name,s.service_pic,mc.cat_pic,s.main_cat_id,s.sub_cat_id
FROM service_orders as so
    left join services as s on s.id=so.service_id
    left join main_category as mc on mc.id=s.main_cat_id
    left join sub_category as sc on sc.id=s.sub_cat_id
    where s.status='Active' and sc.status='Active' and mc.status='Active' GROUP by so.service_id ORDER by service_count desc LIMIT $trending_count";
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
          $response = array("status" => "success", "msg" => "View Services","services"=>$subcatData,"msg_en"=>"","msg_ta"=>"");

    }else{
            $response = array("status" => "error", "msg" => "Services not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
    }

    return $response;

  }

  ############### Top Trending services ###############


  ############### Top Trending services ###############

  function service_rating_and_reviews($user_master_id,$service_id){

    $query="SELECT sr.service_order_id,so.id,so.service_id,sr.rating,sr.review,sr.customer_id,cd.full_name,cd.profile_pic,DATE_FORMAT(sr.created_at,'%d-%m-%Y') as review_date from service_reviews as sr
left join service_orders as so on so.id=sr.service_order_id
left join customer_details as cd on cd.user_master_id=sr.customer_id WHERE so.service_id='$service_id' order by sr.created_at desc";
    $res = $this->db->query($query);

     if($res->num_rows()>0){
        foreach ($res->result() as $rows)
      {
        $profile_pic = $rows->profile_pic;
        if ($profile_pic!=''){
          $profile_pic_url = base_url().'assets/customers/'.$profile_pic;
        } else {
          $profile_pic_url = "";
        }

        if(empty($rows->full_name)){
          $name="SkilEx Customer";
        }else{
          $name=$rows->full_name;
        }
        $ser_data[]  = array(
            "service_id" => $rows->id,
            "rating" => $rows->rating,
            "review" => $rows->review,
            "customer_name" => $name,
            "service_id" => $rows->service_id,
            "review_date" => $rows->review_date,
            "profile_picture" => $profile_pic_url,
            "customer_id" => $rows->customer_id

        );
      }
          $response = array("status" => "success", "msg" => "View Services reviews and rating","services_reviews"=>$ser_data,"msg_en"=>"","msg_ta"=>"");

    }else{
            $response = array("status" => "error", "msg" => "No reviews and rating found!","msg_en"=>"No reviews and rating found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
    }

    return $response;

  }

  ############### Top Trending services ###############


  ############### banner list###############

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
	 function View_maincategory($user_master_id,$version_code)
	{
      if(empty($version_code)){
        $response = array("status" => "error", "msg" => "Sorry you have to update latest App!","msg_en"=>"Sorry you have to update latest App!","msg_ta"=>"பிரிவுகள் கிடைக்கவில்லை!");

      }else{
        $query = "SELECT id,main_cat_name,main_cat_ta_name,cat_pic from main_category WHERE status = 'Active' order by cat_position asc";
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
      }


			return $response;
	}
//-------------------- Main Category End -------------------//

//-------------------- Sub Category -------------------//
	 function View_subcategory($main_cat_id)
	{
			$query = "SELECT id,sub_cat_name,sub_cat_ta_name,sub_cat_pic from sub_category WHERE main_cat_id = '$main_cat_id' AND status = 'Active' order by sub_cat_position asc";
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
       $query="SELECT s.*  FROM services as s
      left join main_category as mc on mc.id=s.main_cat_id
      left join sub_category as sc on sc.id=s.sub_cat_id
      WHERE (s.service_name LIKE '%$service_txt%' or s.service_ta_name LIKE '%$service_txt%') and s.status='Active' and mc.status='Active' and sc.status='Active'";
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
      $query="SELECT  IFNULL(oc.user_master_id,0) AS selected,s.* FROM services  as s  left join order_cart as oc on oc.service_id=s.id  and oc.user_master_id='$user_master_id' where s.main_cat_id='$main_cat_id' and s.sub_cat_id='$sub_cat_id' AND s.status = 'Active' GROUP by s.id order by service_position asc";
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


    function remove_service_from_cart($user_master_id,$category_id,$sub_category_id,$service_id){
       $check_service="SELECT * FROM order_cart WHERE service_id='$service_id' AND user_master_id='$user_master_id'";
      $check_res= $this->db->query($check_service);
      if($check_res->num_rows()==1){
        $insert="DELETE FROM order_cart WHERE service_id='$service_id' AND user_master_id='$user_master_id'";
        $insert_result = $this->db->query($insert);
        if($insert_result){
          $get_total_count="SELECT count(*) as service_count,IFNULL(sum(s.rate_card),'') as total_amt FROM order_cart as oc left join  services as s on s.id=oc.service_id WHERE oc.user_master_id='$user_master_id'";
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
        $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Service not found","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
      }

        return $response;
    }
//-------------------- remove Services Cart  -------------------//


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



  function proceed_to_book_order($user_master_id,$contact_person_name,$contact_person_number,$service_latlon,$service_location,$service_address,$order_date,$order_timeslot,$order_notes){
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
        $phone=$contact_person_number;


        if($advance_amount=='0.00'){
        $adva_status='NA';
        $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
        $user_result = $this->db->query($sQuery);
              if($user_result->num_rows()>0)
              {
                  foreach ($user_result->result() as $rows)
                  {
                    $gcm_key=$rows->mobile_key;
                    $mobile_type=$rows->mobile_type;
                    $preferred_lang_id=$rows->preferred_lang_id;
                    $head='Skilex';
                    if($preferred_lang_id=='1'){
                      $message='ஸ்கிலெக்ஸ்லிருந்து வாழ்த்துக்கள்! தங்களது  ஆர்டர் பதிவு செய்யப்பட்டது.';
                    }else{
                        $message='Greetings from Skilex!.Your Order has been booked.';
                    }
                    $user_type='5';
                    $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                  }

                  $notes=$message;
                  $phone=$phone;
                  $this->smsmodel->send_sms($phone,$notes);
              }
        }else{
          $adva_status='N';
          $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
          $user_result = $this->db->query($sQuery);
                if($user_result->num_rows()>0)
                {
                    foreach ($user_result->result() as $rows)
                    {
                      $gcm_key=$rows->mobile_key;
                      $mobile_type=$rows->mobile_type;
                      $preferred_lang_id=$rows->preferred_lang_id;
                      $head='Skilex';
                      if($preferred_lang_id=='1'){
                        $message='உங்கள் முன்பதிவு  கட்டணம் கிடைத்ததும் உங்கள் ஆர்டர் முன்பதிவு செய்யப்படும்.';
                      }else{
                        $message='Skilex!.Once the advance payment has been received your order will booked.';
                      }
                      $user_type='5';
                      $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                    }

                    $notes=$message;
                    $phone=$phone;
                    $this->smsmodel->send_sms($phone,$notes);
                }

        }

        $insert_service="INSERT INTO service_orders(customer_id,contact_person_name,contact_person_number,main_cat_id,sub_cat_id,service_id,order_date,order_timeslot,order_notes,service_latlon,service_location,service_address,advance_amount_paid,advance_payment_status,service_rate_card,status,created_at,created_by) VALUES('$user_master_id','$contact_person_name','$contact_person_number','$f_cat_id','$f_sub_cat_id','$last_ser_id','$serv_date','$order_timeslot','$order_notes','$service_latlon','$service_location','$service_address','$advance_amount','$adva_status','$ser_rate_card','Pending',NOW(),'$user_master_id')";
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

           $phone=$contact_person_number;
           $notes='Greetings from Skilex!. Your Order has been Booked.';
           $this->smsmodel->send_sms($phone,$notes);


           if($advance_amount=='0.00'){
           $adva_status='NA';
           $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
           $user_result = $this->db->query($sQuery);
                 if($user_result->num_rows()>0)
                 {
                     foreach ($user_result->result() as $rows)
                     {
                       $gcm_key=$rows->mobile_key;
                       $mobile_type=$rows->mobile_type;
                       $preferred_lang_id=$rows->preferred_lang_id;
                       $head='Skilex';
                       if($preferred_lang_id=='1'){
                         $message='ஸ்கிலெக்ஸ்லிருந்து வாழ்த்துக்கள்! தங்களது  ஆர்டர் பதிவு செய்யப்பட்டது.';
                       }else{
                           $message='Greetings from Skilex!.Your Order has been booked.';
                       }
                       $user_type='5';
                       $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                     }

                     $notes=$message;
                     $phone=$phone;
                     $this->smsmodel->send_sms($phone,$notes);
                 }
           }else{
             $adva_status='N';
             $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
             $user_result = $this->db->query($sQuery);
                   if($user_result->num_rows()>0)
                   {
                       foreach ($user_result->result() as $rows)
                       {
                         $gcm_key=$rows->mobile_key;
                         $mobile_type=$rows->mobile_type;
                         $preferred_lang_id=$rows->preferred_lang_id;
                         $head='Skilex';
                         if($preferred_lang_id=='1'){
                           $message='உங்கள் முன்பதிவு  கட்டணம் கிடைத்ததும் உங்கள் ஆர்டர் முன்பதிவு செய்யப்படும்.';
                         }else{
                           $message='Skilex!.Once the advance payment has been received your order will booked.';
                         }
                         $user_type='5';
                         $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                       }

                       $notes=$message;
                       $phone=$phone;
                       $this->smsmodel->send_sms($phone,$notes);
                   }

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


    function service_provider_allocation($user_master_id,$service_id){
      ob_implicit_flush(true);
      ob_end_flush();
    $get_main_cat="SELECT * FROM service_orders WHERE id='$service_id'";
    $get_main_cat_res  = $this->db->query($get_main_cat);
    $res_main_cat=$get_main_cat_res->result();
    foreach($res_main_cat as $row_get_main_cat_id){}
      $main_cat_id_first=$row_get_main_cat_id->main_cat_id;
    $count_provider="SELECT count(*) as prov_count from login_users as lu
    left join vendor_status  as vs on vs.serv_pro_id=lu.id
    left JOIN serv_prov_pers_skills as spps on spps.user_master_id=lu.id
    where lu.status='Active' and vs.online_status='Online' and lu.user_type=3 and lu.document_verify='Y' and spps.main_cat_id='$main_cat_id_first'";

    $result_cnt = $this->db->query($count_provider);
    foreach($result_cnt->result() as $cnt_provider){}
      if($cnt_provider->prov_count==0){

      }else{
         $cnt=$cnt_provider->prov_count-1;


        for ($i=1; $i<=$cnt; $i++) {
          $display_minute=$i;
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
              $get_last_service_provider_id="SELECT spd.id as last_id,so.* FROM service_orders as so left join service_provider_details as spd on spd.user_master_id=so.serv_prov_id where so.serv_prov_id!=0  and (so.status='Paid' OR so.status='Completed') ORDER BY so.id desc LIMIT 1";
                $result_last_sp_id=$this->db->query($get_last_service_provider_id);
                $res_sp_id=$result_last_sp_id->result();
                if($result_last_sp_id->num_rows()==0){

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


                $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$sp_user_master_id'";
                 $user_result = $this->db->query($sQuery);
                 if ($user_result->num_rows() > 0) {
                     foreach ($user_result->result() as $rows) {
                       $gcm_key=$rows->mobile_key;
                       $mobile_type=$rows->mobile_type;
                       $head='Skilex';
                       $message="You have received order from customer.";
                       $user_type='3';
                       $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                     }
                 }
                 $check_order_history="SELECT * FROM service_order_history WHERE service_order_id='$service_id' and serv_prov_id='$sp_user_master_id'";
                  $res_order_history=$this->db->query($check_order_history);

                  if($res_order_history->num_rows()==0){
                    $title="Order";
                    $gcm_key=$rows_id_next->mobile_key;
                    $mobiletype=$rows_id_next->mobile_type;
                    // $notes="Hi $full_name You Received order from Customer $contact_person_name";
                    $notes="Greetings from Skilex! You received an order from the Customer. Please look into the app for more details.";
                    $phone=$Phoneno;
                    $this->smsmodel->send_sms($phone,$notes);
                    ///$this->sendNotification($gcm_key,$title,$Message,$mobiletype);
                    $get_gcm="SELECT * FROM notification_master WHERE user_master_id='$user_master_id' order by id desc";
                     $res_gcm= $this->db->query($get_gcm);
                     if($res_gcm->num_rows()==0){
                     }else{
                       $gcm_result=$res_gcm->result();
                         foreach($gcm_result as $rows_gcm){
                           $gcm_key=$rows_gcm->mobile_key;
                           $mobile_type=$rows_gcm->mobile_type;
                           $head='Skilex';
                           $message='Thank you for booking service ';
                           $user_type='5';
                          $this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                         }
                     }

                    $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Requested' AND service_order_id='$service_id' ORDER BY created_at desc LIMIT 1";
                    $res_expried=$this->db->query($update_exper);


                    $select_expired_user="SELECT * FROM service_order_history WHERE service_order_id='$service_id' AND status='Expired' ORDER BY created_at desc LIMIT 1";
                    $result_expired=$this->db->query($select_expired_user);
                    if($result_expired->num_rows()==0){
                    }else{
                      $result_exp=$result_expired->result();
                      foreach($result_exp as $rows_expired){
                      $serv_id=$rows_expired->serv_prov_id;
                      $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_id'";
                       $user_result = $this->db->query($sQuery);
                       if ($user_result->num_rows() > 0) {
                           foreach ($user_result->result() as $rows) {
                             $gcm_key=$rows->mobile_key;
                             $mobile_type=$rows->mobile_type;
                             $head='Skilex';
                             $message="Service Order expired.";
                             $user_type='3';
                            $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                           }
                       }
                      }
                    }
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
                    // $last_sp_id=$rows_last_sp_id->last_id;
                  if($i==1){
                  $last_sp_id=$rows_last_sp_id->last_id;
                  }else{
                    $checking_order_hist="SELECT * from service_order_history where service_order_id='$service_id' and status='Requested' order by id desc LIMIT 1";
                    $ex_checking_order_hist=$this->db->query($checking_order_hist);
                    $res_checking_hist=$ex_checking_order_hist->result();
                    foreach($res_checking_hist as $rows_checking_existory){}
                     $last_sp_id=$rows_checking_existory->serv_prov_id;


                  }

                  $next_id=$display_minute+$last_sp_id;

                 if($display_minute==1){
                   $limit="LIMIT 1";
                   $get_gcm="SELECT * FROM notification_master WHERE user_master_id='$user_master_id' order by id desc";
                    $res_gcm= $this->db->query($get_gcm);
                    if($res_gcm->num_rows()==0){
                    }else{
                      $gcm_result=$res_gcm->result();
                        foreach($gcm_result as $rows_gcm){
                          $gcm_key=$rows_gcm->mobile_key;
                          $mobile_type=$rows_gcm->mobile_type;
                          $head='Skilex';
                          $message='Thank you for booking service ';
                          $user_type='5';
                          $this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                        }
                    }
                 }else if($display_minute==2){
                     $limit="LIMIT 1,1";
                 }else if($display_minute==3){
                   $limit="LIMIT 2,1";
                 }else{
                   $limit="LIMIT 0";
                 }

                    $check_provider="SELECT spd.id,mobile_key, mobile_type,spd.user_master_id,owner_full_name,phone_no,vs.STATUS
                  FROM serv_prov_pers_skills AS spps
                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active' GROUP by spd.id";

                $res_chec_provider=$this->db->query($check_provider);
                  if($res_chec_provider->num_rows()==1){


                  $get_sp_id="SELECT spd.id,mobile_key, mobile_type,spd.user_master_id,owner_full_name,phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                  COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                  SIN( RADIANS( serv_lat ) ) ) ) AS distance,vs.status
                  FROM serv_prov_pers_skills AS spps
                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active' GROUP by spd.id";

                  }else{
                                // echo $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                //   COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                //   SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                //   FROM serv_prov_pers_skills AS spps
                                //   LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                //   LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                //   LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                //   LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                //   WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                //   AND spd.id>$last_sp_id GROUP BY spps.user_master_id ASC
                                //   UNION
                                //   SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                //                 COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                //                 SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                //   FROM serv_prov_pers_skills AS spps
                                //   LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                //   LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                //   LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                //   LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                //   WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                //   AND spd.id<$last_sp_id GROUP BY spps.user_master_id ASC) s_union $limit";

                                     $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                    COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                    SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                    FROM serv_prov_pers_skills AS spps
                                    LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                    LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                    LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                    LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                    WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                    AND spd.user_master_id>$last_sp_id GROUP BY spps.user_master_id ASC
                                    UNION
                                    SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                                  COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                                  SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                    FROM serv_prov_pers_skills AS spps
                                    LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                    LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                    LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                    LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                    WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                    AND spd.user_master_id<$last_sp_id GROUP BY spps.user_master_id ASC) s_union";




                  }
                  $ex_next_id=$this->db->query($get_sp_id);
                  if($ex_next_id->num_rows()==0){
                    $response = array("status" => "error", "msg" => "Hitback","msg_en"=>"","msg_ta"=>"");
                  }else{
                    $res_next_ip=$ex_next_id->result();
                    foreach($res_next_ip as $rows_id_next){ }
                    $Phoneno=$rows_id_next->phone_no;
                    $full_name=$rows_id_next->owner_full_name;
                    $sp_user_master_id=$rows_id_next->user_master_id;
                    $title="Order";
                    $gcm_key=$rows_id_next->mobile_key;
                    $mobiletype=$rows_id_next->mobile_type;
                    $notes="Hi $full_name You Received order from Customer $contact_person_name";
                    $phone=$Phoneno;

                    $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$sp_user_master_id'";
                     $user_result = $this->db->query($sQuery);
                     if ($user_result->num_rows() > 0) {
                         foreach ($user_result->result() as $rows) {
                           $gcm_key=$rows->mobile_key;
                           $mobile_type=$rows->mobile_type;
                           $head='Skilex';
                           $message="Greetings from Skilex! You received an order from the Customer. Please look into the app for more details.";
                           $user_type='3';
                           $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                         }
                     }
                    $this->smsmodel->send_sms($phone,$notes);
                    $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Requested' AND service_order_id='$service_id' ORDER BY created_at desc LIMIT 1";
                    $res_expried=$this->db->query($update_exper);


                    $select_expired_user="SELECT * FROM service_order_history WHERE service_order_id='$service_id' AND status='Expired' ORDER BY created_at desc LIMIT 1";
                    $result_expired=$this->db->query($select_expired_user);
                    if($result_expired->num_rows()==0){

                    }else{
                      $result_exp=$result_expired->result();
                      foreach($result_exp as $rows_expired){
                      $serv_id=$rows_expired->serv_prov_id;
                      $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_id'";
                       $user_result = $this->db->query($sQuery);
                       if ($user_result->num_rows() > 0) {
                           foreach ($user_result->result() as $rows) {
                             $gcm_key=$rows->mobile_key;
                             $mobile_type=$rows->mobile_type;
                             $head='Skilex';
                             $message="Service Order expired.";
                             $user_type='3';
                             $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                           }
                       }
                      }
                    }
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
             sleep(60);
               // return $response;
          }else{
            $response = array("status" => "error", "msg" => "Service not found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
          }
        }

      }

       return $response;

    }


//-------------------- Service Provider allocation -------------------//



//-------------------- Service Provider allocation -------------------//


    function service_provider_allocation_ios($user_master_id,$service_id,$display_minute){
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
              $get_last_service_provider_id="SELECT spd.id as last_id,so.* FROM service_orders as so left join service_provider_details as spd on spd.user_master_id=so.serv_prov_id where so.serv_prov_id!=0  and (so.status='Paid' OR so.status='Completed') ORDER BY so.id desc LIMIT 1";
                $result_last_sp_id=$this->db->query($get_last_service_provider_id);
                $res_sp_id=$result_last_sp_id->result();
                if($result_last_sp_id->num_rows()==0){

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


                $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$sp_user_master_id'";
                 $user_result = $this->db->query($sQuery);
                 if ($user_result->num_rows() > 0) {
                     foreach ($user_result->result() as $rows) {
                       $gcm_key=$rows->mobile_key;
                       $mobile_type=$rows->mobile_type;
                       $head='Skilex';
                       $message="You have received order from customer.";
                       $user_type='3';
                       $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                     }
                 }
                 $check_order_history="SELECT * FROM service_order_history WHERE service_order_id='$service_id' and serv_prov_id='$sp_user_master_id'";
                  $res_order_history=$this->db->query($check_order_history);

                  if($res_order_history->num_rows()==0){
                    $title="Order";
                    $gcm_key=$rows_id_next->mobile_key;
                    $mobiletype=$rows_id_next->mobile_type;
                    // $notes="Hi $full_name You Received order from Customer $contact_person_name";
                    $notes="Greetings from Skilex! You received an order from the Customer. Please look into the app for more details.";
                    $phone=$Phoneno;
                    $this->smsmodel->send_sms($phone,$notes);
                    $this->sendNotification($gcm_key,$title,$Message,$mobiletype);
                    $get_gcm="SELECT * FROM notification_master WHERE user_master_id='$user_master_id' order by id desc";
                     $res_gcm= $this->db->query($get_gcm);
                     if($res_gcm->num_rows()==0){
                     }else{
                       $gcm_result=$res_gcm->result();
                         foreach($gcm_result as $rows_gcm){
                           $gcm_key=$rows_gcm->mobile_key;
                           $mobile_type=$rows_gcm->mobile_type;
                           $head='Skilex';
                           $message='Thank you for booking service ';
                           $user_type='5';
                          $this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                         }
                     }

                    $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Requested' AND service_order_id='$service_id' ORDER BY created_at desc LIMIT 1";
                    $res_expried=$this->db->query($update_exper);


                    $select_expired_user="SELECT * FROM service_order_history WHERE service_order_id='$service_id' AND status='Expired' ORDER BY created_at desc LIMIT 1";
                    $result_expired=$this->db->query($select_expired_user);
                    if($result_expired->num_rows()==0){
                    }else{
                      $result_exp=$result_expired->result();
                      foreach($result_exp as $rows_expired){
                      $serv_id=$rows_expired->serv_prov_id;
                      $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_id'";
                       $user_result = $this->db->query($sQuery);
                       if ($user_result->num_rows() > 0) {
                           foreach ($user_result->result() as $rows) {
                             $gcm_key=$rows->mobile_key;
                             $mobile_type=$rows->mobile_type;
                             $head='Skilex';
                             $message="Service Order expired.";
                             $user_type='3';
                            $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                           }
                       }
                      }
                    }
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
                }
                else{
                  foreach($res_sp_id as $rows_last_sp_id){}
                    // $last_sp_id=$rows_last_sp_id->last_id;
                  if($display_minute==1){
                  $last_sp_id=$rows_last_sp_id->last_id;
                  }else{
                     $checking_order_hist="SELECT * from service_order_history where service_order_id='$service_id' and status='Requested' order by id desc LIMIT 1";
                    $ex_checking_order_hist=$this->db->query($checking_order_hist);
                    $res_checking_hist=$ex_checking_order_hist->result();
                    foreach($res_checking_hist as $rows_checking_existory){}
                      $last_sp_id=$rows_checking_existory->serv_prov_id;


                  }

                  $next_id=$display_minute+$last_sp_id;

                //  if($display_minute==1){
                //   $limit="LIMIT 1";
                //  }else if($display_minute==2){
                //      $limit="LIMIT 1,1";
                //  }else if($display_minute==3){
                //   $limit="LIMIT 2,1";
                //  }else{
                //   $limit="LIMIT 0";
                //  }

                    $check_provider="SELECT spd.id,mobile_key, mobile_type,spd.user_master_id,owner_full_name,phone_no,vs.STATUS
                  FROM serv_prov_pers_skills AS spps
                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active' GROUP by spd.id";

                $res_chec_provider=$this->db->query($check_provider);
                  if($res_chec_provider->num_rows()==1){


                  $get_sp_id="SELECT spd.id,mobile_key, mobile_type,spd.user_master_id,owner_full_name,phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                  COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                  SIN( RADIANS( serv_lat ) ) ) ) AS distance,vs.status
                  FROM serv_prov_pers_skills AS spps
                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active' GROUP by spd.id";

                  }else{
                                  $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                  COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                  SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                  FROM serv_prov_pers_skills AS spps
                                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                  AND spd.user_master_id>$last_sp_id GROUP BY spps.user_master_id ASC
                                  UNION
                                  SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                                COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                                SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                  FROM serv_prov_pers_skills AS spps
                                  LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                  LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                  LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                  LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                  WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                  AND spd.user_master_id<$last_sp_id GROUP BY spps.user_master_id ASC) s_union";



                                    //  $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                    // COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                    // SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                    // FROM serv_prov_pers_skills AS spps
                                    // LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                    // LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                    // LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                    // LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                    // WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                    // AND spd.user_master_id>$last_sp_id GROUP BY spps.user_master_id ASC
                                    // UNION
                                    // SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                                    //               COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                                    //               SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                                    // FROM serv_prov_pers_skills AS spps
                                    // LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                                    // LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                                    // LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                                    // LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                                    // WHERE spps.main_cat_id='$selected_main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                                    // AND spd.user_master_id<$last_sp_id GROUP BY spps.user_master_id ASC) s_union";




                  }
                  $ex_next_id=$this->db->query($get_sp_id);
                  if($ex_next_id->num_rows()==0){
                    $response = array("status" => "error", "msg" => "Hitback","msg_en"=>"","msg_ta"=>"");
                  }else{
                    $res_next_ip=$ex_next_id->result();
                    foreach($res_next_ip as $rows_id_next){ }
                    $Phoneno=$rows_id_next->phone_no;
                    $full_name=$rows_id_next->owner_full_name;
                    $sp_user_master_id=$rows_id_next->user_master_id;

                    $title="Order";
                    $gcm_key=$rows_id_next->mobile_key;
                    $mobiletype=$rows_id_next->mobile_type;
                    $notes="Hi $full_name You Received order from Customer $contact_person_name";
                    $phone=$Phoneno;

                    $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$sp_user_master_id'";
                     $user_result = $this->db->query($sQuery);
                     if ($user_result->num_rows() > 0) {
                         foreach ($user_result->result() as $rows) {
                           $gcm_key=$rows->mobile_key;
                           $mobile_type=$rows->mobile_type;
                           $head='Skilex';
                           $message="Greetings from Skilex! You received an order from the Customer. Please look into the app for more details.";
                           $user_type='3';
                           $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                         }
                     }
                    $this->smsmodel->send_sms($phone,$notes);
                    $update_exper="UPDATE service_order_history SET status='Expired' WHERE status='Requested' AND service_order_id='$service_id' ORDER BY created_at desc LIMIT 1";
                    $res_expried=$this->db->query($update_exper);


                    $select_expired_user="SELECT * FROM service_order_history WHERE service_order_id='$service_id' AND status='Expired' ORDER BY created_at desc LIMIT 1";
                    $result_expired=$this->db->query($select_expired_user);
                    if($result_expired->num_rows()==0){

                    }else{
                      $result_exp=$result_expired->result();
                      foreach($result_exp as $rows_expired){
                      $serv_id=$rows_expired->serv_prov_id;
                      $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_id'";
                       $user_result = $this->db->query($sQuery);
                       if ($user_result->num_rows() > 0) {
                           foreach ($user_result->result() as $rows) {
                             $gcm_key=$rows->mobile_key;
                             $mobile_type=$rows->mobile_type;
                             $head='Skilex';
                             $message="Service Order expired.";
                             $user_type='3';
                             $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                           }
                       }
                      }
                    }
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
             //sleep(60);
               // return $response;
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
        WHERE so.status='Pending' AND customer_id='$user_master_id' AND (so.advance_payment_status='Y' or so.advance_payment_status='NA') ORDER BY so.id DESC";
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
      // $this->smsmodel->notification_test();
      // $get_gcm="SELECT * FROM notification_master WHERE user_master_id='$user_master_id' order by id desc";
      //  $res_gcm= $this->db->query($get_gcm);
      //  if($res_gcm->num_rows()==0){
      //  }else{
      //    $gcm_result=$res_gcm->result();
      //      foreach($gcm_result as $rows_gcm){
      //        $gcm_key=$rows_gcm->mobile_key;
      //        $mobile_type=$rows_gcm->mobile_type;
      //        $head='Skilex';
      //        $message='Ongoing checking';
      //        $user_type='5';
      //        $this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
      //      }
      //  }
       $service_query="SELECT so.status as order_status,so.resume_date,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,TIME_FORMAT(st.from_time,'%r') as from_time,TIME_FORMAT(st.to_time,'%r') as to_time,
      so.*,IFNULL(rs.from_time, '') as r_fr_time,IFNULL(rs.to_time, '') as r_to_time FROM service_orders  AS so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
        WHERE so.status!='Pending' AND so.status!='Completed'  AND so.status!='Rejected' AND so.status!='Paid' AND so.status!='Cancelled' AND customer_id='$user_master_id'
        ORDER BY so.id DESC";

      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"Services not found!","msg_ta"=>"சேவைகள் கிடைக்கவில்லை!");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
           $resume_time_slot=$rows_service->r_fr_time.'-'.$rows_service->r_to_time;
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
            "resume_time_slot"=>$resume_time_slot,
            "resume_date"=>$rows_service->resume_date,
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
      s.service_name,s.service_ta_name,TIME_FORMAT(st.from_time,'%r') as from_time,
        TIME_FORMAT(st.to_time,'%r') as to_time,so.*
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
      $service_query="SELECT so.status AS order_status,IFNULL(so.serv_pers_id,'') AS person_id,IFNULL(lu.phone_no,'') AS phone_no,IFNULL(spp.profile_pic,'') AS
profile_pic,IFNULL(spp.full_name,'') AS full_name,IFNULL(spd.owner_full_name,'') AS
owner_full_name,TIME_FORMAT(st.from_time,'%r') as from_time,
  TIME_FORMAT(st.to_time,'%r') as to_time,mc.main_cat_name,mc.main_cat_ta_name,
sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time,
(SELECT SUM( ad_service_rate_card) FROM service_order_additional AS soa
WHERE service_order_id='$service_order_id' ) AS ad_serv_rate,so.* FROM service_orders  AS so
LEFT JOIN services AS s ON s.id=so.service_id
LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
LEFT JOIN service_provider_details AS spd ON spd.user_master_id=so.serv_prov_id
LEFT JOIN service_person_details AS spp ON spp.user_master_id=so.serv_pers_id
LEFT JOIN login_users AS lu ON lu.id=so.serv_pers_id
 WHERE so.id='$service_order_id'";
      $res_service = $this->db->query($service_query);
      if($res_service->num_rows()==0){
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"","msg_ta"=>"");
      }else{
        $service_result=$res_service->result();
        foreach($service_result as $rows_service){  }
           $time_slot=$rows_service->from_time.'-'.$rows_service->to_time;
           $r_time_slot=$rows_service->r_fr_time.'-'.$rows_service->r_to_time;
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
            "resume_date"=>$rows_service->resume_date,
            "time_slot"=>$time_slot,
            "r_time_slot"=>$r_time_slot,
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

        $service_query="SELECT spa.travelling_allowance,IF(DATE_FORMAT(so.start_datetime,'%Y-%m-%d %r')='0000-00-00 12:00:00 AM', '',DATE_FORMAT(so.start_datetime,'%Y-%m-%d %r')) as start_time,IF(DATE_FORMAT(so.finish_datetime,'%Y-%m-%d %r')='0000-00-00 12:00:00 AM', '',DATE_FORMAT(so.finish_datetime,'%Y-%m-%d %r')) as finish_time,
        IFNULL(lu.phone_no,'') as phone_no,IFNULL(spp.full_name,'') AS full_name,IFNULL(spd.owner_full_name,'') AS owner_full_name,
        TIME_FORMAT(st.from_time,'%r') as from_time ,TIME_FORMAT(st.to_time,'%r') as to_time,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,
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
        $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"","msg_ta"=>"");
      }else{
        $service_result=$res_service->result();
          $rate=$this->get_distance_rate($service_order_id);
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
            "service_start_time"=>$rows_service->start_time,
            "resume_date"=>$rows_service->resume_date,
            "service_end_time"=>$rows_service->finish_time,
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
            "travelling_allowance"=>$rate,
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
         foreach($offer_result as $rows){
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


         }


          $response = array("status" => "success", "msg" => "Service Bill Found","service_bill"=>$service_bill,"msg_en"=>"","msg_ta"=>"");



       }
       return $response;

  }


  //----------------------Service order bills---------------//

//-------------------- Cancel  reason list   -------------------//



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
    //-------------------- Cancel  reason list   -------------------//


//-------------------- Cancel  Service order    -------------------//


        function cancel_service_order($user_master_id,$service_order_id,$cancel_id,$comments){

           $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
         $user_result = $this->db->query($sQuery);
         if($user_result->num_rows()>0)
         {
             foreach ($user_result->result() as $rows)
             {
               $gcm_key=$rows->mobile_key;
               $mobile_type=$rows->mobile_type;
               $preferred_lang_id=$rows->preferred_lang_id;
               $head='Skilex';
               if($preferred_lang_id=='1'){
               	$message="நன்றி உங்கள் ஆர்டர் ரத்து செய்யப்பட்டது";
               }else{
                 $message="Thank you.Your order has been Cancelled";
               }

               $user_type='5';
               $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
             }

             $notes=$message;
             $phone=$contact_person_number;
             $this->smsmodel->send_sms($phone,$notes);
         }

         $select="SELECT s.id,s.serv_prov_id,s.serv_pers_id,s.customer_id,s.status,lu.phone_no FROM  service_orders as s LEFT JOIN  login_users as lu on lu.id=s.customer_id WHERE s.id='$service_order_id' AND s.customer_id='$user_master_id'";
            $res_offer = $this->db->query($select);
            if($res_offer->num_rows()==0){
                $response = array("status" => "error", "msg" => "No  Service found","msg_en"=>"","msg_ta"=>"");
            }else{
              $offer_result = $res_offer->result();
              foreach($offer_result as $rows_service){ }
               $id=$rows_service->id;
               $serv_prov_id=$rows_service->serv_pers_id;
              $Phoneno=$rows_service->phone_no;
              $notes="Thank you.Your order has been Cancelled";
              $phone=$Phoneno;
              // $this->smsmodel->send_sms($phone,$notes);
              if($serv_prov_id=='0'){

              }else{
                $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$serv_prov_id."'";
                 $user_result = $this->db->query($sQuery);
                 if($user_result->num_rows()>0)
                 {
                     foreach ($user_result->result() as $rows)
                     {
                       $gcm_key=$rows->mobile_key;
                       $mobile_type=$rows->mobile_type;
                       $head='Skilex';
                       $message="Service order is cancelled.";
                       $user_type='3';
                       $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                     }
                 }
              }

              $insert="INSERT INTO cancel_history (cancel_master_id,user_master_id,service_order_id,comments,status,created_at,created_by) VALUES ('$cancel_id','$user_master_id','$service_order_id','$comments','Cancelled',NOW(),'$user_master_id')";
              $res_insert = $this->db->query($insert);

              $update_hist="UPDATE service_order_history SET status='Cancelled' WHERE status='Requested' and service_order_id='$id'";
              $res_update_hist = $this->db->query($update_hist);

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
                $response = array("status" => "error", "msg" => "No Service found","msg_en"=>"No Service found","msg_ta"=>"No Service found");
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
      $rate=$this->get_distance_rate($service_order_id);
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

                 $minimu_purchase_amt=$rows_coupon->minimum_purchase_amt;
                 if($total >= $minimu_purchase_amt){
                   $coupm_amt=($rows_coupon->offer_percent / 100) * $total;
                   $max_amt=$rows_coupon->max_offer_amount;
                   $dis_cp=$total-$coupm_amt;
                  if($coupm_amt > $max_amt){
                    $final_total=$total-$max_amt;
                    $payable=$final_total-$advance;
                    if($coupon_id=='0'){
                        $update="UPDATE service_payments SET net_service_amount='$final_total',payable_amount='$payable' WHERE service_order_id='$service_order_id'";
                    }else{
                        $update="UPDATE service_payments SET travelling_allowance='$rate',coupon_id='$coupon_id',discount_amt='$max_amt',net_service_amount='$final_total',payable_amount='$payable'+'$rate' WHERE service_order_id='$service_order_id'";
                    }
                  }else{
                    $final_total=$total-$coupm_amt;
                    $payable=$final_total-$advance;
                    if($coupon_id=='0'){
                        $update="UPDATE service_payments SET net_service_amount='$final_total',payable_amount='$payable' WHERE service_order_id='$service_order_id'";
                    }else{
                        $update="UPDATE service_payments SET travelling_allowance='$rate',coupon_id='$coupon_id',discount_amt='$coupm_amt',net_service_amount='$final_total',payable_amount='$payable'+'$rate' WHERE service_order_id='$service_order_id'";
                    }

                  }
                  	$update_result = $this->db->query($update);
                    if($update_result){
                      $response = array("status" => "success", "msg" => "You saved $rows_coupon->offer_percent","msg_en"=>"","msg_ta"=>"");
                    }else{
                      $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
                    }
                 }else{
                    	$response = array("status" => "error", "msg" => "You have minimum amount coupon cannot applicable","msg_en"=>"You have minimum amount coupon cannot applicable!","msg_ta"=>"தவறான குறியீடு!");
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
        $rate=$this->get_distance_rate($service_order_id);
      $query="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $res_query = $this->db->query($query);
      if($res_query->num_rows()!=0){
        $res_select=  $res_query->result();
        foreach($res_select as $rows_service){}
          $payment_id=$rows_service->id;
          $advance=$rows_service->paid_advance_amount;
          $total= $rows_service->total_service_amount;
          $payable=$total-$advance;
          $update="UPDATE service_payments SET coupon_id='0',discount_amt='0',net_service_amount='$total',travelling_allowance='$rate',payable_amount='$payable'+'$rate' WHERE service_order_id='$service_order_id' ";
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
      $rate=$this->get_distance_rate($service_order_id);
      $query="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $res_query = $this->db->query($query);
      if($res_query->num_rows()!=0){
          $result_service=  $res_query->result();
              foreach($result_service as $rows_service){}
                $payment_id=$rows_service->id;
                $wallet_amount=$rows_service->wallet_amount;
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
                $update="UPDATE service_payments SET net_service_amount='$net_service_amount',travelling_allowance='$rate',payable_amount='$payable'+'$rate',skilex_amount='$skilex_amount',service_provider_amount='$providrt_amt',sgst_amount='$gst_amount',cgst_amount='$gst_amount',skilex_tax_amount='$gst',serv_pro_net_amount='$providrt_amt',skilex_net_amount='$skile_net_amount',updated_at=NOW() WHERE service_order_id='$service_order_id'";
                	$update_result = $this->db->query($update);
                  if($update_result){
                    $tim=time();
                    $order_id=$tim.'-'.$user_master_id.'-'.$service_order_id.'-'.$payment_id;
                    $pay_details=array(
                      "order_id"=>$order_id,
                      "payable_amount"=>$payable+$rate,
                      "wallet_amount"=>floor($wallet_amount),
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


############### Distance calculation  ###############################3
    function get_distance_rate($service_order_id){
      $get_lat="SELECT * FROM service_orders where id='$service_order_id'";
      $res=$this->db->query($get_lat);
      foreach($res->result() as $rows_lat){}
      $result = explode(",", $rows_lat->service_latlon);
      $lat1=$result[0];
      $lon1= $result[1];

      $get_person="SELECT * FROM service_person_details where user_master_id='$rows_lat->serv_pers_id'";
      $res_person=$this->db->query($get_person);
      foreach($res_person->result() as $rows_person){}
      if(empty($rows_person->person_lat)){
        $get_vendor_status="SELECT * FROM vendor_status WHERE serv_pro_id='$rows_lat->serv_pers_id'";
        $res_vendor=$this->db->query($get_vendor_status);
        foreach($res_vendor->result() as $rows_vendor){}
          $lat2=$rows_vendor->serv_lat;
          $lon2=$rows_vendor->serv_lon;
      }else{
        $lat2=$rows_person->person_lat;
        $lon2=$rows_person->person_long;
      }

      $rad = M_PI / 180;
      $km=acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371;
      $dis= round($km,2);
      $get_rate="SELECT * FROM surge_master where surge_distance>='$dis' order by surge_distance asc LIMIT 1";
      $res_rate=$this->db->query($get_rate);
      if($res_rate->num_rows()==0){
        $rate='0';
      }else{
        foreach($res_rate->result() as $rows_rate){}
          $rate=$rows_rate->surge_price;
      }
      return $rate;
}



############### Distance calculation  ###############################3



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
            $response = array("status" => "success", "msg" => "Tracking found","track_info"=>$tracking_info,"msg_en"=>"","msg_ta"=>"");
           } else {
             $response = array("status" => "error", "msg" => "No Tracking found","msg_en"=>"","msg_ta"=>"");
           }

           return $response;


        }

//--------------------  Service Person Tracking  -------------------//


  ############### Pay Using Wallet  ################################

  function pay_using_wallet($user_master_id,$service_order_id){
      $query="SELECT * FROM user_wallet WHERE user_master_id='$user_master_id'";
      $result=$this->db->query($query);
      if($result->num_rows()==0){
            $wallet_amount='0';
            $response=array("status"=>"error","msg"=>"No balance","msg_en"=>"No balance","msg_ta"=>"No balance");
      }else{
          foreach($result->result() as $rows){}
            $wallet_amount=$rows->amt_in_wallet;
            if($wallet_amount=='0.00'){
              $response=array("status"=>"error","msg"=>"No balance","msg_en"=>"No balance","msg_ta"=>"No balance");
            }else{
            $get_payment="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
            $res_payment=$this->db->query($get_payment);
            foreach($res_payment->result() as $rows){}
             $payable_amt=$rows->payable_amount;


            if($payable_amt >= $wallet_amount){
              $detected_amt=$wallet_amount;
            $finalamount=$payable_amt-$wallet_amount;
            $payable_balance=$finalamount;
            $update_wallet="UPDATE user_wallet SET amt_in_wallet='0',total_amt_used=total_amt_used+'$wallet_amount',updated_at=NOW() where user_master_id='$user_master_id'";

            }else{

              $finalamount=$wallet_amount-$payable_amt;
               $wallet_balance=$finalamount;
                 $detected_amt=$payable_amt;
                $update_wallet="UPDATE user_wallet SET amt_in_wallet='$wallet_balance',total_amt_used=total_amt_used+'$detected_amt',updated_at=NOW() where user_master_id='$user_master_id'";
              }

            $res_update=$this->db->query($update_wallet);
            $ins_history="INSERT INTO wallet_history (user_master_id,transaction_amt,status,notes,created_at) VALUES ('$user_master_id','$detected_amt','Debited','Debited for Service',NOW())";
            $res=$this->db->query($ins_history);
            $update_service_payment="UPDATE service_payments SET wallet_amount='$detected_amt' where service_order_id='$service_order_id'";
            $res_payment=$this->db->query($update_service_payment);
            if($res_payment){
              $response=array("status"=>"success","msg"=>"Paid from wallet","msg_en"=>"Paid from wallet","msg_ta"=>"Paid from wallet");
            }else{
              $response=array("status"=>"error","msg"=>"Something went wrong","msg_en"=>"Something went wrong","msg_ta"=>"Something went wrong");
            }


            }
      }
      return $response;




  }
  ############### Pay Using Wallet  ################################

    ############### Un check Pay Using Wallet  ################################
    function uncheck_from_wallet($user_master_id,$service_order_id){

      $get_wallet_amount="SELECT * FROM service_payments WHERE service_order_id='$service_order_id'";
      $result=$this->db->query($get_wallet_amount);
      if($result->num_rows()==0){
          $response=array("status"=>"error","msg"=>"Something went wrong","msg_en"=>"Something went wrong","msg_ta"=>"Something went wrong");
      }else{
        foreach($result->result() as $rows_amt){}
          $service_wallet=$rows_amt->wallet_amount;
          $update="UPDATE service_payments SET wallet_amount='0' where service_order_id='$service_order_id'";
          $res_update=$this->db->query($update);

          $wallet="UPDATE user_wallet SET amt_in_wallet=amt_in_wallet+'$service_wallet',total_amt_used=total_amt_used-'$service_wallet' WHERE user_master_id='$user_master_id'";
          $res_wallet=$this->db->query($wallet);

          $ins_history="INSERT INTO wallet_history (user_master_id,transaction_amt,status,notes,created_at) VALUES ('$user_master_id','$service_wallet','Credited','Returned from service payment',NOW())";
          $res=$this->db->query($ins_history);
          if($res){
            $response=array("status"=>"success","msg"=>"Amount back to wallet","msg_ta"=>"Amount back to wallet","msg_en"=>"Amount back to wallet");
          }else{
            $response=array("status"=>"error","msg"=>"Something went wrong","msg_ta"=>"Something went wrong","msg_en"=>"Something went wrong");
          }

      }
      return $response;

    }

    ############### Un check Pay Using Wallet  ################################



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


//-------------------- Paid on wallet  -------------------//

    function paid_on_wallet($user_master_id,$service_order_id){

      $select="SELECT * FROM service_orders as so WHERE so.id='$service_order_id'";
      $res = $this->db->query($select);
      if($res->num_rows()==1){
        $result = $res->result();
        foreach($result as $rows){}
        $update="UPDATE service_orders SET status='Paid' WHERE id='$service_order_id'";
        $res_update = $this->db->query($update);
        $update_pay="UPDATE service_payments SET status='Paid' WHERE service_order_id='$service_order_id'";
        $res_pay = $this->db->query($update_pay);
        // $insert="INSERT INTO service_payment_history (service_order_id,service_payment_id,payment_type,notes,status,created_at,created_by) VALUES ('$service_id','$payment_id','Offline','Netamount','Success',NOW(),'$user_master_id')";
        $insert="INSERT INTO service_payment_history (service_order_id,payment_type,notes,status,created_at,created_by) VALUES ('$service_order_id','Online','Netamount','Success',NOW(),'$user_master_id')";
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
    //-------------------- Paid on wallet  -------------------//




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


//-------------------- Service Payment success -------------------//

  function service_payment_success($service_order_id){
    $sQuery = "SELECT * FROM service_orders WHERE id ='".$service_order_id."'";
    $user_result = $this->db->query($sQuery);
    if($user_result->num_rows()>0)
    {
        foreach ($user_result->result() as $rows)
        {
          $customer_id = $rows->customer_id;
          $contact_person_name = $rows->contact_person_name;
          $contact_person_number = $rows->contact_person_number;
          $serv_prov_id=$rows->serv_prov_id;
          $serv_pers_id=$rows->serv_pers_id;
        }
    }


        $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
        $user_result = $this->db->query($sQuery);
        if($user_result->num_rows()>0)
        {
            foreach ($user_result->result() as $rows)
            {
              $gcm_key=$rows->mobile_key;
              $mobile_type=$rows->mobile_type;
              $preferred_lang_id=$rows->preferred_lang_id;
                $head='Skilex';
              if($preferred_lang_id=='1'){
                $message="ஸ்கிலெக்ஸ் ரசீதுக்கு பணம் பெறப்பட்டது.தங்களது சர்வீஸ் கோரிக்கை   நிறைவடைந்தது.";
              }else{
                $message="Service payment success.";
              }
              $user_type='3';
              $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
            }

            $notes=$message;
            $phone=$rows->phone_no;
            $this->smsmodel->send_sms($phone,$notes);
        }


        $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";
        $user_result = $this->db->query($sQuery);
        if($user_result->num_rows()>0)
        {
            foreach ($user_result->result() as $rows)
            {
              $gcm_key=$rows->mobile_key;
              $mobile_type=$rows->mobile_type;
              $preferred_lang_id=$rows->preferred_lang_id;
              $head='Skilex';
              if($preferred_lang_id=='1'){
                $message="ஸ்கிலெக்ஸ் ரசீதுக்கு பணம் பெறப்பட்டது.தங்களது சர்வீஸ் கோரிக்கை நிறைவடைந்தது.எங்கள் சேவையை மதிப்பிடுங்கள்.";
              }else{
                $message=" Service Payment Success Thanks for being the part of Skilex. Kindly rate our Service";
              }

              $user_type='5';
              $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
            }
            $notes=$message;
            $phone=$rows->phone_no;
            $this->smsmodel->send_sms($phone,$notes);

        }
        $sQuery="SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_pers_id'";
        $user_result = $this->db->query($sQuery);
        if($user_result->num_rows()>0)
        {
            foreach ($user_result->result() as $rows)
            {
              $gcm_key=$rows->mobile_key;
              $mobile_type=$rows->mobile_type;
              $preferred_lang_id=$rows->preferred_lang_id;
              $head='Skilex';
              if($preferred_lang_id=='1'){
                $message="ஸ்கிலெக்ஸ் ரசீதுக்கு பணம் பெறப்பட்டது.தங்களது சர்வீஸ் கோரிக்கை   நிறைவடைந்தது.";
              }else{
                $message=" Service Payment Success";
              }
              $user_type='4';
              $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
            }
            $notes=$message;
            $phone=$rows->phone_no;
            $this->smsmodel->send_sms($phone,$notes);
        }
  }
  //-------------------- Service Payment success -------------------//



  function check_every_minute($user_master_id,$service_order_id){


        $insert="INSERT INTO serv_pers_tracking (user_master_id,created_at,service_order_id) VALUES('$user_master_id',NOW(),'$service_order_id')";
       $user_result = $this->db->query($insert);

  }


  ############ Customer feedback question ####################

      function customer_feedback_question($user_master_id){
        $query="SELECT * FROM feedback_master WHERE status='Active' and user_type='5'";
        $res=$this->db->query($query);
        if($res->num_rows()==0){
              $response = array("status" => "error", "msg" => "No feedback question found","msg_en"=>"No feedback question found","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
        }else{
          foreach($res->result() as $rows){
            $data[]=array(
              "id"=>$rows->id,
              "feedback_question"=>$rows->question,
              "answer_option"=>$rows->answer_option
            );

          }
          $response=array("status"=>"success","msg"=>"Feedback questions found","feedback_question"=>$data,"msg_en"=>"","msg_ta"=>"");
        }

         return $response;

      }

    ############ Customer feedback question ####################

    ############ Customer feedback answer ####################

    function customer_feedback_answer($user_master_id,$service_order_id,$feedback_id,$feedback_text){

      $query="SELECT * FROM feedback_response WHERE service_order_id='$service_order_id' and query_id='$feedback_id'  and user_master_id='$user_master_id'";
      $res=$this->db->query($query);
      if($res->num_rows()==0){
        $insert="INSERT INTO feedback_response  (user_master_id,service_order_id,query_id,answer_text,status,created_at,created_by) VALUES ('$user_master_id','$service_order_id','$feedback_id','$feedback_text','Active',NOW(),'$user_master_id')";
        $result=$this->db->query($insert);
        if($result){
          $response=array("status"=>"success","msg"=>"Feedback added successfully","msg_en"=>"","msg_ta"=>"");
        }else{
            $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
        }
      }else{
        $update="UPDATE feedback_response SET answer_text='$feedback_text',created_at=NOW() WHERE service_order_id='$service_order_id' and query_id='$feedback_id' and user_master_id='$user_master_id'";
        $result=$this->db->query($update);
        if($result){
          $response=array("status"=>"success","msg"=>"Feedback added successfully","msg_en"=>"","msg_ta"=>"");
        }else{
            $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
        }
            // $response = array("status" => "error", "msg" => "Something went wrong","msg_en"=>"Oops! Something went wrong!","msg_ta"=>"எதோ தவறு நடந்துள்ளது!");
      }
      return $response;

    }
    ############ Customer feedback answer ####################



    function automatic_provider_allocation(){

      $select="SELECT * FROM service_orders WHERE (DATE(order_date) = CURDATE() - 1 or DATE(order_date) >= CURDATE()) and status='Pending' and (advance_payment_status='N' OR advance_payment_status='NA')";
      $excute= $this->db->query($select);
      if($excute->num_rows()==0){

      }else{
        $result=$excute->result();
        foreach($result as $rows_order){
         $service_order_id=$rows_order->id;
         $user_master_id=$rows_order->customer_id;
         $main_cat_id=$rows_order->main_cat_id;
         $service_latlon=$rows_order->service_latlon;
         $contact_person_name=$rows_order->contact_person_name;
         $contact_person_number=$rows_order->contact_person_number;
         $result = explode(",", $service_latlon);
         $lat=$result[0];
         $long= $result[1];
         $advance_check=$rows_order->advance_payment_status;

          $get_last_provider_id="SELECT spd.id as last_id,so.* FROM service_orders as so left join service_provider_details as spd on spd.user_master_id=so.serv_prov_id where so.serv_prov_id!=0  and (so.status='Paid' OR so.status='Completed') ORDER BY so.id desc LIMIT 1";
          $ex_last_provider_id=$this->db->query($get_last_provider_id);
          if($ex_last_provider_id->num_rows()==0){
            echo "No Last Provider";
          }else{
            $result_last_provider_id=$ex_last_provider_id->result();
            foreach($result_last_provider_id as $row_last_provider_id){}
            $last_provider_id=$row_last_provider_id->serv_prov_id;
            $get_provider_count="SELECT count(*) as prov_count from login_users as lu
            left join vendor_status  as vs on vs.serv_pro_id=lu.id
            left JOIN serv_prov_pers_skills as spps on spps.user_master_id=lu.id
            where lu.status='Active' and vs.online_status='Online' and lu.user_type=3 and lu.document_verify='Y' and spps.main_cat_id='$main_cat_id'";
            $result_cnt = $this->db->query($get_provider_count);
            foreach($result_cnt->result() as $cnt_provider){}
              if($cnt_provider->prov_count==0){

              }else{
             $cnt=$cnt_provider->prov_count;
             $check_order_history="SELECT * FROM service_order_history where service_order_id='$service_order_id' and (status='Requested' OR status='Expired' or status='Cancelled') order by id desc LIMIT 1";
              $check_ex_order_history=$this->db->query($check_order_history);
              if($check_ex_order_history->num_rows()==0){

                $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
               COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
               SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
               FROM serv_prov_pers_skills AS spps
               LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
               LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
               LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
               LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
               WHERE spps.main_cat_id='$main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
               AND spd.user_master_id>$last_provider_id GROUP BY spps.user_master_id ASC
               UNION
               SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                             COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                             SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
               FROM serv_prov_pers_skills AS spps
               LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
               LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
               LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
               LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
               WHERE spps.main_cat_id='$main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
               AND spd.user_master_id<$last_provider_id GROUP BY spps.user_master_id ASC) s_union";
               $ex_next_id=$this->db->query($get_sp_id);
               if($ex_next_id->num_rows()==0){

               }else{
                 foreach($ex_next_id->result() as $rows_provider_first){}
                   $first_provider=$rows_provider_first->user_master_id;

                   $check_exist_history="SELECT * FROM service_order_history where serv_prov_id='$first_provider' AND service_order_id='$service_order_id'";
                   $ex_check_exist_history=$this->db->query($check_exist_history);
                   if($ex_check_exist_history->num_rows()==0){

                      $set_expire="UPDATE service_order_history SET status='Expired' WHERE service_order_id='$service_order_id'";
                      $ex_set_expire=$this->db->query($set_expire);

                      $insert_service_history="INSERT INTO service_order_history (serv_prov_id,service_order_id,status,created_at) VALUES('$first_provider','$service_order_id','Requested',NOW())";
                      $exc=$this->db->query($insert_service_history);

                      // $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$first_provider'";
                      $sQuery      = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$first_provider'";

                         $user_result = $this->db->query($sQuery);
                         if ($user_result->num_rows() > 0) {
                             foreach ($user_result->result() as $rows) {
                               $gcm_key=$rows->mobile_key;
                               $mobile_type=$rows->mobile_type;
                               $preferred_lang_id=$rows->preferred_lang_id;
                               $head='Skilex';
                               if($preferred_lang_id=='1'){
                                 $message="You have received order from customer.";
                                }else{
                                 $message="You have received order from customer.";
                               }

                               $user_type='3';
                               $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                             }
                             $notes=$message;
                             $phone=$rows->phone_no;
                             $this->smsmodel->send_sms($phone,$notes);
                         }

                   }else{

                   }
               }

              }else{
                $result_order_history=$check_ex_order_history->result();
                foreach($result_order_history as $rows_order_history){
                  // echo $rows_order_history->id;
                  $last_sp_id=$rows_order_history->serv_prov_id;

                 $get_sp_id="SELECT * FROM (SELECT spd.id AS id , ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                 COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                 SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                 FROM serv_prov_pers_skills AS spps
                 LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                 LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                 LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                 LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                 WHERE spps.main_cat_id='$main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                 AND spd.user_master_id>$last_sp_id GROUP BY spps.user_master_id ASC
                 UNION
                 SELECT spd.id AS id, ns.mobile_key AS mobile_key, ns.mobile_type AS mobile_type, spps.user_master_id AS user_master_id, spd.owner_full_name AS owner_full_name, lu.phone_no AS phone_no,( 3959 * ACOS( COS( RADIANS('$lat') ) * COS( RADIANS( serv_lat ) ) *
                               COS( RADIANS( serv_lon ) - RADIANS('$long') ) + SIN( RADIANS('$lat') ) *
                               SIN( RADIANS( serv_lat ) ) ) ) AS distance, vs.status AS STATUS
                 FROM serv_prov_pers_skills AS spps
                 LEFT JOIN service_provider_details AS spd ON spd.user_master_id=spps.user_master_id
                 LEFT JOIN login_users AS lu ON lu.id=spd.user_master_id
                 LEFT JOIN vendor_status AS vs ON vs.serv_pro_id=lu.id
                 LEFT JOIN notification_master AS ns ON ns.user_master_id=lu.id
                 WHERE spps.main_cat_id='$main_cat_id' AND spps.status='Active' AND vs.online_status='Online' AND lu.status='Active'
                 AND spd.user_master_id<$last_sp_id GROUP BY spps.user_master_id ASC) s_union";
                 $ex_next_id=$this->db->query($get_sp_id);
                 foreach($ex_next_id->result() as $rows_provider){}
                 $selected_provider=$rows_provider->user_master_id;

                 $check_exist_history="SELECT * FROM service_order_history where serv_prov_id='$selected_provider' AND service_order_id='$service_order_id'";
                 $ex_check_exist_history=$this->db->query($check_exist_history);
                 if($ex_check_exist_history->num_rows()==0){

                   $set_expire="UPDATE service_order_history SET status='Expired' WHERE service_order_id='$service_order_id'";
                   $ex_set_expire=$this->db->query($set_expire);

                   $insert_service_history="INSERT INTO service_order_history (serv_prov_id,service_order_id,status,created_at) VALUES('$selected_provider','$service_order_id','Requested',NOW())";
                   $exc=$this->db->query($insert_service_history);

                 // $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$selected_provider'";
                      $sQuery      = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$selected_provider'";
                    $user_result = $this->db->query($sQuery);
                    if ($user_result->num_rows() > 0) {
                        foreach ($user_result->result() as $rows) {
                          $gcm_key=$rows->mobile_key;
                          $mobile_type=$rows->mobile_type;
                          $preferred_lang_id=$rows->preferred_lang_id;
                          $head='Skilex';
                          if($preferred_lang_id=='1'){
                            $message="You have received order from customer.";
                           }else{
                            $message="You have received order from customer.";
                          }
                          $user_type='3';
                          $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                        }
                        $notes=$message;
                        $phone=$rows->phone_no;
                        $this->smsmodel->send_sms($phone,$notes);

                    }

                 }else{
                   echo "exists";
                 }




                  }

              }
            }
          }
        }
      }

    }




    function hour_cron_job_checking(){
		$date = date_default_timezone_set('Asia/Kolkata');

		$current_time = date("h:i a");
		$start_time = "9:00 am";
		$end_time = "7:00 pm";
		$current = DateTime::createFromFormat('h:i a', $current_time);
		$start = DateTime::createFromFormat('h:i a', $start_time);
		$end = DateTime::createFromFormat('h:i a', $end_time);
		
		if ($current > $start && $current < $end)
		{
		   $this->automatic_provider_allocation();
		} 
	  
	  /* $date = date_default_timezone_set('Asia/Kolkata');
      $today = date("g:i");
      $ten_am='09:00';
      $end_time='7:00';
      if($today >= $ten_am && $today <= $end_time) {
        // $insert="INSERT INTO serv_pers_tracking(created_at) VALUES (NOW())";
        // $excute=$this->db->query($insert);
          $this->automatic_provider_allocation();
      } */
	  
    }


     function check_sms(){

      $notes="ஸ்கிலெக்ஸ் ரசீதுக்கு பணம் பெறப்பட்டது.தங்களது சர்வீஸ் கோரிக்கை நிறைவடைந்தது.";
      $phone='9789108819';
      $this->smsmodel->send_sms($phone,$notes);
    }


    function db_data_updating(){
		$text='SKILEXC0';
		$select="SELECT * FROM login_users where user_type='5'";
		$result=$this->db->query($select);
		foreach($result->result() as $rows){
		  $update="UPDATE login_users SET referral_code='$text$rows->id' WHERE id='$rows->id' and  user_type='5'";
		  $excute=$this->db->query($update);
		}
    }
	
	
############### Customer address add ###############
  function customer_address_add($cust_id,$contact_name,$contact_no,$serv_lat_lon,$serv_loc,$serv_address){
	
	$sql = "SELECT * FROM customer_address WHERE customer_id ='".$cust_id."'";
	$address_result = $this->db->query($sql);
	if($address_result->num_rows()<2) {
		$insert_sql = "INSERT INTO customer_address (customer_id, contact_name, contact_no, serv_lat_lon, serv_loc, serv_address, created_at) VALUES 
		('".$cust_id."','".$contact_name."','".$contact_no."','".$serv_lat_lon."','".$serv_loc."','".$serv_address."',NOW())";
		$insert_result = $this->db->query($insert_sql);
		
		$response=array("status"=>"success","msg_en"=>"Address Added successfully","msg_ta"=>"Address Added successfully");
    }else{
		$response = array("status" => "error","msg_en"=>"Already added!","msg_ta"=>"Already added");
    }
      return $response;
  }
############### Customer address end ###############


############### Customer address list ###############
  function customer_address_list($cust_id){
	
	$sql = "SELECT * FROM customer_address WHERE customer_id ='".$cust_id."'";
	$address_result = $this->db->query($sql);
	$ress = $address_result->result();
	if($address_result->num_rows()>0) {
		$response=array("status"=>"success","address_list"=>$ress);
    }else{
		$response = array("status" => "error","msg_en"=>"Address Not found!","msg_ta"=>"Address Not found!");
    }
      return $response;
  }
############### Customer address end ###############


############### Customer address edit ###############
  function customer_address_edit($address_id,$contact_name,$contact_no,$serv_lat_lon,$serv_loc,$serv_address){
	
	$update="UPDATE customer_address SET contact_name='$contact_name',contact_no='$contact_no',serv_lat_lon='$serv_lat_lon',serv_loc='$serv_loc',serv_address='$serv_address', updated_at=NOW() WHERE id='$address_id'";
	$excute=$this->db->query($update);

	$response=array("status"=>"success","msg_en"=>"Address Updated successfully","msg_ta"=>"Address Updated successfully");
    return $response;
  }
############### Customer address end ###############



}

?>
