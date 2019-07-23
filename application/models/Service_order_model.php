<?php

Class service_order_model extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }



  function get_pending_orders(){
    $check="SELECT lu.phone_no,COUNT(soa.service_order_id) AS number_of_orders,st.from_time,st.to_time,s.service_name,so.*
    FROM service_orders AS so LEFT JOIN service_order_additional AS soa ON so.id = soa.service_order_id LEFT JOIN login_users AS  lu ON lu.id=so.customer_id
    LEFT JOIN service_timeslot AS st ON st.id=so.order_timeslot LEFT JOIN services AS s ON s.id=so.service_id WHERE so.status='Pending' GROUP BY so.id ORDER BY so.created_at DESC";
    $result=$this->db->query($check);
    return $result->result();

    }


  function get_all_offers(){
    $select="SELECT * FROM offer_master ORDER BY id DESC";
    $result=$this->db->query($select);
    return $result->result();
  }


  function get_offer_edit($offer_id){
    $id=base64_decode($offer_id)/98765;
    $select="SELECT * FROM offer_master WHERE id='$id'";
    $result=$this->db->query($select);
    return $result->result();
  }


  function checkoffer_title($offer_title){
    $select="SELECT * FROM offer_master Where offer_title='$offer_title'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
        echo "false";
        }else{
          echo "true";
      }
  }
  function checkoffer_code($offer_code){
    $select="SELECT * FROM offer_master Where offer_code='$offer_code'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
        echo "false";
        }else{
          echo "true";
      }
  }




    function checkoffer_title_exist($offer_title,$id){
       $select="SELECT * FROM offer_master Where offer_title='$offer_title' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }

    function checkoffer_code_exist($offer_code,$id){
       $select="SELECT * FROM offer_master Where offer_code='$offer_code' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }

    function update_offers($offer_id,$offer_title,$offer_code,$offer_percent,$max_offer_amount,$offer_description,$status,$user_id){
      $id=base64_decode($offer_id)/98765;
       $update="UPDATE offer_master SET offer_title='$offer_title',offer_code='$offer_code',offer_percent='$offer_percent',max_offer_amount='$max_offer_amount',offer_description='$offer_description',status='$status',created_by='$user_id',updated_at=NOW() WHERE id='$id'";
      $result=$this->db->query($update);
      if($result){
          $data = array("status" => "success");
            return $data;
      }else{
        $data = array("status" => "failed");
          return $data;
      }
    }






}
?>
