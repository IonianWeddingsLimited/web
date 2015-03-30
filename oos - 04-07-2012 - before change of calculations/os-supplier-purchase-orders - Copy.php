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

if($_GET["invoice"]) {
	
$result = $sql_command->select("$database_supplier_invoices,$database_supplier_details","$database_supplier_invoices.id,
							   $database_supplier_invoices.order_id,
							   $database_supplier_invoices.name,
							   $database_supplier_invoices.qty,
							   $database_supplier_invoices.cost,
							   $database_supplier_invoices.iw_cost,
							   $database_supplier_invoices.currency,
							   $database_supplier_invoices.status,
							   $database_supplier_invoices.exchange_rate,
							   $database_supplier_invoices.timestamp,
							   $database_supplier_details.company_name","WHERE 
							   $database_supplier_invoices.id='".addslashes($_GET["invoice"])."' and
							   $database_supplier_invoices.supplier_id=$database_supplier_details.id 
							   ORDER BY $database_supplier_invoices.timestamp DESC");
$record = $sql_command->result($result);	
	
$dateline = date("d-m-Y",$record[9]);

$start = strpos($record[2], '<p>');
$end = strpos($record[2], '</p>', $start);
$paragraph = substr($record[2], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$get_template->topHTML();
?>
<h1>O/S Supplier Purchase Order</h1>

<form action="<?php echo $site_url; ?>/oos/os-supplier-purchase-orders.php" method="post">
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice"]; ?>" />
<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; margin:5px;"><?php echo $dateline; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[10]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Description</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="description" value="<?php echo stripslashes($paragraph); ?>" style="width:500px;" maxlength="250"></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="qty" value="<?php echo stripslashes($record[3]); ?>" style="width:50px;"></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Cost</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="cost" value="<?php echo stripslashes($record[4]); ?>" style="width:50px;"></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Currency</strong></div>
<div style="float:left; margin:5px;"><select name="currency">
<option value="Euro" <?php if($record[6] == "Euro") { echo "selected=\"selected\""; } ?>>Euro</option>
<option value="Pound" <?php if($record[6] == "Pound") { echo "selected=\"selected\""; } ?>>Pound</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Status</strong></div>
<div style="float:left; margin:5px;"><select name="status">
<option value="Outstanding" <?php if($record[7] == "Outstanding") { echo "selected=\"selected\""; } ?>>Outstanding</option>
<option value="Paid" <?php if($record[6] == "Paid") { echo "selected=\"selected\""; } ?>>Paid</option>
<option value="Invoice Issued" <?php if($record[7] == "Invoice Issued") { echo "selected=\"selected\""; } ?>>Invoice Issued</option>
</select></div>
<div style="clear:left;"></div>

<div style="float:left;"><p><input type="submit" name="action" value="Update" /></p></div>
<div style="float:right;"><p><input type="submit" name="action" value="Generate Invoice" /></p></div>
<div style="clear:both;"></div>
</form>


<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Generate Invoice") {

header("Location: $site_url/oos/purchase-order.php?purchase_order=".$_POST["invoice_id"]);
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
if(!$_POST["description"]) { $error .= "Missing Description<br>"; }
if(!$_POST["qty"]) { $error .= "Missing QTY<br>"; }
if(!$_POST["cost"]) { $error .= "Missing Cost<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Invoice Details","Oops!","$error","Link","oos/os-supplier-purchase-orders.php?invoice=".$_POST["invoice_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_supplier_invoices,"name='".addslashes($_POST["description"])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_supplier_invoices,"qty='".addslashes($_POST["qty"])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_supplier_invoices,"cost='".addslashes($_POST["cost"])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_supplier_invoices,"currency='".addslashes($_POST["currency"])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_supplier_invoices,"status='".addslashes($_POST["status"])."'","id='".addslashes($_POST["invoice_id"])."'");

$get_template->topHTML();
?>
<h1>Purchase Order Updated</h1>

<p>The purchase order has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} else {

$result = $sql_command->select("$database_supplier_invoices,$database_supplier_details","$database_supplier_invoices.id,
							   $database_supplier_invoices.timestamp,
							   $database_supplier_details.company_name","WHERE 
							   $database_supplier_invoices.supplier_id=$database_supplier_details.id 
							   ORDER BY $database_supplier_invoices.timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("d-m-Y",$record[1]);

$list .= "
<div style=\"float:left; width:140px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:340px; margin:5px;\">".stripslashes($record[2])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><a href=\"$site_url/oos/os-supplier-purchase-orders.php?invoice=$record[0]\">View</a></div>
<div style=\"clear:left;\"></div>
";
}

$get_template->topHTML();
?>
<h1>O/S Supplier Purchase Order</h1>

<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:340px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left; width:30px; margin:5px;"></div>
<div style="clear:left;"></div>

<?php echo $list; ?>


<?
$get_template->bottomHTML();
$sql_command->close();
}

?>