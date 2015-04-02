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

if($_GET["a"] == "view") {
$_POST["action"] = "view";
$_POST["gallery_id"] = $_GET["id"];
}

			
if($_POST["action"] == "Update") {

$result = $sql_command->select($database_gallery_pics,"id","WHERE gallery_id='".addslashes($_POST["gallery_id"])."'");
$row = $sql_command->results($result);

foreach($row as $record) {
$idline = "id_".$record[0];
$titleline = "title_".$record[0];
$linkline = "link_".$record[0];
$descriptionline = "description_".$record[0];

$sql_command->update($database_gallery_pics,"title='".addslashes($_POST[$titleline])."'","id='".addslashes($record[0])."'");
$sql_command->update($database_gallery_pics,"description='".addslashes($_POST[$descriptionline])."'","id='".addslashes($record[0])."'");
$sql_command->update($database_gallery_pics,"link='".addslashes($_POST[$linkline])."'","id='".addslashes($record[0])."'");

if($_POST[$idline]) {
$sql_command->update($database_gallery_pics,"displayorder='".addslashes($_POST[$idline])."'","id='".addslashes($record[0])."'");	
} else {
$sql_command->update($database_gallery_pics,"displayorder='999'","id='".addslashes($record[0])."'");	
}
}


$xml_text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<tn2>
	<gallery>
		<title>Fixed Dimensions</title>
		<description>Some Description</description>
		<file_root>$site_url/images/gallery/</file_root>
		<images>\n";

$result = $sql_command->select($database_gallery_pics,"id,imagename,title,description,link","WHERE gallery_id='".addslashes($_POST["gallery_id"])."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {

$record[2] = str_replace("&","&amp;",$record[2]);
$record[2] = str_replace("'","",$record[2]);
$record[2] = str_replace("’","",$record[2]);

$record[3] = str_replace("&","&amp;",$record[3]);
$record[3] = str_replace("'","",$record[3]);
$record[3] = str_replace("’","",$record[3]);

if($record[4] != 0) {
list($theyear,$theid) = explode("_",$record[4]);
$thelink = "<url>".$site_url."/testimonials/".$theyear."/#".$theid."</url>";
$title = stripslashes($record[2]);
} else {
$title = stripslashes($record[2]);	
$thelink = "";
}


$xml_text .= "<image>
				<title>$title</title>
				<description>".stripslashes($record[3])."</description>
				$thelink
				<image_src>/".stripslashes($record[1])."</image_src>
				<thumb_src>/thumbnail/".stripslashes($record[1])."</thumb_src>
			</image>\n";		
}



$xml_text .= "</images>
	</gallery>
</tn2>
\n";	

$fh = fopen($base_directory."/xml/".$_POST["gallery_xml"], 'w') or die("can't open file");
fwrite($fh, $xml_text);
fclose($fh);


$get_template->topHTML();
?>
<h1>Display Order Updated</h1>

<p>The display order has now been updated</p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "view") {



$result = $sql_command->select($database_testimonials,"id,year,testimonial","ORDER BY timestamp DESC");
$row = $sql_command->results($result);

$list .= "<option value=\"0\">None</option>\n";


foreach($row as $record) {

$start = strpos($record[2], '<strong>');
$end = strpos($record[2], '</strong>', $start);
$paragraph = substr($record[2], $start, $end-$start+9);
$paragraph = str_replace("<strong>", "", $paragraph);
$paragraph = str_replace("</strong>", "", $paragraph);
$paragraph = (strlen($paragraph) > 60) ? substr($paragraph, 0, 60) . '...' : $paragraph;

$list .= "<option value=\"".stripslashes($record[1])."_".stripslashes($record[0])."\">".$record[1]." - ".stripslashes($paragraph)."</option>\n";

}


$g_result = $sql_command->select($database_gallery,"id,gallery_name,xml_filename","WHERE id='".addslashes($_POST["gallery_id"])."'");
$g_record = $sql_command->result($g_result);


$result = $sql_command->select($database_gallery_pics,"id,imagename,displayorder,title,description,link","WHERE gallery_id='".addslashes($_POST["gallery_id"])."' ORDER BY displayorder");
$row = $sql_command->results($result);

foreach($row as $record) {
if($record[2] != 999) {
$thevalue = stripslashes($record[2]);
} else {
$thevalue = "";	
}

$replace_list = str_replace("option value=\"$record[5]\"","option value=\"$record[5]\" selected=\"selected\"",$list);

$html_line .= "<div style=\"float:left; width:60px; margin:5px;\"><img src=\"$site_url/images/gallery/".stripslashes($record[1])."\" alt=\"".stripslashes($record[1])."\" width=\"60\"></div>
<div style=\"float:left; margin:5px;\">	
<div style=\"float:left; width:100px; margin:5px;\"><strong>Display Order</strong></div>
<div style=\"float:left; margin:5px;\"><input type=\"text\" name=\"id_".stripslashes($record[0])."\" value=\"".$thevalue."\"/></div>
<div style=\"clear:both;\"></div>
<div style=\"float:left; width:100px; margin:5px;\"><strong>Title</strong></div>
<div style=\"float:left; margin:5px;\"><input type=\"text\" name=\"title_".stripslashes($record[0])."\" value=\"".stripslashes($record[3])."\"  style=\"width:300px;\"/></div>
<div style=\"clear:both;\"></div>
<div style=\"float:left; width:100px; margin:5px;\"><strong>Link</strong></div>
<div style=\"float:left; margin:5px;\"><select name=\"link_".stripslashes($record[0])."\"/>$replace_list</select></div>
<div style=\"clear:both;\"></div>
<div style=\"float:left; width:100px; margin:5px;\"><strong>Description</strong></div>
<div style=\"float:left; margin:5px;\"><textarea name=\"description_".stripslashes($record[0])."\" style=\"width:400px; height:60px;\"/>".stripslashes($record[4])."</textarea></div>
<div style=\"clear:both;\"></div>

</div>
<div style=\"clear:left;\"></div>";	

//$html_line .= "<div style=\"float:left; width:60px; margin:5px;\"><img src=\"$site_url/images/gallery/".stripslashes($record[1])."\" alt=\"".stripslashes($record[1])."\" width=\"60\"></div>
//<div style=\"float:left; margin:5px;\">	<input type=\"text\" name=\"id_".stripslashes($record[0])."\" value=\"".$thevalue."\"/></div>
//<div style=\"clear:left;\"></div>";	

}

$get_template->topHTML();
?>
<h1>Gallery Display Order</h1>
<h2><?php echo stripslashes($g_record[1]); ?></h2>
<form action="<?php echo $site_url; ?>/admin/update-gallery-display.php" method="POST">
<input type="hidden" name="gallery_id" value="<?php echo $_POST["gallery_id"]; ?>" />
<input type="hidden" name="gallery_xml" value="<?php echo stripslashes($g_record[2]); ?>" />

<div style="float:left; width:60px; margin:5px;"><b>Image</b></div>
<div style="float:left; margin:5px;"><b>Value</b></div>
<div style="clear:left;"></div>
<?php echo $html_line; ?>
<p style="margin-top:10px;"><input type="submit" name="action" value="Update"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	

$result = $sql_command->select($database_gallery,"id,gallery_name","ORDER BY gallery_name");
$row = $sql_command->results($result);
	
foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:11px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Select Gallery</h1>

<form action="<?php echo $site_url; ?>/admin/update-gallery-display.php" method="POST">
<input type="hidden" name="action" value="view" />
<select name="gallery_id" class="inputbox_town" size="30" style="width:710px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}

?>