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



if($_POST["action"] == "Update") {

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Supplier","Oops!","$error","Link","oos/update-supplier.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_supplier_details,"contact_title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"contact_firstname='".addslashes($_POST["firstname"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"contact_surname='".addslashes($_POST["lastname"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"contact_email='".addslashes($_POST["email"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"contact_tel='".addslashes($_POST["tel"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_1='".addslashes($_POST["address"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_2='".addslashes($_POST["address2"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_3='".addslashes($_POST["address3"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_town='".addslashes($_POST["town"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_county='".addslashes($_POST["county"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_country='".addslashes($_POST["country"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_supplier_details,"address_postcode='".addslashes($_POST["postcode"])."'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Supplier Updated</h1>

<p>The supplier has now been updated</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-supplier.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View") {
	
$result = $sql_command->select($database_supplier_details,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);


$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($record[12] == $current) { 
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
<h1>Update Supplier</h1>

<form action="<?php echo $site_url; ?>/oos/update-supplier.php" method="POST">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;"><select name="title">
<option value="Mr" <?php if($record[1] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($record[1] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($record[1] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($record[1] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="firstname" style="width:200px;" value="<?php echo stripslashes($record[2]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="lastname" style="width:200px;" value="<?php echo stripslashes($record[3]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="email" style="width:200px;" value="<?php echo stripslashes($record[4]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tel" style="width:200px;" value="<?php echo stripslashes($record[5]); ?>"/> *</div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Company ID</b></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[15]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Company Name</b></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($record[6]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address" style="width:200px;" value="<?php echo stripslashes($record[7]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address2" style="width:200px;" value="<?php echo stripslashes($record[8]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address3" style="width:200px;" value="<?php echo stripslashes($record[9]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="town" style="width:200px;" value="<?php echo stripslashes($record[10]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="county" style="width:200px;" value="<?php echo stripslashes($record[11]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Country</b></div>
<div style="float:left; margin:5px;">	<select id="country" name="country">
				<?php echo $country_list; ?>
			</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Postcode</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="postcode" style="width:100px;" value="<?php echo stripslashes($record[13]); ?>"/> *</div>
<div style="clear:left;"></div>


<p>* - Required Fields</p>



<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/update-supplier.php'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {

$result = $sql_command->select($database_supplier_details,"id,company_name","ORDER BY company_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Supplier</h1>

<form action="<?php echo $site_url; ?>/oos/update-supplier.php" method="POST">
<input type="hidden" name="action" value="View" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}
?>