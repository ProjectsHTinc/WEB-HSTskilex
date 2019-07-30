<?php

Class Dashboardmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }


       function get_number_providers(){
         $query="SELECT count(*) as provider_count from login_users where user_type=3 and document_verify='Y' and status='Active'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_number_persons(){
         $query="SELECT count(*) as person_count from login_users where user_type=4 and document_verify='Y' and status='Active'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }
       function get_number_customer_count(){
         $query="SELECT count(*) as customer_count from login_users where user_type=5  and status='Active'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_number_paid_orders(){
         $query="SELECT count(*) as paid_orders from service_orders where status='Paid'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }
       function get_number_pending_orders(){
         $query="SELECT count(*) as pending_orders from service_orders where status='Pending'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }
       function get_number_cancelled_orders(){
         $query="SELECT count(*) as cancelled_orders from service_orders where status='Cancelled'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }
       function get_number_ongoing_orders(){
         $query="SELECT count(*) as ongoing_orders from service_orders where status!='Cancelled' and status!='Paid' and status!='Pending' and status!='Rejected' and status!='Completed'";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

       function get_total_transaction(){
         $query="SELECT sum(serv_total_amount) as total_transactions from daily_payment_transaction";
         $resultset=$this->db->query($query);
         return $resultset->result();
       }

}
?>
