<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
				  $this->load->model('offersmodel');

	 }


	 public function index(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
			 $data['res']=$this->offersmodel->get_all_offers();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/master/offers/create_offers',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function create_offers(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
	 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
		  	$offer_title=$this->db->escape_str($this->input->post('offer_title'));
				$offer_code=$this->db->escape_str($this->input->post('offer_code'));
				$offer_percent=$this->db->escape_str($this->input->post('offer_percent'));
				$max_offer_amount=$this->db->escape_str($this->input->post('max_offer_amount'));
				$minimum_purchase_amt=$this->db->escape_str($this->input->post('minimum_purchase_amt'));
				$offer_description=$this->db->escape_str($this->input->post('offer_description'));
				$status=$this->db->escape_str($this->input->post('status'));
				$data['res']=$this->offersmodel->create_offers($offer_title,$offer_code,$offer_percent,$max_offer_amount,$minimum_purchase_amt,$offer_description,$status,$user_id);
				if($data['res']['status']=="success"){
					//$this->session->set_flashdata('msg','Successfully Added' );
					$messge = array('message' => 'Successfully Added','class' => 'alert alert-success fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('offers/#list' );
				}else{
					$messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('offers/#list');
				}

		}else {
			 redirect('/login');
		}
	 }


	 public function checkoffer_title(){
		 $offer_title=$this->input->post('offer_title');
		 $data=$this->offersmodel->checkoffer_title($offer_title);
	 }
	 public function checkoffer_code(){
		 $offer_code=$this->input->post('offer_code');
		 $data=$this->offersmodel->checkoffer_code($offer_code);
	 }



	 public function get_offer_edit(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			$offer_id=$this->uri->segment(3);
			$data['res']=$this->offersmodel->get_offer_edit($offer_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/master/offers/edit_offer',$data);
			$this->load->view('admin/admin_footer');
		 }else{
			  redirect('/login');
		 }
	 }

	 public function checkoffer_title_exist(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		  if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $offer_title=$this->input->post('offer_title');
			 $id=$this->uri->segment(3);
			 $data=$this->offersmodel->checkoffer_title_exist($offer_title,$id);
		 }
	 }

	 public function checkoffer_code_exist(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $offer_code=$this->input->post('offer_code');
			 $id=$this->uri->segment(3);
			 $data=$this->offersmodel->checkoffer_code_exist($offer_code,$id);
		 }
	 }


	 public function update_offers(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
	  if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $offer_title=$this->db->escape_str($this->input->post('offer_title'));
			 $offer_code=$this->db->escape_str($this->input->post('offer_code'));
			 $offer_percent=$this->db->escape_str($this->input->post('offer_percent'));
			 $max_offer_amount=$this->db->escape_str($this->input->post('max_offer_amount'));
			 $minimum_purchase_amt=$this->db->escape_str($this->input->post('minimum_purchase_amt'));
			 $offer_description=$this->db->escape_str($this->input->post('offer_description'));
			 $status=$this->db->escape_str($this->input->post('status'));
			 $offer_id=$this->db->escape_str($this->input->post('offer_id'));
			 $data['res']=$this->offersmodel->update_offers($offer_id,$offer_title,$offer_code,$offer_percent,$max_offer_amount,$minimum_purchase_amt,$offer_description,$status,$user_id);
			 if($data['res']['status']=="success"){
				 $messge = array('message' => 'Successfully Updated','class' => 'alert alert-success fade in');
				 $this->session->set_flashdata('msg', $messge);
				 redirect('offers/#list' );
			 }else{
				 $messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
				 $this->session->set_flashdata('msg', $messge);
				 redirect('offers/#list');
			 }
		 }
	 }




}
