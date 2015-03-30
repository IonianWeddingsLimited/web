<?php

require ("../../_includes/settings.php");
require ("../../_includes/function.templates.php");
include ("../../_includes/function.database.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


//$get_template = new main_template();
//include("../run_login.php");



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


if (!empty($_FILES)) {
	

	$tempFile = $_FILES['Filedata']['tmp_name'];
	$_FILES['Filedata']['name'] = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES['Filedata']['name']));
	
	$root = "/home/ionianwe/public_html";
	
	if($_REQUEST['folder'] == "/images/gallery/pending") {
	$targetPath = $root . $_REQUEST['folder'] . '/';
	$targetPath2 = $root . $_REQUEST['folder'] . '_thumbnail/';
	$thesource = $root . $_REQUEST['folder'] . "/";
	$thesource2 = $root . $_REQUEST['folder'] . '_thumbnail/';
	$update_xml = "No";
	
	} else {
	list($folder_location,$gallery_id,$gallery_xml,$gallery_name) = explode("|",$_REQUEST['folder']);
		
	$targetPath = $root . $folder_location . '/';
	$targetPath2 = $root . $folder_location . '/thumbnail/';
	$thesource = $root . $folder_location . "/";
	$thesource2 = $root . $folder_location . '/thumbnail/';
	$update_xml = "Yes";
	$_FILES['Filedata']['name'] = $gallery_id . "-" . $_FILES['Filedata']['name'];
	}
	
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	$targetFile2 =  str_replace('//','/',$targetPath2) . $_FILES['Filedata']['name'];
	$target_name = strtolower($_FILES['Filedata']['name']);

	if($gallery_name == "Home Page") {

@move_uploaded_file($tempFile, $targetFile);


$image_file_type = ereg_replace("[^A-Za-z0-9\.\-]", "", $target_name);

if(ereg(".gif",$image_file_type)) { 
$stype = ".gif";
} elseif(ereg(".jpeg",$image_file_type)) { 
$stype = ".jpeg";
} elseif(ereg(".jpg",$image_file_type)) { 
$stype = ".jpg";
} elseif(ereg(".png",$image_file_type)) { 
$stype = ".png";
}

$size = getimagesize($thesource.$target_name);

switch($stype) {
case '.gif':
$image = imagecreatefromgif($thesource.$target_name);
break;
case '.jpg':
$image = imagecreatefromjpeg($thesource.$target_name);
break;
case '.jpeg':
$image = imagecreatefromjpeg($thesource.$target_name);
break;
case '.png':
$image = imagecreatefrompng($thesource.$target_name);
break;
}

$thumb_width = 935;
$thumb_height = 386;

$width = $size[0];
$height = $size[1];

$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;

if($original_aspect >= $thumb_aspect) {
   $new_height = $thumb_height;
   $new_width = $width / ($height / $thumb_height);
} else {
   $new_width = $thumb_width;
   $new_height = $height / ($width / $thumb_width);
}

$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

imagecopyresampled($thumb,
                   $image,
                   0 - ($new_width - $thumb_width) / 2,
                   0 - ($new_height - $thumb_height) / 2,
                   0, 0,
                   $new_width, $new_height,
                   $width, $height);

if($stype == ".gif") { imagegif($thumb,$targetPath.$target_name);  }
if($stype == ".jpg") { imagejpeg($thumb,$targetPath.$target_name);  }
if($stype == ".jpeg") { imagejpeg($thumb,$targetPath.$target_name);  }
if($stype == ".png") { imagepng($thumb,$targetPath.$target_name);  } 

	} else {


@move_uploaded_file($tempFile, $targetFile);

$image_file_type = ereg_replace("[^A-Za-z0-9\.\-]", "", $target_name);

if(ereg(".gif",$image_file_type)) { 
$stype = ".gif";
} elseif(ereg(".jpeg",$image_file_type)) { 
$stype = ".jpeg";
} elseif(ereg(".jpg",$image_file_type)) { 
$stype = ".jpg";
} elseif(ereg(".png",$image_file_type)) { 
$stype = ".png";
}

$size = getimagesize($thesource.$target_name);

switch($stype) {
case '.gif':
$image = imagecreatefromgif($thesource.$target_name);
break;
case '.jpg':
$image = imagecreatefromjpeg($thesource.$target_name);
break;
case '.jpeg':
$image = imagecreatefromjpeg($thesource.$target_name);
break;
case '.png':
$image = imagecreatefrompng($thesource.$target_name);
break;
}

$thumb_width = 516;
$thumb_height = 415;

$width = $size[0];
$height = $size[1];

$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;

if($original_aspect >= $thumb_aspect) {
   $new_height = $thumb_height;
   $new_width = $width / ($height / $thumb_height);
} else {
   $new_width = $thumb_width;
   $new_height = $height / ($width / $thumb_width);
}

$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

imagecopyresampled($thumb,
                   $image,
                   0 - ($new_width - $thumb_width) / 2,
                   0 - ($new_height - $thumb_height) / 2,
                   0, 0,
                   $new_width, $new_height,
                   $width, $height);

if($stype == ".gif") { imagegif($thumb,$targetPath.$target_name);  }
if($stype == ".jpg") { imagejpeg($thumb,$targetPath.$target_name);  }
if($stype == ".jpeg") { imagejpeg($thumb,$targetPath.$target_name);  }
if($stype == ".png") { imagepng($thumb,$targetPath.$target_name);  }
 

	}
	
	echo str_replace($root,'',$targetFile);
	

	$image2 = new Resize_Image();
	$image2->load($targetPath.$target_name);
	$image2->resizeToWidth("35");
	$image2->save($targetFile2);

if($update_xml == "No") {
$sql_command->insert($database_pendingimages,"imagename","'".addslashes($_FILES['Filedata']['name'])."'");
} else {
$sql_command->insert($database_gallery_pics,"gallery_id,imagename,timestamp,displayorder,title,description,link","'".addslashes($gallery_id)."','".addslashes($_FILES['Filedata']['name'])."','$time','999','','',''");	






$xml_text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<tn2>
	<gallery>
		<title>Fixed Dimensions</title>
		<description>Some Description</description>
		<file_root>$site_url/images/gallery/</file_root>
		<images>\n";

$result = $sql_command->select($database_gallery_pics,"id,imagename,title,description","WHERE gallery_id='".addslashes($gallery_id)."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {
$xml_text .= "<image>
				<title>".stripslashes($record[2])."</title>
				<description>".stripslashes($record[3])."</description>
				<image_src>/".stripslashes($record[1])."</image_src>
				<thumb_src>/thumbnail/".stripslashes($record[1])."</thumb_src>
			</image>\n";		
}

$xml_text .= "</images>
	</gallery>
</tn2>
\n";	

$fh = fopen($base_directory."/xml/".$gallery_xml, 'w+') or die("can't open file");
fwrite($fh, $xml_text);
fclose($fh);

}
}
?>