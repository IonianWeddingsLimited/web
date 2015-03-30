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


$get_template->topHTML();
?>
<h1>Update 5 Star Packages</h1>
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

<form action="<?php echo $site_url; ?>/admin/update-5-star-package.php" method="POST" name="news" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Short Des</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tagline" style="width:500px;" value="<?php echo stripslashes($record[2]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>New Image</b></div>
<div style="float:left; margin:5px;">	<input type="file" name="newsimage"/></div>
<div style="clear:left;"></div>
<textarea name="newspost" style="height:600px; width:100%;" id="the_editor"><?php echo stripslashes($record[3]); ?></textarea>
<?php echo $admin_editor; ?>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update">
</form></div>

<div style="float: left; margin-left:580px; margin-top:10px;">
<form action="<?php echo $site_url; ?>/admin/update-5-star-package.php" method="POST" name="testimonial">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>

</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
if(!$_POST["title"]) { $error .= "Missing Title<br>"; }

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
$get_template->errorHTML("Update 5 Star Packages","Oops!","$error","Link","admin/update-5-star-package.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_news,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"tagline='".addslashes($_POST["tagline"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_news,"content='".addslashes($_POST["newspost"])."'","id='".addslashes($_POST["id"])."'");

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
<h1>5 Star Packages Updated</h1>

<p>The 5 star packages has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_news,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>5 Star Packages Deleted</h1>

<p>The 5 star packages has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$result = $sql_command->select($database_news,"id,title,timestamp","WHERE type='5 Star Package' ORDER BY timestamp DESC");
$row = $sql_command->results($result);

foreach($row as $record) {
	
$dateline = date("d F Y",$record[2]);

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">$dateline - ".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update 5 Star Packages</h1>

<form action="<?php echo $site_url; ?>/admin/update-5-star-package.php" method="POST">
<select name="id" class="inputbox_town" size="30" style="width:700px;"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>