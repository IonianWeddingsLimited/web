<?


$get_template->topHTML();
?>
<h1>Manage Clients</h1>

<?php echo $menu_line; ?>

<h2>Add Note</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="post">
<input type="hidden" name="client_id" value="<?php echo $_POST["client_id"]; ?>" />
<div style="float:left; width:120px; margin:5px;"><strong>Subject</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="subject" style="width:250px" value="<?php echo $_SESSION["subject"]; ?>"/></div>
<div style="clear:left;"></div>
<p><textarea name="note" id="the_editor"><?php echo $_SESSION["note"]; ?></textarea></p>
<?php echo $admin_editor; ?>

<p><input type="submit" name="action" value="Add Note"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>