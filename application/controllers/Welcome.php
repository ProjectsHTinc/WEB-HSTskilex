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

		$this->load->view('admin/login.php');

	}




	public function forgotpassword()
	{

		$this->load->view('admin/forgot_password.php');

	}



}
