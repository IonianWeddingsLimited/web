<?

$package_info_result = $sql_command->select("quotation_history","id,type_id","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);


$island_result = $sql_command->select("$database_clients,$database_navigation","$database_clients.destination,
									  $database_navigation.page_name","WHERE 
									  $database_clients.id='".addslashes($_GET["id"])."' and 
									  $database_clients.destination=$database_navigation.id");
$island_record = $sql_command->result($island_result);

	
$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE deleted='No' and package_name='Ceremony Only Packages'  and island_id='".addslashes($island_record[0])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {	
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px; font-weight:bold; color:#F00;\" disabled=\"disabled\">".stripslashes($menu_record[1])."</option>\n";


$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE deleted='No' and package_id='".addslashes($menu_record[0])."' ORDER BY id");
$item_row = $sql_command->results($item_result);

foreach($item_row as $item_record) {	
if($package_info_record[1] == $item_record[0]) { $selected = "selected=\"selected\""; } else { $selected = ""; }
$list .= "<option value=\"".stripslashes($item_record[0])."\" style=\"font-size:11px;\" $selected>".stripslashes($item_record[1])."</option>\n";
}

}



$menu_result = $sql_command->select($database_packages,"id,package_name","WHERE deleted='No' and package_name!='Ceremony Only Packages'  and island_id='".addslashes($island_record[0])."'	ORDER BY id");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {	
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px; font-weight:bold; color:#F00;\" disabled=\"disabled\">".stripslashes($menu_record[1])."</option>\n";


$item_result = $sql_command->select($database_package_info,"id,iw_name,package_id","WHERE deleted='No' and package_id='".addslashes($menu_record[0])."' ORDER BY id");
$item_row = $sql_command->results($item_result);

foreach($item_row as $item_record) {	
if($package_info_record[1] == $item_record[0]) { $selected = "selected=\"selected\""; } else { $selected = ""; }
$list .= "<option value=\"".stripslashes($item_record[0])."\" style=\"font-size:11px;\" $selected>".stripslashes($item_record[1])."</option>\n";
}

}

$get_template->topHTML();
?>
<h1>Manage Prospect</h1>

<?php echo $menu_line; ?>
<h3><?php echo stripslashes($island_record[1]); ?></h3>

<h2>Change Package > Select Package</h2>


<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="POST">
<input type="hidden" name="action" value="save_change_package" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="island_id" value="<?php echo $island_record[0]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />

<select name="package_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="actn" value="Select Package"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();	

?>