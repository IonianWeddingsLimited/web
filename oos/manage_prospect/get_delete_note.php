<?

$sql_command->delete($database_notes,"client_id='".addslashes($_POST["client_id"])."' and id='".addslashes($_POST["note_id"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Note Deleted','".$time."'");

	
$get_template->topHTML();
?>
<h1>Manage Prospect</h1>

<?php echo $menu_line; ?>

<p>The note has now been deleted</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></p>

<?
$get_template->bottomHTML();
$sql_command->close();
?>