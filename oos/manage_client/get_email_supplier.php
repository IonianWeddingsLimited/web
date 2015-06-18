<?
	//include("../_includes/function.smtp.php");
	//include("../PHPMailer-master/class.phpmailer.php");
	require_once '../PHPMailer-master/class.phpmailer.php';
	$mail = new PHPMailer();
	
	function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
	}

	$ids = (!$_POST['ids']) ? "" : $_POST['ids'];
	$e_ids = (!$_POST['e_ids']) ? "" : $_POST['e_ids'];
	$clientid = $_POST["clientid"];
	if($ids=="") { $error .= "Missing ID<br>"; }
	$message = "";
	$checkpo= array();

	if($error) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Supplier","Oops!","$error","Link","oos");
		$get_template->bottomHTML();
		$sql_command->close();
	}
	
	$c_query = $sql_command->select($database_clients,"wedding_date,title,firstname,lastname,groom_title,groom_firstname,groom_surname","WHERE id = '".addslashes($clientid)."'");
	$c_info = $sql_command->result($c_query);
	$WeddingDate = date("d-m-Y",$c_info[0]);
	$FullClientName = $c_info[1]." ".$c_info[2]." ".$c_info[3]." &amp; ".$c_info[4]." ".$c_info[5]." ".$c_info[6];

	$cols = "reference_id, reference_type, user_id, email, addressee, message, filename";
	$display = "<div id=\"email-wrap\"><form method=\"post\" action=\"".
	           "$site_url/oos/manage-client.php\" name=\"emailPDF\" id=\"emailPDF\">".
	           "<input type=\"hidden\" name=\"clientid\" value=\"".$clientid."\">";

	if ($_POST["action"]=="Email Selected Suppliers") {

		foreach ($ids as $id) {			
			
			$e_oid = "email_oid_".$id;
			$e_sid = "email_sid_".$id;
			$e_add = "email_name_".$id;
			$e_ema = "email_address_".$id;
			
			$e_orderid = $_POST[$e_oid];
			$e_supplierid = $_POST[$e_sid];
			
			$ioiddet = $sql_command->select("$database_supplier_details","contact_title,contact_firstname,contact_surname,contact_email,company_name","WHERE id = '".addslashes($e_supplierid)."'");
			$details = $sql_command->result($ioiddet);
		
			$e_name = ($_POST[$e_add]!="") ? $_POST[$e_add] : $details[0]." ".$details[1]." ".$details[2];
			$e_femail = ($_POST[$e_ema]!="")? $_POST[$e_ema] : $details[3];
			$e_company = $details[4];
			$userid = $the_username;
			$timest = time();
			$e_directory = $base_directory.'/_po/'.$e_supplierid;
			if(!is_dir($e_directory)){
				mkdir($e_directory, 0777, true);
			}
			if (!in_array($e_supplierid,$checkpo)) {
				$pocheck = $sql_command->select("$database_supplier_invoices_main, 
												$database_order_details",
												"$database_supplier_invoices_main.id",
												"where $database_supplier_invoices_main.supplier_id='".addslashes($e_supplierid)."' 
												and $database_supplier_invoices_main.order_id = $database_order_details.id 
												and $database_order_details.client_id='".addslashes($clientid)."' 
												and 
													($database_supplier_invoices_main.status!='Paid' 
													 or $database_supplier_invoices_main.status!='Cancelled')
												GROUP BY $database_supplier_invoices_main.id");
				$poresult = $sql_command->results($pocheck);
			$e_date = date("Y-m-d h:m:s");
			$e_filelist = "";
			$idlist = "";
			$extra_po = "";
			foreach ($poresult as $po) {

				if (in_array($po[0],$ids)) {
				$idlist .= "#".$po[0].", ";
				$e_file = "/".$e_supplierid."/".$po[0]."-".$timest.".pdf";
				$e_filelist .= "<a href=\"$site_url/oos/purchase-order.php?purchase_order=".$po[0]."\" style=\"color: #c08827;\">$site_url/_po".$e_file."</a><br />";
				$vals = "
					'".addslashes($po[0])."',
					'Supplier',
					'".addslashes($login_id)."',
					'".addslashes($e_femail)."',
					'".addslashes($e_name)."',
					'',
					'".addslashes($e_file)."',
					'".addslashes($e_date)."'		";
				$e_insert = $sql_command->insert("emails","$cols,created",$vals);
				}
			}

			$message ="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
			$message .="<tr>";
			$message .="<td width=\"217\" style=\"padding: 20px 0px 20px 0px;\">";
			$message .="<img src=\"$site_url/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
			$message .="<img src=\"$site_url/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
			$message .="</td>";
			$message .="<td style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
			$message .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
			$message .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
			$message .="</td>";
			$message .="</tr>";
			$message .="<tr>";
			$message .="<td colspan=\"2\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
			$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Purchase Orders</h1>";
			$message .="<br />";
			$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $e_name,</h2>";
			$message .="<br />";
			$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Please see Purchase Order from Ionian Weddings for the following wedding:</p>";
			$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">".$WeddingDate.", ".$FullClientName."</p>";
			$message .="<br />";
			$message .="<p><textarea name=\"email_message_".$id."\" style=\"width:80%;\"></textarea></p>";
			$message .= "<br />";
			$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">To view your purchase orders ".$idlist."please refer to the following link: <br />".$e_filelist."</p>";
			$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"$site_url/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
			$message .="<br />";
			$message .= "</td></tr><tr><td colspan=\"2\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"$site_url/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";
/*			$vals = "
			'".addslashes($e_supplierid)."',
			'Supplier',
			'".addslashes($login_id)."',
			'".addslashes($e_femail)."',
			'".addslashes($e_name)."',
			'',
			'".addslashes($e_file)."',
			'".addslashes($e_date)."'		";
			
			$e_insert = $sql_command->insert("emails","$cols,created",$vals);
			*/
			$sc_script .= "CKEDITOR.replace( 'email_message_".$id."',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";
			
			$display .= "<div style=\"float:left; width:100%; margin:5px;\">
					<p>
						<input type=\"checkbox\" name=\"ids[]\" value=\"".$id."\" checked=\"checked\"> ".$e_company." &nbsp;
						Name: <input type=\"text\" name=\"email_name_".$id."\" value=\"".$e_name."\">
						Email: <input type=\"text\" name=\"email_address_".$id."\" value=\"".$e_femail."\">
						<input type=\"hidden\" name=\"email_sid_".$id."\" value=\"".$e_supplierid."\">
						<input type=\"hidden\" name=\"email_oid_".$id."\" value=\"".$e_orderid."\">
						<input type=\"hidden\" name=\"email_file_".$id."\" value=\"".$e_file."\">
						<input type=\"hidden\" name=\"email_timestamp_".$id."\" value=\"".$timest."\">
					</p>
					<div style=\"background:#FFFFFF;\">
					".$message."
					</div>
				</div>
				<div style=\"clear:left;\"></div>";
		}	
			else { $display .= "<input type=\"hidden\" name=\"e_ids[]\" value=\"".$id."\">"; }
			$checkpo[] = $e_supplierid;
		}
		$display .= "<center><button type=\"submit\" name=\"back\" value=\"Back\" onClick=\"window.location.href='$site_url/oos/manage-client.php?a=history&id=".$clientid."'\">Back</button><input type=\"submit\" name=\"action\" value=\"Email PO to Suppliers\"></center></form></div>";
	
	}	elseif ($_POST["action"]=="Email PO to Suppliers")	{
		require('fpdf/class.fpdf.php');
		$emailadd = "payments@ionianweddings.co.uk"; 
		$emailname = "Ionian Weddings Ltd";
	
		$global_from = "<{$emailadd}> \"{$emailname}\"";
	
		//$mail = new smtp_email;

		$mail->Username = $smtp_email;
		$mail->Password = $smtp_password;
		$mail->Host =  $smpt_server;
		$mail->Port =  $smpt_port;
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 
		$mail->SetFrom($smtp_email,$tagline);	// Name is optional
		
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
				$this->Write(0,"© Copyright Ionian Weddings Ltd. ".date("Y")." – 10 Crane Mews, 32 Gould Road, Twickenham, England, TW2 6RS");
				$this->Ln(3.5); 
				$this->Write(0,"(t) / (f) +44 208 894 1991 - (e) weddings@ionianweddings.co.uk - (w) www.ionianweddings.co.uk");
				$this->Ln(3.5); 
				$this->Write(0,"Registered in England and Wales No. 06137035 | VAT Registration Number: 103185747");
			}
		}
	
		
		foreach ($ids as $id) {
			
			$e_femail	=	"";
			$e_name		=	"";
			$e_file		=	"";

			$e_sid = "email_sid_".$id;
			$e_oid = "email_oid_".$id;
			$e_add = "email_name_".$id;
			$e_ema = "email_address_".$id;
			$e_ts = "email_timestamp_".$id;
			
			$custom_message_c = "email_message_".$id;
			$custom_message = $_POST[$custom_message_c];
			$e_supplierid = $_POST[$e_sid];
			$e_orderid = $_POST[$e_oid];
			$e_timestamp = $_POST[$e_ts];
	
			$ioiddet = $sql_command->select("$database_supplier_details","contact_title,contact_firstname,contact_surname,contact_email,company_name","WHERE id = '".addslashes($e_supplierid)."'");
			$details = $sql_command->result($ioiddet);
			
			$e_name = ($_POST[$e_add]!="") ? $_POST[$e_add] : $details[0]." ".$details[1]." ".$details[2];
			$e_femail = ($_POST[$e_ema]!="")? $_POST[$e_ema] : $details[3];
			$e_company = $details[4];
			$userid = $the_username;
			$timest = time();
			
			$e_date = date("Y-m-d h:m:s");
			
			
			$mail->IsSMTP();
			
			$mail->From				=	$emailadd;
			$mail->FromName			=	$emailname;
			$mail->AddReplyTo($emailadd, $emailname);
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			//$e_femail = "jason4k@msn.com";
			$mail->AddAddress($e_femail,$e_name);
			
			
			if (!in_array($e_supplierid,$checkpo)) {
				$pocheck = $sql_command->select("$database_supplier_invoices_main, 
												$database_order_details",
												"$database_supplier_invoices_main.id",
												"where $database_supplier_invoices_main.supplier_id='".addslashes($e_supplierid)."' 
												and $database_supplier_invoices_main.order_id = $database_order_details.id 
												and $database_order_details.client_id='".addslashes($clientid)."' 
												and 
													($database_supplier_invoices_main.status!='Paid' 
													 or $database_supplier_invoices_main.status!='Cancelled')
												GROUP BY $database_supplier_invoices_main.id");
				$poresult = $sql_command->results($pocheck);
	
				
				
				$e_filelist = "";
				$idlist = "";
				foreach ($poresult as $po) {
					
				
					$qties = $itemvalue = $payment_total2 = $payment_total = $paidam = 0;
					if (in_array($po[0],$ids)||in_array($po[0],$e_ids)) {
						$idlist .= "#".$po[0].", ";
						$e_file = "/".$e_supplierid."/".$po[0]."-".$e_timestamp.".pdf";
						$e_filelist .= "<a href=\"$site_url/_po".$e_file."\" style=\"color: #c08827;\">$site_url/_po".$e_file."</a><br />";
						
						$sql_command->update("emails","message='".addslashes($custom_message)."',sent='".addslashes($e_date)."'","reference_id='".addslashes($po[0])."' and filename='".$e_file."'");	
						
						include("../_includes/fn_emailpo.php");
	
						$filename = $base_directory."/_po".$e_file;
						$pdf->output($filename,"F");
						$mail->AddAttachment($filename);
					}
				}
				$EmailMessage ="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
				$EmailMessage .="<tr>";
				$EmailMessage .="<td width=\"217\" style=\"padding: 20px 0px 20px 0px;\">";
				$EmailMessage .="<img src=\"$site_url/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
				$EmailMessage .="<img src=\"$site_url/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
				$EmailMessage .="</td>";
				$EmailMessage .="<td style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
				$EmailMessage .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
				$EmailMessage .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
				$EmailMessage .="</td>";
				$EmailMessage .="</tr>";
				$EmailMessage .="<tr>";
				$EmailMessage .="<td colspan=\"2\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
				$EmailMessage .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Purchase Orders</h1>";
				$EmailMessage .="<br />";
				$EmailMessage .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $e_name,</h2>";
				$EmailMessage .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Please see Purchase Order from Ionian Weddings for the following wedding:</p>";
				$EmailMessage .="<br />";
				$EmailMessage .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">".$WeddingDate.", ".$FullClientName."</p>";
				$EmailMessage .="<br />";
				$EmailMessage .="<div style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">".$custom_message."</div>";
				$EmailMessage .= "<br />";
				$EmailMessage .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">To view your purchase order ".$idlist.", please refer to the following link: <br />".$e_filelist."</p><br />";
				$EmailMessage .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"$site_url/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
				$EmailMessage .="<br />";
				$EmailMessage .= "</td></tr><tr><td colspan=\"2\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"$site_url/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";
	
	
				$mail->Subject	=	"Ionian Weddings purchase order ".$idlist;
				$mail->MsgHTML($EmailMessage);
	
				
				
				if(!$mail->Send()) {
					$display =	"There was an error sending the message";
					throw new Exception($mail->ErrorInfo);
					exit;
				}
				
				$display = "Emails Sent, <a href=\"$site_url/oos/manage-client.php?a=history&id=".$clientid."\">return to client page</a>.";
				$checkpo[] = $e_supplierid;
			}
		}
	}
	$get_template->topHTML();
	echo $display;
	//print_r($ids);
	
	echo "<script type=\"text/javascript\">";
	echo $sc_script;
	echo "</script>";

	$get_template->bottomHTML();
	$sql_command->close();

?>
