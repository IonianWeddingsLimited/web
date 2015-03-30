<?

	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id,cost,iw_cost,currency","WHERE id='".addslashes($_POST["package_id"])."' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

if($item_row[5] == "Pound") { $p_curreny = "&pound;"; } else { $p_curreny = "&euro;"; }


$result = $sql_command->select($database_packages,"id,package_name,reception_id,ceremony_id","WHERE id='".addslashes($item_row[2])."'");
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
									$database_menu_options.currency,
									$database_package_includes.default_qty,
									$database_package_includes.included
									","WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' and
									$database_package_includes.type_id=$database_menu_options.id and
									$database_menu_options.menu_id=$database_menus.id and
									$database_package_includes.type='Menu' and 
									$database_menu_options.deleted='No' and 
									$database_menus.deleted='No'");
$menu_row = $sql_command->results($menu_result);

$added = "No";

foreach($menu_row as $menu_record) {
	if($menu_record[9] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

	if($added == "No" and $menu_record[11] == "Yes") { 
		$html_menu_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[2])."</h3>";
		$added = "Yes";
	}
	if($menu_record[11] == "Yes") { 
		$html_menu_included .= "
		<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:20px;\" value=\"".stripslashes($menu_record[10])."\"></div>
		<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($menu_record[6])."</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
		<div style=\"clear:left;\"></div>";
	}
}


$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras",
											 "$database_package_extras.id,
											 $database_package_includes.qty,
											 $database_package_extras.product_name,
											 $database_package_extras.cost,
											 $database_package_extras.iw_cost,
											 $database_package_extras.currency,
											 $database_category_extras.category_name,
											 $database_package_includes.default_qty,
											 $database_package_includes.included",
											 "WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' 
											 and $database_package_includes.type_id=$database_package_extras.id 
											 and $database_package_extras.category_id=$database_category_extras.id 
											 and $database_package_extras.type='Extra' 
											 and $database_package_extras.deleted='No' 
											 and $database_category_extras.deleted='No'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
	if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

	if($added == "No" and $package_extra_record[8] == "Yes") {
		$html_extra_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_extra_record[6])."</h3>";
		$added = "Yes";
	}

	if($package_extra_record[8] == "Yes") {
		$html_extra_included .= "
		<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_extra_record[7])."\"></div>
		<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
		<div style=\"clear:left;\"></div>";
	}
}


$package_service_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name,
									$database_package_includes.default_qty,
									$database_package_includes.included
									","WHERE $database_package_includes.package_id='".addslashes($_POST["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Service Fee' and 
									$database_package_extras.deleted='No' and 
									$database_category_extras.deleted='No'");
$package_service_row = $sql_command->results($package_service_result);

$added = "No";

foreach($package_service_row as $package_service_record) {
	if($package_service_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

	if($added == "No" and $package_service_record[8] == "Yes") {
		$html_service_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_service_record[6])."</h3>";
		$added = "Yes";
	}

	if($package_service_record[8] == "Yes") { 
		$html_service_included .= "
		<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_service_record[7])."\"></div>
		<div style=\"float:left; width:380px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny -</div>
		<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
		<div style=\"clear:left;\"></div>";
	}
}


$ceremony_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY ceremony_name");
$ceremony_row = $sql_command->results($ceremony_result);

foreach($ceremony_row as $ceremony_record) {
	if($record[3] == $ceremony_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
	$ceremony_list .= "<option value=\"".stripslashes($ceremony_record[0])."\" $selected>".stripslashes($ceremony_record[1])."</option>";
}


$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

foreach($venue_row as $venue_record) {
	if($record[2] == $venue_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
	$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\" $selected>".stripslashes($venue_record[1])."</option>";
}

$get_template->topHTML();
?>

<h1>Manage Prospect</h1>
<?php echo $menu_line; ?>
<h2>Change Package > Select Package > Included in Package</h2>
<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="POST">
  <input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
  <input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />
  <input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
  <input type="hidden" name="invoice_id" value="<?php echo $_POST["invoice_id"]; ?>" />
  <div style="float:left; width:160px; margin:5px;"><strong>Package</strong></div>
  <div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo "<input name=\"gross_package_cost\" value=\"".stripslashes($item_row[4])."\">"; ?></div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><strong>Package Net</strong></div>
  <div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[3]); ?></div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><strong>Package Gross</strong></div>
  <div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[4]); ?></div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><strong>Island</strong></div>
  <div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><b>Ceremony Location</b></div>
  <div style="float:left; margin:5px;">
    <select name="ceremony_id" size="10" style="width:300px;">
      <?php echo $ceremony_list; ?>
    </select>
  </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><b>Reception Location</b></div>
  <div style="float:left; margin:5px;">
    <select name="venue_id" size="10" style="width:300px;">
      <?php echo $venue_list; ?>
    </select>
  </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:160px; margin:5px;"><strong>Package Discount</strong></div>
  <div style="float:left;  margin:5px;">
    <input type="text" name="package_d_value" value="0" style="width:50px;">
  </div>
  <div style="float:left; margin:5px;">
    <select name="package_d_type">
      <option value="Percent">Percent</option>
      <option value="Amount">Amount</option>
    </select>
  </div>
  <div style="clear:left;"></div>
  <?php if($html_menu_included or $html_extra_included or $html_service_included) { ?>
  <h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1>
  <?php } ?>
  <?php if($html_menu_included) { ?>
  <h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2>
  <?php } ?>
  <?php echo $html_menu_included; ?>
  <?php if($html_extra_included) { ?>
  <h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2>
  <?php } ?>
  <?php echo $html_extra_included; ?>
  <?php if($html_service_included) { ?>
  <h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2>
  <?php } ?>
  <?php echo $html_service_included; ?>
  <div style="float:left; margin-top:10px;">
    <input type="submit" name="action" value="Change Package">
  </div>
  <div style="float:right; margin-top:10px; margin-right:10px;">
    <input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=edit-order&id=<?php echo $_POST["client_id"]; ?>&invoice_id=<?php echo $_POST["invoice_id"]; ?>'">
  </div>
  <div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>
