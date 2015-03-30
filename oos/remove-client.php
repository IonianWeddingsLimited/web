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

if($login_record[0] == "Admin" or $login_record[0] == "Super Admin User") {
if($_POST["action"] == "Delete Client") {
	
$sql_command->update($database_clients,"deleted='Yes'","id='".addslashes($_POST["id"])."'");
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["id"])."','".$login_record[1]."','Client Deleted','".$time."'");

$get_template->topHTML();
?>
<h1>Client Deleted</h1>

<p>The client has now been deleted</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/remove-client.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$result = $sql_command->select($database_clients,"id,title,firstname,lastname","WHERE deleted='No' ORDER BY firstname,lastname");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[2])." ".stripslashes($record[3])."</option>\n";
}

$add_header .= "<script language=\"javascript\" type=\"text/javascript\">
function deletechecked() {
	var answer = confirm(\"Confirm Delete\")
    if (answer){ document.messages.submit(); }
    return false;  
}  
</script>";

$get_template->topHTML();
?>
<h1>Delete Client</h1>

<form action="<?php echo $site_url; ?>/oos/remove-client.php" method="POST">
<input type="hidden" name="a" value="Continue" />
<select name="id" class="inputbox_town" size="50" style="width:700px;"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Delete Client"  onclick="return deletechecked();"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}
}
?>