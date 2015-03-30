 <?

	if(!$_GET["id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","Missing Client ID","Link","oos/manage-client.php");
$get_template->bottomHTML();
$sql_command->close();
}



$result = $sql_command->select("$database_notes,$database_users","$database_notes.id,
							   $database_notes.subject,
							   $database_notes.timestamp,
							   $database_users.username","WHERE $database_notes.client_id='".addslashes($_POST["client_id"])."' and  
							   $database_notes.user_id=$database_users.id 
							   ORDER BY $database_notes.timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
$date = date("d-m-Y h:i A",$record[2]);
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:11px;\">".$date." - ".stripslashes($record[3])." - ".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Manage Clients</h1>

<?php echo $menu_line; ?>

<h2>View Notes</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="post">
<input type="hidden" name="action" value="View Note">
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<p><select name="note_id" size="20"  style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select></p>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="View Note"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>