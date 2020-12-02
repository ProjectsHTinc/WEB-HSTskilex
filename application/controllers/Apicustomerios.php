<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicustomerios extends CI_Controller {

		function __construct() {
			 parent::__construct();
				$this->load->model('apicustomermodelios');
				$this->load->model('smsmodel');
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

		public function version_check()
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


			$version_code = $this->input->post("version_code");
			$data['result']=$this->apicustomermodelios->version_check($version_code);
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
		$data['result']=$this->apicustomermodelios->Mobile_check($phone_no);
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
		$unique_number = $this->input->post("unique_number");
		$referral_code = $this->input->post("referral_code");

		$data['result']=$this->apicustomermodelios->Login($user_master_id,$phone_no,$otp,$device_token,$mobiletype,$unique_number,$referral_code);
		$response = $data['result'];
		echo json_encode($response);
	}


//-----------------------------------------------//


//-----------------------------------------------//

	public function guest_login()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Guest";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}



		$unique_number = $this->input->post("unique_number");
		$device_token = $this->input->post("mobile_key");
		$mobiletype = $this->input->post("mobile_type");
		$user_stat = $this->input->post("user_stat");

		$data['result']=$this->apicustomermodelios->guest_login($unique_number,$device_token,$mobiletype,$user_stat);
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

		$data['result']=$this->apicustomermodelios->Email_verifystatus($user_master_id);
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

		$data['result']=$this->apicustomermodelios->Email_verification($user_master_id);
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

		$data['result']=$this->apicustomermodelios->Profile_update($user_master_id,$full_name,$gender,$address,$email);
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
		$data['result']=$this->apicustomermodelios->user_info($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//
//-----------------------------------------------//

	public function user_lang_update()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
		$lang_id  = $this->input->post("lang_id");
		$data['result']=$this->apicustomermodelios->user_lang_update($user_master_id,$lang_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function add_referral_code()
	{
		 $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
		$referral_code  = $this->input->post("referral_code");
			$data['result']=$this->apicustomermodelios->add_referral_code($user_master_id,$referral_code);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function user_points_referral_code()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->user_points_referral_code($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function check_to_claim_points()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->check_to_claim_points($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function confirm_to_claim()
	{
		 $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->confirm_to_claim($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function check_wallet_balance_and_history()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->check_wallet_balance_and_history($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function top_trending_services()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->top_trending_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function service_rating_and_reviews()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
		$service_id  = $this->input->post("service_id");
		$data['result']=$this->apicustomermodelios->service_rating_and_reviews($user_master_id,$service_id);
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
		$temp = pathinfo($profile, PATHINFO_EXTENSION);

		$profileFileName = time().'.'.$temp;
		$uploadPicdir = './assets/customers/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apicustomermodelios->Profile_pic_upload($user_master_id,$profileFileName);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function view_banner_list()
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

		$data['result']=$this->apicustomermodelios->view_banner_list($user_master_id);
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
			$version_code  = $this->input->post("version_code");

		$data['result']=$this->apicustomermodelios->View_maincategory($user_master_id,$version_code);
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

		$data['result']=$this->apicustomermodelios->View_subcategory($main_cat_id);
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
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->Services_list($main_cat_id,$sub_cat_id,$user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function search_service()
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



	  $service_txt  = $this->input->post("service_txt");
		$service_txt_ta  = $this->input->post("service_txt_ta");
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->search_service($service_txt,$service_txt_ta,$user_master_id);
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

		$data['result']=$this->apicustomermodelios->service_details($service_id);
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


		$data['result']=$this->apicustomermodelios->add_service_to_cart($user_master_id,$category_id,$sub_category_id,$service_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function remove_service_from_cart()
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


		$data['result']=$this->apicustomermodelios->remove_service_from_cart($user_master_id,$category_id,$sub_category_id,$service_id);
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

		$data['result']=$this->apicustomermodelios->remove_service_to_cart($cart_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function clear_cart()
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

		$data['result']=$this->apicustomermodelios->clear_cart($user_master_id);
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

		$data['result']=$this->apicustomermodelios->view_cart_summary($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function view_time_slot()
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
		 $service_date=$this->input->post("service_date");


		$data['result']=$this->apicustomermodelios->view_time_slot($user_master_id,$service_date);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function proceed_to_book_order()
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
		$contact_person_name  = $this->input->post("contact_person_name");
		$contact_person_number  = $this->input->post("contact_person_number");
		$service_latlon  = $this->input->post("service_latlon");
		$service_location  = $this->input->post("service_location");
		$service_address  = $this->input->post("service_address");
		$order_date  = $this->input->post("order_date");
		$order_timeslot  = $this->input->post("order_timeslot_id");
			$order_notes  = $this->input->post("order_notes");
		$data['result']=$this->apicustomermodelios->proceed_to_book_order($user_master_id,$contact_person_name,$contact_person_number,$service_latlon,$service_location,$service_address,$order_date,$order_timeslot,$order_notes);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_advance_payment()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$service_id = $this->input->post("service_id");
		$data['result']=$this->apicustomermodelios->service_advance_payment($user_master_id,$service_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_order_status()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id = $this->input->post("service_order_id");
		$data['result']=$this->apicustomermodelios->service_order_status($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_provider_allocation()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}
	  $display_minute = $this->input->post("display_minute");

		$user_master_id  = $this->input->post("user_master_id");
		$order_id = $this->input->post("order_id");
		$result = explode("-", $order_id);
	 	$service_id= $result[2];

		// $data['result']=$this->apicustomermodelios->service_provider_allocation_ios($user_master_id,$service_id,$display_minute);
		// $response = $data['result'];
			$response =array('status'=>'success');
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_pending_and_offers_list()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->service_pending_and_offers_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//




//-----------------------------------------------//

	public function ongoing_services()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->ongoing_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function requested_services()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->requested_services($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_history()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->service_history($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_order_details()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$service_order_id  = $this->input->post("service_order_id");
		$data['result']=$this->apicustomermodelios->service_order_details($service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_order_summary()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$service_order_id  = $this->input->post("service_order_id");
		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->service_order_summary($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function view_addtional_service()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$service_order_id  = $this->input->post("service_order_id");
		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->view_addtional_service($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_reason_for_cancel()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->list_reason_for_cancel($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function cancel_service_order()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$service_order_id  = $this->input->post("service_order_id");
		$cancel_id  = $this->input->post("cancel_id");
		$user_master_id  = $this->input->post("user_master_id");
		$comments= $this->input->post("comments");
		$data['result']=$this->apicustomermodelios->cancel_service_order($user_master_id,$service_order_id,$cancel_id,$comments);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function service_coupon_list()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$data['result']=$this->apicustomermodelios->service_coupon_list($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//


		function apply_coupon_to_order(){

				$_POST = json_decode(file_get_contents("php://input"), TRUE);

				if(!$this->checkMethod())
				{
					return FALSE;
				}

				if($_POST == FALSE)
				{
					$res = array();
					$res["opn"] = "Service";
					$res["scode"] = 204;
					$res["message"] = "Input error";
					echo json_encode($res);
					return;
				}

				$user_master_id  = $this->input->post("user_master_id");
				$coupon_id  = $this->input->post("coupon_id");
				$service_order_id  = $this->input->post("service_order_id");

				$data['result']=$this->apicustomermodelios->apply_coupon_to_order($user_master_id,$coupon_id,$service_order_id);
				$response = $data['result'];
				echo json_encode($response);
		}


//-----------------------------------------------//


//-----------------------------------------------//


		function remove_coupon_from_order(){

				$_POST = json_decode(file_get_contents("php://input"), TRUE);

				if(!$this->checkMethod())
				{
					return FALSE;
				}

				if($_POST == FALSE)
				{
					$res = array();
					$res["opn"] = "Service";
					$res["scode"] = 204;
					$res["message"] = "Input error";
					echo json_encode($res);
					return;
				}

				$user_master_id  = $this->input->post("user_master_id");
				$service_order_id  = $this->input->post("service_order_id");
				$data['result']=$this->apicustomermodelios->remove_coupon_from_order($user_master_id,$service_order_id);
				$response = $data['result'];
				echo json_encode($response);
		}


//-----------------------------------------------//


//-----------------------------------------------//

	public function proceed_for_payment()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apicustomermodelios->proceed_for_payment($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_order_bills()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");

		$data['result']=$this->apicustomermodelios->service_order_bills($user_master_id,$service_order_id);
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

		$data['result']=$this->apicustomermodelios->Service_reviewsadd($user_master_id,$service_order_id,$ratings,$reviews);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function service_person_tracking()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service Tracking";
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
		$person_id  = $this->input->post("person_id");

		$data['result']=$this->apicustomermodelios->service_person_tracking($user_master_id,$person_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function pay_by_cash()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$order_id = $this->input->post("order_id");
		$result = explode("-", $order_id);
		$payment_id= $result[3];
		$service_id= $result[2];
		$amount  = $this->input->post("amount");
		$data['result']=$this->apicustomermodelios->pay_by_cash($user_master_id,$service_id,$payment_id,$amount);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//
//-----------------------------------------------//

	public function paid_on_wallet()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Service";
			$res["scode"] = 204;
			$res["message"] = "Input error";
			echo json_encode($res);
			return;
		}

		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$data['result']=$this->apicustomermodelios->paid_on_wallet($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//




//-----------------------------------------------//

	public function pay_using_wallet()
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



		$service_order_id  = $this->input->post("service_order_id");
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->pay_using_wallet($user_master_id,$service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function uncheck_from_wallet()
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



		$service_order_id  = $this->input->post("service_order_id");
		$user_master_id  = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->uncheck_from_wallet($user_master_id,$service_order_id);
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

		$data['result']=$this->apicustomermodelios->Service_reviewslist($service_order_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function service_payment_success()
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

		$order_id = $this->input->post("order_id");
		$result = explode("-", $order_id);
		$service_order_id= $result[2];
		$data['result']=$this->apicustomermodelios->service_payment_success($service_order_id);
		$response = $data['result'];
		// echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function customer_feedback_question()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
			$data['result']=$this->apicustomermodelios->customer_feedback_question($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function customer_feedback_answer()
	{
	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Input";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}


		$user_master_id  = $this->input->post("user_master_id");
		$service_order_id  = $this->input->post("service_order_id");
		$feedback_id  = $this->input->post("feedback_id");
		$feedback_text  = $this->input->post("feedback_text");
		$data['result']=$this->apicustomermodelios->customer_feedback_answer($user_master_id,$service_order_id,$feedback_id,$feedback_text);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function check_every_minute()
	{
	   // $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Minute";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_master_id = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->check_every_minute($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function automatic_provider_allocation()
	{
	   // $_POST = json_decode(file_get_contents("php://input"), TRUE);



		// $user_master_id = $this->input->post("user_master_id");

		$data['result']=$this->apicustomermodelios->automatic_provider_allocation();
		// $response = $data['result'];
		// echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

 	public function customer_address_add()
	{
 	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
 		{
 			return FALSE;
 		}

 		if($_POST == FALSE)
 		{
			$res = array();
			$res["opn"] = "Input";
 			$res["scode"] = 204;
			$res["message"] = "Input error";

 			echo json_encode($res);
			return;
 		}

 		$cust_id  = $this->input->post("cust_id");
		$contact_name  = $this->input->post("contact_name");
		$contact_no  = $this->input->post("contact_no");
		$serv_lat_lon  = $this->input->post("serv_lat_lon");
		$serv_loc  = $this->input->post("serv_loc");
		$serv_address  = $this->input->post("serv_address");

 		$data['result']=$this->apicustomermodel->customer_address_add($cust_id,$contact_name,$contact_no,$serv_lat_lon,$serv_loc,$serv_address);
 		$response = $data['result'];
 		echo json_encode($response);
 	}

//-----------------------------------------------//

//-----------------------------------------------//

 	public function customer_address_list()
	{
 	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
 		{
 			return FALSE;
 		}

 		if($_POST == FALSE)
 		{
			$res = array();
			$res["opn"] = "Input";
 			$res["scode"] = 204;
			$res["message"] = "Input error";

 			echo json_encode($res);
			return;
 		}

 		$cust_id  = $this->input->post("cust_id");

 		$data['result']=$this->apicustomermodel->customer_address_list($cust_id);
 		$response = $data['result'];
 		echo json_encode($response);
 	}

//-----------------------------------------------//

//-----------------------------------------------//

 	public function customer_address_edit()
	{
 	   $_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
 		{
 			return FALSE;
 		}

 		if($_POST == FALSE)
 		{
			$res = array();
			$res["opn"] = "Input";
 			$res["scode"] = 204;
			$res["message"] = "Input error";

 			echo json_encode($res);
			return;
 		}

 		$address_id  = $this->input->post("address_id");
		$contact_name  = $this->input->post("contact_name");
		$contact_no  = $this->input->post("contact_no");
		$serv_lat_lon  = $this->input->post("serv_lat_lon");
		$serv_loc  = $this->input->post("serv_loc");
		$serv_address  = $this->input->post("serv_address");

 		$data['result']=$this->apicustomermodel->customer_address_edit($address_id,$contact_name,$contact_no,$serv_lat_lon,$serv_loc,$serv_address);
 		$response = $data['result'];
 		echo json_encode($response);
 	}

//-----------------------------------------------//
}
?>
