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

$page = $_GET["page"];
if(!$page) { $page = 1; }

if($_GET["folder"] == "2x1") {
$limit = "72";	
} elseif($_GET["folder"] == "3x1") {
$limit = "80";	
} elseif($_GET["folder"] == "4x1") {
$limit = "18";	
} else {
$limit = "84";	
} 

if($_GET["subfolder"] != "undefined") {
$add_where .= " and subfolder='".addslashes($_GET["subfolder"])."'"; 
} else {
$add_where .= " and (subfolder='' or subfolder='0')"; 	
}


$limitbottom=($page-1)*$limit;
$order_by = "LIMIT $limitbottom,$limit";

$image_result = $sql_command->select($database_image_module,"*","WHERE folder='".addslashes($_GET["folder"])."' and require_crop='No' $add_where ORDER BY id $order_by");
$image_row = $sql_command->results($image_result);

$count=0;
foreach($image_row as $image_record) {
$count++;

$html .= "<img src=\"/images/imageuploads/".addslashes($_GET["folder"])."/".$image_record[4]."?".$time."\" style=\"float:left; margin-right:5px; margin-bottom:5px;cursor:pointer;\" height=\"85\" onclick=\"view_image('".$_GET["folder"]."','".$image_record[0]."');\">";
}
if(!$html) { $html = "<p>No images found</p>"; }

$total_images = 0;
$total_images = $sql_command->count_rows($database_image_module,"id","folder='".addslashes($_GET["folder"])."' and require_crop='No' $add_where");


if($total_images > $limit) {
$totalpages = $total_images / $limit;
list($part1,$part2) = explode(".",$totalpages);
if ($part2) { $newpage = $part1 + 1; } else { $newpage = $totalpages; }

$newpage2 = $newpage - 5;

if($page <= 11) {
$start_page = 1;
$end_page = 21;
} elseif($page >= $newpage2) {
$start_page = $newpage - 21;
$end_page = $newpage;
} else {
$start_page = $page -10;
$end_page = $page + 10;
}


for($eachpage=1; $eachpage <= $newpage; $eachpage++) {
if($eachpage >= $start_page and $eachpage <= $end_page) {	
if($eachpage == $page) {
$pagehtml .= "( $eachpage ) ";
} else {
$pagehtml .= "[&nbsp;<span onclick=\"open_imagemodule('".$_GET["folder"]."','".$eachpage."');\" style=\"color:#c08827; cursor:pointer;\">$eachpage</span>&nbsp;] ";
}
}
}
}


$newpage_end = $newpage - 5;
if($page > 5) { $add_start = "<span onclick=\"open_imagemodule('".$_GET["folder"]."','1');\" style=\"color:#c08827; cursor:pointer;\">&lt;&lt;</span> "; }
if($page < $newpage_end) { $add_end = " <span onclick=\"open_imagemodule('".$_GET["folder"]."','".$newpage_end."');\" style=\"color:#c08827; cursor:pointer;\">&gt;&gt;</span>"; }

if($totalpages > 0) {
$showpageinfo = "<p><strong>Page:</strong> $add_start$pagehtml$add_end</p>";
}


$subfolders_result = $sql_command->select($database_pdf_subfolders,"id,folder,name","WHERE folder='".addslashes($_GET["folder"])."' ORDER BY name");
$subfolders_rows = $sql_command->results($subfolders_result);

foreach($subfolders_rows as $subfolders_record) {
if($subfolders_record[0] == $_GET["subfolder"]) { $foldernameshow = stripslashes($subfolders_record[2]); }
$additional_list .= "[&nbsp;<span onclick=\"open_imagemodule('".$_GET["folder"]."','1','".$subfolders_record[0]."');\" style=\"color:#c08827; cursor:pointer;\">".stripslashes($subfolders_record[2])."</span>&nbsp;] ";
}


?>
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
<p><strong>Folder <?php echo $_GET["folder"]; ?></strong> <?php if($foldernameshow) { echo "( ".$foldernameshow." ) "; } ?></p>
<?php if($additional_list) { ?>
<p><strong>SubFolder:</strong> [&nbsp;<span onclick="open_imagemodule('<?php echo $_GET["folder"]; ?>','1');" style="color:#c08827; cursor:pointer;"><?php echo $_GET["folder"]; ?></span>&nbsp;] <?php echo $additional_list; ?></p>
<?php } ?>
<?php echo $showpageinfo; ?>
<div style="overflow:auto; width:680px; height:570px;"><?php echo $html; ?><div class="clear"></div></div>

</div>
<div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer;">Update Folder</span>
<iframe src="file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>  
</div>
<div class="clear"></div>
</div>


<?


?>