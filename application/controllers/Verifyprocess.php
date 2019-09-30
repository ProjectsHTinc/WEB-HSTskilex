<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifyprocess extends CI_Controller {
		function __construct() {
			 parent::__construct();
			    $this->load->helper('url');
			    $this->load->library('session');
					$this->_ci =& get_instance();
				  $this->load->model('verificationmodel');

	 }


	 public function get_vendor_verify_list(){

		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
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
		if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			$ser_pro_id=$this->uri->segment(3);
			$data['res']=$this->verificationmodel->get_vendor_details($ser_pro_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/verify/update_vendor_details',$data);
			$this->load->view('admin/admin_footer');
		}else {
			 redirect('/login');
		}
	 }


	 public function update_deposit_status(){
				$data=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_role');
				if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$status=$this->db->escape_str($this->input->post('status'));
				$id=$this->db->escape_str($this->input->post('id'));
				$data['res']=$this->verificationmodel->update_deposit_status($status,$id);
				echo json_encode($data['res']);
				}else {
				redirect('/login');
				}
	 }

	 public function update_serv_verify_status(){
			 $data=$this->session->userdata();
			 $user_id=$this->session->userdata('user_id');
			 $user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $status=$this->db->escape_str($this->input->post('status'));
			 $id=$this->db->escape_str($this->input->post('id'));
			 $data['res']=$this->verificationmodel->update_serv_verify_status($status,$id);
			 echo json_encode($data['res']);
			 }else {
			 redirect('/login');
			 }
	 }
	 public function update_serv_display_status(){
			 $data=$this->session->userdata();
			 $user_id=$this->session->userdata('user_id');
			 $user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $status=$this->db->escape_str($this->input->post('status'));
			 $id=$this->db->escape_str($this->input->post('id'));
			 $data['res']=$this->verificationmodel->update_serv_display_status($status,$id);
			 echo json_encode($data['res']);
			 }else {
			 redirect('/login');
			 }
	 }


	 public function get_vendor_doc_status(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			  $company_status=$this->uri->segment(3);
			 	$ser_pro_id=$this->uri->segment(4);
		  	 $data['res']=$this->verificationmodel->get_doc_details($ser_pro_id,$company_status);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/verify/document_list',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 public function update_doc_status(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $doc_status=$this->db->escape_str($this->input->post('doc_status'));
			 $doc_dd_id=$this->db->escape_str($this->input->post('doc_dd_id'));
			 $notes=$this->db->escape_str($this->input->post('notes'));
			 $data['res']=$this->verificationmodel->update_doc_status($doc_status,$doc_dd_id,$notes,$user_id);
			 echo json_encode($data['res']);
		 }else {
				redirect('/login');
		 }
	 }

	 public function doc_history(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
		 $doc_detail_id=base64_decode($this->uri->segment(3))/98765;
		 $data['res']=$this->verificationmodel->document_history($doc_detail_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/verify/document_history',$data);
			$this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 public function get_skills_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
		 $ser_user_id=$this->uri->segment(3);
		 $data['res']=$this->verificationmodel->get_service_skils_for_user($ser_user_id);
			$this->load->view('admin/admin_header');
			$this->load->view('admin/verify/skills_list',$data);
			$this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 	// Service Person

	 public function service_person_list(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
				$ser_pro_id=$this->uri->segment(3);
				$data['res']=$this->verificationmodel->service_person_list($ser_pro_id);
				$this->load->view('admin/admin_header');
				$this->load->view('admin/verify/service_person_list',$data);
				$this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }



	 public function person_doc_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $person_id=$this->uri->segment(3);
			 $data['res']=$this->verificationmodel->person_doc_details($person_id);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/verify/document_list',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }



	 public function service_person_details(){
		 $data=$this->session->userdata();
		 $user_id=$this->session->userdata('user_id');
		 $user_type=$this->session->userdata('user_role');
		 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $person_id=$this->uri->segment(3);
			 $data['res']=$this->verificationmodel->service_person_details($person_id);
			 $this->load->view('admin/admin_header');
			 $this->load->view('admin/verify/update_person_details',$data);
			 $this->load->view('admin/admin_footer');
		 }else {
				redirect('/login');
		 }
	 }


	 public function update_serv_person_verify_status(){
			 $data=$this->session->userdata();
			 $user_id=$this->session->userdata('user_id');
			 $user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $status=$this->db->escape_str($this->input->post('status'));
			 $id=$this->db->escape_str($this->input->post('id'));
			 $data['res']=$this->verificationmodel->update_serv_person_verify_status($status,$id);
			 echo json_encode($data['res']);
			 }else {
			 redirect('/login');
			 }
	 }
	 public function update_serv_person_display_status(){
			 $data=$this->session->userdata();
			 $user_id=$this->session->userdata('user_id');
			 $user_type=$this->session->userdata('user_role');
			 if($user_type=='1'||$user_type=='2'||$user_type=='7'){
			 $status=$this->db->escape_str($this->input->post('status'));
			 $id=$this->db->escape_str($this->input->post('id'));
			 $data['res']=$this->verificationmodel->update_serv_person_display_status($status,$id);
			 echo json_encode($data['res']);
			 }else {
			 redirect('/login');
			 }
	 }



}
