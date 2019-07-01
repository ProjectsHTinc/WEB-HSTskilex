<?php
Class Mailmodel extends CI_Model
{
	public function __construct()
	{
	  parent::__construct();
	}



	function send_mail($email,$notes)
	{

		$to = $email;
		$subject="Skilex ";
		$htmlContent = '
		<html>
		<head>  <title></title>
		</head>
		<body>
		<p style="margin-left:30px;">'.$notes.'</p>
			</body>
		</html>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: skilex<info@skilex.com>' . "\r\n";
		mail($to,$subject,$htmlContent,$headers);
	}







}
?>
