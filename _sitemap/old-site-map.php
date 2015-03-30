<style>
	.sitemap {
		margin-top: 0px;
		margin-left: 30px;
		margin-right: 30px;
		margin-bottom: 0px;
		padding-top: 0px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
	}
	.sitemap h3 {
		border-bottom: dotted 1px #C08827;
		color: #C08827;
		font-size: 14px;
		margin-top: 0px;
		margin-left: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		padding-top: 5px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 5px;
	}
	.sitemap h3 a:visited {
		color: #ffffff;
	}
	.sitemap h3 a:link {
		color: #ffffff;
	}
	.sitemap h3 a:hover {
		color: #C08827;
	}
	.sitemap h3 a {
		color: #ffffff;
		text-decoration: none;
	}
	.sitemap ul {
		margin-top: 0px;
		margin-left: 20px;
		margin-right: 0px;
		margin-bottom: 0px;
		padding-top: 0px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
	}
	.sitemap ul li {
		color: #CC6633;
		margin-top: 3px;
		margin-left: 0px;
		margin-right: 0px;
		margin-bottom: 3px;
		padding-top: 0px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
	}
	.sitemap ul li a:visited {
		color: #C08827;
	}
	.sitemap ul li a:link {
		color: #C08827;
	}
	.sitemap ul li a:hover {
		color: #ffffff;
	}
	.sitemap ul li a {
		color: #C08827;
		text-decoration: none;
	}
	.sitemap ul li ul {
		margin-top: 0px;
		margin-left: 20px;
		margin-right: 0px;
		margin-bottom: 0px;
		padding-top: 0px;
		padding-left: 0px;
		padding-right: 0px;
		padding-bottom: 0px;
	}
	.sitemap ul li ul li a:visited {
		color: #CC6633;
	}
	.sitemap ul li ul li a:link {
		color: #CC6633;
	}
	.sitemap ul li ul li a:hover {
		color: #ffffff;
	}
	.sitemap ul li ul li a {
		color: #CC6633;
		text-decoration: none;
	}
</style>

	<div class="pageform" style="margin-left:-0px; margin-right:-0px;">
		<div class="formheader">
		<?php echo stripslashes($level1_record[2]); ?> 
		</div>
		<div class="sitemap">
		<?


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' and id!='115' and hide_page='No' ORDER BY displayorder");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	

echo "<h3><a href=\"$site_url/".stripslashes($level1_record[2])."/\" target=\"_self\" title=\"".stripslashes($level1_record[1])."\">".stripslashes($level1_record[1])."</a></h3><ul>";


$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and hide_page='No' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and hide_page='No' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

if($level3_row[0][0]) {
echo "<li><a href=\"$site_url/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/\" target=\"_self\" title=\"".stripslashes($level2_record[1])."\"><strong>".stripslashes($level2_record[1])."</strong></a><ul>\n";	
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
?>
		</div>
	</div>



