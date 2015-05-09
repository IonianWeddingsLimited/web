<?
$the_username = $_SESSION["admin_area_username"];
$the_password = $_SESSION["admin_area_password"];

$login_result = $sql_command->select($database_users,"account_option,id","WHERE username='".addslashes($the_username)."' and password='".addslashes($the_password)."'");
$login_record = $sql_command->result($login_result);


if(!$login_record[0]) { $error .= "Missing and/or Incorrect Login Details<br>"; $login_id = $login_record[1]; }
else { $login_id = $login_record[1]; }

if(isset($error) && $error)  {
$_SESSION["admin_area_password"] = "";
$_SESSION["admin_area_username"] = "";
$get_template->topHTML();
?>
<div class="maincopy">
<h1>Admin Area</h1>
<h2>Oops!</h2>
<p><?php echo $error; ?></p>

<form action="<?php echo $site_url;?>/oos/index.php" method="POST">
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Username</b></div>
<div style="float:left; margin-bottom:10px;"><input type="text" name="login_username" class="inputbox"></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; font-size:12px; margin-bottom:10px;"><b>Password</b></div>
<div style="float:left; margin-bottom:10px;"><input type="password" name="login_password" class="inputbox"></div>
<input type="hidden" name="login_redirect" class="inputbox" value="<?php echo $_SERVER["REQUEST_URI"];?>" />
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Login"/></p>
</form>
</div>
<?
$get_template->bottomHTML();
$sql_command->close();
}

$_SESSION['IsAuthorizedok'] = true;

?>