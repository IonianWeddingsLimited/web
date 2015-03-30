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

if(!$_POST["cost"]) { $error .= "Missing Net<br>"; }
if(!$_POST["iw_cost"]) { $error .= "Missing Gross<br>"; }
if(!$_POST["extraname"]) { $error .= "Missing Ceremony Package Name<br>"; }
if(!$_POST["island"]) { $error .= "Missing Island<br>"; }

$_SESSION["island"] = $_POST["island"];
$_SESSION["supplier"] = $_POST["supplier"];
$_SESSION["category"] = $_POST["category"];

$_SESSION["cost"] = $_POST["cost"];
$_SESSION["iw_cost"] = $_POST["iw_cost"];
$_SESSION["extraname"] = $_POST["extraname"];
$_SESSION["notes"] = $_POST["notes"];

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Ceremony Package","Oops!","$error","Link","oos/ceremony-package.php");
$get_template->bottomHTML();
$sql_command->close();
}

$cols = "supplier_id,category_id,island_id,product_name,currency,cost,iw_cost,type,notes,timestamp,code,deleted";

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
'',
'No'";

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

$_POST["extraname"] = str_replace("<p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("</p>", "", $_POST["extraname"]);
$_POST["extraname"] = str_replace("\n", "", $_POST["extraname"]);

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Ceremony Package Added (".addslashes($_POST["extraname"]).")','".$time."','','".addslashes($_POST["island"])."'");

$_SESSION["island"] = "";
$_SESSION["supplier"] = "";
$_SESSION["category"] = "";

$_SESSION["cost"] = "";
$_SESSION["iw_cost"] = "";
$_SESSION["extraname"] = "";
$_SESSION["notes"] = "";

$get_template->topHTML();
?>
<h1>Ceremony Package Added</h1>

<p>The ceremony package has now been added</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/ceremony-package.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selected>".stripslashes($supplier_record[1])."</option>";
}



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


$nav_list = str_replace("value=\"".$_SESSION["island"]."\"","value=\"".$_SESSION["island"]."\" selected=\"selected\"",$nav_list);
$supplier_list = str_replace("value=\"".$_SESSION["supplier"]."\"","value=\"".$_SESSION["supplier"]."\" selected=\"selected\"",$supplier_list);

$get_template->topHTML();
?>
<h1>Add Ceremony Package</h1>

<form action="<?php echo $site_url; ?>/oos/ceremony-package.php" method="POST">
<input type="hidden" name="category" value="59">
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
<div style="float:left; width:160px; margin:5px;"><b>Currency</b></div>
<div style="float:left; margin:5px;"><select name="currency" style="width:100px;">
<option value="Euro" <?php if($_SESSION["currency"] == "Euro") { echo "selected=\"selected\""; } ?>>Euro</option>
<option value="Pound" <?php if($_SESSION["currency"] == "Pound") { echo "selected=\"selected\""; } ?>>Pound</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Net</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="cost" style="width:100px;" value="<?php echo $_SESSION["cost"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Gross</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="iw_cost" style="width:100px;" value="<?php echo $_SESSION["iw_cost"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Ceremony Details</b></div>
<div style="float:left; margin:5px;"><textarea name="extraname" id="the_editor_min" class="the_editor_min"><?php echo $_SESSION["extraname"]; ?></textarea><?php echo $admin_editor_min; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Notes</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="notes" style="width:400px;"  value="<?php echo $_SESSION["notes"]; ?>"/></div>
<div style="clear:left;"></div>
<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>