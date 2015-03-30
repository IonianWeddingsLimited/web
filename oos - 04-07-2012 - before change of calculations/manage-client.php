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

if($_POST["a"] == "Continue") {
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["id"]);
$sql_command->close();
}


if($_GET["client_id"]) { $_POST["client_id"] = $_GET["client_id"]; }
if($_GET["id"]) { $_POST["client_id"] = $_GET["id"]; }
if($_POST["id"]) { $_POST["client_id"] = $_POST["id"]; }


$client_info_result = $sql_command->select($database_clients,"iwcuid,title,firstname,lastname,wedding_date","WHERE id='".addslashes($_POST["client_id"])."'");
$client_info_record = $sql_command->result($client_info_result);

if($client_info_record[4]) { $wedding_date = date("d-m-Y",$client_info_record[4]); } else { $wedding_date = ""; }

$menu_line = "<div style=\"float:left; width:90px; margin:5px;\"><strong>IWCUID</strong></div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($client_info_record[0])."</div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:90px; margin:5px;\"><strong>Name</strong></div>
<div style=\"float:left;  margin:5px;\">".stripslashes($client_info_record[1])." ".stripslashes($client_info_record[2])." ".stripslashes($client_info_record[3])."</div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:90px; margin:5px;\"><strong>Wedding Date</strong></div>
<div style=\"float:left;  margin:5px;\">".$wedding_date."</div>
<div style=\"clear:left;\"></div>

<p style=\"margin-top:10px;\">[ <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]."\">Order History</a> | 
																								<a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_POST["client_id"]."\">Add Order</a> | 
																								<a href=\"$site_url/oos/manage-client.php?a=view&id=".$_POST["client_id"]."\">Update Client</a> | 
																								<a href=\"$site_url/oos/manage-client.php?a=add-note&id=".$_POST["client_id"]."\">Add Note</a> | 
																								<a href=\"$site_url/oos/manage-client.php?a=view-notes&id=".$_POST["client_id"]."\">View Notes</a> ] ";



if($_GET["a"] == "edit-order") {
	
include("manage_client/get_edit_order.php");

} elseif($_GET["a"] == "add-note") {
	
include("manage_client/get_add_note.php");

} elseif($_POST["action"] == "View Note") {
	
include("manage_client/get_view_note.php");


} elseif($_POST["action"] == "Add Note") {
	
include("manage_client/get_save_note.php");

} elseif($_POST["action"] == "Update Note") {
	
include("manage_client/get_save_update_note.php");

} elseif($_POST["action"] == "Delete Note") {
	
include("manage_client/get_delete_note.php");

} elseif($_GET["a"] == "view-notes") {
	
include("manage_client/get_view_notes.php");

} elseif($_POST["action"] == "Delete Order") {
	
$sql_command->delete($database_order_details,"id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_supplier_invoices,"order_id='".addslashes($_POST["invoice_id"])."'");
	
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();	
	
} elseif($_POST["action"] == "Update Order") {
	
include("manage_client/get_update_order_save.php");

} elseif($_GET["a"] == "create-invoice") {
	
include("manage_client/get_create_invoice.php");
	
} elseif($_POST["action"] == "Create Invoice") {

include("manage_client/get_create_invoice_save.php");

} elseif($_GET["a"] == "view-order") {

include("manage_client/get_view_order.php");

} elseif($_GET["a"] == "history") {
	
include("manage_client/get_history.php");

} elseif($_POST["action"] == "Update Status") {
	
	
if($_POST["delete"] == "Yes") {
$sql_command->delete($database_supplier_invoices,"invoice_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_customer_invoices,"id='".addslashes($_POST["invoice_id"])."'");	
$sql_command->delete($database_invoice_history,"invoice_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_order_history,"status='Outstanding'","invoice_id='".addslashes($_POST["invoice_id"])."'");

if($_POST["invoice_type"] == "Deposit") {
$sql_command->update($database_order_history,"status='Cancelled'","order_id='".addslashes($_POST["order_id"])."' and item_type='Deposit'");
}
$status_line = "Invoice Deleted (# ".$_POST["invoice_id"].")";
} else {

$sql_command->update($database_customer_invoices,"status='".addslashes($_POST["status"])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($_POST["invoice_id"])."'");

if($_POST["status"] == "Outstanding") {
$sql_command->update($database_invoice_history,"status='".addslashes($_POST["status"])."'","invoice_id='".addslashes($_POST["invoice_id"])."'");
} else {
$sql_command->update($database_order_history,"status='".addslashes($_POST["status"])."'","invoice_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_invoice_history,"status='".addslashes($_POST["status"])."'","invoice_id='".addslashes($_POST["invoice_id"])."'");	
}

if($_POST["invoice_type"] == "Deposit") {
$sql_command->update($database_order_history,"status='".addslashes($_POST["status"])."'","order_id='".addslashes($_POST["order_id"])."' and item_type='Deposit'");
}

$status_line = "Invoice Status (# ".$_POST["invoice_id"].") Updated - ".$_POST["status"];

if($_POST["status"] == "Cancelled") {
$sql_command->update($database_supplier_invoices,"status='".addslashes($_POST["status"])."'","invoice_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_customer_invoices,"updated_timestamp='".$time."'","id='".addslashes($_POST["invoice_id"])."'");
}

}

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','".$status_line."','".$time."'");

header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();
	
} elseif($_GET["a"] == "add-order") {
	
include("manage_client/get_add_order.php");

} elseif($_GET["a"] == "deposit") {
	
include("manage_client/get_deposit.php");

} elseif($_POST["action"] == "Add Deposit") {
	
include("manage_client/get_add_deposit.php");

} elseif($_GET["a"] == "selectpackage") {
	
include("manage_client/get_select_package.php");

} elseif($_POST["action"] == "Create Order") {

include("manage_client/get_process_new_order.php");

} elseif($_GET["a"] == "view") {
	
include("manage_client/get_update_client.php");

} elseif($_POST["action"] == "Update Client") {
	
include("manage_client/get_update_client_save.php");

} elseif($_GET["a"] == "change-package") {
	
include("manage_client/get_change_package.php");

} elseif($_POST["action"] == "save_change_package") {
	
include("manage_client/get_update_change_package.php");

} elseif($_POST["action"] == "Change Package") {

include("manage_client/get_save_change_package.php");

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_clients,"id='".addslashes($_POST["client_id"])."'");
	
$get_template->topHTML();
?>
<h1>Client Deleted</h1>

<p>The client has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$result = $sql_command->select($database_clients,"id,title,firstname,lastname","ORDER BY firstname,lastname");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[2])." ".stripslashes($record[3])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="a" value="Continue" />
<select name="id" class="inputbox_town" size="50" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="a" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>