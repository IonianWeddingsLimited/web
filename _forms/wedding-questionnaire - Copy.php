<?

$country_result = $sql_command->select($database_country,"value","ORDER BY value");
$country_row = $sql_command->results($country_result);

foreach($country_row as $country_record) {
$current = stripslashes($country_record[0]);
if($_SESSION["contactus_countryID"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["contactus_countryID"] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";

if($_SESSION["weddingquestionnaire_groom_countryofresidenceID"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["weddingquestionnaire_groom_countryofresidenceID"] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$groom_country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";

if($_SESSION["weddingquestionnaire_bride_countryofresidenceID"] == $current) { 
$selected = "selected=\"selected\""; 
} elseif(!$_SESSION["weddingquestionnaire_bride_countryofresidenceID"] and $current == "United Kingdom") {
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$bride_country_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}



$callback_result = $sql_command->select($database_preffered_time,"value","ORDER BY value");
$callback_row = $sql_command->results($callback_result);

foreach($callback_row as $callback_record) {
$current = stripslashes($callback_record[0]);
if($_SESSION["contactus_countryID"] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$callback_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}


$typeofceremony_result = $sql_command->select($database_typeofceremony,"value","ORDER BY value");
$typeofceremony_row = $sql_command->results($typeofceremony_result);

foreach($typeofceremony_row as $typeofceremony_record) {
$current = stripslashes($typeofceremony_record[0]);
if($_SESSION["weddingquestionnaire_typeofceremonyID"] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$typeofceremony_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}


$hearaboutus_result = $sql_command->select($database_hearaboutus,"value","ORDER BY value");
$hearaboutus_row = $sql_command->results($hearaboutus_result);

foreach($hearaboutus_row as $hearaboutus_record) {
$current = stripslashes($hearaboutus_record[0]);
if($_SESSION["weddingquestionnaire_marketingID"] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$hearaboutus_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}


$plantobook_result = $sql_command->select($database_plantobook,"value","");
$plantobook_row = $sql_command->results($plantobook_result);

foreach($plantobook_row as $plantobook_record) {
$current = stripslashes($plantobook_record[0]);
if($_SESSION["weddingquestionnaire_bookingtimeID"] == $current) { 
$selected = "selected=\"selected\""; 
} else {
$selected = ""; 
}
$plantobook_list .= "<option value=\"".$current."\" $selected>".$current."</option>";
}



$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);

foreach($destination_row as $$destination_record) {
$island_html = "";

$html .= "<div style=\"float:left; margin-right:10px;\">
<strong>Ionian Islands</strong>
<div style=\"float:left;\">";

$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes(destination_record[0])."' ORDER BY displayorder");
$destination_island_total = $sql_command->count_rows($database_navigation,"id","WHERE parent_id='".addslashes(destination_record[0])."'");
$destination_island_row = $sql_command->results($destination_island_result);

foreach($destination_island_row as $$destination_island) {
$island_html .= "<input class=\"formcheckbox\" id=\"weddingquestionnaire_corfu\" name=\"weddingquestionnaire_corfu\" type=\"checkbox\" value=\"Yes\" /> Corfu<br>";
}

$html .= "</div>
<div style=\"clear:left;\"></div>
</div>";	
}

?>

<form action="<?php echo $site_url; ?>/formsubmit.php" class="pageform" id="weddingquestionnaire" method="post" name="weddingquestionnaire">
<input type="hidden" name="page" value="weddingquestionnaire">
	<div class="formheader">
<?php echo stripslashes($level1_record[2]); ?> 
	</div>
	<div class="formheader">
		<h1>Personal Details</h1>
		<h2>Bride</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_firstname">First name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_bride_firstname" name="weddingquestionnaire_bride_firstname" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_firstname"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_bride_lastname">Last name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_bride_lastname" name="weddingquestionnaire_bride_lastname" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_lastname"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_email">Email<span class="required">*</span></label>

		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_bride_email" name="weddingquestionnaire_bride_email" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_email"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_tel">Telephone<span class="required">*</span></label>
		<div class="formelement">

			<input class="formtextfieldlong" id="weddingquestionnaire_bride_tel" name="weddingquestionnaire_bride_tel" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_tel"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_nationality">Nationality<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_bride_nationality" name="weddingquestionnaire_bride_nationality" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_nationality"]; ?>" />

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bride_countryofresidenceID">Country of residence (current)<span class="required">*</span></label>
		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_bride_countryofresidenceID" name="weddingquestionnaire_bride_countryofresidenceID">
			<?php echo $bride_country_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h2>Groom</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_firstname">First name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_groom_firstname" name="weddingquestionnaire_groom_firstname" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_firstname"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_groom_lastname">Last name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_groom_lastname" name="weddingquestionnaire_groom_lastname" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_lastname"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_tel">Telephone<span class="required">*</span></label>

		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_groom_tel" name="weddingquestionnaire_groom_tel" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_tel"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_email">Email<span class="required">*</span></label>
		<div class="formelement">

			<input class="formtextfieldlong" id="weddingquestionnaire_groom_email" name="weddingquestionnaire_groom_email" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_email"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_nationality">Nationality<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_groom_nationality" name="weddingquestionnaire_groom_nationality" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_nationality"]; ?>" />

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_groom_countryofresidenceID">Country of residence (current)<span class="required">*</span></label>
		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_groom_countryofresidenceID" name="weddingquestionnaire_groom_countryofresidenceID">
				<?php echo $groom_country_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>Address details</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_address_1">Address Line 1<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_address_1" name="weddingquestionnaire_address_1" type="text" value="<?php echo $_SESSION["weddingquestionnaire_address_1"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_address_2">Address Line 2</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_address_2" name="weddingquestionnaire_address_2" type="text" value="<?php echo $_SESSION["weddingquestionnaire_address_2"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_address_3">Address Line 3</label>

		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_address_3" name="weddingquestionnaire_address_3" type="text" value="<?php echo $_SESSION["weddingquestionnaire_address_3"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_town">Town/City<span class="required">*</span></label>
		<div class="formelement">

			<input class="formtextfieldlong" id="weddingquestionnaire_town" name="weddingquestionnaire_town" value="<?php echo $_SESSION["weddingquestionnaire_town"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_countryID">Country<span class="required">*</span></label>
		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_countryID" name="weddingquestionnaire_countryID">
				<?php echo $country_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_postcode">Post code<span class="required">*</span></label>

		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_postcode" name="weddingquestionnaire_postcode" type="text" value="<?php echo $_SESSION["weddingquestionnaire_postcode"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>Preferred wedding destination(s)</h1>
	</div>

	<div class="formrow">
		<table border="0" class="checkboxblock" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan="2">*Ionian Islands</th>
				<td class="border" rowspan="4">&nbsp;</td>
				<th colspan="3">*Aegean Islands</th>
			</tr>
			<tr>

				<td><input class="formcheckbox" id="weddingquestionnaire_corfu" name="weddingquestionnaire_corfu" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_corfu"] == "Y") { echo "checked=\"checked\""; } ?>/> Corfu</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_lefkada" name="weddingquestionnaire_lefkada" type="checkbox" value="Y"<?php if($_SESSION["weddingquestionnaire_lefkada"] == "Y") { echo "checked=\"checked\""; } ?> /> Lefkada</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_amorgos" name="weddingquestionnaire_amorgos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_amorgos"] == "Y") { echo "checked=\"checked\""; } ?>/> Amorgos</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_mykonos" name="weddingquestionnaire_mykonos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_mykonos"] == "Y") { echo "checked=\"checked\""; } ?>/> Mykonos</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_santorini" name="weddingquestionnaire_santorini" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_santorini"] == "Y") { echo "checked=\"checked\""; } ?>/> Santorini</td>

			</tr>
			<tr>
				<td><input class="formcheckbox" id="weddingquestionnaire_ithaki" name="weddingquestionnaire_ithaki" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_ithaki"] == "Y") { echo "checked=\"checked\""; } ?>/> Ithaki</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_paxos" name="weddingquestionnaire_paxos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_paxos"] == "Y") { echo "checked=\"checked\""; } ?>/> Paxos</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_crete" name="weddingquestionnaire_crete" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_crete"] == "Y") { echo "checked=\"checked\""; } ?>/> Crete</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_rhodes" name="weddingquestionnaire_rhodes" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_rhodes"] == "Y") { echo "checked=\"checked\""; } ?>/> Rhodes</td>

				<td><input class="formcheckbox" id="weddingquestionnaire_skiathos" name="weddingquestionnaire_skiathos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_skiathos"] == "Y") { echo "checked=\"checked\""; } ?>/> Skiathos</td>
			</tr>
			<tr>
				<td><input class="formcheckbox" id="weddingquestionnaire_kefalonia" name="weddingquestionnaire_kefalonia" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_kefalonia"] == "Y") { echo "checked=\"checked\""; } ?>/> Kefalonia</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_zakynthos" name="weddingquestionnaire_zakynthos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_zakynthos"] == "Y") { echo "checked=\"checked\""; } ?>/> Zakynthos</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_kos" name="weddingquestionnaire_kos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_kos"] == "Y") { echo "checked=\"checked\""; } ?>/> kos</td>

				<td><input class="formcheckbox" id="weddingquestionnaire_samos" name="weddingquestionnaire_samos" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_samos"] == "Y") { echo "checked=\"checked\""; } ?>/> Samos</td>
				<td><input class="formcheckbox" id="weddingquestionnaire_cyprus" name="weddingquestionnaire_cyprus" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_cyprus"] == "Y") { echo "checked=\"checked\""; } ?>/> Cyprus</td>
			</tr>
		</table>
	</div>
	
	<div class="formheader">
		<h1>Date and Attendance</h1>

	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_date">Estimated date of wedding (dd/mm/yyyy)<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_date" name="weddingquestionnaire_date" type="text" value="<?php echo $_SESSION["weddingquestionnaire_date"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_guestcount">Anticipated number of guests<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_guestcount" name="weddingquestionnaire_guestcount" type="text" value="<?php echo $_SESSION["weddingquestionnaire_guestcount"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">

		<h1>Type of ceremony</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_typeofceremonyID">Type of ceremony<span class="required">*</span></label>
		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_typeofceremonyID" name="weddingquestionnaire_typeofceremonyID">
				<?php echo $typeofceremony_list; ?>
			</select>
		</div>
		<div class="clear"></div>

	</div>
	
	<div class="formheader">
		<h1>The Ceremony</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_ceremony">Preferred ceremony setting<span class="required">*</span></label>
		<div class="formelement">

			<input class="formtextfieldlong" id="weddingquestionnaire_ceremony" name="weddingquestionnaire_ceremony" type="text" value="<?php echo $_SESSION["weddingquestionnaire_ceremony"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>The Reception</h1>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_reception">Preferred reception setting<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_reception" name="weddingquestionnaire_reception" type="text" value="<?php echo $_SESSION["weddingquestionnaire_reception"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>Budgets and Finances</h1>

	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_budget">What is your estimated budget for your wedding day (excluding flights and accomodation)<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_budget" name="weddingquestionnaire_budget" type="text" value="<?php echo $_SESSION["weddingquestionnaire_budget"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h1>Comments</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_comments">Please provide as much information as possible so we can create the best wedding itinerary for you<span class="required">*</span></label>
		<div class="formelement">
			<textarea class="formtextarealong" col="30" id="weddingquestionnaire_comments" name="weddingquestionnaire_comments" rows="5"><?php echo $_SESSION["weddingquestionnaire_comments"]; ?></textarea>

		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h1>And finally... How did you hear about us?</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_marketingID">How did you hear about Ionian Weddings?<span class="required">*</span></label>

		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_marketingID" name="weddingquestionnaire_marketingID">
				<?php echo $hearaboutus_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>

	<div class="formheader">
		<h2>If through a recommendation</h2>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_recommendation">Who recommended us / where did you read about us?</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_recommendation" name="weddingquestionnaire_recommendation" type="text" value="<?php echo $_SESSION["weddingquestionnaire_recommendation"]; ?>" />

		</div>
		<div class="clear"></div>
	</div>
		
	<div class="formheader">
		<h1>Would you like us to call you back?</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callback">Yes. Please call me</label>

		<div class="formelement">
			<input class="formcheckbox" id="weddingquestionnaire_callback" name="weddingquestionnaire_callback" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_callback"] == "Y") { echo "checked=\"checked\""; } ?>/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callbackdate">Preferred days or dates</label>
		<div class="formelement">

			<input class="formtextfieldlong" id="weddingquestionnaire_callbackdate" name="weddingquestionnaire_callbackdate" type="text" value="<?php echo $_SESSION["weddingquestionnaire_callbackdate"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callbacktimeID">Preferred times</label>
		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_callbacktimeID" name="weddingquestionnaire_callbacktimeID">
<?php echo $callback_list; ?>
			</select>
		</div>
		<div class="clear"></div>

	</div>
	
	<div class="formheader">
		<h1>When do you plan to book your wedding day by?</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_bookingtimeID">When do you plan to book?<span class="required">*</span></label>
		<div class="formelement">

			<select class="formselectlong" id="weddingquestionnaire_bookingtimeID" name="weddingquestionnaire_bookingtimeID">
				<?php echo $plantobook_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="formheader">
		<h2>Terms &amp; Conditions and Privacy</h2>
	</div>

	<div class="formrow">
		<label class="formcheckboxlabel" for="weddingquestionnaire_privacypolicy"><span class="required">*</span>I confirm I have read, understood and agree to the Privacy Policy.  I confirm that Ionian Weddings is authorised to use my personal information.</label>
		<div class="formelement">
			<input class="formcheckbox" id="weddingquestionnaire_privacypolicy" name="weddingquestionnaire_privacypolicy" type="checkbox" value="Y" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formcheckboxlabel" for="weddingquestionnaire_termsandconditions"><span class="required">*</span>I confirm that I have read, understood and accept the Terms and Conditions</label>
		<div class="formelement">
			<input class="formcheckbox" id="weddingquestionnaire_termsandconditions" name="weddingquestionnaire_termsandconditions" type="checkbox" value="Y" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="weddingquestionnaire_callback"><span class="required">*</span>If you would <strong>not</strong> like to receive information on the latest offers, discounts, products and services from Ionian Weddings Ltd, please <strong>untick</strong> this box.</label>

		<div class="formelement">
			<input name="weddingquestionnaire_offers" type="checkbox" class="formcheckbox" id="weddingquestionnaire_offers" value="Y" checked />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_submit">&nbsp;</label>
		<div class="formelement">
			<input class="formsubmit" id="weddingquestionnaire_submit" name="weddingquestionnaire_submit" type="submit" value="Submit" />

			<input class="formreset" id="weddingquestionnaire_reset" name="weddingquestionnaire_reset" type="reset" value="Reset" />
		</div>
		<div class="clear"></div>
	</div>
</form>