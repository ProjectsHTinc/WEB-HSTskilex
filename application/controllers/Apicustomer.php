<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomer extends CI_Controller {

		function __construct() {
			 parent::__construct();
				$this->load->model('apicustomermodel');
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
		$data['result']=$this->apicustomermodel->Mobile_check($phone_no);
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

		$data['result']=$this->apicustomermodel->Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype);
		$response = $data['result'];
		echo json_encode($response);
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

		$data['result']=$this->apicustomermodel->Email_verifystatus($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function email_verification()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Email Verification";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';

		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodel->Email_verification($user_master_id);
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
			$res["opn"] = "Customer Profile Update";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$full_name  = '';
		$gender  = '';
		$address  = '';
		$email  = '';

		$user_master_id  = $this->input->post("user_master_id");
		$full_name  = $this->input->post("full_name");
		$gender  = $this->input->post("gender");
		$address  = $this->input->post("address");
		$email  = $this->input->post("email");

		$data['result']=$this->apicustomermodel->Profile_update($user_master_id,$full_name,$gender,$address,$email);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

    public function profile_pic_upload()
	{
	  	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_master_id = $this->uri->segment(3);
		//$user_master_id = '3';
		$profile = $_FILES["profile_pic"]["name"];
		$profileFileName = time().'-'.$profile;
		$uploadPicdir = './assets/customers/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apicustomermodel->Profile_pic_upload($user_master_id,$profileFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function view_maincategory()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Main Category";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = '';
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodel->View_maincategory($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function view_subcategory()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Sub Category";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$main_cat_id  = '';
		$main_cat_id  = $this->input->post("main_cat_id");

		$data['result']=$this->apicustomermodel->View_subcategory($main_cat_id);
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
			$res["opn"] = "Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$main_cat_id  = '';
		$sub_cat_id  = '';

		$main_cat_id  = $this->input->post("main_cat_id");
		$sub_cat_id  = $this->input->post("sub_cat_id");

		$data['result']=$this->apicustomermodel->Services_list($main_cat_id,$sub_cat_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_details()
	{
		 $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$service_id  = '';

		$service_id  = $this->input->post("service_id");

		$data['result']=$this->apicustomermodel->service_details($service_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function add_service_to_cart()
	{
		 $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$category_id  = $this->input->post("category_id");
		$sub_category_id  = $this->input->post("sub_category_id");
		$service_id  = $this->input->post("service_id");

		$data['result']=$this->apicustomermodel->add_service_to_cart($user_master_id,$category_id,$sub_category_id,$service_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function remove_service_to_cart()
	{
		 $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$cart_id  = $this->input->post("cart_id");

		$data['result']=$this->apicustomermodel->remove_service_to_cart($cart_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//
//-----------------------------------------------//

	public function view_cart_summary()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Services";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodel->view_cart_summary($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function book_service()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service Order";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$customer_id  = '';
		$contact_person  = '';
		$main_cat_id  = '';
		$sub_cat_id  = '';
		$service_id  = '';
		$order_date  = '';
		$order_timeslot  = '';
		$service_latlon  = '';
		$service_location  = '';
		$service_address  = '';

		$customer_id  = $this->input->post("customer_id");
		$contact_person  = $this->input->post("contact_person");
		$main_cat_id  = $this->input->post("main_cat_id");
		$sub_cat_id  = $this->input->post("sub_cat_id");
		$service_id  = $this->input->post("service_id");
		$order_date  = $this->input->post("order_date");
		$order_timeslot  = $this->input->post("order_timeslot");
		$service_latlon  = $this->input->post("service_latlon");
		$service_location  = $this->input->post("service_location");
		$service_address  = $this->input->post("service_address");

		$data['result']=$this->apicustomermodel->Book_service($customer_id,$contact_person,$main_cat_id,$sub_cat_id,$service_id,$order_date,$order_timeslot,$service_latlon,$service_location,$service_address);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_order_list()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service Order List";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id  = '';

		$user_master_id  = $this->input->post("user_master_id");


		$data['result']=$this->apicustomermodel->Service_order_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_reviews_add()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service - Reviews Add";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		$user_master_id  = '';
		$service_order_id = '';
		$ratings = '';
		$reviews = '';

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$ratings  = $this->input->post("ratings");
		$reviews  = $this->input->post("reviews");

		$data['result']=$this->apicustomermodel->Service_reviewsadd($user_master_id,$service_order_id,$ratings,$reviews);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_reviews_list()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service - Reviews List";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$service_order_id = '';

		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apicustomermodel->Service_reviewslist($service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//
}
?>
