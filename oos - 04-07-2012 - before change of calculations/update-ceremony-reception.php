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



if($_POST["action"] == "Update") {
	
if(!$_POST["ceremony_name"]) { $error .= "Missing Ceremony Name<br>"; }

if(!$_POST["reception_1"]) { $error .= "Missing Reception Name<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Ceremony / Reception","Oops!","$error","Link","oos/update-ceremony-reception.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_ceremonies,"ceremony_name='".addslashes($_POST["ceremony_name"])."'","id='".addslashes($_POST["id"])."'");

$sql_command->delete($database_receptions,"ceremony_id='".addslashes($_POST["id"])."'");


$cols = "island_id,ceremony_id,reception_name,ranking";

$current_row = 1;
$end_value = $_POST["theValue"] + 1;

while($current_row < $end_value) {
$reception_name = "reception_".$current_row;

if($_POST[$reception_name]) { 
$values = "'".addslashes($_POST["island_id"])."',
'".addslashes($_POST["id"])."',
'".addslashes($_POST[$reception_name])."',
''";
$sql_command->insert($database_receptions,$cols,$values);														  
}

$current_row++;
}




$get_template->topHTML();
?>
<h1>Ceremoyn and Reception Updated</h1>

<p>The ceremony and reception have now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$menu_result = $sql_command->select($database_ceremonies,"*","WHERE id='".addslashes($_POST["id"])."'");
$menu_record = $sql_command->result($menu_result);


$get_template->topHTML();
?>
<h1>Add Ceremony / Reception</h1>

<script language="JavaScript">
function addElement() {
  var ni = document.getElementById('add_item');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:160px; margin:5px;"><b>Reception Name ' + num +'</b></div>'
+ '<div style="float:left; margin:5px;"><input type="text" name="reception_' + num +'" style="width:240px;"/></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>
    
<form action="<?php echo $site_url; ?>/oos/update-ceremony-reception.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />


<div style="float:left; width:160px; margin:5px;"><b>Ceremony Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="ceremony_name" style="width:240px;" value="<?php echo stripslashes($menu_record[2]); ?>"/> *</div>
<div style="clear:left;"></div>

<p><hr /></p>

<?
	
$total_items = $sql_command->count_rows($database_receptions,"id","ceremony_id='".addslashes($_POST["id"])."'");
$total_items++;

$option_result = $sql_command->select($database_receptions,"*","WHERE ceremony_id='".addslashes($_POST["id"])."' ORDER BY reception_name");
$option_record = $sql_command->results($option_result);

$current = 1;

while($current < $total_items) { 
$getid = $current - 1;
?>
<div style="float:left; width:160px; margin:5px;"><b>Reception Name</b></div>
<div style="float:left; margin:5px;"><input type="text" name="reception_<?php echo $current; ?>" style="width:240px;" value="<?php echo stripslashes($option_record[$getid][3]); ?>"/> *</div>
<div style="clear:left;"></div>
<?php 
$current++;
} ?>
<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>

<p>* - Required Fields</p>


<input type="hidden" value="<?php echo $current -1; ?>" id="theValue" name="theValue">

<div style="float:left; width:100px; margin:5px;"><input type="submit" name="action" value="Update"></div>
<div style="float:left;  margin:5px; margin-left:200px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-ceremony-reception.php'"></div>
<div style="float:right;  margin:5px;"><input type="button" value="Add Another Reception" onclick="addElement();"></div>
<div style="clear:borth;"></div>


</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} elseif($_GET["a"] == "island") {
	

$menu_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE island_id='".addslashes($_GET["island_id"])."' ORDER BY ceremony_name");
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
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
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