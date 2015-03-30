<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");

$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

$result = $sql_command->select("$database_supplier_invoices,$database_supplier_details","$database_supplier_details.*,
							   $database_supplier_invoices.name,
							   $database_supplier_invoices.qty,
							   $database_supplier_invoices.cost,
							   $database_supplier_invoices.currency,
							   $database_supplier_invoices.exchange_rate,
							   $database_supplier_invoices.code","WHERE 
							   $database_supplier_details.id=$database_supplier_invoices.supplier_id AND 
							   $database_supplier_invoices.id='".addslashes($_GET["purchase_order"])."'");
$record = $sql_command->result($result);




define('FPDF_FONTPATH','/home/ionianwe/public_html/oos/font/');
require('fpdf.php');

class PDF extends FPDF {
function Footer() {
global $ring_image;

$this->SetXY(0,-15);
$this->SetLeftMargin('10');
$this->SetFont('Arial','','6'); 
$this->SetTextColor(151,151,151);  
$this->Write(0,"© Copyright Ionian Weddings Ltd. 2011 – 9 Prado Path. Twickenham, England, TW1 4BB");
$this->Ln(3.5); 
$this->Write(0,"(t) / (f) +44 208 892 7556- (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk");
$this->Ln(3.5); 
$this->Write(0,"Registered in England and Wales No. 06137035");
}
}


$pdf=new PDF();
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Invoice No '.$_GET["purchase_order"]);


$pdf->SetY('8');
$pdf->SetLeftMargin('152');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64); 
$pdf->Write(0,'Purchase Order');
$pdf->Ln(10); 


$pdf->SetY('5');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

$pdf->Image($header_image, 10, 0,60);
$pdf->Image($bar_image, 10, 14, 190,0.1);


$pdf->SetY('24');
$pdf->SetLeftMargin('160');
// date
$date = date("jS  F Y",$record[14]);

$pdf->SetFont('Arial','B',7);
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Date: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$date);
$pdf->Ln(5);

// invoice number
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Purchase Order Number: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$_GET["purchase_order"]);
$pdf->Ln(5);


$pdf->SetY('22');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

// business name and address

$pdf->SetFont('Arial','B',7);
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,$record[6]);
$pdf->Ln(3.6);	 
$pdf->SetFont('Arial','','7');
$pdf->SetTextColor(88,88,111);
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
if($record[11]) {
$pdf->Write(0,$record[11]);
$pdf->Ln(3.6);	 
}
if($record[12]) {
$pdf->Write(0,$record[12]);
$pdf->Ln(3.6);	 
}
if($record[13]) {
$pdf->Write(0,$record[13]);
$pdf->Ln(3.6);	 
}

$pdf->Ln($total_address); 

$pdf->SetY('60');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

// invoice header
$pdf->SetFont('','B',7);
$pdf->SetTextColor(230,134,123); 
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(255,255,255);  
$pdf->SetLineWidth(0.3); 


$pdf->Cell(130,5,"Item",'LR',0,'L',true);
$pdf->Cell(20,5,"QTY",'LR',0,'L',true);
$pdf->Cell(20,5,"Unit Cost",'LR',0,'L',true);
$pdf->Cell(20,5,"Total",'LR',0,'L',true);
$pdf->Image($bar_image, 10, 66, 190,0.1);

$pdf->Ln(7);

// invoice text
$pdf->SetFont('','B',7);
$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(255,255,255);  
$pdf->SetLineWidth(0.3);   


$end = strpos($record[16], '</p>', $start);
$paragraph = substr($record[16], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 150) ? substr($paragraph, 0, 150) . '...' : $paragraph;

if($record[20] < 1) { $record[20] = 1; }
$itemvalue = $record[18] * $record[20];

$payment_total = $record[17] * $itemvalue;							   
							  
$pdf->SetLeftMargin('10');
$pdf->SetFont('','',5);
 
$pdf->Cell(129.8,4,$record[21].' - '.$paragraph,'LR',0,'L',true);
$pdf->SetFont('','',7);
$pdf->Cell(19.9,4,$record[17],'LR',0,'L',true);
$pdf->Cell(19.95,4,'€'.number_format($itemvalue,2),'LR',0,'L',true);
$pdf->Cell(19.75,4,'€'.number_format($payment_total,2),'LR',0,'L',true);
$pdf->Cell(174.4,0,'','T'); 
$pdf->Ln(15); 

// net amount
$pdf->SetX('0');
$pdf->SetLeftMargin('160');
$pdf->SetFont('Arial','B',7);
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Net: ');
$pdf->SetLeftMargin('180');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,'€'.number_format($payment_total,2));
$pdf->Ln(5);

// vat amount
$pdf->SetX('0');
$pdf->SetLeftMargin('160');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Vat @ 0%: ');
$pdf->SetLeftMargin('180');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,number_format($getvat,2));
$pdf->Ln(5);

// total amount
$pdf->SetX('0');
$pdf->SetLeftMargin('160');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Total: ');
$pdf->SetLeftMargin('180');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,'€'.number_format($payment_total,2));
$pdf->Ln(5);






$pdf->output("purchase-order-".$_GET["purchase_order"].".pdf","D");

$sql_command->close();
?>