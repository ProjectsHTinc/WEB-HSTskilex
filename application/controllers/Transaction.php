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
		 if($user_type=='1'){
			 $data['res']=$this->transactionmodel->get_daily_transaction();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/transactions/daily_transaction_details',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function update_trans_status(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $status=$this->input->post('status');
			 $id=$this->input->post('daily_id');
			 $transaction_notes=$this->input->post('transaction_notes');
			 $data['res']=$this->transactionmodel->update_trans_status($status,$id,$transaction_notes,$user_id);
			 echo json_encode( $data['res']);
		 }
	 }







}
