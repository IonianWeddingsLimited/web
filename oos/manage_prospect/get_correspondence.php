<?

$result = $sql_command->select("quotation_details,$database_packages,$database_navigation,$database_package_info","quotation_details.id,
							   $database_packages.package_name,
							   $database_package_info.iw_name,
							   $database_navigation.page_name
							   ","WHERE 
							  quotation_details.client_id='".addslashes($_GET["id"])."' and 
							  quotation_details.package_id=$database_package_info.id and 
							  $database_package_info.package_id=$database_packages.id and 
							  $database_packages.island_id=$database_navigation.id
							  ");
$row = $sql_command->results($result);

foreach ($row as $record) {
$html .= "
<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[0])."</div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
<div style=\"float:left; width:280px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-prospect.php?a=view-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-prospect.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> |  <a href=\"$site_url/oos/manage-prospect.php?a=deposit&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Deposit</a> |  <a href=\"$site_url/oos/manage-prospect.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Proforma</a></div>
<div style=\"clear:left;\"></div>";
}


$result = $sql_command->select("quotation_proformas,quotation_details","quotation_proformas.id,
							   quotation_proformas.total_due,
							   quotation_proformas.status,
							   quotation_proformas.timestamp,
							   quotation_proformas.type","WHERE 
							   quotation_proformas.order_id=quotation_details.id AND
							   quotation_details.client_id='".addslashes($_GET["id"])."'
							   ORDER BY quotation_proformas.timestamp DESC");
$row = $sql_command->results($result);


foreach($row as $record) {

$dateline = date("d-m-Y",$record[3]);

if($record[4] == "Deposit") { $link = "deposit"; } else { $link= "invoice"; }

$s1 = "";
$s2 = "";
$s3 = "";
$s4 = "";
$s5 = "";
if($record[2] == "Outstanding") { $s1 = "selected=\"selected\""; }
if($record[2] == "Pending") { $s4 = "selected=\"selected\""; }
if($record[2] == "Cancelled" or $record[2] == "Refunded" or $record[2] == "Paid") {

$list .= "
<div style=\"float:left; width:50px; margin:5px;\">".$record[0]."</div>
<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[4])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:40px; margin:5px;\"><a href=\"$site_url/oos/".$link.".php?invoice=".$record[0]."\" target=\"_blank\">View</a></div>
<div style=\"float:left; width:140px; margin:5px;\">&pound; ".stripslashes($record[1])."</div>
<div style=\"float:left; margin:5px;\">$record[2]</div>
<div style=\"clear:left;\"></div>
";	
} else {

$list .= "
<div style=\"float:left; width:50px; margin:5px;\">".$record[0]."</div>
<div style=\"float:left; width:50px; margin:5px;\">".stripslashes($record[4])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:40px; margin:5px;\"><a href=\"$site_url/oos/".$link.".php?invoice=".$record[0]."\" target=\"_blank\">View</a></div>
<div style=\"float:left; width:140px; margin:5px;\">&pound; ".stripslashes($record[1])."</div>
<form action=\"$site_url/oos/manage-prospect.php\" method=\"post\">
<input type=\"hidden\" name=\"client_id\" value=\"".$_GET["id"]."\">
<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
<div style=\"float:left; margin:5px;\">
<select name=\"status\">
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
<h1>Manage Prospect</h1>

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
<div style="float:left; width:120px; margin:5px;"><strong>Status</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>


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