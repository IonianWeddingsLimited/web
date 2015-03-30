<?

function validateDate($date, $format = 'Y-m-d H:i:s') {
    $version = explode('.', phpversion());
    if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
        $d = DateTime::createFromFormat($format, $date);
    } else {
        $d = new DateTime(date($format, strtotime($date)));
    }
    return $d && $d->format($format) == $date;
}

$curr_date = $timeU = date("Y-m-d H:i:s");
$defaultDate = (isset($_POST['dateDef'])) ? $_POST['dateDef'] : $curr_date ;
$datecheckv = array("/",".","-",":");
$dateU = $_POST['dateT'];
$timeU = date("H:i:s");

list($dd,$mm,$yy) = explode("-",$dateU);
$dateTimeU = $yy."-".$mm."-".$dd." ".$timeU;

$checkdate = validateDate($dateTimeU);

$dateUp = ($checkdate) ? $dateTimeU : $defaultDate;

$posttype = ($_POST['action']=="Add Payment") ? "add" : "mod";

if ($posttype == "add") {
	if(!$_POST["exchange_rate"]) { $error .= "Missing exchange rate<br>"; }
	if($_POST["exchange_rate"] <= 0) { $error .= "Please enter an exchange rate greater than 0<br>"; }
	if(!$_POST["payment"]) { $error .= "Missing payment rate<br>"; }
	//if($_POST["payment"] <= 0) { $error .= "Please enter a payment greater than 0<br>"; }

	$poundcost = $_POST["payment"];

	if($_POST["currency"] == "Pound") {
		$total_due = $poundcost;
	} else {
		$total_due = $poundcost / $_POST["exchange_rate"];
	}

	$payinv = $_POST['payinv'];
	
	if ($payinv <> "") {

	foreach ($payinv as $p) {
		$allocate = $_POST[$p.'-alloc'];	
		$totals = $totals + $allocate;	
		$itotals = $_POST[$p.'-total'];
		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		//if ($allocate<0) { $error .= "Cannot allocate a negative amount."; }
		if ($allocate>$itotals) { $error .= "Total allocated for Order ".$p." exceeds invoice amount.<br />"; }
	}
	if ($totals>$total_due) { $error .= "Total allocated exceeds payment amount.<br />"; }
		
	}
	if($error) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Supplier","Oops!","$error","Link","oos/manage-supplier-po.php?a=partpay&id=".$_POST["client_id"]."&pid=".$_POST["payment_id"]."&f=add");
		$get_template->bottomHTML();	
		$sql_command->close();
	}


	$transid = (!$_POST['transid']) ? 0: $_POST['transid'];
	$cols = "supplier_id,client_id,p_amount,currency,payment_exchange,transaction_id,ip_add,status,cardtype,timestamp";
	$pstatus = $_POST['pstatus'];
	$vals = "'".addslashes($_POST['client_id'])."',
	'".addslashes($_POST['customer_id'])."',
	'".addslashes($total_due)."',
	'".addslashes($_POST['currency'])."',
	'".addslashes($_POST['exchange_rate'])."',
	'".addslashes($transid)."',
	'".$the_username."',
	'".$pstatus."',
	'".$_POST['paymentT']."',
	'".$dateUp."'";
	
	$sql_command->insert("supplier_transactions",$cols,$vals);
	$maxid = $sql_command->maxid(supplier_transactions,"p_id","");
	
	if ($payinv <> "") {
	
		foreach ($payinv as $p) {
			$dateline = $_POST[$p.'-date'];
			$itype = $_POST[$p.'-type'];
			$totalin = str_replace(",","",number_format($_POST[$p.'-total'],2));
			$iorderid = $_POST[$p.'-orderid'];
			$allocate = $_POST[$p.'-alloc'];
			$invid = $_POST[$p.'-invid'];
			$mainid = $_POST[$p.'-mainid'];
			
			$filterv = array("£",",","€"," ");
			$allocate = str_replace($filterv,"",$allocate);
			
			$cols = "pay_no,main_id,invoice_id,order_id,p_amount,status";
			$allocated = ($allocate==0) ? 'Unpaid': ($itotals<$allocate) ? 'Part Paid' : 'Paid';
			$vals = "'".addslashes($maxid)."',
			'".$mainid."',
			'".addslashes($invid)."',
			'".addslashes($p)."',
			'".addslashes($allocate)."',
			'".$allocated."'";
	
			if($allocate>0) { 
				$success = $sql_command->insert("supplier_payments",$cols,$vals);
			
				$payval = $sql_command->select("supplier_payments,supplier_transactions",
											   "sum(supplier_payments.p_amount)",
											   "WHERE supplier_transactions.p_id = supplier_payments.pay_no
											   AND supplier_payments.main_id='".addslashes($mainid)."'
											   AND supplier_transactions.status = 'Paid'");	
				$payvalr = $sql_command->result($payval);			
		
				$result = $sql_command->select("$database_supplier_invoices_main",
											 "status",
											 "WHERE id='".addslashes($mainid)."'");
				$row = $sql_command->result($result);
	
				if ($payvalr[0]>=$totalin && $row[0]!='Paid' && $pstatus=='Paid') {	
				
					$sql_command->update($database_supplier_invoices_main,
										 "status='".addslashes($pstatus)."'",
										 "id='".addslashes($mainid)."'");				
						$sql_command->update("$database_supplier_invoices",
										 "status='".addslashes($pstatus)."'",
										 "order_id='".addslashes($p)."'
										 AND invoice_id='".addslashes($invid)."'
										 AND supplier_id='".addslashes($_POST['client_id'])."'");								
				
					$sql_command->update("supplier_payments,supplier_transactions",
										 "supplier_payments.status='Cancelled'",
										 "supplier_transactions.p_id = supplier_payments.pay_no
										 AND supplier_transactions.status != 'Paid'
										 AND supplier_payments.status != 'Unpaid'
										 AND supplier_payments.p_amount > 0	
										 AND supplier_payments.main_id = '".addslashes($mainid)."'");	
						
					$sql_command->update("supplier_payments,supplier_transactions",	
										 "supplier_payments.p_amount=0",	
										 "supplier_transactions.p_id = supplier_payments.pay_no	
										 AND supplier_transactions.status != 'Paid'	
										 AND supplier_payments.status != 'Unpaid'	
										 AND supplier_payments.p_amount > 0	
										 AND supplier_payments.main_id='".addslashes($mainid)."'");
				}	
			}
		}
	}

	header("Location: $site_url/oos/manage-supplier-po.php?action=Continue&id=".$_POST["client_id"]);
	$sql_command->close();
	
} else {

	$payinv = $_POST['payinv'];
	$result2 = $sql_command->select("supplier_transactions","p_id,p_amount,transaction_id,ip_add,status,cardtype,timestamp",
								"WHERE p_id='".addslashes($_POST['payment_id'])."'");
	$record2 = $sql_command->result($result2);
	
	$total_due = $record2[1]; // assign p_amount
	$totals=0; // default totals
	$totalsall = 0;
	
//	echo count($payinv);

	foreach ($payinv as $p) {
		$allocate = $_POST[$p.'-alloc'];
		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		$current = $_POST[$p.'-current'];
		$itotals = $_POST[$p.'-total'];
		$difference = ($current===$allocate) ? 0 : $current - $allocate;
		if ($allocate<0) { $error .= "Cannot allocate a negative amount."; }
		if (($allocate-$current)>$itotals) { $error .= "Total allocated for Order ".$p." exceeds invoice amount."; }
		$totalsall = $totalsall + ($allocate-difference);
	}
	
	if ($totalsall>$total_due) { $error .= "Total allocated would exceed payment amount."; }
	
	
	if($error) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Supplier","Oops!","$error","Link","oos/manage-supplier-po.php?a=partpay&id=".$_POST["client_id"]."&pid=".$_POST['payment_id']."&f=mod");
		$get_template->bottomHTML();	
		$sql_command->close();
	}
	
	$pid = $_POST['payment_id'];
	$pstatus = $_POST['pstatus'];
	$sql_command->update("supplier_transactions","status='".$pstatus."',timestamp='".$dateUp."'","p_id='".addslashes($pid)."'");


	$cols = "pay_no,main_id,invoice_id,order_id,p_amount,status";
	
	$payinv = $_POST['payinv'];
	
	foreach ($payinv as $p) {
		$dateline = $_POST[$p.'-date'];
		$itype = $_POST[$p.'-type'];
		$totalin = str_replace(",","",number_format($_POST[$p.'-total'],2));
		$iorderid = $_POST[$p.'-orderid'];
		$allocate = $_POST[$p.'-alloc'];	
		$invid = $_POST[$p.'-invid'];	
		$current = $_POST[$p.'-current'];
		$pid = $_POST[$p.'-pid'];
		$mainid = $_POST[$p.'-mainid'];
		
		$filterv = array("£",",","€"," ");
		$allocate = str_replace($filterv,"",$allocate);
		
		
		$allocated = ($allocate<=0) ? 'Unpaid': ($itotals<$allocate) ? 'Part Paid' : 'Paid';
		
		$vals = "'".$_POST['payment_id']."',
		'".$mainid."',
		'".addslashes($invid)."',
		'".addslashes($p)."',
		'".addslashes($allocate)."',
		'".$allocated."'";
		
		$difference = ($current===$allocate) ? 0 : $allocate - $current;
		if ($allocate>0 || $current>0) {
			if (!empty($pid)) {
				$success = $sql_command->update("supplier_payments","p_amount='".addslashes($allocate)."', status='".$allocated."'","p_id='".addslashes($pid)."'");
			}
		
			else {
				$success = $sql_command->insert("supplier_payments",$cols,$vals);
			}
			
			$payval = $sql_command->select("supplier_payments,supplier_transactions",
										   "sum(supplier_payments.p_amount)",
										   "WHERE supplier_transactions.p_id = supplier_payments.pay_no
										   AND supplier_payments.main_id='".addslashes($mainid)."'
										   AND supplier_transactions.status = 'Paid'");	
			$payvalr = $sql_command->result($payval);			
			
	//	$debug="&de=update".$database_supplier_invoices_main."setstatus='".addslashes($pstatus)."'whereid='".addslashes($mainid)."'";
	
			$result = $sql_command->select("$database_supplier_invoices_main",
										   "status",
										   "WHERE id='".addslashes($mainid)."'");
			$row = $sql_command->result($result);
	$payvalr[0] = str_replace(",","",number_format($payvalr[0],2));
	//$debug = "&mid=".$mainid."&oid=".$p."&iid=".$invid."&sid=".$_POST['client_id']."&pay=".$payvalr[0]."&total=".$totalin."&status=".$row[0];
			if ($row[0]!='Paid' && $pstatus=='Paid' && $payvalr[0]>=$totalin) {	
	
				$sql_command->update("$database_supplier_invoices_main",
									 "status='".addslashes($pstatus)."'",
									 "id='".addslashes($mainid)."'");								
	
				$sql_command->update("$database_supplier_invoices",
									 "status='".addslashes($pstatus)."'",
									 "order_id='".addslashes($p)."'
									 AND invoice_id='".addslashes($invid)."'
									 AND supplier_id='".addslashes($_POST['client_id'])."'");								
	
	
				$sql_command->update("supplier_payments,supplier_transactions",
									 "supplier_payments.status='Cancelled'",
									 "supplier_transactions.p_id = supplier_payments.pay_no
									 AND supplier_transactions.status != 'Paid'
									 AND supplier_payments.status != 'Unpaid'
									 AND supplier_payments.p_amount > 0	
									 AND supplier_payments.main_id = '".addslashes($mainid)."'");
			
				$sql_command->update("supplier_payments,supplier_transactions",
									 "supplier_payments.p_amount=0",
									 "supplier_transactions.p_id = supplier_payments.pay_no
									 AND supplier_transactions.status != 'Paid'
									 AND supplier_payments.status != 'Unpaid'
									 AND supplier_payments.p_amount > 0
									 AND supplier_payments.main_id = '".addslashes($mainid)."'");
				$debug = "&mid=".$mainid."&oid=".$p."&iid=".$invid."&sid=".$_POST['client_id']."&pay=".$payvalr[0]."&total=".$totalin;
			}
			else {
				if ($row[0]=="Paid"&& $payvalr[0]<$totalin) {	
					
					$pstatus = "Outstanding";
					$sql_command->update("$database_supplier_invoices_main",
										 "status='Outstanding'",
										 "id='".addslashes($mainid)."'");
					$debug = "&mid=".$mainid."&oid=".$p."&iid=".$invid."&sid=".$_POST['client_id']."&pay=".$payvalr[0]."&total=".$totalin;
					$sql_command->update("$database_supplier_invoices",
										 "status='Outstanding'",
										 "order_id='".addslashes($p)."'
										 AND invoice_id='".addslashes($invid)."'
										 AND supplier_id='".addslashes($_POST['client_id'])."'");								
						
				}	
			}
		}
	}
	header("Location: $site_url/oos/manage-supplier-po.php?action=Continue&id=".$_POST["client_id"].$debug);
	$sql_command->close();
	
}

?>