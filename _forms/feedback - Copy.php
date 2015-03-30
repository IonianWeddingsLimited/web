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
<div style="position:relative;">
<form action="<?php echo $site_url; ?>/formsubmit.php" class="pageform" id="feedback" method="post" name="feedback">
    <input type="hidden" name="page" value="feedback">
	<div class="formheader">
<?php echo stripslashes($level1_record[2]); ?> 
	</div>

	<div class="formheader">
	</div>
	<div class="formrow">
		<label class="formlabel" for="yourname">Your Name<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="yourname" name="yourname" type="text" value="<?php echo $_SESSION["yourname"]; ?>" />

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="how_you_heard">How did you hear about Ionian Weddings<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="how_you_heard" name="how_you_heard" type="text" value="<?php echo $_SESSION["how_you_heard"]; ?>" />
		</div>

		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="why_greece">Why did you choose Greece / Cyprus and the particular island for your
wedding<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="why_greece" name="why_greece" type="text" value="<?php echo $_SESSION["why_greece"]; ?>" />
		</div>
		<div class="clear"></div>

	</div>
	<div class="formrow">
		<label class="formlabel" for="why_choose_us">Why did you choose to book with Ionian Weddings<span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="why_choose_us" name="why_choose_us" type="text" value="<?php echo $_SESSION["why_choose_us"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="formheader">
		<h2>Were you happy with</h2>
	</div>
    

	<div class="formrow">
		<label class="formlabel" for="our_wesite">&nbsp;</label>
		<div class="formelement">
			 1 - Strongly agree / 2 - Agree / 3 - Neither agree nor disagre / 4 - Disagree / 5 - Strongly disagree

		</div>
		<div class="clear"></div>
	</div>



	<div class="formrow">
		<label class="formlabel" for="our_wesite">Our website</label>
		<div class="formelement">
			<input type="radio" name="our_wesite" value="1" <?php if($_SESSION["our_wesite"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="our_wesite" value="2" <?php if($_SESSION["our_wesite"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="our_wesite" value="3" <?php if($_SESSION["our_wesite"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="our_wesite" value="4" <?php if($_SESSION["our_wesite"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="our_wesite" value="5" <?php if($_SESSION["our_wesite"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="speed_of_response">Speed of responses </label>
		<div class="formelement">
						<input type="radio" name="speed_of_response" value="1" <?php if($_SESSION["speed_of_response"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="speed_of_response" value="2" <?php if($_SESSION["speed_of_response"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="speed_of_response" value="3" <?php if($_SESSION["speed_of_response"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="speed_of_response" value="4" <?php if($_SESSION["speed_of_response"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="speed_of_response" value="5" <?php if($_SESSION["speed_of_response"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="ease_of_booking">Ease of booking </label>
		<div class="formelement">
					<input type="radio" name="ease_of_booking" value="1" <?php if($_SESSION["ease_of_booking"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="ease_of_booking" value="2" <?php if($_SESSION["ease_of_booking"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="ease_of_booking" value="3" <?php if($_SESSION["ease_of_booking"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="ease_of_booking" value="4" <?php if($_SESSION["ease_of_booking"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="ease_of_booking" value="5" <?php if($_SESSION["ease_of_booking"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="information_given">Information given </label>
		<div class="formelement">
					<input type="radio" name="information_given" value="1" <?php if($_SESSION["information_given"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="information_given" value="2" <?php if($_SESSION["information_given"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="information_given" value="3" <?php if($_SESSION["information_given"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="information_given" value="4" <?php if($_SESSION["information_given"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="information_given" value="5" <?php if($_SESSION["information_given"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="wedding_guide">Wedding Guide  </label>
		<div class="formelement">
					<input type="radio" name="wedding_guide" value="1" <?php if($_SESSION["wedding_guide"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_guide" value="2" <?php if($_SESSION["wedding_guide"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_guide" value="3" <?php if($_SESSION["wedding_guide"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_guide" value="4" <?php if($_SESSION["wedding_guide"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_guide" value="5" <?php if($_SESSION["wedding_guide"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="wedding_checklist">Wedding Checklist </label>
		<div class="formelement">
					<input type="radio" name="wedding_checklist" value="1" <?php if($_SESSION["wedding_checklist"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_checklist" value="2" <?php if($_SESSION["wedding_checklist"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_checklist" value="3" <?php if($_SESSION["wedding_checklist"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_checklist" value="4" <?php if($_SESSION["wedding_checklist"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_checklist" value="5" <?php if($_SESSION["wedding_checklist"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="face_to_face_meetings">Face to face meetings  </label>
		<div class="formelement">
					<input type="radio" name="face_to_face_meetings" value="1" <?php if($_SESSION["face_to_face_meetings"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="face_to_face_meetings" value="2" <?php if($_SESSION["face_to_face_meetings"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="face_to_face_meetings" value="3" <?php if($_SESSION["face_to_face_meetings"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="face_to_face_meetings" value="4" <?php if($_SESSION["face_to_face_meetings"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="face_to_face_meetings" value="5" <?php if($_SESSION["face_to_face_meetings"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="paperwork">Paperwork (if done by us) </label>
		<div class="formelement">
					<input type="radio" name="paperwork" value="1" <?php if($_SESSION["paperwork"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="paperwork" value="2" <?php if($_SESSION["paperwork"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="paperwork" value="3" <?php if($_SESSION["paperwork"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="paperwork" value="4" <?php if($_SESSION["paperwork"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="paperwork" value="5" <?php if($_SESSION["paperwork"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="wedding_planner">Wedding planner </label>
		<div class="formelement">
					<input type="radio" name="wedding_planner" value="1" <?php if($_SESSION["wedding_planner"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_planner" value="2" <?php if($_SESSION["wedding_planner"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_planner" value="3" <?php if($_SESSION["wedding_planner"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_planner" value="4" <?php if($_SESSION["wedding_planner"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_planner" value="5" <?php if($_SESSION["wedding_planner"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="wedding_location">Wedding location  </label>
		<div class="formelement">
					<input type="radio" name="wedding_location" value="1" <?php if($_SESSION["wedding_location"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_location" value="2" <?php if($_SESSION["wedding_location"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_location" value="3" <?php if($_SESSION["wedding_location"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_location" value="4" <?php if($_SESSION["wedding_location"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_location" value="5" <?php if($_SESSION["wedding_location"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="reception_location">Reception location  </label>
		<div class="formelement">
					<input type="radio" name="reception_location" value="1" <?php if($_SESSION["reception_location"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="reception_location" value="2" <?php if($_SESSION["reception_location"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="reception_location" value="3" <?php if($_SESSION["reception_location"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="reception_location" value="4" <?php if($_SESSION["reception_location"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="reception_location" value="5" <?php if($_SESSION["reception_location"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
        	<div class="formrow">
		<label class="formlabel" for="overall_service">Overall service provided </label>
		<div class="formelement">
					<input type="radio" name="overall_service" value="1" <?php if($_SESSION["overall_service"] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="overall_service" value="2" <?php if($_SESSION["overall_service"] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="overall_service" value="3" <?php if($_SESSION["overall_service"] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="overall_service" value="4" <?php if($_SESSION["overall_service"] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="overall_service" value="5" <?php if($_SESSION["overall_service"] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    
 	<div class="formheader">
		<h2>Wedding Details</h2>
	</div>
       
    	<div class="formrow">
		<label class="formlabel" for="book_accommodation_with">Who did you book your accommodation with: <span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="book_accommodation_with" name="book_accommodation_with" type="text" value="<?php echo $_SESSION["book_accommodation_with"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

       
    	<div class="formrow">
		<label class="formlabel" for="wedding_insurance">Who did you book your wedding insurance with: <span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="wedding_insurance" name="wedding_insurance" type="text" value="<?php echo $_SESSION["wedding_insurance"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

       
    	<div class="formrow">
		<label class="formlabel" for="how_many_guests">How many guests did you have at your wedding: <span class="required">*</span></label>
		<div class="formelement">
			<input class="formtextfieldlong" id="how_many_guests" name="how_many_guests" type="text" value="<?php echo $_SESSION["how_many_guests"]; ?>" />
		</div>
		<div class="clear"></div>
	</div>

       

 	<div class="formheader">
		<h2>Comments</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="comments">Let us know any comments you may have<span class="required">*</span></label>
		<div class="formelement">
			<textarea class="formtextarealong" col="30" id="comments" name="comments" rows="5"><?php echo $_SESSION["comments"]; ?></textarea>
		</div>
		<div class="clear"></div>
	</div>
   
    
	

	
	<div class="formheader">
		<h2>Please leave these boxes ticked if you are you happy for us to feature</h2>
	</div>
	
	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_testimonials">Your testimonials on our website.</label>
		<div class="formelement">
			<input name="contactus_callback" type="checkbox" class="formcheckbox" id="feature_testimonials" value="Yes" <?php if($_SESSION["feature_testimonials"] == "Yes") { echo "checked=\"checked\""; } ?>/>
		</div>
		<div class="clear"></div>
	</div>
    
	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_website">Your wedding photos on our website.</label>
		<div class="formelement">
			<input name="contactus_callback" type="checkbox" class="formcheckbox" id="feature_website" value="Yes" <?php if($_SESSION["feature_website"] == "Yes") { echo "checked=\"checked\""; } ?>/>
		</div>
		<div class="clear"></div>
	</div>
       
    	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_facebook">Your wedding photos on our Facebook page.</label>
		<div class="formelement">
			<input name="contactus_callback" type="checkbox" class="formcheckbox" id="feature_facebook" value="Yes" <?php if($_SESSION["feature_facebook"] == "Yes") { echo "checked=\"checked\""; } ?>/>
		</div>
		<div class="clear"></div>
	</div>
    
     	<div class="formheader">
		<h2>Testimonial</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="testimonial">Please write a testimonial that we could feature on our website <span class="required">*</span></label>
		<div class="formelement">
			<textarea class="formtextarealong" col="30" id="testimonial" name="testimonial" rows="5"><?php echo $_SESSION["testimonial"]; ?></textarea>
		</div>
		<div class="clear"></div>
	</div>
  
 <div class="formheader">
		<h2>Additional Options:</h2>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="privacypolicy">Tick this box if you would be happy to be contacted by another couple, to recommend our services:</label>
		<div class="formelement">

			<input class="formcheckbox" id="privacypolicy" name="privacypolicy" type="checkbox" value="Yes" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_termsandconditions">We are often contacted by newspapers and magazines interested in featuring
our wedding couples. Tick this box if you would be happy to appear in a
publication (we would contact you in this event of course):
</label>
		<div class="formelement">
			<input class="formcheckbox" id="termsandconditions" name="termsandconditions" type="checkbox" value="Yes" />

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
</div>