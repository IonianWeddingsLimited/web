<?


$result = $sql_command->select($database_notes,"id,subject,timestamp","WHERE client_id='".addslashes($_POST["client_id"])."' ORDER BY timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:11px;\"> ".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Manage Clients</h1>

<?php echo $menu_line; ?>

<h2>View Notes</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="post">
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<p><select name="note_id" size="20"  style="width:700px;"><?php echo $list; ?></select></p>
<p><input type="submit" name="action" value="View Note"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>