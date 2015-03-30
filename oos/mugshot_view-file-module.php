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

$_GET['client_id'] = (isset($_GET['client_id'])) ? $_GET['client_id'] : $_SESSION['mugshot_clid'];

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_GET["a"] == "delete") {

unlink($base_directory."/images/imageuploads/mugshot/".$_GET["filename"]);
$sql_command->delete("clients_options","client_option='mugshot' and id='".addslashes($_GET['id'])."'");
}
elseif($_GET["a"] == "remove") {
$images_q = $sql_command->select("clients_options","*","WHERE client_id='".addslashes($_GET['id'])."' AND client_option ='mugshot' and additional='Active'");
$images_r = $sql_command->result($images_q);
$sql_command->update("clients_options","additional='Inactive'","client_option='mugshot' and client_id='".addslashes($images_r[1])."'");
}
elseif($_GET["a"] == "apply") {
	$check_result = $sql_command->select("clients_options","client_id","WHERE client_option = 'mugshot' and id='".addslashes($_GET['id'])."'");
	$check_record = $sql_command->result($check_result);

$sql_command->update("clients_options","additional='Inactive'","client_option='mugshot' and client_id='".addslashes($check_record[0])."'");
$sql_command->update("clients_options","additional='Active'","client_option='mugshot' and id='".addslashes($_GET['id'])."'");


} else {

$image_result = $sql_command->select("clients_options","*","WHERE client_option='mugshot' and client_id='".addslashes($_GET['client_id'])."' and id='".addslashes($_GET["fileid"])."'");
$image_record = $sql_command->result($image_result);

list($width, $height, $type, $attr) = getimagesize($site_url."/images/imageuploads/mugshot/".$image_record[4]);

?>
<script type="text/javascript">
function delete_information(imageid) {
	
$('#image_module_html').html();

$.get('<?php echo $site_url; ?>/oos/mugshot_view-file-module.php?a=delete&id=' + imageid, function(data){
	$('#image_module_html').html(data);
	$.get('<?php echo $site_url; ?>/oos/mugshot_image-module.php?folder=mugshot&client_id=<?php echo $_GET['client_id']; ?>', function(data){
		$('#image_module_html').html(data);																																	
	});
});
}

function add_image(reference,imageid) {
	$.get('<?php echo $site_url; ?>/oos/mugshot_view-file-module.php?a=apply&id=' + imageid, function(data){
	var src = '<?php echo $site_url; ?>/images/imageuploads/mugshot/' + encodeURIComponent(reference);
	$('#image_module_bg').hide();
	$('#image_module_html').html();
	$('#image_module_html').hide();
	$("#mugshot-img").attr("src", src);
});
}
</script>
<div style="position:absolute; top:0; right:0; width:36px; height:36px; z-index:2000; cursor:pointer;" onclick="close_imagemodule();"><img src="/images/close.png" /></div>
<div style="position:relative; width:890px; height:26px; background-color:#666; text-align:left; padding-left:10px; padding-top:10px; color:#FFF;"><strong>Mugshot Management</strong></div>

<div style=" height:664px;">

<div style="overflow:auto; width:680px; height:570px;">
<p style="text-align:center;"><img style="max-width:260px; max-height:440px;" src="/images/imageuploads/mugshot/<?php echo $image_record[3]; ?>?<?php echo $time; ?>" /></p>
<center>
<h3>Update Image</h3>
<div id="added_message" style="font-weight:bold; color:#F00;"></div>

<form method="post">
<p>
<input type="button" value="Back" onclick="parent.open_imagemodule('mugshot','','','<?php echo $_GET['client_id']; ?>');"/>
<input type="button" name="a" value="Delete Image" onclick="parent.delete_information('<?php echo $image_record[0]; ?>');" />
<input type="button" value="Crop Image" onclick="parent.open_awaitingcrop('<?php echo $image_record[0]; ?>');" />
<input type="button" value="Apply Image" onclick="parent.add_image('<?php echo $image_record[3]; ?>','<?php echo $image_record[0]; ?>');"/>
</p>
</form>
</center>
<div class="clear"></div>
</div>
     </div>
</div>


<?
}
?>