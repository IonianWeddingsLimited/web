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


if($_POST["action"] == "Add") {
	
if(!$_POST["title"]) { $error .= "Missing Title<br>"; }
if(!$_POST["link"]) { $error .= "Missing Link<br>"; }
if(!$_POST["description"]) { $error .= "Missing Description<br>"; }

$one_newimage_name = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES["image"]["name"]));
$one_image_filename = $_FILES["image"]["tmp_name"];

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
$get_template->errorHTML("Add Feature Package","Oops!","$error","Link","admin/add-feature-package.php");
$get_template->bottomHTML();
$sql_command->close();
}


$values = "'".addslashes($_POST["title"])."',
'".addslashes($_POST["link"])."',
'',
'".addslashes($_POST["description"])."'";

$sql_command->insert($database_feature_packages,"title,the_link,the_image,description",$values);
$maxid = $sql_command->maxid($database_feature_packages,"id"); 



if($one_image_filename) {
$save_image = $maxid ."-".$one_newimage_name;

$save_image = $maxid ."-".$one_newimage_name;
$sql_command->update($database_feature_packages,"the_image='".addslashes($save_image)."'","id='".addslashes($maxid)."'");
move_uploaded_file($one_image_filename, "$base_directory/images/package-feature/$save_image");

$thesource = $site_url2 . "/images/package-feature/";

$image_file_type = ereg_replace("[^A-Za-z0-9\.\-]", "", $save_image);

if(ereg(".gif",$image_file_type)) { 
$stype = ".gif";
} elseif(ereg(".jpeg",$image_file_type)) { 
$stype = ".jpeg";
} elseif(ereg(".jpg",$image_file_type)) { 
$stype = ".jpg";
} elseif(ereg(".png",$image_file_type)) { 
$stype = ".png";
}

$size = getimagesize($thesource.$save_image);

switch($stype) {
case '.gif':
$image = imagecreatefromgif($thesource.$save_image);
break;
case '.jpg':
$image = imagecreatefromjpeg($thesource.$save_image);
break;
case '.jpeg':
$image = imagecreatefromjpeg($thesource.$save_image);
break;
case '.png':
$image = imagecreatefrompng($thesource.$save_image);
break;
}


$thumb_width = 230;
$thumb_height = 150;

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

if($stype == ".gif") { imagegif($thumb,"$base_directory/images/package-feature/".$save_image);  }
if($stype == ".jpg") { imagejpeg($thumb,"$base_directory/images/package-feature/".$save_image);  }
if($stype == ".jpeg") { imagejpeg($thumb,"$base_directory/images/package-feature/".$save_image);  }
if($stype == ".png") { imagepng($thumb,"$base_directory/images/package-feature/".$save_image);  } 

}
	
$get_template->topHTML();
?>
<h1>Feature Package  Added</h1>

<p>The feature package has now been added</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$get_template->topHTML();
?>
<h1>Add Feature Package</h1>

<form action="<?php echo $site_url; ?>/admin/add-feature-package.php" method="POST" name="news" enctype="multipart/form-data">
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Link</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="link" style="width:500px;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image</b></div>
<div style="float:left; margin:5px;">	<input type="file" name="image"/></div>
<div style="float:left; margin:5px;">Required Size 230 by 150 pixels</div>
<div style="clear:left;"></div>
<textarea name="description" style="height:400px; width:100%;" id="the_editor"></textarea>
<?php echo $admin_editor; ?>
<p style="margin-top:10px;"><input type="submit" name="action" value="Add"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>