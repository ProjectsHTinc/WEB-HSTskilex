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
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $data['res']=$this->service_order_model->get_ongoing_orders();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/ongoing_orders',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function completed_orders(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $data['res']=$this->service_order_model->completed_orders();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/completed_orders',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function cancelled_orders(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $data['res']=$this->service_order_model->cancelled_orders();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/cancelled_orders',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function get_cost_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $service_order_id=$this->uri->segment(3);
			 $data['res']=$this->service_order_model->get_cost_details($service_order_id);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/orders/order_invoice',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 public function get_order_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			$service_order_id=$this->uri->segment(3);
			$data['res']=$this->service_order_model->get_ongoing_order_details($service_order_id);
			$data['res_additional']=$this->service_order_model->get_service_additional($service_order_id);
			$data['res_prov']=$this->service_order_model->get_service_provider($service_order_id);
			$data['res_payments']=$this->service_order_model->get_service_payments($service_order_id);
			$data['res_pay_history']=$this->service_order_model->get_payment_history($service_order_id);
			$data['res_provider_list']=$this->service_order_model->get_provider_list($service_order_id);
			$data['res_reviews']=$this->service_order_model->get_reviews($service_order_id);
			$data['res_bills']=$this->service_order_model->get_service_bills($service_order_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/orders/ongoing_order_details',$data);
			$this->load->view('admin/admin_footer');
		 }else{
				redirect('/login');
		 }
	 }


	 public function get_cancelled_order_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			$service_order_id=$this->uri->segment(3);
			$data['res']=$this->service_order_model->get_ongoing_order_details($service_order_id);
			$data['res_additional']=$this->service_order_model->get_service_additional($service_order_id);
			$data['res_prov']=$this->service_order_model->get_service_provider($service_order_id);
			$data['res_payments']=$this->service_order_model->get_service_payments($service_order_id);
			$data['res_pay_history']=$this->service_order_model->get_payment_history($service_order_id);
			$data['res_provider_list']=$this->service_order_model->get_provider_list($service_order_id);
			$data['res_cancel_list']=$this->service_order_model->get_cancel_details($service_order_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/orders/cancelled_order_details',$data);
			$this->load->view('admin/admin_footer');
		 }else{
				redirect('/login');
		 }
	 }

	 public function assign_orders(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $prov_id=$this->input->post('prov_id');
			 $id=$this->input->post('id');
			 $data['res']=$this->service_order_model->assign_orders($prov_id,$id);
			 echo json_encode( $data['res']);
		 }
	 }







}
