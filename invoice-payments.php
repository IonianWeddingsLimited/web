<?
require ("_includes/settings.php");
require ("_includes/function.templates.php");
include ("_includes/function.database.php");

$getvat = "20.00";
// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


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
							   $database_customer_invoices.id='".addslashes($_GET["invoice"])."' AND 
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
 
echo "The easiest was to pay your deposits is by wire / bank transfer (BAC). Our bank details are below. ";
echo "Please quote your surname and IW booking reference on your transaction";
echo "Ionian Weddings Ltd";
echo "Co-operative Bank PLC";
echo "A/C No 70913224";
echo "Sort Code 08-92-50";
echo "Swift (BIC) - CPBK GB 22";
echo "IBAN – GB21 CPBK 08925070913224";
echo "Balance payments are not refundable; cheques are no longer accepted due to the abolition of the Cheque Guarantee Scheme. We also accept debit cards and credit cards (there is a";
echo "2% transaction fee for credit cards and 3% for international credit cards) – please ask for a form for card payments. If you prefer to pay in Euros, please let us know.";
echo "THANK YOU FOR YOUR BUSINESS.";
echo "images/invoice_abta.jpg";
echo "© Copyright Ionian Weddings Ltd. ".date("Y")." – 10 Crane Mews, 32 Gould Road, Twickenham, England, TW2 6RS";
echo "(t) / (f) +44 208 894 1991 - (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk";
echo "Registered in England and Wales No. 06137035 | VAT Registration Number: 103185747";

if($record[23] == "Outstanding") {
echo "Invoice - Outstanding";
} elseif($record[23] == "Paid") {
echo "Receipt Paid";
} elseif($record[23] == "Refunded") {
echo "Invoice - Refund";
} elseif($record[23] == "Pending") {
echo "Invoice - Pending";
} elseif($record[23] == "Cancelled") {
echo "Invoice - Cancelled";
}
echo $header_image;
echo $bar_image;

// date
$date = date("jS  F Y",$record[21]);

echo 'Date: ';
echo $date;
// order number
echo 'Order Number: ';
echo $record[20];
// invoice number
echo 'Invoice Number: ';
echo $record[19]."/".$_GET["invoice"];
echo 'Due Date: ';
echo 'Immediate';

// name and address
$date2 = date("d/m/Y",$record[17]);
echo 'Ionian Weddings Ltd.';
	 
echo '10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS';
	 
echo '+44 208 894 1991 - weddings@ionianweddings.co.uk';
echo 'TO ' .$record[1]." ".$record[2]." ".$record[3];
	 
echo '(Customer ID '.$record[19].', Wedding Date '.$date2.')';
	 
if($record[4]) {
echo $record[4];
	 
}
if($record[5]) {
echo $record[5];
	
}
if($record[6]) {
echo $record[6];
	 
}
if($record[7]) {
echo $record[7];
	 
}
if($record[8]) {
echo $record[8];
	 
}
if($record[9]) {
echo $record[9];
	 
}
if($record[10]) {
echo $record[10];
	 
}
echo 'Location / Package: ';

echo $record2[1]." > ".$record2[0];

// invoice header


echo "DESCRIPTION";
echo "QTY";
echo "LINE TOTAL";
echo 'T'; 

// invoice text

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
									   item_type","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type='Package'");
$invoice_row = $sql_command->results($invoice_result);
$package_exists = "No";
$total_invoice=0;
foreach($invoice_row as $invoice_record) {
$package_exists = "Yes";
$total_invoice++;
$invoice_record[0] = str_replace("<strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<i>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</i>", "", $invoice_record[0]);
if(eregi("<p>",$invoice_record[0])) {
$start = strpos($invoice_record[0], '<p>');
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 160) ? substr($paragraph, 0, 160) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
$paragraph = preg_replace('~[\r\n]+~', '', $paragraph);
$paragraph = str_replace("&nbsp;", " ", $paragraph);
$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));

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
echo $paragraph;
echo $invoice_record[1];
echo $display_cost;
 
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
									   item_type","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type='Package'");
$invoice_row = $sql_command->results($invoice_result);

$show_additional = "No";
$total_payment = 0;
foreach($invoice_row as $invoice_record) {
$package_exists = "Yes";
$show_additional = "Yes";
$invoice_record[0] = str_replace("<strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<i>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</i>", "", $invoice_record[0]);

if(eregi("<p>",$invoice_record[0])) {
$start = strpos($invoice_record[0], '<p>');
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 180) ? substr($paragraph, 0, 180) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
	
$paragraph = preg_replace('~[\r\n]+~', '', $paragraph);
$paragraph = str_replace("&nbsp;", " ", $paragraph);
$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));

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

echo $paragraph;
echo $invoice_record[1];
echo $display_cost;
 
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
									   item_type","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type!='Package'");
$invoice_row = $sql_command->results($invoice_result);

foreach($invoice_row as $invoice_record) {
$total_invoice++;
$invoice_record[0] = str_replace("<strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<i>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</i>", "", $invoice_record[0]);

if(eregi("<p>",$invoice_record[0])) {
$start = strpos($invoice_record[0], '<p>');
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 160) ? substr($paragraph, 0, 160) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
							   

$paragraph = preg_replace('~[\r\n]+~', '', $paragraph);
$paragraph = str_replace("&nbsp;", " ", $paragraph);
$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));

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
echo $paragraph;
echo $invoice_record[1];
echo $display_cost;

}
}

if($total_invoice > 0) {
$outstanding_euros = $total_payment_euro;
$outstanding_euros_before = $total_payment_euro_before;
$euro_discount = $outstanding_euros_before - $outstanding_euros;
echo "Amount in Euros";
echo "€ ".number_format($outstanding_euros_before,2);
if($euro_discount != 0) {
echo "Discount in Euros";
echo "€ ".number_format($euro_discount,2);
}
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
echo "Deposit Paid";
echo "- € ".number_format($invoice_record[3],2);
$minum_deposit = $total_deposit_paid;
$minum_deposit2 = $invoice_record[3];
}
}
}

if($minum_deposit2 > 0 or $euro_discount != 0) {
echo "Total in Euros";
echo "€ ".number_format($outstanding_euros_before - $minum_deposit2 - $euro_discount,2);
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
									   item_type","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type!='Package'");
$invoice_row = $sql_command->results($invoice_result);
foreach($invoice_row as $invoice_record) {
$show_additional = "Yes";
}
$checktotal = $outstanding_pounds - $minum_deposit;
if($checktotal > 0) {
echo "Amount Outstanding in GBP";
echo "£ ".number_format($outstanding_pounds - $minum_deposit,2);
}
$show_additional = "No";
$total_payment = 0;
foreach($invoice_row as $invoice_record) {
$show_additional = "Yes";
$invoice_record[0] = str_replace("<strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</u>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("<i>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</i>", "", $invoice_record[0]);

if(eregi("<p>",$invoice_record[0])) {
$start = strpos($invoice_record[0], '<p>');
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 180) ? substr($paragraph, 0, 180) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
							   
$paragraph = preg_replace('~[\r\n]+~', '', $paragraph);
$paragraph = str_replace("&nbsp;", " ", $paragraph);
$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));

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
echo $paragraph;
echo $invoice_record[1];
echo $display_cost;
 
}
}
if($checktotal > 0 and $total_additional > 0) {
echo "Additional Amount in GBP";
echo "£ ".number_format($total_additional,2);
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
echo "Deposit Paid";
echo "- £ ".number_format($total_deposit_paid,2);
$minum_deposit = $total_deposit_paid;
}
}

$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);
if($amount_discount != 0) {
$total_gbp = $outstanding_pounds_before + $total_payment_pound_before - $minum_deposit;
echo "TOTAL in GBP";
echo "£ ".number_format($total_gbp,2);
echo "Discount in GBP";
echo "£ ".number_format($amount_discount,2);
} else {
$discount_amount_calc = 0;
}


$total_gbp = $outstanding_pounds + $total_payment_pound - $minum_deposit ;
echo "TOTAL Payable in GBP";
echo "£ ".number_format($total_gbp,2);
echo "invoice-".$_GET["invoice"].".pdf";
$sql_command->close();
?>