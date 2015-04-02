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

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

function changechars($html) {
	$html = str_replace("&rsquo;","'",$html);
	$html = str_replace("&#39;","'",$html);
	$html = str_replace("&amp;","&",$html);
	$html = trim($html);
	$html = ltrim($html);
	
	return $html;
}


function getrow($row,$id1,$id2,$id3,$id4,$html) {
	global $sql_command,$database_image_module;
	
	$sum1 = 18.4;
	$sum2 = 79.6;
	$pdf_height = (185*$sum1)/$sum2;
	$gap = (10*$sum1)/$sum2;
	
	$getx = 10;
	
	$image1_id = "";
	$image2_id = "";
	$image3_id = "";
	$image4_id = "";
	$add_html = "";


	// image 1
	if($id1) {
		list($image1_id) = explode("-",$id1);
		
		$image_1_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($image1_id)."' and require_crop='No'");
		$image_1_record = $sql_command->result($image_1_result);
		
		if (file_exists("../images/imageuploads/".$image_1_record[3]."/".$image_1_record[4]) and $image_1_record[0]) { 
			if($image_1_record[3] == "1x1") { $total_image_size += 1; } elseif($image_1_record[3] == "2x1") { $total_image_size += 2; }  elseif($image_1_record[3] == "3x1") { $total_image_size += 1.3; }  elseif($image_1_record[3] == "4x1") { $total_image_size += 4; } 
				if($total_image_size <= 4.5) {
					$showimage_1 = "Yes";
				}
			}
		}

	// image 2
	if($id2) {
		list($image2_id) = explode("-",$id2);
		
		$image_2_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($image2_id)."' and require_crop='No'");
		$image_2_record = $sql_command->result($image_2_result);
		
		if (file_exists("../images/imageuploads/".$image_2_record[3]."/".$image_2_record[4]) and $image_2_record[0]) { 
			if($image_2_record[3] == "1x1") { $total_image_size += 1; } elseif($image_2_record[3] == "2x1") { $total_image_size += 2; }  elseif($image_2_record[3] == "3x1") { $total_image_size += 1.3; }  elseif($image_2_record[3] == "4x1") { $total_image_size += 4; } 
				if($total_image_size <= 4.5) {
					$showimage_2 = "Yes";
				}
			}
		}

	//image 3
	if($id3) {
		list($image3_id) = explode("-",$id3);
		
		$image_3_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($image3_id)."' and require_crop='No'");
		$image_3_record = $sql_command->result($image_3_result);
		
		if (file_exists("../images/imageuploads/".$image_3_record[3]."/".$image_3_record[4]) and $image_3_record[0]) { 
			if($image_3_record[3] == "1x1") { $total_image_size += 1; } elseif($image_3_record[3] == "2x1") { $total_image_size += 2; }  elseif($image_3_record[3] == "3x1") { $total_image_size += 1.3; }  elseif($image_3_record[3] == "4x1") { $total_image_size += 4; } 
				if($total_image_size <= 4.5) {
					$showimage_3 = "Yes";
				}
			}
		}

	//image4
	if($id4) {
		list($image4_id) = explode("-",$id4);
		
		$image_4_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($image4_id)."' and require_crop='No'");
		$image_4_record = $sql_command->result($image_4_result);
		
		if (file_exists("../images/imageuploads/".$image_4_record[3]."/".$image_4_record[4]) and $image_4_record[0]) { 
			if($image_4_record[3] == "1x1") { $total_image_size += 1; } elseif($image_4_record[3] == "2x1") { $total_image_size += 2; }  elseif($image_4_record[3] == "3x1") { $total_image_size += 1.3; }  elseif($image_4_record[3] == "4x1") { $total_image_size += 4; } 
				if($total_image_size <= 4.5) {
					$showimage_4 = "Yes";
				}
			}
		}



	if($showimage_1 == "Yes") {
		list($width_1, $height_1, $type, $attr) = getimagesize("../images/imageuploads/".$image_1_record[3]."/".$image_1_record[4]);
		
		$show_width1 = ($width_1*$sum1)/$sum2;
		$show_height1 = ($height_1*$sum1)/$sum2;
		$add_html .= "<img src=\"../images/imageuploads/".$image_1_record[3]."/".$image_1_record[4]."\" X=\"".$getx."\" Y=\"".$pdf_height."\" WIDTH=\"".$show_width1."\" HEIGHT=\"".$show_height1."\">";
		$getx += ($show_width1+$gap);
	}
	
	if($showimage_2 == "Yes") {
		list($width_2, $height_2, $type, $attr) = getimagesize("../images/imageuploads/".$image_2_record[3]."/".$image_2_record[4]);
		
		$show_width2 = ($width_2*$sum1)/$sum2;
		$show_height2 = ($height_2*$sum1)/$sum2;
		$add_html .= "<img src=\"../images/imageuploads/".$image_2_record[3]."/".$image_2_record[4]."\" X=\"".$getx."\" Y=\"".$pdf_height."\" WIDTH=\"".$show_width2."\" HEIGHT=\"".$show_height2."\">";
		$getx += ($show_width2+$gap);
	}

	if($showimage_3 == "Yes") {
		list($width_3, $height_3, $type, $attr) = getimagesize("../images/imageuploads/".$image_3_record[3]."/".$image_3_record[4]);
		
		$show_width3 = ($width_3*$sum1)/$sum2;
		$show_height3 = ($height_3*$sum1)/$sum2;
		$add_html .= "<img src=\"../images/imageuploads/".$image_3_record[3]."/".$image_3_record[4]."\" X=\"".$getx."\" Y=\"".$pdf_height."\" WIDTH=\"".$show_width3."\" HEIGHT=\"".$show_height3."\">";
		$getx += ($show_width3+$gap);
	}

	if($showimage_4 == "Yes") {
		list($width_4, $height_4, $type, $attr) = getimagesize("../images/imageuploads/".$image_4_record[3]."/".$image_4_record[4]);
		
		$show_width4 = ($width_4*$sum1)/$sum2;
		$show_height4 = ($height_4*$sum1)/$sum2;
		$add_html .= "<img src=\"../images/imageuploads/".$image_4_record[3]."/".$image_4_record[4]."\" X=\"".$getx."\" Y=\"".$pdf_height."\" WIDTH=\"".$show_width4."\" HEIGHT=\"".$show_height4."\">";
	}



	if($showimage_1 == "Yes" or $showimage_2 == "Yes" or $showimage_3 == "Yes" or $showimage_4 == "Yes") {
		if($row == 1) {
			$html = str_replace("<p>[insert_row_1]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		} elseif($row == 2) {
			$html = str_replace("<p>[insert_row_2]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		} elseif($row == 3) {
			$html = str_replace("<p>[insert_row_3]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		} elseif($row == 4) {
			$html = str_replace("<p>[insert_row_4]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		} elseif($row == 5) {
			$html = str_replace("<p>[insert_row_5]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		} elseif($row == 6) {
			$html = str_replace("<p>[insert_row_6]</p>","<P H=\"".$pdf_height."\">".$add_html."</p>",$html);
		}
	}

	return $html;

}

$header_image = "../images/invoice_header.jpg";
$ring_image = "../images/invoice_rings.jpg";
$bar_image = "../images/invoice_line.jpg";

if($_GET["id"]) {

	$result = $sql_command->select($database_pdf_generator,"*","WHERE id='".addslashes($_GET["id"])."'");
	$record = $sql_command->result($result);

	$record[0] = str_replace("&euro;","€",$record[0]);
	$record[0] = str_replace("&pound;","£",$record[0]);
	
	$record[1] = str_replace("&euro;","€",$record[1]);
	$record[1] = str_replace("&pound;","£",$record[1]);
	
	$record[2] = str_replace("&euro;","€",$record[2]);
	$record[2] = str_replace("&pound;","£",$record[2]);
	
	
	$record[7] = str_replace("&euro;","€",$record[7]);
	$record[7] = str_replace("&pound;","£",$record[7]);
	
	$record[8] = str_replace("&euro;","€",$record[8]);
	$record[8] = str_replace("&pound;","£",$record[8]);
	
	$record[9] = str_replace("&euro;","€",$record[9]);
	$record[9] = str_replace("&pound;","£",$record[9]);
	
	$record[10] = str_replace("&euro;","€",$record[10]);
	$record[10] = str_replace("&pound;","£",$record[10]);
	
	$record[31] = str_replace("&euro;","€",$record[31]);
	$record[31] = str_replace("&pound;","£",$record[31]);


	if(!$record[0]) {
		$get_template->topHTML();
		$get_template->errorHTML("View PDF","Oops!","We could not find the PDF you requested, please try again","Link","oos/pdf-generator-update.php");
		$get_template->bottomHTML();
		$sql_command->close();
	}


	define('FPDF_FONTPATH','/home/ionianwe/public_html/oos/font/');
	require('fpdf.php');
	require('html2pdf.php');

	class PDF extends PDF_HTML {
	
		function Footer() {
			
			global $ring_image, $bar_image;
			
			$this->SetXY(0,-15);
			$this->SetLeftMargin('10');
			$this->SetFont('Arial','','5'); 
			$this->SetTextColor(151,151,151);  
			$gety = $this->GetY(); 


			$this->Image($bar_image, 10, $gety-3, 190,0.1);
			$this->SetY($gety+5); 
			$this->Image($ring_image, 10, $gety -2, 16.93,16.77);
			$this->Image("../images/invoice_abta.jpg", 185, $gety + 0.5, 16.93,6.77);
			$this->SetY($gety+3); 
			$this->SetLeftMargin('30');
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
	$pdf->SetTitle('');
	
	$pdf->SetY('5');
	$pdf->SetFont('Arial','B','10');  
	$pdf->SetTextColor(226,179,64);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetDrawColor(226,179,64);
	$pdf->SetLineWidth(0.1); 
	$pdf->SetLeftMargin('100');

	//$pdf->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
	$pdf->Cell(70,5,$record[31],'',0,'l',true);
	$pdf->Ln(4.2); 
	$pdf->SetFont('Arial','B','10'); 
	$pdf->SetTextColor(143,126,122);
	$pdf->Cell(70,5,stripslashes($record[1]),'',0,'l',true);
	$pdf->Ln(8);
	
	$pdf->SetY('5');
	$pdf->SetX('5');
	$pdf->SetLeftMargin('10');
	
	$pdf->Image($header_image, 10, 3,60);
	$pdf->Image($bar_image, 10, 19, 190,0.1);
	
	$pdf->SetY('29');

	$pdf->SetX(9);
	$pdf->SetLeftMargin('9');
	$pdf->SetFont('Arial','B','12');  
	$pdf->SetTextColor(210,176,79);
	$pdf->Write(5,stripslashes($record[1]),'');

	$pdf->SetX(9);
	$pdf->SetLeftMargin('9');
	$pdf->SetFont('Arial','','9');
	$pdf->SetTextColor(82,81,82);
	$record[2] = str_replace("<br />","<br>",$record[2]);
	$record[2] = str_replace("\r\n","",$record[2]);
	$record[2] = str_replace("\n","",$record[2]);
	$record[2] = str_replace("\r","",$record[2]);
	$record[2] = str_replace("\t","",$record[2]);


	$record[2] = getrow(1,$record[3],$record[4],$record[5],$record[6],stripslashes($record[2]));
	$record[2] = getrow(2,$record[11],$record[12],$record[13],$record[14],stripslashes($record[2]));
	$record[2] = getrow(3,$record[15],$record[16],$record[17],$record[18],stripslashes($record[2]));
	$record[2] = getrow(4,$record[19],$record[20],$record[21],$record[22],stripslashes($record[2]));
	$record[2] = getrow(5,$record[23],$record[24],$record[25],$record[26],stripslashes($record[2]));
	$record[2] = getrow(6,$record[27],$record[28],$record[29],$record[30],stripslashes($record[2]));

	$record[2] = changechars($record[2]);

	$pdf->WriteHTML($record[2],'',$record[1]);

	$pdf->SetX(9);
	$pdf->SetLeftMargin('9');
	$pdf->SetFont('Arial','','10');
	$pdf->SetTextColor(147,131,68);
	$record[7] = str_replace("<br />","<br>",$record[7]);
	$record[7] = str_replace("\r\n","",$record[7]);
	$record[7] = str_replace("\n","",$record[7]);
	$record[7] = str_replace("\r","",$record[7]);
	$record[7] = str_replace("\t","",$record[7]);
	$record[7] = getrow(1,$record[3],$record[4],$record[5],$record[6],stripslashes($record[7]));
	$record[7] = getrow(2,$record[11],$record[12],$record[13],$record[14],stripslashes($record[7]));
	$record[7] = getrow(3,$record[15],$record[16],$record[17],$record[18],stripslashes($record[7]));
	$record[7] = getrow(4,$record[19],$record[20],$record[21],$record[22],stripslashes($record[7]));
	$record[7] = getrow(5,$record[23],$record[24],$record[25],$record[26],stripslashes($record[7]));
	$record[7] = getrow(6,$record[27],$record[28],$record[29],$record[30],stripslashes($record[7]));

	$record[7] = changechars($record[7]);
	
	$pdf->WriteHTML($record[7],'',$record[1]);
	 
	$bullet_list = array();
	$record[8] = str_replace("<br />","<br>",$record[8]);
	$record[8] = str_replace("<ul>","",$record[8]);
	$record[8] = str_replace("</ul>","",$record[8]);
	$record[8] = str_replace("<li>","<p>",$record[8]);
	$record[8] = str_replace("</li>","</p>",$record[8]);
	$record[8] = str_replace("\r\n","",$record[8]);
	$record[8] = str_replace("\n","",$record[8]);
	$record[8] = str_replace("\r","",$record[8]);
	$record[8] = str_replace("\t","",$record[8]);

	$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $record[8], $matches);

	for ($i = 0; $i < $count; ++$i) {
		$bullet_list[] = $matches[0][$i];
	}

	$added_newpage = "No";
	
	$pdf->SetX(8);
	$pdf->SetLeftMargin('8');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','','9');
	$pdf->SetTextColor(114,112,112);
	$count = 0;
	
	foreach($bullet_list as $bullet_record) {
		$count++;
		$gety = $pdf->GetY();
		$newy = $gety + 6;
		$pdf->SetY($gety - 3.5);
		
		$bullet_record = changechars($bullet_record);
	
		$sum1 = 18.4;
		$sum2 = 80;
	
		if($newy > 264) {
			$pdf->AddPage();
			$pdf->SetAuthor('Ionian Weddings');
			$pdf->SetTitle('');
			
			$pdf->SetY('5');
			$pdf->SetFont('Arial','B','10');  
			$pdf->SetTextColor(226,179,64);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetDrawColor(226,179,64);
			$pdf->SetLineWidth(0.1); 
			$pdf->SetLeftMargin('100');
			//$pdf->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
			$pdf->Cell(70,5,$record[31],'',0,'l',true);
			$pdf->Ln(4.2); 
			$pdf->SetFont('Arial','B','10'); 
			$pdf->SetTextColor(143,126,122);
			$pdf->Cell(70,5,stripslashes($record[1]),'',0,'l',true);
			$pdf->Ln(8);
			
			$pdf->SetY('5');
			$pdf->SetX('5');
			$pdf->SetLeftMargin('10');
		
			$pdf->Image($header_image, 10, 3,60);
			$pdf->Image($bar_image, 10, 19, 190,0.1);
			
			$pdf->SetY('24');
			$pdf->SetX(8);
			$pdf->SetLeftMargin('8');
			$pdf->SetFont('Arial','','9');
			$pdf->SetTextColor(114,112,112);
			
			$gety = $pdf->GetY();
			$newy = $gety + 6;
			$pdf->SetY($gety - 3.5);
	
		} else {
			$pdf->SetX(10);
		}
	
		$pdf->SetLeftMargin('10');
		$pdf->Image("../images/bullet.jpg", 10, $newy, (8*$sum1)/$sum2,(7*$sum1)/$sum2);
		$pdf->SetLeftMargin('14');
		$pdf->WriteHTML(stripslashes($bullet_record),'no','',$record[1]);
	
	}

	$pdf->SetX(9);
	$pdf->SetLeftMargin('9');
	$pdf->SetFont('Arial','','10');
	$pdf->SetTextColor(82,81,87);
	$record[9] = str_replace("<br />","<br>",$record[9]);
	$record[9] = str_replace("\r\n","",$record[9]);
	$record[9] = str_replace("\n","",$record[9]);
	$record[9] = str_replace("\r","",$record[9]);
	$record[9] = str_replace("\t","",$record[9]);
	$record[9] = getrow(1,$record[3],$record[4],$record[5],$record[6],stripslashes($record[9]));
	$record[9] = getrow(2,$record[11],$record[12],$record[13],$record[14],stripslashes($record[9]));
	$record[9] = getrow(3,$record[15],$record[16],$record[17],$record[18],stripslashes($record[9]));
	$record[9] = getrow(4,$record[19],$record[20],$record[21],$record[22],stripslashes($record[9]));
	$record[9] = getrow(5,$record[23],$record[24],$record[25],$record[26],stripslashes($record[9]));
	$record[9] = getrow(6,$record[27],$record[28],$record[29],$record[30],stripslashes($record[9]));

	$record[9] = changechars($record[9]);
	
	$pdf->WriteHTML($record[9],'',$record[1]);

	if($record[10]) {
	
		$get_current_y = $pdf->GetY();
		
		if($get_current_y > 240) {
			$pdf->AddPage();
			$pdf->SetAuthor('Ionian Weddings');
			$pdf->SetTitle('');
		
			$pdf->SetY('5');
			$pdf->SetFont('Arial','B','10');  
			$pdf->SetTextColor(226,179,64);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetDrawColor(226,179,64);
			$pdf->SetLineWidth(0.1); 
			$pdf->SetLeftMargin('100');
			//$pdf->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
			$pdf->Cell(70,5,$record[31],'',0,'l',true);
			$pdf->Ln(4.2); 
			$pdf->SetFont('Arial','B','10'); 
			$pdf->SetTextColor(143,126,122);
			$pdf->Cell(70,5,stripslashes($record[1]),'',0,'l',true);
			$pdf->Ln(8);
			
			$pdf->SetY('5');
			$pdf->SetX('5');
			$pdf->SetLeftMargin('10');
			
			$pdf->Image($header_image, 10, 3,60);
			$pdf->Image($bar_image, 10, 19, 190,0.1);
			
			$pdf->SetY('24');
			$pdf->SetX(8);
			$pdf->SetLeftMargin('8');
			$pdf->SetFont('Arial','','9');
			$pdf->SetTextColor(82,81,87);
		
		} else {
			$pdf->Ln(10);
		}
			$pdf->SetX(9);
			$pdf->SetLeftMargin('9');
			$pdf->SetTextColor(82,81,87);
			$pdf->SetFont('Arial','B','12'); 
			$pdf->Write(5,'Notes','');
			$pdf->SetFont('Arial','','9');
			$pdf->SetX(9);
			$pdf->SetLeftMargin('9');
			$record[10] = str_replace("<br />","<br>",$record[10]);
			$record[10] = str_replace("<ul>","<br>",$record[10]);
			$record[10] = str_replace("</ul>","",$record[10]);
			$record[10] = str_replace("<li>",chr(149)." ",$record[10]);
			$record[10] = str_replace("</li>","<br>",$record[10]);
			$record[10] = str_replace("\r\n","",$record[10]);
			$record[10] = str_replace("\n","",$record[10]);
			$record[10] = str_replace("\r","",$record[10]);
			$record[10] = str_replace("\t","",$record[10]);
			$record[10] = getrow(1,$record[3],$record[4],$record[5],$record[6],stripslashes($record[10]));
			$record[10] = getrow(2,$record[11],$record[12],$record[13],$record[14],stripslashes($record[10]));
			$record[10] = getrow(3,$record[15],$record[16],$record[17],$record[18],stripslashes($record[10]));
			$record[10] = getrow(4,$record[19],$record[20],$record[21],$record[22],stripslashes($record[10]));
			$record[10] = getrow(5,$record[23],$record[24],$record[25],$record[26],stripslashes($record[10]));
			$record[10] = getrow(6,$record[27],$record[28],$record[29],$record[30],stripslashes($record[10]));
		
			$record[10] = changechars($record[10]);
			
			$pdf->WriteHTML($record[10],'',$record[1]);

//			$note_list = array();
//			$record[10] = str_replace("<br />","<br>",$record[10]);
//			$record[10] = str_replace("<ul>","<br>",$record[10]);
//			$record[10] = str_replace("</ul>","<br>",$record[10]);
//			$record[10] = str_replace("\r\n","",$record[10]);
//			$record[10] = str_replace("\n","",$record[10]);
//			$record[10] = str_replace("\r","",$record[10]);
//			$record[10] = str_replace("\t","",$record[10]);
		
			//$count = preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $record[10], $matches);
//		
//			for ($i = 0; $i < $count; ++$i) {
//				$note_list[] = $matches[0][$i];
//			}
//		
//			$added_newpage = "No";
//			
//			$pdf->SetX(9);
//			$pdf->SetLeftMargin('9');
//			$pdf->SetTextColor(82,81,87);
//			$pdf->SetFont('Arial','B','12'); 
//			$pdf->Write(5,'Notes','');
//			$pdf->SetFont('Arial','','9');
//			$pdf->SetX(9);
//			$pdf->SetLeftMargin('9');
//			$count = 0;
//			
//			foreach($note_list as $note_record) {
//				$count++;
//				$gety = $pdf->GetY();
//				$newy = $gety + 6;
//				$pdf->SetY($gety - 3.5);
//				
//				$note_record = changechars($note_record);
//			
//				$sum1 = 18.4;
//				$sum2 = 80;
//			
//				if($newy > 264) {
//					$pdf->AddPage();
//					$pdf->SetAuthor('Ionian Weddings');
//					$pdf->SetTitle('');
//					
//					$pdf->SetY('5');
//					$pdf->SetFont('Arial','B','10');  
//					$pdf->SetTextColor(226,179,64);
//					$pdf->SetFillColor(255,255,255);
//					$pdf->SetDrawColor(226,179,64);
//					$pdf->SetLineWidth(0.1); 
//					$pdf->SetLeftMargin('100');
//					//$pdf->Cell(70,5,"Your personalised Wedding Proposal",'',0,'l',true);
//					$pdf->Cell(70,5,$record[31],'',0,'l',true);
//					$pdf->Ln(4.2); 
//					$pdf->SetFont('Arial','B','10'); 
//					$pdf->SetTextColor(143,126,122);
//					$pdf->Cell(70,5,stripslashes($record[1]),'',0,'l',true);
//					$pdf->Ln(8);
//					
//					$pdf->SetY('5');
//					$pdf->SetX('5');
//					$pdf->SetLeftMargin('10');
//				
//					$pdf->Image($header_image, 10, 3,60);
//					$pdf->Image($bar_image, 10, 19, 190,0.1);
//					
//					$pdf->SetY('24');
//					$pdf->SetX(8);
//					$pdf->SetLeftMargin('8');
//					$pdf->SetFont('Arial','','9');
//					$pdf->SetTextColor(114,112,112);
//					
//					$gety = $pdf->GetY();
//					$newy = $gety + 6;
//					$pdf->SetY($gety - 3.5);
//			
//				} else {
//					$pdf->SetX(10);
//				}
//			
//				$pdf->SetLeftMargin('10');
//				$pdf->Image("../images/bullet.jpg", 10, $newy, (8*$sum1)/$sum2,(7*$sum1)/$sum2);
//				$pdf->SetLeftMargin('14');
//				$pdf->WriteHTML(stripslashes($note_record),'no','',$record[1]);
//			
//			}
 
		}

	$external_link = strtolower(trim($record[1]));
	$external_link = str_replace(" - ", " ", $external_link);
	$external_link = str_replace("-", " ", $external_link);
	$external_link = str_replace(" ", "-", $external_link);
	$external_link = ereg_replace("[^A-Za-z0-9-]", "", $external_link);
	$external_link = str_replace("--", "-", $external_link);
	$external_link = str_replace("----", "-", $external_link);
	$external_link = str_replace("-----", "-", $external_link);
	$external_link = str_replace("------", "-", $external_link);
	
	
	$pdf->output($external_link.".pdf","D");
	$sql_command->close();

} elseif($_POST["action"] == "Generate PDF") {


	if(!$_POST["header_title"]) { $error .= "Document Title <br>"; }
	if(!$_POST["introduction_title"]) { $error .= "Missing Introduction Title<br>"; }
	if(!$_POST["introduction_body"]) { $error .= "Missing Introduction Body<br>"; }
	if(!$_POST["body_content"]) { $error .= "Missing Body Content<br>"; }
	if(!$_POST["bullet_points"]) { $error .= "Bullet Points<br>"; }
	
	
	$_SESSION["header_title"] = $_POST["header_title"];
	$_SESSION["introduction_title"] = $_POST["introduction_title"];
	$_SESSION["introduction_body"] = $_POST["introduction_body"];
	$_SESSION["image_ref1"] = $_POST["image_ref1"];
	$_SESSION["image_ref2"] = $_POST["image_ref2"];
	$_SESSION["image_ref3"] = $_POST["image_ref3"];
	$_SESSION["image_ref4"] = $_POST["image_ref4"];
	$_SESSION["body_content"] = $_POST["body_content"];
	$_SESSION["bullet_points"] = $_POST["bullet_points"];
	$_SESSION["salutation"] = $_POST["salutation"];
	$_SESSION["notes"] = $_POST["notes"];

	if($error) {
	$get_template->topHTML();
	$get_template->errorHTML("Generate PDF","Oops!","$error","Link","oos/pdf-generator.php");
	$get_template->bottomHTML();
	$sql_command->close();
}

$cols = "introduction_title,introduction_body,image_ref1,image_ref2,image_ref3,image_ref4,body_content,bullet_list,closing_salutation,notes,image_ref5,image_ref6,image_ref7,image_ref8,image_ref9,image_ref10,image_ref11,image_ref12,image_ref13,image_ref14,image_ref15,image_ref16,image_ref17,image_ref18,image_ref19,image_ref20,image_ref21,image_ref22,image_ref23,image_ref24,header_title";

$_SESSION["introduction_title"] = $_POST["introduction_title"];
$_SESSION["introduction_body"] = $_POST["introduction_body"];
$_SESSION["image_ref1"] = $_POST["image_ref1"];
$_SESSION["image_ref2"] = $_POST["image_ref2"];
$_SESSION["image_ref3"] = $_POST["image_ref3"];
$_SESSION["image_ref4"] = $_POST["image_ref4"];
$_SESSION["body_content"] = $_POST["body_content"];
$_SESSION["bullet_points"] = $_POST["bullet_points"];
$_SESSION["salutation"] = $_POST["salutation"];
$_SESSION["notes"] = $_POST["notes"];

$_SESSION["image_ref5"] = $_POST["image_ref5"];
$_SESSION["image_ref6"] = $_POST["image_ref6"];
$_SESSION["image_ref7"] = $_POST["image_ref7"];
$_SESSION["image_ref8"] = $_POST["image_ref8"];
$_SESSION["image_ref9"] = $_POST["image_ref9"];
$_SESSION["image_ref10"] = $_POST["image_ref10"];
$_SESSION["image_ref11"] = $_POST["image_ref11"];
$_SESSION["image_ref12"] = $_POST["image_ref12"];
$_SESSION["image_ref13"] = $_POST["image_ref13"];
$_SESSION["image_ref14"] = $_POST["image_ref14"];
$_SESSION["image_ref15"] = $_POST["image_ref15"];
$_SESSION["image_ref16"] = $_POST["image_ref16"];
$_SESSION["image_ref17"] = $_POST["image_ref17"];
$_SESSION["image_ref18"] = $_POST["image_ref18"];
$_SESSION["image_ref19"] = $_POST["image_ref19"];
$_SESSION["image_ref20"] = $_POST["image_ref20"];
$_SESSION["image_ref21"] = $_POST["image_ref21"];
$_SESSION["image_ref22"] = $_POST["image_ref22"];
$_SESSION["image_ref23"] = $_POST["image_ref23"];
$_SESSION["image_ref24"] = $_POST["image_ref24"];

$_SESSION["header_title"] = $_POST["header_title"];

$values = "'".addslashes($_POST["introduction_title"])."',
'".addslashes($_POST["introduction_body"])."',
'".addslashes($_POST["image_ref1"])."',
'".addslashes($_POST["image_ref2"])."',
'".addslashes($_POST["image_ref3"])."',
'".addslashes($_POST["image_ref4"])."',
'".addslashes($_POST["body_content"])."',
'".addslashes($_POST["bullet_points"])."',
'".addslashes($_POST["salutation"])."',
'".addslashes($_POST["notes"])."',
'".addslashes($_POST["image_ref5"])."',
'".addslashes($_POST["image_ref6"])."',
'".addslashes($_POST["image_ref7"])."',
'".addslashes($_POST["image_ref8"])."',
'".addslashes($_POST["image_ref9"])."',
'".addslashes($_POST["image_ref10"])."',
'".addslashes($_POST["image_ref11"])."',
'".addslashes($_POST["image_ref12"])."',
'".addslashes($_POST["image_ref13"])."',
'".addslashes($_POST["image_ref14"])."',
'".addslashes($_POST["image_ref15"])."',
'".addslashes($_POST["image_ref16"])."',
'".addslashes($_POST["image_ref17"])."',
'".addslashes($_POST["image_ref18"])."',
'".addslashes($_POST["image_ref19"])."',
'".addslashes($_POST["image_ref20"])."',
'".addslashes($_POST["image_ref21"])."',
'".addslashes($_POST["image_ref22"])."',
'".addslashes($_POST["image_ref23"])."',
'".addslashes($_POST["image_ref24"])."',
'".addslashes($_POST["header_title"])."'";

$sql_command->insert($database_pdf_generator,$cols,$values);
$maxid = $sql_command->maxid($database_pdf_generator,"id");


$_SESSION["introduction_title"] = "";
$_SESSION["introduction_body"] = "";
$_SESSION["image_ref1"] = "";
$_SESSION["image_ref2"] = "";
$_SESSION["image_ref3"] = "";
$_SESSION["image_ref4"] = "";
$_SESSION["body_content"] = "";
$_SESSION["bullet_points"] = "";
$_SESSION["salutation"] = "";
$_SESSION["notes"] = "";

$_SESSION["image_ref5"] = "";
$_SESSION["image_ref6"] = "";
$_SESSION["image_ref7"] = "";
$_SESSION["image_ref8"] = "";
$_SESSION["image_ref9"] = "";
$_SESSION["image_ref10"] = "";
$_SESSION["image_ref11"] = "";
$_SESSION["image_ref12"] = "";
$_SESSION["image_ref13"] = "";
$_SESSION["image_ref14"] = "";
$_SESSION["image_ref15"] = "";
$_SESSION["image_ref16"] = "";
$_SESSION["image_ref17"] = "";
$_SESSION["image_ref18"] = "";
$_SESSION["image_ref19"] = "";
$_SESSION["image_ref20"] = "";
$_SESSION["image_ref21"] = "";
$_SESSION["image_ref22"] = "";
$_SESSION["image_ref23"] = "";
$_SESSION["image_ref24"] = "";

$_SESSION["header_title"] = "";

$get_template->topHTML();
?>

<h1>PDF Generated</h1>
<p>To view/download the Generated PDF, <a href="pdf-generator.php?id=<?php echo $maxid; ?>" target="_blank">click here</a></p>
<p>
	<input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/pdf-generator.php'">
</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {



$add_header = "<script type=\"text/javascript\">

function close_imagemodule() {
$('#image_module_bg').hide();
$('#image_module_html').html();
$('#image_module_html').hide();
}

function open_imagemodule(folder,page,subfolder) {

$.get('".$site_url."/oos/image-module.php?folder=' + folder + '&page=' + page + '&subfolder=' + subfolder, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

function view_image(folder,fileid) {

$.get('".$site_url."/oos/view-file-module.php?folder=' + folder + '&fileid=' + fileid, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}
</script>
<script type=\"text/javascript\">
function add_image(reference,size) {

var selected_value =  $(\"#image_ref_selection option:selected\").val();

if(selected_value == \"Image1\") {
$(\"#image_ref1\").val(reference);	
$(\"#image_ref1_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 1) image 1');	
} else if(selected_value == \"Image2\") {
$(\"#image_ref2\").val(reference);	
$(\"#image_ref2_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 2');
} else if(selected_value == \"Image3\") {
$(\"#image_ref3\").val(reference);	
$(\"#image_ref3_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 3');
} else if(selected_value == \"Image4\") {
$(\"#image_ref4\").val(reference);	
$(\"#image_ref4_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 4');
} else if(selected_value == \"Image5\") {
$(\"#image_ref5\").val(reference);	
$(\"#image_ref5_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 1');
} else if(selected_value == \"Image6\") {
$(\"#image_ref6\").val(reference);	
$(\"#image_ref6_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 2');
} else if(selected_value == \"Image7\") {
$(\"#image_ref7\").val(reference);	
$(\"#image_ref7_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 3');
} else if(selected_value == \"Image8\") {
$(\"#image_ref8\").val(reference);	
$(\"#image_ref8_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 4');
} else if(selected_value == \"Image9\") {
$(\"#image_ref9\").val(reference);	
$(\"#image_ref9_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 1');
} else if(selected_value == \"Image10\") {
$(\"#image_ref10\").val(reference);	
$(\"#image_ref10_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 2');
} else if(selected_value == \"Image11\") {
$(\"#image_ref11\").val(reference);	
$(\"#image_ref11_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 3');
} else if(selected_value == \"Image12\") {
$(\"#image_ref12\").val(reference);	
$(\"#image_ref12_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 4');
} else if(selected_value == \"Image13\") {
$(\"#image_ref13\").val(reference);	
$(\"#image_ref13_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 1');
} else if(selected_value == \"Image14\") {
$(\"#image_ref14\").val(reference);	
$(\"#image_ref14_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 2');
} else if(selected_value == \"Image15\") {
$(\"#image_ref15\").val(reference);	
$(\"#image_ref15_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 3');
} else if(selected_value == \"Image16\") {
$(\"#image_ref16\").val(reference);	
$(\"#image_ref16_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 4');
} else if(selected_value == \"Image17\") {
$(\"#image_ref17\").val(reference);	
$(\"#image_ref17_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 1');
} else if(selected_value == \"Image18\") {
$(\"#image_ref18\").val(reference);	
$(\"#image_ref18_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 2');
} else if(selected_value == \"Image19\") {
$(\"#image_ref19\").val(reference);	
$(\"#image_ref19_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 3');
} else if(selected_value == \"Image20\") {
$(\"#image_ref20\").val(reference);	
$(\"#image_ref20_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 4');
} else if(selected_value == \"Image21\") {
$(\"#image_ref21\").val(reference);	
$(\"#image_ref21_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 1');
} else if(selected_value == \"Image22\") {
$(\"#image_ref22\").val(reference);	
$(\"#image_ref22_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 2');
} else if(selected_value == \"Image23\") {
$(\"#image_ref23\").val(reference);	
$(\"#image_ref23_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 3');
} else if(selected_value == \"Image24\") {
$(\"#image_ref24\").val(reference);	
$(\"#image_ref24_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 4');
}

}
function open_awaitingcrop(id,page) {

$.get('".$site_url."/oos/view-crop-module.php?id=' + id + '&page=' + page, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

</script>

<script type=\"text/javascript\">

function open_search() {

$.get('".$site_url."/oos/search-module.php', function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

function open_originals(id,page) {

$.get('".$site_url."/oos/original-module.php?id=' + id + '&page=' + page, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}


function subfolder(mode,id) {

$.get('".$site_url."/oos/folder-module.php?mode=' + mode + '&id=' + id, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

</script>
<style type=\"text/css\">
#image_module_html {
position:absolute;
display:none;
width: 900px;
height:700px;
z-index:1000;
background-color:#dcddde;
display:none;
text-align:left;
}

#image_module_bg {
position:fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index:999;
background-color:#000;
opacity:0.8;
display:none;
}
</style>
<script type=\"text/javascript\">
function showdiv(div) {
$('#show_row1').hide();	
$('#show_row2').hide();	
$('#show_row3').hide();	
$('#show_row4').hide();	
$('#show_row5').hide();	
$('#show_row6').hide();	

$('#' + div).show();
}
</script>
";

$body_top = "<div id=\"image_module_bg\"></div><div id=\"image_module_html\"></div>";

$get_template->topHTML();
?>
<h1>PDF Generator</h1>
<form action="<?php echo $site_url; ?>/oos/pdf-generator.php" method="post" >
	<h3 style="margin-top:10px;">Images</h3>
	<p>You can place 4 images across the PDF depending on size selected ( <span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">Open Image Managment</span> )</p>
	<h3 onclick="showdiv('show_row1');" style="cursor:pointer;">View Row 1</h3>
	<div id="show_row1" style="display:block;">
		<p>Please enter [insert_row_1] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref1" id="image_ref1" style="width:250px;" value="<?php echo $_SESSION["image_ref1"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref1_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref2" id="image_ref2" style="width:250px;" value="<?php echo $_SESSION["image_ref2"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref2_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref3" id="image_ref3" style="width:250px;" value="<?php echo $_SESSION["image_ref3"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref3_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref4" id="image_ref4" style="width:250px;" value="<?php echo $_SESSION["image_ref4"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref4_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<h3 onclick="showdiv('show_row2');" style="cursor:pointer;">View Row 2</h3>
	<div id="show_row2" style="display:none;">
		<p>Please enter [insert_row_2] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref5" id="image_ref5" style="width:250px;" value="<?php echo $_SESSION["image_ref5"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref5_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref6" id="image_ref6" style="width:250px;" value="<?php echo $_SESSION["image_ref6"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref6_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref7" id="image_ref7" style="width:250px;" value="<?php echo $_SESSION["image_ref7"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref7_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref8" id="image_ref8" style="width:250px;" value="<?php echo $_SESSION["image_ref8"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref8_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<h3 onclick="showdiv('show_row3');" style="cursor:pointer;">View Row 3</h3>
	<div id="show_row3" style="display:none;">
		<p>Please enter [insert_row_3] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref9" id="image_ref9" style="width:250px;" value="<?php echo $_SESSION["image_ref9"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref9_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref10" id="image_ref10" style="width:250px;" value="<?php echo $_SESSION["image_ref10"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref10_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref11" id="image_ref11" style="width:250px;" value="<?php echo $_SESSION["image_ref11"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref11_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref12" id="image_ref12" style="width:250px;" value="<?php echo $_SESSION["image_ref12"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref12_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<h3 onclick="showdiv('show_row4');" style="cursor:pointer;">View Row 4</h3>
	<div  id="show_row4" style="display:none;">
		<p>Please enter [insert_row_4] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref13" id="image_ref13" style="width:250px;" value="<?php echo $_SESSION["image_ref13"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref13_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref14" id="image_ref14" style="width:250px;" value="<?php echo $_SESSION["image_ref14"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref14_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref15" id="image_ref15" style="width:250px;" value="<?php echo $_SESSION["image_ref15"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref15_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref16" id="image_ref16" style="width:250px;" value="<?php echo $_SESSION["image_ref16"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref16_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<h3 onclick="showdiv('show_row5');" style="cursor:pointer;">View Row 5</h3>
	<div id="show_row5" style="display:none;">
		<p>Please enter [insert_row_5] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref17" id="image_ref17" style="width:250px;" value="<?php echo $_SESSION["image_ref17"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref17_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref18" id="image_ref18" style="width:250px;" value="<?php echo $_SESSION["image_ref18"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref18_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref19" id="image_ref19" style="width:250px;" value="<?php echo $_SESSION["image_ref19"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref19_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref20" id="image_ref20" style="width:250px;" value="<?php echo $_SESSION["image_ref20"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref20_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<h3 onclick="showdiv('show_row6');" style="cursor:pointer;">View Row 6</h3>
	<div id="show_row6" style="display:none;">
		<p>Please enter [insert_row_6] on a new line in its own P element in the introduction text to insert this row.</p>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref21" id="image_ref21" style="width:250px;" value="<?php echo $_SESSION["image_ref21"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref21_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref22" id="image_ref22" style="width:250px;" value="<?php echo $_SESSION["image_ref22"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref22_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref23" id="image_ref23" style="width:250px;" value="<?php echo $_SESSION["image_ref23"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref23_size"></span></div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="image_ref24" id="image_ref24" style="width:250px;" value="<?php echo $_SESSION["image_ref24"]; ?>"/>
		</div>
		<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref24_size"></span></div>
		<div style="clear:left;"></div>
	</div>
	<p>
	<hr />
	</p>
	<?php if(!$_SESSION["header_title"]) {
$_SESSION["header_title"] = "Your Mediterranean Wedding Proposal";
} ?>
	<h3>Document Title <span style="color:#000;">(required)</span></h3>
	<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="header_title" style="width:500px;" value="<?php echo $_SESSION["header_title"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<h3>Introduction <span style="color:#000;">(required)</span></h3>
	<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="introduction_title" style="width:500px;" value="<?php echo $_SESSION["introduction_title"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<textarea name="introduction_body" id="the_editor_min" class="the_editor_min"><?php echo $_SESSION["introduction_body"]; ?></textarea>
	<script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
					});
</script>
	<?php if(!$_SESSION["body_content"]) {
$_SESSION["body_content"] = "<p>The package below has been created to include all the essential elements for your [enter_introduction_name].<br>You may of course add any extras of your choice</p>";
} ?>
	<h3 style="margin-top:10px;">Body Content <span style="color:#000;">(required)</span></h3>
	<textarea name="body_content" id="the_editor_min2" class="the_editor_min2"><?php echo $_SESSION["body_content"]; ?></textarea>
	<script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min2',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
					});
</script>
	<h3 style="margin-top:10px;">Bullet Points <span style="color:#000;">(required)</span></h3>
	<p>Each &lt;P&gt; tag will be a bullet point</p>
	<textarea name="bullet_points" id="the_editor_min3" class="the_editor_min3"><?php echo $_SESSION["bullet_points"]; ?></textarea>
	<script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min3',
					{
						skin : 'kama',
						toolbar : 'Bullet',
						width: 700,
						height: 400,
						
						on :
		{
			instanceReady : function( ev )
			{
				this.dataProcessor.writer.setRules( 'p',
					{
						indent : false,
						breakBeforeOpen : true,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : true
					});
			}
		}
					});

</script>
	<h3 style="margin-top:10px;">Closing Salutation</h3>
	<?php if(!$_SESSION["salutation"]) {
$_SESSION["salutation"] = "<p>The cost of this package is €[enter_cost] for [enter-year]<br>Ionian Weddings arrangement fee is €[enter_agreement_fee]</p>";
} ?>
	<textarea name="salutation" id="the_editor_min4" class="the_editor_min4"><?php echo $_SESSION["salutation"]; ?></textarea>
	<script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min4',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
					});
</script>
	<h3 style="margin-top:10px;">Notes</h3>
	<textarea name="notes" id="the_editor_min5" class="the_editor_min5"><?php echo $_SESSION["notes"]; ?></textarea>
	<script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min5',
					{
						skin : 'kama',
						toolbar : 'Note',
						width: 700,
						height: 200,
					});
</script>
	<p style="margin-top:10px;">
		<input type="submit" name="action" value="Generate PDF">
	</p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>
