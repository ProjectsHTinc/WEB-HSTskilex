<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

	function __construct() {
		 parent::__construct();
	}


    public function check_customers()
	{
	    echo $chkConst = "SELECT * FROM customer_details";
		$result = $this->db->query($chkConst);
		if($result->num_rows()>0){
			 foreach($result->result() as $row){
				 $user_master_id = $row->user_master_id;
                 echo $user_master_id;
                 echo "<br>";
				 
					 echo $chkConst_1 = "SELECT * FROM login_users WHERE id = '$user_master_id' AND user_type = '5'";
					 $result_1 = $this->db->query($chkConst_1);
					 if($result->num_rows()>0){
					 } else {
						 	echo $query="DELETE FROM customer_details WHERE id = '$user_master_id'";
							//$result=$this->db->query($query);
					 }
					echo "<br>";

					}
			 }
	}
