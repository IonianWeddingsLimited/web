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

if($_POST["action"] == "Continue") {
	
$result = $sql_command->select($database_level_2,"*","WHERE parent_id_1='7' and id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);


$get_template->topHTML();
?>
<h1>Update Planing Advice</h1>
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
<form action="<?php echo $site_url; ?>/admin/update-planning-advice.php" method="POST" name="testimonial">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Meta Title</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_title" style="width:400px;" value="<?php echo stripslashes($record[4]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Keywords</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_key" style="width:400px;"  value="<?php echo stripslashes($record[5]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Description</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_des" style="width:400px;"  value="<?php echo stripslashes($record[6]); ?>"/></div>
<div style="clear:left;"></div>

<p><hr /></p>

<div style="float:left; width:160px; margin:5px;"><b>HTML Page</b></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[2]); ?></div>
<div style="clear:left;"></div>
<textarea name="html" style="height:400px; width:100%;" id="the_editor"><?php echo stripslashes($record[9]); ?></textarea>
<?php echo $admin_editor; ?>

<p style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update"></p>
</form>


<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
	
if(!$_POST["html"]) { $error .= "Missing HTML<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Planing Advice","Oops!","$error","Link","admin/update-advice.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_level_2,"meta_title='".addslashes($_POST["meta_title"])."'","parent_id_1='7' and id='".addslashes($_POST["id"])."'");
$sql_command->update($database_level_2,"meta_key='".addslashes($_POST["meta_key"])."'","parent_id_1='7' and id='".addslashes($_POST["id"])."'");
$sql_command->update($database_level_2,"meta_des='".addslashes($_POST["meta_des"])."'","parent_id_1='7' and id='".addslashes($_POST["id"])."'");
$sql_command->update($database_level_2,"html='".addslashes($_POST["html"])."'","parent_id_1='7' and id='".addslashes($_POST["id"])."'");
$sql_command->update($database_level_2,"active_page='Yes'","parent_id_1='7' and id='".addslashes($_POST["id"])."'");


$get_template->topHTML();
?>
<h1>Planing Advice Updated</h1>

<p>Planing advice has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} else {

$result = $sql_command->select($database_level_2,"id,name","WHERE parent_id_1='7'and  active_page='Yes' ORDER BY name");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\">".stripslashes($record[1])."</option>\n";
}



$get_template->topHTML();
?>
<h1>Update Planing Advice</h1>

<form action="<?php echo $site_url; ?>/admin/update-planning-advice.php" method="POST">
<select name="id" class="inputbox_town" size="30" style="width:700px;"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>