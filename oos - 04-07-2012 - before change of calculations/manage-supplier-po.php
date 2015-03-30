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

if($_GET["id"]) {
$_GET["id"] = $_GET["id"];
$_GET["action"] = "Continue";
}

if($_GET["action"] == "Continue" or $_GET["action"] == "Update" or $_GET["a"] == "Update Status") {
	
if($_GET["a"] == "Update Status") {
$sql_command->update($database_supplier_invoices,"status='".addslashes($_GET["status"])."'","id='".addslashes($_GET["po_id"])."'");
$sql_command->update($database_supplier_invoices,"updated_timestamp='".$time."'","id='".addslashes($_GET["po_id"])."'");
$message = "<h3>Status Updated</h3>";
}

$result = $sql_command->select("$database_supplier_invoices,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices.id,
							   $database_supplier_invoices.order_id,
							   $database_supplier_invoices.cost,
							   $database_supplier_invoices.currency,
							   $database_supplier_invoices.status,
							   $database_supplier_invoices.timestamp,
							   $database_supplier_details.id,
							   $database_supplier_details.company_name,
							   $database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.iwcuid","WHERE 
							   $database_supplier_invoices.supplier_id='".addslashes($_GET["id"])."' and 
							   $database_supplier_invoices.supplier_id=$database_supplier_details.id  and
							   $database_order_details.id=$database_supplier_invoices.order_id and 
							   $database_order_details.client_id=$database_clients.id 
							   ORDER BY $database_supplier_invoices.timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
$supplier_name = stripslashes($record[7]);

$dateline = date("d-m-Y",$record[5]);

if($record[4] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}

$s1 = "";
$s2 = "";
$s3 = "";
$s4 = "";
$s5 = "";
$checkbox_add = "No";


if($record[4] == "Outstanding") { $s1 = "selected=\"selected\""; $checkbox_add = "Yes"; }
if($record[4] == "Pending") { $s4 = "selected=\"selected\""; $checkbox_add = "Yes"; }
if($record[4] == "Cancelled") { $s5 = "selected=\"selected\""; }
if($record[4] == "Refunded") { $s3 = "selected=\"selected\""; }
if($record[4] == "Paid") { $s2 = "selected=\"selected\""; }

if($checkbox_add == "Yes") {
$list2 .= "<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
<div style=\"float:left; width:50px; margin:5px;\">$p_curreny ".$record[2]."</div>
<div style=\"float:left; width:160px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".stripslashes($record[9])." ".stripslashes($record[10])." ".stripslashes($record[11])."</a></div>
<div style=\"clear:left;\"></div>";
}

$list .= "
<div style=\"float:left; width:60px; margin:5px;  font-size:11px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".$record[12]."</a></div>
<div style=\"float:left; width:50px; margin:5px;  font-size:11px;\">".$record[1]."</div>
<div style=\"float:left; width:50px; margin:5px; font-size:11px;\">$p_curreny ".$record[2]."</div>
<div style=\"float:left; width:30px;  margin:5px; font-size:11px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
<div style=\"float:left; width:160px; margin:5px; font-size:11px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".stripslashes($record[9])." ".stripslashes($record[10])." ".stripslashes($record[11])."</a></div>
<div style=\"float:left; width:60px; margin:5px; font-size:11px;\">".$dateline."</div>


<form action=\"$site_url/oos/manage-supplier-po.php\" method=\"get\">
<input type=\"hidden\" name=\"po_id\" value=\"".$record[0]."\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET["id"]."\">
<div style=\"float:left; margin:5px;\">
<select name=\"status\">
<option value=\"Outstanding\" $s1>Outstanding</option>
<option value=\"Paid\" $s2>Paid</option>
<option value=\"Refunded\" $s3>Refunded</option>
<option value=\"Pending\" $s4>Pending</option>
<option value=\"Cancelled\" $s5>Cancelled</option>
</select> </div>
<div style=\"float:left; margin:5px;\"><input type=\"submit\" name=\"a\" value=\"Update Status\"></div>
</form>
<div style=\"clear:left;\"></div>
";	
}

$get_template->topHTML();
?>
<h1>Supplier Purchase Orders</h1>
<h2><?php echo $supplier_name; ?></h2>
<?php echo $message; ?>

<div style="float:left; width:60px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left;  width:30px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; width:160px; margin:5px;"><strong>Client</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Date</strong></div>
<div style="float:left;  margin:5px;"><strong>Status</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>

<?php if($list2) { ?>
<h3>Download Multiple Invoices</h3>
<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order.php" name="download">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:160px; margin:5px;"><strong>Client</strong></div>
<div style="clear:left;"></div>
<?php echo $list2; ?>

<p><input type="submit" name="action" value="Download Selected Purchase Orders"></p>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {

$result = $sql_command->select($database_supplier_details,"id,company_name","ORDER BY company_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Supplier</h1>

<form action="<?php echo $site_url; ?>/oos/manage-supplier-po.php" method="get">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}
?>