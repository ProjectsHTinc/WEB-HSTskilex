<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifyprocess extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
				  $this->load->model('verificationmodel');

	 }


	 public function get_vendor_verify_list(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1' || $user_type=='2'){
			 $data['res']=$this->verificationmodel->get_all_vendors();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/verify/vendor_verify_list',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function get_vendor_details(){
		 $data=$this->session->userdata();
		$user_id=$this->session->userdata('user_id');
		$user_type=$this->session->userdata('user_role');
		if($user_type=='1' || $user_type=='2'){
			$ser_pro_id=$this->uri->segment(3);
			$data['res']=$this->verificationmodel->get_vendor_details($ser_pro_id);
			// echo "<pre>";
			print_r($data['res']);
			// exit;
			$this->load->view('admin/admin_header');
			$this->load->view('admin/verify/update_vendor_details',$data);
			$this->load->view('admin/admin_footer');
		}else {
			 redirect('/login');
		}
	 }


}
