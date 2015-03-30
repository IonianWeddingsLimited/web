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

	
$result = $sql_command->select("$database_customer_invoices,$database_order_details,$database_clients","$database_customer_invoices.id,
							   $database_customer_invoices.order_id,
							   $database_customer_invoices.iw_cost,
							   $database_customer_invoices.status,
							   $database_customer_invoices.timestamp,
							   $database_customer_invoices.type,
							   $database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname","WHERE 
							   $database_customer_invoices.order_id=$database_order_details.id and
							   $database_order_details.client_id=$database_clients.id and 
							   ($database_customer_invoices.status != 'Outstanding' and $database_customer_invoices.status != 'Pending')
							   ORDER BY $database_customer_invoices.timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("d-m-Y",$record[4]);

$list .= "
<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:80px; margin:5px;\">".$record[1]."</div>
<div style=\"float:left; width:80px; margin:5px;\">&pound; ".$record[2]."</div>
<div style=\"float:left; width:140px; margin:5px;\">".$record[5]."</div>
<div style=\"float:left; width:140px; margin:5px;\">".$record[3]."</div>
<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[6]."\">".stripslashes($record[7])." ".stripslashes($record[8])." ".stripslashes($record[9])."</a></div>
<div style=\"clear:left;\"></div>
";
}

$get_template->topHTML();
?>
<h1>Archived Client Invoices</h1>

<div style="float:left; width:80px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Type</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Status</strong></div>
<div style="float:left; margin:5px;"><strong>Client</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>


<?
$get_template->bottomHTML();
$sql_command->close();


?>