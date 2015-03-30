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


if($_POST["action"] == "Continue" and $_POST["island_id"]) {
header("Location: $site_url/oos/update-ceremony-reception.php?a=island&island_id=".$_POST["island_id"]);
exit();
}



if($_POST["action"] == "Delete Ceremony") {
	

if(!$_POST["id"]) { $error .= "Missing Ceremony Id<br>"; }
	
	
	
if($error) {
$get_template->topHTML();
$get_template->errorHTML("Delete Ceremony","Oops!","$error","Link","oos/update-ceremony-reception.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_ceremonies,"deleted='Yes'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_receptions,"deleted='Yes'","ceremony_id='".addslashes($_POST["id"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Delete Ceremony/Reception (".addslashes($_POST["ceremony_name"]).")','".$time."','','".addslashes($_POST["island_id"])."'");

$get_template->topHTML();
?>
<h1>Ceremony Deleted</h1>

<p>The ceremony has now been deleted</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-ceremony-reception.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} elseif($_POST["action"] == "Update") {
	
if(!$_POST["ceremony_name"]) { $error .= "Missing Ceremony Name<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Ceremony / Reception","Oops!","$error","Link","oos/update-ceremony-reception.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_ceremonies,"ceremony_name='".addslashes($_POST["ceremony_name"])."', notes='".addslashes($_POST["ceremony_note"])."'","id='".addslashes($_POST["id"])."'");

//$sql_command->delete($database_receptions,"ceremony_id='".addslashes($_POST["id"])."'");




$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Update Ceremony/Reception (".addslashes($_POST["ceremony_name"]).")','".$time."','','".addslashes($_POST["island_id"])."'");



$get_template->topHTML();
?>
<h1>Ceremony Updated</h1>

<p>The ceremony has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$menu_result = $sql_command->select($database_ceremonies,"*","WHERE id='".addslashes($_POST["id"])."'");
$menu_record = $sql_command->result($menu_result);

$add_header .= "<script language=\"javascript\" type=\"text/javascript\">
function deletechecked() {
	var answer = confirm(\"Confirm Delete\")
    if (answer){ document.messages.submit(); }
    return false;  
}  
</script>";
$get_template->topHTML();
?>
<h1>Update Ceremony</h1>


    
<form action="<?php echo $site_url; ?>/oos/update-ceremony-reception.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />


<div style="float:left; width:160px; margin:5px;"><b>Ceremony Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="ceremony_name" style="width:240px;" value="<?php echo stripslashes($menu_record[2]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Ceremony Note</b></div>
<div style="float:left; margin:5px;">
<textarea name="ceremony_note" id="ceremony_note" class="the_editor_min"><?php echo stripslashes($menu_record[4]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'ceremony_note',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
						on :
		{
			instanceReady : function( ev )
			{
				this.dataProcessor.writer.setRules( 'p',
					{
						indent : false,
						breakBeforeOpen : true,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : true
					});
			}
		}
					});
</script>
</div>
<div style="clear:left;"></div>
<p>* - Required Fields</p>



<div style="float:left; width:100px; margin:5px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left;  margin:5px; margin-left:200px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-ceremony-reception.php'"></div>
<div style="float:right;  margin:5px;"><input type="submit" name="action" value="Delete Ceremony" onclick="return deletechecked();"/></div>
<div style="clear:borth;"></div>


</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "island") {
	

$menu_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE  deleted='No' and island_id='".addslashes($_GET["island_id"])."' ORDER BY ceremony_name");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {	
$list .= "<option value=\"".stripslashes($menu_record[0])."\" style=\"font-size:11px;\">".stripslashes($menu_record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Ceremony / Reception</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-ceremony-reception.php" method="POST">
<input type="hidden" name="action" value="View" />
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />

<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?php } else { ?>
<p>Please make sure you select an option</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and  parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}


$get_template->topHTML();
?>
<h1>Update Ceremony / Reception</h1>

<?php if($nav_list) { ?>
<form action="<?php echo $site_url; ?>/oos/update-ceremony-reception.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?php } else { ?>
<p>Please make sure you select an option</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} 


?>