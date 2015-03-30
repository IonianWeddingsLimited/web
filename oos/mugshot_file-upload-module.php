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

$_GET['client_id'] = (isset($_GET['client_id'])) ? $_GET['client_id'] : $_SESSION['mugshot_clid'];

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

$_POST["savefolder"] = "mugshot";
		  
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

$min_width = 150;	
 

if($newimage_name) {
$filelocation2 = $base_directory."/images/imageuploads/mugshot/".$generate_filename;
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
<p>The file dimenions are not big enough.</p>
<p>You minimum file dimensions you can have are 220 by 150 pixels</p>
<p><a href="mugshot_file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif(!$newimage_name) {
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>Please select an image to upload.</p>
<p><a href="mugshot_file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif($image_record[0]) {	
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>An image already exists with the filename <strong><?php echo $generate_filename; ?></strong>.</p>
<p><a href="mugshot_file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} elseif(!preg_match('/.+\.(jpeg|jpg|gif|png)/', $newimage_name)) {	
@unlink($filelocation2);
?><style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Oops</h3>
<p>Please upload a JPG / GIF / JPEG or PNG file.</p>
<p><a href="mugshot_file-upload-module.php" style="color:#F00; cursor:pointer;">Back</a></p>
<?
} else {

$filelocation = $base_directory."/images/imageuploads/mugshot/".$generate_filename;
@copy($filelocation2, $filelocation);

$sql_command->insert("clients_options","client_id,client_option,option_value,additional","'".$_POST['client_id']."','mugshot','".addslashes($generate_filename)."','Inactive'");
$maxid = $sql_command->maxid("clients_options","id");

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//parent.open_awaitingcrop('<?php //echo $maxid; ?>');

	$.get('<?php echo $site_url; ?>/oos/mugshot_image-module.php?folder=mugshot&client_id=<?php echo $_POST['client_id']; ?>', function(data){
		$('#image_module_html', window.parent.document).html(data);																																	
	});

});
</script>
<style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:180px;">
<h3>Image Uploaded</h3>
<p>The image has now been uploaded, you can need to crop the image to the correct size.</p>
<p><span onclick="parent.open_awaitingcrop('<?php echo $maxid; ?>');" style="color:#F00; cursor:pointer; text-decoration:underline;">Crop File</span> | <a href="mugshot_file-upload-module.php" style="color:#F00; cursor:pointer;">Upload Another File</a></p>
<?
}
	
} else {


?>
<style type="text/css">
body { background-color: #ccc; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:170px;">
<h3>Upload Image</h3>
<form method="post" action="mugshot_file-upload-module.php" enctype="multipart/form-data">
<p><input type="file" name="upload_image" style="width:170px;" /></p>
<p><input type="submit" name="action" value="Upload"/></p>
<input type="hidden" name="client_id" value="<?php echo $_GET['client_id']; ?>" />
</form>

<h3>Minimum File Size</h3>
<p>200 x 150 pixels</p>
<br />

</div>
<?
}
?>