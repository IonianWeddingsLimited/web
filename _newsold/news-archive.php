<?

$per_page = 20;

if($level2_name == "article") {
	
$result = $sql_command->select($database_news,"title,tagline,content,imagename,timestamp","WHERE short_url='".addslashes($level3_name)."'");
$record = $sql_command->result($result);	

if($record[3]) { $image_line = "<img title=\"".stripslashes($record[0])."\" src=\"$site_url/images/page/news/".stripslashes($record[3])."\" alt=\"".stripslashes($record[0])."\" border=\"0\" align=\"right\"/>"; } else { $image_line = ""; }
?>
<div class="maincopy">

<h1><?php echo stripslashes($record[0]); ?></h1>
<?php echo $image_line; ?>
<?php echo stripslashes($record[2]); ?>
<div style="clear:both;"></div>
</div>
<?

} else {

if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;


$last12months = $time - 31556926;

$result = $sql_command->select($database_news,"title,tagline,content,imagename,timestamp,short_url,external_link","WHERE type='News' and timestamp < $last12months ORDER BY timestamp DESC LIMIT $limitbottom,$per_page");
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("d F Y",$record[4]);

if($record[2]) {
if($record[3]) { $image_line = "<div class=\"accordianimage\"><img title=\"".stripslashes($record[0])."\" src=\"$site_url/images/page/news/".stripslashes($record[3])."\" alt=\"".stripslashes($record[0])."\" border=\"0\" /></div>"; } else { $image_line = ""; }
$class_name = "accordianheader accordianlink";
$read_more = "<div onclick=\"return popitup('$site_url/latest-news/article/$record[5]')\"><h4><a href=\"$site_url/latest-news/article/$record[5]\">Read more &gt;</a></h4></div>";
$content_html = "<div class=\"accordiancontent\">
			<div class=\"maincopy\">
			$image_line
				".stripslashes($record[2])."
			</div>
		</div>";
} else {
$class_name = "accordianlink";
$read_more = "";
$content_html = "";
}
	
if($record[6] and $record[6] != "http://") {
$header_text = "<h1>".stripslashes($record[0])."</h1>";
$onclickaction = "onclick=\"window.open('$record[6]',target='_blank')\" style=\"cursor:pointer;\"";
$content_html = "<div class=\"$add_bit\" style=\"padding:0px;\"></div>";
$read_more = "<h4>Read more &gt;</h4>";
} else {
$header_text = "<h1>".stripslashes($record[0])."</h1>";	
$onclickaction = "";
}
			
$list_html .= "<div class=\"accordianlistitem\">
		<div class=\"$class_name\" $onclickaction>
			$header_text
			<h3>$dateline</h3>
			<div class=\"clear\"></div>
			<h2>".stripslashes($record[1])."</h2>
			$read_more 

			<div class=\"clear\"></div>
		</div>
		$content_html
		<div class=\"clear\"></div>
	</div>";
}






$number_rows = $sql_command->count_rows($database_news,"id","type='News' and timestamp < $last12months ORDER BY timestamp DESC");

if($number_rows > $per_page) {
$totalpages = $number_rows / $per_page;

list($part1,$part2) = explode(".",$totalpages);

if ($part2) {
$newpage = $part1[0] + 1;
} else {
$newpage = $totalpages;
}

for($eachpage=1; $eachpage <= $newpage; $eachpage++) {
$pagehtml .= "<li class=\"paginationlink\"><a href=\"$site_url/news-archive/$eachpage\" target=\"_self\" title=\"$eachpage\">$eachpage</a></li>";
}
}




?>
<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=500,width=1000,scrollbars=Yes');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
<div class="maincopy"> <?php echo stripslashes($level1_record[2]); ?>  </div>

<?php if($pagehtml) {
	
if($page != 1) {
$previd = $page-1;
$prev_link = "<li class=\"previouslink\"><a href=\"$site_url/news-archive/".$previd."\" target=\"_self\" title=\"&lt; Previous;\">&lt; Previous</a></li>";
}

if($page !=$newpage) {
$nextid = $page+1;
$next_link = "<li class=\"nextlink\"><a href=\"$site_url/news-archive/".$nextid."\" target=\"_self\" title=\"Next &gt;\">Next &gt;</a></li>";
}
	
?>
<div class="pagination"  style="margin-left:-14px; margin-right:-14px; width:968px;">
	<ul>
		<?php echo $prev_link; ?>
		<?php echo $pagehtml; ?>
		<?php echo $next_link; ?>
		<li class="clear"></li>
	</ul>
</div>
<?php } ?>


<div class="accordianlist" style="margin-left:-14px; margin-right:-14px;">
<?php echo $list_html; ?>
</div>
<?php } ?>