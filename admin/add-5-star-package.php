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

if($_POST["action"] == "Add") {
	
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
$get_template->errorHTML("Add 5 Star Package","Oops!","$error","Link","admin/add-5-star-package.php");
$get_template->bottomHTML();
$sql_command->close();
}


$values = "'".addslashes($_POST["title"])."',
'".addslashes($_POST["tagline"])."',
'".addslashes($_POST["newspost"])."',
'',
'5 Star Package',
'$time'";

$sql_command->insert($database_news,"title,tagline,content,imagename,type,timestamp",$values);
$maxid = $sql_command->maxid($database_news,"id"); 


if($one_image_filename) {
$save_image = $maxid ."-".$one_newimage_name;

$image = new Resize_Image();
$image->load($one_image_filename);
$image->resizeToWidth("500");
$image->save($base_directory."/images/page/news/".$save_image);
	
$sql_command->update($database_news,"imagename='".addslashes($save_image)."'","id='".addslashes($maxid)."'");
}
	
$get_template->topHTML();
?>
<h1>5 Star Package Added</h1>

<p>The 5 star package has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	



$get_template->topHTML();
?>
<h1>Add 5 Star Package</h1>

<form action="<?php echo $site_url; ?>/admin/add-5-star-package.php" method="POST" enctype="multipart/form-data">
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Short Des</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tagline" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image</b></div>
<div style="float:left; margin:5px;">	<input type="file" name="newsimage"/></div>
<div style="clear:left;"></div>
<textarea name="newspost" style="height:400px; width:100%;" id="the_editor"></textarea>
<?php echo $admin_editor; ?>
<p style="margin-top:10px;"><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>