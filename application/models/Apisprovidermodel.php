<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apisprovidermodel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this
            ->load
            ->model('mailmodel');
        $this
            ->load
            ->model('smsmodel');
    }

    //-------------------- Version check -------------------//
    function version_check($version_code)
    {
        if ($version_code >= 4)
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
        $sperson_count = "SELECT * FROM service_person_details WHERE service_provider_id = '" . $user_master_id . "'";
        $sperson_count_res = $this
            ->db
            ->query($sperson_count);
        $sperson_count = $sperson_count_res->num_rows();

        $request_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Requested'";
        $request_count_res = $this
            ->db
            ->query($request_count);
        $request_orders_count = $request_count_res->num_rows();

        $accept_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Accepted'";
        $accept_count_res = $this
            ->db
            ->query($accept_count);
        $accept_orders_count = $accept_count_res->num_rows();

        $assigned_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Assigned'";
        $assigned_count_res = $this
            ->db
            ->query($assigned_count);
        $assigned_orders_count = $assigned_count_res->num_rows();

        $initiated_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Initiated'";
        $initiated_count_res = $this
            ->db
            ->query($initiated_count);
        $initiated_orders_count = $initiated_count_res->num_rows();

        $ongoing_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Ongoing'";
        $ongoing_count_res = $this
            ->db
            ->query($ongoing_count);
        $ongoing_orders_count = $ongoing_count_res->num_rows();

        $finished_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Completed'";
        $finished_count_res = $this
            ->db
            ->query($finished_count);
        $finished_orders_count = $finished_count_res->num_rows();

        $canceled_count = "SELECT * FROM service_orders WHERE serv_prov_id = '" . $user_master_id . "' AND status = 'Cancelled'";
        $canceled_count_res = $this
            ->db
            ->query($canceled_count);
        $canceled_orders_count = $canceled_count_res->num_rows();

        $dashboardData = array(
            "serv_person_count" => $sperson_count,
            "serv_requested_count" => $request_orders_count,
            "serv_accepted_count" => $accept_orders_count,
            "serv_assigned_count" => $assigned_orders_count,
            "serv_initiated_count" => $initiated_orders_count,
            "serv_ongoing_count" => $ongoing_orders_count,
            "serv_finished_count" => $finished_orders_count,
            "serv_canceled_count" => $canceled_orders_count
        );
        $response = array(
            "status" => "success",
            "msg" => "Dashboard Datas",
            "dashboardData" => $dashboardData
        );
        return $response;
    }

    //#################### Dashboard End ####################//
    

    //#################### Register ####################//
    public function Register($name, $mobile, $email)
    {
        $sql = "SELECT * FROM login_users WHERE phone_no ='" . $mobile . "' AND user_type = '3' AND status='Active'";
        $user_result = $this
            ->db
            ->query($sql);
        $ress = $user_result->result();

        $digits = 4;
        $OTP = str_pad(rand(0, pow(10, $digits) - 1) , $digits, '0', STR_PAD_LEFT);

        if ($user_result->num_rows() > 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "User already Exist."
            );

        }
        else
        {
            $insert_sql = "INSERT INTO login_users (user_type, phone_no, mobile_verify, email, email_verify, document_verify, otp, welcome_status, status) VALUES ('3','" . $mobile . "','N','" . $email . "','N','N','" . $OTP . "','N','Active')";
            $insert_result = $this
                ->db
                ->query($insert_sql);
            $user_master_id = $this
                ->db
                ->insert_id();

            $update_sql = "UPDATE login_users SET created_by  = '" . $user_master_id . "', created_at =NOW() WHERE id ='" . $user_master_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $insert_query = "INSERT INTO service_provider_details (user_master_id, owner_full_name, serv_prov_display_status, serv_prov_verify_status, deposit_status, status,created_at,created_by ) VALUES ('" . $user_master_id . "','" . $name . "','Inactive','Pending','Unpaid','Active',NOW(),'" . $user_master_id . "')";
            $insert_result = $this
                ->db
                ->query($insert_query);

            $enc_user_master_id = base64_encode($user_master_id);

            // $notes = "OTP :" . $OTP;
            $msg = "Your SkilEx Verification code is: " . $OTP . "  0gQ4RsI6iX4";
            $templateid = '1707161432164819940';
            $notes = $msg;
            $phone = $mobile;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            //$this->smsmodel->send_sms($phone,$notes);
            $subject = "SKILEX - New User Registered";
            $notes = '<p>Name:<span>' . $name . '</span></p><p>Email ID:<span>' . $email . '</span></p><p>Phone:<span>' . $mobile . '</span></p>';
            $this
                ->mailmodel
                ->send_mail_to_skilex($subject, $notes);

            //$this->sendNotification($gcm_key,$title,$message,$mobiletype)
            $response = array(
                "status" => "success",
                "msg" => "Mobile OTP",
                "user_master_id" => $user_master_id,
                "phone_no" => $mobile,
                "otp" => $OTP
            );
        }

        return $response;
    }
    //#################### Register End ####################//

    //#################### Mobile Check ####################//
    public function Mobile_check($phone_no)
    {
        $sql = "SELECT * FROM login_users WHERE phone_no ='" . $phone_no . "' AND user_type = '3' AND status='Active'";
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
            }

            $update_sql = "UPDATE login_users SET otp = '" . $OTP . "', updated_at=NOW() WHERE id ='" . $user_master_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $msg = "Your SkilEx Verification code is: " . $OTP . "  Y3XZqSQzX9V";
            $templateid = '1707161432164819940';
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
                "msg" => "User not found."
            );
        }

        return $response;
    }
    //#################### Mobile Check End ####################//

    //#################### Login ####################//
    public function Login($user_master_id, $phone_no, $otp, $device_token, $mobiletype)
    {
        $sql = "SELECT * FROM login_users WHERE phone_no = '" . $phone_no . "' AND otp = '" . $otp . "' AND user_type = '3' AND status='Active'";
        $sql_result = $this
            ->db
            ->query($sql);

        if ($sql_result->num_rows() > 0)
        {
            $update_sql = "UPDATE login_users SET mobile_verify ='Y', updated_at=NOW() WHERE id='$user_master_id'";
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

            $user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.user_type, B.* FROM login_users A, service_provider_details B WHERE A.id = B.user_master_id AND A.id = '" . $user_master_id . "'";
            $user_result = $this
                ->db
                ->query($user_sql);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $user_master_id = $rows->user_master_id;
                    $full_name = $rows->owner_full_name;
                    $phone_no = $rows->phone_no;
                    $mobile_verify = $rows->mobile_verify;
                    $email = $rows->email;
                    $email_verify = $rows->email_verify;
                    $gender = $rows->gender;
                    $profile_pic = $rows->profile_pic;
                    if ($profile_pic != '')
                    {
                        $profile_pic_url = base_url() . 'assets/providers/' . $profile_pic;
                    }
                    else
                    {
                        $profile_pic_url = "";
                    }
                    $address = $rows->address;
                    $city = $rows->city;
                    $state = $rows->state;
                    $zip = $rows->zip;
                    $serv_prov_display_status = $rows->serv_prov_display_status;
                    $serv_prov_verify_status = $rows->serv_prov_verify_status;
                    $refundable_deposit = $rows->refundable_deposit;
                    $deposit_status = $rows->deposit_status;
                    $company_status = $rows->company_status;
                    $also_service_person = $rows->also_service_person;
                    $status = $rows->status;
                    $user_type = $rows->user_type;
                    $bank_name = $rows->bank_name;
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
                "city" => $city,
                "serv_prov_display_status" => $serv_prov_display_status,
                "serv_prov_verify_status" => $serv_prov_verify_status,
                "refundable_deposit" => $refundable_deposit,
                "deposit_status" => $deposit_status,
                "company_status" => $company_status,
                "also_service_person" => $also_service_person,
                "status" => $status,
                "bank_name" => $bank_name,
                "user_type" => $user_type
            );

            $sQuery = "SELECT A.main_cat_id,B.main_cat_name,B.main_cat_ta_name FROM serv_prov_pers_skills A,main_category B WHERE A.user_master_id ='" . $user_master_id . "' AND A.main_cat_id = B.id";
            $cat_result = $this
                ->db
                ->query($sQuery);
            $category_count = $cat_result->num_rows();
            if ($cat_result->num_rows() != 0)
            {
                $category_result = $cat_result->result();
            }
            else
            {
                $category_result = "";
            }

            $doc_url = base_url() . 'assets/providers/documents/';
            $sQuery = "SELECT A.id,A.doc_master_id,B.doc_name,A.doc_proof_number, A.file_name,A.status FROM document_details A, document_master B WHERE A.doc_master_id = B.id AND A.user_master_id='" . $user_master_id . "'";
            $doc_result = $this
                ->db
                ->query($sQuery);
            if ($doc_result->num_rows() != 0)
            {
                foreach ($doc_result->result() as $rows)
                {
                    $id = $rows->id;
                    $doc_master_id = $rows->doc_master_id;
                    $doc_name = $rows->doc_name;
                    $doc_proof_number = $rows->doc_proof_number;
                    $file_name = $rows->file_name;
                    $doc_status = $rows->status;

                    $doc_list[] = array(
                        "id" => $id,
                        "doc_master_id" => $doc_master_id,
                        "doc_name" => $doc_name,
                        "doc_proof_number" => $doc_proof_number,
                        "file_name" => $file_name,
                        "doc_status" => $doc_status,
                        "file_url" => $doc_url . $file_name
                    );
                }
                $documet_list = array(
                    "status" => "success",
                    "msg" => "Document found",
                    "documents_list" => $doc_list
                );
            }
            else
            {
                $documet_list = array(
                    "status" => "norecord",
                    "msg" => "No Document found"
                );
            }

            $sQuery = "SELECT * FROM service_provider_company_details WHERE user_master_id ='" . $user_master_id . "'";
            $comp_result = $this
                ->db
                ->query($sQuery);

            if ($cat_result->num_rows() != 0)
            {
                $company_data_result = array(
                    "status" => "success",
                    "msg" => "Company data found",
                    "company_data" => $comp_result->result()
                );
            }
            else
            {

                $company_data_result = array(
                    "status" => "norecord",
                    "msg" => "No Company data found"
                );
            }

            $response = array(
                "status" => "success",
                "msg" => "Login Successfully",
                "userData" => $userData,
                "categoryCount" => $category_count,
                "docData" => $documet_list,
                "companyData" => $company_data_result
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

    //#################### Email Verification ####################//
    public function Email_verfication($dec_user_master_id)
    {
        $update_sql = "UPDATE login_users SET email_verify = 'Y', updated_at=NOW(), updated_by ='" . $dec_user_master_id . "' WHERE id ='" . $dec_user_master_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        if ($update_result)
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
    //#################### Email Verification End ####################//

    //#################### Email Verify status ####################//
    public function Email_verifystatus($user_master_id)
    {
        $sql = "SELECT * FROM login_users WHERE id ='" . $user_master_id . "' AND user_type = '3' AND status='Active'";
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

    //#################### Profile Update ####################//
    public function Profile_update($user_master_id, $full_name, $gender, $email)
    {
        $update_sql = "UPDATE service_provider_details SET owner_full_name='$full_name',gender='$gender',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
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

        // $update_sql    = "UPDATE service_provider_details SET owner_full_name='$full_name',gender='$gender',address='$address',city='$city',state='$state',zip='$zip',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
        // $update_result = $this->db->query($update_sql);
        // $response = array(
        //     "status" => "success",
        //     "msg" => "Profile Updated"
        // );
        // return $response;
        
    }
    //#################### Profile Update End ####################//

    //#################### Profile Pic Update ####################//
    public function Profile_pic_upload($user_master_id, $profileFileName)
    {
        $update_sql = "UPDATE service_provider_details SET profile_pic='$profileFileName',updated_at =NOW() WHERE user_master_id='$user_master_id'";
        $update_result = $this
            ->db
            ->query($update_sql);
        $picture_url = base_url() . 'assets/providers/' . $profileFileName;

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
        $select = "SELECT * FROM login_users as lu LEFT JOIN service_provider_details as cd ON lu.id=cd.user_master_id WHERE lu.id='$user_master_id'";
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
                $pic = base_url() . 'assets/providers/' . $profile;
            }
            $user_info = array(
                "phone_no" => $rows->phone_no,
                "email" => $rows->email,
                "full_name" => $rows->owner_full_name,
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

    public function Provider_status($user_master_id, $lat, $lon)
    {
        $sql = "SELECT * from vendor_status where serv_pro_id='" . $user_master_id . "'";
        $sql_result = $this
            ->db
            ->query($sql);

        if ($sql_result->num_rows() > 0)
        {
            $update_sql = "UPDATE vendor_status set online_status='Online', serv_lat = '" . $lat . "', serv_lon='" . $lon . "', status='Active', created_at=now(), created_by='" . $user_master_id . "' WHERE serv_pro_id='$user_master_id'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $response = array(
                "status" => "success",
                "msg" => "Vendor status updated"
            );
            return $response;
        }
        else if ($sql_result->num_rows() == 0)
        {
            $insQuery = "INSERT into vendor_status(serv_pro_id, online_status, serv_lat, serv_lon, status, created_by, created_at) values ('" . $user_master_id . "','Online', '" . $lat . "', '" . $lon . "', 'Active','" . $user_master_id . "',NOW())";
            $insert_status = $this
                ->db
                ->query($insQuery);

            $response = array(
                "status" => "success",
                "msg" => "Vendor status added"
            );
            return $response;
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something went wrong"
            );
            return $response;
        }
    }

    // Check Application STATUS
    function check_application_status($user_master_id, $status)
    {
        if ($status == 1)
        {
            $checkstatus = "spd.serv_pers_verify_status='Approved'";
        }
        else
        {
            $checkstatus = "(spd.serv_pers_verify_status='Pending' OR spd.serv_pers_verify_status='Rejected')";
        }
        $select = "SELECT spd.id,spd.full_name,lu.phone_no,spd.serv_pers_verify_status FROM service_person_details as spd left join login_users  as lu on lu.id=spd.user_master_id where spd.service_provider_id='$user_master_id' AND $checkstatus";
        $res = $this
            ->db
            ->query($select);
        if ($res->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "Application not found"
            );
        }
        else
        {
            $result = $res->result();
            $response = array(
                "status" => "success",
                "msg" => "View Application",
                "applicant" => $result
            );
        }
        return $response;

    }
    // Check Application STATUS

    //#################### Category list ####################//
    function Category_list($user_master_id)
    {

        $query = "SELECT id,main_cat_name,main_cat_ta_name,cat_pic from main_category WHERE status = 'Active'";
        $res = $this
            ->db
            ->query($query);

        if ($res->num_rows() > 0)
        {
            foreach ($res->result() as $rows)
            {
                $cat_pic = $rows->cat_pic;
                if ($cat_pic != '')
                {
                    $cat_pic_url = base_url() . 'assets/category/' . $cat_pic;
                }
                else
                {
                    $cat_pic_url = '';
                }

                $catData[] = array(
                    "cat_id" => $rows->id,
                    "cat_name" => $rows->main_cat_name,
                    "cat_ta_name" => $rows->main_cat_ta_name,
                    "cat_pic_url" => $cat_pic_url,
                    "user_preference" => 'N'
                );
            }
            $response = array(
                "status" => "success",
                "msg" => "View Category",
                "categories" => $catData
            );

        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Category not found"
            );
        }

        return $response;
    }
    //#################### Category list End ####################//
    
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
                "msg" => "Sub Category Not Found"
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
    //#################### Services list End ####################//

    //#################### Provider Add Category/Services ####################//
    public function Serv_prov_category_add($user_master_id, $category_id)
    {

        $delete = "DELETE FROM  serv_prov_pers_skills WHERE user_master_id='$user_master_id'";
        $delete_query = $this
            ->db
            ->query($delete);
        $result = explode(",", $category_id);
        $cnt = count($result);
        if ($cnt > 2)
        {
            $response = array(
                "status" => "error",
                "msg" => "You cannot more than 2 category.."
            );
        }
        else
        {
            for ($i = 0;$i < $cnt;$i++)
            {

                $sQuery = "INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,status,created_at,created_by) VALUES ('$user_master_id','$result[$i]','Active',NOW(),'$user_master_id')";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }
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
                    "msg" => "Something Went Wrong"
                );
            }
        }

        return $response;
    }

    /* public function Serv_prov_services_add($user_master_id,$category_id,$sub_category_id,$service_id)
    {
    $sQuery = "INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,sub_cat_id,service_id,status,created_at,created_by) VALUES ('". $user_master_id . "','". $category_id . "','". $sub_category_id . "','". $service_id . "','Active',NOW(),'". $user_master_id . "')";
    $ins_query = $this->db->query($sQuery);
    
    if($ins_query){
    $response=array("status" => "success","msg" => "Services Added Sucessfully!..");
    }else{
    $response=array("status" => "error");
    }
    
    return $response;
    } */
    //#################### Provider Add Category/Services End ####################//
    
    //#################### Provider List Category/Services ####################//
    public function List_prov_person_category($user_master_id)
    {
        $sQuery = "SELECT A.main_cat_id,B.main_cat_name,B.main_cat_ta_name FROM serv_prov_pers_skills A,main_category B WHERE A.user_master_id ='" . $user_master_id . "' AND A.main_cat_id = B.id";
        $cat_result = $this
            ->db
            ->query($sQuery);
        $category_count = $cat_result->num_rows();
        if ($cat_result->num_rows() != 0)
        {
            $category_result = $cat_result->result();
            $response = array(
                "status" => "success",
                "msg" => "List Category",
                "listCategory" => $category_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Went Wrong"
            );
        }
        return $response;
    }
    //#################### Provider List Category/Services End ####################//

    //#################### Update company status ####################//
    public function Update_company_status($user_master_id, $company_status)
    {
        $sQuery = "UPDATE service_provider_details SET company_status ='$company_status',updated_at=NOW() WHERE user_master_id='$user_master_id'";
        $ins_query = $this
            ->db
            ->query($sQuery);

        if ($ins_query)
        {
            $response = array(
                "status" => "success",
                "msg" => "Company status updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Went Wrong"
            );
        }

        return $response;
    }
    //#################### Update company status End ####################//
    
    //#################### Add Individual status ####################//
    public function add_individual_status($user_master_id, $no_of_service_person, $also_service_person)
    {
        $sQuery = "UPDATE service_provider_details SET no_of_service_person ='$no_of_service_person', also_service_person = '$also_service_person', updated_at=NOW() WHERE user_master_id='$user_master_id'";
        $uptdate_query = $this
            ->db
            ->query($sQuery);

        if ($also_service_person == 'N')
        {
            $response = array(
                "status" => "success",
                "msg" => "Individual updated"
            );
        }
        else
        {
            $user_sql = "SELECT A.id as user_master_id, A.phone_no, A.mobile_verify, A.email, A.email_verify, A.document_verify, A.welcome_status, B.* FROM login_users A, service_provider_details B WHERE A.id = B.user_master_id AND A.id = '" . $user_master_id . "'";
            $user_result = $this
                ->db
                ->query($user_sql);
            if ($user_result->num_rows() > 0)
            {
                foreach ($user_result->result() as $rows)
                {
                    $full_name = $rows->owner_full_name;
                    $mobile = $rows->phone_no;
                    $mobile_verify = $rows->mobile_verify;
                    $email = $rows->email;
                    $email_verify = $rows->email_verify;
                    $gender = $rows->gender;
                    $address = $rows->address;
                    $city = $rows->city;
                    $state = $rows->state;
                    $zip = $rows->zip;
                }
            }
            $check = "SELECT * FROM login_users WHERE phone_no='$mobile' and user_type='4'";
            $check_sql = $this
                ->db
                ->query($check);

            if ($check_sql->num_rows() == 0)
            {
                $insert_sql = "INSERT INTO login_users (user_type, phone_no, mobile_verify, email, email_verify, document_verify, welcome_status, status,created_at,created_by) VALUES ('4','" . $mobile . "','N','" . $email . "','N','N','N','Active',NOW(),'" . $user_master_id . "')";
                $insert_result = $this
                    ->db
                    ->query($insert_sql);
                $sperson_master_id = $this
                    ->db
                    ->insert_id();

                $select_category = "SELECT * FROM serv_prov_pers_skills where user_master_id='$user_master_id'";
                $res_select_category = $this
                    ->db
                    ->query($select_category);
                foreach ($res_select_category->result() as $row_category)
                {
                    $cat_id = $row_category->main_cat_id;
                    $sQuery = "INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,status,created_at,created_by) VALUES ('$sperson_master_id','$cat_id','Active',NOW(),'$user_master_id')";
                    $ins_query = $this
                        ->db
                        ->query($sQuery);
                }

                $insert_query = "INSERT INTO service_person_details (user_master_id,service_provider_id,full_name, serv_pers_display_status, serv_pers_verify_status,also_service_provider,status,created_at,created_by ) VALUES ('" . $sperson_master_id . "','" . $user_master_id . "','" . $full_name . "','Inactive','Pending','Y','Active',NOW(),'" . $user_master_id . "')";
                $insert_result = $this
                    ->db
                    ->query($insert_query);
                if ($uptdate_query)
                {
                    $response = array(
                        "status" => "success",
                        "msg" => "Individual updated"
                    );
                }
                else
                {
                    $response = array(
                        "status" => "error",
                        "msg" => "Something Went Wrong"
                    );
                }
            }
            else
            {
                $response = array(
                    "status" => "error",
                    "msg" => "Mobile number already exists"
                );
            }

        }

        return $response;
    }
    //#################### Add Individual status End ####################//

    //#################### Add Company status  ####################//
    public function Add_company_status($user_master_id, $company_name, $no_of_service_person, $company_address, $company_city, $company_state, $company_zip, $company_info, $company_building_type)
    {

        $sQuery = "UPDATE service_provider_details SET no_of_service_person ='$no_of_service_person', also_service_person = 'N', updated_at=NOW() WHERE user_master_id='$user_master_id'";
        $uptdate_query = $this
            ->db
            ->query($sQuery);

        $sQuery = "INSERT INTO service_provider_company_details (user_master_id,company_name,company_address,company_city,company_state,company_zip,company_info,company_building_type,status,created_at,created_by) VALUES ('" . $user_master_id . "','" . $company_name . "','" . $company_address . "','" . $company_city . "','" . $company_state . "','" . $company_zip . "','" . $company_info . "','" . $company_building_type . "','Active',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        if ($ins_query)
        {
            $response = array(
                "status" => "success",
                "msg" => "Company Details updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Went Wrong"
            );
        }

        return $response;
    }
    //#################### Add Company status End ####################//

    //#################### Master ID Proof list ####################//
    public function List_idaddress_proofs($company_type)
    {
        if ($company_type == 'Individual')
        {
            $sQuery = "SELECT id, doc_name, doc_type, company_doc_type FROM document_master WHERE doc_type = 'IdAddressProof' AND company_doc_type = '" . $company_type . "' AND status='Active'";
        }
        else
        {
            $sQuery = "SELECT id, doc_name, doc_type, company_doc_type FROM document_master WHERE doc_type = 'AddressProof' AND company_doc_type = '" . $company_type . "' AND status='Active'";
        }
        $doc_result = $this
            ->db
            ->query($sQuery);
        $document_result = $doc_result->result();

        if ($doc_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "ID or Address Master list",
                "proof_list" => $document_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Master Proof Not Found"
            );
        }
        return $response;
    }
    //#################### Master ID Proof list End ####################//

    //#################### Master Building Proof list ####################//
    public function List_building_proofs($user_master_id)
    {
        $sQuery = "SELECT * FROM document_master WHERE doc_type = 'BuildingProof' AND company_doc_type = 'Company' AND status='Active'";
        $doc_result = $this
            ->db
            ->query($sQuery);
        $document_result = $doc_result->result();

        if ($doc_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Building Proof",
                "proof_list" => $document_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Building Proof Not Found"
            );
        }
        return $response;
    }
    //#################### Master Building Proof list End ####################//

    //#################### Document Upload ####################//
    public function Upload_doc($user_master_id, $doc_master_id, $doc_proof_number, $documentFileName)
    {
        $sQuery = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);
        $last_insert_id = $this
            ->db
            ->insert_id();
        $document_url = base_url() . 'assets/providers/documents/' . $documentFileName;

        $get_user_details = "SELECT * FROM service_provider_details where user_master_id='$user_master_id'";
        $ex_get_user_details = $this
            ->db
            ->query($get_user_details);
        $ex_get_user_details_result = $ex_get_user_details->result();
        foreach ($ex_get_user_details_result as $rows_user_details)
        {
        }
        $get_user_name = $rows_user_details->owner_full_name;

        $get_doc_name = "SELECT * FROM document_master WHERE id='$doc_master_id'";
        $ex_get_doc_name = $this
            ->db
            ->query($get_doc_name);
        foreach ($ex_get_doc_name->result() as $rows_doc_name)
        {
        }

        $subject = "SKILEX - $get_user_name Uploaded new document";
        $notes = '<p>Document:<span><a target="_blank" href="' . $document_url . '"' . $doc_proof_number . '>Download ' . $rows_doc_name->doc_name . '</a></span></p>';
        $this
            ->mailmodel
            ->send_mail_to_skilex($subject, $notes);

        $sQuery = "INSERT INTO document_notes(user_master_id,doc_detail_id,notes,status,created_at,created_by) VALUES ('" . $user_master_id . "','" . $last_insert_id . "','Uploaded','Active',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        $prov_sql = "SELECT * FROM service_provider_details WHERE user_master_id = '" . $user_master_id . "' AND also_service_person = 'Y'";
        $prov_result = $this
            ->db
            ->query($prov_sql);
        if ($prov_result->num_rows() > 0)
        {
            $pers_sql = "SELECT * FROM service_person_details WHERE service_provider_id = '" . $user_master_id . "'";
            $pers_result = $this
                ->db
                ->query($pers_sql);
            if ($pers_result->num_rows() > 0)
            {
                foreach ($pers_result->result() as $rows)
                {
                    $person_user_master_id = $rows->user_master_id;
                }

            }

            $doc_sql = "SELECT * FROM document_master WHERE id = '" . $doc_master_id . "'";
            $doc_result = $this
                ->db
                ->query($doc_sql);
            if ($doc_result->num_rows() > 0)
            {
                foreach ($doc_result->result() as $rows)
                {
                    $doc_type = trim($rows->doc_type);
                }

                if ($doc_type == 'IdAddressProof')
                {
                    $sQuery = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $person_user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
                    $ins_query = $this
                        ->db
                        ->query($sQuery);
                }
                if ($doc_master_id == '3')
                {
                    $sQuery = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $person_user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
                    $ins_query = $this
                        ->db
                        ->query($sQuery);
                }
            }
        }
        $response = array(
            "status" => "success",
            "msg" => "Document Uploaded",
            "document_id" => $last_insert_id,
            "doc_master_id" => $doc_master_id,
            "document_url" => $document_url
        );
        return $response;
    }
    //#################### Document Upload End ####################//

    //#################### Document Upload ####################//
    public function re_upload_doc($user_master_id, $doc_master_id, $doc_proof_number, $documentFileName, $doc_detail_id)
    {
        // $sQuery         = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
        $sQuery = "UPDATE document_details SET doc_master_id='$doc_master_id',doc_proof_number='$doc_proof_number',file_name='$documentFileName',status='Uploaded',updated_at=NOW() WHERE id='$doc_detail_id' AND user_master_id='$user_master_id'";
        $ins_query = $this
            ->db
            ->query($sQuery);
        $last_insert_id = $this
            ->db
            ->insert_id();
        $document_url = base_url() . 'assets/providers/documents/' . $documentFileName;

        $get_user_details = "SELECT * FROM service_provider_details where user_master_id='$user_master_id'";
        $ex_get_user_details = $this
            ->db
            ->query($get_user_details);
        $ex_get_user_details_result = $ex_get_user_details->result();
        foreach ($ex_get_user_details_result as $rows_user_details)
        {
        }
        $get_user_name = $rows_user_details->owner_full_name;

        $get_doc_name = "SELECT * FROM document_master WHERE id='$doc_master_id'";
        $ex_get_doc_name = $this
            ->db
            ->query($get_doc_name);
        foreach ($ex_get_doc_name->result() as $rows_doc_name)
        {
        }

        $subject = "SKILEX - $get_user_name Re-Uploaded new document";
        $notes = '<p>Document:<span><a target="_blank" href="' . $document_url . '"' . $doc_proof_number . '>Download ' . $rows_doc_name->doc_name . '</a></span></p>';
        $this
            ->mailmodel
            ->send_mail_to_skilex($subject, $notes);

        $sQuery = "INSERT INTO document_notes(user_master_id,doc_detail_id,notes,status,created_at,created_by) VALUES ('" . $user_master_id . "','" . $last_insert_id . "','Uploaded','Active',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        $prov_sql = "SELECT * FROM service_provider_details WHERE user_master_id = '" . $user_master_id . "' AND also_service_person = 'Y'";
        $prov_result = $this
            ->db
            ->query($prov_sql);
        if ($prov_result->num_rows() > 0)
        {
            $pers_sql = "SELECT * FROM service_person_details WHERE service_provider_id = '" . $user_master_id . "'";
            $pers_result = $this
                ->db
                ->query($pers_sql);
            if ($pers_result->num_rows() > 0)
            {
                foreach ($pers_result->result() as $rows)
                {
                    $person_user_master_id = $rows->user_master_id;
                }

            }

            // $doc_sql    = "SELECT * FROM document_master WHERE id = '" . $doc_master_id . "'";
            // $doc_result = $this->db->query($doc_sql);
            // if ($doc_result->num_rows() > 0) {
            //     foreach ($doc_result->result() as $rows) {
            //         $doc_type = trim($rows->doc_type);
            //     }
            //
            //     if ($doc_type == 'IdAddressProof') {
            //         $sQuery    = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $person_user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
            //         $ins_query = $this->db->query($sQuery);
            //     }
            //     if ($doc_master_id == '3') {
            //         $sQuery    = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $person_user_master_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
            //         $ins_query = $this->db->query($sQuery);
            //     }
            // }
            
        }
        $response = array(
            "status" => "success",
            "msg" => "Document Uploaded",
            "document_id" => $last_insert_id,
            "doc_master_id" => $doc_master_id,
            "document_url" => $document_url
        );
        return $response;
    }
    //#################### Document Upload End ####################//

    //################### Update provider bank detail ##################//
    public function Update_provider_bank_detail($user_master_id, $bank_name, $branch_name, $acc_no, $ifsc_code, $any_police_case)
    {
        $update_sql = "UPDATE service_provider_details SET bank_name='$bank_name',any_police_case='$any_police_case',bank_branch_name='$branch_name',bank_acc_no='$acc_no',bank_ifsc_code='$ifsc_code',updated_at=NOW(),updated_by='$user_master_id' WHERE user_master_id='$user_master_id'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $response = array(
            "status" => "success",
            "msg" => "Service provider bank details updated"
        );
        return $response;
    }
    //##################################################################//

    //#################### Document list ####################//
    public function List_provider_doc($user_master_id)
    {
        $doc_url = base_url() . 'assets/providers/documents/';

        $sQuery = "SELECT A.id,A.doc_master_id,B.doc_name,A.doc_proof_number, A.file_name,A.status FROM document_details A, document_master B WHERE A.doc_master_id = B.id AND A.user_master_id='$user_master_id'";
        $doc_result = $this
            ->db
            ->query($sQuery);

        if ($doc_result->num_rows() != 0)
        {
            foreach ($doc_result->result() as $rows)
            {
                $id = $rows->id;
                $doc_master_id = $rows->doc_master_id;
                $doc_name = $rows->doc_name;
                $doc_proof_number = $rows->doc_proof_number;
                $status = $rows->status;
                $file_name = $rows->file_name;

                $data[] = array(
                    "id" => $id,
                    "doc_master_id" => $doc_master_id,
                    "doc_name" => $doc_name,
                    "doc_proof_number" => $doc_proof_number,
                    "file_name" => $file_name,
                    "status" => $status,
                    "file_url" => $doc_url . $file_name
                );
            }

            $bank_details = "SELECT bank_name,bank_branch_name,bank_acc_no,bank_ifsc_code FROM service_provider_details where user_master_id='$user_master_id'";
            $bank_result = $this
                ->db
                ->query($bank_details);
            $bank_ex = $bank_result->result();

            $response = array(
                "status" => "success",
                "msg" => "Documents list",
                "bank_details" => $bank_ex,
                "document_result" => $data
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Documents Not Found"
            );
        }
        return $response;

    }
    //#################### Document list End ####################//

    //#################### Welcome status ####################//
    public function provider_active_status_update($user_master_id)
    {
        $sQuery1 = "UPDATE login_users SET welcome_status ='Y',updated_at=NOW() WHERE id='$user_master_id'";
        $sQuery = "UPDATE service_provider_details SET serv_prov_display_status ='Active',updated_at=NOW() WHERE user_master_id='$user_master_id'";
        $ins_query1 = $this
            ->db
            ->query($sQuery1);
        $ins_query = $this
            ->db
            ->query($sQuery);

        if ($ins_query)
        {
            $response = array(
                "status" => "success",
                "msg" => "Active status updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something went wrong"
            );
        }

        return $response;

    }
    
    //#################### Document list End ####################//
    function document_rejected_details($doc_detail_id)
    {
        $sQuery = " SELECT notes FROM document_notes WHERE STATUS='Rejected' and doc_detail_id='$doc_detail_id' order by created_at desc LIMIT 1";
        $usr_result = $this
            ->db
            ->query($sQuery);
        $user_result = $usr_result->result();

        if ($usr_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => $user_result[0]->notes,
                "doc_notes" => $user_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Document notes Not found"
            );
        }
        return $response;
    }

    //#################### Create Service Persons ####################//
    public function Create_serv_person($user_master_id, $name, $mobile, $email)
    {
        if (empty($email))
        {
            $sql = "SELECT * FROM login_users WHERE phone_no ='" . $mobile . "'  AND user_type = '4' AND status='Inactive'";
        }
        else
        {
            $sql = "SELECT * FROM login_users WHERE (phone_no ='" . $mobile . "' OR email = '" . $email . "') AND user_type = '4' AND status='Inactive'";

        }
        $user_result = $this
            ->db
            ->query($sql);
        $ress = $user_result->result();

        if ($user_result->num_rows() > 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "User already Exist."
            );

        }
        else
        {
            $insert_sql = "INSERT INTO login_users (user_type, phone_no, mobile_verify, email, email_verify, document_verify, welcome_status, status) VALUES ('4','" . $mobile . "','N','" . $email . "','N','N','N','Inactive')";
            $insert_result = $this
                ->db
                ->query($insert_sql);
            $serv_person_id = $this
                ->db
                ->insert_id();

            $update_sql = "UPDATE login_users SET created_by  = '" . $user_master_id . "', created_at =NOW() WHERE id ='" . $serv_person_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $insert_query = "INSERT INTO service_person_details (user_master_id, service_provider_id, full_name, serv_pers_display_status, serv_pers_verify_status, also_service_provider, status,created_at,created_by ) VALUES ('" . $serv_person_id . "','" . $user_master_id . "','" . $name . "','Inactive','Pending','N','Active',NOW(),'" . $user_master_id . "')";
            $insert_result = $this
                ->db
                ->query($insert_query);

            $notes = "SKILEX - Service Person Created";
            $templateid = '1707161433083037008';

            $phone = $mobile;
            $this
                ->smsmodel
                ->send_sms($phone, $notes, $templateid);
            // $this->smsmodel->send_sms($phone,$notes);
            $subject = "SKILEX - New Expert Created";
            $notes = '<p>Name:<span>' . $name . '</span></p><p>Email ID:<span>' . $email . '</span></p><p>Phone:<span>' . $mobile . '</span></p>';
            $this
                ->mailmodel
                ->send_mail_to_skilex($subject, $notes);

            //$this->sendNotification($gcm_key,$title,$message,$mobiletype)
            $response = array(
                "status" => "success",
                "msg" => "Service Person Created",
                "user_master_id" => $user_master_id,
                "serv_person_id" => $serv_person_id
            );
        }

        return $response;
    }
    //#################### Create Service Persons ####################//

    //#################### Service persons details update ####################//
    public function Update_serv_person_details($user_master_id, $serv_person_id, $full_name, $gender, $address, $city, $pincode, $state, $language_known, $edu_qualification, $any_police_case)
    {
        $sQuery = "UPDATE service_person_details SET full_name ='" . $full_name . "',any_police_case='$any_police_case',gender='" . $gender . "',address='" . $address . "',city='" . $city . "',state='" . $state . "',zip='" . $pincode . "',edu_qualification='" . $edu_qualification . "',language_known='" . $language_known . "',updated_at=NOW(),updated_by='" . $user_master_id . "' WHERE user_master_id = '" . $serv_person_id . "' AND service_provider_id ='" . $user_master_id . "'";
        $usr_result = $this
            ->db
            ->query($sQuery);

        if ($usr_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service persons details updated"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something went wrong"
            );
        }
        return $response;
    }
    //#################### Service Persons details update end ####################//

    //#################### Service Persons list ####################//
    public function List_serv_persons($user_master_id)
    {
        $sQuery = "SELECT A.*,B.phone_no,B.mobile_verify,B.email,B.email_verify,B.document_verify,B.welcome_status FROM service_person_details A,login_users B WHERE A.user_master_id = B.id AND A.service_provider_id ='" . $user_master_id . "'";
        $usr_result = $this
            ->db
            ->query($sQuery);
        $user_result = $usr_result->result();

        if ($usr_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Persons list",
                "list_service_persons" => $user_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Persons Not found"
            );
        }
        return $response;
    }
    //#################### Service Persons list End ####################//

    //#################### Service Person Details ####################//
    public function Serv_person_details($serv_pres_id)
    {
        $sQuery = "SELECT A.*,B.phone_no,B.mobile_verify,B.email,B.email_verify,B.document_verify,B.welcome_status FROM service_person_details A,login_users B WHERE A.user_master_id = B.id AND A.user_master_id ='" . $serv_pres_id . "'";
        $usr_result = $this
            ->db
            ->query($sQuery);
        $user_result = $usr_result->result();

        $assigned_count = "SELECT * FROM service_orders WHERE serv_pers_id = '" . $serv_pres_id . "' AND status = 'Assigned'";
        $assigned_count_res = $this
            ->db
            ->query($assigned_count);
        $assigned_orders_count = $assigned_count_res->num_rows();

        $ongoing_count = "SELECT * FROM service_orders WHERE serv_pers_id = '" . $serv_pres_id . "' AND (status = 'Initiated' OR status = 'Started' OR status = 'Ongoing')";
        $ongoing_count_res = $this
            ->db
            ->query($ongoing_count);
        $ongoing_orders_count = $ongoing_count_res->num_rows();

        $dashboardData = array(
            "serv_assigned_count" => $assigned_orders_count,
            "serv_ongoing_count" => $ongoing_orders_count
        );

        if ($usr_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Person Details",
                "list_service_persons" => $user_result,
                "service_order_details" => $dashboardData
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Person Not found"
            );
        }
        return $response;
    }
    //#################### Service Persons Details ####################//
    
    //#################### Service Person Document Upload ####################//
    public function Serv_person_upload_doc($user_master_id, $serv_person_id, $doc_master_id, $doc_proof_number, $documentFileName)
    {
        $sQuery = "INSERT INTO document_details(user_master_id,doc_master_id,doc_proof_number,file_name,status,created_at,created_by) VALUES ('" . $serv_person_id . "','" . $doc_master_id . "','" . $doc_proof_number . "','" . $documentFileName . "','Pending',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);
        $last_insert_id = $this
            ->db
            ->insert_id();
        $document_url = base_url() . 'assets/persons/documents/' . $documentFileName;

        $get_user_details = "SELECT * FROM service_provider_details where user_master_id='$user_master_id'";
        $ex_get_user_details = $this
            ->db
            ->query($get_user_details);
        $ex_get_user_details_result = $ex_get_user_details->result();
        foreach ($ex_get_user_details_result as $rows_user_details)
        {
        }
        $get_user_name = $rows_user_details->owner_full_name;

        $get_doc_name = "SELECT * FROM document_master WHERE id='$doc_master_id'";
        $ex_get_doc_name = $this
            ->db
            ->query($get_doc_name);
        foreach ($ex_get_doc_name->result() as $rows_doc_name)
        {
        }

        $subject = "SKILEX - $get_user_name Uploaded Expert document";
        $notes = '<p>Document:<span><a href="' . $document_url . '"' . $doc_proof_number . '>Download ' . $rows_doc_name->doc_name . '</a></span></p>';
        $this
            ->mailmodel
            ->send_mail_to_skilex($subject, $notes);

        $sQuery = "INSERT INTO document_notes(user_master_id,doc_detail_id,notes,status,created_at,created_by) VALUES ('" . $serv_person_id . "','" . $last_insert_id . "','Uploaded','Active',NOW(),'" . $user_master_id . "')";
        $ins_query = $this
            ->db
            ->query($sQuery);

        $response = array(
            "status" => "success",
            "msg" => "Document Uploaded",
            "document_id" => $last_insert_id,
            "doc_master_id" => $doc_master_id,
            "document_url" => $document_url
        );
        return $response;
    }
    //#################### Service Person Document Upload End ####################//

    //#################### Service Persons Document list ####################//
    public function List_persons_doc($serv_person_id)
    {

        $sQuery = "SELECT * FROM service_person_details WHERE user_master_id ='" . $serv_person_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $also_service_provider = $rows->also_service_provider;
            }

            if ($also_service_provider == 'Y')
            {
                $doc_url = base_url() . 'assets/providers/documents/';
            }
            else
            {
                $doc_url = base_url() . 'assets/persons/documents/';
            }

        }

        $sQuery = "SELECT A.id,A.doc_master_id,B.doc_name,A.doc_proof_number, A.file_name,A.status FROM document_details A, document_master B WHERE A.doc_master_id = B.id AND A.user_master_id='" . $serv_person_id . "'";
        $doc_result = $this
            ->db
            ->query($sQuery);

        if ($doc_result->num_rows() > 0)
        {
            foreach ($doc_result->result() as $rows)
            {
                $id = $rows->id;
                $doc_master_id = $rows->doc_master_id;
                $doc_name = $rows->doc_name;
                $doc_proof_number = $rows->doc_proof_number;
                $file_name = $rows->file_name;

                $data[] = array(
                    "id" => $id,
                    "doc_master_id" => $doc_master_id,
                    "doc_name" => $doc_name,
                    "doc_proof_number" => $doc_proof_number,
                    "file_name" => $file_name,
                    "file_url" => $doc_url . $file_name
                );
            }
            $response = array(
                "status" => "success",
                "msg" => "Documents list",
                "document_result" => $data
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Documents Not Found"
            );
        }
        return $response;
    }
    //#################### Service Persons Document list End ####################//

    //#################### Persons Add Category/Services ####################//
    public function Serv_pers_category_add($user_master_id, $serv_person_id, $category_id)
    {
        $delete = "DELETE FROM  serv_prov_pers_skills WHERE user_master_id='$user_master_id'";
        $delete_query = $this
            ->db
            ->query($delete);
        $result = explode(",", $category_id);
        $cnt = count($result);
        for ($i = 0;$i < $cnt;$i++)
        {

            $sQuery = "INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,status,created_at,created_by) VALUES ('$serv_person_id','$result[$i]','Active',NOW(),'$user_master_id')";
            $ins_query = $this
                ->db
                ->query($sQuery);
        }
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
                "msg" => "Something Went Wrong"
            );
        }

        return $response;
    }

    /* public function Serv_pers_services_add($user_master_id,$serv_person_id,$category_id,$sub_category_id,$service_id)
    {
    $sQuery = "INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,sub_cat_id,service_id,status,created_at,created_by) VALUES ('". $serv_person_id . "','". $category_id . "','". $sub_category_id . "','". $service_id . "','Active',NOW(),'". $user_master_id . "')";
    $ins_query = $this->db->query($sQuery);
    
    if($ins_query){
    $response=array("status" => "success","msg" => "Services Added Sucessfully!..");
    }else{
    $response=array("status" => "error");
    }
    
    return $response;
    } */
    //#################### Persons Add Category/Services End ####################//

    //#################### List requested services ####################//
    public function List_requested_services($user_master_id)
    {
        $sQuery = "SELECT so.id,soh.status,so.order_notes,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') AS order_date,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_name,sc.sub_cat_ta_name,s.service_name,s.service_ta_name,st.from_time,st.to_time
    from service_orders as so
    left join service_order_history as soh on soh.service_order_id=so.id
    left JOIN main_category as mc on mc.id=so.main_cat_id
    left join sub_category as sc on sc.id=so.sub_cat_id
    left join services as s on s.id=so.service_id
    left join service_timeslot as st on st.id=so.order_timeslot
    where soh.serv_prov_id='$user_master_id'  and (soh.status='Requested' OR so.status='Accepted') GROUP by so.id ORDER BY so.id desc";
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
    //#################### List requested services End ####################//

    //#################### Aassigned detailed services ####################//
    public function Detail_requested_services($user_master_id, $service_order_id)
    {

        $sQuery = "SELECT
					A.id,
					A.service_location,
					DATE_FORMAT(A.order_date, '%e-%m-%Y') as order_date,
					A.contact_person_name,
					A.contact_person_number,
					A.service_rate_card,
          A.order_notes,
					B.main_cat_name,
					B.main_cat_ta_name,
					C.sub_cat_name,
					C.sub_cat_ta_name,
					D.service_name,
					D.service_ta_name,
					E.from_time,
					E.to_time

				FROM
                	service_order_history AA,
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E
				WHERE
					  AA.serv_prov_id = '" . $user_master_id . "' AND AA.status = 'Requested' AND A.id = '" . $service_order_id . "' AND AA.service_order_id = A.id AND A.main_cat_id = B.id AND A.sub_cat_id = C.id
            AND A.service_id = D.id AND A.order_timeslot = E.id";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order Details",
                "detail_services_order" => $service_result
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
    //#################### Aassigned detailed services End ####################//

    //#################### Accept requested services ####################//
    public function Accept_requested_services($user_master_id, $service_order_id)
    {

        $check_order = "SELECT * FROM service_order_history WHERE status='Expired' AND service_order_id='$service_order_id' AND serv_prov_id='$user_master_id'";
        $result_ch_order = $this
            ->db
            ->query($check_order);

        if ($result_ch_order->num_rows() == 0)
        {
            $update_sql = "UPDATE service_orders SET serv_prov_id = '" . $user_master_id . "', status  = 'Accepted', updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $check = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND serv_prov_id='$user_master_id' AND status='Requested'";
            $che_query = $this
                ->db
                ->query($check);
            if ($che_query->num_rows() == 1)
            {
                //$sQuery    = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Accepted',NOW(),'" . $user_master_id . "')";
                $sQuery = "UPDATE service_order_history SET status='Accepted' WHERE service_order_id='$service_order_id' AND serv_prov_id='$user_master_id' AND status='Requested'";
                $ins_query = $this
                    ->db
                    ->query($sQuery);
            }
            else
            {
                $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $user_master_id . "','Accepted',NOW(),'" . $user_master_id . "')";
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
                }
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
                        $message = "ஸ்கிலெக்ஸ் தங்கள் சர்வீஸ் கோரிக்கையை ஏற்றுகொண்டோம்";
                        $templateid = '1707161527170769665';
                    }
                    else
                    {
                        $message = "Skilex- Your Services has been accepted.";
                        $templateid = '1707161518662058671';
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

            if ($update_result)
            {
                $response = array(
                    "status" => "success",
                    "msg" => "Service Request Accepted"
                );
            }
            else
            {
                $response = array(
                    "status" => "error",
                    "msg" => "Something Went Wrong"
                );
            }

        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service has been Expired"
            );
        }

        return $response;
    }
    //#################### Accept requested services End ####################//

    //#################### Assigned requested services ####################//
    public function Assigned_accepted_services($user_master_id, $service_order_id, $service_person_id)
    {
        $update_sql = "UPDATE service_orders SET status = 'Assigned', serv_pers_id = '" . $service_person_id . "', updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
        $update_result = $this
            ->db
            ->query($update_sql);

        $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Assigned'";
        $res_select = $this
            ->db
            ->query($select);
        if ($res_select->num_rows() == 0)
        {
            $sQuery = "INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES ('" . $service_order_id . "','" . $service_person_id . "','Assigned',NOW(),'" . $user_master_id . "')";
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
            }
        }

        $sQuery = "SELECT * FROM login_users WHERE id ='" . $service_person_id . "'";
        $user_result = $this
            ->db
            ->query($sQuery);
        if ($user_result->num_rows() > 0)
        {
            foreach ($user_result->result() as $rows)
            {
                $sperson_mobile = $rows->phone_no;
                $preferred_lang_id = $rows->preferred_lang_id;
            }
        }

        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$service_person_id'";

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
                    $message = "ஸ்கிலெக்ஸ்லிருந்து வாழ்த்துக்கள்! ஸ்கிலெக்ஸ் சர்வீஸ் கோரிக்கை  ஒதுக்கப்பட்டது.மேலும் அறிய ஆப்பை  பார்க்கவும்.";
                    $templateid = '1707161433655197439';
                }
                else
                {
                    $message = "Service request assigned.";
                    $templateid = '1707161432827883995';
                }
                $user_type = '4';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
            $phone = $sperson_mobile;
            $notes = $message;
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
                    $message = "ஸ்கிலெக்ஸ் தங்களது கோரிக்கையைக்கான சர்வீஸ் நபர் ஒதுக்கப்பட்டுள்ளது.";
                    $templateid = '1707161527181073930';
                }
                else
                {
                    $message = 'Service expert assigned to your order.';
                    $templateid = '1707161518695124556';
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

        //$this->sendNotification($customer_mobile_key,$title,$message_details,$customer_mobile_type)
        if ($update_result)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Assigned"
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Went Wrong"
            );
        }

        return $response;
    }
    //#################### Assigned requested services End ####################//

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
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.serv_prov_id = '" . $user_master_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id order by A.id desc";
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

    //#################### Aassigned detailed services ####################//
    public function Detail_assigned_services($user_master_id, $service_order_id)
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
					 A.id = '" . $service_order_id . "' AND A.serv_prov_id = '" . $user_master_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id
           AND A.serv_pers_id = F.user_master_id";
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
    //#################### Aassigned detailed services End ####################//

    //#################### List Ongoing services ####################//
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
        //       $user_type='3';
        //       //$this->smsmodel->send_notification($head,$message,$gcm_key,$mobile_type,$user_type);
        //       $this->smsmodel->push_notification_android($head,$message,$gcm_key,$mobile_type,$user_type);
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
					 A.serv_prov_id = '$user_master_id' AND (A.status = 'Initiated' OR A.status = 'Started' OR A.status = 'Ongoing' OR A.status = 'Hold') AND A.main_cat_id = B.id AND A.sub_cat_id = C.id
           AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id order by A.id desc";
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

    //#################### Initiated detailed services ####################//
    public function Detail_initiated_services($user_master_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_latlon as service_location,
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
					 A.id = '" . $service_order_id . "' AND A.serv_prov_id = '" . $user_master_id . "'
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
    //#################### Initiated detailed services End ####################//

    //#################### Ongoing detailed services ####################//
    public function Detail_ongoing_services($user_master_id, $service_order_id)
    {

        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') as order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') as resume_date,
        so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name as service_person,IFNULL(rs.from_time, '') as r_fr_time,IFNULL(rs.to_time, '') as r_to_time
        from service_orders as so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
        LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
        where so.serv_prov_id='$user_master_id' and so.id='$service_order_id' and (so.status='Hold' or so.status='Ongoing' Or so.status='Started')";
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

    //#################### Additional service orders ####################//
    public function Additional_service_orders($service_order_id)
    {
        $sQuery = "SELECT
						A.ad_service_rate_card,
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
						A.service_order_id = '" . $service_order_id . "' AND A.service_id = B.id AND B.main_cat_id = C.id AND B.sub_cat_id = D.id";
        $serv_result = $this
            ->db
            ->query($sQuery);

        $service_result = $serv_result->result();
        $service_count = $serv_result->num_rows();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Addtional Service list",
                "service_count" => $service_count,
                "service_list" => $service_result
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

    //#################### List completed services ####################//
    public function List_completed_services($user_master_id)
    {

        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') AS order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') AS resume_date,sppd.owner_full_name AS service_provider,
sp.status AS Payment_status,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name AS service_person,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time
    FROM service_orders AS so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details AS sppd ON so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments AS sp ON sp.service_order_id=so.id
    WHERE so.serv_prov_id='$user_master_id'  AND (so.status='Completed' OR so.status='Paid') order by so.id desc";
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
        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') AS order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') AS resume_date,sppd.owner_full_name AS service_provider,
sp.status AS Payment_status,so.finish_datetime,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name AS service_person,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time
    FROM service_orders AS so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details AS sppd ON so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments AS sp ON sp.service_order_id=so.id
    WHERE so.serv_prov_id='$user_master_id' AND so.id='$service_order_id' AND (so.status='Completed' OR so.status='Paid' OR so.status='Cancelled')";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        $addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '" . $service_order_id . "'";
        $addtional_serv_res = $this
            ->db
            ->query($addtional_serv);
        $addtional_serv_count = $addtional_serv_res->num_rows();

        $trans_query = "SELECT * FROM service_payments WHERE service_order_id = '$service_order_id'";
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
    
    //#################### Service bills list ####################//
    public function List_service_bills($user_master_id, $service_order_id)
    {
        $bill_url = base_url() . 'assets/bills/';

        $sQuery = "SELECT * FROM service_order_bills WHERE service_order_id = '" . $service_order_id . "'";
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

    //#################### Cancel service Reasons ####################//
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
    //#################### Cancel service Reasons End ####################//

    //#################### Cancel services ####################//
    public function Cancel_services($user_master_id, $service_order_id, $cancel_master_id, $comments)
    {

        $check_order = "SELECT * FROM service_order_history WHERE status='Expired' AND service_order_id='$service_order_id' AND serv_prov_id='$user_master_id'";
        $result_ch_order = $this
            ->db
            ->query($check_order);
        if ($result_ch_order->num_rows() == 0)
        {
            $update_sql = "UPDATE service_orders SET status = 'Cancelled', updated_by  = '" . $user_master_id . "', updated_at =NOW() WHERE id ='" . $service_order_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

            $select = "SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND serv_prov_id='$user_master_id'  AND status='Cancelled'";
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
            else
            {
                $sQuery = "UPDATE service_order_history SET status='Cancelled' WHERE service_order_id='$service_order_id' AND serv_prov_id='$user_master_id'";
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
                    $provider_id = $rows->serv_prov_id;
                }
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
                        $message = "ஸ்கிலெக்ஸ் உங்கள் சேவை கோரிக்கை ரத்து செய்யப்பட்டது. இதனால் ஏற்பட்ட சிரமத்திற்கு வருந்துகிறோம். மற்றொரு சேவை நபர் விரைவில் நியமிக்கப்படுவார்.";
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
                $phone = $contact_person_number;
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
                    "status" => "error",
                    "msg" => "Something Went Wrong"
                );
            }
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Something Went Wrong"
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
					 A.serv_prov_id = '" . $user_master_id . "' AND A.status = 'Cancelled' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id";
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
					 A.id = '" . $service_order_id . "' AND A.serv_prov_id = '" . $user_master_id . "' AND A.status = 'Cancelled' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id";
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

    //-------------------- Addtional Service order  details  -------------------//
    function view_addtional_service($user_master_id, $service_order_id)
    {
        $select = "SELECT s.id,s.service_name,s.service_ta_name,s.rate_card,s.service_pic,s.rate_card_details,s.rate_card_details_ta FROM  service_order_additional AS soa LEFT JOIN services AS s ON soa.service_id=s.id WHERE service_order_id='$service_order_id'";
        $res_offer = $this
            ->db
            ->query($select);
        if ($res_offer->num_rows() == 0)
        {
            $response = array(
                "status" => "error",
                "msg" => "No Service found",
                "msg_en" => "",
                "msg_ta" => ""
            );
        }
        else
        {
            $offer_result = $res_offer->result();
            foreach ($offer_result as $rows_service)
            {
                $service_pic = $rows_service->service_pic;
                if ($service_pic != '')
                {
                    $service_pic_url = base_url() . 'assets/category/' . $service_pic;
                }
                else
                {
                    $service_pic_url = '';
                }
                $service_list[] = array(
                    "id" => $rows_service->id,
                    "service_name" => $rows_service->service_name,
                    "service_ta_name" => $rows_service->service_ta_name,
                    "rate_card" => $rows_service->rate_card,
                    "rate_card_details" => $rows_service->rate_card_details,
                    "rate_card_details_ta" => $rows_service->rate_card_details_ta,
                    "service_pic" => $service_pic_url,
                );

            }

            $response = array(
                "status" => "success",
                "msg" => "service found",
                'service_list' => $service_list,
                "msg_en" => "",
                "msg_ta" => ""
            );
        }
        return $response;

    }
    //-------------------- Addtional Service order  details  -------------------//

    //#################### Vendor status update ####################//
    public function Vendor_status_update($serv_pro_id, $online_status, $serv_lat, $serv_lon)
    {
        $sql = "SELECT * FROM vendor_status WHERE serv_pro_id  ='" . $serv_pro_id . "'";
        $user_result = $this
            ->db
            ->query($sql);

        if ($user_result->num_rows() > 0)
        {
            $update_sql = "UPDATE vendor_status SET online_status = '" . $online_status . "', serv_lat  = '" . $serv_lat . "',serv_lon  = '" . $serv_lon . "' WHERE serv_pro_id ='" . $serv_pro_id . "'";
            $update_result = $this
                ->db
                ->query($update_sql);

        }
        else
        {
            $insert_sql = "INSERT INTO vendor_status (serv_pro_id, online_status, serv_lat, serv_lon, status, created_at, created_by ) VALUES ('" . $serv_pro_id . "','" . $online_status . "','" . $serv_lat . "','" . $serv_lon . "','Active',NOW(),'" . $serv_pro_id . "')";
            $insert_result = $this
                ->db
                ->query($insert_sql);

        }
        $response = array(
            "status" => "success",
            "msg" => "Vendor Status Update"
        );
        return $response;
    }
    //#################### Vendor status update End ####################//

    //#################### Transaction Details ####################//
    public function Transaction_details($user_master_id)
    {
        $sql = "SELECT id,total_service_per_day,serv_total_amount,serv_prov_commission_amt,skilex_commission_amt,online_transaction_amt,offline_transaction_amt,taxable_amount FROM daily_payment_transaction WHERE serv_prov_id = '" . $user_master_id . "' AND DATE(service_date) >= Date(NOW()) - INTERVAL 1 DAY";
        $tran_ress = $this
            ->db
            ->query($sql);

        if ($tran_ress->num_rows() > 0)
        {
            $yesterday_result = $tran_ress->result();
        }
        else
        {
            $yesterday_result = array(
                "total_service_per_day" => "0",
                "serv_total_amount" => "0",
                "serv_prov_commission_amt" => "0",
                "skilex_commission_amt" => "0",
                "online_transaction_amt" => "0",
                "offline_transaction_amt" => "0",
                "taxable_amount" => "0"

            );
        }

        $sQuery = "SELECT SUM(total_service_per_day) AS total_services,SUM(serv_total_amount) AS total_amount,SUM(serv_prov_commission_amt) AS total_serv_prov_commission,SUM(skilex_commission_amt) AS total_skilex_commission,SUM(online_transaction_amt) AS total_online_transaction,SUM(offline_transaction_amt) AS total_offline_transaction,SUM(taxable_amount) AS total_taxable_amount FROM daily_payment_transaction WHERE serv_prov_id = '" . $user_master_id . "' AND service_date <= Date(NOW()) - INTERVAL 2 DAY AND service_date < CURDATE()";
        $overall_ress = $this
            ->db
            ->query($sQuery);

        if ($overall_ress->num_rows() > 0)
        {
            $overall_result = $overall_ress->result();
        }
        else
        {
            $overall_result = "No Records Found";
        }

        $response = array(
            "status" => "success",
            "msg" => "Transaction Details",
            "yesterdayResult" => $yesterday_result,
            "overallResult" => $overall_result
        );
        return $response;
    }
    //#################### Transaction Details End ####################//

    //#################### Transaction list ####################//
    public function Transaction_list($user_master_id)
    {
        $sql = "SELECT id,DATE_FORMAT(service_date, '%e-%M-%Y') AS service_date,total_service_per_day,serv_total_amount,serv_prov_commission_amt,skilex_commission_amt,online_transaction_amt,offline_transaction_amt,taxable_amount,serv_prov_closing_status,pay_to_serv_prov,online_skilex_commission,offline_skilex_commission,online_serv_prov_commission,offline_serv_prov_commission,skilex_closing_status,serv_prov_closing_status  FROM daily_payment_transaction WHERE serv_prov_id = '" . $user_master_id . "'";
        $tran_ress = $this
            ->db
            ->query($sql);

        if ($tran_ress->num_rows() > 0)
        {
            $result = $tran_ress->result();
            foreach ($result as $rows_result)
            {
                if ($rows_result->pay_to_serv_prov <= 0)
                {
                    $pay_to_ser_provider_flag = "Yes";
                }
                else
                {
                    $pay_to_ser_provider_flag = "No";
                }

                $transaction_result[] = array(
                    "id" => $rows_result->id,
                    "total_service_per_day" => $rows_result->total_service_per_day,
                    "service_date" => $rows_result->service_date,
                    "serv_total_amount" => $rows_result->serv_total_amount,
                    "serv_prov_commission_amt" => $rows_result->serv_prov_commission_amt,
                    "skilex_commission_amt" => $rows_result->skilex_commission_amt + $rows_result->taxable_amount,
                    "online_transaction_amt" => $rows_result->online_transaction_amt,
                    "offline_transaction_amt" => $rows_result->offline_transaction_amt,
                    "online_skilex_commission" => $rows_result->online_skilex_commission,
                    "offline_skilex_commission" => $rows_result->offline_skilex_commission,
                    "online_serv_prov_commission" => $rows_result->online_serv_prov_commission,
                    "offline_serv_prov_commission" => $rows_result->offline_serv_prov_commission,
                    "taxable_amount" => $rows_result->taxable_amount,
                    "pay_to_serv_prov" => abs($rows_result->pay_to_serv_prov) ,
                    "pay_to_ser_provider_flag" => $pay_to_ser_provider_flag,
                    "skilex_closing_status" => $rows_result->skilex_closing_status,
                    "serv_prov_closing_status" => $rows_result->serv_prov_closing_status,

                );
            }
            $response = array(
                "status" => "success",
                "msg" => "Transaction List",
                "transactionResult" => $transaction_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "No Records Found"
            );
        }

        return $response;
    }
    //#################### Transaction list End ####################//

    //#################### View Transaction Details ####################//
    public function View_transaction_details($user_master_id, $daily_payment_id)
    {
        $sql = "SELECT id,total_service_per_day,DATE_FORMAT(service_date,'%d-%M-%Y') as service_date,serv_total_amount,serv_prov_commission_amt,skilex_commission_amt,online_transaction_amt,offline_transaction_amt,online_skilex_commission,offline_skilex_commission,online_serv_prov_commission,offline_serv_prov_commission,taxable_amount,pay_to_serv_prov,skilex_closing_status,serv_prov_closing_status,transaction_notes,order_id,ccavenue_track_id FROM daily_payment_transaction WHERE serv_prov_id = '$user_master_id' AND id='$daily_payment_id'";
        $tran_ress = $this
            ->db
            ->query($sql);

        if ($tran_ress->num_rows() > 0)
        {
            $result = $tran_ress->result();
            foreach ($result as $rows_result)
            {
            }
            if ($rows_result->pay_to_serv_prov < 0)
            {
                $pay_to_ser_provider_flag = "Yes";
            }
            else
            {
                $pay_to_ser_provider_flag = "No";
            }

            $transaction_result = array(
                "id" => $rows_result->id,
                "total_service_per_day" => $rows_result->total_service_per_day,
                "service_date" => $rows_result->service_date,
                "serv_total_amount" => $rows_result->serv_total_amount,
                "serv_prov_commission_amt" => $rows_result->serv_prov_commission_amt,
                "skilex_commission_amt" => $rows_result->skilex_commission_amt + $rows_result->taxable_amount,
                "online_transaction_amt" => $rows_result->online_transaction_amt,
                "offline_transaction_amt" => $rows_result->offline_transaction_amt,
                "online_skilex_commission" => $rows_result->online_skilex_commission,
                "offline_skilex_commission" => $rows_result->offline_skilex_commission,
                "online_serv_prov_commission" => $rows_result->online_serv_prov_commission,
                "offline_serv_prov_commission" => $rows_result->offline_serv_prov_commission,
                "taxable_amount" => $rows_result->taxable_amount,
                "pay_to_serv_prov" => abs($rows_result->pay_to_serv_prov) ,
                "pay_to_ser_provider_flag" => $pay_to_ser_provider_flag,
                "skilex_closing_status" => $rows_result->skilex_closing_status,
                "serv_prov_closing_status" => $rows_result->serv_prov_closing_status,
                "transaction_notes" => $rows_result->transaction_notes,
                "order_id" => $rows_result->order_id,
                "ccavenue_track_id" => $rows_result->ccavenue_track_id,

            );

            $response = array(
                "status" => "success",
                "msg" => "Transaction List",
                "transactionResult" => $transaction_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "No Records Found"
            );
        }

        return $response;
    }
    //#################### View Transaction Details End ####################//

    //#################### GET deposit amount ####################//
    function get_deposit_amt($user_master_id)
    {
        $sql = "SELECT deposit_amt FROM tax_commission WHERE id=1";
        $tran_ress = $this
            ->db
            ->query($sql);
        $result = $tran_ress->result();
        if ($tran_ress->num_rows() > 0)
        {
            foreach ($result as $row_deposit)
            {
            }

            $select = "SELECT * FROM service_provider_details where user_master_id='$user_master_id'";
            $res = $this
                ->db
                ->query($select);
            foreach ($res->result() as $rows_result)
            {
            }

            $refundable_deposit = $rows_result->refundable_deposit;
            if ($refundable_deposit == '0.00')
            {
                $deposit = $row_deposit->deposit_amt;
            }
            else
            {
                $deposit = intval($refundable_deposit);
            }

            $response = array(
                "status" => "success",
                "msg" => "Deposit amount",
                "deposit_data" => $deposit
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "No Records Found"
            );
        }

        return $response;
    }
    //#################### GET deposit amount ####################//

    //-------------------- Cancel  reason list   -------------------//
    function list_reason_for_cancel($user_master_id)
    {
        $select = "SELECT * FROM cancel_master WHERE user_type=3";
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

    function payment_notification($user_master_id, $provider_id, $expert_id)
    {

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
                $message = "Customer notification inside app check";
                $user_type = '5';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
        }
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$provider_id'";
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
                $message = "Provider notification inside app check.";
                $user_type = '3';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
        }
        $sQuery = "SELECT nm.*,lu.phone_no,lu.preferred_lang_id FROM notification_master as nm left join login_users as lu on lu.id=nm.user_master_id WHERE nm.user_master_id ='$expert_id'";
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
                $message = "Expert notification inside app check.";
                $user_type = '4';
                $this
                    ->smsmodel
                    ->send_push_notification($head, $message, $gcm_key, $mobile_type, $user_type);
            }
        }

    }

    // function add_skills_auto(){
    //   $query="SELECT spd.id,spd.user_master_id as provider_user_id,spd.owner_full_name,spps.user_master_id as person_user_id,spps.full_name from service_provider_details as spd left join service_person_details as spps on spps.full_name=spd.owner_full_name
    //   where spd.also_service_person='Y'";
    //   $user_result = $this->db->query($query);
    //   foreach($user_result->result() as $rows){
    //
    //     $check="SELECT * FROM serv_prov_pers_skills where user_master_id='$rows->person_user_id'";
    //     $res= $this->db->query($check);
    //     if($res->num_rows()==0){
    //
    //         $get_skills_from_provider="SELECT * FROM serv_prov_pers_skills where user_master_id='$rows->provider_user_id'";
    //         $res_skiles= $this->db->query($get_skills_from_provider);
    //         foreach($res_skiles->result() as $rows_skils){
    //
    //           $insert="INSERT INTO serv_prov_pers_skills (user_master_id,main_cat_id,status,created_at) VALUES ('$rows->person_user_id','$rows_skils->main_cat_id','Active',NOW())";
    //           $res_pers= $this->db->query($insert);
    //
    //
    //         }
    //
    //
    //     }else{
    //
    //     }
    //   }
    //
    // }
	
	
	//#################### Service Person List Assigned services ####################//
    public function Sp_list_assigned_services($serv_pers_id)
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
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.serv_pers_id = '" . $serv_pers_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id order by A.id desc";
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

    //#################### Service Person Assigned detailed services ####################//
    public function Sp_detail_assigned_services($serv_pers_id, $service_order_id)
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id  = '" . $serv_pers_id . "' AND A.status = 'Assigned' AND A.main_cat_id = B.id AND A.sub_cat_id = C.id AND A.service_id = D.id AND A.order_timeslot = E.id
           AND A.serv_pers_id = F.user_master_id";
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
    //#################### Aassigned detailed services End ####################//
    
	//#################### List Ongoing services ####################//
    public function Sp_list_ongoing_services($serv_pers_id)
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
					F.full_name AS service_person
				FROM
					service_orders A,
					main_category B,
					sub_category C,
					services D,
					service_timeslot E,
					service_person_details F
				WHERE
					 A.serv_pers_id = '$serv_pers_id' AND (A.status = 'Initiated' OR A.status = 'Started' OR A.status = 'Ongoing' OR A.status = 'Hold') AND A.main_cat_id = B.id AND A.sub_cat_id = C.id
           AND A.service_id = D.id AND A.order_timeslot = E.id AND A.serv_pers_id = F.user_master_id order by A.id desc";
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

    //#################### Initiated detailed services ####################//
    public function Sp_detail_initiated_services($serv_pers_id, $service_order_id)
    {
        $sQuery = "SELECT
					A.id,
					A.service_latlon as service_location,
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
					 A.id = '" . $service_order_id . "' AND A.serv_pers_id = '" . $serv_pers_id . "'
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
    //#################### Initiated detailed services End ####################//

    //#################### Ongoing detailed services ####################//
    public function Sp_detail_ongoing_services($serv_pers_id, $service_order_id)
    {

        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') as order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') as resume_date,
        so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name as service_person,IFNULL(rs.from_time, '') as r_fr_time,IFNULL(rs.to_time, '') as r_to_time
        from service_orders as so
        LEFT JOIN services AS s ON s.id=so.service_id
        LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
        LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
        LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
        LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
        LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
        where so.serv_pers_id ='$serv_pers_id' and so.id='$service_order_id' and (so.status='Hold' or so.status='Ongoing' Or so.status='Started')";
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
	
	
	//#################### List completed or cancelled services ####################//
    public function Sp_list_services_history($serv_pers_id)
    {
        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') AS order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') AS resume_date,sppd.owner_full_name AS service_provider,
sp.status AS Payment_status,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name AS service_person,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time
    FROM service_orders AS so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details AS sppd ON so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments AS sp ON sp.service_order_id=so.id
    WHERE so.serv_pers_id ='$serv_pers_id'  AND (so.status='Completed' OR so.status='Paid' OR OR so.status='Cancelled') order by so.id desc";
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
    //#################### List completed or cancelled services End ####################//

    //#################### Detail completed or cancelled services ####################//
    public function Sp_detail_services_history($serv_pers_id, $service_order_id)
    {
        $sQuery = "SELECT so.id,so.service_location,DATE_FORMAT(so.order_date, '%e-%m-%Y') AS order_date,DATE_FORMAT(so.resume_date, '%e-%m-%Y') AS resume_date,sppd.owner_full_name AS service_provider,
sp.status AS Payment_status,so.finish_datetime,so.contact_person_name,so.contact_person_number,so.service_rate_card,mc.main_cat_name,mc.main_cat_ta_name,sc.sub_cat_ta_name,sc.sub_cat_name,s.service_name,s.service_ta_name,st.from_time,st.to_time,so.status,so.start_datetime,so.material_notes,so.serv_prov_id,spd.full_name AS service_person,IFNULL(rs.from_time, '') AS r_fr_time,IFNULL(rs.to_time, '') AS r_to_time
    FROM service_orders AS so
    LEFT JOIN services AS s ON s.id=so.service_id
    LEFT JOIN main_category AS mc ON so.main_cat_id=mc.id
    LEFT JOIN sub_category AS sc ON so.sub_cat_id=sc.id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot AS rs ON rs.id=so.resume_timeslot
    LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
    LEFT JOIN service_provider_details AS sppd ON so.serv_prov_id=sppd.user_master_id
    LEFT JOIN service_payments AS sp ON sp.service_order_id=so.id
    WHERE so.serv_pers_id='$serv_pers_id' AND so.id='$service_order_id' AND (so.status='Completed' OR so.status='Paid' OR so.status='Cancelled')";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        $addtional_serv = "SELECT * FROM service_order_additional WHERE service_order_id = '" . $service_order_id . "'";
        $addtional_serv_res = $this
            ->db
            ->query($addtional_serv);
        $addtional_serv_count = $addtional_serv_res->num_rows();

        $trans_query = "SELECT * FROM service_payments WHERE service_order_id = '$service_order_id'";
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
    //#################### Detail completed or cancelled services End ####################//
	
	
	//#################### Track Order ####################//
    public function Sp_track_order($service_order_id)
    {
        $sQuery = "SELECT contact_person_name, contact_person_number,service_latlon,service_location,service_address FROM service_orders WHERE id= '$service_order_id'";
        $serv_result = $this
            ->db
            ->query($sQuery);
        $service_result = $serv_result->result();

        if ($serv_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Order Details",
                "detail_services_order" => $service_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Order Details Not found"
            );
        }
        return $response;
    }
    //#################### Track Order End ####################//
	
	//#################### Track Order ####################//
    public function Sp_verify_status($user_master_id,$status)
    {
		if ($status == 'Approved'){
				$sQuery = "SELECT
							lu.id AS user_master_id,
							spd.full_name,
							spd.serv_pers_verify_status,
							lu.phone_no
						FROM
							login_users AS lu
						LEFT JOIN service_person_details AS spd
						ON
							spd.user_master_id = lu.id
						WHERE
							lu.user_type = '4' AND spd.service_provider_id = '$user_master_id' AND spd.serv_pers_verify_status = 'Approved'
						ORDER BY
							spd.id DESC";
		} else {
			$sQuery = "SELECT
						lu.id AS user_master_id,
						spd.full_name,
						spd.serv_pers_verify_status,
						lu.phone_no
					FROM
						login_users AS lu
					LEFT JOIN service_person_details AS spd
					ON
						spd.user_master_id = lu.id
					WHERE
						lu.user_type = '4' AND spd.service_provider_id = '$user_master_id' AND spd.serv_pers_verify_status != 'Approved'
					ORDER BY
						spd.id DESC";
		}
        $person_result = $this
            ->db
            ->query($sQuery);
        $per_result = $person_result->result();

        if ($person_result->num_rows() > 0)
        {
            $response = array(
                "status" => "success",
                "msg" => "Service Person Details",
                "person_list" => $per_result
            );
        }
        else
        {
            $response = array(
                "status" => "error",
                "msg" => "Service Person Details Not found"
            );
        }
        return $response;
    }
    //#################### Track Order End ####################//
	
	
	//####################  Organization details ####################//
    public function Organization_details($user_master_id)
    {
		$sQuery = "SELECT * FROM service_provider_company_details WHERE user_master_id ='" . $user_master_id . "'";
		$comp_result = $this
			->db
			->query($sQuery);

		if ($comp_result->num_rows() != 0)
		{
			$response = array(
				"status" => "success",
				"msg" => "Company data found",
				"company_data" => $comp_result->result()
			);
		}
		else
		{

			$response = array(
				"status" => "error",
				"msg" => "No Company data found"
			);
		}
       return $response;
    }
    //#################### Organization details End ####################//
	
	
	//####################  Organization details ####################//
    public function Bank_details($user_master_id)
    {
		$sQuery = "SELECT bank_name,bank_branch_name,bank_acc_no,bank_ifsc_code FROM service_provider_details WHERE user_master_id ='" . $user_master_id . "'";
		$bank_result = $this
			->db
			->query($sQuery);

		if ($bank_result->num_rows() != 0)
		{
			$response = array(
				"status" => "success",
				"msg" => "Bank Details",
				"company_data" => $bank_result->result()
			);
		}
		else
		{

			$response = array(
				"status" => "error",
				"msg" => "No Records Found"
			);
		}
       return $response;
    }
    //#################### Organization details End ####################//
}

?>
