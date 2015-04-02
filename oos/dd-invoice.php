<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

$getvat = "20.00";

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


$get_template = new oos_template();
include("run_login.php");

$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";



	
	
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

if(!$record[0] or $record[22] == 0) {
$get_template->topHTML();
$get_template->errorHTML("Download Invoice","Oops!","No invoice exists with this invoice number","Link","oos/");
$get_template->bottomHTML();
$sql_command->close();
}


$result2 = $sql_command->select("$database_order_details,$database_packages,$database_package_info,$database_navigation","$database_packages.package_name,
							   $database_navigation.page_name","WHERE 
							   $database_order_details.id='".$record[20]."' AND 
							   $database_order_details.package_id=$database_package_info.id and 
							   $database_package_info.package_id=$database_packages.id and 
							   $database_packages.island_id=$database_navigation.id");
$record2 = $sql_command->result($result2);

 
define('FPDF_FONTPATH','/home/ionianwe/public_html/oos/font/');
require('fpdf.php');

class PDF extends FPDF {
function Footer() {

$this->SetXY(0,-55);
$this->SetLeftMargin('10');
$this->SetFont('Arial','','5'); 
$this->SetTextColor(151,151,151); 
$this->Write(0,"The easiest was to pay your deposits is by wire / bank transfer (BAC). Our bank details are below. ");
$this->SetFont('Arial','B','5'); 
$this->Write(0,"Please quote your surname and IW booking reference on your transaction");
$this->Ln(5); 
$this->SetFont('Arial','','5'); 
$this->Write(0,"Ionian Weddings Ltd");
$this->Ln(3.2); 
$this->Write(0,"Co-operative Bank PLC");
$this->Ln(3.2); 
$this->Write(0,"A/C No 70913224");
$this->Ln(3.2); 
$this->Write(0,"Sort Code 08-92-50");
$this->Ln(3.2); 
$this->Write(0,"Swift (BIC) - CPBK GB 22");
$this->Ln(3.2); 
$this->Write(0,"IBAN – GB21 CPBK 08925070913224");
$this->Ln(5); 
$this->Write(0,"Balance payments are not refundable; cheques are no longer accepted due to the abolition of the Cheque Guarantee Scheme. We also accept debit cards and credit cards (there is a 2% transaction fee for ");
$this->Ln(3.2); 
$this->Write(0,"credit cards and 3% for international credit cards) – please ask for a form for card payments. If you prefer to pay in Euros, please let us know.");
$this->Ln(5); 
$this->SetTextColor(152,72,5);
$this->Write(0,"THANK YOU FOR YOUR BUSINESS.");
$this->Ln(5); 

$gety = $this->GetY(); 

$this->Image("../images/invoice_abta.jpg", 185, $gety + 0.5, 16.93,6.77);

$this->SetTextColor(151,151,151);  
$this->Write(0,"© Copyright Ionian Weddings Ltd. ".date("Y")." – 10 Crane Mews, 32 Gould Road, Twickenham, England, TW2 6RS");
$this->Ln(3.5); 
$this->Write(0,"(t) / (f) +44 208 894 1991 - (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk");
$this->Ln(3.5); 
$this->Write(0,"Registered in England and Wales No. 06137035 | VAT Registration Number: 103185747");
}
}


$pdf=new PDF();
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Invoice No '.$_GET["invoice"]);


$pdf->SetY('5');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(226,179,64);
$pdf->SetLineWidth(0.1); 

$pdf->SetLeftMargin('130');
if($record[23] == "Outstanding") {
$pdf->Cell(70,5,"Invoice - Outstanding",'',0,'R',true);
} elseif($record[23] == "Paid") {
$pdf->Cell(70,5,"Receipt Paid",'',0,'R',true);
} elseif($record[23] == "Refunded") {
$pdf->Cell(70,5,"Invoice - Refund",'',0,'R',true);
} elseif($record[23] == "Pending") {
$pdf->Cell(70,5,"Invoice - Pending",'',0,'R',true);
}elseif($record[23] == "Cancelled") {
$pdf->Cell(70,5,"Invoice - Cancelled",'',0,'R',true);
}



$pdf->Ln(10); 



$pdf->SetY('5');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

$pdf->Image($header_image, 10, 0,60);
$pdf->Image($bar_image, 10, 14, 190,0.1);


$pdf->SetY('24');
$pdf->SetLeftMargin('150');
// date
$date = date("jS  F Y",$record[21]);

$pdf->SetFont('Arial','B',7);
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Date: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$date);
$pdf->Ln(5);

// order number
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Order Number: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$record[20]);
$pdf->Ln(5);

// invoice number
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Invoice Number: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$record[19]."/".$_GET["invoice"]);
$pdf->Ln(10);

$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Due Date: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,'Immediate');


$pdf->SetY('22');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

// name and address

$date2 = date("d/m/Y",$record[17]);

$pdf->SetFont('Arial','',7);
$pdf->SetTextColor(0,0,0); 
$pdf->Write(0,'Ionian Weddings Ltd.');
$pdf->Ln(3.6);	 
$pdf->Write(0,'10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS');
$pdf->Ln(3.6);	 
$pdf->Write(0,'+44 208 894 1991 - weddings@ionianweddings.co.uk');
$pdf->Ln(8);	 
$pdf->Write(0,'TO ' .$record[1]." ".$record[2]." ".$record[3]);
$pdf->Ln(3.6);	 
$pdf->Write(0,'(Customer ID '.$record[19].', Wedding Date '.$date2.')');
$pdf->Ln(3.6);	 
if($record[4]) {
$pdf->Write(0,$record[4]);
$pdf->Ln(3.6);	 
}
if($record[5]) {
$pdf->Write(0,$record[5]);
$pdf->Ln(3.6);	
}
if($record[6]) {
$pdf->Write(0,$record[6]);
$pdf->Ln(3.6);	 
}
if($record[7]) {
$pdf->Write(0,$record[7]);
$pdf->Ln(3.6);	 
}
if($record[8]) {
$pdf->Write(0,$record[8]);
$pdf->Ln(3.6);	 
}
if($record[9]) {
$pdf->Write(0,$record[9]);
$pdf->Ln(3.6);	 
}
if($record[10]) {
$pdf->Write(0,$record[10]);
$pdf->Ln(3.6);	 
}

$pdf->Ln(3.6);
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Location / Package: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$record2[1]." > ".$record2[0]);
$pdf->Ln(3.6);

$pdf->SetX('5');
$pdf->SetLeftMargin('10');

// invoice header
$pdf->SetFont('','B',7);
$pdf->SetTextColor(226,179,64);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(226,179,64);
$pdf->SetLineWidth(0.1); 



$pdf->Ln(0.1);
$pdf->Cell(160.05,5,"DESCRIPTION",'',0,'L',true);
$pdf->Cell(9.95,5,"QTY",'',0,'C',true);
$pdf->Cell(19.95,5,"LINE TOTAL",'',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


// invoice text
$pdf->SetFont('','B',6);
$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1);   






























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

if(eregi("<p>",$invoice_record[0])) {
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 160) ? substr($paragraph, 0, 160) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}

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

}

}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;

$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
	
$total_payment_euro += $total_iw_cost;
$total_payment_euro_before += $total_iw_cost_before;

if($total_iw_cost != 0) {
$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(160.05,4,$paragraph,'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(9.95,5,$invoice_record[1],'LR',0,'C',true);
$pdf->Cell(19.95,4,'€ '.number_format($total_iw_cost_before,2),'LR',0,'R',true);

$pdf->Ln(3.6); 
}
}
$pdf->Ln(0.4);



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

if(eregi("<p>",$invoice_record[0])) {
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 180) ? substr($paragraph, 0, 180) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}

	
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

}

}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;

$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
			
						   
$total_payment_pound += $total_iw_cost;
$total_payment_pound_before += $total_iw_cost_before;

if($total_iw_cost != 0) {
$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(170,4,$paragraph ." x".$invoice_record[1],'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,4,'£'.number_format($total_iw_cost_before,2),'LR',0,'R',true);

$pdf->Ln(3.6); 
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

if(eregi("<p>",$invoice_record[0])) {
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 160) ? substr($paragraph, 0, 160) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
							   








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

}

}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;

$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
			
$total_payment_euro += $total_iw_cost;
$total_payment_euro_before += $total_iw_cost_before;

if($total_iw_cost != 0) {
$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(160.05,4,$paragraph,'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(9.95,5,$invoice_record[1],'LR',0,'C',true);
$pdf->Cell(19.95,4,'€ '.number_format($total_iw_cost_before,2),'LR',0,'R',true);

$pdf->Ln(3.6);
}
}
$pdf->Ln(0.4);


if($total_invoice > 0) {
$pdf->Cell(189.95,0,'','T'); 


$pdf->Ln(0.1);

$outstanding_euros = $total_payment_euro;
$outstanding_euros_before = $total_payment_euro_before;

$pdf->Cell(170,5,"Amount Outstanding in Euros",'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"€ ".number_format($outstanding_euros_before,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


$outstanding_pounds = $outstanding_euros / $record[22];
$outstanding_pounds_before = $outstanding_euros_before / $record[22];


$pdf->Cell(170,5,"Amount Outstanding in GBP",'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"£ ".number_format($outstanding_pounds_before,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);

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
									   item_type","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type!='Package'");
$invoice_row = $sql_command->results($invoice_result);


$show_additional = "No";
$total_payment = 0;
foreach($invoice_row as $invoice_record) {
$show_additional = "Yes";
$invoice_record[0] = str_replace("<strong>", "", $invoice_record[0]);
$invoice_record[0] = str_replace("</strong>", "", $invoice_record[0]);

if(eregi("<p>",$invoice_record[0])) {
$end = strpos($invoice_record[0], '</p>', $start);
$paragraph = substr($invoice_record[0], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 180) ? substr($paragraph, 0, 180) . '...' : $paragraph;
} else {
$paragraph = stripslashes($invoice_record[0]);
}
							   


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

}

}

$the_cost = $invoice_record[1] * $adjustment;
$total_iw_cost = $invoice_record[1] * $adjustment_iw;


$the_cost_before = $invoice_record[1] * $invoice_record[2];
$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
						   
$total_payment_pound += $total_iw_cost;
$total_payment_pound_before += $total_iw_cost_before;

if($total_iw_cost_before > $total_iw_cost) { 
$line_iw_euro = $total_iw_cost_before; 
} else {
$line_iw_euro = $total_iw_cost; 	
}


if($total_payment_pound_before > $total_payment_pound) { 
$line_iw_pound = $total_payment_pound_before;
} else {
$line_iw_pound = $total_payment_pound;	
}

if($total_iw_cost != 0) {
$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(170,4,$paragraph ." x".$invoice_record[1],'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,4,'£'.number_format($line_iw_euro,2),'LR',0,'R',true);

$pdf->Ln(3.6); 
}
}
if($show_additional == "Yes") {
$pdf->Ln(0.4);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
$pdf->SetFont('','B',6);
$pdf->Cell(170,5,"Additional Amount in GBP",'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"£ ".number_format($line_iw_pound,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
}


$minum_deposit = 0;
if($package_exists == "Yes") {
$invoice_result = $sql_command->select($database_invoice_history,"name,
									   qty,
									   cost,
									   iw_cost,
									   currency,
									   timestamp,
									   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid'");
$invoice_record = $sql_command->result($invoice_result);

if($invoice_record[0]) {

if($invoice_record[4] == "Pound") {
$total_deposit_paid = regi_replace(",","",$invoice_record[3]);
} else {
$total_deposit_paid = eregi_replace(",","",number_format($invoice_record[3] / $invoice_record[6],2));
}

$pdf->Ln(0.1);
$pdf->SetFont('','B',6);
$pdf->Cell(170,5,"Deposit Paid",'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"- £ ".number_format($total_deposit_paid,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);



$minum_deposit = $total_deposit_paid;


}


}


// start payments

$result = $sql_command->select("$database_customer_invoices","customer_invoices.order_id,customer_invoices.type",
							   "WHERE $database_customer_invoices.id='".addslashes($_GET["invoice"])."'
							   AND $database_customer_invoices.status != 'Paid'
							   ORDER BY $database_customer_invoices.timestamp DESC");
$recorda = $sql_command->result($result);

if (count($recorda)>0) {

	if ($minum_deposit>0) {
	
		$invoice_result = $sql_command->select($database_invoice_history,"invoice_id","WHERE order_id='".addslashes($recorda[0])."' and item_type='Deposit' and status='Paid'");
		$invoice_record = $sql_command->result($invoice_result);
		$dresp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount),customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."' 
							 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
		$drespr = $sql_command->result($dresp);
		$payDates = $drespr[1];
		$totalpp = $drespr[0];
		$format_gbp = "£ ".number_format($totalpp,2);
	}
	else {
		$resp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount),customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."'");
		$respr = $sql_command->result($resp);
		$payDates = $respr[1];
		$totalpp = $respr[0];
		$format_gbp = "£ ".number_format($totalpp,2);
	}
	if ($totalpp>0) {
		$resp = $sql_command->select("customer_payments,customer_transactions",
							 "customer_payments.pay_no,customer_payments.p_amount",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.invoice_id = '".addslashes($_GET['invoice'])."'");
		$respr = $sql_command->results($resp);
		$payids = 	"";

		list($payDate,$payTime) = explode(" ",$payDates);
		foreach($respr as $pids) {
			$pamount = $pids[1];
			$payids = $pids[0];	
			$pdf->Ln(0.1);
			$pdf->SetFont('','B',6);
			$pdf->Cell(170,5,"Part Payment ID - ".$payids." (".$payDate.")",'LR',0,'L',true);
			$pdf->SetFont('','',6);
			$pdf->Cell(19.95,5,"- £ ".number_format($pamount,2),'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
		}
	}
}

// end payments



$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);

if($discount_amount_calc > 0) {
$total_gbp = $outstanding_pounds_before + $total_payment_pound_before - $minum_deposit;

$pdf->Ln(0.1);
$pdf->Cell(170,5,"TOTAL in GBP",'LR',0,'L',true);
$pdf->Cell(19.95,5,"£ ".number_format($total_gbp,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
$pdf->Cell(170,5,"Discount in GBP",'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"£ ".number_format($discount_amount_calc,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
} else {
$discount_amount_calc = 0;
}


$pdf->SetFont('','B',7);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(251,212,180);
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1); 

$total_gbp = $outstanding_pounds + $total_payment_pound - $minum_deposit;

$pdf->Ln(0.1);
$pdf->Cell(170,5,"TOTAL Payable in GBP",'LR',0,'R',true);
$pdf->Cell(19.95,5,"£ ".number_format($total_gbp,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


$pdf->Ln(5);

$pdf->output("invoice-".$_GET["invoice"].".pdf","D");

$sql_command->close();
?>