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
header("Location: $site_url/oos/update-menu.php?a=island&island_id=".$_POST["island_id"]);
exit();
}



if($_POST["action"] == "Update") {
	
$_POST["local_tax"] = ereg_replace("[^0-9\.]", "", $_POST["local_tax"]);
$_POST["apply_discount"] = ereg_replace("[^0-9\.]", "", $_POST["apply_discount"]);
$_POST["discount"] = ereg_replace("[^0-9\.]", "", $_POST["discount"]);


if(!$_POST["iw_menu_name"]) { $error .= "Missing IW Menu Name<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Menu","Oops!","$error","Link","oos/update-menu.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_menus,"supplier_id='".addslashes($_POST["supplier_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"venue_id='".addslashes($_POST["venue_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"island_id='".addslashes($_POST["island_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"menu_name_iw='".addslashes($_POST["iw_menu_name"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"local_tax_percent='".addslashes($_POST["local_tax"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"discount_at='".addslashes($_POST["apply_discount"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");
$sql_command->update($database_menus,"discount_percent='".addslashes($_POST["discount"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["menu_id"])."'");



$menu_option_result = $sql_command->select($database_menu_options,"*","WHERE menu_id='".addslashes($_POST["menu_id"])."'  and deleted='No' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$menu_name = "menu_name_".$menu_option_record[0];
$cost = "cost_".$menu_option_record[0];
$iw_cost = "iw_cost_".$menu_option_record[0];
$currency = "currency_".$menu_option_record[0];
$note = "note_".$menu_option_record[0];

if($_POST[$menu_name] and $_POST[$cost] and $_POST[$iw_cost] and $_POST[$currency]) { 
$sql_command->update($database_menu_options,"menu_name='".addslashes($_POST[$menu_name])."'","menu_id='".addslashes($_POST["menu_id"])."' and id='".addslashes($menu_option_record[0])."'");
$sql_command->update($database_menu_options,"cost='".addslashes($_POST[$cost])."'","menu_id='".addslashes($_POST["menu_id"])."' and id='".addslashes($menu_option_record[0])."'");
$sql_command->update($database_menu_options,"cost_iw='".addslashes($_POST[$iw_cost])."'","menu_id='".addslashes($_POST["menu_id"])."' and id='".addslashes($menu_option_record[0])."'");
$sql_command->update($database_menu_options,"currency='".addslashes($_POST[$currency])."'","menu_id='".addslashes($_POST["menu_id"])."' and id='".addslashes($menu_option_record[0])."'");
$sql_command->update($database_menu_options,"note='".addslashes($_POST[$note])."'","menu_id='".addslashes($_POST["menu_id"])."' and id='".addslashes($menu_option_record[0])."'");
} else {
$sql_command->update($database_menu_options,"deleted='Yes'","menu_id='".addslashes($_POST["menu_id"])."' and id='".$menu_option_record[0]."'");
}
}




$cols = "menu_id,menu_name,cost,cost_iw,currency,note,timestamp,deleted";

$current_row = 1;
$end_value = $_POST["theValue"] + 1;

while($current_row < $end_value) {
$menu_name = "new_menu_name_".$current_row;
$cost = "new_cost_".$current_row;
$iw_cost = "new_iw_cost_".$current_row;
$currency = "new_currency_".$current_row;
$note = "new_note_".$current_row;

if($_POST[$menu_name] and $_POST[$cost] and $_POST[$iw_cost] and $_POST[$currency]) { 
$values = "'".addslashes($_POST["menu_id"])."',
'".addslashes($_POST[$menu_name])."',
'".addslashes($_POST[$cost])."',
'".addslashes($_POST[$iw_cost])."',
'".addslashes($_POST[$currency])."',
'".addslashes($_POST[$note])."',
'$time',
'No'";
$sql_command->insert($database_menu_options,$cols,$values);														  
}

$current_row++;
}



$get_template->topHTML();
?>
<h1>Menu Updated</h1>

<p>The menu has now been updated</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-menu.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$menu_result = $sql_command->select($database_menus,"*","WHERE id='".addslashes($_POST["menu_id"])."'");
$menu_record = $sql_command->result($menu_result);

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
if($menu_record[1] == $supplier_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}

$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE island_id='".addslashes($_POST["island_id"])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

$venue_list .= "<option value=\"0\">None</option>";


foreach($venue_row as $venue_record) {
if($menu_record[2] == $venue_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\" $selected>".stripslashes($venue_record[1])."</option>";
}

$get_template->topHTML();
?>
<h1>Update Menu</h1>

<script language="JavaScript">
function addElement() {
  var ni = document.getElementById('add_item');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:220px; margin:5px;"><input type="text" name="new_menu_name_' + num +'" style="width:220px;"/></div>'
+ '<div style="float:left; width:60px; margin:5px;"><input type="text" name="new_cost_' + num +'" style="width:60px;"/></div>'
+ '<div style="float:left; width:60px; margin:5px;"><input type="text" name="new_iw_cost_' + num +'" style="width:60px;"/></div>'
+ '<div style="float:left; width:60px; margin:5px;"><select name="new_currency_' + num +'" style="width:60px;">'
+ '<option value="Euro">Euro</option>'
+ '<option value="Pound">Pound</option>'
+ '</select></div>'
+ '<div style="float:left; width:180px; margin:5px;"><input type="text" name="new_note_' + num +'" style="width:180px;"/></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>
    
<form action="<?php echo $site_url; ?>/oos/update-menu.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="menu_id" value="<?php echo $_POST["menu_id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Supplier</b></div>
<div style="float:left; margin:5px;"><select name="supplier_id" style="width:300px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Venue</b></div>
<div style="float:left; margin:5px;"><select name="venue_id" style="width:300px;">
<?php echo $venue_list; ?>
</select></div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><b>IW Menu Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="iw_menu_name" style="width:350px;" value="<?php echo stripslashes($menu_record[4]); ?>"/> *</div>
<div style="clear:left;"></div>

<p><hr /></p>

<p>Please enter the cost Per Person</p>

<div style="float:left; width:220px; margin:5px;"><b>Menu Name</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Cost</b></div>
<div style="float:left; width:60px; margin:5px;"><b>IW Cost</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Currency</b></div>
<div style="float:left; width:180px; margin:5px;"><b>Note</b></div>
<div style="float:left; margin:5px;"><b>Code</b></div>
<div style="clear:left;"></div>

<?
	
$total_menu_items = $sql_command->count_rows($database_menu_options,"id","menu_id='".addslashes($_POST["menu_id"])."' and deleted='No'");
$total_menu_items++;

$menu_option_result = $sql_command->select($database_menu_options,"*","WHERE menu_id='".addslashes($_POST["menu_id"])."'  and deleted='No' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);



foreach($menu_option_row as $menu_option_record) {
?>
<div style="float:left; width:220px; margin:5px;"><input type="text" name="menu_name_<?php echo $menu_option_record[0]; ?>" style="width:220px;" value="<?php echo stripslashes($menu_option_record[2]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="cost_<?php echo $menu_option_record[0]; ?>" style="width:60px;" value="<?php echo stripslashes($menu_option_record[3]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="iw_cost_<?php echo $menu_option_record[0]; ?>" style="width:60px;" value="<?php echo stripslashes($menu_option_record[4]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><select name="currency_<?php echo $menu_option_record[0]; ?>" style="width:60px;" value="<?php echo stripslashes($menu_option_record[0]); ?>">
<option value="Euro" <?php if($menu_option_record[5] == "Euro") { echo "selected=\"selected=\"selected\""; } ?>>Euro</option>
<option value="Pound"  <?php if($menu_option_record[5] == "Pound") { echo "selected=\"selected=\"selected\""; } ?>>Pound</option>
</select></div>
<div style="float:left; width:180px; margin:5px;"><input type="text" name="note_<?php echo $menu_option_record[0]; ?>" style="width:180px;" value="<?php echo stripslashes($menu_option_record[6]); ?>"/></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($menu_option_record[8]); ?></div>
<div style="clear:left;"></div>
<?php 

} ?>
<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>

<p>* - Required Fields</p>


<input type="hidden" value="0" id="theValue" name="theValue">

<div style="float:left; margin:5px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left; margin:5px; margin-left:250px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-menu.php'"></div>
<div style="float:right; margin:5px;"><input type="button" value="Add Another Menu" onclick="addElement();"></div>
<div style="clear:borth;"></div>


</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "island") {
	

$menu_result = $sql_command->select("$database_menus,$database_supplier_details","$database_menus.id,
									$database_menus.menu_name_iw,
									$database_supplier_details.company_name","WHERE 
									$database_menus.supplier_id=$database_supplier_details.id AND 
									$database_menus.island_id='".addslashes($_GET["island_id"])."'
									ORDER BY $database_supplier_details.company_name, $database_menus.menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {	
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px;\">".stripslashes($menu_record[2])." - ".stripslashes($menu_record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Menu</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-menu.php" method="POST">
<input type="hidden" name="action" value="View" />
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />

<select name="menu_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

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
<h1>Update Menu</h1>

<?php if($nav_list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-menu.php" method="POST">
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