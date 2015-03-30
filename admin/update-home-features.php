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



if($_POST["action"] == "Update") {
	


$sql_command->update($database_home_feature,"description='".addslashes($_POST["html_1"])."'","id='1'");
$sql_command->update($database_home_feature,"description='".addslashes($_POST["html_2"])."'","id='2'");
$sql_command->update($database_home_feature,"description='".addslashes($_POST["html_3"])."'","id='3'");
$sql_command->update($database_home_feature,"description='".addslashes($_POST["html_4"])."'","id='4'");



$get_template->topHTML();
?>
<h1>Home Features Updated</h1>

<p>The home features have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} else {

$result = $sql_command->select($database_home_feature,"id,description","ORDER BY id");
$row = $sql_command->results($result);

$count=0;
foreach($row as $record) {
$count++;

if($count != 1) { $hr = "<hr>"; } else { $hr = ""; }

$list .= "$hr
<textarea name=\"html_".$record[0]."\" style=\"width:500px;\" id=\"the_editor".$record[0]."\"/>".stripslashes($record[1])."</textarea>\n";
}

$get_template->topHTML();
?>
<h1>Update Home Features</h1>

<form action="<?php echo $site_url; ?>/admin/update-home-features.php" method="POST">

<?php echo $list; ?>
<?php echo $admin_editor4; ?>

<p style="margin-top:10px;"><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>