<?php

Class Transactionmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('smsmodel');


  }



  function get_daily_transaction(){
    $check="SELECT spd.owner_full_name,dpt.* FROM daily_payment_transaction as dpt LEFT JOIN
    service_provider_details as spd on spd.user_master_id=dpt.serv_prov_id ORDER BY dpt.service_date DESC";
    $result=$this->db->query($check);
    return $result->result();

    }





  function update_trans_status($status,$id,$transaction_notes,$user_id){
   $update="UPDATE daily_payment_transaction SET skilex_closing_status='Paid',serv_prov_closing_status='Received',transaction_notes='$transaction_notes',updated_at=NOW(),updated_by='$user_id' WHERE id='$id'";
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
