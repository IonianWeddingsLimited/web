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


if($_POST["action"] == "Continue") {
	
$result = $sql_command->select($database_users,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$get_template->topHTML();
?>
<h1>Update User</h1>
<script language="javascript" type="text/javascript">

function deletechecked()
{
    var answer = confirm("Confirm Delete")
    if (answer){
        document.messages.submit();
    }
    
    return false;  
}  

</script>

<form action="<?php echo $site_url; ?>/oos/update-user.php" method="POST">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Username</b></div>
<div style="float:left; margin:5px;"><input type="text" name="username" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Password</b></div>
<div style="float:left; margin:5px;"><input type="text" name="password" value="<?php echo stripslashes($record[2]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Account Type</b></div>
<div style="float:left; margin:5px;"><select name="account_type">
<option value="Super Admin User" <?php if($record[3] == "Super Admin User") { echo "selected=\"selected\""; } ?>>Super Admin User</option>
<option value="Admin User" <?php if($record[3] == "Admin User") { echo "selected=\"selected\""; } ?>>Admin User</option>
<option value="OOS Admin User" <?php if($record[3] == "OOS Admin User") { echo "selected=\"selected\""; } ?>>OOS Admin User</option>
<option value="Website Admin User" <?php if($record[3] == "Website Admin User") { echo "selected=\"selected\""; } ?>>Website Admin User</option>
</select>
</div>
<div style="clear:left;"></div>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left; margin-top:10px; margin-left:250px;"><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-user.php'"></div>
</form>

<form action="<?php echo $site_url; ?>/oos/update-user.php" method="POST" name="testimonial">
<div style="float: right; margin-right:10px; margin-top:10px;">
<input type="hidden" name="username" value="<?php echo stripslashes($record[1]); ?>"/>
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
if(!$_POST["username"]) { $error .= "Missing Username<br>"; }
if(!$_POST["password"]) { $error .= "Missing Password<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update User","Oops!","$error","Link","admin/update-user.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_users,"username='".addslashes($_POST["username"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_users,"password='".addslashes($_POST["password"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_users,"account_option='".addslashes($_POST["account_type"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'','".$login_record[1]."','User Updated (".addslashes($_POST["username"]).")','".$time."'");

$get_template->topHTML();
?>
<h1>User Updated</h1>

<p>The user has now been updated</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-user.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_users,"id='".addslashes($_POST["id"])."'");
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'','".$login_record[1]."','User Deleted (".addslashes($_POST["username"]).")','".$time."'");
	
$get_template->topHTML();
?>
<h1>User Deleted</h1>

<p>The user has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$result = $sql_command->select($database_users,"id,username","WHERE account_option!='Super Admin' ORDER BY username");
$row = $sql_command->results($result);

foreach($row as $record) {
if($record[1] != "admin") {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}
}

$get_template->topHTML();
?>
<h1>Update User</h1>

<form action="<?php echo $site_url; ?>/oos/update-user.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>