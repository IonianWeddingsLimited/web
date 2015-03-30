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
	
if(!$_POST["name"]) { $error .= "Missing Preffered Time<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Preffered Time","Oops!","$error","Link","admin/update-preffered-time.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->insert($database_preffered_time,"value","'".addslashes($_POST["name"])."'");


$get_template->topHTML();
?>
<h1>Preffered Time Added</h1>

<p>The preffered time has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_preffered_time,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>Preffered Time Deleted</h1>

<p>The preffered time has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {
	

$result = $sql_command->select($database_preffered_time,"id,value","ORDER BY value");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Preffered Time</h1>

<form action="<?php echo $site_url; ?>/admin/update-preffered-time.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Preffered Time:</b></div>
<div style="float:left; margin:5px;"><input type="text" name="name" maxlength="250" style="width:350px"></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Add"></p>
</form>
<p><hr /></p>
<form action="<?php echo $site_url; ?>/admin/update-preffered-time.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Preffered Time:</b></div>
<div style="float:left; margin:5px;"><select name="id" class="inputbox_town"><?php echo $list; ?></select></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Delete"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>