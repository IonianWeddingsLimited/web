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
	
$result = $sql_command->select($database_navigation,"*","WHERE id='".addslashes($_POST["nav_id"])."'");
$record = $sql_command->result($result);

$gallery_list .= "<option value=\"0\">None</option>";

$gallery_result = $sql_command->select($database_gallery,"id,gallery_name","ORDER BY gallery_name");
$gallery_row = $sql_command->results($gallery_result);

foreach($gallery_row as $gallery_record) {
if($gallery_record[0] == $record[2]) {
$gallery_list .= "<option value=\"".stripslashes($gallery_record[0])."\" selected=\"selected\">".stripslashes($gallery_record[1])."</option>";
} else {
$gallery_list .= "<option value=\"".stripslashes($gallery_record[0])."\">".stripslashes($gallery_record[1])."</option>";
}
}




$feature_result = $sql_command->select($database_feature_packages,"id,title","ORDER BY title");
$feature_row = $sql_command->results($feature_result);

foreach($feature_row as $feature_record) {
if($feature_record[0] == $record[18]) {
$feature_list .= "<option value=\"".stripslashes($feature_record[0])."\" selected=\"selected\">".stripslashes($feature_record[1])."</option>";
} else {
$feature_list .= "<option value=\"".stripslashes($feature_record[0])."\">".stripslashes($feature_record[1])."</option>";
}
}

$display_feature_result = $sql_command->select($database_show_features,"parent_id,feature_id","WHERE parent_id='".addslashes($_POST["nav_id"])."'");
$display_feature_row = $sql_command->results($display_feature_result);

foreach($display_feature_row as $display_feature_record) {
$feature_list = str_replace("value=\"".$display_feature_record[1]."\"","value=\"".$display_feature_record[1]."\" selected=\"selected\"",$feature_list);	
}




//if($record[0] > 18 and $record[0] != 20) { 

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);

foreach($level1_row as $level1_record) {

if($record[1] == $level1_record[0]) { $l1_selected = "selected=\"selected\""; } else { $l1_selected = ""; }

if($level1_record[1] == "Planning advice") {
$nav_list .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\" $l1_selected>/".stripslashes($level1_record[2])."/</option>";
$nav_list2 .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/</option>";
} elseif($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages"  or $level1_record[1] == "Navigation Header" or $level1_record[1] == "Navigation Footer") {

$nav_list .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\" $l1_selected>/".stripslashes($level1_record[2])."/</option>";
$nav_list2 .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/</option>";

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

if($record[1] == $level2_record[0]) { $l2_selected = "selected=\"selected\""; } else { $l2_selected = ""; }

$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;\" $l2_selected>/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/</option>";
$nav_list2 .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/</option>";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($record[1] == $level3_record[0]) { $l3_selected = "selected=\"selected\""; } else { $l3_selected = ""; }

$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\" $l3_selected>/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</option>";
$nav_list2 .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\" >/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</option>";
}
}

}
}


$additional_result = $sql_command->select($database_navigation,"parent_id","WHERE feature_id='".addslashes($_POST["nav_id"])."'");
$additional_row = $sql_command->results($additional_result);

foreach($additional_row as $additional_record) {
$nav_list2 = str_replace("value=\"".$additional_record[0]."\"","value=\"".$additional_record[0]."\" selected=\"selected\"",$nav_list2);		
}


$change_nav_html = "
<p><hr /></p>

<div style=\"margin:5px;\"><b>Add Under</b></div>
<div style=\"margin:5px;\"><select name=\"new_nav_id\" style=\"width:700px;\" size=\"10\"><$nav_list</select></div>
";


//}

$get_template->topHTML();
?>
<h1>Update Page</h1>
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

<form action="<?php echo $site_url; ?>/admin/update-page.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="nav_id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="hidden" name="old_page_name" value="<?php echo stripslashes($record[3]); ?>" />
<input type="hidden" name="old_link" value="<?php echo stripslashes($record[4]); ?>" />

<?php if($record[12]) { ?>
<h3>Current Thumbnail</h3>
<p><img src="<?php echo $site_url; ?>/images/page/feature/<?php echo stripslashes($record[12]); ?>" /></p>
<?php } ?>

<div style="float:left; width:160px; margin:5px;"><b>Meta Title</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_title" style="width:400px;" value="<?php echo stripslashes($record[7]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Keywords</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_key" style="width:400px;" value="<?php echo stripslashes($record[8]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Meta Description</b></div>
<div style="float:left; margin:5px;"><input type="text" name="meta_des" style="width:400px;" value="<?php echo stripslashes($record[9]); ?>"/></div>
<div style="clear:left;"></div>

<?php echo $change_nav_html; 

if($change_nav_html) { ?>
<div style="margin:5px;"><b>Additional Parents</b><br />(Hold Ctrl to select/deselect multiple)</div>
<div style="margin:5px;"><select name="additional_id[]" style="width:700px;" size="10" multiple="multiple"><?php echo $nav_list2; ?></select></div>

<?php } ?>

<p><hr /></p>


<div style="float:left; width:160px; margin:5px;"><b>Page Name</b></div>
<div style="float:left; margin:5px;"><input type="text" name="page_name" style="width:400px;" value="<?php echo stripslashes($record[3]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Hide Page</b></div>
<div style="float:left; margin:5px;"><select name="hide_page">
<option value="No" <?php if($record[15] == "No") { echo "selected=\"selected\""; } ?>>No</option>
<option value="Yes" <?php if($record[15] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>External Link</b></div>
<div style="float:left; margin:5px;"><select name="external_link">
<option value="No" <?php if($record[10] == "No") { echo "selected=\"selected\""; } ?>>No</option>
<option value="Yes" <?php if($record[10] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>External Url</b></div>
<div style="float:left; margin:5px;"><input type="text" name="external_url" style="width:300px;" value="<?php echo stripslashes($record[11]); ?>"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Insert Gallery</b></div>
<div style="float:left; margin:5px;"><select name="gallery_id"><?php echo $gallery_list; ?></select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Insert Feature</b><br />(Hold Ctrl to select/deselect multiple)</div>
<div style="float:left; margin:5px;"><select name="feature_id[]" size="5" multiple="multiple"><?php echo $feature_list; ?></select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Page Thumbnail</b></div>
<div style="float:left; margin:5px;"><input type="file" name="thumbnail" /></div>
<div style="float:left; margin:5px;">Size must be 188 x 122 pixels</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Existing Thumbnail</b></div>
<div style="float:left; margin:5px;"><input type="checkbox" name="delete_old_image" value="Yes" /> Tick to remove existing thumbnail</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Show Thumbnails</b></div>
<div style="float:left; margin:5px;"><select name="show_thumbnail">
<option value="No" <?php if($record[13] == "No") { echo "selected=\"selected\""; } ?>>No</option>
<option value="Yes" <?php if($record[13] == "Yes") { echo "selected=\"selected\""; } ?>>Yes</option>
</select> 
</div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><b>Thumbnail Tagline</b></div>
<div style="float:left; margin:5px;"><textarea name="thumbnail_tag" style="height:200px; width:400px;" id="the_editor_basic"><?php echo stripslashes($record[17]); ?></textarea><?php echo $admin_editor_basic; ?></div>
<div style="clear:left;"></div>

<textarea name="html" style="height:400px; width:100%;" id="the_editor"><?php echo stripslashes($record[5]); ?></textarea>
<?php echo $admin_editor; ?>

<div style="float: left; margin-top:10px;"><input type="submit" name="action" value="Update"></div>
</form>
<?php if($record[0] > 18 and $record[0] != 20) { ?>
<div style="float:left; margin-left:580px; margin-top:10px;">
<form action="<?php echo $site_url; ?>/admin/update-page.php" method="POST">
<input type="hidden" name="nav_id" value="<?php echo stripslashes($record[0]); ?>" />
<input type="submit" name="action" value="Delete" onclick="return deletechecked();"></div>
<?php } ?>
<div style="clear:left;"></div>
<h3>How to insert thumbnails</h3>
<p>Select the position in the content body of where you want the thumbnails to appear, then insert one of the following lines</p>
<p>[INSERT_THUMBNAILS_1] - Will insert 1 thumbnail per line<br />
[INSERT_THUMBNAILS_2] - Will insert 2 thumbnails per line<br />
[INSERT_THUMBNAILS_3] - Will insert 3 thumbnails per line<br />
[INSERT_THUMBNAILS_4] - Will insert 4 thumbnails per line</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update") {
	
$_POST["page_url"] = ereg_replace("[^A-Za-z0-9\-]", "", strtolower($_POST["page_url"]));
	
if(!$_POST["page_name"]) { $error .= "Missing Page Name<br>"; }


$one_newimage_name = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES["thumbnail"]["name"]));
$one_image_filename = $_FILES["thumbnail"]["tmp_name"];

if($one_image_filename) {
if(!ereg(".jpg",$one_newimage_name)) { 
if(!ereg(".gif",$one_newimage_name)) {
if(!ereg(".jpeg",$one_newimage_name)) { 
if(!ereg(".png",$one_newimage_name)) { 
$error .= "Thumbnail Image must be .jpg / .jpeg / .gif or .png<br>"; 
}
}
}
}
}


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Page","Oops!","$error","Link","admin/update-page.php");
$get_template->bottomHTML();
$sql_command->close();
}


if($_POST["page_name"] != $_POST["old_page_name"]) {
$workout_link = strtolower($_POST["page_name"]);
$workout_link = str_replace(" - ", " ", $workout_link);
$workout_link = str_replace("-", " ", $workout_link);
$workout_link = str_replace(" ", "-", $workout_link);
$workout_link = ereg_replace("[^A-Za-z0-9-]", "", $workout_link);
$workout_link = str_replace("--", "-", $workout_link);
$workout_link = str_replace("----", "-", $workout_link);
$workout_link = str_replace("-----", "-", $workout_link);
$workout_link = str_replace("------", "-", $workout_link);

$currentcount = -1;

function checkurl($workout_variable,$parent_id,$count) {
global $sql_command, $database_navigation;
$count++;

if($count == 0) {
$workout_link = $workout_variable; 
} else {
$workout_link = $workout_variable . "-$count"; 
}

$existing_result = $sql_command->select($database_navigation,"id","WHERE parent_id='".addslashes($parent_id)."' and page_link='".addslashes($workout_link)."'");
$existing_row = $sql_command->result($existing_result);

if($existing_row[0]) { 
return checkurl($workout_variable,$parent_id,$count);
} else {
return $workout_link;
}
}

$workout_link = checkurl($workout_link,$_POST["new_nav_id"],$currentcount);

$sql_command->update($database_navigation,"page_link='".addslashes($workout_link)."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"page_name='".addslashes($_POST["page_name"])."'","id='".addslashes($_POST["nav_id"])."'");
} else {
$workout_link = $_POST["old_link"];	
}


if($_POST["delete_old_image"] == "Yes") {
$sql_command->update($database_navigation,"thumbnail_image=''","id='".addslashes($_POST["nav_id"])."'");	
}

$sql_command->update($database_navigation,"meta_title='".addslashes($_POST["meta_title"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"meta_key='".addslashes($_POST["meta_key"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"meta_des='".addslashes($_POST["meta_des"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"html='".addslashes($_POST["html"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"hide_page='".addslashes($_POST["hide_page"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"thumbnail_position='".addslashes($_POST["thumbnail_position"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"show_thumbnails='".addslashes($_POST["show_thumbnail"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"thumbnail_tag='".addslashes($_POST["thumbnail_tag"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"external_link='".addslashes($_POST["external_link"])."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"external_url='".addslashes($_POST["external_url"])."'","id='".addslashes($_POST["nav_id"])."'");





$sql_command->delete($database_navigation,"feature_id='".addslashes($_POST["nav_id"])."'");	



if($_POST["additional_id"]) {
$level3_result = $sql_command->select($database_navigation,"id,parent_id,page_link","WHERE id='".addslashes($_POST["nav_id"])."'");
$level3_record = $sql_command->result($level3_result);

if($level3_record[2]) { $level3_record[2] = $level3_record[2]."/"; }

$level2_result = $sql_command->select($database_navigation,"id,parent_id,page_link","WHERE id='".addslashes($level3_record[1])."'");
$level2_record = $sql_command->result($level2_result);

if($level2_record[2]) { $level2_record[2] = $level2_record[2]."/"; }

$level1_result = $sql_command->select($database_navigation,"id,parent_id,page_link","WHERE id='".addslashes($level2_record[1])."'");
$level1_record = $sql_command->result($level1_result);

if($level1_record[2]) { $level1_record[2] = $level1_record[2]."/"; }

$link = "/".$level1_record[2].$level2_record[2].$level3_record[2].$workout_link;

foreach ($_POST["additional_id"] as $selectedParent) {
echo $_POST["new_nav_id"]. " ". $selectedParent."<br>";
	
if($_POST["new_nav_id"] != $selectedParent) {
$values = "'".addslashes($selectedParent)."',
'',
'".addslashes($_POST["page_name"])."',
'$workout_link',
'',
'',
'',
'',
'',
'Yes',
'$link',
'',
'',
'100',
'".addslashes($_POST["hide_page"])."',
'',
'',
'".addslashes($_POST["nav_id"])."',
'No'";

$cols = "parent_id,gallery_id,page_name,page_link,html,xml_file_name,meta_title,meta_key,meta_des,external_link,external_url,thumbnail_image,show_thumbnails,displayorder,hide_page,thumbnail_position,thumbnail_tag,feature_id,deleted";
$sql_command->insert($database_navigation,$cols,$values);
}
}
}









$sql_command->delete($database_show_features,"parent_id='".addslashes($_POST["nav_id"])."'");	

if($_POST["feature_id"]) {
foreach ($_POST["feature_id"] as $selectedOption) {
$sql_command->insert($database_show_features,"parent_id,feature_id","'".addslashes($_POST["nav_id"])."','".addslashes($selectedOption)."'");	
}
}


if($_POST["gallery_id"] == 0) {
$xml_filename = "";	
} else {
$gallery_result = $sql_command->select($database_gallery,"id,xml_filename","WHERE id='".addslashes($_POST["gallery_id"])."'");
$gallery_record = $sql_command->result($gallery_result);
$xml_filename = stripslashes($gallery_record[1]);	
}

$sql_command->update($database_navigation,"xml_file_name='".addslashes($xml_filename)."'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"gallery_id='".addslashes($_POST["gallery_id"])."'","id='".addslashes($_POST["nav_id"])."'");

if($_POST["new_nav_id"]) {
$sql_command->update($database_navigation,"parent_id='".addslashes($_POST["new_nav_id"])."'","id='".addslashes($_POST["nav_id"])."'");
}


if($one_image_filename) {
$save_image = $_POST["nav_id"] ."-".$one_newimage_name;
$sql_command->update($database_navigation,"thumbnail_image='".addslashes($save_image)."'","id='".addslashes($_POST["nav_id"])."'");

move_uploaded_file($one_image_filename, "$base_directory/images/page/feature/$save_image");

$thesource = $site_url2 . "/images/page/feature/";

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


$thumb_width = 188;
$thumb_height = 122;

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

if($stype == ".gif") { imagegif($thumb,"$base_directory/images/page/feature/".$save_image);  }
if($stype == ".jpg") { imagejpeg($thumb,"$base_directory/images/page/feature/".$save_image);  }
if($stype == ".jpeg") { imagejpeg($thumb,"$base_directory/images/page/feature/".$save_image);  }
if($stype == ".png") { imagepng($thumb,"$base_directory/images/page/feature/".$save_image);  } 


}




$get_template->topHTML();
?>
<h1>Page Updated</h1>

<p>The page has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Delete") {
	
$sql_command->update($database_navigation,"deleted='Yes'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->update($database_navigation,"hide_page='Yes'","id='".addslashes($_POST["nav_id"])."'");
$sql_command->delete($database_navigation,"feature_id='".addslashes($_POST["nav_id"])."'");	

$get_template->topHTML();
?>
<h1>Page Deleted</h1>

<p>The page has now been deleted</p>
<?
$get_template->bottomHTML();
$sql_command->close();	
	
} else {

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' and deleted='No'  and feature_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	

$nav_list .= "<option value=\"".stripslashes($level1_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/</option>";

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/</option>";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</option>";


$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and deleted='No' and feature_id='0' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
$nav_list .= "<option value=\"".stripslashes($level4_record[0])."\" style=\"font-size:11px;\">/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."/</option>";
}
}
}
}



$get_template->topHTML();
?>
<h1>Update Page</h1>

<form action="<?php echo $site_url; ?>/admin/update-page.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="nav_id" class="inputbox_town" size="30" style="width:710px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>