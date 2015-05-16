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

require('fpdf/class.fpdf.php');

class PDF extends FPDF {
	function Footer() {
		$this->SetXY(0,-15);
		$this->SetLeftMargin('10');
		$this->SetFont('Arial','','5'); 
		$this->SetTextColor(151,151,151); 
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


list($start_day,$start_month,$start_year) = explode("-",$_GET["from"]);
list($end_day,$end_month,$end_year) = explode("-",$_GET["to"]);


$start = mktime(0, 0, 0, $start_month, $start_day, $start_year);
$end = mktime(0, 0, 0, $end_month, $end_day, $start_year);


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->tFPDF();
$pdf->AddFont('Arial', '','', true);
$pdf->AddFont('Arial', 'B','', true);
$pdf->AddPage();
$pdf->SetAuthor('Ionian Weddings');
$pdf->SetTitle('Ionian Weddings');


$pdf->SetY('8');
$pdf->SetLeftMargin('150');
$pdf->SetFont('Arial','','18');  
$pdf->SetTextColor(226,179,64); 
$pdf->Write(0,'HMRC Report');
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
$pdf->Write(0,'From: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$_GET["from"]);
$pdf->Ln(5);
$pdf->SetFont('','B','');
$pdf->SetTextColor(230,134,123); 
$pdf->Write(0,'To: ');
$pdf->SetFont('','','');
$pdf->SetTextColor(88,88,111);
$pdf->Write(0,$_GET["to"]);
$pdf->Ln(7.5);


$pdf->Cell(20,5,'Wedding Date','',0,'L',true);
$pdf->Cell(20,5,'Order ID','',0,'L',true);
$pdf->Cell(15,5,"Cost",'',0,'C',true);
$pdf->Cell(15,5,"IW Cost",'',0,'C',true);
$pdf->Cell(15,5,"VAT",'',0,'C',true);
$pdf->Cell(15,5,"Profit",'',0,'C',true);
$pdf->Ln(5.1);
$pdf->Cell(100,0,'','T'); 
$pdf->Ln(0.1);

$pdf->SetFont('Arial','',6);
$pdf->SetTextColor(88,88,111);
$pdf->SetFillColor(255,255,255);  
$pdf->SetDrawColor(226,179,64);  
$pdf->SetLineWidth(0.1);   



//$result = $sql_command->select($database_customer_invoices,"order_id,cost,iw_cost,vat,updated_timestamp,type,included_package","WHERE updated_timestamp > $start and updated_timestamp < $end and status='Paid'");
$result = $sql_command->select("$database_clients,$database_order_details,$database_customer_invoices",
								"$database_customer_invoices.order_id,
								$database_customer_invoices.cost,
								$database_customer_invoices.iw_cost,
								$database_customer_invoices.vat,
								$database_clients.wedding_date,
								$database_customer_invoices.type,
								$database_customer_invoices.included_package",
								"WHERE	$database_clients.id					=	$database_order_details.client_id
								and		$database_order_details.id				=	$database_customer_invoices.order_id
								and		$database_clients.wedding_date			>=	".$start."
								and		$database_clients.wedding_date			<	".$end."
								and		$database_customer_invoices.status		=	'Paid'");
$rows = $sql_command->results($result);

foreach($rows as $record) {
	
$remove_deposit_value = 0;

if($record[6] == "Yes") {
$deposit_check_result = $sql_command->select($database_customer_invoices,"order_id,cost,iw_cost","WHERE status='Paid' and type='Deposit' and order_id='".addslashes($record[0])."'");
$deposit_check_record = $sql_command->result($deposit_check_result);

$remove_deposit_value = $deposit_check_record[2];
}
		
	
$cost = $record[1];
$iwcost = $record[2] - $remove_deposit_value;
$vat = ($iwcost / 100) * $record[3];
$profit = $iwcost - $cost;
$date = date("d-m-Y",$record[4]);

$pdf->Cell(20,4,$date,'LR',0,'L',true);
$pdf->Cell(20,4,$record[0],'LR',0,'L',true);
$pdf->Cell(15,4,utf8_decode("Â£")." ".number_format($cost,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("Â£")." ".number_format($iwcost,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("Â£")." ".number_format($vat,2),'LR',0,'C',true);
$pdf->Cell(15,4,utf8_decode("Â£")." ".number_format($profit,2),'LR',0,'C',true);
$pdf->Ln(3.6); 
}
$pdf->Ln(0.5); 
$pdf->Cell(100,0,'','T'); 
$pdf->Ln(0.1);


$pdf->output("hmrc-report-".$_GET["from"]."-".$_GET["to"].".pdf","D");

$sql_command->close();
?>