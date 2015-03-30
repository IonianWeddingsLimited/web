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



if($_POST["action"] == "Add") {

if ($_POST['cli_pros'] == 'Client') {
	$iwcuid_result = $sql_command->select($database_clients,"id","WHERE deleted='No' and iwcuid='".addslashes($_POST["iwcuid"])."'");
	$iwcuid_record = $sql_command->result($iwcuid_result);
	
	if($iwcuid_record[0]) { $error .= "IWCUID Already exists<br />"; }
}



if(!$_POST["firstname"]) { $error .= "Missing Bride Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Bride Lastname<br>"; }
if ($_POST['cli_pros'] == 'Client') {
	if(!$_POST["iwcuid"]) { $error .= "Missing IWCUID<br>"; }
	if(!$_POST["password"]) { $error .= "Missing Password<br>"; }
}
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

if(!$_POST["wedding_date"]) { $error .= "Please enter an estimated wedding date<br />"; }

if($_POST["wedding_date"]) {
list($w_day,$w_month,$w_year) = explode("-",$_POST["wedding_date"]);
if(strlen($w_day)!=2 or strlen($w_month)!=2 or strlen($w_year)!=4) { $error .= "Invalid Wedding date, format  <strong>DD-MM-YYYY</strong><br />"; }
}


$_SESSION["title"] = $_POST["title"];
$_SESSION["firstname"] = $_POST["firstname"];
$_SESSION["lastname"] = $_POST["lastname"];
$_SESSION["password"] = $_POST["password"];
$_SESSION["email"] = $_POST["email"];
$_SESSION["tel"] = $_POST["tel"];
$_SESSION["address"] = $_POST["address"];
$_SESSION["address2"] = $_POST["address2"];
$_SESSION["address3"] = $_POST["address3"];
$_SESSION["town"] = $_POST["town"];
$_SESSION["county"] = $_POST["county"];
$_SESSION["country"] = $_POST["country"];
$_SESSION["postcode"] = $_POST["postcode"];
$_SESSION["mob"] = $_POST["mob"];
$_SESSION["fax"] = $_POST["fax"];
$_SESSION["dob"] = $_POST["dob"];
$_SESSION["mailinglist"] = $_POST["mailinglist"];
$_SESSION["wedding_date"] = $_POST["wedding_date"];
$_SESSION["destination"] = $_POST["destination"];
$_SESSION["iwcuid"] = $_POST["iwcuid"];
$_SESSION["groom_title"] = $_POST["groom_title"];
$_SESSION["groom_firstname"] = $_POST["groom_firstname"];
$_SESSION["groom_surname"] = $_POST["groom_surname"];
$_SESSION["groom_dob"] = $_POST["groom_dob"];
$_SESSION["wedding_time"] = $_POST["wedding_time"];
$_SESSION["groom_mob"] = $_POST["groom_mob"];
$_SESSION["groom_fax"] = $_POST["groom_fax"];
$_SESSION["groom_email"] = $_POST["groom_email"];
$_SESSION["groom_tel"] = $_POST["groom_tel"];
$_SESSION["planner_id"] = $_POST["planner_id"];
$_SESSION["type_of_ceremony"] 			=	$_POST["type_of_ceremony"];
$_SESSION["bride_country"]				=	$_POST["bride_country"];
$_SESSION["groom_country"]				=	$_POST["groom_country"];
$_SESSION["bride_passport"]				=	$_POST["bride_passport"];
$_SESSION["groom_passport"]				=	$_POST["groom_passport"];
$_SESSION["bride_birth_certificate"]	=	$_POST["bride_birth_certificate"];
$_SESSION["groom_birth_certificate"]	=	$_POST["groom_birth_certificate"];
$_SESSION["bride_divorced"]				=	$_POST["bride_divorced"];
$_SESSION["groom_divorced"]				=	$_POST["groom_divorced"];
$_SESSION["bride_adopted"]				=	$_POST["bride_adopted"];
$_SESSION["groom_adopted"]				=	$_POST["groom_adopted"];
$_SESSION["bride_widowed"]				=	$_POST["bride_widowed"];
$_SESSION["groom_widowed"]				=	$_POST["groom_widowed"];
$_SESSION["bride_deed_poll"]			=	$_POST["bride_deed_poll"];
$_SESSION["groom_deed_poll"]			=	$_POST["groom_deed_poll"];
$_SESSION["bride_baptised"]				=	$_POST["bride_baptised"];
$_SESSION["groom_baptised"]				=	$_POST["groom_baptised"];
$_SESSION["bride_baptised_certificate"]	=	$_POST["bride_baptised_certificate"];
$_SESSION["groom_baptised_certificate"]	=	$_POST["groom_baptised_certificate"];
$_SESSION["tandc"]						=	$_POST["tandc"];
$_SESSION["over18"]						=	$_POST["over18"];

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Client","Oops!","$error","Link","oos/add-client.php");
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["wedding_date"]);
if($day and $month and $year) {
$savedate = mktime(0, 0, 0, $month, $day, $year);
}

list($day2,$month2,$year2) = explode("-",$_POST["dob"]);
if($day2 and $month2 and $year2) {
$savedate2 = mktime(0, 0, 0, $month2, $day2, $year2);
}

list($day3,$month3,$year3) = explode("-",$_POST["groom_dob"]);
if($day3 and $month3 and $year3) {
$savedate3 = mktime(0, 0, 0, $month3, $day3, $year3);
}

$cols = "title,firstname,lastname,address_1,address_2,address_3,town,county,country,postcode,email,tel,mob,fax,dob,mailing_list,wedding_date,destination,iwcuid,cli_pass,deleted,groom_title,groom_firstname,groom_surname,groom_dob,wedding_time,groom_email,groom_tel,groom_mob,groom_fax,planner_id,type_of_ceremony,bride_country,groom_country,bride_passport,groom_passport,bride_birth_certificate,groom_birth_certificate,bride_divorced,groom_divorced,bride_adopted,groom_adopted,bride_widowed,groom_widowed,bride_deed_poll,groom_deed_poll,bride_baptised,groom_baptised,bride_baptised_certificate,groom_baptised_certificate,tandc,over18";

$values = "'".addslashes($_POST["title"])."',
'".addslashes($_POST["firstname"])."',
'".addslashes($_POST["lastname"])."',
'".addslashes($_POST["address"])."',
'".addslashes($_POST["address2"])."',
'".addslashes($_POST["address3"])."',
'".addslashes($_POST["town"])."',
'".addslashes($_POST["county"])."',
'".addslashes($_POST["country"])."',
'".addslashes($_POST["postcode"])."',
'".addslashes($_POST["email"])."',
'".addslashes($_POST["tel"])."',
'".addslashes($_POST["mob"])."',
'".addslashes($_POST["fax"])."',
'".addslashes($savedate2)."',
'".addslashes($_POST["mailinglist"])."',
'".addslashes($savedate)."',
'".addslashes($_POST["destination"])."',
'".addslashes($_POST["iwcuid"])."',
'".addslashes($_POST["password"])."',
'No',
'".addslashes($_POST["groom_title"])."',
'".addslashes($_POST["groom_firstname"])."',
'".addslashes($_POST["groom_surname"])."',
'".$savedate3."',
'".addslashes($_POST["wedding_time"])."',
'".addslashes($_POST["groom_email"])."',
'".addslashes($_POST["groom_tel"])."',
'".addslashes($_POST["groom_mob"])."',
'".addslashes($_POST["groom_fax"])."',
'".addslashes($_POST["planner_id"])."',
'".addslashes($_POST["type_of_ceremony"])."',
'".addslashes($_POST["bride_country"])."',
'".addslashes($_POST["groom_country"])."',
'".addslashes($_POST["bride_passport"])."',
'".addslashes($_POST["groom_passport"])."',
'".addslashes($_POST["bride_birth_certificate"])."',
'".addslashes($_POST["groom_birth_certificate"])."',
'".addslashes($_POST["bride_divorced"])."',
'".addslashes($_POST["groom_divorced"])."',
'".addslashes($_POST["bride_adopted"])."',
'".addslashes($_POST["groom_adopted"])."',
'".addslashes($_POST["bride_widowed"])."',
'".addslashes($_POST["groom_widowed"])."',
'".addslashes($_POST["bride_deed_poll"])."',
'".addslashes($_POST["groom_deed_poll"])."',
'".addslashes($_POST["bride_baptised"])."',
'".addslashes($_POST["groom_baptised"])."',
'".addslashes($_POST["bride_baptised_certificate"])."',
'".addslashes($_POST["groom_baptised_certificate"])."',
'".addslashes($_POST["tandc"])."',
'".addslashes($_POST["over18"])."'";



$sql_command->insert($database_clients,$cols,$values);
$maxid = $sql_command->maxid($database_clients,"id");

$sql_command->update($_SESSION("survey"),"archive='Yes'","id='".addslashes($_SESSION["prospect_id"])."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'".$maxid."','".$login_record[1]."','Client Created','".$time."','','".addslashes($_POST["destination"])."'");

$sql_command->insert("clients_options",
					 "client_id,
					 client_option,
					 option_value",
					 "'".$maxid."',
					 'hearaboutus',
					 '".$_POST['hearabout']."'");
if ($_POST['cli_pros'] == 'Client') {
	$sql_command->insert("clients_options",
						 "client_id,
						 client_option,
						 option_value",
						 "'".$maxid."',
						 'client_type',
						 'Active'");
} else {
	$sql_command->insert("clients_options",
						 "client_id,
						 client_option,
						 option_value",
						 "'".$maxid."',
						 'client_type',
						 'Prospect'");
}
$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
$_SESSION["password"] = "";
$_SESSION["email"] = "";
$_SESSION["tel"] = "";
$_SESSION["address"] = "";
$_SESSION["address2"] = "";
$_SESSION["address3"] = "";
$_SESSION["town"] = "";
$_SESSION["county"] = "";
$_SESSION["country"] = "";
$_SESSION["postcode"] = "";
$_SESSION["mob"] = "";
$_SESSION["fax"] = "";
$_SESSION["dob"] = "";
$_SESSION["mailinglist"] = "";
$_SESSION["wedding_date"] = "";
$_SESSION["destination"] = "";
$_SESSION["iwcuid"] = "";
$_SESSION["groom_title"] = "";
$_SESSION["groom_firstname"] = "";
$_SESSION["groom_surname"] = "";
$_SESSION["groom_dob"] = "";
$_SESSION["wedding_time"] = "";
$_SESSION["groom_mob"] = "";
$_SESSION["groom_fax"] = "";
$_SESSION["groom_email"] = "";
$_SESSION["groom_tel"] = "";
$_SESSION["hearabout"] = "";
$_SESSION["planner_id"] = "";
$_SESSION["type_of_ceremony"] 			=	"";
$_SESSION["bride_country"]				=	"";
$_SESSION["groom_country"]				=	"";
$_SESSION["bride_passport"]				=	"";
$_SESSION["groom_passport"]				=	"";
$_SESSION["bride_birth_certificate"]	=	"";
$_SESSION["groom_birth_certificate"]	=	"";
$_SESSION["bride_divorced"]				=	"";
$_SESSION["groom_divorced"]				=	"";
$_SESSION["bride_adopted"]				=	"";
$_SESSION["groom_adopted"]				=	"";
$_SESSION["bride_widowed"]				=	"";
$_SESSION["groom_widowed"]				=	"";
$_SESSION["bride_deed_poll"]			=	"";
$_SESSION["groom_deed_poll"]			=	"";
$_SESSION["bride_baptised"]				=	"";
$_SESSION["groom_baptised"]				=	"";
$_SESSION["bride_baptised_certificate"]	=	"";
$_SESSION["groom_baptised_certificate"]	=	"";
$_SESSION["tandc"]						=	"";
$_SESSION["over18"]						=	"";

if ($_POST['cli_pros'] == 'Client') {
header("Location: $site_url/oos/manage-client.php?a=history&id=".$maxid);
} else {
header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$maxid);	
}
$sql_command->close();

} else {

$planner_result = $sql_command->select("users","id, username","ORDER BY username");
$planner_row = $sql_command->results($planner_result);

foreach($planner_row as $planner_record) {
$currentValue	=	stripslashes($planner_record[0]);
$currentOption	=	stripslashes($planner_record[1]);
if($_SESSION["planner_id"] == $currentValue) { 
	$selected = "selected=\"selected\"";
} elseif(!$_SESSION["planner_id"] and $currentValue == "4") {
	$selected = "selected=\"selected\""; 
} else {
	$selected = ""; 
}
$planner_list .= "<option value=\"".$currentValue."\" $selected>".$currentOption."</option>";
}	

$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($_SESSION["country"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["country"] and $current == "United Kingdom") {
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
if($_SESSION["bride_country"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["bride_country"] and $current == "United Kingdom") {
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
if($_SESSION["groom_country"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["groom_country"] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$groom_country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}

if(!$_SESSION["title"]) {
$_SESSION["title"] = "Miss";
}
if(!$_SESSION["mailinglist"]) {
$_SESSION["mailinglist"] = "Yes";
}
$typeofceremony_result = $sql_command->select($database_typeofceremony,"value","ORDER BY value");
$typeofceremony_row = $sql_command->results($typeofceremony_result);

foreach($typeofceremony_row as $typeofceremony_record) {
	$current = stripslashes($typeofceremony_record[0]);
	
	if($_SESSION["type_of_ceremony"] == $current) { 
		$selected = "selected=\"selected\""; 
	} else {
		$selected = ""; 
	}
	$typeofceremony_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}
$add_header = "<script language=\"JavaScript\" src=\"$site_url/js/calendar_eu.js\"></script>
<link rel=\"stylesheet\" href=\"$site_url/css/calendar.css\">";


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}

$nav_list = str_replace("value=\"".$_SESSION["destination"]."\"","value=\"".$_SESSION["destination"]."\" selected=\"selected\"",$nav_list);

$get_template->topHTML();
?>

<h1>Add Client</h1>
<form action="<?php echo $site_url; ?>/oos/add-client.php" method="post" name="client">
	<div style="float:left; width:160px; margin:5px;"><b>Prospect / Client</b></div>
	<div style="float:left; margin:5px;">
		<select name="cli_pros" id="cli_pros" style="width:205px;">
			<option value="Client" selected="selected">Client</option>
			<option value="Prospect">Prospect</option>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>IWCUID</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="iwcuid" style="width:200px;" value="<?php echo $_SESSION["iwcuid"]; ?>"/>
		* (required if client)</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Password</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="password" style="width:200px;" value="<?php echo $_SESSION["password"]; ?>"/>
			* (required if client)</div>
		<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Planner</b></div>
	<div style="float:left; margin:5px;">
		<select id="planner_id" name="planner_id" style="width: 205px;">
			<?php echo $planner_list?>
		</select>
		* </div>
	<div style="clear:left;"></div>
	<?php 
$hearab = (strlen($_SESSION["hearabout"])<1) ? "Unknown": $_SESSION["hearabout"];
?>
	<div style="float:left; width:160px; margin:5px;"><b>Heard about us?</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="hearabout" style="width:200px;" value="<?php echo $hearab; ?>">
		* </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Mailing List</b></div>
	<div style="float:left; margin:5px;">
		<select name="mailinglist">
			<option value="Yes" <?php if($_SESSION["mailinglist"] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
			<option value="No" <?php if($_SESSION["mailinglist"] == "No") { echo "selected=\"selected\""; } ?>>No</option>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;">&nbsp;</div>
	<div style="float:left; width: 220px; margin:5px;"> <b>Bride</b> </div>
	<div style="float:left; margin:5px;"> <b>Groom</b> </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<select name="title">
			<option value="Mr" <?php if($_SESSION["title"] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
			<option value="Miss" <?php if($_SESSION["title"] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
			<option value="Ms" <?php if($_SESSION["title"] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
			<option value="Dr" <?php if($_SESSION["title"] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
		</select>
	</div>
	<div style="float:left; margin:5px;">
		<select name="groom_title">
			<option value="Mr" <?php if($_SESSION["groom_title"] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
			<option value="Miss" <?php if($_SESSION["groom_title"] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
			<option value="Ms" <?php if($_SESSION["groom_title"] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
			<option value="Dr" <?php if($_SESSION["groom_title"] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>First Name</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="firstname" style="width:200px;" value="<?php echo $_SESSION["firstname"]; ?>"/>
		*</div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_firstname" style="width:200px;" value="<?php echo $_SESSION["groom_firstname"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Last Name</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="lastname" style="width:200px;" value="<?php echo $_SESSION["lastname"]; ?>"/>
		*</div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_surname" style="width:200px;" value="<?php echo $_SESSION["groom_surname"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>DOB</b> (Format DD-MM-YYYY)</div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="dob" id="dob" style="width:100px;" value="<?php echo $_SESSION["dob"]; ?>"/>
		<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'dob'
	});

	</script> </div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_dob" id="groom_dob" style="width:100px;" value="<?php echo $_SESSION["groom_dob"]; ?>"/>
		<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'groom_dob'
	});

	</script> </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Nationality</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<select id="bride_country" name="bride_country" style="width:206px;">
			<?php echo $bride_country_list; ?>
		</select>
	</div>
	<div style="float:left; width: 220px; margin:5px;">
		<select id="groom_country" name="groom_country" style="width:206px;">
			<?php echo $groom_country_list; ?>
		</select>
	</div>
	<div style="clear:left;"></div>
	<hr />
	<div style="float:left; width:160px; margin:5px;">&nbsp;</div>
	<div style="float:left; width: 220px; margin:5px;"> <b>Bride</b> </div>
	<div style="float:left; margin:5px;"> <b>Groom</b> </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="email" style="width:200px;" value="<?php echo $_SESSION["email"]; ?>"/>
		*</div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_email" style="width:200px;" value="<?php echo $_SESSION["groom_email"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="tel" style="width:200px;" value="<?php echo $_SESSION["tel"]; ?>"/>
		*</div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_tel" style="width:200px;" value="<?php echo $_SESSION["groom_tel"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Mob</b></div>
	<div style="float:left; width: 220px; margin:5px;">
		<input type="text" name="mob" style="width:200px;" value="<?php echo $_SESSION["mob"]; ?>"/>
	</div>
	<div style="float:left; margin:5px;">
		<input type="text" name="groom_mob" style="width:200px;" value="<?php echo $_SESSION["groom_mob"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<!--<div style="float:left; width:160px; margin:5px;"><b>Bride Fax</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="fax" style="width:200px;" value="<?php echo $_SESSION["fax"]; ?>"/></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_fax" style="width:200px;" value="<?php echo $_SESSION["groom_fax"]; ?>"/></div>

<div style="clear:left;"></div>-->
	<hr />
	<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="address" style="width:200px;" value="<?php echo $_SESSION["address"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="address2" style="width:200px;" value="<?php echo $_SESSION["address2"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="address3" style="width:200px;" value="<?php echo $_SESSION["address3"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="town" style="width:200px;" value="<?php echo $_SESSION["town"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="county" style="width:200px;" value="<?php echo $_SESSION["county"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Country</b></div>
	<div style="float:left; margin:5px;">
		<select id="country" name="country">
			<?php echo $country_list; ?>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Postcode</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="postcode" style="width:100px;" value="<?php echo $_SESSION["postcode"]; ?>"/>
		*</div>
	<div style="clear:left;"></div>
	<hr />
	<div style="float:left; width:160px; margin:5px;"><b>Wedding Date</b></div>
	<div style="float:left; margin:5px;">
		<input type="text" name="wedding_date" id="wedding_date" style="width:100px;" value="<?php echo $_SESSION["wedding_date"]; ?>"/>
		<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'wedding_date'
	});

	</script></div>
	<div style="float:left; margin:10px;"> at </div>
	<div style="float:left; margin:5px;">
		<input type="text" name="wedding_time" style="width:100px;" value="<?php echo $_SESSION["wedding_time"]; ?>"/>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Destination</b></div>
	<div style="float:left; margin:5px;">
		<select name="destination" class="inputbox_town" size="10" style="width:300px;">
			<?php echo $nav_list; ?>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><b>Type of Ceremony</b></div>
	<div style="float:left; margin:5px;">
		<select class="formselectlong" id="type_of_ceremony" name="type_of_ceremony">
			<?
				echo $typeofceremony_list;
			?>
		</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;">&nbsp;</div>
	<div style="float:left; width: 100px; margin:5px;"> <b>Bride</b> </div>
	<div style="float:left; margin:5px;"> <b>Groom</b> </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Do you have a valid 10 year passport not due to expire within 6 months at the time of travel?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_passport" type="radio" <?php if($_SESSION["bride_passport"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_passport" type="radio" <?php if($_SESSION["bride_passport"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_passport" type="radio" <?php if($_SESSION["groom_passport"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_passport" type="radio" <?php if($_SESSION["groom_passport"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Do you have a full Birth Certificate (with names of both parents)?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_birth_certificate" type="radio" <?php if($_SESSION["bride_birth_certificate"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_birth_certificate" type="radio" <?php if($_SESSION["bride_birth_certificate"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_birth_certificate" type="radio" <?php if($_SESSION["groom_birth_certificate"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_birth_certificate" type="radio" <?php if($_SESSION["groom_birth_certificate"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Are you divorced?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_divorced" type="radio" <?php if($_SESSION["bride_divorced"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_divorced" type="radio" <?php if($_SESSION["bride_divorced"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_divorced" type="radio" <?php if($_SESSION["groom_divorced"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_divorced" type="radio" <?php if($_SESSION["groom_divorced"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Are you adopted?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_adopted" type="radio" <?php if($_SESSION["bride_adopted"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_adopted" type="radio" <?php if($_SESSION["bride_adopted"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_adopted" type="radio" <?php if($_SESSION["groom_adopted"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_adopted" type="radio" <?php if($_SESSION["groom_adopted"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Have you been widowed?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_widowed" type="radio" <?php if($_SESSION["bride_widowed"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_widowed" type="radio" <?php if($_SESSION["bride_widowed"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_widowed" type="radio" <?php if($_SESSION["groom_widowed"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_widowed" type="radio" <?php if($_SESSION["groom_widowed"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Have you changed your name by Deed Poll?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_deed_poll" type="radio" <?php if($_SESSION["bride_deed_poll"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_deed_poll" type="radio" <?php if($_SESSION["bride_deed_poll"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_deed_poll" type="radio" <?php if($_SESSION["groom_deed_poll"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_deed_poll" type="radio" <?php if($_SESSION["groom_deed_poll"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Have you been baptised? (For religious ceremonies Only)</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_baptised" type="radio" <?php if($_SESSION["bride_baptised"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_baptised" type="radio" <?php if($_SESSION["bride_baptised"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_baptised" type="radio" <?php if($_SESSION["groom_baptised"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_baptised" type="radio" <?php if($_SESSION["groom_baptised"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>Do you have your baptism certificate?</b></div>
	<div style="float:left; width: 100px; margin:5px;">
		<input name="bride_baptised_certificate" type="radio" <?php if($_SESSION["bride_baptised_certificate"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="bride_baptised_certificate" type="radio" <?php if($_SESSION["bride_baptised_certificate"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="float:left; margin:5px;">
		<input name="groom_baptised_certificate" type="radio" <?php if($_SESSION["groom_baptised_certificate"] == "Yes") { echo "selected=\"selected\""; } ?> value="Yes" /> Yes
		<input name="groom_baptised_certificate" type="radio" <?php if($_SESSION["groom_baptised_certificate"] == "No") { echo "selected=\"selected\""; } ?> value="No" /> No
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:450px; margin:5px;"><b>I have read, understood and agree to be bound by Ionian Weddings' Terms and Conditions</b></div>
	<div style="float:left; margin:5px;">
			<input name="tandc" type="checkbox" <?php if($_SESSION["groom_baptised_certificate"] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
			You can read them by clicking <a href="http://www.ionianweddings.co.uk/terms-and-conditions/" target="_blank" style="color:#c08827;">here</a>
	</div>
	<div style="float:left; width:450px; margin:5px;"><b>I confirm that I am over 18 years of age</b></div>
	<div style="float:left; margin:5px;">
			<input name="over18" type="checkbox" <?php if($_SESSION["groom_baptised_certificate"] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
	</div>
	<div style="clear:left;"></div>
	<p>* - Required Fields</p>
	<p>
		<input type="submit" name="action" value="Add">
	</p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>
