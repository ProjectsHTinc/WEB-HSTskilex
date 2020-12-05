<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
  class Export_model extends CI_Model {
 
    public function exportList($from_date,$to_date) {
			
	  $timestamp = strtotime($from_date);
      $from_date_new = date('Y-m-d', $timestamp);

      $timestamp_to_date = strtotime($to_date);
      $to_date_new = date('Y-m-d', $timestamp_to_date);

      $check="SELECT
				so.id AS so_id,
				so.order_date,
				so.contact_person_name,
				spv.owner_full_name AS spv_name,
				s.service_name,
				sp.paid_advance_amount,
				sp.service_amount,
				sp.ad_service_amount,
				sp.total_service_amount,
				sp.discount_amt,
				sp.net_service_amount,
				sp.serv_pro_net_amount,
				sp.skilex_net_amount,
				sp.skilex_tax_amount,
				sp.sgst_amount,
				sp.cgst_amount
			FROM
				service_payments AS sp
			LEFT JOIN offer_master AS om
			ON
				om.id = sp.coupon_id
			LEFT JOIN service_orders AS so
			ON
				so.id = sp.service_order_id
			LEFT JOIN services AS s
			ON
				s.id = so.service_id
			LEFT JOIN login_users AS lu
			ON
				lu.id = so.serv_prov_id
			LEFT JOIN service_provider_details AS spv
			ON
				lu.id = spv.user_master_id
			WHERE sp.status = 'Paid' AND (so.order_date BETWEEN '$from_date_new' AND '$to_date_new') ORDER BY so.order_date";

	    $result=$this->db->query($check);
		return $result->result();

        }
   }
?>