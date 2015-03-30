<?php

class main_template {

	function topHTML() {
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $database_gray_feature, $current_page, $add_header, $_SERVER, $add_header;
	
	
	if(!$meta_title) { $meta_title = "Ionian Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = ""; }
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="msvalidate.01" content="981010E92BCC633C19C312945FA62582" />
<title><?php echo $meta_title; ?></title>
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<meta name="description" content="<?php echo $meta_description; ?>" />
<?php if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />
<?php } else { ?>
<meta name="robots" content="index, follow" />
<?php } ?>
<link href="<?php echo $site_url; ?>/css/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/iw_responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/css/responsive.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.tn3.min_v14.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.ender.min.js"></script>
<script src="<?php echo $site_url; ?>/js/ddlevelsmenu.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/js_ddaccordion.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/js_ddaccordion_config.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/js_selectnav.js"></script>
<script language="JavaScript" src="<?php echo $site_url;?>/js/calendar_eu.js"></script>
<link rel="stylesheet" href="<?php echo $site_url;?>/css/calendar.css">
<script type='text/javascript'>
//<![CDATA[ 
//$(window).load(function(){
//
////$('.maincontent .pagelist').parent().prependTo('.maincontent .maincopy');
//$(".pagelist").detach().insertAfter(".maincopy h1:first");
//
//});//]]>  

function checkWindowSize() {
	
	var iSiteWidth	=	$(".site").width();
	
	//alert(iSiteWidth);
	
	if (iSiteWidth <= 767) {
		$(".pagelist").detach().insertAfter(".maincopy h1:first");
	}
	if ($('.maincopy .teamtable').length){
	} else {
		if ($('.maincopy .mainhero').length){
			if (iSiteWidth <= 480) {
				$(".maincopy h1:first").detach().insertBefore(".maincopy .mainhero");
			} else {
				$(".maincopy h1:first").detach().insertAfter(".maincopy .mainhero");
			}
		}
	}
}
$(document).ready(function() {
	checkWindowSize();
	$(window).resize(checkWindowSize);
	$(window).resize(function() {
		checkWindowSize();
		$(window).resize(checkWindowSize);
	});
	$('p').filter(function () { return this.innerHTML == "" }).remove();
	$('.forminput').focus(function() {
    if (this.value == this.title) {
        $(this).val("");
    }
}).blur(function() {
    if (this.value == "") {
        $(this).val(this.title);
    }
});
	
});
</script>
<meta property="og:title" content="<?php echo $meta_title; ?>" />
<meta property="og:url" content="http://www.ionianweddings.co.uk<?php echo  $_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:image" content="http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif" />
<meta property="og:type" content="company" />
<meta property="og:site_name" content="Ionian Weddings" />
<meta property="fb:app_id" content="179394783501" />
<?php echo $add_header; ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2321991-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php echo $add_header; ?>
</head>

<body>
<div class="site">
<div class="header">
	<div class="logo">
		<h1><a href="<?php echo $site_url; ?>/" target="_self" title=""><img src="<?php echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </h1>
		<h2><img src="<?php echo $site_url; ?>/images/interface/i_exclusively_mediterranean_weddings.gif" alt="Exclusively Mediterranean Weddings" border="0" title="Exclusively Mediterranean Weddings" /></h2>
	</div>
	<div class="headersm">
		<ul>
			<li class="headersmitem"><a href="https://www.facebook.com/IonianWeddings" target="_blank" title="facebook"><img src="<?php echo $site_url; ?>/images/interface/b_header_facebook.gif" alt="facebook" border="0" title="facebook" /></a></li>
			<li class="headersmitem"><a href="https://twitter.com/#!/ionianweddings" target="_blank" title="twitter"><img src="<?php echo $site_url; ?>/images/interface/b_header_twitter.gif" alt="twitter" border="0" title="twitter" /></a></li>
			<li class="headersmitem"><a href="https://pinterest.com/ionianweddings/" target="_blank" title="Pinterest"><img src="<?php echo $site_url; ?>/images/interface/b_header_pinterest.gif" alt="Pinterest" border="0" title="Pinterest" /></a></li>
			<li class="headersmitem"><a href="http://ionianweddings.tumblr.com/" target="_blank" title="tumblr"><img src="<?php echo $site_url; ?>/images/interface/b_header_tumblr.gif" alt="tumblr" border="0" title="tumblr" /></a></li>
			<div class="clear"></div>
		</ul>
	</div>
	<div class="headernavigation">
		<div class="fblike">
			<div id="fb-root"></div>
			<script src="http://connect.facebook.net/en_US/all.js#appId=179394783501&amp;xfbml=1"></script>
			<fb:like href="http://www.ionianweddings.co.uk<?php echo  $_SERVER["REQUEST_URI"]; ?>" send="false" layout="button_count" width="0" show_faces="false" font=""></fb:like>
		</div>
		<!--<div style="clear:both; margin-bottom:10px;"></div>-->
		<h1>Call us today on 020 8894 1991 / 020 8898 9917</h1>
		<?php
			$main_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='165' and hide_page != 'Yes' ORDER BY displayorder");
			$main_row = $sql_command->results($main_result);
			
			echo "<ul>";
			foreach($main_row as $main_record) {
		?>
		<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/<?php echo stripslashes($main_record[1]); ?>/" target="_self" title="<?php echo stripslashes($main_record[0]); ?>"><?php echo stripslashes($main_record[0]); ?></a></li>
		<?php } ?>
		<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/search/" target="_self" title="Search">Search</a></li>
		<li class="clear"></li>
		</ul>
	</div>
	<div class="headervote">
		<a href="<?php echo $site_url; ?>/awards/" target="_self" title="Click to vote for us - British Travel Awards 2013 - www.britishtravelawards.com"><img src="<?php echo $site_url; ?>/images/interface/b_vote_2013.gif" alt="Click to vote for us - British Travel Awards 2013 - www.britishtravelawards.com" height="108" border="0" title="Click to vote for us - British Travel Awards 2013 - www.britishtravelawards.com" width="108" /></a>
	</div>
	<div class="clear"></div>
</div>
<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
		<li><a href="<?php echo $site_url; ?>/packages/" rel="ddsubmenu5" target="_self" title="Packages">Packages</a></li>
		<li><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
		<li><a href="<?php echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="News & Press">News & Press</a></li>
		<li><a href="<?php echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
		<li><a href="<?php echo $site_url; ?>/destinations/" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
		<li><a href="<?php echo $site_url; ?>/inspiration-ideas/" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>
<?php

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' and hide_page='No'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages") {

if($level1_record[1] == "Inspiration &amp; Ideas" ) {
?>
<ul id="ddsubmenu1" class="ddsubmenustyle">
<?php
} elseif($level1_record[1] == "Destinations" ) {
?>
<ul id="ddsubmenu2" class="ddsubmenustyle">
<?php
} elseif($level1_record[1] == "Types of Ceremony" ) {
?>
<ul id="ddsubmenu3" class="ddsubmenustyle">
	<?php
} elseif($level1_record[1] == "Packages" ) {
?>
	<ul id="ddsubmenu5" class="ddsubmenustyle">
		<?php
}
$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and hide_page='No' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and hide_page='No' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

    if(isset($level3_row[0][0])) {

    echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";
foreach($level3_row as $level3_record) {

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and hide_page='No' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

if(isset($level4_row[0][0])) {
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
?>
	</ul>
	<?php
}
}
?>
	<ul id="ddsubmenu4" class="ddsubmenustyle">
		<li><a href="<?php echo $site_url; ?>/latest-news/" target="_self" title="Latest News">Latest News</a></li>
		<li><a href="<?php echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
		<li><a href="<?php echo $site_url; ?>/ionian-weddings-blog/" target="_self" title="Ionian Weddings Blog">Ionian Weddings Blog</a></li>
		<li><a href="<?php echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
	</ul>
	<div class="maincontent">
		<?php
		
			if ($current_page == "index") {
		?>
			<div class="homecontent">
				<p>We are the UK's leading experts in Mediterannean weddings and our award winning approach means we will make your dreams come true.</p>
				<h1>We 'do' perfection.</h1>
			</div>
		<?php	
			}
		?>
		<div class="destinationnav">
			<ul id="destinationnav">
				<!--<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/" target="_self" title="Destinations">All Destinations</a></li>-->
				<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/greek-aegean-islands/" target="_self" title="Greek Aegean Islands">Greek Aegean Islands</a></li>
				<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/greek-ionian-islands/" target="_self" title="Greek Ionian Islands">Greek Ionian Islands</a></li>
				<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/cyprus/" target="_self" title="Cyprus">Cyprus</a></li>
				<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/malta/" target="_self" title="Malta">Malta</a></li>
				<li class="destinationnavitem"><a href="http://www.ionianweddings.co.uk/destinations/italy/" target="_self" title="Italy">Italy</a></li>
				<li class="clear"></li>
			</ul>
		</div>
		<div class="packagesnav">
			<ul id="packagesnav">
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/" target="_self" title="All Packages">All Packages</a></li>
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/greek-aegean-island-wedding-packages/" target="_self" title="Greek Aegean Island Wedding Packages">Greek Aegean Island Wedding Packages</a></li>
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/greek-ionian-island-wedding-packages/" target="_self" title="Greek Ionian Island Wedding Packages">Greek Ionian Island Wedding Packages</a></li>
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/cyprus-island-wedding-packages/" target="_self" title="Cyprus Island Wedding Packages">Cyprus Island Wedding Packages</a></li>
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/malta-wedding-packages/" target="_self" title="Malta Wedding packages">Malta Wedding packages</a></li>
				<li class="packagesnavitem"><a href="http://www.ionianweddings.co.uk/packages/italy-wedding-packages/" target="_self" title="Italy Wedding packages">Italy Wedding packages</a></li>
			</ul>
		</div>
		<?php
			if ($current_page == "index") {
				$gray_result = $sql_command->select($database_gray_feature,"title,description,link_name,link_url","ORDER BY id DESC");
				$gray_record = $sql_command->results($gray_result);
		?>
		<div class="sitefeatures top">
			<ul>
				<li class="sitefeaturelink sitefeaturecolor04">
					<h1><a href="<?php echo stripslashes($gray_record[3][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[3][2]); ?>"><?php echo stripslashes($gray_record[3][0]); ?></a></h1>
					<p><?php echo stripslashes($gray_record[3][1]); ?></p>
					<h2><a href="<?php echo stripslashes($gray_record[3][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[3][2]); ?>"><?php echo stripslashes($gray_record[3][2]); ?></a></h2>
				</li>
				<li class="sitefeaturelink sitefeaturecolor03">
					<h1><a href="<?php echo stripslashes($gray_record[2][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[2][2]); ?>"><?php echo stripslashes($gray_record[2][0]); ?></a></h1>
					<p><?php echo stripslashes($gray_record[2][1]); ?></p>
					<h2><a href="<?php echo stripslashes($gray_record[2][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[2][2]); ?>"><?php echo stripslashes($gray_record[2][2]); ?></a></h2>
				</li>
				<li class="sitefeaturelink sitefeaturecolor02">
					<h1><a href="<?php echo stripslashes($gray_record[1][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[1][2]); ?>"><?php echo stripslashes($gray_record[1][0]); ?></a></h1>
					<p><?php echo stripslashes($gray_record[1][1]); ?></p>
					<h2><a href="<?php echo stripslashes($gray_record[1][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[1][2]); ?>"><?php echo stripslashes($gray_record[1][2]); ?></a></h2>
				</li>
				<li class="sitefeaturelink sitefeaturecolor01">
					<h1><a href="<?php echo stripslashes($gray_record[0][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[0][2]); ?>"><?php echo stripslashes($gray_record[0][0]); ?></a></h1>
					<p><?php echo stripslashes($gray_record[0][1]); ?></p>
					<h2><a href="<?php echo stripslashes($gray_record[0][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[0][2]); ?>"><?php echo stripslashes($gray_record[0][2]); ?></a></h2>
				</li>
				<li class="clear"></li>
			</ul>
		</div>
		<?php
			}
		?>
		<div class="mainnav">
			<ul id="mainnav">
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/inspiration-ideas/" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/packages/greek-aegean-island-wedding-packages/" target="_self" title="Destinations">Destinations</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/types-of-ceremony/" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/latest-news/" target="_self" title="Latest News">Latest News</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/news-archive/" target="_self" title="News Archive">News Archive</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/ionian-weddings-blog/" target="_self" title="Ionian Weddings Blog">Ionian Weddings Blog</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/planning-advice/" target="_self" title="Planning Advice">Planning Advice</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/our-story/" target="_self" title="Our Story">Our Story</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/awards/" target="_self" title="Awards">Awards</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/our-team/" target="_self" title="Our Team">Our Team</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/faqs/" target="_self" title="FAQs">FAQs</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/contact-us/" target="_self" title="Contact Us">Contact Us</a></li>
				<li class="mainnavitem"><a href="http://www.ionianweddings.co.uk/search/" target="_self" title="Search">Search</a></li>
			</ul>
		</div>
<?php
	}
	
	
function imageHTML() {	
	
		global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $database_gray_feature, $current_page, $add_header, $_SERVER, $add_header;
	
	
	if(!$meta_title) { $meta_title = "Ionian Weddings"; }
	if(!$meta_keywords) { $meta_keywords = ""; }
	if(!$meta_description) { $meta_description = "Picture your perfect wedding and create a mood board Share it. Mail it. Tweet it. Pin it. Love it."; }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=0">
<meta name="msvalidate.01" content="981010E92BCC633C19C312945FA62582" />
<title><?php echo $meta_title; ?></title>
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<meta name="description" content="<?php echo $meta_description; ?>" />
<?php if($current_page == "notfound.php") { ?>
<meta name="robots" content="noindex, nofollow" />
<?php } else { ?>
<meta name="robots" content="index, follow" />
<?php } ?>
<meta property="og:title" content="<?php echo $meta_title; ?>" />
<meta property="og:url" content="http://www.ionianweddings.co.uk<?php echo $_SERVER["REQUEST_URI"]; ?>#" />
<meta property="og:type" content="company" />
<meta property="og:site_name" content="Ionian Weddings" />

<?php
	$site_url = "http://www.ionianweddings.co.uk";
	
	if (isset($_POST['imgids'])) {
		$imagearray = str_replace("\"","",$_POST['imgids']);
		
		$database_gallery = "gallery";
		$database_gallery_pics = "gallery_pictures";
		$database_gallery_mason = "gallery_masonary";
		
		$smimgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
										   $database_gallery_pics.id,
										   $database_gallery_pics.gallery_id,
										   $database_gallery_pics.imagename,
										   $database_gallery_pics.displayorder,
										   $database_gallery_pics.title,
										   $database_gallery_pics.description,
										   $database_gallery_pics.link",
										   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.img_id IN (".$imagearray.") AND $database_gallery_mason.meta_id != 10 ORDER BY $database_gallery_pics.weight DESC LIMIT 1");
		$smrec = $sql_command->results($smimgres);
		
		foreach($smrec as $smrow) {
			echo "<link rel=\"image_src\" type=\"image/jpeg\" href=\"".$site_url."/images/gallery/".$smrow[0]."/".$smrow[3]."\" />\n";
			echo "<meta property=\"og:image\" content=\"".$site_url."/images/gallery/".$smrow[0]."/".$smrow[3]."\" /> \n";
			echo "<meta property=\"og:image:secure_url\" content=\"".$site_url."/images/gallery/".$smrow[0]."/".$smrow[3]."\" />\n";
			
			
			$data = getimagesize($site_url."/images/gallery/".$smrow[0]."/".$smrow[3]);
			$width = $data[0];
			$height = $data[1];

			echo "<meta property=\"og:image:width\" content=\"".$width."\" />\n";
			echo "<meta property=\"og:image:height\" content=\"".$height."\" />\n";
		}
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/css/iw-image-ine.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/_jgm/dist/css/justifiedGallery.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/css/jcarousel.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/css/colorbox.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/css/iw-image-ine-responsive.css" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/_pgw/pgwmodal.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/js/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/js/jcarousel/jcarousel.responsive.js"></script>
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/mason.js"></script>-->
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/main.php"></script>-->
<script type="text/javascript" src="<?php echo $site_url;?>/_jgm/src/js/justifiedGallery.js"></script>
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/jquery.infinitescroll.js"></script>-->
<script type="text/javascript" src="<?php echo $site_url;?>/js/jquery.colorbox.js"></script>
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/jquery.touch-punch.min.js"></script>-->
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/iscroll.js?v3"></script>-->
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/touch.min.js"></script>-->
<!--<script type="text/javascript" src="<?php //echo $site_url;?>/js/js-lazy-load.js"></script>-->
<script language="JavaScript" src="<?php echo $site_url;?>/js/calendar_eu.js"></script>
<!--<script language="JavaScript" src="<?php //echo $site_url; ?>/js/js_rightclick.js"></script>-->
<link href="<?php echo $site_url;?>/css/calendar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url;?>/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="/_pgw/pgwmodal.css"/>
<script>
var items
/*function loaded() {
	items = new iScroll('gallery', { dnd:dragndrop, vScrollbar:true, desktopCompatibility:false });
}*/
$(document).ready(function() {
	
	$('#gallery').justifiedGallery({
		captions	:	false,
		lastrow		:	"nojustify",
		margins		:	0,
		randomize	:	false,
		rowHeight	:	160
	});

//	$("img.lazy").lazyload({
//		effect : "fadeIn",
//		failure_limit : 500,
//		skip_invisible : true,
//	   	threshold : 200
//	});

	$("#complete").click(function(){
		var elements = [];

		//iterates through each input field and pushes the name to the array
		$(".jcarousel .item img").each(function() {
		    var masonaryID = $(this).attr("id");
		    elements.push(masonaryID);
		});
		var masonaryID = elements.join(",");
		  $('input[name="imgids"]').val(masonaryID);
		  if (masonaryID.length>0) {
		  	$( "#continue" ).submit();
		  }
		  else {
			alert("Please add an image to the image basket by dragging an image to the bottom of the screen or clicking the love symbol on the desired image.");
			return false;  
		  }
	});

	$('#contactus a').click(function() {
		$.colorbox({
			href: '.sformheader',
			inline:true,
			width:"300px",
			fixed:true,
//			onClosed: function () {
//				$( "#sharenav").hide();
//				$( "#popnav").show();
//			}
		});
	});


	//Converts RGB color to HEX (including #)
	$.cssHooks.backgroundColor = {
		get: function(elem) {
			if (elem.currentStyle)
				var bg = elem.currentStyle["background-color"];
			else if (window.getComputedStyle)
				var bg = document.defaultView.getComputedStyle(elem,
					null).getPropertyValue("background-color");
			if (bg.search("rgb") == -1)
				return bg;
			else {
				bg = bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
				function hex(x) {
					return ("0" + parseInt(x).toString(16)).slice(-2);
				}
				return "#" + hex(bg[1]) + hex(bg[2]) + hex(bg[3]);
			}
		}
	};

		var colorclass = '';
		
		$('.swatchitem').click(function() {
			
			var colorclass = $(this).css('backgroundColor');
			
			colorclass	=	colorclass.replace('#', '');
			
			$(".box-container img.imageswapitem").fadeOut('slow');
			//$(".imageswap img.imageswapitem").css('display','none');
			$(".box-container img.imageswapitem."+colorclass).fadeIn('slow');
			//$(".imageswap img.imageswapitem."+colorclass).css('display','block');
			
		});
		$('.swatchreset').click(function() {
			$(".box-container img.imageswapitem").fadeOut('slow');
		});
	});
</script>
<?php echo $add_header; ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2321991-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
	
    <?php
	
	}

    function bottomHTML() {
        global $site_url, $current_page, $sql_command, $database_navigation,$database_gray_feature, $database_home_feature;


        $showcase_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='21' ORDER BY displayorder LIMIT 4");
        $showcase_row = $sql_command->results($showcase_result);

        foreach($showcase_row as $showcase_record) {
            list($weddingname,$weddinglocation) = explode(",",$showcase_record[0]);

            $showcase_html = "<li class=\"homefeaturenavigationlink\"><a href=\"$site_url/wedding-showcase/".stripslashes($showcase_record[1])."\" target=\"_self\" title=\"".stripslashes($showcase_record[0])."\">".stripslashes($weddingname)."</a></li>\n";
        }

        if($current_page == "index") {

            $result = $sql_command->select($database_home_feature,"description","ORDER BY id");
            $record = $sql_command->results($result);

            ?>
	</div>
	<div class="homefeatures">
		<div class="homefeature">
			<div class="homefeaturecontent"> <?php echo stripslashes($record[0][0]); ?> </div>
		</div>
		<!--<div class="homefeature">
			<div class="homefeaturecontent"> <?php echo stripslashes($record[1][0]); ?> </div>
		</div>-->
		<div class="homefeature2">
			<a href="/image-ine/" target="_blank" title="Design your dream wedding - Ionian Weddings Image-ine - Click here"><img src="/images/image-ine.jpg" alt="Design your dream wedding - Ionian Weddings Image-ine - Click here" border="0" title="Design your dream wedding - Ionian Weddings Image-ine - Click here" /></a>
		</div>
		<!--<div class="homefeature">
			<div class="homefeaturecontent"> <?php echo stripslashes($record[2][0]); ?> </div>
		</div>-->
		<div class="homefeature">
			<div class="homefeaturecontent"> <?php echo stripslashes($record[3][0]); ?> </div>
				<a class="twitter-timeline"  href="https://twitter.com/ionianweddings"  data-widget-id="362936785866739712">Tweets by @ionianweddings</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<div class="clear"></div>
	</div>
	<?php } else { 

$gray_result = $sql_command->select($database_gray_feature,"title,description,link_name,link_url","ORDER BY id DESC");
$gray_record = $sql_command->results($gray_result);

	?>
	<!--<div class="sitefeatures bottom">-->
	<div class="sitefeatures">
		<ul>
			<li class="sitefeaturelink sitefeaturecolor04">
				<h1><a href="<?php echo stripslashes($gray_record[3][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[3][2]); ?>"><?php echo stripslashes($gray_record[3][0]); ?></a></h1>
				<p><?php echo stripslashes($gray_record[3][1]); ?></p>
				<h2><a href="<?php echo stripslashes($gray_record[3][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[3][2]); ?>"><?php echo stripslashes($gray_record[3][2]); ?></a></h2>
			</li>
			<li class="sitefeaturelink sitefeaturecolor03">
				<h1><a href="<?php echo stripslashes($gray_record[2][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[2][2]); ?>"><?php echo stripslashes($gray_record[2][0]); ?></a></h1>
				<p><?php echo stripslashes($gray_record[2][1]); ?></p>
				<h2><a href="<?php echo stripslashes($gray_record[2][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[2][2]); ?>"><?php echo stripslashes($gray_record[2][2]); ?></a></h2>
			</li>
			<li class="sitefeaturelink sitefeaturecolor02">
				<h1><a href="<?php echo stripslashes($gray_record[1][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[1][2]); ?>"><?php echo stripslashes($gray_record[1][0]); ?></a></h1>
				<p><?php echo stripslashes($gray_record[1][1]); ?></p>
				<h2><a href="<?php echo stripslashes($gray_record[1][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[1][2]); ?>"><?php echo stripslashes($gray_record[1][2]); ?></a></h2>
			</li>
			<li class="sitefeaturelink sitefeaturecolor01">
				<h1><a href="<?php echo stripslashes($gray_record[0][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[0][2]); ?>"><?php echo stripslashes($gray_record[0][0]); ?></a></h1>
				<p><?php echo stripslashes($gray_record[0][1]); ?></p>
				<h2><a href="<?php echo stripslashes($gray_record[0][3]); ?>" target="_self" title="<?php echo stripslashes($gray_record[0][2]); ?>"><?php echo stripslashes($gray_record[0][2]); ?></a></h2>
			</li>
			<li class="clear"></li>
		</ul>
	</div>
	</div>
	<?php } ?>
	</div>
	<div class="footer">
	<div class="footericons">
		<ul>
			<li class="footericonslink"><a href="http://www.abta.com/home" target="_blank" title="ABTA"><img src="<?php echo $site_url; ?>/images/b_abta.png" alt="ABTA" border="0" title="ABTA" /></a></li>
			<li class="footericonslink"><a href="http://www.visitgreece.gr/" target="_blank" title="Greek National Tourist Information"><img src="<?php echo $site_url; ?>/images/b_greek_national_tourist_information.png" alt="Greek National Tourist Information" border="0" title="Greek National Tourist Information" /></a></li>
			<li class="footericonslink"><a href="http://www.visitmalta.com/" target="_blank" title="Malta Gozo Comino"><img src="<?php echo $site_url; ?>/images/b_malta_gozo_comino.png" alt="Malta Gozo Comino" border="0" title="Malta Gozo Comino" /></a></li>
			<!--<li class="footericonslink"><a href="http://www.facebook.com/pages/Ionian-Weddings/179394783501" target="_blank" title="Facebook"><img src="<?php echo $site_url; ?>/images/b_facebook.png" alt="Facebook" border="0" title="Facebook" /></a></li>-->
			<li class="clear"></li>
		</ul>
	</div>
	<div class="footercontent">
		<h1>This site is brought to you by Ionian Weddings Limited, the only site you need to make your wedding dreams come true</h1>
	</div>
	<div class="footernavigation">
	<ul>
		<li class="footernavigationlink">Copyright &copy; <?php echo date('Y') ;?>. All 2 rights reserved.</li>
		<li class="clear"></li>
	</ul>
	<?php
$main_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='166' and hide_page != 'Yes' ORDER BY displayorder");
$main_row = $sql_command->results($main_result);

echo "<ul>";
foreach($main_row as $main_record) {
?>
	<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/<?php echo stripslashes($main_record[1]); ?>/" target="_self" title="<?php echo stripslashes($main_record[0]); ?>"><?php echo stripslashes($main_record[0]); ?></a></li>
	<?php } ?>
	<li class="clear"></li>
</ul>
</div>
<div class="clear"></div>
</div>
</div>
</body>
</html>
<?php
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
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $current_page, $add_header, $login_record, $body_top;
	
	
	if(!$meta_title) { $meta_title = "Ionian Weddings"; }
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/jquery.tn3.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url; ?>/js/ddlevelsmenu.js" >

/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript" src="<?php echo $site_url; ?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/ckeditor/ckfinder/ckfinder.js"></script>
<script language="JavaScript" src="<?php echo $site_url;?>/js/calendar_eu.js"></script>
<link rel="stylesheet" href="<?php echo $site_url;?>/css/calendar.css">
<?php echo $add_header; ?>
</head>

<body>
<?php echo $body_top; ?>
<div class="site">
<div class="header">
	<div class="logo">
		<h1><a href="<?php echo $site_url; ?>/" target="_self" title=""><img src="<?php echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </h1>
		<h2><img src="<?php echo $site_url; ?>/images/interface/i_exclusively_mediterranean_weddings.gif" alt="Exclusively Mediterranean Weddings" border="0" title="Exclusively Mediterranean Weddings" /></h2>
	</div>
	<div class="headernavigation">
		<h1>Call us today on 020 8894 1991</h1>
		<?php
$main_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='165' and hide_page != 'Yes' ORDER BY displayorder");
$main_row = $sql_command->results($main_result);

echo "<ul>";
foreach($main_row as $main_record) {
?>
		<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/<?php echo stripslashes($main_record[1]); ?>/" target="_self" title="<?php echo stripslashes($main_record[0]); ?>"><?php echo stripslashes($main_record[0]); ?></a></li>
		<?php } ?>
		<li class="headernavigationlink"><a href="<?php echo $site_url; ?>/search/" target="_self" title="Search">Search</a></li>
		<li class="clear"></li>
		</ul>
	</div>
	<div class="clear"></div>
</div>
<div class="main">
<div id="ddtopmenubar" class="mattblackmenu">
	<ul>
		<li><a href="<?php echo $site_url; ?>/packages/" rel="ddsubmenu5" target="_self" title="Packages">Packages</a></li>
		<li><a href="<?php echo $site_url; ?>/testimonials/" target="_self" title="Testimonials">Testimonials</a></li>
		<li><a href="<?php echo $site_url; ?>/latest-news/" rel="ddsubmenu4" target="_self" title="News & Press">News & Press</a></li>
		<li><a href="<?php echo $site_url; ?>/types-of-ceremony/" rel="ddsubmenu3" target="_self" title="Types of Ceremony">Types of Ceremony</a></li>
		<li><a href="<?php echo $site_url; ?>/destinations/" rel="ddsubmenu2" target="_self" title="Destinations">Destinations</a></li>
		<li><a href="<?php echo $site_url; ?>/inspiration-ideas/" rel="ddsubmenu1" target="_self" title="Inspiration &amp; Ideas">Inspiration &amp; Ideas</a></li>
	</ul>
</div>
<script type="text/javascript">ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")</script>
<?php

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'  and hide_page='No'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Inspiration &amp; Ideas" or $level1_record[1] == "Destinations" or $level1_record[1] == "Types of Ceremony" or $level1_record[1] == "Packages") {

if($level1_record[1] == "Inspiration &amp; Ideas" ) {
?>
<ul id="ddsubmenu1" class="ddsubmenustyle">
<?php
} elseif($level1_record[1] == "Destinations" ) {
?>
<ul id="ddsubmenu2" class="ddsubmenustyle">
<?php
} elseif($level1_record[1] == "Types of Ceremony" ) {
?>
<ul id="ddsubmenu3" class="ddsubmenustyle">
	<?php
} elseif($level1_record[1] == "Packages" ) {
?>
	<ul id="ddsubmenu5" class="ddsubmenustyle">
		<?php
}

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."'  and hide_page='No' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."'  and hide_page='No' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

if($level3_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\">".stripslashes($level2_record[1])."</a><ul>\n";	
foreach($level3_row as $level3_record) {

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."'  and hide_page='No' ORDER BY displayorder");
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
?>
	</ul>
	<?php
}
}
?>
	<ul id="ddsubmenu4" class="ddsubmenustyle">
		<li><a href="<?php echo $site_url; ?>/latest-news/" target="_self" title="Latest News">Latest News</a></li>
		<li><a href="<?php echo $site_url; ?>/news-archive/" target="_self" title="News Archive">News Archive</a></li>
		<li><a href="<?php echo $site_url; ?>/ionian-weddings-blog/" target="_self" title="Ionian Weddings Blog">Ionian Weddings Blog</a></li>
		<li><a href="<?php echo $site_url; ?>/in-the-press/" target="_self" title="Ionian Weddings in the Press">Ionian Weddings in the Press</a></li>
	</ul>
	<div class="maincontent">
		<div class="maincopy">
			<div id="adminnav" style="float:left; width:160px; padding:10px; font-size:12px; margin-bottom:20px; color:#ccc;">
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User") { ?>
				<b>News / Offers</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-news.php">Add News</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-news.php">Update News</a></p>
				<b>Testimonials</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-testimonial.php">Add Testimonial</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-testimonial.php">Update Testimonial</a></p>
				<b>Features</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/update-features.php">Update Features</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-home-features.php">Update Home Features</a></p>
				<b>Form Results</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/wedding-questionnaire.php">Wedding Questionnaire</a><br />
					- <a href="<?php echo $site_url; ?>/admin/personal-consultations.php">Personal Consultations</a><br />
					- <a href="<?php echo $site_url; ?>/admin/book-a-callback.php">Book a Callback</a><br />
					- <a href="<?php echo $site_url; ?>/admin/feedback.php">Feedback</a><br />
					- <a href="<?php echo $site_url; ?>/admin/contact-us.php">Contact Us</a></p>
				<?php } ?>
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User" or $login_record[0] == "Website Admin User") { ?>
				<b>Navigation</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-page.php">Add Page</a><br  />
					- <a href="<?php echo $site_url; ?>/admin/update-page.php">Update Page</a><br  />
					- <a href="<?php echo $site_url; ?>/admin/display-order.php">Update Display Order</a><br  />
					- <a href="<?php echo $site_url; ?>/admin/other-meta-tags.php">Other Meta Tags</a></p>
				<b>Gallery</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-gallery.php">Add Gallery</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-gallery.php">Update Gallery</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-gallery-display.php">Gallery Display Order</a></p>
				<?php } ?>
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User") { ?>
				<b>Feature Packages</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-feature-package.php">Add Feature Package</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-feature-package.php">Update Feature Package</a></p>
				<b>Form Dropdown Lists</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/update-country.php">Update Country</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-preffered-time.php">Update Preffered time</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-type-of-ceremony.php">Update Type of ceremony</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-hear-about-us.php">Update Hear about us</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-plan-to-book.php">Update Plan to book</a></p>
				<b>Users</b>
				<p> - <a href="<?php echo $site_url; ?>/admin/add-user.php">Add Users</a><br />
					- <a href="<?php echo $site_url; ?>/admin/update-user.php">Update Users</a></p>
				<?php } ?>
				<p><a href="<?php echo $site_url; ?>/admin/logout.php">Logout</a></p>
			</div>
			<div style="float:left; width:710px; padding:10px 0px 10px 20px; margin-bottom:20px; font-size:12px;">
				<?php
	}


	function bottomHTML() {
	global $site_url, $current_page, $database_navigation, $sql_command;
	

	?>
			</div>
			<div style="clear:left;"></div>
		</div>
	</div>
	</div>
	<div class="footer">
	<div class="footericons">
		<ul>
			<li class="footericonslink"><a href="http://www.abta.com/home" target="_blank" title="ABTA"><img src="<?php echo $site_url; ?>/images/b_abta.png" alt="ABTA" border="0" title="ABTA" /></a></li>
			<li class="footericonslink"><a href="http://www.visitgreece.gr/" target="_blank" title="Greek National Tourist Information"><img src="<?php echo $site_url; ?>/images/b_greek_national_tourist_information.png" alt="Greek National Tourist Information" border="0" title="Greek National Tourist Information" /></a></li>
			<!--<li class="footericonslink"><a href="http://www.facebook.com/pages/Ionian-Weddings/179394783501" target="_blank" title="Facebook"><img src="<?php echo $site_url; ?>/images/b_facebook.png" alt="Facebook" border="0" title="Facebook" /></a></li>-->
			<li class="clear"></li>
		</ul>
	</div>
	<div class="footercontent">
		<h1>This site is brought to you by Ionian Weddings Limited, the only site you need to make your wedding dreams come true</h1>
	</div>
	<div class="footernavigation">
	<ul>
		<li class="footernavigationlink">Copyright &copy; <?php echo date('Y') ;?>. All rights reserved.</li>
		<li class="clear"></li>
	</ul>
	<?php
$main_result = $sql_command->select($database_navigation,"page_name,page_link","WHERE parent_id='166' and hide_page != 'Yes' ORDER BY displayorder");
$main_row = $sql_command->results($main_result);

echo "<ul>";
foreach($main_row as $main_record) {
?>
	<li class="footernavigationlink"><a href="<?php echo $site_url; ?>/<?php echo stripslashes($main_record[1]); ?>/" target="_self" title="<?php echo stripslashes($main_record[0]); ?>"><?php echo stripslashes($main_record[0]); ?></a></li>
	<?php } ?>
	<li class="clear"></li>
</ul>
</div>
<div class="clear"></div>
</div>
</div>
</body>
</html>
<?php
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




class oos_template {

	function topHTML() {
	global $site_url, $meta_title, $meta_keywords, $meta_description,  $sql_command, $database_navigation, $current_page, $add_header, $login_record, $hide_sidebar, $body_top;
	
	
	if(!$meta_title) { $meta_title = "Ionian Weddings"; }
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
<link href="<?php echo $site_url; ?>/css/iw_oos.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $site_url; ?>/skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/js/jquery.tn3.min.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/ckeditor/ckfinder/ckfinder.js"></script>
<script type="text/JavaScript" src="<?php echo $site_url;?>/js/calendar_eu.js"></script>
<script type="text/javascript" src="<?php echo $site_url;?>/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $site_url;?>/css/calendar.css">
<link rel="stylesheet" href="<?php echo $site_url;?>/css/jquery.autocomplete.css">
<?php echo $add_header; ?>
<style type="text/css">
.maincontent h2 {
	font-size:18px;
}
.maincontent h3 {
	font-size:14px;
}
a.buttonlink, a.buttonlink:link, a.buttonlink:visited, a.buttonlink:active {
	text-decoration:none;
}
a.buttonlink:hover {
	text-decoration:none;
}
</style>
</head>

<body>
<?php echo $body_top; ?>
<div class="site">
<div class="header">
	<div class="logo">
		<h1><a href="<?php echo $site_url; ?>/" target="_self" title=""><img src="<?php echo $site_url; ?>/images/interface/i_logo_ionian_weddings.gif" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></a> </h1>
		<h2><img src="<?php echo $site_url; ?>/images/interface/i_exclusively_mediterranean_weddings.gif" alt="Exclusively Mediterranean Weddings" border="0" title="Exclusively Mediterranean Weddings" /></h2>
	</div>
	<div class="headernavigation">
		<h1>Online Ordering System</h1>
	</div>
	<div class="clear"></div>
</div>
<div class="main">
	<div class="maincontent">
		<div class="maincopy" style="margin-top:20px;">
			<?php if($hide_sidebar != "Yes") { ?>
			<div id="adminnav" style="float:left; width:160px; padding:10px; font-size:12px; margin-bottom:20px; color:#ccc;">
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User" or $login_record[0] == "OOS Admin User") { ?>
				<b><a style="color: #000000;;" href="<?php echo $site_url; ?>/oos/search-submissions.php">Super Search</a></b><br />
				<br />
				<?php if($login_record[0] == "Super Admin User") { ?>
                <b>CSV Update</b>
					<p>- <a href="<?php echo $site_url; ?>/oos/csv-packages.php">Package Prices</a><br />
				- <a href="<?php echo $site_url; ?>/oos/csv-package-extras.php">Package Extras Prices</a><br />
					- <a href="<?php echo $site_url; ?>/oos/csv-menu-items.php">Menu Item Prices</a></p>
                    <?php } ?>
                  <b>Web Form Submissions</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/wedding-questionnaire.php">Wedding Questionnaire</a><br />
					- <a href="<?php echo $site_url; ?>/oos/personal-consultations.php">Personal Consultations</a><br />
					- <a href="<?php echo $site_url; ?>/oos/book-a-callback.php">Book a Call Back</a><br />
					- <a href="<?php echo $site_url; ?>/oos/contact-us.php">Contact Us</a><br />
                     <?php
                    	if ($_SESSION['admin_area_username']=="u1") {
					?>
						- <a href="<?php echo $site_url; ?>/oos/image-ine.php">Image-ine</a>
					<?php
						}
					?>
                    </p>
				<b>Customers & Orders</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/search.php">Search</a><br />
					- <a href="<?php echo $site_url; ?>/oos/add-client.php">Add Client</a><br />
					<?php } ?>
					- <a href="<?php echo $site_url; ?>/oos/manage-client.php">Manage Customer/Orders</a><br />
                      <?php
                    //if ($_SESSION['admin_area_username']=="u1") { 
					?>
					- <a href="<?php echo $site_url; ?>/oos/add-prospect.php">Add Prospects</a><br />
					- <a href="<?php echo $site_url; ?>/oos/manage-prospect.php">Manage Prospects</a><br />
                    <?php 
					//}
					?>
					<?php if($login_record[0] == "Super Admin User") { ?>
					- <a href="<?php echo $site_url; ?>/oos/remove-client.php">Delete Client/Prospect</a><br />
					<?php } ?>
					- <a href="<?php echo $site_url; ?>/oos/manage-supplier-po.php">Manage Supplier P/O's</a></p>
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User" or $login_record[0] == "OOS Admin User") { ?>
				<b>Outstanding</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/os-client-invoices.php">Client Invoices</a><br />
					- <a href="<?php echo $site_url; ?>/oos/os-supplier-purchase-orders.php">Supplier Purchase Orders</a></p>
				<b>Archive</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/archived-client-invoices.php">Client Invoices</a><br />
					- <a href="<?php echo $site_url; ?>/oos/archived-supplier-purchase-orders.php">Supplier Purchase Orders</a></p>
				<?php } ?>
				<?php if($login_record[0] == "Super Admin User") { ?>
				<b>Reports</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/hmrc-report-1.php">HMRC Report by Month</a><br />
					- <a href="<?php echo $site_url; ?>/oos/hmrc-report-2.php">HMRC Report by Date</a><br />
					- <a href="<?php echo $site_url; ?>/oos/destination-report.php">Revenue by Destination</a><br />
					<!--- <a href="<?php echo $site_url; ?>/oos/destination-report-2.php">Destination / Wedding Date</a><br />-->
					- <a href="<?php echo $site_url; ?>/oos/destination-report-3.php">Average Booking Value</a></p>
				<?php } ?>
				<?php if($login_record[0] == "Super Admin User" or $login_record[0] == "Admin User" or $login_record[0] == "OOS Admin User") { ?>
				<b>Ceremony Packages</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/ceremony-package.php">Add Ceremony Package</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-ceremony-package.php">Update Ceremony Package</a></p>
				<b>AI Packages</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-package.php">Add AI Package</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-package.php">Update AI Packages</a><br />
					- <a href="<?php echo $site_url; ?>/oos/included-in-package.php">Included in AI Packages</a></p>
				<b>Receptions Venues</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-venue.php">Add Venue</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-venue.php">Update Venue</a></p>
				<b>Ceremony Venues</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-ceremony-reception.php">Add Ceremony</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-ceremony-reception.php">Update Ceremony</a></p>
				<b>Menus</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-menu.php">Add Menu</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-menu.php">Update Menu</a></p>
				<b>Extra Categories</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-category.php">Add Category</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-category.php">Update Category</a></p>
				<b>Extras</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-extra.php">Add Extra</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-extra.php">Update Extra</a></p>
				<b>Suppliers</b>
				<p> - <a href="<?php echo $site_url; ?>/oos/add-supplier.php">Add Supplier</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-supplier.php">Update Supplier</a></p>
				<b>PDF Generator</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/pdf-generator.php">Add PDF</a><br />
					- <a href="<?php echo $site_url; ?>/oos/pdf-generator-update.php">Update PDF</a></p>
				<?php } ?>
				<?php if($login_record[0] == "Super Admin User") { ?>
				<b>Users</b>
				<p>- <a href="<?php echo $site_url; ?>/oos/add-user.php">Add Users</a><br />
					- <a href="<?php echo $site_url; ?>/oos/update-user.php">Update Users</a><br />
					- <a href="<?php echo $site_url; ?>/oos/activity-report.php">Activity Report</a><br />
					- <a href="<?php echo $site_url; ?>/oos-help/" target="_blank">Help</a>
					<?php } ?>
					<?php 
//<b>Actions</b>
//<p>
//- <a href="<?php echo $site_url; ?/>/oos/update-menu-prices.php">Update Menu Prices</a><br />
//- <a href="</? echo $site_url; ?/>/oos/update-extra-prices.php">Update Extra Prices</a></p>?>
				<p><a href="<?php echo $site_url; ?>/oos/logout.php">Logout</a></p>
			</div>
			<div style="float:left; width:710px; padding:10px 0px 10px 20px; margin-bottom:20px; font-size:12px;">
				<?php } else { ?>
				<div style="float:left; width:900px; padding:10px 0px 10px 20px; margin-bottom:20px; font-size:12px;">
					<?php } ?>
					<?php
	}


	function bottomHTML() {
	global $site_url, $current_page;
	

	?>
				</div>
				<div style="clear:left;"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php
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
