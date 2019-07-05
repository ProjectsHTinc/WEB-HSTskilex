<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apisprovider extends CI_Controller {

		function __construct() {
			 parent::__construct();
				$this->load->model('apisprovidermodel');
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

	public function register()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Registration";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$name = '';
		$mobile = '';
		$email = '';

		$name = $this->input->post("name");
		$mobile = $this->input->post("mobile");
		$email = $this->input->post("email");

		$data['result']=$this->apisprovidermodel->Register($name,$mobile,$email);
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

		$data['result']=$this->apisprovidermodel->Mobile_check($phone_no);
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

		$data['result']=$this->apisprovidermodel->Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype);
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

		$data['result']=$this->apisprovidermodel->Email_verfication($dec_user_master_id);
		
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

		$data['result']=$this->apisprovidermodel->Email_verifystatus($user_master_id);
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

		$data['result']=$this->apisprovidermodel->Profile_update($user_master_id,$full_name,$gender,$address,$city,$state,$zip);
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
		$uploadPicdir = './assets/providers/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apisprovidermodel->Profile_pic_upload($user_master_id,$profileFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function category_list()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Main category list";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apisprovidermodel->Category_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function services_list()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Services list";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$category_id  = '';
		$category_id  = $this->input->post("category_id");

		$data['result']=$this->apisprovidermodel->Services_list($category_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function provider_add_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "User Services Add";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$category_id  = '';
		$sub_category_id  = '';
		$service_id  = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$category_id  = $this->input->post("category_id");
		$sub_category_id  = $this->input->post("sub_category_id");
		$service_id  = $this->input->post("service_id");

		$data['result']=$this->apisprovidermodel->Provider_add_services($user_master_id,$category_id,$sub_category_id,$service_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function update_company_status()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Company Status";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$company_status = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$company_status  = $this->input->post("company_status");

		$data['result']=$this->apisprovidermodel->Update_company_status($user_master_id,$company_status);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function add_individual_status()
	{
	   //$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Company Status";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$no_of_service_person = '';
		$also_service_person = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$no_of_service_person  = $this->input->post("no_of_service_person");
		$also_service_person  = $this->input->post("also_service_person");
		
		$data['result']=$this->apisprovidermodel->Add_individual_status($user_master_id,$no_of_service_person,$also_service_person);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function add_company_status()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Company Details Add";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id = '';
		$company_name = '';
		$no_of_service_person = '';
		$company_address = '';
		$company_city = '';
		$company_state = '';
		$company_zip  = '';
		$company_info = '';
		$company_building_type = '';
		
		$user_master_id  = $this->input->post("user_master_id");
		$company_name  = $this->input->post("company_name");
		$no_of_service_person  = $this->input->post("no_of_service_person");
		$company_address  = $this->input->post("company_address");
		$company_city  = $this->input->post("company_city");
		$company_state  = $this->input->post("company_state");
		$company_zip  = $this->input->post("company_zip");
		$company_info  = $this->input->post("company_info");
		$company_building_type  = $this->input->post("company_building_type");

		$data['result']=$this->apisprovidermodel->Add_company_status($user_master_id,$company_name,$no_of_service_person,$company_address,$company_city,$company_state,$company_zip,$company_info,$company_building_type);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_idaddress_proofs()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List master id, Address proofs";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$company_type = '';
		
		$company_type  = $this->input->post("company_type");

		$data['result']=$this->apisprovidermodel->List_idaddress_proofs($company_type);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function list_building_proofs()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List master Address proofs";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apisprovidermodel->List_building_proofs($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function upload_doc()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_master_id = $this->uri->segment(3);
		$doc_master_id = $this->uri->segment(4);
		$doc_proof_number = $this->uri->segment(5);
		
		$document = $_FILES["document_file"]["name"];
		$extension  = end((explode(".", $document)));
		$documentFileName = $user_master_id.'-'.time().'.'.$extension ;
		$uploaddir = './assets/providers/documents/';
		$documentFile = $uploaddir.$documentFileName;
		move_uploaded_file($_FILES['document_file']['tmp_name'], $documentFile);

		$data['result']=$this->apisprovidermodel->Upload_doc($user_master_id,$doc_master_id,$doc_proof_number,$documentFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function list_provider_doc()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Service Providers Documents";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
				
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apisprovidermodel->List_provider_doc($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

}
?>
