<?

$header_image = "../images/invoice_header.jpg";
						$ring_image = "../images/invoice_rings.jpg";
						$bar_image = "../images/invoice_line.jpg";
			
						$result = $sql_command->select("$database_supplier_invoices_main,
													   $database_supplier_details",
										 			   "$database_supplier_details.id,
													   $database_supplier_details.contact_title,
													   $database_supplier_details.contact_firstname,
													   $database_supplier_details.contact_surname,
													   $database_supplier_details.contact_email,
													   $database_supplier_details.contact_tel,
													   $database_supplier_details.company_name,
													   $database_supplier_details.address_1,
													   $database_supplier_details.address_2,
													   $database_supplier_details.address_3,
													   $database_supplier_details.address_town,
													   $database_supplier_details.address_county,
													   $database_supplier_details.address_country,
													   $database_supplier_details.address_postcode,
													   $database_supplier_details.timestamp,
													   $database_supplier_details.code,
													   $database_supplier_invoices_main.exchange_rate,
													   $database_supplier_invoices_main.order_id,
													   $database_supplier_invoices_main.status,
													   $database_supplier_invoices_main.timestamp",
													   "WHERE $database_supplier_details.id=$database_supplier_invoices_main.supplier_id 
													   AND $database_supplier_invoices_main.id='".addslashes($po[0])."'");
						$record = $sql_command->result($result);
						
						$pdf = new PDF();

						$pdf->AliasNbPages();
						$pdf->tFPDF();
						$pdf->AddPage();
						$pdf->AddFont('Arial', '','', true);
 						$pdf->AddFont('Arial', 'B','', true);

						$pdf->SetAuthor('Ionian Weddings');
						$pdf->SetTitle('Purchase Order '.$id);

						$pdf->SetY('5');
						$pdf->SetFont('Arial','','18');  
						$pdf->SetTextColor(226,179,64);
						$pdf->SetFillColor(255,255,255);
						$pdf->SetDrawColor(226,179,64);
						$pdf->SetLineWidth(0.1); 
						$pdf->SetLeftMargin('130');
						
						if($record[18] == "Outstanding") {
							$pdf->Cell(70,5,"Purchase Order - Outstanding",'',0,'R',true);
						} elseif($record[18] == "Paid") {
							$pdf->Cell(70,5,"Purchase Order - Paid",'',0,'R',true);
						} elseif($record[18] == "Refunded") {
							$pdf->Cell(70,5,"Purchase Order - Refund",'',0,'R',true);
						} elseif($record[18] == "Pending") {
							$pdf->Cell(70,5,"Purchase Order - Pending",'',0,'R',true);
						} elseif($record[18] == "Cancelled") {
							$pdf->Cell(70,5,"Purchase Order - Cancelled",'',0,'R',true);
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
						$date = date("jS  F Y",$record[19]);
						$pdf->SetFont('Arial','B',8);
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
						$pdf->Write(0,$id);
						$pdf->Ln(5);
						
						$pdf->SetY('22');
						$pdf->SetX('5');
						$pdf->SetLeftMargin('10');
						// business name and address
						$pdf->SetFont('Arial','B',8);
						$pdf->SetTextColor(230,134,123); 
						$pdf->Write(0,$record[6]);
						$pdf->Ln(3.6);	 
						$pdf->SetFont('Arial','',8);
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
			
						$client_info_result = $sql_command->select("$database_order_details,
																   $database_clients",
																   "$database_clients.*",
																   "WHERE $database_order_details.client_id=$database_clients.id 
																   AND $database_order_details.id='".$record[17]."'");
						$client_info_record = $sql_command->result($client_info_result);
						
						$package_info_result = $sql_command->select("$database_order_details,
																	$database_packages,
																	$database_package_info,
																	$database_navigation",
																	"$database_packages.package_name,
																	$database_navigation.page_name",
																	"WHERE $database_order_details.id='".$record[17]."' 
																	AND $database_order_details.package_id=$database_package_info.id 
																	AND $database_package_info.package_id=$database_packages.id 
																	AND $database_packages.island_id=$database_navigation.id");
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
						$pdf->SetFont('','B',8);
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
						$pdf->SetFont('','B',8);
						$pdf->SetTextColor(88,88,111);
						$pdf->SetFillColor(255,255,255);  
						$pdf->SetDrawColor(226,179,64);  
						$pdf->SetLineWidth(0.1);
						
						$result2 = $sql_command->select("$database_supplier_invoices_details",
														"id,
														name,
														qty,
														cost,
														currency,
														code",
														"WHERE main_id='".addslashes($po[0])."'");
						$row2 = $sql_command->results($result2); 
			
						$codecheck = array();
			
						foreach($row2 as $record2) {
							
							$pochecks = $sql_command->select($database_order_history,
															"sum(qty),
															exchange_rate",
															"WHERE order_id='".addslashes($record[17])."'
															AND code = '".addslashes($record2[5])."'");
							$pocheck = $sql_command->result($pochecks);
			
							$qties = $pocheck[0];
							$code = $record2[5];
			
							if (abs($qties) > 0 && !in_array($code,$codecheck)) {
								$codecheck[] = $code;
								$record2[1] = str_replace("<strong>", "", $record2[1]);
								$record2[1] = str_replace("</strong>", "", $record2[1]);
								$record2[1] = str_replace("<u>", "", $record2[1]);
								$record2[1] = str_replace("</u>", "", $record2[1]);
								$record2[1] = str_replace("<i>", "", $record2[1]);
								$record2[1] = str_replace("</i>", "", $record2[1]);
								if(eregi("<p>",$record2[1])) {
									$start = strpos($record2[1], '<p>');
									$end = strpos($record2[1], '</p>', $start);
									$paragraph = substr($record2[1], $start, $end-$start+4);	
									$paragraph = str_replace("<p>", "", $paragraph);	
									$paragraph = str_replace("</p>", "", $paragraph);	
									$paragraph = (strlen($paragraph) > 150) ? substr($paragraph, 0, 150) . '...' : $paragraph;	
								} else {	
									$paragraph = stripslashes($record2[1]);	
								}	
						
								$paragraph = preg_replace('~[\r\n]+~', '', $paragraph);
								$paragraph = str_replace("&nbsp;", " ", $paragraph);
								$paragraph = str_replace("&amp;", "&", $paragraph);
								$filterv = array("<br>","<br />","<br/>");
								$paragraph = str_replace($filterv, "\n", $paragraph);
								$paragraph = trim(preg_replace('/\s\s+/', ' ', $paragraph));
								//$itemvalue = $record2[3] * $record[16];
						
							
								$itemvalue = $record2[3];
								$exrate=$pocheck[1];
								if($record2[4] != "Euro") { 
									$p_curreny = "£"; 
									$payment_total2 = $qties * ($itemvalue/$exrate);		
								} else {
									$p_curreny = "€";
									$payment_total2 = $qties * $itemvalue;
								}
								$payment_total += $payment_total2;
								if ($payment_total2 <> 0) {
									$pdf->SetLeftMargin('10');
									$pdf->SetFont('','',8);	
									$pdf->Cell(160.05,4,$record2[5].' - '.$paragraph.$test,'LR',0,'L',true);	
									$pdf->SetFont('','',8);	
									$pdf->Cell(9.95,5,$qties,'LR',0,'C',true);	
									$pdf->Cell(19.95,4,$p_curreny.' '.number_format($payment_total2,2),'LR',0,'R',true);	
									$pdf->Ln(4);
								}
							}	
						}
						
						$resp = $sql_command->select("supplier_payments,supplier_transactions",
											 "sum(supplier_payments.p_amount)",
											 "WHERE supplier_transactions.p_id = supplier_payments.pay_no 
											 AND supplier_transactions.status = 'Paid'
											 AND supplier_payments.status != 'Unpaid'
											 AND supplier_payments.main_id = '".addslashes($po[0])."'");
						$respr = $sql_command->result($resp);
						
						$paidam = $respr[0];
			
						$resps = $sql_command->select("supplier_payments,supplier_transactions",
													 "supplier_transactions.p_id,supplier_payments.p_amount,supplier_transactions.timestamp",
													 "WHERE supplier_transactions.p_id = supplier_payments.pay_no 
													 AND supplier_transactions.status = 'Paid'
													 AND supplier_payments.status != 'Unpaid'
													 AND supplier_payments.main_id = '".addslashes($po[0])."'");
						$respra = $sql_command->results($resps);
			
						foreach($respra as $pids) {
							$payam = $pids[1];
							$payd = $pids[2];
							$payids = $pids[0];
							if ($payam>0) {
								$pdf->SetLeftMargin('10');
								$pdf->SetFont('','',8);
								$pdf->Cell(160.05,4,"Part Payment IDs - ".$payids." (".$payd.")",'LR',0,'L',true);
								$pdf->SetFont('','',8);
								$pdf->Cell(9.95,5,"1",'LR',0,'C',true);
								$pdf->Cell(19.95,4,€.' '.number_format($payam,2),'LR',0,'R',true);
								$pdf->Ln(4);
							}
						}
			
						$pdf->Cell(189.95,0,'','T');
						// net amount
						$pdf->Ln(0.1);
						$pdf->SetFont('','B',8);
						$pdf->Cell(170,5,"Net",'LR',0,'R',true);
						$pdf->SetFont('','',8);
						$pdf->Cell(19.95,5,$p_curreny." ".number_format($payment_total,2),'LR',0,'R',true);
						$pdf->Ln(5.1);
						$pdf->Cell(189.95,0,'','T'); 
						$pdf->Ln(0.1);
			
						// vat amount
						//$pdf->Ln(0.1);
						//$pdf->SetFont('','B',7);
						//$pdf->Cell(170,5,"Vat",'LR',0,'R',true);
						//$pdf->SetFont('','',6);
						//$pdf->Cell(19.95,5,$p_curreny." ".number_format($getvat,2),'LR',0,'R',true);
						//$pdf->Ln(5.1);
						//$pdf->Cell(189.95,0,'','T'); 
						//$pdf->Ln(0.1);
			
						// total amount
						$pdf->SetFont('','B',8);
						$pdf->SetTextColor(0,0,0); 
						$pdf->SetFillColor(251,212,180);
						$pdf->SetDrawColor(226,179,64);  
						$pdf->SetLineWidth(0.1); 
						$payment_total = ($payment_total+$getvat) - $paidam;
						$pdf->Ln(0.1);
						$pdf->Cell(170,5,"Total",'LR',0,'R',true);
						$pdf->SetFont('','',8);
						$pdf->Cell(19.95,5,$p_curreny." ".number_format($payment_total,2),'LR',0,'R',true);
						$pdf->Ln(5.1);
						$pdf->Cell(189.95,0,'','T'); 
						$pdf->Ln(0.1);
			
						$comments_q = $sql_command->select("notes","note","WHERE note_primary_reference = '".addslashes($record[17])."' AND note_secondary_reference = '".addslashes($record[0])."' AND note_type = 'SupplierComment' AND extra = 'Yes'");
						$comments_r = $sql_command->result($comments_q);
			
						if ($comments_r) {
							$p_filter= array("<strong>","</strong>","<u>","</u>","<i>","</i>","&nbsp;","<ul>","</ul>");
							$p_note = str_replace($p_filter,"",$comments_r[0]);
							$p_note = str_replace("<p> ","<p>",$p_note);
							$p_note = str_replace("<li>","<p> • ",$p_note);
							$p_note = str_replace("</li>","</p>",$p_note);
							$p_note = preg_replace('~[\r\n]+~', '', $p_note);
							$p_note = str_replace("&nbsp;", " ", $p_note);
							$p_note = trim(preg_replace('/\s\s+/', ' ', $p_note));
							
							$pdf->SetFont('','',8);
							$pdf->SetTextColor(88,88,111);
							$pdf->SetFillColor(255,255,255);  
							$pdf->SetDrawColor(255,255,255); 
						
							$count = preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $p_note, $matches);
			
							$filter_p = array("<p>","</p>");
							for ($i = 0; $i < $count; ++$i) {
								$pdf->Cell(190,5,str_replace($filter_p,"",$matches[0][$i]),'LR',0,'L',true);
								$pdf->Ln(4);
							}
							
						}
						
						?>