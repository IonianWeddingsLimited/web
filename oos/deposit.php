<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

$getvat = "20.00";

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");

$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

$result = $sql_command->select("$database_customer_invoices,$database_order_details,$database_clients","$database_clients.*,
							   $database_customer_invoices.order_id,
							   $database_customer_invoices.timestamp,
							   $database_order_details.exchange_rate","WHERE 
							   $database_customer_invoices.id='".addslashes($_GET["invoice"])."' AND 
							   $database_customer_invoices.order_id=$database_order_details.id AND 
							   $database_order_details.client_id=$database_clients.id");
$record = $sql_command->result($result);

$result2 = $sql_command->select("$database_order_details,$database_packages,$database_navigation","$database_packages.package_name,
							   $database_navigation.page_name","WHERE 
							   $database_order_details.id='".$record[20]."' AND 
							   $database_order_details.package_id=$database_packages.id and 
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


$pdf->SetY('8');
$pdf->SetLeftMargin('175');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64); 
$pdf->Write(0,'Invoice');
$pdf->Ln(10); 


$pdf->SetY('5');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

$pdf->Image($header_image, 10, 0,60);
$pdf->Image($bar_image, 10, 14, 190,0.1);


$pdf->SetY('24');
$pdf->SetLeftMargin('160');
// date
$date = date("jS  F Y",$record[18]);

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
$pdf->Write(0,$record[17]);
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
$pdf->Write(0,'Location / Package ');
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
$pdf->Cell(170,5,"DESCRIPTION",'',0,'L',true);
$pdf->Cell(19.95,5,"Amount",'',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


// invoice text
$pdf->SetFont('','B',7);
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
									   code","WHERE invoice_id='".addslashes($_GET["invoice"])."' and order_id='".addslashes($record[20])."'");
$invoice_row = $sql_command->results($invoice_result);

foreach($invoice_row as $invoice_record) {

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
							   

$payment_total = $invoice_record[1] * $invoice_record[3];							   
$total_payment_pound += $payment_total;

$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(170,4,$paragraph,'LR',0,'L',true);
$pdf->SetFont('','',7);
$pdf->Cell(19.95,4,'','LR',0,'L',true);
$pdf->Cell(19.95,4,'£ '.number_format($invoice_record[3],2),'LR',0,'R',true);

$pdf->Ln(3.6); 
}
$pdf->Ln(0.4);
$pdf->Cell(189.95,0,'','T'); 

if($record[22] < 1) { $record[22] = 1; }

$outstanding_euro = $total_payment_pound * $record[22];

$pdf->Ln(0.1);



$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1); 



$pdf->SetTextColor(0,0,0); 
$pdf->Cell(170,5,"Amount Outstanding in GBP",'LR',0,'R',true);
$pdf->Cell(19.95,5,"£ ".$total_payment_pound,'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


$pdf->Ln(5);

$pdf->output("invoice-".$_GET["invoice"].".pdf","D");

$sql_command->close();
?>