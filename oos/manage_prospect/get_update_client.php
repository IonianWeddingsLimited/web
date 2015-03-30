<?

if(!$_GET["id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","Missing Prospect ID","Link","oos/manage-prospect.php");
$get_template->bottomHTML();
$sql_command->close();
}


$result = $sql_command->select($database_clients,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

if($record[15]) { $dob = date("d-m-Y",$record[15]); }
if($record[24]) { $dob2 = date("d-m-Y",$record[24]); }

$planner_result = $sql_command->select("users","id, username","ORDER BY username");
$planner_row = $sql_command->results($planner_result);

foreach($planner_row as $planner_record) {
$currentValue	=	stripslashes($planner_record[0]);
$currentOption	=	stripslashes($planner_record[1]);
if($record[32] == $currentValue) { 
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
if($record[9] == $current) { 
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
if($record[34] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$record[34] and $current == "United Kingdom") {
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
if($record[35] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$record[35] and $current == "United Kingdom") {
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
	
	if($record[33] == $current) { 
		$selected = "selected=\"selected\""; 
	} else {
		$selected = ""; 
	}
	$typeofceremony_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}
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

$nav_list = str_replace("value=\"".$record[18]."\"","value=\"".$record[18]."\" selected=\"selected\"",$nav_list);

$add_header = "<script language=\"JavaScript\" src=\"$site_url/js/calendar_eu.js\"></script>
<link rel=\"stylesheet\" href=\"$site_url/css/calendar.css\">";

if ($the_username!="jay") {
/*$add_header .= "<script language=\"JavaScript\">
$(function() {
	$(\"#options .add\").click(function(){		
		$(\"#options #file\").click();
	});
	$(\"#options #file\").change(function() {
										  alert(\"hi\");
		$(\"#submit\").submit();
	});		  	   
		   
});
</script>";

		fileInput = $(this),
		selectedFile = fileInput.val();
   	if(selectedFile != '') {
       	form.ajaxSubmit({
		    success: function(response){
	    	},
	    	error: function(responseData, textStatus, errorThrown){
            },
	        complete: function(){
    	   		//upon completion we clear the file input value
    	   		fileInput.val('');
    	   	}
	    });
    }
*/
	
}
if ($the_username!="jay") {
$add_header .= "<script type=\"text/javascript\">

function close_imagemodule() {
$('#image_module_bg').hide();
$('#image_module_html').html();
$('#image_module_html').hide();
}

function remove_mugshot(imageid) {
	$.get('".$site_url."/oos/mugshot_view-file-module.php?a=remove&id=' + imageid, function(data){
	$('#image_module_html').html(data);
	$(\"#mugshot-img\").removeAttr(\"src\");
});

}

function open_imagemodule(folder,page,subfolder) {

$.get('".$site_url."/oos/mugshot_image-module.php?folder=' + folder + '&page=' + page + '&subfolder=' + subfolder + '&client_id=".$_GET['id']."', function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

function view_image(folder,fileid) {

$.get('".$site_url."/oos/mugshot_view-file-module.php?folder=' + folder + '&fileid=' + fileid + '&client_id=".$_GET['id']."', function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}
function open_awaitingcrop(id,page) {

$.get('".$site_url."/oos/mugshot_view-crop-module.php?id=' + id + '&page=' + page + '&client_id=".$_GET['id']."', function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

</script>
<style type=\"text/css\">
#image_module_html {
position:absolute;
display:none;
width: 900px;
height:700px;
z-index:1000;
background-color:#dcddde;
display:none;
text-align:left;
}

#image_module_bg {
position:fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index:999;
background-color:#000;
opacity:0.8;
display:none;
}
</style>";

$body_top = "<div id=\"image_module_bg\"></div><div id=\"image_module_html\"></div>";
}

$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'mugshot_add.php'; 
$max_file_size = 30000; // size in bytes 


if($record[17]) { $wd = date("d-m-Y",$record[17]); }

$option_query = $sql_command->select("clients_options",
									 "option_value",
									 "WHERE client_id='".$_GET["id"]."' AND client_option = 'default_currency'");
$option_result = $sql_command->result($option_query);
$currencydef = $option_result[0];

$get_template->topHTML();
?>

<h1>Manage Prospect</h1>
<?php echo $menu_line; ?>
<h2>Update Prospect</h2>
<div style="position:relative;">
	<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="post" name="client">
		<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
		<input type="hidden" name="ftype" value="Prospect" />
		<div style="float:left; width:160px; margin:5px;"><b>Client Type</b></div>
		<div style="float:left; margin:5px;">
			<select name="ctype" style="width:205px;">
				<option value="0">Client</option>
				<option value="1" selected="selected">Prospect</option>
			</select>
			*</div>
		<div style="clear:left;"></div>
				<div style="float:left; width:160px; margin:5px;"><b>Planner</b></div>
		<div style="float:left; margin:5px;">
			<select id="planner_id" name="planner_id" style="width: 205px;">
				<?php echo $planner_list?>
			</select>
			* </div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>IWCUID</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="iwcuid" style="width:200px;" value="<?php echo $record[19]; ?>"/>
			*</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Password</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="password" style="width:200px;" value="<?php echo $record[31]; ?>"/>
			*</div>
		<div style="clear:left;"></div>
		<?
$aboutus_q = $sql_command->select("clients_options","option_value","WHERE client_id = '".$_GET['id']."' and client_option = 'hearaboutus'");
$aboutus_r = $sql_command->result($aboutus_q);

$hearab = (strlen($aboutus_r[0])<1) ? "Unknown": $aboutus_r[0];
?>
		<div style="float:left; width:160px; margin:5px;"><b>Heard about us?</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="hearabout" style="width:200px;" value="<?php echo $hearab; ?>">
			* </div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Mailing List</b></div>
		<div style="float:left; margin:5px;">
			<select name="mailinglist">
				<option value="Yes" <?php if($record[16] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
				<option value="No" <?php if($record[16] == "No") { echo "selected=\"selected\""; } ?>>No</option>
			</select>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Display Currency</b></div>
		<div style="float:left; margin:5px;">
			<select name="displaycurrency">
				<option value="Not Applicable" <?php if($currencydef == "Not Applicable") { echo "selected=\"selected\""; } ?>>Not Applicable</option>
				<option value="Euro" <?php if($currencydef == "Euro") { echo "selected=\"selected\""; } ?>>Euro</option>
				<option value="Pound" <?php if($currencydef == "Pound") { echo "selected=\"selected\""; } ?>>Pound</option>
			</select>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;">&nbsp;</div>
		<div style="float:left; width: 220px; margin:5px;">
			<b>Bride</b>
		</div>
		<div style="float:left; margin:5px;">
			<b>Groom</b>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<select name="title">
				<option value="Mr" <?php if($record[1] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
				<option value="Miss" <?php if($record[1] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
				<option value="Ms" <?php if($record[1] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
				<option value="Dr" <?php if($record[1] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
			</select>
		</div>
		<div style="float:left; margin:5px;">
			<select name="groom_title">
				<option value="Mr" <?php if($record[21] == "Mr") { echo "selected=\"selected\""; } ?>>Mr</option>
				<option value="Miss" <?php if($record[21] == "Miss") { echo "selected=\"selected\""; } ?>>Miss</option>
				<option value="Ms" <?php if($record[21] == "Ms") { echo "selected=\"selected\""; } ?>>Ms</option>
				<option value="Dr" <?php if($record[21] == "Dr") { echo "selected=\"selected\""; } ?>>Dr</option>
			</select>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>First Name</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="firstname" style="width:200px;" value="<?php echo stripslashes($record[2]); ?>"/>*
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_firstname" style="width:200px;" value="<?php echo stripslashes($record[22]); ?>"/>*
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Last Name</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="lastname" style="width:200px;" value="<?php echo stripslashes($record[3]); ?>"/>*
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_surname" style="width:200px;" value="<?php echo stripslashes($record[23]); ?>"/>*
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>DOB</b> (Format DD-MM-YYYY)</div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="dob" id="dob" style="width:100px;" value="<?php echo $dob; ?>"/>
			<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'client',
		// input name
		'controlname': 'dob'
	});

	</script>
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_dob" id="groom_dob" style="width:100px;" value="<?php echo $dob2; ?>"/>
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
		<div style="float:left; width: 220px; margin:5px;">
			<b>Bride</b>
		</div>
		<div style="float:left; margin:5px;">
			<b>Groom</b>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Email</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="email" style="width:200px;" value="<?php echo stripslashes($record[11]); ?>"/>*
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_email" style="width:200px;" value="<?php echo stripslashes($record[26]); ?>"/>*
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Tel</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="tel" style="width:200px;" value="<?php echo stripslashes($record[12]); ?>"/>*
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_tel" style="width:200px;" value="<?php echo stripslashes($record[27]); ?>"/>*
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Mob</b></div>
		<div style="float:left; width: 220px; margin:5px;">
			<input type="text" name="mob" style="width:200px;" value="<?php echo stripslashes($record[13]); ?>"/>
		</div>
		<div style="float:left; margin:5px;">
			<input type="text" name="groom_mob" style="width:200px;" value="<?php echo stripslashes($record[28]); ?>"/>
		</div>
		<div style="clear:left;"></div>
		<!--<div style="float:left; width:160px; margin:5px;"><b>Bride Fax</b></div>
<div style="float:left; margin:5px;"><input type="text" name="fax" style="width:200px;" value="<?php echo stripslashes($record[14]); ?>"/></div>
<div style="float:left; margin:5px;">	<input type="text" name="groom_fax" style="width:200px;" value="<?php echo stripslashes($record[29]); ?>"/></div>

<div style="clear:left;"></div>-->
		<hr />
		<div style="float:left; width:160px; margin:5px;"><b>Address 1</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="address" style="width:200px;" value="<?php echo stripslashes($record[4]); ?>"/>
			*</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Address 2</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="address2" style="width:200px;" value="<?php echo stripslashes($record[5]); ?>"/>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Address 3</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="address3" style="width:200px;" value="<?php echo stripslashes($record[6]); ?>"/>
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>Town</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="town" style="width:200px;" value="<?php echo stripslashes($record[7]); ?>"/>
			*</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:160px; margin:5px;"><b>County</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="county" style="width:200px;" value="<?php echo stripslashes($record[8]); ?>"/>
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
			<input type="text" name="postcode" style="width:100px;" value="<?php echo stripslashes($record[10]); ?>"/>
			*</div>
		<div style="clear:left;"></div>
		<hr />
		<div style="float:left; width:160px; margin:5px;"><b>Wedding Date</b></div>
		<div style="float:left; margin:5px;">
			<input type="text" name="wedding_date" id="wedding_date" style="width:100px;" value="<?php echo stripslashes($wd); ?>"/>
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
			<input type="text" name="wedding_time" style="width:100px;" value="<?php echo stripslashes($record[25]); ?>"/>
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
				<option value="">Select type of ceremony...</option>
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
			<input name="bride_passport" type="radio" <?php if($record[36] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_passport" type="radio" <?php if($record[36] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_passport" type="radio" <?php if($record[37] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_passport" type="radio" <?php if($record[37] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Do you have a full Birth Certificate (with names of both parents)?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_birth_certificate" type="radio" <?php if($record[38] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_birth_certificate" type="radio" <?php if($record[38] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_birth_certificate" type="radio" <?php if($record[39] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_birth_certificate" type="radio" <?php if($record[39] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Are you divorced?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_divorced" type="radio" <?php if($record[40] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_divorced" type="radio" <?php if($record[40] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_divorced" type="radio" <?php if($record[41] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_divorced" type="radio" <?php if($record[41] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Are you adopted?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_adopted" type="radio" <?php if($record[42] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_adopted" type="radio" <?php if($record[42] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_adopted" type="radio" <?php if($record[43] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_adopted" type="radio" <?php if($record[43] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Have you been widowed?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_widowed" type="radio" <?php if($record[44] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_widowed" type="radio" <?php if($record[44] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_widowed" type="radio" <?php if($record[45] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_widowed" type="radio" <?php if($record[45] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Have you changed your name by Deed Poll?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_deed_poll" type="radio" <?php if($record[46] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_deed_poll" type="radio" <?php if($record[46] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_deed_poll" type="radio" <?php if($record[47] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_deed_poll" type="radio" <?php if($record[47] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Have you been baptised? (For religious ceremonies Only)</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_baptised" type="radio" <?php if($record[48] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_baptised" type="radio" <?php if($record[48] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_baptised" type="radio" <?php if($record[49] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_baptised" type="radio" <?php if($record[49] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="clear:left;"></div>
		<div style="float:left; width:450px; margin:5px;"><b>Do you have your baptism certificate?</b></div>
		<div style="float:left; width: 100px; margin:5px;">
			<input name="bride_baptised_certificate" type="radio" <?php if($record[50] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="bride_baptised_certificate" type="radio" <?php if($record[50] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; margin:5px;">
			<input name="groom_baptised_certificate" type="radio" <?php if($record[51] == "Yes") { echo "checked"; } ?> value="Yes" /> Yes
			<input name="groom_baptised_certificate" type="radio" <?php if($record[51] == "No") { echo "checked"; } ?> value="No" /> No
		</div>
		<div style="float:left; width:450px; margin:5px;"><b>I have read, understood and agree to be bound by Ionian Weddings' Terms and Conditions</b></div>
		<div style="float:left; margin:5px;">
				<input name="tandc" type="checkbox" <?php if($record[52] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
				You can read them by clicking <a href="http://www.ionianweddings.co.uk/terms-and-conditions/" target="_blank" style="color:#c08827;">here</a>
		</div>
		<div style="float:left; width:450px; margin:5px;"><b>I confirm that I am over 18 years of age</b></div>
		<div style="float:left; margin:5px;">
				<input name="over18" type="checkbox" <?php if($record[53] == "Yes") { echo "checked"; } ?> value="Yes" style="vertical-align: middle;" />
		</div>
		<div style="clear:left;"></div>
		<p>* - Required Fields</p>
		<div style="float:left; margin-top:10px;">
			<input type="submit" name="action" value="Update Client">
		</div>
		<div style="float:right; margin-top:10px; margin-right:10px;">
			<input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=history&id=<?php echo $_GET["id"]; ?>'">
		</div>
		<div style="clear:both;"></div>
	</form>
	<?

if ($the_username!="jay") {
	$images_q = $sql_command->select("clients_options","*","WHERE client_id='".addslashes($_GET['id'])."' AND client_option ='mugshot' and additional='Active'");
	$images_r = $sql_command->result($images_q);
?>
	<div id="mugshot-wrap">
		<div id="mugshot">
			<?php if ($images_r) { echo "<img id=\"mugshot-img\" src=\"".$site_url."/images/imageuploads/mugshot/".$images_r[3]."\" width=\"100%\" height=\"100%\" />"; } ?>
			<div id="options"> <a href="#" class="add" onclick="open_imagemodule('mugshot','','<?php echo $_GET['id']; ?>');"> <span >Add Photo</span>&nbsp; </a> <a href="#" class="remove" onclick="remove_mugshot('<?php echo $_GET['id']; ?>');"> <span >Remove Photo</span>&nbsp; </a> </div>
		</div>
	</div>
</div>
<?
}
echo "</div>";
$_SESSION['mugshot_clid'] = 0;
$_SESSION['mugshot_clid'] = $_GET['id'];
$get_template->bottomHTML();
$sql_command->close();

?>
