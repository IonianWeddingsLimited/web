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

if($_POST["action"] == "View PDF Details") {
header("Location: $site_url/oos/pdf-generator-update.php?id=".$_POST["id"]);
exit();
}

if($_GET["id"]) {
$_POST["action"] = "View PDF Details";
$_POST["id"] = $_GET["id"];
}


if($_POST["action"] == "Delete PDF") {

$sql_command->delete($database_pdf_generator,"id='".addslashes($_POST["id"])."'");

$get_template->topHTML();
?>
<h1>PDF Deleted</h1>

<p>The PDF has now been deleted.</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/pdf-generator-update.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Update PDF") {

if(!$_POST["header_title"]) { $error .= "Missing Document Title<br>"; }
if(!$_POST["introduction_title"]) { $error .= "Missing Introduction Title<br>"; }
if(!$_POST["introduction_body"]) { $error .= "Missing Introduction Body<br>"; }
if(!$_POST["body_content"]) { $error .= "Missing Body Content<br>"; }
if(!$_POST["bullet_points"]) { $error .= "Bullet Points<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update PDF","Oops!","$error","Link","oos/pdf-generator-update.php");
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update($database_pdf_generator,"introduction_title='".addslashes($_POST["introduction_title"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"introduction_body='".addslashes($_POST["introduction_body"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref1='".addslashes($_POST["image_ref1"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref2='".addslashes($_POST["image_ref2"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref3='".addslashes($_POST["image_ref3"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref4='".addslashes($_POST["image_ref4"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"body_content='".addslashes($_POST["body_content"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"bullet_list='".addslashes($_POST["bullet_points"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"closing_salutation='".addslashes($_POST["salutation"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"notes='".addslashes($_POST["notes"])."'","id='".addslashes($_POST["id"])."'");
	
	
$sql_command->update($database_pdf_generator,"image_ref5='".addslashes($_POST["image_ref5"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref6='".addslashes($_POST["image_ref6"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref7='".addslashes($_POST["image_ref7"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref8='".addslashes($_POST["image_ref8"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref9='".addslashes($_POST["image_ref9"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref10='".addslashes($_POST["image_ref10"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref11='".addslashes($_POST["image_ref11"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref12='".addslashes($_POST["image_ref12"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref13='".addslashes($_POST["image_ref13"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref14='".addslashes($_POST["image_ref14"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref15='".addslashes($_POST["image_ref15"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref16='".addslashes($_POST["image_ref16"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref17='".addslashes($_POST["image_ref17"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref18='".addslashes($_POST["image_ref18"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref19='".addslashes($_POST["image_ref19"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref20='".addslashes($_POST["image_ref20"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref21='".addslashes($_POST["image_ref21"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref22='".addslashes($_POST["image_ref22"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref23='".addslashes($_POST["image_ref23"])."'","id='".addslashes($_POST["id"])."'");
$sql_command->update($database_pdf_generator,"image_ref24='".addslashes($_POST["image_ref24"])."'","id='".addslashes($_POST["id"])."'");

$sql_command->update($database_pdf_generator,"header_title='".addslashes($_POST["header_title"])."'","id='".addslashes($_POST["id"])."'");


$get_template->topHTML();
?>
<h1>PDF Updated</h1>

<p>To view/download the Generated PDF, <a href="pdf-generator.php?id=<?php echo $_POST["id"]; ?>" target="_blank">click here</a></p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/pdf-generator-update.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "View PDF Details") {

$result = $sql_command->select($database_pdf_generator,"*","WHERE id='".addslashes($_POST["id"])."'");
$record = $sql_command->result($result);

$add_header = "<script type=\"text/javascript\">

function close_imagemodule() {
$('#image_module_bg').hide();
$('#image_module_html').html();
$('#image_module_html').hide();
}

function open_imagemodule(folder,page,subfolder) {

$.get('".$site_url."/oos/image-module.php?folder=' + folder + '&page=' + page + '&subfolder=' + subfolder, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

function view_image(folder,fileid) {

$.get('".$site_url."/oos/view-file-module.php?folder=' + folder + '&fileid=' + fileid, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}
</script>
<script type=\"text/javascript\">
function add_image(reference,size) {

var selected_value =  $(\"#image_ref_selection option:selected\").val();

if(selected_value == \"Image1\") {
$(\"#image_ref1\").val(reference);	
$(\"#image_ref1_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 1) image 1');	
} else if(selected_value == \"Image2\") {
$(\"#image_ref2\").val(reference);	
$(\"#image_ref2_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 2');
} else if(selected_value == \"Image3\") {
$(\"#image_ref3\").val(reference);	
$(\"#image_ref3_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 3');
} else if(selected_value == \"Image4\") {
$(\"#image_ref4\").val(reference);	
$(\"#image_ref4_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 4');
} else if(selected_value == \"Image5\") {
$(\"#image_ref5\").val(reference);	
$(\"#image_ref5_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 1');
} else if(selected_value == \"Image6\") {
$(\"#image_ref6\").val(reference);	
$(\"#image_ref6_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 2');
} else if(selected_value == \"Image7\") {
$(\"#image_ref7\").val(reference);	
$(\"#image_ref7_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 3');
} else if(selected_value == \"Image8\") {
$(\"#image_ref8\").val(reference);	
$(\"#image_ref8_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 2) image 4');
} else if(selected_value == \"Image9\") {
$(\"#image_ref9\").val(reference);	
$(\"#image_ref9_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 1');
} else if(selected_value == \"Image10\") {
$(\"#image_ref10\").val(reference);	
$(\"#image_ref10_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 2');
} else if(selected_value == \"Image11\") {
$(\"#image_ref11\").val(reference);	
$(\"#image_ref11_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 3');
} else if(selected_value == \"Image12\") {
$(\"#image_ref12\").val(reference);	
$(\"#image_ref12_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 3) image 4');
} else if(selected_value == \"Image13\") {
$(\"#image_ref13\").val(reference);	
$(\"#image_ref13_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 1');
} else if(selected_value == \"Image14\") {
$(\"#image_ref14\").val(reference);	
$(\"#image_ref14_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 2');
} else if(selected_value == \"Image15\") {
$(\"#image_ref15\").val(reference);	
$(\"#image_ref15_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 3');
} else if(selected_value == \"Image16\") {
$(\"#image_ref16\").val(reference);	
$(\"#image_ref16_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 4) image 4');
} else if(selected_value == \"Image17\") {
$(\"#image_ref17\").val(reference);	
$(\"#image_ref17_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 1');
} else if(selected_value == \"Image18\") {
$(\"#image_ref18\").val(reference);	
$(\"#image_ref18_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 2');
} else if(selected_value == \"Image19\") {
$(\"#image_ref19\").val(reference);	
$(\"#image_ref19_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 3');
} else if(selected_value == \"Image20\") {
$(\"#image_ref20\").val(reference);	
$(\"#image_ref20_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 5) image 4');
} else if(selected_value == \"Image21\") {
$(\"#image_ref21\").val(reference);	
$(\"#image_ref21_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 1');
} else if(selected_value == \"Image22\") {
$(\"#image_ref22\").val(reference);	
$(\"#image_ref22_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 2');
} else if(selected_value == \"Image23\") {
$(\"#image_ref23\").val(reference);	
$(\"#image_ref23_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 3');
} else if(selected_value == \"Image24\") {
$(\"#image_ref24\").val(reference);	
$(\"#image_ref24_size\").html(size);	
$(\"#added_message\").html('The file has been selected for use as row 6) image 4');
}

}

function open_awaitingcrop(id,page) {

$.get('".$site_url."/oos/view-crop-module.php?id=' + id + '&page=' + page, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}


function open_originals(id,page) {

$.get('".$site_url."/oos/original-module.php?id=' + id + '&page=' + page, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}


</script>

<script type=\"text/javascript\">

function open_search() {

$.get('".$site_url."/oos/search-module.php', function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}

function subfolder(mode,id) {

$.get('".$site_url."/oos/folder-module.php?mode=' + mode + '&id=' + id, function(data){
$('#image_module_bg').show();
$('#image_module_html').html(data);
$('#image_module_html').show();
var container_left = ($(window).width() - 900) / 2;
$('#image_module_html').css(\"top\",100);
$('#image_module_html').css(\"left\",container_left);
});

}
</script>
<style type=\"text/css\">
#image_module_html {
position:absolute;
display:none;
width: 900px;
height:700px;
z-index:1000;
background-color:#dcddde;
display:none;
text-align:left;
}

#image_module_bg {
position:fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index:999;
background-color:#000;
opacity:0.8;
display:none;
}
</style>
<script type=\"text/javascript\">
function showdiv(div) {
$('#show_row1').hide();	
$('#show_row2').hide();	
$('#show_row3').hide();	
$('#show_row4').hide();	
$('#show_row5').hide();	
$('#show_row6').hide();	

$('#' + div).show();
}
</script>";

$body_top = "<div id=\"image_module_bg\"></div><div id=\"image_module_html\"></div>";


$get_template->topHTML();
?>
<h1>PDF Generator</h1>

<p>To view/download the Generated PDF, <a href="pdf-generator.php?id=<?php echo $record[0]; ?>" target="_blank">click here</a></p>

<form action="<?php echo $site_url; ?>/oos/pdf-generator-update.php" method="post" >
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<h3 style="margin-top:10px;">Images</h3>
<p>You can place 4 images across the PDF depending on size selected ( <span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">Open Image Managment</span> )</p>

<h3 onclick="showdiv('show_row1');" style="cursor:pointer;">View Row 1</h3>
<div id="show_row1" style="display:block;">
<p>Please enter [insert_row_1] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref1" id="image_ref1" style="width:250px;" value="<?php echo stripslashes($record[3]); ?>" /></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref1_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref2" id="image_ref2" style="width:250px;" value="<?php echo stripslashes($record[4]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref2_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref3" id="image_ref3" style="width:250px;" value="<?php echo stripslashes($record[5]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref3_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref4" id="image_ref4" style="width:250px;" value="<?php echo stripslashes($record[6]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref4_size"></span></div>
<div style="clear:left;"></div>
</div>

<h3 onclick="showdiv('show_row2');" style="cursor:pointer;">View Row 2</h3>
<div id="show_row2" style="display:none;">
<p>Please enter [insert_row_2] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref5" id="image_ref5" style="width:250px;" value="<?php echo stripslashes($record[11]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref5_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref6" id="image_ref6" style="width:250px;" value="<?php echo stripslashes($record[12]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref6_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref7" id="image_ref7" style="width:250px;" value="<?php echo stripslashes($record[13]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref7_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref8" id="image_ref8" style="width:250px;" value="<?php echo stripslashes($record[14]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref8_size"></span></div>
<div style="clear:left;"></div>
</div>

<h3 onclick="showdiv('show_row3');" style="cursor:pointer;">View Row 3</h3>
<div id="show_row3" style="display:none;">
<p>Please enter [insert_row_3] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref9" id="image_ref9" style="width:250px;" value="<?php echo stripslashes($record[15]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref9_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref10" id="image_ref10" style="width:250px;" value="<?php echo stripslashes($record[16]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref10_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref11" id="image_ref11" style="width:250px;" value="<?php echo stripslashes($record[17]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref11_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref12" id="image_ref12" style="width:250px;" value="<?php echo stripslashes($record[18]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref12_size"></span></div>
<div style="clear:left;"></div>
</div>

<h3 onclick="showdiv('show_row4');" style="cursor:pointer;">View Row 4</h3>
<div  id="show_row4" style="display:none;">
<p>Please enter [insert_row_4] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref13" id="image_ref13" style="width:250px;" value="<?php echo stripslashes($record[19]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref13_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref14" id="image_ref14" style="width:250px;" value="<?php echo stripslashes($record[20]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref14_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref15" id="image_ref15" style="width:250px;" value="<?php echo stripslashes($record[21]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref15_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref16" id="image_ref16" style="width:250px;" value="<?php echo stripslashes($record[22]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref16_size"></span></div>
<div style="clear:left;"></div>
</div>

<h3 onclick="showdiv('show_row5');" style="cursor:pointer;">View Row 5</h3>
<div id="show_row5" style="display:none;">
<p>Please enter [insert_row_5] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref17" id="image_ref17" style="width:250px;" value="<?php echo stripslashes($record[23]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref17_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref18" id="image_ref18" style="width:250px;" value="<?php echo stripslashes($record[24]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref18_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref19" id="image_ref19" style="width:250px;" value="<?php echo stripslashes($record[25]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref19_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref20" id="image_ref20" style="width:250px;" value="<?php echo stripslashes($record[26]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref20_size"></span></div>
<div style="clear:left;"></div>
</div>

<h3 onclick="showdiv('show_row6');" style="cursor:pointer;">View Row 6</h3>
<div id="show_row6" style="display:none;">
<p>Please enter [insert_row_6] on a new line in its own P element in the introduction text to insert this row.</p>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 1</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref21" id="image_ref21" style="width:250px;" value="<?php echo stripslashes($record[27]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref21_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 2</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref22" id="image_ref22" style="width:250px;" value="<?php echo stripslashes($record[28]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref22_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 3</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref23" id="image_ref23" style="width:250px;" value="<?php echo stripslashes($record[29]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref23_size"></span></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Image Ref 4</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="image_ref24" id="image_ref24" style="width:250px;" value="<?php echo stripslashes($record[30]); ?>"/></div>
<div style="float:left; width:160px; margin:5px; margin-left:10px; margin-top:5px;"><span id="image_ref24_size"></span></div>
<div style="clear:left;"></div>
</div>

<p><hr /></p>
<h3>Document Title <span style="color:#000;">(required)</span></h3>
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="header_title" style="width:500px;" value="<?php echo stripslashes($record[31]); ?>"/></div>
<div style="clear:left;"></div>

<h3>Introduction <span style="color:#000;">(required)</span></h3>
<div style="float:left; width:160px; margin:5px;"><b>Title</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="introduction_title" style="width:500px;" value="<?php echo stripslashes($record[1]); ?>"/></div>
<div style="clear:left;"></div>
<textarea name="introduction_body" id="the_editor_min" class="the_editor_min"><?php echo stripslashes($record[2]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
						on :
		{
			instanceReady : function( ev )
			{
				this.dataProcessor.writer.setRules( 'p',
					{
						indent : false,
						breakBeforeOpen : true,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : true
					});
			}
		}
					});
</script>


<h3 style="margin-top:10px;">Body Content <span style="color:#000;">(required)</span></h3>

<textarea name="body_content" id="the_editor_min2" class="the_editor_min2"><?php echo stripslashes($record[7]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min2',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
					});
</script>

<h3 style="margin-top:10px;">Bullet Points <span style="color:#000;">(required)</span></h3>

<p>Each &lt;P&gt; tag will be a bullet point</p>

<textarea name="bullet_points" id="the_editor_min3" class="the_editor_min3"><?php echo stripslashes($record[8]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min3',
					{
						skin : 'kama',
						toolbar : 'Bullet',
						width: 700,
						height: 400,
					});
</script>

<h3 style="margin-top:10px;">Closing Salutation</h3>

<textarea name="salutation" id="the_editor_min4" class="the_editor_min4"><?php echo stripslashes($record[9]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min4',
					{
						skin : 'kama',
						toolbar : 'PDF',
						width: 700,
						height: 200,
					});
</script>

<h3 style="margin-top:10px;">Notes</h3>

<textarea name="notes" id="the_editor_min5" class="the_editor_min5"><?php echo stripslashes($record[10]); ?></textarea><script type="text/javascript">
				CKEDITOR.replace( 'the_editor_min5',
					{
						skin : 'kama',
						toolbar : 'Note',
						width: 700,
						height: 200,
					});
</script>
<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Update PDF"></div>
</form>
<form action="<?php echo $site_url; ?>/oos/pdf-generator-update.php" method="post" >
<input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" />

<div style="float:right; margin-top:10px;"><input type="submit" name="action" value="Delete PDF"></div>
<div class="clear"></div>
</form>


<?
$get_template->bottomHTML();
$sql_command->close();
} else {
	
$result = $sql_command->select($database_pdf_generator,"*","ORDER BY introduction_title");
$row = $sql_command->results($result);

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";	
}

$get_template->topHTML();
?>
<h1>Update PDF</h1>

<?php if($list) { ?>
<form action="<?php echo $site_url; ?>/oos/pdf-generator-update.php" method="POST">
<input type="hidden" name="action" value="View PDF Details" />
<select name="id" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="View PDF Details"></p>
</form>
<?php } else { ?>
<p>Please add a PDF</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>