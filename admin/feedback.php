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
$get_template = new admin_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_GET["action"] == "Continue") {
	
if(!$_GET["id"]) {
header("Location: $site_url/admin/feedback.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_feedback,"*","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

$dateline = date("jS F Y",$record[28]);
$dateline2 = date("g:i a",$record[28]);

$get_template->topHTML();
?>
<h1>Form - Feedback</h1>
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
<form action="<?php echo $site_url; ?>/admin/feedback.php" method="POST" class="pageform">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
	<div class="formheader">
<?php echo stripslashes($level1_record[2]); ?> 
	</div>

	<div class="formheader">
	</div>
	<div class="formrow">
		<label class="formlabel" for="yourname">first Name<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[1]); ?>

		</div>
		<div class="clear"></div>
	</div>
        	<div class="formrow">
		<label class="formlabel" for="yourname">Surname<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[29]); ?>

		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formlabel" for="yourname">IWCUID<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[30]); ?>

		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formlabel" for="yourname">Date of Wedding<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[31]); ?>

		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="how_you_heard">How did you hear about Ionian Weddings<span class="required">*</span></label>
		<div class="formelement">
		<?php echo stripslashes($record[2]); ?>
		</div>

		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel" for="why_greece">Why did you choose Greece / Cyprus and the particular island for your
wedding<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[3]); ?>
		</div>
		<div class="clear"></div>

	</div>
	<div class="formrow">
		<label class="formlabel" for="why_choose_us">Why did you choose to book with Ionian Weddings<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[4]); ?>
		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formlabel" for="why_choose_us">Location<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[32]); ?>
		</div>
		<div class="clear"></div>
	</div>
    	<div class="formrow">
		<label class="formlabel" for="why_choose_us">Venues<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[33]); ?>
		</div>
		<div class="clear"></div>
	</div>

	<div class="formheader">
		<h2>Were you happy with</h2>
	</div>
    
	<div class="formrow">
		<label class="formlabel" for="our_wesite">Our website  </label>
		<div class="formelement">
			<input type="radio" name="our_wesite" value="1" <?php if($record[5] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="our_wesite" value="2" <?php if($record[5] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="our_wesite" value="3" <?php if($record[5] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="our_wesite" value="4" <?php if($record[5] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="our_wesite" value="5" <?php if($record[5] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="speed_of_response">Speed of responses </label>
		<div class="formelement">
						<input type="radio" name="speed_of_response" value="1" <?php if($record[6] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="speed_of_response" value="2" <?php if($record[6] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="speed_of_response" value="3" <?php if($record[6] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="speed_of_response" value="4" <?php if($record[6] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="speed_of_response" value="5" <?php if($record[6] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="ease_of_booking">Ease of booking </label>
		<div class="formelement">
					<input type="radio" name="ease_of_booking" value="1" <?php if($record[7] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="ease_of_booking" value="2" <?php if($record[7] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="ease_of_booking" value="3" <?php if($record[7] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="ease_of_booking" value="4" <?php if($record[7] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="ease_of_booking" value="5" <?php if($record[7] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    	<div class="formrow">
		<label class="formlabel" for="information_given">Information given </label>
		<div class="formelement">
					<input type="radio" name="information_given" value="1" <?php if($record[8] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="information_given" value="2" <?php if($record[8] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="information_given" value="3" <?php if($record[8] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="information_given" value="4" <?php if($record[8] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="information_given" value="5" <?php if($record[8] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="wedding_guide">Wedding Guide  </label>
		<div class="formelement">
					<input type="radio" name="wedding_guide" value="1" <?php if($record[9] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_guide" value="2" <?php if($record[9] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_guide" value="3" <?php if($record[9] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_guide" value="4" <?php if($record[9] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_guide" value="5" <?php if($record[9] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="wedding_checklist">Wedding Checklist </label>
		<div class="formelement">
					<input type="radio" name="wedding_checklist" value="1" <?php if($record[10] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_checklist" value="2" <?php if($record[10] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_checklist" value="3" <?php if($record[10] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_checklist" value="4" <?php if($record[10] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_checklist" value="5" <?php if($record[10] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
    	<div class="formrow">
		<label class="formlabel" for="face_to_face_meetings">Face to face meetings </label>
		<div class="formelement">
					<input type="radio" name="face_to_face_meetings" value="1" <?php if($record[11] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="face_to_face_meetings" value="2" <?php if($record[11] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="face_to_face_meetings" value="3" <?php if($record[11] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="face_to_face_meetings" value="4" <?php if($record[11] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="face_to_face_meetings" value="5" <?php if($record[11] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="paperwork">Paperwork (if done by us) </label>
		<div class="formelement">
					<input type="radio" name="paperwork" value="1" <?php if($record[12] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="paperwork" value="2" <?php if($record[12] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="paperwork" value="3" <?php if($record[12] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="paperwork" value="4" <?php if($record[12] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="paperwork" value="5" <?php if($record[12] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="wedding_planner">Wedding planner  </label>
		<div class="formelement">
					<input type="radio" name="wedding_planner" value="1" <?php if($record[13] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_planner" value="2" <?php if($record[13] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_planner" value="3" <?php if($record[13] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_planner" value="4" <?php if($record[13] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_planner" value="5" <?php if($record[13] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="wedding_location">Wedding location  </label>
		<div class="formelement">
					<input type="radio" name="wedding_location" value="1" <?php if($record[14] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="wedding_location" value="2" <?php if($record[14] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="wedding_location" value="3" <?php if($record[14] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="wedding_location" value="4" <?php if($record[14] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="wedding_location" value="5" <?php if($record[14] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
        	<div class="formrow">
		<label class="formlabel" for="reception_location">Reception location  </label>
		<div class="formelement">
					<input type="radio" name="reception_location" value="1" <?php if($record[15] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="reception_location" value="2" <?php if($record[15] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="reception_location" value="3" <?php if($record[15] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="reception_location" value="4" <?php if($record[15] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="reception_location" value="5" <?php if($record[15] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>    
        	<div class="formrow">
		<label class="formlabel" for="overall_service">Overall service provided  </label>
		<div class="formelement">
					<input type="radio" name="overall_service" value="1" <?php if($record[16] == "1") { echo "checked=\"checked\""; } ?>/> 1 
            <input type="radio" name="overall_service" value="2" <?php if($record[16] == "2") { echo "checked=\"checked\""; } ?>/> 2 
            <input type="radio" name="overall_service" value="3" <?php if($record[16] == "3") { echo "checked=\"checked\""; } ?>/> 3 
            <input type="radio" name="overall_service" value="4" <?php if($record[16] == "4") { echo "checked=\"checked\""; } ?>/> 4 
            <input type="radio" name="overall_service" value="5" <?php if($record[16] == "5") { echo "checked=\"checked\""; } ?>/> 5 
		</div>
		<div class="clear"></div>
	</div>
    
    
 	<div class="formheader">
		<h2>Wedding Details</h2>
	</div>
       
    	<div class="formrow">
		<label class="formlabel" for="book_accommodation_with">Who did you book your accommodation with: <span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[17]); ?>
		</div>
		<div class="clear"></div>
	</div>

       
    	<div class="formrow">
		<label class="formlabel" for="wedding_insurance">Who did you book your wedding insurance with: <span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[18]); ?>
		</div>
		<div class="clear"></div>
	</div>

       
    	<div class="formrow">
		<label class="formlabel" for="how_many_guests">How many guests did you have at your wedding: <span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[19]); ?>
		</div>
		<div class="clear"></div>
	</div>

       

 	<div class="formheader">
		<h2>Comments</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="comments">Let us know any comments you may have<span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[20]); ?>
		</div>
		<div class="clear"></div>
	</div>
   
    
	

	
	<div class="formheader">
		<h2>Please leave these boxes ticked if you are you happy for us to feature</h2>
	</div>
	
	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_testimonials">Your testimonials on our website.</label>
		<div class="formelement">
			<?php echo stripslashes($record[21]); ?>
		</div>
		<div class="clear"></div>
	</div>
    
	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_website">Your wedding photos on our website.</label>
		<div class="formelement">
			<?php echo stripslashes($record[22]); ?>
		</div>
		<div class="clear"></div>
	</div>
       
    	<div class="formrow">
		<label class="formcheckboxlabel" for="feature_facebook">Your wedding photos on our Facebook page.</label>
		<div class="formelement">
			<?php echo stripslashes($record[23]); ?>
		</div>
		<div class="clear"></div>
	</div>
    
     	<div class="formheader">
		<h2>Testimonial</h2>
	</div>

	<div class="formrow">
		<label class="formlabel" for="testimonial">Please write a testimonial that we could feature on our website <span class="required">*</span></label>
		<div class="formelement">
			<?php echo stripslashes($record[24]); ?>
		</div>
		<div class="clear"></div>
	</div>
  
 <div class="formheader">
		<h2>Additional Options:</h2>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="privacypolicy">Tick this box if you would be happy to be contacted by another couple, to recommend our services:</label>
		<div class="formelement">

			<?php echo stripslashes($record[25]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formcheckboxlabel" for="contactus_termsandconditions">We are often contacted by newspapers and magazines interested in featuring
our wedding couples. Tick this box if you would be happy to appear in a
publication (we would contact you in this event of course):
</label>
		<div class="formelement">
			<?php echo stripslashes($record[26]); ?>

		</div>
		<div class="clear"></div>
	</div>
       
</div>

<?php if($_POST["action_type"] == "view_archive") { ?>
<div style="float: left;  margin-left:20px; width:610px;"><input type="submit" name="action" value="Remove From Archive">
</form></div>


<div style="float: left; ">
<form action="<?php echo $site_url; ?>/admin/feedback.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px;  width:610px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left;">
<form action="<?php echo $site_url; ?>/admin/feedback.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_feedback,"archive='No'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_feedback,"archive='Yes'","id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>Message Archived</h1>

<p>The message has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_info_feedback,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>Message Deleted</h1>

<p>The message has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {
	

$result = $sql_command->select($database_info_feedback,"id,yourname,archive,timestamp","ORDER BY timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
	
$dateline = date("jS F Y",$record[3]);

if($record[2] == "Yes") {
$archive_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])."</option>\n";
} else {
$active_list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])."</option>\n";
}
}


$total_rows = $sql_command->count_rows($database_info_feedback,"id","");


$feedback_html_1_1 = $sql_command->count_rows($database_info_feedback,"id","our_wesite='1'");
$feedback_html_1_2 = $sql_command->count_rows($database_info_feedback,"id","our_wesite='2'");
$feedback_html_1_3 = $sql_command->count_rows($database_info_feedback,"id","our_wesite='3'");
$feedback_html_1_4 = $sql_command->count_rows($database_info_feedback,"id","our_wesite='4'");
$feedback_html_1_5 = $sql_command->count_rows($database_info_feedback,"id","our_wesite='5'");

$feedback_html_2_1 = $sql_command->count_rows($database_info_feedback,"id","speed_of_response='1'");
$feedback_html_2_2 = $sql_command->count_rows($database_info_feedback,"id","speed_of_response='2'");
$feedback_html_2_3 = $sql_command->count_rows($database_info_feedback,"id","speed_of_response='3'");
$feedback_html_2_4 = $sql_command->count_rows($database_info_feedback,"id","speed_of_response='4'");
$feedback_html_2_5 = $sql_command->count_rows($database_info_feedback,"id","speed_of_response='5'");

$feedback_html_3_1 = $sql_command->count_rows($database_info_feedback,"id","ease_of_booking='1'");
$feedback_html_3_2 = $sql_command->count_rows($database_info_feedback,"id","ease_of_booking='2'");
$feedback_html_3_3 = $sql_command->count_rows($database_info_feedback,"id","ease_of_booking='3'");
$feedback_html_3_4 = $sql_command->count_rows($database_info_feedback,"id","ease_of_booking='4'");
$feedback_html_3_5 = $sql_command->count_rows($database_info_feedback,"id","ease_of_booking='5'");

$feedback_html_4_1 = $sql_command->count_rows($database_info_feedback,"id","information_given='1'");
$feedback_html_4_2 = $sql_command->count_rows($database_info_feedback,"id","information_given='2'");
$feedback_html_4_3 = $sql_command->count_rows($database_info_feedback,"id","information_given='3'");
$feedback_html_4_4 = $sql_command->count_rows($database_info_feedback,"id","information_given='4'");
$feedback_html_4_5 = $sql_command->count_rows($database_info_feedback,"id","information_given='5'");

$feedback_html_5_1 = $sql_command->count_rows($database_info_feedback,"id","wedding_guide='1'");
$feedback_html_5_2 = $sql_command->count_rows($database_info_feedback,"id","wedding_guide='2'");
$feedback_html_5_3 = $sql_command->count_rows($database_info_feedback,"id","wedding_guide='3'");
$feedback_html_5_4 = $sql_command->count_rows($database_info_feedback,"id","wedding_guide='4'");
$feedback_html_5_5 = $sql_command->count_rows($database_info_feedback,"id","wedding_guide='5'");

$feedback_html_6_1 = $sql_command->count_rows($database_info_feedback,"id","wedding_checklist='1'");
$feedback_html_6_2 = $sql_command->count_rows($database_info_feedback,"id","wedding_checklist='2'");
$feedback_html_6_3 = $sql_command->count_rows($database_info_feedback,"id","wedding_checklist='3'");
$feedback_html_6_4 = $sql_command->count_rows($database_info_feedback,"id","wedding_checklist='4'");
$feedback_html_6_5 = $sql_command->count_rows($database_info_feedback,"id","wedding_checklist='5'");

$feedback_html_7_1 = $sql_command->count_rows($database_info_feedback,"id","face_to_face_meetings='1'");
$feedback_html_7_2 = $sql_command->count_rows($database_info_feedback,"id","face_to_face_meetings='2'");
$feedback_html_7_3 = $sql_command->count_rows($database_info_feedback,"id","face_to_face_meetings='3'");
$feedback_html_7_4 = $sql_command->count_rows($database_info_feedback,"id","face_to_face_meetings='4'");
$feedback_html_7_5 = $sql_command->count_rows($database_info_feedback,"id","face_to_face_meetings='5'");

$feedback_html_8_1 = $sql_command->count_rows($database_info_feedback,"id","paperwork='1'");
$feedback_html_8_2 = $sql_command->count_rows($database_info_feedback,"id","paperwork='2'");
$feedback_html_8_3 = $sql_command->count_rows($database_info_feedback,"id","paperwork='3'");
$feedback_html_8_4 = $sql_command->count_rows($database_info_feedback,"id","paperwork='4'");
$feedback_html_8_5 = $sql_command->count_rows($database_info_feedback,"id","paperwork='5'");

$feedback_html_9_1 = $sql_command->count_rows($database_info_feedback,"id","wedding_planner='1'");
$feedback_html_9_2 = $sql_command->count_rows($database_info_feedback,"id","wedding_planner='2'");
$feedback_html_9_3 = $sql_command->count_rows($database_info_feedback,"id","wedding_planner='3'");
$feedback_html_9_4 = $sql_command->count_rows($database_info_feedback,"id","wedding_planner='4'");
$feedback_html_9_5 = $sql_command->count_rows($database_info_feedback,"id","wedding_planner='5'");

$feedback_html_10_1 = $sql_command->count_rows($database_info_feedback,"id","wedding_location='1'");
$feedback_html_10_2 = $sql_command->count_rows($database_info_feedback,"id","wedding_location='2'");
$feedback_html_10_3 = $sql_command->count_rows($database_info_feedback,"id","wedding_location='3'");
$feedback_html_10_4 = $sql_command->count_rows($database_info_feedback,"id","wedding_location='4'");
$feedback_html_10_5 = $sql_command->count_rows($database_info_feedback,"id","wedding_location='5'");

$feedback_html_11_1 = $sql_command->count_rows($database_info_feedback,"id","reception_location='1'");
$feedback_html_11_2 = $sql_command->count_rows($database_info_feedback,"id","reception_location='2'");
$feedback_html_11_3 = $sql_command->count_rows($database_info_feedback,"id","reception_location='3'");
$feedback_html_11_4 = $sql_command->count_rows($database_info_feedback,"id","reception_location='4'");
$feedback_html_11_5 = $sql_command->count_rows($database_info_feedback,"id","reception_location='5'");

$feedback_html_12_1 = $sql_command->count_rows($database_info_feedback,"id","overall_service='1'");
$feedback_html_12_2 = $sql_command->count_rows($database_info_feedback,"id","overall_service='2'");
$feedback_html_12_3 = $sql_command->count_rows($database_info_feedback,"id","overall_service='3'");
$feedback_html_12_4 = $sql_command->count_rows($database_info_feedback,"id","overall_service='4'");
$feedback_html_12_5 = $sql_command->count_rows($database_info_feedback,"id","overall_service='5'");



$result_1 = $sql_command->select($database_info_feedback,"book_accommodation_with,count(*)","GROUP BY book_accommodation_with ORDER BY book_accommodation_with");
$row_1 = $sql_command->results($result_1);

foreach($row_1 as $record_1) {
if($record_1[0]) {
$html_1 .= "<b>".stripslashes($record_1[0]).":</b> ".stripslashes($record_1[1])."</b><br>";
}
}


$result_2 = $sql_command->select($database_info_feedback,"wedding_insurance,count(*)","GROUP BY wedding_insurance ORDER BY wedding_insurance");
$row_2 = $sql_command->results($result_2);

foreach($row_2 as $record_2) {
if($record_2[0]) {
$html_2 .= "<b>".stripslashes($record_2[0]).":</b> ".stripslashes($record_2[1])."</b><br>";
}
}


$result_3 = $sql_command->select($database_info_feedback,"how_many_guests,count(*)","GROUP BY how_many_guests ORDER BY how_many_guests");
$row_3 = $sql_command->results($result_3);

foreach($row_3 as $record_3) {
if($record_3[0]) {
$html_3 .= "<b>".stripslashes($record_3[0]).":</b> ".stripslashes($record_3[1])."</b><br>";
}
}



$get_template->topHTML();
?>
<h1>Form - Feedback</h1>
<p><b>Active Messages</b></p>
<form action="<?php echo $site_url; ?>/admin/feedback.php" method="get">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $active_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<p><hr /></p>
<p><b>Archived Messages</b></p>
<form action="<?php echo $site_url; ?>/admin/feedback.php" method="get">
<input type="hidden" name="action" value="Continue" />
<input type="hidden" name="action_type" value="view_archive" />
<select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();"><?php echo $archive_list; ?></select>
<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>

<p><b>Contact Us Results</b> (Total: <?php echo $total_rows; ?>)</p>

<h2>Our Wesite</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_1_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_1_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_1_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_1_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_1_5; ?></p>

<h2>Speed of response</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_2_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_2_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_2_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_2_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_2_5; ?></p>

<h2>Ease of booking</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_3_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_3_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_3_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_3_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_3_5; ?></p>

<h2>Information Given</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_4_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_4_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_4_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_4_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_4_5; ?></p>

<h2>Wedding Guide</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_5_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_5_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_5_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_5_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_5_5; ?></p>

<h2>Wedding Checklist</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_6_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_6_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_6_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_6_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_6_5; ?></p>

<h2>Face to face meetings</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_7_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_7_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_7_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_7_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_7_5; ?></p>

<h2>Paperwork</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_8_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_8_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_8_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_8_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_8_5; ?></p>

<h2>Wedding Planner</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_9_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_9_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_9_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_9_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_9_5; ?></p>

<h2>Wedding Location</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_10_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_10_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_10_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_10_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_10_5; ?></p>

<h2>Reception Location</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_11_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_11_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_11_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_11_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_11_5; ?></p>

<h2>Overall Service</h2>
<p><strong>Rating 1:</strong> <?php echo $feedback_html_12_1; ?><br />
<strong>Rating 2:</strong> <?php echo $feedback_html_12_2; ?><br />
<strong>Rating 3:</strong> <?php echo $feedback_html_12_3; ?><br />
<strong>Rating 4:</strong> <?php echo $feedback_html_12_4; ?><br />
<strong>Rating 5:</strong> <?php echo $feedback_html_12_5; ?></p>



<?
$get_template->bottomHTML();
$sql_command->close();
}

?>