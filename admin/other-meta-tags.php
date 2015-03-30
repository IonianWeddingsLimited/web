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

if($_POST["action"] == "Continue") {
	
$result = $sql_command->select($database_meta_tags,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);


$get_template->topHTML();
?>
<h1>Other Meta Tags</h1>

<form action="<?php echo $site_url; ?>/admin/other-meta-tags.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Meta Title</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_title" style="width:400px;" value="<?php echo stripslashes($record[2]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Keywords</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_key" style="width:400px;" value="<?php echo stripslashes($record[4]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Description</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_des" style="width:400px;" value="<?php echo stripslashes($record[3]); ?>"/></div>
<div style="clear:left;"></div>

<p><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
	

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Other Meta Tags","Oops!","$error","Link","admin/other-meta-tags.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_meta_tags,"meta_title='".addslashes($_POST["meta_title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_meta_tags,"meta_keyword='".addslashes($_POST["meta_key"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_meta_tags,"meta_des='".addslashes($_POST["meta_des"])."'","id='".addslashes($_POST["id"])."'");


$get_template->topHTML();
?>
<h1>Other Meta Tag Updated</h1>

<p>The meta tags have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {

$current = date("Y",$time);
$currentplus = $current + 1;

$result = $sql_command->select($database_meta_tags,"id,page_name","WHERE year < $currentplus ORDER BY page_name");
$row = $sql_command->results($result);
	
foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:11px;\">".stripslashes($record[1])."</option>";
}



$get_template->topHTML();
?>
<h1>Other Meta Tags</h1>

<form action="<?php echo $site_url; ?>/admin/other-meta-tags.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="30" style="width:710px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>