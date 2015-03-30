<?php
require ("_includes/settings.php");
require ("_includes/function.templates.php");
include ("_includes/function.database.php");
include ("_includes/function.genpass.php");

// Connect to sql database
$sql_command = new \sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


$get_template = new main_template();


list($blank,$level1_name,$level2_name,$level3_name,$level4_name) = explode("/", $_SERVER["REQUEST_URI"]);

if($level1_name == "navigation_header") {
if($level3_name) { $addlinelink = $level3_name."/"; }
if($level4_name) { $addlinelink .= $level4_name."/"; }
	
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$site_url."/".$level2_name."/".$addlinelink);
exit();		
}

if($level1_name == "navigation_footer") {
if($level3_name) { $addlinelink = $level3_name."/"; }
if($level4_name) { $addlinelink .= $level4_name."/"; }
	
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$site_url."/".$level2_name."/".$addlinelink);
exit();		
}


$last_char = substr($_SERVER["REQUEST_URI"], -1);
if($last_char != "/") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$_SERVER["REQUEST_URI"]."/");
exit();	
}

if($level2_name == "our-story" or $level3_name == "our-story" or $level4_name == "our-story") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: http://www.ionianweddings.co.uk/our-story/");
exit();
}

if($level1_name == "5-star-packages" or $level1_name == "special-offers") {
		
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

$meta_title = stripslashes($level1_record[3]);
$meta_key = stripslashes($level1_record[4]);
$meta_des = stripslashes($level1_record[5]);

$get_template->topHTML();
include("_misc-news/".$level1_name.".php");
$get_template->bottomHTML();
$sql_command->close();

} elseif($level1_name == "site-map") {
		
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }

$meta_title = stripslashes($level1_record[3]);
$meta_key = stripslashes($level1_record[4]);
$meta_des = stripslashes($level1_record[5]);

$get_template->topHTML();
include("_sitemap/".$level1_name.".php");
$get_template->bottomHTML();
$sql_command->close();

} elseif($level1_name == "latest-news" or $level1_name == "news-archive" or $level1_name == "in-the-press" or $level1_name == "ionian-weddings-blog") {
		
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }

$meta_title = stripslashes($level1_record[3]) . " - Page $page";
$meta_key = stripslashes($level1_record[4]);
$meta_des = stripslashes($level1_record[5]);

$get_template->topHTML();
include("_news/".$level1_name.".php");
$get_template->bottomHTML();
$sql_command->close();

} 
elseif($level1_name == "contact-us" or $level1_name == "wedding-questionnaire" or $level1_name == "personal-consultations" or $level1_name == "book-a-call-back"  or $level1_name == "feedback" or $level1_name == "invoice-payment" or $level1_name == "quotations" or $level1_name == "payment") {

$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }

$meta_title = stripslashes($level1_record[3]);
$meta_key = stripslashes($level1_record[4]);
$meta_des = stripslashes($level1_record[5]);

$add_header = "  <link href=\"".$site_url."/css/datepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>
  <script src=\"".$site_url."/js/datepicker.js\"></script>
  
  <script>
  $(document).ready(function() {
    $(\"#datepicker\").datepicker({ dateFormat: 'dd-mm-yy' });
});
  </script>";

$get_template->topHTML();
if ($level1_name!="payment") { include("_forms/".$level1_name.".php"); }
else { include("_wopay/worldpay_c.php"); }
$get_template->bottomHTML();
$sql_command->close();

}
elseif ($level1_name == "image-ine"){
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }

$meta_title = stripslashes($level1_record[3]);
$meta_key = stripslashes($level1_record[4]);
$meta_des = stripslashes($level1_record[5]);

//$add_header = "  <link href=\"".$site_url."/css/datepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>";
	
$get_template->imageHTML();
include("_forms/".$level1_name.".php");
$sql_command->close();
	
}
else {
	
	
if($level4_name and $level4_name != "#") {
	
$level1_result = $sql_command->select($database_navigation,"id","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);
	
$level2_result = $sql_command->select($database_navigation,"id","WHERE parent_id='".addslashes($level1_record[0])."' and page_link='".addslashes($level2_name)."'");
$level2_record = $sql_command->result($level2_result);

$level3_result = $sql_command->select($database_navigation,"id","WHERE parent_id='".addslashes($level2_record[0])."' and  page_link='".addslashes($level3_name)."'");
$level3_record = $sql_command->result($level3_result);

$level4_result = $sql_command->select($database_navigation,"id,page_name,html,xml_file_name,meta_title,meta_key,meta_des,show_thumbnails,feature_id,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and  page_link='".addslashes($level4_name)."'");
$level4_record = $sql_command->result($level4_result);

if($level4_record[9] == "Yes") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$level4_record[10]."");
exit();	
}


$meta_title = stripslashes($level4_record[4]);
$meta_key = stripslashes($level4_record[5]);
$meta_des = stripslashes($level4_record[6]);

$site_content = stripslashes($level4_record[2]);	
$xml_file = stripslashes($level4_record[3]);
$parent_id = stripslashes($level4_record[0]);
$show_thumbnails = stripslashes($level4_record[7]);
$feature_id = stripslashes($level4_record[8]);

} elseif($level3_name and $level3_name != "#") {
	
$level1_result = $sql_command->select($database_navigation,"id","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);
	
$level2_result = $sql_command->select($database_navigation,"id","WHERE parent_id='".addslashes($level1_record[0])."' and page_link='".addslashes($level2_name)."'");
$level2_record = $sql_command->result($level2_result);

$level3_result = $sql_command->select($database_navigation,"id,page_name,html,xml_file_name,meta_title,meta_key,meta_des,show_thumbnails,feature_id,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and  page_link='".addslashes($level3_name)."'");
$level3_record = $sql_command->result($level3_result);

if($level3_record[9] == "Yes") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$level3_record[10]."");
exit();	
}

$meta_title = stripslashes($level3_record[4]);
$meta_key = stripslashes($level3_record[5]);
$meta_des = stripslashes($level3_record[6]);

$site_content = stripslashes($level3_record[2]);	
$xml_file = stripslashes($level3_record[3]);
$parent_id = stripslashes($level3_record[0]);
$show_thumbnails = stripslashes($level3_record[7]);
$feature_id = stripslashes($level3_record[8]);

} elseif($level2_name and $level2_name != "#") {
	
$level1_result = $sql_command->select($database_navigation,"id","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);
	
$level2_result = $sql_command->select($database_navigation,"id,page_name,html,xml_file_name,meta_title,meta_key,meta_des,show_thumbnails,feature_id,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and page_link='".addslashes($level2_name)."'");
$level2_record = $sql_command->result($level2_result);

if($level2_record[9] == "Yes") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$level2_record[10]."");
exit();	
}

$meta_title = stripslashes($level2_record[4]);
$meta_key = stripslashes($level2_record[5]);
$meta_des = stripslashes($level2_record[6]);

$site_content = stripslashes($level2_record[2]);	
$xml_file = stripslashes($level2_record[3]);
$parent_id = stripslashes($level2_record[0]);
$show_thumbnails = stripslashes($level2_record[7]);
$feature_id = stripslashes($level2_record[8]);

} elseif($level1_name and $level1_name != "#") {
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,xml_file_name,meta_title,meta_key,meta_des,show_thumbnails,feature_id,external_link,external_url","WHERE page_link='".addslashes($level1_name)."'");
$level1_record = $sql_command->result($level1_result);

if($level1_record[9] == "Yes") {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: ".$level1_record[10]."");
exit();	
}

$meta_title = stripslashes($level1_record[4]);
$meta_key = stripslashes($level1_record[5]);
$meta_des = stripslashes($level1_record[6]);

$site_content = stripslashes($level1_record[2]);
$xml_file = stripslashes($level1_record[3]);
$parent_id = stripslashes($level1_record[0]);
$show_thumbnails = stripslashes($level1_record[7]);
$feature_id = stripslashes($level1_record[8]);
}
	



if(!$site_content) {
header("Location: $site_url/");
$sql_command->close();
} else {

if($show_thumbnails == "Yes") {

if($level1_name) {
$linkurl .= "$site_url/$level1_name";
}
if($level2_name) {
$linkurl .= "/$level2_name";
}
if($level3_name) {
$linkurl .= "/$level3_name";
}


if($show_thumbnails == "Yes") {
if(strpos($site_content, "INSERT_THUMBNAILS_1") !== false){ $splitcount = 1; 
} elseif(strpos($site_content, "INSERT_THUMBNAILS_2") !== false){ $splitcount = 2; 
} elseif(strpos($site_content, "INSERT_THUMBNAILS_3") !== false){ $splitcount = 3; 
} else{ $splitcount = 4; }		


if($parent_id == 5) {
	
$thumbnail_result = $sql_command->select($database_navigation,"id,page_name,page_link,thumbnail_image,thumbnail_tag ","WHERE parent_id='".addslashes($parent_id)."' and hide_page='No' ORDER BY displayorder");
$thumbnail_row = $sql_command->results($thumbnail_result);

$count_rows=0;
foreach($thumbnail_row as $thumbnail_record) {

if($thumbnail_record[3]) { $image_location = "page/feature/".stripslashes($thumbnail_record[3]); } else { $image_location = "b_awaiting_image.jpg"; }
if($thumbnail_record[4]) { $tag_line = "<p>".stripslashes($thumbnail_record[4])."</p>"; } else { $tag_line = ""; }

$pictures = "<a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">
<img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" /></a>
			\n";
				
$textline = "<a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a>\n";

$showhumbnails .= "<div class='boxlistrow'>
				<div class='boxlistleft'>
					<a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">
						<img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" />
					</a>
					<h3><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></h3>
				</div>
				<div class='boxlistright'>
					<h3><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></h3>
					".stripslashes($thumbnail_record[4])."
				</div>
				<div class='clear'></div>
			</div>";
			

$count_rows++;
}





if($showhumbnails) {
$displaythumbnails = "<div class=\"boxlist\">$showhumbnails</div>";
} 

$site_content = str_replace("<p>[INSERT_THUMBNAILS]</p>",$displaythumbnails,$site_content);
$site_content = str_replace("[INSERT_THUMBNAILS]",$displaythumbnails,$site_content);

} elseif($parent_id == 2) {
	
$thumbnail_parent_result = $sql_command->select($database_navigation,"id,page_name,page_link,thumbnail_image,thumbnail_tag ","WHERE parent_id='".addslashes($parent_id)."' and hide_page='No' ORDER BY displayorder");
$thumbnail_parent_row = $sql_command->results($thumbnail_parent_result);	
	
$oldlink = $linkurl;

foreach($thumbnail_parent_row as $thumbnail_parent_record) {	
$showhumbnails = "";
$pictures = "";
$textline = "";
$third_line = "";
$mobilerow = "";
$linkurl = $oldlink . "/".stripslashes($thumbnail_parent_record[2]);

$thumbnail_result = $sql_command->select($database_navigation,"id,page_name,page_link,thumbnail_image,thumbnail_tag ","WHERE parent_id='".addslashes($thumbnail_parent_record[0])."' and hide_page='No' ORDER BY displayorder");
$thumbnail_row = $sql_command->results($thumbnail_result);

$count_rows=0;
foreach($thumbnail_row as $thumbnail_record) {

if($count_rows%$splitcount == 0 and $count_rows !=0) {
$showhumbnails .= "<table border=\"0\" class=\"pagelistrow no1 toprow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>
<table border=\"0\" class=\"pagelistrow no1 bottomrow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>";
//<div class=\"mobilelistrow\">
//$mobilerow
//<div class=\"clear\"></div>
//</div>";
$pictures = "";
$textline = "";
$third_line = "";
$mobilerow = "";
}

if($thumbnail_record[3]) { $image_location = "page/feature/".stripslashes($thumbnail_record[3]); } else { $image_location = "b_awaiting_image.jpg"; }
if($thumbnail_record[4]) { $tag_line = "<p>".stripslashes($thumbnail_record[4])."</p>"; } else { $tag_line = ""; }

$pictures .= "<th><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">
<img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" /></a></th>
				<th class=\"pagelistspacer\"></th>\n";
				
$textline .= "<td class=\"pagelistheader\"><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></td><td class=\"pagelistspacer\"></td>\n";

if($thumbnail_record[4]) {
$third_line .= "<td>".stripslashes($thumbnail_record[4])."</td><td class=\"pagelistspacer\"></td>\n";
} else {
$third_line .= "<td></td><td class=\"pagelistspacer\"></td>\n";
}

$mobilerow .=	"<div class=\"mobilelistrowitem\">
					<a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\"><img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" />
					<h3><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></h3>
					".stripslashes($thumbnail_record[4])."
				</div>";

$count_rows++;

}

if($pictures) {
$showhumbnails .= "<table border=\"0\" class=\"pagelistrow no2 toprow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>
<table border=\"0\" class=\"pagelistrow no2 bottomrow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>";
//<div class=\"mobilelistrow\">
//$mobilerow
//<div class=\"clear\"></div>
//</div>";
}

if($showhumbnails) {
$displaythumbnails .= "<div class=\"pagelist\" style=\"margin-left:0px;\">
<h1><a href=\"$linkurl/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\">".stripslashes($thumbnail_parent_record[1])."</a></h1>
$showhumbnails 
</div>";
} else {
if($thumbnail_parent_record[3]) { $image_location = "page/feature/".stripslashes($thumbnail_parent_record[3]); } else { $image_location = "b_awaiting_image.jpg"; }
$displaythumbnails .= "<div class=\"pagelist\" style=\"margin-left:0px;\">
<h1><a href=\"$linkurl/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\">".stripslashes($thumbnail_parent_record[1])."</a></h1>
<table border=\"0\" class=\"pagelistrow no3\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td><a href=\"$linkurl/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\">
<img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_parent_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_parent_record[1])."\" /></a></td>
				<td class=\"pagelistspacer\" rowspan=\"3\"></td>
</tr>
<tr>
<th><a href=\"$linkurl/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\">".stripslashes($thumbnail_parent_record[1])."</a></th>
</tr>
<tr>
<td>".stripslashes($thumbnail_parent_record[4])."</td></tr>
</table>
</div>";
//<div class=\"mobilelistrow\">
//<div class=\"mobilelistrowitem\">
//<a href=\"$linkurl/".stripslashes($thumbnail_parent_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\"><img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_parent_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_parent_record[1])."\" />
//<h3><a href=\"$linkurl/".stripslashes($thumbnail_parent_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_parent_record[1])."\">".stripslashes($thumbnail_parent_record[1])."</a></h3>
//".stripslashes($thumbnail_parent_record[4])."
//</div>
//<div class=\"clear\"></div>
//</div>";
}

}


$site_content = str_replace("<p>[INSERT_THUMBNAILS_1]</p>",$displaythumbnails,$site_content);
$site_content = str_replace("<p>[INSERT_THUMBNAILS_2]</p>",$displaythumbnails,$site_content);
$site_content = str_replace("<p>[INSERT_THUMBNAILS_3]</p>",$displaythumbnails,$site_content);
$site_content = str_replace("<p>[INSERT_THUMBNAILS_4]</p>",$displaythumbnails,$site_content);

$site_content = str_replace("[INSERT_THUMBNAILS_1]",$displaythumbnails,$site_content);
$site_content = str_replace("[INSERT_THUMBNAILS_2]",$displaythumbnails,$site_content);
$site_content = str_replace("[INSERT_THUMBNAILS_3]",$displaythumbnails,$site_content);
$site_content = str_replace("[INSERT_THUMBNAILS_4]",$displaythumbnails,$site_content);
	
} else {

$thumbnail_result = $sql_command->select($database_navigation,"id,page_name,page_link,thumbnail_image,thumbnail_tag,external_link,external_url","WHERE parent_id='".addslashes($parent_id)."' and hide_page='No' ORDER BY displayorder");
$thumbnail_row = $sql_command->results($thumbnail_result);

$count_rows=0;
foreach($thumbnail_row as $thumbnail_record) {

if($count_rows%$splitcount == 0 and $count_rows !=0) {
$showhumbnails .= "<table border=\"0\" class=\"pagelistrow no41 toprow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>
<table border=\"0\" class=\"pagelistrow no41 bottomrow\" cellspacing=\"0\" cellpadding=\"0\">
<tr class=\"row1\">$pictures</tr>
<tr class=\"row2\">$textline</tr>
<tr class=\"row3\">$third_line</tr>
</table>";
//<div class=\"mobilelistrow\">
//$mobilerow
//<div class\"clear\"></div>
//</div>";
$pictures = "";
$textline = "";
$third_line = "";
$mobilerow = "";
}

if($thumbnail_record[3]) { $image_location = "page/feature/".stripslashes($thumbnail_record[3]); } else { $image_location = "b_awaiting_image.jpg"; }
if($thumbnail_record[4]) { $tag_line = "<p>".stripslashes($thumbnail_record[4])."</p>"; } else { $tag_line = ""; }

if($thumbnail_record[5] == "Yes") {
$dolink = stripslashes($thumbnail_record[6]);
} else {
$dolink = "$linkurl/".stripslashes($thumbnail_record[2])."/";
}

$pictures .= "<th><a href=\"$dolink\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">
<img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" /></a></th>
				<th class=\"pagelistspacer\"></th>\n";



$textline .= "<td class=\"pagelistheader\"><a href=\"$dolink\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></td><td class=\"pagelistspacer\"></td>\n";

if($thumbnail_record[4]) {
$third_line .= "<td>".stripslashes($thumbnail_record[4])."</td><td class=\"pagelistspacer\"></td>\n";
} else {
			$third_line .= "<td></td><td class=\"pagelistspacer\"></td>\n";
}

				$mobilerow .=	"<div class=\"mobilelistrowitem\">
									<a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\"><img src=\"$site_url/images/$image_location\" alt=\"".stripslashes($thumbnail_record[1])."\" border=\"0\" title=\"".stripslashes($thumbnail_record[1])."\" />
									<h3><a href=\"$linkurl/".stripslashes($thumbnail_record[2])."/\" target=\"_self\" title=\"".stripslashes($thumbnail_record[1])."\">".stripslashes($thumbnail_record[1])."</a></h3>
									".stripslashes($thumbnail_record[4])."
								</div>";
				
				$count_rows++;
}

				if($pictures) {
				$showhumbnails .=	"<table border=\"0\" class=\"pagelistrow no42 toprow\" cellspacing=\"0\" cellpadding=\"0\">
									<tr class=\"row1\">$pictures</tr>
									<tr class=\"row2\">$textline</tr>
									<tr class=\"row3\">$third_line</tr>
									</table>
									<table border=\"0\" class=\"pagelistrow no42 bottomrow\" cellspacing=\"0\" cellpadding=\"0\">
									<tr class=\"row1\">$pictures</tr>
									<tr class=\"row2\">$textline</tr>
									<tr class=\"row3\">$third_line</tr>
									</table>";
									//<div class=\"mobilelistrow\">
									//$mobilerow
									//<div class=\"clear\"></div>
									//</div>";
				}

				if($showhumbnails) {
					$displaythumbnails = "<div class=\"pagelist\" style=\"margin-left:0px;\">$showhumbnails </div>";
				} 

				$site_content = str_replace("<p>[INSERT_THUMBNAILS_1]</p>",$displaythumbnails,$site_content);
				$site_content = str_replace("<p>[INSERT_THUMBNAILS_2]</p>",$displaythumbnails,$site_content);
				$site_content = str_replace("<p>[INSERT_THUMBNAILS_3]</p>",$displaythumbnails,$site_content);
				$site_content = str_replace("<p>[INSERT_THUMBNAILS_4]</p>",$displaythumbnails,$site_content);
				
				$site_content = str_replace("[INSERT_THUMBNAILS_1]",$displaythumbnails,$site_content);
				$site_content = str_replace("[INSERT_THUMBNAILS_2]",$displaythumbnails,$site_content);
				$site_content = str_replace("[INSERT_THUMBNAILS_3]",$displaythumbnails,$site_content);
				$site_content = str_replace("[INSERT_THUMBNAILS_4]",$displaythumbnails,$site_content);

			}

		}

	}


		if($xml_file) { 


		$get_template->topHTML();
?>
		<div class="maincopy">
			<div class="mainhero floatright">
				<script type="text/javascript">
						$(document).ready(function() {
							var tn1 = $('.hero').tn3({
								skinDir:"skins",
								imageClick:"url",
								width:516,
								height:462,
								responsive:true,
								autoplay:true,
								//delay: 2000,
								image:{
									crop:false,
									transitions:[{
										type: "fade",
										easing: "easeInQuad",
										duration: 900
									}]
								},
								external:[{
								origin:"xml",
								url:"<?php echo $site_url; ?>/xml/<?php echo $xml_file; ?>"
								}]
							});
						});
						
						
					</script>
					<div class="hero"></div>
				</div>
<?php

				$display_feature_result = $sql_command->select($database_show_features,"feature_id","WHERE parent_id='".addslashes($parent_id)."'");
				$display_feature_row = $sql_command->results($display_feature_result);
			
				foreach($display_feature_row as $display_feature_record) { 
			
				$feature_result = $sql_command->select($database_feature_packages,"title,the_link,the_image,description","WHERE id='".addslashes($display_feature_record[0])."'");
				$feature_record = $sql_command->result($feature_result);
			
				if($feature_record[0]) {
?>
					<div class="mainfeature floatright show">
						<div class="mainfeatureimage"><a href="<?php echo stripslashes($feature_record[1]); ?>"><img src="<?php echo $site_url; ?>/images/package-feature/<?php echo stripslashes($feature_record[2]); ?>" alt="<?php echo stripslashes($feature_record[0]); ?>" border="0" title="<?php echo stripslashes($feature_record[0]); ?>" /></a></div>
						<div class="mainfeaturecopy">
							<h3><a href="<?php echo stripslashes($feature_record[1]); ?>"><?php echo stripslashes($feature_record[0]); ?></a></h3><?php echo stripslashes($feature_record[3]); ?></div>
					
						<div class="clear"></div>
					</div>
<?php
				}
			}
					echo $site_content;

					$display_feature_result = $sql_command->select($database_show_features,"feature_id","WHERE parent_id='".addslashes($parent_id)."'");
					$display_feature_row = $sql_command->results($display_feature_result);
				
					foreach($display_feature_row as $display_feature_record) { 
				
					$feature_result = $sql_command->select($database_feature_packages,"title,the_link,the_image,description","WHERE id='".addslashes($display_feature_record[0])."'");
					$feature_record = $sql_command->result($feature_result);
				
					if($feature_record[0]) {
?>
					<div class="mainfeature floatleft hide">
						<div class="mainfeatureimage"><a href="<?php echo stripslashes($feature_record[1]); ?>"><img src="<?php echo $site_url; ?>/images/package-feature/<?php echo stripslashes($feature_record[2]); ?>" alt="<?php echo stripslashes($feature_record[0]); ?>" border="0" title="<?php echo stripslashes($feature_record[0]); ?>" /></a></div>
						<div class="mainfeaturecopy">
							<h3><a href="<?php echo stripslashes($feature_record[1]); ?>"><?php echo stripslashes($feature_record[0]); ?></a></h3><?php echo stripslashes($feature_record[3]); ?></div>
					
						<div class="clear"></div>
					</div>
<?php
					}
					}
?>
				<div class="clear"></div>
				</div>
<?php
			$get_template->bottomHTML();
			$sql_command->close();

			} else {

				$get_template->topHTML();
?>
				<div class="maincopy">
<?php
						$display_feature_result = $sql_command->select($database_show_features,"feature_id","WHERE parent_id='".addslashes($parent_id)."'");
						$display_feature_row = $sql_command->results($display_feature_result);
						
						foreach($display_feature_row as $display_feature_record) { 
						
						$feature_result = $sql_command->select($database_feature_packages,"title,the_link,the_image,description","WHERE id='".addslashes($display_feature_record[0])."'");
						$feature_record = $sql_command->result($feature_result);
	
						if($feature_record[0]) {
?>
						<div class="mainfeature floatright">
							<div class="mainfeatureimage"><a href="<?php echo stripslashes($feature_record[1]); ?>"><img src="<?php echo $site_url; ?>/images/package-feature/<?php echo stripslashes($feature_record[2]); ?>" alt="<?php echo stripslashes($feature_record[0]); ?>" border="0" title="<?php echo stripslashes($feature_record[0]); ?>" /></a></div>
							<div class="mainfeaturecopy">
								<h1><a href="<?php echo stripslashes($feature_record[1]); ?>"><?php echo stripslashes($feature_record[0]); ?></a></h1><?php echo stripslashes($feature_record[3]); ?></div>
						
							<div class="clear"></div>
						</div>	
<?php
						}
					}
				
					echo $site_content;
?>
				</div>
<?php
				$get_template->bottomHTML();
				$sql_command->close();

			}
		}
	}
?>