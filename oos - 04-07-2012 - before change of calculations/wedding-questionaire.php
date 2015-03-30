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
header("Location: $site_url/oos/wedding-questionaire.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_wedding_questionnaire,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

$dateline = date("jS F Y",$record[34]);
$dateline2 = date("g:i a",$record[34]);








$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);

foreach($destination_row as $destination_record) {
$island_html = "";


$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

$count_row=0;
foreach($destination_island_row as $destination_island) {

if($count_row%4==0 and $count_row!=0) {
$island_html .= "</div><div class=\"dest_form2\" style=\"float:left; padding:5px;\">";
}

$destination_check_result = $sql_command->select($database_questionaire_destinations,"id","WHERE questionaire_id='".addslashes($record[0])."' AND destination_id='".addslashes($destination_record[0])."' AND island_id='".addslashes($destination_island[0])."'");
$destination_check_row = $sql_command->result($destination_check_result);

if($destination_check_row[0]) { $checked = "checked=\"checked\""; } else { $checked = ""; }

$island_html .= "<p><input class=\"formcheckbox\" name=\"".$destination_record[0]."-".$destination_island[0]."\" type=\"checkbox\" value=\"Yes\" $checked/> ".stripslashes($destination_island[1])."<p>";
$count_row++;
}

if($island_html) {
$html .= "<div class=\"dest_form\" style=\"float:left; margin-right:20px; padding: 0 5px 0 10px;\">
<strong>".stripslashes($destination_record[1])."</strong><br />
<div class=\"dest_form2\" style=\"float:left; padding:5px;\">
$island_html
</div>
<div style=\"clear:left;\"></div>
</div>";	
}
}






$get_template->topHTML();
?>
<h1>Form - Wedding Questionaire</h1>
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
<form action="<?php echo $site_url; ?>/oos/wedding-questionaire.php" method="POST" class="pageform">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div class="formheader">
		<h1>Personal Details</h1>
		<h2>Bride</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_firstname">First name</label>

		<div class="formelement">
			<?php echo stripslashes($record[1]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_bride_lastname">Last name</label>

		<div class="formelement">
			<?php echo stripslashes($record[2]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_email">Email</label>

		<div class="formelement">
			<?php echo stripslashes($record[3]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_tel">Telephone</label>
		<div class="formelement">

			<?php echo stripslashes($record[4]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_nationality">Nationality</label>
		<div class="formelement">
<?php echo stripslashes($record[5]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_countryofresidenceID">Country of residence (current)</label>
		<div class="formelement">
<?php echo stripslashes($record[6]); ?>

		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h2>Groom</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_firstname">First name</label>

		<div class="formelement">
			<?php echo stripslashes($record[7]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_groom_lastname">Last name</label>

		<div class="formelement">
			<?php echo stripslashes($record[8]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_tel">Telephone</label>

		<div class="formelement">
			<?php echo stripslashes($record[9]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_email">Email</label>
		<div class="formelement">
<?php echo stripslashes($record[10]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_nationality">Nationality</label>
		<div class="formelement">

		<?php echo stripslashes($record[11]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_countryofresidenceID">Country of residence (current)</label>
		<div class="formelement">

			<?php echo stripslashes($record[12]); ?>

		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>Address details</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_address_1">Address Line 1</label>

		<div class="formelement">
		<?php echo stripslashes($record[13]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_address_2">Address Line 2</label>
		<div class="formelement">
<?php echo stripslashes($record[14]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_address_3">Address Line 3</label>


		<div class="formelement">
		<?php echo stripslashes($record[15]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_town">Town/City</label>
		<div class="formelement">
<?php echo stripslashes($record[16]); ?>
		</div>
		<div class="clear"></div>
	</div>
	    	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_countryID">County</label>
		<div class="formelement">
		<?php echo stripslashes($record[36]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_countryID">Country</label>
		<div class="formelement">
		<?php echo stripslashes($record[17]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_postcode">Post code</label>

		<div class="formelement">
		<?php echo stripslashes($record[18]); ?>

		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>Preferred wedding destination(s)</h1>
	</div>

	<div class="formrow">
		<div class="dest_form" style="margin-left:20px;">
		<?php echo $html; ?>
        <div style="clear:left;"></div>
        <p style="margin-left:20px; color:#ccc;"><strong>Comments/Others:</strong><br /> <?php echo stripslashes($record[35]); ?></p>
        </div>

	</div>
	
	<div class="formheader">
		<h1>Date and Attendance</h1>

	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_date">Estimated date of wedding</label>
		<div class="formelement">
<?php echo stripslashes($record[19]); ?>
		</div>
		<div class="clear"></div>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_guestcount">Anticipated number of guests</label>
		<div class="formelement">
<?php echo stripslashes($record[20]); ?>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">

		<h1>Type of ceremony</h1>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_typeofceremonyID">Type of ceremony</label>
		<div class="formelement">
			<?php echo stripslashes($record[21]); ?>
		</div>

		<div class="clear"></div>

	</div>
	
	<div class="formheader">
		<h1>The Ceremony</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_ceremony">Preferred ceremony setting</label>

		<div class="formelement">
<?php echo stripslashes($record[22]); ?>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>The Reception</h1>
	</div>

	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_reception">Preferred reception setting</label>
		<div class="formelement">
			<?php echo stripslashes($record[23]); ?>
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h1>Budgets and Finances</h1>

	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_budget">What is your estimated budget for your wedding day (excluding flights and accomodation)</label>
		<div class="formelement">
			<?php echo stripslashes($record[24]); ?>
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h1>Comments</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_comments"><?php echo stripslashes($record[25]); ?></label>		
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>And finally... How did you hear about us?</h1>

	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_marketingID">How did you hear about Ionian Weddings?</label>

		<div class="formelement">
			<?php echo stripslashes($record[26]); ?>

		</div>
		<div class="clear"></div>
	</div>

	<div class="formheader">
		<h2>If through a recommendation</h2>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_recommendation">Who recommended us / where did you read about us?</label>

		<div class="formelement">
			<?php echo stripslashes($record[27]); ?>

		</div>
		<div class="clear"></div>
	</div>
		
	<div class="formheader">
		<h1>Would you like us to call you back?</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callback">Yes. Please call me</label>

		<div class="formelement">
		<?php echo stripslashes($record[28]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_callbackdate">Preferred days or dates</label>
		<div class="formelement">

			<?php echo stripslashes($record[29]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callbacktimeID">Preferred times</label>

		<div class="formelement">
			<?php echo stripslashes($record[30]); ?>
		</div>
		<div class="clear"></div>

	</div>

	
	<div class="formheader">
		<h1>When do you plan to book your wedding day by?</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bookingtimeID">When do you plan to book?</label>
		<div class="formelement">
<?php echo stripslashes($record[31]); ?>
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h1>Latest Offers</h1>
	</div>

	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback">Receive latest offers, discounts, products and services.</label>

		<div class="formelement">

			<?php echo stripslashes($record[33]); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>




<?php if($_POST["action_type"] == "view_archive") { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Remove From Archive">
</form></div>


<div style="float: left; ">
<form action="<?php echo $site_url; ?>/oos/wedding-questionaire.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left; ">
<form action="<?php echo $site_url; ?>/oos/wedding-questionaire.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>

<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_wedding_questionnaire,"archive='No'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	

} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_wedding_questionnaire,"archive='Yes'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Questionaire Archived</h1>

<p>The questionaire has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Create Client") {
	
$result = $sql_command->select($database_info_wedding_questionnaire,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$_SESSION["title"] = "Miss";
$_SESSION["firstname"] = stripslashes($record[1]);
$_SESSION["lastname"] = stripslashes($record[2]);
$_SESSION["email"] = stripslashes($record[3]);
$_SESSION["tel"] = stripslashes($record[4]);
$_SESSION["address"] = stripslashes($record[13]);
$_SESSION["address2"] = stripslashes($record[14]);
$_SESSION["address3"] = stripslashes($record[15]);
$_SESSION["town"] = stripslashes($record[16]);
$_SESSION["county"] = stripslashes($record[36]);
$_SESSION["country"] = stripslashes($record[17]);
$_SESSION["postcode"] = stripslashes($record[18]);
$_SESSION["wedding_date"] = stripslashes($record[19]);

if($record[33] == "Yes") { $_SESSION["mailinglist"] = "Yes"; } else {  $_SESSION["mailinglist"] = "No"; }


header("Location: $site_url/oos/add-client.php");
$sql_command->close();	
	
} else {
	

$result = $sql_command->select($database_info_wedding_questionnaire,"id,bride_firstname,bride_lastname,groom_firstname,groom_lastname,archive,timestamp","ORDER BY timestamp DESC");
$row = $sql_command->results($result);

$total_rows = 0;
foreach($row as $record) {
$total_rows++;

$dateline = date("jS F Y",$record[6]);

if($record[5] == "Yes") {
$archive_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])." ".stripslashes($record[2])." | ".stripslashes($record[3])." ".stripslashes($record[4])."</option>\n";
} else {
$active_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])." ".stripslashes($record[2])." | ".stripslashes($record[3])." ".stripslashes($record[4])."</option>\n";
}
}


$typeofceremony_result = $sql_command->select($database_info_wedding_questionnaire,"type_of_ceremony,count(*)","GROUP BY type_of_ceremony ORDER BY type_of_ceremony");
$typeofceremony_row = $sql_command->results($typeofceremony_result);

foreach($typeofceremony_row as $typeofceremony_record) {
$typeofceremony_html .= "<b>".stripslashes($typeofceremony_record[0]).":</b> ".stripslashes($typeofceremony_record[1])."</b><br>";
}

$hearaboutus_result = $sql_command->select($database_info_wedding_questionnaire,"hearaboutus,count(*)","GROUP BY hearaboutus ORDER BY hearaboutus");
$hearaboutus_row = $sql_command->results($hearaboutus_result);

foreach($hearaboutus_row as $hearaboutus_record) {
$hearaboutus_html .= "<b>".stripslashes($hearaboutus_record[0]).":</b> ".stripslashes($hearaboutus_record[1])."</b><br>";
}

$preferred_times_result = $sql_command->select($database_info_wedding_questionnaire,"callback_time,count(*)","GROUP BY callback_time ORDER BY callback_time");
$preferred_times_row = $sql_command->results($preferred_times_result);

foreach($preferred_times_row as $preferred_times_record) {
$preferred_times_html .= "<b>".stripslashes($preferred_times_record[0]).":</b> ".stripslashes($preferred_times_record[1])."</b><br>";
}

$plan_to_book_result = $sql_command->select($database_info_wedding_questionnaire,"plan_to_book,count(*)","GROUP BY plan_to_book ORDER BY plan_to_book");
$plan_to_book_row = $sql_command->results($plan_to_book_result);

foreach($plan_to_book_row as $plan_to_book_record) {
$plan_to_book_html .= "<b>".stripslashes($plan_to_book_record[0]).":</b> ".stripslashes($plan_to_book_record[1])."</b><br>";
}

$guests_result = $sql_command->select($database_info_wedding_questionnaire,"anticipated_number_of_guests,count(*)","GROUP BY anticipated_number_of_guests ORDER BY anticipated_number_of_guests");
$guests_row = $sql_command->results($guests_result);

$count_guests = 0;
foreach($guests_row as $guests_record) {


if($count_guests%25==0 and $count_guests!=0) {
$guests_html .= "</p></div><div style=\"float:left; width:100px;\"><p>";	
}

$guests_html .= "<b>".stripslashes($guests_record[0]).":</b> ".stripslashes($guests_record[1])."</b><br>";

$count_guests++;
}


$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);

foreach($destination_row as $destination_record) {
$island_html = "";


$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

$count_row=0;
foreach($destination_island_row as $destination_island) {

if($count_row%4==0 and $count_row!=0) {
$island_html .= "</div><div class=\"dest_form2\" style=\"float:left; padding:5px;\">";
}

$total_rows = $sql_command->count_rows($database_questionaire_destinations,"id","destination_id='".addslashes($destination_record[0])."' AND island_id='".addslashes($destination_island[0])."'");

$island_html .= "<p style=\"color:#000000;\"><b>".stripslashes($destination_island[1]).":</b> $total_rows<p>";
$count_row++;
}

if($island_html) {
$html .= "<div class=\"dest_form\" style=\"float:left; margin-right:20px; padding: 0 5px 0 0px;\">
<strong>".stripslashes($destination_record[1])."</strong><br />
<div class=\"dest_form2\" style=\"float:left; padding:5px;\">
$island_html
</div>
<div style=\"clear:left;\"></div>
</div>";	
}
}



if($_POST["action"] == "Download CSV") {
header("Location: ".$site_url."/oos/download-data.php?type=csv&data=".$_POST["data"]."&from=".$_POST["date_from"]."&to=".$_POST["date_to"]);
exit();
}



$get_template->topHTML();
?>
<h1>Form - Wedding Questionaire</h1>


<form method="post" action"<?php echo $site_url; ?>/wedding-questionaire.php" name="getcsvdata">
<input type="hidden" name="data" value="questionnaire" />
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

<p><b>Active Questionaire</b></p>
<form action="<?php echo $site_url; ?>/oos/wedding-questionaire.php" method="get">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $active_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<p><hr /></p>
<p><b>Archived Questionaire</b></p>
<form action="<?php echo $site_url; ?>/oos/wedding-questionaire.php" method="get">
<input type="hidden" name="action" value="Continue" />
<input type="hidden" name="action_type" value="view_archive" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $archive_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<p><hr /></p>
<p><b>Questionaire Results</b> (Total: <?php echo $total_rows; ?>)</p>

<h2>Types of Ceremonys</h2>
<p><?php echo $typeofceremony_html; ?></p>
<h2>Hear Aboutus</h2>
<p><?php echo $hearaboutus_html; ?></p>
<h2>Preferred times</h2>
<p><?php echo $preferred_times_html; ?></p>
<h2>Plan to Book</h2>
<p><?php echo $plan_to_book_html; ?></p>


<h2>Preferred wedding destination</h2>

		<div class="dest_form">
		<?php echo $html; ?>
        <div style="clear:left;"></div>
        </div>

<h2>Guests</h2>
<div style="float:left;  width:100px;">
<p><?php echo $guests_html; ?></p>
</div>
<div style="clear:left;"></div>


<?
$get_template->bottomHTML();
$sql_command->close();
}

?>