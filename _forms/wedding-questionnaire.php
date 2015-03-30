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
$total_des = 0;
foreach($destination_row as $destination_record) {
$island_html = "";
if($total_des%4==0  and $total_des!=0) {
$html .= "<div class=\"clear\"></div>";
}
$total_des++;

$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

$count_row=0;
foreach($destination_island_row as $destination_island) {

if($count_row%4==0 and $count_row!=0) {
$island_html .= "</div><div class=\"dest_form2\" style=\"float:left; padding:5px;\">";
}


$island_html .= "<p><input class=\"checkisland\" name=\"".$destination_record[0]."_".$destination_island[0]."\" type=\"checkbox\" value=\"Yes\" onclick=\"runcheckisland();\"/> ".stripslashes($destination_island[1])."<p>";
$count_row++;
}

if($island_html) {
$html .= "<div class=\"dest_form\">
<strong>".stripslashes($destination_record[1])."</strong><br />
<div class=\"dest_form2\" style=\"float:left; padding:5px;\">
$island_html
</div>
<div style=\"clear:left;\"></div>
</div>";	
} else {
$html .= "<div class=\"dest_form\">
<strong>".stripslashes($destination_record[1])."</strong><br />
<div class=\"dest_form2\" style=\"float:left; padding:5px;\">
<p><input class=\"checkisland\" name=\"".$destination_record[0]."\" type=\"checkbox\" value=\"Yes\" onclick=\"runcheckisland();\"/> ".stripslashes($destination_record[1])."<p>
</div>
<div style=\"clear:left;\"></div>
</div>";		
}
}


			



?>
<script type="text/javascript">


function runcheckisland() {

var selectedCheckboxCount = 0;
    $("input[type=checkbox].checkisland").each(function() {
        if ($(this).is(":checked")) {
            selectedCheckboxCount++;
        }
    });
	


if (selectedCheckboxCount == 4) {
alert("If you require information for more than 3 islands, please put that in the comments box and we will send you appropriate suggestions");
}

$.fn.limit = function(n) {
  var self = this;
  this.click(function(){ return (self.filter(":checked").length<=n); });
}
$("input[type=checkbox].checkisland").limit(3);


}


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
<script>
	$(function() {
		$( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
	});
	</script>
    
    
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

			<input class="formtextfieldlong" id="weddingquestionnaire_bride_tel" name="weddingquestionnaire_bride_tel" type="text" value="<?php echo $_SESSION["weddingquestionnaire_bride_tel"]; ?>" onchange=" if (!checkUKTelephone(this.value)) { alert (telNumberErrors[telNumberErrorNo]); }"/>
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
			<input class="formtextfieldlong" id="weddingquestionnaire_groom_tel" name="weddingquestionnaire_groom_tel" type="text" value="<?php echo $_SESSION["weddingquestionnaire_groom_tel"]; ?>" onchange=" if (!checkUKTelephone(this.value)) { alert (telNumberErrors[telNumberErrorNo]); }"/>
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

		<label class="formlabel" for="contactus_county">County<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="contactus_county" name="contactus_county" value="<?php echo $_SESSION["contactus_county"]; ?>" />
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
		<div class="dest_form">
			<?php echo $html; ?>
			<div class="clear"></div>
        </div>
	</div>
	<div class="formheader">
		<h2>Comments/Others:</h2>
	</div>
	<div class="formrow">
		<label class="formlabel" for="destination_other">Add any other preferred destination(s) or comments:</label>
		<div class="formelement">
			<textarea class="formtextarealong" col="30" id="destination_other" name="destination_other" rows="5"><?php echo $_SESSION["destination_other"]; ?></textarea>
		</div>
	</div>
	
	<div class="formheader">
		<h1>Date and Attendance</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_date">Estimated date of wedding (dd/mm/yyyy)<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="datepicker" name="weddingquestionnaire_date" type="text" style="width:100px;" value="<?php echo $_SESSION["weddingquestionnaire_date"]; ?>" />
		</div>
        <div class="formelementnote">DD-MM-YYYY</div>
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
		<h1>And finally... How did you hear about us?</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_marketingID">How did you hear about Ionian Weddings?<span class="required">*</span></label>

		<div class="formelement">
			<input type="text" class="formselectlong" id="weddingquestionnaire_marketingID" name="weddingquestionnaire_marketingID">
<!--				<?php // echo $hearaboutus_list; ?>
			</select> -->
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