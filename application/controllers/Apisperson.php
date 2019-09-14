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
// 		$address = '';
		$email = '';
// 		$city = '';
// 		$state = '';
// 		$zip = '';

		$user_master_id  = $this->input->post("user_master_id");
		$full_name = $this->input->post("full_name");
		$gender  = $this->input->post("gender");
		$email  = $this->input->post("email");
// 		$address  = $this->input->post("address");
// 		$city  = $this->input->post("city");
// 		$state  = $this->input->post("state");
// 		$zip  = $this->input->post("zip");
// 		$edu_qualification  = $this->input->post("edu_qualification");
// 		$language_known  = $this->input->post("language_known");

// 		$data['result']=$this->apispersonmodel->Profile_update($user_master_id,$full_name,$gender,$address,$city,$state,$zip,$edu_qualification,$language_known);
		$data['result']=$this->apispersonmodel->Profile_update($user_master_id,$full_name,$gender,$email);
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
		$temp = pathinfo($profile, PATHINFO_EXTENSION);

		$profileFileName = time().'.'.$temp;
		$uploadPicdir = './assets/persons/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apispersonmodel->Profile_pic_upload($user_master_id,$profileFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function user_info()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Customer Profile Update";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apispersonmodel->user_info($user_master_id);
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

	public function initiate_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Initiat services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id  ='';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Initiate_services($user_master_id,$service_order_id);
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

	public function service_process()
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

		$data['result']=$this->apispersonmodel->Service_process($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function request_otp()
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

		$data['result']=$this->apispersonmodel->Request_otp($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function start_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Start services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$service_order_id  ='';
		$service_otp = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$service_otp = $this->input->post("service_otp");

		$data['result']=$this->apispersonmodel->Start_services($user_master_id,$service_order_id,$service_otp);
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
			$res["opn"] = "List started services";
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
			$res["opn"] = "Category list";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->Category_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function sub_category_list()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Sub category list";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$category_id  = '';

		$category_id  = $this->input->post("category_id");

		$data['result']=$this->apispersonmodel->Sub_category_list($category_id);
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
		$sub_category_id  = '';

		$category_id  = $this->input->post("category_id");
		$sub_category_id  = $this->input->post("sub_category_id");

		$data['result']=$this->apispersonmodel->Services_list($category_id,$sub_category_id);
		$response = $data['result'];
		echo json_encode($response);
	}


	/* public function services_list()
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

		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apispersonmodel->Services_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}*/

//-----------------------------------------------//


//-----------------------------------------------//

	public function add_addtional_services()
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

		$user_master_id  = '';
		$service_order_id  = '';
		$service_id  = '';
		$ad_service_rate_card  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$service_id  = $this->input->post("service_id");
		$ad_service_rate_card  = $this->input->post("ad_service_rate_card");

		$data['result']=$this->apispersonmodel->Add_addtional_services($user_master_id,$service_order_id,$service_id,$ad_service_rate_card);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function list_addtional_services()
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

		$user_master_id  = '';
		$service_order_id  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->List_addtional_services($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function remove_addtional_services()
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

		$user_master_id  = '';
		$order_additional_id  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$order_additional_id  = $this->input->post("order_additional_id");

		$data['result']=$this->apispersonmodel->Remove_addtional_services($user_master_id,$order_additional_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function upload_service_bills()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_master_id = $this->uri->segment(3);
		$service_order_id = $this->uri->segment(4);

		$document = $_FILES["bill_copy"]["name"];
		$extension  = end((explode(".", $document)));
		$documentFileName = $service_order_id.'-'.time().'.'.$extension ;
		$uploaddir = './assets/bills/';
		$documentFile = $uploaddir.$documentFileName;
		move_uploaded_file($_FILES['bill_copy']['tmp_name'], $documentFile);

		$data['result']=$this->apispersonmodel->Upload_service_bills($user_master_id,$service_order_id,$documentFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function list_service_bills()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "list service bills";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$service_order_id  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->List_service_bills($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function update_ongoing_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Update Ongoing Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$service_order_id  = '';
		$material_notes ='';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$material_notes  = $this->input->post("material_notes");

		$data['result']=$this->apispersonmodel->Update_ongoing_services($user_master_id,$service_order_id,$material_notes);
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


//-----------------------------------------------//

	public function complete_services()
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
		$service_order_id = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Complete_services($user_master_id,$service_order_id);
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

	public function add_tracking()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service person tracking";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id = '';
		$lat_1 = '';
		$lon_1 = '';
		$lat_1 = '';
		$location = '';
		$lat_2 = '';
		$lon_2 = '';
		$miles_distance_bw = '';
		$service_order_id  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$lat_1  = $this->input->post("lat_1");
		$lon_1  = $this->input->post("lon_1");
		$location  = $this->input->post("location");
		$lat_2  = $this->input->post("lat_2");
		$lon_2  = $this->input->post("lon_2");
		$miles_distance_bw  = $this->input->post("miles_distance_bw");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apispersonmodel->Add_tracking($user_master_id,$lat_1,$lon_1,$location,$lat_2,$lon_2,$miles_distance_bw,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//






}
?>
