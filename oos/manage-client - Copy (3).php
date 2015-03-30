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


if($_GET["a"] == "view" or $_GET["a"] == "history") {
$menu_line = "<p>[ <a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_GET["id"]."\">Add Order</a> | <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_GET["id"]."\">Order History</a> | <a href=\"$site_url/oos/manage-client.php?a=view&id=".$_GET["id"]."\">Update Client</a> ] ";
} else if($_GET["a"] == "add-order") {
$menu_line = "<p>[ <a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_GET["id"]."\">Add Order</a> | <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_GET["id"]."\">Order History</a> | <a href=\"$site_url/oos/manage-client.php?a=view&id=".$_GET["id"]."\">Update Client</a> ] ";
} else if($_GET["a"] == "create-invoice") {
$menu_line = "<p>[ <a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_GET["id"]."\">Add Order</a> | <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_GET["id"]."\">Order History</a> | <a href=\"$site_url/oos/manage-client.php?a=view&id=".$_GET["id"]."\">Update Client</a> ] ";
} elseif($_POST["a"] or $_POST["action"]) {
$menu_line = "<p>[ <a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_POST["client_id"]."\">Add Order</a> | <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_POST["id"]."\">Order History</a> | <a href=\"$site_url/oos/manage-client.php?a=view&id=".$_POST["client_id"]."\">Update Client</a> ] ";	
 }else {
$menu_line = "<p>[ <a href=\"$site_url/oos/manage-client.php?a=add-order&id=".$_GET["id"]."\">Add Order</a> | <a href=\"$site_url/oos/manage-client.php?a=history&id=".$_GET["id"]."\">Order History</a> | <a href=\"$site_url/oos/manage-client.php?a=create-invoice&id=".$_GET["id"]."\">Create Invoice</a> | <a href=\"$site_url/oos/manage-client.php?a=view&id=".$_GET["id"]."\">Update Client</a> ] ";	
}


if($_GET["a"] == "edit-order") {
	
	
	
	
	
	
	
	
$package_info_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info","$database_order_details.package_id,
									$database_order_details.package_cost,
									$database_order_details.package_iw_cost,
									$database_order_details.package_currency,
								    $database_packages.package_name,
									$database_package_info.iw_name,
									$database_packages.island_id
									","WHERE $database_order_details.id='".addslashes($_GET["invoice_id"])."' and
									$database_order_details.package_id=$database_package_info.id and 
									$database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);


if($package_info_record[3] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[1];
$total_iw_pound_cost += $package_info_record[2];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[1];
$total_iw_euro_cost += $package_info_record[2];
}


$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE island_id='".addslashes($package_info_record[6])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE menu_id='".addslashes($menu_record[0])."' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($menu_option_record[0])."' and item_type='Menu' and type='Extra'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
$iw_cost = number_format($menu_option_record[4] - $get_checked_record[1],2);
} else { 
$selected = ""; 
$percent_value = ($menu_option_record[4] / 100);
$iw_cost = number_format($menu_option_record[4] - ($percent_value * $get_checked_record[1]) ,2);
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$iw_cost = number_format($menu_option_record[4],2);
}

$html_menu .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:400px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($menu_option_record[3])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($iw_cost)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$menu_option_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$menu_option_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";
}

}




$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($package_info_record[6])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 island_id='".addslashes($package_info_record[6])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Extra'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
$iw_cost = number_format($extra_record[4] - $get_checked_record[1],2);
} else { 
$selected = ""; 
$percent_value = ($extra_record[4] / 100);
$iw_cost = number_format($extra_record[4] - ($percent_value * $get_checked_record[1]) ,2);
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$iw_cost = number_format($extra_record[4],2);
}

$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:400px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($iw_cost)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}


$total_rows2 = $sql_command->count_rows($database_package_extras,"id","category_id='".addslashes($extra_cat_record[0])."' and type='Service'");

if($total_rows2 > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$service_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 category_id=".addslashes($extra_cat_record[0])." and 
									 type='Service'
									 ORDER BY product_name");
$service_row = $sql_command->results($service_result);

foreach($service_row as $service_record) {
if($service_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($service_record[0])."' and item_type='Service Fee'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
$iw_cost = number_format($service_record[4] - $get_checked_record[1],2);
} else { 
$selected = ""; 
$percent_value = ($service_record[4] / 100);
$iw_cost = number_format($service_record[4] - ($percent_value * $get_checked_record[1]) ,2);
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$iw_cost = number_format($service_record[4],2);
}
$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($service_record[3])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".stripslashes($iw_cost)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_d_value_$service_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"service_d_type_$service_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}

}





$menu_result = $sql_command->select("$database_package_includes,$database_menus,$database_menu_options","$database_menu_options.id,
									$database_package_includes.qty,
									$database_menus.menu_name_iw,
									$database_menus.local_tax_percent,
									$database_menus.discount_at,
									$database_menus.discount_percent,
									$database_menu_options.menu_name,
									$database_menu_options.cost,
									$database_menu_options.cost_iw,
									$database_menu_options.currency
									","WHERE $database_package_includes.package_id='".addslashes($package_info_record[0])."' and
									$database_package_includes.type_id=$database_menu_options.id and
									$database_menu_options.menu_id=$database_menus.id and
									$database_package_includes.type='Menu'");
$menu_row = $sql_command->results($menu_result);

$added = "No";

foreach($menu_row as $menu_record) {
if($menu_record[9] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_menu_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[2])."</h3>";
$added = "Yes";
}

$html_menu_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:30px;\" value=\"".stripslashes($menu_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($menu_record[6])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}



$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($package_info_record[0])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_extra_record[6])."</h3>";
$added = "Yes";
}

$html_extra_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($package_extra_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$package_service_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($package_info_record[0])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Service Fee'");
$package_service_row = $sql_command->results($package_service_result);

$added = "No";

foreach($package_service_row as $package_service_record) {
if($package_service_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_service_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_service_record[6])."</h3>";
$added = "Yes";
}

$html_service_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($package_service_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Edit Order</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $package_info_record[6]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $package_info_record[0]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />

<div style="float:left; width:40px; margin:5px;">1 x</div>
<div style="float:left; width:490px; margin:5px;"><?php echo stripslashes($package_info_record[4]); ?> > <?php echo stripslashes($package_info_record[5]); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $curreny; ?> <?php echo stripslashes($package_info_record[1]); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="clear:left;\"></div>


<?php if($html_menu_included or $html_extra_included or $html_service_included) { ?><h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1><?php } ?>


<?php if($html_menu_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu_included; ?>

<?php if($html_extra_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra_included; ?>

<?php if($html_service_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service_included; ?>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Add Additional Options to Package</h1>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<div style="float:left;">
<p><input type="submit" name="action" value="Update Order"></p>
</div>
</form>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $package_info_record[6]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $package_info_record[0]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<script language="javascript" type="text/javascript">

function deletechecked()
{
    var answer = confirm("Confirm Delete")
    if (answer){
        document.messages.submit();
    }
    
    return false;  
}  

</script>
<div style="float:right;">
<p><input type="submit" name="action" value="Delete Order" onclick="return deletechecked();"></p>
</div>
</form>
<div style="clear:both;"></div>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete Order") {
	
$sql_command->delete($database_order_details,"id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."'");
$sql_command->delete($database_supplier_invoices,"order_id='".addslashes($_POST["invoice_id"])."'");
	
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();	
	
} elseif($_POST["action"] == "Update Order") {
	
$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp";
$cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type";

$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Menu'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Extra'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Service Fee'");
$sql_command->delete($database_supplier_invoices,"order_id='".addslashes($_POST["invoice_id"])."'");

$menu_option_result = $sql_command->select("$database_menu_options,$database_menus","$database_menu_options.id,
										   $database_menu_options.menu_name,
										   $database_menu_options.cost,
										   $database_menu_options.cost_iw,
										   $database_menu_options.currency,
										   $database_menus.supplier_id,
										   $database_menus.venue_id,
										   $database_menus.island_id,
										   $database_menus.menu_name_iw,
										   $database_menus.local_tax_percent,
										   $database_menus.discount_at,
										   $database_menus.discount_percent","WHERE $database_menu_options.menu_id=$database_menus.id");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$add_supplier = "No";

$qty = "menu_included_qty_".$menu_option_record[0];	
		 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty])."',
'".addslashes($menu_option_record[2])."',
'0',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Included',
'Outstanding',
'0',
'$time',
'',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}

$qty_extra = "menu_qty_".$menu_option_record[0];
$d_value_extra = "menu_d_value_".$menu_option_record[0];
$d_type_extra = "menu_d_type_".$menu_option_record[0];

if($_POST[$qty_extra] > 0) {
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."'";
$sql_command->insert($database_order_history,$cols,$values);	
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[5])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($total_qty)."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[4])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	

	
	
	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "extra_included_qty_".$package_extra_record[0];	
			 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time',
'',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}


$qty_extra = "extra_qty_".$package_extra_record[0];
$d_value_extra = "extra_value_".$menu_option_record[0];
$d_type_extra = "extra_type_".$menu_option_record[0];

if($_POST[$qty_extra] > 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."'";
$sql_command->insert($database_order_history,$cols,$values);		
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[1])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($total_qty)."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'".addslashes($package_extra_record[5])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	


	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Service'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "service_included_qty_".$package_extra_record[0];	
			 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";

$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);	
}

$qty_extra = "service_qty_".$package_extra_record[0];
$d_value_extra = "service_value_".$menu_option_record[0];
$d_type_extra = "service_type_".$menu_option_record[0];

if($_POST[$qty_extra] > 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Service Fee',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."'";
$sql_command->insert($database_order_history,$cols,$values);		
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[1])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($total_qty)."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'".addslashes($package_extra_record[5])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	
	
	
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();	
	
	
	
	
} elseif($_GET["a"] == "create-invoice") {
	

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info","
									$database_order_details.package_id,
									$database_order_details.package_cost,
									$database_order_details.package_iw_cost,
									$database_order_details.package_currency,
								    $database_packages.package_name,
									$database_package_info.iw_name
									","WHERE $database_order_details.id='".addslashes($_GET["invoice_id"])."' and
									$database_order_details.package_id=$database_package_info.id and 
									$database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);


if($package_info_record[3] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[1];
$total_iw_pound_cost += $package_info_record[2];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[1];
$total_iw_euro_cost += $package_info_record[2];
}






$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."' and $database_order_history.type='Extra'");

if($total_count > 0) {
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select("$database_order_history,$database_menu_options","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.status
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."' and $database_order_history.type='Extra'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}

if($menu_option_record[8] == "Pound") { 
$curreny = "&pound;"; 
$total_pound_cost += $menu_option_record[2] * $menu_option_record[3];
$total_iw_pound_cost += $menu_option_record[2] * $menu_option_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $menu_option_record[2] * $menu_option_record[3];
$total_iw_euro_cost += $menu_option_record[2] * $menu_option_record[4];
}

if($menu_option_record[11] == "Outstanding") {
$option_status = "<input type=\"checkbox\" name=\"id_$menu_option_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($menu_option_record[11]);	
}

$html_menu .= "<div style=\"float:left; width:40px; margin:5px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($menu_option_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($menu_option_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
<div style=\"clear:left;\"></div>";
}
}
}












$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and $database_order_history.type='Extra'");

if($total_count > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.status
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and $database_order_history.type='Extra'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $extra_record[2] * $extra_record[3];
$total_iw_pound_cost += $extra_record[2] * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $extra_record[2] * $extra_record[3];
$total_iw_euro_cost += $extra_record[2] * $extra_record[4];
}

if($extra_record[11] == "Outstanding") {
$option_status = "<input type=\"checkbox\" name=\"id_$extra_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($extra_record[11]);	
}

$html_extra .= "<div style=\"float:left; width:40px; margin:5px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"clear:left;\"></div>";

}	
}
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and $database_order_history.type='Extra'");

if($total_count > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.status
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and $database_order_history.type='Extra'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {

if($extra_record[8] == "Pound") { 
$curreny = "&pound;";
$total_pound_cost += $extra_record[2] * $extra_record[3];
$total_iw_pound_cost += $extra_record[2] * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $extra_record[2] * $extra_record[3];
$total_iw_euro_cost += $extra_record[2] * $extra_record[4];
}

if($extra_record[11] == "Outstanding") {
$option_status = "<input type=\"checkbox\" name=\"id_$extra_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($extra_record[11]);	
}

$html_service .= "<div style=\"float:left; width:40px; margin:5px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"clear:left;\"></div>";

}	
}
}




$include_result = $sql_command->select("$database_order_history","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty","WHERE $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and $database_order_history.type='Included'");
$include_row = $sql_command->results($include_result);

foreach($include_row as $include_record) {
$start = strpos($include_record[1], '<p>');
$end = strpos($include_record[1], '</p>', $start);
$paragraph = substr($include_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$includes .= stripslashes($include_record[2])."x ".stripslashes($paragraph)."<br>";	
}

$package_paid_result = $sql_command->select($database_order_history,"id,status","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_paid_record = $sql_command->result($package_paid_result);


$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Create Invoice Details</h2>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Please tick the boxes for the items you want to create an invoice for.</h1>
<form action="<?php echo $sit_url; ?>/oos/manage-client.php" method="post">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />

<h2 style="margin-top:10px; margin-bottom:10px;">Package</h2>
<?php if($package_paid_record[1] == "Outstanding") { ?>
<div style="float:left; width:40px; margin:5px;"><input type="hidden" name="package_amount" value="<?php echo stripslashes($package_info_record[2]); ?>"/><input type="checkbox" name="package_payment" value="Yes"></div>
<?php } else { ?>
<div style="float:left; width:40px; margin:5px;"><?php echo stripslashes($package_paid_record[1]); ?></div>
<?php } ?>
<div style="float:left; width:30px; margin:5px;">1 x</div>
<div style="float:left; width:450px; margin:5px;"><?php echo stripslashes($package_info_record[4]); ?> > <?php echo stripslashes($package_info_record[5]); ?><br /><strong>Includes</strong><br /><?php echo $includes; ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[1]); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="clear:left;\"></div>


<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" value="0"></div>
<div style="clear:left;\"></div>


<p><input type="submit" name="action" value="Create Invoice" /></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} elseif($_POST["action"] == "Create Invoice") {
$order_result = $sql_command->select($database_order_history,"id,qty,cost,iw_cost,currency","WHERE order_id='".addslashes($_POST["invoice_id"])."' and status='Outstanding'");
$order_row = $sql_command->results($order_result);

$pass_ok = "No";

foreach($order_row as $order_record) {
$theitem = "id_".$order_record[0];	

if($_POST[$theitem] == "Yes") { 
$pass_ok = "Yes";



}
}

if($_POST["exchange_rate"] < 1) {
$_POST["exchange_rate"] = 1;
}


if($pass_ok == "No" and $_POST["package_payment"] != "Yes") {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}
	
$total_due = 0;

$cols = "order_id,total_due,status,timestamp";
$sql_command->insert($database_customer_invoices,$cols,"'".addslashes($_POST["invoice_id"])."','','Outstanding','$time'");
$maxid = $sql_command->maxid($database_customer_invoices,"id","");

$cols = "invoice_id,history_id";


foreach($order_row as $order_record) {
$theitem = "id_".$order_record[0];	

if($_POST[$theitem] == "Yes") { 

if($order_record[4] == "Pound") {
$total_due += $order_record[1] * $order_record[3];
} else {
$exchanged = $order_record[3] * $_POST["exchange_rate"];
$total_due += $order_record[1] * $exchanged;
}
$sql_command->insert($database_customer_invoices_items,$cols,"'$maxid','".addslashes($order_record[0])."'");
$sql_command->update($database_order_history,"status='Invoice Issued'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
$sql_command->update($database_order_history,"exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
}
}

if($_POST["package_payment"] == "Yes") {
$sql_command->update($database_order_history,"status='Invoice Issued'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");

$total_due += $_POST["package_amount"] * $_POST["exchange_rate"];
}


$sql_command->update($database_customer_invoices,"total_due='".stripslashes($total_due)."'","id='".$maxid."'");



header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();


} elseif($_GET["a"] == "view_order") {
	

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info","
									$database_order_details.package_id,
									$database_order_details.package_cost,
									$database_order_details.package_iw_cost,
									$database_order_details.package_currency,
								    $database_packages.package_name,
									$database_package_info.iw_name
									","WHERE $database_order_details.id='".addslashes($_GET["invoice_id"])."' and
									$database_order_details.package_id=$database_package_info.id and 
									$database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);


if($package_info_record[3] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[1];
$total_iw_pound_cost += $package_info_record[2];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[1];
$total_iw_euro_cost += $package_info_record[2];
}






$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");

if($total_count > 0) {
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select("$database_order_history,$database_menu_options","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}

if($menu_option_record[8] == "Pound") { 
$curreny = "&pound;"; 
$total_pound_cost += $menu_option_record[2] * $menu_option_record[3];
$total_iw_pound_cost += $menu_option_record[2] * $menu_option_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $menu_option_record[2] * $menu_option_record[3];
$total_iw_euro_cost += $menu_option_record[2] * $menu_option_record[4];
}



$html_menu .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($menu_option_record[2])." x</div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($menu_option_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
<div style=\"clear:left;\"></div>";
}
}
}












$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $extra_record[2] * $extra_record[3];
$total_iw_pound_cost += $extra_record[2] * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $extra_record[2] * $extra_record[3];
$total_iw_euro_cost += $extra_record[2] * $extra_record[4];
}
$html_extra .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"clear:left;\"></div>";

}	
}
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}

if($extra_record[8] == "Pound") { 
$curreny = "&pound;";
$total_pound_cost += $extra_record[2] * $extra_record[3];
$total_iw_pound_cost += $extra_record[2] * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $extra_record[2] * $extra_record[3];
$total_iw_euro_cost += $extra_record[2] * $extra_record[4];
}
$html_service .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"clear:left;\"></div>";

}	
}
}


$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Order Details</h2>

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($package_info_record[4]); ?> > <?php echo stripslashes($package_info_record[5]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Iw Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="clear:left;"></div>


<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Package Options</h1>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<div style="float:left; width:120px; margin:5px;"><strong>Total Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo $total_euro_cost; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Total Iw Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo $total_iw_euro_cost; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Total Euro Profit</strong></div>
<div style="float:left; margin:5px;">&euro; <?php $total_euro = $total_iw_euro_cost -  $total_euro_cost; echo $total_euro; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Total Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo $total_pound_cost; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Total Iw Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo $total_iw_pound_cost; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Total Pound Profile</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $total_pound = $total_iw_pound_cost - $total_pound_cost; echo $total_pound; ?></div>
<div style="clear:left;"></div>
<?
$get_template->bottomHTML();
$sql_command->close();





	
	

} elseif($_GET["a"] == "history") {

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
<div style=\"float:left; width:90px; margin:5px;\">".stripslashes($record[0])."</div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[3])."</div>
<div style=\"float:left; width:280px; margin:5px;\">".stripslashes($record[1])." - ".stripslashes($record[2])."</div>
<div style=\"float:left; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=view_order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">View</a> | <a href=\"$site_url/oos/manage-client.php?a=edit-order&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Edit</a> |  <a href=\"$site_url/oos/manage-client.php?a=create-invoice&id=".$_GET["id"]."&invoice_id=".$record[0]."\">Create Invoice</a></div>
<div style=\"clear:left;\"></div>";
}


$result = $sql_command->select("$database_customer_invoices,$database_order_details","$database_customer_invoices.order_id,
							   $database_customer_invoices.total_due,
							   $database_customer_invoices.status,
							   $database_customer_invoices.timestamp","WHERE 
							   $database_customer_invoices.order_id=$database_order_details.id AND
							   $database_order_details.client_id='".addslashes($_GET["id"])."'
							   ORDER BY $database_customer_invoices.timestamp DESC");
$row = $sql_command->results($result);



	
foreach($row as $record) {

$dateline = date("d-m-Y",$record[3]);

$list .= "
<div style=\"float:left; width:140px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:140px; margin:5px;\">&pound; ".stripslashes($record[1])."</div>
<div style=\"float:left; width:140px; margin:5px;\">".stripslashes($record[2])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?view-invoice=$record[0]&id=".$_GET["id"]."\" target=\"_blank\">View</a></div>
<div style=\"clear:left;\"></div>
";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Order History</h2>

<div style="float:left; width:90px; margin:5px;"><strong>Order Number</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; width:300px; margin:5px;"><strong>Package</strong></div>
<div style="clear:left;"></div>

<?php echo $html; ?>

<h2>Invoice History</h2>

<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Status</strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_GET["a"] == "view-invoice") {
	
	
	
} elseif($_GET["a"] == "add-order") {
	
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Add Order</h2>


<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="action" value="Select Island" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Select Island"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Select Island") {
	
	
$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE island_id='".addslashes($_POST["island_id"])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {	
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px; font-weight:bold; color:#F00;\" disabled=\"disabled\">".stripslashes($menu_record[1])."</option>\n";


$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE package_id='".addslashes($menu_record[0])."' ORDER BY id");
$item_row = $sql_command->results($item_result);

foreach($item_row as $item_record) {	
$list .= "<option value=\"".stripslashes($item_record[0])."\" style=\"font-size:11px;\">".stripslashes($item_record[1])."</option>\n";
}

}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Add Order > Select Package</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="action" value="Select Package" />
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />

<select name="package_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Select Package"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Select Package") {
	
	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id,cost,iw_cost,currency","WHERE id='".addslashes($_POST["package_id"])."' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

if($item_row[5] == "Pound") { $p_curreny = "&pound;"; } else { $p_curreny = "&euro;"; }


$result = $sql_command->select($database_packages,"id,package_name","WHERE id='".addslashes($item_row[2])."'");
$record = $sql_command->result($result);

$island_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_POST["island_id"])."'");
$island_record = $sql_command->result($island_result);



$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE island_id='".addslashes($_POST["island_id"])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE menu_id='".addslashes($menu_record[0])."' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }



$html_menu .= "<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"0\"></div>
<div style=\"float:left; width:400px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$menu_option_record[0]\" value=\"0\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$menu_option_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";
}

}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($_POST["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 island_id='".addslashes($_POST["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"0\"></div>
<div style=\"float:left; width:400px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$extra_record[0]\" value=\"0\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}


$total_rows2 = $sql_command->count_rows($database_package_extras,"id","category_id='".addslashes($extra_cat_record[0])."' and type='Service'");

if($total_rows2 > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$service_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 category_id=".addslashes($extra_cat_record[0])." and 
									 type='Service'
									 ORDER BY product_name");
$service_row = $sql_command->results($service_result);

foreach($service_row as $service_record) {
if($service_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"0\"></div>
<div style=\"float:left; width:400px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($service_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($service_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$service_record[0]\" value=\"0\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$service_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}

}





$menu_result = $sql_command->select("$database_package_includes,$database_menus,$database_menu_options","$database_menu_options.id,
									$database_package_includes.qty,
									$database_menus.menu_name_iw,
									$database_menus.local_tax_percent,
									$database_menus.discount_at,
									$database_menus.discount_percent,
									$database_menu_options.menu_name,
									$database_menu_options.cost,
									$database_menu_options.cost_iw,
									$database_menu_options.currency
									","WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' and
									$database_package_includes.type_id=$database_menu_options.id and
									$database_menu_options.menu_id=$database_menus.id and
									$database_package_includes.type='Menu'");
$menu_row = $sql_command->results($menu_result);

$added = "No";

foreach($menu_row as $menu_record) {
if($menu_record[9] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_menu_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[2])."</h3>";
$added = "Yes";
}

$html_menu_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:30px;\" value=\"".stripslashes($menu_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($menu_record[6])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}



$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_extra_record[6])."</h3>";
$added = "Yes";
}

$html_extra_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($package_extra_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$package_service_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Service Fee'");
$package_service_row = $sql_command->results($package_service_result);

$added = "No";

foreach($package_service_row as $package_service_record) {
if($package_service_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_service_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_service_record[6])."</h3>";
$added = "Yes";
}

$html_service_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($package_service_record[1])."\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny -</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Add Order > Select Package > Add to Package</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[3]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Iw Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[4]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
<div style="clear:left;"></div>

<?php if($html_menu_included or $html_extra_included or $html_service_included) { ?><h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1><?php } ?>


<?php if($html_menu_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu_included; ?>

<?php if($html_extra_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra_included; ?>

<?php if($html_service_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service_included; ?>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Add Additional Options to Package</h1>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<p><input type="submit" name="action" value="Create Order"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Create Order") {


$package_info_result = $sql_command->select("$database_package_info,$database_packages","$database_package_info.cost,
											$database_package_info.iw_cost,
											$database_package_info.currency,
											$database_package_info.iw_name,
											$database_packages.package_name
											","WHERE $database_package_info.id='".addslashes($_POST["package_id"])."' and $database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);

$cols = "client_id,package_id,package_cost,package_iw_cost,package_currency,total_paid,total_refunded,total_due,timestamp";
$sql_command->insert($database_order_details,$cols,"'".addslashes($_POST["client_id"])."','".addslashes($_POST["package_id"])."','".addslashes($package_info_record[0])."','".addslashes($package_info_record[1])."','".addslashes($package_info_record[2])."','0','0','0','$time'");		
$maxid = $sql_command->maxid($database_order_details,"id","");	


$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp";
$cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp";

$values = "'$maxid',
'".addslashes($_POST["package_id"])."',
'".addslashes($package_info_record[4])." > ".addslashes($package_info_record[3])."',
'1',
'".addslashes($package_info_record[0])."',
'".addslashes($package_info_record[1])."',
'0',
'0',
'0',
'".addslashes($package_info_record[2])."',
'Package',
'Extra',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);



$menu_option_result = $sql_command->select("$database_menu_options,$database_menus","$database_menu_options.id,
										   $database_menu_options.menu_name,
										   $database_menu_options.cost,
										   $database_menu_options.cost_iw,
										   $database_menu_options.currency,
										   $database_menus.supplier_id,
										   $database_menus.venue_id,
										   $database_menus.island_id,
										   $database_menus.menu_name_iw,
										   $database_menus.local_tax_percent,
										   $database_menus.discount_at,
										   $database_menus.discount_percent","WHERE $database_menu_options.menu_id=$database_menus.id");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$add_supplier = "No";

$qty = "menu_included_qty_".$menu_option_record[0];	
		 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty])."',
'".addslashes($menu_option_record[2])."',
'0',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Included',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);	
}


$qty_extra = "menu_qty_".$menu_option_record[0];

if($_POST[$qty_extra] > 0) {
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Extra',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);	
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'$maxid',
'".addslashes($menu_option_record[5])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($total_qty)."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[4])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	

	
	
	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "extra_included_qty_".$package_extra_record[0];	
			 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);	
}

$qty_extra = "extra_qty_".$package_extra_record[0];

if($_POST[$qty_extra] > 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Extra',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);		
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'$maxid',
'".addslashes($package_extra_record[1])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($total_qty)."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'".addslashes($package_extra_record[5])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	


	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Service'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "service_included_qty_".$package_extra_record[0];	
			 
if($_POST[$qty] > 0) { 
$add_supplier = "Yes";

$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);	
}


$qty_extra = "service_qty_".$package_extra_record[0];

if($_POST[$qty_extra] > 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Service Fee',
'Extra',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_order_history,$cols,$values);		
}

if($add_supplier == "Yes") {
$total_qty = $_POST[$qty_extra] + $_POST[$qty];
$invoice_values = "'$maxid',
'".addslashes($package_extra_record[1])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($total_qty)."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'".addslashes($package_extra_record[5])."',
'Outstanding',
'0',
'$time'";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}
}	
	
	
	
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();


} elseif($_GET["a"] == "view") {
	
$result = $sql_command->select($database_clients,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

$dob = date("d-m-Y",$record[15]);

$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($record[9] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Update Client</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;"><select name="title">
<option value="Mr" <?php if($record[1] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($record[1] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($record[1] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($record[1] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="firstname" style="width:200px;" value="<?php echo stripslashes($record[2]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="lastname" style="width:200px;" value="<?php echo stripslashes($record[3]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="dob" style="width:100px;" value="<?php echo $dob; ?>"/> (Format DD-MM-YYYY)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Mailing List</b></div>
<div style="float:left; margin:5px;"><select name="mailinglist">
<option value="Yes" <?php if($record[16] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
<option value="No" <?php if($record[16] == "No") { echo "selected=\"selected\""; } ?>>No</option>
</select></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="email" style="width:200px;" value="<?php echo stripslashes($record[11]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tel" style="width:200px;" value="<?php echo stripslashes($record[12]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Mob</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="mob" style="width:200px;" value="<?php echo stripslashes($record[13]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Fax</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="fax" style="width:200px;" value="<?php echo stripslashes($record[14]); ?>"/></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address" style="width:200px;" value="<?php echo stripslashes($record[4]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address2" style="width:200px;" value="<?php echo stripslashes($record[5]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address3" style="width:200px;" value="<?php echo stripslashes($record[6]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="town" style="width:200px;" value="<?php echo stripslashes($record[7]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="county" style="width:200px;" value="<?php echo stripslashes($record[8]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Country</b></div>
<div style="float:left; margin:5px;">	<select id="country" name="country">
				<?php echo $country_list; ?>
			</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Postcode</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="postcode" style="width:100px;" value="<?php echo stripslashes($record[10]); ?>"/> *</div>
<div style="clear:left;"></div>

<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Update Client"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update Client") {
	

if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Lastname<br>"; }
if(!$_POST["email"]) { $error .= "Missing Email<br>"; }
if(!$_POST["tel"]) { $error .= "Missing Tel<br>"; }
if(!$_POST["address"]) { $error .= "Missing Address<br>"; }
if(!$_POST["town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["county"]) { $error .= "Missing County<br>"; }
if(!$_POST["country"]) { $error .= "Missing Country<br>"; }
if(!$_POST["postcode"]) { $error .= "Missing Postcode<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=view&id=".$_POST["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["dob"]);

$savedate = mktime(0, 0, 0, $month, $day, $year);

$sql_command->update($database_clients,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"firstname='".addslashes($_POST["firstname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"lastname='".addslashes($_POST["lastname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_1='".addslashes($_POST["address"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_2='".addslashes($_POST["address2"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_3='".addslashes($_POST["address3"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"town='".addslashes($_POST["town"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"county='".addslashes($_POST["county"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"country='".addslashes($_POST["country"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"postcode='".addslashes($_POST["postcode"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"email='".addslashes($_POST["email"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"tel='".addslashes($_POST["tel"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"mob='".addslashes($_POST["mob"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"fax='".addslashes($_POST["fax"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"dob='".addslashes(savedate)."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"mailing_list='".addslashes($_POST["mailinglist"])."'","id='".addslashes($_POST["client_id"])."'");


$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<p>The client has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

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