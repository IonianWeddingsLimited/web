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
	


$sql_command->update($database_gray_feature,"title='".addslashes($_POST["title_1"])."'","id='1'");
$sql_command->update($database_gray_feature,"link_name='".addslashes($_POST["linkname_1"])."'","id='1'");
$sql_command->update($database_gray_feature,"link_url='".addslashes($_POST["linklocation_1"])."'","id='1'");
$sql_command->update($database_gray_feature,"description='".addslashes($_POST["description_1"])."'","id='1'");

$sql_command->update($database_gray_feature,"title='".addslashes($_POST["title_2"])."'","id='2'");
$sql_command->update($database_gray_feature,"link_name='".addslashes($_POST["linkname_2"])."'","id='2'");
$sql_command->update($database_gray_feature,"link_url='".addslashes($_POST["linklocation_2"])."'","id='2'");
$sql_command->update($database_gray_feature,"description='".addslashes($_POST["description_2"])."'","id='2'");

$sql_command->update($database_gray_feature,"title='".addslashes($_POST["title_3"])."'","id='3'");
$sql_command->update($database_gray_feature,"link_name='".addslashes($_POST["linkname_3"])."'","id='3'");
$sql_command->update($database_gray_feature,"link_url='".addslashes($_POST["linklocation_3"])."'","id='3'");
$sql_command->update($database_gray_feature,"description='".addslashes($_POST["description_3"])."'","id='3'");

$sql_command->update($database_gray_feature,"title='".addslashes($_POST["title_4"])."'","id='4'");
$sql_command->update($database_gray_feature,"link_name='".addslashes($_POST["linkname_4"])."'","id='4'");
$sql_command->update($database_gray_feature,"link_url='".addslashes($_POST["linklocation_4"])."'","id='4'");
$sql_command->update($database_gray_feature,"description='".addslashes($_POST["description_4"])."'","id='4'");



$get_template->topHTML();
?>
<h1>Features Updated</h1>

<p>The features have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} else {

$result = $sql_command->select($database_gray_feature,"id,title,description,link_name,link_url","ORDER BY id");
$row = $sql_command->results($result);

$count=0;
foreach($row as $record) {
$count++;

if($count != 1) { $hr = "<hr>"; } else { $hr = ""; }

$list .= "$hr<div style=\"float:left; width:160px; margin:5px;\"><b>Title</b></div>
<div style=\"float:left; margin:5px;\">	<input type=\"text\" name=\"title_".$record[0]."\" style=\"width:400px;\" value=\"".stripslashes($record[1])."\"/></div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:160px; margin:5px;\"><b>Link Name</b></div>
<div style=\"float:left; margin:5px;\">	<input type=\"text\" name=\"linkname_".$record[0]."\" style=\"width:400px;\" value=\"".stripslashes($record[3])."\"/></div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:160px; margin:5px;\"><b>Link Location</b></div>
<div style=\"float:left; margin:5px;\">	<input type=\"text\" name=\"linklocation_".$record[0]."\" style=\"width:400px;\" value=\"".stripslashes($record[4])."\"/></div>
<div style=\"clear:left;\"></div>
<div style=\"float:left; width:160px; margin:5px;\"><b>Description</b></div>
<div style=\"float:left; margin:5px;\"><textarea name=\"description_".$record[0]."\" style=\"width:500px;\" id=\"the_editor".$count."\">".stripslashes($record[2])."</textarea></div>
<div style=\"clear:left;\"></div>\n";
}

$get_template->topHTML();
?>
<h1>Update Features</h1>

<form action="<?php echo $site_url; ?>/admin/update-features.php" method="POST">

<?php echo $list; ?>
<?php echo $admin_editor4; ?>

<p style="margin-top:10px;"><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>