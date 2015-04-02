<?

class main_template {

	function topHTML() {
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_level_2,$database_level_3,$database_level_4, $current_page;
	
	
	if(!$meta_title) { $meta_title = "Ionina Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = ""; }
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><? echo $meta_title; ?></title>
<meta name="keywords" content="<? echo $meta_keywords; ?>" />
<meta name="description" content="<? echo $meta_description; ?>" />
<? if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />	
<? } else { ?>
<meta name="robots" content="index, follow" />
<? } ?>



<link href="<? echo $site_url; ?>/css/iw.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<? echo $site_url; ?>/js/jquery.tn3.min.js"></script>
<script src="<? echo $site_url; ?>/js/ddlevelsmenu.js" type="text/javascript"></script>
<script type="text/javascript" src="<? echo $site_url; ?>/js/js_ddaccordion.js"></script>
<script type="text/javascript" src="<? echo $site_url; ?>/js/js_ddaccordion_config.js"></script>
</head>

<body>
<div class="site">

	<div class="header">
	<div class="logo"> <a href="<? echo $site_url; ?>/index.php" target="_self" title=""><img src="<? echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </div>
	<div class="headernavigation">
		<h1><img src="<? echo $site_url; ?>/images/interface/i_call_us_today_on_020_8892_7556.gif" alt="Call us today on 020 8894 1991" border="" title="Call us today on 020 8894 1991" /></h1>
        <ul>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/planning-advice/" target="_self" title="Planning advice">Planning advice</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/our-story/" target="_self" title="Our Story">Our Story</a></li>

			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/our-team/" target="_self" title="Our Team">Our Team</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/faqs/" target="_self" title="FAQs">FAQs</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/site-map/" target="_self" title="Site map">Site map</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/contact-us/" target="_self" title="Contact us">Contact us</a></li>
			<li class="clear"></li>
		</ul>
	</div>

	<div class="clear"></div>
</div>	


<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
		<li><a href="#" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
		<li><a href="#" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
		<li><a href="<? echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
		<li><a href="<? echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="Latest News">Latest News</a></li>
		<li><a href="<? echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
		<li><a href="<? echo $site_url; ?>/packages/" target="_self" title="Packages">Packages</a></li>
	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>



	
	
<ul id="ddsubmenu1" class="ddsubmenustyle">
<?
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level,add_link,link_url","WHERE parent_id_1='1' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
if($level2_record[4] == "Yes") {
echo "<li><a href=\"$site_url".stripslashes($level2_record[5])."\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";		
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
}
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";



?>
<ul id="ddsubmenu2" class="ddsubmenustyle">
<? 
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level","WHERE parent_id_1='2' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";


?>
<ul id="ddsubmenu3" class="ddsubmenustyle">
<? 
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level","WHERE parent_id_1='3' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";

?>

<ul id="ddsubmenu4" class="ddsubmenustyle">
	<li><a href="<? echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
	<li><a href="<? echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
</ul>	

<div class="maincontent">
	
<?
	}


	function bottomHTML() {
	global $site_url, $current_page;
	

	?>
    
    
    <? if($current_page == "index") { ?></div>
<table border="0" cellspacing="0" cellpadding="0" class="homefeatures">
	<tr>
		<td class="homefeature">
			<div class="homefeaturecontent">
				<h1>The best of the best</h1>
				<p>See our most opulent luxury wedding venues and packages - when only the very best will suffice.</p>
				<h1><a href="#" target="_self" title="See our 5 Star packages">See our 5 Star packages</a></h1>

			</div>
			<div class="homefeaturenavigation">
				<h1>Wedding Showcase<img src="<? echo $site_url; ?>/images/page/feature/i_feature_01.jpg" alt="Wedding Showcase" border="0" align="right" title="Wedding Showcase" /></h1>
				<ul>
					<li class="homefeaturenavigationlink"><a href="#" target="_self" title="Jane &amp; Andreas, Crete">Jane &amp; Andreas, Crete</a></li>
					<li class="homefeaturenavigationlink"><a href="#" target="_self" title="Hayley & Steve, Skiathos">Hayley &amp; Steve, Skiathos</a></li>

					<li class="homefeaturenavigationlink"><a href="#" target="_self" title="Jackie &amp; Don, Kos">Jackie &amp; Don, Kos</a></li>
					<li class="homefeaturenavigationlink"><a href="#" target="_self" title="Photo Gallery">Photo Gallery</a></li>
				</ul>
			</div>
		</td>
		<td class="homefeaturepad"></td>
		<td class="homefeature">

			<div class="homefeaturecontent">
				<p><img src="<? echo $site_url; ?>/images/page/feature/i_feature_02.jpg" alt="Exclusive, not expensive" border="0" title="Exclusive, not expensive" /></p>
				<h1>Exclusive, not expensive</h1>
				<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum vel lectus. Sed volutpat purus vel odio lobortis convallis.</p>
			</div>
			<div class="homefeaturenavigation">
				<ul>
					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Dana Villas, Santorini">Dana Villas, Santorini</a></li>

					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Melissani Lake, Kefalonia">Melissani Lake, Kefalonia</a></li>
					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Foki beach, Kefalonia">Foki beach, Kefalonia</a></li>
				</ul>
			</div>
		</td>
		<td class="homefeaturepad"></td>
		<td class="homefeature">
			<div class="homefeaturecontent">

				<p><img src="<? echo $site_url; ?>/images/page/feature/i_feature_03.jpg" alt="Special offers" border="0" title="Special offers" /></p>
				<h1>Special offers</h1>
				<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum vel lectus. Sed volutpat purus vel odio lobortis convallis.</p>
			</div>
			<div class="homefeaturenavigation">
				<ul>
					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Kefalonia - spring specials">Kefalonia - spring specials</a></li>

					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Zakynthos beach weddings">Zakynthos beach weddings</a></li>
					<li class="homefeaturenavigationlink homefeaturenavigationlinkborder"><a href="#" target="_self" title="Cyprus 5 star luxury packages">Cyprus 5 star luxury packages</a></li>
				</ul>
			</div>
		</td>
		<td class="homefeaturepad"></td>
		<td class="homefeature">
			<div class="homefeaturecontent">

				<h1>Sign up for updates</h1>
				<p>Find out first about our latest offers and new destinations plus the chance to win a dream wedding package worth Â£10,000</p>
				<form action="#" class="form" id="registration" method="post" name="registration">
					<label class="formlabelshort floatleft" for="registration_firstname">First Name<span class="required">*</span></label>
					<label class="formlabelshort floatright" for="registration_lastname">Last Name<span class="required">*</span></label>
					<div class="clear"></div>

					<input class="formtextfieldshort floatleft" id="registration_firstname" name="registration_firstname" />
					<input class="formtextfieldshort floatright" id="registration_lastname" name="registration_lastname" />
					<div class="clear"></div>
					<label class="formlabellong" for="registration_email">Email Address<span class="required">*</span></label>
					<input class="formtextfieldlong" id="registration_email" name="registration_email" />
					<p><span class="required">*</span>Indicates a required field<input class="formsubmit" id="registration_submit" name="registration_submit" type="submit" value="Sign Up" /></p>
					<p>Please Read our <a href="#" target="_self" title="Privacy Policy">Privacy Policy</a> here</p>

				</form>
			</div>
		</td>
	</tr>
</table>	
<? } else { ?>
<div class="sitefeatures">
	<ul>
		<li class="sitefeaturelink sitefeaturecolor01">
			<h1><a href="<? echo $site_url; ?>/testimonials/" target="_self" title="Find out more">Find out why Ionian Weddings are the best partner you can choose for your big day</a></h1>
			<p>Don't take our word for it, hear from our customers who came to appreciate the difference we make.</p>

			<h2><a href="<? echo $site_url; ?>/testimonials/" target="_self" title="Find out more">Find out more</a></h2>
		</li>
		<li class="sitefeaturelink sitefeaturecolor02">
			<h1><a href="<? echo $site_url; ?>/book-a-call-back/" target="_self" title="Book a call back">Would you prefer to talk to us than browse?</a></h1>
			<p>Call us on 0208 892 7556 if you would like to chat.</p>
			<h2><a href="<? echo $site_url; ?>/book-a-call-back/" target="_self" title="Book a call back">Book a call back</a></h2>
		</li>

		<li class="sitefeaturelink sitefeaturecolor03">
			<h1><a href="<? echo $site_url; ?>/personal-consultations/" target="_self" title="Reserve a consultation">Imagine having your own personal wedding planner</a></h1>
			<p>Now you can - call for a chat to arrange a personal consultation.</p>
			<h2><a href="<? echo $site_url; ?>/personal-consultations/" target="_self" title="Reserve a consultation">Reserve a consultation</a></h2>
		</li>
		<li class="sitefeaturelink sitefeaturecolor04">
			<h1><a href="<? echo $site_url; ?>/wedding-questionnaire/" target="_self" title="Start here">What would make your perfect wedding day?</a></h1>

			<p>Take our 5 minute wedding questionnaire and tell us all!</p>
			<h2><a href="<? echo $site_url; ?>/wedding-questionnaire/" target="_self" title="Start here">Start here</a></h2>
		</li>
		<li class="clear"></li>
	</ul>
</div></div>
<? } ?>
   </div>
	<div class="footer">
	<div class="footercontent">
		<h1>This site is brought to you by Ionian Weddings Limited, the only site you need to make your wedding dreams come true</h1>

	</div>
	<div class="footernavigation">
		<ul>
			<li class="footernavigationlink">Copyright &copy; 2012. All rights reserverd.</li>
			<li class="clear"></li>
		</ul>
		<ul>

			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/our-team/" target="_self" title="About us">About us</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/help/" target="_self" title="Help">Help</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/site-map/" target="_self" title="Site Map">Site Map</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/privacy-policy/" target="_self" title="Privacy Policy">Privacy Policy</a></li>
            <li class="footernavigationlink"><a href="<? echo $site_url; ?>/terms-and-conditions/" target="_self" title="Terms and Conditions">Terms and Conditions</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/contact-us/" target="_self" title="Contact Us">Contact Us</a></li>
			<li class="clear"></li>

		</ul>
	</div>
	<div class="clear"></div>
</div></div>
</body>
</html>

<?
	}


		function errorHTML($e_title, $e_header, $errors, $backlink, $url) {
		global $laterooms, $site_url;

		if($backlink == "Yes") {
		$backhtml = "<p>[ <a href=\"$site_url/$url\" class=\"red\">Back</a> ]";
		} elseif($backlink == "Link") {
		$backhtml = "<p>[ <a href=\"$site_url/$url\" class=\"red\">Back</a> ]";
		} else {
		$backhtml = "";
		}
		
		echo "<h1>$e_title</h1>
		<h2>$e_header</h2>
		<p>$errors</p>
		<p>$backhtml</p>";

	}
	

}


class admin_template {

	function topHTML() {
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_level_2,$database_level_3,$database_level_4, $current_page, $add_header;
	
	
	if(!$meta_title) { $meta_title = "Ionina Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = ""; }
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><? echo $meta_title; ?></title>
<meta name="keywords" content="<? echo $meta_keywords; ?>" />
<meta name="description" content="<? echo $meta_description; ?>" />
<? if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />	
<? } else { ?>
<meta name="robots" content="index, follow" />
<? } ?>



<link href="<? echo $site_url; ?>/css/iw.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="<? echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<? echo $site_url; ?>/js/jquery.tn3.min.js"></script>
<script src="<? echo $site_url; ?>/js/ddlevelsmenu.js" type="text/javascript">

/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript" src="<? echo $site_url; ?>/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		convert_urls : false,
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "<? echo $site_url;?>/css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "<? echo $site_url;?>/lists/template_list.js",
		external_link_list_url : "<? echo $site_url;?>/lists/link_list.js",
		external_image_list_url : "<? echo $site_url;?>/lists/image_list.js",
		media_external_list_url : "<? echo $site_url;?>/lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<script language="JavaScript" src="<? echo $site_url;?>/js/calendar_eu.js"></script>
<link rel="stylesheet" href="<? echo $site_url;?>/css/calendar.css">
<? echo $add_header; ?>
</head>

<body>
<div class="site">

	<div class="header">
	<div class="logo"> <a href="<? echo $site_url; ?>/index.php" target="_self" title=""><img src="<? echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </div>
	<div class="headernavigation">
    <h1><img src="<? echo $site_url; ?>/images/interface/i_call_us_today_on_020_8892_7556.gif" alt="Call us today on 020 8894 1991" border="" title="Call us today on 020 8894 1991" /></h1>
		<ul>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/planning-advice/" target="_self" title="Planning advice">Planning advice</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/our-story/" target="_self" title="Our Story">Our Story</a></li>

			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/our-team/" target="_self" title="Our Team">Our Team</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/faqs/" target="_self" title="FAQs">FAQs</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/site-map/" target="_self" title="Site map">Site map</a></li>
			<li class="headernavigationlink"><a href="<? echo $site_url; ?>/contact-us/" target="_self" title="Contact us">Contact us</a></li>
			<li class="clear"></li>
		</ul>
	</div>

	<div class="clear"></div>
</div>	


<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
		<li><a href="#" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
		<li><a href="#" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
		<li><a href="<? echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
		<li><a href="<? echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="Latest News">Latest News</a></li>
		<li><a href="<? echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
		<li><a href="<? echo $site_url; ?>/packages/" target="_self" title="Packages">Packages</a></li>
	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>



	
	
<ul id="ddsubmenu1" class="ddsubmenustyle">
<?
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level,add_link,link_url","WHERE parent_id_1='1' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
if($level2_record[4] == "Yes") {
echo "<li><a href=\"$site_url".stripslashes($level2_record[5])."\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";		
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
}
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/inspiration-ideas/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";



?>
<ul id="ddsubmenu2" class="ddsubmenustyle">
<? 
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level","WHERE parent_id_1='2' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/destinations/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";


?>
<ul id="ddsubmenu3" class="ddsubmenustyle">
<? 
$level2_result = $sql_command->select($database_level_2,"id,name,link,sub_level","WHERE parent_id_1='3' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {

if($level2_record[3] == "No") {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	

$level3_result = $sql_command->select($database_level_3,"id,name,link,sub_level","WHERE parent_id_2='".stripslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
	
if($level3_record[3] == "No") {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
} else {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	

$level4_result = $sql_command->select($database_level_4,"id,name,link,sub_level","WHERE parent_id_3='".stripslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
echo "<li><a href=\"$site_url/types-of-ceremony/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}

echo "</ul></li>";
}
}

echo "</ul></li>";
}
}
echo "</ul>";



?>

<ul id="ddsubmenu4" class="ddsubmenustyle">
	<li><a href="<? echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
	<li><a href="<? echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
</ul>	

<div class="maincontent">
<div class="maincopy">
<div id="adminnav" style="float:left; width:160px; background-color:#565a5f; padding:10px; font-size:12px; margin-bottom:20px; color:#ccc;">
<b>News</b>
<p>
- <a href="<? echo $site_url; ?>/admin/add-news.php">Add News</a><br />
- <a href="<? echo $site_url; ?>/admin/update-news.php">Update News</a></p>

<b>In the Press</b>
<p>
- <a href="<? echo $site_url; ?>/admin/add-press.php">Add Press Info</a><br />
- <a href="<? echo $site_url; ?>/admin/update-press.php">Update Press Info</a></p>


<b>Orders</b>
<p>
- <a href="<? echo $site_url; ?>/admin/create-order.php">Create Order</a><br />
- <a href="<? echo $site_url; ?>/admin/manage-orders.php">Manage Orders</a><br />
- <a href="<? echo $site_url; ?>/admin/orders-archive.php">Orders Archive</a><br />
- <a href="<? echo $site_url; ?>/admin/customer-quote-invoice.php">Customer Quote/Invoice</a><br />
- <a href="<? echo $site_url; ?>/admin/supplier-purchase-order.php">Supplier Purchase Order</a><br />
- <a href="<? echo $site_url; ?>/admin/customer-resources.php">Customer Resources</a></p>

<b>Suppliers</b>
<p>
- <a href="<? echo $site_url; ?>/admin/supplier-add-supplier.php">Add Supplier</a><br />
- <a href="<? echo $site_url; ?>/admin/supplier-update-supplier.php">Update Supplier</a></p>


<b>Packages</b>
<p>
- <a href="<? echo $site_url; ?>/admin/package-update-location.php">Add / Update Location</a><br />
- <a href="<? echo $site_url; ?>/admin/package-update-island.php">Add / Update Island</a><br />
- <a href="<? echo $site_url; ?>/admin/package-update-package.php">Add / Update Package</a></p>

<b>Destinations</b>
<p>
- <a href="<? echo $site_url; ?>/admin/destinations-update-ceremony.php">Add / Update Location</a><br />
- <a href="<? echo $site_url; ?>/admin/destinations-update-ceremony.php">Add / Update Island</a><br />
- <a href="<? echo $site_url; ?>/admin/destinations-update-ceremony.php">Add / Update City</a></p>

<b>Type of Ceremony</b>
<p>
- <a href="<? echo $site_url; ?>/admin/add-ceremony.php">Add Ceremony</a><br />
- <a href="<? echo $site_url; ?>/admin/update-ceremony.php">Update Ceremony</a></p>

<b>Testimonials</b>
<p>
- <a href="<? echo $site_url; ?>/admin/add-testimonial.php">Add Testimonial</a><br />
- <a href="<? echo $site_url; ?>/admin/update-testimonial.php">Update Testimonial</a></p>

<b>Form Results</b>
<p>
- <a href="<? echo $site_url; ?>/admin/wedding-questionaire.php">Wedding Questionaire</a><br />
- <a href="<? echo $site_url; ?>/admin/personal-consultations.php">Personal Consultations</a><br />
- <a href="<? echo $site_url; ?>/admin/book-a-callback.php">Book a Callback</a><br />
- <a href="<? echo $site_url; ?>/admin/contact-us.php">Contact Us</a></p>


<b>Form Dropdown Lists</b>
<p>
- <a href="<? echo $site_url; ?>/admin/update-country.php">Update Country</a><br />
- <a href="<? echo $site_url; ?>/admin/update-preffered-time.php">Update Preffered time</a><br />
- <a href="<? echo $site_url; ?>/admin/update-type-of-ceremony.php">Update Type of ceremony</a><br />
- <a href="<? echo $site_url; ?>/admin/update-hear-about-us.php">Update Hear about us</a><br />
- <a href="<? echo $site_url; ?>/admin/update-plan-to-book.php">Update Plan to book</a></p>

<b>Upload Files</b>
<p>
- <a href="<? echo $site_url; ?>/admin/upload-xml.php">Upload XML</a><br />
- <a href="<? echo $site_url; ?>/admin/upload-image.php">Upload Image</a></p>

<b>Users</b>
<p>
- <a href="<? echo $site_url; ?>/admin/add-user.php">Add Users</a><br />
- <a href="<? echo $site_url; ?>/admin/update-user.php">Update Users</a></p>


<b>Update HTML</b>
<p>
- <a href="<? echo $site_url; ?>/admin/update-html.php">Update Main Navigation</a><br  />
- <a href="<? echo $site_url; ?>/admin/update-planning-advice.php">Update Planning Advice</a></p>

<b>Menu Display Orders</b><br />

<p><b style="color:#ffffff; font-size:10px;">Packages</b><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php">Location Menu</a><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php">Island Menu</a><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php">Package Menu</a><br />
<b style="color:#ffffff; font-size:10px;">Destination</b><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php?type=destination&sort=location">Location Menu</a><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php?type=destination&sort=island">Island Menu</a><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php?type=destination&sort=city">City Menu</a><br />
<b style="color:#ffffff; font-size:10px;">Ceremony</b><br />
- <a href="<? echo $site_url; ?>/admin/display-order.php?type=ceremony">Ceremony Menu</a></p>

<p><a href="<? echo $site_url; ?>/admin/logout.php">Logout</a></p>
</div>
    
    <div style="float:left; width:710px; padding:10px 0px 10px 20px; margin-bottom:20px; font-size:12px;">	
<?
	}


	function bottomHTML() {
	global $site_url, $current_page;
	

	?>
    
	</div>
    <div style="clear:left;"></div>
    </div>
    </div>
    
   </div>
	<div class="footer">
	<div class="footercontent">
		<h1>This site is brought to you by Ionian Weddings Limited, the only site you need to make your wedding dreams come true</h1>

	</div>
	<div class="footernavigation">
		<ul>
			<li class="footernavigationlink">Copyright &copy; 2012. All rights reserverd.</li>
			<li class="clear"></li>
		</ul>
		<ul>

			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/about-us/" target="_self" title="About us">About us</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/help/" target="_self" title="Help">Help</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/site-map/" target="_self" title="Site Map">Site Map</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/privacy-policy/" target="_self" title="Privacy Policy">Privacy Policy</a></li>
            <li class="footernavigationlink"><a href="<? echo $site_url; ?>/terms-and-conditions/" target="_self" title="Terms and Conditions">Terms and Conditions</a></li>
			<li class="footernavigationlink"><a href="<? echo $site_url; ?>/contact-us/" target="_self" title="Contact Us">Contact Us</a></li>
			<li class="clear"></li>

		</ul>
	</div>
	<div class="clear"></div>
</div></div>
</body>
</html>

<?
	}


		function errorHTML($e_title, $e_header, $errors, $backlink, $url) {
		global $laterooms, $site_url;

		if($backlink == "Yes") {
		$backhtml = "<p>[ <a href=\"$site_url/$url\" class=\"red\">Back</a> ]";
		} elseif($backlink == "Link") {
		$backhtml = "<p>[ <a href=\"$site_url/$url\" class=\"red\">Back</a> ]";
		} else {
		$backhtml = "";
		}
		
		echo "<h1>$e_title</h1>
		<h2>$e_header</h2>
		<p>$errors</p>
		<p>$backhtml</p>";

	}
	

}

?>