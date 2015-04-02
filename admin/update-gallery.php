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


if($_POST["action"] == "Update") {

if(strlen($_POST["gallery_name"]) > 1) {
$sql_command->update($database_gallery,"gallery_name='".addslashes($_POST["gallery_name"])."'","id='".addslashes($_POST["gallery_id"])."'");
}

$get_template->topHTML();
?>
<h1>Gallery Updated</h1>

<p>The gallery has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {

$result = $sql_command->select($database_gallery_pics,"id,imagename","WHERE gallery_id='".addslashes($_POST["gallery_id"])."'");
$row = $sql_command->results($result);

foreach($row as $record) {
@unlink($base_directory."/images/gallery/".stripslashes($record[1]));
@unlink($base_directory."/images/gallery/thumbnail/".stripslashes($record[1]));	
}

$sql_command->delete($database_gallery,"id='".addslashes($_POST["gallery_id"])."'");	
$sql_command->delete($database_gallery_pics,"gallery_id='".addslashes($_POST["gallery_id"])."'");	
@unlink($base_directory."/xml/".$_POST["xml_file"]);	

$sql_command->update($database_navigation,"xml_file_name=''","xml_file_name='".addslashes($_POST["xml_file"])."'");	

$get_template->topHTML();
?>
<h1>Gallery Deleted</h1>

<p>The gallery has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_GET["a"] == "pullimages") {
	
$xml_result = $sql_command->select($database_gallery,"xml_filename","WHERE id='".addslashes($_GET["gallery_id"])."'");
$xml_record = $sql_command->result($xml_result);

$result = $sql_command->select($database_gallery_pics,"id,imagename","WHERE gallery_id='".addslashes($_GET["gallery_id"])."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {
$pending_images .= "<div style=\"float:left; margin:5px; text-align:center; border:1px solid #ccc; padding:5px;\"><img src=\"$site_url/images/gallery/thumbnail/".stripslashes($record[1])."\" alt=\"".stripslashes($record[1])."\"><br>
[ <a href=\"$site_url/admin/update-gallery.php?a=view&c=delete&gallery_id=".$_GET["gallery_id"]."&id=$record[0]&gallery_xml=".$xml_record[0]."\">Delete</a> ] </div>\n";

}

echo $pending_images."<div style=\"clear:left;\"></div>";


} elseif($_POST["action"] == "Continue" or $_GET["a"] == "view") {

if($_GET["gallery_id"]) { $_POST["gallery_id"] = $_GET["gallery_id"]; }

if($_GET["c"] == "delete" and $_GET["id"]) {
	
$result = $sql_command->select($database_gallery_pics,"id,imagename","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

if($record[0]) {
$sql_command->delete($database_gallery_pics,"id='".addslashes($_GET["id"])."'");	
@unlink($base_directory."/images/gallery/".stripslashes($record[1]));
@unlink($base_directory."/images/gallery/thumbnail/".stripslashes($record[1]));
}

$xml_text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<tn2>
	<gallery>
		<title>Fixed Dimensions</title>
		<description>Some Description</description>
		<file_root>$site_url/images/gallery/</file_root>
		<images>\n";

$result = $sql_command->select($database_gallery_pics,"id,imagename,title,description,link","WHERE gallery_id='".addslashes($_GET["gallery_id"])."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {

$record[2] = str_replace("&","&amp;",$record[2]);
$record[2] = str_replace("'","",$record[2]);
$record[2] = str_replace("’","",$record[2]);

$record[3] = str_replace("&","&amp;",$record[3]);
$record[3] = str_replace("'","",$record[3]);
$record[3] = str_replace("’","",$record[3]);

if($record[4] != 0) {
list($theyear,$theid) = explode("_",$record[4]);
$thelink = "<url>".$site_url."/testimonials/".$theyear."/#".$theid."</url>";
$title = stripslashes($record[2]);
} else {
$title = stripslashes($record[2]);	
$thelink = "";
}

$xml_text .= "<image>
				<title>$title</title>
				<description>".stripslashes($record[3])."</description>
				$thelink
				<image_src>/".stripslashes($record[1])."</image_src>
				<thumb_src>/thumbnail/".stripslashes($record[1])."</thumb_src>
			</image>\n";		
}

$xml_text .= "</images>
	</gallery>
</tn2>
\n";	

$fh = fopen($base_directory."/xml/".$_GET["gallery_xml"], 'w') or die("can't open file");
fwrite($fh, $xml_text);
fclose($fh);
}



if($_GET["c"] == "deleteall") {
	
$result = $sql_command->select($database_gallery_pics,"id,imagename","WHERE gallery_id='".addslashes($_GET["gallery_id"])."'");
$row = $sql_command->results($result);

foreach($row as $record) {
@unlink($base_directory."/images/gallery/".stripslashes($record[1]));
@unlink($base_directory."/images/gallery/thumbnail/".stripslashes($record[1]));	
}

$sql_command->delete($database_gallery_pics,"gallery_id='".addslashes($_GET["gallery_id"])."'");	

$xml_text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<tn2>
	<gallery>
		<title>Fixed Dimensions</title>
		<description>Some Description</description>
		<file_root>$site_url/images/gallery/</file_root>
		<images>
		</images>
	</gallery>
</tn2>
\n";	

$fh = fopen($base_directory."/xml/".$_GET["gallery_xml"], 'w') or die("can't open file");
fwrite($fh, $xml_text);
fclose($fh);
}


$result = $sql_command->select($database_gallery,"id,gallery_name,xml_filename","WHERE id='".addslashes($_POST["gallery_id"])."'");
$record = $sql_command->result($result);


$add_header = "<script type=\"text/javascript\" src=\"$site_url/admin/uploads/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"$site_url/admin/uploads/swfobject.js\"></script>
<script type=\"text/javascript\" src=\"$site_url/admin/uploads/jquery.uploadify.v2.1.4.js\"></script>
<script type=\"text/javascript\">
$(function() {
$('#custom_file_upload').uploadify({
  'uploader'       : '$site_url/admin/uploads/uploadify.swf',
  'script'         : '$site_url/admin/uploads/uploadify.php',
  'cancelImg'      : '$site_url/admin/uploads/cancel.png',
  'folder'         : '/images/gallery|".$_POST["gallery_id"]."|".$record[2]."|".$record[1]."',
  'multi'          : true,
  'auto'           : true,
  'fileExt'        : '*.jpg;*.jpeg;*.gif;*.png',
  'fileDesc'       : 'Image Files Only (.JPG, .JPEG, .GIF, .PNG)',
  'queueID'        : 'custom-queue',
  'queueSizeLimit' : 100,
  'simUploadLimit' : 5,
  'sizeLimit'   : 2097152,
  'removeCompleted': false,
  'onSelectOnce'   : function(event,data) {
      $('#status-message').text(data.filesSelected + ' files have been added to the queue.');
    },
  'onAllComplete'  : function(event,data) {
      $('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
    }
});			
});

</script>
<script type=\"text/javascript\">
function pull_current_images() {

if (window.XMLHttpRequest) {
xmlhttp=new XMLHttpRequest(); 
} else {
xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
}

xmlhttp.onreadystatechange=function() {
if (xmlhttp.readyState==4 && xmlhttp.status==200) {
document.getElementById('currentimages').innerHTML=xmlhttp.responseText;
}
}

xmlhttp.open(\"GET\",\"update-gallery.php?a=pullimages&gallery_id=".$_POST["gallery_id"]."\",true);
xmlhttp.send();
}

setInterval( \"pull_current_images()\", 3000 );

$(function() {
		   pull_current_images();
		   });
		   
		   
</script>";

$get_template->topHTML();
?>
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
        <style type="text/css">

#custom-queue {
  border: 1px solid #E5E5E5;
  height: 170px;
margin-bottom: 10px;
  width: 710px;
  overflow:scroll;
}			

.uploadifyQueueItem {
	background-color: #F5F5F5;
	border: 2px solid #E5E5E5;
	font: 11px Verdana, Geneva, sans-serif;
	margin-top: 1px;
	padding: 5px;
	width: 680px;
}
.uploadifyError {
	background-color: #FDE5DD !important;
	border: 2px solid #FBCBBC !important;
}
.uploadifyQueueItem .cancel {
	float: right;
}
.uploadifyQueue .completed {
	background-color: #E5E5E5;
}
.uploadifyProgress {
	background-color: #E5E5E5;
	margin-top: 10px;
	width: 100%;
}
.uploadifyProgressBar {
	background-color: #0099FF;
	height: 3px;
	width: 1px;
}
</style>
<h1>Update Gallery</h1>
<?php if($record[1] == "Home Page") { ?>
<p>There is a 2mb file size limit for uploads and the required image size is 935 by 386 pixels</p>
<?php } else { ?>
<p>There is a 2mb file size limit for uploads and the required image size is 516 by 415 pixels</p>
<?php } ?> 
 
        <div class="custom-demo">
        <div id="status-message">Select image files to upload:</div>


<div id="custom-queue"></div>
<input id="custom_file_upload" type="file" name="Filedata" />        </div>
<form action="<?php echo $site_url; ?>/admin/update-gallery.php" method="post">
<input type="hidden" name="gallery_id" value="<?php echo $record[0]; ?>" />
<p><hr /></p>
<div style="float:left; width:160px; margin:5px;"><b>Gallery Name</b></div>
<div style="float:left; margin:5px;"><input type="text" name="gallery_name" value="<?php echo stripslashes($record[1]); ?>" style="width:400px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Current Images</b><p>[  <a href="<?php echo $site_url; ?>/admin/update-gallery.php?a=view&c=deleteall&gallery_id=<?php echo $record[0]; ?>&gallery_xml=<?php echo $record[2]; ?>">Delete All</a> ]</p>
<p><a href="<?php echo $site_url; ?>/admin/update-gallery-display.php?a=view&id=<?php echo $record[0]; ?>">Update display order</a></p></div>
<div id="currentimages" style="float:left; width:540px;"></div>
<div style="clear:left;"></div>


<div style="float:left;">
<p><input type="submit" name="action" value="Update"/></p>
</div>
</form>

<form action="<?php echo $site_url; ?>/admin/update-gallery.php" method="post">
<input type="hidden" name="gallery_id" value="<?php echo $record[0]; ?>" />
<input type="hidden" name="xml_file" value="<?php echo $record[2]; ?>" />
<div style="float:right;">
<p><input type="submit" name="action" value="Delete"  onclick="return deletechecked();"/></p>
</div>

<div style="clear:both;"></div>
</form>


<?
$get_template->bottomHTML();
$sql_command->close();
} else {
	

$result = $sql_command->select($database_gallery,"id,gallery_name","ORDER BY gallery_name");
$row = $sql_command->results($result);
	
foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:11px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Gallery</h1>

<form action="<?php echo $site_url; ?>/admin/update-gallery.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="gallery_id" class="inputbox_town" size="30" style="width:710px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}

?>