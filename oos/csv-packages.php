<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_POST["a"] == "Upload CSV") {
	
$one_newname = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES["import_file"]["name"]));
$one_filename = $_FILES["import_file"]["tmp_name"];


if(!ereg(".csv",$one_newname)) { 
$error .= "File must be a .csv<br>"; 
}

if(!eregi('packages', $one_newname)) { $error .= "Please upload packages csv file<br>"; }
	

if($error) {
$get_template->topHTML();
$get_template->errorHTML("CSV Update - Packages","Oops!","$error","Link","oos/csv-packages.php");
$get_template->bottomHTML();
$sql_command->close();
}


move_uploaded_file($one_filename, "$base_directory/oos/csvimportbackup/$one_newname");

$html .="<div style=\"float:left; width:70px;\"><strong>ID</strong></div>
<div style=\"float:left; width:420px;\"><strong>Name</strong></div>
<div style=\"float:left; width:100px;\"><strong>Net</strong></div>
<div style=\"float:left; width:100px;\"><strong>Gross</strong></div>
<div class=\"clear\"></div>";

if (($handle = fopen("csvimportbackup/$one_newname", "r")) !== FALSE) {
while (($array = fgetcsv($handle, filesize("csvimportbackup/$one_newname"), ",")) !== FALSE) {

$display_net = $array[4];
$display_gross = $array[5];

$array[4] = ereg_replace("[^0-9\.-]", "", $array[4]);
$array[5] = ereg_replace("[^0-9\.-]", "", $array[5]);

if($array[0] and $array[1] and $array[2] and $array[3] and $array[4] and $array[5]) {
$record[3] = str_replace("''",'"',stripslashes($record[3]));
$record[4] = str_replace("''",'"',stripslashes($record[4]));
$record[5] = str_replace("''",'"',stripslashes($record[5]));


if (eregi('P', $array[0])) {
	
$array[0] = str_replace("P","",$array[0]);
$html .="<div style=\"float:left; width:70px;\">$array[0]C</div>
<div style=\"float:left; width:420px;\">$array[3]</div>
<div style=\"float:left; width:100px;\">$display_net</div>
<div style=\"float:left; width:100px;\">$display_gross</div>
<div class=\"clear\"></div>";

//$sql_command->update($database_package_info,"iw_name='".addslashes($array[3])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_info,"cost='".addslashes($array[4])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_info,"iw_cost='".addslashes($array[5])."'","id='".addslashes($array[0])."'");
} elseif (eregi('C', $array[0])) {
$array[0] = str_replace("C","",$array[0]);

$html .="<div style=\"float:left; width:70px;\">$array[0]C</div>
<div style=\"float:left; width:420px;\">$array[3]</div>
<div style=\"float:left; width:100px;\">$display_net</div>
<div style=\"float:left; width:100px;\">$display_gross</div>
<div class=\"clear\"></div>";

//$sql_command->update($database_package_extras,"product_name='".addslashes($array[3])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_extras,"cost='".addslashes($array[4])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_extras,"iw_cost='".addslashes($array[5])."'","id='".addslashes($array[0])."'");
}


}

}
fclose($handle);
}
	
unlink("$base_directory/oos/csvimportbackup/$one_newname");
	
$get_template->topHTML();
?>
<h1>Packages Updated</h1>

<p>The packages database has now been updated</p>
<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/csv-packages.php'"></p>
<hr />
<?php echo $html; ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["a"] == "Download CSV") {

if($_POST["island_id"] == "none" or !$_POST["island_id"]) {
$get_template->topHTML();
$get_template->errorHTML("CSV Update - Packags","Oops!","Please select an island","Link","oos/csv-packages.php");
$get_template->bottomHTML();
$sql_command->close();
}

$found= "No";

$page_result = $sql_command->select($database_navigation,"page_name","WHERE id='".addslashes($_POST["island_id"])."'");
$page_record = $sql_command->result($page_result);
	

$result = $sql_command->select("$database_packages,$database_navigation","
							   $database_packages.id,
							   $database_packages.package_name,
							   $database_navigation.page_name","
							   WHERE 
							   $database_packages.island_id='".$_POST["island_id"]."' AND
							   $database_packages.island_id=$database_navigation.id  AND  
							   $database_packages.deleted='No' 
							   ORDER BY $database_packages.package_name");
$row = $sql_command->results($result);

$found= "No";
	
$csv_string .= '"Record","Island","Package","Package Item","Net","Gross"'; 	
$csv_string .= "\n";

foreach($row as $record) {
$found= "Yes";
	
$option_result = $sql_command->select($database_package_info,"*","WHERE package_id='".addslashes($record[0])."'  and deleted='No' ORDER BY iw_name");
$option_row = $sql_command->results($option_result);

foreach($option_row as $option_record) {
$record[0] = str_replace('"',"''",stripslashes($record[0]));
$record[1] = str_replace('"',"''",stripslashes($record[1]));
$record[2] = str_replace('"',"''",stripslashes($record[2]));
$record[3] = str_replace('"',"''",stripslashes($record[3]));
$record[4] = str_replace('"',"''",stripslashes($record[4]));
$option_record[2] = str_replace('"',"''",stripslashes($option_record[2]));
$option_record[3] = str_replace('"',"''",stripslashes($option_record[3]));
$option_record[4] = str_replace('"',"''",stripslashes($option_record[4]));

if($option_record[5] == "Euro") {
$type = "€ ";
} elseif($option_record[5] == "Pound") {
$type = "£ ";
} else { 
$type = "";
}

$csv_string .= '"' .$option_record[0]. 'P","' .$record[2] . '","' .$record[1]. '","' .stripslashes($option_record[2]). '","'.$type.' ' .$option_record[3]. '","'.$type.' ' .$option_record[4].  '"'; 	
$csv_string .= "\n";
}
}






$result = $sql_command->select("$database_package_extras,$database_category_extras,$database_navigation","
							   $database_package_extras.id,
							   $database_package_extras.product_name,
							   $database_package_extras.cost,
							   $database_package_extras.iw_cost,
							   $database_package_extras.code,
							   $database_category_extras.category_name,
							   $database_navigation.page_name,
							   $database_package_extras.currency","
							   WHERE 
							   $database_package_extras.category_id=$database_category_extras.id AND
							   $database_package_extras.island_id='".$_POST["island_id"]."' AND
							   $database_package_extras.island_id=$database_navigation.id  AND  
							   $database_package_extras.deleted='No' and 
							   $database_category_extras.id=59 
							   ORDER BY $database_category_extras.category_name, $database_package_extras.product_name");
$row = $sql_command->results($result);



foreach($row as $record) {
$found= "Yes";
	
$record[0] = str_replace('"',"''",stripslashes($record[0]));
$record[1] = str_replace('"',"''",stripslashes($record[1]));
$record[2] = str_replace('"',"''",stripslashes($record[2]));
$record[3] = str_replace('"',"''",stripslashes($record[3]));
$record[4] = str_replace('"',"''",stripslashes($record[4]));
$record[5] = str_replace('"',"''",stripslashes($record[5]));
$record[6] = str_replace('"',"''",stripslashes($record[6]));
$record[7] = str_replace('"',"''",stripslashes($record[7]));

$record[1] = str_replace("<p>","",$record[1]);
$record[1] = str_replace("</p>"," ",$record[1]);
$record[1] = str_replace("\n\r"," ",$record[1]);
$record[1] = str_replace("\n"," ",$record[1]);
$record[1] = str_replace("\r"," ",$record[1]);
$record[1] = ltrim($record[1]);
$record[1] = trim($record[1]);

if($record[7] == "Euro") {
$type = "€ ";
} elseif($record[7] == "Pound") {
$type = "£ ";
} else { 
$type = "";
}

$csv_string .= '"' .$record[0]. 'C","' .$record[6] . '","-","' .stripslashes($record[1]). '","'.$type.' ' .stripslashes($record[2]). '","'.$type.' ' .$record[3].  '"'; 	
$csv_string .= "\n";
}


if($found == "No") {
$csv_string .= 'No Items Found'; 	
$csv_string .= "\n";	
}

$filename = stripslashes($page_record[0])."-packages-".date('M-j-Y-G-i').".csv";
$fp = fopen("../../csvbackup/".$filename,"w");
fwrite($fp,$csv_string);
fclose($fp);

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\";");
header("Content-length: " . strlen($csv_string));



echo $csv_string;

$sql_command->close();


} else {

$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}
$add_header = "<script type=\"text/javascript\">

function checkupload() {
return confirm(\"Please confirm file upload\");
}

</script>";

$get_template->topHTML();
?>
<h1>CSV Update - Packages</h1>

<h3>Download Prices</h3>
<form method="post" action="csv-packages.php">
<div style="float:left; padding:5px;"><strong>Island</strong></div>
<div style="float:left; padding:5px;"><select name="island_id"><option value="none">- - - Please select an island - - -</option><?php echo $nav_list; ?></select></div>
<div class="clear"></div>
<p style="margin-top:10px;"><input type="submit" name="a" value="Download CSV" /></p>
</form>

<p><hr /></p>

<h3>Upload Prices</h3>

<form method="post" action="csv-packages.php" enctype="multipart/form-data">
<div style="float:left; padding:5px;"><strong>CSV</strong></div>
<div style="float:left; padding:5px;"><input type="file" name="import_file"></div>
<div class="clear"></div>
<p style="margin-top:10px;"><input type="submit" name="a" value="Upload CSV" onclick="return checkupload();"/></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

}
?>