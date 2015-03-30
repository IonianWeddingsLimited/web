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
$get_template->errorHTML("Add ".$_POST["news_type"]."","Oops!","$error","Link","admin/add-news.php");
$get_template->bottomHTML();
$sql_command->close();
}

list($day,$month,$year) = explode("-",$_POST["dateinfo"]);

$savedate = mktime(0, 0, 0, $month, $day, $year);

if($_POST["overrule"] != "Yes") {
$_POST["overrule"] = "No";
}


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

if($_POST["gallery_id"] == 0) {
$xml_filename = "";	
} else {
$gallery_result = $sql_command->select($database_gallery,"id,xml_filename","WHERE id='".addslashes($_POST["gallery_id"])."'");
$gallery_record = $sql_command->result($gallery_result);
$xml_filename = stripslashes($gallery_record[1]);	
}

$values = "'".addslashes($_POST["title"])."',
'".addslashes($_POST["tagline"])."',
'".addslashes($_POST["newspost"])."',
'',
'".addslashes($_POST["news_type"])."',
'$savedate',
'".addslashes($_POST["overrule"])."',
'".addslashes($workout_url)."',
'".addslashes($_POST["external_link"])."',
'".addslashes($_POST["gallery_id"])."',
'".addslashes($xml_filename)."'";


$sql_command->insert($database_news,"title,tagline,content,imagename,type,timestamp,overrule,short_url,external_link,gallery_id,xml_file_name",$values);
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
<h1><?php echo $_POST["news_type"]; ?> Added</h1>

<p>The <?php echo $_POST["news_type"]; ?> has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$today_date = date("d-m-Y",$time);

$gallery_list .= "<option value=\"0\">None</option>";

$gallery_result = $sql_command->select($database_gallery,"id,gallery_name","ORDER BY gallery_name");
$gallery_row = $sql_command->results($gallery_result);

foreach($gallery_row as $gallery_record) {
$gallery_list .= "<option value=\"".stripslashes($gallery_record[0])."\">".stripslashes($gallery_record[1])."</option>";
}


$get_template->topHTML();
?>
<h1>Add News</h1>

<form action="<?php echo $site_url; ?>/admin/add-news.php" method="POST" name="news" enctype="multipart/form-data">
<div style="float:left; width:160px; margin:5px;"><b>Date Added</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="dateinfo" value="<?php echo $today_date; ?>"/>
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
<div style="float:left; margin:5px;"><input type="checkbox" name="overrule" value="Yes"/> (5 Star Package and Special Offers only)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Type</b></div>
<div style="float:left; margin:5px;"><select name="news_type">
<option value="News">News</option>
<option value="Press">In the Press</option>
<option value="5 Star Package">5 Star Package</option>
<option value="Special Offer">Special Offer</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>External Link</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="external_link" style="width:300px;" value="http://"/> (Will override collapsible list option)</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Short Des</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="tagline" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Insert Gallery</b></div>
<div style="float:left; margin:5px;"><select name="gallery_id"><?php echo $gallery_list; ?></select></div>
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