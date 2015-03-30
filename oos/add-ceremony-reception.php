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



if($_POST["action"] == "Add Ceremony") {

if(!$_POST["ceremony_name"]) { $error .= "Missing Ceremony Name<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Ceremony","Oops!","$error","Link","oos/add-ceremony-reception.php");
$get_template->bottomHTML();
$sql_command->close();
}

$cols = "island_id,ceremony_name,deleted,notes";

$values = "'".addslashes($_POST["island_id"])."','".addslashes($_POST["ceremony_name"])."','No','".addslashes($_POST["ceremony_note"])."'";

$sql_command->insert($database_ceremonies,$cols,$values);
$maxid = $sql_command->maxid($database_ceremonies,"id");


$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Ceremony Added (".addslashes($_POST["ceremony_name"]).")','".$time."','','".addslashes($_POST["island_id"])."'");


$get_template->topHTML();
?>
<h1>Ceremony And Reception Added</h1>

<p>The ceremony has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
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
<h1>Add Ceremony</h1>
<form action="<?php echo $site_url; ?>/oos/add-ceremony-reception.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>Island</b></div>
<div style="float:left; margin:5px;"><select name="island_id" size="10" style="width:300px;">
<?php echo $nav_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Ceremony Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="ceremony_name" style="width:350px;"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Ceremony Note</b></div>
<div style="float:left; margin:5px;">
<textarea name="ceremony_note" id="ceremony_note" class="the_editor_min"></textarea><script type="text/javascript">
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

<p style="margin-top:10px;"><input type="submit" name="action" value="Add Ceremony"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} 


?>