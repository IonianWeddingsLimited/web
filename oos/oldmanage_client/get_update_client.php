<?

$result = $sql_command->select($database_clients,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

if($record[15]) { $dob = date("d-m-Y",$record[15]); }
if($record[24]) { $dob2 = date("d-m-Y",$record[24]); }

$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($record[9] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
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

$nav_list = str_replace("value=\"".$record[18]."\"","value=\"".$record[18]."\" selected=\"selected\"",$nav_list);

$add_header = "<script language=\"JavaScript\" src=\"$site_url/js/calendar_eu.js\"></script>
<link rel=\"stylesheet\" href=\"$site_url/css/calendar.css\">";

if($record[17]) { $wd = date("d-m-Y",$record[17]); }


$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Update Client</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="post" name="client">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<div style="float:left; width:160px; margin:5px;"><b>IWCUID</b></div>
<div style="float:left; margin:5px;"><input type="text" name="iwcuid" style="width:200px;" value="<?php echo $record[19]; ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride Title</b></div>
<div style="float:left; margin:5px;"><select name="title">
<option value="Mr" <?php if($record[1] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($record[1] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($record[1] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($record[1] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="firstname" style="width:200px;" value="<?php echo stripslashes($record[2]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="lastname" style="width:200px;" value="<?php echo stripslashes($record[3]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Bride DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="dob" id="dob" style="width:100px;" value="<?php echo $dob; ?>"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'dob'
	});

	</script> </div>
<div style="float:left; margin:5px;">(Format DD-MM-YYYY)</div>
<div style="clear:left;"></div>


<div style="float:left; width:160px; margin:5px;"><b>Groom Title</b></div>
<div style="float:left; margin:5px;"><select name="groom_title">
<option value="Mr" <?php if($record[21] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
<option value="Miss" <?php if($record[21] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
<option value="Ms" <?php if($record[21] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
<option value="Dr" <?php if($record[21] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom First Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_firstname" style="width:200px;" value="<?php echo stripslashes($record[22]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom Last Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_surname" style="width:200px;" value="<?php echo stripslashes($record[23]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Groom DOB</b></div>
<div style="float:left; margin:5px;"><input type="text" name="groom_dob" id="dob" style="width:100px;" value="<?php echo $dob2; ?>"/> <script language="JavaScript">
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
<option value="Yes" <?php if($record[16] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
<option value="No" <?php if($record[16] == "No") { echo "selected=\"selected\""; } ?>>No</option>
</select></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="email" style="width:200px;" value="<?php echo stripslashes($record[11]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tel" style="width:200px;" value="<?php echo stripslashes($record[12]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Mob</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="mob" style="width:200px;" value="<?php echo stripslashes($record[13]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Fax</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="fax" style="width:200px;" value="<?php echo stripslashes($record[14]); ?>"/></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address" style="width:200px;" value="<?php echo stripslashes($record[4]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address2" style="width:200px;" value="<?php echo stripslashes($record[5]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="address3" style="width:200px;" value="<?php echo stripslashes($record[6]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="town" style="width:200px;" value="<?php echo stripslashes($record[7]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="county" style="width:200px;" value="<?php echo stripslashes($record[8]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Country</b></div>
<div style="float:left; margin:5px;">	<select id="country" name="country">
				<?php echo $country_list; ?>
			</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Postcode</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="postcode" style="width:100px;" value="<?php echo stripslashes($record[10]); ?>"/> *</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Wedding Date</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="wedding_date" id="wedding_date" style="width:100px;" value="<?php echo stripslashes($wd); ?>"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'wedding_date'
	});

	</script></div>
        <div style="float:left; margin:10px;">	at </div>
    <div style="float:left; margin:5px;">	<input type="text" name="wedding_time" style="width:100px;" value="<?php echo stripslashes($record[25]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Destination</b></div>
<div style="float:left; margin:5px;"><select name="destination" class="inputbox_town" size="10" style="width:300px;"><?php echo $nav_list; ?></select></div>
<div style="clear:left;"></div>

<p>* - Required Fields</p>



<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update Client"></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_GET["id"]; ?>'"></div>
<div style="clear:both;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

?>