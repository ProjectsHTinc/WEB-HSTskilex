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



	 	// Tax & Commission

		public function tax_commission(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'){
				 $data['res']=$this->mastermodel->get_all_tax_commission();
				$this->load->view('admin/admin_header');
				$this->load->view('admin/master/tax_commission',$data);
				$this->load->view('admin/admin_footer');
			 }else{
				 	redirect('/login');
			 }
		}


		public function update_tax_percentage(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'){
				 $sgst=$this->db->escape_str($this->input->post('sgst'));
				 $cgst=$this->db->escape_str($this->input->post('cgst'));
				 $data['res']=$this->mastermodel->update_tax_percentage($sgst,$cgst,$user_id);
				 echo json_encode($data['res']);
			 }else{
					redirect('/login');
			 }
		}

		public function update_commission_percentage(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'){
				 $internal_commission=$this->db->escape_str($this->input->post('internal_commission'));
				 $external_commission=$this->db->escape_str($this->input->post('external_commission'));
				 $data['res']=$this->mastermodel->update_commission_percentage($internal_commission,$external_commission,$user_id);
				 echo json_encode($data['res']);
			 }else{
					redirect('/login');
			 }
		}



	 		// Category section



	 public function create_category(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
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
					$messge = array('message' => 'New service category has been created','class' => 'alert alert-success fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_category');
				}else{
					$messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_category');
				}

		 }else {
				redirect('/login');
		 }

	 }


	 public function checkcategory(){
		 $main_cat_name=$this->db->escape_str($this->input->post('main_cat_name'));
		 $data=$this->mastermodel->checkcategory($main_cat_name);
	 }

	 public function checkcategorytamil(){
		 $main_cat_ta_name=$this->db->escape_str($this->input->post('main_cat_ta_name'));
		 $data=$this->mastermodel->checkcategorytamil($main_cat_ta_name);
	 }

	 public function get_category_edit(){
		$data=$this->session->userdata();
		$user_id=$this->session->userdata('user_id');
		$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
					 $messge = array('message' => 'Category has been Updated Successfully','class' => 'alert alert-success fade in');
 					 $this->session->set_flashdata('msg', $messge);
					 redirect('masters/create_category');
				 }else{
					 $messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					 $this->session->set_flashdata('msg', $messge);
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
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$main_cat_name=$this->db->escape_str($this->input->post('main_cat_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkcategoryexist($main_cat_name,$id);
			}
		}

		public function checkcategorytamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$main_cat_ta_name=$this->db->escape_str($this->input->post('main_cat_ta_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkcategorytamilexist($main_cat_ta_name,$id);
			}
		}




		// Sub Category section

		public function create_sub_category(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
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
		  if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
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
					$messge = array('message' => 'New service sub-category  has been created','class' => 'alert alert-success fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_sub_category/'.$this->input->post('main_cat_id').'');
				}else{
					$messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_sub_category/'.$this->input->post('main_cat_id').'');
				}

			}
		}



		public function checksubcategory(){
			$sub_cat_name=$this->db->escape_str($this->input->post('sub_cat_name'));
			$data=$this->mastermodel->checksubcategory($sub_cat_name);
		}

		public function checksubcategorytamil(){
			$sub_cat_ta_name=$this->db->escape_str($this->input->post('sub_cat_ta_name'));
			$data=$this->mastermodel->checksubcategorytamil($sub_cat_ta_name);
		}


		public function sub_category_update(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
					 $messge = array('message' => 'Sub category has been saved','class' => 'alert alert-success fade in');
					 $this->session->set_flashdata('msg', $messge);
						redirect('masters/create_sub_category/'.$main_cat_id.'');
				 }else{
					 $messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					 $this->session->set_flashdata('msg', $messge);
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
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$sub_cat_name=$this->db->escape_str($this->input->post('sub_cat_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checksubcategoryexist($sub_cat_name,$id);
			}
		}

		public function checksubcategorytamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$sub_cat_ta_name=$this->db->escape_str($this->input->post('sub_cat_ta_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checksubcategorytamilexist($sub_cat_ta_name,$id);
			}
		}



		// Create Service for sub Category


		public function create_service(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
				$service_name=$this->db->escape_str($this->input->post('service_name'));
				$sub_cat_id=base64_decode($this->db->escape_str($this->input->post('sub_cat_id')))/98765;
				$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
				$is_advance_payment=$this->db->escape_str($this->input->post('is_advance_payment'));
				$advance_amount=$this->db->escape_str($this->input->post('advance_amount'));
				$rate_card=$this->db->escape_str($this->input->post('rate_card'));
				$rate_card_details=$this->db->escape_str($this->input->post('rate_card_details'));
				$rate_card_details_ta=$this->db->escape_str($this->input->post('rate_card_details_ta'));
				$inclusions=$this->db->escape_str($this->input->post('inclusions'));
				$inclusions_ta=$this->db->escape_str($this->input->post('inclusions_ta'));
				$exclusion=$this->db->escape_str($this->input->post('exclusions'));
				$exclusions_ta=$this->db->escape_str($this->input->post('exclusions_ta'));
				$service_procedure=$this->db->escape_str($this->input->post('service_procedure'));
				$service_procedure_ta=$this->db->escape_str($this->input->post('service_procedure_ta'));
				$others=$this->db->escape_str($this->input->post('others'));
				$others_ta=$this->db->escape_str($this->input->post('others_ta'));

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
				$data['res']=$this->mastermodel->service_creation($service_name,$service_ta_name,$status,$cat_pic,$user_id,$sub_cat_id,$is_advance_payment,$advance_amount,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta);
				if($data['res']['status']=="success"){
					$messge = array('message' => 'New service  has been created','class' => 'alert alert-success fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_service/'.$this->input->post('sub_cat_id').'#list');
				}else{
					$messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					$this->session->set_flashdata('msg', $messge);
					redirect('masters/create_service/'.$this->input->post('sub_cat_id').'#list');
				}

			}
		}


		public function checkservice(){
			$service_name=$this->db->escape_str($this->input->post('service_name'));
			$data=$this->mastermodel->checkservice($service_name);
		}

		public function checkservicetamil(){
			$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
			$data=$this->mastermodel->checkservicetamil($service_ta_name);
		}


		public function get_service_edit(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$service_name=$this->db->escape_str($this->input->post('service_name'));
				$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
				$status=$this->db->escape_str($this->input->post('status'));
				$cat_old_img=$this->db->escape_str($this->input->post('cat_old_img'));
				$service_id=$this->db->escape_str($this->input->post('service_id'));
				$main_cat_id=	base64_encode($this->input->post('sub_cat_id')*98765);
				$is_advance_payment=$this->db->escape_str($this->input->post('is_advance_payment'));
				$advance_amount=$this->db->escape_str($this->input->post('advance_amount'));
				$rate_card=$this->db->escape_str($this->input->post('rate_card'));
				$rate_card_details=$this->db->escape_str($this->input->post('rate_card_details'));
				$rate_card_details_ta=$this->db->escape_str($this->input->post('rate_card_details_ta'));
				$inclusions=$this->db->escape_str($this->input->post('inclusions'));
				$inclusions_ta=$this->db->escape_str($this->input->post('inclusions_ta'));
				$exclusion=$this->db->escape_str($this->input->post('exclusions'));
				$exclusions_ta=$this->db->escape_str($this->input->post('exclusions_ta'));
				$service_procedure=$this->db->escape_str($this->input->post('service_procedure'));
				$service_procedure_ta=$this->db->escape_str($this->input->post('service_procedure_ta'));
				$others=$this->db->escape_str($this->input->post('others'));
				$others_ta=$this->db->escape_str($this->input->post('others_ta'));


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
				$data['res']=$this->mastermodel->service_update($service_name,$service_ta_name,$status,$cat_pic,$user_id,$service_id,$is_advance_payment,$advance_amount,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta);
				 if($data['res']['status']=="success"){
					 $messge = array('message' => 'Service  has been saved','class' => 'alert alert-success fade in');
					 $this->session->set_flashdata('msg', $messge);
						redirect('masters/create_service/'.$main_cat_id.'#list');
				 }else{
					 $messge = array('message' => 'Something Went Wrong','class' => 'alert alert-danger fade in');
					 $this->session->set_flashdata('msg', $messge);
						redirect('masters/create_service/'.$main_cat_id.'#list');
				 }

			}else {
				 redirect('/login');
			}
		}



		public function checkserviceexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$service_name=$this->db->escape_str($this->input->post('service_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkserviceexist($service_name,$id);
			}
		}

		public function checkservicetamilexist(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$service_ta_name=$this->db->escape_str($this->input->post('service_ta_name'));
				$id=$this->uri->segment(3);
				$data=$this->mastermodel->checkservicetamilexist($service_ta_name,$id);
			}
		}




		// Create banners for offers


		public function banner_list(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
				$data['res']=$this->mastermodel->get_banner();
				$this->load->view('admin/admin_header');
				$this->load->view('admin/master/banner/create',$data);
				$this->load->view('admin/admin_footer');

			}
		}


		public function create_banner(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
				$banner_title=$this->db->escape_str($this->input->post('banner_title'));
				$status=$this->db->escape_str($this->input->post('status'));
				$profilepic = $_FILES['banner_img']['name'];
				if(empty($profilepic)){
				$pic=' ';
			}else{
				$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
				$pic = round(microtime(true)) . '.' . $temp;
				$uploaddir = 'assets/banners/';
				$profilepic = $uploaddir.$pic;
				move_uploaded_file($_FILES['banner_img']['tmp_name'], $profilepic);
			}
			$data['res']=$this->mastermodel->create_banner($banner_title,$pic,$status,$user_id);
			if($data['res']['status']=="success"){
				$messge = array('message' => 'New banner  has been created','class' => 'alert alert-success fade in');
				$this->session->set_flashdata('msg', $messge);
				redirect('masters/banner_list');
			}else{
				$messge = array('message' => 'Something went wrong','class' => 'alert alert-danger fade in');
				$this->session->set_flashdata('msg', $messge);
				redirect('masters/banner_list');
			}
		}else{
			redirect('/');
		}
		}



		public function get_banner_edit(){
			$data=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
			 $ban_id=$this->uri->segment(3);
			 $data['res']=$this->mastermodel->get_banner_edit($ban_id);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/master/banner/edit',$data);
			 $this->load->view('admin/admin_footer');
		 }else{
			 	redirect('/');
		 }
	}



	public function update_banner(){
		$data=$this->session->userdata();
		$user_id=$this->session->userdata('user_id');
		$user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='6'||$user_type=='7'){
			$banner_title=$this->db->escape_str($this->input->post('banner_title'));
			$ban_id=$this->db->escape_str($this->input->post('ban_id'));
			$banner_old_img=$this->db->escape_str($this->input->post('banner_old_img'));
			$status=$this->db->escape_str($this->input->post('status'));
			$profilepic = $_FILES['banner_img']['name'];
			if(empty($profilepic)){
			$pic=$banner_old_img;
		}else{
			$temp = pathinfo($profilepic, PATHINFO_EXTENSION);
			$pic = round(microtime(true)) . '.' . $temp;
			$uploaddir = 'assets/banners/';
			$profilepic = $uploaddir.$pic;
			move_uploaded_file($_FILES['banner_img']['tmp_name'], $profilepic);
		}
		$data['res']=$this->mastermodel->update_banner($banner_title,$pic,$status,$ban_id,$user_id);
		if($data['res']['status']=="success"){
			$messge = array('message' => 'Banner Updated Successfully','class' => 'alert alert-success fade in');
			$this->session->set_flashdata('msg', $messge);
			redirect('masters/banner_list');
		}else{
			$messge = array('message' => 'Something went wrong','class' => 'alert alert-danger fade in');
			$this->session->set_flashdata('msg', $messge);
			redirect('masters/banner_list');
		}
	}else{
		redirect('/');
	}
	}




}
