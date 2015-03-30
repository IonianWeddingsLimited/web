<?

	
	
	
$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type,status","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

if($package_info_record[6] == "Amount") { 
$new_package_cost = number_format($package_info_record[3] - $package_info_record[5],2);
} else { 
$percent_value = ($package_info_record[3] / 100);
$new_package_cost = number_format($package_info_record[3] - ($percent_value * $package_info_record[5]) ,2);
}

if($package_info_record[4] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}
	
	
$island_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info","$database_order_details.package_id,
									$database_packages.island_id
									","WHERE $database_order_details.id='".addslashes($_GET["invoice_id"])."' and
									$database_order_details.package_id=$database_package_info.id and 
									$database_package_info.package_id=$database_packages.id");
$island_record = $sql_command->result($island_result);


if($package_info_record[4] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[1];
$total_iw_pound_cost += $package_info_record[2];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[1];
$total_iw_euro_cost += $package_info_record[2];
}


$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE island_id='".addslashes($island_record[1])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE menu_id='".addslashes($menu_record[0])."' and deleted='No' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($menu_option_record[0])."' and item_type='Menu' and type='Extra' and status='Outstanding'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);


if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}


if($get_checked_record[3] == "Gross") { 
$selected_d = "selected=\"selected\"";
} else {
$selected_d = "";
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
}

$iw_cost = $menu_option_record[4];

$html_menu .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($menu_option_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$menu_option_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$menu_option_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select><select name=\"menu_d_$menu_option_record[0]\">
<option value=\"Net\">Net</option>
<option value=\"Gross\" $selected_d>Gross</option>
</select></div>
<div style=\"clear:left;\"></div>";
}

}




$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($island_record[1])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 island_id='".addslashes($island_record[1])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Extra'  and status='Outstanding'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

if($get_checked_record[3] == "Gross") { 
$selected_d = "selected=\"selected\"";
} else {
$selected_d = "";
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
}

$iw_cost = $extra_record[4];

$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select><select name=\"extra_d_$extra_record[0]\">
<option value=\"Net\">Net</option>
<option value=\"Gross\" $selected_d>Gross</option>
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

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($service_record[0])."' and item_type='Service Fee'  and status='Outstanding'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);

if($get_checked_record[2] == "Amount") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

if($get_checked_record[3] == "Gross") { 
$selected_d = "selected=\"selected\"";
} else {
$selected_d = "";
}
} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
}

$iw_cost = $service_record[4];

$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($service_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_d_value_$service_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"service_d_type_$service_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select><select name=\"service_d_$service_record[0]\">
<option value=\"Net\">Net</option>
<option value=\"Gross\" $selected_d>Gross</option>
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
									$database_package_includes.type='Menu' and
									$database_menu_options.deleted='No'");
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
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_record[6])."</div>
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
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
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
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}


$orderdetail_info_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Edit Order</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $island_record[1]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $package_info_record[0]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />

<div style="float:left; width:40px; margin:5px;">1 x</div>
<div style="float:left; width:320px; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo str_replace(",","",$new_package_cost); ?></div>

<div style="float:left; width:30px; margin:5px;"><input type="text" name="package_d_value" value="<?php echo stripslashes($package_info_record[5]); ?>" style="width:30px;"></div>
<div style="float:left; margin:5px;"><select name="package_d_type">
<option value="Percent" <?php if($package_info_record[6] == "Percent") { echo "selected=\"selected\""; } ?>>Percent</option>
<option value="Amount" <?php if($package_info_record[6] == "Amount") { echo "selected=\"selected\""; } ?>>Amount</option>
</select></div>
<div style="clear:left;"></div>
<?php if($package_info_record[7] == "Outstanding" or $package_info_record[7] == "Cancelled" or $package_info_record[7] == "Refunded") { ?>
<div style=" margin:5px;">[ <a href="<?php echo $site_url; ?>/oos/manage-client.php?a=change-package&id=<?php echo $_POST["client_id"]; ?>&invoice_id=<?php echo $_GET["invoice_id"]; ?>">Change Package</a> ]</div>
<?php } ?>
<?php if($html_menu_included or $html_extra_included or $html_service_included) { ?><h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1><?php } ?>


<?php if($html_menu_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu_included; ?>

<?php if($html_extra_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra_included; ?>

<?php if($html_service_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service_included; ?>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Add Additional Options to Package</h1>

<div style="float:left; width:40px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:320px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Cost</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>IW Cost</strong></div>
<div style="clear:left;"></div>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>


<div style="float:left;"><p><input type="submit" name="action" value="Update Order"></p></div>
<div style="float:left; margin-left:200px;"><p><input type="button" name="" value="Back"   onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></p></div>
</form>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $island_record[1]; ?>" />
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

?>