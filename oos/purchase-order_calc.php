<?

$qties = $itemvalue = $payment_total2 = $payment_total = $paidam = 0;


	
$result = $sql_command->select("$database_supplier_invoices_main,
							   $database_supplier_details",
							   "$database_supplier_details.id,
							   $database_supplier_details.contact_title,
							   $database_supplier_details.contact_firstname,
							   $database_supplier_details.contact_surname,
							   $database_supplier_details.contact_email,
							   $database_supplier_details.contact_tel,
							   $database_supplier_details.company_name,
							   $database_supplier_details.address_1,
							   $database_supplier_details.address_2,
							   $database_supplier_details.address_3,
							   $database_supplier_details.address_town,
							   $database_supplier_details.address_county,
							   $database_supplier_details.address_country,
							   $database_supplier_details.address_postcode,
							   $database_supplier_details.timestamp,
							   $database_supplier_details.code,
							   $database_supplier_invoices_main.exchange_rate,
							   $database_supplier_invoices_main.order_id,
							   $database_supplier_invoices_main.status,
							   $database_supplier_invoices_main.timestamp",
							   "WHERE $database_supplier_details.id=$database_supplier_invoices_main.supplier_id 
							   AND $database_supplier_invoices_main.id='".addslashes($purchase_order)."'");
$record_calc = $sql_command->result($result);
/*
$checkinc = $sql_command->select("order_history",
								 "id,
								 name,
								 sum(qty),
								 cost,
								 currency,
								 code,
								 exchange_rate",
								 "WHERE order_history.order_id = '".addslashes($record_calc[17])."' 
								 AND NOT EXISTS(SELECT * FROM supplier_invoice_details WHERE supplier_invoice_details.order_id='".addslashes($record_calc[17])."' AND supplier_invoice_details.code = order_history.code) 
								 AND EXISTS(SELECT * FROM package_extras WHERE package_extras.supplier_id = '".addslashes($record_calc[0])."' AND package_extras.code = order_history.code)");
	
$checkin = $sql_command->results($checkinc);
foreach ($checkin as $c) {	
	$qties = $c[2];
	
	if ($qties > 0 || $qties < 0) {
	
		$itemvalue = $c[3];
		$exrate=$c[6];
		if($c[4] != "Euro") { 
			$p_curreny = "£"; 
			$payment_total2 = $qties * ($itemvalue/$exrate);		
		} else {
			$p_curreny = "€";
			$payment_total2 = $qties * $itemvalue;
		}
		$payment_total += $payment_total2;

	}	
}
*/

$result2 = $sql_command->select($database_supplier_invoices_details,
								"id,
								name,
								qty,
								cost,
								currency,
								code,
								invoice_id",
								"WHERE main_id='".addslashes($purchase_order)."'");
$row2 = $sql_command->results($result2);
$ic = 0;
$codecheck = array();
foreach($row2 as $record2) {
	
	$pochecks = $sql_command->select($database_order_history,
									"sum(qty),
									exchange_rate",
									"WHERE order_id='".addslashes($record_calc[17])."'
									AND invoice_id = '".addslashes($record2[6])."' 
									AND code = '".addslashes($record2[5])."'");
	$pocheck = $sql_command->result($pochecks);

	$qties = $pocheck[0];
	$code = $record2[5];
	if (abs($qties) > 0 ) { $ic++; }
	if (abs($qties) > 0 && !in_array($code,$codecheck)) {
		$codecheck[] = $code;
		$itemvalue = $record2[3];
		$exrate=$pocheck[1];
		if($record2[4] != "Euro") { 
			$p_curreny = "£"; 
			//$payment_total2 = $qties * ($itemvalue/$exrate);
			$payment_total2 = $qties * $itemvalue;		
		} else {
			$p_curreny = "€";
			$payment_total2 = $qties * $itemvalue;
		}
		$payment_total += $payment_total2;

	}
}
$resp = $sql_command->select("supplier_payments,supplier_transactions",
					 "sum(supplier_payments.p_amount)",
					 "WHERE supplier_transactions.p_id = supplier_payments.pay_no 
					 AND supplier_transactions.status = 'Paid'
					 AND supplier_payments.status != 'Unpaid'
					 AND supplier_payments.main_id = '".addslashes($purchase_order)."'");
$respr = $sql_command->result($resp);

$paidam = $respr[0];

$totals = str_replace(",","",number_format(($payment_total+$getvat),2));
$po_amount = $payment_total+$getvat;
$payment_total = ($payment_total+$getvat) - $paidam;
?>