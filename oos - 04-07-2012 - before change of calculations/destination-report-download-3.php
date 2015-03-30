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

define('FPDF_FONTPATH','/home/ionianwe/public_html/oos/font/');
require('fpdf.php');

class PDF extends FPDF {
function Footer() {

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

if(!$_GET["year"]) { $_GET["year"] = $end_year; }


	
$april_start = mktime(0, 0, 0, 4, 1, $_GET["year"]);
$may_start = mktime(0, 0, 0, 5, 1, $_GET["year"]);
$june_start = mktime(0, 0, 0, 6, 1, $_GET["year"]);
$july_start = mktime(0, 0, 0, 7, 1, $_GET["year"]);
$august_start = mktime(0, 0, 0, 8, 1, $_GET["year"]);
$september_start = mktime(0, 0, 0, 9, 1, $_GET["year"]);
$october_start = mktime(0, 0, 0, 10, 1, $_GET["year"]);
$november_start = mktime(0, 0, 0, 11, 1, $_GET["year"]);
$december_start = mktime(0, 0, 0, 12, 1, $_GET["year"]);
$january_start = mktime(0, 0, 0, 1, 1, $_GET["year"]+1);
$february_start = mktime(0, 0, 0, 2, 1, $_GET["year"]+1);
$march_start = mktime(0, 0, 0, 3, 1, $_GET["year"]+1);
$april2_start = mktime(0, 0, 0, 4, 1, $_GET["year"]+1);
	


$april_total_bookings = 0;
$may_total_bookings = 0;
$june_total_bookings = 0;
$july_total_bookings = 0;
$august_total_bookings = 0;
$september_total_bookings = 0;
$october_total_bookings = 0;
$november_total_bookings = 0;
$december_total_bookings = 0;
$january_total_bookings = 0;
$february_total_bookings = 0;
$march_total_bookings = 0;


$april_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $april_start and 
									 $database_clients.wedding_date < $may_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$april_rows = $sql_command->results($april_result);

foreach($april_rows as $april_record) {
$april_cost += $april_record[1];
$april_iwcost += $april_record[2];
$april_vat += ($april_record[2] / 100) * $april_record[4];
$april_total_bookings += 1;
}
$april_profit = $april_iwcost - $april_cost;


$may_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $may_start and 
									 $database_clients.wedding_date < $june_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$may_rows = $sql_command->results($may_result);

foreach($may_rows as $may_record) {
$may_cost += $may_record[1];
$may_iwcost += $may_record[2];
$may_vat += ($may_record[2] / 100) * $may_record[4];
$may_total_bookings += 1;
}
$may_profit = $may_iwcost - $may_cost;


$june_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $june_start and 
									 $database_clients.wedding_date < $july_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$june_rows = $sql_command->results($june_result);

foreach($june_rows as $june_record) {
$june_cost += $june_record[1];
$june_iwcost += $june_record[2];
$june_vat += ($june_record[2] / 100) * $june_record[4];
$june_total_bookings += 1;
}
$june_profit = $june_iwcost - $june_cost;


$july_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $july_start and 
									 $database_clients.wedding_date < $august_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$july_rows = $sql_command->results($july_result);

foreach($july_rows as $july_record) {
$july_cost += $july_record[1];
$july_iwcost += $july_record[2];
$july_vat += ($july_record[2] / 100) * $july_record[4];
$july_total_bookings += 1;
}
$july_profit = $july_iwcost - $july_cost;


$august_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $august_start and 
									 $database_clients.wedding_date < $september_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$august_rows = $sql_command->results($august_result);

foreach($august_rows as $august_record) {
$august_cost += $august_record[1];
$august_iwcost += $august_record[2];
$august_vat += ($august_record[2] / 100) * $august_record[4];
$august_total_bookings += 1;
}
$august_profit = $august_iwcost - $august_cost;



$september_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $september_start and 
									 $database_clients.wedding_date < $october_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$september_rows = $sql_command->results($september_result);

foreach($september_rows as $september_record) {
$september_cost += $september_record[1];
$september_iwcost += $september_record[2];
$september_vat += ($september_record[2] / 100) * $september_record[4];
$september_total_bookings += 1;
}
$september_profit = $september_iwcost - $september_cost;



$october_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $october_start and 
									 $database_clients.wedding_date < $november_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$october_rows = $sql_command->results($october_result);

foreach($october_rows as $october_record) {
$october_cost += $october_record[1];
$october_iwcost += $october_record[2];
$october_vat += ($october_record[2] / 100) * $october_record[4];
$october_total_bookings += 1;
}
$october_profit = $october_iwcost - $october_cost;



$november_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $november_start and 
									 $database_clients.wedding_date < $december_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$november_rows = $sql_command->results($november_result);

foreach($november_rows as $november_record) {
$november_cost += $november_record[1];
$november_iwcost += $november_record[2];
$november_vat += ($november_record[2] / 100) * $november_record[4];
$november_total_bookings += 1;
}
$november_profit = $november_iwcost - $november_cost;



$december_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $december_start and 
									 $database_clients.wedding_date < $january_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$december_rows = $sql_command->results($december_result);

foreach($december_rows as $december_record) {
$december_cost += $december_record[1];
$december_iwcost += $december_record[2];
$december_vat += ($december_record[2] / 100) * $december_record[4];
$december_total_bookings += 1;
}
$december_profit = $december_iwcost - $december_cost;



$january_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $january_start and 
									 $database_clients.wedding_date < $february_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$january_rows = $sql_command->results($january_result);

foreach($january_rows as $january_record) {
$january_cost += $january_record[1];
$january_iwcost += $january_record[2];
$january_vat += ($january_record[2] / 100) * $january_record[4];
$january_total_bookings += 1;
}
$january_profit = $january_iwcost - $january_cost;


$february_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $february_start and 
									 $database_clients.wedding_date < $march_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$february_rows = $sql_command->results($february_result);

foreach($february_rows as $february_record) {
$february_cost += $february_record[1];
$february_iwcost += $february_record[2];
$february_vat += ($february_record[2] / 100) * $february_record[4];
$february_total_bookings += 1;
}
$february_profit = $february_iwcost - $february_cost;


$march_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $march_start and 
									 $database_clients.wedding_date < $april2_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$march_rows = $sql_command->results($march_result);

foreach($march_rows as $march_record) {
$march_cost += $march_record[1];
$march_iwcost += $march_record[2];
$march_vat += ($march_record[2] / 100) * $march_record[4];
$march_total_bookings += 1;
}
$march_profit = $march_iwcost - $march_cost;




$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE id='".addslashes($_GET["island_id"])."'");
$level1_record = $sql_command->result($level1_result);
	


$pdf=new PDF();
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Ionian Weddings');


$pdf->SetY('8');
$pdf->SetLeftMargin('145');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64); 
$pdf->Write(0,'Revenue Report');
$pdf->Ln(10); 


$pdf->SetY('5');
$pdf->SetX('5');
$pdf->SetLeftMargin('10');

$pdf->Image($header_image, 10, 0,60);
$pdf->Image($bar_image, 10, 14, 190,0.1);


$pdf->SetFont('','B',7);
$pdf->SetTextColor(226,179,64);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(226,179,64);
$pdf->SetLineWidth(0.1); 

$pdf->Ln(15);
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Wedding Year: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$_GET["year"]);
$pdf->Ln(5);
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'Destination: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$level1_record[1]);
$pdf->Ln(7.5);


$pdf->Cell(100,5,'','',0,'L',true);
$pdf->Cell(15,5,"Total Weddings",'',0,'C',true);
$pdf->Cell(15,5,"Cost",'',0,'C',true);
$pdf->Cell(15,5,"IW Cost",'',0,'C',true);
$pdf->Ln(5.1);
$pdf->Cell(145,0,'','T'); 
$pdf->Ln(0.1);

$pdf->SetFont('Arial','',6);
$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1);   




if($april_total_bookings > 0) { $april_cost_average = $april_cost / $april_total_bookings; } else { $april_cost_average = 0; } 
if($april_total_bookings > 0) { $april_iwcost_average = $april_iwcost / $april_total_bookings; } else { $april_iwcost_average = 0; }

if($may_total_bookings > 0) { $may_cost_average = $may_cost / $may_total_bookings; } else { $may_cost_average = 0; } 
if($may_total_bookings > 0) { $may_iwcost_average = $may_iwcost / $may_total_bookings; } else { $may_iwcost_average = 0; } 

if($june_total_bookings > 0) { $june_cost_average = $june_cost / $june_total_bookings; } else { $june_cost_average = 0; } 
if($june_total_bookings > 0) { $june_iwcost_average = $june_iwcost / $june_total_bookings; } else { $june_iwcost_average = 0; }

if($july_total_bookings > 0) { $july_cost_average = $july_cost / $july_total_bookings; } else { $july_cost_average = 0; } 
if($july_total_bookings > 0) { $july_iwcost_average = $july_iwcost / $july_total_bookings; } else { $july_iwcost_average = 0; } 

if($august_total_bookings > 0) { $august_cost_average = $august_cost / $august_total_bookings; } else { $august_cost_average = 0; } 
if($august_total_bookings > 0) { $august_iwcost_average = $august_iwcost / $august_total_bookings; } else { $august_iwcost_average = 0; } 

if($september_total_bookings > 0) { $september_cost_average = $september_cost / $september_total_bookings; } else { $september_cost_average = 0; } 
if($september_total_bookings > 0) {$september_iwcost_average = $september_iwcost / $september_total_bookings; } else { $september_iwcost_average = 0; } 

if($october_total_bookings > 0) { $october_cost_average = $october_cost / $october_total_bookings; } else { $october_cost_average = 0; } 
if($october_total_bookings > 0) { $october_iwcost_average = $october_iwcost / $october_total_bookings; } else { $october_iwcost_average = 0; }

if($november_total_bookings > 0) { $november_cost_average = $november_cost / $november_total_bookings; } else { $november_cost_average = 0; } 
if($november_total_bookings > 0) { $november_iwcost_average = $november_iwcost / $november_total_bookings; } else { $november_iwcost_average = 0; } 

if($december_total_bookings > 0) { $december_cost_average = $december_cost / $december_total_bookings; } else { $december_cost_average = 0; }  
if($december_total_bookings > 0) { $december_iwcost_average = $december_iwcost / $december_total_bookings; } else { $december_iwcost_average = 0; } 

if($january_total_bookings > 0) { $january_cost_average = $january_cost / $january_total_bookings; } else { $january_cost_average = 0; }  
if($january_total_bookings > 0) { $january_iwcost_average = $january_iwcost / $january_total_bookings; } else { $january_iwcost_average = 0; }

if($february_total_bookings > 0) { $february_cost_average = $february_cost / $february_total_bookings; } else { $february_cost_average = 0; } 
if($february_total_bookings > 0) { $february_iwcost_average = $february_iwcost / $february_total_bookings; } else { $february_iwcost_average = 0; } 

if($march_total_bookings > 0) { $march_cost_average = $march_cost / $march_total_bookings; } else { $march_cost_average = 0; } 
if($march_total_bookings > 0) { $march_iwcost_average = $march_iwcost / $march_total_bookings; } else { $march_iwcost_average = 0; } 



///
$pdf->Cell(100,4,'April '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$april_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'May '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$may_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($may_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($may_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'June '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$june_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($june_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($june_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'July '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$july_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($july_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($july_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 

$pdf->Cell(100,4,'August '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$august_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($august_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($august_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'September '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$september_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($september_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($september_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'October '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$october_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,5,utf8_decode("£")." ".number_format($october_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($october_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'November '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$november_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($november_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($november_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 

$pdf->Cell(100,4,'December '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$december_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($december_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($december_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'January '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$january_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($january_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($january_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'February '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$february_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($february_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($february_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'March '.$_GET["year"],'LR',0,'L',true);
$pdf->Cell(15,4,$march_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($march_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($march_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(4.1); 
$pdf->Cell(145,0,'','T'); 
$pdf->Ln(0.1);
$pdf->Cell(100,4,'First Quarter','LR',0,'L',true);
$pdf->Cell(15,4,$april_total_bookings+$may_total_bookings+$june_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_cost_average+$may_cost_average+$june_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_iwcost_average+$may_iwcost_average+$june_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'Second Quarter','LR',0,'L',true);
$pdf->Cell(15,4,$july_total_bookings+$august_total_bookings+$september_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($july_cost_average+$august_cost_average+$september_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($july_iwcost_average+$august_iwcost_average+$september_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'Third Quarter','LR',0,'L',true);
$pdf->Cell(15,4,$october_total_bookings+$november_total_bookings+$december_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($october_cost_average+$november_cost_average+$december_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($october_iwcost_average+$november_iwcost_average+$december_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
$pdf->Cell(100,4,'Fourth Quarter','LR',0,'L',true);
$pdf->Cell(15,4,$janaury_total_bookings+$february_total_bookings+$march_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($january_cost_average+$february_cost_average+$march_cost_average,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($january_iwcost_average+$february_iwcost_average+$march_iwcost_average,2),'LR',0,'C',true);
$pdf->Ln(4.1); 
$pdf->Cell(145,0,'','T'); 
$pdf->Ln(0.1);
$pdf->Cell(100,4,'Total','LR',0,'L',true);
$pdf->Cell(15,4,$april_total_bookings+$may_total_bookings+$june_total_bookings+$july_total_bookings+$august_total_bookings+$september_total_bookings+$october_total_bookings+$november_total_bookings+$december_total_bookings+$janaury_total_bookings+$february_total_bookings+$march_total_bookings,'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_cost+$may_cost+$june_cost+$july_cost+$august_cost+$september_cost+$october_cost+$november_cost+$december_cost+$january_cost+$february_cost+$march_cost,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("£")." ".number_format($april_iwcost+$may_iwcost+$june_iwcost+$july_iwcost+$august_iwcost+$september_iwcost+$october_iwcost+$november_iwcost+$december_iwcost+$january_iwcost+$february_iwcost+$march_iwcost,2),'LR',0,'C',true);
$pdf->Ln(4.1); 
$pdf->Cell(145,0,'','T'); 
$pdf->Ln(0.1);

$pdf->output("hmrc-report-".$_GET["year"].".pdf","D");

$sql_command->close();
?>