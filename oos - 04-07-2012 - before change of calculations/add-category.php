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
	
if(!$_POST["name"]) { $error .= "Missing Category<BR>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Category","Oops!","$error","Link","admin/add-category.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->insert($database_category_extras,"category_name","'".addslashes($_POST["name"])."'");


$get_template->topHTML();
?>
<h1>Category Added</h1>

<p>The category has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {


$get_template->topHTML();
?>
<h1>Add Category</h1>

<form action="<?php echo $site_url; ?>/oos/add-category.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Category Name:</b></div>
<div style="float:left; margin:5px;"><input type="text" name="name" maxlength="250" style="width:350px"></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>