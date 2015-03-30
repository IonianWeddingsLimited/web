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

if($_POST["action"] == "Continue" and $_POST["supplier_id"]) {
header("Location: $site_url/oos/update-extra.php?a=continue&supplier_id=".$_POST["supplier_id"]);
exit();
}

if($_POST["action"] == "Delete Extra")  {

if(!$_POST["id"]) { $error .= "Missing Extra IdId<br>"; }
	
	
	
if($error) {
$get_template->topHTML();
$get_template->errorHTML("Delete Extra","Oops!","$error","Link","oos/update-extra.php");
$get_template->bottomHTML();
$sql_command->close();
}

$_POST["extraname"] = str_replace("<p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("</p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("\n", "", $_POST["extraname"]);

$sql_command->update($database_package_extras,"deleted='Yes'","id='".addslashes($_POST["id"])."'");
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Delete Extra (".addslashes($_POST["extraname"]).")','".$time."','','".addslashes($_POST["island"])."'");

$get_template->topHTML();
?>
<h1>Extra Deleted</h1>

<p>The extra has now been deleted</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-extra.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
$_POST["cost"] = ereg_replace("[^0-9\.]", "", $_POST["cost"]);
$_POST["iw_cost"] = ereg_replace("[^0-9\.]", "", $_POST["iw_cost"]);

if(!$_POST["cost"]) { $error .= "Missing Net<br>"; }
if(!$_POST["iw_cost"]) { $error .= "Missing Gross<br>"; }
if(!$_POST["extraname"]) { $error .= "Missing Extra Name<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Extra","Oops!","$error","Link","oos/update-extra.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_package_extras,"supplier_id='".addslashes($_POST["supplier"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"category_id='".addslashes($_POST["category"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"island_id='".addslashes($_POST["island"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"product_name='".addslashes($_POST["extraname"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"currency='".addslashes($_POST["currency"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"cost='".addslashes($_POST["cost"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"iw_cost='".addslashes($_POST["iw_cost"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_package_extras,"notes='".addslashes($_POST["notes"])."'","id='".addslashes($_POST["id"])."'");

$_POST["extraname"] = str_replace("<p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("</p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("\n", "", $_POST["extraname"]);

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Update Extra (".addslashes($_POST["extraname"]).")','".$time."','','".addslashes($_POST["island"])."'");

$get_template->topHTML();
?>
<h1>Extra Updated</h1>

<p>The extra has now been updated</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-extra.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$result = $sql_command->select($database_package_extras,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);


$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
if($record[1] == $supplier_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}



$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

if($record[3] == $level2_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }

$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\" $selected>".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
if($record[3] == $level3_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\" $selected>".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}

$category_result = $sql_command->select($database_category_extras,"id,category_name","WHERE id!=58 and id!=59 and deleted='No' ORDER BY category_name");
$category_row = $sql_command->results($category_result);

foreach($category_row as $category_record) {
if($record[2] == $category_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$category_list .= "<option value=\"".stripslashes($category_record[0])."\" $selected>".stripslashes($category_record[1])."</option>";
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
<h1>Update Extra</h1>

<form action="<?php echo $site_url; ?>/oos/update-extra.php" method="POST">
<input type="hidden" name="supplier_id" value="<?php echo $_POST["supplier_id"]; ?>" />
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Code</b></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[11]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Island</b></div>
<div style="float:left; margin:5px;"><select name="island" size="10" style="width:300px;">
<?php echo $nav_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Supplier</b></div>
<div style="float:left; margin:5px;"><select name="supplier" style="width:300px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Category</b></div>
<div style="float:left; margin:5px;"><select name="category" style="width:300px;">
<?php echo $category_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Currency</b></div>
<div style="float:left; margin:5px;"><select name="currency" style="width:100px;">
<option value="Euro" <?php if($record[5] == "Euro") { echo "selected=\"selected\""; } ?>>Euro</option>
<option value="Pound" <?php if($record[5] == "Pound") { echo "selected=\"selected\""; } ?>>Pound</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Net</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="cost" style="width:100px;" value="<?php echo stripslashes($record[6]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Gross</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="iw_cost" style="width:100px;" value="<?php echo stripslashes($record[7]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Extra Name</b></div>
<div style="float:left; margin:5px;"><textarea name="extraname" id="the_editor_min" class="the_editor_min"><?php echo stripslashes($record[4]); ?></textarea><?php echo $admin_editor_min; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Notes</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="notes" style="width:400px;"/></div>
<div style="clear:left;"></div>

<p>* - Required Fields</p>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left; margin-left:240px; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-extra.php'"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="action" value="Delete Extra" onclick="return deletechecked();"/></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "continue") {



$result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
							   $database_package_extras.product_name,
							   $database_category_extras.category_name","
							   WHERE $database_package_extras.category_id=$database_category_extras.id AND
							   $database_package_extras.supplier_id='".addslashes($_GET["supplier_id"])."' and 
							   $database_package_extras.deleted='No' 
							   ORDER BY $database_category_extras.category_name, $database_package_extras.product_name");
$row = $sql_command->results($result);


foreach($row as $record) {
if(eregi("<p>",$record[1])) {
$start = strpos($record[1], '<p>');
$end = strpos($record[1], '</p>', $start);
$paragraph = substr($record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 200) ? substr($paragraph, 0, 200) . '...' : $paragraph;
} else {
$paragraph = stripslashes($record[1]);
}

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\"><strong>".stripslashes($record[2])."</strong> - ".stripslashes($paragraph)."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Extra</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-extra.php" method="POST">
<input type="hidden" name="action" value="View" />
<input type="hidden" name="supplier_id" value="<?php echo $_GET["supplier_id"]; ?>" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?php } else { ?>
<p>Please make sure you select an option</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} else {

$result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Extra</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-extra.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="supplier_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

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