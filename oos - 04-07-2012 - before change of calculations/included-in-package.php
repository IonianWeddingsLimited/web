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
	

$cols = "package_id,type_id,qty,type";

$sql_command->delete($database_package_includes,"package_id='".addslashes($_POST["package_id"])."'");



$menu_option_result = $sql_command->select($database_menu_options,"id","");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$qty = "menu_qty_".$menu_option_record[0];	
			 
if($_POST[$qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($menu_option_record[0])."',
'".addslashes($_POST[$qty])."',
'Menu'";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}


$package_extra_result = $sql_command->select($database_package_extras,"id","WHERE type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$qty = "extra_qty_".$package_extra_record[0];	

if($_POST[$qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($_POST[$qty])."',
'Extra'";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}


$package_service_result = $sql_command->select($database_package_extras,"id","WHERE type='Service'");
$package_service_row = $sql_command->results($package_service_result);

foreach($package_service_row as $package_service_record) {
$qty = "service_qty_".$package_service_record[0];	

if($_POST[$qty] > 0) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($package_service_record[0])."',
'".addslashes($_POST[$qty])."',
'Service Fee'";
$sql_command->insert($database_package_includes,$cols,$values);														  
}
}



$get_template->topHTML();
?>
<h1>Included in Package Updated</h1>

<p>The include in package details have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE id='".addslashes($_POST["package_id"])."' ORDER BY iw_name");
$item_row = $sql_command->result($item_result);

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


$get_checked_result = $sql_command->select($database_package_includes,"qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($menu_option_record[0])."' and  type='Menu'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$selected = "selected=\"selected\"";
$qty = stripslashes($get_checked_record[0]);
} else {
$selected = "";
$qty = "0";
}

$html_menu .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($menu_option_record[4])."</div>
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

$get_checked_result = $sql_command->select($database_package_includes,"qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($extra_record[0])."' and type='Extra'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$selected = "selected=\"selected\"";
$qty = stripslashes($get_checked_record[0]);
} else {
$selected = "";
$qty = "0";
}

$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($extra_record[4])."</div>
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

$get_checked_result = $sql_command->select($database_package_includes,"qty","WHERE package_id='".addslashes($_POST["package_id"])."' and type_id='".addslashes($service_record[0])."' and  type='Service Fee'");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$selected = "selected=\"selected\"";
$qty = stripslashes($get_checked_record[0]);
} else {
$selected = "";
$qty = "0";
}

$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:490px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($service_record[3])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".stripslashes($service_record[4])."</div>
<div style=\"clear:left;\"></div>";

}	
}

}



$get_template->topHTML();
?>
<h1>Included in Package</h1>

<form action="<?php echo $site_url; ?>/oos/included-in-package.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />

<div style="float:left; width:60px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[1]); ?> > <?php echo stripslashes($item_row[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:60px; margin:5px;"><strong>Island</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($island_record[1]); ?></div>
<div style="clear:left;"></div>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>
<p><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_GET["a"] == "island") {
	

$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE island_id='".addslashes($_GET["island_id"])."'	ORDER BY id");
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