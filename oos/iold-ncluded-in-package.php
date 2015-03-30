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

if($_POST["action"] == "Continue" and $_POST["island_id"]) {
header("Location: $site_url/oos/included-in-package.php?a=island&island_id=".$_POST["island_id"]);
exit();
}

if($_POST["action"] == "Update") {
	

$cols = "package_id,type_id,qty,type,included,default_qty";

$sql_command->delete($database_package_includes,"package_id='".addslashes($_POST["package_id"])."'");



$menu_option_result = $sql_command->select($database_menu_options,"id","WHERE deleted='No'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$qty = "menu_qty_".$menu_option_record[0];	
$cost = "menu_cost_".$menu_option_record[0];	
$iw = "menu_iw_".$menu_option_record[0];	
$default_qty = "menu_default_qty_".$menu_option_record[0];	
$include_ai = "menu_ip_".$menu_option_record[0];	

$_POST[$cost] = ereg_replace("[^0-9\.]", "", $_POST[$cost]);
$_POST[$iw] = ereg_replace("[^0-9\.]", "", $_POST[$iw]);
$_POST[$qty] = ereg_replace("[^0-9]", "", $_POST[$qty]);
$_POST[$default_qty] = ereg_replace("[^0-9]", "", $_POST[$default_qty]);
if($_POST[$include_ai] != "Yes") { $_POST[$include_ai] = "No"; }

if($_POST[$iw] or $_POST[$cost]) {
$sql_command->update($database_menu_options,"cost='".addslashes($_POST[$cost])."'","id='".$menu_option_record[0]."'");
$sql_command->update($database_menu_options,"cost_iw='".addslashes($_POST[$iw])."'","id='".$menu_option_record[0]."'");
}

if($_POST[$qty] > 0 or $_POST[$default_qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($menu_option_record[0])."',
'".addslashes($_POST[$qty])."',
'Menu',
'".addslashes($_POST[$include_ai])."',
'".addslashes($_POST[$default_qty])."'";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}


$package_extra_result = $sql_command->select($database_package_extras,"id","WHERE deleted='No' and  type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$qty = "extra_qty_".$package_extra_record[0];	
$cost = "extra_cost_".$package_extra_record[0];	
$iw = "extra_iw_".$package_extra_record[0];	
$default_qty = "extra_default_qty_".$package_extra_record[0];	
$include_ai = "extra_ip_".$package_extra_record[0];	

$_POST[$cost] = ereg_replace("[^0-9\.]", "", $_POST[$cost]);
$_POST[$iw] = ereg_replace("[^0-9\.]", "", $_POST[$iw]);
$_POST[$qty] = ereg_replace("[^0-9]", "", $_POST[$qty]);
$_POST[$default_qty] = ereg_replace("[^0-9]", "", $_POST[$default_qty]);
if($_POST[$include_ai] != "Yes") { $_POST[$include_ai] = "No"; }

if($_POST[$iw] or $_POST[$cost]) {
$sql_command->update($database_package_extras,"cost='".addslashes($_POST[$cost])."'","id='".$package_extra_record[0]."'");
$sql_command->update($database_package_extras,"iw_cost='".addslashes($_POST[$iw])."'","id='".$package_extra_record[0]."'");
}

if($_POST[$qty] > 0 or $_POST[$default_qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($_POST[$qty])."',
'Extra',
'".addslashes($_POST[$include_ai])."',
'".addslashes($_POST[$default_qty])."'";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}


$package_service_result = $sql_command->select($database_package_extras,"id","WHERE deleted='No' and type='Service'");
$package_service_row = $sql_command->results($package_service_result);

foreach($package_service_row as $package_service_record) {
$qty = "service_qty_".$package_service_record[0];	
$cost = "service_cost_".$package_service_record[0];	
$iw = "service_iw_".$package_service_record[0];	
$default_qty = "service_default_qty_".$package_service_record[0];	
$include_ai = "service_ip_".$package_service_record[0];	

$_POST[$cost] = ereg_replace("[^0-9\.]", "", $_POST[$cost]);
$_POST[$iw] = ereg_replace("[^0-9\.]", "", $_POST[$iw]);
$_POST[$qty] = ereg_replace("[^0-9]", "", $_POST[$qty]);
$_POST[$default_qty] = ereg_replace("[^0-9]", "", $_POST[$default_qty]);
if($_POST[$include_ai] != "Yes") { $_POST[$include_ai] = "No"; }

if($_POST[$iw] or $_POST[$cost]) {
$sql_command->update($database_package_extras,"cost='".addslashes($_POST[$cost])."'","id='".$package_service_record[0]."'");
$sql_command->update($database_package_extras,"iw_cost='".addslashes($_POST[$iw])."'","id='".$package_service_record[0]."'");
}

if($_POST[$qty] > 0 or $_POST[$default_qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($package_service_record[0])."',
'".addslashes($_POST[$qty])."',
'Service Fee'',
'".addslashes($_POST[$include_ai])."',
'".addslashes($_POST[$default_qty])."";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}


$sql_command->update($database_packages,"ceremony_id='".addslashes($_POST["ceremony_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_main_id"])."'");
$sql_command->update($database_packages,"reception_id='".addslashes($_POST["venue_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_main_id"])."'");



$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Included in Package Changed','".$time."','".addslashes($_POST["package_id"])."','".addslashes($_POST["island_id"])."'");


$get_template->topHTML();
?>
<h1>Included in Package Updated</h1>

<p>The include in package details have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {

if(!$_POST["package_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Select Package","Oops!","Please select a package","Link","oos/included-in-package.php?a=island&island_id=".addslashes($_POST["island_id"])."");
$get_template->bottomHTML();
$sql_command->close();
}


$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id=59 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","deleted='No' and island_id='".addslashes($_POST["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	

$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,supplier_id","WHERE 
									 deleted='No' and 
									 island_id='".addslashes($_POST["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$start = strpos($extra_record[1], '<p>');
$end = strpos($extra_record[1], '</p>', $start);
$paragraph = substr($extra_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = str_replace("<strong>", "", $paragraph);
$paragraph = str_replace("</strong>", "", $paragraph);

$get_checked_result = $sql_command->select($database_package_includes,"qty,included,default_qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($extra_record[0])."' and type='Extra'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) { $qty = stripslashes($get_checked_record[0]); } else { $qty = "0"; }
if($get_checked_record[2]) { $default_qty = stripslashes($get_checked_record[2]); } else { $default_qty = "0"; }
if($get_checked_record[1] == "Yes") { $included = "checked=\"checked\""; $include_html .= "<strong>".stripslashes($extra_cat_record[1])."</strong> > ".stripslashes($paragraph)." (".$default_qty.")<br>"; } else { $included = ""; }


$supplier_result = $sql_command->select($database_supplier_details,"company_name","WHERE id='".addslashes($extra_record[7])."'");
$supplier_record = $sql_command->result($supplier_result);

$html_extra2 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:420px; margin:5px;\">".stripslashes($paragraph)."  (".stripslashes($supplier_record[0]).")</div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_cost_$extra_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($extra_record[3])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_iw_$extra_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($extra_record[4])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_default_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$default_qty\"></div>
<div style=\"float:left; margin:5px;\"><input type=\"checkbox\" name=\"extra_ip_$extra_record[0]\" value=\"Yes\" $included></div>
<div style=\"clear:left;\"></div>";

}	
}
}

$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE id='".addslashes($_POST["package_id"])."' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

$result = $sql_command->select($database_packages,"id,package_name,reception_id,ceremony_id","WHERE id='".addslashes($item_row[2])."'");
$record = $sql_command->result($result);

$island_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_POST["island_id"])."'");
$island_record = $sql_command->result($island_result);



$menu_result = $sql_command->select($database_menus,"id,menu_name_iw,supplier_id","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE deleted='No' and menu_id='".addslashes($menu_record[0])."' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_package_includes,"qty,included,default_qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($menu_option_record[0])."' and  type='Menu'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) { $qty = stripslashes($get_checked_record[0]); } else { $qty = "0"; }
if($get_checked_record[2]) { $default_qty = stripslashes($get_checked_record[2]); } else { $default_qty = "0"; }
if($get_checked_record[1] == "Yes") { $included = "checked=\"checked\""; $include_html .= "<strong>".stripslashes($menu_record[1])."</strong> > ".stripslashes($menu_option_record[2])." (".$default_qty.")<br>"; } else { $included = ""; }

$supplier_result = $sql_command->select($database_supplier_details,"company_name","WHERE id='".addslashes($menu_record[2])."'");
$supplier_record = $sql_command->result($supplier_result);

$html_menu .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:420px; margin:5px;\">".stripslashes($menu_option_record[2])." (".stripslashes($supplier_record[0]).")</div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"menu_cost_$menu_option_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($menu_option_record[3])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"menu_iw_$menu_option_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($menu_option_record[4])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"menu_default_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$default_qty\"></div>
<div style=\"float:left; margin:5px;\"><input type=\"checkbox\" name=\"menu_ip_$menu_option_record[0]\" value=\"Yes\" $included></div>
<div style=\"clear:left;\"></div>";
}

}







$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id!=59 and id!=58 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","deleted='No' and island_id='".addslashes($_POST["island_id"])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,supplier_id","WHERE 
									 deleted='No' and 
									 island_id='".addslashes($_POST["island_id"])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$start = strpos($extra_record[1], '<p>');
$end = strpos($extra_record[1], '</p>', $start);
$paragraph = substr($extra_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = str_replace("<strong>", "", $paragraph);
$paragraph = str_replace("</strong>", "", $paragraph);

$get_checked_result = $sql_command->select($database_package_includes,"qty,included,default_qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($extra_record[0])."' and type='Extra'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) { $qty = stripslashes($get_checked_record[0]); } else { $qty = "0"; }
if($get_checked_record[2]) { $default_qty = stripslashes($get_checked_record[2]); } else { $default_qty = "0"; }
if($get_checked_record[1] == "Yes") { $included = "checked=\"checked\""; $include_html .= "<strong>".stripslashes($extra_cat_record[1])."</strong> > ".stripslashes($paragraph)." (".$default_qty.")<br>"; } else { $included = ""; }


$supplier_result = $sql_command->select($database_supplier_details,"company_name","WHERE id='".addslashes($extra_record[7])."'");
$supplier_record = $sql_command->result($supplier_result);

$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:420px; margin:5px;\">".stripslashes($paragraph)."  (".stripslashes($supplier_record[0]).")</div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_cost_$extra_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($extra_record[3])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_iw_$extra_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($extra_record[4])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"extra_default_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$default_qty\"></div>
<div style=\"float:left; margin:5px;\"><input type=\"checkbox\" name=\"extra_ip_$extra_record[0]\" value=\"Yes\" $included></div>
<div style=\"clear:left;\"></div>";

}	
}







$total_rows2 = $sql_command->count_rows($database_package_extras,"id","deleted='No' and category_id='".addslashes($extra_cat_record[0])."' and type='Service'");

if($total_rows2 > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";
	
$service_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,supplier_id","WHERE 
									 category_id=".addslashes($extra_cat_record[0])." and 
									 deleted='No' and 
									 type='Service' 
									 ORDER BY product_name");
$service_row = $sql_command->results($service_result);

foreach($service_row as $service_record) {
if($service_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$start = strpos($extra_record[1], '<p>');
$end = strpos($service_record[1], '</p>', $start);
$paragraph = substr($service_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = str_replace("<strong>", "", $paragraph);
$paragraph = str_replace("</strong>", "", $paragraph);

$get_checked_result = $sql_command->select($database_package_includes,"qty,included,default_qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($service_record[0])."' and  type='Service Fee'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) { $qty = stripslashes($get_checked_record[0]); } else { $qty = "0"; }
if($get_checked_record[2]) { $default_qty = stripslashes($get_checked_record[2]); } else { $default_qty = "0"; }
if($get_checked_record[1] == "Yes") { $included = "checked=\"checked\""; $include_html .= "<strong>".stripslashes($extra_cat_record[1])."</strong> > ".stripslashes($paragraph)." (".$default_qty.")<br>"; } else { $included = ""; }


$supplier_result = $sql_command->select($database_supplier_details,"company_name","WHERE id='".addslashes($service_record[7])."'");
$supplier_record = $sql_command->result($supplier_result);


$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:420px; margin:5px;\">".stripslashes($paragraph)."  (".stripslashes($supplier_record[0]).")</div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"service_cost_$service_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($service_record[3])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"service_iw_$service_record[0]\" style=\"width:50px;\" value=\"$curreny ".stripslashes($service_record[4])."\"></div>
<div style=\"float:left; width:50px; margin:5px;\"><input type=\"text\" name=\"service_default_qty_$service_record[0]\" style=\"width:30px;\" value=\"$default_qty\"></div>
<div style=\"float:left; margin:5px;\"><input type=\"checkbox\" name=\"service_ip_$service_record[0]\" value=\"Yes\" $included></div>
<div style=\"clear:left;\"></div>";

}	
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
<h1>Included in Package</h1>

<form action="<?php echo $site_url; ?>/oos/included-in-package.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />
<input type="hidden" name="package_main_id" value="<?php echo $record[0]; ?>" />
<input type="hidden" name="package_name" value="<?php echo stripslashes($island_record[1]); ?> > <?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?>" />

<div style="float:left; width:160px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Included in AI</strong></div>
<div style="float:left; margin:5px;"><?php echo $include_html; ?></div>
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

<?php if($html_extra2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2>
<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:420px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Default<br />QTY</strong></div>
<div style="float:left; margin:5px;"><strong>Include<br />In AI</strong></div>
<div style="clear:left;"></div>
<?php } ?>
<?php echo $html_extra2; ?>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2>
<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:420px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Default<br />QTY</strong></div>
<div style="float:left; margin:5px;"><strong>Include<br />In AI</strong></div>
<div style="clear:left;"></div>
<?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2>
<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:420px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Default<br />QTY</strong></div>
<div style="float:left; margin:5px;"><strong>Include<br />In AI</strong></div>
<div style="clear:left;"></div>
<?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2>
<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:420px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:50px; margin:5px;"><strong>Default<br />QTY</strong></div>
<div style="float:left; margin:5px;"><strong>Include<br />In AI</strong></div>
<div style="clear:left;"></div><?php } ?>

<?php echo $html_service; ?>
<p><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_GET["a"] == "island") {
	
$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE deleted='No' and island_id='".addslashes($_GET["island_id"])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);


$found = "No";	
foreach($menu_row as $menu_record) {	
if($menu_record[1] == "Ceremony Only Packages") {
$found = "Yes";	
}
}

if($found == "No") {
$sql_command->insert($database_packages,"island_id,supplier_id,ceremony_id,reception_id,package_name,deleted","'".addslashes($_GET["island_id"])."','','','','Ceremony Only Packages','No'");
$maxid = $sql_command->maxid($database_packages,"id");

$sql_command->insert($database_package_info,"package_id,iw_name,cost,iw_cost,currency,type,notes,timestamp,code,deleted","'".$maxid."','Ceremony Package','0','0','Euro','','','$time','','No'");	
$maxid2 = $sql_command->maxid($database_package_info,"id");

$name = "Ceremony Package";
$totalcharacters = strlen($name);
$middleend = $totalcharacters / 2;
$first = $name[0];
$middle = $name[$middleend];
$last = $name[$totalcharacters-1];
$code =  $maxid2 . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "P";
$sql_command->update($database_package_info,"code='".addslashes($code)."'","id='".$maxid2."'") . "P";	
}



$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE deleted='No' and package_name='Ceremony Only Packages' and island_id='".addslashes($_GET["island_id"])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);


foreach($menu_row as $menu_record) {	
$list .= "<option value=\"\" style=\"font-size:11px; font-weight:bold; color:#F00;\" disabled=\"disabled\">".stripslashes($menu_record[1])."</option>\n";


$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE deleted='No' and package_id='".addslashes($menu_record[0])."' ORDER BY id");
$item_row = $sql_command->results($item_result);

foreach($item_row as $item_record) {	
$list .= "<option value=\"".stripslashes($item_record[0])."\" style=\"font-size:11px;\">".stripslashes($item_record[1])."</option>\n";
}

}


$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE deleted='No' and package_name!='Ceremony Only Packages' and island_id='".addslashes($_GET["island_id"])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);


foreach($menu_row as $menu_record) {	
$list .= "<option value=\"\" style=\"font-size:11px; font-weight:bold; color:#F00;\" disabled=\"disabled\">".stripslashes($menu_record[1])."</option>\n";


$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE deleted='No' and package_id='".addslashes($menu_record[0])."' ORDER BY id");
$item_row = $sql_command->results($item_result);

foreach($item_row as $item_record) {	
$list .= "<option value=\"".stripslashes($item_record[0])."\" style=\"font-size:11px;\">".stripslashes($item_record[1])."</option>\n";
}

}

$get_template->topHTML();
?>
<h1>Included in Package</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/included-in-package.php" method="POST">
<input type="hidden" name="action" value="View" />
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />

<select name="package_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?php } else { ?>
<p>Please make sure you select an option</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}


$get_template->topHTML();
?>
<h1>Included in Package</h1>

<?php if($nav_list) { ?>
<form action="<?php echo $site_url; ?>/oos/included-in-package.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?php } else { ?>
<p>Please make sure you select an option</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} 


?>