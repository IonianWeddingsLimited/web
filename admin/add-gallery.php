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


if($_POST["action"] == "Create Gallery") {

if(!$_POST["gallery_name"]) { $error .= "Please enter a gallery name<br>"; }

$check_result = $sql_command->select($database_gallery,"id","WHERE gallery_name='".addslashes($_POST["gallery_name"])."'");
$check_record = $sql_command->result($check_result);

if($check_record[0]) { $error .= "A Gallery with this name already exists<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Gallery","Oops!","$error","Link","admin/add-gallery.php");
$get_template->bottomHTML();
$sql_command->close();
}

$gallery_xml = ereg_replace("[^A-Za-z0-9]", "", strtolower($_POST["gallery_name"]));
$gallery_xml = "xml_".$gallery_xml.".xml";

$cols = "gallery_name,xml_filename,timestamp";
$values = "'".addslashes($_POST["gallery_name"])."',
'$gallery_xml',
'$time'";

$sql_command->insert($database_gallery,$cols,$values);
$maxid = $sql_command->maxid($database_gallery,"id"); 


$xml_text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<tn2>
	<gallery>
		<title>Fixed Dimensions</title>
		<description>Some Description</description>
		<file_root>$site_url/images/gallery/</file_root>
		<images>\n";



$cols = "gallery_id,imagename,timestamp,displayorder,title,description,link";

$result = $sql_command->select($database_pendingimages,"id,imagename","");
$row = $sql_command->results($result);

foreach($row as $record) {


copy($base_directory."/images/gallery/pending/".stripslashes($record[1]), $base_directory."/images/gallery/$maxid-".stripslashes($record[1]));
copy($base_directory."/images/gallery/pending_thumbnail/".stripslashes($record[1]), $base_directory."/images/gallery/thumbnail/$maxid-".stripslashes($record[1]));
unlink($base_directory."/images/gallery/pending/".stripslashes($record[1]));
unlink($base_directory."/images/gallery/pending_thumbnail/".stripslashes($record[1]));

$values = "'$maxid',
'$maxid-".addslashes($record[1])."',
'$time',
'999',
'',
'',
''";

$sql_command->insert($database_gallery_pics,$cols,$values);

$xml_text .= "<image>
				<image_src>/$maxid-".stripslashes($record[1])."</image_src>
				<thumb_src>/thumbnail/$maxid-".stripslashes($record[1])."</thumb_src>
			</image>\n";
			
}

$sql_command->delete($database_pendingimages,"");
	
$xml_text .= "</images>
	</gallery>
</tn2>
\n";	

$fh = fopen($base_directory."/xml/".$gallery_xml, 'w') or die("can't open file");
fwrite($fh, $xml_text);
fclose($fh);

$get_template->topHTML();
?>
<h1>Gallery Created</h1>

<p>The gallery has now been created</p>
<p>To update the display order of this gallery please <a href="<?php echo $site_url; ?>/admin/update-gallery-display.php?a=view&id=<?php echo $maxid; ?>">click here</a></p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_GET["a"] == "pullimages") {
$result = $sql_command->select($database_pendingimages,"id,imagename","");
$row = $sql_command->results($result);

foreach($row as $record) {
$pending_images .= "<div style=\"float:left; margin:5px; text-align:center; border:1px solid #ccc; padding:5px;\"><img src=\"$site_url/images/gallery/pending_thumbnail/".stripslashes($record[1])."\" alt=\"".stripslashes($record[1])."\"><br>
[ <a href=\"add-gallery.php?a=delete&id=$record[0]\">Delete</a> ] </div>\n";
}

echo $pending_images."<div style=\"clear:left;\"></div>\n";


} else {

if($_GET["a"] == "delete" and $_GET["id"]) {
	
$result = $sql_command->select($database_pendingimages,"id,imagename","WHERE id='".addslashes($_GET["id"])."'");
$record = $sql_command->result($result);

if($record[0]) {
$sql_command->delete($database_pendingimages,"id='".addslashes($_GET["id"])."'");	
@unlink($base_directory."/images/gallery/pending/".stripslashes($record[1]));
@unlink($base_directory."/images/gallery/pending_thumbnail/".stripslashes($record[1]));
}
}



if($_GET["a"] == "deleteall") {
	
$result = $sql_command->select($database_pendingimages,"id,imagename","");
$row = $sql_command->results($result);

foreach($row as $record) {
@unlink($base_directory."/images/gallery/pending/".stripslashes($record[1]));
@unlink($base_directory."/images/gallery/pending_thumbnail/".stripslashes($record[1]));	
}

$sql_command->delete($database_pendingimages,"");	
}

$add_header = "<script type=\"text/javascript\" src=\"$site_url/admin/uploads/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"$site_url/admin/uploads/swfobject.js\"></script>
<script type=\"text/javascript\" src=\"$site_url/admin/uploads/jquery.uploadify.v2.1.4.js\"></script>
<script type=\"text/javascript\">
$(function() {
$('#custom_file_upload').uploadify({
  'uploader'       : '$site_url/admin/uploads/uploadify.swf',
  'script'         : '$site_url/admin/uploads/uploadify.php',
  'cancelImg'      : '$site_url/admin/uploads/cancel.png',
  'folder'         : '/images/gallery/pending',
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

xmlhttp.open(\"GET\",\"add-gallery.php?a=pullimages\",true);
xmlhttp.send();
}

setInterval( \"pull_current_images()\", 3000 );

$(function() {
		   pull_current_images();
		   });
		   
		   
</script>";


$get_template->topHTML();
?>

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
<h1>Add Gallery</h1>
<p>There is a 2mb file size limit for uploads and the required image size is 516 by 415 pixels</p>
   
        <div class="custom-demo">
        <div id="status-message">Select image files to upload:</div>


<div id="custom-queue"></div>
<input id="custom_file_upload" type="file" name="Filedata" />        </div>

<p><hr /></p>
<form method="post" action="<?php echo $site_url; ?>/admin/add-gallery.php">
<div style="float:left; width:160px; margin:5px;"><b>Gallery Name</b></div>
<div style="float:left; margin:5px;"><input type="text" name="gallery_name" style="width:400px;" /></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Current Images</b><p>[  <a href="<?php echo $site_url; ?>/admin/add-gallery.php?a=deleteall">Delete All</a> ]</p></div>
<div id="currentimages" style="float:left; width:540px;"></div>
<div style="clear:left;"></div>
<p><input type="submit" name="action" value="Create Gallery" /></p>
</form>


<?
$get_template->bottomHTML();
$sql_command->close();
}

?>