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

$header_image = "../images/invoice_header_bar.jpg";
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




define('FPDF_FONTPATH','/home/ionianwe/public_html/oos/font/');
require('fpdf.php');

class PDF extends FPDF {
}

$pdf=new PDF();
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Invoice No '.$_GET["invoice"]);


$pdf->SetY('8');
$pdf->SetLeftMargin('170');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64); 
$pdf->Write(0,'Invoice');
$pdf->Ln(10); 


$pdf->SetY('5');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

$pdf->Image($header_image, 10, 0,190,19.05);


$pdf->SetY('24');
$pdf->SetLeftMargin('160');
// date
$date = date("jS  F Y",$record[21]);

$pdf->SetFont('Arial','',7);
$pdf->SetTextColor(0,0,0); 
$pdf->Write(0,'DATE ');
$pdf->Write(0,$date);
$pdf->Ln(5);

// invoice number
$pdf->Write(0,'INVOICE ');
$pdf->Write(0,$record[19]."/".$_GET["invoice"]);
$pdf->Ln(10);

$pdf->Write(0,'DUE DATE ');
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
$pdf->Write(0,'9 Prado Path, Twickenham, TW1 4BB');
$pdf->Ln(3.6);	 
$pdf->Write(0,'02088927556 - weddings@ionianweddings.co.uk');
$pdf->Ln(8);	 
$pdf->Write(0,'TO ' .$record[1]." ".$record[2]." ".$record[3]);
$pdf->Ln(3.6);	 
$pdf->Write(0,'(Customer ID '.$record[19].', Wedding Date '.$date2.')');
$pdf->Ln(3.6);	 



$pdf->SetY('44');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

// invoice header
$pdf->SetFont('','B',7);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);  
$pdf->SetLineWidth(0.1); 


$pdf->Cell(189.95,5,'','T'); 
$pdf->Ln(0.1);
$pdf->Cell(170,5,"DESCRIPTION",'LR',0,'C',true);
$pdf->Cell(19.95,5,"Amount",'LR',0,'C',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


// invoice text
$pdf->SetFont('','B',7);
$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(0,0,0);  
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
									   code","WHERE invoice_id='".addslashes($_GET["invoice"])."' and item_type='Deposit' and order_id='".addslashes($record[20])."'");
$invoice_row = $sql_command->results($invoice_result);

foreach($invoice_row as $invoice_record) {

if(eregi("<p>",$invoice_record[0])) {
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
 
$pdf->Cell(170,4,$paragraph ." x".$invoice_record[1],'LR',0,'L',true);
$pdf->SetFont('','',7);
$pdf->Cell(19.95,4,'','LR',0,'L',true);

$pdf->Ln(3.6); 
}
$pdf->Ln(0.4);
$pdf->Cell(189.95,0,'','T'); 

$pdf->SetFont('','B',7);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(195,195,195);
$pdf->SetDrawColor(0,0,0);  
$pdf->SetLineWidth(0.1); 

if($record[22] < 1) { $record[22] = 1; }

$outstanding_euro = $total_payment_pound * $record[22];

$pdf->Ln(0.1);
$pdf->Cell(170,5,"Total",'LR',0,'R',true);
$pdf->Cell(19.95,5,"€ ".$outstanding_euro,'LR',0,'L',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);

$pdf->SetFont('','B',6);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);  
$pdf->SetLineWidth(0.1); 


$pdf->SetTextColor(0,0,0); 
$pdf->Cell(170,5,"Total Outstanding in GBP",'LR',0,'L',true);
$pdf->Cell(19.95,5,"£ ".$total_payment_pound,'LR',0,'L',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


$pdf->Ln(5);

// invoice header

$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(0,0,0);  
$pdf->SetLineWidth(0); 

$pdf->SetFont('','',5);
$pdf->Cell(189.95,5,"The easiest was to pay your deposits is by wire / bank transfer (BAC). Our bank details are below",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->SetFont('','BU',5);
$pdf->Cell(189.95,5,"Please quote your surname and IW booking reference on your transaction.",'',0,'C',true);
$pdf->Ln(6);
$pdf->SetFont('','',5);
$pdf->Cell(189.95,5,"The account below is for GBP payments only.",'',0,'C',true);
$pdf->Ln(5);
$pdf->Cell(189.95,5,"Ionian Weddings Ltd",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"Co-operative Bank PLC.",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"A/C No 70913224",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"Sort Code 08-92-50",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"Swift (BIC) - CPBK GB 22",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"IBAN – GB21 CPBK 08925070913224",'',0,'C',true);
$pdf->Ln(5);
$pdf->Cell(189.95,5,"Balance payments are not refundable; cheques are no longer accepted due to the abolition of the Cheque Guarantee Scheme.",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->Cell(189.95,5,"We also accept debit cards and credit cards (there is a 2% transaction fee for credit cards and 3% for international credit cards) – please ask for a form for card payments.",'',0,'C',true);
$pdf->Ln(5);

$pdf->Cell(189.95,5,"If you prefer to pay in Euros, please let us know.",'',0,'C',true);
$pdf->Ln(5);
$pdf->SetTextColor(152,72,6); 
$pdf->SetFont('','B',5);
$pdf->Cell(189.95,5,"THANK YOU FOR YOUR BUSINESS!",'',0,'C',true);
$pdf->Ln(3.6);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFont('','',5);
$pdf->Cell(189.95,5,"www.ionianweddings.co.uk",'',0,'C',true);
$pdf->Ln(3.6);


$gety = $pdf->GetY(); 

$pdf->Image("../images/invoice_abta.jpg", 180, $gety+10, 16.93,6.77);

$sety = $gety+10;
$pdf->SetY($sety);

$pdf->SetFont('','B','');
$pdf->Write(0,'VAT Registration Number ');
$pdf->SetFont('','','');
$pdf->Write(0,'103185747');
$pdf->Ln(5);





$pdf->output("invoice-".$_GET["invoice"].".pdf","D");

$sql_command->close();
?>