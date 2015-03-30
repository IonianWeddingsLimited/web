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
header("Location: $site_url/oos/update-package.php?a=island&island_id=".$_POST["island_id"]);
exit();
}

if($_POST["action"] == "Delete Package") {
	
if(!$_POST["package_id"]) { $error .= "Missing Package Id<br>"; }
if(!$_POST["island_id"]) { $error .= "Missing Island Id<br>"; }
	
	
	
if($error) {
$get_template->topHTML();
$get_template->errorHTML("Delete Package","Oops!","$error","Link","oos/update-package.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_packages,"deleted='Yes'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_id"])."'");
$sql_command->update($database_package_info,"deleted='Yes'","package_id='".addslashes($_POST["package_id"])."'");
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Package Deleted (".addslashes($_POST["name"]).")','".$time."','".addslashes($_POST["package_id"])."','".addslashes($_POST["island_id"])."'");

$get_template->topHTML();
?>
<h1>Package Deleted</h1>

<p>The package has now been deleted</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-package.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} elseif($_POST["action"] == "Update") {
	

if(!$_POST["name"]) { $error .= "Missing  Name<br>"; }



$sql_command->update($database_packages,"supplier_id='".addslashes($_POST["supplier_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_id"])."'");
$sql_command->update($database_packages,"ceremony_id='".addslashes($_POST["ceremony_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_id"])."'");
$sql_command->update($database_packages,"reception_id='".addslashes($_POST["venue_id"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_id"])."'");
$sql_command->update($database_packages,"package_name='".addslashes($_POST["name"])."'","island_id='".addslashes($_POST["island_id"])."' and id='".addslashes($_POST["package_id"])."'");







$package_option_result = $sql_command->select($database_package_info,"*","WHERE package_id='".addslashes($_POST["package_id"])."' and deleted='No' ORDER BY iw_name");
$package_option_row = $sql_command->results($package_option_result);


foreach($package_option_row as $package_option_record) { 
$menu_name = "menu_name_".$package_option_record[0];
$cost = "cost_".$package_option_record[0];
$iw_cost = "iw_cost_".$package_option_record[0];
$currency = "currency_".$package_option_record[0];
$note = "note_".$package_option_record[0];

if($_POST[$menu_name]) { 
$sql_command->update($database_package_info,"iw_name='".addslashes($_POST[$menu_name])."'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
$sql_command->update($database_package_info,"cost='".addslashes($_POST[$cost])."'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
$sql_command->update($database_package_info,"iw_cost='".addslashes($_POST[$iw_cost])."'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
$sql_command->update($database_package_info,"currency='".addslashes($_POST[$currency])."'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
$sql_command->update($database_package_info,"notes='".addslashes($_POST[$note])."'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
} else {
$sql_command->update($database_package_info,"deleted='Yes'","package_id='".addslashes($_POST["package_id"])."' and id='".addslashes($package_option_record[0])."'");
}
}








$cols = "package_id,iw_name,cost,iw_cost,currency,type,notes,timestamp,code,deleted";

$current_row = 1;
$end_value = $_POST["theValue"] + 1;

while($current_row < $end_value) {
$menu_name = "new_menu_name_".$current_row;
$cost = "new_cost_".$current_row;
$iw_cost = "new_iw_cost_".$current_row;
$currency = "new_currency_".$current_row;
$note = "new_note_".$current_row;

if($_POST[$menu_name]) { 
$values = "'".addslashes($_POST["package_id"])."',
'".addslashes($_POST[$menu_name])."',
'".addslashes($_POST[$cost])."',
'".addslashes($_POST[$iw_cost])."',
'".addslashes($_POST[$currency])."',
'',
'".addslashes($_POST[$note])."',
'$time',
'',
'No'";
$sql_command->insert($database_package_info,$cols,$values);			
$maxid2 = $sql_command->maxid($database_package_info,"id");

$totalcharacters = strlen($_POST[$menu_name]);
$middleend = $totalcharacters / 2;

$_POST[$menu_name] = str_replace("<p>", "", $_POST[$menu_name]);
$_POST[$menu_name] = str_replace("</p>", "", $_POST[$menu_name]);
$_POST[$menu_name] = ereg_replace("[^A-Za-z]", "", $_POST[$menu_name]);

$totalcharacters = strlen($_POST[$menu_name]);
$middleend = $totalcharacters / 2;

$first = $_POST[$menu_name][0];
$middle = $_POST[$menu_name][$middleend];
$last = $_POST[$menu_name][$totalcharacters-1];

$code =  $maxid2 . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "P";

$sql_command->update($database_package_info,"code='".addslashes($code)."'","id='".$maxid2."'") . "P";

}

$current_row++;
}


$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Package Updated','".$time."','".addslashes($_POST["package_id"])."','".addslashes($_POST["island_id"])."'");

$get_template->topHTML();
?>
<h1>Package Updated</h1>

<p>The package has now been updated</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-package.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$menu_result = $sql_command->select($database_packages,"*","WHERE id='".addslashes($_POST["package_id"])."'");
$menu_record = $sql_command->result($menu_result);

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

$supplier_list .= "<option value=\"0\">None</option>";
foreach($supplier_row as $supplier_record) {
if($menu_record[2] == $supplier_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}



$ceremony_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY ceremony_name");
$ceremony_row = $sql_command->results($ceremony_result);

foreach($ceremony_row as $ceremony_record) {
if($menu_record[3] == $ceremony_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$ceremony_list .= "<option value=\"".stripslashes($ceremony_record[0])."\" $selected>".stripslashes($ceremony_record[1])."</option>";
}


$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

foreach($venue_row as $venue_record) {
if($menu_record[4] == $venue_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\" $selected>".stripslashes($venue_record[1])."</option>";
}

$add_header .= "<script language=\"javascript\" type=\"text/javascript\">
function deletechecked() {
	var answer = confirm(\"Confirm Delete\")
    if (answer){ document.messages.submit(); }
    return false;  
}  
</script>";

$get_template->topHTML();
?>
<h1>Update Package</h1>

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
+ '<div style="float:left; width:60px; margin:5px;"><input type="text" name="new_cost_' + num +'" style="width:60px;" value="0.00" /></div>'
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
    
<form action="<?php echo $site_url; ?>/oos/update-package.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $_POST["package_id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Supplier</b></div>
<div style="float:left; margin:5px;"><select name="supplier_id" style="width:300px;">
<?php echo $supplier_list; ?>
</select></div>
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

<div style="float:left; width:160px; margin:5px;"><b>Internal Package Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="name" style="width:350px;" value="<?php echo stripslashes($menu_record[5]); ?>"/> *</div>
<div style="clear:left;"></div>

<p style="text-align:right;"><input type="submit" name="action" value="Delete Package" onclick="return deletechecked();"/></p>

<p><hr /></p>

<p>Please enter the cost Per Person</p>

<div style="float:left; width:220px; margin:5px;"><b>External Package Name</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Net</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Gross</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Currency</b></div>
<div style="float:left; width:180px; margin:5px;"><b>Note</b></div>
<div style="float:left; margin:5px;"><b>Code</b></div>
<div style="clear:left;"></div>

<?
	
$total_menu_items = $sql_command->count_rows($database_package_info,"id","package_id='".addslashes($_POST["package_id"])."' and deleted='No'");
$total_menu_items++;

$package_option_result = $sql_command->select($database_package_info,"*","WHERE package_id='".addslashes($_POST["package_id"])."' and deleted='No' ORDER BY iw_name");
$package_option_row = $sql_command->results($package_option_result);


foreach($package_option_row as $package_option_record) { 
?>
<div style="float:left; width:220px; margin:5px;"><input type="text" name="menu_name_<?php echo $package_option_record[0]; ?>" style="width:220px;" value="<?php echo stripslashes($package_option_record[2]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="cost_<?php echo $package_option_record[0]; ?>" style="width:60px;" value="<?php echo stripslashes($package_option_record[3]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="iw_cost_<?php echo $package_option_record[0]; ?>" style="width:60px;" value="<?php echo stripslashes($package_option_record[4]); ?>"/></div>
<div style="float:left; width:60px; margin:5px;"><select name="currency_<?php echo $package_option_record[0]; ?>" style="width:60px;">
<option value="Euro" <?php if($package_option_record[5] == "Euro") { echo "selected=\"selected=\"selected\""; } ?>>Euro</option>
<option value="Pound" <?php if($package_option_record[5] == "Pound") { echo "selected=\"selected=\"selected\""; } ?>>Pound</option>
</select></div>
<div style="float:left; width:180px; margin:5px;"><input type="text" name="note_<?php echo $package_option_record[0]; ?>" style="width:180px;" value="<?php echo stripslashes($package_option_record[7]); ?>"/></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($package_option_record[9]); ?></div>
<div style="clear:left;"></div>
<?php 
} ?>
<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>

<p>* - Required Fields</p>


<input type="hidden" value="0" id="theValue" name="theValue">

<div style="float:left; margin:5px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left; margin:5px; margin-left:250px;"><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-package.php'"></div>
<div style="float:right; margin:5px;"><input type="button" value="Add Another Package" onclick="addElement();"></div>
<div style="clear:borth;"></div>


</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "island") {
	

$menu_result = $sql_command->select($database_packages,"$database_packages.id,
									$database_packages.package_name","WHERE 
									$database_packages.island_id='".addslashes($_GET["island_id"])."' and 
									$database_packages.deleted='No' 
									ORDER BY $database_packages.package_name");
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





foreach($menu_row as $menu_record) {	
if($menu_record[1] != "Ceremony Only Packages") {
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px;\">".stripslashes($menu_record[1])."</option>\n";
}
}




$get_template->topHTML();
?>
<h1>Update Package</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-package.php" method="POST">
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

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and  parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}


$get_template->topHTML();
?>
<h1>Update Package</h1>

<?php if($nav_list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-package.php" method="POST">
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