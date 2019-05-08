<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apimainmodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


//#################### Email ####################//

	public function sendMail($email,$subject,$email_message)
	{
		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: Webmaster<hello@happysanz.com>' . "\r\n";
		mail($email,$subject,$email_message,$headers);
	}

//#################### Email End ####################//


//#################### SMS ####################//

	public function sendSMS($Phoneno,$Message)
	{
        //Your authentication key
        $authKey = "191431AStibz285a4f14b4";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = "$Phoneno";

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "EDUAPP";

        //Your message to send, Add URL encoding here.
        $message = urlencode($Message);

        //Define route
        $route = "transactional";

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url="https://control.msg91.com/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);
	}

//#################### SMS End ####################//


//#################### Notification ####################//

	public function sendNotification($gcm_key,$title,$message,$mobiletype)
	{

		if ($mobiletype =='1'){

		    require_once 'assets/notification/Firebase.php';
            require_once 'assets/notification/Push.php';

            $device_token = explode(",", $gcm_key);
            $push = null;

        //first check if the push has an image with it
		    $push = new Push(
					$title,
					$message,
					null
				);

// 			//if the push don't have an image give null in place of image
// 			 $push = new Push(
// 			 		'HEYLA',
// 		     		'Hi Testing from maran',
// 			 		'http://heylaapp.com/assets/notification/images/event.png'
// 			 	);

    		//getting the push from push object
    		$mPushNotification = $push->getPush();

    		//creating firebase class object
    		$firebase = new Firebase();

    	foreach($device_token as $token) {
    		 $firebase->send(array($token),$mPushNotification);
    	}

		} else {

			$device_token = explode(",", $gcm_key);
			$passphrase = 'hs123';
		    $loction ='assets/notification/happysanz.pem';

			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

			// Open a connection to the APNS server
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => array(
					'body' => $message,
					'action-loc-key' => 'EDU App',
				),
				'badge' => 2,
				'sound' => 'assets/notification/oven.caf',
				);
			$payload = json_encode($body);

			foreach($device_token as $token) {

				// Build the binary notification
    			$msg = chr(0) . pack("n", 32) . pack("H*", str_replace(" ", "", $token)) . pack("n", strlen($payload)) . $payload;
        		$result = fwrite($fp, $msg, strlen($msg));
			}

				fclose($fp);
		}

	}

//#################### Notification End ####################//

//#################### Mobile Check ####################//

	public function Mobile_chk($mobile)
	{
		$sql = "SELECT * FROM login_users WHERE phone_no ='".$mobile."' AND user_type = '5' AND status='Active'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		$digits = 6;
		$OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			
		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $user_id = $rows->user_id;
			}
			
			$update_sql = "UPDATE login_users SET otp = '".$OTP."', updated_date=NOW() WHERE id ='".$user_id."'";
			$update_result = $this->db->query($update_sql);
		} else {
			 $insert_sql = "INSERT INTO login_users (phone_no, otp, user_type, status) VALUES ('". $mobile . "','". $OTP . "','5','Active')";
             $insert_result = $this->db->query($insert_sql);
			 $user_id = $this->db->insert_id();
			 
			 $insert_query = "INSERT INTO customer_details (user_master_id, status) VALUES ('". $user_id . "','Active')";
             $insert_result = $this->db->query($insert_sql);
		}
		
		$response = array("status" => "success", "msg" => "Mobile OTP", "user_id"=>$user_id, "mobile"=>$mobile, "otp"=>$OTP);
	}

//#################### Mobile Check End ####################//

//#################### Login ####################//

	public function Login($mobile,$otp,$device_token,$mobiletype)
	{
		$sql = "SELECT * FROM edu_users A, edu_role B  WHERE A.user_type = B.role_id AND A.user_name ='".$username."' and A.user_password = md5('".$password."') and A.status='Active'";
		$user_result = $this->db->query($sql);
		$ress = $user_result->result();

		if($user_result->num_rows()>0)
		{
			foreach ($user_result->result() as $rows)
			{
				  $user_id = $rows->user_id;
				  $login_count = $rows->login_count+1;
				  $user_type = $rows->user_type;
				  $update_sql = "UPDATE edu_users SET last_login_date=NOW(),login_count='$login_count' WHERE user_id='$user_id'";
				  $update_result = $this->db->query($update_sql);
			}

				$userData  = array(
							"user_id" => $ress[0]->user_id,
							"name" => $ress[0]->name,
							"user_name" => $ress[0]->user_name,
							"user_pic" => $ress[0]->user_pic,
							"user_type" => $ress[0]->user_type,
							"user_type_name" => $ress[0]->user_type_name,
							"password_status" => $ress[0]->password_status
						);

                    	$gcmQuery = "SELECT * FROM edu_notification WHERE gcm_key like '%" .$gcmkey. "%' LIMIT 1";
                    	$gcm_result = $this->db->query($gcmQuery);
                    	$gcm_ress = $gcm_result->result();

                		if($gcm_result->num_rows()==0)
                		{
                		    $sQuery = "INSERT INTO edu_notification (user_id,gcm_key,mobile_type) VALUES ('". $user_id . "','". $gcmkey . "','". $mobiletype . "')";
                		     $update_gcm = $this->db->query($sQuery);
                		}


				  if ($user_type==1)  {

				 	 	$response = array("status" => "loggedIn", "msg" => "User loggedIn successfully", "userData" => $userData, "year_id" => $year_id);
						return $response;
				  }
				  else if ($user_type==2) {

						$teacher_id = $rows->teacher_id;

						$sqlYear = "SELECT * FROM edu_academic_year WHERE CURDATE() >= from_month AND CURDATE() <= to_month AND status = 'Active'";
                		$year_result = $this->db->query($sqlYear);
                		$ress_year = $year_result->result();

                		if($year_result->num_rows()==1)
                		{
                			foreach ($year_result->result() as $rows)
                			{
                			    $from_month = $rows->from_month;
                			    $to_month  = $rows->to_month ;
                			}
                		}

                        $start    = new DateTime($from_month);
                        $start->modify('first day of this month');
                        $end      = new DateTime($to_month);
                        $end->modify('first day of next month');
                        $interval = DateInterval::createFromDateString('1 month');
                        $period   = new DatePeriod($start, $interval, $end);

                        $month = array();
                        foreach($period as $dt) {
                         $month[] = $dt->format("m-Y");
                        }

                        //$teacher_query = "SELECT t.teacher_id, t.name, t.sex, t.age, t.nationality, t.religion, t.community_class, t.community, t.address, t.email,t.phone, t.sec_email, t.sec_phone, t.profile_pic, t.update_at, t.subject, t.class_name AS class_taken, t.class_teacher,c.class_name, se.sec_name
                        //                FROM
                        //                edu_teachers AS t, edu_classmaster AS cm, edu_class AS c, edu_sections AS se
                        //                WHERE
                        //                t.class_teacher = cm.class_sec_id AND cm.class = c.class_id AND cm.section = se.sec_id AND t.teacher_id = '$teacher_id'";

                        $teacher_query = "SELECT t.teacher_id, t.name, t.sex, t.age, t.nationality, t.religion, t.community_class, t.community, t.address, t.email,t.phone, t.sec_email, t.sec_phone, t.profile_pic, t.update_at, t.subject, t.class_name AS class_taken, t.class_teacher FROM edu_teachers AS t WHERE t.teacher_id = '$teacher_id'";
						$teacher_res = $this->db->query($teacher_query);
						$teacher_profile = $teacher_res->result();

                        if($teacher_res->num_rows()>0){
							 foreach($teacher_profile as $rows){
								$class_teacher = $rows->class_teacher;
								//$subject_id = $rows->subject;
							}
						}

						$class_sub_query = "SELECT
											class_master_id,
											teacher_id,
											class_name,
											sec_name,
											subject_name,A.subject_id
										FROM
											edu_teacher_handling_subject A,
											edu_classmaster B,
											edu_subject C,
											edu_class D,
											edu_sections E
										WHERE
											A.class_master_id = B.class_sec_id AND B.class = D.class_id AND B.section = E.sec_id AND A.subject_id = C.subject_id AND A.teacher_id = '$teacher_id' ORDER by class_master_id";
						$class_sub_res = $this->db->query($class_sub_query);

						 if($class_sub_res->num_rows()==0){
							 $class_sub_result = array("status" => "error", "msg" => "Class and Section not found");

						}else{

							$class_sub_result = array("status" => "success", "msg" => "Class and Section found","data"=> $class_sub_res->result());
						}



						$sqldays = "SELECT A.day_id, B.list_day FROM `edu_timetable` A, `edu_days` B WHERE A.day_id = B.d_id AND A.teacher_id = '$teacher_id' AND A.year_id = '$year_id' AND A.term_id = '$term_id' GROUP BY day_id ORDER BY A.day_id";
						$day_res = $this->db->query($sqldays);

						if($day_res->num_rows()==0){
							 $day_result = array("status" => "error", "msg" => "TimeTable days not found");

						}else{
							 $day_result = array("status" => "success", "msg" => "TimeTable Days","data"=> $day_res->result());
						}

						$timetable_query = "SELECT
									tt.table_id,
									tt.class_id,
									c.class_name,
									ss.sec_name,
									tt.subject_id,
									tt.teacher_id,
									tt.day_id,
									tt.period,
									t.name,
									s.subject_name,
									tt.from_time,
									tt.to_time,
									tt.break_name,
									tt.is_break
								FROM
									edu_timetable AS tt
								LEFT JOIN edu_subject AS s
								ON
									tt.subject_id = s.subject_id
								LEFT JOIN edu_teachers AS t
								ON
									tt.teacher_id = t.teacher_id
								INNER JOIN edu_classmaster AS cm
								ON
									tt.class_id = cm.class_sec_id
								INNER JOIN edu_class AS c
								ON
									cm.class = c.class_id
								INNER JOIN edu_sections AS ss
								ON
									cm.section = ss.sec_id
								WHERE
									tt.teacher_id = '$teacher_id' AND tt.year_id = '$year_id' AND tt.term_id = '$term_id'
								ORDER BY
									tt.day_id,
									tt.period";
						$timetable_res = $this->db->query($timetable_query);

						 if($timetable_res->num_rows()==0){
							 $timetable_result = array("status" => "error", "msg" => "TimeTable not found");

						}else{

							 $timetable_result = array("status" => "success", "msg" => "TimeTable found","data"=> $timetable_res->result());
						}

						$stud_query = "SELECT
                                        A.enroll_id,
										A.status,
                                        A.admission_id,
                                        A.class_id,
                                        A.name,
										E.sex,
                                        F.subject_name as pref_language,
										E.status,
                                        CONCAT(C.class_name, ' ', D.sec_name) AS class_section
                                    FROM
                                        edu_enrollment A,
                                        edu_classmaster B,
                                        edu_class C,
                                        edu_sections D,
                                        edu_admission E,
                                        edu_subject F
                                    WHERE
                                        A.class_id = B.class_sec_id AND B.class = C.class_id AND B.section = D.sec_id AND A.admission_id = E.admission_id AND E.language = F.subject_id AND A.admit_year = '$year_id' AND A.class_id IN(SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id') ORDER BY A.class_id";

						$stud_res = $this->db->query($stud_query);

						 if($stud_res->num_rows()==0){
							 $stud_result = array("status" => "error", "msg" => "Student not found");
						}else{
							 $stud_result = array("status" => "success", "msg" => "Student found","data"=>$stud_result= $stud_res->result());
						}

/*
					 $exam_query = "SELECT ex.exam_id,ex.exam_name,ex.exam_flag AS is_internal_external,ed.classmaster_id, ss.sec_name,c.class_name,COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate,
						COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
						CASE WHEN ems.status='Publish' OR ems.status='Approved' THEN 1 ELSE 0 END AS MarkStatus
						FROM edu_examination ex
						RIGHT JOIN edu_exam_details ed on ex.exam_id = ed.exam_id and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
						INNER JOIN edu_classmaster AS cm ON ed.classmaster_id = cm.class_sec_id
						INNER JOIN edu_class AS c ON cm.class=c.class_id
						INNER JOIN edu_sections AS ss ON cm.section=ss.sec_id
						WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						GROUP by ed.classmaster_id, ed.exam_id

						UNION ALL

						SELECT ex.exam_id,ex.exam_name,ex.exam_flag AS is_internal_external,ed.classmaster_id, ss.sec_name,c.class_name, COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate,
						COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
						CASE WHEN ems.status='Publish' OR ems.status='Approved' THEN 1 ELSE 0 END AS MarkStatus
						FROM edu_examination ex
						LEFT JOIN edu_exam_details ed on ed.exam_id = ex.exam_id and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
						INNER JOIN edu_classmaster AS cm ON ed.classmaster_id = cm.class_sec_id
						INNER JOIN edu_class AS c ON cm.class=c.class_id
						INNER JOIN edu_sections AS ss ON cm.section=ss.sec_id
						WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ex.exam_id NOT IN (SELECT DISTINCT exam_id FROM edu_exam_details where classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')) GROUP by ed.classmaster_id,ed.exam_id";
*/

					 $exam_query = "SELECT ex.exam_id,ex.exam_name,0 AS is_internal_external,ed.classmaster_id, ss.sec_name,c.class_name,COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate,
						COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
						CASE WHEN ems.status='Publish' OR ems.status='Approved' THEN 1 ELSE 0 END AS MarkStatus
						FROM edu_examination ex
						RIGHT JOIN edu_exam_details ed on ex.exam_id = ed.exam_id and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
						INNER JOIN edu_classmaster AS cm ON ed.classmaster_id = cm.class_sec_id
						INNER JOIN edu_class AS c ON cm.class=c.class_id
						INNER JOIN edu_sections AS ss ON cm.section=ss.sec_id
						WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						GROUP by ed.classmaster_id, ed.exam_id

						UNION ALL

						SELECT ex.exam_id,ex.exam_name,0 AS is_internal_external,ed.classmaster_id, ss.sec_name,c.class_name, COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate,
						COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
						CASE WHEN ems.status='Publish' OR ems.status='Approved' THEN 1 ELSE 0 END AS MarkStatus
						FROM edu_examination ex
						LEFT JOIN edu_exam_details ed on ed.exam_id = ex.exam_id and ed.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')
						LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
						INNER JOIN edu_classmaster AS cm ON ed.classmaster_id = cm.class_sec_id
						INNER JOIN edu_class AS c ON cm.class=c.class_id
						INNER JOIN edu_sections AS ss ON cm.section=ss.sec_id
						WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ex.exam_id NOT IN (SELECT DISTINCT exam_id FROM edu_exam_details where classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')) GROUP by ed.classmaster_id,ed.exam_id";

						$exam_res = $this->db->query($exam_query);

						 if($exam_res->num_rows()==0){
							 $exam_result = array("status" => "error", "msg" => "Exams not found");
						}else{
							 $exam_result = array("status" => "success", "msg" => "Exams found","data"=>$exam_result= $exam_res->result());
						}

						$examdetail_query = "SELECT A.exam_id,A.exam_name,C.subject_name,B.subject_id,B.exam_date, B.times,B.is_internal_external,B.subject_total,B.internal_mark,B.external_mark,B.classmaster_id, E.class_name, F.sec_name FROM
							`edu_examination` A, `edu_exam_details` B, `edu_subject` C, `edu_classmaster` D, `edu_class` E, `edu_sections` F WHERE
							A.`exam_id` = B. exam_id AND B.subject_id = C.subject_id AND
							B.classmaster_id=D.class_sec_id AND D.class = E.class_id AND
							D.section = F.sec_id AND B.classmaster_id in (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id')";
							$examdetail_res = $this->db->query($examdetail_query);

						 if($examdetail_res->num_rows()==0){
							 $examdetail_result = array("status" => "error", "msg" => "Exams not found");
						}else{
							 $examdetail_result = array("status" => "success", "msg" => "Exams found","data"=>$examdetail_result= $examdetail_res->result());
						}

						$hw_query = "SELECT A.hw_id, A.hw_type, A.title, A.test_date, A.due_date,A.teacher_id ,A.class_id, A.hw_details, A.mark_status, A.subject_id,B.subject_name, D.class_name, E.sec_name FROM
                            `edu_homework` A, `edu_subject` B, `edu_classmaster` C, `edu_class` D, `edu_sections` E WHERE
                            A.subject_id = B.subject_id AND A.year_id ='$year_id' AND
                            A.subject_id IN (SELECT DISTINCT subject_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id') AND A.class_id IN (SELECT DISTINCT class_master_id from edu_teacher_handling_subject WHERE teacher_id ='$teacher_id') AND
                            A.class_id = C. class_sec_id AND C.class = D.class_id AND
                            C.section = E.sec_id AND A.status = 'Active' AND A.teacher_id='$teacher_id'";
							$hw_res = $this->db->query($hw_query);

						 if($hw_res->num_rows()==0){
							 $hw_result = array("status" => "error", "msg" => "Homeworks not found");

						}else{

							$hw_result = array("status" => "success", "msg" => "Homeworks found","data"=>$hw_result= $hw_res->result());
						}

						$reminder_query = "SELECT * from edu_reminder WHERE user_id  ='$user_id'";
						$reminder_res = $this->db->query($reminder_query);

						 if($reminder_res->num_rows()==0){
							 $reminder_result = array("status" => "error", "msg" => "Reminders not found");

						}else{

							 $reminder_result = array("status" => "success", "msg" => "Reminders found","data"=>$reminder_result= $reminder_res->result());
						}

						  $internal_marks="40";
                          $external_marks="60";

                          $academic_marks=array("internals"=>$internal_marks,"externals"=>$external_marks);

						$response = array("status" => "loggedIn", "msg" => "User loggedIn successfully", "userData" => $userData,"teacherProfile" =>$teacher_profile,"classSubject"=>$class_sub_result,"timeTabledays"=>$day_result,"timeTable"=>$timetable_result,"studDetails"=>$stud_result,"Exams"=>$exam_result,"examDetails"=>$examdetail_result,"homeWork"=>$hw_result,"Reminders"=>$reminder_result, "year_id" => $year_id, "academic_month" => $month,"academic_marks"=>$academic_marks);
						return $response;
				  }
				  else if ($user_type==3) {

						$student_id = $rows->student_id;

						$student_query = "SELECT * from edu_admission WHERE admission_id='$student_id' AND status = 'Active'";
						$student_res = $this->db->query($student_query);
						$student_profile= $student_res->result();

							foreach($student_profile as $rows){
								$admit_id = $rows->admission_id;
								$parent_id = $rows->parnt_guardn_id;
							}

						$father_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Father' AND status = 'Active'";
						$father_res = $this->db->query($father_query);
						$father_profile = $father_res->result();

						foreach($father_profile as $rows){
								$admisson_id = $rows->admission_id;
								$relationship = $rows->relationship;
						}

						$fatherProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/

							"id" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->id,
                            "name" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->name,
                            "occupation" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->occupation,
                            "income" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->income,
                            "home_address" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_address ,
                            "email" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->email,
                            "mobile" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->mobile,
                            "home_phone" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_phone,
                            "office_phone" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->office_phone,
                            "relationship" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->relationship,
                            "user_pic" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->user_pic

						);

						$mother_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Mother' AND status = 'Active'";
						$mother_res = $this->db->query($mother_query);
						$mother_profile = $mother_res->result();

						foreach($mother_profile as $rows){
								$admisson_id = $rows->admission_id;
						}

						$motherProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/
							"id" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->id,
                            "name" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->name,
                            "occupation" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->occupation,
                            "income" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->income,
                            "home_address" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_address ,
                            "email" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->email,
                            "mobile" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->mobile,
                            "home_phone" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_phone,
                            "office_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->office_phone,
                            "relationship" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->relationship,
                            "user_pic" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->user_pic
						);

						$guardian_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Guardian' AND status = 'Active'";
						$guardian_res = $this->db->query($guardian_query);
						$guardian_profile = $guardian_res->result();

						foreach($guardian_profile as $rows){
								$admisson_id = $rows->admission_id;
						}

						$guardianProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/
							"id" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->id,
                            "name" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->name,
                            "occupation" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->occupation,
                            "income" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->income,
                            "home_address" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_address ,
                            "email" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->email,
                            "mobile" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->mobile,
                            "home_phone" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_phone,
                            "office_phone" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->office_phone,
                            "relationship" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->relationship,
                            "user_pic" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->user_pic
						);


						$enroll_query = "SELECT A.enroll_id AS registered_id,A.admission_id,A.admisn_no AS admission_no,A.class_id,A.name,C.class_name,D.sec_name
						from edu_enrollment A, edu_classmaster B, edu_class C, edu_sections D WHERE A.class_id = B.class_sec_id AND
						B.class = C.class_id AND B.section = D.sec_id AND A.admit_year ='$year_id' AND A.admission_id = '$admit_id'";
						$enroll_res = $this->db->query($enroll_query);
						$stu_enroll_res= $enroll_res->result();

						$parentProfile = array("fatherProfile" =>$fatherProfile,"motherProfile" =>$motherProfile,"guardianProfile" =>$guardianProfile);

				  		$response = array("status" => "loggedIn", "msg" => "User loggedIn successfully", "userData" => $userData,"studentProfile" =>$student_profile,"parentProfile" =>$parentProfile,"registeredDetails"=>$stu_enroll_res, "year_id" => $year_id);
						return $response;
				  }
				  else {
				  		$parent_id = $rows->parent_id;

                        $parent_query = "SELECT * from edu_parents WHERE id ='$parent_id' AND status = 'Active'";
						$parent_res = $this->db->query($parent_query);
						$parent_profile = $parent_res->result();

						foreach($parent_profile as $rows){
								$admisson_id = $rows->admission_id;
						}

                        $father_query = "SELECT * from edu_parents WHERE admission_id IN ($admisson_id) AND relationship = 'Father' AND status = 'Active'";
						$father_res = $this->db->query($father_query);
						$father_profile = $father_res->result();

						foreach($father_profile as $rows){
								$admisson_id = $rows->admission_id;
						}
						$fatherProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/
							"id" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->id,
                            "name" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->name,
                            "occupation" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->occupation,
                            "income" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->income,
                            "home_address" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_address ,
                            "email" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->email,
                            "mobile" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->mobile,
                            "home_phone" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_phone,
                            "office_phone" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->office_phone,
                            "relationship" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->relationship,
                            "user_pic" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->user_pic
						);

						$mother_query = "SELECT * from edu_parents WHERE admission_id IN ($admisson_id) AND relationship = 'Mother' AND status = 'Active'";
						$mother_res = $this->db->query($mother_query);
						$mother_profile = $mother_res->result();

						foreach($mother_profile as $rows){
								$admisson_id = $rows->admission_id;
						}

						$motherProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/
							"id" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->id,
                            "name" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->name,
                            "occupation" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->occupation,
                            "income" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->income,
                            "home_address" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_address ,
                            "email" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->email,
                            "mobile" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->mobile,
                            "home_phone" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_phone,
                            "office_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->office_phone,
                            "relationship" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->relationship,
                            "user_pic" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->user_pic
						);

					 	$guardian_query = "SELECT * from edu_parents WHERE admission_id IN ($admisson_id) AND relationship = 'Guardian' AND status = 'Active'";
						$guardian_res = $this->db->query($guardian_query);
						$guardian_profile = $guardian_res->result();

						foreach($guardian_profile as $rows){
								$admisson_id = $rows->admission_id;
						}

						$guardianProfile  = array(
/*
							"id" => $father_profile[0]->id,
							"name" => $father_profile[0]->name,
							"occupation" => $father_profile[0]->occupation,
							"income" => $father_profile[0]->income,
							"home_address" => $father_profile[0]->home_address ,
							"email" => $father_profile[0]->email,
							"mobile" => $father_profile[0]->mobile,
							"home_phone" => $father_profile[0]->home_phone,
							"office_phone" => $father_profile[0]->office_phone,
							"relationship" => $father_profile[0]->relationship,
							"user_pic" => $father_profile[0]->user_pic
*/
							"id" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->id,
                            "name" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->name,
                            "occupation" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->occupation,
                            "income" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->income,
                            "home_address" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_address ,
                            "email" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->email,
                            "mobile" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->mobile,
                            "home_phone" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_phone,
                            "office_phone" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->office_phone,
                            "relationship" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->relationship,
                            "user_pic" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->user_pic
						);
						$parentProfile = array("fatherProfile" =>$fatherProfile,"motherProfile" =>$motherProfile,"guardianProfile" =>$guardianProfile);


						$enroll_query = "SELECT A.enroll_id AS registered_id,A.admission_id,A.admisn_no AS admission_no,A.class_id,A.name,C.class_name,D.sec_name from edu_enrollment A, edu_classmaster B, edu_class C, edu_sections D WHERE A.class_id = B.class_sec_id AND B.class = C.class_id AND B.section = D.sec_id AND A.admit_year ='$year_id' AND A.admission_id IN ($admisson_id)";
						$enroll_res = $this->db->query($enroll_query);
						$stu_enroll_res= $enroll_res->result();

				  		$response = array("status" => "loggedIn", "msg" => "User loggedIn successfully", "userData" => $userData,"parentProfile" =>$parentProfile,"registeredDetails"=>$stu_enroll_res, "year_id" => $year_id);
						return $response;
				  }

			} else {
			 			$response = array("status" => "error", "msg" => "Invalid login");
						return $response;
			 }
	}

//#################### Main Login End ####################//


//#################### Forgot Password ####################//
	public function forgotPassword($user_name)
	{
			$year_id = $this->getYear();
			$digits = 6;
			$OTP = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);


			$user_query = "SELECT * FROM edu_users WHERE user_name ='".$user_name."' and status='Active'";
			$user_res = $this->db->query($user_query);
			$user_result= $user_res->result();


			if($user_res->num_rows()==1)
			{
				foreach ($user_res->result() as $rows)
				{
				  $user_id = $rows->user_id;
				  $user_type = $rows->user_type;
				  $name = $rows->name;
				}

				if ($user_type==1)  {
					$response = array("status" => "sucess", "msg" => "Please contact server Admin");
				}
				else if ($user_type==2) {

						$teacher_id = $rows->teacher_id;

						$teacher_query = "SELECT * from edu_teachers WHERE teacher_id ='$teacher_id' AND status = 'Active'";
						$teacher_res = $this->db->query($teacher_query);
						$teacher_profile= $teacher_res->result();

							foreach($teacher_profile as $rows){
								$email = $rows->email;
							}

						$update_sql = "UPDATE edu_users SET user_password = md5('$OTP'),updated_date=NOW(),password_status='0' WHERE user_id='$user_id'";
						$update_result = $this->db->query($update_sql);

						$subject = "Forgot Password";
						$htmlContent = 'Dear '. $name . '<br><br>' .  'Password : '. $OTP.'<br><br>Regards<br>';
						$this->sendMail($email,$subject,$htmlContent);

						$response = array("status" => "sucess", "msg" => "Password Updated", "Email" => $email);
				}
				else if ($user_type==3) {

						$student_id = $rows->student_id;

						$student_query = "SELECT * from edu_admission WHERE admission_id='$student_id' AND status = 'Active'";
						$student_res = $this->db->query($student_query);
						$student_profile= $student_res->result();

							foreach($student_profile as $rows){
								$email = $rows->email;
							}

						$update_sql = "UPDATE edu_users SET user_password = md5('$OTP'),updated_date=NOW(),password_status='0' WHERE user_id='$user_id'";
						$update_result = $this->db->query($update_sql);

						$subject = "Forgot Password";
						$htmlContent = 'Dear '. $name . '<br><br>' . 'Password : '. $OTP.'<br><br>Regards<br>';
						$this->sendMail($email,$subject,$htmlContent);

						$response = array("status" => "sucess", "msg" => "Password Updated", "Email" => $email);
				}
				else {

						$parent_id = $rows->parent_id;

						$parent_query = "SELECT * from edu_parents WHERE id='$parent_id' AND status = 'Active'";
						$parent_res = $this->db->query($parent_query);
						$parent_profile= $parent_res->result();

							foreach($parent_profile as $rows){
								$email = $rows->email;
							}


						$update_sql = "UPDATE edu_users SET user_password = md5('$OTP'),updated_date=NOW(),password_status='0' WHERE user_id='$user_id'";
						$update_result = $this->db->query($update_sql);

						$subject = "Forgot Password";
						$htmlContent = 'Dear '. $name . '<br><br>' .  'Password : '. $OTP.'<br><br>Regards<br>';
						$this->sendMail($email,$subject,$htmlContent);

						$response = array("status" => "sucess", "msg" => "Password Updated", "Email" => $email);
				}

			} else {
				$response = array("status" => "error", "msg" => "User Not Found");
			}
			return $response;
	}
//#################### Forgot Password End ####################//


//#################### Reset Password ####################//
	public function resetPassword($user_id,$password)
	{
			$update_sql = "UPDATE edu_users SET user_password = md5('$password'),updated_date=NOW(),password_status='1' WHERE user_id='$user_id'";
			$update_result = $this->db->query($update_sql);

			$response = array("status" => "sucess", "msg" => "Password Updated");
			return $response;
	}
//#################### Reset Password End ####################//


//#################### Profile Pic Update ####################//
	public function updateProfilepic($user_id,$user_type,$userFileName)
	{
            $update_sql= "UPDATE edu_users SET user_pic='$userFileName', updated_date=NOW() WHERE user_id='$user_id' and user_type='$user_type'";
			$update_result = $this->db->query($update_sql);

			$response = array("status" => "success", "msg" => "Profile Picture Updated","user_picture"=>$userFileName);
			return $response;
	}
//#################### Profile Pic Update End ####################//


//#################### Change Password ####################//
	public function changePassword($user_id,$old_password,$password)
	{
			$user_query = "SELECT * FROM edu_users WHERE user_id ='$user_id' and user_password= md5('$old_password') and status='Active'";
			$user_res = $this->db->query($user_query);
			$user_result= $user_res->result();

			if($user_res->num_rows()==1)
			{
				$update_sql = "UPDATE edu_users SET user_password = md5('$password'),updated_date=NOW() WHERE user_id='$user_id'";
				$update_result = $this->db->query($update_sql);

                $response = array("status" => "sucess", "msg" => "Password Updated");
			} else {
				$response = array("status" => "error", "msg" => "Entered Current Password is wrong.");
			}

			return $response;
	}
//#################### Change Password End ####################//


//#################### Events for Students and Parents ####################//
	public function dispEvents($class_id)
	{
			$year_id = $this->getYear();

		 	$event_query = "SELECT event_id,year_id,event_name,event_details,status,DATE_FORMAT(event_date,'%d-%m-%Y') as event_date,sub_event_status FROM `edu_events` WHERE year_id='$year_id' AND status='Active'";
			$event_res = $this->db->query($event_query);
			$event_result= $event_res->result();
			$event_count = $event_res->num_rows();
/*
			foreach($event_result as $rows){
				$event_id = $rows->event_id;

					$gallery_query = "SELECT * FROM `edu_events_galllery` WHERE event_id ='$event_id'";
					$gallery_res = $this->db->query($gallery_query);
					$gallery_result= $gallery_res->result();

					if($gallery_res->num_rows()!=0){
						//echo $gallery_result;
					}
			}
*/
			 if($event_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Events Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Events", "count" => $event_count, "eventDetails"=>$event_result);
			}

			return $response;
	}
//#################### Events Details End ####################//


//#################### Events for Students and Parents ####################//
	public function dispsubEvents ($event_id)
	{
			$year_id = $this->getYear();

			$subevent_query = "SELECT A.sub_event_name,B.name  from edu_event_coordinator A, edu_teachers B WHERE A.event_id = '$event_id' AND A.co_name_id = B.teacher_id AND A.status='Active'";

			$subevent_res = $this->db->query($subevent_query);
			$subevent_result= $subevent_res->result();

			 if($subevent_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Sub Events Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Sub Events", "subeventDetails"=>$subevent_result);
			}

			return $response;
	}
//#################### Event Details End ####################//


//#################### Circular for All ####################//
	public function dispCircular($user_id)
	{

			$year_id = $this->getYear();

			 $circular_query = "SELECT
                                A.circular_type,
                                B.circular_title,
                                B.circular_description,
                                A.circular_date
                            FROM
                                edu_circular A,
                                edu_circular_master B
                            WHERE
                                A.user_id = '$user_id' AND B.academic_year_id = '$year_id' AND A.circular_master_id = B.id";

			$circular_res = $this->db->query($circular_query);
			$circular_result= $circular_res->result();

			 if($circular_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Circular Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Circular", "circularDetails"=>$circular_result);
			}
            //print_r($response);exit;
			return $response;
	}
//#################### Circular End ####################//

//#################### Add Onduty ####################//
	public function addOnduty ($user_type,$user_id,$od_for,$from_date,$to_date,$notes,$status,$created_by,$created_at)
	{
			$year_id = $this->getYear();

		    $onduty_query = "INSERT INTO `edu_on_duty`( `user_type`, `user_id`, `year_id`, `od_for`, `from_date`, `to_date`, `notes`, `status`, `created_by`, `created_at`) VALUES ('$user_type','$user_id','$year_id','$od_for','$from_date','$to_date','$notes','$status','$created_by','$created_at')";
	        $onduty_res = $this->db->query($onduty_query);

			if($onduty_res) {
			    $response = array("status" => "success", "msg" => "Onduty Added");
			} else {
			    $response = array("status" => "error");
			}
			return $response;
	}
//#################### Onduty End ####################//

//#################### Onduty for All ####################//
	public function dispOnduty ($user_type,$user_id)
	{
			$year_id = $this->getYear();

            if ($user_type=='2'){
			     $Onduty_query = "SELECT
                                    A.od_for,
                                    A.from_date,
                                    A.to_date,
                                    A.notes,
                                    A.status,
                                    C.teacher_id,
                                    D.name
                                FROM
                                    edu_on_duty A,
                                    edu_users C,
                                    edu_teachers D
                                WHERE
                                    A.user_id = C.user_id AND C.teacher_id = D.teacher_id AND A.user_type = '$user_type' AND A.user_id = '$user_id' AND A.year_id = '$year_id'";
            }

            if ($user_type=='3'){
			     $Onduty_query = "SELECT
                                    A.od_for,
                                    A.from_date,
                                    A.to_date,
                                    A.notes,
                                    A.status,
                                    C.student_id,
                                    D.name
                                FROM
                                    edu_on_duty A,
                                    edu_users C,
                                    edu_admission D
                                WHERE
                                    A.user_id = C.user_id AND C.student_id = D.admission_id AND A.user_type = '$user_type' AND A.user_id = '$user_id' AND A.year_id = '$year_id'";
            }

            if ($user_type=='4')
            {
                $user_sql = "SELECT *  FROM `edu_users` WHERE student_id = '$user_id'";
                $user_result = $this->db->query($user_sql);
        		$user_ress = $user_result->result();

        		if($user_result->num_rows()>0)
        		{
        			foreach ($user_result->result() as $rows)
        			{
        				  $user_id = $rows->user_id;
        			}
        		}
        		  $user_type = '3';
        		  $Onduty_query = "SELECT
                                    A.od_for,
                                    A.from_date,
                                    A.to_date,
                                    A.notes,
                                    A.status,
                                    C.student_id,
                                    D.name
                                FROM
                                    edu_on_duty A,
                                    edu_users C,
                                    edu_admission D
                                WHERE
                                    A.user_id = C.user_id AND C.student_id = D.admission_id AND A.user_type = '$user_type' AND A.user_id = '$user_id' AND A.year_id = '$year_id'";
                }


			$Onduty_res = $this->db->query($Onduty_query);
			$Onduty_result = $Onduty_res->result();

			 if($Onduty_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Onduty Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Onduty", "ondutyDetails"=>$Onduty_result);
			}

			return $response;
	}
//#################### Onduty End ####################//

//#################### View Groups ####################//
	public function dispGrouplist ($user_type,$user_id)
	{
			$year_id = $this->getYear();

            if ($user_type=='1'){
			     $Group_query = "SELECT id, group_title FROM `edu_grouping_master` WHERE year_id = '$year_id'";
            } else {
				 $Group_query = "SELECT id, group_title FROM `edu_grouping_master` WHERE year_id = '$year_id' AND group_lead_id = '$user_id'";
			}

			$Group_res = $this->db->query($Group_query);
			$Group_result = $Group_res->result();

			 if($Group_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Groups Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Groups", "groupDetails"=>$Group_result);
			}

			return $response;
	}
//#################### View Groups End ####################//

//#################### Send Group Message ####################//
/*	public function sendGroupmessageold ($group_title_id,$message_type,$message_details,$created_by)
	{
			$year_id = $this->getYear();

			$m_type = explode(",", $message_type);
			$m_type_cnt = count($m_type);

			if($m_type_cnt==1){
				 $m_type1=$m_type[0];
			}

			if($m_type_cnt==2){
				 $m_type1=$m_type[0];
				 $m_type2=$m_type[1];
			}

			if($m_type_cnt==3){
				 $m_type1=$m_type[0];
				 $m_type2=$m_type[1];
				 $m_type3=$m_type[2];
			}


			if($m_type_cnt==3) {
                $subject = 'Group Notification';
				$email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
				$email_res = $this->db->query($email_query);
			    $email_result = $email_res->result();

    			 if($email_res->num_rows()!=0){
    				foreach ($email_result as $rows)
        			{
        				  $sEmail = $rows->email;
        				  $this->sendMail($sEmail,$subject,$message_details);
        			}
    			 }


				$mobile_query = "SELECT egm.group_member_id, ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
				$mobile_res = $this->db->query($mobile_query);
			    $mobile_result = $email_res->result();

    			 if($mobile_res->num_rows()!=0){
    				foreach ($mobile_result as $rows)
        			{
        				  $sMobile = $rows->mobile;
        				  $this->sendSMS($sMobile,$message_details);
        			}
    			 }

    			$gcm_query = "SELECT egm.group_member_id,ep.parent_id,en.gcm_key FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id = eu.user_id WHERE egm.group_title_id = '$group_title_id'";
				$gcm_res = $this->db->query($gcm_query);
			    $gcm_result = $gcm_res->result();

    			 if($gcm_res->num_rows()!=0){
    				foreach ($gcm_result as $rows)
        			{
        				$sParent_id = $rows->parent_id;

        				$sql = "SELECT eu.user_id,en.gcm_key FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$sParent_id'";
						$sgsm  = $this->db->query($sql);
						$res = $sgsm->result();

						foreach($res as $row){
						    $sGcm_key = $row->gcm_key;
						    $this->sendNotification($sGcm_key,$subject,$message_details);
						}

        			}
    		    }

			 }


			if($m_type_cnt==2) {
			     if($m_type1=='SMS' && $m_type2=='Mail')
		 		  {
					    $subject = 'Group Notification';
        				$email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
        				$email_res = $this->db->query($email_query);
        			    $email_result = $email_res->result();

            			 if($email_res->num_rows()!=0){
            				foreach ($email_result as $rows)
                			{
                				  $sEmail = $rows->email;
                				  $this->sendMail($sEmail,$subject,$message_details);
                			}
            			 }


        				$mobile_query = "SELECT egm.group_member_id, ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
        				$mobile_res = $this->db->query($mobile_query);
        			    $mobile_result = $email_res->result();

            			 if($mobile_res->num_rows()!=0){
            				foreach ($mobile_result as $rows)
                			{
                				  $sMobile = $rows->mobile;
                				  $this->sendSMS($sMobile,$message_details);
                			}
    			         }
		 		  }
		 		  if($m_type1=='SMS' && $m_type2=='Notification')
		 		  {
					    $subject = 'Group Notification';
        				$email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
        				$email_res = $this->db->query($email_query);
        			    $email_result = $email_res->result();

            			 if($email_res->num_rows()!=0){
            				foreach ($email_result as $rows)
                			{
                				  $sEmail = $rows->email;
                				  $this->sendMail($sEmail,$subject,$message_details);
                			}
            			 }

        			 	$gcm_query = "SELECT egm.group_member_id,ep.parent_id,en.gcm_key FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id = eu.user_id WHERE egm.group_title_id = '$group_title_id'";
        				$gcm_res = $this->db->query($gcm_query);
        			    $gcm_result = $gcm_res->result();

            			 if($gcm_res->num_rows()!=0){
            				foreach ($gcm_result as $rows)
                			{
                				$sParent_id = $rows->parent_id;

                				$sql = "SELECT eu.user_id,en.gcm_key FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$sParent_id'";
        						$sgsm  = $this->db->query($sql);
        						$res = $sgsm->result();

        						foreach($res as $row){
        						    $sGcm_key = $row->gcm_key;
        						    $this->sendNotification($sGcm_key,$subject,$message_details);
        						}

                			}
		 		        }
		 		  }
		 		  if($m_type1=='Mail' && $m_type2=='Notification')
		 		  {
		 		        $subject = 'Group Notification';
        				$email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
        				$email_res = $this->db->query($email_query);
        			    $email_result = $email_res->result();

            			 if($email_res->num_rows()!=0){
            				foreach ($email_result as $rows)
                			{
                				  $sEmail = $rows->email;
                				  $this->sendMail($sEmail,$subject,$message_details);
                			}
            			 }

 					    $gcm_query = "SELECT egm.group_member_id,ep.parent_id,en.gcm_key FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id = eu.user_id WHERE egm.group_title_id = '$group_title_id'";
        				$gcm_res = $this->db->query($gcm_query);
        			    $gcm_result = $gcm_res->result();

            			 if($gcm_res->num_rows()!=0){
            				foreach ($gcm_result as $rows)
                			{
                				$sParent_id = $rows->parent_id;

                				$sql = "SELECT eu.user_id,en.gcm_key FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$sParent_id'";
        						$sgsm  = $this->db->query($sql);
        						$res = $sgsm->result();

        						foreach($res as $row){
        						    $sGcm_key = $row->gcm_key;
        						    $this->sendNotification($sGcm_key,$subject,$message_details);
        						}

                			}
            			 }
		 		   }
			    }


			if($m_type_cnt==1) {
                if($m_type1=='Mail'){
                    $subject = 'Group Notification';
    				$email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
    				$email_res = $this->db->query($email_query);
    			    $email_result = $email_res->result();

        			 if($email_res->num_rows()!=0){
        				foreach ($email_result as $rows)
            			{
            				  $sEmail = $rows->email;
            				  $this->sendMail($sEmail,$subject,$message_details);
            			}
        			 }
				  }

                if($m_type1=='SMS') {
				    $mobile_query = "SELECT egm.group_member_id, ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'";
    				$mobile_res = $this->db->query($mobile_query);
    			    $mobile_result = $email_res->result();

        			 if($mobile_res->num_rows()!=0){
        				foreach ($mobile_result as $rows)
            			{
            				  $sMobile = $rows->mobile;
            				  $this->sendSMS($sMobile,$message_details);
            			}
			         }
				}

				if($m_type1=='Notification') {
                    $gcm_query = "SELECT egm.group_member_id,ep.parent_id,en.gcm_key FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id = eu.user_id WHERE egm.group_title_id = '$group_title_id'";
                    $gcm_res = $this->db->query($gcm_query);
                    $gcm_result = $gcm_res->result();

                    if($gcm_res->num_rows()!=0){
                    foreach ($gcm_result as $rows)
                        {
                        	$sParent_id = $rows->parent_id;

                        	$sql = "SELECT eu.user_id,en.gcm_key FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$sParent_id'";
                        	$sgsm  = $this->db->query($sql);
                        	$res = $sgsm->result();

                        	foreach($res as $row){
                        	    $sGcm_key = $row->gcm_key;
                        	    $this->sendNotification($sGcm_key,$subject,$message_details);
                        	}

                        }
                    }
				}

			 }

		    $grouphistory_query = "INSERT INTO `edu_grouping_history`(`group_title_id`, `notes`, `notification_type`, `status`, `created_by`, `created_at`) VALUES ('$group_title_id','$message_details','$message_type','Active','$created_by',NOW())";
			$grouphistory_res = $this->db->query($grouphistory_query);
			$last_historyid = $this->db->insert_id();

			if($grouphistory_res) {
				$response = array("status" => "success", "msg" => "Group Message Added", "last_group_history_id"=>$last_historyid);
			} else {
				$response = array("status" => "error");
			}

			return $response;
	}*/
//#################### Group Message End ####################//

//#################### Send Group Message ####################//
	public function sendGroupmessage ($group_title_id,$messagetype_sms,$messagetype_mail,$messagetype_notification,$message_details,$created_by)
	{
			$year_id = $this->getYear();
            $message_type ='';

                if($messagetype_sms=="1"){
                     $message_type = "SMS";
                }

                if ($messagetype_mail=="1"){
                        if ($message_type=='') {
                             $message_type = "Mail";
                         } else {
                             $message_type = $message_type.",Mail";
                        }
                }
                if ($messagetype_notification=="1"){
                        if ($message_type=='') {
                             $message_type = "Notification";
                        } else {
                             $message_type = $message_type.",Notification";
                        }
                }


                if($messagetype_sms != 0){

                    $mobile_query = "SELECT egm.group_member_id, ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id' AND ep.primary_flag = 'Yes'";
                	$mobile_res = $this->db->query($mobile_query);
                    $mobile_result = $mobile_res->result();

                	 if($mobile_res->num_rows()!=0){
                		foreach ($mobile_result as $rows)
                		{
                			  $sMobile = $rows->mobile;
                			  $this->sendSMS($sMobile,$message_details);
                		}
                     }
                }

            if($messagetype_mail != 0){
                $subject = 'Group Notification';
                $email_query = "SELECT egm.group_member_id, ep.email FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET( ea.admission_id,ep.admission_id)WHERE egm.group_title_id = '$group_title_id'  AND ep.primary_flag = 'Yes'";
                $email_res = $this->db->query($email_query);
                $email_result = $email_res->result();

                 if($email_res->num_rows()!=0){
                	foreach ($email_result as $rows)
                	{
                		  $sEmail = $rows->email;
                		  $this->sendMail($sEmail,$subject,$message_details);
                	}
                 }

            }

            if($messagetype_notification != 0){
                $subject = 'Group Notification';

                $gcm_query = "SELECT egm.group_member_id,ep.id,en.gcm_key FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id = egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id = eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id = eu.user_id WHERE egm.group_title_id = '$group_title_id'  AND ep.primary_flag = 'yes'";
                $gcm_res = $this->db->query($gcm_query);
                $gcm_result = $gcm_res->result();

                if($gcm_res->num_rows()!=0){
                	foreach ($gcm_result as $rows)
                    {
                    	$sParent_id = $rows->id;

                    	$sql = "SELECT eu.user_id,en.gcm_key,en.mobile_type FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$sParent_id'";
                    	$sgsm  = $this->db->query($sql);
                    	$res = $sgsm->result();

                    	foreach($res as $row){
                    	    $sGcm_key = $row->gcm_key;
                    	    $this->sendNotification($sGcm_key,$subject,$message_details,$mobile_type);
                    	}

                    }
                }
            }

		    $grouphistory_query = "INSERT INTO `edu_grouping_history`(`group_title_id`, `notes`, `notification_type`, `status`, `created_by`, `created_at`) VALUES ('$group_title_id','$message_details','$message_type','Active','$created_by',NOW())";
			$grouphistory_res = $this->db->query($grouphistory_query);
			$last_historyid = $this->db->insert_id();

			if($grouphistory_res) {
				$response = array("status" => "success", "msg" => "Group Message Added", "last_group_history_id"=>$last_historyid);
			} else {
				$response = array("status" => "error");
			}

			return $response;
	}
//#################### Group Message End ####################//




//#################### View Group Messages ####################//
	public function dispGroupmessage ($user_type,$user_id)
	{
			$year_id = $this->getYear();

            if ($user_type=='1'){
			     $Group_query = "SELECT B.id, A.id AS group_title_id, A.group_title, B.notes, B.created_at FROM `edu_grouping_master` A, `edu_grouping_history` B WHERE A.year_id = '$year_id' AND A.id = B.`group_title_id` ORDER BY B.id DESC";
            } else {
				 $Group_query = "SELECT B.id, A.id AS group_title_id, A.group_title, B.notes, B.created_at FROM `edu_grouping_master` A, `edu_grouping_history` B WHERE A.year_id = '$year_id' AND A.id = B.`group_title_id` AND group_lead_id = '$user_id' ORDER BY B.id DESC";
			}

			$Group_res = $this->db->query($Group_query);
			$Group_result = $Group_res->result();

			 if($Group_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Group Message Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Group Messages", "groupmsgDetails"=>$Group_result);
			}

			return $response;
	}
//#################### View Group Messages End ####################//

//#################### Leave Details ####################//
	public function dispLeaves ($user_type,$class_id,$sec_id,$class_sec_id)
	{
			$year_id = $this->getYear();

			if ($user_type == '1') {
				$class_query = "SELECT * from edu_classmaster WHERE class='$class_id' AND section = '$sec_id'";
				$class_res = $this->db->query($class_query);
				$class_result = $class_res->result();

					foreach($class_result as $rows){
						$class_sec_id = $rows->class_sec_id;
					}
			}

		     $leave_query = "SELECT * FROM (SELECT
								el.leave_date AS START,
								el.days AS day,
								el.leaves_name AS title,
								lm.leave_type AS description,
								lm.status
							FROM
								edu_leavemaster AS lm,
								edu_leaves AS el
							WHERE
								lm.leave_id = el.leave_mas_id AND lm.leave_type = 'Special Holiday' AND FIND_IN_SET('$class_sec_id', lm.leave_classes) AND lm.status = 'Active'

							UNION

							SELECT
								eh.leave_list_date AS START,
								eh.day,
								lm.leave_type AS title,
								lm.leave_type AS description,
								lm.status
							FROM
								edu_holidays_list_history AS eh
							LEFT OUTER JOIN edu_leavemaster AS lm
							ON
								lm.leave_id = eh.leave_masid
							WHERE
								FIND_IN_SET('$class_sec_id', lm.leave_classes) AND lm.status = 'Active') val ORDER by START";

			$leave_res = $this->db->query($leave_query);
			$leave_result= $leave_res->result();

			 if($leave_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Leaves Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Leaves", "leaveDetails"=>$leave_result);
			}

			return $response;
	}
//#################### Leaves End ####################//

//#################### Leave Details ####################//
	public function disp_upcomingLeaves ($user_type,$class_id,$sec_id,$class_sec_id)
	{
			$year_id = $this->getYear();

			if ($user_type == '1') {
				$class_query = "SELECT * from edu_classmaster WHERE class='$class_id' AND section = '$sec_id'";
				$class_res = $this->db->query($class_query);
				$class_result = $class_res->result();

					foreach($class_result as $rows){
						$class_sec_id = $rows->class_sec_id;
					}
			}

		     $leave_query = "SELECT * FROM ( SELECT
							el.leave_date AS START,
							el.days AS day,
							el.leaves_name AS title,
							lm.leave_type AS description,
							lm.status
						FROM
							edu_leavemaster AS lm,
							edu_leaves AS el
						WHERE
							lm.leave_id = el.leave_mas_id AND lm.leave_type = 'Special Holiday' AND FIND_IN_SET('$class_sec_id', lm.leave_classes) AND lm.status = 'Active'  AND el.leave_date >=CURDATE()

						UNION

						SELECT
							eh.leave_list_date AS START,
							eh.day,
							lm.leave_type AS title,
							lm.leave_type AS description,
							lm.status
						FROM
							edu_holidays_list_history AS eh
						LEFT OUTER JOIN edu_leavemaster AS lm
						ON
							lm.leave_id = eh.leave_masid
						WHERE
							FIND_IN_SET('$class_sec_id', lm.leave_classes) AND lm.status = 'Active' AND eh.leave_list_date >=CURDATE()) val ORDER by START";

			$leave_res = $this->db->query($leave_query);
			$leave_result= $leave_res->result();

			 if($leave_res->num_rows()==0){
				 $response = array("status" => "error", "msg" => "Leaves Not Found");
			}else{
				$response = array("status" => "success", "msg" => "View Leaves", "upcomingleavesDetails"=>$leave_result);
			}

			return $response;
	}
//#################### Leaves End ####################//

	//#################### Timetable days ####################//

	public function dispTimetable_days($class_id)
	{
	    $year_id = $this->getYear();
		 $term_id = $this->getTerm();

		 $sqldays = "SELECT A.day_id, B.list_day FROM `edu_timetable` A, `edu_days` B WHERE A.day_id = B.d_id AND A.class_id = '$class_id' AND A.year_id = '$year_id' AND A.term_id = '$term_id' GROUP BY day_id ORDER BY A.day_id";

			$day_res = $this->db->query($sqldays);
			$day_result= $day_res->result();
			$day_count = $day_res->num_rows();

		if($day_count>0)
		{
			 $response = array("status" => "success", "msg" => "Timetable Days", "timetableDays"=>$day_result);
		} else {
			$response = array("status" => "error", "msg" => "No Records Found");
		}
		return $response;
	}

	//#################### Timetable days End ####################//

	//#################### Timetable ####################//

	public function dispTimetable($class_id,$day_id)
	{
	    $year_id = $this->getYear();
		$term_id = $this->getTerm();

		$sqltimetable = "SELECT A.class_id, A.day_id, A.period, A.subject_id, IFNULL(B.subject_name,'') as subject_name, IFNULL( C.name,'') as name, A.from_time, A.to_time, A.is_break,A.break_name FROM edu_timetable AS A LEFT JOIN edu_teachers AS C ON A.teacher_id = C.teacher_id LEFT JOIN edu_subject AS B ON A.subject_id = B.subject_id WHERE A.year_id = '$year_id' AND A.term_id = '$term_id' AND A.class_id = '$class_id' AND A.day_id = '$day_id' ORDER BY A.period";

			$timetable_res = $this->db->query($sqltimetable);
			$timetable_result= $timetable_res->result();
			$timetable_count = $timetable_res->num_rows();

		if($timetable_count>0)
		{
			 $response = array("status" => "success", "msg" => "Timetable Days", "timeTable"=>$timetable_result);
		} else {
			$response = array("status" => "error", "msg" => "No Records Found");
		}
		return $response;
	}

	//#################### Timetable End ####################//




  function groupmessagehistory($group_id){
    $query="SELECT egh.group_title_id,egm.group_title,egh.notes,egh.notification_type,egh.created_by,eu.name,egh.created_at FROM edu_grouping_history AS egh
    LEFT JOIN edu_grouping_master AS egm  ON egh.group_title_id=egm.id LEFT JOIN edu_users as eu ON eu.user_id=egh.created_by WHERE egh.group_title_id='$group_id' order by egh.id desc;";
    $resultset=$this->db->query($query);
    $res=$resultset->result();
    $res_history_cnt = $resultset->num_rows();

    if($res_history_cnt>0)
    {
       $response = array("status" => "success", "msg" => "History Found", "msg_history"=>$res);
    } else {
      $response = array("status" => "error", "msg" => "No Records Found");
    }
    return $response;

  }
}





?>
