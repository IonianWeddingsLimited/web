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

if($_GET["a"] == "add") {

$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE folder='".addslashes($_GET["dosavefolder"])."' and name='".addslashes($_GET["foldername"])."'");
$subfolders_record = $sql_command->result($subfolders_result);

if(!$subfolders_record[0]) {
$sql_command->insert($database_pdf_subfolders,"folder,name","'".addslashes($_GET["dosavefolder"])."','".addslashes($_GET["foldername"])."'");
}


} elseif($_GET["mode"] == "add") {
	

$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE folder='".$image_record[3]."' ORDER BY name");
$subfolders_rows = $sql_command->results($subfolders_result);

foreach($subfolders_rows as $subfolders_record) {
if($subfolders_record[0] == $image_record[7]) { $add_selected = "selected=\"selected\""; } else { $add_selected = ""; }
$list1 .= "<option value=\"".$subfolders_record[0]."\" $add_selected >- ".stripslashes($subfolders_record[2])."</option>\n";
}

?>
<script type="text/javascript">
function update_information() {
$('#updated_message').html();

var dosavefolder = $("#dosavefolder").val();
var foldername = $("#foldername").val();

if(!foldername) {
$('#updated_message').html('Missing folder name');	
} else {
$.get('<?php echo $site_url; ?>/oos/mugshot_folder-module.php?a=add&dosavefolder=' + encodeURIComponent(dosavefolder) + '&foldername=' + encodeURIComponent(foldername), function(data){
$('#updated_message').html('The folder ' + foldername + ' has now been added');
});
}
}

</script>
<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Client Photo Management</strong></div>

<div style=" height:464px;">
<div style="float:left; width:680px; padding:10px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
<div style="float:left;"><span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">1x1</span> | 
<span onclick="open_imagemodule('2x1','1');" style="color:#c08827; cursor:pointer;">2x1</span> | 
<span onclick="open_imagemodule('3x1','1');" style="color:#c08827; cursor:pointer;">3x1</span> | 
<span onclick="open_imagemodule('4x1','1');" style="color:#c08827; cursor:pointer;">4x1</span></div>
<div style="float:right;"><span onclick="open_awaitingcrop();" style="color:#c08827; cursor:pointer;">Awaiting Cropping</span> | <span onclick="open_originals();" style="color:#c08827; cursor:pointer;">Original Images</span> | <span onclick="open_search();" style="color:#c08827; cursor:pointer;">Search Images</span></div>
<div class="clear"></div>
<p><strong>Add Folder</strong></p>


<div style="overflow:auto; width:480px; height:370px;">

<div id="updated_message" style="font-weight:bold; color:#F00;"></div>
<div style="float:left; width:150px; padding:5px; margin-top:10px;"><strong>Parent Folder</strong></div>
<div style="float:left; padding:5px; margin-top:10px;"><select name="dosavefolder" id="dosavefolder" style="width:200px; padding:5px; border:1px solid #ccc;">
<option value="1x1">1x1</option>
<option value="2x1">2x1</option>
<option value="3x1">3x1</option>
<option value="4x1">4x1</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; padding:5px; "><strong>Folder Name</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="foldername"  id="foldername" style="width:200px; padding:5px; border:1px solid #ccc;"/></div>
<div style="clear:left;"></div>

<p><input type="button" name="a" value="Add Folder" onclick="update_information();" /></p>

</div>
     </div><div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer;">Update Folder</span>
<iframe src="mugshot_file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>   
</div>
<div class="clear"></div>
</div>


<?
} elseif($_GET["mode"] == "edit") {

?>
<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Client Photo Management</strong></div>

<div style=" height:464px;">
<div style="float:left; width:680px; padding:10px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
<div style="float:left;"><span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">1x1</span> | 
<span onclick="open_imagemodule('2x1','1');" style="color:#c08827; cursor:pointer;">2x1</span> | 
<span onclick="open_imagemodule('3x1','1');" style="color:#c08827; cursor:pointer;">3x1</span> | 
<span onclick="open_imagemodule('4x1','1');" style="color:#c08827; cursor:pointer;">4x1</span></div>
<div style="float:right;"><span onclick="open_awaitingcrop();" style="color:#c08827; cursor:pointer;">Awaiting Cropping</span> | <span onclick="open_originals();" style="color:#c08827; cursor:pointer;">Original Images</span> | <span onclick="open_search();" style="color:#c08827; cursor:pointer;">Search Images</span></div>
<div class="clear"></div>
<p><strong>Update Folder</strong></p>

<div style="overflow:auto; width:660px; height:570px;"><iframe src="mugshot_folder-module.php" style="width:660px; height:570px; border:0px;" width="660" height="570" border="0"  frameBorder="0" ></iframe>
</div>
</div><div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer;">Update Folder</span>
<iframe src="mugshot_file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>   
</div>
<div class="clear"></div>
</div>
<?

} elseif($_GET["mode"] == "Save Edit") {
	
	



$image_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($_GET["fileid"])."' and require_crop='No'");
$image_record = $sql_command->result($image_result);

$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE id='".addslashes($_GET["id"])."' ORDER BY name");
$subfolders_rows = $sql_command->results($subfolders_result);

foreach($subfolders_rows as $subfolders_record) {
if($subfolders_record[0] == $image_record[7]) { $add_selected = "selected=\"selected\""; } else { $add_selected = ""; }
$list1 .= "<option value=\"".$subfolders_record[0]."\" $add_selected >- ".stripslashes($subfolders_record[2])."</option>\n";
}

?>
<script type="text/javascript">
function update_information() {
$('#updated_message').html();

var dosavefolder = $("#dosavefolder").val();
var foldername = $("#foldername").val();


if(!foldername) {
$('#updated_message').html('Missing folder name');	
} else {
$.get('<?php echo $site_url; ?>/oos/mugshot_folder-module.php?a=ajax&do=add&dosavefolder=' + encodeURIComponent(dosavefolder) + '&foldername=' + encodeURIComponent(foldername), function(data){
$('#updated_message').html('The folder ' + foldername + ' has now been added');
});
}
}
</script>
<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Client Photo Management</strong></div>

<div style=" height:464px;">
<div style="float:left; width:680px; padding:10px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
<div style="float:left;"><span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">1x1</span> | 
<span onclick="open_imagemodule('2x1','1');" style="color:#c08827; cursor:pointer;">2x1</span> | 
<span onclick="open_imagemodule('3x1','1');" style="color:#c08827; cursor:pointer;">3x1</span> | 
<span onclick="open_imagemodule('4x1','1');" style="color:#c08827; cursor:pointer;">4x1</span></div>
<div style="float:right;"><span onclick="open_awaitingcrop();" style="color:#c08827; cursor:pointer;">Awaiting Cropping</span> | <span onclick="open_originals();" style="color:#c08827; cursor:pointer;">Original Images</span> | <span onclick="open_search();" style="color:#c08827; cursor:pointer;">Search Images</span></div>
<div class="clear"></div>
<p><strong>Add Folder</strong></p>


<div style="overflow:auto; width:480px; height:570px;">

<div id="updated_message" style="font-weight:bold; color:#F00;"></div>
<div style="float:left; width:150px; padding:5px; margin-top:10px;"><strong>Parent Folder</strong></div>
<div style="float:left; padding:5px; margin-top:10px;"><select name="dosavefolder" id="dosavefolder" style="width:200px; padding:5px; border:1px solid #ccc;">
<option value="1x1">1x1</option>
<option value="2x1">2x1</option>
<option value="3x1">3x1</option>
<option value="4x1">4x1</option>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; padding:5px; "><strong>Folder Name</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="foldername"  id="foldername" style="width:200px; padding:5px; border:1px solid #ccc;"/></div>
<div style="clear:left;"></div>

<p><input type="button" name="a" value="Add Folder" onclick="update_information();" /></p>

</div>
     </div><div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer;">Update Folder</span>
<iframe src="mugshot_file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>   
</div>
<div class="clear"></div>
</div>


<?
} elseif($_POST["action"] == "View Folder") {
	
$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE id='".addslashes($_POST["id"])."'");
$subfolders_record = $sql_command->result($subfolders_result);



?>
<style type="text/css">
form { padding:0; margin:0; }
</style>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:600px;">

<form method="post" action="mugshot_folder-module.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />
<div style="float:left; width:150px; padding:5px; "><strong>Parent Folder</strong></div>
<div style="float:left; padding:5px;"><?php echo $subfolders_record[1]; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; padding:5px; "><strong>Folder Name</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="foldername" value="<?php echo stripslashes($subfolders_record[2]); ?>" style="width:200px; padding:5px; border:1px solid #ccc;"/></div>
<div style="clear:left;"></div>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update Folder"/></div>
</form>
<form method="post" action="mugshot_folder-module.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="action" value="Delete Folder"/></div>
<div style="clear:left"></div>
<p style="font-size:11px;">If you delete this folder all images will be moved to the parent folder</p>
</form>
</div>
<?
} elseif($_POST["action"] == "Update Folder") {
	
if(!$_POST["foldername"]) {
?>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:600px;">

<h3>Oops</h3>
<p>Missing Folder name</p>
<p><a href="mugshot_folder-module.php">Back</a></p></div>
<?php
} else {
$sql_command->update($database_pdf_subfolders,"name='".addslashes($_POST["foldername"])."'","id='".addslashes($_POST["id"])."'");
?>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:180px; width:600px;">

<h3>Updated</h3>
<p>The folder name has now been updated.</p></div>
<?php
}

} elseif($_POST["action"] == "Delete Folder") {
	

$sql_command->delete($database_pdf_subfolders,"id='".addslashes($_POST["id"])."'");
$sql_command->update($database_image_module,"subfolder=''","subfolder='".addslashes($_POST["id"])."'");
?>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px; width:600px;">

<h3>Updated</h3>
<p>The folder name has now been deleted.</p></div>
<?php



} else {


$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","ORDER BY folder,name");
$subfolders_rows = $sql_command->results($subfolders_result);

foreach($subfolders_rows as $subfolders_record) {

if($subfolders_record[1] == "1x1") {
$list1 .= "<option value=\"".$subfolders_record[0]."\">1x1) ".stripslashes($subfolders_record[2])."</option>\n";
} elseif($subfolders_record[1] == "2x1") {
$list2 .= "<option value=\"".$subfolders_record[0]."\">1x1) ".stripslashes($subfolders_record[2])."</option>\n";
} elseif($subfolders_record[1] == "3x1") {
$list3 .= "<option value=\"".$subfolders_record[0]."\">1x1) ".stripslashes($subfolders_record[2])."</option>\n";
} elseif($subfolders_record[1] == "4x1") {
$list4 .= "<option value=\"".$subfolders_record[0]."\">1x1) ".stripslashes($subfolders_record[2])."</option>\n";
} 
}


?>
<div style="text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;  width:600px;">
<!--
<form method="post" action="mugshot_folder-module.php" enctype="multipart/form-data">
<div style="float:left; width:150px; padding:5px; margin-top:10px;"><strong>Folder</strong></div>
<div style="float:left; padding:5px; margin-top:10px;"><select name="id" style="width:200px; padding:5px; border:1px solid #ccc;">
<?php //echo $list1; ?>
<?php //echo $list2; ?>
<?php //echo $list3; ?>
<?php //echo $list4; ?>
</select></div>
<div style="clear:left;"></div>

<p><input type="submit" name="action" value="View Folder"/></p>

</form>-->
</div>
<?
}
?>