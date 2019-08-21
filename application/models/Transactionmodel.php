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


    function from_date_to_date($from_date,$to_date){
      $timestamp = strtotime($from_date);
      $from_date_new = date('Y-m-d', $timestamp);
      $timestamp_to_date = strtotime($to_date);
      $to_date_new = date('Y-m-d', $timestamp_to_date);
      $check="SELECT spd.owner_full_name,dpt.* FROM daily_payment_transaction as dpt LEFT JOIN
      service_provider_details as spd on spd.user_master_id=dpt.serv_prov_id WHERE (service_date BETWEEN '$from_date_new' AND '$to_date_new') ORDER BY dpt.service_date DESC";
     $result=$this->db->query($check);
      return $result->result();
    }

      function provider_based_transaction(){
     $check="SELECT spd.owner_full_name,sum(total_service_per_day) as total_service_per_day,sum(serv_prov_commission_amt) as serv_provider_total,sum(skilex_commission_amt) as skilex_commission_amt,sum(serv_total_amount) as serv_total_amount FROM daily_payment_transaction as dpt LEFT JOIN
        service_provider_details as spd on spd.user_master_id=dpt.serv_prov_id   GROUP BY  dpt.serv_prov_id";
        $result=$this->db->query($check);
        return $result->result();

        }

        function day_wise_transaction(){
       $check="SELECT service_date,sum(total_service_per_day) as service_per_day,sum(serv_total_amount) as total_amt  FROM daily_payment_transaction GROUP by service_date order by service_date desc";
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


  function online_payment_history(){
       $check="SELECT * FROM online_payment_history ORDER BY id desc";
       $result=$this->db->query($check);
       return $result->result();
  }

  function online_payment_details($online_id){
    $id=base64_decode($online_id)/98765;
     $check="SELECT * FROM online_payment_history WHERE id='$id'";
    $result=$this->db->query($check);
    return $result->result();
  }














}
?>
