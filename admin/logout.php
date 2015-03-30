<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
require ("../_includes/function.database.php");


$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

// Get Templates
$get_template = new main_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";


$_SESSION["admin_area_password"] = "";
$_SESSION["admin_area_username"] = "";

$get_template->topHTML();
?>
<div class="maincopy">
<h1>Logout</h1>

<p>You have now been logged out of the admin area.</p>
</div>
<?
$get_template->bottomHTML();
?>