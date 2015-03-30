<?
require ("_includes/settings.php");
include ("_includes/function.database.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$site_url = "http://www.ionianweddings.co.uk";

$content .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
\n";
	

$content .= "<url>
  <loc>".$site_url."/</loc>
  <changefreq>weekly</changefreq>
  <priority>1.00</priority>
</url>\n";


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0' and deleted='No'  and feature_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[2] == "navigation_header" or $level1_record[2] == "navigation_footer") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$content .= "<url>
  <loc>".$site_url."/".stripslashes($level2_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.95</priority>
</url>\n";
$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$content .= "<url>
  <loc>".$site_url."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.90</priority>
</url>\n";

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and deleted='No' and feature_id='0' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
$content .= "<url>
  <loc>".$site_url."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.85</priority>
</url>\n";
}
}
}

} else {

$content .= "<url>
  <loc>".$site_url."/".stripslashes($level1_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>1.00</priority>
</url>\n";

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	

$content .= "<url>
  <loc>".$site_url."/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.95</priority>
</url>\n";
$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' and deleted='No' and feature_id='0'  ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$content .= "<url>
  <loc>".$site_url."/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.90</priority>
</url>\n";

$level4_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level3_record[0])."' and deleted='No' and feature_id='0' ORDER BY displayorder");
$level4_row = $sql_command->results($level4_result);

foreach($level4_row as $level4_record) {
$content .= "<url>
  <loc>".$site_url."/".stripslashes($level1_record[2])."/".stripslashes($level2_record[2])."/".stripslashes($level3_record[2])."/".stripslashes($level4_record[2])."/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.85</priority>
</url>\n";
}
}
}
}
}
	


$fh = fopen("sitemap.xml", 'w') or die("can't open file");
fwrite($fh, $content."\n</urlset>");
fclose($fh);