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

if($_POST["action"] == "Add") {
	
if(!$_POST["testimonial"]) { $error .= "Missing Testimonial<br>"; }

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

if(!$day or !$month or !$year) { $error .= "Please select a valid date<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Testimonial","Oops!","$error","Link","admin/add-testimonial.php");
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

$savedate = mktime(0, 0, 0, $month, $day, $year);

$values = "'".addslashes($year)."',
'".addslashes($_POST["testimonial"])."',
'$savedate'";

$sql_command->insert($database_testimonials,"year,testimonial,timestamp",$values);


$get_template->topHTML();
?>
<h1>Testimonial Added</h1>

<p>The testimonial has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$this_year = date("d-m-Y",$time);



$get_template->topHTML();
?>
<h1>Add Testimonial</h1>

<form action="<?php echo $site_url; ?>/admin/add-testimonial.php" method="POST" name="testimonial">
<div style="float:left; width:160px; margin:5px;"><b>Date</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="dateinfo" value="<?php echo $this_year; ?>"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'testimonial',
		// input name
		'controlname': 'dateinfo'
	});

	</script></div>
<div style="clear:left;"></div>
<textarea name="testimonial" style="height:400px; width:100%;" id="the_editor"></textarea>
<?php echo $admin_editor; ?>
<p style="margin-top:10px;"><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>