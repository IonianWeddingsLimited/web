<?

function getInvTotal($invoiceno) {

$getvat = "20.00";

$result = $sql_command->select("$database_customer_invoices,$database_order_details,$database_clients","$database_clients.id,
$database_clients.title,
$database_clients.firstname,
$database_clients.lastname,
$database_clients.address_1,
$database_clients.address_2,
$database_clients.address_3,
$database_clients.town,
$database_clients.county,
$database_clients.country,
$database_clients.postcode,
$database_clients.email,
$database_clients.tel,
$database_clients.mob,
$database_clients.fax,
$database_clients.dob,
$database_clients.mailing_list,
$database_clients.wedding_date,
$database_clients.destination,
$database_clients.iwcuid,
$database_customer_invoices.order_id,
$database_customer_invoices.timestamp,
							   $database_customer_invoices.exchange_rate,
							   $database_customer_invoices.status","WHERE 
							   $database_customer_invoices.id='".addslashes($invoiceno)."' AND 
							   $database_customer_invoices.order_id=$database_order_details.id AND 
							   $database_order_details.client_id=$database_clients.id");
$record = $sql_command->result($result);

$result2 = $sql_command->select("$database_order_details,$database_packages,$database_package_info,$database_navigation","$database_packages.package_name,
							   $database_navigation.page_name","WHERE 
							   $database_order_details.id='".$record[20]."' AND 
							   $database_order_details.package_id=$database_package_info.id and 
							   $database_package_info.package_id=$database_packages.id and 
							   $database_packages.island_id=$database_navigation.id");
$record2 = $sql_command->result($result2);

$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   local_tax_percent,
									   discount_at,
									   discount_percent,
									   currency,
									   exchange_rate,
									   timestamp,
									   d_value,
									   d_type,
									   code,
									   d_,
									   item_type","WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type='Package'");
$invoice_row = $sql_command->results($invoice_result);
$package_exists = "No";
$total_invoice=0;
foreach($invoice_row as $invoice_record) {
$package_exists = "Yes";
$total_invoice++;

$adjustment_iw = $invoice_record[3];
$adjustment = $invoice_record[2];

// Work out adjustments
if($invoice_record[1] > 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] + ($percent_value * $invoice_record[10]);
}
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
} elseif($invoice_record[1] < 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
}
if($invoice_record[10] == 0 and $invoice_record[13] == "Net") { 
$adjustment_iw = $invoice_record[2];
$invoice_record[3] = $invoice_record[2];
}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;
$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
	
$total_payment_euro += $total_iw_cost;
$total_payment_euro_before += $total_iw_cost_before;

if($total_iw_cost_before > $total_iw_cost) { 
$line_iw_euro = $total_iw_cost_before; 
} else {
$line_iw_euro = $total_iw_cost; 	
}

}

$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   local_tax_percent,
									   discount_at,
									   discount_percent,
									   currency,
									   exchange_rate,
									   timestamp,
									   d_value,
									   d_type,
									   code,
									   d_,
									   item_type","WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type='Package'");
$invoice_row = $sql_command->results($invoice_result);

$show_additional = "No";
$total_payment = 0;
foreach($invoice_row as $invoice_record) {
$package_exists = "Yes";
$show_additional = "Yes";

$adjustment_iw = $invoice_record[3];
$adjustment = $invoice_record[2];

// Work out adjustments
if($invoice_record[1] > 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] + ($percent_value * $invoice_record[10]);
}
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
	
} elseif($invoice_record[1] < 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10]; 
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
}
if($invoice_record[10] == 0 and $invoice_record[13] == "Net") { 
$adjustment_iw = $invoice_record[2];
$invoice_record[3] = $invoice_record[2];
}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;
$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
			
						   
$total_payment_pound += $total_iw_cost;
$total_payment_pound_before += $total_iw_cost_before;
if($total_iw_cost_before > $total_iw_cost) { 
$euro_amount_discount += $total_iw_cost_before - $total_iw_cost;
$line_iw_euro = $total_iw_cost_before; 
} else {
$line_iw_euro = $total_iw_cost; 	
}
if($total_iw_cost != 0) {
$display_cost = '£ '.number_format($line_iw_euro,2);
$display_cost = eregi_replace("£ -","- £ ",$display_cost);
 
}
}


$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   local_tax_percent,
									   discount_at,
									   discount_percent,
									   currency,
									   exchange_rate,
									   timestamp,
									   d_value,
									   d_type,
									   code,
									   d_,
									   item_type","WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type!='Package'");
$invoice_row = $sql_command->results($invoice_result);

foreach($invoice_row as $invoice_record) {
$total_invoice++;

$adjustment_iw = $invoice_record[3];
$adjustment = $invoice_record[2];

// Work out adjustments
if($invoice_record[1] > 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] + ($percent_value * $invoice_record[10]);
}
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
	
} elseif($invoice_record[1] < 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
}
if($invoice_record[10] == 0 and $invoice_record[13] == "Net") { 
$adjustment_iw = $invoice_record[2];
$invoice_record[3] = $invoice_record[2];
}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;
$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
			
$total_payment_euro += $total_iw_cost;
$total_payment_euro_before += $total_iw_cost_before;

if($total_iw_cost_before > $total_iw_cost) { 
$line_iw_euro = $total_iw_cost_before; 
} else {
$line_iw_euro = $total_iw_cost; 	
}
if($total_iw_cost != 0) {
$display_cost = '€ '.number_format($line_iw_euro,2);
$display_cost = eregi_replace("€ -","- € ",$display_cost);
}
}

if($total_invoice > 0) {
$outstanding_euros = $total_payment_euro;
$outstanding_euros_before = $total_payment_euro_before;
$euro_discount = $outstanding_euros_before - $outstanding_euros;
$minum_deposit = 0;
$minum_deposit2 = 0;
if($package_exists == "Yes") {
$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   currency,
									   timestamp,
									   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Euro'");
$invoice_record = $sql_command->result($invoice_result);
if($invoice_record[0]) {
$total_deposit_paid = eregi_replace(",","",number_format($invoice_record[3] / $invoice_record[6],2));
$minum_deposit = $total_deposit_paid;
$minum_deposit2 = $invoice_record[3];
}
}
}

$outstanding_pounds = $outstanding_euros  / $record[22];
$outstanding_pounds_before = $outstanding_euros_before / $record[22];
$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   local_tax_percent,
									   discount_at,
									   discount_percent,
									   currency,
									   exchange_rate,
									   timestamp,
									   d_value,
									   d_type,
									   code,
									   d_,
									   item_type","WHERE invoice_id='".addslashes($invoiceno)."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type!='Package'");
$invoice_row = $sql_command->results($invoice_result);
foreach($invoice_row as $invoice_record) {
$show_additional = "Yes";
}
$checktotal = $outstanding_pounds - $minum_deposit;

$show_additional = "No";
$total_payment = 0;
foreach($invoice_row as $invoice_record) {
$show_additional = "Yes";

$adjustment_iw = $invoice_record[3];
$adjustment = $invoice_record[2];

// Work out adjustments
if($invoice_record[1] > 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] + $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] + ($percent_value * $invoice_record[10]);
}
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
	
} elseif($invoice_record[1] < 0) {
	
if($invoice_record[13] == "Gross") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment_iw = $invoice_record[3] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[3] / 100);
$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Net") { 
if($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
$adjustment = $invoice_record[2] - $invoice_record[10];
} elseif($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
$percent_value = ($invoice_record[2] / 100);
$adjustment = $invoice_record[2] - ($percent_value * $invoice_record[10]);
} 
} elseif($invoice_record[13] == "Absolute Gross") { 
$adjustment_iw = $invoice_record[10];
$invoice_record[3] = $invoice_record[10];
} elseif($invoice_record[13] == "Absolute Net") { 
$adjustment = $invoice_record[10];
$invoice_record[2] = $invoice_record[10];
}
	
}
if($invoice_record[10] == 0 and $invoice_record[13] == "Net") { 
$adjustment_iw = $invoice_record[2];
$invoice_record[3] = $invoice_record[2];
}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;

$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
						   
$total_payment_pound += $total_iw_cost;
$total_payment_pound_before += $total_iw_cost_before;
if($total_iw_cost_before > $total_iw_cost) { 
$amount_discount += $total_iw_cost_before - $total_iw_cost;
$line_iw_euro = $total_iw_cost_before; 
} else {
$line_iw_euro = $total_iw_cost; 	
}

$total_additional += $line_iw_euro;
if($total_iw_cost != 0) {
$display_cost = '£ '.number_format($line_iw_euro,2);
$display_cost = eregi_replace("£ -","- £ ",$display_cost);
 
}
}

if($package_exists == "Yes") {
$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   currency,
									   timestamp,
									   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Pound'");
$invoice_record = $sql_command->result($invoice_result);
if($invoice_record[0]) {
$total_deposit_paid = eregi_replace(",","",$invoice_record[3]);
$minum_deposit = $total_deposit_paid;
}
}

$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);
if($amount_discount != 0) {
$total_gbp = $outstanding_pounds_before + $total_payment_pound_before - $minum_deposit;
} else {
$discount_amount_calc = 0;
}


$total_gbp = $outstanding_pounds + $total_payment_pound - $minum_deposit ;
$total_gbp = "£ ".number_format($total_gbp,2);

return $total_gbp;

}

?>