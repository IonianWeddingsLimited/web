<?

require ("_includes/settings.php");
require ("_includes/function.templates.php");
include ("_includes/function.database.php");
require ("_includes/function.smtp.php");

$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


$get_template = new main_template();



if($_POST["page"] == "feedback") {
	





if(!$_POST["yourname"]) { $error .= "Missing Name<br>"; }
if(!$_POST["how_you_heard"]) { $error .= "Missing How did you hear about Ionian Weddings<br>"; }
if(!$_POST["why_greece"]) { $error .= "Missing Why did you choose Greece <br>"; }
if(!$_POST["why_choose_us"]) { $error .= "Missing Why did you choose to book with Ionian Weddings<br>"; }

if(!$_POST["book_accommodation_with"]) { $error .= "Missing Who did you book your accommodation with<br>"; }
if(!$_POST["wedding_insurance"]) { $error .= "Missing Who did you book your wedding insurance with<br>"; }
if(!$_POST["how_many_guests"]) { $error .= "Missing How many guests did you have at your wedding<br>"; }
if(!$_POST["comments"]) { $error .= "Missing Comments<br>"; }
if(!$_POST["testimonial"]) { $error .= "Missing Testimonial<br>"; }


$_SESSION["yourname"] = $_POST["yourname"];
$_SESSION["how_you_heard"] = $_POST["how_you_heard"];
$_SESSION["why_greece"] = $_POST["why_greece"];
$_SESSION["why_choose_us"] = $_POST["why_choose_us"];
$_SESSION["our_wesite"] = $_POST["our_wesite"];
$_SESSION["speed_of_response"] = $_POST["speed_of_response"];
$_SESSION["ease_of_booking"] = $_POST["ease_of_booking"];
$_SESSION["information_given"] = $_POST["information_given"];
$_SESSION["wedding_guide"] = $_POST["wedding_guide"];
$_SESSION["wedding_checklist"] = $_POST["wedding_checklist"];
$_SESSION["face_to_face_meetings"] = $_POST["face_to_face_meetings"];
$_SESSION["paperwork"] = $_POST["paperwork"];
$_SESSION["wedding_planner"] = $_POST["wedding_planner"];
$_SESSION["wedding_location"] = $_POST["wedding_location"];
$_SESSION["reception_location"] = $_POST["reception_location"];
$_SESSION["overall_service"] = $_POST["overall_service"];
$_SESSION["book_accommodation_with"] = $_POST["book_accommodation_with"];
$_SESSION["wedding_insurance"] = $_POST["wedding_insurance"];
$_SESSION["how_many_guests"] = $_POST["how_many_guests"];
$_SESSION["comments"] = $_POST["comments"];
$_SESSION["feature_testimonials"] = $_POST["feature_testimonials"];
$_SESSION["feature_website"] = $_POST["feature_website"];
$_SESSION["feature_facebook"] = $_POST["feature_facebook"];
$_SESSION["testimonial"] = $_POST["testimonial"];
$_SESSION["privacypolicy"] = $_POST["privacypolicy"];
$_SESSION["termsandconditions"] = $_POST["termsandconditions"];

if($error) {
$print_title = "<h1>Oops!</h1>";
$print_body = "<p>$error</p>";
$print_back = "<p><a href=\"$site_url/feedback/\">Back</a></p>";
} else {


$extra ="<br>";
$message .= "Name: ".$_POST["yourname"].$extra;
$message .= "How did you hear about Ionian Weddings: ". $_POST["how_you_heard"].$extra;
$message .= "Why did you choose Greece / Cyprus and the particular island for your wedding: ". $_POST["why_greece"].$extra;
$message .= "Why did you choose to book with Ionian Weddings: ". $_POST["why_choose_us"].$extra;
$message .= "Our Website: ".$_POST["our_wesite"].$extra;
$message .= "Speed of responses: ". $_POST["speed_of_response"].$extra;
$message .= "Ease of booking: ". $_POST["ease_of_booking"].$extra;
$message .= "Information given: ". $_POST["information_given"].$extra;
$message .= "Wedding Guide: ".$_POST["wedding_guide"].$extra;
$message .= "Wedding Checklist: ". $_POST["wedding_checklist"].$extra;
$message .= "Face to face meetings: ". $_POST["face_to_face_meetings"].$extra;
$message .= "Paperwork (if done by us:) ". $_POST["paperwork"].$extra;
$message .= "Wedding planner: ". $_POST["wedding_planner"].$extra;
$message .= "Wedding location: ". $_POST["wedding_location"].$extra;
$message .= "Reception location: ". $_POST["reception_location"].$extra;
$message .= "Overall service provided: ". $_POST["overall_service"].$extra;
$message .= "Who did you book your accommodation with: ". $_POST["book_accommodation_with"].$extra;
$message .= "Who did you book your wedding insurance with: ". $_POST["wedding_insurance"].$extra;
$message .= "How many guests did you have at your wedding: ". $_POST["how_many_guests"].$extra.$extra;
$message .= "Let us know any comments you may have: ". $_POST["comments"].$extra.$extra;
$message .= "Your testimonials on our website: ". $_POST["feature_testimonials"].$extra;
$message .= "Your wedding photos on our website: ". $_POST["feature_website"].$extra;
$message .= "Your wedding photos on our Facebook page: ". $_POST["feature_facebook"].$extra.$extra;
$message .= "Testimonial: ". $_POST["testimonial"].$extra;

$global_from = "weddings@ionianweddings.co.uk";

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW -Feedback Form");		// Name is optional
$mail->AddTo($send_contact_form);	// Name is optional

$mail->Subject = "Ionian Weddings Feedback from ".$_POST["yourname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

if($_POST["contactus_callback"] == "Y") { $_POST["contactus_callback"] = "Yes"; } else { $_POST["contactus_callback"] = "No"; }


if($_POST["feature_testimonials"] != "Yes") { $_POST["feature_testimonials"] = "No"; }
if($_POST["feature_website"] != "Yes") { $_POST["feature_website"] = "No"; }
if($_POST["feature_facebook"] != "Yes") { $_POST["feature_facebook"] = "No"; }
if($_POST["privacypolicy"] != "Yes") { $_POST["privacypolicy"] = "No"; }
if($_POST["termsandconditions"] != "Yes") { $_POST["termsandconditions"] = "No"; }

$cols = "yourname,how_you_heard,why_greece,why_choose_us,our_wesite,speed_of_response,ease_of_booking,information_given,wedding_guide,wedding_checklist,face_to_face_meetings,paperwork,wedding_planner,wedding_location,reception_location,overall_service,book_accommodation_with,wedding_insurance,how_many_guests,comments,feature_testimonials,feature_website,feature_facebook,testimonial,privacypolicy,termsandconditions,archive,timestamp";

$values = "'".addslashes($_POST["yourname"])."',
'".addslashes($_POST["how_you_heard"])."',
'".addslashes($_POST["why_greece"])."',
'".addslashes($_POST["why_choose_us"])."',
'".addslashes($_POST["our_wesite"])."',
'".addslashes($_POST["speed_of_response"])."',
'".addslashes($_POST["ease_of_booking"])."',
'".addslashes($_POST["information_given"])."',
'".addslashes($_POST["wedding_guide"])."',
'".addslashes($_POST["wedding_checklist"])."',
'".addslashes($_POST["face_to_face_meetings"])."',
'".addslashes($_POST["paperwork"])."',
'".addslashes($_POST["wedding_planner"])."',
'".addslashes($_POST["wedding_location"])."',
'".addslashes($_POST["reception_location"])."',
'".addslashes($_POST["overall_service"])."',
'".addslashes($_POST["book_accommodation_with"])."',
'".addslashes($_POST["wedding_insurance"])."',
'".addslashes($_POST["how_many_guests"])."',
'".addslashes($_POST["comments"])."',
'".addslashes($_POST["feature_testimonials"])."',
'".addslashes($_POST["feature_website"])."',
'".addslashes($_POST["feature_facebook"])."',
'".addslashes($_POST["testimonial"])."',
'".addslashes($_POST["privacypolicy"])."',
'".addslashes($_POST["termsandconditions"])."',
'No',
'$time'";

if(!$_SESSION["lastsubmitted"] or $_SESSION["lastsubmitted"] < $time) {
$sql_command->insert($database_info_feedback,$cols,$values);
$_SESSION["lastsubmitted"] = $time + 120;
}

$_SESSION["yourname"] = "";
$_SESSION["how_you_heard"] = "";
$_SESSION["why_greece"] = "";
$_SESSION["why_choose_us"] = "";
$_SESSION["our_wesite"] = "";
$_SESSION["speed_of_response"] = "";
$_SESSION["ease_of_booking"] = "";
$_SESSION["information_given"] = "";
$_SESSION["wedding_guide"] = "";
$_SESSION["wedding_checklist"] = "";
$_SESSION["face_to_face_meetings"] = "";
$_SESSION["paperwork"] = "";
$_SESSION["wedding_planner"] = "";
$_SESSION["wedding_location"]  = "";
$_SESSION["reception_location"]  = "";
$_SESSION["overall_service"] = "";
$_SESSION["book_accommodation_with"] = "";
$_SESSION["wedding_insurance"] = "";
$_SESSION["how_many_guests"]  = "";
$_SESSION["comments"] = "";
$_SESSION["feature_testimonials"]  = "";
$_SESSION["feature_website"] = "";
$_SESSION["feature_facebook"] = "";
$_SESSION["testimonial"] = "";
$_SESSION["privacypolicy"] = "";
$_SESSION["termsandconditions"] = "";

$print_title = "<h1>Email Sent</h1>";
$print_body = "<p>Thank you for your feedback.</p>";
}
}











if($_POST["page"] == "contactus") {
	
if(!$_POST["contactus_firstname"]) { $error .= "Missing First name<br>"; }
if(!$_POST["contactus_lastname"]) { $error .= "Missing Last name<br>"; }
if(!$_POST["contactus_email"]) { $error .= "Missing Email<br>"; }
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST["contactus_email"])) { $error .= "Invalid Email Entered<br>"; }

if(!$_POST["contactus_tel"]) { $error .= "Missing Telephone<br>"; }
if(!$_POST["contactus_address_1"]) { $error .= "Missing Address Line 1<br>"; }
if(!$_POST["contactus_town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["contactus_county"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_countryID"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_postcode"]) { $error .= "Missing Postcode<br>"; }
if(!$_POST["contactus_comments"]) { $error .= "Missing Comments<br>"; }


if($_POST["contactus_privacypolicy"] != "Y") { $error .= "Please agree to the privacy policy<br>"; }
if($_POST["contactus_termsandconditions"] != "Y") { $error .= "Please agree to the terms and conditions<br>"; }



$_SESSION["contactus_firstname"] = $_POST["contactus_firstname"];
$_SESSION["contactus_lastname"] = $_POST["contactus_lastname"];
$_SESSION["contactus_email"] = $_POST["contactus_email"];
$_SESSION["contactus_tel"] = $_POST["contactus_tel"];
$_SESSION["contactus_address_1"] = $_POST["contactus_address_1"];
$_SESSION["contactus_address_2"] = $_POST["contactus_address_2"];
$_SESSION["contactus_address_3"] = $_POST["contactus_address_3"];
$_SESSION["contactus_town"] = $_POST["contactus_town"];
$_SESSION["contactus_countryID"] = $_POST["contactus_countryID"];
$_SESSION["contactus_postcode"] = $_POST["contactus_postcode"];
$_SESSION["contactus_comments"] = $_POST["contactus_comments"];
$_SESSION["contactus_county"] = $_POST["contactus_county"];

if($error) {
$print_title = "<h1>Oops!</h1>";
$print_body = "<p>$error</p>";
$print_back = "<p><a href=\"$site_url/contact-us/\">Back</a></p>";
} else {


$extra ="<br>";
$message .= "First name: ".$_POST["contactus_firstname"].$extra;
$message .= "Last name: ".$_POST["contactus_lastname"].$extra;
$message .= "Email: ".$_POST["contactus_email"].$extra;
$message .= "Telephone: ".$_POST["contactus_tel"].$extra;
$message .= "Address 1: ".$_POST["contactus_address_1"].$extra;
$message .= "Address 2: ".$_POST["contactus_address_2"].$extra;
$message .= "Address 3: ".$_POST["contactus_address_3"].$extra;
$message .= "Town: ".$_POST["contactus_town"].$extra;
$message .= "Country: ".$_POST["contactus_countryID"].$extra;
$message .= "Postcode: ".$_POST["contactus_postcode"].$extra.$extra;
$message .= "Comments".$extra;
$message .= $_POST["contactus_comments"];

$global_from = $_POST["contactus_email"];

if($_SESSION["subject"]) {
$subject = $_SESSION["subject"];
} else {
$subject = "Enquiry";
}

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Contact Form");		// Name is optional
$mail->AddTo($_POST["contactus_email"]);	// Name is optional

$mail->Subject = "Ionian Weddings $subject from ".$_POST["contactus_firstname"] . " ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Contact Form");		// Name is optional
$mail->AddTo($send_contact_form);	// Name is optional

$mail->Subject = "Ionian Weddings $subject from ".$_POST["contactus_firstname"] . " ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();


if($_POST["contactus_callback"] == "Y") { $_POST["contactus_callback"] = "Yes"; } else { $_POST["contactus_callback"] = "No"; }

$cols = "firstname,lastname,email,telephone,address_1,address_2,address_3,town,country,postcode,comments,archive,timestamp,getlatestoffers,subject,county,ip";
$values = "'".addslashes($_POST["contactus_firstname"])."',
'".addslashes($_POST["contactus_lastname"])."',
'".addslashes($_POST["contactus_email"])."',
'".addslashes($_POST["contactus_tel"])."',
'".addslashes($_POST["contactus_address_1"])."',
'".addslashes($_POST["contactus_address_2"])."',
'".addslashes($_POST["contactus_address_3"])."',
'".addslashes($_POST["contactus_town"])."',
'".addslashes($_POST["contactus_countryID"])."',
'".addslashes($_POST["contactus_postcode"])."',
'".addslashes($_POST["contactus_comments"])."',
'No',
'$time',
'".addslashes($_POST["contactus_callback"])."',
'".addslashes($_SESSION["subject"])."',
'".addslashes($_POST["contactus_county"])."',
'".addslashes($_SERVER["REMOTE_ADDR"])."'";


if(!$_SESSION["lastsubmitted"] or $_SESSION["lastsubmitted"] < $time) {
$sql_command->insert($database_info_contactus,$cols,$values);
$_SESSION["lastsubmitted"] = $time + 120;
}


$_SESSION["contactus_firstname"] = "";
$_SESSION["contactus_lastname"] = "";
$_SESSION["contactus_email"] = "";
$_SESSION["contactus_tel"] = "";
$_SESSION["contactus_address_1"] = "";
$_SESSION["contactus_address_2"] = "";
$_SESSION["contactus_address_3"] = "";
$_SESSION["contactus_town"] = "";
$_SESSION["contactus_countryID"] = "";
$_SESSION["contactus_postcode"] = "";
$_SESSION["contactus_comments"] = "";
$_SESSION["subject"] = "";
$_SESSION["contactus_county"] = "";

$print_title = "<h1>Email Sent</h1>";
$print_body = "<p>Thank you for contacting us, you will receive a reply shortly.</p>";
}
}








if($_POST["page"] == "bookacallback") {
	
if(!$_POST["contactus_firstname"]) { $error .= "Missing First name<br>"; }
if(!$_POST["contactus_lastname"]) { $error .= "Missing Last name<br>"; }
if(!$_POST["contactus_email"]) { $error .= "Missing Email<br>"; }
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST["contactus_email"])) { $error .= "Invalid Email Entered<br>"; }

if(!$_POST["contactus_tel"]) { $error .= "Missing Telephone<br>"; }
if(!$_POST["contactus_address_1"]) { $error .= "Missing Address Line 1<br>"; }
if(!$_POST["contactus_town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["contactus_county"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_countryID"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_postcode"]) { $error .= "Missing Postcode<br>"; }

if($_POST["contactus_privacypolicy"] != "Y") { $error .= "Please agree to the privacy policy<br>"; }
if($_POST["contactus_termsandconditions"] != "Y") { $error .= "Please agree to the terms and conditions<br>"; }

$_SESSION["contactus_firstname"] = $_POST["contactus_firstname"];
$_SESSION["contactus_lastname"] = $_POST["contactus_lastname"];
$_SESSION["contactus_email"] = $_POST["contactus_email"];
$_SESSION["contactus_tel"] = $_POST["contactus_tel"];
$_SESSION["contactus_address_1"] = $_POST["contactus_address_1"];
$_SESSION["contactus_address_2"] = $_POST["contactus_address_2"];
$_SESSION["contactus_address_3"] = $_POST["contactus_address_3"];
$_SESSION["contactus_town"] = $_POST["contactus_town"];
$_SESSION["contactus_county"] = $_POST["contactus_county"];
$_SESSION["contactus_countryID"] = $_POST["contactus_countryID"];
$_SESSION["contactus_postcode"] = $_POST["contactus_postcode"];
$_SESSION["contactus_comments"] = $_POST["contactus_comments"];
$_SESSION["weddingquestionnaire_callback"] = $_POST["weddingquestionnaire_callback"];
$_SESSION["weddingquestionnaire_callbacktimeID"] = $_POST["weddingquestionnaire_callbacktimeID"];
$_SESSION["weddingquestionnaire_callbackdate"] = $_POST["weddingquestionnaire_callbackdate"];
$_SESSION["weddingquestionnaire_date"] = $_POST["weddingquestionnaire_date"];

if($error) {
$print_title = "<h1>Oops!</h1>";
$print_body = "<p>$error</p>";
$print_back = "<p><a href=\"$site_url/book-a-call-back/\">Back</a></p>";
} else {


$extra ="<br>";
$message .= "First name: ".$_POST["contactus_firstname"].$extra;
$message .= "Last name: ".$_POST["contactus_lastname"].$extra;
$message .= "Email: ".$_POST["contactus_email"].$extra;
$message .= "Telephone: ".$_POST["contactus_tel"].$extra;
$message .= "Address 1: ".$_POST["contactus_address_1"].$extra;
$message .= "Address 2: ".$_POST["contactus_address_2"].$extra;
$message .= "Address 3: ".$_POST["contactus_address_3"].$extra;
$message .= "Town: ".$_POST["contactus_town"].$extra;
$message .= "Country: ".$_POST["contactus_countryID"].$extra;
$message .= "Postcode: ".$_POST["contactus_postcode"].$extra.$extra;

$message .= "Preferred days or dates for your call back: ".$_POST["weddingquestionnaire_callbackdate"].$extra;
$message .= "Preferred times for your call back: ".$_POST["weddingquestionnaire_callbacktimeID"].$extra.$extra;

$message .= "Comments".$extra;
$message .= $_POST["contactus_comments"];

$global_from = $_POST["contactus_email"];

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Book a Call Back");		// Name is optional
$mail->AddTo($_POST["contactus_email"]);	// Name is optional

$mail->Subject = "Ionian Weddings Call Back from ".$_POST["contactus_firstname"]." ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Book a Call Back");		// Name is optional
$mail->AddTo($send_contact_form);	// Name is optional

$mail->Subject = "Ionian Weddings Call Back from ".$_POST["contactus_firstname"]." ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

if($_POST["contactus_callback"] == "Y") { $_POST["contactus_callback"] = "Yes"; } else { $_POST["contactus_callback"] = "No"; }

$cols = "firstname,lastname,email,telephone,address_1,address_2,address_3,town,country,postcode,comments,archive,timestamp,getlatestoffers,preferred_day,preferred_time,county,ip";
$values = "'".addslashes($_POST["contactus_firstname"])."',
'".addslashes($_POST["contactus_lastname"])."',
'".addslashes($_POST["contactus_email"])."',
'".addslashes($_POST["contactus_tel"])."',
'".addslashes($_POST["contactus_address_1"])."',
'".addslashes($_POST["contactus_address_2"])."',
'".addslashes($_POST["contactus_address_3"])."',
'".addslashes($_POST["contactus_town"])."',
'".addslashes($_POST["contactus_countryID"])."',
'".addslashes($_POST["contactus_postcode"])."',
'".addslashes($_POST["contactus_comments"])."',
'No',
'$time',
'".addslashes($_POST["contactus_callback"])."',
'".addslashes($_POST["weddingquestionnaire_callbackdate"])."',
'".addslashes($_POST["weddingquestionnaire_callbacktimeID"])."',
'".addslashes($_POST["contactus_county"])."',
'".addslashes($_SERVER["REMOTE_ADDR"])."'";


if(!$_SESSION["lastsubmitted"] or $_SESSION["lastsubmitted"] < $time) {
$sql_command->insert($database_info_bookacallback,$cols,$values);
$_SESSION["lastsubmitted"] = $time + 120;
}




$_SESSION["contactus_firstname"] = "";
$_SESSION["contactus_lastname"] = "";
$_SESSION["contactus_email"] = "";
$_SESSION["contactus_tel"] = "";
$_SESSION["contactus_address_1"] = "";
$_SESSION["contactus_address_2"] = "";
$_SESSION["contactus_address_3"] = "";
$_SESSION["contactus_town"] = "";
$_SESSION["contactus_countryID"] = "";
$_SESSION["contactus_county"] = "";
$_SESSION["contactus_postcode"] = "";
$_SESSION["contactus_comments"] = "";
$_SESSION["weddingquestionnaire_callback"] = "";
$_SESSION["weddingquestionnaire_callbacktimeID"] = "";
$_SESSION["weddingquestionnaire_callbackdate"] = "";
$_SESSION["weddingquestionnaire_date"] = "";

$print_title = "<h1>Email Sent</h1>";
$print_body = "<p>Thank you for contacting us, you will receive a reply shortly.</p>";
}
}










if($_POST["page"] == "personalconsultations") {
	
if(!$_POST["contactus_firstname"]) { $error .= "Missing First name<br>"; }
if(!$_POST["contactus_lastname"]) { $error .= "Missing Last name<br>"; }
if(!$_POST["contactus_email"]) { $error .= "Missing Email<br>"; }
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST["contactus_email"])) { $error .= "Invalid Email Entered<br>"; }

if(!$_POST["contactus_tel"]) { $error .= "Missing Telephone<br>"; }
if(!$_POST["contactus_address_1"]) { $error .= "Missing Address Line 1<br>"; }
if(!$_POST["contactus_town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["contactus_county"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_countryID"]) { $error .= "Missing County<br>"; }
if(!$_POST["contactus_postcode"]) { $error .= "Missing Postcode<br>"; }

if($_POST["contactus_privacypolicy"] != "Y") { $error .= "Please agree to the privacy policy<br>"; }
if($_POST["contactus_termsandconditions"] != "Y") { $error .= "Please agree to the terms and conditions<br>"; }

$_SESSION["contactus_firstname"] = $_POST["contactus_firstname"];
$_SESSION["contactus_lastname"] = $_POST["contactus_lastname"];
$_SESSION["contactus_email"] = $_POST["contactus_email"];
$_SESSION["contactus_tel"] = $_POST["contactus_tel"];
$_SESSION["contactus_address_1"] = $_POST["contactus_address_1"];
$_SESSION["contactus_address_2"] = $_POST["contactus_address_2"];
$_SESSION["contactus_address_3"] = $_POST["contactus_address_3"];
$_SESSION["contactus_town"] = $_POST["contactus_town"];
$_SESSION["contactus_countryID"] = $_POST["contactus_countryID"];
$_SESSION["contactus_postcode"] = $_POST["contactus_postcode"];
$_SESSION["contactus_comments"] = $_POST["contactus_comments"];
$_SESSION["weddingquestionnaire_callback"] = $_POST["weddingquestionnaire_callback"];
$_SESSION["weddingquestionnaire_callbacktimeID"] = $_POST["weddingquestionnaire_callbacktimeID"];
$_SESSION["weddingquestionnaire_callbackdate"] = $_POST["weddingquestionnaire_callbackdate"];
$_SESSION["contactus_county"] = $_POST["contactus_county"];

if($error) {
$print_title = "<h1>Oops!</h1>";
$print_body = "<p>$error</p>";
$print_back = "<p><a href=\"$site_url/personal-consultations/\">Back</a></p>";
} else {


$extra ="<br>";
$message .= "First name: ".$_POST["contactus_firstname"].$extra;
$message .= "Last name: ".$_POST["contactus_lastname"].$extra;
$message .= "Email: ".$_POST["contactus_email"].$extra;
$message .= "Telephone: ".$_POST["contactus_tel"].$extra;
$message .= "Address 1: ".$_POST["contactus_address_1"].$extra;
$message .= "Address 2: ".$_POST["contactus_address_2"].$extra;
$message .= "Address 3: ".$_POST["contactus_address_3"].$extra;
$message .= "Town: ".$_POST["contactus_town"].$extra;
$message .= "Country: ".$_POST["contactus_countryID"].$extra;
$message .= "Postcode: ".$_POST["contactus_postcode"].$extra.$extra;

$message .= "Would you like us to call you back? (Yes. Please call me): ".$_POST["weddingquestionnaire_callback"].$extra;
$message .= "Preferred days or dates for your consultation: ".$_POST["weddingquestionnaire_callbackdate"].$extra;
$message .= "Preferred times for your consultation: ".$_POST["weddingquestionnaire_callbacktimeID"].$extra.$extra;

$message .= "Comments".$extra;
$message .= $_POST["contactus_comments"];

$global_from = $_POST["contactus_email"];

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Personal Consultations");		// Name is optional
$mail->AddTo($_POST["contactus_email"]);	// Name is optional

$mail->Subject = "Ionian Weddings Consultation from ".$_POST["contactus_firstname"] ." ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Personal Consultations");		// Name is optional
$mail->AddTo($send_contact_form);	// Name is optional

$mail->Subject = "Ionian Weddings Consultation from ".$_POST["contactus_firstname"] ." ".$_POST["contactus_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

if($_POST["contactus_callback"] == "Y") { $_POST["contactus_callback"] = "Yes"; } else { $_POST["contactus_callback"] = "No"; }
if($_POST["weddingquestionnaire_callback"] == "Y") { $_POST["weddingquestionnaire_callback"] = "Yes"; } else { $_POST["weddingquestionnaire_callback"] = "No"; }

$cols = "firstname,lastname,email,telephone,address_1,address_2,address_3,town,country,postcode,comments,archive,timestamp,getlatestoffers,preferred_day,preferred_time,callback,county,ip";
$values = "'".addslashes($_POST["contactus_firstname"])."',
'".addslashes($_POST["contactus_lastname"])."',
'".addslashes($_POST["contactus_email"])."',
'".addslashes($_POST["contactus_tel"])."',
'".addslashes($_POST["contactus_address_1"])."',
'".addslashes($_POST["contactus_address_2"])."',
'".addslashes($_POST["contactus_address_3"])."',
'".addslashes($_POST["contactus_town"])."',
'".addslashes($_POST["contactus_countryID"])."',
'".addslashes($_POST["contactus_postcode"])."',
'".addslashes($_POST["contactus_comments"])."',
'No',
'$time',
'".addslashes($_POST["contactus_callback"])."',
'".addslashes($_POST["weddingquestionnaire_callbackdate"])."',
'".addslashes($_POST["weddingquestionnaire_callbacktimeID"])."',
'".addslashes($_POST["weddingquestionnaire_callback"])."',
'".addslashes($_POST["contactus_county"])."',
'".addslashes($_SERVER["REMOTE_ADDR"])."'";


if(!$_SESSION["lastsubmitted"] or $_SESSION["lastsubmitted"] < $time) {
$sql_command->insert($database_info_personal_consultations,$cols,$values);
$_SESSION["lastsubmitted"] = $time + 120;
}


$_SESSION["contactus_firstname"] = "";
$_SESSION["contactus_lastname"] = "";
$_SESSION["contactus_email"] = "";
$_SESSION["contactus_tel"] = "";
$_SESSION["contactus_address_1"] = "";
$_SESSION["contactus_address_2"] = "";
$_SESSION["contactus_address_3"] = "";
$_SESSION["contactus_town"] = "";
$_SESSION["contactus_countryID"] = "";
$_SESSION["contactus_postcode"] = "";
$_SESSION["contactus_comments"] = "";
$_SESSION["weddingquestionnaire_callback"] = "";
$_SESSION["weddingquestionnaire_callbacktimeID"] = "";
$_SESSION["weddingquestionnaire_callbackdate"] = "";
$_SESSION["contactus_county"] = "";

$print_title = "<h1>Email Sent</h1>";
$print_body = "<p>Thank you for contacting us, you will receive a reply shortly.</p>";
}
}







if($_POST["page"] == "weddingquestionnaire") {
	
if(!$_POST["weddingquestionnaire_bride_firstname"]) { $error .= "Missing Bride First name<br>"; }
if(!$_POST["weddingquestionnaire_bride_lastname"]) { $error .= "Missing Bride Last name<br>"; }
if(!$_POST["weddingquestionnaire_bride_email"]) { $error .= "Missing Bride Email<br>"; }
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST["weddingquestionnaire_bride_email"])) { $error .= "Invalid Bride Email Entered<br>"; }
if(!$_POST["weddingquestionnaire_bride_tel"]) { $error .= "Missing Bride Tel<br>"; }
if(!$_POST["weddingquestionnaire_bride_nationality"]) { $error .= "Missing Bride Nationality<br>"; }
if(!$_POST["weddingquestionnaire_bride_countryofresidenceID"]) { $error .= "Missing Bride Country of Residence<br>"; }

if(!$_POST["weddingquestionnaire_groom_firstname"]) { $error .= "Missing Groom First name<br>"; }
if(!$_POST["weddingquestionnaire_groom_lastname"]) { $error .= "Missing Groom Last name<br>"; }
if(!$_POST["weddingquestionnaire_groom_email"]) { $error .= "Missing Groom Email<br>"; }
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST["weddingquestionnaire_groom_email"])) { $error .= "Invalid Groom Email Entered<br>"; }
if(!$_POST["weddingquestionnaire_groom_tel"]) { $error .= "Missing Groom Tel<br>"; }
if(!$_POST["weddingquestionnaire_groom_nationality"]) { $error .= "Missing Groom Nationality<br>"; }
if(!$_POST["weddingquestionnaire_groom_countryofresidenceID"]) { $error .= "Missing Groom Country of Residence<br>"; }

if(!$_POST["weddingquestionnaire_address_1"]) { $error .= "Missing Address Line 1<br>"; }
if(!$_POST["weddingquestionnaire_town"]) { $error .= "Missing Town<br>"; }
if(!$_POST["contactus_county"]) { $error .= "Missing County<br>"; }
if(!$_POST["weddingquestionnaire_countryID"]) { $error .= "Missing County<br>"; }
if(!$_POST["weddingquestionnaire_postcode"]) { $error .= "Missing Postcode<br>"; }

$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);
$destination_selected = "No";
foreach($destination_row as $destination_record) {
$line2 = $destination_record[0];
if($_POST[$line2] == "Yes") {
$destination_selected = "Yes";
}

$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

foreach($destination_island_row as $destination_island) {
$line = $destination_record[0]."_".$destination_island[0];
if($_POST[$line] == "Yes") {
$destination_selected = "Yes";
}
}
}

if($destination_selected == "No" and  !$_POST["destination_other"]) {
$error .= "Please select a prefered destination<br>";
}


if(!$_POST["weddingquestionnaire_date"]) { $error .= "Please enter an estimated wedding date<br />"; }

if($_POST["weddingquestionnaire_date"]) {
list($w_day,$w_month,$w_year) = explode("-",$_POST["weddingquestionnaire_date"]);
if(strlen($w_day)!=2 or strlen($w_month)!=2 or strlen($w_year)!=4) { $error .= "Invalid Wedding date, format  <strong>DD-MM-YYYY</strong><br />"; }
}


if(!$_POST["weddingquestionnaire_guestcount"]) { $error .= "Missing No Guests<br>"; }
if(!$_POST["weddingquestionnaire_typeofceremonyID"]) { $error .= "Missing Type of Ceremony<br>"; }
if(!$_POST["weddingquestionnaire_ceremony"]) { $error .= "Missing Preferred Ceremony<br>"; }
if(!$_POST["weddingquestionnaire_reception"]) { $error .= "Missing Reception<br>"; }
if(!$_POST["weddingquestionnaire_budget"]) { $error .= "Missing Budget<br>"; }
if(!$_POST["weddingquestionnaire_comments"]) { $error .= "Missing Comments<br>"; }


if($_POST["weddingquestionnaire_privacypolicy"] != "Y") { $error .= "Please agree to the privacy policy<br>"; }
if($_POST["weddingquestionnaire_termsandconditions"] != "Y") { $error .= "Please agree to the terms and conditions<br>"; }



$_SESSION["weddingquestionnaire_bride_firstname"] = $_POST["weddingquestionnaire_bride_firstname"];
$_SESSION["weddingquestionnaire_bride_lastname"] = $_POST["weddingquestionnaire_bride_lastname"];
$_SESSION["weddingquestionnaire_bride_email"] = $_POST["weddingquestionnaire_bride_email"];
$_SESSION["weddingquestionnaire_bride_tel"] = $_POST["weddingquestionnaire_bride_tel"];
$_SESSION["weddingquestionnaire_bride_nationality"] = $_POST["weddingquestionnaire_bride_nationality"];
$_SESSION["weddingquestionnaire_bride_countryofresidenceID"] = $_POST["weddingquestionnaire_bride_countryofresidenceID"];
$_SESSION["weddingquestionnaire_groom_firstname"] = $_POST["weddingquestionnaire_groom_firstname"];
$_SESSION["weddingquestionnaire_groom_lastname"] = $_POST["weddingquestionnaire_groom_lastname"];
$_SESSION["weddingquestionnaire_groom_tel"] = $_POST["weddingquestionnaire_groom_tel"];
$_SESSION["weddingquestionnaire_groom_email"] = $_POST["weddingquestionnaire_groom_email"];
$_SESSION["weddingquestionnaire_groom_nationality"] = $_POST["weddingquestionnaire_groom_nationality"];
$_SESSION["weddingquestionnaire_groom_countryofresidenceID"] = $_POST["weddingquestionnaire_groom_countryofresidenceID"];
$_SESSION["weddingquestionnaire_address_1"] = $_POST["weddingquestionnaire_address_1"];
$_SESSION["weddingquestionnaire_address_2"] = $_POST["weddingquestionnaire_address_2"];
$_SESSION["weddingquestionnaire_address_3"] = $_POST["weddingquestionnaire_address_3"];
$_SESSION["weddingquestionnaire_town"] = $_POST["weddingquestionnaire_town"];
$_SESSION["weddingquestionnaire_countryID"] = $_POST["weddingquestionnaire_countryID"];
$_SESSION["weddingquestionnaire_postcode"]  = $_POST["weddingquestionnaire_postcode"];
$_SESSION["weddingquestionnaire_date"] = $_POST["weddingquestionnaire_date"];
$_SESSION["weddingquestionnaire_guestcount"] = $_POST["weddingquestionnaire_guestcount"];
$_SESSION["weddingquestionnaire_typeofceremonyID"] = $_POST["weddingquestionnaire_typeofceremonyID"];
$_SESSION["weddingquestionnaire_ceremony"] = $_POST["weddingquestionnaire_ceremony"];
$_SESSION["weddingquestionnaire_reception"] = $_POST["weddingquestionnaire_reception"];
$_SESSION["weddingquestionnaire_budget"] = $_POST["weddingquestionnaire_budget"];
$_SESSION["weddingquestionnaire_comments"] = $_POST["weddingquestionnaire_comments"];
$_SESSION["weddingquestionnaire_marketingID"] = $_POST["weddingquestionnaire_marketingID"];
$_SESSION["weddingquestionnaire_recommendation"] = $_POST["weddingquestionnaire_recommendation"];
$_SESSION["weddingquestionnaire_callback"] = $_POST["weddingquestionnaire_callback"];
$_SESSION["weddingquestionnaire_callbackdate"] = $_POST["weddingquestionnaire_callbackdate"];
$_SESSION["weddingquestionnaire_callbacktimeID"] = $_POST["weddingquestionnaire_callbacktimeID"];
$_SESSION["weddingquestionnaire_bookingtimeID"] = $_POST["weddingquestionnaire_bookingtimeID"];
$_SESSION["weddingquestionnaire_offers"] = $_POST["weddingquestionnaire_offers"];
$_SESSION["destination_other"] = $_POST["destination_other"];
$_SESSION["contactus_county"] = $_POST["contactus_county"];

if($error) {
$print_title = "<h1>Oops!</h1>";
$print_body = "<p>$error</p>";
$print_back = "<p><a href=\"$site_url/wedding-questionnaire/\">Back</a></p>";
} else {


$extra ="<br>";
$message .= "Bride First name: ".$_POST["weddingquestionnaire_bride_firstname"].$extra;
$message .= "Bride Last name: ".$_POST["weddingquestionnaire_bride_lastname"].$extra;
$message .= "Bride Telephone: ".$_POST["weddingquestionnaire_bride_tel"].$extra;
$message .= "Bride Email: ".$_POST["weddingquestionnaire_bride_email"].$extra;
$message .= "Bride Nationality: ".$_POST["weddingquestionnaire_bride_nationality"].$extra;
$message .= "Bride Country of residence (current): ".$_POST["weddingquestionnaire_bride_countryofresidenceID"].$extra.$extra;

$message .= "Groom First name: ".$_POST["weddingquestionnaire_groom_firstname"].$extra;
$message .= "Groom Last name: ".$_POST["weddingquestionnaire_groom_lastname"].$extra;
$message .= "Groom Telephone: ".$_POST["weddingquestionnaire_groom_tel"].$extra;
$message .= "Groom Email: ".$_POST["weddingquestionnaire_groom_email"].$extra;
$message .= "Groom Nationality: ".$_POST["weddingquestionnaire_groom_nationality"].$extra;
$message .= "Groom Country of residence (current): ".$_POST["weddingquestionnaire_groom_countryofresidenceID"].$extra.$extra;

$message .= "Address 1: ".$_POST["weddingquestionnaire_address_1"].$extra;
$message .= "Address 2: ".$_POST["weddingquestionnaire_address_2"].$extra;
$message .= "Address 3: ".$_POST["weddingquestionnaire_address_3"].$extra;
$message .= "Address Town: ".$_POST["weddingquestionnaire_town"].$extra;
$message .= "Country: ".$_POST["weddingquestionnaire_countryID"].$extra;
$message .= "Post code: ".$_POST["weddingquestionnaire_postcode"].$extra.$extra;


$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);

foreach($destination_row as $destination_record) {
$message .= stripslashes($destination_record[1]).$extra;

$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

foreach($destination_island_row as $destination_island) {
$idline = $destination_record[0]."_".$destination_island[0];
$message .= stripslashes($destination_island[1]).": ".$_POST[$idline].$extra;
}
}

$message .= $extra;
$message .= "Other Destination: ".$_POST["destination_other"].$extra.$extra;

$message .= "Estimated date of wedding (dd/mm/yyyy): ".$_POST["weddingquestionnaire_date"].$extra;
$message .= "Anticipated number of guests: ".$_POST["weddingquestionnaire_guestcount"].$extra;
$message .= "Type of ceremony: ".$_POST["weddingquestionnaire_typeofceremonyID"].$extra;
$message .= "Prefered ceremony setting: ".$_POST["weddingquestionnaire_ceremony"].$extra;
$message .= "Prefered reception setting: ".$_POST["weddingquestionnaire_reception"].$extra;
$message .= "Budgets and Finances: ".$_POST["weddingquestionnaire_budget"].$extra.$extra;

$message .= "Comments: ".$_POST["weddingquestionnaire_comments"].$extra.$extra;

$message .= "Who recommended us / where did you read about us?: ".$_POST["weddingquestionnaire_marketingID"].$extra;
$message .= "How did you hear about Ionian Weddings?: ".$_POST["weddingquestionnaire_recommendation"].$extra;
$message .= "Yes. Please call me: ".$_POST["weddingquestionnaire_callback"].$extra;
$message .= "Preferred days or dates: ".$_POST["weddingquestionnaire_callbackdate"].$extra;
$message .= "Preferred times: ".$_POST["weddingquestionnaire_callbacktimeID"].$extra;
$message .= "When do you plan to book: ".$_POST["weddingquestionnaire_bookingtimeID"].$extra;
$message .= "Offers: ".$_POST["weddingquestionnaire_offers"].$extra;

$global_from = $_POST["weddingquestionnaire_bride_email"];

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Wedding Questionnaire");		// Name is optional
$mail->AddTo($_POST["weddingquestionnaire_bride_email"]);	// Name is optional

$mail->Subject = "Ionian Weddings Questionnaire from ".$_POST["weddingquestionnaire_bride_firstname"]." ".$_POST["weddingquestionnaire_bride_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();

$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;

$mail->SetFrom($smtp_email,"IW - Wedding Questionnaire");		// Name is optional
$mail->AddTo($send_contact_form);	// Name is optional

$mail->Subject = "Ionian Weddings Questionnaire from ".$_POST["weddingquestionnaire_bride_firstname"]." ".$_POST["weddingquestionnaire_bride_lastname"];
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();


if($_POST["weddingquestionnaire_offers"] == "Y") { $_POST["weddingquestionnaire_offers"] = "Yes"; } else { $_POST["weddingquestionnaire_offers"] = "No"; }
if($_POST["weddingquestionnaire_callback"] == "Y") { $_POST["weddingquestionnaire_callback"] = "Yes"; } else { $_POST["weddingquestionnaire_callback"] = "No"; }


$cols = "bride_firstname,bride_lastname,bride_email,bride_telephone,bride_nationality,bride_country,groom_firstname,groom_lastname,groom_email,groom_telephone,groom_nationality,groom_country,
address_1,address_2,address_3,town,country,postcode,estimated_date_of_wedding,anticipated_number_of_guests,type_of_ceremony,prefered_ceremony_setting,prefered_reception_setting,estimated_budget,comments,hearaboutus,
recommended_us,callback,callback_date,callback_time,plan_to_book,archive,getlatestoffers,timestamp,destination_other,county,ip";

$values = "'".addslashes($_POST["weddingquestionnaire_bride_firstname"])."',
'".addslashes($_POST["weddingquestionnaire_bride_lastname"])."',
'".addslashes($_POST["weddingquestionnaire_bride_email"])."',
'".addslashes($_POST["weddingquestionnaire_bride_tel"])."',
'".addslashes($_POST["weddingquestionnaire_bride_nationality"])."',
'".addslashes($_POST["weddingquestionnaire_bride_countryofresidenceID"])."',
'".addslashes($_POST["weddingquestionnaire_groom_firstname"])."',
'".addslashes($_POST["weddingquestionnaire_groom_lastname"])."',
'".addslashes($_POST["weddingquestionnaire_groom_email"])."',
'".addslashes($_POST["weddingquestionnaire_groom_tel"])."',
'".addslashes($_POST["weddingquestionnaire_groom_nationality"])."',
'".addslashes($_POST["weddingquestionnaire_groom_countryofresidenceID"])."',
'".addslashes($_POST["weddingquestionnaire_address_1"])."',
'".addslashes($_POST["weddingquestionnaire_address_2"])."',
'".addslashes($_POST["weddingquestionnaire_address_3"])."',
'".addslashes($_POST["weddingquestionnaire_town"])."',
'".addslashes($_POST["weddingquestionnaire_countryID"])."',
'".addslashes($_POST["weddingquestionnaire_postcode"])."',
'".addslashes($_POST["weddingquestionnaire_date"])."',
'".addslashes($_POST["weddingquestionnaire_guestcount"])."',
'".addslashes($_POST["weddingquestionnaire_typeofceremonyID"])."',
'".addslashes($_POST["weddingquestionnaire_ceremony"])."',
'".addslashes($_POST["weddingquestionnaire_reception"])."',
'".addslashes($_POST["weddingquestionnaire_budget"])."',
'".addslashes($_POST["weddingquestionnaire_comments"])."',
'".addslashes($_POST["weddingquestionnaire_marketingID"])."',
'".addslashes($_POST["weddingquestionnaire_recommendation"])."',
'".addslashes($_POST["weddingquestionnaire_callback"])."',
'".addslashes($_POST["weddingquestionnaire_callbackdate"])."',
'".addslashes($_POST["weddingquestionnaire_callbacktimeID"])."',
'".addslashes($_POST["weddingquestionnaire_bookingtimeID"])."',
'No',
'".addslashes($_POST["weddingquestionnaire_offers"])."',
'$time',
'".addslashes($_POST["destination_other"])."',
'".addslashes($_POST["contactus_county"])."',
'".addslashes($_SERVER["REMOTE_ADDR"])."'";


if(!$_SESSION["lastsubmitted"] or $_SESSION["lastsubmitted"] < $time) {
$sql_command->insert($database_info_wedding_questionnaire,$cols,$values);
$max_id = $sql_command->maxid($database_info_wedding_questionnaire,"id");

$cols = "questionaire_id,destination_id,island_id";

$destination_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='2' ORDER BY displayorder");
$destination_row = $sql_command->results($destination_result);

foreach($destination_row as $destination_record) {
$line2 = $destination_record[0];
if($_POST[$line2] == "Yes") {
$sql_command->insert($database_questionaire_destinations,$cols,"'".addslashes($max_id)."','".addslashes($destination_record[0])."',''");
}

$destination_island_result = $sql_command->select($database_navigation,"id,page_name","WHERE parent_id='".addslashes($destination_record[0])."' ORDER BY displayorder");
$destination_island_row = $sql_command->results($destination_island_result);

foreach($destination_island_row as $destination_island) {
$line = $destination_record[0]."_".$destination_island[0];
if($_POST[$line] == "Yes") {
$sql_command->insert($database_questionaire_destinations,$cols,"'".addslashes($max_id)."','".addslashes($destination_record[0])."','".addslashes($destination_island[0])."'");
}
}
}
$_SESSION["lastsubmitted"] = $time + 120;
}

$_SESSION["weddingquestionnaire_bride_firstname"] = "";
$_SESSION["weddingquestionnaire_bride_lastname"] = "";
$_SESSION["weddingquestionnaire_bride_email"] = "";
$_SESSION["weddingquestionnaire_bride_tel"] = "";
$_SESSION["weddingquestionnaire_bride_nationality"] = "";
$_SESSION["weddingquestionnaire_bride_countryofresidenceID"] = "";
$_SESSION["weddingquestionnaire_groom_firstname"] = "";
$_SESSION["weddingquestionnaire_groom_lastname"] = "";
$_SESSION["weddingquestionnaire_groom_tel"] = "";
$_SESSION["weddingquestionnaire_groom_email"] = "";
$_SESSION["weddingquestionnaire_groom_nationality"] = "";
$_SESSION["weddingquestionnaire_groom_countryofresidenceID"] = "";
$_SESSION["weddingquestionnaire_address_1"] = "";
$_SESSION["weddingquestionnaire_address_2"] = "";
$_SESSION["weddingquestionnaire_address_3"] = "";
$_SESSION["weddingquestionnaire_town"] = "";
$_SESSION["weddingquestionnaire_countryID"] = "";
$_SESSION["weddingquestionnaire_postcode"]  = "";
$_SESSION["weddingquestionnaire_date"] = "";
$_SESSION["weddingquestionnaire_guestcount"] = "";
$_SESSION["weddingquestionnaire_typeofceremonyID"] = "";
$_SESSION["weddingquestionnaire_ceremony"] = "";
$_SESSION["weddingquestionnaire_reception"] = "";
$_SESSION["weddingquestionnaire_budget"] = "";
$_SESSION["weddingquestionnaire_comments"] = "";
$_SESSION["weddingquestionnaire_marketingID"] = "";
$_SESSION["weddingquestionnaire_recommendation"] = "";
$_SESSION["weddingquestionnaire_callback"] = "";
$_SESSION["weddingquestionnaire_callbackdate"] = "";
$_SESSION["weddingquestionnaire_callbacktimeID"] = "";
$_SESSION["weddingquestionnaire_bookingtimeID"] = "";
$_SESSION["weddingquestionnaire_offers"] = "";
$_SESSION["destination_other"] = "";
$_SESSION["contactus_county"] = "";

$print_title = "<h1>Email Sent</h1>";
$print_body = "<p>Thank you for contacting us, you will receive a reply shortly.</p>";
}
}












if($print_title and $print_body) {
$get_template->topHTML();
?>
<div class="maincopy">
<?php echo $print_title; ?>
<?php echo $print_body; ?>
<?php echo $print_back; ?>
</div>
<?
$get_template->bottomHTML();
$sql_command->close();
} else {
header("Location: $site_url/index.php");
$sql_command->close();
}





?>