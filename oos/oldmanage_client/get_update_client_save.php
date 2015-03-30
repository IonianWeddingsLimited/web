<?

if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Lastname<br>"; }
if(!$_POST["groom_firstname"]) { $error .= "Missing Groom Firstname<br>"; }
if(!$_POST["groom_surname"]) { $error .= "Missing Groom Lastname<br>"; }
if(!$_POST["email"]) { $error .= "Missing Email<br>"; }
if(!$_POST["tel"]) { $error .= "Missing Tel<br>"; }
if(!$_POST["address"]) { $error .= "Missing Address<br>"; }
if(!$_POST["town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["county"]) { $error .= "Missing County<br>"; }
if(!$_POST["country"]) { $error .= "Missing Country<br>"; }
if(!$_POST["postcode"]) { $error .= "Missing Postcode<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=view&id=".$_POST["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

list($day2,$month2,$year2) = explode("-",$_POST["dob"]);
$savedate2 = mktime(0, 0, 0, $month2, $day2, $year2);

list($day,$month,$year) = explode("-",$_POST["wedding_date"]);
$savedate = mktime(0, 0, 0, $month, $day, $year);

list($day3,$month3,$year3) = explode("-",$_POST["groom_dob"]);
$savedate3 = mktime(0, 0, 0, $month3, $day3, $year3);

$sql_command->update($database_clients,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"firstname='".addslashes($_POST["firstname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"lastname='".addslashes($_POST["lastname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_1='".addslashes($_POST["address"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_2='".addslashes($_POST["address2"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"address_3='".addslashes($_POST["address3"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"town='".addslashes($_POST["town"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"county='".addslashes($_POST["county"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"country='".addslashes($_POST["country"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"postcode='".addslashes($_POST["postcode"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"email='".addslashes($_POST["email"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"tel='".addslashes($_POST["tel"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"mob='".addslashes($_POST["mob"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"fax='".addslashes($_POST["fax"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"dob='".addslashes($savedate2)."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"mailing_list='".addslashes($_POST["mailinglist"])."'","id='".addslashes($_POST["client_id"])."'");

$sql_command->update($database_clients,"groom_title='".addslashes($_POST["groom_title"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_firstname='".addslashes($_POST["groom_firstname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_surname='".addslashes($_POST["groom_surname"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_dob='".addslashes($savedate3)."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"wedding_time='".addslashes($_POST["wedding_time"])."'","id='".addslashes($_POST["client_id"])."'");


$sql_command->update($database_clients,"wedding_date='".addslashes($savedate)."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"destination='".addslashes($_POST["destination"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"iwcuid='".addslashes($_POST["iwcuid"])."'","id='".addslashes($_POST["client_id"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Client Details Updated','".$time."'");

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<p>The client has now been updated</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

?>