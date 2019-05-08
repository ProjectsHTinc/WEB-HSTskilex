<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apimain extends CI_Controller {



		function __construct() {
			 parent::__construct();
				$this->load->model('apimainmodel');

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

	public function mobile_chk()
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

		$mobile = '';

		$mobile = $this->input->post("mobile");

		$data['result']=$this->apimainmodel->Mobile_chk($mobile);
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

		$mobile = '';
		$otp = '';
		$gcmkey ='';
		$mobiletype ='';

		$mobile = $this->input->post("mobile");
		$otp = $this->input->post("otp");
		$device_token = $this->input->post("device_token");
		$mobiletype = $this->input->post("mobile_type");

		$data['result']=$this->apimainmodel->Login($mobile,$otp,$device_token,$mobiletype);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

	public function forgot_Password()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Forgot Password";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_name = '';
	 	$user_name = $this->input->post("user_name");


		$data['result']=$this->apimainmodel->forgotPassword($user_name);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

	public function reset_Password()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Reset Password";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$password = '';

		$user_id = $this->input->post("user_id");
	 	$password = $this->input->post("password");

		$data['result']=$this->apimainmodel->resetPassword($user_id,$password);
		$response = $data['result'];
		echo json_encode($response);
	}


//-----------------------------------------------//

//-----------------------------------------------//

	public function update_Profilepic()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		echo $user_id = $this->input->post("user_id");
		echo $user_type = $this->input->post("user_type");
		echo $user_pic = $_FILES["user_pic"]["name"];


	    if($user_type==1)
		{
		    $uploadPicdir = 'assets/admin/profile/';
		}
		else if ($user_type==2) {
		     $uploadPicdir = 'assets/teachers/profile/';
		}
		else if ($user_type==3) {
		    $uploadPicdir = 'assets/student/profile/';
		}
		else {
		   $uploadPicdir = 'assets/parents/profile/';
		}

		$Picture 		= pathinfo($_FILES['user_pic']['name']);
	    $sPicture 		= "user_".$user_id.".".$Picture['extension'];
		$uploadPic 		= $uploadPicdir . $sPicture;
		$uploadtmpPic 	= $_FILES['user_pic']['tmp_name'];
		move_uploaded_file($uploadtmpPic,$uploadPic);

	//	$query = mysql_query("UPDATE user_master SET user_image='$sPicture' WHERE id = '$user_id'") or die(mysql_error());
		$response=array("status"=>"success","msg"=>"Image update successfully!!","image"=>$sPicture);
		echo json_encode($response);


/*
		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Profile Pic Update";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$user_type ='';

		$user_id = $this->input->post("user_id");
		$user_type = $this->input->post("user_type");
		$user_pic = $_FILES["user_pic"]["name"];

	 	if($user_type==1)
		{
		    $uploaddir = 'assets/admin/profile/';
		}
		else if ($user_type==2) {
		     $uploaddir = 'assets/teachers/profile/';
		}
		else if ($user_type==3) {
		    $uploaddir = 'assets/student/profile/';
		}
		else {
		   $uploaddir = 'assets/parents/profile/';
		}

        $userFileName = time().$user_pic;
        $profilepic = $uploaddir.$userFileName;
	    move_uploaded_file($_FILES['user_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apimainmodel->updateProfilepic($user_id,$user_type,$userFileName);
		$response = $data['result'];
		echo json_encode($response);
		*/
	}



/// User Profile Pic upload


	public function user_profilepic_upload($user_id,$user_type)
	{
	    $_POST = json_decode(file_get_contents("php://input"), TRUE);

		$user_id = $user_id;
     	$user_type = $user_type;
		$profile = $_FILES["user_pic"]["name"];
		$userFileName = time().'-'.$profile;

	    if($user_type==1)
		{
		    $uploadPicdir = 'assets/admin/profile/';
		}
		else if ($user_type==2) {
		     $uploadPicdir = 'assets/teachers/profile/';
		}
		else if ($user_type==3) {
		    $uploadPicdir = 'assets/student/profile/';
		}
		else {
		   $uploadPicdir = 'assets/parents/profile/';
		}

		$profilepic = $uploadPicdir.$userFileName;
		move_uploaded_file($_FILES['user_pic']['tmp_name'], $profilepic);

		$data['result']=$this->apimainmodel->updateProfilepic($user_id,$user_type,$userFileName);
		$response = $data['result'];
		echo json_encode($response);

	}



//-----------------------------------------------//

	public function change_Password()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Reset Password";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$old_password = '';
		$password = '';

		$user_id = $this->input->post("user_id");
		$old_password = $this->input->post("old_password");
	 	$password = $this->input->post("password");

		$data['result']=$this->apimainmodel->changePassword($user_id,$old_password,$password);
		$response = $data['result'];
		echo json_encode($response);
	}


//-----------------------------------------------//

	public function disp_Events()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Events View";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$class_id= '';
	 	$class_id = $this->input->post("class_id");


		$data['result']=$this->apimainmodel->dispEvents($class_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function disp_subEvents()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Events View";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$event_id= '';
	 	$event_id = $this->input->post("event_id");

		$data['result']=$this->apimainmodel->dispsubEvents($event_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_Circular()
	{
	   	$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Circular";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

	    $user_id = '';
	    $user_id = $this->input->post("user_id");



		$data['result']=$this->apimainmodel->dispCircular($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function add_Onduty()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Onduty Add";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_type = '';
		$user_id = '';
		$od_for = '';
		$from_date = '';
        $to_date = '';
        $notes = '';
        $status = '';
        $created_by = '';
        $created_at = '';

        $user_type = $this->input->post("user_type");
		$user_id = $this->input->post("user_id");
        $od_for = $this->input->post("od_for");
        $from_date = $this->input->post("from_date");
        $to_date = $this->input->post("to_date");
        $notes = $this->input->post("notes");
        $status = $this->input->post("status");
        $created_by = $this->input->post("created_by");
        $created_at = $this->input->post("created_at");


		$data['result']=$this->apimainmodel->addOnduty($user_type,$user_id,$od_for,$from_date,$to_date,$notes,$status,$created_by,$created_at);
		$response = $data['result'];
		echo json_encode($response);
	}
//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_Onduty()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Onduty";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$user_type = '';
	 	$user_type = $this->input->post("user_type");
	 	$user_id = $this->input->post("user_id");

		$data['result']=$this->apimainmodel->dispOnduty($user_type,$user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_Grouplist()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Grouplist";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$user_type = '';
	 	$user_type = $this->input->post("user_type");
	 	$user_id = $this->input->post("user_id");

		$data['result']=$this->apimainmodel->dispGrouplist($user_type,$user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//

	public function send_Groupmessageold()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Send Group Message";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$group_title_id = '';
		$message_type = '';
		$message_details = '';
		$created_by = '';

	 	$group_title_id = $this->input->post("group_title_id");
	 	$message_type = $this->input->post("message_type");
		$message_details = $this->input->post("message_details");
		$created_by = $this->input->post("created_by");


		$data['result']=$this->apimainmodel->sendGroupmessage($group_title_id,$message_type,$message_details,$created_by);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function send_Groupmessage()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Send Group Message";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$group_title_id = '';
		$messagetype_sms = '';
		$messagetype_mail = '';
		$messagetype_notification = '';
		$message_details = '';
		$created_by = '';

	 	$group_title_id = $this->input->post("group_title_id");
	 	$messagetype_sms = $this->input->post("messagetype_sms");
		$messagetype_mail = $this->input->post("messagetype_mail");
		$messagetype_notification = $this->input->post("messagetype_notification");
		$message_details = $this->input->post("message_details");
		$created_by = $this->input->post("created_by");


		$data['result']=$this->apimainmodel->sendGroupmessage($group_title_id,$messagetype_sms,$messagetype_mail,$messagetype_notification,$message_details,$created_by);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_Groupmessage()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Group Message";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$user_type = '';
	 	$user_type = $this->input->post("user_type");
	 	$user_id = $this->input->post("user_id");

		$data['result']=$this->apimainmodel->dispGroupmessage($user_type,$user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

		public function grp_messsage_history()
		{

			$_POST = json_decode(file_get_contents("php://input"), TRUE);

			if(!$this->checkMethod())
			{
				return FALSE;
			}

			if($_POST == FALSE)
			{
				$res = array();
				$res["opn"] = "View Group Message";
				$res["scode"] = 204;
				$res["message"] = "Input error";

				echo json_encode($res);
				return;
			}
				//echo "1";exit;
		 	$group_id = $this->input->post("group_id");
			$data['result']=$this->apimainmodel->groupmessagehistory($group_id);
			$response = $data['result'];
			echo json_encode($response);
		}

//-----------------------------------------------//


//-----------------------------------------------//

	public function disp_Leaves()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Leave List";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_type = '';
		$class_id = '';
		$sec_id = '';
		$class_sec_id = '';

		$user_type = $this->input->post("user_type");
		$class_id = $this->input->post("class_id");
		$sec_id = $this->input->post("sec_id");
	  	$class_sec_id = $this->input->post("class_sec_id");

		$data['result']=$this->apimainmodel->dispLeaves($user_type,$class_id,$sec_id,$class_sec_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_upcomingLeaves()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Upcoming Leave List";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_type = $this->input->post("user_type");
		$class_id = $this->input->post("class_id");
		$sec_id = $this->input->post("sec_id");
	  	$class_sec_id = $this->input->post("class_sec_id");

		$data['result']=$this->apimainmodel->disp_upcomingLeaves($user_type,$class_id,$sec_id,$class_sec_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_timetabledays()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Attendence Days";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$class_id = '';
		$class_id = $this->input->post("class_id");


		$data['result']=$this->apimainmodel->dispTimetable_days($class_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_timetable()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Attendence Display";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$class_id = '';
		$day_id = '';
		$class_id = $this->input->post("class_id");
		$day_id = $this->input->post("day_id");

		$data['result']=$this->apimainmodel->dispTimetable($class_id,$day_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

}
