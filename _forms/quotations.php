<?
include("_includes/settings.php");
include("_includes/function.smtp.php");

if (isset($_POST["run"])){
	
	if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
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
	if(!$_POST["tandc"]) { $error .= "You must agree to our Terms and conditions<br>"; }
	if(!$_POST["over18"]) { $error .= "You must be over 18 years of age<br>"; }
	
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
	
	if ($error == "") {
		$sql_command->update($database_clients,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["client_id"])."'");
		//$sql_command->update($database_clients,"cli_pass='".addslashes($_POST["password"])."'","id='".addslashes($_POST["client_id"])."'");
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
		//$sql_command->update($database_clients,"fax='".addslashes($_POST["fax"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"dob='".addslashes($savedate2)."'","id='".addslashes($_POST["client_id"])."'");
		//$sql_command->update($database_clients,"mailing_list='".addslashes($_POST["mailinglist"])."'","id='".addslashes($_POST["client_id"])."'");
		
		$sql_command->update($database_clients,"groom_title='".addslashes($_POST["groom_title"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_firstname='".addslashes($_POST["groom_firstname"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_surname='".addslashes($_POST["groom_surname"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_dob='".addslashes($savedate3)."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"wedding_time='".addslashes($_POST["wedding_time"])."'","id='".addslashes($_POST["client_id"])."'");
		
		
		$sql_command->update($database_clients,"wedding_date='".addslashes($savedate)."'","id='".addslashes($_POST["client_id"])."'");
		//$sql_command->update($database_clients,"destination='".addslashes($_POST["destination"])."'","id='".addslashes($_POST["client_id"])."'");
		//$sql_command->update($database_clients,"iwcuid='".addslashes($_POST["iwcuid"])."'","id='".addslashes($_POST["client_id"])."'");
		
		$sql_command->update($database_clients,"groom_email='".addslashes($_POST["groom_email"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_tel='".addslashes($_POST["groom_tel"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_mob='".addslashes($_POST["groom_mob"])."'","id='".addslashes($_POST["client_id"])."'");
		$sql_command->update($database_clients,"groom_fax='".addslashes($_POST["groom_fax"])."'","id='".addslashes($_POST["client_id"])."'");
		//$sql_command->update($database_clients,"planner_id='".addslashes($_POST["planner_id"])."'","id='".addslashes($_POST["client_id"])."'");
		
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
		
		$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
		$message .="<tr>";
		$message .="<td colspan=\"2\" style=\"padding: 20px 0px 20px 0px;\">";
		$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
		$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
		$message .="</td>";
		$message .="<td colspan=\"2\" style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
		$message .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
		$message .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
		$message .="</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td colspan=\"4\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
		$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Booking Questionnaire</h1>";
		$message .="<br />";
		$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear ".$_POST["firstname"].",</h2>";
		$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\"><br /> Thank you for completing the booking questionnaire.</p>";
		$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-top: solid 1px #c08827; border-left: solid 1px #c08827; margin:0 auto;\">";
		$message .="<tr>";
		$message .="<th style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Question</th>";
		$message .="<th style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Bride</th>";
		$message .="<th style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Groom</th>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Title</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["title"]."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["groom_title"]."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">First name</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["firstname"]."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["groom_firstname"]."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Last name</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["lastname"]."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".$_POST["groom_surname"]."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Date of Birth</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["dob"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_dob"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Nationality</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_country"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_country"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Email Address</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["email"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_email"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Telephone</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["tel"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_tel"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Mobile</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["mob"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_mob"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Address line 1</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["address"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Address line 2</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["address2"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Address line 3</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["address3"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Town</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["town"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">County</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["county"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Country</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["country"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Postcode</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["postcode"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Wedding Date &amp; Time</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes(date('d/m/Y',$savedate))."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["wedding_time"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Type of ceremony</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["type_of_ceremony"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Do you have a valid 10 year passport not due to expire within 6 months at the time of travel?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_passport"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_passport"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Do you have a full Birth Certificate (with names of both parents)?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_birth_certificate"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_birth_certificate"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Are you divorced?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_divorced"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_divorced"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Are you adopted?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_adopted"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_adopted"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Have you been widowed?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_widowed"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_widowed"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Have you changed your name by Deed Poll?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_deed_poll"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_deed_poll"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Have you been baptised? (For religious ceremonies Only)</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_baptised"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_baptised"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">Do you have your baptism certificate?</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["bride_baptised_certificate"])."</td>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["groom_baptised_certificate"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">I have read, understood and agree to be bound by Ionian Weddings' Terms and Conditions</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["tandc"])."</td>";
		$message .="</tr>";
		$message .="<tr>";
		$message .="<td style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">I confirm that I am over 18 years of age</td>";
		$message .="<td colspan=\"2\" style=\"border-right: solid 1px #c08827; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 3px 3px 3px 3px;\">".addslashes($_POST["over18"])."</td>";
		$message .="</tr>";
		$message .="</table>";
		$message .="<br />";
		$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
		$message .="<br />";
		$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";

		//echo $message;

		function test_input($data)
		{
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}	
		
		$pename = test_input($_POST["firstname"]." ".$_POST["lastname"]);
		$pemail = test_input($_POST["email"]);
		
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 	
		
		$name = (empty($pename)) ? $name : $pename;
		$femail = (empty($pemail)) ? $femail : (!preg_match($regex,$pemail)) ? $femail : $pemail;
		
		$emailadd = "payments@ionianweddings.co.uk"; 
		$emailname = "<Ionian Weddings Ltd>";
		
		$global_from = "<{$emailadd}> \"{$emailname}\"";
		
		$mail = new smtp_email;
		$mail->Username = $smtp_email;
		$mail->Password = $smtp_password;
		$mail->Server =  $smpt_server;
		$mail->Port =  $smpt_port;
		
		
		$mail->SetFrom($smtp_email,$tagline);	// Name is optional
		$mail->AddTo($emailadd,$emailname);	// Name is optional
		
		$mail->Subject = $pename.", Your Ionian Weddings Booking Questionnaire";
		$mail->Message = $message;
		$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
		$mail->ResponseTimeout = 8;		// CMD response timeout (sec)
		
		$e_date = date("Y-m-d h:m:s");
		
		$success = $mail->Send();
	}
}
?>
<script type="application/javascript">
$(function() {
var payinvcheck = 0;
var submitenabled = false;
var payLimit = <? echo $payLimit; ?>;
var thisChk = 0;
var thisChkA = 0;
var totalA = 0;
var myForm = $("input[name='payinv[]']");
	
	$("#allinv").click(function() {
	
	for( var i=0; i < myForm.length; i++ ) {
			var thisChk = $("input[name='payinv[]']").eq(i).val();
			var thisChkA = $("#"+thisChk+"-total").val();
			var totalA = $("#cart-total").val();
			if (totalA<payLimit) {
				totalA = parseFloat(totalA,12) + parseFloat(thisChkA,12);
				$("#cart-total").val(totalA); 
			$("input[name='payinv[]']").eq(i).attr("checked","checked");
				if (totalA>=payLimit) {
					alert("There is a £"+payLimit+" payment limit, exceeding this limit will cause a part payment to be allocated to the bottom invoice. \n\n\nIf you have selected multiple invoices, please feel free to make multiple payments."); 
					$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
				}
		}
			else { break; }
		}

		payinvcheck = $("input[name='payinv[]']:checked").length;
		$('input[type="submit"][name="payinvoices"]').removeAttr('disabled');
	});

	$("#noinv").click(function() {
	$("input[name='payinv[]']:checked").removeAttr("checked");
	$("input[name='payinv[]']:disabled").removeAttr("disabled");
		$('input[type="submit"][name="payinvoices"]').attr('disabled','disabled');
		$("#cart-total").val(0); 
	});

	$("input[name='payinv[]']").change(function() {
		currentchk = payinvcheck;										
		payinvcheck = $("input[name='payinv[]']:checked").length;
		thisChk = $(this).val();
		thisChkA = $("#"+thisChk+"-total").val();
		totalA = $("#cart-total").val();
		
		if (payinvcheck>currentchk) {
			totalA = parseFloat(totalA,12) + parseFloat(thisChkA,12);
			if (totalA>=payLimit) { 
				alert("There is a £"+payLimit+" payment limit, exceeding this limit will cause a part payment to be allocated to the bottom invoice. \n\n\nIf you have selected multiple invoices, please feel free to make multiple payments."); 
				$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
			}
		}
		else {
			totalA = parseFloat(totalA,12) - parseFloat(thisChkA,12);
			if (totalA>=payLimit) { 
				$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
			}
			else {
				$("input[name='payinv[]']").removeAttr("disabled");
			}
		}
		if (payinvcheck>0) {
			switch(submitenabled) {
				case true:
					break;
				case false:
					$('input[type="submit"][name="payinvoices"]').removeAttr('disabled');
					submitenabled=true;
					break;
			}
		}
		else {
			$('input[type="submit"][name="payinvoices"]').attr('disabled','disabled');
			submitenabled=false;
		}
		$("#cart-total").val(totalA);

	});
});
</script>
<?

	require("_wopay/worldpayconfig.php"); 
	
function j_check_date($date) {
 if (strlen($date) > 10) {
  return FALSE;
 }
 else {
  $pieces = explode('/', $date);
  if (count($pieces) != 3) {
   return FALSE;
  }
  else {
   $day = $pieces[0];
   $month = $pieces[1];
   $year = $pieces[2];
   return checkdate($month, $day, $year); // this bit makes the function British - RULE BRITANNIA!!
  }
 }
}


$errorso = 0;
$errori = "";

if (isset($_POST['wedding-d'])) {
	$wdate = $_POST['wedding-d'];	
} else {
	$wdate = "DD/MM/YYYY";
}
if (isset($_POST['customer-id'])) { $cuid = $_POST['customer-id']; } else { $cuid = $level2_name; }
if ($cuid=="unset") { unset($_SESSION['wedding_date']); unset($_SESSION['password']); unset($_SESSION['clid']); $cuid = ""; } 

if ($wdate <> "DD/MM/YYYY") {
	$searchd = array('.', '-');
	$wdate = str_replace($searchd,"/",$wdate);
	$datevalid = j_check_date($wdate);
	list($day,$month,$year) = explode("/",$wdate);
	$dateval = mktime(0, 0, 0, $month, $day, $year);	
}

//if(isset($_POST['viewinvoice']) && $_POST['customer-id']=="") { $errorso++; $errori = "Please enter a valid Ionian Wedding ID."; }
if(isset($_POST['viewinvoice']) && $datevalid==false) { $errorso++; $errord = "Please enter a valid date."; }
if(isset($_POST['viewinvoice']) && $errorso==0) {
	//$iwcur = $sql_command->count_rows("clients","*","iwcuid = '" . addslashes($_POST['customer-id']) . "' AND wedding_date = '". $dateval ."' AND cli_pass = '".$_POST['password']."'");
	$iwcur = $sql_command->count_rows("clients","*","wedding_date = '". $dateval ."' AND cli_pass = '".$_POST['password']."'");
	if($iwcur>0) {
		$_SESSION['wedding_date'] = $dateval;
		$_SESSION['password'] = $_POST['password'];
	}
	else {
		$errori = "The information you provided is incorrect, please check and try again.";	
	}
}
if (isset($_SESSION['password'])) {
		$iwcu = $sql_command->select("clients","*","WHERE wedding_date = '" . addslashes($_SESSION['wedding_date']) . "' and cli_pass = '" . addslashes($_SESSION['password']) . "'and deleted='No'");

		$iwcustomer	=	$sql_command->result($iwcu);
		$clid		=	$iwcustomer[0];
		$fname		=	$iwcustomer[1] . " " . $iwcustomer[2] . " " . $iwcustomer[3];
		$cname		=	$iwcustomer[2];
		$add1		=	$iwcustomer[4];
		$add2		=	$iwcustomer[5];
		$add3		=	$iwcustomer[6];
		$town		=	$iwcustomer[7];
		$pcode		=	strtoupper($iwcustomer[10]);
		$emaila		=	$iwcustomer[11];
		$contactno	=	$iwcustomer[12];
		$wdates		=	date('d/m/Y',$iwcustomer[17]);
		$mlist		=	$iwcustomer[16];
		$mobno		=	$iwcustomer[13];
		$country	=	$iwcustomer[9];
		$tandc		=	$iwcustomer[52];
		$over18		=	$iwcustomer[53];

$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($iwcustomer[9] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}

$bride_country_result = $sql_command->select($database_country,"value","ORDER BY value");
$bride_country_row = $sql_command->results($bride_country_result);

foreach($bride_country_row as $bride_country_record) {
$current = stripslashes($bride_country_record[0]);
if($iwcustomer[34] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$iwcustomer[34] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$bride_country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}

$groom_country_result = $sql_command->select($database_country,"value","ORDER BY value");
$groom_country_row = $sql_command->results($groom_country_result);

foreach($groom_country_row as $groom_country_record) {
$current = stripslashes($groom_country_record[0]);
if($iwcustomer[35] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$iwcustomer[35] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$groom_country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}
$typeofceremony_result = $sql_command->select($database_typeofceremony,"value","ORDER BY value");
$typeofceremony_row = $sql_command->results($typeofceremony_result);

foreach($typeofceremony_row as $typeofceremony_record) {
	$current = stripslashes($typeofceremony_record[0]);
	
	if($iwcustomer[33] == $current) { 
		$selected = "selected=\"selected\""; 
	} else {
		$selected = ""; 
	}
	$typeofceremony_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}
		
		if($iwcustomer[15]) { $dob = date("d-m-Y",$iwcustomer[15]); }
		if($iwcustomer[24]) { $dob2 = date("d-m-Y",$iwcustomer[24]); }
		if($iwcustomer[17]) { $wd = date("d-m-Y",$iwcustomer[17]); }
		
		$iwct = $sql_command->select("clients_options","id, client_id, client_option, option_value","WHERE client_id = '" . addslashes($iwcustomer[0]) . "' and client_option = 'Prospect'");

		$iwclienttype = $sql_command->result($iwct);
		
		if ($iwclienttype[2]	== "Prospect") {
			$clientType			=	"prospect";
		} else {
			$clientType			=	"client";
		}

?>

<div class="pageform">
	<div class='formheader'>
		<h1>Hello <? echo $cname; ?> <span class="notme">(<a href="/quotations/unset/" title="exit" nofollow>Not you? click here</a>)</span></h1>
		<p>You are able to view your quotations below, if you have a query or a quotation is listed or is not listed below <a href="/contact-us/" title="Contact Us" target="_blank">please contact us.</a></p>
	</div>
	<? if(isset($_POST['payinvoices']) || isset($_POST['payinvoice'])) {
		} else {
			if ($tandc == "Yes") {
	?>
		<div class='formheader'><p>Thank you for completing the booking questionnaire.</p><p>You will receive a confirmation email shortly.</p></div>
	<?
			} else {
	?>
	<form action="<? echo $site_url; ?>/quotations/" id="client" method="post" name="client">
		<input type="hidden" name="run" value="true">
		<input type="hidden" name="client_id" value="<? echo $iwcustomer[0];?>">
		<div class="formheader">
			<h1>Booking Questionnaire</h1>
			<?
			echo $error;
		?>
		</div>
		<div class="formrow">
			<div class="formlabel">&nbsp;</div>
			<div class="formcolumn1">
				<h1>Bride</h1>
			</div>
			<div class="formcolumn2">
				<h1>Groom</h1>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="title">Title</label>
			<div class="formcolumn1">
				<select class="formselectshort" name="title">
					<option value="Mr" <? if($iwcustomer[1] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
					<option value="Miss" <? if($iwcustomer[1] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
					<option value="Ms" <? if($iwcustomer[1] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
					<option value="Dr" <? if($iwcustomer[1] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
				</select>
			</div>
			<div class="formcolumn2">
				<select class="formselectshort" name="groom_title">
					<option value="Mr" <? if($iwcustomer[21] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
					<option value="Miss" <? if($iwcustomer[21] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
					<option value="Ms" <? if($iwcustomer[21] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
					<option value="Dr" <? if($iwcustomer[21] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="firstname">First Name</label>
			<div class="formcolumn1">
				<input class="formtextfieldshort" type="text" name="firstname" value="<? echo stripslashes($iwcustomer[2]); ?>"/>
				* </div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="groom_firstname" value="<? echo stripslashes($iwcustomer[22]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="lastname">Last Name</label>
			<div class="formcolumn1">
				<input class="formtextfieldshort" type="text" name="lastname" value="<? echo stripslashes($iwcustomer[3]); ?>"/>
				* </div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="groom_surname" value="<? echo stripslashes($iwcustomer[23]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="dob">Date of Birth (Format DD-MM-YYYY)</label>
			<div class="formcolumn1">
				<input class="formtextfieldcalendar" type="text" name="dob" id="dob"  value="<? echo $dob; ?>"/>
				<script language="JavaScript">
					new tcal ({
						// form name
						'formname': 'client',
						// input name
						'controlname': 'dob'
					});
				</script> 
			</div>
			<div class="formcolumn2">
				<input class="formtextfieldcalendar" type="text" name="groom_dob" id="groom_dob"  value="<? echo $dob2; ?>"/>
				<script language="JavaScript">
					new tcal ({
						// form name
						'formname': 'client',
						// input name
						'controlname': 'groom_dob'
					});
				</script> 
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_country">Nationality</label>
			<div class="formcolumn1">
				<select class="formselectshort" id="bride_country" name="bride_country">
					<? echo $bride_country_list; ?>
				</select>
			</div>
			<div class="formcolumn2">
				<select class="formselectshort" id="groom_country" name="groom_country">
					<? echo $groom_country_list; ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formheader">
			<h1>Contact details</h1>
		</div>
		<div class="formrow">
			<label class="formlabel" for="email">Email Address</label>
			<div class="formcolumn1">
				<input class="formtextfieldshort" type="text" name="email" value="<? echo stripslashes($iwcustomer[11]); ?>"/>
				* </div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="groom_email" value="<? echo stripslashes($iwcustomer[26]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="tel">Telephone</label>
			<div class="formcolumn1">
				<input class="formtextfieldshort" type="text" name="tel" value="<? echo stripslashes($iwcustomer[12]); ?>"/>
				* </div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="groom_tel" value="<? echo stripslashes($iwcustomer[27]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="mob">Mobile</label>
			<div class="formcolumn1">
				<input class="formtextfieldshort" type="text" name="mob" value="<? echo stripslashes($iwcustomer[13]); ?>"/>
			</div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="groom_mob" value="<? echo stripslashes($iwcustomer[28]); ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formheader">
			<h1>Address details</h1>
		</div>
		<div class="formrow">
			<label class="formlabel" for="address">Address 1</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="address" value="<? echo stripslashes($iwcustomer[4]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="address2">Address 2</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="address2" value="<? echo stripslashes($iwcustomer[5]); ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="address3">Address 3</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="address3" value="<? echo stripslashes($iwcustomer[6]); ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="town">Town</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="town" value="<? echo stripslashes($iwcustomer[7]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="county">County</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="county" value="<? echo stripslashes($iwcustomer[8]); ?>"/>
				* </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="country">Country</label>
			<div class="formelement">
				<select class="formselectshort" id="country" name="country">
					<? echo $country_list; ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="postcode">Postcode</label>
			<div class="formelement">
				<input class="formtextfieldshort" type="text" name="postcode" value="<? echo stripslashes($iwcustomer[10]); ?>"/>
				*</div>
			<div class="clear"></div>
		</div>
		<div class="formheader">
			<h1>Booking details</h1>
		</div>
		<div class="formrow">
			<label class="formlabel" for="wedding_date">Wedding Date &amp; Time</label>
			<div class="formcolumn1">
				<input class="formtextfieldcalendar" type="text" name="wedding_date" id="wedding_date" value="<? echo stripslashes($wd); ?>"/>
				<script language="JavaScript">
					new tcal ({
						// form name
						'formname': 'client',
						// input name
						'controlname': 'wedding_date'
					});
				</script> 
			</div>
			<div class="formcolumn2">
				<input class="formtextfieldshort" type="text" name="wedding_time" value="<? echo stripslashes($iwcustomer[25]); ?>"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="type_of_ceremony">Type of Ceremony</label>
			<div class="formelement">
				<select class="formselectshort" id="type_of_ceremony" name="type_of_ceremony">
					<option value="">Select type of ceremony...</option>
					<?
						echo $typeofceremony_list;
					?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<div class="formlabel">&nbsp;</div>
			<div class="formcolumn1">
				<h1>Bride</h1>
			</div>
			<div class="formcolumn2">
				<h1>Groom</h1>
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_passport">Do you have a valid 10 year passport not due to expire within 6 months at the time of travel?</label>
			<div class="formcolumn1">
				<input name="bride_passport" type="radio" <? if($iwcustomer[36] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_passport" type="radio" <? if($iwcustomer[36] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_passport" type="radio" <? if($iwcustomer[37] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_passport" type="radio" <? if($iwcustomer[37] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_birth_certificate">Do you have a full Birth Certificate (with names of both parents)?</label>
			<div class="formcolumn1">
				<input name="bride_birth_certificate" type="radio" <? if($iwcustomer[38] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_birth_certificate" type="radio" <? if($iwcustomer[38] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_birth_certificate" type="radio" <? if($iwcustomer[39] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_birth_certificate" type="radio" <? if($iwcustomer[39] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_divorced">Are you divorced?</label>
			<div class="formcolumn1">
				<input name="bride_divorced" type="radio" <? if($iwcustomer[40] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_divorced" type="radio" <? if($iwcustomer[40] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_divorced" type="radio" <? if($iwcustomer[41] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_divorced" type="radio" <? if($iwcustomer[41] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<div class="clear"></div>
			<label class="formlabel" for="bride_adopted">Are you adopted?</label>
			<div class="formcolumn1">
				<input name="bride_adopted" type="radio" <? if($iwcustomer[42] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_adopted" type="radio" <? if($iwcustomer[42] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_adopted" type="radio" <? if($iwcustomer[43] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_adopted" type="radio" <? if($iwcustomer[43] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_widowed">Have you been widowed?</label>
			<div class="formcolumn1">
				<input name="bride_widowed" type="radio" <? if($iwcustomer[44] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_widowed" type="radio" <? if($iwcustomer[44] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_widowed" type="radio" <? if($iwcustomer[45] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_widowed" type="radio" <? if($iwcustomer[45] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_deed_poll">Have you changed your name by Deed Poll?</label>
			<div class="formcolumn1">
				<input name="bride_deed_poll" type="radio" <? if($iwcustomer[46] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_deed_poll" type="radio" <? if($iwcustomer[46] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_deed_poll" type="radio" <? if($iwcustomer[47] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_deed_poll" type="radio" <? if($iwcustomer[47] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_baptised">Have you been baptised? (For religious ceremonies Only)</label>
			<div class="formcolumn1">
				<input name="bride_baptised" type="radio" <? if($iwcustomer[48] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_baptised" type="radio" <? if($iwcustomer[48] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_baptised" type="radio" <? if($iwcustomer[49] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_baptised" type="radio" <? if($iwcustomer[49] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="bride_baptised_certificate">Do you have your baptism certificate?</label>
			<div class="formcolumn1">
				<input name="bride_baptised_certificate" type="radio" <? if($iwcustomer[50] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="bride_baptised_certificate" type="radio" <? if($iwcustomer[50] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="formcolumn2">
				<input name="groom_baptised_certificate" type="radio" <? if($iwcustomer[51] == "Yes") { echo "checked"; } ?> value="Yes" />
				Yes
				<input name="groom_baptised_certificate" type="radio" <? if($iwcustomer[51] == "No") { echo "checked"; } ?> value="No" />
				No </div>
			<div class="clear"></div>
		</div>
		<div class="formheader">
			<h1>Please tick the following boxes</h1>
		</div>
		<div class="formrow">
			<label class="formlabel" for="tandc">I have read, understood and agree to be bound by Ionian Weddings' Terms and Conditions</label>
			<div class="formcolumn1">
				<input name="tandc" type="checkbox" <? if($iwcustomer[52] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
				You can read them by clicking <a href="http://www.ionianweddings.co.uk/terms-and-conditions/" target="_blank" style="color:#c08827;">here</a> </div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="over18">I confirm that I am over 18 years of age</label>
			<div class="formcolumn1">
				<input name="over18" type="checkbox" <? if($iwcustomer[53] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="formrow">
			<label class="formlabel" for="client_submit">&nbsp;</label>
			<div class="formelement">
				<input class="formreset" id="client_submit" name="client_submit" type="submit" value="Update Details" />
				<input class="formreset" id="client_reset" name="client_reset" type="reset" value="Reset" />
			</div>
			<div class="clear"></div>
		</div>
	</form>
	<?
			}
		}
		
if(isset($_POST['payinvoices']) || isset($_POST['payinvoice'])) {

	$desc=""; 
	if(isset($_POST['payinvoice'])) {
		$filterva = array(",","£","$","€"," ","-");
		$p = $_POST['payinvoice'];
		$link = "pdf_quotation";
		$totaltopay=array();
		$list="";
		$dateline = $_POST[$p.'-date'];
		$itype = $_POST[$p.'-type'];
		$itotal = str_replace($filterva,"",$itotal);
		$itotal = "£ ".number_format($_POST[$p.'-total'],2);
		$itotals = $_POST[$p.'-total'];
		$iorderid = $_POST[$p.'-orderid'];

			$itotals = str_replace($filterva,"",$itotals);
//			$amount = str_replace($filterva,"",$amount);


		$desc .= "Invoice ".$p.":".$iorderid.":".$itotals;
		$list .=	"<div class=\"formlisttr\">
					<div class=\"formlisttd\">".$p."</div>
					<div class=\"formlisttd\">".$dateline."</div>
					<div class=\"formlisttd\">
						<a href=\"$site_url/".$link.".php?invoice=".$p."\" target=\"_blank\">Click here</a>
					</div>
					<div id=\"etotal\" data-desc=\"".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2))."\" data-amount=\"".str_replace($filterva,"",number_format($itotals,2))."\" class=\"formlisttd\">".$itotal."</div>
					</div>";

		$totaltopay[$i] = $itotals;		
		
	} else {
		$payinv = $_POST['payinv'];
		$link = "pdf_quotation";
		$totaltopay=array();
		$list="";
		$i=0;
		$newarray = implode(",", $payinv);
		
		$filterva = array(",","£","$","€"," ","-");
		foreach ($payinv as $p_amount) {
			$pay_a[$p_amount] = number_format($_POST[$p_amount.'-total'],2);
		}
		$total_pay = str_replace($filterva,"",number_format(($payLimit - array_sum($pay_a)),2));
		
		$filterinv = end(array_filter($pay_a,function ($value) use($total_pay) { return ($value >= $total_pay); } ));
		
		foreach ($payinv as $p) {
			$dateline = $_POST[$p.'-date'];
			$itype = $_POST[$p.'-type'];
			

			$itotal = "£ ".number_format($_POST[$p.'-total'],2);
			$itotals = str_replace($filterva,"",$itotal);
			$iorderid = $_POST[$p.'-orderid'];

	
		//	$itotal = str_replace($filterva,"",$itotal);
			$itotals = str_replace($filterva,"",$itotals);
		//	$amount = str_replace($filterva,"",$amount);
			$paycheck = $sql_command->count_nrow("$database_invoice_history",
												"*",
												"invoice_id IN (".$newarray.")
												and order_id='".addslashes($iorderid)."' 
												and currency='Euro' and item_type='Package'");

			
//			$paycheck = $sql_command->count_rows("$database_customer_invoices","*","$database_customer_invoices.order_id='".$iorderid."' AND $database_customer_invoices.id IN (".$newarray.") AND $database_customer_invoices.id!='".addslashes($p)."'");
			if ($itype==="Deposit" && $paycheck>0) {
				
			
				$list .=	"<div class=\"formlisttr\">
								<div class=\"formlisttd\">
								".$p." - Deposit Invoice removed due to selecting to pay main invoice(s).
								</div>
								<div class=\"formlisttd\">".$itype."</div>
								<div class=\"formlisttd\">".$dateline."</div>
								<div class=\"formlisttd\">n/a</div>
								<div class=\"formlisttd\">£ 0.00</div>
							</div>
							";
			}
			else{
				$filterva = array(",","£","$","€"," ","-");
				$curr_total = str_replace($filterva,"",number_format(array_sum($totaltopay),2));
				$totaltopay[$p] = str_replace($filterva,"",number_format($itotals,2));
				
				$itotals = (array_sum($totaltopay) >= $payLimit) ?  $payLimit-$curr_total:  $itotals;
				$payID = (array_sum($totaltopay) >= $payLimit) ?  "etotal":  count($p)>1 ?  "": "etotal";				
				if ($curr_total<=$payLimit) {
					if ($i === 0) { 
						$desc = "Invoice ".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2)); 
						$i++; 
					}
					else { 
						$desc .= " and ".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2));
						$i++; 
					}
					$list .=	"<div class=\"formlisttr\">
								<div class=\"formlisttd\">".$p."</div>
								<div class=\"formlisttd\">".$dateline."</div>
								<div class=\"formlisttd\">
									<a href=\"$site_url/".$link.".php?quote=".$p."\" target=\"_blank\">Click here</a>
								</div>";				
					$list .= "<div id=\"".$payID."\" data-desc=\"".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2))."\" data-amount=\"".str_replace($filterva,"",number_format($itotals,2))."\" class=\"formlisttd\">£ ".number_format($itotals,2)."</div></div>";
					$totaltopay[$p] = str_replace($filterva,"",number_format($itotals,2));
				}
				else {
					$totaltopay[$p] = 0;	
				}
			}
		}
		$itotals = str_replace(",","",number_format(array_sum($totaltopay),2));
	}
	require("_wopay/paymentToken.php");
?>
	<div class="formheader">
		<h1>Confirm invoices below</h1>
	</div>
	<div class="pageform">
		<div class="formrow">
			<div class="formlist">
				<div class="formlisttable">
					<div class="formlisttr">
						<div class="formlistth">Quote ID</div>
						<div class="formlistth">Date</div>
						<div class="formlistth">View PDF</div>
						<div class="formlistth">Amount</div>
					</div>
					<?	echo $list; ?>
					<div id="ccload" class="formlisttr">
						<div class="formlisttd">&nbsp;</div>
						<div class="formlisttd">&nbsp;</div>
						<div class="formlisttd">Total:</div>
						<div id="stotal" class="formlisttd"><strong><? echo "£ ".number_format(array_sum($totaltopay),2); ?></strong></div>
					</div>
				</div>
				<div class="formlisttable">
					<div class="formlisttr">
						<div class="formlisttd">
							</label>
							<input class="formsubmit" name="invoice_submit" type="submit" value="Go to WorldPay &raquo;"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
<?
}
else {
	?>
<div class='formheader'>
	<h1>Quotations</h1>
</div>
<?	echo "<form class=\"pageform\" name=\"payin\" action=\"$site_url/quotations/\" method=\"post\">"; ?>
<div class="formlist">
	<div class="formlisttable">
		<div class="formlisttr desktopcart">
			<div class="formlistth">Quote ID</div>
			<div class="formlistth">Date</div>
			<div class="formlistth">Status</div>
			<div class="formlistth">Amount</div>
			<div class="formlistth">View PDF</div>
			<!--<div class="formlistth">Select</div>
			<div class="formlistth">Pay</div>-->
		</div>
		<? 
// $result = $sql_command->select("$database_quotation_proformas,$database_quotation_details","$database_quotation_proformas.id,
//							$database_quotation_proformas.iw_cost,
//							$database_quotation_proformas.status,
//							$database_quotation_proformas.timestamp,
//							$database_quotation_proformas.type,
//							$database_quotation_proformas.order_id,
//							$database_quotation_proformas.included_package","WHERE 
//							$database_quotation_proformas.order_id=$database_order_details.id AND 
//							$database_quotation_details.client_id='".addslashes($clid)."' AND 
//							$database_quotation_proformas.status='Quotation'
//							ORDER BY $database_quotation_proformas.id DESC");
 $result = $sql_command->select("quotation_proformas,quotation_details","quotation_proformas.id,
							quotation_proformas.iw_cost,
							quotation_proformas.status,
							quotation_proformas.timestamp,
							quotation_proformas.type,
							quotation_proformas.order_id,
							quotation_proformas.included_package","WHERE 
							quotation_proformas.order_id=quotation_details.id AND 
							quotation_details.client_id='".addslashes($clid)."' AND 
							(quotation_proformas.status='Outstanding' OR 
							quotation_proformas.status='Quotation')
							ORDER BY quotation_proformas.id DESC");
$row = $sql_command->results($result);
$list = "";
/*$list = "SELECT $database_customer_invoices.id,
							$database_customer_invoices.iw_cost,
							$database_customer_invoices.status,
							$database_customer_invoices.timestamp,
							$database_customer_invoices.type,
							$database_customer_invoices.order_id,
							$database_customer_invoices.included_package FROM $database_customer_invoices,$database_order_details WHERE 
							$database_customer_invoices.order_id=$database_order_details.id AND 
							$database_order_details.client_id='".addslashes($clid)."' AND 
							($database_customer_invoices.status='Outstanding' OR 
							$database_customer_invoices.status='Quotation')
							ORDER BY $database_customer_invoices.id DESC"; */ 
foreach($row as $record) {

$dateline = date("d-m-Y",$record[3]);

if($record[4] == "Deposit") { $link = "pdf_quotation"; } else { $link= "pdf_quotation"; }

/*
if($record[6] == "Yes") {
$result_cost = $sql_command->select($database_customer_invoices,"iw_cost","WHERE order_id='".addslashes($record[5])."' and type='Deposit' and status='Paid'");
$record_cost = $sql_command->result($result_cost);
$total_invoice_cost = $record[1] - $record_cost[0];	
} else {
$total_invoice_cost = $record[1];	
}
*/

if($record[2]!='Outstanding' AND $record[2]!='Quotation') { $paystatus = "disabled"; } else { $paystatus = ""; } 

if ($record[4]=="Deposit") {
	$resp = $sql_command->select("customer_payments,customer_transactions",
							"sum(customer_payments.p_amount)",
							"WHERE customer_transactions.p_id = customer_payments.pay_no 
							AND customer_payments.status != 'Unpaid'
							AND customer_payments.invoice_id = '".addslashes($record[0])."' AND 
							customer_transactions.status = 'Paid'");

	$respr = $sql_command->result($resp);
	$totalpp = $record[1] - $respr[0];
	$new_total_gbp = "£ ".number_format($totalpp,2);
	$totalin = number_format($totalpp,2);
}
else {
	$invoiceno = $record[0];
	include("_includes/fn_proforma-payment.php");
	$totalin		=	number_format($total_gbp,2);
	$new_total_gbp	=	"£ ".number_format($total_gbp,2);
}
$filterva = array(",","£","$","€"," ","-");
$totalin = str_replace($filterva,"",$totalin);
$list .= "
<div class=\"formlisttr mobilecart\">
	<div class=\"formlistth\">Quote ID</div>
	<div class=\"formlistth\">Type</div>
	<div class=\"formlistth\">Date</div>
	<div class=\"formlistth\">Status</div>
</div>
<div class=\"formlisttr mobilecart\">
	<div class=\"formlisttd\">".$record[0]."</div>
	<div class=\"formlisttd\">".stripslashes($record[4])."</div>
	<div class=\"formlisttd\">".$dateline."</div>
	<div class=\"formlisttd\">".$record[2]."</div>
</div>
<div class=\"formlisttr mobilecart\">
	<div class=\"formlistth\">Amount</div>
	<div class=\"formlistth\">View PDF</div>
	<!--<div class=\"formlistth\">Select</div>
	<div class=\"formlistth\">Confirm</div>-->
</div>
<div class=\"formlisttr\">
	<div class=\"formlisttd desktopcart\">".$record[0]."</div>
	<div class=\"formlisttd desktopcart\">".$dateline."</div>
	<div class=\"formlisttd desktopcart\">".$record[2]."</div>
	<div class=\"formlisttd\">".$new_total_gbp."</div>
	<div class=\"formlisttd\">
		<a href=\"$site_url/".$link.".php?invoice=".$record[0]."\" target=\"_blank\">Click here</a>
	</div>
	<!--<div class=\"formlisttd\">
		<input type=\"checkbox\" name=\"payinv[]\" value=\"".$record[0]."\" ".$paystatus.">
		<input type=\"hidden\" name=\"client_id\" value=\"".$clid."\">
		<input type=\"hidden\" name=\"".$record[0]."-invoice_id\" value=\"".$record[0]."\">
		<input type=\"hidden\" name=\"".$record[0]."-invoice_type\" value=\"".stripslashes($record[4])."\">
		<input type=\"hidden\" name=\"".$record[0]."-order_id\" value=\"".stripslashes($record[5])."\">
		<input type=\"hidden\" name=\"".$record[0]."-invid\" value=\"".$record[0]."\">
		<input type=\"hidden\" name=\"".$record[0]."-date\" value=\"".$dateline."\">
		<input type=\"hidden\" name=\"".$record[0]."-type\" value=\"".stripslashes($record[4])."\">
		<input type=\"hidden\" id=\"".$record[0]."-total\" name=\"".$record[0]."-total\" value=\"".$totalin."\">
		<input type=\"hidden\" name=\"".$record[0]."-orderid\" value=\"".stripslashes($record[5])."\">
	</div>
	<div class=\"formlisttd\">
		<button name=\"payinvoice\" type=\"submit\" value=\"".$record[0]."\" ".$paystatus.">Pay</button>
	</div>-->
</div>
<div class=\"formlisttrspacer\"></div>
";
}

echo $list; ?>
	</div>
</div>
<? if($list) { ?>
<!--<div class="formlist">
	<div class="formlisttable">
		<div class="formlisttr">
			<div class="formlisttd">Select <a id="allinv" href="#payin">All</a> | <a id="noinv" href="#payin">None</a> <span>(Please note, only Outstanding invoices will be checked).</span></div>
			<div class="formlisttd">
				<input type="submit" name="payinvoices" value="Pay All" disabled>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="cart-total" name="cart-total" value="0">-->
</form>
</div>
<? 
}
else {
?>
<div class="formlist">
	<div class="formlisttable">
		<div class="formlisttr">
			<div class="formlisttd">No invoices outstanding.</div>
			<div class="formlisttd">&nbsp;</div>
		</div>
	</div>
</div>
</form>
</div>
<?	
}
}
}
else {
?>
<script>
	$(function() {
		$( "#wedding-d" ).datepicker({ dateFormat: 'dd/mm/yy' });
	});
	</script>
<form action="<? echo $site_url; ?>/quotations/" class="pageform" id="invoices" method="post" name="invoices">
	<input type="hidden" name="page" value="invoice">
	<div class="formheader">
		<h1>View Quotations</h1>
		<p> To view your quotations use your wedding date and password in the boxes below.</p>
	</div>
	<!--<div class="formrow">
		<label class="formlabel" for="customer-id">Ionian Weddings ID:</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="customer-id" name="customer-id" type="text" value="<? echo $cuid; ?>" />
		</div>
		<div class="clear"></div>
	</div>-->
	<div class="formrow">
		<label class="formlabel" for="wedding-d">Date of Wedding:</label>
		<div class="formelement">
			<input class="formtextfieldlong forminput" id="wedding-d" name="wedding-d" type="text" value="<? echo $wdate; ?>" title="DD/MM/YYYY" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="password">Password:</label>
		<div class="formelement">
			<input class="formtextfieldlong forminput" id="password" name="password" type="password" value="" title="Password" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="viewinvoice">&nbsp;</label>
		<div class="formelement">
			<input id="viewinvoice" name="viewinvoice" type="submit" value="View Quotes" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="viewinvoice">&nbsp;</label>
		<div class="formelement">
			<p><? echo $errori ; ?></p>
			<p><? echo $errord ; ?></p>
		</div>
		<div class="clear"></div>
	</div>
</form>
<? } ?>
<div class="cardlogorow"> 
	<!-- Payment Methods Displayed --> 
	<!--
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=307221"></script><br /><br />
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=314618"></script><br /><br />
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=314618"></script>-->
	<ul>
		<!-- Powered by WorldPay logo-->
		<li class="cardlogoitem floatleft"><a href="http://www.worldpay.com/" target="_blank" title="Payment Processing - WorldPay - Opens in new browser window"><img src="http://www.worldpay.com/images/poweredByWorldPay.gif" border="0" alt="WorldPay Payments Processing"></a></li>
		<li class="cardlogoitem floatright"><? echo $displayVISD; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayVISE; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayMaestro; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayVisa; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayMastercard; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayJCB; ?></li>
		<li class="cardlogoitem floatright"><? echo $displayELV; ?></li>
		<li class="clear"></li>
	</ul>
</div>
