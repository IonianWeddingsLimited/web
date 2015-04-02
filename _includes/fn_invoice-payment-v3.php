<?
$getvat = "20.00";
$adjustment = $adjustment_iw = $amount_discount = $dresp = $drespr = $euro_amount_discount = $euro_discount = $format_gbp = $line_iw_euro = $minum_deposit = $outstanding_euros = $outstanding_euros_before = $outstanding_pounds = $outstanding_pounds_before = $package_exists = $percent_value = $resp = $respr = $show_additional = $the_cost = $the_cost_before = $total_additional = $total_deposit_paid = $total_gbp = $total_invoice = $total_iw_cost = $total_iw_cost_before = $total_payment = $total_payment_euro = $total_payment_euro_before = $total_payment_pound = $total_payment_pound_before = $totalpp = "";


$currencydef ="";




if (empty($currency_name)) {
	$result = $sql_command->select("$database_customer_invoices,
								   $database_order_details,
								   $database_clients",
								   "$database_customer_invoices.order_id,
								   $database_customer_invoices.exchange_rate",
								   "WHERE $database_customer_invoices.id='" . $invoiceno . "' 
								   AND $database_customer_invoices.order_id=$database_order_details.id 
								   AND $database_order_details.client_id=$database_clients.id");
	$invrecord = $sql_command->result($result);
	
	//echo "test 1 ".$invoiceno"<br />";

	$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_",
											 "WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Euro' and item_type='Package'");
	$invoice_row = $sql_command->results($invoice_result);
		
	$package_exists = "No";
	$total_invoice=0;
	foreach($invoice_row as $invoice_record) {
		$package_exists = "Yes";
		$total_invoice++;
		$adjustment_iw = $invoice_record[2];
		$adjustment = $invoice_record[1];
		// Work out adjustments
		if($invoice_record[0] > 0) {
			
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
				} 
			}	
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
				}
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		elseif($invoice_record[0] < 0) {	
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
		if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			$adjustment_iw = $invoice_record[1];
			$invoice_record[2] = $invoice_record[1];
		}
		$the_cost = $invoice_record[0] * $adjustment;
		$total_iw_cost = $invoice_record[0] * $adjustment_iw;
		$the_cost_before = $invoice_record[0] * $invoice_record[1];
		$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
			
		$total_payment_euro += $total_iw_cost;
		$total_payment_euro_before += $total_iw_cost_before;
		if($total_iw_cost_before > $total_iw_cost) { 
			$line_iw_euro = $total_iw_cost_before; 
		}
		else {
			$line_iw_euro = $total_iw_cost; 	
		}
	}
	
	//echo "test 2 ".$invrecord[1]."<br />";
	
	$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_","WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Pound' and item_type='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	$show_additional = "No";
	$total_payment = 0;
	foreach($invoice_row as $invoice_record) {
		$package_exists = "Yes";
		$show_additional = "Yes";
		$adjustment_iw = $invoice_record[2];
		$adjustment = $invoice_record[1];
		// Work out adjustments
		if($invoice_record[0] > 0) {
		
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
				}
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		elseif($invoice_record[0] < 0) {	
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4]; 
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
		if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			$adjustment_iw = $invoice_record[1];
			$invoice_record[2] = $invoice_record[1];
		}
		$the_cost = $invoice_record[0] * $adjustment;
		$total_iw_cost = $invoice_record[0] * $adjustment_iw;
		$the_cost_before = $invoice_record[0] * $invoice_record[1];
		$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
		$total_payment_pound += $total_iw_cost;
		$total_payment_pound_before += $total_iw_cost_before;
		if($total_iw_cost_before > $total_iw_cost) { 
			$euro_amount_discount += $total_iw_cost_before - $total_iw_cost;
			$line_iw_euro = $total_iw_cost_before; 
		} 
		else {
			$line_iw_euro = $total_iw_cost; 	
		}
	}

	//echo "test 3 ".$invrecord[1]."<br />";

	$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_,
											 name",
											 "WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Euro' 
											 and item_type!='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	foreach($invoice_row as $invoice_record) {
		$total_invoice++;
		$adjustment_iw = $invoice_record[2];
		$adjustment = $invoice_record[1];
		// Work out adjustments
		if($invoice_record[0] > 0) {
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
				}
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		elseif($invoice_record[0] < 0) {
				
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}	
		}
		//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
		if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			$adjustment_iw = $invoice_record[1];
			$invoice_record[2] = $invoice_record[1];
		}
		$the_cost = $invoice_record[0] * $adjustment;
		$total_iw_cost = $invoice_record[0] * $adjustment_iw;
		$the_cost_before = $invoice_record[0] * $invoice_record[1];
		$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
					
		$total_payment_euro += $total_iw_cost;
		$total_payment_euro_before += $total_iw_cost_before;
		if($total_iw_cost_before > $total_iw_cost) { 
			$line_iw_euro = $total_iw_cost_before; 
		} 
		else {
			$line_iw_euro = $total_iw_cost; 	
		}
//		if ($line_iw_euro) {
//			echo $invoice_record[7]." : ".round($line_iw_euro,2)."<br />";
//		}
	}
	if($total_invoice > 0) {
		$outstanding_euros = $total_payment_euro;
		$outstanding_euros_before = $total_payment_euro_before;
		$euro_discount = $outstanding_euros_before - $outstanding_euros;
		$minum_deposit = 0;
		$minum_deposit2 = 0;
		if($package_exists == "Yes") {
			$invoice_result = $sql_command->select($database_invoice_history,"qty,
									 cost,
									 iw_cost,
									 currency,
									 timestamp,
									 exchange_rate",
									 "WHERE order_id='".addslashes($invrecord[0])."' 
									 and item_type='Deposit' and status='Paid' 
									 and currency='Euro'");
			$invoice_row = $sql_command->results($invoice_result);
			foreach($invoice_row as $invoice_record) {
				$total_deposit_paid = str_replace(",","",number_format($invoice_record[2] / $invoice_record[5],2));
				$minum_deposit = $minum_deposit + $total_deposit_paid;
				$minum_deposit2 = $invoice_record[2];
				$test_deposit=$minum_deposit;
			}
		}
	}
	$totalpp  = 0;
	if ($minum_deposit>0) {
		$invoice_result = $sql_command->select($database_invoice_history,
												   "invoice_id",
												   "WHERE order_id='".addslashes($recorda[0])."' 
												   and item_type='Deposit' and status='Paid'");
		$invoice_record = $sql_command->result($invoice_result);
			
		$dresp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.payment_currency = 'Euro'
									 AND customer_payments.invoice_id = '".addslashes($invoiceno)."' 
									 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
		$drespr = $sql_command->result($dresp);
		//		$payDates = $drespr[1];
		$totalpp = $drespr[0];
		//		$format_gbp = "£ ".number_format($totalpp,2);
	}
	else {
		$resp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount)",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Euro'
								 AND customer_payments.invoice_id = '".addslashes($invoiceno)."'");
		$respr = $sql_command->result($resp);
		
		$totalpp = $respr[0];
		
		// end payments
	}
	
	
//	if ($invrecord[1] == "") {
//		$ExchangeRate	=	1;
//	} elseif ($invrecord[1] == 0) {
//		$ExchangeRate	=	1;
//	} else {
//		$ExchangeRate	=	$invrecord[1];
//	}
	
	//echo "test 4 ".$invrecord[1]."<br />";
	
	if($invrecord[1]) {
		
		$outstanding_pounds = (empty($currency_name)) ? $outstanding_euros / $invrecord[1] : $outstanding_euros;
	
		$outstanding_pounds_before = (empty($currency_name)) ? $outstanding_euros_before / $invrecord[1] : $outstanding_euros;
	}

	$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_","WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Pound' 
											 and item_type!='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	foreach($invoice_row as $invoice_record) {
		$show_additional = "Yes";
	}
	$show_additional = "No";
	$total_payment = 0;
	foreach($invoice_row as $invoice_record) {
		$show_additional = "Yes";
		$adjustment_iw = $invoice_record[2];
		$adjustment = $invoice_record[1];
		// Work out adjustments
		if($invoice_record[0] > 0) {
			
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
				}
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		elseif($invoice_record[0] < 0) {
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] - $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[1] / 100);
					$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Absolute Gross") { 
				$adjustment_iw = $invoice_record[4];
				$invoice_record[2] = $invoice_record[4];
			}
			elseif($invoice_record[6] == "Absolute Net") { 
				$adjustment = $invoice_record[4];
				$invoice_record[1] = $invoice_record[4];
			}
		}
		//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
		if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			$adjustment_iw = $invoice_record[1];
			$invoice_record[2] = $invoice_record[1];
		}
		$the_cost = $invoice_record[0] * $adjustment;
		$total_iw_cost = $invoice_record[0] * $adjustment_iw;
		$the_cost_before = $invoice_record[0] * $invoice_record[1];
		$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
		$total_payment_pound += $total_iw_cost;
		$total_payment_pound_before += $total_iw_cost_before;
		if($total_iw_cost_before > $total_iw_cost) { 
			$amount_discount += $total_iw_cost_before - $total_iw_cost;
			$line_iw_euro = $total_iw_cost_before; 
		} else {
			$line_iw_euro = $total_iw_cost; 	
		}
		$total_additional += $line_iw_euro;
	}
	if($package_exists == "Yes") {
		$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 currency,
											 timestamp,
											 exchange_rate",
											 "WHERE order_id='".addslashes($invrecord[0])."' 
											 and item_type='Deposit' 
											 and status='Paid' 
											 and currency='Pound'");
		$invoice_row = $sql_command->results($invoice_result);
		foreach($invoice_row as $invoice_record) {
			$total_deposit_paid = eregi_replace(",","",$invoice_record[2]);
			$minum_deposit = $minum_deposit + $total_deposit_paid;
		}
	}
	// start payments
	if ($minum_deposit>0) {
	
		$invoice_result = $sql_command->select($database_invoice_history,"invoice_id","WHERE order_id='".addslashes($recorda[0])."' and item_type='Deposit' and status='Paid'");
		$invoice_record = $sql_command->result($invoice_result);
		$dresp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount)",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.payment_currency = 'Pound'
							 AND customer_payments.invoice_id = '".addslashes($invoiceno)."' 
							 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
		$drespr = $sql_command->result($dresp);
	
		$totalpp = $drespr[0];
	//	$format_gbp = "£ ".number_format($totalpp,2);
	}
	else {
		$resp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount)",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.payment_currency = 'Pound'
							 AND customer_payments.invoice_id = '".addslashes($invoiceno)."'");
		$respr = $sql_command->result($resp);
		$totalpp = $respr[0];
		//	$format_gbp = "£ ".number_format($totalpp,2);
	}
	// end payments
	
	
	$total_gbp = round(($outstanding_pounds + $total_payment_pound - $minum_deposit - $totalpp),2);
	
//	echo round($outstanding_pounds,2)."<br /><br />";
//	echo round($total_payment_pound,2)."<br /><br />";
//	echo round($minum_deposit,2)."<br /><br />";
//	echo round($totalpp,2)."<br /><br />";
	
	//$totalpp = 0;
	//$currency_i = ($currencydef=="Not Applicable") ?  "pound": (!$currencydef) ? "pound" : $currencydef;
	//$c_symbol = ($currency_i=="pound") ?  "£":  $c_symbol;
	
	// testing
	// $currency_i = "euro"; 
	// $c_symbol = "€";
	
	//$total_gbp = ($currency_i=="pound") ?  $total_gbp :  $total_gbp * $invrecord[1];
}






// non uk

else {
		$result = $sql_command->select("$database_customer_invoices,
									   $database_order_details,
									   $database_clients",
									   "$database_customer_invoices.order_id,
									   $database_customer_invoices.exchange_rate",
									   "WHERE $database_customer_invoices.id='" . $invoiceno . "' 
									   AND $database_customer_invoices.order_id=$database_order_details.id 
									   AND $database_order_details.client_id=$database_clients.id");
		$invrecord = $sql_command->result($result);
	if ($currency_name=="Euro") {
	
		$invoice_result = $sql_command->select($database_invoice_history,"qty,
												 cost,
												 iw_cost,
												 discount_percent,
												 d_value,
												 d_type,
												 d_",
												 "WHERE invoice_id='".addslashes($invoiceno)."' 
												 and order_id='".addslashes($invrecord[0])."' 
												 and currency='Euro' and item_type='Package'");
		$invoice_row = $sql_command->results($invoice_result);
			
		$package_exists = "No";
		$total_invoice=0;
		foreach($invoice_row as $invoice_record) {
			$package_exists = "Yes";
			$total_invoice++;
			$adjustment_iw = $invoice_record[2];
			$adjustment = $invoice_record[1];
			// Work out adjustments
			if($invoice_record[0] > 0) {
				
				if($invoice_record[6] == "Gross") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment_iw = $invoice_record[2] + $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[2] / 100);
						$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Net") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment = $invoice_record[1] + $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[1] / 100);
						$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
					}
				}
				elseif($invoice_record[6] == "Absolute Gross") { 
					$adjustment_iw = $invoice_record[4];
					$invoice_record[2] = $invoice_record[4];
				}
				elseif($invoice_record[6] == "Absolute Net") { 
					$adjustment = $invoice_record[4];
					$invoice_record[1] = $invoice_record[4];
				}
			}
			elseif($invoice_record[0] < 0) {
			
				if($invoice_record[6] == "Gross") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment_iw = $invoice_record[2] - $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[2] / 100);
						$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Net") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment = $invoice_record[1] - $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[1] / 100);
						$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Absolute Gross") { 
					$adjustment_iw = $invoice_record[4];
					$invoice_record[2] = $invoice_record[4];
				}
				elseif($invoice_record[6] == "Absolute Net") { 
					$adjustment = $invoice_record[4];
					$invoice_record[1] = $invoice_record[4];
				}
			}
			//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
				$adjustment_iw = $invoice_record[1];
				$invoice_record[2] = $invoice_record[1];
			}
			$the_cost = $invoice_record[0] * $adjustment;
			$total_iw_cost = $invoice_record[0] * $adjustment_iw;
			$the_cost_before = $invoice_record[0] * $invoice_record[1];
			$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
				
			$total_payment_euro += $total_iw_cost;
			$total_payment_euro_before += $total_iw_cost_before;
			if($total_iw_cost_before > $total_iw_cost) { 
				$line_iw_euro = $total_iw_cost_before; 
			} else {
				$line_iw_euro = $total_iw_cost; 	
			}
		}
	}

	if ($currency_name=="Pound") {
		$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_","WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Pound' and item_type='Package'");
		$invoice_row = $sql_command->results($invoice_result);
			$poundone = "select qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_ from $database_invoice_history WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Pound' and item_type='Package'";
		$show_additional = "No";
		$total_payment = 0;
		foreach($invoice_row as $invoice_record) {
			$package_exists = "Yes";
			$show_additional = "Yes";
			$adjustment_iw = $invoice_record[2];
			$adjustment = $invoice_record[1];
			// Work out adjustments
			if($invoice_record[0] > 0) {
			
				if($invoice_record[6] == "Gross") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment_iw = $invoice_record[2] + $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[2] / 100);
						$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Net") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment = $invoice_record[1] + $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[1] / 100);
						$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
					}
				}
				elseif($invoice_record[6] == "Absolute Gross") { 
					$adjustment_iw = $invoice_record[4];
					$invoice_record[2] = $invoice_record[4];
				}
				elseif($invoice_record[6] == "Absolute Net") { 
					$adjustment = $invoice_record[4];
					$invoice_record[1] = $invoice_record[4];
				}
			}
			elseif($invoice_record[0] < 0) {	
				if($invoice_record[6] == "Gross") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment_iw = $invoice_record[2] - $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[2] / 100);
						$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Net") { 
					if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
						$adjustment = $invoice_record[1] - $invoice_record[4];
					}
					elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
						$percent_value = ($invoice_record[1] / 100);
						$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
					} 
				}
				elseif($invoice_record[6] == "Absolute Gross") { 
					$adjustment_iw = $invoice_record[4];
					$invoice_record[2] = $invoice_record[4]; 
				}
				elseif($invoice_record[6] == "Absolute Net") { 
					$adjustment = $invoice_record[4];
					$invoice_record[1] = $invoice_record[4];
				}
			}
			//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
			if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") {
				$adjustment_iw = $invoice_record[1];
				$invoice_record[2] = $invoice_record[1];
			}
			$the_cost = $invoice_record[0] * $adjustment;
			$total_iw_cost = $invoice_record[0] * $adjustment_iw;
			$the_cost_before = $invoice_record[0] * $invoice_record[1];
			$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
			$total_payment_pound += $total_iw_cost;
			$total_payment_pound_before += $total_iw_cost_before;
			if($total_iw_cost_before > $total_iw_cost) { 
				$euro_amount_discount += $total_iw_cost_before - $total_iw_cost;
				$line_iw_euro = $total_iw_cost_before; 
			} else {
				$line_iw_euro = $total_iw_cost; 	
			}
		}
	}
	
	if ($currency_name=="Euro") {
		$invoice_result = $sql_command->select($database_invoice_history,"qty,
												 cost,
												 iw_cost,
												 discount_percent,
													 d_value,
											 d_type,
											 d_",
											 "WHERE invoice_id='".addslashes($invoiceno)."' 
											 and order_id='".addslashes($invrecord[0])."' 
											 and currency='Euro' 
											 and item_type!='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	foreach($invoice_row as $invoice_record) {
		$total_invoice++;
		$adjustment_iw = $invoice_record[2];
		$adjustment = $invoice_record[1];
		// Work out adjustments
		if($invoice_record[0] > 0) {
			if($invoice_record[6] == "Gross") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment_iw = $invoice_record[2] + $invoice_record[4];
				}
				elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
					$percent_value = ($invoice_record[2] / 100);
					$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
				} 
			}
			elseif($invoice_record[6] == "Net") { 
				if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
					$adjustment = $invoice_record[1] + $invoice_record[4];
				}
			elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
				$percent_value = ($invoice_record[1] / 100);
				$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
			}
		}
		elseif($invoice_record[6] == "Absolute Gross") { 
			$adjustment_iw = $invoice_record[4];
			$invoice_record[2] = $invoice_record[4];
		}
		elseif($invoice_record[6] == "Absolute Net") { 
			$adjustment = $invoice_record[4];
			$invoice_record[1] = $invoice_record[4];
		}
	}
	elseif($invoice_record[0] < 0) {
			
		if($invoice_record[6] == "Gross") { 
			if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
				$adjustment_iw = $invoice_record[2] - $invoice_record[4];
			}
			elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
				$percent_value = ($invoice_record[2] / 100);
				$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
			} 
		}
		elseif($invoice_record[6] == "Net") { 
			if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
				$adjustment = $invoice_record[1] - $invoice_record[4];
			}
			elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
				$percent_value = ($invoice_record[1] / 100);
				$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
			} 
		}
		elseif($invoice_record[6] == "Absolute Gross") { 
			$adjustment_iw = $invoice_record[4];
			$invoice_record[2] = $invoice_record[4];
		}
		elseif($invoice_record[6] == "Absolute Net") { 
			$adjustment = $invoice_record[4];
			$invoice_record[1] = $invoice_record[4];
		}	
	}
	//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
	if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") { 
		$adjustment_iw = $invoice_record[1];
		$invoice_record[2] = $invoice_record[1];
	}
	$the_cost = $invoice_record[0] * $adjustment;
	$total_iw_cost = $invoice_record[0] * $adjustment_iw;
	$the_cost_before = $invoice_record[0] * $invoice_record[1];
	$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
				
	$total_payment_euro += $total_iw_cost;
	$total_payment_euro_before += $total_iw_cost_before;
	if($total_iw_cost_before > $total_iw_cost) { 
		$line_iw_euro = $total_iw_cost_before; 
	} 
	else {
		$line_iw_euro = $total_iw_cost; 	
	}
		}
		if($total_invoice > 0) {
			$outstanding_euros = $total_payment_euro;
			$outstanding_euros_before = $total_payment_euro_before;
			$euro_discount = $outstanding_euros_before - $outstanding_euros;
			$minum_deposit = 0;
			$minum_deposit2 = 0;
			if($package_exists == "Yes") {
				$invoice_result = $sql_command->select($database_invoice_history,"qty,
										 cost,
										 iw_cost,
										 currency,
										 timestamp,
										 exchange_rate",
										 "WHERE order_id='".addslashes($invrecord[0])."' 
										 and item_type='Deposit' and status='Paid' 
										 and currency='Euro'");
				$invoice_row = $sql_command->results($invoice_result);
				foreach($invoice_row as $invoice_record) {
					$total_deposit_paid = eregi_replace(",","",number_format($invoice_record[2] / $invoice_record[5],2));
					$minum_deposit = $minum_deposit + $total_deposit_paid;
					$minum_deposit2 = $invoice_record[2];
				}
			}
		}
		$totalpp  = 0;
		if ($minum_deposit>0) {
				$invoice_result = $sql_command->select($database_invoice_history,
													   "invoice_id",
													   "WHERE order_id='".addslashes($recorda[0])."' 
													   and item_type='Deposit' and status='Paid'");
				$invoice_record = $sql_command->result($invoice_result);
				
				$dresp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.payment_currency = 'Euro'
									 AND customer_payments.invoice_id = '".addslashes($invoiceno)."' 
									 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
				$drespr = $sql_command->result($dresp);
		//		$payDates = $drespr[1];
				$totalpp = $drespr[0];
		//		$format_gbp = "£ ".number_format($totalpp,2);
		}
		else {
			$resp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount)",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Euro'
								 AND customer_payments.invoice_id = '".addslashes($invoiceno)."'");
			$respr = $sql_command->result($resp);
		
			$totalpp = $respr[0];
		
		// end payments
		}
		$outstanding_euros -= $totalpp;
		$totalpp=0;
	}
	
	$outstanding_pounds = (empty($currency_name)) ? $outstanding_euros / $invrecord[1] : $outstanding_euros;
	
	$outstanding_pounds_before = (empty($currency_name)) ? $outstanding_euros_before / $invrecord[1] : $outstanding_euros;
	
	if ($currency_name=="Pound") {

		$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_","WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($invrecord[0])."' and currency='Pound' and item_type!='Package'");
			$invoice_row = $sql_command->results($invoice_result);
			$poundtwo = "select qty,
											 cost,
											 iw_cost,
											 discount_percent,
											 d_value,
											 d_type,
											 d_ FROM $database_invoice_history WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($invrecord[0])."' and currency='Pound' and item_type!='Package'";
			foreach($invoice_row as $invoice_record) {
				$show_additional = "Yes";
			}
			$show_additional = "No";
			$total_payment = 0;
			foreach($invoice_row as $invoice_record) {
				$show_additional = "Yes";
				$adjustment_iw = $invoice_record[2];
				$adjustment = $invoice_record[1];
				// Work out adjustments
				if($invoice_record[0] > 0) {
					
					if($invoice_record[6] == "Gross") { 
						if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
							$adjustment_iw = $invoice_record[2] + $invoice_record[4];
						}
								elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
							$percent_value = ($invoice_record[2] / 100);
							$adjustment_iw = $invoice_record[2] + ($percent_value * $invoice_record[4]);
						} 
					}
							elseif($invoice_record[6] == "Net") { 
						if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
							$adjustment = $invoice_record[1] + $invoice_record[4];
						}
								elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
							$percent_value = ($invoice_record[1] / 100);
							$adjustment = $invoice_record[1] + ($percent_value * $invoice_record[4]);
						}
					}
							elseif($invoice_record[6] == "Absolute Gross") { 
						$adjustment_iw = $invoice_record[4];
						$invoice_record[2] = $invoice_record[4];
					}
							elseif($invoice_record[6] == "Absolute Net") { 
						$adjustment = $invoice_record[4];
						$invoice_record[1] = $invoice_record[4];
					}
				}
						elseif($invoice_record[0] < 0) {
					if($invoice_record[6] == "Gross") { 
						if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
							$adjustment_iw = $invoice_record[2] - $invoice_record[4];
						}
								elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
							$percent_value = ($invoice_record[2] / 100);
							$adjustment_iw = $invoice_record[2] - ($percent_value * $invoice_record[4]);
						} 
					}
							elseif($invoice_record[6] == "Net") { 
						if($invoice_record[5] == "Amount" and $invoice_record[4] != 0) {
							$adjustment = $invoice_record[1] - $invoice_record[4];
						}
								elseif($invoice_record[5] == "Percent" and $invoice_record[4] != 0) {
							$percent_value = ($invoice_record[1] / 100);
							$adjustment = $invoice_record[1] - ($percent_value * $invoice_record[4]);
						} 
					}
							elseif($invoice_record[6] == "Absolute Gross") { 
						$adjustment_iw = $invoice_record[4];
						$invoice_record[2] = $invoice_record[4];
					}
							elseif($invoice_record[6] == "Absolute Net") { 
						$adjustment = $invoice_record[4];
						$invoice_record[1] = $invoice_record[4];
					}
				}
				//if($invoice_record[4] == 0 and $invoice_record[6] == "Net") {
				if($invoice_record[2] <> 0 and $invoice_record[4] == 0 and $invoice_record[6] == "Net") { 
					$adjustment_iw = $invoice_record[1];
					$invoice_record[2] = $invoice_record[1];
				}
				$the_cost = $invoice_record[0] * $adjustment;
				$total_iw_cost = $invoice_record[0] * $adjustment_iw;
				$the_cost_before = $invoice_record[0] * $invoice_record[1];
				$total_iw_cost_before = $invoice_record[0] * $invoice_record[2];
				$total_payment_pound += $total_iw_cost;
				$total_payment_pound_before += $total_iw_cost_before;
				if($total_iw_cost_before > $total_iw_cost) { 
					$amount_discount += $total_iw_cost_before - $total_iw_cost;
					$line_iw_euro = $total_iw_cost_before; 
				} else {
					$line_iw_euro = $total_iw_cost; 	
				}
				$total_additional += $line_iw_euro;
			}
			if($package_exists == "Yes") {
				$invoice_result = $sql_command->select($database_invoice_history,"qty,
											 cost,
											 iw_cost,
											 currency,
											 timestamp,
											 exchange_rate",
											 "WHERE order_id='".addslashes($invrecord[0])."' 
											 and item_type='Deposit' 
											 and status='Paid' 
											 and currency='Pound'");
			$invoice_row = $sql_command->results($invoice_result);
			foreach($invoice_row as $invoice_record) {
				$total_deposit_paid = eregi_replace(",","",$invoice_record[2]);
				$minum_deposit = $minum_deposit + $total_deposit_paid;
			}
		}
		// start payments
		if ($minum_deposit>0) {
		
			$invoice_result = $sql_command->select($database_invoice_history,"invoice_id","WHERE order_id='".addslashes($recorda[0])."' and item_type='Deposit' and status='Paid'");
			$invoice_record = $sql_command->result($invoice_result);
			$dresp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount)",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Pound'
								 AND customer_payments.invoice_id = '".addslashes($invoiceno)."' 
								 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
			$drespr = $sql_command->result($dresp);
		
			$totalpp = $drespr[0];
		//	$format_gbp = "£ ".number_format($totalpp,2);
		}
		else {
			$resp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount)",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Pound'
								 AND customer_payments.invoice_id = '".addslashes($invoiceno)."'");
			$respr = $sql_command->result($resp);
			$totalpp = $respr[0];
		//	$format_gbp = "£ ".number_format($totalpp,2);
		}
	// end payments
	}
	
	$total_gbp = round(($outstanding_pounds + $total_payment_pound - $minum_deposit - $totalpp), 2);
}
?>