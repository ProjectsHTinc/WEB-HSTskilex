<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apispersonmodel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this
            ->load
            ->model('smsmodel');
    }

    //#################### Email ####################//
    public function sendMail($email, $subject, $email_message)
    {
        // Set content-type header for sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // Additional headers
        $headers .= 'From: Webmaster<info@skilex.in>' . "\r\n";
        mail($email, $subject, $email_message, $headers);
    }
    //#################### Email End ####################//
    
    //-------------------- Version check -------------------//
    function version_check($version_code)
    {
        if ($version_code >= 3)
        {
            $response = array(
                "status" => "success"
            );
        }
        else
        {
            $response = array(
                "status" => "error"
            );
        }
        return $response;
    }
    //-------------------- Version check -------------------//
    
    //#################### Dashboard ####################//
    public function Dashboard($user_master_id)
    {
        $assigned_count = "SELECT * FROM service_orders WHERE serv_pers_id = '" . $user_master_id . "' AND status = 'Assigned'";
        $assigned_count_res = $this
            ->db
            ->query($assigned_count);
        $assigned_orders_count = $assigned_count_res->num_rows();

        $ongoing_count = "SELECT * FROM service_orders WHERE serv_pers_id = '" . $user_master_id . "' AND (status = 'Initiated' OR status = 'Started' OR status = 'Ongoing' OR status = 'Hold')";
        $ongoing_count_res = $this
            ->db
            ->query($ongoing_count);
        $ongoing_orders_count = $ongoing_count_res->num_rows();

        $dashboardData = array(
            "serv_assigned_count" => $assigned_orders_count,
            "serv_ongoing_count" => $ongoing_orders_count,
        );
        $response = array(
            "status" => "success",
            "msg" => "Dashboard Datas",
            "dashboardData" => $dashboardData
        );
        return $response;
    }
    //#################### Dashboard End ####################//

    //#################### Mobile Check ####################//
    public function Mobile_check($phone_no)
    {
        $sql = "SELECT * FROM login_users WHERE phone_no ='" . $phone_no . "' AND user_type = '4' AND status='Active'";
        $user_result = $this
            ->db
            ->query($sql);
        $ress = $user_result->result();

        $digits = 4;
        $OTP = str_pad(rand(0, pow(10, $digits) - 1) , $digits, '0', STR_PAD_LEFT);

        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $user_master_id = $rows->id;
                $preferred_lang_id = $rows->preferred_lang_id;
            }

            $update_sql = "UPDATE login_users SET otp = '" . $OTP . "', updated_at=NOW() WHERE id ='" . $user_master_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            if ($preferred_lang_id == '1')
            {
                $msg = "Your SkilEx Verification code is: ".$OTP."  OSFrgSQC1Mb";
                $templateid = '1707161725077245705';
            }
            else
            {
                $msg = "Your SkilEx Verification code is: ".$OTP."  OSFrgSQC1Mb";
                $templateid = '1707161725077245705';
            }

            $notes = $msg;
            $phone = $phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            $response = array(
                "status" => "success",
                "msg" => "Mobile OTP",
                "user_master_id" => $user_master_id,
                "phone_no" => $phone_no,
                "otp" => $OTP
            );

        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Invalid login"
            );
        }

        return $response;
    }
    //#################### Mobile Check End ####################//
    
    //#################### Login ####################//
    public function Login($user_master_id, $phone_no, $otp, $device_token, $mobiletype)
    {
        $sql = "SELECT * FROM login_users WHERE phone_no = '" . $phone_no . "' AND otp = '" . $otp . "' AND user_type = '4' AND status='Active'";
        $sql_result = $this
            ->db
            ->query($sql);

        if ($sql_result->num_rows() > 0)
        {
            $update_sql = "UPDATE login_users SET mobile_verify ='Y' WHERE id='$user_master_id'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $gcmQuery = "SELECT * FROM notification_master WHERE mobile_key like '%" . $device_token . "%' AND user_master_id = '" . $user_master_id . "' LIMIT 1";
            $gcm_result = $this
                ->db
                ->query($gcmQuery);
            $gcm_ress = $gcm_result->result();
            if ($gcm_result->num_rows() == 0)
            {
                $sQuery = "INSERT INTO notification_master (user_master_id,mobile_key,mobile_type,created_at) VALUES ('" . $user_master_id . "','" . $device_token . "','" . $mobiletype . "',NOW())";
                $update_gcm = $this
                    ->db
                    ->query($sQuery);
            }

            $user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.user_type, B.full_name, B.gender, B.profile_pic, B.address FROM login_users A, service_person_details B WHERE A.id = B.user_master_id AND A.id = '" . $user_master_id . "'";
            $user_result = $this
                ->db
                ->query($user_sql);
            if ($user_result->num_rows() > 0)
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
                    if ($profile_pic != '')
                    {
                        $profile_pic_url = base_url() . 'assets/persons/' . $profile_pic;
                    }
                    else
                    {
                        $profile_pic_url = "";
                    }
                    $address = $rows->address;
                    $user_type = $rows->user_type;
                }
            }

            $userData = array(
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

            $response = array(
                "status" => "success",
                "msg" => "Login Successfully",
                "userData" => $userData
            );
            return $response;
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Invalid login"
            );
            return $response;
        }
    }
    //#################### Main Login End ####################//
    
    //#################### Email Verify status ####################//
    public function Email_verifystatus($user_master_id)
    {
        $sql = "SELECT * FROM login_users WHERE id ='" . $user_master_id . "' AND user_type = '5' AND status='Active'";
        $user_result = $this
            ->db
            ->query($sql);
        $ress = $user_result->result();

        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $email_verify = $rows->email_verify;
            }
        }
        $response = array(
            "status" => "success",
            "msg" => "Email Verify Status",
            "user_master_id" => $user_master_id,
            "email_verify_satus" => $email_verify
        );
        return $response;
    }
    //#################### Email Verify status End ####################//

    //#################### Email Verify status ####################//
    public function Email_verification($user_master_id)
    {
        $sql = "SELECT * FROM login_users WHERE id ='" . $user_master_id . "' AND user_type = '5' AND status='Active'";
        $user_result = $this
            ->db
            ->query($sql);
        $ress = $user_result->result();

        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $email_id = $rows->email;
            }
        }
        $enc_user_master_id = base64_encode($user_master_id);

        $subject = "SKILEX - Verification Email";
        $email_message = 'Please Click the Verification link. <a href="' . base_url() . 'home/email_verfication/' . $enc_user_master_id . '" target="_blank" style="background-color: #478ECC; font-size:15px; font-weight: bold; padding: 10px; text-decoration: none; color: #fff; border-radius: 5px;">Verify Your Email</a><br><br><br>';
        $this->sendMail($email_id, $subject, $email_message);

        $response = array(
            "status" => "success",
            "msg" => "Email Verification Sent"
        );
        return $response;
    }
    //#################### Email Verify status End ####################//
    
    //#################### Profile Update ####################//
    public function Profile_update($user_master_id, $full_name, $gender, $email)
    {
        $update_sql = "UPDATE service_person_details SET full_name='$full_name',gender='$gender', updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $update = "UPDATE login_users SET email='$email' WHERE id='$user_master_id'";
        $ex_update = $this
            ->db
            ->query($update);

        $response = array(
            "status" => "success",
            "msg" => "Profile Updated"
        );
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
    public function Profile_pic_upload($user_master_id, $profileFileName)
    {
        $update_sql = "UPDATE service_person_details SET profile_pic='$profileFileName' WHERE user_master_id='$user_master_id'";
        $update_result = $this
            ->db
            ->query($update_sql);
        $picture_url = base_url() . 'assets/persons/' . $profileFileName;

        $response = array(
            "status" => "success",
            "msg" => "Profile Picture Updated",
            "picture_url" => $picture_url
        );
        return $response;
    }
    //#################### Profile Pic Update End ####################//

    function user_info($user_master_id)
    {
        $select = "SELECT * FROM login_users as lu LEFT JOIN service_person_details as cd ON lu.id=cd.user_master_id WHERE lu.id='$user_master_id'";
        $res = $this
            ->db
            ->query($select);
        if ($res->num_rows() == 1)
        {
            foreach ($res->result() as $rows)
            {
            }
            $profile = $rows->profile_pic;
            if (empty($profile))
            {
                $pic = "";
            }
            else
            {
                $pic = base_url() . 'assets/persons/' . $profile;
            }
            $user_info = array(
                "phone_no" => $rows->phone_no,
                "email" => $rows->email,
                "full_name" => $rows->full_name,
                "gender" => $rows->gender,
                "profile_pic" => $pic,
            );
            $response = array(
                "status" => "success",
                "msg" => "User information",
                "user_details" => $user_info,
                "msg" => "",
                "msg_ta" => ""
            );

        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "No User information found",
                "msg" => "User details not found!",
                "msg_ta" => "பயனர் விபரங்கள் கிடைக்கவில்லை!"
            );
        }
        return $response;
    }

    #################### Expert Digital ID Card ####################//
    function digital_id_card($user_master_id)
    {
        $query = "SELECT spd.profile_pic,spd.full_name,DATE_FORMAT(spd.created_at ,'%d-%m-%Y') as joining_date,spd.user_master_id,lu.phone_no FROM service_person_details as spd
      left join login_users as lu on lu.id=spd.user_master_id where spd.user_master_id='$user_master_id'";
        $res = $this
            ->db
            ->query($query);
        if ($res->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "No User information found",
                "msg" => "User details not found!",
                "msg_ta" => "பயனர் விபரங்கள் கிடைக்கவில்லை!"
            );

        }
        else
        {
            foreach ($res->result() as $rows)
            {
            }
            $service_get = "SELECT spps.main_cat_id,mc.main_cat_name,mc.main_cat_ta_name from serv_prov_pers_skills as spps
          left join main_category as mc  on mc.id=spps.main_cat_id
          where spps.user_master_id='$user_master_id' LIMIT 2";
            $res_service = $this
                ->db
                ->query($service_get);
            if ($res_service->num_rows() == 0)
            {
                $service_return = array(
                    "status" => "error"
                );
            }
            else
            {
                foreach ($res_service->result() as $rows_service)
                {
                    $service_array[] = array(
                        "id" => $rows_service->main_cat_id,
                        "main_cat_name" => $rows_service->main_cat_name,
                        "main_cat_ta_name" => $rows_service->main_cat_ta_name
                    );
                }
                $service_return = array(
                    "status" => "success",
                    "service_list" => $service_array
                );
            }

            $profile = $rows->profile_pic;
            if (empty($profile))
            {
                $pic = "";
            }
            else
            {
                $pic = base_url() . 'assets/persons/' . $profile;
            }
            $result_data = array(
                "id" => $rows->user_master_id,
                "full_name" => $rows->full_name,
                "joining_date" => $rows->joining_date,
                "phone_no" => $rows->phone_no,
                "profile_pic" => $pic
            );
            $response = array(
                "status" => "success",
                "msg_en" => "Information found",
                "msg_ta" => "Information found",
                "result" => $result_data,
                "service_data" => $service_return
            );
        }
        return $response;
    }
    #################### Expert Digital ID Card ####################//
    
    ############ Expert feedback question ####################
    function expert_feedback_question($user_master_id)
    {
        $query = "SELECT * FROM feedback_master WHERE status='Active' and user_type='4'";
        $res = $this
            ->db
            ->query($query);
        if ($res->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "No feedback question found",
                "msg_en" => "No feedback question found",
                "msg_ta" => "எதோ தவறு நடந்துள்ளது!"
            );
        }
        else
        {
            foreach ($res->result() as $rows)
            {
                $data[] = array(
                    "id" => $rows->id,
                    "feedback_question" => $rows->question,
                    "answer_option" => $rows->answer_option
                );

            }
            $response = array(
                "status" => "success",
                "msg" => "Feedback questions found",
                "feedback_question" => $data,
                "msg_en" => "",
                "msg_ta" => ""
            );
        }

        return $response;

    }
    ############ Expert feedback question ####################

    ############ Customer feedback answer ####################
    function expert_feedback_answer($user_master_id, $service_order_id, $feedback_id, $feedback_text)
    {

        $query = "SELECT * FROM feedback_response WHERE service_order_id='$service_order_id' and query_id='$feedback_id' and user_master_id='$user_master_id'";
        $res = $this
            ->db
            ->query($query);

        if ($res->num_rows() == 0)
        {
            $insert = "INSERT INTO feedback_response  (user_master_id,service_order_id,query_id,answer_text,status,created_at,created_by) VALUES ('$user_master_id','$service_order_id','$feedback_id','$feedback_text','Active',NOW(),'$user_master_id')";
            $result = $this
                ->db
                ->query($insert);
            if ($result)
            {
                $response = array(
                    "status" => "success",
                    "msg" => "Feedback added successfully",
                    "msg_en" => "",
                    "msg_ta" => ""
                );
            }
            else
            {
                $response = array(
                    "status" => "error",
                    "msg" => "Something went wrong",
                    "msg_en" => "Oops! Something went wrong!",
                    "msg_ta" => "எதோ தவறு நடந்துள்ளது!"
                );
            }
        }
        else
        {
            $update = "UPDATE feedback_response SET answer_text='$feedback_text',created_at=NOW() WHERE service_order_id='$service_order_id' and query_id='$feedback_id' and user_master_id='$user_master_id'";
            $result = $this
                ->db
                ->query($update);
            if ($result)
            {
                $response = array(
                    "status" => "success",
                    "msg" => "Feedback added successfully",
                    "msg_en" => "",
                    "msg_ta" => ""
                );
            }
            else
            {
                $response = array(
                    "status" => "error",
                    "msg" => "Something went wrong",
                    "msg_en" => "Oops! Something went wrong!",
                    "msg_ta" => "எதோ தவறு நடந்துள்ளது!"
                );
            }
        }
        return $response;
    }
    ############ Customer feedback answer ####################

    //#################### List Aassigned services ####################//
    public function List_assigned_services($user_master_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.serv_pers_id = '" . $user_master_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_prov_id = F.user_master_id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "list_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### List Aassigned services End ####################//
    
    //#################### Detailed Assigned services ####################//
    public function Detail_assigned_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.serv_pers_id,
					A.service_location,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id = '" . $user_master_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id
           AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "detail_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### Assigned detailed services End ####################//
    
    //#################### Initiate services ####################//
    public function Initiate_services($user_master_id, $service_order_id)
    {
        $update_sql = "UPDATE service_orders SET status = 'Initiated', iniate_datetime =NOW() ,updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Initiated'";
        $res_select = $this
            ->db
            ->query($select);
        if ($res_select->num_rows() == 0)
        {
            $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Initiated',NOW(),'" . $user_master_id . "')";
            $ins_query = $this
                ->db
                ->query($sQuery);
        }
        else
        {

        }

        $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $customer_id = $rows->customer_id;
                $contact_person_name = $rows->contact_person_name;
                $contact_person_number = $rows->contact_person_number;
                $serv_prov_id = $rows->serv_prov_id;
            }
        }

        // $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$serv_prov_id."'";
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை ஆரம்பிக்கப்பட்டது..";
                    $templateid = '1707161433329355562';
                }
                else
                {
                    $message = "Skilex - Service Expert initiated";
                    $templateid = '1707161518690954551';

                }

                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை ஆரம்பிக்கப்பட்டது.";
                    $templateid = '1707161433329355562';
                }
                else
                {
                    $message = "Skilex - Service Expert initiated";
                    $templateid = '1707161518686209153';
                }
                $user_type = '4';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        // $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$customer_id."'";
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";

        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சேவை கோரிக்கை தொடங்கப்பட்டது. சேவை நிபுணரைக் கண்காணிப்பதற்கான ஆப்பில்பார்க்கவும்";
                    $templateid = '1707161433314072224';
                }
                else
                {
                    $message = "SKILEX - Service request initiated. Please look into the app for tracking the Service expert.";
                    $templateid = '1707161518668055531';
                }
                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $contact_person_number;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        //$title = "Service Request Initiated";
        

        $response = array(
            "status" => "success",
            "msg" => "Service Order Initiated"
        );
        return $response;
    }
    //#################### Initiat services End ####################//
    

    function from_hold_to_ongoing($user_master_id, $service_order_id)
    {
        $update_sql = "UPDATE service_orders SET status = 'Ongoing', iniate_datetime =NOW() ,updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Ongoing'";
        $res_select = $this
            ->db
            ->query($select);
        if ($res_select->num_rows() == 0)
        {
            $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Ongoing',NOW(),'" . $user_master_id . "')";
            $ins_query = $this
                ->db
                ->query($sQuery);
        }
        else
        {

        }

        $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $customer_id = $rows->customer_id;
                $contact_person_name = $rows->contact_person_name;
                $contact_person_number = $rows->contact_person_number;
                $serv_prov_id = $rows->serv_prov_id;
            }
        }

        // $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$serv_prov_id."'";
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";

        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சேவை மீண்டும் தொடங்கப்பட்டுள்ளது.";
                    $templateid = '1707161433591835313';
                }
                else
                {
                    $message = "Skilex-Service is has restarted.";
                    $templateid = '1707161518674086035';
                }

                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";

        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சேவை மீண்டும் தொடங்கப்பட்டுள்ளது.";
                    $templateid = '1707161433591835313';
                }
                else
                {
                    $message = "Skilex-Service is has restarted.";
                    $templateid = '1707161518674086035';
                }

                $user_type = '4';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";

        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சேவை மீண்டும் தொடங்கப்பட்டுள்ளது.";
                    $templateid = '1707161433591835313';
                }
                else
                {
                    $message = "Skilex-Service is has restarted.";
                    $templateid = '1707161518674086035';
                }
                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $contact_person_number;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $response = array(
            "status" => "success",
            "msg" => "Service Order Ongoing"
        );
        return $response;
    }

    ########### List Ongoing services ####################//
    public function List_ongoing_services($user_master_id)
    {
        // $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='".$user_master_id."'";
        // $user_result = $this->db->query($sQuery);
        // if($user_result->num_rows()>0)
        // {
        //     foreach ($user_result->result() as $rows)
        //     {
        //       $gcm_key=$rows->mobile_key;
        //       $mobile_type=$rows->mobile_type;
        //       $head='Skilex';
        //       $message="Notification checking";
        //       $user_type='4';
        //       $this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
        //     }
        // }
        $sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
					A.status,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					TIME_FORMAT(E.from_time,'%r') as from_time,
          TIME_FORMAT(E.to_time,'%r') as to_time,
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.serv_pers_id = '$user_master_id'
           AND (A.status = 'Initiated' OR A.status = 'Started' OR A.status = 'Ongoing' OR A.status = 'Hold')
           AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id
           AND A.serv_pers_id = F.user_master_id order by A.id desc";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "list_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### List Ongoing services End ####################//

    ########### Detailed Initiated  services ####################//
    public function Detail_initiated_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_location,
					A.service_address,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id = '" . $user_master_id . "'
           AND A.status = 'Initiated' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "detail_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### Detailed Initiated  services End ####################//

    ########### Initiated detailed services ####################//
    public function Service_process($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_location,
					A.service_address,
					A.service_latlon,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id = '" . $user_master_id . "' AND (A.status = 'Started' OR A.status = 'Initiated') AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id
           AND A.serv_prov_id = F.user_master_id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "detail_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### Initiated detailed services End ####################//

    //#################### Request otp ####################//
    public function Request_otp($user_master_id, $service_order_id)
    {
        $sql = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "' AND serv_pers_id = '" . $user_master_id . "'";
        $user_result = $this
            ->db
            ->query($sql);

        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $contact_person_number = $rows->contact_person_number;
            }

            $digits = 4;
            $OTP = str_pad(rand(0, pow(10, $digits) - 1) , $digits, '0', STR_PAD_LEFT);

            $update_sql = "UPDATE service_orders SET service_otp = '" . $OTP . "', updated_at=NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $update_sql = "UPDATE service_orders SET status = 'Started', start_datetime =NOW() ,updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Started'";
            $res_select = $this
                ->db
                ->query($select);
            if ($res_select->num_rows() == 0)
            {
                $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Started',NOW(),'" . $user_master_id . "')";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }
            $message_details = "Your SkilEx Verification code is :". $OTP.".";
            $templateid = '1707161432164819940';

            $notes = $message_details;
            $phone = $contact_person_number;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            $response = array(
                "status" => "success",
                "msg" => "OTP send"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Request otp End ####################//

    //#################### Start services ####################//
    public function Start_services($user_master_id, $service_order_id, $service_otp)
    {
        $sql = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "' AND serv_pers_id = '" . $user_master_id . "' AND service_otp = '" . $service_otp . "'";
        $user_result = $this
            ->db
            ->query($sql);

        if ($user_result->num_rows() > 0)
        {
            $update_sql = "UPDATE service_orders SET status = 'Ongoing', start_datetime =NOW() ,updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Ongoing'";
            $res_select = $this
                ->db
                ->query($select);
            if ($res_select->num_rows() == 0)
            {
                $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Ongoing',NOW(),'" . $user_master_id . "')";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }

            $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $customer_id = $rows->customer_id;
                    $contact_person_name = $rows->contact_person_name;
                    $contact_person_number = $rows->contact_person_number;
                    $serv_prov_id = $rows->serv_prov_id;
                }
            }

            $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $gcm_key = $rows->mobile_key;
                    $mobile_type = $rows->mobile_type;
                    $preferred_lang_id = $rows->preferred_lang_id;
                    $head = 'Skilex';
                    if ($preferred_lang_id == '1')
                    {
                        $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை தொடர்ந்து செல்கிறது.";
                        $templateid = '1707161433591835313';
                    }
                    else
                    {
                        $message = "Skilex - The service request is Ongoing.";
                        $templateid = '1707161518674086035';
                    }
                    $user_type = '3';
                    $this
                        ->smsmodel
                        ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
                }
                $notes = $message;
                $phone = $rows->phone_no;

                $this
                    ->smsmodel
                    ->send_sms($phone, $notes, $templateid);
                //$this->smsmodel->send_sms($phone,$notes);
                
            }

            $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$user_master_id'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $gcm_key = $rows->mobile_key;
                    $mobile_type = $rows->mobile_type;
                    $preferred_lang_id = $rows->preferred_lang_id;
                    $head = 'Skilex';
                    if ($preferred_lang_id == '1')
                    {
                        $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை ஆரம்பிக்கப்பட்டது";
                        $templateid = '1707161433329355562';
                    }
                    else
                    {
                        $message = "Skilex - Service request started.";
                        $templateid = '1707161518666252098';
                    }

                    $user_type = '4';
                    $this
                        ->smsmodel
                        ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
                }
                $notes = $message;
                $phone = $rows->phone_no;
                $this
                    ->smsmodel
                    ->send_sms($phone, $notes, $templateid);
                //$this->smsmodel->send_sms($phone,$notes);
                
            }

            $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $gcm_key = $rows->mobile_key;
                    $mobile_type = $rows->mobile_type;
                    $preferred_lang_id = $rows->preferred_lang_id;
                    $head = 'Skilex';
                    if ($preferred_lang_id == '1')
                    {
                        $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை ஆரம்பிக்கப்பட்டது.";
                        $templateid = '1707161433329355562';
                    }
                    else
                    {
                        $message = "Skilex - Service request started.";
                        $templateid = '1707161518666252098';
                    }

                    $user_type = '5';
                    $this
                        ->smsmodel
                        ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
                }

                $notes = $message;
                $phone = $contact_person_number;
                $this
                    ->smsmodel
                    ->send_sms($phone, $notes, $templateid);
                //$this->smsmodel->send_sms($phone,$notes);
                
            }

            $response = array(
                "status" => "success",
                "msg" => "Service Started"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Start services End ####################//

    //#################### Detailed Ongoing  services ####################//
    public function Detail_ongoing_services($user_master_id, $service_order_id)
    {

        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') as order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') as resume_date,
    so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,DATE_FORMAT(so.start_datetime, '%d-%m-%Y %h:%s') as start_datetime,so.material_notes,so.serv_prov_id,spd.full_name as service_person,IFNULL(rs.from_time, '') as r_fr_time,IFNULL(rs.to_time, '') as r_to_time
    from service_orders as so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    where so.serv_pers_id='$user_master_id' and so.id='$service_order_id' and (so.status='Hold' or so.status='Ongoing' Or so.status='Started' Or so.status='Initiate')";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        $addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '" . $service_order_id . "' AND status = 'Active'";
        $addtional_serv_res = $this
            ->db
            ->query($addtional_serv);
        $addtional_serv_count = $addtional_serv_res->num_rows();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "detail_services_order" => $service_result,
                "addtional_services_count" => $addtional_serv_count
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
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
					A.user_master_id = '" . $user_master_id . "' AND A.main_cat_id = B.id AND A.status = 'Active'";
        $ser_result = $this
            ->db
            ->query($sQuery);

        $category_result = $ser_result->result();
        $category_count = $ser_result->num_rows();

        if ($ser_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Category list",
                "category_count" => $category_count,
                "category_list" => $category_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Services Not Found"
            );
        }

        return $response;
    }
    //#################### Person Category list End ####################//
    
    //#################### Sub Category list ####################//
    public function Sub_category_list($category_id)
    {
        $sQuery = "SELECT * FROM sub_category WHERE main_cat_id = '$category_id' AND status='Active'";
        $cat_result = $this
            ->db
            ->query($sQuery);

        $category_result = $cat_result->result();
        $category_count = $cat_result->num_rows();

        if ($cat_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Sub Category list",
                "sub_category_count" => $category_count,
                "sub_category_list" => $category_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Category Not Found"
            );
        }

        return $response;
    }
    //#################### Sub Category list End ####################//
    
    //#################### Services list ####################//
    public function Services_list($category_id, $sub_category_id)
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
        $ser_result = $this
            ->db
            ->query($sQuery);

        $services_result = $ser_result->result();
        $services_count = $ser_result->num_rows();

        if ($ser_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Services list",
                "service_count" => $services_count,
                "service_list" => $services_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Services Not Found"
            );
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
    	A.user_master_id = '".$user_master_id."' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.status = 'Active'";
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
    
    //#################### Add Extra service ####################//
    function add_extra_services($user_master_id, $service_order_id)
    {

        $select = "SELECT * FROM service_orders  WHERE id='$service_order_id'";
        $select_query = $this
            ->db
            ->query($select);
        if ($select_query->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }
        else
        {
            $result = $select_query->result();
            foreach ($result as $rows)
            {
            }
            $main_cat_id = $rows->main_cat_id;
            $query = "SELECT *  FROM services WHERE main_cat_id='$main_cat_id' and status='Active'";
            $res = $this
                ->db
                ->query($query);
            if ($res->num_rows() > 0)
            {
                foreach ($res->result() as $rows)
                {
                    $service_pic = $rows->service_pic;
                    if ($service_pic != '')
                    {
                        $service_pic_url = base_url() . 'assets/category/' . $service_pic;
                    }
                    else
                    {
                        $service_pic_url = '';
                    }
                    $subcatData[] = array(
                        "service_id" => $rows->id,
                        "main_cat_id" => $rows->main_cat_id,
                        "sub_cat_id" => $rows->sub_cat_id,
                        "service_name" => $rows->service_name,
                        "service_ta_name" => $rows->service_ta_name,
                        "service_pic_url" => $service_pic_url,
                        "rate_card" => $rows->rate_card,
                        "selected" => "0",
                    );
                }
                $response = array(
                    "status" => "success",
                    "msg" => "View Services",
                    "services" => $subcatData,
                    "msg_en" => "",
                    "msg_ta" => ""
                );

            }
            else
            {
                $response = array(
                    "status" => "error",
                    "msg" => "Services not found",
                    "msg_en" => "Services not found!",
                    "msg_ta" => "சேவைகள் கிடைக்கவில்லை!"
                );
            }

        }

        return $response;

    }
    //#################### Add Extra service  ####################//

    //#################### Add addtional Services ####################//
    function Add_addtional_services($user_master_id, $service_order_id, $service_id, $ad_service_rate_card)
    {
        $sQuery = "INSERT INTO service_order_additional (service_order_id,service_id,ad_service_rate_card,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $service_id . "','" . $ad_service_rate_card . "','Active',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        if ($ins_query)
        {
            $response = array(
                "status" => "success",
                "msg" => "Services Added Sucessfully!.."
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Add addtional Services End ####################//

    //#################### Remove addtional Services End ####################//
    function remove_addtional_services($user_master_id, $service_order_id, $service_id)
    {
        $sQuery = "DELETE  FROM service_order_additional WHERE service_order_id='$service_order_id' and service_id='$service_id'  ORDER BY created_at desc  LIMIT 1";
        $ins_query = $this
            ->db
            ->query($sQuery);

        if ($ins_query)
        {
            $response = array(
                "status" => "success",
                "msg" => "Services Removed Sucessfully!.."
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }
        return $response;
    }
    //#################### Remove addtional Services End ####################//
    
    //#################### Additional service list orders ####################//
    public function List_addtional_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
						A.id,
						A.ad_service_rate_card,
						B.service_name,
            B.service_pic,
						B.service_ta_name,
            C.main_cat_name,
						B.main_cat_id,
						C.main_cat_ta_name,
            B.sub_cat_id,
            B.rate_card,
						D.sub_cat_name,
						D.sub_cat_ta_name
					FROM
						service_order_additional A,
						services B,
						main_category C,
						sub_category D
					WHERE
						A.service_order_id = '" . $service_order_id . "' AND A.service_id = B.id AND B.main_cat_id = C.id AND B.sub_cat_id = D.id";
        $serv_result = $this
            ->db
            ->query($sQuery);

        $service_count = $serv_result->num_rows();

        if ($serv_result->num_rows() > 0)
        {
            $service_result = $serv_result->result();
            foreach ($service_result as $rows)
            {
                $service_pic = $rows->service_pic;
                if ($service_pic != '')
                {
                    $service_pic_url = base_url() . 'assets/category/' . $service_pic;
                }
                else
                {
                    $service_pic_url = '';
                }
                $service_list_result[] = array(
                    "id" => $rows->id,
                    "main_cat_id" => $rows->main_cat_id,
                    "sub_cat_id" => $rows->sub_cat_id,
                    "service_name" => $rows->service_name,
                    "service_ta_name" => $rows->service_ta_name,
                    "service_pic_url" => $service_pic_url,
                    "ad_service_rate_card" => $rows->rate_card,
                );
            }

            $response = array(
                "status" => "success",
                "msg" => "Addtional Service list",
                "service_count" => $service_count,
                "service_list" => $service_list_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Services Not Found"
            );
        }

        return $response;
    }
    //#################### Additional service orders End ####################//
    
    //#################### Remove Additional service remove ####################//
    public function list_remove_addtional_services($user_master_id, $order_additional_id)
    {
        $sQuery = "DELETE FROM service_order_additional WHERE id = '" . $order_additional_id . "'";
        $serv_result = $this
            ->db
            ->query($sQuery);

        if ($serv_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Additional Service Removed"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Services Not Found"
            );
        }

        return $response;
    }
    //#################### Remove Additional service remove End ####################//

    //#################### Upload service bills ####################//
    public function Upload_service_bills($user_master_id, $service_order_id, $documentFileName)
    {
        $sQuery = "INSERT INTO service_order_bills(service_order_id,serv_pers_id,file_name,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','" . $documentFileName . "',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);
        $last_insert_id = $this
            ->db
            ->insert_id();
        $document_url = base_url() . 'assets/bills/' . $documentFileName;

        $response = array(
            "status" => "success",
            "msg" => "Service Bill Uploaded"
        );
        return $response;
    }
    //#################### Upload service bills End ####################//

    //#################### Service bills list ####################//
    public function List_service_bills($user_master_id, $service_order_id)
    {
        $bill_url = base_url() . 'assets/bills/';

        $sQuery = "SELECT * FROM service_order_bills WHERE service_order_id = '" . $service_order_id . "' AND serv_pers_id ='" . $user_master_id . "'";
        $doc_result = $this
            ->db
            ->query($sQuery);

        if ($doc_result->num_rows() != 0)
        {
            foreach ($doc_result->result() as $rows)
            {
                $id = $rows->id;
                $bill_copy = $rows->file_name;

                $data[] = array(
                    "id" => $id,
                    "bill_copy" => $bill_copy,
                    "bill_copy_url" => $bill_url . $bill_copy
                );
            }
            $response = array(
                "status" => "success",
                "msg" => "Bill list",
                "bill_copy_result" => $data
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Bills Not Found"
            );
        }
        return $response;

    }
    //#################### Service bills list End ####################//

    //#################### Update ongoing services ####################//
    public function Update_ongoing_services($user_master_id, $service_order_id, $material_notes)
    {
        $update_sql = "UPDATE service_orders SET material_notes = '" . $material_notes . "', updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $customer_id = $rows->customer_id;
                $contact_person_name = $rows->contact_person_name;
                $contact_person_number = $rows->contact_person_number;
                $provider_id = $rows->serv_prov_id;
            }
        }

        $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='$customer_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $head = 'Skilex';
                $message = "Your service order is updated.";
                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
        }
        $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='$provider_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $head = 'Skilex';
                $message = "Service order is updated.";
                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
        }

        if ($update_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order Updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Additional service remove End ####################//
    
    //#################### Onhold service ####################//
    function onhold_services($user_master_id, $service_order_id, $resume_date, $resume_timeslot, $status)
    {

        $update_sql = "UPDATE service_orders SET status = '$status',resume_date='$resume_date',resume_timeslot='$resume_timeslot', updated_by  = '$user_master_id', updated_at =NOW() WHERE id ='$service_order_id'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='$status'";
        $res_select = $this
            ->db
            ->query($select);
        if ($res_select->num_rows() == 0)
        {
            $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('$service_order_id','$user_master_id','$status',NOW(),'$user_master_id ')";
            $ins_query = $this
                ->db
                ->query($sQuery);
        }

        $get_prov = "SELECT sppd.id,lu.id,lu.phone_no,lu.preferred_lang_id from service_person_details as sppd
      left join service_provider_details as spd on spd.id=sppd.service_provider_id
      left join login_users as lu on lu.id=sppd.service_provider_id
      where sppd.user_master_id='$user_master_id'";
        $get_res = $this
            ->db
            ->query($get_prov);
        foreach ($get_res->result() as $row_res)
        {
        }
        $Phoneno = $row_res->phone_no;
        $preferred_lang_id = $row_res->preferred_lang_id;
        if ($preferred_lang_id == '1')
        {
            $notes = "ஸ்கிலெக்ஸ்-சேவை நிறுத்தி வைக்கப்பட்டுள்ளது";
            $templateid = '1707161433640095471';
        }
        else
        {
            $notes = "Skilex-Service is On hold";
            $templateid = '1707161518684046346';
        }

        $phone = $Phoneno;
        $this
            ->smsmodel
            ->send_sms($phone, $notes, $templateid);
        //$this->smsmodel->send_sms($phone,$notes);
        

        $select = "SELECT * FROM service_orders where id='$service_order_id'";
        $select_res = $this
            ->db
            ->query($select);
        foreach ($select_res->result() as $sel_row)
        {
        }
        $Phoneno = $sel_row->contact_person_number;
        $customer_id = $sel_row->customer_id;
        $serv_prov_id = $sel_row->serv_prov_id;
        $resume = date("d-m-Y", strtotime($resume_date));
        // $notes="Your Service is hold now will resume on ".$resume;
        $phone = $Phoneno;
        //$this->smsmodel->send_sms($phone,$notes);
        

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் சேவை இப்போது நிறுத்தப்பட்டுள்ளது $resume மீண்டும் தொடங்கும்";
                    $templateid = '1707161433578925667';
                }
                else
                {
                    $message = "Service is hold now will resume on " . $resume;
                    $templateid = '1707161518688384162';
                }

                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ் உங்கள் சேவை இப்போது நிறுத்தப்பட்டுள்ளது.$resume.மீண்டும் தொடங்கும";
                    $templateid = '1707161433578925667';
                }
                else
                {
                    $message = "Your Service is hold now will resume on " . $resume;
                    $templateid = '1707161518688384162';
                }

                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        if ($update_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order Status Updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Onhold service ####################//
    
    //-------------------- Time slot -------------------//
    function view_time_slot($user_master_id)
    {
        $query = "SELECT id,DATE_FORMAT(from_time, '%h:%i %p') as from_time,DATE_FORMAT(to_time, '%h:%i %p') as to_time  FROM service_timeslot  WHERE  status='Active'";
        $res = $this
            ->db
            ->query($query);
        if ($res->num_rows() > 0)
        {
            $order_list = $res->result();
            foreach ($order_list as $rows)
            {
                $time_slot = $rows->from_time . '-' . $rows->to_time;
                $view_time_slot[] = array(
                    'timeslot_id' => $rows->id,
                    'time_range' => $time_slot
                );
            }
            $response = array(
                "status" => "success",
                "msg" => "View Timeslot",
                "service_time_slot" => $view_time_slot,
                "msg_en" => "",
                "msg_ta" => ""
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service timeslot not found",
                "msg_en" => "Service time not found!",
                "msg_ta" => "சேவை நேரம் கிடைக்கவில்லை!"
            );
        }

        return $response;
    }
    //-------------------- Time slot -------------------//

    //#################### Cancel service Resons ####################//
    public function Cancel_service_reasons($user_type)
    {
        $sQuery = "SELECT id, reasons FROM cancel_master WHERE user_type ='" . $user_type . "'";
        $res_result = $this
            ->db
            ->query($sQuery);
        $reason_result = $res_result->result();

        if ($res_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Cancel Service Reasons",
                "list_reasons" => $reason_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Reasons Not found"
            );
        }
        return $response;
    }
    //#################### Cancel service Resons End ####################//

    //#################### Cancel services ####################//
    public function Cancel_services($user_master_id, $service_order_id, $cancel_master_id, $comments)
    {
        $update_sql = "UPDATE service_orders SET status = 'Cancelled', updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Cancelled'";
        $res_select = $this
            ->db
            ->query($select);
        if ($res_select->num_rows() == 0)
        {
            $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Cancelled',NOW(),'" . $user_master_id . "')";
            $ins_query = $this
                ->db
                ->query($sQuery);
        }

        $sQuery = "INSERT INTO cancel_history (cancel_master_id,user_master_id,service_order_id,comments,created_at,created_by) VALUES ('" . $cancel_master_id . "','" . $user_master_id . "','" . $service_order_id . "','" . $comments . "',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $customer_id = $rows->customer_id;
                $contact_person_name = $rows->contact_person_name;
                $contact_person_number = $rows->contact_person_number;
                $serv_prov_id = $rows->serv_prov_id;
            }
        }

        // $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_prov_id'";
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ்-உங்கள் சேவை கோரிக்கை ரத்து செய்யப்பட்டது";
                    $templateid = '1707161518659574142';
                }
                else
                {
                    $message = "Skilex-Your service request has been cancelled";
                    $templateid = '1707161518664219488';
                }

                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        $sQuery = "SELECT * FROM notification_master WHERE user_master_id ='$customer_id'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $gcm_key = $rows->mobile_key;
                $mobile_type = $rows->mobile_type;
                $preferred_lang_id = $rows->preferred_lang_id;
                $head = 'Skilex';
                if ($preferred_lang_id == '1')
                {
                    $message = "ஸ்கிலெக்ஸ்-உங்கள் சேவை கோரிக்கை ரத்து செய்யப்பட்டது. இதனால் ஏற்பட்ட சிரமத்திற்கு வருந்துகிறோம். மற்றொரு சேவை நபர் விரைவில் நியமிக்கப்படுவார்.";
                    $templateid = '1707161518650286082';
                }
                else
                {
                    $message = "Skilex-Your service request has been cancelled. We regret for the inconvenience caused. Another service person will be assigned shortly.";
                    $templateid = '1707161518677936626';
                }
                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $notes = $message;
            $phone = $rows->phone_no;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            
        }

        if ($update_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Cancel Services"
            );
        }
        else
        {
            $response = array(
                "status" => "error"
            );
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
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.serv_pers_id = '" . $user_master_id . "' AND A.status = 'Cancelled' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "list_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### List canceled services End ####################//

    //#################### Detail canceled services ####################//
    public function Detail_canceled_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id = '" . $user_master_id . "' AND A.status = 'Cancelled' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        $reason_query = "SELECT
						A.id,C.reasons,A.comments,B.id as cancel_user_id,D.id as role_id,D.role_name
					FROM
						cancel_history A,
						login_users B,
						cancel_master C,
						user_role D
					WHERE
						A.service_order_id = '" . $service_order_id . "' AND A.user_master_id = B.id AND B.id AND A.cancel_master_id = C.id AND B.user_type = D.id";
        $reason_res = $this
            ->db
            ->query($reason_query);
        $reason_result = $reason_res->result();
        if ($reason_res->num_rows() > 0)
        {
            foreach ($reason_res->result() as $rows)
            {
                $role_id = $rows->role_id;
                $cancel_user_id = $rows->cancel_user_id;
            }
        }

        if ($role_id == '3')
        {
            $usrQuery = "SELECT owner_full_name AS name FROM service_provider_details WHERE user_master_id = '" . $cancel_user_id . "' LIMIT 1";
        }
        else if ($role_id == '4')
        {
            $usrQuery = "SELECT full_name AS name FROM service_person_details WHERE user_master_id = '" . $cancel_user_id . "' LIMIT 1";
        }
        else
        {
            $usrQuery = "SELECT full_name AS name FROM customer_details WHERE user_master_id = '" . $cancel_user_id . "' LIMIT 1";
        }
        $usr_ress = $this
            ->db
            ->query($usrQuery);
        $usr_result = $usr_ress->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order Details",
                "detail_services_order" => $service_result,
                "cancel_reason" => $reason_result,
                "canceld_by" => $usr_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order Not found"
            );
        }
        return $response;
    }
    //#################### Detail canceled services End ####################//

    //#################### Complete services ####################//
    public function Complete_services($user_master_id, $service_order_id)
    {

        $sQuery = "SELECT * FROM service_orders WHERE id = '" . $service_order_id . "'";

        $query_res = $this
            ->db
            ->query($sQuery);

        if ($query_res->num_rows() > 0)
        {
            foreach ($query_res->result() as $rows)
            {
                $advance_payment_status = $rows->advance_payment_status;
                $advance_amount_paid = $rows->advance_amount_paid;
                $service_rate_card = $rows->service_rate_card;
            }

            $sQuery = "SELECT SUM(ad_service_rate_card) AS add_service_amount FROM service_order_additional WHERE service_order_id = '" . $service_order_id . "'";
            $query_res = $this
                ->db
                ->query($sQuery);
            if ($query_res->num_rows() > 0)
            {
                foreach ($query_res->result() as $rows)
                {
                    $add_service_amount = $rows->add_service_amount;
                }
            }
            else
            {
                $add_service_amount = '0.00';
            }

            $total_amount = $service_rate_card + $add_service_amount;

            $sQuery = "SELECT * FROM service_payments WHERE service_order_id = '" . $service_order_id . "'";
            $query_res = $this
                ->db
                ->query($sQuery);
            if ($query_res->num_rows() > 0)
            {
                $sQuery = "UPDATE service_payments SET service_amount ='" . $service_rate_card . "', ad_service_amount='" . $add_service_amount . "',total_service_amount  ='" . $total_amount . "', net_service_amount = '" . $total_amount . "', status = 'Pending',  updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE service_order_id ='" . $service_order_id . "'";

                $update_result = $this
                    ->db
                    ->query($sQuery);

            }
            else
            {

                $sQuery = "INSERT INTO service_payments (service_order_id,service_amount,ad_service_amount,total_service_amount,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $service_rate_card . "','" . $add_service_amount . "','" . $total_amount . "','Pending',NOW(),'" . $user_master_id . "')";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }

            $sQuery = "UPDATE service_orders SET status = 'Completed', finish_datetime =NOW(), updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($sQuery);

            $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Completed'";
            $res_select = $this
                ->db
                ->query($select);
            if ($res_select->num_rows() == 0)
            {
                $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Completed',NOW(),'" . $user_master_id . "')";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }

            $sQuery = "SELECT * FROM service_orders WHERE id ='" . $service_order_id . "'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $customer_id = $rows->customer_id;
                    $contact_person_name = $rows->contact_person_name;
                    $contact_person_number = $rows->contact_person_number;
                    $serv_prov_id = $rows->serv_prov_id;
                }
            }

            // $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_prov_id'";
            $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$serv_prov_id'";
            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $gcm_key = $rows->mobile_key;
                    $mobile_type = $rows->mobile_type;
                    $head = 'Skilex';
                    $preferred_lang_id = $rows->preferred_lang_id;
                    $head = 'Skilex';
                    if ($preferred_lang_id == '1')
                    {
                        $message = "ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை  நிறைவடைந்தது.";
                        $templateid = '1707161433338125655';
                    }
                    else
                    {
                        $message = "Skilex- Service Request Completed";
                        $templateid = '1707161518675860544';
                    }

                    $user_type = '3';
                    $this
                        ->smsmodel
                        ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
                }
                $notes = $message;
                $phone = $rows->phone_no;
                $this
                    ->smsmodel
                    ->send_sms($phone, $notes, $templateid);
                //$this->smsmodel->send_sms($phone,$notes);
                
            }
            $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$customer_id'";

            $user_result = $this
                ->db
                ->query($sQuery);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $gcm_key = $rows->mobile_key;
                    $mobile_type = $rows->mobile_type;
                    $preferred_lang_id = $rows->preferred_lang_id;
                    $head = 'Skilex';
                    if ($preferred_lang_id == '1')
                    {
                        $message = "ஸ்கிலெக்ஸ் சேவை கோரிக்கை முடிந்தது. பில் உருவாக்கப்பட்டது ஸ்கிலெக்ஸ் ஆப் மூலம் கட்டணத்தை செலுத்துங்கள்.";
                        $templateid = '1707161433582953263';
                    }
                    else
                    {
                        $message = "SKILEX - Service Request Completed. Bill Generated. Kindly pay the bill through Skilex App.";
                        $templateid = '1707161432984923904';
                    }

                    $user_type = '5';
                    $this
                        ->smsmodel
                        ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
                }
                $notes = $message;
                $phone = $contact_person_number;
                $this
                    ->smsmodel
                    ->send_sms($phone, $notes, $templateid);
                //$this->smsmodel->send_sms($phone,$notes);
                
            }

            $response = array(
                "status" => "success",
                "msg" => "Completed Services"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Wrong"
            );
        }

        return $response;
    }
    //#################### Complete services End ####################//

    //#################### List completed services ####################//
    public function List_completed_services($user_master_id)
    {

        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') as order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') as resume_date,sppd.owner_full_name as service_provider,
sp.status as Payment_status,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,TIME_FORMAT(st.from_time,'%r') as from_time,
  TIME_FORMAT(st.to_time,'%r') as to_time,so.status,DATE_FORMAT(so.start_datetime, '%d-%m-%Y %h:%s') as start_datetime,so.material_notes,so.serv_prov_id,spd.full_name as service_person,IFNULL(rs.from_time, '') as r_fr_time,IFNULL(rs.to_time, '') as r_to_time
    from service_orders as so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details as sppd on so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments as sp on sp.service_order_id=so.id
    where so.serv_pers_id='$user_master_id'  and (so.status='Completed' or so.status='Paid' or so.status='Cancelled') order by so.id desc";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "list_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### List completed services End ####################//

    //#################### Detail completed services ####################//
    public function Detail_completed_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e %m %Y') AS order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') AS resume_date,sppd.owner_full_name AS service_provider,
sp.status AS Payment_status,DATE_FORMAT(so.finish_datetime, '%d-%m-%Y %h:%s') as finish_datetime,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,TIME_FORMAT(st.from_time,'%r') as from_time,
  TIME_FORMAT(st.to_time,'%r') as to_time,so.status,DATE_FORMAT(so.start_datetime, '%d-%m-%Y %h:%s') as start_datetime,so.material_notes,so.serv_prov_id,spd.full_name AS service_person,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time
    FROM service_orders AS so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details AS sppd ON so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments AS sp ON sp.service_order_id=so.id
    WHERE so.serv_pers_id='$user_master_id' AND so.id='$service_order_id'  AND (so.status='Completed' OR so.status='Paid' OR so.status='Cancelled')";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        $addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '" . $service_order_id . "' AND status = 'Active'";
        $addtional_serv_res = $this
            ->db
            ->query($addtional_serv);
        $addtional_serv_count = $addtional_serv_res->num_rows();

        $trans_query = "SELECT * FROM service_payments WHERE service_order_id = '" . $service_order_id . "'";
        $trans_res = $this
            ->db
            ->query($trans_query);
        $trans_result = $trans_res->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order List",
                "detail_services_order" => $service_result,
                "addtional_services_count" => $addtional_serv_count,
                "transaction_details" => $trans_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order List Not found"
            );
        }
        return $response;
    }
    //#################### Detail completed services End ####################//
    
    //#################### Service Person Tracking ####################//
    public function Add_tracking($user_master_id, $latitude, $longitude, $location, $miles, $service_order_id, $location_datetime)
    {
        $dt = strtotime($location_datetime); //make timestamp with datetime string
        $chk_date = date("Y-m-d", $dt); //echo the year of the datestamp just created
        $user_query = "SELECT * FROM serv_pers_tracking WHERE user_master_id = '$user_master_id' AND date(created_at) = '$chk_date' ORDER BY id DESC LIMIT 1";
        $user_result = $this
            ->db
            ->query($user_query);
        $user_res = $user_result->result();

        if ($user_result->num_rows() > 0)
        {
            foreach ($user_res as $rows)
            {
                $to_latitude = $rows->to_lat;
                $to_longitude = $rows->to_long;
            }

            $location_query = "INSERT INTO serv_pers_tracking (user_master_id,user_lat,user_long,user_location,to_lat,to_long,miles,created_at,service_order_id) VALUES ('$user_master_id','$to_latitude','$to_longitude','$location','$latitude','$longitude','$miles','$location_datetime','$service_order_id')";
            $location_res = $this
                ->db
                ->query($location_query);
            $response = array(
                "status" => "Sucess",
                "msg" => "Location Added"
            );
        }
        else
        {

            $location_query = "INSERT INTO serv_pers_tracking (user_master_id,user_lat,user_long,user_location,to_lat,to_long,miles,created_at,service_order_id) VALUES ('$user_master_id','$latitude','$longitude','$location','$latitude','$longitude','$miles','$location_datetime','$service_order_id')";
            $location_res = $this
                ->db
                ->query($location_query);
            $response = array(
                "status" => "Sucess",
                "msg" => "Location Added"
            );
        }

        return $response;
    }
    //#################### Cancel services End ####################//

    function add_current_location($user_master_id, $latitude, $longitude)
    {
        $select = "SELECT * FROM vendor_status WHERE serv_pro_id='$user_master_id'";
        $user_result = $this
            ->db
            ->query($select);
        if ($user_result->num_rows() == 0)
        {
            $insert = "INSERT INTO vendor_status (serv_pro_id,online_status,serv_lat,serv_lon,status,created_at,created_by) VALUES('$user_master_id','Online','$latitude','$longitude','Active',NOW(),'$user_master_id')";
            $ins_result = $this
                ->db
                ->query($insert);
        }
        else
        {
            $update = "UPDATE vendor_status SET serv_lat='$latitude',serv_lon='$longitude',created_at=NOW(),created_by='$user_master_id' WHERE serv_pro_id='$user_master_id'";
            $ins_result = $this
                ->db
                ->query($update);
        }
        if ($ins_result)
        {
            $response = array(
                "status" => "Sucess",
                "msg" => "Location Added"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Failed to add"
            );
        }
        return $response;

    }
    //-------------------- Cancel  reason list   -------------------//

    function list_reason_for_cancel($user_master_id)
    {
        $select = "SELECT * FROM cancel_master WHERE user_type=4";
        $res_offer = $this
            ->db
            ->query($select);
        if ($res_offer->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "No  Service found",
                "msg_en" => "",
                "msg_ta" => ""
            );
        }
        else
        {
            $offer_result = $res_offer->result();
            foreach ($offer_result as $rows)
            {
                $cancel_list[] = array(
                    "id" => $rows->id,
                    "cancel_reason" => $rows->reasons,
                );
            }

            $response = array(
                "status" => "success",
                "msg" => "Service Cancelled",
                "reason_list" => $cancel_list,
                "msg_en" => "",
                "msg_ta" => ""
            );

        }
        return $response;
    }
    //-------------------- Cancel  reason list   -------------------//
    
}

?>
