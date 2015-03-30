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




?>
<script type="text/javascript">
function checkUKTelephone (telephoneNumber) {

  // Convert into a string and check that we were provided with something
  var telnum = telephoneNumber + " ";
  if (telnum.length == 1)  {
     telNumberErrorNo = 1;
     return false
  }
  telnum.length = telnum.length - 1;
  
  // Don't allow country codes to be included (assumes a leading "+")
  var exp = /^(\+)[\s]*(.*)$/;
  if (exp.test(telnum) == true) {
     telNumberErrorNo = 2;
     return false;
  }
  
  // Remove spaces from the telephone number to help validation
  while (telnum.indexOf(" ")!= -1)  {
    telnum = telnum.slice (0,telnum.indexOf(" ")) + telnum.slice (telnum.indexOf(" ")+1)
  }
  
  // Remove hyphens from the telephone number to help validation
  while (telnum.indexOf("-")!= -1)  {
    telnum = telnum.slice (0,telnum.indexOf("-")) + telnum.slice (telnum.indexOf("-")+1)
  }  
  
  // Now check that all the characters are digits
  exp = /^[0-9]{10,11}$/;
  if (exp.test(telnum) != true) {
     telNumberErrorNo = 3;
     return false;
  }
  
  // Now check that the first digit is 0
  exp = /^0[0-9]{9,10}$/;
  if (exp.test(telnum) != true) {
     telNumberErrorNo = 4;
     return false;
  }
	
	// Disallow numbers allocated for dramas.
	 
  // Array holds the regular expressions for the drama telephone numbers
  var tnexp = new Array ();
	tnexp.push (/^(0113|0114|0115|0116|0117|0118|0121|0131|0141|0151|0161)(4960)[0-9]{3}$/);
	tnexp.push (/^02079460[0-9]{3}$/);
	tnexp.push (/^01914980[0-9]{3}$/);
	tnexp.push (/^02890180[0-9]{3}$/);
	tnexp.push (/^02920180[0-9]{3}$/);
	tnexp.push (/^01632960[0-9]{3}$/);
	tnexp.push (/^07700900[0-9]{3}$/);
	tnexp.push (/^08081570[0-9]{3}$/);
	tnexp.push (/^09098790[0-9]{3}$/);
	tnexp.push (/^03069990[0-9]{3}$/);
	
	for (var i=0; i<tnexp.length; i++) {
    if ( tnexp[i].test(telnum) ) {
      telNumberErrorNo = 5;
      return false;
    }
	}
  
  // Finally check that the telephone number is appropriate.
  exp = (/^(01|02|03|05|070|071|072|073|074|075|07624|077|078|079)[0-9]+$/);
	if (exp.test(telnum) != true) {
     telNumberErrorNo = 5;
     return false;
  }
  
  // Telephone number seems to be valid - return the stripped telehone number  
  return telnum;
}
var telNumberErrorNo = 0;
var telNumberErrors = new Array ();
telNumberErrors[0] = "Valid UK telephone number";
telNumberErrors[1] = " number not provided";
telNumberErrors[2] = " number without the country code, please";
telNumberErrors[3] = " numbers should contain 10 or 11 digits";
telNumberErrors[4] = " number should start with a 0";
telNumberErrors[5] = " number is either invalid or inappropriate";
</script>

<form action="<?php echo $site_url; ?>/formsubmit.php" class="pageform" id="contactus" method="post" name="contactus">
<input type="hidden" name="page" value="personalconsultations">
	<div class="formheader">
<?php echo stripslashes($level1_record[2]); ?> 
	</div>
	<div class="formheader">
		<h1>Reserve A Consultation</h1>

		<h2>Personal Details</h2>
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
			<input class="formtextfieldlong" id="contactus_tel" name="contactus_tel" type="text" value="<?php echo $_SESSION["contactus_tel"]; ?>" onchange=" if (!checkUKTelephone(this.value)) { alert (telNumberErrors[telNumberErrorNo]); }"/>
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
		<h1>Your Consultation Appointment</h1>
	</div>

	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callback">Would you like us to call you back? (Yes. Please call me)</label>
		<div class="formelement">
			<input class="formcheckbox" id="weddingquestionnaire_callback" name="weddingquestionnaire_callback" type="checkbox" value="Y" <?php if($_SESSION["weddingquestionnaire_callback"] == "Y") { echo "checked=\"checked\""; } ?>/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">

		<label class="formlabel" for="weddingquestionnaire_callbackdate">Preferred days or dates for your consultation</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="weddingquestionnaire_callbackdate" name="weddingquestionnaire_callbackdate" type="text" value="<?php echo $_SESSION["weddingquestionnaire_callbackdate"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_callbacktimeID">Preferred times for your consultation</label>

		<div class="formelement">
			<select class="formselectlong" id="weddingquestionnaire_callbacktimeID" name="weddingquestionnaire_callbacktimeID">
			<?php echo $callback_list; ?>
			</select>

		</div>
		<div class="clear"></div>
	</div>
		
	<div class="formheader">
		<h1>Comments</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="contactus_comments">Let us know any ideas you may have</label>

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