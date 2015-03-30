<?

class main_template {

	function topHTML() {
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $current_page;
	
	
	if(!$meta_title) { $meta_title = "Ionina Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = ""; }
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo $meta_title; ?></title>
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<meta name="description" content="<?php echo $meta_description; ?>" />
<?php if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />	
<?php } else { ?>
<meta name="robots" content="index, follow" />
<?php } ?>


<link href="<?php echo $site_url; ?>/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/iw.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.tn3.min.js"></script>
<script src="<?php echo $site_url; ?>/js/ddlevelsmenu.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/js_ddaccordion.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/js_ddaccordion_config.js"></script>
</head>

<body>
<div class="site">

	<div class="header">
	<div class="logo"> <a href="<?php echo $site_url; ?>/index.php" target="_self" title=""><img src="<?php echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </div>
	<div class="headernavigation">
		<h1><img src="<?php echo $site_url; ?>/images/interface/i_call_us_today_on_020_8892_7556.gif" alt="Call us today on 020 8894 1991" border="" title="Call us today on 020 8894 1991" /></h1>
        <ul>
            <li class="headernavigationlink"><a href="<?php echo $site_url; ?>/planning-advice/" target="_self" title="Planning advice">Planning advice</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/our-story/" target="_self" title="Our Story">Our Story</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/our-team/" target="_self" title="Our Team">Our Team</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/faqs/" target="_self" title="FAQs">FAQs</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/site-map/" target="_self" title="Site map">Site map</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/contact-us/" target="_self" title="Contact us">Contact us</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/search/" target="_self" title="Planning advice">Search</a></li>
			<li class="clear"></li>
		</ul>
	</div>

	<div class="clear"></div>
</div>	


<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
    <li><a href="<?php echo $site_url; ?>/inspiration-ideas/" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
    <li><a href="<?php echo $site_url; ?>/destinations/" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
    <li><a href="<?php echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
    <li><a href="<?php echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="Latest News">Latest News</a></li>
    <li><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
	<li><a href="<?php echo $site_url; ?>/packages/" rel="ddsubmenu5" target="_self" title="Packages">Packages</a></li>    
   	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>

<?


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' and hide_page='No'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages") {

if($level1_record[1] == "Inspiration &amp; Ideas" ) {
?><ul id="ddsubmenu1" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Destinations" ) {
?><ul id="ddsubmenu2" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Types of Ceremony" ) {
?><ul id="ddsubmenu3" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Packages" ) {
?><ul id="ddsubmenu5" class="ddsubmenustyle"><?
}

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and hide_page='No' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and hide_page='No' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

if($level3_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	
foreach($level3_row as $level3_record) {

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and hide_page='No' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

if($level4_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	
foreach($level4_row as $level4_record) {

// check internal link  level 4
if($level4_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level4_record[4])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."/\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}
}
// end internal link  level 4

echo "</ul></il>\n";
} else {
	
	
// check internal link  level 3
if($level3_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level3_record[4])."\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
}
}
// end internal link level 4


}
echo "</ul></il>\n";
} else {
	
// check internal link  level 2
if($level2_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level2_record[4])."\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
}
}
// end internal link level 2


}
?></ul><?
}
}
?>




<ul id="ddsubmenu4" class="ddsubmenustyle">
	<li><a href="<?php echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
	<li><a href="<?php echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
</ul>	

<div class="maincontent">
	
<?
	}


	function bottomHTML() {
	global $site_url, $current_page, $sql_command, $database_navigation;


$showcase_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='21' ORDER BY displayorder LIMIT 4");
$showcase_row = $sql_command->results($showcase_result);

foreach($showcase_row as $showcase_record) {
list($weddingname,$weddinglocation) = explode(",",$showcase_record[0]);

$showcase_html .= "<li class=\"homefeaturenavigationlink\"><a href=\"$site_url/wedding-showcase/".stripslashes($showcase_record[1])."\" target=\"_self\" title=\"".stripslashes($showcase_record[0])."\">".stripslashes($weddingname)."</a></li>\n";
}

 if($current_page == "index") { ?></div>
<table border="0" cellspacing="0" cellpadding="0" class="homefeatures">
	<tr>
		<td class="homefeature">
			<div class="homefeaturecontent">
				<h1>The best of the best</h1>
				<p>See our most opulent luxury wedding venues and packages - when only the very best will suffice.</p>
				<h1><a href="#" target="_self" title="See our 5 Star packages">See our 5 Star packages</a></h1>

			</div>
			<div class="homefeaturenavigation">
				<h1><a href="http://83.149.102.2/~ionianwe/inspiration-ideas/wedding-showcase/">Wedding Showcase</a> <img src="<?php echo $site_url; ?>/images/page/feature/i_feature_01.jpg" alt="Wedding Showcase" border="0" align="right" title="Wedding Showcase" /></h1>
				<ul>
					<?php echo $showcase_html; ?>
				</ul>
			</div>
		</td>
		<td class="homefeaturepad"></td>
		<td class="homefeature">

			<div class="homefeaturecontent">
				<p><img src="<?php echo $site_url; ?>/images/page/feature/i_feature_02.jpg" alt="Exclusive, not expensive" border="0" title="Exclusive, not expensive" /></p>
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

				<p><img src="<?php echo $site_url; ?>/images/page/feature/i_feature_03.jpg" alt="Special offers" border="0" title="Special offers" /></p>
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
		<td class="homefeature" style="padding:0;">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=149927145049158";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like-box" data-href="https://www.facebook.com/#!/pages/Ionian-Weddings/179394783501" data-width="225" data-height="277" data-show-faces="false" data-border-color="ffffff" data-stream="true" data-header="false"></div>

		</td>
	</tr>
</table>	
<?php } else { ?>
<div class="sitefeatures">
	<ul>
		<li class="sitefeaturelink sitefeaturecolor01">
			<h1><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Find out more">Find out why Ionian Weddings are the best partner you can choose for your big day</a></h1>
			<p>Don't take our word for it, hear from our customers who came to appreciate the difference we make.</p>

			<h2><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Find out more">Find out more</a></h2>
		</li>
		<li class="sitefeaturelink sitefeaturecolor02">
			<h1><a href="<?php echo $site_url; ?>/book-a-call-back/" target="_self" title="Book a call back">Would you prefer to talk to us than browse?</a></h1>
			<p>Call us on 0208 892 7556 if you would like to chat.</p>
			<h2><a href="<?php echo $site_url; ?>/book-a-call-back/" target="_self" title="Book a call back">Book a call back</a></h2>
		</li>

		<li class="sitefeaturelink sitefeaturecolor03">
			<h1><a href="<?php echo $site_url; ?>/personal-consultations/" target="_self" title="Reserve a consultation">Imagine having your own personal wedding planner</a></h1>
			<p>Now you can - call for a chat to arrange a personal consultation.</p>
			<h2><a href="<?php echo $site_url; ?>/personal-consultations/" target="_self" title="Reserve a consultation">Reserve a consultation</a></h2>
		</li>
		<li class="sitefeaturelink sitefeaturecolor04">
			<h1><a href="<?php echo $site_url; ?>/wedding-questionnaire/" target="_self" title="Start here">What would make your perfect wedding day?</a></h1>

			<p>Take our 5 minute wedding questionnaire and tell us all!</p>
			<h2><a href="<?php echo $site_url; ?>/wedding-questionnaire/" target="_self" title="Start here">Start here</a></h2>
		</li>
		<li class="clear"></li>
	</ul>
</div></div>
<?php } ?>
   </div>
	<div class="footer">
	<div class="footercontent">
		<h1>This site is brought to you by Ionian Weddings Limited, the only site you need to make your wedding dreams come true</h1>

	</div>
	<div class="footernavigation">
		<ul>
			<li class="footernavigationlink">Copyright &copy; 2012. All Rights Reserved.</li>
			<li class="clear"></li>
		</ul>
		<ul>

			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/our-team/" target="_self" title="About us">About us</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/help/" target="_self" title="Help">Help</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/site-map/" target="_self" title="Site Map">Site Map</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/privacy-policy/" target="_self" title="Privacy Policy">Privacy Policy</a></li>
            <li class="footernavigationlink"><a href="<?php echo $site_url; ?>/terms-and-conditions/" target="_self" title="Terms and Conditions">Terms and Conditions</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/contact-us/" target="_self" title="Contact Us">Contact Us</a></li>
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
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $current_page, $add_header, $login_record;
	
	
	if(!$meta_title) { $meta_title = "Ionina Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = ""; }
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo $meta_title; ?></title>
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<meta name="description" content="<?php echo $meta_description; ?>" />
<?php if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />	
<?php } else { ?>
<meta name="robots" content="index, follow" />
<?php } ?>



<link href="<?php echo $site_url; ?>/css/iw.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.tn3.min.js"></script>
<script src="<?php echo $site_url; ?>/js/ddlevelsmenu.js" type="text/javascript">

/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript" src="<?php echo $site_url; ?>/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		convert_urls : false,
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,forecolor,cut,copy,paste,pastetext,pasteword,|,code,",
		theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,|,hr,removeformat,|,sub,sup,|,charmap,advhr,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "<?php echo $site_url;?>/css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "<?php echo $site_url;?>/lists/template_list.js",
		external_link_list_url : "<?php echo $site_url;?>/lists/link_list.js",
		external_image_list_url : "<?php echo $site_url;?>/lists/image_list.js",
		media_external_list_url : "<?php echo $site_url;?>/lists/media_list.js",

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
<script language="JavaScript" src="<?php echo $site_url;?>/js/calendar_eu.js"></script>
<link rel="stylesheet" href="<?php echo $site_url;?>/css/calendar.css">
<?php echo $add_header; ?>
</head>

<body>
<div class="site">

	<div class="header">
	<div class="logo"> <a href="<?php echo $site_url; ?>/index.php" target="_self" title=""><img src="<?php echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </div>
	<div class="headernavigation">
    <h1><img src="<?php echo $site_url; ?>/images/interface/i_call_us_today_on_020_8892_7556.gif" alt="Call us today on 020 8894 1991" border="" title="Call us today on 020 8894 1991" /></h1>
		<ul>
      
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/planning-advice/" target="_self" title="Planning advice">Planning advice</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/our-story/" target="_self" title="Our Story">Our Story</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/our-team/" target="_self" title="Our Team">Our Team</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/faqs/" target="_self" title="FAQs">FAQs</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/site-map/" target="_self" title="Site map">Site map</a></li>
			<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/contact-us/" target="_self" title="Contact us">Contact us</a></li>
            <li class="headernavigationlink"><a href="<?php echo $site_url; ?>/search/" target="_self" title="Planning advice">Search</a></li>
			<li class="clear"></li>
		</ul>
	</div>

	<div class="clear"></div>
</div>	


<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
    <li><a href="<?php echo $site_url; ?>/inspiration-ideas/" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
    <li><a href="<?php echo $site_url; ?>/destinations/" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
    <li><a href="<?php echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
    <li><a href="<?php echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="Latest News">Latest News</a></li>
    <li><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
	<li><a href="<?php echo $site_url; ?>/packages/" rel="ddsubmenu5" target="_self" title="Packages">Packages</a></li>    
   	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>



	
<?


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages") {

if($level1_record[1] == "Inspiration &amp; Ideas" ) {
?><ul id="ddsubmenu1" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Destinations" ) {
?><ul id="ddsubmenu2" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Types of Ceremony" ) {
?><ul id="ddsubmenu3" class="ddsubmenustyle"><?
} elseif($level1_record[1] == "Packages" ) {
?><ul id="ddsubmenu5" class="ddsubmenustyle"><?
}

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

if($level3_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	
foreach($level3_row as $level3_record) {

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

if($level4_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a><ul>\n";	
foreach($level4_row as $level4_record) {

// check internal link  level 4
if($level4_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level4_record[4])."\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."/\" target=\"_self\" title=\"".stripslashes($level4_record[1])."\">".stripslashes($level4_record[1])."</a></li>\n";	
}
}
// end internal link  level 4

echo "</ul></il>\n";
} else {
	
	
// check internal link  level 3
if($level3_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level3_record[4])."\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/\" target=\"_self\" title=\"".stripslashes($level3_record[1])."\">".stripslashes($level3_record[1])."</a></li>\n";	
}
}
// end internal link level 4


}
echo "</ul></il>\n";
} else {
	
// check internal link  level 2
if($level2_record[3] == "Yes") {
echo "<li><a href=\"".stripslashes($level2_record[4])."\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";
} else {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a></li>\n";	
}
}
// end internal link level 2


}
?></ul><?
}
}
?>


<ul id="ddsubmenu4" class="ddsubmenustyle">
	<li><a href="<?php echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
	<li><a href="<?php echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
</ul>	

<div class="maincontent">
<div class="maincopy">
<div id="adminnav" style="float:left; width:160px; background-color:#565a5f; padding:10px; font-size:12px; margin-bottom:20px; color:#ccc;">
<?php if($login_record[0] == "Super Admin") { ?>
<b>Clients</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-client.php">Add Client</a><br />
- <a href="<?php echo $site_url; ?>/admin/manage-client.php">Manage Client</a></p>

<b>Orders</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/outstanding-weddings.php">Outstanding Weddings</a><br />
- <a href="<?php echo $site_url; ?>/admin/outustanding-invoices.php">Outstanding Invoices</a><br />
- <a href="<?php echo $site_url; ?>/admin/outustanding-purchase-orders.php">Outstanding Purchase Orders</a></p>

<b>Suppliers</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/supplier-add-supplier.php">Add Supplier</a><br />
- <a href="<?php echo $site_url; ?>/admin/supplier-update-supplier.php">Update Supplier</a></p>
<?php } ?>

<?php if($login_record[0] == "Super Admin" or $login_record[0] == "Admin") { ?>
<b>News</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-news.php">Add News</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-news.php">Update News</a></p>


<b>In the Press</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-press.php">Add Press Info</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-press.php">Update Press Info</a></p>

<b>Testimonials</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-testimonial.php">Add Testimonial</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-testimonial.php">Update Testimonial</a></p>

<b>Form Results</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/wedding-questionaire.php">Wedding Questionaire</a><br />
- <a href="<?php echo $site_url; ?>/admin/personal-consultations.php">Personal Consultations</a><br />
- <a href="<?php echo $site_url; ?>/admin/book-a-callback.php">Book a Callback</a><br />
- <a href="<?php echo $site_url; ?>/admin/contact-us.php">Contact Us</a></p>
<?php } ?>


<?php if($login_record[0] == "Super Admin" or $login_record[0] == "Admin" or $login_record[0] == "User") { ?>
<b>Navigation</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-page.php">Add Page</a><br  />
- <a href="<?php echo $site_url; ?>/admin/update-page.php">Update Page</a><br  />
- <a href="<?php echo $site_url; ?>/admin/display-order.php">Update Display Order</a></p>

<b>Gallery</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-gallery.php">Add Gallery</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-gallery.php">Update Gallery</a></p>
<?php } ?>

<?php if($login_record[0] == "Super Admin" or $login_record[0] == "Admin") { ?>
<b>Form Dropdown Lists</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/update-country.php">Update Country</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-preffered-time.php">Update Preffered time</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-type-of-ceremony.php">Update Type of ceremony</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-hear-about-us.php">Update Hear about us</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-plan-to-book.php">Update Plan to book</a></p>

<b>Users</b>
<p>
- <a href="<?php echo $site_url; ?>/admin/add-user.php">Add Users</a><br />
- <a href="<?php echo $site_url; ?>/admin/update-user.php">Update Users</a></p>
<?php } ?>

<p><a href="<?php echo $site_url; ?>/admin/logout.php">Logout</a></p>
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
			<li class="footernavigationlink">Copyright &copy; 2012. All Rights Reserved.</li>
			<li class="clear"></li>
		</ul>
		<ul>

			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/about-us/" target="_self" title="About us">About us</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/help/" target="_self" title="Help">Help</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/site-map/" target="_self" title="Site Map">Site Map</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/privacy-policy/" target="_self" title="Privacy Policy">Privacy Policy</a></li>
            <li class="footernavigationlink"><a href="<?php echo $site_url; ?>/terms-and-conditions/" target="_self" title="Terms and Conditions">Terms and Conditions</a></li>
			<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/contact-us/" target="_self" title="Contact Us">Contact Us</a></li>
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