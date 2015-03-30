<?
$uid = (isset($_POST['cli_id'])) ? "?a=history&id=".$_POST['cli_id'] : "";
if (count($_POST['payall'])>=0) {
	
	if ($_POST['action']==="Approve Payments") {
		$payall = $_POST['payall'];
		
		foreach($payall as $p) {
			$sql_command->update("customer_transactions","status='Paid'","p_id='".addslashes($p)."'");
			
			$pays = $sql_command->select("customer_payments",
										 "invoice_id,order_id",
										 "WHERE pay_no='".addslashes($p)."'");	
			$payr = $sql_command->results($pays);

			foreach($payr as $pay) {
				$payval = $sql_command->select("customer_payments,customer_transactions",
											   "sum(customer_payments.p_amount)",
											   "WHERE customer_transactions.p_id = customer_payments.pay_no
											   AND customer_payments.invoice_id='".addslashes($pay[0])."'
											   AND customer_transactions.status = 'Paid'");
				$payvalr = $sql_command->result($payval);		
				
				$result = $sql_command->select("$database_customer_invoices","iw_cost,type,status",
										   "WHERE id = '".addslashes($pay[0])."'
										   AND (status != 'Paid' or status != 'Cancelled')");
				$row = $sql_command->result($result);
				
				if ($row[1]=="Deposit") {
					$totalin = $row[0];
				}
				else {
					$invoiceno = $pay[0];
					include("../_includes/fn_invoice-payment-v3.php");	
					$totalin = $total_gbp;						
	
				}
				$totalin = ($totalin<0) ? 0 : str_replace(",","",number_format($totalin,2));	
				if ($payvalr[0]>=$totalin && $row[2]!='Paid') {	
					$sql_command->update($database_customer_invoices,"status='Paid'","id='".addslashes($pay[0])."'");	
					$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($pay[0])."'");
					
					$sql_command->update("$database_invoice_history",
										 "$database_invoice_history.status='Paid'",
										 "$database_invoice_history.invoice_id='".addslashes($pay[0])."'");				
				
					$status_line = "Invoice Status (# ".$pay[0].") Updated - ".$pstatus;
	
					$sql_command->insert($database_client_historyinfo,
										 "client_id,user_id,comment,timestamp",
										 "'".addslashes($_POST["client_id"])."',
										 '".$login_record[1]."','".$status_line."','".$time."'");	
					
					$sql_command->update("customer_payments,customer_transactions",
										 "customer_payments.status='Cancelled'",
										 "customer_transactions.p_id = customer_payments.pay_no
										 AND customer_transactions.status != 'Paid'
										 AND customer_payments.p_amount > 0	
										 AND customer_payments.invoice_id = '".addslashes($pay[0])."'");
					
					$sql_command->update("customer_payments,customer_transactions",
										 "customer_payments.p_amount=0",
										 "customer_transactions.p_id = customer_payments.pay_no
										 AND customer_transactions.status != 'Paid'
										 AND customer_payments.p_amount > 0
										 AND customer_payments.invoice_id = '".addslashes($pay[0])."'");
				}		
			}
			
		}
		header("Location: $site_url/oos/manage-client.php".$uid);

	}
	elseif ($_POST['action']==="Delete Payments") {
		$payall = $_POST['payall'];
		foreach($payall as $p) {

			$pays = $sql_command->select("customer_payments","invoice_id,order_id",
										 "WHERE pay_no='".addslashes($p)."'");
			$payr = $sql_command->results($pays);

			foreach($payr as $pay) {	

				$result = $sql_command->select("$database_customer_invoices","status",
											   "WHERE id = '".addslashes($pay[0])."'");
				$row = $sql_command->result($result);
				
				$payval = $sql_command->select("customer_payments,customer_transactions",
											   "sum(customer_payments.p_amount)",
											   "WHERE customer_transactions.p_id = customer_payments.pay_no
											   AND customer_payments.invoice_id='".addslashes($pay[0])."'
											   AND customer_transactions.status = 'Paid'");
				
				$payvalr = $sql_command->result($payval);	
				
				if ($row[0]=="Paid" && $payvalr[0]>0) { 
					$sql_command->update($database_customer_invoices,"status='Outstanding'","id='".addslashes($pay[0])."'");
					$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($pay[0])."'");
						
					$sql_command->update("$database_invoice_history",
										 "$database_invoice_history.status='Outstanding'",
										 "$database_invoice_history.invoice_id='".addslashes($pay[1])."'");				
	
							
					$status_line = "Invoice Status (# ".$p.") Updated - Outstanding";
					$sql_command->insert($database_client_historyinfo,
										 "client_id,user_id,comment,timestamp",
										 "'".addslashes($_POST["cli_id"])."',
										 '".$login_record[1]."','".$status_line."','".$time."'");	
				}	
				$sql_command->update("customer_payments","status='Cancelled'","pay_no='".addslashes($p)."'");
				$sql_command->update("customer_payments","p_amount=0","pay_no='".addslashes($p)."'");		
			}
			$sql_command->update("customer_transactions","status='Cancelled'","p_id='".addslashes($p)."'");	
			$sql_command->update("customer_transactions","reason='".$_POST['reason']."'","p_id='".addslashes($p)."'");
		}		
		header("Location: $site_url/oos/manage-client.php".$uid);
	}

	else {
		header("Location: $site_url/oos/manage-client.php".$uid);
	}
}
else {
	header("Location: $site_url/oos/manage-client.php".$uid); 
}
?>
