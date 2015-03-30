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



if($_POST["action"] == "Update") {
	
$result = $sql_command->select($database_navigation,"id","WHERE parent_id='".addslashes($_POST["nav_id"])."'");
$row = $sql_command->results($result);

foreach($row as $record) {
$idline = "id_".$record[0];
if($_POST[$idline]) {
$sql_command->update($database_navigation,"displayorder='".addslashes($_POST[$idline])."'","parent_id='".addslashes($_POST["nav_id"])."' and id='".addslashes($record[0])."'");	
} else {
$sql_command->update($database_navigation,"displayorder='100'","parent_id='".addslashes($_POST["nav_id"])."' and id='".addslashes($record[0])."'");	
}
}

$get_template->topHTML();
?>
<h1>Display Order Updated</h1>

<p>The display order has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Continue") {
	
	
$result = $sql_command->select($database_navigation,"id,page_name,displayorder","WHERE parent_id='".addslashes($_POST["nav_id"])."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {
$html_line .= "<div style=\"float:left; width:300px; margin:5px;\">".stripslashes($record[1])."</div>
<div style=\"float:left; margin:5px;\">	<input type=\"text\" name=\"id_".stripslashes($record[0])."\" value=\"".stripslashes($record[2])."\"/></div>
<div style=\"clear:left;\"></div>";	
}


$get_template->topHTML();
?>
<h1>Display Order</h1>

<?php if(!$html_line) { ?>
<p>There are no sub menus under this navigation level</p>
<?php } else { ?>
<p>The lower the number the higher up the menu list it will appear</p>

<form action="<?php echo $site_url; ?>/admin/display-order.php" method="POST">
<input type="hidden" name="nav_id" value="<?php echo $_POST["nav_id"]; ?>" />

<div style="float:left; width:300px; margin:5px;"><b>Menu Item</b></div>
<div style="float:left; margin:5px;"><b>Value</b></div>
<div style="clear:left;"></div>
<?php echo $html_line; ?>
<p style="margin-top:10px;"><input type="submit" name="action" value="Update"></p>
</form>
<?
}
$get_template->bottomHTML();
$sql_command->close();

} else {

$nav_list .= "<option value=\"0\" style=\"font-size:11px;\">/</option>";

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages" or $level1_record[1] == "Navigation Header" or $level1_record[1] == "Navigation Footer") {

$nav_list .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/</option>";

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

if($level2_record[1] == "Personal Consultations" or $level2_record[1] == "Testimonials") {
} else {
$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/</option>";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</option>";
}
}
} 
}
}


$get_template->topHTML();
?>
<h1>Update Display Order</h1>

<form action="<?php echo $site_url; ?>/admin/display-order.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="nav_id" class="inputbox_town" size="30" style="width:710px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}
?>