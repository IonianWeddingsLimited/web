<?
	
	if(!$_GET["id"]) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Client","Oops!","Missing Client ID","Link","oos/manage-client.php");
		$get_template->bottomHTML();
		$sql_command->close();
	}
	$c_symbol = "£";
	
		$result = $sql_command->select("$database_supplier_invoices_main,
									$database_supplier_details,
									$database_order_details,
									$database_clients",
									"$database_supplier_invoices_main.id,
									$database_supplier_invoices_main.order_id,
									$database_supplier_invoices_main.supplier_id,
									$database_supplier_invoices_main.invoice_id,
									$database_supplier_invoices_main.status,
									$database_supplier_invoices_main.timestamp,
									$database_supplier_details.id,
									$database_supplier_details.company_name,
									$database_supplier_details.contact_title,
									$database_supplier_details.contact_firstname,
									$database_supplier_details.contact_surname,
									$database_supplier_details.contact_email,
									$database_clients.id,
									$database_clients.title,
									$database_clients.firstname,
									$database_clients.lastname,
									$database_clients.iwcuid,
									$database_supplier_invoices_main.status","WHERE 
									$database_supplier_details.deleted='No' and 
									$database_clients.deleted='No' and 
									$database_order_details.client_id='".addslashes($_GET["id"])."' and 
									$database_supplier_invoices_main.supplier_id=$database_supplier_details.id  and
									$database_order_details.id=$database_supplier_invoices_main.order_id and 
									$database_order_details.client_id=$database_clients.id 
									ORDER BY $database_supplier_invoices_main.timestamp DESC");
	$row = $sql_command->results($result);
	$poe_filter = array();
	foreach($row as $record) {

	//$cost = 0;
	
	$purchase_order = $record[0];
	
	include("purchase-order_calc.php");
	
	$cost = number_format($payment_total,2);
	$po_cost = number_format($po_amount, 2);
		
	if ($ic>0) {
		switch($p_curreny) {
			case	"£";
				$iPOTotalGBP	+=	$payment_total;
				$iPOTotalEuro	+=	0;
			break;
			default:
				$iPOTotalGBP	+=	0;
				$iPOTotalEuro	+=	$payment_total;
			break;
		}

			if ($po_cost <> 0) {
				
				switch ($the_username) {
					case "u1":
						$poemaillist .=	"<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"ids[]\" value=\"".$record[0]."\"><input type=\"hidden\" name=\"sup_ids[]\" value=\"".$record[2]."\"></div>
									<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
									<div style=\"float:left; width:120px; margin:5px;\">".$record[7]."</div>
									<div style=\"float:left; width:80px; margin:5px;\">".$record[17]."</div>
									<div style=\"float:left; width:50px; margin:5px;\">
										<a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View PO</a>
									</div>
									<div style=\"float:left; margin:5px; width:150px;\"><input type=\"text\" name=\"email_name_".$record[0]."\" value=\"".$record[8]." " .$record[9]." ".$record[10]."\"></div>
									<div style=\"float:left; margin:5px; width:150px;\"><input type=\"text\" name=\"email_address_".$record[0]."\" value=\"".$record[11]."\"><input type=\"hidden\" name=\"email_sid_".$record[0]."\" value=\"".$record[2]."\"><input type=\"hidden\" name=\"email_oid_".$record[0]."\" value=\"".$record[1]."\"></div>
									<div style=\"clear:left;\"></div>";	
						break;
						
					default:
						$poemaillist .=	"<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"ids[]\" value=\"".$record[0]."\"></div>
									<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
									<div style=\"float:left; width:120px; margin:5px;\">".$record[7]."</div>
									<div style=\"float:left; width:80px; margin:5px;\">".$record[17]."</div>
									<div style=\"float:left; width:50px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View PO</a></div>
									<div style=\"float:left; margin:5px; width:150px;\"><input type=\"text\" name=\"email_name_".$record[0]."\" value=\"".$record[8]." " .$record[9]." ".$record[10]."\"></div>
									<div style=\"float:left; margin:5px; width:150px;\"><input type=\"text\" name=\"email_address_".$record[0]."\" value=\"".$record[11]."\"><input type=\"hidden\" name=\"email_sid_".$record[0]."\" value=\"".$record[2]."\"><input type=\"hidden\" name=\"email_oid_".$record[0]."\" value=\"".$record[1]."\"></div>
									<div style=\"clear:left;\"></div>";	
						break;
				}		
			}
			if ($po_cost <> 0) {
				$polist .=	"<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
									<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
									<div style=\"float:left; width: 120px; margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[2]."\">".stripslashes($record[7])."</a></div>
									<div style=\"float:left; width:80px; margin:5px;\">".$record[17]."</div>
									<div style=\"float:left; width:180px; margin:5px;\">$p_curreny ".$po_cost." ($p_curreny ".$cost." to pay)</div>
									<div style=\"float:left; width:50px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View PO</a></div>
									<div style=\"clear:left;\"></div>";
			}
		}
	}


	$result = $sql_command->select("$database_order_details,
								   $database_packages,
								   $database_navigation,
								   $database_package_info",
								   "$database_order_details.id,
								   $database_packages.package_name,
								   $database_package_info.iw_name,
								   $database_navigation.page_name",
								   "WHERE $database_order_details.client_id='".addslashes($_GET["id"])."'
								   and $database_order_details.package_id=$database_package_info.id 
								   and $database_package_info.package_id=$database_packages.id
								   and ($database_packages.island_id=$database_navigation.id)");
	$row = $sql_command->results($result);
	
	foreach ($row as $record) {
//		switch ($the_username) {
//			case "u1":
				$html .=	"<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[0])."</div>
							<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
							<div style=\"float:left; width:220px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
							<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=view-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-client.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> | <a href=\"$site_url/oos/manage-client.php?a=deposit&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Deposit</a> | <a href=\"$site_url/oos/manage-client.php?a=fee&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Fees</a> | <a href=\"$site_url/oos/manage-client.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Invoice</a></div>
							<div style=\"clear:left;\"></div>";
//			break;
//			default:
//				$html .= 	"<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[0])."</div>
//							<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
//							<div style=\"float:left; width:220px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
//							<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=view-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-client.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> | <a href=\"$site_url/oos/manage-client.php?a=deposit&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Deposit</a> | <a href=\"$site_url/oos/manage-client.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Invoice</a></div>
//							<div style=\"clear:left;\"></div>";
//			break;
//		}	
	}
	
	
	$result = $sql_command->select("$database_customer_invoices,$database_order_details",
								   "$database_customer_invoices.id,
								   $database_customer_invoices.iw_cost,
								   $database_customer_invoices.status,
								   $database_customer_invoices.timestamp,
								   $database_customer_invoices.type,
								   $database_customer_invoices.order_id,
								   $database_customer_invoices.included_package,
								   $database_customer_invoices.order_id","WHERE 
								   $database_customer_invoices.order_id=$database_order_details.id AND
								   $database_order_details.client_id='".addslashes($_GET["id"])."'
								   ORDER BY $database_customer_invoices.timestamp DESC");
	$row = $sql_command->results($result);
	
	$c_symbol = "£";
	foreach($row as $record) {
	$currency_name = "";
	$oldsymbol = $c_symbol;
	$statusform	=	"";
	
	$dateline = date("d-m-Y",$record[3]);
	
	if($record[4] == "Deposit") {$link = "invoice"; }
	else { $link= "invoice"; }
	
	$s1 = "";
	$s2 = "";
	$s3 = "";
	$s4 = "";
	$s5 = "";
	if($record[2] == "Outstanding") { $s1 = "selected=\"selected\""; }
	if($record[2] == "Pending") { $s4 = "selected=\"selected\""; }
	if($record[2] == "Cancelled") { $s5 = "selected=\"selected\""; }
	if($record[2] == "Refunded") { $s3 = "selected=\"selected\""; }
	if($record[2] == "Paid") { $s2 = "selected=\"selected\""; }
	if($record[2] == "Quotation") { $s6 = "selected=\"selected\""; }
	
	$invoiceno = $record[0];	
	$num_rows = $sql_command->count_nrow("clients_options",
										 "id",
										 "client_id='".addslashes($invoiceno)."' 
										 and client_option='continental' 
										 and option_value='Yes'");
	
	if ($num_rows>0) {
		$currency_q = $sql_command->select("$database_order_history 
										   LEFT OUTER JOIN $db_currency_conversion ON 
										   $db_currency_conversion.currency_name = $database_order_history.currency",
										   "$database_order_history.currency,$db_currency_conversion.currency_symbol",
										   "WHERE $database_order_history.invoice_id = '".addslashes($invoiceno)."'
										   GROUP BY $database_order_history.currency
										   ORDER BY $db_currency_conversion.currency_name ASC");
		$currency_i = $sql_command->results($currency_q);
		$i = 1;
		$new_total_gbp = "";
		
		foreach($currency_i as $ci) {
			$statusform	="";
			$c_symbol = $ci[1];
			$currency_name=$ci[0];
			include("../_includes/fn_invoice-payment-v3.php");
			$new_total_gbp	.=	(empty($new_total_gbp)) ?  "":  " / ";
			//$new_total_gbp	.=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
			$new_total_gbp	.=	$c_symbol." ".number_format($total_gbp,2);
			$test[$i] = $total_gbp;
			$i++;
		}
		$curr_i = "&currency=".$currency_name;	
		//$new_total_gbp = str_replace("-","",str_replace($totalfilter," / ",$new_total_gbp));
		$new_total_gbp = str_replace($totalfilter," / ",$new_total_gbp);
	}
	else {
		if ($record[4]=="Deposit") {
			$invoicer_result = $sql_command->select("$database_invoice_history 
											LEFT OUTER JOIN $db_currency_conversion 
												ON $db_currency_conversion.currency_name = $database_invoice_history.currency",
												"$database_invoice_history.currency,
												$db_currency_conversion.currency_symbol",
											   "WHERE invoice_id='".addslashes($invoiceno)."'");
		
			$invoice_rowr = $sql_command->result($invoicer_result);
			$c_symbol = $invoice_rowr[1];
			$currency_name = $invoice_rowr[0];
			$curr_i = "&currency=".$currency_name;
		}
		else { 	$singleinv = true; }
		include("../_includes/fn_invoice-payment-v3.php");
		$totalin		=	number_format($total_gbp,2);
		//$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
		$new_total_gbp	=	$c_symbol." ".number_format($total_gbp,2);
		$c_symbol = "";
		$currency_name = "";
		$singleinv = false;
	}
	switch($login_record[0]) {
		case	"Super Admin User";
				$statusform	.=	"<select name=\"status\">
								<option value=\"Quotation\" $s6>Quotation</option>
								<option value=\"Outstanding\" $s1>Outstanding</option>
								<option value=\"Paid\" $s2>Paid</option>
								<option value=\"Refunded\" $s3>Refunded</option>
								<option value=\"Pending\" $s4>Pending</option>
								<option value=\"Cancelled\" $s5>Cancelled</option>
								</select>";
		break;
		default:
			switch($record[2]) {
				case	"Paid";
						$statusform .=	$record[2]."<input type=\"hidden\" name=\"status\" value=\"".$record[2]."\" />";	
				break;
				case	"Refunded";
						$statusform	.=	$record[2]."<input type=\"hidden\" name=\"status\" value=\"".$record[2]."\" />";
				break;
				default:
						$statusform	.=	"<select name=\"status\">
										<option value=\"Quotation\" $s6>Quotation</option>
										<option value=\"Outstanding\" $s1>Outstanding</option>
										<option value=\"Pending\" $s4>Pending</option>
										<option value=\"Cancelled\" $s5>Cancelled</option>
										</select>";
				break;
			}
		break;
	}
	
	$list .= "<div style=\"float:left; width:50px; margin:5px;\">".$record[0]."</div>
				<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($record[4])."</div>
				<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
				<div style=\"float:left; width:40px; margin:5px;\">
					<a href=\"$site_url/oos/invoice.php?invoice=".$record[0].$curr_i."\" target=\"_blank\">View</a>
				</div>
				<div style=\"float:left; width:110px; margin:5px;\">".$new_total_gbp."</div>
				<form action=\"$site_url/oos/manage-client.php\" method=\"post\">
					<div style=\"float:left; width:50px; margin:5px;\">
						<input type=\"checkbox\" name=\"delete\" value=\"Yes\">
					</div>
				<input type=\"hidden\" name=\"client_id\" value=\"".$_GET["id"]."\">
				<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
				<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">
				<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">
				<div style=\"float:left; margin:5px;width:95px;\">". $statusform ."</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"submit\" name=\"action\" value=\"Update Status\">
				</div>
			</form>
			<div style=\"clear:left;\"></div>";
			$c_symbol = $oldsymbol;
			$curr_i = "";
	}
//	$statusform	=	"";
//	}

	$result = $sql_command->select("$database_notes,$database_users","$database_notes.id,
							   $database_notes.subject,
							   $database_notes.timestamp,
							   $database_users.username","WHERE $database_notes.client_id='".addslashes($_POST["client_id"])."' and  
							   $database_notes.user_id=$database_users.id 
							   ORDER BY $database_notes.timestamp DESC");
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
	$date = date("d-m-Y h:i A",$record[2]);
	$notes_html .= "
	<form action=\"".$site_url."/oos/manage-client.php\" method=\"post\">
	<div style=\"float:left; width:140px; margin:5px;\">".$date."</div>
	<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
	<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($record[1])."</div>
	<div style=\"float:left; margin:5px;\"><input type=\"submit\" value=\"View\" /></div>
	<div style=\"clear:left;\"></div>
	<input type=\"hidden\" name=\"note_id\" value=\"".stripslashes($record[0])."\" />
	<input type=\"hidden\" name=\"action\" value=\"View Note\">
	<input type=\"hidden\" name=\"client_id\" value=\"".$_POST["client_id"]."\" />
	</form>\n";//<div style=\"float:left; margin:5px;\"><a onclick=\"this.form.submit();\">Edit Note</a></div>
	}
	
	$result = $sql_command->select("$database_emails,$database_users,$database_supplier_invoices_main,$database_order_details","Distinct $database_emails.reference_id,
								   $database_emails.email,
								   $database_emails.addressee,
								   $database_emails.message,
								   $database_emails.filename,
								   $database_emails.sent,
								   $database_emails.timestamp,
								   $database_users.username,
								   $database_supplier_invoices_main.supplier_id",
								   "WHERE $database_order_details.client_id='".addslashes(addslashes($_GET["id"]))."' and
								   $database_supplier_invoices_main.order_id = $database_order_details.id and
								   $database_supplier_invoices_main.id = $database_emails.reference_id and
								   $database_emails.reference_type = 'Supplier' and
								   $database_emails.sent != '0000-00-00 00:00:00' and
								   $database_emails.user_id=$database_users.id 
								   ORDER BY $database_emails.email_id DESC");
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
		$emaildate = date("d-m-Y h:i A",$record[6]);
		//$emailfilename	=	stripslashes($record[10])."/".stripslashes($record[1])."-".strtotime($record[6]).".pdf";
		$emailtransaction_html .= "
		<div style=\"float:left; width:140px; margin:5px;\">".$record[6]."</div>
		<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($record[7])."</div>
		<div style=\"float:left; width:170px; margin:5px;\">".stripslashes($record[2])."<br /><a href=\"mailto:".stripslashes($record[1])."\">".stripslashes($record[1])."</a></div>
		<div style=\"float:left; width:60px; margin:5px;\"><a href=\"/_po".stripslashes($record[4])."\" target=\"_blank\">View PO</a></div>
		<div style=\"float:left; margin:5px;\">".stripslashes($record[3])."</div>
		<div style=\"clear:left;\"></div>
		";
	}
	
	$result = $sql_command->select("$database_client_historyinfo,$database_users","$database_client_historyinfo.id,
								   $database_client_historyinfo.comment,
								   $database_client_historyinfo.timestamp,
								   $database_users.username",
								   "WHERE $database_client_historyinfo.client_id='".addslashes($_GET["id"])."' and
								   $database_client_historyinfo.user_id=$database_users.id 
								   ORDER BY $database_client_historyinfo.timestamp DESC");
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
	$dateline = date("d-m-Y",$record[2]);
	$transaction_html .= "
	<div style=\"float:left; width:140px; margin:5px;\">".$dateline."</div>
	<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
	<div style=\"float:left; margin:5px;\">".stripslashes($record[1])."</div>
	<div style=\"clear:left;\"></div>
	";
	}
	
	
	$result2 = $sql_command->select("$database_gallery_mason","$database_gallery_mason.meta_value","WHERE 
								   $database_gallery_mason.img_id = ".addslashes($_GET["id"])." AND
								   $database_gallery_mason.meta_id = 10");
	$row2 = $sql_command->results($result2);
	
	
	
		
	foreach($row2 as $record2) {
	$dateline2 = date("d-m-Y",$record2[0]);
	$image_ine_html .= "
	<div style=\"float:left; width:100%; margin:5px;\"><a href=\"http://www.ionianweddings.co.uk/image-ine/".$record2[0]."/\" title=\"IMAGE-INE - Ionian Weddings\" target=\"_blank\">http://www.ionianweddings.co.uk/image-ine/".$record2[0]." (".$dateline2.")</a></div>
	<div style=\"clear:left;\"></div>
	";
	}
	
	$add_header .= "<script type=\"application/javascript\">
	function checkSelected() {
		var inputElems = document.getElementsByClassName(\"payall\");
		var counta = 0;
		for (var i=0; i<inputElems.length; i++) {
			if (inputElems[i].type === \"checkbox\" && inputElems[i].checked === true) {
			counta++;
			}
		}
		return counta;
	}
	function checkVoid(){
		var count = checkSelected();
		if (count>0) {
			var reason=prompt(\"What is the reason for voiding this payment?\",\"Payment is incorrect\");
			if (reason!=\"\"){
				document.getElementById('reason').value=reason;
				return true;
			}
			else {
				return false;
			}
		}
		alert(\"Please choose a payment to void by selecting the checkbox next to the relevant payment.\");
		return false;
	}
	function checkApprove() {
		var count = checkSelected();
		if (count>0) {
			return true;
		}
		else {
			alert(\"Please choose a payment to approve by clicking the checkbox next to the relevant payment.\");
			return false;
		}
	}
	</script>
	
	<script language='JavaScript'>
		checked = false;
		
		function checkedAll() {
			if (checked == false) {
				checked = true;
			} else {
				checked = false;
			}
			for (var i = 0; i < document.getElementById('downloadPDF').elements.length; i++) {
				var chk = document.getElementById('downloadPDF').elements[i].name;
				if (chk.match(/download_.*/)) {
					document.getElementById('downloadPDF').elements[i].checked = checked;
				}
			}
		}
		
		function emailedAll() {
			if (checked == false) {
				checked = true;
			} else {
				checked = false;
			}
			for (var i = 0; i < document.getElementById('emailPDF').elements.length; i++) {
				var chk = document.getElementById('emailPDF').elements[i].name;
				if (chk.match(/ids/)) {
					document.getElementById('emailPDF').elements[i].checked = checked;
				}
			}
		}

	</script>";
	$add_header .= "
	<script type=\"text/javascript\">
	$(function() {
		$(window).resize(function(){
			var mugshot = $(\".main\");
			var rr = ($(window).width() - (mugshot.offset().left + mugshot.outerWidth()));
			var rt = mugshot.offset().top;
			$(\"#mugshot-wrap\").css({top: rt, right: rr, 'max-width': 180, 'max-height': 230, overflow: 'hidden', padding: '10px 35px 2px 0' });						  
								  });
		var mugshot = $(\".main\");
		var rr = ($(window).width() - (mugshot.offset().left + mugshot.outerWidth()));
		var rt = mugshot.offset().top;
		$(\"#mugshot-wrap\").css({top: rt, right: rr, 'max-width': 180, 'max-height': 230, overflow: 'hidden', padding: '10px 35px 2px 0' });
   	});
	</script>
	";

	$get_template->topHTML();
	?>
	<h1>Manage Client</h1>
	
	<?php echo $menu_line; ?>
	
	<h2>Order History</h2>
	
	<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
	<div style="float:left; width:140px; margin:5px;"><strong>Island</strong></div>
	<div style="float:left; width:220px; margin:5px;"><strong>Package</strong></div>
	<div style="clear:left;"></div>
	
	<?php echo $html; ?>
	
	<h2 style="margin-top:10px;">Invoice History</h2>
	
	<div style="float:left; width:50px; margin:5px;"><strong>Invoice</strong></div>
	<div style="float:left; width:80px; margin:5px;"><strong>Type</strong></div>
	<div style="float:left; width:80px; margin:5px;"><strong>Date</strong></div>
	<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
	<div style="float:left; width:110px; margin:5px;"><strong>Amount</strong></div>
	<div style="float:left; width:50px; margin:5px;"><strong>Delete</strong></div>
	<div style="float:left; width:120px; margin:5px;"><strong>Status</strong></div>
	<div style="clear:left;"></div>
	
	<?php 
	echo $list;
	echo "["; 
	if($list) {
		echo " <a href=\"$site_url/oos/download-all-invoices.php?client_id=".$_POST["client_id"]."\">Download All Invoices</a> |";
	}
	echo " <a href=\"$site_url/oos/manage-client.php?a=emailinv&id=".$_GET["id"]."\">Email payment link</a> |";
	echo " <a href=\"$site_url/oos/manage-client.php?a=emailconfirm&id=".$_GET["id"]."\">Email payment confirmation</a> |";
	echo " <a href=\"$site_url/oos/manage-client.php?a=emailpass&id=".$_GET["id"]."\">Email password</a> ]";
	
	$result2 = $sql_command->select("customer_transactions LEFT OUTER JOIN $db_currency_conversion 
									on $db_currency_conversion.currency_name = customer_transactions.currency",
									"customer_transactions.p_id,
									customer_transactions.p_amount,
									customer_transactions.transaction_id,
									customer_transactions.ip_add,
									customer_transactions.status,
									customer_transactions.cardtype,
									customer_transactions.timestamp,
									customer_transactions.currency,
									$db_currency_conversion.currency_symbol",
									"WHERE customer_transactions.customer_id=".$_GET['id']." 
									and customer_transactions.status!='Refused' 
									and customer_transactions.status!='Cancelled' 
									ORDER by customer_transactions.p_id DESC");
	$row2 = $sql_command->results($result2);
	$payment_html = "";
	foreach($row2 as $record2) {
	
		$payr = $sql_command->select("customer_payments","SUM(customer_payments.p_amount)","WHERE customer_payments.pay_no='".addslashes($record2[0])."'");
		$payv = $sql_command->result($payr);
		
		$p_currency = ($record2[8]) ? $record2[8]: "£";
			
		$alloc = (!$payv[0]) ? 0 : $payv[0];
		$unall = (($record2[1] - $alloc) !=0) ? false : ($record2[4]=="Paid") ? false : true;
		$difference = $record2[1] - $alloc;
		
		list($dateS,$timeS) = explode(" ",$record2[6]);
		list($yr,$mt,$dy) = explode("-",$dateS);
		$dateS = $dy."/".$mt."/".$yr;
		$payment_html .= "<tr>";
		$payment_html .= "<td>".$record2[0]."</td>";
		$payment_html .= "<td>".$p_currency." ".number_format($record2[1],2)."</td>";
		$payment_html .= "<td>".$record2[5]."</td>";
		$payment_html .= "<td>".$record2[2]."</td>";
		$payment_html .= "<td>".ucwords($record2[3])."</td>";
		$payment_html .= "<td>".$dateS."</td>";
		$payment_html .= "<td><a href=\"$site_url/oos/manage-client.php?a=partpay&id=".$_GET['id']."&pid=".$record2[0]."&f=mod\">View Payment</a></td>";
		$payment_html .= "<td>".$record2[4]."</td>";
		$payment_html .= "<td><input type=\"checkbox\" name=\"payall[]\" value=\"".$record2[0]."\" class=\"payall\" /></td>";
		$payment_html .= "</tr>";
	}
	echo "<h2 style=\"margin-top:10px;\">Payment History</h2>";
	echo "<form method=\"post\" action=\"manage-client.php\" name=\"paymentp\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	echo "<tr>";
	echo "<th scope=\"col\">Payment ID</th>";
	echo "<th scope=\"col\">Paid</th>";
	echo "<th scope=\"col\">Payment Type</th>";
	echo "<th scope=\"col\">Trans-ref</th>";
	echo "<th scope=\"col\">Paid by</th>";
	echo "<th scope=\"col\">Date</th>";
	echo "<th scope=\"col\">View</th>";
	echo "<th scope=\"col\">Status</th>";
	echo "<th scope=\"col\">Select</th>";
	echo "</tr>";
	echo "<tr><td colspan=\"7\">&nbsp;</td></tr>";
	echo $payment_html; 
	echo "</table>";
	echo "<br />";
	echo "<input type=\"hidden\" name=\"cli_id\" value=\"".$_GET["id"]."\" ?>";
	echo "<input type=\"hidden\" id=\"reason\" name=\"reason\" value=\"\" ?>";
	echo "[ <a href=\"$site_url/oos/manage-client.php?a=partpay&id=".$_GET["id"]."&f=add\">Add Payment</a>";
	switch($login_record[0]) {
		case	"Super Admin User";
				echo " | <input class=\"paymentb\" type=\"submit\" name=\"action\" value=\"Approve Payments\" onclick=\"return checkApprove();\" />";
		break;
	}
	echo " | <input class=\"paymentb\" type=\"submit\" name=\"action\" value=\"Delete Payments\" onclick=\"return checkVoid();\" /> ]</form>";
	//echo $respa . "<br />" . $respb."<br />hi ";

	?>
<?
//switch ($the_username) {
//	case "u1":
	$result2 = $sql_command->select("supplier_transactions",
									"p_id,
									p_amount,
									transaction_id,
									ip_add,
									status,
									cardtype,
									timestamp,currency,
									supplier_id",
									"WHERE client_id=".$_GET['id']." 
									and status!='Refused' 
									and status!='Cancelled'");
	
	$row2 = $sql_command->results($result2);
	$payment_html = "";
	
	foreach($row2 as $record2) {
		
		
		
		$pcurrency = $sql_command->select("currency_conversion","currency_symbol","WHERE currency_name='".$record2[7]."'");
		$currencyp = $sql_command->result($pcurrency);
		
		$payr = $sql_command->select("supplier_payments",
									 "SUM(supplier_payments.p_amount)",
									 "WHERE supplier_payments.pay_no='".addslashes($record2[0])."'");
		$payv = $sql_command->result($payr);
		$alloc = (!$payv[0]) ? 0 : $payv[0];
		$unall = ($record2[1] - $alloc !=0) ? false : ($record2[4]=="Paid") ? false : true;
		$difference = $record2[1] - $alloc;
		
		list($dateS,$timeS) = explode(" ",$record2[6]);
		list($yr,$mt,$dy) = explode("-",$dateS);
		$dateS = $dy."/".$mt."/".$yr;
		
$payment_html .= "<tr>";
$payment_html .= "<td valign='top'>".$record2[0]."</td>";

$psupplierr = $sql_command->select($database_supplier_details,"company_name","WHERE id='".$record2[8]."'");
$psupplierv = $sql_command->result($psupplierr);
if($psupplierv) {
	$payment_html .= "<td valign='top'>".$psupplierv[0]."</td>";
} else {
	$payment_html .= "<td valign='top'>N/A</td>";
}
$payment_html .= "<td valign='top'>".$currencyp[0]." ".number_format($record2[1],2)."</td>";
$payment_html .= "<td valign='top'>".$record2[5]."</td>";
$payment_html .= "<td valign='top'>".$record2[2]."</td>";
$payment_html .= "<td valign='top'>".ucwords($record2[3])."</td>";
$payment_html .= "<td valign='top'>".$dateS."</td>";
$payment_html .= "<td valign='top'><a href=\"$site_url/oos/manage-supplier-po.php?a=partpay&id=".$_GET['id']."&pid=".$record2[0]."&f=mod\">View</a></td>";
$payment_html .= "<td valign='top'>".$record2[4]."</td>";
//$payment_html .= "<td valign='top'><input type=\"checkbox\" name=\"payall[]\" value=\"".$record2[0]."\" class=\"payall\" /></td>";
$payment_html .= "</tr>";
}

echo "<h2 style=\"margin-top:10px;\">Supplier Payment History</h2>";
echo "<form method=\"post\" action=\"manage-supplier-po.php\" name=\"paymentp\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
echo "<tr>";
echo "<th scope=\"col\">Payment ID</th>";
echo "<th scope=\"col\">Supplier(s)</th>";
echo "<th scope=\"col\">Paid</th>";
echo "<th scope=\"col\">Payment Type</th>";
echo "<th scope=\"col\">Trans-ref</th>";
echo "<th scope=\"col\">Paid by</th>";
echo "<th scope=\"col\">Date</th>";
echo "<th scope=\"col\">View</th>";
echo "<th scope=\"col\">Status</th>";
//echo "<th scope=\"col\">Select</th>";
echo "</tr>";
echo "<tr><td colspan=\"7\">&nbsp;</td></tr>";
echo $payment_html; 
echo "</form>"; 
echo "</table>";
//break;
//}
?>
<?
//if ($login_record[1] == 7) {
if($polist) {
?>
<h2 style="margin-top:20px;">Download Multiple Purchase Orders</h2>
<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order-by-order.php" name="downloadPDF" id="downloadPDF">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<div style="float:left; width:40px; margin:5px;"><strong>Select</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:120px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Status</strong></div>
<div style="float:left; width:180px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>PDF</strong></div>
<div style="clear:left;"></div>
<?php echo $polist; ?>
<?
	if ($iPOTotalEuro or $iPOTotalGBP) {
?>
<div style="float:left; width:230px; margin:5px;"></div>
<div style="float:left; width:80px; margin:5px;"><strong>Total</strong></div>
<div style="float:left;  margin:5px 5px 5px 5px;">
	<?php echo "&euro; ".number_format($iPOTotalEuro,2); ?>
	/
	<?php echo "&pound; ".number_format($iPOTotalGBP,2);  ?>
</div>
<div style="clear:left;"></div>
<?
	}
?>
<div style="float:left; width:40px; margin:5px 5px 5px 5px;"><input type='checkbox' name='checked' onclick='checkedAll();'></div>
<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Download Selected Purchase Orders"></p>
</form>
<h2 style="margin-top:20px;">Email Multiple Suppliers</h2>
<form method="post" action="<?php echo $site_url; ?>/oos/manage-client.php" name="emailPDF" id="emailPDF">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<input type="hidden" name="clientid" value="<?php echo $_GET['id']; ?>">
<div style="float:left; width:40px; margin:5px;"><strong>Select</strong></div>
<div style="float:left; margin:5px; width:50px;"><strong>Order #</strong></div>
<div style="float:left; margin:5px; width:120px;"><strong>Company</strong></div>
<div style="float:left; margin:5px; width:80px;"><strong>Status</strong></div>
<div style="float:left; margin:5px; width:50px;"><strong>View</strong></div>
<div style="float:left; margin:5px; width:150px;"><strong>Recipient</strong></div>
<div style="float:left; margin:5px; width:150px;"><strong>Email</strong></div>
<div style="clear:left;"></div>
<?php echo $poemaillist; ?>
<div style="float:left; width:40px; margin:5px 5px 5px 5px;"><input type='checkbox' name='checked' onclick='emailedAll();'></div>
<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Email Selected Suppliers"></p>
</form>
<?
	}
	
	if ($emailtransaction_html) {
?>
<h2 style="margin-top:10px;">Supplier Email History</h2>
<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Sent By</strong></div>
<div style="float:left; width:170px; margin:5px;"><strong>Recipient</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; margin:5px;"><strong>message</strong></div>
<div style="clear:left;"></div>
<?
		echo $emailtransaction_html;
	}

	if ($image_ine_html) {
?>
<h2 style="margin-top:20px;">IMAGE-INE History</h2>
<div style="float:left; width:100%; margin:5px;"><strong>Links</strong></div>
<?
		echo $image_ine_html;
	}
	
	if ($notes_html) {
?>
<h2 style="margin-top:10px;">View Notes</h2>
<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left; width:320px; margin:5px;"><strong>Title</strong></div>
<div style="float:left; margin:5px;"><strong>Edit</strong></div>
<div style="clear:left;"></div>
<?
		echo $notes_html;
	}
?>

<h2 style="margin-top:10px;">Transaction History</h2>

<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left; margin:5px;"><strong>Comment</strong></div>
<div style="clear:left;"></div>

<?php echo $transaction_html; 
//var_dump($test);



$images_q = $sql_command->select("clients_options","*","WHERE client_id='".addslashes($_GET['id'])."' AND client_option ='mugshot' and additional='Active'");
$images_r = $sql_command->result($images_q);

if ($images_r) { 
	echo "<div id=\"mugshot-wrap\">
	<img id=\"mugshot-img\" src=\"".$site_url."/images/imageuploads/mugshot/".$images_r[3]."\" width=\"100%\" height=\"100%\" />
	
	</div>";
}
?>
	
	<?
	$get_template->bottomHTML();
	$sql_command->close();
?>