<?php

Class Offersmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }



  function create_offers($offer_title,$offer_code,$offer_percent,$max_offer_amount,$minimum_purchase_amt,$offer_description,$status,$user_id){
    $check="SELECT * FROM offer_master WHERE offer_code='$offer_code'";
    $result=$this->db->query($check);
    if($result->num_rows()==0){

            $insert="INSERT INTO offer_master(offer_title,offer_code,offer_percent,max_offer_amount,minimum_purchase_amt,offer_description,status,created_at,created_by) VALUES('$offer_title','$offer_code','$offer_percent','$max_offer_amount','$minimum_purchase_amt','$offer_description','$status',NOW(),'$user_id')";
            $result=$this->db->query($insert);
            if($result){
                $data = array("status" => "success");
                  return $data;
            }else{
              $data = array("status" => "failed");
                return $data;
            }

      }else{

        $data = array("status" => "Already exist");
          return $data;
    }

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

    function update_offers($offer_id,$offer_title,$offer_code,$offer_percent,$max_offer_amount,$minimum_purchase_amt,$offer_description,$status,$user_id){
      $id=base64_decode($offer_id)/98765;
       $update="UPDATE offer_master SET offer_title='$offer_title',offer_code='$offer_code',offer_percent='$offer_percent',max_offer_amount='$max_offer_amount',minimum_purchase_amt='$minimum_purchase_amt',offer_description='$offer_description',status='$status',created_by='$user_id',updated_at=NOW() WHERE id='$id'";
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
