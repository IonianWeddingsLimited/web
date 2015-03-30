<?


	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id,cost,iw_cost,currency","WHERE id='".addslashes($_POST["package_id"])."' and  deleted='No' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

if($item_row[5] == "Pound") { $p_curreny = "&pound;"; } else { $p_curreny = "&euro;"; }


$result = $sql_command->select($database_packages,"id,package_name","WHERE id='".addslashes($item_row[2])."'");
$record = $sql_command->result($result);

$island_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_POST["island_id"])."'");
$island_record = $sql_command->result($island_result);


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

<h2>Change Package > Select Package > Included in Package</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_POST["invoice_id"]; ?>" />

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


<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Change Package"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=edit-order&id=<?php echo $_POST["client_id"]; ?>&invoice_id=<?php echo $_POST["invoice_id"]; ?>'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();








?>