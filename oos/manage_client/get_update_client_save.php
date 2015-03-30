<?

if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
if(!$_POST["password"]) { $error .= "Missing Password<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Lastname<br>"; }
if(!$_POST["groom_firstname"]) { $error .= "Missing Groom Firstname<br>"; }
if(!$_POST["groom_surname"]) { $error .= "Missing Groom Lastname<br>"; }
if(!$_POST["email"]) { $error .= "Missing Bride Email<br>"; }
if(!$_POST["tel"]) { $error .= "Missing Bride Tel<br>"; }
if(!$_POST["groom_email"]) { $error .= "Missing Groom Email<br>"; }
if(!$_POST["groom_tel"]) { $error .= "Missing Groom Tel<br>"; }
if(!$_POST["address"]) { $error .= "Missing Address<br>"; }
if(!$_POST["town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["county"]) { $error .= "Missing County<br>"; }
if(!$_POST["country"]) { $error .= "Missing Country<br>"; }
if(!$_POST["postcode"]) { $error .= "Missing Postcode<br>"; }
//if(!$_POST["tandc"]) { $error .= "You must agree to our Terms and conditions<br>"; }
//if(!$_POST["over18"]) { $error .= "You must be over 18 years of age<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=view&id=".$_POST["client_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

if($_POST["dob"]) {
list($day2,$month2,$year2) = explode("-",$_POST["dob"]);
$savedate2 = mktime(0, 0, 0, $month2, $day2, $year2);
}

if($_POST["wedding_date"]) {
list($day,$month,$year) = explode("-",$_POST["wedding_date"]);
$savedate = mktime(0, 0, 0, $month, $day, $year);
}

if($_POST["groom_dob"]) {
list($day3,$month3,$year3) = explode("-",$_POST["groom_dob"]);
$savedate3 = mktime(0, 0, 0, $month3, $day3, $year3);
}

$sql_command->update($database_clients,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"cli_pass='".addslashes($_POST["password"])."'","id='".addslashes($_POST["client_id"])."'");
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

$sql_command->update($database_clients,"groom_email='".addslashes($_POST["groom_email"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_tel='".addslashes($_POST["groom_tel"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_mob='".addslashes($_POST["groom_mob"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_fax='".addslashes($_POST["groom_fax"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"planner_id='".addslashes($_POST["planner_id"])."'","id='".addslashes($_POST["client_id"])."'");

$sql_command->update($database_clients,"type_of_ceremony='".addslashes($_POST["type_of_ceremony"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_country='".addslashes($_POST["bride_country"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_country='".addslashes($_POST["groom_country"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_passport='".addslashes($_POST["bride_passport"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_passport='".addslashes($_POST["groom_passport"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_birth_certificate='".addslashes($_POST["bride_birth_certificate"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_birth_certificate='".addslashes($_POST["groom_birth_certificate"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_divorced='".addslashes($_POST["bride_divorced"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_divorced='".addslashes($_POST["groom_divorced"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_adopted='".addslashes($_POST["bride_adopted"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_adopted='".addslashes($_POST["groom_adopted"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_widowed='".addslashes($_POST["bride_widowed"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_widowed='".addslashes($_POST["groom_widowed"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_deed_poll='".addslashes($_POST["bride_deed_poll"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_deed_poll='".addslashes($_POST["groom_deed_poll"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_baptised='".addslashes($_POST["bride_baptised"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_baptised='".addslashes($_POST["groom_baptised"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"bride_baptised_certificate='".addslashes($_POST["bride_baptised_certificate"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"groom_baptised_certificate='".addslashes($_POST["groom_baptised_certificate"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"tandc='".addslashes($_POST["tandc"])."'","id='".addslashes($_POST["client_id"])."'");
$sql_command->update($database_clients,"over18='".addslashes($_POST["over18"])."'","id='".addslashes($_POST["client_id"])."'");

//if ($the_username =="u1") {
//	$ctype_q = $sql_command->count_nrow("clients_options","option_value","client_id = '".addslashes($_POST["client_id"])."' and client_option = 'clienttype'");
//	$ctype = ($aboutus_q>0) ? $sql_command->update("clients_options","option_value='".addslashes($_POST["ctype"])."'","client_id = '".addslashes($_POST["client_id"])."' and client_option = 'clienttype'") : $sql_command->insert("clients_options","client_id,client_option,option_value","'".addslashes($_POST["client_id"])."','clienttype','".addslashes($_POST["ctype"])."'");
//}

$aboutus_q = $sql_command->count_nrow("clients_options","option_value","client_id = '".addslashes($_POST["client_id"])."' and client_option = 'hearaboutus'");
$aboutus = ($aboutus_q>0) ? $sql_command->update("clients_options","option_value='".addslashes($_POST["hearabout"])."'","client_id = '".addslashes($_POST["client_id"])."' and client_option = 'hearaboutus'") : $sql_command->insert("clients_options","client_id,client_option,option_value","'".addslashes($_POST["client_id"])."','hearaboutus','".addslashes($_POST["hearabout"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Client Details Updated','".$time."','','".addslashes($_POST["destination"])."'");

include("convertclient.php");

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php //echo $menu_line; ?>

<p>The client has now been updated</p>
<?
	if ($_POST["ctype"]=="1") {
		$updateurl	=	"manage-prospect.php";
	} else {
		$updateurl	=	"manage-client.php";
	}
?>
<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/<?php echo $updateurl; ?>?a=history&id=<?php echo $_POST["client_id"]; ?>'"> <input type="button" name="" value="View" onclick="window.location='<?php echo $site_url; ?>/oos/<?php echo $updateurl; ?>?a=history&id=<?php echo $_POST["client_id"]; ?>'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

?>