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

	 public function get_city_edit(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $city_id=$this->uri->segment(3);
			 	$data['res']=$this->mastermodel->get_city_edit($city_id);
			//	print_r($data['res']);exit;
			$this->load->view('admin/admin_header');
			$this->load->view('admin/master/edit_city',$data);
			$this->load->view('admin/admin_footer');
		 }else{
			  redirect('/login');
		 }
	 }

	 public function checkcityexist(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $city_name=$this->input->post('city_name');
			 $id=$this->uri->segment(3);
			 $data=$this->mastermodel->checkcityexist($city_name,$id);
		 }
	 }

	 public function checkcitytamilexist(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $city_ta_name=$this->input->post('city_ta_name');
			 $id=$this->uri->segment(3);
			 $data=$this->mastermodel->checkcitytamilexist($city_ta_name,$id);
		 }
	 }


	 public function update_locations(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type== 1){
			 $city_name=$this->db->escape_str($this->input->post('city_name'));
			 $city_ta_name=$this->db->escape_str($this->input->post('city_ta_name'));
			 $latitude=$this->db->escape_str($this->input->post('latitude'));
			 $longitude=$this->db->escape_str($this->input->post('longitude'));
			 $status=$this->db->escape_str($this->input->post('status'));
			 $city_id=$this->db->escape_str($this->input->post('city_id'));
			 $data['res']=$this->mastermodel->update_locations($city_name,$city_ta_name,$latitude,$longitude,$status,$city_id,$user_id);
			 echo json_encode($data['res']);
		 }
	 }



	 		// Category section



	 public function create_category(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $data['res']=$this->mastermodel->get_all_category();
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/master/create_category',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }

	 }


	 public function category_creation(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $main_cat_name=$this->db->escape_str($this->input->post('main_cat_name'));
			 $main_cat_ta_name=$this->db->escape_str($this->input->post('main_cat_ta_name'));
			 $status=$this->db->escape_str($this->input->post('status'));
			 $profilepic = $_FILES['cat_pic']['name'];
			 if(empty($profilepic)){
			 $cat_pic=' ';
		 }else{
			 $temp = pathinfo($profilepic, PATHINFO_EXTENSION);
			 $cat_pic = round(microtime(true)) . '.' . $temp;
			 $uploaddir = 'assets/category/';
			 $profilepic = $uploaddir.$cat_pic;
			 move_uploaded_file($_FILES['cat_pic']['tmp_name'], $profilepic);
		 }
			 $data['res']=$this->mastermodel->category_creation($main_cat_name,$main_cat_ta_name,$status,$cat_pic,$user_id);
				if($data['res']['status']=="success"){
					redirect('masters/create_category');
				}else{
					redirect('masters/create_category');
				}

		 }else {
				redirect('/login');
		 }

	 }


	 public function checkcategory(){
		 $main_cat_name=$this->input->post('main_cat_name');
		 $data=$this->mastermodel->checkcategory($main_cat_name);
	 }

	 public function checkcategorytamil(){
		 $main_cat_ta_name=$this->input->post('main_cat_ta_name');
		 $data=$this->mastermodel->checkcategorytamil($main_cat_ta_name);
	 }

	 public function get_category_edit(){
		$data=$this->session->userdata();
		$user_id=$this->session->userdata('user_id');
		$user_type=$this->session->userdata('user_role');
		if($user_type=='1'){
			$cat_id=$this->uri->segment(3);
			 $data['res']=$this->mastermodel->get_category_edit($cat_id);
		 $this->load->view('admin/admin_header');
		 $this->load->view('admin/master/edit_category',$data);
		 $this->load->view('admin/admin_footer');
		}else{
			 redirect('/login');
		}
	 }


		public function category_update(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type=='1'){
				$main_cat_name=$this->db->escape_str($this->input->post('main_cat_name'));
				$main_cat_ta_name=$this->db->escape_str($this->input->post('main_cat_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$cat_old_img=$this->db->escape_str($this->input->post('cat_old_img'));
				$cat_id=$this->db->escape_str($this->input->post('cat_id'));
				$profilepic = $_FILES['cat_pic']['name'];
				if(empty($profilepic)){
				$cat_pic=$cat_old_img;
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$cat_pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/category/';
				$profilepic = $uploaddir.$cat_pic;
				move_uploaded_file($_FILES['cat_pic']['tmp_name'], $profilepic);
			}
				$data['res']=$this->mastermodel->category_update($main_cat_name,$main_cat_ta_name,$status,$cat_pic,$user_id,$cat_id);
				 if($data['res']['status']=="success"){
					 redirect('masters/create_category');
				 }else{
					 redirect('masters/create_category');
				 }

			}else {
				 redirect('/login');
			}
		}


		public function checkcategoryexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$main_cat_name=$this->input->post('main_cat_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkcategoryexist($main_cat_name,$id);
			}
		}

		public function checkcategorytamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$main_cat_ta_name=$this->input->post('main_cat_ta_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkcategorytamilexist($main_cat_ta_name,$id);
			}
		}




}
