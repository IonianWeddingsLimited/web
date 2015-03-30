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
							   $database_supplier_invoices.code,
							   $database_supplier_invoices.order_id,
							   $database_supplier_invoices.status","WHERE 
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
$this->SetFont('Arial','','5'); 
$this->SetTextColor(151,151,151);  
$gety = $this->GetY(); 

$this->Image("../images/invoice_abta.jpg", 185, $gety + 0.5, 16.93,6.77);

$this->SetTextColor(151,151,151);  
$this->Write(0,"© Copyright Ionian Weddings Ltd. 2011 – 9 Prado Path. Twickenham, England, TW1 4BB");
$this->Ln(3.5); 
$this->Write(0,"(t) / (f) +44 208 892 7556- (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk");
$this->Ln(3.5); 
$this->Write(0,"Registered in England and Wales No. 06137035 | VAT Registration Number: 103185747");
}
}


$pdf=new PDF();
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Purchase Order '.$_GET["purchase_order"]);


$pdf->SetY('5');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(226,179,64);
$pdf->SetLineWidth(0.1); 
$pdf->SetLeftMargin('130');
if($record[23] == "Outstanding") {
$pdf->Cell(70,5,"Purchase Order - Outstanding",'',0,'R',true);
} elseif($record[23] == "Paid") {
$pdf->Cell(70,5,"Purchase Order - Paid",'',0,'R',true);
} elseif($record[23] == "Refunded") {
$pdf->Cell(70,5,"Purchase Order - Refund",'',0,'R',true);
} elseif($record[23] == "Pending") {
$pdf->Cell(70,5,"Purchase Order - Pending",'',0,'R',true);
} elseif($record[23] == "Cancelled") {
$pdf->Cell(70,5,"Purchase Order - Cancelled",'',0,'R',true);
} 

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

$pdf->Ln(3.6);	






$client_info_result = $sql_command->select("$database_order_details,$database_clients","$database_clients.*","WHERE 
							   $database_order_details.client_id=$database_clients.id and 
							   $database_order_details.id='".$record[22]."'");
$client_info_record = $sql_command->result($client_info_result);

$package_info_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info,$database_navigation","$database_packages.package_name,
							   $database_navigation.page_name","WHERE 
							   $database_order_details.id='".$record[22]."' AND 
							   $database_order_details.package_id=$database_package_info.id and 
							   $database_package_info.package_id=$database_packages.id and 
							   $database_packages.island_id=$database_navigation.id");
$package_info_record = $sql_command->result($package_info_result);

$date2 = date("d/m/Y",$client_info_record[17]);

$pdf->Write(0,'Client Name ' .$client_info_record[1]." ".$client_info_record[2]." ".$client_info_record[3]);
$pdf->Ln(3.6);	 
$pdf->Write(0,'(Customer ID '.$client_info_record[19].', Wedding Date '.$date2.')');

$pdf->Ln(3.6);
$pdf->Ln(3.6);
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Location / Package: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$package_info_record[1]." > ".$package_info_record[0]);
$pdf->Ln(3.6);



$gety = $pdf->GetY();

$pdf->SetY($gety);
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
$pdf->Cell(160.05,4,$record[21].' - '.$paragraph,'LR',0,'L',true);
$pdf->SetFont('','',6);
$pdf->Cell(9.95,5,$record[17],'LR',0,'C',true);
$pdf->Cell(19.95,4,'€ '.number_format($payment_total,2),'LR',0,'R',true);
$pdf->Ln(4);
$pdf->Cell(189.95,0,'','T'); 
 

// net amount
$pdf->Ln(0.1);
$pdf->SetFont('','B',7);
$pdf->Cell(170,5,"Net",'LR',0,'R',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"€ ".number_format($payment_total,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


// vat amount
$pdf->Ln(0.1);
$pdf->SetFont('','B',7);
$pdf->Cell(170,5,"Vat",'LR',0,'R',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"€ ".number_format($getvat,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);


// total amount
$pdf->SetFont('','B',7);
$pdf->SetTextColor(0,0,0); 
$pdf->SetFillColor(251,212,180);
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1); 

$pdf->Ln(0.1);
$pdf->Cell(170,5,"Total",'LR',0,'R',true);
$pdf->SetFont('','',6);
$pdf->Cell(19.95,5,"€ ".number_format($payment_total+$getvat,2),'LR',0,'R',true);
$pdf->Ln(5.1);
$pdf->Cell(189.95,0,'','T'); 
$pdf->Ln(0.1);





$pdf->output("purchase-order-".$_GET["purchase_order"].".pdf","D");

$sql_command->close();
?>