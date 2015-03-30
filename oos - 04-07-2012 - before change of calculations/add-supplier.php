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

if(!$_POST["firstname"]) { $error .= "Missing Firstname<br>"; }
if(!$_POST["lastname"]) { $error .= "Missing Lastname<br>"; }
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
$_SESSION["company"] = $_POST["company"];
$_SESSION["address"] = $_POST["address"];
$_SESSION["address2"] = $_POST["address2"];
$_SESSION["address3"] = $_POST["address3"];
$_SESSION["town"] = $_POST["town"];
$_SESSION["county"] = $_POST["county"];
$_SESSION["country"] = $_POST["country"];
$_SESSION["postcode"] = $_POST["postcode"];

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Supplier","Oops!","$error","Link","oos/add-supplier.php");
$get_template->bottomHTML();
$sql_command->close();
}

$cols = "contact_title,contact_firstname,contact_surname,contact_email,contact_tel,company_name,address_1,address_2,address_3,address_town,address_county,address_country,address_postcode,timestamp,code";

$values = "'".addslashes($_POST["title"])."',
'".addslashes($_POST["firstname"])."',
'".addslashes($_POST["lastname"])."',
'".addslashes($_POST["email"])."',
'".addslashes($_POST["tel"])."',
'".addslashes($_POST["company"])."',
'".addslashes($_POST["address"])."',
'".addslashes($_POST["address2"])."',
'".addslashes($_POST["address3"])."',
'".addslashes($_POST["town"])."',
'".addslashes($_POST["county"])."',
'".addslashes($_POST["country"])."',
'".addslashes($_POST["postcode"])."',
'$time',
''";

$sql_command->insert($database_supplier_details,$cols,$values);
$maxid = $sql_command->maxid($database_supplier_details,"id");

$_POST["company"] = str_replace("<p>", "", $_POST["company"]);
$_POST["company"] = str_replace("</p>", "", $_POST["company"]);
$_POST["company"] = ereg_replace("[^A-Za-z]", "", $_POST["company"]);

$totalcharacters = strlen($_POST["company"]);
$middleend = $totalcharacters / 2;

$first = $_POST["company"][0];
$middle = $_POST["company"][$middleend];
$last = $_POST["company"][$totalcharacters-1];

$code =  $maxid . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "S";

$sql_command->update($database_supplier_details,"code='".addslashes($code)."'","id='".$maxid."'");

$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
$_SESSION["email"] = "";
$_SESSION["tel"] = "";
$_SESSION["company"] = "";
$_SESSION["address"] = "";
$_SESSION["address2"] = "";
$_SESSION["address3"] = "";
$_SESSION["town"] = "";
$_SESSION["county"] = "";
$_SESSION["country"] = "";
$_SESSION["postcode"] = "";
	
$get_template->topHTML();
?>
<h1>Supplier Added</h1>

<p>The supplier has now been added</p>
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







$get_template->topHTML();
?>
<h1>Add Supplier</h1>

<form action="<?php echo $site_url; ?>/oos/add-supplier.php" method="POST">

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
<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="email" style="width:200px;" value="<?php echo $_SESSION["email"]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tel" style="width:200px;" value="<?php echo $_SESSION["tel"]; ?>"/> *</div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Company Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="company" style="width:200px;" value="<?php echo $_SESSION["company"]; ?>"/> *</div>
<div style="clear:left;"></div>
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

<p>* - Required Fields</p>

<p><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>