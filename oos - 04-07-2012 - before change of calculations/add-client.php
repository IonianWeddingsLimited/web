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

$iwcuid_result = $sql_command->select($database_clients,"id","WHERE iwcuid='".addslashes($_POST["iwcuid"])."'");
$iwcuid_record = $sql_command->result($iwcuid_result);

if($iwcuid_record[0]) { $error .= "IWCUID Already exists<br />"; }

if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Lastname<br>"; }
if(!$_POST["email"]) { $error .= "Missing Email<br>"; }
if(!$_POST["tel"]) { $error .= "Missing Tel<br>"; }
if(!$_POST["address"]) { $error .= "Missing Address<br>"; }
if(!$_POST["town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["county"]) { $error .= "Missing County<br>"; }
if(!$_POST["country"]) { $error .= "Missing Country<br>"; }
if(!$_POST["postcode"]) { $error .= "Missing Postcode<br>"; }
if(!$_POST["iwcuid"]) { $error .= "Missing IWCUID<br>"; }

if(!$_POST["wedding_date"]) { $error .= "Please enter an estimated wedding date<br />"; }

if($_POST["wedding_date"]) {
list($w_day,$w_month,$w_year) = explode("-",$_POST["wedding_date"]);
if(strlen($w_day)!=2 or strlen($w_month)!=2 or strlen($w_year)!=4) { $error .= "Invalid Wedding date, format  <strong>DD-MM-YYYY</strong><br />"; }
}


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
$_SESSION["wedding_date"] = $_POST["wedding_date"];
$_SESSION["destination"] = $_POST["destination"];
$_SESSION["iwcuid"] = $_POST["iwcuid"];

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

$cols = "title,firstname,lastname,address_1,address_2,address_3,town,county,country,postcode,email,tel,mob,fax,dob,mailing_list,wedding_date,destination,iwcuid";

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
'".addslashes($_POST["iwcuid"])."'";

$sql_command->insert($database_clients,$cols,$values);
$maxid = $sql_command->maxid($database_clients,"id");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".$maxid."','".$login_record[1]."','Client Created','".$time."'");

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

header("Location: $site_url/oos/manage-client.php?a=history&id=".$maxid);
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

$add_header = "<script language=\"JavaScript\" src=\"$site_url/js/calendar_eu.js\"></script>
<link rel=\"stylesheet\" href=\"$site_url/css/calendar.css\">";


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

<form action="<?php echo $site_url; ?>/oos/add-client.php" method="post" name="client">

<div style="float:left; width:160px; margin:5px;"><b>IWCUID</b></div>
<div style="float:left; margin:5px;"><input type="text" name="iwcuid" style="width:200px;" value="<?php echo $_SESSION["iwcuid"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;"><select name="title">
<option value="Mr" <?php if($_SESSION["title"] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($_SESSION["title"] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($_SESSION["title"] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($_SESSION["title"] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="firstname" style="width:200px;" value="<?php echo $_SESSION["firstname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="lastname" style="width:200px;" value="<?php echo $_SESSION["lastname"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="dob" id="dob" style="width:100px;" value="<?php echo $_SESSION["dob"]; ?>"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'dob'
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