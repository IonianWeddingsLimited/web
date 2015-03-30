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

if($_POST["action"] == "Update") {
	
if(!$_POST["name"]) { $error .= "Missing Category<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Category","Oops!","$error","Link","admin/update-category.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_category_extras,"category_name='".addslashes($_POST["name"])."'","id='".addslashes($_POST["id"])."'");


$get_template->topHTML();
?>
<h1>Category Updated</h1>

<p>The category has now been updated</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-category.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {

$result = $sql_command->select($database_category_extras,"id,category_name","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$get_template->topHTML();
?>
<h1>Update Category</h1>

<form action="<?php echo $site_url; ?>/oos/update-category.php" method="POST">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Category Name:</b></div>
<div style="float:left; margin:5px;"><input type="text" name="name" maxlength="250" style="width:350px" value="<?php echo stripslashes($record[1]); ?>"></div>
<div style="clear:left;"></div>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-category.php'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} else {
	
	
$result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Category</h1>

<form action="<?php echo $site_url; ?>/oos/update-category.php" method="POST">
<input type="hidden" name="action" value="View" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}
?>