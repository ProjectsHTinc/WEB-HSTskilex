<?php

Class service_order_model extends CI_Model
{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('smsmodel');


  }



  function get_pending_orders(){
    $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,so.*
    FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN services AS s ON s.id=so.service_id WHERE so.status='Pending' GROUP BY so.id ORDER BY so.created_at DESC";
    $result=$this->db->query($check);
    return $result->result();

    }

    function get_ongoing_orders(){
       $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,stt.from_time as r_from_time,
       stt.to_time as r_to_time,so.* FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
      LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN service_timeslot as stt on stt.id=so.resume_timeslot
      LEFT JOIN services AS s ON s.id=so.service_id WHERE so.status!='Pending' AND so.status!='Paid' AND so.status!='Cancelled' AND so.status!='Completed' AND so.status!='Rejected' GROUP BY so.id ORDER BY so.created_at DESC";
      $result=$this->db->query($check);
      return $result->result();

      }

      function completed_orders(){
     $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,stt.from_time as r_from_time,
     stt.to_time as r_to_time,so.* FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
       LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN service_timeslot as stt on stt.id=so.resume_timeslot
        LEFT JOIN services AS s ON s.id=so.service_id WHERE so.status='Paid' OR so.status='Completed' GROUP BY so.id ORDER BY so.created_at DESC";
       $result=$this->db->query($check);
       return $result->result();
      }

      function cancelled_orders(){
     $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,stt.from_time as r_from_time,
     stt.to_time as r_to_time,so.* FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
       LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN service_timeslot as stt on stt.id=so.resume_timeslot LEFT JOIN services AS s ON s.id=so.service_id WHERE so.status='Cancelled' OR so.status='Rejected' GROUP BY so.id ORDER BY so.created_at DESC";
       $result=$this->db->query($check);
       return $result->result();
      }


      function get_cost_details($service_order_id){
          $id=base64_decode($service_order_id)/98765;
          $select="SELECT sp.*,om.offer_title,om.offer_code,so.order_date,so.id as so_id FROM service_payments as sp left join offer_master as om on om.id=sp.coupon_id left join service_orders as so on so.id=sp.service_order_id where service_order_id='$id'";
          $result=$this->db->query($select);
          return $result->result();

      }

  function get_order_details($service_order_id){
    $id=base64_decode($service_order_id)/98765;
    $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,stt.from_time as r_from_time,
    stt.to_time as r_to_time,so.* FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN service_timeslot as stt on stt.id=so.resume_timeslot LEFT JOIN services AS s ON s.id=so.service_id WHERE  so.id='$id'";
    $result=$this->db->query($check);
    return $result->result();
  }

  function get_ongoing_order_details($service_order_id){
    $id=base64_decode($service_order_id)/98765;
    $check="SELECT stt.from_time as r_from_time,
    stt.to_time as r_to_time,lu.phone_no,spd.owner_full_name,spp.full_name,spp.profile_pic,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,so.*
    FROM service_orders AS so
    LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id
    LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot
    LEFT JOIN service_timeslot as stt on stt.id=so.resume_timeslot
    LEFT JOIN service_provider_details as spd on spd.user_master_id=so.serv_prov_id
    LEFT JOIN service_person_details as spp on spp.user_master_id=so.serv_pers_id
    LEFT JOIN services AS s ON s.id=so.service_id WHERE  so.id='$id'";
    $result=$this->db->query($check);
    return $result->result();
  }


  function get_service_additional($service_order_id){
      $id=base64_decode($service_order_id)/98765;
      $query="SELECT soa.*,s.service_name FROM service_order_additional AS soa
      LEFT JOIN services AS s ON s.id=soa.service_id WHERE soa.status='Pending' AND service_order_id='$id'";
      $result=$this->db->query($query);
      return $result->result();
  }

  function get_service_bills($service_order_id){
      $id=base64_decode($service_order_id)/98765;
      $query="SELECT * FROM service_order_bills WHERE service_order_id='$id'";
      $result=$this->db->query($query);
      return $result->result();
  }



  function get_service_provider($service_order_id){
    $id=base64_decode($service_order_id)/98765;
    // $query="SELECT spd.owner_full_name,soh.* FROM service_order_history AS soh left join service_provider_details as spd on spd.user_master_id=soh.serv_prov_id
    // WHERE  service_order_id='$id' order by created_at desc";
    $query="SELECT soh.id,soh.serv_prov_id,IFNULL(spd.owner_full_name, sppd.full_name) AS name,lu.user_type,ur.role_name,soh.status,soh.created_at from service_order_history as soh
    left join service_provider_details as spd on spd.user_master_id=soh.serv_prov_id
    left join  service_person_details as sppd on sppd.user_master_id=soh.serv_prov_id
    left join login_users as lu on lu.id=soh.serv_prov_id
    left join user_role as ur on ur.id=lu.user_type
    where service_order_id='$id' ORDER by soh.id desc";
    $result=$this->db->query($query);
    return $result->result();
  }

  function get_customer_feedback($service_order_id){
    $id=base64_decode($service_order_id)/98765;

		$sql="SELECT * FROM service_orders WHERE id = '$id'";
		$resultset=$this->db->query($sql);
		$res=$resultset->result();
		foreach($res as $rows){
				$customer_id = $rows->customer_id;
			}
		$query="SELECT
				B.question,
				A.answer_text,
				A.service_order_id,
				A.user_master_id
			FROM
				feedback_response A,
				feedback_master B
			WHERE
				A.`service_order_id` = '$id' AND A.user_master_id ='$customer_id' AND A.query_id = B.id";
    $result=$this->db->query($query);
    return $result->result();
  }

  function get_expert_feedback($service_order_id){
    $id=base64_decode($service_order_id)/98765;

	$sql="SELECT * FROM service_orders WHERE id = '$id'";
		$resultset=$this->db->query($sql);
		$res=$resultset->result();
		foreach($res as $rows){
				$serv_pers_id = $rows->serv_pers_id;
			}
		$query="SELECT
				B.question,
				A.answer_text,
				A.service_order_id,
				A.user_master_id
			FROM
				feedback_response A,
				feedback_master B
			WHERE
				A.`service_order_id` = '$id' AND A.user_master_id ='$serv_pers_id' AND A.query_id = B.id";
    $result=$this->db->query($query);
    return $result->result();
  }

  function get_service_payments($service_order_id){
    $id=base64_decode($service_order_id)/98765;
    $query="SELECT  sp.*,om.offer_code FROM  service_payments as sp  left join offer_master as om on om.id=sp.coupon_id  WHERE service_order_id='$id'";
    $result=$this->db->query($query);
    return $result->result();
  }

  function get_payment_history($service_order_id){
    $id=base64_decode($service_order_id)/98765;
    $query="SELECT * FROM service_payment_history WHERE service_order_id='$id'";
    $result=$this->db->query($query);
    return $result->result();
  }

    function get_provider_list($service_order_id){
      $id=base64_decode($service_order_id)/98765;
      // $query="SELECT lu.id AS user_id,spd.owner_full_name,vs.*,so.main_cat_id FROM vendor_status AS vs
      // LEFT JOIN service_provider_details AS spd ON spd.user_master_id=vs.serv_pro_id
      // left join service_orders as so on so.id='$id'
      // LEFT JOIN serv_prov_pers_skills AS spps ON spd.user_master_id=spps.user_master_id
      // LEFT JOIN login_users AS lu ON lu.id=vs.serv_pro_id WHERE  EXISTS( SELECT * FROM service_order_history AS soh WHERE soh.service_order_id='$id' AND soh.serv_prov_id = vs.serv_pro_id) AND lu.status='Active'  and spps.main_cat_id=so.main_cat_id  GROUP by user_id";
      $query="SELECT lu.id AS user_id,spd.owner_full_name,vs.*,so.main_cat_id FROM vendor_status AS vs
      LEFT JOIN service_provider_details AS spd ON spd.user_master_id=vs.serv_pro_id
      left join service_orders as so on so.id='$id'
      LEFT JOIN serv_prov_pers_skills AS spps ON spd.user_master_id=spps.user_master_id
      LEFT JOIN login_users AS lu ON lu.id=vs.serv_pro_id WHERE lu.status='Active'  and spps.main_cat_id=so.main_cat_id  GROUP by user_id";
      $result=$this->db->query($query);
      return $result->result();
    }


    function get_cancel_details($service_order_id){
      $id=base64_decode($service_order_id)/98765;
      $query="SELECT cm.reasons,ur.role_name,ch.* FROM cancel_history as ch
      left join cancel_master as cm ON cm.id=ch.cancel_master_id
      left join user_role as ur ON ur.id=cm.user_type
      where ch.service_order_id='$id'";
      $result=$this->db->query($query);
      return $result->result();

    }

    function assign_orders($prov_id,$id,$user_id){
      $service_order_id=base64_decode($id)/98765;
      $select="SELECT * FROM login_users AS lu WHERE id='$prov_id'";
      $result=$this->db->query($select);
      $res=$result->result();
      foreach($res as $rows){}
        $phone_no=$rows->phone_no;
        $notes="You Received order from Customer.Please look into app for more details";
        $this->smsmodel->send_sms($phone_no,$notes);



        $update="UPDATE service_order_history SET status='Expired',created_at=NOW()  WHERE service_order_id='$service_order_id' AND (status='Requested' or status='Ongoing' or status='Accepted' or status='Initiated' or status='Assigned')";
        $res_update=$this->db->query($update);

         $select_expired_user="SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND status='Expired' ORDER BY created_at desc LIMIT 1";
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

        $check_provider_id="SELECT * FROM service_order_history WHERE service_order_id='$service_order_id' AND serv_prov_id='$prov_id'";
        $ex_prov_query=$this->db->query($check_provider_id);
        if($ex_prov_query->num_rows()>=1){
          $insert="UPDATE service_order_history SET status='Requested',created_at=NOW(),created_by='$user_id' WHERE service_order_id='$service_order_id' AND serv_prov_id='$prov_id'";
        }else{
          $insert="INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES('$service_order_id','$prov_id','Requested',NOW(),'$user_id')";
        }
        $res_inset=$this->db->query($insert);
        $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$prov_id'";
         $user_result = $this->db->query($sQuery);
         if ($user_result->num_rows() > 0) {
             foreach ($user_result->result() as $rows) {
               $gcm_key=$rows->mobile_key;
               $mobile_type=$rows->mobile_type;
               $head='Skilex';
               $message="You have assigned  order from customer.";
               $user_type='3';
               $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
             }
         }



        if($res_inset){


            $data = array("status" => "success");
              return $data;
        }else{
          $data = array("status" => "failed");
            return $data;
        }
    }



    function get_reviews($service_order_id){
      $id=base64_decode($service_order_id)/98765;
      $query="SELECT sr.* from service_reviews as sr  where sr.service_order_id='$id'";
      $result=$this->db->query($query);
      return $result->result();
    }


    function cancel_service_order_from_admin($id,$user_id){
      $service_order_id=base64_decode($id)/98765;
      $query="SELECT * from service_orders   WHERE id='$service_order_id'";
      $result=$this->db->query($query);
      $res=$result->result();
      foreach($res as $rows_res){}
        $serv_prov_id=$rows_res->serv_prov_id;
        $serv_person_id=$rows_res->serv_pers_id;
        $customer_id=$rows_res->customer_id;

        // $insert="INSERT INTO service_order_history (service_order_id,serv_prov_id,status,created_at,created_by) VALUES('$service_order_id','$user_id','Cancelled',NOW(),'$user_id')";
        // $res_ins=$this->db->query($insert);

        $insert="UPDATE  service_order_history SET status='Expired' WHERE service_order_id='$service_order_id' and status='Requested'";
        $res_ins=$this->db->query($insert);
        $update="UPDATE service_orders SET status='Cancelled' WHERE id='$service_order_id'";
        $res_update=$this->db->query($update);
        if($res_update){
           $response = array("status" => "success", "msg" => "Service order has been cancelled");
        }else{
             $response = array("status" => "error", "msg" => "Something went wrong!");
        }


        $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$customer_id'";
         $user_result = $this->db->query($sQuery);
         if ($user_result->num_rows() > 0) {
             foreach ($user_result->result() as $rows) {
               $gcm_key=$rows->mobile_key;
               $mobile_type=$rows->mobile_type;
               $head='Skilex';
               $message="Service Order has cancelled please contact the Skilex.";
               $user_type='5';
              $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
             }
         }

         if(!empty($serv_prov_id)){
           $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_prov_id'";
            $user_result = $this->db->query($sQuery);
            if ($user_result->num_rows() > 0) {
                foreach ($user_result->result() as $rows) {
                  $gcm_key=$rows->mobile_key;
                  $mobile_type=$rows->mobile_type;
                  $head='Skilex';
                  $message="Service Order has cancelled please contact the Skilex.";
                  $user_type='3';
                 $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                }
            }
         }
         if(!empty($serv_person_id)){
           $sQuery      = "SELECT * FROM notification_master WHERE user_master_id ='$serv_person_id'";
            $user_result = $this->db->query($sQuery);
            if ($user_result->num_rows() > 0) {
                foreach ($user_result->result() as $rows) {
                  $gcm_key=$rows->mobile_key;
                  $mobile_type=$rows->mobile_type;
                  $head='Skilex';
                  $message="Service Order has cancelled please contact the Skilex.";
                  $user_type='4';
                 $this->smsmodel->send_push_notification($head,$message,$gcm_key,$mobile_type,$user_type);
                }
            }
         }
         return $response;


    }








}
?>
