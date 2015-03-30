<?


$the_username = $_SESSION["admin_area_username"];
$the_password = $_SESSION["admin_area_password"];

$login_result = $sql_command->select($database_users,"account_option,id","WHERE username='".addslashes($the_username)."' and password='".addslashes($the_password)."'");
$login_record = $sql_command->result($login_result);


if(!$login_record[0]) { $error .= "Missing and/or Incorrect Login Details<br>"; }

if($error) {
$_SESSION["admin_area_password"] = "";
$_SESSION["admin_area_username"] = "";
$get_template->topHTML();
?>
<div class="maincopy">
<h1>Admin Area</h1>
<h2>Oops!</h2>
<p><?php echo $error; ?></p>
</div>
<?
$get_template->bottomHTML();
$sql_command->close();
}


?>