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
$get_template = new admin_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";



if($_POST["action"] == "Add") {

$iwcuid_result = $sql_command->select($database_clients,"id","WHERE deleted='No' and iwcuid='".addslashes($_POST["iwcuid"])."'");
$iwcuid_record = $sql_command->result($iwcuid_result);

if($iwcuid_record[0]) { $error .= "IWCUID Already exists<br />"; }


if(!$_POST["firstname"]) { $error .= "Missing Bride Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Bride Lastname<br>"; }
if(!$_POST["groom_firstname"]) { $error .= "Missing Groom Firstname<br>"; }
if(!$_POST["groom_surname"]) { $error .= "Missing Groom Lastname<br>"; }
if(!$_POST["email"]) { $error .= "Missing Email<br>"; }
if(!$_POST["tel"]) { $error .= "Missing Tel<br>"; }
if(!$_POST["address"]) { $error .= "Missing Address<br>"; }
if(!$_POST["town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["county"]) { $error .= "Missing County<br>"; }
if(!$_POST["country"]) { $error .= "Missing Country<br>"; }
if(!$_POST["postcode"]) { $error .= "Missing Postcode<br>"; }


$_SESSION["title"] = $_POST["title"];
$_SESSION["firstname"] = $_POST["firstname"];
$_SESSION["lastname"] = $_POST["lastname"];
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
$_SESSION["groom_title"] = $_POST["groom_title"];
$_SESSION["groom_firstname"] = $_POST["groom_firstname"];
$_SESSION["groom_surname"] = $_POST["groom_surname"];
$_SESSION["groom_dob"] = $_POST["groom_dob"];
$_SESSION["wedding_time"] = $_POST["wedding_time"];

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Client","Oops!","$error","Link","admin/add-client.php");
$get_template->bottomHTML();
$sql_command->close();
}


$cols = "title,firstname,lastname,address_1,address_2,address_3,town,county,country,postcode,email,tel,mob,fax,dob,mailing_list,wedding_date,destination,iwcuid,deleted,groom_title,groom_firstname,groom_surname,groom_dob,wedding_time";

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
'No',
'".addslashes($_POST["groom_title"])."',
'".addslashes($_POST["groom_firstname"])."',
'".addslashes($_POST["groom_surname"])."',
'".addslashes($_POST["groom_dob"])."',
'".addslashes($_POST["wedding_time"])."'";
$sql_command->insert($database_clients,$cols,$values);

$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
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


$get_template->topHTML();
?>
<h1>Client Added</h1>

<p>The client has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

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

if(!$_SESSION["title"]) {
$_SESSION["title"] = "Miss";
}
if(!$_SESSION["mailinglist"]) {
$_SESSION["mailinglist"] = "Yes";
}
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
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

<form action="<?php echo $site_url; ?>/admin/add-client.php" method="POST">
<div style="float:left; width:160px; margin:5px;"><b>IWCUID</b></div>
<div style="float:left; margin:5px;"><input type="text" name="iwcuid" style="width:200px;" value="<?php echo $_SESSION["iwcuid"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride Title</b></div>
<div style="float:left; margin:5px;"><select name="title">
<option value="Mr" <?php if($_SESSION["title"] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($_SESSION["title"] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($_SESSION["title"] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($_SESSION["title"] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="firstname" style="width:200px;" value="<?php echo $_SESSION["firstname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="lastname" style="width:200px;" value="<?php echo $_SESSION["lastname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="dob" style="width:100px;" value="<?php echo $_SESSION["dob"]; ?>"/> (Format DD-MM-YYYY)</div>
<div style="clear:left;"></div>


<div style="float:left; width:160px; margin:5px;"><b>Groom Title</b></div>
<div style="float:left; margin:5px;"><select name="groom_title">
<option value="Mr" <?php if($_SESSION["groom_title"] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($_SESSION["groom_title"] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($_SESSION["groom_title"] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($_SESSION["groom_title"] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_firstname" style="width:200px;" value="<?php echo $_SESSION["groom_firstname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_surname" style="width:200px;" value="<?php echo $_SESSION["groom_surname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="groom_dob" id="groom_dob" style="width:100px;" value="<?php echo $_SESSION["groom_dob"]; ?>"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'groom_dob'
	});

	</script> </div>
<div style="float:left; margin:5px;">(Format DD-MM-YYYY)</div>
<div style="clear:left;"></div>



<div style="float:left; width:160px; margin:5px;"><b>Mailing List</b></div>
<div style="float:left; margin:5px;"><select name="mailinglist">
<option value="Yes" <?php if($_SESSION["mailinglist"] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
<option value="No" <?php if($_SESSION["mailinglist"] == "No") { echo "selected=\"selected\""; } ?>>No</option>
</select></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="email" style="width:200px;" value="<?php echo $_SESSION["email"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tel" style="width:200px;" value="<?php echo $_SESSION["tel"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Mob</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="mob" style="width:200px;" value="<?php echo $_SESSION["mob"]; ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Fax</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="fax" style="width:200px;" value="<?php echo $_SESSION["fax"]; ?>"/></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address" style="width:200px;" value="<?php echo $_SESSION["address"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address2" style="width:200px;" value="<?php echo $_SESSION["address2"]; ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address3" style="width:200px;" value="<?php echo $_SESSION["address3"]; ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="town" style="width:200px;" value="<?php echo $_SESSION["town"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="county" style="width:200px;" value="<?php echo $_SESSION["county"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Country</b></div>
<div style="float:left; margin:5px;">	<select id="country" name="country">
				<?php echo $country_list; ?>
			</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Postcode</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="postcode" style="width:100px;" value="<?php echo $_SESSION["postcode"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Wedding Date</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="wedding_date" id="wedding_date" style="width:100px;" value="<?php echo $_SESSION["wedding_date"]; ?>"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'wedding_date'
	});

	</script></div>
    <div style="float:left; margin:10px;">	at </div>
    <div style="float:left; margin:5px;">	<input type="text" name="wedding_time" style="width:100px;" value="<?php echo $_SESSION["wedding_time"]; ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Destination</b></div>
<div style="float:left; margin:5px;"><select name="destination" class="inputbox_town" size="10" style="width:300px;"><?php echo $nav_list; ?></select></div>
<div style="clear:left;"></div>

<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>