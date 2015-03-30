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

if($_POST["action"] == "Continue") {



$start_year = 2007;
$end_year = date("Y",$time);

while($end_year > $start_year) {
if($_POST["id"] == $end_year) {
header("Location: $site_url/admin/add-testimonial.php");
$sql_command->close();
}
$end_year--;
}


$result = $sql_command->select($database_testimonials,"id,testimonial,timestamp","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$dateline = date("d-m-Y",$record[2]);

$get_template->topHTML();
?>
<h1>Update Testimonial</h1>
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
<form action="<?php echo $site_url; ?>/admin/update-testimonial.php" method="POST" name="testimonial">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Date</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="dateinfo" value="<?php echo $dateline; ?>"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'testimonial',
		// input name
		'controlname': 'dateinfo'
	});

	</script></div>
<div style="clear:left;"></div>
<textarea name="testimonial" style="height:400px; width:100%;" id="the_editor"><?php echo stripslashes($record[1]); ?></textarea>
<?php echo $admin_editor; ?>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update">
</form></div>


<div style="float: left; margin-left:580px; margin-top:10px;">
<form action="<?php echo $site_url; ?>/admin/update-testimonial.php" method="POST" name="testimonial">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>

</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
if(!$_POST["testimonial"]) { $error .= "Missing Testimonial<br>"; }

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

if(!$day or !$month or !$year) { $error .= "Please select a valid date<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Testimonial","Oops!","$error","Link","admin/update-testimonial.php");
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

$savedate = mktime(0, 0, 0, $month, $day, $year);

$sql_command->update($database_testimonials,"year='".addslashes($year)."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_testimonials,"testimonial='".addslashes($_POST["testimonial"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_testimonials,"timestamp='".addslashes($savedate)."'","id='".addslashes($_POST["id"])."'");



$get_template->topHTML();
?>
<h1>Testimonial Updated</h1>

<p>Testimonial has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_testimonials,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>Testimonial Deleted</h1>

<p>The testimonial has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {
	


$start_year = 2007;
$end_year = date("Y",$time);

while($end_year > $start_year) {
$list .= "<option value=\"$end_year\" style=\"font-weight:bold;\">$end_year</option>\n";

$result = $sql_command->select($database_testimonials,"id,testimonial,timestamp","WHERE year='$end_year' ORDER BY timestamp");
$row = $sql_command->results($result);

foreach($row as $record) {
$dateline = date("d F Y", $record[2]);

$start = strpos($record[1], '<strong>');
$end = strpos($record[1], '</strong>', $start);
$paragraph = substr($record[1], $start, $end-$start+4);
$paragraph = str_replace("<strong>", "", $paragraph);
$paragraph = str_replace("</strong>", "", $paragraph);
$paragraph = (strlen($paragraph) > 200) ? substr($paragraph, 0, 200) . '...' : $paragraph;

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">- $dateline - ".$paragraph."</option>\n";
}


$end_year--;
}




$get_template->topHTML();
?>
<h1>Update Testimonial</h1>

<form action="<?php echo $site_url; ?>/admin/update-testimonial.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>