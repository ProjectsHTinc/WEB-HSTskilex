<?php

Class Loginmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('mailmodel');


  }

       function check_login($username,$password)
       {
		  $query = "SELECT * FROM login_admin WHERE  username = '$username'";
      $resultset = $this->db->query($query);

		  if($resultset->num_rows()==1){
            $pwdcheck = "SELECT * FROM login_admin WHERE username = '$username' AND password='$password'";
            $res = $this->db->query($pwdcheck);

            if($res->num_rows()==1){
               foreach($res->result() as $rows){

                 $status= $rows->status;

                 switch($status){
                    case "Active":
		             $data = array("email"=>$rows->email,"mobile"=>$rows->phone,"msg"=>"success","user_role"=>$rows->admin_type,"status"=>"success","user_id"=>$rows->id);
							   $this->session->set_userdata($data);
                 return $data;

                    case "Inactive":
                          $data= array("status" => "failed","msg" => "Your Account Is De-Activated");
                          return $data;
                      break;

                      }
                   }

                 }
                 else{
                  $data= array("status" => "failed","msg" => "Invalid Username or Password");
                  return $data;
                 }
                 }

                else{
                  $data= array("status" => "failed","msg" => "Invalid Username or Password");
                  return $data;

            }

       }





       function get_user_info($user_id){
         $query="SELECT * From login_admin  WHERE id='$user_id'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_staff_details($staff_id){
        $id=base64_decode($staff_id)/98765;
       $query="SELECT * From login_admin  WHERE id='$id'";
        $resultset=$this->db->query($query);
        return $resultset->result();
       }

       function get_all_staff(){
         $query="SELECT ur.role_name,la.* FROM login_admin as la left join user_role as ur on ur.id=la.admin_type order by la.id desc";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }


       function update_password($current_password,$new_password,$confrim_password,$user_id){
            $pwd=md5($new_password);
            $query="UPDATE login_admin SET password='$pwd',	updated_at=NOW() WHERE id='$user_id'";
           $result=$this->db->query($query);
           if($result){
             $data = array("status" => "success");
           }else{
             $data = array("status" => "failed");
           }
           return $data;

       }



       function check_email_exist($email,$user_id){
         $select="SELECT * FROM login_admin Where email='$email' AND id!='$user_id'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
			        echo "false";
           }else{
             echo "true";
         }
       }
       function check_phone_exist($phone,$user_id){
         $select="SELECT * FROM login_admin Where phone='$phone' AND id!='$user_id'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
              echo "false";
           }else{
             echo "true";
         }
       }


       function check_staff_email_exist($email,$id){
         $select="SELECT * FROM login_admin Where email='$email' AND id!='$id'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
             echo "false";
           }else{
             echo "true";
         }
       }
       function check_staff_phone_exist($phone,$id){
         $select="SELECT * FROM login_admin Where phone='$phone' AND id!='$id'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
              echo "false";
           }else{
             echo "true";
         }
       }


       function checkphone($phone){
       $select="SELECT * FROM login_admin Where phone='$phone'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
           echo "false";
           }else{
             echo "true";
         }
       }
       function checkusername($username){
       $select="SELECT * FROM login_admin Where username='$username'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
           echo "false";
           }else{
             echo "true";
         }
       }
       function checkemail($email){
         $select="SELECT * FROM login_admin Where email='$email'";
           $result=$this->db->query($select);
           if($result->num_rows()>0){
             echo "false";
             }else{
               echo "true";
           }
       }

      function get_all_user_role(){
        $select="SELECT * FROM user_role where id IN (6,7,2)";
        $result=$this->db->query($select);
        return $result->result();
      }

      function get_register_staff($name,$email,$phone,$username,$city,$qualification,$address,$gender,$status,$user_id,$pic,$role_id){
        $digits = 8;
        $OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $password=md5($OTP);
        $select="SELECT * FROM login_admin Where email='$email'";
        $result=$this->db->query($select);
        if($result->num_rows()==0){
          $to=$email;
          $notes = '
            <html>
            <head>  <title></title>
            </head>
            <body>
            <p>Hi  '.$name.'</p>
            <p>Hi..Please Use Below Username & Password to login</p>
              <table cellspacing="0" style="border:2px solid #000;width:300px;margin: 40px;    padding: 15px;">
                    <tr>
                        <th>Username:</th><td>'.$username.'</td>
                    </tr>
                    <tr>
                        <th>Password:</th><td>'.$OTP.'</td>
                    </tr>
                    <tr>
                        <th></th><td><a href="'.base_url() .'login">Click here  to Login</a></td>
                    </tr>
                </table>
            </body>
            </html>';
          $this->mailmodel->send_mail($email,$notes);
          $insert="INSERT INTO login_admin (admin_type,profile_pic,name,password,email,phone,username,city,qualification,address,gender,status,created_by,created_at) VALUES ('$role_id','$pic','$name','$password','$email','$phone','$username','$city','$qualification','$address','$gender','$status','$user_id',NOW())";
            $resultset=$this->db->query($insert);
            if($resultset){
              $data = array("status" => "success");
              return $data;
            }else{
              $data = array("status" => "failed");
              return $data;
            }
        }else{
          $data = array("status" => "already exist");
          return $data;
        }
      }



	   function update_profile($email,$phone,$name,$city,$qualification,$address,$gender,$user_id){
			$select = "UPDATE login_admin SET name='$name',phone='$phone',city='$city',address='$address',gender='$gender',email='$email' WHERE id='$user_id'";
			$result = $this->db->query($select);
				if($result){
					$data = array("status" => "success");
				}else{
					$data = array("status" => "failed");
				}
			return $data;
       }


 	   function update_staff_profile($status,$id,$pic,$email,$phone,$name,$city,$qualification,$address,$gender,$user_id,$role_id){
       $staff_id=$id;
 			  $select = "UPDATE login_admin SET name='$name',phone='$phone',admin_type='$role_id',city='$city',address='$address',gender='$gender',email='$email',status='$status',qualification='$qualification',profile_pic='$pic' WHERE id='$staff_id'";
 		$result = $this->db->query($select);
 				if($result){
 					$data = array("status" => "success");
 				}else{
 					$data = array("status" => "failed");
 				}
 			return $data;
        }


       function check_current_password($current_password,$user_id){
         $pwd=$current_password;
         $select="SELECT * FROM login_admin Where password='$pwd' AND id='$user_id'";
           $result=$this->db->query($select);
           if($result->num_rows()==0){
             echo "false";
             }else{
               echo "true";
           }
       }

       function update_otp($phone,$otp){
         $query="SELECT * FROM login_admin WHERE phone='$phone'";
         $result=$this->db->query($query);
         if($result->num_rows()==0){
           echo "Mobile Number  Not found";
         }else{
           $query="UPDATE login_admin SET otp='$otp' WHERE phone='$phone'";
           $result=$this->db->query($query);
           if($result){
             $data= array("status" => "success","msg" => "Password Sent to Registered Mobile number");
             return $data;
           }else{
             $data= array("status" => "failed","msg" => "Invalid Mobile number");
             return $data;
           }
         }

       }

       function check_otp_password($cookie_phone,$phone_number_otp){
         $query="SELECT * FROM login_admin WHERE phone='$cookie_phone' AND otp='$phone_number_otp'";
         $result=$this->db->query($query);
         if($result->num_rows()==1){
           $data= array("status" => "success","msg" => "Otp Verified");
           return $data;
         }else{
             $data= array("status" => "failed","msg" => "Invalid Otp");
             return $data;
         }
       }

       function reset_password($cookie_phone,$new_password,$confrim_password){
         $query="UPDATE login_admin SET password='$new_password' WHERE phone='$cookie_phone'";
         $result=$this->db->query($query);
         if($result){
           $data= array("status" => "success","msg" => "Password Changed Successfully");
           return $data;
         }else{
           $data= array("status" => "failed","msg" => "Invalid Mobile number");
           return $data;
         }
       }




       function get_all_customer_details(){
         $query="SELECT lu.*,cd.* FROM login_users AS lu LEFT JOIN  customer_details AS cd  ON lu.id=cd.user_master_id WHERE lu.user_type=5 ORDER BY lu.id DESC";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_customer_details($cust_id){
          $id=base64_decode($cust_id)/98765;
         $query="SELECT lu.*,cd.full_name,cd.gender,cd.profile_pic FROM login_users AS lu LEFT JOIN  customer_details AS cd  ON lu.id=cd.user_master_id WHERE lu.id='$id'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_all_provider_list(){
         $query="SELECT lu.id,lu.status,spd.owner_full_name,vs.online_status,spd.company_status,lu.updated_at
          from login_users as lu
          left join service_provider_details as spd on spd.user_master_id=lu.id
          left join vendor_status as vs on vs.serv_pro_id=lu.id
          where lu.user_type=3 and spd.serv_prov_verify_status='Approved' ORDER BY lu.id DESC";
         $resultset=$this->db->query($query);
         return $resultset->result();

       }

       function get_all_person_list(){
         $query="SELECT lu.id,lu.status,spd.full_name,vs.online_status,lu.updated_at
         from login_users as lu
         left join service_person_details as spd on spd.user_master_id=lu.id
         left join vendor_status as vs on vs.serv_pro_id=lu.id
         where lu.user_type=4 and spd.serv_pers_verify_status='Approved' ORDER BY lu.id DESC";
         $resultset=$this->db->query($query);
         return $resultset->result();

       }

       function get_all_prov_person_list($pro_id){
          $id=base64_decode($pro_id)/98765;
        $query="SELECT lu.id,lu.status,spd.full_name,vs.online_status,lu.updated_at
        from login_users as lu
        left join service_person_details as spd on spd.user_master_id=lu.id
        left join vendor_status as vs on vs.serv_pro_id=lu.id
        where lu.user_type=4 and spd.serv_pers_verify_status='Approved' and service_provider_id='$id' ORDER BY lu.id DESC";
         $resultset=$this->db->query($query);
         return $resultset->result();

       }


       function get_provider_orders($p_id){
           $id=base64_decode($p_id)/98765;
         $query="SELECT so.order_date,spd.full_name,so.status,s.service_name,so.id,so.iniate_datetime FROM service_orders AS so
          LEFT JOIN services AS s ON s.id=so.service_id
          LEFT JOIN service_person_details AS spd ON spd.user_master_id=so.serv_pers_id
          WHERE so.serv_prov_id='$id' ORDER BY so.order_date desc";
          $resultset=$this->db->query($query);
          return $resultset->result();
       }

       function get_customer_orders($c_id){
           $id=base64_decode($c_id)/98765;
          $query="SELECT so.order_date,cd.full_name,so.status,s.service_name,so.id FROM service_orders AS so
          LEFT JOIN services AS s ON s.id=so.service_id
            LEFT JOIN customer_details AS cd ON cd.user_master_id=so.serv_pers_id
          WHERE so.customer_id='$id' ORDER BY so.order_date desc";
          $resultset=$this->db->query($query);
          return $resultset->result();
       }


       function contact_form($name,$email,$subject,$phone_number,$message){
         $insert="INSERT INTO tb_contact_form (name,email,subject,phone_number,message,created_at) VALUES ('$name','$email','$subject','$phone_number','$message',NOW())";
         $resultset=$this->db->query($insert);
         if($insert){
           $data=array("status"=>"success");
         }else{
           $data=array("status"=>"error");
         }
         return $data;
       }

       function newsletter_form($email){
         $select="SELECT * FROM tb_newsletter WHERE email_id='$email'";
         $res=$this->db->query($select);
         if($res->num_rows()==0){
           $insert="INSERT INTO tb_newsletter (email_id,status,created_at) VALUES ('$email','Active',NOW())";
           $resultset=$this->db->query($insert);
           if($insert){
             $data=array("status"=>"success");
           }else{
             $data=array("status"=>"error");
           }
         }else{
           $data=array("status"=>"You have already subscribed!");
         }


         return $data;
       }


       function get_contacted_list(){
         $select="SELECT * FROM tb_contact_form order by id desc";
         $res=$this->db->query($select);
         return $res->result();

       }

}
?>
