<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apisperson extends CI_Controller {

		function __construct() {
			 parent::__construct();
				$this->load->model('apispersonmodel');
				$this->load->helper("url");
				$this->load->library('session');
	 }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$this->load->view('welcome_message');
	}


	public function checkMethod()
	{
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			$res = array();
			$res["scode"] = 203;
			$res["message"] = "Request Method not supported";

			echo json_encode($res);
			return FALSE;
		}
		return TRUE;
	}

//-----------------------------------------------//

	public function dashboard()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Dashboard";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->Dashboard($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function mobile_check()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Mobile Check";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$phone_no = '';

		$phone_no = $this->input->post("phone_no");

		$data['result']=$this->apispersonmodel->Mobile_check($phone_no);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function login()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Login";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id = '';
		$phone_no = '';
		$otp = '';
		$gcmkey ='';
		$mobiletype ='';

		$user_master_id = $this->input->post("user_master_id");
		$phone_no = $this->input->post("phone_no");
		$otp = $this->input->post("otp");
		$device_token = $this->input->post("device_token");
		$mobiletype = $this->input->post("mobile_type");

		$data['result']=$this->apispersonmodel->Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype);
		$response = $data['result'];
		echo json_encode($response);
	}


//-----------------------------------------------//

//-----------------------------------------------//

	public function email_verfication()
	{
	  $_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_master_id = $this->uri->segment(3);
		$dec_user_master_id = base64_decode($user_master_id);

		$data['result']=$this->apispersonmodel->Email_verfication($dec_user_master_id);
		
		if($data['result']['status']=='success'){
				echo "Success";
			}else{
				echo "Error";
		}

	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function email_verify_status()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Email Verify Status";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->Email_verifystatus($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function profile_update()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Profile Update";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id = '';
		$full_name = '';
		$gender = '';
		$address = '';
		$city = '';
		$state = '';
		$zip = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$full_name = $this->input->post("full_name");
		$gender  = $this->input->post("gender");
		$address  = $this->input->post("address");
		$city  = $this->input->post("city");
		$state  = $this->input->post("state");
		$zip  = $this->input->post("zip");
		$edu_qualification  = $this->input->post("edu_qualification");
		$language_known  = $this->input->post("language_known");

		$data['result']=$this->apispersonmodel->Profile_update($user_master_id,$full_name,$gender,$address,$city,$state,$zip,$edu_qualification,$language_known);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function profile_pic_upload()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_master_id = $this->uri->segment(3);
		$profile = $_FILES["profile_pic"]["name"];
		$profileFileName = time().'-'.$profile;
		$uploadPicdir = './assets/persons/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apispersonmodel->Profile_pic_upload($user_master_id,$profileFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//




//-----------------------------------------------//

	public function list_assigned_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List assigned services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->List_assigned_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function detail_assigned_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List assigned services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id  ='';
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Detail_assigned_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_ongoing_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List ongoing services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->List_ongoing_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function detail_initiated_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List assigned services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id  ='';
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Detail_initiated_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function detail_ongoing_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List assigned services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id  ='';
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Detail_ongoing_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function additional_service_orders()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List assigned services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$service_order_id  ='';
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Additional_service_orders($service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_completed_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Completed services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->List_completed_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function detail_completed_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Detail Completed services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id = '';	
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Detail_completed_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function cancel_service_reasons()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Cancel services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_type = '';
		$user_type  = $this->input->post("user_type");

		$data['result']=$this->apispersonmodel->Cancel_service_reasons($user_type);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function cancel_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Cancel services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id = '';
		$cancel_master_id = '';
		$comments = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$cancel_master_id  = $this->input->post("cancel_master_id");
		$comments  = $this->input->post("comments");

		$data['result']=$this->apispersonmodel->Cancel_services($user_master_id,$service_order_id,$cancel_master_id,$comments);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function list_canceled_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List canceled services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->List_canceled_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function detail_canceled_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Detail canceled services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id = '';	
		
		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Detail_canceled_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//




}
?>
