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



if($_POST["action"] == "Continue") {
	
$result = $sql_command->select($database_feature_packages,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$dateline = date("d-m-Y",$record[6]);

$get_template->topHTML();
?>
<h1>Update Feature Package</h1>
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

<?php if($record[3]) { ?>
<h3>Current Thumbnail</h3>
<p><img src="<?php echo $site_url; ?>/images/package-feature/<?php echo stripslashes($record[3]); ?>" /></p>
<?php } ?>

<form action="<?php echo $site_url; ?>/admin/update-feature-package.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="title" style="width:500px;" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Link</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="link" style="width:500px;" value="<?php echo stripslashes($record[2]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image</b></div>
<div style="float:left; margin:5px;">	<input type="file" name="image"/></div>
<div style="float:left; margin:5px;">Required Size 230 by 150 pixels</div>
<div style="clear:left;"></div>
<textarea name="description" style="height:400px; width:100%;" id="the_editor"><?php echo stripslashes($record[4]); ?></textarea>
<?php echo $admin_editor; ?>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update">
</form></div>

<div style="float: left; margin-left:580px; margin-top:10px;">
<form action="<?php echo $site_url; ?>/admin/update-feature-package.php" method="POST">
<input type="hidden" name="id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<div style="clear:left;"></div>

</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
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
$get_template->errorHTML("Update Feature Package","Oops!","$error","Link","admin/update-feature-package.php");
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_feature_packages,"title='".addslashes($_POST["title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_feature_packages,"the_link='".addslashes($_POST["link"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_feature_packages,"description='".addslashes($_POST["description"])."'","id='".addslashes($_POST["id"])."'");



if($one_image_filename) {
$save_image = $_POST["id"] ."-".$one_newimage_name;
$sql_command->update($database_feature_packages,"the_image='".addslashes($save_image)."'","id='".addslashes($_POST["id"])."'");
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
<h1>Feature Package Updated</h1>

<p>The feature package has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->delete($database_feature_packages,"id='".addslashes($_POST["id"])."'");
	
$get_template->topHTML();
?>
<h1>Feature Package Deleted</h1>

<p>The feature package has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$result = $sql_command->select($database_feature_packages,"id,title","ORDER BY title");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Feature Package</h1>

<form action="<?php echo $site_url; ?>/admin/update-feature-package.php" method="POST">
<select name="id" class="inputbox_town" size="30" style="width:700px;"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>