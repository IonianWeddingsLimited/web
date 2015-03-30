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
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_GET["a"] == "delete") {

$check_file = $_GET["id"]."-".$_GET["filename"];


$check_result = $sql_command->select($database_pdf_generator,"id","WHERE image_ref1='".addslashes($check_file)."' or 
image_ref2='".addslashes($check_file)."' or 
image_ref3='".addslashes($check_file)."' or 
image_ref4='".addslashes($check_file)."' or 
image_ref5='".addslashes($check_file)."' or 
image_ref6='".addslashes($check_file)."' or 
image_ref7='".addslashes($check_file)."' or 
image_ref8='".addslashes($check_file)."' or 
image_ref9='".addslashes($check_file)."' or 
image_ref10='".addslashes($check_file)."' or 
image_ref11='".addslashes($check_file)."' or 
image_ref12='".addslashes($check_file)."' or 
image_ref13='".addslashes($check_file)."' or 
image_ref14='".addslashes($check_file)."' or 
image_ref15='".addslashes($check_file)."' or 
image_ref16='".addslashes($check_file)."' or 
image_ref17='".addslashes($check_file)."' or 
image_ref18='".addslashes($check_file)."' or 
image_ref19='".addslashes($check_file)."' or 
image_ref20='".addslashes($check_file)."' or 
image_ref21='".addslashes($check_file)."' or 
image_ref22='".addslashes($check_file)."' or 
image_ref23='".addslashes($check_file)."' or 
image_ref24='".addslashes($check_file)."'");
$check_record = $sql_command->result($check_result);

if($check_record[0]) {
?>
This image is been used in a PDF, cannot delete it
<?
} else {
$sql_command->delete($database_image_module,"id='".addslashes($_GET["id"])."'");
unlink($base_directory."/images/imageuploads/".$_GET["folder"]."/".$_GET["filename"]);
?>
<script type="text/javascript">
$( document ).ready(function() {
open_imagemodule('<?php echo $_GET["folder"]; ?>','1');
});
</script>
<?
}

} elseif($_GET["a"] == "update") {

$sql_command->update($database_image_module,"title='".addslashes($_GET["title"])."'","id='".addslashes($_GET["id"])."'");
$sql_command->update($database_image_module,"description='".addslashes($_GET["description"])."'","id='".addslashes($_GET["id"])."'");
if($_GET["dosavefolder"] != "1x1" and $_GET["dosavefolder"] != "2x1" and $_GET["dosavefolder"] != "3x1" and $_GET["dosavefolder"] != "4x1"){
$sql_command->update($database_image_module,"subfolder='".addslashes($_GET["dosavefolder"])."'","id='".addslashes($_GET["id"])."'");
}
} else {


$image_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($_GET["fileid"])."' and require_crop='No'");
$image_record = $sql_command->result($image_result);

$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE folder='".$image_record[3]."' ORDER BY name");
$subfolders_rows = $sql_command->results($subfolders_result);

foreach($subfolders_rows as $subfolders_record) {
if($subfolders_record[0] == $image_record[7]) { $add_selected = "selected=\"selected\""; } else { $add_selected = ""; }
$list1 .= "<option value=\"".$subfolders_record[0]."\" $add_selected >- ".stripslashes($subfolders_record[2])."</option>\n";
}


list($width, $height, $type, $attr) = getimagesize("../images/imageuploads/".$image_record[3]."/".$image_record[4]);

?>
<script type="text/javascript">
function update_information() {
$('#updated_message').html();

var dosavefolder = $("#dosavefolder").val();
var title = $("#title").val();
var description = $("#description").val();


$("#show_title").html(title);
$("#show_description").html(description);

$.get('<?php echo $site_url; ?>/oos/view-file-module.php?a=update&id=<?php echo $image_record[0]; ?>&title=' + encodeURIComponent(title) + '&description=' + encodeURIComponent(description) + '&dosavefolder=' + encodeURIComponent(dosavefolder), function(data){
$('#updated_message').html('The image title and description have now been updated');
});
}


function delete_information() {
$('#updated_message').html();

var id = $("#id").val();

$.get('<?php echo $site_url; ?>/oos/view-file-module.php?a=delete&id=<?php echo $image_record[0]; ?>&folder=<?php echo $image_record[3]; ?>&filename=<?php echo $image_record[4]; ?>', function(data){
$('#updated_message').html(data);
});
}

</script>
<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Image Management</strong></div>

<div style=" height:664px;">
<div style="float:left; width:680px; padding:10px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
<div style="float:left;"><span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">1x1</span> | 
<span onclick="open_imagemodule('2x1','1');" style="color:#c08827; cursor:pointer;">2x1</span> | 
<span onclick="open_imagemodule('3x1','1');" style="color:#c08827; cursor:pointer;">3x1</span> | 
<span onclick="open_imagemodule('4x1','1');" style="color:#c08827; cursor:pointer;">4x1</span></div>
<div style="float:right;"><span onclick="open_awaitingcrop();" style="color:#c08827; cursor:pointer;">Awaiting Cropping</span> | <span onclick="open_originals();" style="color:#c08827; cursor:pointer;">Original Images</span> | <span onclick="open_search();" style="color:#c08827; cursor:pointer;">Search Images</span></div>
<div class="clear"></div>
<p><strong>Folder <?php echo $_GET["folder"]; ?></strong><br />
Image Size: <?php echo $width; ?> by <?php echo $height; ?></strong></p>


<div style="overflow:auto; width:680px; height:570px;">
<p style="text-align:center;" id="show_title"><strong><?php echo stripslashes($image_record[1]); ?></strong></p>
<p style="text-align:center;"><img src="/images/imageuploads/<?php echo $_GET["folder"]; ?>/<?php echo $image_record[4]; ?>?<?php echo $time; ?>" /></p>
<p id="show_description"><?php echo stripslashes($image_record[2]); ?></p>

<h3>Use Image</h3>
<div id="added_message" style="font-weight:bold; color:#F00;"></div>

<form method="post">
<p><select id="image_ref_selection" name="image_ref_selection">
<option value="Image1">Row 1) Image Reference 1</option>
<option value="Image2">Row 1) Image Reference 2</option>
<option value="Image3">Row 1) Image Reference 3</option>
<option value="Image4">Row 1) Image Reference 4</option>
<option value="Image5">Row 2) Image Reference 1</option>
<option value="Image6">Row 2) Image Reference 2</option>
<option value="Image7">Row 2) Image Reference 3</option>
<option value="Image8">Row 2) Image Reference 4</option>
<option value="Image9">Row 3) Image Reference 1</option>
<option value="Image10">Row 3) Image Reference 2</option>
<option value="Image11">Row 3) Image Reference 3</option>
<option value="Image12">Row 3) Image Reference 4</option>
<option value="Image13">Row 4) Image Reference 1</option>
<option value="Image14">Row 4) Image Reference 2</option>
<option value="Image15">Row 4) Image Reference 3</option>
<option value="Image16">Row 4) Image Reference 4</option>
<option value="Image17">Row 5) Image Reference 1</option>
<option value="Image18">Row 5) Image Reference 2</option>
<option value="Image19">Row 5) Image Reference 3</option>
<option value="Image20">Row 5) Image Reference 4</option>
<option value="Image21">Row 6) Image Reference 1</option>
<option value="Image22">Row 6) Image Reference 2</option>
<option value="Image23">Row 6) Image Reference 3</option>
<option value="Image24">Row 6) Image Reference 4</option>
</select></p>
<p><input type="button" value="Use" onclick="add_image('<?php echo $image_record[0]; ?>-<?php echo $image_record[4]; ?>','<?php echo $image_record[3]; ?>');"/></strong></p>
</form>

<p><hr /></p>
<h3>Update Information</h3>
<div id="updated_message" style="font-weight:bold; color:#F00;"></div>
<div style="float:left; width:100px; padding:5px; margin-top:10px;"><strong>Folder</strong></div>
<div style="float:left; padding:5px; margin-top:10px;"><select name="dosavefolder" id="dosavefolder"  style="width:200px; padding:5px; border:1px solid #ccc;">
<option value="<?php echo $image_record[3]; ?>"><?php echo $image_record[3]; ?></option>
<?php echo $list1; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:100px; padding:5px; "><strong>Title</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="title"  id="title" value="<?php echo stripslashes($image_record[1]); ?>" style="width:200px; padding:5px; border:1px solid #ccc;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:100px; padding:5px;"><strong>Description</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="description" id="description" value="<?php echo stripslashes($image_record[2]); ?>" style="width:300px;padding:5px; border:1px solid #ccc;" /></div>
<div style="clear:left;"></div>
<div style="float:left; margin-top:10px;"><input type="button" name="a" value="Save Info" onclick="update_information();" /></div>
<div style="float:right; margin-top:10px;"><input type="button" name="a" value="Delete Image" onclick="delete_information();" /></div>
<div class="clear"></div>
</div>
     </div><div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer;">Update Folder</span>
<iframe src="file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>   
</div>
<div class="clear"></div>
</div>


<?
}
?>