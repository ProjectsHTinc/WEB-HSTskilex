<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
				  $this->load->model('mastermodel');

	 }


	 public function create_city(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $data['res']=$this->mastermodel->get_all_locations();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/master/create_city',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function city_creation(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
		  	$city_name=$this->db->escape_str($this->input->post('city_name'));
				$city_ta_name=$this->db->escape_str($this->input->post('city_ta_name'));
				$latitude=$this->db->escape_str($this->input->post('latitude'));
				$longitude=$this->db->escape_str($this->input->post('longitude'));
				$status=$this->db->escape_str($this->input->post('status'));
				$data['res']=$this->mastermodel->city_creation($city_name,$city_ta_name,$latitude,$longitude,$status,$user_id);
				echo json_encode($data['res']);
		}else {
			 redirect('/login');
		}
	 }


	 public function checkcity(){
		 $city_name=$this->input->post('city_name');
		 $data=$this->mastermodel->checkcity($city_name);
	 }

	 public function checkcitytamil(){
		 $city_ta_name=$this->input->post('city_ta_name');
		 $data=$this->mastermodel->checkcityname($city_ta_name);
	 }





}
