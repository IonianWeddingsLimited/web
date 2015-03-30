<?

if(!$_POST["subject"]) { $error .= "Missing Country<br>"; }
if(!$_POST["note"]) { $error .= "Missing Note<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=view-note");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_notes,"subject='".addslashes($_POST["subject"])."'","client_id='".addslashes($_POST["client_id"])."' and id='".addslashes($_POST["note_id"])."'");
$sql_command->update($database_notes,"note='".addslashes($_POST["note"])."'","client_id='".addslashes($_POST["client_id"])."' and id='".addslashes($_POST["note_id"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Note Updated (".addslashes($_POST["subject"]).")','".$time."'");

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<p>The note has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();
?>