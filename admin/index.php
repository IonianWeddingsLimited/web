<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();

if($_SESSION["admin_area_username"] and $_SESSION["admin_area_password"]) {
header("location: $site_url/admin/admin-area.php");
$sql_command->close();
}


// Check login details
if($_POST["action"] == "Login") {
$_SESSION["admin_area_username"] = $_POST["login_username"];
$_SESSION["admin_area_password"] = $_POST["login_password"];
header("location: $site_url/admin/admin-area.php");
$sql_command->close();

} else {

$get_template->topHTML();
?>
<div class="maincopy">
<h1>Admin Area</h1>

<form action="<?php echo $site_url;?>/admin/index.php" method="POST">
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Username</b></div>
<div style="float:left; margin-bottom:10px;"><input type="username" name="login_username" class="inputbox"></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Password</b></div>
<div style="float:left; margin-bottom:10px;"><input type="password" name="login_password" class="inputbox"></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Login"/></p>
</form>
</div>
<?
$get_template->bottomHTML();
$sql_command->close();

}
?>