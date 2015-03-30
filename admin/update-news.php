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


class Resize_Image {
   
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }

   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToWidth($width) {
   	  $oldwidth = $this->getWidth();
   	  if($oldwidth>$width) {
      $ratio = $width / $oldwidth;
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
	  }
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }  
     
}


if($_POST["action"] == "Continue") {
	
$result = $sql_command->select($database_news,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);


$gallery_list .= "<option value=\"0\">None</option>";

$gallery_result = $sql_command->select($database_gallery,"id,gallery_name","ORDER BY gallery_name");
$gallery_row = $sql_command->results($gallery_result);

foreach($gallery_row as $gallery_record) {
if($gallery_record[0] == $record[10]) {
$gallery_list .= "<option value=\"".stripslashes($gallery_record[0])."\" selected=\"selected\">".stripslashes($gallery_record[1])."</option>";
} else {
$gallery_list .= "<option value=\"".stripslashes($gallery_record[0])."\">".stripslashes($gallery_record[1])."</option>";
}
}


$dateline = date("d-m-Y",$record[6]);

$get_template->topHTML();
?>
<h1>Update <?php echo $_POST["type"]; ?></h1>
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

<form action="<?php echo $site_url; ?>/admin/update-news.php" method="POST" name="news" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo $_POST["type"]; ?>" />
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="hidden" name="old_title" value="<?php echo stripslashes($record[1]); ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Date Added</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="dateinfo" value="<?php echo $dateline; ?>"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'news',
		// input name
		'controlname': 'dateinfo'
	});

	</script></div>
<div style="float:left; margin:5px;">(Expire Date if 5 Star Wedding / Special Offer)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Overrule Expire Date</b></div>
<div style="float:left; margin:5px;"><input type="checkbox" name="overrule" value="Yes" <?php if($record[7] == "Yes") { echo "checked=\"checked\""; } ?>/> (5 Star Package and Special Offers only)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>External Link</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="external_link" style="width:300px;" value="<?php echo stripslashes($record[9]); ?>"/> (Will override collapsible list option)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Short Des</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tagline" style="width:500px;" value="<?php echo stripslashes($record[2]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Insert Gallery</b></div>
<div style="float:left; margin:5px;"><select name="gallery_id"><?php echo $gallery_list; ?></select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>New Image</b></div>
<div style="float:left; margin:5px;">	<input type="file" name="newsimage"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Existing Image</b></div>
<div style="float:left; margin:5px;"><input type="checkbox" name="delete_old_image" value="Yes" /> Tick to remove existing image</div>
<div style="clear:left;"></div>
<textarea name="newspost" style="height:600px; width:100%;" id="the_editor"><?php echo stripslashes($record[3]); ?></textarea>
<?php echo $admin_editor; ?>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update">
</div>
</form>

<div style="float: left; margin-left:580px; margin-top:10px;">
<form action="<?php echo $site_url; ?>/admin/update-news.php" method="POST" name="testimonial">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();">
</div>
<div style="clear:left;"></div>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
if(!$_POST["title"]) { $error .= "Missing Title<br>"; }

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

if(!$day or !$month or !$year) { $error .= "Please select a valid date<br>"; }

$one_newimage_name = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES["newsimage"]["name"]));
$one_image_filename = $_FILES["newsimage"]["tmp_name"];

if($one_image_filename) {
if(!ereg(".jpg",$one_newimage_name)) { 
if(!ereg(".gif",$one_newimage_name)) {
if(!ereg(".jpeg",$one_newimage_name)) { 
if(!ereg(".png",$one_newimage_name)) { 
$error .= "Image must be .jpg / .jpeg / .gif or .png<br>"; 
}
}
}
}
}


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update","Oops!","$error","Link","admin/update-news.php");
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

$savedate = mktime(0, 0, 0, $month, $day, $year);

if($_POST["overrule"] != "Yes") {
$_POST["overrule"] = "No";
}

if($_POST["delete_old_image"] == "Yes") {
$sql_command->update($database_news,"imagename=''","id='".addslashes($_POST["id"])."'");	
}


if($_POST["gallery_id"] == 0) {
$xml_filename = "";	
} else {
$gallery_result = $sql_command->select($database_gallery,"id,xml_filename","WHERE id='".addslashes($_POST["gallery_id"])."'");
$gallery_record = $sql_command->result($gallery_result);
$xml_filename = stripslashes($gallery_record[1]);	
}

$sql_command->update($database_news,"xml_file_name='".addslashes($xml_filename)."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"gallery_id='".addslashes($_POST["gallery_id"])."'","id='".addslashes($_POST["id"])."'");


$sql_command->update($database_news,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"tagline='".addslashes($_POST["tagline"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"content='".addslashes($_POST["newspost"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"timestamp='".addslashes($savedate)."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"overrule='".addslashes($_POST["overrule"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"external_link='".addslashes($_POST["external_link"])."'","id='".addslashes($_POST["id"])."'");

if($_POST["title"] != $_POST["old_title"]) {
$workout_url = strtolower($_POST["title"]);
$workout_url = str_replace(" - ", " ", $workout_url);
$workout_url = str_replace("-", " ", $workout_url);
$workout_url = str_replace(" ", "-", $workout_url);
$workout_url = ereg_replace("[^A-Za-z0-9-]", "", $workout_url);
$workout_url = str_replace("--", "-", $workout_url);
$workout_url = str_replace("----", "-", $workout_url);
$workout_url = str_replace("-----", "-", $workout_url);
$workout_url = str_replace("------", "-", $workout_url);

$currentcount = -1;

function checkurl($variable,$count) {
global $sql_command, $database_news;
$count++;

if($count == 0) {
$workout_url = $variable; 
} else {
$workout_url = $variable . "-$count"; 
}

$existing_result = $sql_command->select($database_news,"id","WHERE short_url='".addslashes($workout_url)."'");
$existing_row = $sql_command->result($existing_result);

if($existing_row[0]) { 
return checkurl($variable,$count);
} else {
return $workout_url;
}
}

$workout_url = checkurl($workout_url,$currentcount);
$sql_command->update($database_news,"short_url='".addslashes($workout_url)."'","id='".addslashes($_POST["id"])."'");
}

if($one_image_filename) {
$save_image = $_POST["id"] ."-".$one_newimage_name;

$image = new Resize_Image();
$image->load($one_image_filename);
$image->resizeToWidth("500");
$image->save($base_directory."/images/page/news/".$save_image);
	
$sql_command->update($database_news,"imagename='".addslashes($save_image)."'","id='".addslashes($_POST["id"])."'");
}


$get_template->topHTML();
?>
<h1><?php echo $_POST["type"]; ?> Updated</h1>

<p>The <?php echo $_POST["type"]; ?> has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_news,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1><?php echo $_POST["type"]; ?> Deleted</h1>

<p>The <?php echo $_POST["type"]; ?> has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} elseif($_POST["action"] == "View") {

$result = $sql_command->select($database_news,"id,title,timestamp","WHERE type='".addslashes($_POST["type"])."' ORDER BY timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
	
$dateline = date("d F Y",$record[2]);

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Select <?php echo $_POST["type"]; ?></h1>

<form action="<?php echo $site_url; ?>/admin/update-news.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<input type="hidden" name="type" value="<?php echo $_POST["type"]; ?>" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} else {

$result = $sql_command->select($database_news,"type","GROUP BY type ORDER BY type DESC");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[0])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update News</h1>

<form action="<?php echo $site_url; ?>/admin/update-news.php" method="POST">
<input type="hidden" name="action" value="View" />
<select name="type" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}

?>