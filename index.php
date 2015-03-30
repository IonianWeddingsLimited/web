<?php
require ("_includes/settings.php");
require ("_includes/function.templates.php");
include ("_includes/function.database.php");
include ("_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

// Get Templates
$get_template = new main_template();


if(eregi("index.php",$_SERVER["REQUEST_URI"])) {
header("Location: http://www.ionianweddings.co.uk");
exit();
}

$current_page = "index";

$meta_result = $sql_command->select($database_meta_tags,"meta_title,meta_keyword,meta_des","WHERE id='1'");
$meta_record = $sql_command->result($meta_result);

$meta_title = stripslashes($meta_record[0]);
$meta_key = stripslashes($meta_record[1]);
$meta_des = stripslashes($meta_record[2]);
					 
					 
$gray_result = $sql_command->select($database_gray_feature,"title,description,link_name,link_url","ORDER BY id DESC");
$gray_record = $sql_command->results($gray_result);


$get_template->topHTML();
?>
<div class="homehero">
	<!--<div class="awards"><a href="/awards/" target="_self" title="Click to vote for us - British Travel Awards 2012 - www.britishtravelawards.com"><img src="/images/page/b_click_to_vote_for_us_british_travel_awards_2012.png" alt="Click to vote for us - British Travel Awards 2012 - www.britishtravelawards.com" border="0" class="png" height="120" title="Click to vote for us - British Travel Awards 2012 - www.britishtravelawards.com" width="120" /></a></div>-->
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
</div>	<script type="text/javascript">
		$(document).ready(function() {
			var tn1 = $('.hero').tn3({
				skinDir:"skins",
				width:935,
				height:433,
				imageClick:"url",
				autoplay:true,
				//delay: 2000,
				image:{
					crop:false,
					transitions:[{
						//type:"grid",
						//duration: 1000,
						//gridX:15,
						//gridY:10,
						//diagonalStart:"tl"
						type: "fade",
						easing: "easeInQuad",
						duration: 900
					}]
				},
				external:[{
				origin:"xml",
				url:"/xml/xml_homepage.xml"
				}]
			});
		});
	</script>
	<div class="hero"></div>

</div>
<?php
$get_template->bottomHTML();
$sql_command->close();
