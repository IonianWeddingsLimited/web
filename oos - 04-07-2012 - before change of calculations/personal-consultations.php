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

if($_GET["action"] == "Continue") {

if(!$_GET["id"]) {
header("Location: $site_url/oos/personal-consultations.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_personal_consultations,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

$dateline = date("jS F Y",$record[13]);
$dateline2 = date("g:i a",$record[13]);

$get_template->topHTML();
?>
<h1>Form - Personal Consultations</h1>
<p><b>Submitted on:</b> <?php echo $dateline; ?> at <?php echo $dateline2; ?></p>

<script language="javascript" type="text/javascript">

function deletechecked()
{
    var answer = confirm("Confirm Delete")
    if (answer){
        document.messages.submit();
    }
    
    return false;  
}  

</script>
<div>
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="POST" class="pageform">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div class="formheader">
		<h1>Personal Details</h1>
	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_firstname">First name</label>
		<div class="formelement">
			<?php echo stripslashes($record[1]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_lastname">Last name</label>
		<div class="formelement">
			<?php echo stripslashes($record[2]); ?>
		</div>

		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_email">Email</label>
		<div class="formelement">
			<?php echo stripslashes($record[3]); ?>
		</div>
		<div class="clear"></div>

	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_tel">Telephone</label>
		<div class="formelement">
			<?php echo stripslashes($record[4]); ?>
		</div>
		<div class="clear"></div>
	</div>

	<div class="formrow">

		<label class="formlabel" for="contactus_address_1">Address Line 1</label>
		<div class="formelement">
			<?php echo stripslashes($record[5]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_address_2">Address Line 2</label>
		<div class="formelement">
			<?php echo stripslashes($record[6]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_address_3">Address Line 3</label>

		<div class="formelement">
			<?php echo stripslashes($record[7]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_town">Town/City</label>

		<div class="formelement">

			<?php echo stripslashes($record[8]); ?>
		</div>
		<div class="clear"></div>
	</div>
            	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_countryID">County</label>
		<div class="formelement">
		<?php echo stripslashes($record[18]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_countryID">Country</label>

		<div class="formelement">
		<?php echo stripslashes($record[9]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_postcode">Post code</label>

		<div class="formelement">
			<?php echo stripslashes($record[10]); ?>

		</div>
		<div class="clear"></div>
	</div>
		
	<div class="formheader">
		<h1>Comments</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="contactus_comments"><?php echo stripslashes($record[11]); ?></label>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h1>Your Consultation Appointment</h1>
	</div>

	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback">Would you like us to call you back?</label>

		<div class="formelement">

			<?php echo stripslashes($record[17]); ?>
		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback">Preferred days or dates for your consultation?</label>

		<div class="formelement">

			<?php echo stripslashes($record[15]); ?>
		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback">Preferred times for your consultation</label>

		<div class="formelement">

			<?php echo stripslashes($record[16]); ?>
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formheader">
		<h1>Latest Offers</h1>
	</div>

	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback">Receive latest offers, discounts, products and services.</label>

		<div class="formelement">

			<?php echo stripslashes($record[14]); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>

<?php if($_POST["action_type"] == "view_archive") { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Remove From Archive">
</form></div>


<div style="float: left; ">
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left;">
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_personal_consultations,"archive='No'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_personal_consultations,"archive='Yes'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Message Archived</h1>

<p>The message has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_POST["action"] == "Create Client") {
	
$result = $sql_command->select($database_info_personal_consultations,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$_SESSION["title"] = "Miss";
$_SESSION["firstname"] = stripslashes($record[1]);
$_SESSION["lastname"] = stripslashes($record[2]);
$_SESSION["email"] = stripslashes($record[3]);
$_SESSION["tel"] = stripslashes($record[4]);
$_SESSION["address"] = stripslashes($record[5]);
$_SESSION["address2"] = stripslashes($record[6]);
$_SESSION["address3"] = stripslashes($record[7]);
$_SESSION["town"] = stripslashes($record[8]);
$_SESSION["country"] = stripslashes($record[9]);
$_SESSION["county"] = stripslashes($record[18]);
$_SESSION["postcode"] = stripslashes($record[10]);


header("Location: $site_url/oos/add-client.php");
$sql_command->close();	

} else {
	

$result = $sql_command->select($database_info_personal_consultations,"id,firstname,lastname,archive,timestamp","ORDER BY timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("jS F Y",$record[4]);


if($record[3] == "Yes") {
$archive_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])." ".stripslashes($record[2])."</option>\n";
} else {
$active_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])." ".stripslashes($record[2])."</option>\n";
}
}

$total_rows = $sql_command->count_rows($database_info_personal_consultations,"id","");

$preferred_times_result = $sql_command->select($database_info_personal_consultations,"preferred_time,count(*)","GROUP BY preferred_time ORDER BY preferred_time");
$preferred_times_row = $sql_command->results($preferred_times_result);

foreach($preferred_times_row as $preferred_times_record) {
if($preferred_times_record[0]) {
$preferred_times_html .= "<b>".stripslashes($preferred_times_record[0]).":</b> ".stripslashes($preferred_times_record[1])."</b><br>";
}
}

$plan_to_book_result = $sql_command->select($database_info_personal_consultations,"preferred_day,count(*)","GROUP BY preferred_day ORDER BY preferred_day");
$plan_to_book_row = $sql_command->results($plan_to_book_result);

foreach($plan_to_book_row as $plan_to_book_record) {
if($plan_to_book_record[0]) {
$plan_to_book_html .= "<b>".stripslashes($plan_to_book_record[0]).":</b> ".stripslashes($plan_to_book_record[1])."</b><br>";
}
}


if($_POST["action"] == "Download CSV") {
header("Location: ".$site_url."/oos/download-data.php?type=csv&data=".$_POST["data"]."&from=".$_POST["date_from"]."&to=".$_POST["date_to"]);
exit();
}

$get_template->topHTML();
?>
<h1>Form - Personal Consultations</h1>

<form method="post" action"<?php echo $site_url; ?>/wedding-questionaire.php" name="getcsvdata">
<input type="hidden" name="data" value="personal" />
<div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_from"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_from'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_to"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_to'
	});

	</script></div>
<div style="clear:left;"></div>
<input type="submit" name="action" value="Download CSV" />
</form>

<p><hr /></p>

<p><b>Active Messages</b></p>
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="get">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $active_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<p><hr /></p>
<p><b>Archived Messages</b></p>
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="get">
<input type="hidden" name="action" value="Continue" />
<input type="hidden" name="action_type" value="view_archive" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $archive_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<p><hr /></p>
<p><b>Personal Consultation Results</b> (Total: <?php echo $total_rows; ?>)</p>

<h2>Preferred times</h2>
<p><?php echo $preferred_times_html; ?></p>
<h2>Preferred Day</h2>
<p><?php echo $plan_to_book_html; ?></p>

<?
$get_template->bottomHTML();
$sql_command->close();
}

?>