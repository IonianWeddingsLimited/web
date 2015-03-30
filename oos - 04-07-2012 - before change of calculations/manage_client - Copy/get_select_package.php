<?


	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id,cost,iw_cost,currency","WHERE id='".addslashes($_GET["package_id"])."' and  deleted='No' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

if($item_row[5] == "Pound") { $p_curreny = "&pound;"; } else { $p_curreny = "&euro;"; }


$result = $sql_command->select($database_packages,"id,package_name","WHERE id='".addslashes($item_row[2])."'");
$record = $sql_command->result($result);

$island_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_GET["island_id"])."'");
$island_record = $sql_command->result($island_result);



$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE island_id='".addslashes($_GET["island_id"])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE menu_id='".addslashes($menu_record[0])."' and deleted='No' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }



$html_menu .= "<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:20px;\" value=\"0\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
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
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($_GET["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 island_id='".addslashes($_GET["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$html_extra .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:20px;\" value=\"0\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"0\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
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
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:20px;\" value=\"0\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($service_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($service_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_d_value_$service_record[0]\" value=\"0\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"service_d_type_$service_record[0]\">
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
									","WHERE $database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
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
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:20px;\" value=\"".stripslashes($menu_record[1])."\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($menu_record[6])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}



$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
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
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_extra_record[1])."\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$package_service_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
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
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_service_record[1])."\"></div>
<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny -</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Add Order > Select Package > Add to Package</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_GET["package_id"]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["client_id"]; ?>" />

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[3]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package IW Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[4]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Discount</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="package_d_value" value="0" style="width:50px;"></div>
<div style="float:left; margin:5px;"><select name="package_d_type">
<option value="Percent">Percent</option>
<option value="Amount">Amount</option>
</select></div>
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


?>