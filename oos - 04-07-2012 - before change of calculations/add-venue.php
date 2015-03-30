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
	
if(!$_POST["venue_name"]) { $error .= "Missing Venue<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Venue","Oops!","$error","Link","admin/add-venue.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->insert($database_venue_names,"island_id,venue_name","'".addslashes($_POST["island"])."','".addslashes($_POST["venue_name"])."'");


$get_template->topHTML();
?>
<h1>Venue Added</h1>

<p>The venue has now been added</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/add-venue.php'"></p>
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
<h1>Add Venue</h1>

<form action="<?php echo $site_url; ?>/oos/add-venue.php" method="POST">

<div style="float:left; width:160px; margin:5px;"><b>Island</b></div>
<div style="float:left; margin:5px;"><select name="island" size="10" style="width:300px;">
<?php echo $nav_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Venue Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="venue_name" style="width:350px;"/> *</div>
<div style="clear:left;"></div>
<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

}
?>