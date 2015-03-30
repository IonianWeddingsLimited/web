<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$per_page = 20;

$get_template = new main_template();


list($blank,$blank,$year,$page) = explode("/", $_SERVER["REQUEST_URI"]);

if(preg_match('/our-story/i', $_SERVER["REQUEST_URI"])) {
header ('HTTP/1.1 301 Moved Permanently');
header("Location: http://www.ionianweddings.co.uk/our-story/");
exit();
}

if(!$year) { $year = date("Y",$time); }

if($page) { $page = $page; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;


$current_year = date("Y",$time);;

if($current_year == $year) {
$year_number_rows = $sql_command->count_rows($database_testimonials,"id","year='".addslashes($current_year)."'");
if($year_number_rows == 0) {
$year = $year - 1;
$show_current_year = "No";
}
}

$meta_result = $sql_command->select($database_meta_tags,"meta_title,meta_keyword,meta_des","WHERE year='".addslashes($year)."'");
$meta_record = $sql_command->result($meta_result);

$meta_title = stripslashes($meta_record[0]);
$meta_key = stripslashes($meta_record[1]);
$meta_des = stripslashes($meta_record[2]);
				
$level1_result = $sql_command->select($database_navigation,"id,page_name,html,meta_title,meta_key,meta_des","WHERE page_link='testimonials'");
$level1_record = $sql_command->result($level1_result);




$result = $sql_command->select($database_testimonials,"testimonial,id","WHERE year='".addslashes($year)."' ORDER BY timestamp DESC");
$row = $sql_command->results($result);
$number_rows = $sql_command->count_rows($database_testimonials,"id","year='".addslashes($year)."' ");
$totalpages = $number_rows / 2;
list($part1,$part2) = explode(".",$totalpages);


$current_row = 0;
foreach($row as $record) {


if($current_row == $part1) { $list .= "</div><div class=\"columnright\">"; }


$list .= "<a name=\"$record[1]\"></a><div class=\"testimonialitem\">
".stripslashes($record[0])."
</div>\n";
$current_row++;	
}







//$number_rows = $sql_command->count_rows($database_testimonials,"id","year='".addslashes($year)."' ORDER BY timestamp DESC");

//if($number_rows > $per_page) {

//$totalpages = $number_rows / $per_page;

//list($part1,$part2) = explode(".",$totalpages);

//if ($part2) {
//$newpage = $part1[0] + 1;
//} else {
//$newpage = $totalpages;
//}

//for($eachpage=1; $eachpage <= $newpage; $eachpage++) {
//if($eachpage == $page) {
//$pagehtml .= "( $eachpage ) ";
//} else {
//$pagehtml .= "[ <a href=\"$site_url/testimonials/$year/$eachpage\" class=\"red\">$eachpage</a> ] ";
//}
//}
//}


//if($pagehtml) {
//$showpageinfo = "<h1>See more $year Testimonials</h1><p><b>Page:</b> $pagehtml</p>";
//} else {
//$showpageinfo = "";
//}











$start_year = 2007;
$start_year2 = 2008;
$end_year = date("Y",$time);

while($end_year > $start_year) {
if($end_year == $start_year2) {
$year_list .= "<a href=\"$site_url/testimonials/$end_year\" target=\"_self\" title=\"$end_year\">$end_year</a>";
} else {
$year_list .= "<a href=\"$site_url/testimonials/$end_year\" target=\"_self\" title=\"$end_year\">$end_year</a> | ";	
}
$end_year--;
}

$get_template->topHTML();
?><div class="maincopy">

<div class="pagesplit">
<div class="columnleft">
<h1>Testimonials <?php echo $year; ?></h1>
</div>
			<div class="columnright">
				<!-- Need to datadrive links for each year -->
				<h1>Testimonials <?php echo $year_list; ?></h1>

			</div>
			<div class="clear"></div>
            
<div class="columnleft"><?
echo $list;
?></div>
<div class="clear"></div>
				

		</div>               
	   <?php              
 $feature_result = $sql_command->select($database_feature_packages,"title,the_link,the_image,description","WHERE id='6'");
$feature_record = $sql_command->result($feature_result);

if($feature_record[0]) {
?>
<style type="text/css">
.test_feature p {
	font-size:11px;
}
</style>
<div class="test_feature" style="background-color:#f9eed8; padding:20px; margin-left:-10px; margin-right:-10px; margin-bottom:10px; font-size:10px;">
<a href="<?php echo stripslashes($feature_record[1]); ?>"><img src="<?php echo $site_url; ?>/images/package-feature/<?php echo stripslashes($feature_record[2]); ?>" alt="<?php echo stripslashes($feature_record[0]); ?>" border="0" title="<?php echo stripslashes($feature_record[0]); ?>" align="right" style="margin-left:20px;"/></a>

<h1 style="font-size:12px;"><a href="<?php echo stripslashes($feature_record[1]); ?>"><?php echo stripslashes($feature_record[0]); ?></a></h1>
<?php echo stripslashes($feature_record[3]); ?>

</div>	</div>
<?
}               
    ?>  
			
		
	<?
$get_template->bottomHTML();
$sql_command->close();


?>