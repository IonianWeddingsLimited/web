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
	
	$add_header .= "<script type=\"Application/Javascript\">
	
	$(function() {
		$(\".extra_show\").click(function() {
			$(\"#extra_menu\").css({ 'opacity': 1, 'height': 'auto' });
			$(\".extra_show\").css({ 'display': 'none' });
			$(\".extra_hide\").css({ 'display': 'inline' });
		});
		$(\".extra_hide\").click(function() {
			$(\"#extra_menu\").css({ 'opacity': 0, 'height': 0 });
			$(\".extra_hide\").css({ 'display': 'none' });
			$(\".extra_show\").css({ 'display': 'inline' });
		});
	});
	
	</script>";
	

	
	
	// Get Templates
	$get_template = new oos_template();
	$meta_title = "Admin";
	$meta_description = "";
	$meta_keywords = "";
	
	$search_s = $_GET["id"];
	
	list($search_type,$id) = explode("_t_",$search_s);

	if($search_type == "Active") {
		header("Location: $site_url/oos/manage-client.php?a=history&id=".$id);
		$sql_command->close();
	}
	if($search_type == "Prospect") {
		header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$id);
		$sql_command->close();
	}

	if($_POST["action"] == "Download CSV") {
		header("Location: ".$site_url."/oos/download-alldata.php?".$_POST["query"]);
		exit();
	}	
	

	if($search_type == "wedding") {
	if($_GET["action"] == "Continue") {
		if(!$id) {
			header("Location: $site_url/oos/search-submissions.php");
			$sql_command->close();
		}

		$result = $sql_command->select($database_info_wedding_questionnaire,"*","WHERE id='".addslashes($id)."'");
		$record = $sql_command->result($result);
		$dateline = date("jS F Y",$record[34]);
		$dateline2 = date("g:i a",$record[34]);
		$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
		$destination_row = $sql_command->results($destination_result);
		$total_des=0;
		foreach($destination_row as $destination_record) {
			$island_html = "";
			
			if($total_des%3==0  and $total_des!=0) {
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

				$destination_check_result = $sql_command->select($database_questionaire_destinations,"id","WHERE questionaire_id='".addslashes($record[0])."' AND destination_id='".addslashes($destination_record[0])."' AND island_id='".addslashes($destination_island[0])."'");
				$destination_check_row = $sql_command->result($destination_check_result);
				
				if($destination_check_row[0]) {
					$checked = "checked=\"checked\"";
				} else {
					$checked = "";
				}

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
			} else {
				$destination_check_result = $sql_command->select($database_questionaire_destinations,"id","WHERE questionaire_id='".addslashes($record[0])."' AND destination_id='".addslashes($destination_record[0])."'");
				$destination_check_row = $sql_command->result($destination_check_result);
				
				if($destination_check_row[0]) {
					$checked = "checked=\"checked\"";
				} else {
					$checked = "";
				}

				$html .= "<div class=\"dest_form\" style=\"float:left; margin-right:20px; padding: 0 5px 0 10px;\">
<strong>".stripslashes($destination_record[1])."</strong><br />
<div class=\"dest_form2\" style=\"float:left; padding:5px;\">
<p><input class=\"formcheckbox\" name=\"".$destination_record[0]."\" type=\"checkbox\" value=\"Yes\" $checked/> ".stripslashes($destination_record[1])."<p>
</div>
<div style=\"clear:left;\"></div>
</div>";
			}

		}









//output

		$get_template->topHTML();
		?>
<h1>Form - Search Submissions</h1>
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
<form action="<?php echo $site_url; ?>/oos/search-submissions.php" method="POST" class="pageform">
<input type="hidden" name="id" value="wedding_t_<?php echo stripslashes($record[0]); ?>" />
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
		<h1>Prefered wedding destination(s)</h1>
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
		<label class="formlabel" for="weddingquestionnaire_ceremony">Prefered ceremony setting</label>
		<div class="formelement">
<?php echo stripslashes($record[22]); ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formheader">
		<h1>The Reception</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="weddingquestionnaire_reception">Prefered reception setting</label>
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




<?php 

if($_POST["action_type"] == "view_archive") { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Remove From Archive">
</div></form>
<div style="float: left; ">
<form action="<?php echo $site_url; ?>/oos/search-submissions.php" method="POST">
<input type="hidden" name="id" value="wedding_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Archive">
</form></div>
<div style="float: left; ">
<form action="<?php echo $site_url; ?>/oos/search-submissions.php" method="POST">
<input type="hidden" name="id" value="wedding_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
		$get_template->bottomHTML();
		$sql_command->close();
	}
	
	// continue end - wedding
	

	elseif($_POST["action"] == "Remove From Archive") {
		$sql_command->update($database_info_wedding_questionnaire,"archive='No'","id='".addslashes($id)."'");
		$get_template->topHTML();
		?>
<h1>Message Removed From Archive</h1>
<p>The message has now been removed from archived</p>
<?
		$get_template->bottomHTML();
		$sql_command->close();
	}

	elseif($_POST["action"] == "Archive") {
		$sql_command->update($database_info_wedding_questionnaire,"archive='Yes'","id='".addslashes($id)."'");
		$get_template->topHTML();
		?>
<h1>Questionnaire Archived</h1>
<p>The questionnaire has now been archived</p>
<?
		$get_template->bottomHTML();
		$sql_command->close();
	}

	elseif($_POST["action"] == "Create Client") {
		$result = $sql_command->select($database_info_wedding_questionnaire,"*","WHERE id='".addslashes($id)."'");
		$record = $sql_command->result($result);
		$_SESSION["title"] = "";
		$_SESSION["firstname"] = "";
		$_SESSION["lastname"] = "";
		$_SESSION["email"] = "";
		$_SESSION["tel"] = "";
		$_SESSION["address"] = "";
		$_SESSION["address2"] = "";
		$_SESSION["address3"] = "";
		$_SESSION["town"] = "";
		$_SESSION["county"] = "";
		$_SESSION["country"] = "";
		$_SESSION["postcode"] = "";
		$_SESSION["mob"] = "";
		$_SESSION["fax"] = "";
		$_SESSION["dob"] = "";
		$_SESSION["mailinglist"] = "";
		$_SESSION["wedding_date"] = "";
		$_SESSION["destination"] = "";
		$_SESSION["iwcuid"] = "";
		$_SESSION["groom_title"] = "";
		$_SESSION["groom_firstname"] = "";
		$_SESSION["groom_surname"] = "";
		$_SESSION["groom_dob"] = "";
		$_SESSION["wedding_time"] = "";
		$_SESSION["hearabout"] = "";
		$_SESSION["title"] = "Miss";
		$_SESSION["firstname"] = stripslashes($record[1]);
		$_SESSION["lastname"] = stripslashes($record[2]);
		$_SESSION["groom_title"] = "Mr";
		$_SESSION["groom_firstname"] = stripslashes($record[7]);
		$_SESSION["groom_surname"] = stripslashes($record[8]);
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
		$_SESSION["hearabout"] = stripslashes($record[26]);
		$_SESSION["groom_email"] = stripslashes($record[9]);
		$_SESSION["groom_tel"] = stripslashes($record[10]);
		$_SESSION["prospect_id"] = stripslashes($record[0]);
		$_SESSION["survey"] = $database_info_wedding_questionnaire;

		
		if($record[33] == "Yes") {
			$_SESSION["mailinglist"] = "Yes";
		} else {
			$_SESSION["mailinglist"] = "No";
		}

		header("Location: $site_url/oos/add-client.php");
		$sql_command->close();
	} 
}	
	
// end wedding

// start callback

 // Callback
elseif($search_type == "callback") {
	if($_GET["action"] == "Continue") {
	
if(!$id) {
header("Location: $site_url/oos/book-a-callback.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_bookacallback,"*","WHERE id='".addslashes($id)."'");
$record = $sql_command->result($result);

$dateline = date("jS F Y",$record[13]);
$dateline2 = date("g:i a",$record[13]);

$get_template->topHTML();
?>
<h1>Form - Book a Callback</h1>
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
<form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="POST" class="pageform">
<input type="hidden" name="id" value="callback_t_<?php echo stripslashes($record[0]); ?>" />
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
		<?php echo stripslashes($record[17]); ?>

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
<form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="POST">
<input type="hidden" name="id" value="callback_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left;">
<form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="POST">
<input type="hidden" name="id" value="callback_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_bookacallback,"archive='No'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_bookacallback,"archive='Yes'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Archived</h1>

<p>The message has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Create Client") {
	
$result = $sql_command->select($database_info_bookacallback,"*","WHERE id='".addslashes($id)."'");
$record = $sql_command->result($result);
$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
$_SESSION["email"] = "";
$_SESSION["tel"] = "";
$_SESSION["address"] = "";
$_SESSION["address2"] = "";
$_SESSION["address3"] = "";
$_SESSION["town"] = "";
$_SESSION["county"] = "";
$_SESSION["country"] = "";
$_SESSION["postcode"] = "";
$_SESSION["mob"] = "";
$_SESSION["fax"] = "";
$_SESSION["dob"] = "";
$_SESSION["mailinglist"] = "";
$_SESSION["wedding_date"] = "";
$_SESSION["destination"] = "";
$_SESSION["iwcuid"] = "";
$_SESSION["groom_title"] = "";
$_SESSION["groom_firstname"] = "";
$_SESSION["groom_surname"] = "";
$_SESSION["groom_dob"] = "";
$_SESSION["wedding_time"] = "";
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
$_SESSION["postcode"] = stripslashes($record[10]);
$_SESSION["county"] = stripslashes($record[17]);

$_SESSION["prospect_id"] = stripslashes($record[0]);
$_SESSION["survey"] = $database_info_bookacallback;

if($record[14] == "Yes") { $_SESSION["mailinglist"] = "Yes"; } else {  $_SESSION["mailinglist"] = "No"; }

header("Location: $site_url/oos/add-client.php");
$sql_command->close();	
	
} 
}

// end callback

// start contact


elseif ($search_type == "contact") {
	
	if($_GET["action"] == "Continue") {
	
if(!$id) {
header("Location: $site_url/oos/contact-us.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_contactus,"*","WHERE id='".addslashes($id)."'");
$record = $sql_command->result($result);

$dateline = date("jS F Y",$record[13]);
$dateline2 = date("g:i a",$record[13]);

$get_template->topHTML();
?>
<h1>Form - Contact Us</h1>
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
<form action="<?php echo $site_url; ?>/oos/contact-us.php" method="POST" class="pageform">
<input type="hidden" name="id" value="contact_t_<?php echo stripslashes($record[0]); ?>" />
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
		<?php echo stripslashes($record[16]); ?>

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
<form action="<?php echo $site_url; ?>/oos/contact-us.php" method="POST">
<input type="hidden" name="id" value="contact_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px;  width:570px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left;">
<form action="<?php echo $site_url; ?>/oos/contact-us.php" method="POST">
<input type="hidden" name="id" value="contact_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_contactus,"archive='No'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_contactus,"archive='Yes'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Archived</h1>

<p>The message has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_POST["action"] == "Create Client") {
	
$result = $sql_command->select($database_info_contactus,"*","WHERE id='".addslashes($id)."'");
$record = $sql_command->result($result);
$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
$_SESSION["email"] = "";
$_SESSION["tel"] = "";
$_SESSION["address"] = "";
$_SESSION["address2"] = "";
$_SESSION["address3"] = "";
$_SESSION["town"] = "";
$_SESSION["county"] = "";
$_SESSION["country"] = "";
$_SESSION["postcode"] = "";
$_SESSION["mob"] = "";
$_SESSION["fax"] = "";
$_SESSION["dob"] = "";
$_SESSION["mailinglist"] = "";
$_SESSION["wedding_date"] = "";
$_SESSION["destination"] = "";
$_SESSION["iwcuid"] = "";
$_SESSION["groom_title"] = "";
$_SESSION["groom_firstname"] = "";
$_SESSION["groom_surname"] = "";
$_SESSION["groom_dob"] = "";
$_SESSION["wedding_time"] = "";
$_SESSION["title"] = "Miss";
$_SESSION["firstname"] = stripslashes($record[1]);
$_SESSION["lastname"] = stripslashes($record[2]);
$_SESSION["email"] = stripslashes($record[3]);
$_SESSION["tel"] = stripslashes($record[4]);
$_SESSION["address"] = stripslashes($record[5]);
$_SESSION["address2"] = stripslashes($record[6]);
$_SESSION["address3"] = stripslashes($record[7]);
$_SESSION["town"] = stripslashes($record[8]);
$_SESSION["county"] = stripslashes($record[16]);
$_SESSION["country"] = stripslashes($record[9]);
$_SESSION["postcode"] = stripslashes($record[10]);

$_SESSION["prospect_id"] = stripslashes($record[0]);
$_SESSION["survey"] = $database_info_contactus;


if($record[14] == "Yes") { $_SESSION["mailinglist"] = "Yes"; } else {  $_SESSION["mailinglist"] = "No"; }

header("Location: $site_url/oos/add-client.php");
$sql_command->close();	
	
}
	
	
}
//end contact


// start personal

elseif ($search_type=="personal") {
	
	
if($_GET["action"] == "Continue") {

if(!$id) {
header("Location: $site_url/oos/personal-consultations.php");
$sql_command->close();
}

$result = $sql_command->select($database_info_personal_consultations,"*","WHERE id='".addslashes($id)."'");
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
<input type="hidden" name="id" value="personal_t_<?php echo stripslashes($record[0]); ?>" />
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
<input type="hidden" name="id" value="personal_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } else { ?>
<div style="float: left;  margin-left:20px; width:570px;"><input type="submit" name="action" value="Archive">
</form></div>


<div style="float: left;">
<form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="POST">
<input type="hidden" name="id" value="personal_t_<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Create Client"></div>
<div style="clear:left;"></div>
</form>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Remove From Archive") {
	
	
$sql_command->update($database_info_personal_consultations,"archive='No'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Removed From Archive</h1>

<p>The message has now been removed from archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "Archive") {
	

$sql_command->update($database_info_personal_consultations,"archive='Yes'","id='".addslashes($id)."'");

$get_template->topHTML();
?>
<h1>Message Archived</h1>

<p>The message has now been archived</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_POST["action"] == "Create Client") {
	
$result = $sql_command->select($database_info_personal_consultations,"*","WHERE id='".addslashes($id)."'");
$record = $sql_command->result($result);
$_SESSION["title"] = "";
$_SESSION["firstname"] = "";
$_SESSION["lastname"] = "";
$_SESSION["email"] = "";
$_SESSION["tel"] = "";
$_SESSION["address"] = "";
$_SESSION["address2"] = "";
$_SESSION["address3"] = "";
$_SESSION["town"] = "";
$_SESSION["county"] = "";
$_SESSION["country"] = "";
$_SESSION["postcode"] = "";
$_SESSION["mob"] = "";
$_SESSION["fax"] = "";
$_SESSION["dob"] = "";
$_SESSION["mailinglist"] = "";
$_SESSION["wedding_date"] = "";
$_SESSION["destination"] = "";
$_SESSION["iwcuid"] = "";
$_SESSION["groom_title"] = "";
$_SESSION["groom_firstname"] = "";
$_SESSION["groom_surname"] = "";
$_SESSION["groom_dob"] = "";
$_SESSION["wedding_time"] = "";
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

$_SESSION["prospect_id"] = stripslashes($record[0]);
$_SESSION["survey"] = $database_info_personal_consultations;

header("Location: $site_url/oos/add-client.php");
$sql_command->close();	

}
	
	
}

//end personal

else {
	
	
	$level1_result = $sql_command->select($database_navigation,
										  "id,page_name,page_link,external_link,external_url",
										  "WHERE parent_id='0'");
	$level1_row = $sql_command->results($level1_result);
	
	foreach($level1_row as $level1_record) {
		if($level1_record[1] == "Destinations") {
			$level2_result = $sql_command->select($database_navigation,
												  "id,page_name,page_link,external_link,external_url",
												  "WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
			$level2_row = $sql_command->results($level2_result);
			foreach($level2_row as $level2_record) {
				$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\" disabled=\"disabled\">".stripslashes($level2_record[1])."</option>\n";
				
				$level3_result = $sql_command->select($database_navigation,
													  "id,page_name,page_link,external_link,external_url",
													  "WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
				$level3_row = $sql_command->results($level3_result);
				foreach($level3_row as $level3_record) {
					$nid = $level3_record[0];
					$nav_ids[$nid] = $level3_record[1];
					$d_selected = ($_GET["destination"]==$level3_record[0]) ? "selected=\"selected\"" : "";
					$d_name .= ($_GET["destination"]==$level3_record[0]) ? $level3_record[1] : "";
					$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\" $d_selected>".stripslashes($level3_record[1])."</option>\n";
				}
			}
		}
	
	}
	
	if($_GET["s"]!="" ||
	$_GET["date_from"]!="" ||
	$_GET["destination"]>0 ||
	$_GET["location"]!="" ||
	$_GET["b_client"]>1 ||
	$_GET["amounts"]!=0) {
		
		$search_c = "";
		$destination = $_GET["destination"];
		$location = $_GET["location"];
		$amounts = $_GET["amounts"];
		$option_filter = "";
		$location_filter .= "";
		$destination_filter .= "";
		$amount_filter .= "";	
		$add_des_line = "";
		$count_c = 0;
		
		$bclient=$_GET["b_client"];
		$select_q = "";
		$email_list = array();
		$client_id = array();
		
		if ($_GET["amounts"]!=0) {
			switch ($_GET["amounts"]){
				case "1":
					$amtext = "�0 to �5k";
					$amfrom = 0;
					$amto = 5000;
					break;
				case "2":
					$amtext = "�5 to �10k";
					$amfrom = 5000;
					$amto = 10000;
					break;
				case "3":
					$amtext = "�10 to �15k";
					$amfrom = 10000;
					$amto = 15000;
					break;
				case "4":
					$amtext = "�15k+";
					$amfrom = 15000;
					$amto = 5000000;
					break;
			}
			$amount_q = "SELECT order_details.client_id FROM order_details,order_history WHERE order_details.id = order_history.order_id group by order_details.id HAVING sum(order_history.iw_cost) >= '".$amfrom."' and sum(order_history.iw_cost) <= '".$amto."'";
			
			$amount_r = mysql_query($amount_q) or die(mysql_error());
			$am_c = $sql_command->results($amount_r);
			foreach ($am_c as $am) {
				$amount_c[] = $am[0];	
			}

		}
			if ($_GET["s"]!="" && $_GET["stype"]=="email") {
				$wfilter = "($database_info_wedding_questionnaire.bride_email LIKE '%".$_GET["s"]."%' OR $database_info_wedding_questionnaire.groom_email LIKE '%".$_GET["s"]."%') AND ";	
				$wfilter = "($database_clients.email LIKE '%".$_GET["s"]."%' OR $database_clients.groom_email LIKE '%".$_GET["s"]."%') AND ";	
				$nfilter = "(email LIKE '%".$_GET["s"]."%') AND ";
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">Email:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
			}
			elseif ($_GET["s"]!="" && $_GET["stype"]=="name") {
				list($first_n,$last_n) = explode(" ",$_GET["s"]);
				$wfilter = ($last_n!="") ? 
				"(($database_info_wedding_questionnaire.groom_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.groom_lastname = '".addslashes($last_n)."') OR ($database_info_wedding_questionnaire.bride_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.bride_lastname = '".addslashes($last_n)."')) AND " : 
				"($database_info_wedding_questionnaire.groom_firstname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.groom_lastname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_firstname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_lastname LIKE '%".addslashes($first_n)."%') AND ";
	
				$cfilter = ($last_n!="") ? 
				"(($database_clients.groom_firstname = '".addslashes($first_n)."' AND $database_clients.groom_surname = '".addslashes($last_n)."') OR ($database_clients.firstname = '".addslashes($first_n)."' AND $database_clients.lastname = '".addslashes($last_n)."')) AND " : 
				"($database_clients.groom_firstname LIKE '%".addslashes($first_n)."%' OR $database_clients.groom_surname LIKE '%".addslashes($first_n)."%' OR $database_clients.firstname LIKE '%".addslashes($first_n)."%' OR $database_clients.lastname LIKE '%".addslashes($first_n)."%') AND ";
	
	
				$nfilter = ($last_n!="") ? "(firstname = '".addslashes($first_n)."' AND lastname = '".addslashes($last_n)."')" : "(firstname LIKE '%".addslashes($_GET["s"])."%' OR lastname LIKE '%".addslashes($_GET["s"])."%') AND ";		
				
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">Name:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
			}
			
			if ($location!="") {
				$locationwq_filter = "$database_info_wedding_questionnaire.county LIKE '%".$_GET["location"]."%' AND ";
				$locationcl_filter = "$database_clients.county LIKE '%".$_GET["location"]."%' AND ";
				$location_filter = "county LIKE '%".$_GET["location"]."%' AND ";
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">County:</span> <a href=\"search-submissions.php?location=".$_GET["location"]."\">".$_GET["location"]."</a>";
		}
		if($destination>0) {
			$destination_filter = "$database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id AND $database_questionaire_destinations.island_id='".addslashes($destination)."' AND ";
			$add_des_line = ",$database_questionaire_destinations";
			$cdestination_filter = "$database_clients.destination='".addslashes($destination)."' AND ";
			$count_c++;
			$search_c .= "<span style=\"color:#000;\">Destination:</span> <a href=\"search-submissions.php?destination=".$_GET["destination"]."\">".$d_name."</a>";
		}
		if ($_GET["amounts"]!=0) {
			$amount_cl = (count($amount_c)>0) ? implode(",",$amount_c) : 0;
			$cl_filter_line = "clients.id IN (".$amount_cl.") AND ";
			$count_c++;
			$search_c .= "<span style=\"color:#000;\">Amounts:</span> <a href=\"search-submissions.php?amounts=".$_GET["amounts"]."\">".$amtext."</a>";
		}
		
		if ($_GET["date_from"]!="" && $_GET[""]!="date_to") {
			$filter_va = array("/"," ",":","-");
			$sdate = str_replace($filter_va,"-",$_GET["date_from"]);
			$tdate = str_replace($filter_va,"-",$_GET["date_to"]);
			
			$sdate = ($sdate=="") ? "01-01-1900" : $sdate;
			$tdate = ($tdate=="") ? date("d-m-Y") : $tdate;
			
			$dates = ($sdate!=""&&$tdate!="") ? $_GET["date_from"]." > ".$tdate : "";
			list($sd,$sm,$sy) = explode("-",$sdate);
			list($td,$tm,$ty) = explode("-",$tdate);
			$td++;
			$sdate = strtotime("".$sm."/".$sd."/".$sy."");
			$tdate = strtotime("".$tm."/".$td."/".$ty."");
			
			$datefilter = ($dates!="") ? ($_GET["s"]!="") ? "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "";

			$wq_datefilter = ($dates!="") ? ($_GET["s"]!="") ? "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "";

$cl_datefilter = ($dates!="") ? ($_GET["s"]!="") ? "($database_clients.wedding_date >= ".$sdate." and $database_clients.wedding_date < ".$tdate.") AND ": "($database_clients.wedding_date >= ".$sdate." and $database_clients.wedding_date < ".$tdate.") AND ": "";

			$search_c .= "<span style=\"color:#000;\">Date:</span> <a href=\"search-submissions.php?date_from=".$_GET["date_from"]."&date_to=".$_GET["date_to"]."\">$dates</a>";
		}
	
		if ($bclient==3) {
			$cl_filter_line = "clients_options.option_value != 'Active' AND ";
			$count_c++;
			$search_c .= "Prospects Only";
		}
		elseif ($bclient==2) { 	$count_c++;
			$count_c++;
			$search_c .= "Clients Only";
		 }
		
		$where = ($count_c>0) ? "WHERE " : "";
		
		$where_cl = "$database_clients.deleted='No' AND ".$cl_datefilter.$cdestination_filter.$locationcl_filter.$acl_filter.$cl_filter_line.$cfilter;
		$where_wq = $wq_datefilter.$destination_filter.$locationwq_filter.$awq_filter.$wfilter;
		$where_cb = $datefilter.$location_filter.$acb_filter.$nfilter;
		$where_cu = $datefilter.$location_filter.$acu_filter.$nfilter;
		$where_pc = $datefilter.$location_filter.$apc_filter.$nfilter;

		$where_cl = preg_replace('/ AND $/', ' ', $where_cl);			
		$where_wq = preg_replace('/ AND $/', ' ', $where_wq);
		$where_cb = preg_replace('/ AND $/', ' ', $where_cb);
		$where_cu = preg_replace('/ AND $/', ' ', $where_cu);
		$where_pc = preg_replace('/ AND $/', ' ', $where_pc);
	
		$query_cl = "select $database_clients.id,
				   $database_clients.wedding_date,
							   COALESCE(clients_options.option_value,\"Active\") as opt_val,
				   			   $database_clients.iwcuid,
							   $database_clients.wedding_date,
							   $database_clients.firstname,
							   $database_clients.lastname,			
				   $database_clients.destination,clients.groom_firstname,clients.groom_surname, clients.email,clients.groom_email FROM $database_clients LEFT OUTER JOIN clients_options ON clients_options.client_id = $database_clients.id AND clients_options.client_option = 'client_type' $where $where_cl";
		$result_cl = mysql_query($query_cl);				   
		$row_cl = $sql_command->results($result_cl);

if ($amounts>0) { $bclient = 2; }
		$query_wq = "select $database_info_wedding_questionnaire.id,
					   $database_info_wedding_questionnaire.timestamp,
										   'wedding' as TableName,								   
										   $database_info_wedding_questionnaire.archive,
										   $database_info_wedding_questionnaire.ip,
										   $database_info_wedding_questionnaire.bride_firstname,
										   $database_info_wedding_questionnaire.bride_lastname,
										   $database_info_wedding_questionnaire.groom_firstname,
										   $database_info_wedding_questionnaire.groom_lastname, $database_info_wedding_questionnaire.groom_email,$database_info_wedding_questionnaire.bride_email from $database_info_wedding_questionnaire$add_des_line $where $where_wq ORDER BY $database_info_wedding_questionnaire.bride_lastname,
										   $database_info_wedding_questionnaire.groom_lastname";
			
			$result_wq = mysql_query($query_wq);				   
			$row_w = $sql_command->results($result_wq);

				$query_cb = "select id,timestamp,'callback' as TableName,archive,ip,firstname,lastname, email from $database_info_bookacallback $where $where_cb ORDER BY lastname";
				$result_cb = mysql_query($query_cb);
				$row_cb = $sql_command->results($result_cb);
				
			
				$query_c = "select id,timestamp,'contact' as TableName,archive,ip,firstname,lastname,email from $database_info_contactus $where $where_cu ORDER BY lastname";
				$result_c = mysql_query($query_c);
				$row_c = $sql_command->results($result_c);
				
				
				$query_p = "select id,timestamp,'personal' as TableName,archive,ip,firstname,lastname,email from $database_info_personal_consultations $where $where_pc ORDER BY lastname";
				$result_p = mysql_query($query_p);
				$row_p = $sql_command->results($result_p);
		}
		
		else {
		
		$result_w = $sql_command->select($database_info_wedding_questionnaire,
									   "id,timestamp,'wedding' as TableName,
									   archive,ip,bride_firstname,bride_lastname,groom_firstname,groom_lastname,groom_email,bride_email",
									   "ORDER BY timestamp DESC");
		$row_w = $sql_command->results($result_w);

		$result_cl = $sql_command->select("$database_clients","$database_clients.id,
				   $database_clients.wedding_date,
							   COALESCE(clients_options.option_value,\"Active\") as opt_val,
				   			   $database_clients.iwcuid,
							   $database_clients.wedding_date,
							   $database_clients.firstname,
							   $database_clients.lastname,			
				   $database_clients.destination,clients.groom_firstname,clients.groom_surname, clients.email,clients.groom_email","LEFT OUTER JOIN clients_options ON clients_options.client_id = $database_clients.id AND clients_options.client_option = 'client_type' WHERE $database_clients.deleted='No'");
$row_cl = $sql_command->results($result_cl);

		$result_cb = $sql_command->select($database_info_bookacallback,
										  "id,timestamp,'callback' as TableName,archive,ip,firstname,lastname,email",
										  "ORDER BY timestamp DESC");
		$row_cb = $sql_command->results($result_cb);	
		
		$result_c = $sql_command->select($database_info_contactus,
										 "id,timestamp,'contact' as TableName,archive,ip,firstname,lastname,email",
										 "ORDER BY timestamp DESC");
		$row_c = $sql_command->results($result_c);
		
		$result_p = $sql_command->select($database_info_personal_consultations,
										 "id,timestamp,'personal' as TableName,archive,ip,firstname,lastname,email",
										 "ORDER BY timestamp DESC");
		$row_p = $sql_command->results($result_p);
		
	}
	$testing = array();
	
	if ($email_list != "") {
		
		foreach($row_cl as $record) {
			if ((!in_array($record[10],$email_list)&&!in_array($record[11],$email_list)) || ($record[10]=="" && $record[11]=="")) {
				$testing[] = $record;
				$email_list[] = $record[10]; $email_list[] = $record[11]; 
			}
		}

		foreach($row_w as $record) {
			if ((!in_array($record[9],$email_list)&&!in_array($record[10],$email_list) && $bclient!=2) || ($record[9]=="" && $record[10]==""))							{
				$testing[] = $record; 
				$email_list[] = $record[7]; 
			}
		}

	}
	
	foreach($row_p as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}

	foreach($row_cb as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}
	
	foreach($row_c as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}
	
	foreach ($testing as $key => $row) {
    	$timeSt[$key] = $row[1];
		$names[$key] = $row[5];		
	}
	array_multisort($names, SORT_ASC, $timeSt, SORT_DESC, $testing);
	
	foreach ($testing as $record) {
		$total_rows++;
		$dateline = date("jS F Y",$record[1]);
		
		if($record[4]) { $ip = " - ".$record[4]; }
		else { $ip = ""; }
		$dest_id = $record[7];
		switch($record[2]) {
			case "Prospect":
				$active_list .= "<option value=\"".$record[2]."_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." | ".stripslashes(ucwords($nav_ids[$dest_id]))." (Prospect)</option>\n";
				break;
				
			case "Active":
			
				$active_list .= "<option value=\"".$record[2]."_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." / ".stripslashes(ucwords($record[8]))." ".stripslashes(ucwords($record[9]))." | ".$nav_ids[$dest_id]." (Client)</option>\n";
				break;


			case "wedding":
				$active_list .= "<option value=\"wedding_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." | ".stripslashes(ucwords($record[7]))." ".stripslashes(ucwords($record[8]))." ".$ip." (Wedding Questionnaire)</option>\n";
				break;
			case "callback":	
				$active_list .= "<option value=\"callback_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." ".$ip." (Book a Callback)</option>\n";
				break;
			case "contact":	
				$active_list .= "<option value=\"contact_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." ".$ip." (Contact Us)</option>\n";
				break;
			case "personal":	
				$active_list .= "<option value=\"personal_t_".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes(ucwords($record[5]))." ".stripslashes(ucwords($record[6]))." ".$ip." (Personal Consultation)</option>\n";
				break;			
		}
	}

	
	$get_template->topHTML();
	//echo "select id,timestamp,'wedding' as TableName,									   archive,ip,bride_firstname,bride_lastname,groom_firstname,groom_lastname									   FROM $database_info_wedding_questionnaire WHERE $datefilter groom_lastname LIKE '%".addslashes($_GET["s"])."%' 									   OR bride_lastname LIKE '%".addslashes($_GET["s"])."%' ORDER BY bride_lastname,groom_lastname";
//echo $test_q;

//echo $nfilter;
//echo $wfilter;
//echo $_GET["stype"];

if($_GET["stype"]=="name") { $names = "selected=\"yes\""; }
if($_GET["stype"]=="email") { $emails = "selected=\"yes\""; }
if($_GET["b_client"]==2) { $s1 = "selected=\"yes\""; }
if($_GET["b_client"]==3) { $s2 = "selected=\"yes\""; }
?>	

	<h1>Form - Search Submissions </h1>
    <?php if($search_c!="") { echo "<h4>".$search_c."</h4><a href=\"search-submissions.php\">Clear Search Criteria</a>"; }  ?>
    <form name="searchdata" action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
    <p><strong>Search</strong>
		<input type="text" name="s" value="<?php echo $_GET["s"]; ?>" />
        <select name="stype"><option value="name" <?php echo $names ?>>Names</option><option value="email" <?php echo $emails ?>>Email Address</option></select>
		<input type="submit" name="submit" value="Search">
	  </p>
    <div id="extra_nav" class="tran2">
    <?php if ($search_c) { $display_a = "style=\"display:none !important;\""; $display_b = "style=\"display:inline !important;\""; $display_c = "style=\"display:block; opacity:1; height:auto;\"";  } ?>
    <a href="#" class="extra_show" <?php echo $display_a; ?>>[+ Options]</a> 
    <a href="#" class="extra_hide" <?php echo $display_b; ?>>[- Options]</a>

    <div id="extra_menu" class="tran15" <?php echo $display_c; ?>>

    <?php
    $select_l .= "<option value=\"1\">Select All</option>";
	$select_l .= "<option value=\"2\" $s1>Clients Only</option>";
	$select_l .= "<option value=\"3\" $s2>Prospects Only</option>";
	echo "

          <div style=\"float:left; width:140px; margin:5px;\">
             <b>Search Filter</b>
          </div>
          <div style=\"float:left; margin:5px;\">
             <select name=\"b_client\" style=\"width:150px;\">$select_l</select> * Filter out clients or prospects.
          </div>
          <div style=\"clear:left;\"></div>
    ";
    ?>

	  <div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
	  <div style="float:left; margin:5px;">
		<input type="text" name="date_from" value="<?php echo $_GET["date_from"]; ?>" />
		<script language="JavaScript">
		new tcal ({
			
			'formname': 'searchdata',
			
			'controlname': 'date_from'
		});
		</script>
	  </div>
	  <div style="clear:left;"></div>
	  <div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
	  <div style="float:left; margin:5px;">
		<input type="text" name="date_to" value="<?php echo $_GET["date_to"]; ?>" />
		<script language="JavaScript">
		new tcal ({
			
			'formname': 'searchdata',
			
			'controlname': 'date_to'
		});
		</script>
	  </div>
	  <div style="clear:left;"></div>
      
      <div style="float:left; width:140px; margin:5px;"><b>Amount</b></div>
	  <div style="float:left; margin:5px;">
		<select name="amounts" style="width:150px;">
        	<?php 
			$am_txt = array("Any Amount","�0 to �5k","�5k to �10k","�10k to �15k","�15k+");
			for ($i=0; $i<5; $i++) { 
				$a_sel = ($i == $_GET["amounts"]) ? "selected=\"selected\"" : "";
				echo "<option value=\"".$i."\" $a_sel>".$am_txt[$i]."</option>"; 
			} 
			?>
		</select>
	  </div>
	  <div style="clear:left;"></div>
       
      <div style="float:left; width:140px; margin:5px;"><b>County</b></div>
	  <div style="float:left; margin:5px;">
		<input type="text" name="location" value="<?php echo $_GET["location"]; ?>" />
	  </div>
	  <div style="clear:left;"></div>
	 <div style="float:left; width:140px; margin:5px;"><strong>Destination</strong></div>
	  <div style="float:left;  margin:5px;">
		<select name="destination" size="10" style="width:500px;">
		  <option value="0" style="font-size:11px;" selected="selected">All Destinations</option>
		  <?php  echo $nav_list; ?>
		</select>
	  </div>
	  <div style="clear:left;"></div>
    </div>
    </div>
	<hr />
   	</form>
	<p><b>Contacts</b></p>
	<form action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
	  <input type="hidden" name="action" value="Continue" />
	  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
		<?php  echo $active_list; ?>
	  </select>
	  <p style="float:left; margin-top:10px;">
		<input type="submit" name="action" value="Continue">
	  </p>
	</form>
    <div style="float:right;margin-top:10px;">
    <form method="post" action="<?php echo $site_url; ?>/oos/search-submissions.php" name="getcsvdata">
    	<input type="hidden" name="query" value="<?php echo "type=csv&s=".$_GET["s"]."&stype=".$_GET["stype"]."&from=".$_GET["date_from"]."&to=".$_GET["date_to"]."&destination=".$_GET["destination_id"]."&b_client=".$_GET["bclient"]."&location=".$_GET["location"]."&amounts=".$_GET["amounts"]; ?>" />
		<input type="submit" name="action" value="Download CSV" />
    </form>
</div>
<div style="clear:left;"></div>
	<p>
	<hr />
	</p>
	<!--<p><b>Archived Contact</b></p>
	<form action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
	  <input type="hidden" name="action" value="Continue" />
	  <input type="hidden" name="action_type" value="view_archive" />
	  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
		<?php  echo $archive_list; ?>
	  </select>
	  <p style="margin-top:10px;">
		<input type="submit" name="action" value="Continue">
	  </p>
	</form>
    -->
	</div>
	<div style="clear:left;"></div>
	<?
/*
echo $query_cl."<br/>";
echo $query_p."<br/>";
echo $query_wq."<br/>";
echo $query_cb."<br/>";
echo $query_c."<br/>";

print_r($testing);
*/	$get_template->bottomHTML();
	$sql_command->close();	
	
}
?>


