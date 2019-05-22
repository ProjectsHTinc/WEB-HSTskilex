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




		// Sub Category section

		public function create_sub_category(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$id=$this->uri->segment(3);
				$data['res']=$this->mastermodel->get_all_sub_category($id);
				$this->load->view('admin/admin_header');
				$this->load->view('admin/master/create_sub_category',$data);
				$this->load->view('admin/admin_footer');

			}
		}

		public function get_sub_category_edit(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $cat_id=$this->uri->segment(3);
			$data['res']=$this->mastermodel->get_sub_category_edit($cat_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/master/edit_sub_category',$data);
			$this->load->view('admin/admin_footer');
		 }else{
				redirect('/login');
		 }
		}



		public function sub_category_creation(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$sub_cat_name=$this->db->escape_str($this->input->post('sub_cat_name'));
				$main_cat_id=base64_decode($this->db->escape_str($this->input->post('main_cat_id')))/98765;
				$sub_cat_ta_name=$this->db->escape_str($this->input->post('sub_cat_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$profilepic = $_FILES['sub_cat_pic']['name'];
				if(empty($profilepic)){
				$cat_pic=' ';
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$cat_pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/category/';
				$profilepic = $uploaddir.$cat_pic;
				move_uploaded_file($_FILES['sub_cat_pic']['tmp_name'], $profilepic);
			}
				$data['res']=$this->mastermodel->sub_category_creation($sub_cat_name,$sub_cat_ta_name,$status,$cat_pic,$user_id,$main_cat_id);
				if($data['res']['status']=="success"){
					redirect('masters/create_sub_category/'.$this->input->post('main_cat_id').'');
				}else{
					redirect('masters/create_sub_category/'.$this->input->post('main_cat_id').'');
				}

			}
		}



		public function checksubcategory(){
			$sub_cat_name=$this->input->post('sub_cat_name');
			$data=$this->mastermodel->checksubcategory($sub_cat_name);
		}

		public function checksubcategorytamil(){
			$sub_cat_ta_name=$this->input->post('sub_cat_ta_name');
			$data=$this->mastermodel->checksubcategorytamil($sub_cat_ta_name);
		}


		public function sub_category_update(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type=='1'){
				$sub_cat_name=$this->db->escape_str($this->input->post('sub_cat_name'));
				$sub_cat_ta_name=$this->db->escape_str($this->input->post('sub_cat_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$cat_old_img=$this->db->escape_str($this->input->post('cat_old_img'));
				$cat_id=$this->db->escape_str($this->input->post('cat_id'));
				$main_cat_id=	base64_encode($this->input->post('main_cat_id')*98765);
				$profilepic = $_FILES['sub_cat_pic']['name'];
				if(empty($profilepic)){
				$cat_pic=$cat_old_img;
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$cat_pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/category/';
				$profilepic = $uploaddir.$cat_pic;
				move_uploaded_file($_FILES['sub_cat_pic']['tmp_name'], $profilepic);
			}
				$data['res']=$this->mastermodel->sub_category_update($sub_cat_name,$sub_cat_ta_name,$status,$cat_pic,$user_id,$cat_id);
				 if($data['res']['status']=="success"){
						redirect('masters/create_sub_category/'.$main_cat_id.'');
				 }else{
						redirect('masters/create_sub_category/'.$main_cat_id.'');
				 }

			}else {
				 redirect('/login');
			}
		}


		public function checksubcategoryexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$sub_cat_name=$this->input->post('sub_cat_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checksubcategoryexist($sub_cat_name,$id);
			}
		}

		public function checksubcategorytamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$sub_cat_ta_name=$this->input->post('sub_cat_ta_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checksubcategorytamilexist($sub_cat_ta_name,$id);
			}
		}



		// Create Service for sub Category


		public function create_service(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$id=$this->uri->segment(3);
				$data['res']=$this->mastermodel->get_all_service($id);
				$this->load->view('admin/admin_header');
				$this->load->view('admin/master/create_service',$data);
				$this->load->view('admin/admin_footer');

			}
		}


		public function service_creation(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$service_name=$this->db->escape_str($this->input->post('service_name'));
				$sub_cat_id=base64_decode($this->db->escape_str($this->input->post('sub_cat_id')))/98765;
				$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$profilepic = $_FILES['service_pic']['name'];
				if(empty($profilepic)){
				$cat_pic=' ';
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$cat_pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/category/';
				$profilepic = $uploaddir.$cat_pic;
				move_uploaded_file($_FILES['service_pic']['tmp_name'], $profilepic);
			}
				$data['res']=$this->mastermodel->service_creation($service_name,$service_ta_name,$status,$cat_pic,$user_id,$sub_cat_id);
				if($data['res']['status']=="success"){
					redirect('masters/create_service/'.$this->input->post('sub_cat_id').'');
				}else{
					redirect('masters/create_service/'.$this->input->post('sub_cat_id').'');
				}

			}
		}


		public function checkservice(){
			$service_name=$this->input->post('service_name');
			$data=$this->mastermodel->checkservice($service_name);
		}

		public function checkservicetamil(){
			$service_ta_name=$this->input->post('service_ta_name');
			$data=$this->mastermodel->checkservicetamil($service_ta_name);
		}


		public function get_service_edit(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'){
			 $cat_id=$this->uri->segment(3);
			$data['res']=$this->mastermodel->get_service_edit($cat_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/master/edit_service',$data);
			$this->load->view('admin/admin_footer');
		 }else{
				redirect('/login');
		 }
		}

		public function service_update(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type=='1'){
				$service_name=$this->db->escape_str($this->input->post('service_name'));
				$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$cat_old_img=$this->db->escape_str($this->input->post('cat_old_img'));
				$service_id=$this->db->escape_str($this->input->post('service_id'));
				$main_cat_id=	base64_encode($this->input->post('sub_cat_id')*98765);
				$profilepic = $_FILES['service_pic']['name'];
				if(empty($profilepic)){
				$cat_pic=$cat_old_img;
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$cat_pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/category/';
				$profilepic = $uploaddir.$cat_pic;
				move_uploaded_file($_FILES['service_pic']['tmp_name'], $profilepic);
			}
				$data['res']=$this->mastermodel->service_update($service_name,$service_ta_name,$status,$cat_pic,$user_id,$service_id);
				 if($data['res']['status']=="success"){
						redirect('masters/create_service/'.$main_cat_id.'');
				 }else{
						redirect('masters/create_service/'.$main_cat_id.'');
				 }

			}else {
				 redirect('/login');
			}
		}



		public function checkserviceexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$service_name=$this->input->post('service_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkserviceexist($service_name,$id);
			}
		}

		public function checkservicetamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			if($user_type== 1){
				$service_ta_name=$this->input->post('service_ta_name');
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkservicetamilexist($service_ta_name,$id);
			}
		}




}
