<?


	if(!$_POST["client_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","Missing Prospect ID","Link","oos/manage-prospect.php");
$get_template->bottomHTML();
$sql_command->close();
}

if(!$_POST["note_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","Please select a note","Link","oos/manage-prospect.php?a=view-notes&id=".$_POST["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

$result = $sql_command->select("$database_notes,$database_users","$database_notes.id,
							   $database_notes.subject,
							   $database_notes.note,
							   $database_notes.timestamp,
							   $database_users.username","WHERE $database_notes.client_id='".addslashes($_POST["client_id"])."' and 
							   $database_notes.id='".addslashes($_POST["note_id"])."' and  
							   $database_notes.user_id=$database_users.id ");
$record = $sql_command->result($result);


 
$added = date("d-m-Y",$record[3]);

$get_template->topHTML();
?>
<h1>Manage Prospects</h1>

<?php echo $menu_line; ?>

<h2>View Note</h2>

<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="post">
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<input type="hidden" name="note_id" value="<?php echo $_POST["note_id"]; ?>" />
<div style="float:left; width:120px; margin:5px;"><strong>Added</strong></div>
<div style="float:left; margin:5px;"><?php echo $added; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Added By</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[4]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Subject</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="subject" style="width:250px" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<p><textarea name="note" id="the_editor"><?php echo stripslashes($record[2]); ?></textarea></p>
<?php echo $admin_editor; ?>


<div style="float:left; width:120px; margin:5px;"><input type="submit" name="action" value="Update Note"></div>
<div style="float:left; margin-top:10px; margin-left:200px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=view-notes&id=<?php echo $_POST["client_id"]; ?>'"></div>
</form>
<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="post">
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<input type="hidden" name="note_id" value="<?php echo $_POST["note_id"]; ?>" />
<div style="float:right; margin:5px;"><input type="submit" name="action" value="Delete Note"></div>
<div style="clear:both;"></div>




</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>