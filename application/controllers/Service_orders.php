<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_orders extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
				  $this->load->model('service_order_model');

	 }


	 public function pending_orders(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $data['res']=$this->service_order_model->get_pending_orders();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/pending_orders',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function ongoing_orders(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $data['res']=$this->service_order_model->get_ongoing_orders();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/ongoing_orders',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function get_order_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			$service_order_id=$this->uri->segment(3);
			$data['res']=$this->service_order_model->get_order_details($service_order_id);
			$data['res_additional']=$this->service_order_model->get_service_additional($service_order_id);
			$data['res_prov']=$this->service_order_model->get_service_provider($service_order_id);
			$data['res_payments']=$this->service_order_model->get_service_payments($service_order_id);
			$data['res_pay_history']=$this->service_order_model->get_payment_history($service_order_id);
			$data['res_provider_list']=$this->service_order_model->get_provider_list($service_order_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/orders/pending_order_details',$data);
			$this->load->view('admin/admin_footer');
		 }else{
			  redirect('/login');
		 }
	 }


	 public function get_ongoing_order_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			$service_order_id=$this->uri->segment(3);
			$data['res']=$this->service_order_model->get_order_details($service_order_id);
			$data['res_additional']=$this->service_order_model->get_service_additional($service_order_id);
			$data['res_prov']=$this->service_order_model->get_service_provider($service_order_id);
			$data['res_payments']=$this->service_order_model->get_service_payments($service_order_id);
			$data['res_pay_history']=$this->service_order_model->get_payment_history($service_order_id);
			$data['res_provider_list']=$this->service_order_model->get_provider_list($service_order_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/orders/ongoing_order_details',$data);
			$this->load->view('admin/admin_footer');
		 }else{
				redirect('/login');
		 }
	 }

	 public function assign_orders(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $prov_id=$this->input->post('prov_id');
			 $id=$this->input->post('id');
			 $data['res']=$this->service_order_model->assign_orders($prov_id,$id);
			 echo json_encode( $data['res']);
		 }
	 }

	 public function checkoffer_code_exist(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $offer_code=$this->input->post('offer_code');
			 $id=$this->uri->segment(3);
			 $data=$this->offersmodel->checkoffer_code_exist($offer_code,$id);
		 }
	 }


	 public function update_offers(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $offer_title=$this->db->escape_str($this->input->post('offer_title'));
			 $offer_code=$this->db->escape_str($this->input->post('offer_code'));
			 $offer_percent=$this->db->escape_str($this->input->post('offer_percent'));
			 $max_offer_amount=$this->db->escape_str($this->input->post('max_offer_amount'));
			 $offer_description=$this->db->escape_str($this->input->post('offer_description'));
			 $status=$this->db->escape_str($this->input->post('status'));
			 $offer_id=$this->db->escape_str($this->input->post('offer_id'));
			 $data['res']=$this->offersmodel->update_offers($offer_id,$offer_title,$offer_code,$offer_percent,$max_offer_amount,$offer_description,$status,$user_id);
			 if($data['res']['status']=="success"){
				 $this->session->set_flashdata('msg','Successfully Updated' );
				 redirect('offers/#list' );
			 }else{
				 $this->session->set_flashdata('msg',$data['res']['status']);
				 redirect('offers/#list');
			 }
		 }
	 }




}
