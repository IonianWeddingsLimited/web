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


if($_POST["action"] == "Add") {
	
if(!$_POST["username"]) { $error .= "Missing Username<br>"; }
if(!$_POST["password"]) { $error .= "Missing Password<br>"; }

$result = $sql_command->select($database_users,"id","WHERE username='".addslashes($_POST["username"])."'");
$record = $sql_command->result($result);

if($record[0]) { $error .= "This username already exists<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add User","Oops!","$error","Link","admin/add-user.php");
$get_template->bottomHTML();
$sql_command->close();
}


$values = "'".addslashes($_POST["username"])."',
'".addslashes($_POST["password"])."',
'".addslashes($_POST["account_type"])."'";

$sql_command->insert($database_users,"username,password,account_option",$values);

	
$get_template->topHTML();
?>
<h1>User Added</h1>

<p>The user has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$get_template->topHTML();
?>
<h1>Add User</h1>

<form action="<?php echo $site_url; ?>/admin/add-user.php" method="POST">
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Username</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="username"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Password</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="password"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Account Type</b></div>
<div style="float:left; margin:5px;"><select name="account_type">
<option value="Super Admin User">Super Admin User</option>
<option value="Admin User">Admin User</option>
<option value="OOS Admin User">OOS Admin User</option>
<option value="Website Admin User">Website Admin User</option>
</select>
</div>
<div style="clear:left;"></div>
<p style="margin-top:10px;"><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>