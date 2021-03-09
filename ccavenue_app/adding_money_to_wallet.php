<?php
    ob_start();
	error_reporting(0);
    include('Crypto.php');
    include("connection.php");

	$workingKey = '7FB71109FBD688214546E4C2BFF63D8B';		//Working Key should be provided here.
	$encResponse = $_POST["encResp"];			            //This is the response sent by the CCAvenue Server
	$rcvdString = decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status = "";
	$decryptValues = explode('&', $rcvdString);
	$dataSize = sizeof($decryptValues);


for($i = 0; $i < $dataSize; $i++)
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==0)   $orderid=$information[1];
		if($i==1)   $track_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
		if($i==3)	$order_status=trim($information[1]);
		if($i==4)   $failure_message=$information[1];
		if($i==5)   $payment_mode=$information[1];
		if($i==6)   $card_name=$information[1];
		if($i==7)   $status_code=$information[1];
		if($i==8)   $status_message=$information[1];
		if($i==9)   $currency=$information[1];
		if($i==10)  $amount=$information[1];
		if($i==11)  $billing_name=$information[1];
		if($i==12)  $billing_address=$information[1];
		if($i==13)  $billing_city=$information[1];
		if($i==14)  $billing_state=$information[1];
		if($i==15)  $billing_zip=$information[1];
		if($i==16)  $billing_country=$information[1];
		if($i==17)  $billing_tel=$information[1];
		if($i==18)  $billing_email=$information[1];
		if($i==19)  $delievery_name=$information[1];
		if($i==20)  $delievery_address=$information[1];
		if($i==21)  $delievery_city=$information[1];
		if($i==22)  $delievery_state=$information[1];
		if($i==23)  $delievery_zip=$information[1];
		if($i==24)  $delievery_country=$information[1];
		if($i==25)  $delievery_tel=$information[1];
		if($i==26)  $merch_param1=$information[1];
		if($i==27)  $merch_param2=$information[1];
		if($i==28)  $merch_param3=$information[1];
		if($i==29)  $merch_param4=$information[1];
		if($i==30)  $merch_param5=$information[1];
		if($i==31)  $vault=$information[1];
		if($i==32)  $offer_type=$information[1];
		if($i==33)  $offer_code=$information[1];
		if($i==34)  $discount_value=$information[1];
		if($i==35)  $mer_amt=$information[1];
		if($i==36)  $eci_value=$information[1];
		if($i==37)  $retry=$information[1];
		if($i==38)  $response_code=$information[1];
		if($i==39)  $billing_notes=$information[1];
		if($i==40)  $transdate=$information[1];
		if($i==41)  $bin_country=$information[1];
	}

    	$string = $orderid;
        $result = explode("-", $string);
        $order_id=$result[0];
        $user_id= $result[1];

		$sQuery = "INSERT INTO ccavenue_status (order_id,user_id,track_id,bank_ref_no,order_status,failure_message,payment_mode,card_name,status_code,status_message,currency,amount,billing_name,billing_address, billing_city,billing_state,billing_zip,billing_country,billing_tel,billing_email,delievery_name,delievery_address,delievery_city,delievery_state,delievery_zip,delievery_country,delievery_tel,merch_param1,merch_param2,merch_param3,merch_param4,merch_param5,vault,offer_type,offer_code,discount_value, mer_amt,eci_value,retry,response_code,billing_notes,trans_date,bin_country) VALUES ('$orderid','$user_id','$track_id','$bank_ref_no','$order_status','$failure_message','$payment_mode','$card_name','$status_code','$status_message','$currency','$amount','$billing_name','$billing_address','$billing_city','$billing_state','$billing_zip','$billing_country','$billing_tel','$billing_email','$delievery_name','$delievery_address','$delievery_city','$delievery_state','$delievery_zip','$delievery_country','$delievery_tel','$merch_param1','$merch_param2','$merch_param3','$merch_param4','$merch_param5','$vault','$offer_type','$offer_code','$discount_value','$mer_amt','$eci_value','$retry','$response_code','$billing_notes','$transdate','$bin_country')";
        mysqli_query($link, $sQuery);

    	if($order_status=="Success")
    	{
			$insert_sp = "INSERT INTO customer_wallet_history (customer_id,order_id,transaction_amt,notes,status,created_at,created_by) VALUES ('$user_id','$amount','Added money to wallet','Credited',NOW(),'$user_id')";
			mysqli_query($link, $insert_sp);

			$insert_sph = "SELECT * FROM customer_wallet WHERE customer_id='$user_id'";
			$result= mysqli_query($link, $insert_sph);
			if (mysqli_num_rows($result) == '0') {
				$wallet_query="INSERT INTO customer_wallet (customer_id,amt_in_wallet,total_amt_in_wallet,status,updated_at,updated_by) VALUES ('$user_id','$amount','$amount','Active',NOW(),'$user_id')";
			}else{
				$wallet_query="UPDATE customer_wallet SET amt_in_wallet=amt_in_wallet+'$amount',total_amt_in_wallet=total_amt_in_wallet+'$amount' WHERE customer_id='$user_id'";
			}
			mysqli_query($link, $wallet_query);
			$response["status"] = "Success";

    	}else{
			$response["status"] = $order_status;
		}

      echo json_encode($response);
?>
