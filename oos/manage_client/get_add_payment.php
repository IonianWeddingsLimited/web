<?
// function to check date
function validateDate($date, $format = 'Y-m-d H:i:s') {
    $version = explode('.', phpversion());
    if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
        $d = DateTime::createFromFormat($format, $date);
    } else {
        $d = new DateTime(date($format, strtotime($date)));
    }
    return $d && $d->format($format) == $date;
}

// set date
$curr_date = date("Y-m-d H:i:s");
// take current date of payment
$defaultDate = (isset($_POST['dateDef'])) ? $_POST['dateDef'] : $curr_date ;
// date filter check array
$datecheckv = array("/",".","-",":");
//take posted date
$dateU = $_POST['dateT'];
//get current time
$timeU = date("H:i:s");

//split posted date into variables for days months and years
list($dd,$mm,$yy) = explode("-",$dateU);
// assign date and time to variable
$dateTimeU = $yy."-".$mm."-".$dd." ".$timeU;

// check is a valid date
$checkdate = validateDate($dateTimeU);

// if date is valid use it otherwise use default
$dateUp = ($checkdate) ? $dateTimeU : $defaultDate;

// if post action = Add Payment, assign add otherwise assign mod
$posttype = ($_POST['action']=="Add Payment") ? "add" : "mod";

// check if posttype is add
if ($posttype == "add") {
	// if exchange rate is not set, flag error
	if(!$_POST["exchange_rate"]) { $error .= "Missing exchange rate<br>"; }
	// if exchange rate is less than 0, flag error
	if($_POST["exchange_rate"] <= 0) { $error .= "Please enter an exchange rate greater than 0<br>"; }
	// if payment amount is not set, flag error
	if(!$_POST["payment"]) { $error .= "Missing payment rate<br>"; }
	// if payment amount is less than 0, flag error
//	if($_POST["payment"] <= 0) {
	//	$error .= "Please enter a payment greater than 0<br>";
//	}
	//$error .= "Payment".$_POST["payment"]."<br>";

	// assign variable of payment amount
	$poundcost = $_POST["payment"];
	// assign variable of payment status
	$pstatus = $_POST['pstatus'];
	// assign array of payments
	$payinv = $_POST['payinv'];
	// assign variable of transaction id or use 0
	$transid = (!$_POST['transid']) ? 0: $_POST['transid'];
	// assign variable of payment type
	$paymentType = $_POST['paymentT'];
	
	// default totals variable
	$totals = 0;
	// call all payments and assign to p variable
	
	if ($payinv != 0) {
	
		foreach ($payinv as $p) {
			// default allocate value
			$allocate = 0;
			// assign currency for required invoice payment
			$currency = $_POST[$p.'-currency'];
			$itotals = $_POST[$p.'-total'];
			if ($_POST['currency']==$currency) { $allocate = $_POST[$p.'-alloc']; }
			elseif ($_POST['currency']=="Pound") { $allocate = $_POST[$p.'-alloc'] / $_POST["exchange_rate"]; }
			else { $allocate = $_POST[$p.'-alloc'] * $_POST["exchange_rate"]; }
		
			$filterv = array("£",",","€"," ");
			$allocate = str_replace($filterv,"",$allocate);
			
			$invid = $_POST[$p.'-invid'];	
			$itotals = $_POST[$p.'-total'];
			
			$filterv = array("£",",","€"," ");
			$itotals = str_replace($filterv,"",$itotals);
	
			$itype = $_POST[$p.'-type'];
			$iorderid = $_POST[$p.'-orderid'];
			
			if ($allocate > 0 && $_POST['currency']!==$currency) { 
				if ($currency=="Pound") { $error .= "Cannot allocate a Pound payment to a Euro invoice."; }
				if ($currency=="Euro") { $error .= "Cannot allocate a Euro payment to a Pound invoice."; }		
			}
	
			$totals += $allocate;
			
			//if ($allocate<0) { $error .= "Cannot allocate a negative amount."; }
			//if ($allocate>$itotals) { $error .= "Total allocated for Invoice ".$invid." exceeds invoice amount.<br />"; }
		}
	}
	$filterv = array("£",",","€"," ");
	$poundcost = str_replace($filterv,"",$poundcost);

	if ($totals>$poundcost) { $error .= "Total allocated exceeds payment amount.<br />"; }
		
	
	if($error) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=partpay&id=".$_POST["client_id"]."&pid=".$_POST["payment_id"]."&f=add");
		$get_template->bottomHTML();	
		$sql_command->close();
	}


	$cols = "customer_id,
	p_amount,
	currency,
	payment_exchange,
	transaction_id,
	ip_add,
	status,
	cardtype,
	timestamp";
	
	$vals = "'".addslashes($_POST['client_id'])."',
	'".addslashes($poundcost)."',
	'".addslashes($_POST['currency'])."',
	'".addslashes($_POST['exchange_rate'])."',
	'".addslashes($transid)."',
	'".$the_username."',
	'".$pstatus."',
	'".$paymentType."',
	'".$dateUp."'";
	
	$sql_command->insert("customer_transactions",$cols,$vals);
	$maxid = $sql_command->maxid(customer_transactions,"p_id");
	$status_line = "Payment created (# ".$maxid.") Type - ".$_POST["status"];
	$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$the_username."','".$status_line."','".$time."'");
	
	if ($payinv != 0) {
	foreach ($payinv as $p) {
		$allocate = 0;
		$currency = $_POST[$p.'-currency'];
		
		$allocate = $_POST[$p.'-alloc'];

	
		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		
		$invid = $_POST[$p.'-invid'];	
		$itotals = $_POST[$p.'-total'];

		$itype = $_POST[$p.'-type'];
		$iorderid = $_POST[$p.'-orderid'];
		
		$cols = "pay_no,invoice_id,order_id,p_amount,payment_currency,status";
		$allocated = ($allocate==0) ? 'Unpaid': ($itotals<$allocate) ? 'Part Paid' : 'Paid';
		$vals = "'".addslashes($maxid)."',
		'".addslashes($invid)."',
		'".addslashes($iorderid)."',
		'".addslashes($allocate)."',
		'".$currency."',
		'".$allocated."'";

		if($allocate!=0) { 
			$success = $sql_command->insert("customer_payments",$cols,$vals);
			
			$payval = $sql_command->select("customer_payments,customer_transactions",
										   "sum(customer_payments.p_amount)",
										   "WHERE customer_transactions.p_id = customer_payments.pay_no
										   AND customer_payments.invoice_id='".addslashes($invid)."'
										   AND customer_transactions.status = 'Paid'");
			$payvalr = $sql_command->result($payval);		
			
			$result = $sql_command->select("$database_customer_invoices","iw_cost,type,status",
										   "WHERE id = '".addslashes($invid)."'
										   AND (status != 'Paid' or status != 'Cancelled')");
			$row = $sql_command->result($result);
					
			if ($row[1]=="Deposit") {
				$totalin = $row[0];
			}
			else {
				$invoiceno = $invid;
				include("../_includes/fn_invoice-payment-v3.php");	
				$totalin = $total_gbp;						
			}
			$totalin = ($totalin<0) ? 0 : str_replace(",","",number_format($totalin,2));	
			if ($payvalr[0]>=$totalin && $row[2]!='Paid' && $pstatus=='Paid') {	
				$sql_command->update($database_customer_invoices,"status='Paid'","id='".addslashes($invid)."'");	
				$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($invid)."'");
				
				$sql_command->update("$database_invoice_history",
									 "$database_invoice_history.status='Paid'",
									 "$database_invoice_history.invoice_id='".addslashes($invid)."'");				
			
				$status_line = "Invoice Status (# ".$invid.") Updated - ".$pstatus;
				$sql_command->insert($database_client_historyinfo,
									 "client_id,user_id,comment,timestamp",
									 "'".addslashes($_POST["client_id"])."',
									 '".$login_record[1]."','".$status_line."','".$time."'");	
				
				$sql_command->update("customer_payments,customer_transactions",
									 "customer_payments.status='Cancelled'",
									 "customer_transactions.p_id = customer_payments.pay_no
									 AND customer_transactions.status != 'Paid'
									 AND customer_payments.p_amount > 0	
									 AND customer_payments.invoice_id = '".addslashes($invid)."'");
				
				$sql_command->update("customer_payments,customer_transactions",
									 "customer_payments.p_amount=0",
									 "customer_transactions.p_id = customer_payments.pay_no
									 AND customer_transactions.status != 'Paid'
									 AND customer_payments.p_amount > 0
									 AND customer_payments.invoice_id = '".addslashes($invid)."'");
			}		
		}
	}
	}
		

	header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
	$sql_command->close();
	
}
else {

	$result2 = $sql_command->select("customer_transactions",
									"p_id,
									p_amount,
									currency,
									transaction_id,
									ip_add,
									status,
									cardtype,
									timestamp,
									currency",
									"WHERE p_id='".addslashes($_POST['payment_id'])."'");
	$record2 = $sql_command->result($result2);
	
	$total_due = $record2[1]; // assign p_amount
	$pay_currency = $record2[8];
	$totals=0; // default totals
	$totalsall = 0;
	// assign variable of payment status
	$pstatus = $_POST['pstatus'];
	// assign array of payments
	$payinv = $_POST['payinv'];
	
//	echo count($payinv);
	$totalsall = 0;
	foreach ($payinv as $p) {
		$allocate = $_POST[$p.'-alloc'];
		// assign currency for required invoice payment
		$currency = $_POST[$p.'-currency'];
		$current = $_POST[$p.'-current'];
		$itotals = $_POST[$p.'-total'];
		$difference = ($current===$allocate) ? 0 : abs($current - $allocate);
		//if ($allocate<0) { $error .= "Cannot allocate a negative amount."; }
		//if (($allocate-$current)>$itotals) { $error .= "Total allocated for Invoice ".$invid." exceeds invoice amount."; }
		
		if ($pay_currency===$currency) { $allocate = $_POST[$p.'-alloc']; }
		elseif ($pay_currency==="Pound") { $allocate = $_POST[$p.'-alloc'] / $_POST["exchange_rate"]; }
		else { $allocate = $_POST[$p.'-alloc'] * $_POST["exchange_rate"]; }

		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		
		$invid = $_POST[$p.'-invid'];	
		$itotals = $_POST[$p.'-total'];

		$itype = $_POST[$p.'-type'];
		$iorderid = $_POST[$p.'-orderid'];
		if ($allocate > 0 && $pay_currency!==$currency) { 
			if ($pay_currency=="Pound") { $error .= "Cannot allocate a Pound payment to a Euro invoice."; }
			if ($pay_currency=="Euro") { $error .= "Cannot allocate a Euro payment to a Pound invoice."; }		
		}
		
		
		$totalsall += $allocate;
	}
	
	//if ($totalsall>$total_due) { $error .= "Total allocated: ".$allocate." would exceed payment amount of ".$total_due.$pay_currency.$currency."."; }
	
	
	if($error) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=partpay&id=".$_POST["client_id"]."&pid=".$_POST['payment_id']."&f=mod");
		$get_template->bottomHTML();	
		$sql_command->close();
	}
	
	$pid = $_POST['payment_id'];

	
	
	$sql_command->update("customer_transactions","status='".$pstatus."',timestamp='".$dateUp."'","p_id='".addslashes($pid)."'");
	$status_line = "Payment updated (# ".$_POST['payment_id'].")";
	$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$the_username."','".$status_line."','".$time."'");

	$cols = "pay_no,invoice_id,order_id,p_amount,payment_currency,status";

	foreach ($payinv as $p) {
		$allocate = 0;
		$currency = $_POST[$p.'-currency'];
		
		
		$allocate = $_POST[$p.'-alloc'];
	
		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		
		$invid = $_POST[$p.'-invid'];	
		$itotals = $_POST[$p.'-total'];

		$itype = $_POST[$p.'-type'];
		$iorderid = $_POST[$p.'-orderid'];
		
		$current = ($_POST[$p.'-current']>0) ? $_POST[$p.'-current'] : 0;
		$pid = $_POST[$p.'-pid'] ? $_POST[$p.'-pid'] : "";
		$invid = $_POST[$p.'-invid'] ? $_POST[$p.'-invid'] : "";
			
		
		$allocated = ($allocate<=0) ? 'Unpaid': ($itotals<$allocate) ? 'Part Paid' : 'Paid';
		
		$vals = "'".$_POST['payment_id']."',
		'".addslashes($invid)."',
		'".addslashes($iorderid)."',
		'".addslashes($allocate)."',
		'".$currency."',
		'".$allocated."'";
		
		$difference = ($current===$allocate) ? 0 : (($current - $allocate)<0) ? $allocate - $current : $current - $allocate;
	

		if (!empty($pid)) {
			$success = $sql_command->update("customer_payments",
											"p_amount='".addslashes($allocate)."',
											status='".$allocated."',
											payment_currency='".$currency."'",
											"p_id='".addslashes($pid)."'");
		}

		else {

			$success = ($allocate!=0) ? $sql_command->insert("customer_payments",$cols,$vals) : false;
		}
		
		$payval = $sql_command->select("customer_payments,customer_transactions",
									   "sum(customer_payments.p_amount)",
									   "WHERE customer_transactions.p_id = customer_payments.pay_no
									   AND customer_payments.invoice_id='".addslashes($invid)."'
									   AND customer_transactions.status = 'Paid'");
		$payvalr = $sql_command->result($payval);		
		
		$result = $sql_command->select("$database_customer_invoices","iw_cost,type,status",
									   "WHERE id = '".addslashes($invid)."'
									   AND (status != 'Paid' or status != 'Cancelled')");
		$row = $sql_command->result($result);

		$invoiceno = $invid;				
		if ($row[1]=="Deposit") {
			$invoicer_result = $sql_command->select("$database_invoice_history 
													LEFT OUTER JOIN $db_currency_conversion 
													ON $db_currency_conversion.currency_name = $database_invoice_history.currency",
													"$database_invoice_history.currency",
													"WHERE invoice_id='".addslashes($invid)."'");
		
			$invoice_rowr = $sql_command->result($invoicer_result);
			$currency_name = $invoice_rowr[0];

			include("../_includes/fn_invoice-payment-v3.php");
			$totalin = $total_gbp;
		}
		else {
		
			include("../_includes/fn_invoice-payment-v3.php");	
			$totalin = $total_gbp;
		}
		
		$totalin = ($totalin<0) ? 0 : str_replace(",","",number_format($totalin,2));
		
		if ($payvalr[0]>=$totalin && $row[2]!='Paid' && $pstatus=='Paid' && ($allocate > 0 || $current >0)) {	
			$sql_command->update($database_customer_invoices,"status='Paid'","id='".addslashes($invid)."'");	
			$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($invid)."'");
			
			$sql_command->update("$database_invoice_history",
								 "$database_invoice_history.status='Paid'",
								 "$database_invoice_history.invoice_id='".addslashes($invid)."'");				

			$sql_command->update("$database_order_history",
								 "$database_order_history.status='Paid'",
								 "$database_order_history.invoice_id='".addslashes($invid)."'");				

			$status_line = "Invoice Status (# ".$invid.") Updated - ".$pstatus;
			$sql_command->insert($database_client_historyinfo,
								 "client_id,user_id,comment,timestamp",
								 "'".addslashes($_POST["client_id"])."',
								 '".$login_record[1]."','".$status_line."','".$time."'");	
			
			$sql_command->update("customer_payments,customer_transactions",
								 "customer_payments.status='Cancelled'",
								 "customer_transactions.p_id = customer_payments.pay_no
								 AND customer_transactions.status != 'Paid'
								 AND customer_payments.p_amount > 0	
								 AND customer_payments.invoice_id = '".addslashes($invid)."'");
			
			$sql_command->update("customer_payments,customer_transactions",
								 "customer_payments.p_amount=0",
								 "customer_transactions.p_id = customer_payments.pay_no
								 AND customer_transactions.status != 'Paid'
								 AND customer_payments.p_amount > 0
								 AND customer_payments.invoice_id = '".addslashes($invid)."'");
		}		
		else {
			if ($row[2]=="Paid" && ($allocate>0 or $current>0)) {	
				$pstatus = "Outstanding";
				$success = $sql_command->update($database_customer_invoices,"status='Outstanding'","id='".addslashes($invid)."'");
				$success = $sql_command->update($database_customer_invoices,"updated_timestamp=".$time,"id='".addslashes($invid)."'");
			
				$result = $sql_command->select("$database_customer_invoices","iw_cost,type",
											   "WHERE id = '".addslashes($invid)."'
											   AND (status != 'Paid' or status != 'Cancelled')");
				$row = $sql_command->result($result);
					
				$sql_command->update("$database_invoice_history",
									 "$database_invoice_history.status='Outstanding'",
									 "$database_invoice_history.invoice_id='".addslashes($invid)."'");				
		
				$status_line = "Invoice Status (# ".$invid.") Updated - Outstanding";
				$sql_command->insert($database_client_historyinfo,
									 "client_id,user_id,comment,timestamp",
									 "'".addslashes($_POST["client_id"])."',
									 '".$login_record[1]."','".$status_line."','".$time."'");	
				

			}
	
		}
	}

	header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
	$sql_command->close();
	
}

?>