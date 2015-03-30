<?
require ("../../_includes/settings.php");
require ("../../_includes/function.templates.php");
include ("../../_includes/function.database.php");
include ("../../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("../run_login.php");

// Get Templates
$get_template = new oos_template();

$add_header = " <script language='JavaScript'>
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


if($_GET["id"]) {
	$_GET["id"] = $_GET["id"];
}

if($_GET["id"] == "") {  	
	$get_template->topHTML();
?>
	<h1>No Client Selected</h1>
	<p>Please go back and select a cleint</p>
<?
	$get_template->bottomHTML();
	$sql_command->close();	
	
} else {

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
									$database_clients.id,
									$database_clients.title,
									$database_clients.firstname,
									$database_clients.lastname,
									$database_clients.iwcuid","WHERE 
									$database_supplier_details.deleted='No' and 
									$database_clients.deleted='No' and 
									$database_order_details.client_id='".addslashes($_GET["id"])."' and 
									$database_supplier_details.id!='21' and 
									$database_supplier_invoices_main.supplier_id=$database_supplier_details.id  and
									$database_order_details.id=$database_supplier_invoices_main.order_id and 
									$database_order_details.client_id=$database_clients.id 
									ORDER BY $database_supplier_invoices_main.timestamp DESC");
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
	$cost = 0;
	
	$purchase_order = $record[0];
	
	include("../purchase-order_calc.php");
	
	$cost = number_format($payment_total,2);

	$list2 .=	"<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
				<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
				<div style=\"float:left; width:150px; margin:5px;\">$p_curreny ".$cost."</div>
				<div style=\"float:left; width:50px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
				<div style=\"float:left;  margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[2]."\">".stripslashes($record[7])."</a></div>
				<div style=\"clear:left;\"></div>";
	}

	$get_template->topHTML();
?>
<?php if($list2) { ?>
<h3>Download Multiple Purchase Orders</h3>
<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order-by-order.php" name="download" id="downloadPDF">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<div style="float:left; width:40px; margin:5px;"><strong>Select</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left;  margin:5px;"><strong>Supplier</strong></div>
<div style="clear:left;"></div>
<?php echo $list2; ?>
<div style="float:left; width:40px; margin:5px 5px 5px 5px;"><input type='checkbox' name='checkall' onclick='checkedAll();'></div>
<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Download Selected Purchase Orders"></p>
</form>
<?php } ?>
<?
	$get_template->bottomHTML();
	$sql_command->close();
}
?>