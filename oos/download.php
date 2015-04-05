<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");


require('fpdf.php');

class PDF extends FPDF {
function Footer() {
$this->SetXY(0,-5);
$this->SetLeftMargin('74');
$this->SetFont('Arial','','8'); 
$this->Write(0,"Aeris Analytics LLC. Copyright 2010. All rights reserved.");
}
}





$pdf=new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial','','12');  
$pdf->Write(0,'Stationary Engine Compliance Requirements');
$pdf->Ln(5); 
$pdf->SetFont('Arial','','10');
$pdf->Write(0,'EPA NESHAP Subpart ZZZZ');
$pdf->Ln(10); 

$pdf->SetFont('Arial','','8');
$pdf->Write(0,'Prepared for:');
$pdf->Ln(3); 
$pdf->Write(0,stripslashes($member_row[1]));
$pdf->Ln(3); 
$pdf->Write(0,stripslashes($member_row[2]));
$pdf->Ln(10); 
$pdf->Write(0,'Report Date:	'.date("d F Y", $r_row[30]));
$pdf->Ln(10); 
$pdf->Write(0,'Report Number: '.$total_reports);

$pdf->Ln(20); 
$pdf->Write(0,'For more information on the NESHAPS Rule or our Report Generator,');
$pdf->Ln(3); 
$pdf->Write(0,'please contact Aeris Analytics at 720-475-1525 or www.tool-info@AerisAnalytics.com.');

$pdf->Image("_images/topleft.jpg", 175, 16, 30);
$pdf->Ln(180);

// invoice header

$pdf->SetFillColor(238,236,225);   
$pdf->SetLineWidth(0.3);   

$pdf->SetFont('','B',8);
$pdf->Cell(170,5,"Disclosure, Release of Liability and Terms of Use:",'',0,'L',true);
$pdf->Ln(5);
$pdf->SetFont('','',5);
$pdf->cell(170,3,"Although every effort has been made to present a detailed and accurate analysis of those requirements to be met for the subject engines for which the applicability of RICE NESHAP and/or NSPS rules are,",'',0,'L',true);
$pdf->Ln(3);
$pdf->cell(170,3,"evaluated herein including thespecific requirements governing the engines evaluated, any information relied upon herein in determining and fulfilling specific regulatory compliance obligations is",'',0,'L',true);
$pdf->Ln(3);
$pdf->cell(170,3,"done at the report recipient's own risk. No warranties either express or implied, or guarantees of actual compliance with regulatory standards are delivered with this analysis. ",'',0,'L',true);
$pdf->Ln(3);
$pdf->cell(170,3,"The content of this report should in no way be construed as legal opinion or advice. The report recipient assumes all risk and liability in the use of any information contained herein.",'',0,'L',true);


  
$pdf->AddPage();

$pdf->SetX('5');
$pdf->SetLeftMargin('5');

$pdf->SetFont('Arial','','12');  
$pdf->Write(0,'Stationary Engine Compliance Requirements Report');
$pdf->Ln(10); 

$pdf->SetFont('Arial','B','7'); 
$pdf->Write(0,'Owner and Facility Information');
$pdf->Ln(6);

$pdf->SetFont('Arial','','7'); 


     $pdf->SetFillColor(235,235,235);   
     $pdf->SetDrawColor(0,0,0);  
     $pdf->SetLineWidth(0.01);   
	 
$engine_result = $sql_command->select($display_database,"*","id='".addslashes($reports_record[2])."'","","","");
$engine_row = $sql_command->get_result($engine_result);

		$width1 = 30;
		$width2 = 60;
		   
		$w1_size = 6;
		$w2_size = 4;
		
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Owner/Operator:",'TLR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[4]),'TLR',0,'L',false); 
		$pdf->Ln(5); 
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size);  
        $pdf->Cell($width1,5,"Facility:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($r_row[5]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size);
        $pdf->Cell($width1,5,"Location (City / State):",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[6]),'LR',0,'L',false);  
		$pdf->Ln(5); 
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Location (County):",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[7]),'LR',0,'L',false);  
  		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(6); 
		
		
		$width1 = 30;
		$width2 = 55;
		
$pdf->SetFont('Arial','B','7'); 
$pdf->Write(0,'Engine Information');
$pdf->Ln(6);

$pdf->SetFont('Arial','','7'); 

		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Identification:",'TLR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,"Engine ".stripslashes($r_row[3]),'TLR',0,'L',false);
		$pdf->SetFont('','B',$w1_size);  
        $pdf->Cell($width1,5,"Serial Number:",'TLR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[23]),'TLR',0,'L',false); 
		$pdf->Ln(5);   
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Manufacturer:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[21]),'LR',0,'L',false); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Combustion Type:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[13]),'LR',0,'L',false); 
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Model:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[22]),'LR',0,'L',false); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Fuel Type:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[15]),'LR',0,'L',false); 
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Manufactured Date:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[24]),'LR',0,'L',false); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Rated Max Power:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[14])." HP",'LR',0,'L',false); 
  		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Construction Date:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[25]),'LR',0,'L',false);
		$pdf->SetFont('','B',$w1_size);  
        $pdf->Cell($width1,5,"EPA Certification:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[18]),'LR',0,'L',false); 
  		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Reconstruction Date:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[28]),'LR',0,'L',false); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Application:",'LR',0,'L',true);
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($r_row[16]),'LR',0,'L',false); 
  		$pdf->Ln(5); 
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Modified Date:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($r_row[29]),'LR',0,'L',false); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Anual Usage:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size);
		$pdf->Cell($width2,5,stripslashes($r_row[17]),'LR',0,'L',false); 
  		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2+$width1+$width2,0,'','T'); 
		$pdf->Ln(5);  



		
$pdf->SetFont('Arial','','7'); 
$pdf->Write(0,'This engine is subject to NESHAPS RICE Subpart ZZZZ: YES');


$pdf->AddPage();


$pdf->SetX('5');
$pdf->SetLeftMargin('5');
 
 	$pdf->SetFillColor(235,235,235);
     
     $pdf->SetDrawColor(0,0,0);  
     $pdf->SetLineWidth(0.01);   
	 
 

$engine_result = $sql_command->select($display_database,"*","id='".addslashes($reports_record[2])."'","","","");
$engine_row = $sql_command->get_result($engine_result);


$pdf->SetFont('Arial','','8');  	
$pdf->Write(0,"NESHAP Compliance Requirements for Diesel Engines ".stripslashes($engine_row[2]) . " (".$engine_row[1]." HP)");
$pdf->Ln(6);  

$pdf->SetFont('Arial','','4'); 
		
if(($reports_record[4] == "step1a" and $engine_row[0] == "9") or 
($reports_record[4] == "step1a" and $engine_row[0] == "19") or 
$reports_record[4] == "step1b" or 
($reports_record[4] == "step2a2" and $engine_row[0] == "13") or  
($reports_record[4] == "step2a2" and $engine_row[0] == "14") or 
($reports_record[4] == "step2a2" and $engine_row[0] == "16") or 
($reports_record[4] == "step2a2" and $engine_row[0] == "17") or 
($reports_record[4] == "step2a2" and $engine_row[0] == "19") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "1") or  
($reports_record[4] == "step2b1" and $engine_row[0] == "2") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "3") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "4") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "5") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "8") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "9") or 
($reports_record[4] == "step2b1" and $engine_row[0] == "10")) {

		$width1 = 30;
		$width2 = 150;
		
	  	 
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);   
		$pdf->SetFont('','B',''); 
        $pdf->Cell($width1,5,"Engine Category",'LR',0,'L',true);  
		$pdf->SetFont('','','');
		$pdf->Cell($width2,5,stripslashes($engine_row[2]) . " (".$engine_row[1]." HP)",'LR',0,'L',false); 
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B','');  
        $pdf->Cell($width1,5,"Date Constructed",'LR',0,'L',true);  
		$pdf->SetFont('','',''); 
		$pdf->Cell($width2,5,stripslashes($engine_row[3]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(5); 
		$pdf->Write(0,stripslashes($engine_row[4]));

} else {	

		
		$width1 = 45;
		$width2 = 155;
		
		$w1_size = 6;
		$w2_size = 4;
		
	    $pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Engine Category:",'TLR',0,'L',true);
		$pdf->SetFont('','',$w2_size);   
		$pdf->Cell($width2,5,stripslashes($engine_row[2]) . " (".$engine_row[1]." HP)",'LR',0,'L',false); 
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Date Constructed:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[3]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Compliance Date:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($engine_row[4]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Emission Limitations:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[5]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Operating Limitations:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[6]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Fuel Requirements:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($engine_row[7]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Performance Tests:",'LR',0,'L',true);
		$pdf->SetFont('','',$w2_size);   
		$pdf->Cell($width2,5,stripslashes($engine_row[8]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Maintenance and Operation Requirements:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[9]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0);  
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Initial Compliance:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[10]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Continuous Compliance:",'LR',0,'L',true);  
		$pdf->SetFont('','',$w2_size); 
		$pdf->Cell($width2,5,stripslashes($engine_row[11]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Notification Requirements:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[12]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Recordkeeping Requirements:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[9]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"Reporting Requirements:",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[14]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 
		$pdf->Ln(0); 
		$pdf->SetFont('','B',$w1_size); 
        $pdf->Cell($width1,5,"General Provisions (40 CFR part 63):",'LR',0,'L',true); 
		$pdf->SetFont('','',$w2_size);  
		$pdf->Cell($width2,5,stripslashes($engine_row[15]),'LR',0,'L',false);  
		$pdf->Ln(5);  
		$pdf->Cell($width1+$width2,0,'','T'); 

$pdf->Ln(10);  
$pdf->SetFont('Arial','','7'); 
$pdf->Write(0,'Important Note:  These requirements apply only to the national regulations established by the EPA.  ');
$pdf->Ln(3);  
$pdf->Write(0,'There may be additional requirements imposed by the State.  Please contact the appropriate State regulatory authorities or Aeris Analytics for more information');

}


$pdf->output("engine_".stripslashes($r_row[3]).".pdf","D");


$sql_command->close();
?>