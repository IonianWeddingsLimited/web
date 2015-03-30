<?php

require_once "magpie/rss_fetch.inc";
$per_page = 20;


if(is_numeric($level2_name)) { $page = $level2_name; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;
$limitbottom++;

$url = 'http://ionianweddings.tumblr.com/rss';
$rss = fetch_rss($url);
$total_pages = 0;



foreach ($rss->items as $item ) {
$total_pages++;
$endat = $limitbottom + $per_page;

	$title = $item[title];
	$url   = $item[link];
	$des = $item[description];
     $date = date("d F Y", strtotime($item[pubdate]));

$des = str_replace("class=\"text_exposed_root text_exposed\"","",$des);
$des = str_replace("id=\"id_5048b454208122704378367\"","",$des);


//$des = preg_replace('/<a[^>]+>Continue reading.*?<\/a>/i','',$des);
if($total_pages >= $limitbottom and $total_pages < $endat) {
$print_blog .= "<div class=\"accordianlistitem\">
		<div class=\"accordianheader accordianlink\" >
			<h1>".$title."</h1>
			<h3>".$date."</h3>
			<div class=\"clear\"></div>
			<div onclick=\"return popitup('".$url."')\"><h4><a href=\"".$url."\">Read more &gt;</a></h4></div> 

			<div class=\"clear\"></div>
		</div>
		<div class=\"accordiancontent\">
			<div class=\"maincopy\">
".$des."

			</div>
		</div>
		<div class=\"clear\"></div>
	</div>";
}
}



$number_rows = $total_pages;

if($number_rows > $per_page) {
$totalpages = $number_rows / $per_page;

list($part1,$part2) = explode(".",$totalpages);

if ($part2) {
$newpage = $part1[0] + 1;
} else {
$newpage = $totalpages;
}

for($eachpage=1; $eachpage <= $newpage; $eachpage++) {
$pagehtml .= "<li class=\"paginationlink\"><a href=\"$site_url/ionian-weddings-blog/$eachpage\" target=\"_self\" title=\"$eachpage\">$eachpage</a></li>";
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
			<script type="text/javascript">
	$(document).ready(function() {
	<?php echo $add_javascript; ?>
	});
	
	
</script>
<div class="maincopy"> <?php echo stripslashes($level1_record[2]); ?>  </div>

<?php if($pagehtml) {
	
if($page != 1) {
$previd = $page-1;
$prev_link = "<li class=\"previouslink\"><a href=\"$site_url/ionian-weddings-blog/".$previd."\" target=\"_self\" title=\"&lt; Previous;\">&lt; Previous</a></li>";
}

if($page !=$newpage) {
$nextid = $page+1;
$next_link = "<li class=\"nextlink\"><a href=\"$site_url/ionian-weddings-blog/".$nextid."\" target=\"_self\" title=\"Next &gt;\">Next &gt;</a></li>";
}
	
?>
<div class="pagination paginationoutdent">
	<ul>
		<?php echo $prev_link; ?>
		<?php echo $pagehtml; ?>
		<?php echo $next_link; ?>
		<li class="clear"></li>
	</ul>
</div>
<?php } ?>


<div class="accordianlist" style="margin-left:-14px; margin-right:-14px;">


<?php echo $print_blog; ?>
</div>
