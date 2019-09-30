<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
				  $this->load->model('transactionmodel');

	 }


	 public function daily_transaction(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 	if($user_type=='1'||$user_type=='2'){
			 $data['res']=$this->transactionmodel->get_daily_transaction();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/daily_transaction_details',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function from_date_to_date(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'){
			 $from_date=$this->input->post('from_date');
			 $to_date=$this->input->post('to_date');
			 $data['res']=$this->transactionmodel->from_date_to_date($from_date,$to_date);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/from_date_to_date_transaction',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }

	 public function provider_based_transaction(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'){
			 $data['res']=$this->transactionmodel->provider_based_transaction();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/provider_based_transaction',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function from_date_and_to_date_transactions(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'){
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/datewise_transactions',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }

	 public function online_payment_history(){

		 		 $data=$this->session->userdata();
		 		 $user_id=$this->session->userdata('user_id');
		 		 $user_type=$this->session->userdata('user_role');
		 		 if($user_type=='1'||$user_type=='2'){
					 $data['res']=$this->transactionmodel->online_payment_history();
		 			 $this->load->view('admin/admin_header');
		 			 $this->load->view('admin/transactions/online_payment_history',$data);
		 			 $this->load->view('admin/admin_footer');
		 		 }else {
		 				redirect('/login');
		 		 }
	 }

	 public function day_wise_transaction(){

				 $data=$this->session->userdata();
				 $user_id=$this->session->userdata('user_id');
				 $user_type=$this->session->userdata('user_role');
				 if($user_type=='1'||$user_type=='2'){
					 $data['res']=$this->transactionmodel->day_wise_transaction();
					 $this->load->view('admin/admin_header');
					 $this->load->view('admin/transactions/day_wise_transaction',$data);
					 $this->load->view('admin/admin_footer');
				 }else {
						redirect('/login');
				 }
	 }

	 public function online_payment_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'){
			 $online_id=$this->uri->segment(3);
			 $data['res']=$this->transactionmodel->online_payment_details($online_id);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/online_payment_details',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 public function update_trans_status(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'){
			 $status=$this->input->post('status');
			 $id=$this->input->post('daily_id');
			 $transaction_notes=$this->input->post('transaction_notes');
			 $data['res']=$this->transactionmodel->update_trans_status($status,$id,$transaction_notes,$user_id);
			 echo json_encode( $data['res']);
		 }
	 }







}
