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

	   function getadminuser($user_id){
         $query="SELECT ep.*,eu.* From edu_users as eu left join edu_staff_details as ep on eu.user_master_id=ep.id AND eu.user_type='1' OR eu.user_type='2' WHERE eu.user_id='$user_id'";
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
       function checkmobile($phone){
       $select="SELECT * FROM user_master Where mobile='$phone'";
         $result=$this->db->query($select);
         if($result->num_rows()>0){
           echo "false";
           }else{
             echo "true";
         }
       }
       function checkemail($email){
         $select="SELECT * FROM user_master Where email='$email'";
           $result=$this->db->query($select);
           if($result->num_rows()>0){
             echo "false";
             }else{
               echo "true";
           }
       }
       function check_ins_code($institute_code){
         $select="SELECT * FROM user_master Where institute_code='$institute_code'";
           $result=$this->db->query($select);
           if($result->num_rows()>0){
             echo "false";
             }else{
               echo "true";
           }
       }
       function check_ins_name($institute_name){
         $select="SELECT * FROM user_details Where institute_name='$institute_name'";
           $result=$this->db->query($select);
           if($result->num_rows()>0){
             echo "false";
             }else{
               echo "true";
           }
       }

       function check_otp($otp,$last_insert){
         $select="SELECT * FROM user_master Where mobile_otp='$otp' AND id='$last_insert'";
           $result=$this->db->query($select);
           if($result->num_rows()==1){
             $update="UPDATE user_master SET mobile_verify='Y' WHERE id='$last_insert'";
              $result=$this->db->query($update);
             $data = array("status" => "success","last_id"=>$last_insert);
             return $data;
             }else{
               $data = array("status" => "failed","msg"=>"Invalid OTP");
               return $data;
           }
       }

	   function update_profile($email,$phone,$name,$city,$address,$gender,$user_id){
			 $select = "UPDATE login_admin SET name='$name',phone='$phone',city='$city',address='$address',gender='$gender',email='$email' WHERE id='$user_id'";

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

       function forgot_password($email){
         $query="SELECT * FROM login_admin WHERE email='$email'";
         $result=$this->db->query($query);
         if($result->num_rows()==0){
           echo "Email Not found";
         }else{
        foreach($result->result() as $row){}
             $email= $row->email;
             $name= $row->name;
             $user_master_id= $row->id;
             $digits = 8;
             $OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
             $reset_pwd=md5($OTP);
             $reset="UPDATE login_admin SET password='$reset_pwd' WHERE id='$user_master_id'";
             $result_pwd=$this->db->query($reset);
             $query="SELECT * FROM login_admin WHERE id='$user_master_id'";
             $resultset=$this->db->query($query);
             foreach($resultset->result() as $rows){}
             $to=$email;
             $subject = '"Password Reset"';
             $htmlContent = '
               <html>
               <head>  <title></title>
               </head>
               <body>
               <p>Hi  '.$name.'</p>
               <center><p>Hi Your Account Password is Reset.Please Use Below Password to login</p></center>
                 <table cellspacing="0">

                       <tr>
                           <th>Password:</th><td>'.$OTP.'</td>
                       </tr>
                       <tr>
                           <th></th><td><a href="'.base_url() .'">Click here  to Login</a></td>
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
           if($sent){
             $data= array("status" => "success","msg" => "Password Sent to Registered Email");
             return $data;
           }else{
             $data= array("status" => "failed","msg" => "Invalid Username or Password");
             return $data;
           }
     }

       }


}
?>
