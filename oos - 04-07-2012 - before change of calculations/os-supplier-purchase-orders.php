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


$result = $sql_command->select("$database_supplier_invoices,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices.id,
							   $database_supplier_invoices.order_id,
							   $database_supplier_invoices.cost,
							   $database_supplier_invoices.currency,
							   $database_supplier_invoices.status,
							   $database_supplier_invoices.timestamp,
							   $database_supplier_details.id,
							   $database_supplier_details.company_name,
							   $database_clients.id,
							   $database_clients.iwcuid","WHERE 
							   $database_supplier_invoices.supplier_id=$database_supplier_details.id and 
							   ($database_supplier_invoices.status = 'Outstanding' or $database_supplier_invoices.status = 'Pending') and 
							   $database_order_details.id=$database_supplier_invoices.order_id and 
							   $database_order_details.client_id=$database_clients.id 
							   ORDER BY $database_supplier_invoices.timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("d-m-Y",$record[5]);

if($record[4] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}

$list .= "
<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>

<div style=\"float:left; width:100px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".$record[9]."/".$record[1]."</a></div>
<div style=\"float:left; width:60px; margin:5px;\">$p_curreny ".$record[2]."</div>
<div style=\"float:left; width:40px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
<div style=\"float:left; width:260px; margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[6]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; margin:5px;\">".stripslashes($record[4])."</div>
<div style=\"clear:left;\"></div>
";
}

$get_template->topHTML();
?>
<h1>Outstanding Supplier Purchase Orders</h1>

<div style="float:left; width:80px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:100px; margin:5px;"><strong>IWCUID /Order #</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:40px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; width:260px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left;  margin:5px;"><strong>Status</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>


<?
$get_template->bottomHTML();
$sql_command->close();



?>