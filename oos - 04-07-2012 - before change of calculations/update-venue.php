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
header("Location: $site_url/oos/update-venue.php?a=island&island_id=".$_POST["island_id"]);
exit();
}



if($_POST["action"] == "Update") {
	
if(!$_POST["name"]) { $error .= "Missing Venue<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Venue","Oops!","$error","Link","oos/update-venue.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_venue_names,"venue_name='".addslashes($_POST["name"])."'","id='".addslashes($_POST["id"])."'");



$get_template->topHTML();
?>
<h1>Venue Updated</h1>

<p>The venue has now been updated</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-venue.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {

$result = $sql_command->select($database_venue_names,"id,island_id,venue_name","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$get_template->topHTML();
?>
<h1>Update Venue</h1>

<form action="<?php echo $site_url; ?>/oos/update-venue.php" method="POST">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Venue Name:</b></div>
<div style="float:left; margin:5px;"><input type="text" name="name" maxlength="250" style="width:350px" value="<?php echo stripslashes($record[2]); ?>"></div>
<div style="clear:left;"></div>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-venue.php'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "island") {
	
	
$result = $sql_command->select($database_venue_names,"id,venue_name","WHERE island_id='".addslashes($_GET["island_id"])."' ORDER BY venue_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Category</h1>

<form action="<?php echo $site_url; ?>/oos/update-venue.php" method="POST">
<input type="hidden" name="action" value="View" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
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
<h1>Update Menu Venue</h1>

<form action="<?php echo $site_url; ?>/oos/update-venue.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} 

?>