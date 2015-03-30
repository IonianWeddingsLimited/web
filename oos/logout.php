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
<p><?php echo $_SERVER['HTTP_REFERER'];?></p>
<form action="<?php echo $site_url;?>/oos/index.php" method="POST">
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Username</b></div>
<div style="float:left; margin-bottom:10px;"><input type="text" name="login_username" class="inputbox" /></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Password</b></div>
<div style="float:left; margin-bottom:10px;"><input type="password" name="login_password" class="inputbox" /></div>
<div style="clear:left;"></div>
<input type="hidden" name="login_redirect" class="inputbox" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
<p><input type="submit" name="action" value="Login"/></p>
</form>
</div>
<?
$get_template->bottomHTML();
?>