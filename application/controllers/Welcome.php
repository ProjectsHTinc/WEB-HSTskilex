<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');

	}



	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function login()
	{
		// $this->load->view('admin/admin_header.php');
		$this->load->view('admin/login.php');
		// $this->load->view('admin/admin_footer.php');
	}


	public function dashboard()
	{
		$this->load->view('admin/admin_header.php');
		$this->load->view('admin/login.php');
		$this->load->view('admin/admin_footer.php');
	}



}
