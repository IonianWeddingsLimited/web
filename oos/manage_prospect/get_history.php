<?
	
	if(!$_GET["id"]) {
		$get_template->topHTML();
		$get_template->errorHTML("Manage Prospect","Oops!","Missing Prospect ID","Link","oos/manage-prospect.php");
		$get_template->bottomHTML();
		$sql_command->close();
	}
	$c_symbol = "£";
	
	/*$result = $sql_command->select("$database_supplier_invoices_main,
									$database_supplier_details,
									quotation_details,
									$database_clients",
									"$database_supplier_invoices_main.id,
									$database_supplier_invoices_main.order_id,
									$database_supplier_invoices_main.supplier_id,
									$database_supplier_invoices_main.invoice_id,
									$database_supplier_invoices_main.status,
									$database_supplier_invoices_main.timestamp,
									$database_supplier_details.id,
									$database_supplier_details.company_name,
									$database_clients.id,
									$database_clients.title,
									$database_clients.firstname,
									$database_clients.lastname,
									$database_clients.iwcuid","WHERE 
									$database_supplier_details.deleted='No' and 
									$database_clients.deleted='No' and 
									quotation_details.client_id='".addslashes($_GET["id"])."' and 
									$database_supplier_invoices_main.supplier_id=$database_supplier_details.id  and
									quotation_details.id=$database_supplier_invoices_main.order_id and 
									quotation_details.client_id=$database_clients.id 
									ORDER BY $database_supplier_invoices_main.timestamp DESC");
									//$database_supplier_details.id!='21' and 
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
		
	//$cost = 0;
	
	$purchase_order = $record[0];
	
	include("purchase-order_calc.php");
	
	$cost = number_format($payment_total,2);

	$polist .=	"<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
				<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
				<div style=\"float:left; width:150px; margin:5px;\">$p_curreny ".$cost."</div>
				<div style=\"float:left; width:50px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
				<div style=\"float:left;  margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[2]."\">".stripslashes($record[7])."</a></div>
				<div style=\"clear:left;\"></div>";
	}

	*/
	$result = $sql_command->select("quotation_details,
								   $database_packages,
								   $database_navigation,
								   $database_package_info",
								   "quotation_details.id,
								   $database_packages.package_name,
								   $database_package_info.iw_name,
								   $database_navigation.page_name",
								   "WHERE quotation_details.client_id='".addslashes($_GET["id"])."'
								   and quotation_details.package_id=$database_package_info.id 
								   and $database_package_info.package_id=$database_packages.id
								   and ($database_packages.island_id=$database_navigation.id)");
	$row = $sql_command->results($result);
	
	foreach ($row as $record) {
	$html .= "
	<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[0])."</div>
	<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
	<div style=\"float:left; width:280px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
	<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-prospect.php?a=view-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-prospect.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> |  <a href=\"$site_url/oos/manage-prospect.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Proforma</a></div>
	<div style=\"clear:left;\"></div>";
	}
	
	
	$result = $sql_command->select("quotation_proformas,quotation_details",
								   "quotation_proformas.id,
								   quotation_proformas.iw_cost,
								   quotation_proformas.status,
								   quotation_proformas.timestamp,
								   quotation_proformas.type,
								   quotation_proformas.order_id,
								   quotation_proformas.included_package,
								   quotation_proformas.order_id","WHERE 
								   quotation_proformas.order_id=quotation_details.id AND
								   quotation_details.client_id='".addslashes($_GET["id"])."'
								   ORDER BY quotation_proformas.timestamp DESC");
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
		$currency_q = $sql_command->select("quotation_history 
										   LEFT OUTER JOIN $db_currency_conversion ON 
										   $db_currency_conversion.currency_name = quotation_history.currency",
										   "quotation_history.currency,$db_currency_conversion.currency_symbol",
										   "WHERE quotation_history.invoice_id = '".addslashes($invoiceno)."'
										   GROUP BY quotation_history.currency
										   ORDER BY $db_currency_conversion.currency_name ASC");
		$currency_i = $sql_command->results($currency_q);
		$i = 1;
		$new_total_gbp = "";
		
		foreach($currency_i as $ci) {
			$statusform	="";
			$c_symbol = $ci[1];
			$currency_name=$ci[0];
			include("../_includes/fn_proforma-payment.php");
			$new_total_gbp	.=	(empty($new_total_gbp)) ?  "":  " / ";
			$new_total_gbp	.=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
			$test[$i] = $total_gbp;
			$i++;
		}
		$curr_i = "&currency=".$currency_name;	
		$new_total_gbp = str_replace("-","",str_replace($totalfilter," / ",$new_total_gbp));
	}
	else {
		if ($record[4]=="Deposit") {
			$invoicer_result = $sql_command->select("quotation_proforma_history 
											LEFT OUTER JOIN $db_currency_conversion 
												ON $db_currency_conversion.currency_name = quotation_proforma_history.currency",
												"quotation_proforma_history.currency,
												$db_currency_conversion.currency_symbol",
											   "WHERE invoice_id='".addslashes($invoiceno)."'");
		
			$invoice_rowr = $sql_command->result($invoicer_result);
			$c_symbol = $invoice_rowr[1];
			$currency_name = $invoice_rowr[0];
			$curr_i = "&currency=".$currency_name;
		}
		else { 	$singleinv = true; }
		include("../_includes/fn_proforma-payment.php");
		$totalin		=	number_format($total_gbp,2);
		$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
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
				<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[4])."</div>
				<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
				<div style=\"float:left; width:40px; margin:5px;\">
					<a href=\"$site_url/oos/proforma.php?invoice=".$record[0].$curr_i."\" target=\"_blank\">View</a>
				</div>
				<div style=\"float:left; width:140px; margin:5px;\">".$new_total_gbp."</div>
				<form action=\"$site_url/oos/manage-prospect.php\" method=\"post\">
					<div style=\"float:left; width:50px; margin:5px;\">
						<input type=\"checkbox\" name=\"delete\" value=\"Yes\">
					</div>
				<input type=\"hidden\" name=\"client_id\" value=\"".$_GET["id"]."\">
				<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
				<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">
				<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">
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
	<form action=\"".$site_url."/oos/manage-prospect.php\" method=\"post\">
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
		function checkedAll () {
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
	<h1>Manage Prospect</h1>
	
	<?php echo $menu_line; ?>
	
	<h2>Quotation History</h2>
	
	<div style="float:left; width:50px; margin:5px;"><strong>Quote #</strong></div>
	<div style="float:left; width:140px; margin:5px;"><strong>Island</strong></div>
	<div style="float:left; width:300px; margin:5px;"><strong>Package</strong></div>
	<div style="clear:left;"></div>
	
	<?php echo $html; ?>
	
	<!--<h2 style="margin-top:10px;">test loop</h2>-->
	
	<h2 style="margin-top:10px;">Proforma History</h2>
	
	<div style="float:left; width:50px; margin:5px;"><strong>Proforma</strong></div>
	<div style="float:left; width:50px; margin:5px;"><strong>Type</strong></div>
	<div style="float:left; width:80px; margin:5px;"><strong>Date</strong></div>
	<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
	<div style="float:left; width:140px; margin:5px;"><strong>Amount</strong></div>
	<div style="float:left; width:50px; margin:5px;"><strong>Delete</strong></div>
	<!--<div style="float:left; width:120px; margin:5px;"><strong>Status</strong></div>-->
	<div style="clear:left;"></div>
	
	<?php 
	echo $list;
	echo "["; 
	if($list) {
	//echo " <a href=\"$site_url/oos/download-all-invoices.php?client_id=".$_POST["client_id"]."\">Download All Invoices</a> |";
	}
	echo " <a href=\"$site_url/oos/manage-prospect.php?a=emailinv&id=".$_GET["id"]."\">Email quotation link</a> |";
	
	echo " <a href=\"$site_url/oos/manage-prospect.php?a=emailpass&id=".$_GET["id"]."\">Email password</a> ]";
	
	
	?>
	
<h2 style="margin-top:20px;">IMAGE-INE History</h2>
<div style="float:left; width:100%; margin:5px;"><strong>Links</strong></div>

<?php echo $image_ine_html; ?>

<h2 style="margin-top:10px;">View Notes</h2>
<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left; width:320px; margin:5px;"><strong>Title</strong></div>
<div style="float:left; margin:5px;"><strong>Edit</strong></div>
<div style="clear:left;"></div>
<?php echo $notes_html; ?>

<h2 style="margin-top:10px;">Transaction History</h2>

<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left; margin:5px;"><strong>Comment</strong></div>
<div style="clear:left;"></div>

<?php 
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
	echo $transaction_html;  


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