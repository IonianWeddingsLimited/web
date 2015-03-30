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
}

?>

<form action="<?php echo $site_url; ?>/formsubmit.php" class="pageform" id="contactus" method="post" name="contactus">
    <input type="hidden" name="page" value="contactus">
	<div class="formheader">
<?php echo stripslashes($level1_record[2]); ?> 
	</div>

	<div class="formheader">
		<h1>Personal Details</h1>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="contactus_firstname">First name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_firstname" name="contactus_firstname" type="text" value="<?php echo $_SESSION["contactus_firstname"]; ?>" />

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_lastname">Last name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_lastname" name="contactus_lastname" type="text" value="<?php echo $_SESSION["contactus_lastname"]; ?>" />
		</div>

		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_email">Email<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_email" name="contactus_email" type="text" value="<?php echo $_SESSION["contactus_email"]; ?>" />
		</div>
		<div class="clear"></div>

	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_tel">Telephone<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_tel" name="contactus_tel" type="text" value="<?php echo $_SESSION["contactus_tel"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="formrow">
		<label class="formlabel" for="contactus_address_1">Address Line 1<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_address_1" name="contactus_address_1" type="text" value="<?php echo $_SESSION["contactus_address_1"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="contactus_address_2">Address Line 2</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_address_2" name="contactus_address_2" type="text" value="<?php echo $_SESSION["contactus_address_2"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_address_3">Address Line 3</label>

		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_address_3" name="contactus_address_3" type="text" value="<?php echo $_SESSION["contactus_address_3"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_town">Town/City<span class="required">*</span></label>
		<div class="formelement">

			<input class="formtextfieldlong" id="contactus_town" name="contactus_town" value="<?php echo $_SESSION["contactus_town"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
        	<div class="formrow">

		<label class="formlabel" for="contactus_county">County<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_county" name="contactus_county" value="<?php echo $_SESSION["contactus_county"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_countryID">Country<span class="required">*</span></label>
		<div class="formelement">
			<select class="formselectlong" id="contactus_countryID" name="contactus_countryID">
<?php echo $country_list; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_postcode">Post code<span class="required">*</span></label>

		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_postcode" name="contactus_postcode" type="text" value="<?php echo $_SESSION["contactus_postcode"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
		
	<div class="formheader">
		<h1>Comments</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="contactus_comments">Let us know any comments you may have<span class="required">*</span></label>
		<div class="formelement">
			<textarea class="formtextarealong" col="30" id="contactus_comments" name="contactus_comments" rows="5"><?php echo $_SESSION["contactus_comments"]; ?></textarea>
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="formheader">
		<h2>Terms &amp; Conditions and Privacy</h2>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_privacypolicy"><span class="required">*</span>I confirm I have read, understood and agree to the Privacy Policy.  I confirm that Ionian Weddings is authorised to use my personal information.</label>
		<div class="formelement">

			<input class="formcheckbox" id="contactus_privacypolicy" name="contactus_privacypolicy" type="checkbox" value="Y" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_termsandconditions"><span class="required">*</span>I confirm that I have read, understood and accept the Terms and Conditions</label>
		<div class="formelement">
			<input class="formcheckbox" id="contactus_termsandconditions" name="contactus_termsandconditions" type="checkbox" value="Y" />

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_callback"><span class="required">*</span>If you would <strong>not</strong> like to receive information on the latest offers, discounts, products and services from Ionian Weddings Ltd, please <strong>untick</strong> this box.</label>

		<div class="formelement">
			<input name="contactus_callback" type="checkbox" class="formcheckbox" id="contactus_callback" value="Y" checked />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_submit">&nbsp;</label>
		<div class="formelement">
			<input class="formsubmit" id="contactus_submit" name="contactus_submit" type="submit" value="Submit" />

			<input class="formreset" id="contactus_reset" name="contactus_reset" type="reset" value="Reset" />
		</div>
		<div class="clear"></div>
	</div>
</form>