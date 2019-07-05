<?php

Class Verificationmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }




  function get_all_vendors(){
    $select="SELECT lu.id as user_master_id,lu.*,spd.* FROM login_users as lu left join service_provider_details as spd on spd.user_master_id=lu.id where lu.user_type='3' and document_verify='N' ORDER BY lu.id DESC";
    $result=$this->db->query($select);
    return $result->result();
  }

  function get_vendor_details($ser_pro_id){
    $id=base64_decode($ser_pro_id)/98765;
    $select="SELECT lu.id as user_master_id,lu.*,spd.* FROM login_users as lu left join service_provider_details as spd on spd.user_master_id=lu.id where lu.user_type='3' and lu.id='$id' and spd.user_master_id='$id'";
    $result=$this->db->query($select);
    return $result->result();
  }



}
?>
