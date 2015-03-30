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
$get_template = new admin_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_POST["action"] == "Add") {
	
if(!$_POST["name"]) { $error .= "Missing Type of Ceremony<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Type of Ceremony","Oops!","$error","Link","admin/update-type-of-ceremony.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->insert($database_typeofceremony,"value","'".addslashes($_POST["name"])."'");


$get_template->topHTML();
?>
<h1>Type of Ceremony Added</h1>

<p>The type of ceremony has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_typeofceremony,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>Type of Ceremony Deleted</h1>

<p>The type of ceremony has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {
	

$result = $sql_command->select($database_typeofceremony,"id,value","ORDER BY value");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Type of Ceremony</h1>

<form action="<?php echo $site_url; ?>/admin/update-type-of-ceremony.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Type of Ceremony Name:</b></div>
<div style="float:left; margin:5px;"><input type="text" name="name" maxlength="250" style="width:350px"></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Add"></p>
</form>
<p><hr /></p>
<form action="<?php echo $site_url; ?>/admin/update-type-of-ceremony.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Type of Ceremony:</b></div>
<div style="float:left; margin:5px;"><select name="id" class="inputbox_town"><?php echo $list; ?></select></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Delete"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>