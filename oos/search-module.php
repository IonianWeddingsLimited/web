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

if($_GET["a"] == "view") {
	
$image_result = $sql_command->select($database_image_module,"*","WHERE id='".addslashes($_GET["id"])."' and require_crop='No'");
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

</script>
<hr />
<span onclick="search_db('<?php echo $_GET["term"]; ?>','<?php echo $_GET["type"]; ?>','<?php echo $_GET["page"]; ?>');" style="color:#F00; cursor:pointer;">&lt;&lt; Return to search page</span> 
<hr />
<p style="text-align:center;" id="show_title"><strong><?php echo stripslashes($image_record[1]); ?></strong></p>
<p style="text-align:center;"><img src="/images/imageuploads/<?php echo $image_record[3]; ?>/<?php echo $image_record[4]; ?>?<?php echo $time; ?>" width="<?php echo $width; ?>"/></p>
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
<div style="float:left; width:100px; padding:5px;"><strong>Title</strong></div>
<div style="float:left; padding:5px; "><input type="text" name="title"  id="title" value="<?php echo stripslashes($image_record[1]); ?>" style="width:200px; padding:5px; border:1px solid #ccc;"/></div>
<div style="clear:left;"></div>
<div style="float:left; width:100px; padding:5px;"><strong>Description</strong></div>
<div style="float:left; padding:5px;"><input type="text" name="description" id="description" value="<?php echo stripslashes($image_record[2]); ?>" style="width:300px;padding:5px; border:1px solid #ccc;" /></div>
<div style="clear:left;"></div>
<p><input type="button" name="a" value="Save Info" onclick="update_information();" /></p>
<?

} elseif($_GET["a"] == "search") {
	
$page = $_GET["page"];
if(!$page) { $page = 1; }


$limit = 50;
$limitbottom=($page-1)*$limit;
$order_by = "LIMIT $limitbottom,$limit";

if($_GET["type"] == "title") {
$searchin = "title like '%".addslashes($_GET["term"])."%' and ";
} elseif($_GET["type"] == "description") {
$searchin = "description like '%".addslashes($_GET["term"])."%' and ";
} elseif($_GET["type"] == "filename") {
$searchin = "filename like '%".addslashes($_GET["term"])."%' and ";
}





$image_result = $sql_command->select($database_image_module,"*","WHERE $searchin  require_crop='No' ORDER BY id $order_by");
$image_row = $sql_command->results($image_result);

$count=0;
foreach($image_row as $image_record) {
$count++;

$html .= "<img src=\"/images/imageuploads/".addslashes($image_record[3])."/".$image_record[4]."?".$time."\" style=\"float:left; margin-right:5px; margin-bottom:5px;cursor:pointer;\" height=\"85\" onclick=\"search_view('".$image_record[0]."','".$_GET["term"]."','".$_GET["type"]."','".$_GET["page"]."');\">";
}
if(!$html) { $html = "<p>No images found</p>"; }

$total_images = 0;
$total_images = $sql_command->count_rows($database_image_module,"id"," $searchin  require_crop='No'");


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
$pagehtml .= "[&nbsp;<span onclick=\"search_db('".$_GET["term"]."','".$_GET["type"]."','".$eachpage."');\" style=\"color:#c08827; cursor:pointer;\">$eachpage</span>&nbsp;] ";
}
}
}
}


$newpage_end = $newpage - 5;
if($page > 5) { $add_start = "<span onclick=\"search_db('".$_GET["term"]."','".$_GET["type"]."','1');\" style=\"color:#c08827; cursor:pointer;\">&lt;&lt;</span> "; }
if($page < $newpage_end) { $add_end = " <span onclick=\"search_db('".$_GET["term"]."','".$_GET["type"]."','".$newpage_end."');\" style=\"color:#c08827; cursor:pointer;\">&gt;&gt;</span>"; }

if($totalpages > 0) {
$showpageinfo = "<p>Page: $add_start$pagehtml$add_end</p>";
}

?>
<?php echo $showpageinfo; ?>
<?php echo $html; ?>
<div class="clear"></div>



<?php } else { ?>


<script type="text/javascript">
function search_db(term,type,page) {

if(!term) { var term = $("#keyword").val();}
if(!type) { var type = $("#searchin").val(); }
if(!page) { var page = 1; }

$('#results').html();
$.get('<?php echo $site_url; ?>/oos/search-module.php?a=search&term=' + encodeURIComponent(term) + '&type=' + encodeURIComponent(type) + '&page=' + encodeURIComponent(page), function(data){
$('#results').html(data);
});
}
function search_view(id,term,type,page) {

$('#results').html();
$.get('<?php echo $site_url; ?>/oos/search-module.php?a=view&id=' + id + '&term=' + encodeURIComponent(term) + '&type=' + encodeURIComponent(type) + '&page=' + encodeURIComponent(page), function(data){
$('#results').html(data);
});
}
</script>

<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Image Management</strong></div>

<div style=" height:464px;">
<div style="float:left; width:680px; padding:10px; text-align:left; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
<div style="float:left;"><span onclick="open_imagemodule('1x1','1');" style="color:#c08827; cursor:pointer;">1x1</span> | 
<span onclick="open_imagemodule('2x1','1');" style="color:#c08827; cursor:pointer;">2x1</span> | 
<span onclick="open_imagemodule('3x1','1');" style="color:#c08827; cursor:pointer;">3x1</span> | 
<span onclick="open_imagemodule('4x1','1');" style="color:#c08827; cursor:pointer;">4x1</span></div>
<div style="float:right;"><span onclick="open_awaitingcrop();" style="color:#c08827; cursor:pointer;">Awaiting Cropping</span> | <span onclick="open_originals();" style="color:#c08827; cursor:pointer;">Original Images</span> | <span onclick="open_search();" style="color:#c08827; cursor:pointer;">Search Images</span></div>
<div class="clear"></div>
<p><strong>Search Images</p>
<div style="overflow:auto; width:680px; height:570px;">

<div style="float:left; width:150px; padding:5px; margin-top:10px;">Keyword</div>
<div style="float:left; padding:5px; margin-top:10px;"><input type="text" name="keyword" id="keyword" /></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; padding:5px;">Search In</div>
<div style="float:left; padding:5px;"><select name="searchin" id="searchin"><option value="title">Title</option><option value="description">Description</option><option value="filename">Filename</option></select></div>
<div style="clear:left;"></div>
<p><input type="button" name="a" value="Search" onclick="search_db();" /></p>
<div id="results"></div>

</div>


     </div><div style="float:left; width:180px; padding:10px; background-color:#ccc; height:640px; font-size:12px;">
<span onclick="subfolder('add');" style="color:#c08827; cursor:pointer; font-weight:normal;">Add Folder</span> | 
<span onclick="subfolder('edit');" style="color:#c08827; cursor:pointer; font-weight:normal;">Update Folder</span>
<iframe src="file-upload-module.php" style="width:180px;height:600px;border:0px; overflow:hidden;" scrolling="no" width="180" height="600"  frameBorder="0" ></iframe>  
</div>
<div class="clear"></div>
</div>

<?php } ?>