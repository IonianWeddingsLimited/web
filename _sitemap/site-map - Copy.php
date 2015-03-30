<style type="text/css">
.maincopy ul {
	padding:0;
	margin:0;
}

.maincopy ul ul {
	padding:0;
	margin:0;
	margin-left:10px;
}

.maincopy ul ul ul {
	padding:0;
	margin:0;
	margin-left:10px;
}

</style>
<div class="maincopy"> <?php echo stripslashes($level1_record[2]); ?> 
<?


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' ORDER BY displayorder");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	

echo "<h3><a href=\"$site_url/".stripslashes($level1_record[2])."/\" target=\"_self\" title=\"".stripslashes($level1_record[1])."\">".stripslashes($level1_record[1])."</a></h3><ul>";


$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

if($level3_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\"><strong>".stripslashes($level2_record[1])."</strong></a><ul>\n";	
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
?>
</div>




