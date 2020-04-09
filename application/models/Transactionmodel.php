<?php

Class Transactionmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('smsmodel');


  }


  function daily_cron_job(){
    $sQuery = "SELECT
        so.serv_prov_id,
        so.order_date,
        COUNT(so.serv_prov_id) AS service_per_day,
        SUM(sp.net_service_amount) AS service_total_amt,
        SUM(sp.serv_pro_net_amount) AS serv_prov_comm_amt,
        SUM(sp.skilex_net_amount) AS skilex_comm_amt,
        SUM(sp.skilex_tax_amount) AS tax_able_amt,
        SUM(sp.online_amount+sp.wallet_amount) AS online_trans_amt,
        SUM(sp.offline_amount) AS offline_trans_amt,
        SUM(sp.online_amount+sp.wallet_amount * 0.2) AS online_skile_com_amt,
        SUM(sp.online_amount+sp.wallet_amount * 0.8) AS online_sp_com_amt,
        SUM(sp.offline_amount * 0.2) AS offline_skile_com_amt,
        SUM(sp.offline_amount * 0.8) AS offline_sp_com_amt,
        (SUM(sp.online_amount+sp.wallet_amount * 0.8) - SUM(sp.offline_amount * 0.2)) AS pay_to_serv
      FROM
      service_orders AS so
      LEFT JOIN service_payments AS sp  ON so.id = sp.service_order_id
      LEFT JOIN service_payment_history AS sphh ON sphh.payment_order_id = sp.id
      WHERE  so.order_date!=CURRENT_DATE() and sp.status = 'Paid'
      GROUP BY  so.serv_prov_id,so.order_date";
    $result=$this->db->query($sQuery);
    if($result->num_rows()==0){

    }else{
      foreach($result->result() as $rows){
        $serv_prov_id=$rows->serv_prov_id;
        $service_per_day=$rows->service_per_day;
        $order_date=$rows->order_date;
        $service_total_amt=$rows->service_total_amt;
        $serv_prov_comm_amt=$rows->serv_prov_comm_amt;
        $skilex_comm_amt=$rows->skilex_comm_amt;
        $tax_able_amt=$rows->tax_able_amt;
        $online_trans_amt=$rows->online_trans_amt;
        $offline_trans_amt=$rows->offline_trans_amt;
        $online_skile_com_amt=$rows->online_skile_com_amt;
        $online_sp_com_amt=$rows->online_sp_com_amt;
        $offline_skile_com_amt=$rows->offline_skile_com_amt;
        $offline_sp_com_amt=$rows->offline_sp_com_amt;
        $pay_to_serv=$rows->pay_to_serv;
        if($pay_to_serv < 0){
          $skilex_closing_status='Notreceived';
          $serv_prov_closing_status='Unpaid';
        }else{
          $skilex_closing_status='Unpaid';
          $serv_prov_closing_status='Notreceived';
        }


        $check="SELECT * FROM daily_payment_transaction WHERE serv_prov_id='$serv_prov_id' and service_date='$order_date'";
        $result_check=$this->db->query($check);
        if($result_check->num_rows()==0){
          $insQuery = "INSERT INTO daily_payment_transaction(serv_prov_id,total_service_per_day,service_date,serv_total_amount,serv_prov_commission_amt,
                  skilex_commission_amt,online_transaction_amt,offline_transaction_amt,online_skilex_commission,offline_skilex_commission,online_serv_prov_commission,offline_serv_prov_commission,taxable_amount,pay_to_serv_prov,skilex_closing_status,serv_prov_closing_status,created_at)
                VALUES('$serv_prov_id','$service_per_day','$order_date','$service_total_amt','$serv_prov_comm_amt','$skilex_comm_amt','$online_trans_amt','$offline_trans_amt','$online_skile_com_amt','$offline_skile_com_amt','$online_sp_com_amt','$offline_sp_com_amt','$tax_able_amt','$pay_to_serv','$skilex_closing_status','$serv_prov_closing_status',NOW())";
          $result_ins=$this->db->query($insQuery);
        }else{

        }


      }

    }

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

 function from_date_to_date_tax_details($from_date,$to_date){

	  $timestamp = strtotime($from_date);
      $from_date_new = date('Y-m-d', $timestamp);

      $timestamp_to_date = strtotime($to_date);
      $to_date_new = date('Y-m-d', $timestamp_to_date);

      $check="SELECT SUM(serv_total_amount) AS tot_amount,SUM(serv_prov_commission_amt) AS sp_commission,SUM(skilex_commission_amt) AS sk_commision,SUM(taxable_amount) AS tax_amount,'$from_date_new' AS from_date,'$to_date_new' AS to_date FROM daily_payment_transaction WHERE (service_date BETWEEN '$from_date_new' AND '$to_date_new')";
	  $result=$this->db->query($check);

      return $result->result();
    }

 function from_date_to_date_tax_list($from_date,$to_date){

      $check="SELECT spd.owner_full_name,dpt.* FROM daily_payment_transaction as dpt LEFT JOIN
      service_provider_details as spd on spd.user_master_id=dpt.serv_prov_id WHERE (service_date BETWEEN '$from_date' AND '$to_date') ORDER BY dpt.service_date DESC";
	  $result=$this->db->query($check);

      return $result->result();
    }












}
?>
