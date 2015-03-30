<?

if(!$_GET["package_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Select Package","Oops!","Please select a package","Link","oos/manage-prospect.php?a=add-order&id=".$_GET["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id,cost,iw_cost,currency","WHERE id='".addslashes($_GET["package_id"])."' and  deleted='No' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

if($item_row[5] == "Pound") { $p_curreny = "&pound;"; } else { $p_curreny = "&euro;"; }


$result = $sql_command->select($database_packages,"id,package_name,reception_id,ceremony_id","WHERE id='".addslashes($item_row[2])."'");
$record = $sql_command->result($result);

$island_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_GET["island_id"])."'");
$island_record = $sql_command->result($island_result);







$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","WHERE id=59 and deleted='No' ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","deleted='No' and island_id='".addslashes($_GET["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra2 .= "
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									   deleted='No' and 
									 island_id='".addslashes($_GET["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$include_result = $sql_command->select($database_package_includes,"qty,default_qty,included","WHERE type_id='".$extra_record[0]."'");
$include_record = $sql_command->result($include_result);

$qty = 0;
if($include_record[2] == "No") { $qty += $include_record[1]; }
if($include_record[0] > 0 ) { $qty += $include_record[0]; }



$html_extra2 .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:20px;\" value=\"".$qty."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($extra_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"0\" style=\"width:30px;\"></div>

<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select><select name=\"extra_d_$extra_record[0]\">
<option value=\"Net\" selected=\"selected\">Net</option>
<option value=\"Gross\">Gross</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}
}

$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE deleted='No' and island_id='".addslashes($_GET["island_id"])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE deleted='No' and menu_id='".addslashes($menu_record[0])."' and deleted='No' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$include_result = $sql_command->select($database_package_includes,"qty,default_qty,included","WHERE type_id='".$menu_option_record[0]."'");
$include_record = $sql_command->result($include_result);

$qty = 0;
if($include_record[2] == "No") { $qty += $include_record[1]; }
if($include_record[0] > 0 ) { $qty += $include_record[0]; }

$html_menu .= "<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:20px;\" value=\"".$qty."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($menu_option_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($menu_option_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$menu_option_record[0]\" value=\"0\" style=\"width:30px;\"></div>

<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$menu_option_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select><select name=\"menu_d_$menu_option_record[0]\">
<option value=\"Net\" selected=\"selected\">Net</option>
<option value=\"Gross\">Gross</option>
</select></div>
<div style=\"clear:left;\"></div>";
}

}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","WHERE id!=59 and id!=58 and deleted='No' ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","deleted='No' and island_id='".addslashes($_GET["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									   deleted='No' and 
									 island_id='".addslashes($_GET["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$include_result = $sql_command->select($database_package_includes,"qty,default_qty,included","WHERE type_id='".$extra_record[0]."'");
$include_record = $sql_command->result($include_result);

$qty = 0;
if($include_record[2] == "No") { $qty += $include_record[1]; }
if($include_record[0] > 0 ) { $qty += $include_record[0]; }



$html_extra .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:20px;\" value=\"".$qty."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($extra_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"0\" style=\"width:30px;\"></div>

<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select><select name=\"extra_d_$extra_record[0]\">
<option value=\"Net\" selected=\"selected\">Net</option>
<option value=\"Gross\">Gross</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}


$total_rows2 = $sql_command->count_rows($database_package_extras,"id","deleted='No' and category_id='".addslashes($extra_cat_record[0])."' and type='Service'");

if($total_rows2 > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
	
$service_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									   deleted='No' and 
									 category_id=".addslashes($extra_cat_record[0])." and 
									 type='Service'
									 ORDER BY product_name");
$service_row = $sql_command->results($service_result);

foreach($service_row as $service_record) {
if($service_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$include_result = $sql_command->select($database_package_includes,"qty,default_qty,included","WHERE type_id='".$service_record[0]."'");
$include_record = $sql_command->result($include_result);

$qty = 0;
if($include_record[2] == "No") { $qty += $include_record[1]; }
if($include_record[0] > 0 ) { $qty += $include_record[0]; }


$html_service .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:20px;\" value=\"".$qty."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($service_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">".$curreny."&nbsp;".stripslashes($service_record[4])."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_d_value_$service_record[0]\" value=\"0\" style=\"width:30px;\"></div>

<div style=\"float:left; margin:5px;\"><select name=\"service_d_type_$service_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\">Amount</option>
</select><select name=\"service_d_$service_record[0]\">
<option value=\"Net\" selected=\"selected\">Net</option>
<option value=\"Gross\">Gross</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}

}




$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name,
									$database_package_includes.default_qty,
									$database_package_includes.included
									","WHERE 
									$database_package_extras.deleted='No' and
									$database_category_extras.deleted='No' and 	
									$database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and 
									$database_package_extras.category_id=59 and 
									$database_package_includes.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included2 .= "
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

if($package_extra_record[8] == "Yes") {
$html_extra_included2 .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_extra_record[7])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($package_extra_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
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
									$database_menu_options.currency,
									$database_package_includes.default_qty,
									$database_package_includes.included
									","WHERE 
									$database_menus.deleted='No' and
									$database_menu_options.deleted='No' and 	
									$database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
									$database_package_includes.type_id=$database_menu_options.id and
									$database_menu_options.menu_id=$database_menus.id and
									$database_package_includes.type='Menu' and 
									$database_menu_options.deleted='No'");
$menu_row = $sql_command->results($menu_result);

$added = "No";

foreach($menu_row as $menu_record) {
if($menu_record[9] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_menu_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[2])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

if($menu_record[11] == "Yes") { 
$html_menu_included .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:20px;\" value=\"".stripslashes($menu_record[10])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_record[6])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($menu_record[7])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}
}


$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name,
									$database_package_includes.default_qty,
									$database_package_includes.included
									","WHERE 
									$database_package_extras.deleted='No' and
									$database_category_extras.deleted='No' and 	
									$database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_category_extras.id!=59 and 
									$database_package_includes.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_extra_record[6])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

if($package_extra_record[8] == "Yes") {
$html_extra_included .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_extra_record[7])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($package_extra_record[3])."</div>
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
									","WHERE 
									$database_package_extras.deleted='No' and
									$database_category_extras.deleted='No' and 									
									$database_package_includes.package_id='".addslashes($_GET["package_id"])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Service Fee'");
$package_service_row = $sql_command->results($package_service_result);

$added = "No";

foreach($package_service_row as $package_service_record) {
if($package_service_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_service_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_service_record[6])."</h3>
<div style=\"float:left; width:20px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>Cost</strong></div>
<div style=\"float:left; width:70px; margin:5px;\"><strong>IW Cost</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}
if($package_service_record[8] == "Yes") { 
$html_service_included .= "
<div style=\"float:left; width:20px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:20px;\" value=\"".stripslashes($package_service_record[7])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny ".stripslashes($package_service_record[3])."</div>
<div style=\"float:left; width:70px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}
}




$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}


$ceremony_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE deleted='No' and island_id='".addslashes($_GET["island_id"])."' ORDER BY ceremony_name");
$ceremony_row = $sql_command->results($ceremony_result);

foreach($ceremony_row as $ceremony_record) {
if($record[3] == $ceremony_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$ceremony_list .= "<option value=\"".stripslashes($ceremony_record[0])."\" $selected>".stripslashes($ceremony_record[1])."</option>";
}


$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE deleted='No' and island_id='".addslashes($_GET["island_id"])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

foreach($venue_row as $venue_record) {
if($record[2] == $venue_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\" $selected>".stripslashes($venue_record[1])."</option>";
}




$get_template->topHTML();
$supplier_list = str_replace("'","&#39",$supplier_list); 
?>
<h1>Manage Prospect</h1>
<script language="JavaScript">
function addElement() {
  var ni = document.getElementById('add_item');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_extra_qty_' + num +'" style="width:20px;"/></div>'
+ '<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_extra_name_' + num +'" style="width:220px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;"><select name="bespoke_extra_currency_' + num +'" style="width:70px;">'
+ '<option value="Euro">Euro</option>'
+ '<option value="Pound">Pound</option>'
+ '</select></div>'
+ '<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_cost_' + num +'" style="width:70px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_iw_cost_' + num +'" style="width:70px;"/></div>'
+ '<div style="float:left; width:150px; margin:5px;"><select name="bespoke_supplier_' + num +'" style="width:150px;">'
+ '<?php echo htmlspecialchars($supplier_list); ?>'
+ '</select></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>

<?php echo $menu_line; ?>

<h2>Add Order > Select Package > Add to Package</h2>

<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_GET["package_id"]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["client_id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[3]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Package IW Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($item_row[4]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><b>Ceremony Location</b></div>
<div style="float:left; margin:5px;"><select name="ceremony_id" size="10" style="width:300px;">
<?php echo $ceremony_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Reception Location</b></div>
<div style="float:left; margin:5px;"><select name="venue_id" size="10" style="width:300px;">
<?php echo $venue_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Package Discount</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="package_d_value" value="0" style="width:50px;"></div>
<div style="float:left; margin:5px;"><select name="package_d_type">
<option value="Percent">Percent</option>
<option value="Amount">Amount</option>
</select></div>
<div style="clear:left;"></div>

<?php if($html_menu_included or $html_extra_included or $html_service_included or $html_extra_included2 or $html_extra2) { ?><h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1><?php } ?>

<?php if($html_extra_included2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2><?php } ?>

<?php echo $html_extra_included2; ?>

<?php if($html_menu_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu_included; ?>

<?php if($html_extra_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra_included; ?>

<?php if($html_service_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service_included; ?>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Add Additional Options to Package</h1>

<?php if($html_extra2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2><?php } ?>

<?php echo $html_extra2; ?>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2>
<?php } ?>

<?php echo $html_service; ?>


<h2 style="margin-top:10px; margin-bottom:10px;">Bespoke Extra</h2>



<div style="float:left; width:20px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:220px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Currency</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Cost</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>IW Cost</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Supplier</strong></div>
<div style="clear:left;"></div>

<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_extra_qty_1" style="width:20px;"/></div>
<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_extra_name_1" style="width:220px;"/></div>
<div style="float:left; width:70px; margin:5px;"><select name="bespoke_extra_currency_1" style="width:70px;">
<option value="Euro">Euro</option>
<option value="Pound">Pound</option>
</select></div>
<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_cost_1" style="width:70px;"/></div>
<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_iw_cost_1" style="width:70px;"/></div>
<div style="float:left; width:150px; margin:5px;"><select name="bespoke_supplier_1" style="width:150px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>


<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>
<input type="hidden" value="1" id="theValue" name="theValue">


<div style="float:left; margin-top:20px;"><input type="submit" name="action" value="Create Quote"></div>
<div style="float:left; margin-top:20px; margin-left:220px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=add-order&id=<?php echo $_GET["client_id"]; ?>'"></div>
<div style="float:right; width:180px; margin:5px;margin-top:20px; "><input type="button" value="Add Another Extra" onclick="addElement();"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();


?>