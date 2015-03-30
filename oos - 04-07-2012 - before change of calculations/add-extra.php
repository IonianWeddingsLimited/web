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



if($_POST["action"] == "Add") {
	
$_POST["cost"] = ereg_replace("[^0-9\.]", "", $_POST["cost"]);
$_POST["iw_cost"] = ereg_replace("[^0-9\.]", "", $_POST["iw_cost"]);

if(!$_POST["cost"]) { $error .= "Missing Cost<br>"; }
if(!$_POST["iw_cost"]) { $error .= "Missing IW Cost<br>"; }
if(!$_POST["extraname"]) { $error .= "Missing Extra Name<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Extra","Oops!","$error","Link","oos/add-extra.php");
$get_template->bottomHTML();
$sql_command->close();
}

$cols = "supplier_id,category_id,island_id,product_name,currency,cost,iw_cost,type,notes,timestamp,code";

$values = "'".addslashes($_POST["supplier"])."',
'".addslashes($_POST["category"])."',
'".addslashes($_POST["island"])."',
'".addslashes($_POST["extraname"])."',
'".addslashes($_POST["currency"])."',
'".addslashes($_POST["cost"])."',
'".addslashes($_POST["iw_cost"])."',
'Extra',
'".addslashes($_POST["notes"])."',
'$time',
''";

$sql_command->insert($database_package_extras,$cols,$values);
$maxid = $sql_command->maxid($database_package_extras,"id");

$_POST["product_name"] = str_replace("<p>", "", $_POST["product_name"]);
$_POST["product_name"] = str_replace("</p>", "", $_POST["product_name"]);
$_POST["product_name"] = ereg_replace("[^A-Za-z]", "", $_POST["product_name"]);

$totalcharacters = strlen($_POST["product_name"]);
$middleend = $totalcharacters / 2;

$first = $_POST["product_name"][0];
$middle = $_POST["product_name"][$middleend];
$last = $_POST["product_name"][$totalcharacters-1];

$code =  $maxid . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "E";

$sql_command->update($database_package_extras,"code='".addslashes($code)."'","id='".$maxid."'");
	
$get_template->topHTML();
?>
<h1>Extra Added</h1>

<p>The extra has now been added</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/add-extra.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}



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

$category_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$category_row = $sql_command->results($category_result);

foreach($category_row as $category_record) {
$category_list .= "<option value=\"".stripslashes($category_record[0])."\" $selected>".stripslashes($category_record[1])."</option>";
}

$get_template->topHTML();
?>
<h1>Add Extra</h1>

<form action="<?php echo $site_url; ?>/oos/add-extra.php" method="POST">

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
<option value="Euro">Euro</option>
<option value="Pound">Pound</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Cost</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="cost" style="width:100px;"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>IW Cost</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="iw_cost" style="width:100px;"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Extra Name</b></div>
<div style="float:left; margin:5px;"><textarea name="extraname" id="the_editor_min" class="the_editor_min"></textarea><?php echo $admin_editor_min; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Notes</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="notes" style="width:400px;"/></div>
<div style="clear:left;"></div>
<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>