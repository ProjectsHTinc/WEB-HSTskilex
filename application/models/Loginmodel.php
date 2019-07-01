<?php

Class Loginmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


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
         $query="SELECT * From login_admin  WHERE admin_type='2' ORDER BY id DESC";
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


      function get_register_staff($name,$email,$phone,$username,$city,$qualification,$address,$gender,$status,$user_id){
        $digits = 8;
        $OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $password=md5($OTP);
        $select="SELECT * FROM login_admin Where email='$email'";
        $result=$this->db->query($select);
        if($result->num_rows()==0){
          $to=$email;
          $subject = '"Account Created"';
          $htmlContent = '
            <html>
            <head>  <title></title>
            </head>
            <body>
            <p>Hi  '.$name.'</p>
            <center><p>Hi..Please Use Below Username & Password to login</p></center>
              <table cellspacing="0" style="border:2px solid #000;width:300px;">
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

        // Set content-type header for sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // Additional headers
        $headers .= 'From: skilex<info@skilex.com>' . "\r\n";
        $sent= mail($to,$subject,$htmlContent,$headers);
          $insert="INSERT INTO login_admin (admin_type,name,password,email,phone,username,city,qualification,address,gender,status,created_by,created_at) VALUES ('2','$name','$password','$email','$phone','$username','$city','$qualification','$address','$gender','$status','$user_id',NOW())";
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



	   function update_profile($email,$phone,$name,$city,$address,$gender,$user_id){
			 $select = "UPDATE login_admin SET name='$name',phone='$phone',city='$city',qualification='$qualification',address='$address',gender='$gender',email='$email' WHERE id='$user_id'";
			$result = $this->db->query($select);
				if($result){
					$data = array("status" => "success");
				}else{
					$data = array("status" => "failed");
				}
			return $data;
       }


 	   function update_staff_profile($email,$phone,$name,$city,$address,$gender,$user_id,$id,$status){
       $staff_id=base64_decode($id)/98765;
 			 $select = "UPDATE login_admin SET name='$name',phone='$phone',city='$city',address='$address',gender='$gender',email='$email',status='$status' WHERE id='$staff_id'";
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
         $query="SELECT lu.*,cd.full_name,cd.gender,cd.profile_pic,cd.address FROM login_users AS lu LEFT JOIN  customer_details AS cd  ON lu.id=cd.user_master_id WHERE lu.id='$id'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }


}
?>
