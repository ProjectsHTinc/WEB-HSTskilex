<?php
date_default_timezone_set('Asia/Kolkata');
$current_time = date("h:i A", time());
// include("connection.php");

        $sQuery = "SELECT
						so.serv_prov_id,
						so.order_date,
						COUNT(so.serv_prov_id) AS service_per_day,
						SUM(sp.net_service_amount) AS service_total_amt,
						SUM(sp.serv_pro_net_amount) AS serv_prov_comm_amt,
						SUM(sp.skilex_net_amount) AS skilex_comm_amt,
						SUM(sp.skilex_tax_amount) AS tax_able_amt,
						SUM(sp.online_amount) AS online_trans_amt,
						SUM(sp.offline_amount) AS offline_trans_amt,
						SUM(sp.online_amount * 0.2) AS online_skile_com_amt,
						SUM(sp.online_amount * 0.8) AS online_sp_com_amt,
						SUM(sp.offline_amount * 0.2) AS offline_skile_com_amt,
						SUM(sp.offline_amount * 0.8) AS offline_sp_com_amt,
						(SUM(sp.online_amount * 0.8) - SUM(sp.offline_amount * 0.2)) AS pay_to_serv
					FROM
						service_orders AS so
					LEFT JOIN service_payments AS sp
					ON
						so.id = sp.service_order_id
					LEFT JOIN service_payment_history AS sphh
					ON
						sphh.payment_order_id = sp.id
					WHERE
						so.order_date = CURDATE() AND sp.status = 'Paid'
					GROUP BY
						so.serv_prov_id";

        $objRs = mysql_query($sQuery) or die("Could not select Query");

		if (mysql_num_rows($objRs)> 0)
        	{
        		while ($row = mysql_fetch_array($objRs))
        		{
					$serv_prov_id =  $order_id = trim($row['serv_prov_id']);
					$service_per_day =  $order_id = trim($row['service_per_day']);
					$order_date =  $order_id = trim($row['order_date']);
					$service_total_amt =  $order_id = trim($row['service_total_amt']);
					$serv_prov_comm_amt =  $order_id = trim($row['serv_prov_comm_amt']);
					$skilex_comm_amt =  $order_id = trim($row['skilex_comm_amt']);
					$tax_able_amt =  $order_id = trim($row['tax_able_amt']);
					$online_trans_amt =  $order_id = trim($row['online_trans_amt']);
					$offline_trans_amt =  $order_id = trim($row['offline_trans_amt']);
					$online_skile_com_amt =  $order_id = trim($row['online_skile_com_amt']);
					$online_sp_com_amt =  $order_id = trim($row['online_sp_com_amt']);
					$offline_skile_com_amt =  $order_id = trim($row['offline_skile_com_amt']);
					$offline_sp_com_amt =  $order_id = trim($row['offline_sp_com_amt']);
					$pay_to_serv =  $order_id = trim($row['pay_to_serv']);


				$insQuery = "INSERT INTO daily_payment_transaction(
								serv_prov_id,
								total_service_per_day,
								service_date,
								serv_total_amount,
								serv_prov_commission_amt,
								skilex_commission_amt,
								online_transaction_amt,
								offline_transaction_amt,
								online_skilex_commission,
								offline_skilex_commission,
								online_serv_prov_commission,
								offline_serv_prov_commission,
								taxable_amount,
								pay_to_serv_prov,
								skilex_closing_status,
								serv_prov_closing_status,
								created_at
							)
							VALUES(
								'$serv_prov_id',
								'$service_per_day',
								'$order_date',
								'$service_total_amt',
								'$serv_prov_comm_amt',
								'$skilex_comm_amt',
								'$online_trans_amt',
								'$offline_trans_amt',
								'$online_skile_com_amt',
								'$online_skile_com_amt',
								'$online_sp_com_amt',
								'$online_sp_com_amt',
								'$tax_able_amt',
								'$pay_to_serv',
								'Nopay',
								'Unpaid',
								NOW())";
				$insobjRs  = mysql_query($insQuery) or die("Could not select Query");
				}
        	}

?>
