<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apisprovider extends CI_Controller
{

    function __construct()
    {
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
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $res            = array();
            $res["scode"]   = 203;
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

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Dashboard";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->Dashboard($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function register()
    {
      $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Registration";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $name   = '';
        $mobile = '';
        $email  = '';

        $name   = $this->input->post("name");
        $mobile = $this->input->post("mobile");
        $email  = $this->input->post("email");

        $data['result'] = $this->apisprovidermodel->Register($name, $mobile, $email);
        $response       = $data['result'];
        echo json_encode($response);
    }


    //-----------------------------------------------//

    //-----------------------------------------------//

    public function mobile_check()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Mobile Check";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $phone_no = '';

        $phone_no = $this->input->post("phone_no");

        $data['result'] = $this->apisprovidermodel->Mobile_check($phone_no);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function login()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Login";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $phone_no       = '';
        $otp            = '';
        $gcmkey         = '';
        $mobiletype     = '';

        $user_master_id = $this->input->post("user_master_id");
        $phone_no       = $this->input->post("phone_no");
        $otp            = $this->input->post("otp");
        $device_token   = $this->input->post("device_token");
        $mobiletype     = $this->input->post("mobile_type");

        $data['result'] = $this->apisprovidermodel->Login($user_master_id, $phone_no, $otp, $device_token, $mobiletype);
        $response       = $data['result'];
        echo json_encode($response);
    }


    //-----------------------------------------------//

    //-----------------------------------------------//

    public function email_verfication()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $user_master_id     = $this->uri->segment(3);
        $dec_user_master_id = base64_decode($user_master_id);

        $data['result'] = $this->apisprovidermodel->Email_verfication($dec_user_master_id);

        if ($data['result']['status'] == 'success') {
            echo "Success";
        } else {
            echo "Error";
        }
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function email_verify_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Email Verify Status";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->Email_verifystatus($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function profile_update()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Profile Update";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $full_name      = '';
        $gender         = '';
        // $address        = '';
        $email = '';
        // $city           = '';
        // $state          = '';
        // $zip            = '';

        $user_master_id = $this->input->post("user_master_id");
        $full_name      = $this->input->post("full_name");
        $gender         = $this->input->post("gender");
        $email  = $this->input->post("email");
        // $address        = $this->input->post("address");
        // $city           = $this->input->post("city");
        // $state          = $this->input->post("state");
        // $zip            = $this->input->post("zip");

        $data['result'] = $this->apisprovidermodel->Profile_update($user_master_id, $full_name, $gender, $email);
        // $data['result'] = $this->apisprovidermodel->Profile_update($user_master_id, $full_name, $gender, $address, $city, $state, $zip);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function profile_pic_upload()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $user_master_id = $this->uri->segment(3);

        $profile   = $_FILES["profile_pic"]["name"];
        $temp = pathinfo($profile, PATHINFO_EXTENSION);

		$profileFileName = time().'.'.$temp;
		$uploadPicdir = './assets/providers/';
		$profilepic = $uploadPicdir.$profileFileName;
		move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilepic);

        $data['result'] = $this->apisprovidermodel->Profile_pic_upload($user_master_id, $profileFileName);
        $response       = $data['result'];
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
		$data['result']=$this->apisprovidermodel->user_info($user_master_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


    //-----------------------------------------------//

    public function provider_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Vendor Status";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $company_status = '';

        $user_master_id = $this->input->post("user_master_id");
        $sp_lat = $this->input->post("lat");
        $sp_lon = $this->input->post("lon");

        $data['result'] = $this->apisprovidermodel->Provider_status($user_master_id, $sp_lat, $sp_lon);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function category_list()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Main category list";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->Category_list($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function sub_category_list()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Sub category list";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $category_id = '';
        $category_id = $this->input->post("category_id");

        $data['result'] = $this->apisprovidermodel->Sub_category_list($category_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function services_list()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Services list";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $category_id     = '';
        $sub_category_id = '';

        $category_id     = $this->input->post("category_id");
        $sub_category_id = $this->input->post("sub_category_id");

        $data['result'] = $this->apisprovidermodel->Services_list($category_id, $sub_category_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function serv_prov_category_add()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "User Services Add";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $category_id    = '';

        $user_master_id = $this->input->post("user_master_id");
        $category_id    = $this->input->post("category_ids");


        $data['result'] = $this->apisprovidermodel->Serv_prov_category_add($user_master_id, $category_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    /* public function serv_prov_services_add()
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

    $data['result']=$this->apisprovidermodel->Serv_prov_services_add($user_master_id,$category_id,$sub_category_id,$service_id);
    $response = $data['result'];
    echo json_encode($response);
    }*/

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_prov_person_category()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "User Services List";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $category_id    = '';

        $user_master_id = $this->input->post("user_master_id");


        $data['result'] = $this->apisprovidermodel->List_prov_person_category($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function update_company_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Company Status";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $company_status = '';

        $user_master_id = $this->input->post("user_master_id");
        $company_status = $this->input->post("company_status");

        $data['result'] = $this->apisprovidermodel->Update_company_status($user_master_id, $company_status);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function add_individual_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Company Status";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id       = '';
        $no_of_service_person = '';
        $also_service_person  = '';

        $user_master_id       = $this->input->post("user_master_id");
        $no_of_service_person = $this->input->post("no_of_service_person");
        $also_service_person  = $this->input->post("also_service_person");

        $data['result'] = $this->apisprovidermodel->Add_individual_status($user_master_id, $no_of_service_person, $also_service_person);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function add_company_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Company Details Add";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id        = '';
        $company_name          = '';
        $no_of_service_person  = '';
        $company_address       = '';
        $company_city          = '';
        $company_state         = '';
        $company_zip           = '';
        $company_info          = '';
        $company_building_type = '';

        $user_master_id        = $this->input->post("user_master_id");
        $company_name          = $this->input->post("company_name");
        $no_of_service_person  = $this->input->post("no_of_service_person");
        $company_address       = $this->input->post("company_address");
        $company_city          = $this->input->post("company_city");
        $company_state         = $this->input->post("company_state");
        $company_zip           = $this->input->post("company_zip");
        $company_info          = $this->input->post("company_info");
        $company_building_type = $this->input->post("company_building_type");

        $data['result'] = $this->apisprovidermodel->Add_company_status($user_master_id, $company_name, $no_of_service_person, $company_address, $company_city, $company_state, $company_zip, $company_info, $company_building_type);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_idaddress_proofs()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List master id, Address proofs";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $company_type = '';

        $company_type = $this->input->post("company_type");

        $data['result'] = $this->apisprovidermodel->List_idaddress_proofs($company_type);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function list_building_proofs()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List master Address proofs";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_building_proofs($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function upload_doc()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $user_master_id   = $this->uri->segment(3);
        $doc_master_id    = $this->uri->segment(4);
        $doc_proof_number = $this->uri->segment(5);

        $document         = $_FILES["document_file"]["name"];
        $extension        = end((explode(".", $document)));
        $documentFileName = $user_master_id . '-' . time() . '.' . $extension;
        $uploaddir        = './assets/providers/documents/';
        $documentFile     = $uploaddir . $documentFileName;
        move_uploaded_file($_FILES['document_file']['tmp_name'], $documentFile);

        $data['result'] = $this->apisprovidermodel->Upload_doc($user_master_id, $doc_master_id, $doc_proof_number, $documentFileName);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    // ------------------Update service provider bank detail--------------------------- //

    public function update_provider_bank_detail()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Bank info update";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $user_master_id = '';
        $full_name      = '';
        $gender         = '';
        $address        = '';
        $city           = '';
        $state          = '';
        $zip            = '';

        $user_master_id = $this->input->post("user_master_id");
        $bank_name      = $this->input->post("bank_name");
        $branch_name    = $this->input->post("branch_name");
        $acc_no         = $this->input->post("acc_no");
        $ifsc_code      = $this->input->post("ifsc_code");

        $data['result'] = $this->apisprovidermodel->Update_provider_bank_detail($user_master_id, $bank_name, $branch_name, $acc_no, $ifsc_code);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    public function list_provider_doc()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List Service Providers Documents";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_provider_doc($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function provider_active_status()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List Service Providers Documents";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->provider_active_status_update($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function create_serv_person()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Create Service Persons";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $name           = '';
        $mobile         = '';
        $email          = '';

        $user_master_id = $this->input->post("user_master_id");
        $name           = $this->input->post("name");
        $mobile         = $this->input->post("mobile");
        $email          = $this->input->post("email");

        $data['result'] = $this->apisprovidermodel->Create_serv_person($user_master_id, $name, $mobile, $email);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function update_serv_person_details()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Create Service Persons";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id    = '';
        $serv_person_id    = '';
        $full_name         = '';
        $gender            = '';
        $address           = '';
        $city              = '';
        $pincode           = '';
        $state             = '';
        $language_known    = '';
        $edu_qualification = '';

        $user_master_id    = $this->input->post("user_master_id");
        $serv_person_id    = $this->input->post("serv_person_id");
        $full_name         = $this->input->post("full_name");
        $gender            = $this->input->post("gender");
        $address           = $this->input->post("address");
        $city              = $this->input->post("city");
        $pincode           = $this->input->post("pincode");
        $state             = $this->input->post("state");
        $language_known    = $this->input->post("language_known");
        $edu_qualification = $this->input->post("edu_qualification");


        $data['result'] = $this->apisprovidermodel->Update_serv_person_details($user_master_id, $serv_person_id, $full_name, $gender, $address, $city, $pincode, $state, $language_known, $edu_qualification);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_serv_persons()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List Service Persons Documents";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_serv_persons($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function serv_person_details()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Service Persons Details";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $serv_pres_id = '';

        $serv_pres_id = $this->input->post("serv_pres_id");

        $data['result'] = $this->apisprovidermodel->Serv_person_details($serv_pres_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function serv_person_upload_doc()
    {

        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        $user_master_id   = $this->uri->segment(3);
        $serv_person_id   = $this->uri->segment(4);
        $doc_master_id    = $this->uri->segment(5);
        $doc_proof_number = $this->uri->segment(6);

        $document         = $_FILES["document_file"]["name"];
        $extension        = end((explode(".", $document)));
        $documentFileName = $user_master_id . '-' . time() . '.' . $extension;
        $uploaddir        = './assets/persons/documents/';
        $documentFile     = $uploaddir . $documentFileName;
        move_uploaded_file($_FILES['document_file']['tmp_name'], $documentFile);

        $data['result'] = $this->apisprovidermodel->Serv_person_upload_doc($user_master_id, $serv_person_id, $doc_master_id, $doc_proof_number, $documentFileName);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_persons_doc()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List Service Persons Documents";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $serv_person_id = '';

        $serv_person_id = $this->input->post("serv_person_id");

        $data['result'] = $this->apisprovidermodel->List_persons_doc($serv_person_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function serv_pers_category_add()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "User Services Add";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';
        $serv_person_id = '';
        $category_id    = '';

        $user_master_id = $this->input->post("user_master_id");
        $serv_person_id = $this->input->post("serv_person_id");
        $category_id    = $this->input->post("category_ids");

        $data['result'] = $this->apisprovidermodel->Serv_pers_category_add($user_master_id, $serv_person_id, $category_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    /* public function serv_pers_services_add()
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
    $serv_person_id = '';
    $category_id  = '';
    $sub_category_id  = '';
    $service_id  = '';

    $user_master_id  = $this->input->post("user_master_id");
    $serv_person_id  = $this->input->post("serv_person_id");
    $category_id  = $this->input->post("category_id");
    $sub_category_id  = $this->input->post("sub_category_id");
    $service_id  = $this->input->post("service_id");

    $data['result']=$this->apisprovidermodel->Serv_pers_services_add($user_master_id,$serv_person_id,$category_id,$sub_category_id,$service_id);
    $response = $data['result'];
    echo json_encode($response);
    } */

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_requested_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List requested services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_requested_services($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function detail_requested_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_requested_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function accept_requested_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Accept requested services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Accept_requested_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function assigned_accepted_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Assigned Accepted services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id    = '';
        $service_order_id  = '';
        $service_person_id = '';

        $user_master_id    = $this->input->post("user_master_id");
        $service_order_id  = $this->input->post("service_order_id");
        $service_person_id = $this->input->post("serv_person_id");

        $data['result'] = $this->apisprovidermodel->Assigned_accepted_services($user_master_id, $service_order_id, $service_person_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_assigned_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_assigned_services($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function detail_assigned_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_assigned_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_ongoing_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List ongoing services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_ongoing_services($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function detail_initiated_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_initiated_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function detail_ongoing_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_ongoing_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function additional_service_orders()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List assigned services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }

        $service_order_id = '';
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Additional_service_orders($service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function list_completed_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List Completed services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_completed_services($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function detail_completed_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Detail Completed services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_completed_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function cancel_service_reasons()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Cancel services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_type = '';
        $user_type = $this->input->post("user_type");

        $data['result'] = $this->apisprovidermodel->Cancel_service_reasons($user_type);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function cancel_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Cancel services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';
        $cancel_master_id = '';
        $comments         = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");
        $cancel_master_id = $this->input->post("cancel_master_id");
        $comments         = $this->input->post("comments");

        $data['result'] = $this->apisprovidermodel->Cancel_services($user_master_id, $service_order_id, $cancel_master_id, $comments);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function list_canceled_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "List canceled services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");

        $data['result'] = $this->apisprovidermodel->List_canceled_services($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function detail_canceled_services()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Detail canceled services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $service_order_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $service_order_id = $this->input->post("service_order_id");

        $data['result'] = $this->apisprovidermodel->Detail_canceled_services($user_master_id, $service_order_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function vendor_status_update()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Detail canceled services";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $serv_pro_id   = '';
        $online_status = '';
        $serv_lat      = '';
        $serv_lon      = '';

        $serv_pro_id   = $this->input->post("serv_pro_id");
        $online_status = $this->input->post("online_status");
        $serv_lat      = $this->input->post("serv_lat");
        $serv_lon      = $this->input->post("serv_lon");

        $data['result'] = $this->apisprovidermodel->Vendor_status_update($serv_pro_id, $online_status, $serv_lat, $serv_lon);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//

    //-----------------------------------------------//

    public function transaction_details()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Transaction_details";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");


        $data['result'] = $this->apisprovidermodel->Transaction_details($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function transaction_list()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "Transaction_details";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id = '';

        $user_master_id = $this->input->post("user_master_id");


        $data['result'] = $this->apisprovidermodel->Transaction_list($user_master_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


    //-----------------------------------------------//

    public function view_transaction_details()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        if (!$this->checkMethod()) {
            return FALSE;
        }

        if ($_POST == FALSE) {
            $res            = array();
            $res["opn"]     = "View Transaction_details";
            $res["scode"]   = 204;
            $res["message"] = "Input error";

            echo json_encode($res);
            return;
        }
        $user_master_id   = '';
        $daily_payment_id = '';

        $user_master_id   = $this->input->post("user_master_id");
        $daily_payment_id = $this->input->post("daily_payment_id");


        $data['result'] = $this->apisprovidermodel->View_transaction_details($user_master_id, $daily_payment_id);
        $response       = $data['result'];
        echo json_encode($response);
    }

    //-----------------------------------------------//


}
?>
