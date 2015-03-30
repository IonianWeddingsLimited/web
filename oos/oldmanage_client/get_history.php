<?

$result = $sql_command->select("$database_order_details,$database_packages,$database_navigation,$database_package_info","$database_order_details.id,
							   $database_packages.package_name,
							   $database_package_info.iw_name,
							   $database_navigation.page_name
							   ","WHERE 
							  $database_order_details.client_id='".addslashes($_GET["id"])."' and 
							  $database_order_details.package_id=$database_package_info.id and 
							  $database_package_info.package_id=$database_packages.id and 
							  $database_packages.island_id=$database_navigation.id
							  ");
$row = $sql_command->results($result);

foreach ($row as $record) {
$html .= "
<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[0])."</div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
<div style=\"float:left; width:280px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=view-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-client.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> |  <a href=\"$site_url/oos/manage-client.php?a=deposit&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Deposit</a> |  <a href=\"$site_url/oos/manage-client.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Invoice</a></div>
<div style=\"clear:left;\"></div>";
}


$result = $sql_command->select("$database_customer_invoices,$database_order_details","$database_customer_invoices.id,
							   $database_customer_invoices.iw_cost,
							   $database_customer_invoices.status,
							   $database_customer_invoices.timestamp,
							   $database_customer_invoices.type,
							   $database_customer_invoices.order_id","WHERE 
							   $database_customer_invoices.order_id=$database_order_details.id AND
							   $database_order_details.client_id='".addslashes($_GET["id"])."'
							   ORDER BY $database_customer_invoices.timestamp DESC");
$row = $sql_command->results($result);


foreach($row as $record) {

$dateline = date("d-m-Y",$record[3]);

if($record[4] == "Deposit") { $link = "invoice"; } else { $link= "invoice"; }

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



$list .= "
<div style=\"float:left; width:50px; margin:5px;\">".$record[0]."</div>
<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[4])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:40px; margin:5px;\"><a href=\"$site_url/oos/".$link.".php?invoice=".$record[0]."\" target=\"_blank\">View</a></div>
<div style=\"float:left; width:140px; margin:5px;\">&pound; ".number_format($record[1],2)."</div>
<form action=\"$site_url/oos/manage-client.php\" method=\"post\">
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"checkbox\" name=\"delete\" value=\"Yes\"></div>
<input type=\"hidden\" name=\"client_id\" value=\"".$_GET["id"]."\">
<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">
<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">
<div style=\"float:left; margin:5px;\">
<select name=\"status\">
<option value=\"Quotation\" $s6>Quotation</option>
<option value=\"Outstanding\" $s1>Outstanding</option>
<option value=\"Paid\" $s2>Paid</option>
<option value=\"Refunded\" $s3>Refunded</option>
<option value=\"Pending\" $s4>Pending</option>
<option value=\"Cancelled\" $s5>Cancelled</option>
</select> </div>

<div style=\"float:left; margin:5px;\"><input type=\"submit\" name=\"action\" value=\"Update Status\"></div>
</form>
<div style=\"clear:left;\"></div>
";

}


$result = $sql_command->select("$database_client_historyinfo,$database_users","$database_client_historyinfo.id,
							   $database_client_historyinfo.comment,
							   $database_client_historyinfo.timestamp,
							   $database_users.username","WHERE 
							   $database_client_historyinfo.client_id='".addslashes($_GET["id"])."' and
							   $database_client_historyinfo.user_id=$database_users.id 
							   ORDER BY $database_client_historyinfo.timestamp DESC");
$row = $sql_command->results($result);



	
foreach($row as $record) {
$dateline = date("d-m-Y",$record[2]);
$transaction_html .= "
<div style=\"float:left; width:100px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
<div style=\"float:left; margin:5px;\">".stripslashes($record[1])."</div>
<div style=\"clear:left;\"></div>
";
}


$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Order History</h2>

<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; width:300px; margin:5px;"><strong>Package</strong></div>
<div style="clear:left;"></div>

<?php echo $html; ?>

<h2 style="margin-top:10px;">Invoice History</h2>

<div style="float:left; width:50px; margin:5px;"><strong>Invoice</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Type</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
<div style="float:left; width:140px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Delete</strong></div>
<div style="float:left; width:120px; margin:5px;"><strong>Status</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>

<?php if($list) { ?>
[ <a href="<?php echo $site_url; ?>/oos/download-all-invoices.php?client_id=<?php echo $_POST["client_id"]; ?>">Download All Invoices</a> ]
<?php } ?>
<h2 style="margin-top:10px;">Transaction History</h2>

<div style="float:left; width:100px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left; margin:5px;"><strong>Comment</strong></div>
<div style="clear:left;"></div>

<?php echo $transaction_html; ?>


<?
$get_template->bottomHTML();
$sql_command->close();

?>