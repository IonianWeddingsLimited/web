<?

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

if($package_info_record[6] == "Amount" and $package_info_record[5] >= 1) { 
$new_package_cost = $package_info_record[3] - $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] >= 1) { 
$percent_value = ($package_info_record[3] / 100);
$new_package_cost = $package_info_record[3] - ($percent_value * $package_info_record[5]);
} else { 
$new_package_cost = $package_info_record[3];
}





if($package_info_record[5] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[2];
$total_iw_pound_cost += $new_package_cost;
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[2];
$total_iw_euro_cost += $new_package_cost;
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
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Net") { 
$iw_cost = $menu_option_record[4] - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 1) { 
$percent_value = ($menu_option_record[4] / 100);
$iw_cost = $menu_option_record[4] - ($percent_value * $menu_option_record[11]);
} else {
$iw_cost = $menu_option_record[4];
}


if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Gross") { 
$iw_cost = $iw_cost - abs($menu_option_record[11] / $menu_option_record[2]);
} 

$the_cost = $menu_option_record[2] * $menu_option_record[3];
$total_iw_cost_orig = $menu_option_record[2] * $menu_option_record[4];

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 1) { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $menu_option_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}






if($menu_option_record[8] == "Pound") { 
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}


$html_menu .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($menu_option_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($menu_option_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($menu_option_record[3],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
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
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}



if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$iw_cost = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1) { 
$percent_value = ($extra_record[4] / 100);
$iw_cost = $extra_record[4] - ($percent_value * $extra_record[11]);
} else {
$iw_cost = $extra_record[4];
}

if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$iw_cost = $iw_cost - abs($extra_record[11] / $extra_record[2]);
} 

$the_cost = $extra_record[2] * $extra_record[3];
$total_iw_cost_orig = $extra_record[2] * $extra_record[4];


if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1) { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $extra_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}





if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

$html_extra .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
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
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}



if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$iw_cost = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1) { 
$percent_value = ($extra_record[4] / 100);
$iw_cost = $extra_record[4] - ($percent_value * $extra_record[11]);
} else {
$iw_cost = $extra_record[4];
}

if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$iw_cost = $iw_cost - abs($extra_record[11] / $extra_record[2]);
} 

$the_cost = $extra_record[2] * $extra_record[3];
$total_iw_cost_orig = $extra_record[2] * $extra_record[4];

if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1) { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $extra_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}


if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

$html_service .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
<div style=\"clear:left;\"></div>";

}	
}
}


$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$package_exchange_record = $sql_command->result($package_exchange_result);

if($package_exchange_record[0] < 1) {
$package_exchange_record[0] = 1;
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Order Details</h2>

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($package_info_record[2],2); ?> / &pound; <?php echo number_format($package_info_record[2] / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package IW Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($new_package_cost,2); ?> / &pound; <?php echo number_format($new_package_cost / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>


<h3 style="color:#000; margin-top:10px; margin-bottom:10px;">Package Options</h3>

<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:450px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Cost</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>IW Cost</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>IW Total</strong></div>
<div style="clear:left;"></div>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_euro_cost,2); ?> / &pound; <?php echo number_format($total_euro_cost / $package_exchange_record[0],2); ?> </div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_iw_euro_cost,2); ?> / &pound; <?php echo number_format($total_iw_euro_cost / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Profit</strong></div>
<div style="float:left; margin:5px;">&euro; <?php $total_euro = $total_iw_euro_cost -  $total_euro_cost; echo number_format($total_euro,2); ?> / &pound; <?php echo number_format($total_euro / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_pound_cost,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_iw_pound_cost,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Profile</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $total_pound = $total_iw_pound_cost - $total_pound_cost; echo number_format($total_pound,2); ?></div>
<div style="clear:left;"></div>

<div style="float:left; width:150px; margin:5px;"><strong>Total Overall Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $euro_pound = $total_iw_euro_cost / $package_exchange_record[0]; echo number_format($euro_pound + $total_iw_pound_cost,2); ?></div>
<div style="clear:left;"></div>

<div style="margin-top:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></div>
<?
$get_template->bottomHTML();
$sql_command->close();

?>