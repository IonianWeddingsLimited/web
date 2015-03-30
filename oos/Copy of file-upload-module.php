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
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=90, $permissions=null) {
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

if($_POST["action"] == "Upload") {

$newimage_name = ereg_replace("[^A-Za-z0-9\.-]", "", strtolower($_FILES["upload_image"]["name"]));
$image_filename = $_FILES["upload_image"]["tmp_name"];

if($_POST["savefolder"] != "1x1" and $_POST["savefolder"] != "2x1" and $_POST["savefolder"] != "3x1" and $_POST["savefolder"] != "4x1") {

$folder_result = $sql_command->select($database_pdf_subfolders,"id,folder","WHERE id='".addslashes($_POST["savefolder"])."'");
$folder_record = $sql_command->result($folder_result);

$_POST["savefolder"] = $folder_record[1];
}
		  
$random = mt_rand(100000,999999);   
$generate_filename = strtolower(trim($newimage_name));
$generate_filename = str_replace(" - ", " ", $generate_filename);
$generate_filename = str_replace("-", " ", $generate_filename);
$generate_filename = str_replace(" ", "-", $generate_filename);
$generate_filename = ereg_replace("[^A-Za-z0-9\.-]", "", $generate_filename);
$generate_filename = str_replace("--", "-", $generate_filename);
$generate_filename = str_replace("----", "-", $generate_filename);
$generate_filename = str_replace("-----", "-", $generate_filename);
$generate_filename = str_replace("------", "-", $generate_filename);
$generate_filename = $random."-".$generate_filename;

$image_result = $sql_command->select($database_image_module,"id","WHERE folder='".addslashes($_POST["savefolder"])."' and filename='".addslashes($generate_filename)."'");
$image_record = $sql_command->result($image_result);

if($_POST["savefolder"] == "2x1") {
$min_width = "406";	
} elseif($_POST["savefolder"] == "3x1") {
$min_width = "267";	
} elseif($_POST["savefolder"] == "4x1") {
$min_width = "822";	
} else {
$min_width = "198";	
} 

if($newimage_name) {
$filelocation2 = $base_directory."/images/imageuploads/originals/".$generate_filename;
@move_uploaded_file($image_filename, $filelocation2);

list($orig_width, $orig_height) = getimagesize($filelocation2);

if($orig_width > 840) {
	$image = new Resize_Image();
	$image->load($filelocation2);
	$image->resizeToWidth(840);
	$image->save($filelocation2);  
}
	
list($orig_width, $orig_height) = getimagesize($filelocation2);
}

if($newimage_name and ($orig_width < $min_width or $orig_height < 185)) {
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>The file dimenions are not big enough for this folder.</p>
<p>You minimum file dimensions you can have for the folder <?php echo $_POST["savefolder"]; ?> is <?php echo $min_width; ?> by 185 pixels</p>
<p><a href="file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif(!$newimage_name) {
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>Please select an image to upload.</p>
<p><a href="file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif($image_record[0]) {	
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>An image already exists with the filename <strong><?php echo $generate_filename; ?></strong>.</p>
<p><a href="file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif(!preg_match('/.+\.(jpeg|jpg|gif|png)/', $newimage_name)) {	
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>Please upload a JPG / GIF / JPEG or PNG file.</p>
<p><a href="file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} else {

$filelocation = $base_directory."/images/imageuploads/".$_POST["savefolder"]."/".$generate_filename;
@copy($filelocation2, $filelocation);

$sql_command->insert($database_image_module,"title,description,folder,filename,timestamp,require_crop,subfolder,original","'','','".addslashes($_POST["savefolder"])."','".addslashes($generate_filename)."','".$time."','Yes','".addslashes($folder_record[0])."','Yes'");
$sql_command->insert($database_image_module,"title,description,folder,filename,timestamp,require_crop,subfolder,original","'','','".addslashes($_POST["savefolder"])."','".addslashes($generate_filename)."','".$time."','Yes','".addslashes($folder_record[0])."','No'");
$maxid = $sql_command->maxid($database_image_module,"id");

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript">
function opennow() {
parent.open_awaitingcrop('<?php echo $maxid; ?>');
}
$(document).ready(function() {
setTimeout(opennow, 1000);
});
</script>
<style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:180px;">
<h3>Image Uploaded</h3>
<p>The image has now been uploaded to the <strong>Awaiting Cropping page</strong>, you will need to crop the image to the correct size.</p>
<p><span onclick="parent.open_awaitingcrop('<?php echo $maxid; ?>');" style="color:#F00; cursor:pointer; text-decoration:underline;">Crop File</span> | <a href="file-upload-module.php" style="color:#F00; cursor:pointer;">Upload Another File</a></p>
<?
}
	
} else {


?>
<style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Upload Image</h3>
<form method="post" action="file-upload-module.php" enctype="multipart/form-data">
<p><input type="file" name="upload_image" style="width:170px;" /></p>
<p><input type="submit" name="action" value="Upload"/></p>

</form>

<h3>Minimum File Sizes</h3>
<p><strong>1x1</strong> - 198x185 pixels</p>
<p><strong>2x1</strong> - 406x185 pixels</p>
<p><strong>3x1</strong> - 267x185 pixels</p>
<p><strong>4x1</strong> - 822x185 pixels</p>

</div>
<?
}
?>