<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

$getvat = "20.00";
$total_payment_euro = 0;
$total_payment_euro_before = 0;
$total_payment_pound = 0;
$total_payment_pound_before = 0;
$minum_deposit = 0;
$minum_deposit2 = 0;
$total_additional = 0;
$amount_discount = 0;

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


$get_template = new oos_template();
include("run_login.php");
$displayInc = ($the_username=="u1") ? true : false;
$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

$currency_q = $sql_command->select("$db_currency_conversion",
								   "$db_currency_conversion.currency_name,
								   $db_currency_conversion.currency_acro,
								   $db_currency_conversion.currency_symbol",
								   "");
$currency_i = $sql_command->results($currency_q);
$c_acro = array();
$c_symbol = array();
foreach ($currency_i as $ci) {
	$cid = $ci[0];
	$c_acro[$cid] = $ci[1];
	$c_symbol[$cid] = $ci[2];
}

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

$clientcurrencyresult = $sql_command->select("$database_clients_options",
									"$database_clients_options.option_value",
									"WHERE	$database_clients_options.client_id='".addslashes($_GET["invoice"])."'
									AND		$database_clients_options.client_option='continental'");
$clientcurrencyrecord = $sql_command->result($clientcurrencyresult);

if ($clientcurrencyrecord[0] == 'Yes') {
	$InvoiceCurrency	=	"Euro";
} else {
	$InvoiceCurrency	=	"";
}


$result2 = $sql_command->select("$database_order_details,$database_packages,$database_package_info,$database_navigation","$database_packages.package_name,
							   $database_navigation.page_name","WHERE 
							   $database_order_details.id='".$record[20]."' AND 
							   $database_order_details.package_id=$database_package_info.id and 
							   $database_package_info.package_id=$database_packages.id and 
							   $database_packages.island_id=$database_navigation.id");
$record2 = $sql_command->result($result2);

require('fpdf/class.fpdf.php');

class PDF extends FPDF {

//	function Header() {
//		$this->SetXY(5,5);
//		$this->SetLeftMargin('10');
//		
//		$this->Image("../images/invoice_header.jpg", 10, 0,60);
//		$this->Image("../images/invoice_line.jpg", 10, 14, 190,0.1);
//		
//		$this->SetY('5');
//		$this->SetFont('Arial','','18');  
//		$this->SetTextColor(226,179,64);
//		$this->SetFillColor(255,255,255);
//		$this->SetDrawColor(226,179,64);
//		$this->SetLineWidth(0.1); 
//		
//		$this->SetLeftMargin('130');
//		
//		if($record[23] == "Outstanding") {
//			$this->Cell(70,5,"Invoice - Outstanding",'',0,'R',true);
//		} elseif($record[23] == "Paid") {
//			$this->Cell(70,5,"Receipt Paid",'',0,'R',true);
//		} elseif($record[23] == "Refunded") {
//			$this->Cell(70,5,"Invoice - Refund",'',0,'R',true);
//		} elseif($record[23] == "Pending") {
//			$pdf->Cell(70,5,"Invoice - Pending",'',0,'R',true);
//		} elseif($record[23] == "Cancelled") {
//			$this->Cell(70,5,"Invoice - Cancelled",'',0,'R',true);
//		} else {
//			$this->Cell(70,5,$record[23],'',0,'R',true);
//		}
//		$this->Ln(0);
//	}
	function Footer() {
		global $InvoiceCurrency;
		
		$this->SetXY(0,-70);
		$this->SetLeftMargin('10');
		$this->SetFont('Arial','','8'); 
		$this->SetTextColor(151,151,151); 
	
		if($InvoiceCurrency == 'Euro') {
			$this->Write(0,"Please pay by international bank transfer into our Euro account. Our bank details in Greece are below. ");
		}
		else {
			$this->Write(0,"The easiest was to pay your deposits is by wire / bank transfer (BAC). Our bank details are below. ");
		}
		$this->Ln(3.2); 
		$this->Write(0,"For more information about how we calculate the exchange rates click or copy this link http://www.ionianweddings.co.uk/terms-and-conditions/#prices");
		$this->Ln(3.2); 
		$this->Write(0,"Please quote your surname and IW booking reference on your transaction");
	
		if($InvoiceCurrency == 'Euro') {
		
//			$col1.="For Euro (€) Payments:\n";
//			$col1.="Account Holder Name - Andreas Palikiras\n";
//			$col1.="Bank – National Bank of Greece\n";
//			$col1.="Branch Code - 749\n";
//			$col1.="Address – Corfu City Centre, 6 Samara Street, Kerkyra 49100, Greece\n";
//			$col1.="Swift Code (BIC) - ETHNGRAA\n";
//			$col1.="A/C No. - 279/603592-25\n";
//			$col1.="IBAN – GR4201102790000027960359225\n";
			
			$this->Ln(5); 
			$this->SetFont('Arial','','8'); 
			$this->Write(0,"For Euro (€) Payments:");
			$this->Ln(3.2); 
			$this->Write(0,"Account Holder Name - Andreas Palikiras");
			$this->Ln(3.2); 
			$this->Write(0,"Bank – National Bank of Greece");
			$this->Ln(3.2); 
			$this->Write(0,"Branch Code - 749");
			$this->Ln(3.2); 
			$this->Write(0,"Address – Corfu City Centre, 6 Samara Street, Kerkyra 49100, Greece");
			$this->Ln(3.2); 
			$this->Write(0,"Swift Code (BIC) - ETHNGRAA");
			$this->Ln(3.2); 
			$this->Write(0,"A/C No. - 279/603592-25");
			$this->Ln(3.2); 
			$this->Write(0,"IBAN – GR4201102790000027960359225");
			$this->Ln(5);
		} else {
			$this->Ln(5); 
			$this->SetFont('Arial','','8'); 
			$this->Write(0,"For Electronic Payments:");
			$this->Ln(3.2); 
			$this->Write(0,"Account Holder - Ionian Weddings Ltd");
			$this->Ln(3.2); 
			$this->Write(0,"Bank - Co-operative Bank PLC");
			$this->Ln(3.2); 
			$this->Write(0,"A/C No. - 70913224");
			$this->Ln(3.2); 
			$this->Write(0,"Sort Code - 08-92-50");
			$this->Ln(3.2); 
			$this->Write(0,"Swift (BIC) - CPBK GB 22");
			$this->Ln(3.2); 
			$this->Write(0,"IBAN – GB21 CPBK 08925070913224");
			$this->Ln(5);
		}
		if($InvoiceCurrency == 'Euro') {
			$this->Ln(3.2); 
			$this->Write(0,"Balance payments are not refundable; if you wish to pay in Euros, you can only pay by international transfer into the account above. ");
			$this->Ln(3.2); 
			$this->Write(0,"Please ask us if you prefer to pay in Pounds.");
		}
		else {
			$this->Ln(3.2); 
			$this->Write(0,"Balance payments are not refundable; cheques are no longer accepted due to the abolition of the Cheque Guarantee Scheme.");
			$this->Ln(3.2);
			$this->Write(0,"We also accept debit cards and credit cards (there is a 2% transaction fee for credit cards and 3% for international credit cards)");
			$this->Ln(3.2);
			$this->Write(0,"Please ask for a form for card payments. If you prefer to pay in Euros, please let us know.");
		}
	
	
		/*$this->Write(0,"Balance payments are not refundable; cheques are no longer accepted due to the abolition of the Cheque Guarantee Scheme. We also accept debit cards and credit cards (there is a");
		$this->Ln(3.2); 
		$this->Write(0,"2% transaction fee for credit cards and 3% for international credit cards) – please ask for a form for card payments. If you prefer to pay in Euros, please let us know.");
		*/
		$this->Ln(5); 
		$this->SetTextColor(152,72,6.6);
		$this->Write(0,"THANK YOU FOR CHOOSING IONIAN WEDDINGS.");
		$this->Ln(5); 
	
		$gety = $this->GetY(); 
		
		$this->Image("../images/invoice_abta.jpg", 185, $gety + 0.5, 16.93,6.77);
		$this->SetFont('Arial','','8'); 
		$this->SetTextColor(151,151,151);  
		$this->Write(0,"© Copyright Ionian Weddings Ltd. ".date("Y")." – 10 Crane Mews, 32 Gould Road, Twickenham, England, TW2 6RS");
		$this->Ln(3.5); 
		$this->Write(0,"(t) / (f) +44 208 894 1991 - (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk");
		$this->Ln(3.5); 
		$this->Write(0,"Registered in England and Wales No. 06137035 | VAT Registration Number: 103185747");
		$this->Ln(3.5);
		$this->Write(0,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln(3.5);
	}
}

$breakheight = 221;

$pdf = new PDF();
$pdf->SetAutoPageBreak(true , 75);
$pdf->AliasNbPages();
$pdf->tFPDF();
$pdf->AddFont('Arial', '','', true);
$pdf->AddFont('Arial', 'B','', true);
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
} elseif($record[23] == "Cancelled") {
	$pdf->Cell(70,5,"Invoice - Cancelled",'',0,'R',true);
} elseif($record[23] == "Quotation") {
	$pdf->Cell(70,5,"Quotation",'',0,'R',true);
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

$pdf->SetFont('Arial','B',8);
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

$pdf->SetFont('Arial','',8);
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
$pdf->SetFont('','B',8);;
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

$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1);   


$currency_name = $InvoiceCurrency;

if (empty($currency_name)) { 

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
										   item_type,order_id,type_id",
										   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
										   and order_id='".addslashes($record[20])."' 
										   and currency='Euro' and item_type='Package'");
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
		$paragraph = str_replace("&amp;", "&", $paragraph);
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
	
	$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
	// Display Package Line
	
	if($total_iw_cost != 0 && $displayitem==0) {

		$x = $pdf->GetX();
		$y = $pdf->GetY();
				
		$pdf->SetLeftMargin('10');
		$pdf->SetFont('','',8);;
		
		$display_cost = '€ '.number_format($line_iw_euro,2);
		$display_cost = eregi_replace("€ -","- € ",$display_cost);
		$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
		
		$pdf->SetFont('','',8);;
		
		$y2 = $pdf->GetY();
		
		if ($y > $breakheight) {
			$y=	10;
		}
		
		$CellHeight	=	($y2 - $y);
		$LineHeight	=	($y2 - $y) - $CellHeight;
		
		$pdf->SetXY($x + 160.05, $y);
		$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
		
		$pdf->SetXY($x + 160.05 + 9.95, $y);
		$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
		$pdf->Ln($LineHeight);

		$include_check = $sql_command->select($database_order_history,"name,qty,order_id,type_id",
												   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
												   and order_id='".addslashes($record[20])."'
												   and type='Included'and qty>0");
		$include_results = $sql_command->results($include_check);
		
		// Display Individual AI items
		
		foreach ($include_results as $ir) {
			$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($ir[3])."' AND note_type = 'Hide' AND extra = 'Yes'");
			if ($displayitem==0) {
				if(eregi("<p>",$ir[0])) {
				$start = strpos($ir[0], '<p>');
				$end = strpos($ir[0], '</p>', $start);
				$paragraph = substr($ir[0], $start, $end-$start+4);
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				} else {
				$paragraph = stripslashes($ir[0]);
				}
				
				$paragraph = str_replace("&nbsp;", " ", $paragraph);
				$paragraph = str_replace("&amp;", "&", $paragraph);
				$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));
				$pdf->SetLeftMargin('10');
				$pdf->SetFont('','',8);;
				
				$display_cost = 'Included';
				//if ($displayInc === true) {

				$pdf->Cell(160.05,4,"  - ".$paragraph,'LR',0,'L',true);
				$pdf->SetFont('','',8);;
				$pdf->Cell(9.95,4,$ir[1],'LR',0,'C',true);
				$pdf->Cell(19.95,4,$display_cost,'LR',0,'R',true);
				
				$pdf->Ln(3.6); 
				//}
			}
		}
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
											   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type='Package'");
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
		$paragraph = str_replace("&amp;", "&", $paragraph);
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
		
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
		
		// Don't Know yet
		
		if($total_iw_cost != 0 && $displayitem==0) {

			$x = $pdf->GetX();
			$y = $pdf->GetY();
					
			$pdf->SetLeftMargin('10');
			$pdf->SetFont('','',8);;
			
			$display_cost = '€ '.number_format($line_iw_euro,2);
			$display_cost = eregi_replace("€ -","- € ",$display_cost);
			$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
			
			$pdf->SetFont('','',8);;
			
			$y2 = $pdf->GetY();
			
			if ($y > $breakheight) {
				$y=	10;
			}
			
			$CellHeight	=	($y2 - $y);
			$LineHeight	=	($y2 - $y) - $CellHeight;
			
			$pdf->SetXY($x + 160.05, $y);
			$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
			
			$pdf->SetXY($x + 160.05 + 9.95, $y);
			$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
			$pdf->Ln($LineHeight);
		
			$include_check = $sql_command->select($database_order_history,"name,qty,order_id,type_id",
												   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
												   and order_id='".addslashes($record[20])."'
												   and type='Included'");
			$include_results = $sql_command->results($include_check);
			
			foreach ($include_results as $ir) {
				$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($ir[3])."' AND note_type = 'Hide' AND extra = 'Yes'");
				if ($displayitem==0) {
					if(eregi("<p>",$ir[0])) {
					$start = strpos($ir[0], '<p>');
					$end = strpos($ir[0], '</p>', $start);
					$paragraph = substr($ir[0], $start, $end-$start+4);
					$paragraph = str_replace("<p>", "", $paragraph);
					$paragraph = str_replace("</p>", "", $paragraph);
					} else {
					$paragraph = stripslashes($ir[0]);
					}
					
					$paragraph = str_replace("&nbsp;", " ", $paragraph);
					$paragraph = str_replace("&amp;", "&", $paragraph);
					$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));
					$pdf->SetLeftMargin('10');
					$pdf->SetFont('','',8);;
					
					$display_cost = 'Included';
					//if ($displayInc === true) {
	
					$pdf->Cell(160.05,4,"  - ".$paragraph,'LR',0,'L',true);
					$pdf->SetFont('','',8);;
					$pdf->Cell(9.95,4,$ir[1],'LR',0,'C',true);
					$pdf->Cell(19.95,4,$display_cost,'LR',0,'R',true);
					
					$pdf->Ln(3.6); 
					//}

				}
			}
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
										   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type!='Package' and type='Extra'");
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
	$paragraph = str_replace("&amp;", "&", $paragraph);
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
		
		// Package Extra / Bespoke Extra
	
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
//		if ($the_username =="u2") { 
			if($displayitem==0) {
				
				$x = $pdf->GetX();
				$y = $pdf->GetY();

				$pdf->SetLeftMargin('10');
				$pdf->SetFont('','',8);;
				
				$display_cost = '€ '.number_format($line_iw_euro,2);
				$display_cost = eregi_replace("€ -","- € ",$display_cost);
				$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
				
				$pdf->SetFont('','',8);;
				
				$y2 = $pdf->GetY();
				
				if ($y > $breakheight) {
					$y=	10;
				}
				
				$CellHeight	=	($y2 - $y);
				$LineHeight	=	($y2 - $y) - $CellHeight;
				
				$pdf->SetXY($x + 160.05, $y);
				$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
				
				$pdf->SetXY($x + 160.05 + 9.95, $y);
				$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
				$pdf->Ln($LineHeight);
			}
//		} else {
//			if($total_iw_cost != 0 && $displayitem==0) {
//				
//				$x = $pdf->GetX();
//				$y = $pdf->GetY();
//
//				$pdf->SetLeftMargin('10');
//				$pdf->SetFont('','B',8);;
//				
//				$display_cost = '€ '.number_format($line_iw_euro,2);
//				$display_cost = eregi_replace("€ -","- € ",$display_cost);
//				$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
//				
//				$pdf->SetFont('','',8);;
//				
//				$y2 = $pdf->GetY();
//				
//				$CellHeight	=	($y2 - $y);
//				$LineHeight	=	($y2 - $y) - $CellHeight;
//				
//				$pdf->SetXY($x + 160.05, $y);
//				$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
//				
//				$pdf->SetXY($x + 160.05 + 9.95, $y);
//				$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
//				$pdf->Ln($LineHeight);
//			}
//			if($total_iw_cost != 0 && $displayitem==0) {
//				$pdf->SetLeftMargin('10');
//				$pdf->SetFont('','B',8);;
//				
//				$display_cost = '€ '.number_format($line_iw_euro,2);
//				$display_cost = eregi_replace("€ -","- € ",$display_cost);
//				
//				$pdf->Cell(160.05,4,$paragraph,'LR',0,'L',true);
//				$pdf->SetFont('','',8);;
//				$pdf->Cell(9.95,4,$invoice_record[1],'LR',0,'C',true);
//				$pdf->Cell(19.95,4,$display_cost,'LR',0,'R',true);
//				
//				$pdf->Ln(3.6);
//			}
//		}
	}
	
	
	//if($total_invoice > 0) {
	$pdf->Ln(0.4);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	
	$outstanding_euros = $total_payment_euro;
	$outstanding_euros_before = $total_payment_euro_before;
	$euro_discount = $outstanding_euros_before - $outstanding_euros;
	
	$pdf->Cell(170,5,"Amount in Euros",'LR',0,'L',true);
	$pdf->SetFont('','',8);;
	$pdf->Cell(19.95,5,"€ ".number_format($outstanding_euros_before,2),'LR',0,'R',true);
	$pdf->Ln(5.1);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	if($euro_discount != 0) {
	$pdf->Cell(170,5,"Discount in Euros",'LR',0,'L',true);
	$pdf->SetFont('','',8);;
	$pdf->Cell(19.95,5,"€ ".number_format($euro_discount,2),'LR',0,'R',true);
	$pdf->Ln(5.1);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	//}
	
	$minum_deposit = 0;
	$minum_deposit2 = 0;
	
	if($package_exists == "Yes") {
		
		$deposit_result = $sql_command->select($database_invoice_history,"name,
											   qty,
											   cost,
											   iw_cost,
											   currency,
											   timestamp,
											   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Euro'");
		$deposit_row = $sql_command->results($deposit_result);
	
		foreach($deposit_row as $deposit_record) {
		
			$total_deposit_paid = eregi_replace(",","",number_format($deposit_record[3] / $deposit_record[6],2));
		
			$pdf->Ln(0.1);
			
			$pdf->Cell(170,5,$deposit_record[0]." Paid",'LR',0,'L',true);
			$pdf->SetFont('','',8);;
			$pdf->Cell(19.95,5,"- € ".number_format($deposit_record[3],2),'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
			$minum_deposit = $minum_deposit + $total_deposit_paid;
			$minum_deposit2 = $deposit_record[3];
		}
	}		
}
		// start payments

if ($minum_deposit>0) {
		$invoice_result = $sql_command->select($database_invoice_history,
											   "invoice_id",
											   "WHERE order_id='".addslashes($recorda[0])."' 
											   and item_type='Deposit' and status='Paid'");
		$invoice_record = $sql_command->result($invoice_result);
		
		$dresp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount),customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
 							 AND customer_payments.payment_currency = 'Euro'
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
						 AND customer_payments.payment_currency = 'Euro'
						 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."'");
	$respr = $sql_command->result($resp);
	$payDates = $respr[1];
	$totalpp = $respr[0];
	$format_gbp = "£ ".number_format($totalpp,2);
}

// end payments

	$outstanding_euros_before -= $totalpp;
	$outstanding_euros -=  $totalpp;
	if($minum_deposit2 > 0 or $euro_discount != 0) {
	$pdf->Cell(170,5,"Total in Euros",'LR',0,'L',true);
	$pdf->SetFont('','',8);;
	$pdf->Cell(19.95,5,"€ ".number_format($outstanding_euros_before - $minum_deposit2 - $euro_discount,2),'LR',0,'R',true);
	$pdf->Ln(5.1);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	}
	
if ($totalpp>0) {
	$resp = $sql_command->select("customer_payments,customer_transactions",
						 "customer_payments.pay_no,customer_payments.p_amount, customer_transactions.cardtype, customer_transactions.timestamp",
						 "WHERE customer_transactions.p_id = customer_payments.pay_no 
						 AND customer_transactions.status = 'Paid'
						 AND customer_payments.status != 'Unpaid'
						 AND customer_payments.payment_currency = 'Euro'
						 AND customer_payments.invoice_id = '".addslashes($_GET['invoice'])."'");
	$respr = $sql_command->results($resp);
	$payids = 	"";
	list($payDate,$payTime) = explode(" ",$payDates);
	list($yy,$mm,$dd) = explode("-",$payDate);
	$payDate = $dd."/".$mm."/".$yy;
	foreach($respr as $pids) {
		$pamount = $pids[1];
		$payids = $pids[0];	
		$paytype = $pids[2];
		$UnixPayDate = date("d-m-Y", strtotime($pids[3]));
		if ($pamount>0) {
			$paymentpamount	=	str_replace("-","",number_format($pamount,2));
			$PaymentLabel	=	"1 Part Payment ID";
			$PaymentSymbol	=	"- ";
		} else {
			$paymentpamount	=	str_replace("-","",number_format($pamount,2));
			$PaymentLabel	=	"Part Refund ID";
			$PaymentSymbol	=	"";
		}
		$pdf->Ln(0.1);
		
		$pdf->Cell(170,5,$PaymentLabel." - ".$payids." (".$paytype." ".$UnixPayDate.")",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,$PaymentSymbol."€ ".$paymentpamount,'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	}
}

$totalpp = 0;



$outstanding_pounds = (empty($currency_name)) ? $outstanding_euros / $record[22] : $outstanding_euros;
$outstanding_pounds_before = (empty($currency_name)) ? $outstanding_euros_before / $record[22] : $outstanding_euros;


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
										   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type!='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	
	foreach($invoice_row as $invoice_record) {
	$show_additional = "Yes";
	}
	
	$checktotal = $outstanding_pounds - $minum_deposit;
	if($checktotal > 0) {
	$pdf->Cell(170,5,"Amount Outstanding in GBP",'LR',0,'L',true);
	$pdf->SetFont('','',8);;
	$pdf->Cell(19.95,5,"£ ".number_format($outstanding_pounds - $minum_deposit,2),'LR',0,'R',true);
	$pdf->Ln(5.1);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);	
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
	$paragraph = str_replace("&amp;", "&", $paragraph);
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
		
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
	
		if($total_iw_cost != 0 && $displayitem==0) {

			$x = $pdf->GetX();
			$y = $pdf->GetY();
					
			$pdf->SetLeftMargin('10');
			$pdf->SetFont('','',8);;
			
			$display_cost = '£ '.number_format($line_iw_euro,2);
			$display_cost = eregi_replace("£ -","- £ ",$display_cost);
			$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
			
			$pdf->SetFont('','',8);;
			
			$y2 = $pdf->GetY();
			
			if ($y > $breakheight) {
				$y=	10;
			}
			
			$CellHeight	=	($y2 - $y);
			$LineHeight	=	($y2 - $y) - $CellHeight;
			
			$pdf->SetXY($x + 160.05, $y);
			$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
			
			$pdf->SetXY($x + 160.05 + 9.95, $y);
			$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
			$pdf->Ln($LineHeight);
		}
	}
	if($checktotal > 0 and $total_additional > 0) {
	$pdf->Ln(0.4);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	
	$pdf->Cell(170,5,"Additional Amount in GBP",'LR',0,'L',true);
	$pdf->SetFont('','',8);;
	$pdf->Cell(19.95,5,"£ ".number_format($total_additional,2),'LR',0,'R',true);
	$pdf->Ln(5.1);
	$pdf->Cell(189.95,0,'','T'); 
	$pdf->Ln(0.1);
	}
	
	
	
	if($package_exists == "Yes") {
		$deposit_result = $sql_command->select($database_invoice_history,"name,
											   qty,
											   cost,
											   iw_cost,
											   currency,
											   timestamp,
											   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Pound'");
		$deposit_row = $sql_command->results($deposit_result);
		
		foreach($deposit_row as $deposit_record) {
			
			$total_deposit_paid = eregi_replace(",","",$deposit_record[3]);
			
			$pdf->Ln(0.1);
			
			$pdf->Cell(170,5,$deposit_record[0]." Paid",'LR',0,'L',true);
			$pdf->SetFont('','',8);;
			$pdf->Cell(19.95,5,"- £ ".number_format($deposit_record[3],2),'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
			$minum_deposit = $minum_deposit + $total_deposit_paid;
		}
	}


$acro = (empty($currency_name)) ? "GBP" : $currency_i[0];
$symbol = (empty($currency_name)) ? "£" : $currency_i[1]; 

// start payments

$result = $sql_command->select("$database_customer_invoices","customer_invoices.order_id,customer_invoices.type",
							   "WHERE $database_customer_invoices.id='".addslashes($_GET["invoice"])."'
							   AND $database_customer_invoices.status != 'Paid'
							   ORDER BY $database_customer_invoices.timestamp DESC");
$recorda = $sql_command->result($result);

if (count($recorda)>0) {
// start payments

	if ($minum_deposit>0) {
			$invoice_result = $sql_command->select($database_invoice_history,
												   "invoice_id",
												   "WHERE order_id='".addslashes($recorda[0])."' 
												   and item_type='Deposit' and status='Paid'");
			$invoice_record = $sql_command->result($invoice_result);
			
			$dresp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount),customer_transactions.timestamp",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Pound'
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
							 AND customer_payments.payment_currency = 'Pound'
							 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."'");
		$respr = $sql_command->result($resp);
		$payDates = $respr[1];
		$totalpp = $respr[0];
		$format_gbp = "£ ".number_format($totalpp,2);
	}
	if ($totalpp>0) {
		$resp = $sql_command->select("customer_payments,customer_transactions",
							 "customer_payments.pay_no,customer_payments.p_amount, customer_transactions.cardtype, customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.payment_currency = 'Pound'
							 AND customer_payments.invoice_id = '".addslashes($_GET['invoice'])."'");
		$respr = $sql_command->results($resp);
		$payids = 	"";
		
		list($payDate,$payTime) = explode(" ",$payDates);
		
		list($yy,$mm,$dd) = explode("-",$payDate);
		
		$payDate = $dd."/".$mm."/".$yy;
		
		foreach($respr as $pids) {
			$pamount = $pids[1];
			$payids = $pids[0];	
			$paytype = $pids[2];
			$UnixPayDate = date("d-m-Y", strtotime($pids[3]));
			if ($pamount>0) {
				$paymentpamount	=	str_replace("-","",number_format($pamount,2));
				$PaymentLabel	=	"Part Payment ID";
				$PaymentSymbol	=	"- ";
			} else {
				$paymentpamount	=	str_replace("-","",number_format($pamount,2));
				$PaymentLabel	=	"Part Refund ID";
				$PaymentSymbol	=	"";
			}
			$pdf->Ln(0.1);
			
			$pdf->Cell(170,5,$PaymentLabel." - ".$payids." (".$paytype." ".$UnixPayDate.")",'LR',0,'L',true);
			$pdf->SetFont('','',8);;
			$pdf->Cell(19.95,5,$PaymentSymbol."£ ".$paymentpamount,'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
		}
	}

}

// end payments


$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);

if($amount_discount != 0) {
$total_gbp = round(($outstanding_pounds_before + $total_payment_pound_before - $minum_deposit), 2);

$pdf->Ln(0.1);
$pdf->Cell(170,5,"TOTAL in GBP",'LR',0,'L',true);
$pdf->Cell(19.95,5,"£ ".number_format($total_gbp,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
$pdf->Cell(170,5,"Discount in GBP",'LR',0,'L',true);
$pdf->SetFont('','',8);;
$pdf->Cell(19.95,5,"£ ".number_format($amount_discount,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);
} else {
$discount_amount_calc = 0;
}


$pdf->SetFont('','B',8);;
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(251,212,180);
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1); 

$total_gbp = round(($outstanding_pounds + $total_payment_pound - $minum_deposit -$totalpp), 2);

$pdf->Ln(0.1);
$pdf->Cell(170,5,"TOTAL Payable in ".$acro,'LR',0,'R',true);
$pdf->Cell(19.95,5,$symbol." ".number_format($total_gbp,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


$pdf->Ln(2);
	
	$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate,reception_id,ceremony_id","WHERE id='".$record[20] ."'");
	$package_exchange_record = $sql_command->result($package_exchange_result);
	
	if($package_exchange_record[0] < 1) {
		$package_exchange_record[0] = 1;
	}

	$ceremony_result = $sql_command->select($database_ceremonies,"notes","WHERE id='".addslashes($package_exchange_record[2])."' ORDER BY ceremony_name");
	$ceremony_record = $sql_command->result($ceremony_result);
		
	if ($ceremony_record[0] != "") {
		$pdf->SetFont('','',8);
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
		
		$pdf->MultiCell(190.05, 3, "Ceremony Venue Note", 'LR', 'L', false);
		$pdf->Ln(2);
		
		$c_filter = array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
		$c_note = str_replace($c_filter,"",$ceremony_record[0]);
		$c_note = str_replace("<p> ","<p>",$c_note);
		$c_note = str_replace("<li>","<p>• ",$c_note);
		$c_note = str_replace("</li>","</p>",$c_note);
		$c_note = preg_replace('~[\r\n]+~', '', $c_note);
		$c_note = str_replace("&nbsp;", " ", $c_note);
		$c_note = trim(preg_replace('/\s\s+/', ' ', $c_note));
		
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
	
		$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $c_note, $matches);
	//	$bullet_list = array();
		$filter_c = array("<p>","</p>");
		for ($i = 0; $i < $count; ++$i) {
			//$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
			$pdf->MultiCell(190.05, 3, str_replace($filter_c,"",$matches[0][$i]), 'LR', 'L', false);
			$pdf->Ln(2);
			//$bullet_list[] = $matches[0][$i];
		}
		
//		$pdf->SetFillColor(255,255,255);
//		$pdf->SetDrawColor(255,255,255);
//		$pdf->Cell(190,5,$c_note.$count,'LR',0,'L',true);
	}
	
	$pdf->Ln(2);
	
	$venue_result = $sql_command->select($database_venue_names,"notes","WHERE id='".addslashes($package_exchange_record[1])."' ORDER BY venue_name");
	$venue_record = $sql_command->result($venue_result);
	
	if ($venue_record[0] != "") {
		$pdf->SetFont('','',8);
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
		
		$pdf->MultiCell(190.05, 3, "Reception Venue Note", 'LR', 'L', false);
		$pdf->Ln(2);
		
		$v_filter= array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
		$v_note = str_replace($v_filter,"",$venue_record[0]);
		$v_note = str_replace("<p> ","<p>",$v_note);
		$v_note = str_replace("<li>","<p>• ",$v_note);
		$v_note = str_replace("</li>","</p>",$v_note);
		$v_note = preg_replace('~[\r\n]+~', '', $v_note);
		$v_note = str_replace("&nbsp;", " ", $v_note);
		$v_note = trim(preg_replace('/\s\s+/', ' ', $v_note));
		
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
	
		$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $v_note, $matches);
	//	$bullet_list = array();
		$filter_v = array("<p>","</p>");
		for ($i = 0; $i < $count; ++$i) {
			//$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
			$pdf->MultiCell(190.05, 3, str_replace($filter_v,"",$matches[0][$i]), 'LR', 'L', false);
			$pdf->Ln(2);
			//$bullet_list[] = $matches[0][$i];
		}
		
//		$pdf->SetFillColor(255,255,255);
//		$pdf->SetDrawColor(255,255,255);
//		$pdf->Cell(190,5,$v_note.$count,'LR',0,'L',true);
	}
	
	$pdf->Ln(2);

$comments_q = $sql_command->select("notes","note","WHERE note_primary_reference = '".addslashes($record[20])."' AND note_secondary_reference = '".addslashes($_GET['invoice'])."' AND note_type = 'InvoiceComment' AND extra = 'Yes'");
$comments_r = $sql_command->result($comments_q);
if ($comments_r) {
	$pdf->SetFont('','',8);
	$pdf->SetTextColor(88,88,111);
	$pdf->SetFillColor(255,255,255);  
	$pdf->SetDrawColor(255,255,255); 
		
	$pdf->MultiCell(190.05, 3, "Please Note", 'LR', 'L', false);
	$pdf->Ln(2);
		
	$p_filter= array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
	$p_note = str_replace($p_filter,"",$comments_r[0]);
	$p_note = str_replace("<p> ","<p>",$p_note);
	$p_note = str_replace("<li>","<p> • ",$p_note);
	$p_note = str_replace("</li>","</p>",$p_note);
	$p_note = preg_replace('~[\r\n]+~', '', $p_note);
	$p_note = str_replace("&nbsp;", " ", $p_note);
	$p_note = str_replace("&pound;", " £", $p_note);
	$p_note = str_replace("&euro;", " €", $p_note);
	$p_note = trim(preg_replace('/\s\s+/', ' ', $p_note));
	
	$pdf->SetFont('','',8);;
	$pdf->SetTextColor(88,88,111);
	$pdf->SetFillColor(255,255,255);  
	$pdf->SetDrawColor(255,255,255); 

	$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $p_note, $matches);
//	$bullet_list = array();
	$filter_p = array("<p>","</p>");
	for ($i = 0; $i < $count; ++$i) {
		$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
		$pdf->Ln(4);
		//$bullet_list[] = $matches[0][$i];
	}
	
//	$pdf->SetFillColor(255,255,255);
//	$pdf->SetDrawColor(255,255,255);
//	$pdf->Cell(190,5,$p_note.$count,'LR',0,'L',true);
}
}























// non-uk

else {
	
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
										   item_type,order_id,type_id",
										   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
										   and order_id='".addslashes($record[20])."' 
										   and currency='Euro' and item_type='Package'");
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
	$paragraph = str_replace("&amp;", "&", $paragraph);
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
	
	$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
	
	if($total_iw_cost != 0 && $displayitem==0) {

		$x = $pdf->GetX();
		$y = $pdf->GetY();
				
		$pdf->SetLeftMargin('10');
		$pdf->SetFont('','',8);;
		
		$display_cost = '€ '.number_format($line_iw_euro,2);
		$display_cost = eregi_replace("€ -","- € ",$display_cost);
		$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
		
		$pdf->SetFont('','',8);;
		
		$y2 = $pdf->GetY();
		
		if ($y > $breakheight) {
			$y=	10;
		}
		
		$CellHeight	=	($y2 - $y);
		$LineHeight	=	($y2 - $y) - $CellHeight;
		
		$pdf->SetXY($x + 160.05, $y);
		$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
		
		$pdf->SetXY($x + 160.05 + 9.95, $y);
		$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
		$pdf->Ln($LineHeight);
		
		$include_check = $sql_command->select($database_order_history,"name,qty,order_id,type_id",
											   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
											   and order_id='".addslashes($record[20])."'
											   and type='Included'");
		$include_results = $sql_command->results($include_check);
		
		foreach ($include_results as $ir) {
			$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($ir[3])."' AND note_type = 'Hide' AND extra = 'Yes'");
			if ($displayitem==0) {
				if(eregi("<p>",$ir[0])) {
				$start = strpos($ir[0], '<p>');
				$end = strpos($ir[0], '</p>', $start);
				$paragraph = substr($ir[0], $start, $end-$start+4);
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				} else {
				$paragraph = stripslashes($ir[0]);
				}
				
				$paragraph = str_replace("&nbsp;", " ", $paragraph);
				$paragraph = str_replace("&amp;", "&", $paragraph);
				$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));
				$pdf->SetLeftMargin('10');
				$pdf->SetFont('','',8);;
				
				$display_cost = 'Included';
				//if ($displayInc === true) {

				$pdf->Cell(160.05,4,"  - ".$paragraph,'LR',0,'L',true);
				$pdf->SetFont('','',8);;
				$pdf->Cell(9.95,4,$ir[1],'LR',0,'C',true);
				$pdf->Cell(19.95,4,$display_cost,'LR',0,'R',true);
				
				$pdf->Ln(3.6); 
				//}
			}
		}
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
										   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Euro' and item_type!='Package'");
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
	$paragraph = str_replace("&amp;", "&", $paragraph);
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
	
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
	
		if($total_iw_cost != 0 && $displayitem==0) {

			$x = $pdf->GetX();
			$y = $pdf->GetY();
					
			$pdf->SetLeftMargin('10');
			$pdf->SetFont('','',8);;
			
			$display_cost = '€ '.number_format($line_iw_euro,2);
			$display_cost = eregi_replace("€ -","- € ",$display_cost);
			$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
			
			$pdf->SetFont('','',8);;
			
			$y2 = $pdf->GetY();
			
			if ($y > $breakheight) {
				$y=	10;
			}
			
			$CellHeight	=	($y2 - $y);
			$LineHeight	=	($y2 - $y) - $CellHeight;
			
			$pdf->SetXY($x + 160.05, $y);
			$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
			
			$pdf->SetXY($x + 160.05 + 9.95, $y);
			$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
			$pdf->Ln($LineHeight);

		}
	}
	
	
	if($total_invoice > 0) {
		$pdf->Ln(0.4);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
		
		$outstanding_euros = $total_payment_euro;
		$outstanding_euros_before = $total_payment_euro_before;
		$euro_discount = $outstanding_euros_before - $outstanding_euros;
	
		$pdf->Cell(170,5,"Amount in Euros",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"€ ".number_format($outstanding_euros_before,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);

		if($euro_discount != 0) {
			$pdf->Cell(170,5,"Discount in Euros",'LR',0,'L',true);
			$pdf->SetFont('','',8);;
			$pdf->Cell(19.95,5,"€ ".number_format($euro_discount,2),'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
		}
	
		$minum_deposit = 0;
		$minum_deposit2 = 0;
	
		if($package_exists == "Yes") {
			$deposit_result = $sql_command->select($database_invoice_history,"name,
												   qty,
												   cost,
												   iw_cost,
												   currency,
												   timestamp,
										   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Euro'");
			$deposit_row = $sql_command->results($deposit_result);
		
			if ($deposit_row) {
				$total_deposit_paid = eregi_replace(",","",number_format($deposit_record[3] / $deposit_record[6],2));
				
				$pdf->Ln(0.1);
				
				$pdf->Cell(170,5,$deposit_record[0]." Paid",'LR',0,'L',true);
				$pdf->SetFont('','',8);;
				$pdf->Cell(19.95,5,"- € ".number_format($deposit_record[3],2),'LR',0,'R',true);
				$pdf->Ln(5.1);
				$pdf->Cell(189.95,0,'','T'); 
				$pdf->Ln(0.1);
				$minum_deposit = $minum_deposit + $total_deposit_paid;
				$minum_deposit2 = $deposit_record[3];
			}
		}
	}
	
	
	if($minum_deposit2 > 0 or $euro_discount != 0) {
		$pdf->Cell(170,5,"Total in Euros",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"€ ".number_format($outstanding_euros_before - $minum_deposit2 - $euro_discount,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	}
	// start payments
	
	if ($minum_deposit>0) {
			$invoice_result = $sql_command->select($database_invoice_history,
												   "invoice_id",
												   "WHERE order_id='".addslashes($recorda[0])."' 
												   and item_type='Deposit' and status='Paid'");
			$invoice_record = $sql_command->result($invoice_result);
			
			$dresp = $sql_command->select("customer_payments,customer_transactions",
								 "sum(customer_payments.p_amount),customer_transactions.timestamp",
								 "WHERE customer_transactions.p_id = customer_payments.pay_no 
								 AND customer_transactions.status = 'Paid'
								 AND customer_payments.status != 'Unpaid'
								 AND customer_payments.payment_currency = 'Euro'
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
							 AND customer_payments.payment_currency = 'Euro'
							 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."'");
		$respr = $sql_command->result($resp);
		$payDates = $respr[1];
		$totalpp = $respr[0];
		$format_gbp = "£ ".number_format($totalpp,2);
	}
	if ($totalpp>0) {
		$resp = $sql_command->select("customer_payments,customer_transactions",
							 "customer_payments.pay_no,customer_payments.p_amount, customer_transactions.cardtype, customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.payment_currency = 'Euro'
							 AND customer_payments.invoice_id = '".addslashes($_GET['invoice'])."'");
		$respr = $sql_command->results($resp);
		$payids = 	"";
		list($payDate,$payTime) = explode(" ",$payDates);
		list($yy,$mm,$dd) = explode("-",$payDate);
		$payDate = $dd."/".$mm."/".$yy;
		foreach($respr as $pids) {
			$pamount = $pids[1];
			$payids = $pids[0];	
			$paytype = $pids[2];
			$UnixPayDate = date("d-m-Y", strtotime($pids[3]));
			if ($pamount>0) {
				$paymentpamount	=	str_replace("-","",number_format($pamount,2));
				$PaymentLabel	=	"3 Part Payment ID";
				$PaymentSymbol	=	"- ";
			} else {
				$paymentpamount	=	str_replace("-","",number_format($pamount,2));
				$PaymentLabel	=	"Part Refund ID";
				$PaymentSymbol	=	"";
			}
			$pdf->Ln(0.1);
			
			$pdf->Cell(170,5,$PaymentLabel." - ".$payids." (".$paytype." ".$UnixPayDate.")",'LR',0,'L',true);
			$pdf->SetFont('','',8);;
			$pdf->Cell(19.95,5,$PaymentSymbol."€ ".$paymentpamount,'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
		}
	}
	$outstanding_euros -= $totalpp;
	$outstanding_euros_before -= $totalpp;
	$totalpp= 0;
	
	// end payments
	
	$outstanding_pounds = (empty($currency_name)) ? $outstanding_euros / $record[22] : $outstanding_euros;
	$outstanding_pounds_before = (empty($currency_name)) ? $outstanding_euros_before / $record[22] : $outstanding_euros;

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
										   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	
	
	$show_additional = "No";
	$total_payment = 0;
	$iItemCount	= 0;
	foreach($invoice_row as $invoice_record) {
		
		$iItemCount	= $iItemCount + 1;
		
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
		$paragraph = str_replace("&amp;", "&", $paragraph);
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
	
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
	
	
		if($total_iw_cost != 0 && $displayitem==0) {

		$x = $pdf->GetX();
		$y = $pdf->GetY();
				
		$pdf->SetLeftMargin('10');
		$pdf->SetFont('','',8);;
		
		$display_cost = '€ '.number_format($line_iw_euro,2);
		$display_cost = eregi_replace("€ -","- € ",$display_cost);
		$pdf->MultiCell(160.05, 3, $paragraph, 'LR', 'L', false);
		
		$pdf->SetFont('','',8);;
		
		$y2 = $pdf->GetY();
		
		if ($y > $breakheight) {
			$y=	10;
		}
		
		$CellHeight	=	($y2 - $y);
		$LineHeight	=	($y2 - $y) - $CellHeight;
		
		$pdf->SetXY($x + 160.05, $y);
		$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
		
		$pdf->SetXY($x + 160.05 + 9.95, $y);
		$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
		$pdf->Ln($LineHeight);

		$include_check = $sql_command->select($database_order_history,"name,qty,order_id,type_id",
												   "WHERE invoice_id='".addslashes($_GET["invoice"])."' 
												   and order_id='".addslashes($record[20])."'
												   and type='Included'");
		$include_results = $sql_command->results($include_check);
		
		foreach ($include_results as $ir) {
			$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($ir[3])."' AND note_type = 'Hide' AND extra = 'Yes'");
			if ($displayitem==0) {
				if(eregi("<p>",$ir[0])) {
					$start = strpos($ir[0], '<p>');
					$end = strpos($ir[0], '</p>', $start);
					$paragraph = substr($ir[0], $start, $end-$start+4);
					$paragraph = str_replace("<p>", "", $paragraph);
					$paragraph = str_replace("</p>", "", $paragraph);
				} else {
					$paragraph = stripslashes($ir[0]);
				}
				
				$paragraph = str_replace("&nbsp;", " ", $paragraph);
				$paragraph = str_replace("&amp;", "&", $paragraph);
				$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));
				$pdf->SetLeftMargin('10');
				$pdf->SetFont('','',8);;
				
				$display_cost = 'Included';
				//if ($displayInc === true) {

				$pdf->Cell(160.05,4,"  - ".$paragraph,'LR',0,'L',true);
				$pdf->SetFont('','',8);;
				$pdf->Cell(9.95,4,$ir[1],'LR',0,'C',true);
				$pdf->Cell(19.95,4,$display_cost,'LR',0,'R',true);
				
				$pdf->Ln(3.6); 
				//}
			}
		}
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
										   item_type,order_id,type_id","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."' and currency='Pound' and item_type!='Package'");
	$invoice_row = $sql_command->results($invoice_result);
	
	foreach($invoice_row as $invoice_record) {
		$show_additional = "Yes";
	}
	
	$checktotal = $outstanding_pounds - $minum_deposit;
	
	if ($total_payment_euro_before	> 0) {
	//if($checktotal > 0) {
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(0,0,0); 
		$pdf->SetFillColor(251,212,180);
		$pdf->SetDrawColor(226,179,64);  
		$pdf->SetLineWidth(0.1); 
		
		$total_gbp = round(( $outstanding_pounds + $total_payment_pound - $minum_deposit - $totalpp), 2);
		$totalpp = 0;
	
		//if ($outstanding_pounds>0) { 
			$pdf->Ln(0.1);
			$pdf->Cell(170,5,"TOTAL Payable in EUR ",'LR',0,'R',true);
			$pdf->Cell(19.95,5,"€ ".number_format($total_gbp,2),'LR',0,'R',true);
			$pdf->Ln(5.1);
			$pdf->Cell(189.95,0,'','T'); 
			$pdf->Ln(0.1);
		//}

	}
	
	$total_gbp = $outstanding_pounds = $total_payment_pound = $minum_deposit = $totalpp = 0;
	
	$pdf->SetTextColor(88,88,111);
	$pdf->SetFillColor(255,255,255);  
	$pdf->SetDrawColor(226,179,64);  
	$pdf->SetLineWidth(0.1); 
	
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
	$paragraph = str_replace("&amp;", "&", $paragraph);
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
		
		$displayitem = $sql_command->count_nrow("notes","notes_id","note_primary_reference = '".addslashes($invoice_record[15])."' AND note_secondary_reference = '".addslashes($invoice_record[16])."' AND note_type = 'Hide' AND extra = 'Yes'");
		
	
		if($total_iw_cost != 0 && $displayitem==0) {

			$x = $pdf->GetX();
			$y = $pdf->GetY();
					
			$pdf->SetLeftMargin('10');
			$pdf->SetFont('','',8);;
			
			$display_cost = '€ '.number_format($line_iw_euro,2);
			$display_cost = eregi_replace("€ -","- € ",$display_cost);
			$pdf->MultiCell(160.05, 3,$paragraph, 'LR', 'L', false);
			
			$pdf->SetFont('','',8);;
			
			$y2 = $pdf->GetY();
			
			if ($y > $breakheight) {
				$y=	10;
			}
			
			$CellHeight	=	($y2 - $y);
			$LineHeight	=	($y2 - $y) - $CellHeight;
			
			$pdf->SetXY($x + 160.05, $y);
			$pdf->MultiCell(9.95,$CellHeight,$invoice_record[1], 'LR', 'C', true);
			
			$pdf->SetXY($x + 160.05 + 9.95, $y);
			$pdf->MultiCell(19.95,$CellHeight,$display_cost, 'LR', 'R', true);
			$pdf->Ln($LineHeight);

		}
	}
	if($checktotal > 0 and $total_additional > 0) {
		$pdf->Ln(0.4);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
		
		$pdf->Cell(170,5,"Amount in GBP",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"£ ".number_format($total_additional,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	}
	
	
	
	if($package_exists == "Yes") {
	$deposit_result = $sql_command->select($database_invoice_history,"name,
										   qty,
										   cost,
										   iw_cost,
										   currency,
										   timestamp,
										   exchange_rate","WHERE order_id='".addslashes($record[20])."' and item_type='Deposit' and status='Paid' and currency='Pound'");
	$deposit_row = $sql_command->results($deposit_result);
	
	foreach($deposit_row as $deposit_record) {
		
		$total_deposit_paid = eregi_replace(",","",$deposit_record[3]);
		
		$pdf->Ln(0.1);
		
		$pdf->Cell(170,5,$deposit_record[0]." Paid",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"- £ ".number_format($deposit_record[3],2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
		$minum_deposit = $minum_deposit + $total_deposit_paid;
	}
}


// start payments

if ($minum_deposit>0) {
		$invoice_result = $sql_command->select($database_invoice_history,"invoice_id","WHERE order_id='".addslashes($recorda[0])."' and item_type='Deposit' and status='Paid'");
		$invoice_record = $sql_command->result($invoice_result);
		$dresp = $sql_command->select("customer_payments,customer_transactions",
							 "sum(customer_payments.p_amount),customer_transactions.timestamp",
							 "WHERE customer_transactions.p_id = customer_payments.pay_no 
							 AND customer_transactions.status = 'Paid'
							 AND customer_payments.status != 'Unpaid'
							 AND customer_payments.payment_currency = 'Pound'
							 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."' 
							 AND customer_payments.invoice_id != '".addslashes($invoice_record[0])."'");
		$drespr = $sql_command->result($dresp);
		$payDates = $drespr[1];
		$totalpp = $drespr[0];
		$format_gbp = "£ ".number_format($totalpp,2);
} else {
	$resp = $sql_command->select("customer_payments,customer_transactions",
						 "sum(customer_payments.p_amount),customer_transactions.timestamp",
						 "WHERE customer_transactions.p_id = customer_payments.pay_no 
						 AND customer_transactions.status = 'Paid'
						 AND customer_payments.payment_currency = 'Pound'
						 AND customer_payments.status != 'Unpaid'
						 AND customer_payments.invoice_id = '".addslashes($_GET["invoice"])."'");
	$respr = $sql_command->result($resp);
	$payDates = $respr[1];
	$totalpp = $respr[0];
	$format_gbp = "£ ".number_format($totalpp,2);
}
if ($totalpp>0) {
	$resp = $sql_command->select("customer_payments,customer_transactions",
						 "customer_payments.pay_no,customer_payments.p_amount, customer_transactions.cardtype, customer_transactions.timestamp",
						 "WHERE customer_transactions.p_id = customer_payments.pay_no 
						 AND customer_transactions.status = 'Paid'
						 AND customer_payments.status != 'Unpaid'
						 AND customer_payments.payment_currency = 'Pound'
						 AND customer_payments.invoice_id = '".addslashes($_GET['invoice'])."'");
	$respr = $sql_command->results($resp);
	$payids = 	"";
	list($payDate,$payTime) = explode(" ",$payDates);
	list($yy,$mm,$dd) = explode("-",$payDate);
	$payDate = $dd."/".$mm."/".$yy;
	foreach($respr as $pids) {
		$pamount = $pids[1];
		$payids = $pids[0];	
		$paytype = $pids[2];
		$UnixPayDate = date("d-m-Y", strtotime($pids[3]));
		if ($pamount>0) {
			$paymentpamount	=	str_replace("-","",number_format($pamount,2));
			$PaymentLabel	=	"4 Part Payment ID";
			$PaymentSymbol	=	"- ";
		} else {
			$paymentpamount	=	str_replace("-","",number_format($pamount,2));
			$PaymentLabel	=	"Part Refund ID";
			$PaymentSymbol	=	"";
		}
		$pdf->Ln(0.1);
		
		$pdf->Cell(170,5,$PaymentLabel." - ".$payids." (".$paytype." ".$UnixPayDate.")",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"- £ ".number_format($pamount,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	}
}


// end payments


	$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);

	if($amount_discount != 0) {
		$total_gbp = round(($outstanding_pounds_before + $total_payment_pound_before - $minum_deposit),2);
		
		$pdf->Ln(0.1);
		$pdf->Cell(170,5,"TOTAL in GBP",'LR',0,'L',true);
		$pdf->Cell(19.95,5,"£ ".number_format($total_gbp,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
		$pdf->Cell(170,5,"Discount in GBP",'LR',0,'L',true);
		$pdf->SetFont('','',8);;
		$pdf->Cell(19.95,5,"£ ".number_format($amount_discount,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	} else {
		$discount_amount_calc = 0;
	}


	$pdf->SetFont('','',8);;
	$pdf->SetTextColor(0,0,0); 
	$pdf->SetFillColor(251,212,180);
	$pdf->SetDrawColor(226,179,64);  
	$pdf->SetLineWidth(0.1); 

	$total_gbp = round(($outstanding_pounds + $total_payment_pound - $minum_deposit -$totalpp),2);
	
	if ($outstanding_pounds>0 || $total_payment_pound>0) { 
		$pdf->Ln(0.1);
		$pdf->Cell(170,5,"TOTAL Payable in GBP",'LR',0,'R',true);
		$pdf->Cell(19.95,5,"£ ".number_format($total_gbp,2),'LR',0,'R',true);
		$pdf->Ln(5.1);
		$pdf->Cell(189.95,0,'','T'); 
		$pdf->Ln(0.1);
	}

	$pdf->Ln(2);
	
	$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate,reception_id,ceremony_id","WHERE id='".$record[20] ."'");
	$package_exchange_record = $sql_command->result($package_exchange_result);
	
	if($package_exchange_record[0] < 1) {
		$package_exchange_record[0] = 1;
	}

	$ceremony_result = $sql_command->select($database_ceremonies,"notes","WHERE id='".addslashes($package_exchange_record[2])."' ORDER BY ceremony_name");
	$ceremony_record = $sql_command->result($ceremony_result);
		
	if ($ceremony_record[0] != "") {
		$pdf->SetFont('','',8);
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
		
		$pdf->MultiCell(190.05, 3, "Ceremony Venue Note", 'LR', 'L', false);
		$pdf->Ln(2);
		
		$c_filter = array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
		$c_note = str_replace($c_filter,"",$ceremony_record[0]);
		$c_note = str_replace("<p> ","<p>",$c_note);
		$c_note = str_replace("<li>","<p>• ",$c_note);
		$c_note = str_replace("</li>","</p>",$c_note);
		$c_note = preg_replace('~[\r\n]+~', '', $c_note);
		$c_note = str_replace("&nbsp;", " ", $c_note);
		$c_note = trim(preg_replace('/\s\s+/', ' ', $c_note));
		
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
	
		$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $c_note, $matches);
	//	$bullet_list = array();
		$filter_c = array("<p>","</p>");
		for ($i = 0; $i < $count; ++$i) {
			//$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
			$pdf->MultiCell(190.05, 3, str_replace($filter_c,"",$matches[0][$i]), 'LR', 'L', false);
			$pdf->Ln(2);
			//$bullet_list[] = $matches[0][$i];
		}
		
//		$pdf->SetFillColor(255,255,255);
//		$pdf->SetDrawColor(255,255,255);
//		$pdf->Cell(190,5,$c_note.$count,'LR',0,'L',true);
	}
	
	$pdf->Ln(2);
	
	$venue_result = $sql_command->select($database_venue_names,"notes","WHERE id='".addslashes($package_exchange_record[1])."' ORDER BY venue_name");
	$venue_record = $sql_command->result($venue_result);
	
	if ($venue_record[0] != "") {
		$pdf->SetFont('','',8);
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
		
		$pdf->MultiCell(190.05, 3, "Reception Venue Note", 'LR', 'L', false);
		$pdf->Ln(2);
		
		$v_filter= array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
		$v_note = str_replace($v_filter,"",$venue_record[0]);
		$v_note = str_replace("<p> ","<p>",$v_note);
		$v_note = str_replace("<li>","<p>• ",$v_note);
		$v_note = str_replace("</li>","</p>",$v_note);
		$v_note = preg_replace('~[\r\n]+~', '', $v_note);
		$v_note = str_replace("&nbsp;", " ", $v_note);
		$v_note = trim(preg_replace('/\s\s+/', ' ', $v_note));
		
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
	
		$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $v_note, $matches);
	//	$bullet_list = array();
		$filter_v = array("<p>","</p>");
		for ($i = 0; $i < $count; ++$i) {
			//$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
			$pdf->MultiCell(190.05, 3, str_replace($filter_v,"",$matches[0][$i]), 'LR', 'L', false);
			$pdf->Ln(2);
			//$bullet_list[] = $matches[0][$i];
		}
		
//		$pdf->SetFillColor(255,255,255);
//		$pdf->SetDrawColor(255,255,255);
//		$pdf->Cell(190,5,$v_note.$count,'LR',0,'L',true);
	}
	
	$pdf->Ln(2);
	
	$comments_q = $sql_command->select("notes","note","WHERE note_primary_reference = '".addslashes($record[20])."' AND note_secondary_reference = '".addslashes($_GET['invoice'])."' AND note_type = 'InvoiceComment' AND extra = 'Yes'");
	$comments_r = $sql_command->result($comments_q);

	if ($comments_r) {
		$pdf->SetFont('','',8);
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
		
		$pdf->MultiCell(190.05, 3, "Please Note", 'LR', 'L', false);
		$pdf->Ln(2);
		
		$p_filter= array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
		$p_note = str_replace($p_filter,"",$comments_r[0]);
		$p_note = str_replace("<p> ","<p>",$p_note);
		$p_note = str_replace("<li>","<p>• ",$p_note);
		$p_note = str_replace("</li>","</p>",$p_note);
		$p_note = preg_replace('~[\r\n]+~', '', $p_note);
		$p_note = str_replace("&nbsp;", " ", $p_note);
		$p_note = trim(preg_replace('/\s\s+/', ' ', $p_note));
		
		$pdf->SetFont('','',8);;
		$pdf->SetTextColor(88,88,111);
		$pdf->SetFillColor(255,255,255);  
		$pdf->SetDrawColor(255,255,255); 
	
		$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $p_note, $matches);
	//	$bullet_list = array();
		$filter_p = array("<p>","</p>");
		for ($i = 0; $i < $count; ++$i) {
			//$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
			$pdf->MultiCell(190.05, 3, str_replace($filter_p,"",$matches[0][$i]).$y, 'LR', 'L', false);
			$pdf->Ln(2);
			//$bullet_list[] = $matches[0][$i];
		}
		
//		$pdf->SetFillColor(255,255,255);
//		$pdf->SetDrawColor(255,255,255);
//		$pdf->Cell(190,5,$p_note.$count,'LR',0,'L',true);
	}
	
}

$pdf->output("invoice-".$_GET["invoice"].".pdf","D");

$sql_command->close();
?>