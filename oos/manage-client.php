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

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


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


$client_info_result = $sql_command->select($database_clients,"iwcuid,title,firstname,lastname,wedding_date,groom_title,groom_firstname,groom_surname","WHERE id='".addslashes($_POST["client_id"])."'");
$client_info_record = $sql_command->result($client_info_result);

if($client_info_record[4]) { $wedding_date = date("d-m-Y",$client_info_record[4]); } else { $wedding_date = ""; }

$menu_line = "<div style=\"float:left; width:90px; margin:5px;\"><strong>IWCUID</strong></div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($client_info_record[0])."</div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:90px; margin:5px;\"><strong>Name</strong></div>
<div style=\"float:left;  margin:5px;\">".stripslashes($client_info_record[1])." ".stripslashes($client_info_record[2])." ".stripslashes($client_info_record[3])." &amp; ".stripslashes($client_info_record[5])." ".stripslashes($client_info_record[6])." ".stripslashes($client_info_record[7])."</div>
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

include("manage_client/copy_get_edit_order.php");

} elseif($_GET["a"] == "add-note") {
	
include("manage_client/get_add_note.php");

} elseif($_POST["action"] == "View Note") {
	
include("manage_client/get_view_note.php");

} elseif($_POST["action"] == "Email Selected Suppliers") {
	
include("manage_client/get_email_supplier.php");
	
}

elseif($_POST["action"]=="Email PO to Suppliers") {
	
include("manage_client/get_email_supplier.php");
	
}

elseif($_POST["action"] == "Add Note") {
	
include("manage_client/get_save_note.php");

} elseif($_POST["action"] == "Update Note") {
	
include("manage_client/get_save_update_note.php");

} elseif($_POST["action"] == "Delete Note") {
	
include("manage_client/get_delete_note.php");

} elseif ($_POST["action"] =="Update Comments") {

include("manage_client/get_update_comments.php");
	
} elseif($_GET["a"] == "view-notes") {
	
include("manage_client/get_view_notes.php");

} elseif($_POST["action"] == "Delete Order") {
	
$sql_command->delete($database_order_details,"id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_supplier_invoices,"order_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_supplier_invoices_main,"order_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_supplier_invoices_details,"order_id='".addslashes($_POST["invoice_id"])."'");

header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();	
	
} elseif($_POST["action"] == "Update Order") {
	
include("manage_client/copy_get_update_order_save.php");

} elseif($_GET["a"] == "create-invoice") {

include("manage_client/copy_get_create_invoice.php");	
	
} elseif($_POST["action"] == "Create Invoice") {
	
include("manage_client/copy_get_create_invoice_save.php");

} elseif($_GET["a"] == "view-order") {

//switch ($the_username) {
//	case "u1":
//		include("manage_client/get_view_order_new.php");
//	break;
//	default:
		include("manage_client/get_view_order.php");
//	break;
//}

} elseif($_GET["a"] == "history") {
	
include("manage_client/get_history.php");

} elseif($_POST["action"] == "Update Status") {
	
	
	if($_POST["delete"] == "Yes") {
		$sql_command->delete($database_supplier_invoices,"invoice_id='".addslashes($_POST["invoice_id"])."'");
		$sql_command->delete($database_supplier_invoices_main,"invoice_id='".addslashes($_POST["invoice_id"])."'");
		$sql_command->delete($database_supplier_invoices_details,"invoice_id='".addslashes($_POST["invoice_id"])."'");
		$sql_command->delete($database_customer_invoices,"id='".addslashes($_POST["invoice_id"])."'");	
		$sql_command->delete($database_invoice_history,"invoice_id='".addslashes($_POST["invoice_id"])."'");
		$sql_command->update($database_order_history,"status='Outstanding'","invoice_id='".addslashes($_POST["invoice_id"])."'");

		if($_POST["invoice_type"] == "Deposit") {
			$sql_command->update($database_order_history,"status='Cancelled'","order_id='".addslashes($_POST["order_id"])."' and item_type='Deposit'");
		}
		$status_line = "Invoice Deleted (# ".$_POST["invoice_id"].")";
	}
	else {
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

include("manage_client/copy_get_add_order.php");

} elseif($_GET["a"] == "deposit") {
	
include("manage_client/get_deposit.php");

} elseif($_GET["a"] == "fee") {
	
include("manage_client/get_fee.php");

} elseif($_GET["a"] == "emailinv") {
	
include("manage_client/get_email.php");

}
elseif($_POST["action"] == "Send Email") {
	
include("manage_client/get_email.php");

}
elseif($_GET["a"] == "emailpass") {
	
include("manage_client/get_email.php");

}
elseif($_GET["a"] == "emailconfirm") {
	
include("manage_client/get_email.php");

}
elseif($_GET["a"] == "partpay") {
	
include("manage_client/get_partpay.php");

}elseif($_POST["action"] == "Add Deposit") {
	
include("manage_client/get_add_deposit.php");

} elseif($_POST["action"] == "Add Fee") {
	
include("manage_client/get_add_fee.php");

} elseif($_POST["action"] == "Add Payment") {
	
include("manage_client/get_add_payment.php");

}
elseif($_POST["action"] == "Modify Payment") {
	
include("manage_client/get_add_payment.php");

}
elseif($_POST["action"] == "Approve Payments") {
	
include("manage_client/modify_payments.php");

}
elseif($_POST["action"] == "Delete Payments") {
	
include("manage_client/modify_payments.php");

}

elseif($_GET["a"] == "selectpackage") {

include("manage_client/copy_get_select_package.php");

} elseif($_POST["action"] == "Create Order") {

include("manage_client/copy_get_process_new_order.php");

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
	
if($_POST["action"] == "Download CSV") {
	header("Location: ".$site_url."/oos/download-client.php?type=csv&data=".$_POST["data"]."&from=".$_POST["date_from"]."&to=".$_POST["date_to"]."&destination=".$_POST["destination_id"]);
	exit();
}

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {

	if($level1_record[1] == "Destinations") {

		$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
		$level2_row = $sql_command->results($level2_result);
	
		foreach($level2_row as $level2_record) {	


			$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\" disabled=\"disabled\">".stripslashes($level2_record[1])."</option>\n";
	
			$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
			$level3_row = $sql_command->results($level3_result);

			foreach($level3_row as $level3_record) {
			$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
			}
		}
	}
}

$result = $sql_command->select("$database_clients,clients_options",
							   "$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.destination,
							   $database_clients.groom_title,
							   $database_clients.groom_firstname,
							   $database_clients.groom_surname,
							   $database_clients.iwcuid,
							   $database_clients.wedding_date",
							   "WHERE $database_clients.deleted='No'
								AND $database_clients.id = clients_options.client_id 
								AND clients_options.client_option = 'client_type'
								AND clients_options.option_value!='Prospect'
								AND clients_options.option_value!='Imageine' 
								ORDER BY $database_clients.firstname,$database_clients.lastname");
$row = $sql_command->results($result);

foreach($row as $record) {

$des_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($record[4])."'");
$des_record = $sql_command->result($des_result);
$date = date("d-m-Y",$record[9]);

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[2])." ".stripslashes($record[3])." - ".stripslashes($record[6])." ".stripslashes($record[7])." ( ".stripslashes($record[8])." ) - ".$date." - ".stripslashes($des_record[1])."</option>\n";
}

$result2 = $sql_command->select($database_clients,"id,title,firstname,lastname,wedding_date","WHERE deleted='No' AND imageine='Yes' ORDER BY firstname,lastname");
$row2 = $sql_command->results($result2);

foreach($row2 as $record2) {
$date2 = date("d-m-Y",$record2[4]);

$list2 .= "<option value=\"".stripslashes($record2[0])."\" style=\"font-size:10px;\">".stripslashes($record2[2])." ".stripslashes($record2[3])." - ".$date2."</option>\n";
}

$get_template->topHTML();
?>
<h1>Download Clients</h1>
<form method="post" action="<?php echo $site_url; ?>/oos/manage-client.php" name="getcsvdata">
<input type="hidden" name="data" value="client" />
<div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_from"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_from'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_to"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_to'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left;  margin:5px;"><select name="destination_id" size="10" style="width:500px;">
<?php echo $nav_list; ?>
</select>
</div>
<div style="clear:left;"></div>
<input type="submit" name="action" value="Download CSV" />
</form>
<p>&nbsp;</p>

<h1>Manage Client</h1>



<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="a" value="Continue" />
<select name="id" class="inputbox_town" size="50" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>
<br /><br />

<p style="margin-top:10px;"><input type="submit" name="a" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>