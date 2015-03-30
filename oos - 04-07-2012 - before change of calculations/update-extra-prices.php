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

if($_POST["action"] == "Import") {
	
$one_newimage_name = ereg_replace("[^A-Za-z0-9\.\-]", "", strtolower($_FILES["import_file"]["name"]));
$one_image_filename = $_FILES["import_file"]["tmp_name"];


if(!ereg(".csv",$one_newimage_name)) { 
$error .= "File must be a .csv<br>"; 
}

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Update Extra Prices","Oops!","$error","Link","admin/update-extra-prices.php");
$get_template->bottomHTML();
$sql_command->close();
}


move_uploaded_file($one_image_filename, "$base_directory/oos/csvimportbackup/$one_newimage_name");



if (($handle = fopen("csvimportbackup/$one_newimage_name", "r")) !== FALSE) {
while (($array = fgetcsv($handle, filesize("csvimportbackup/$one_newimage_name"), ",")) !== FALSE) {

if($array[0] and $array[1] and $array[2] and $array[3] and $array[4] and $array[5] and $array[6]) {
$record[4] = str_replace("''",'"',stripslashes($record[4]));
$record[5] = str_replace("''",'"',stripslashes($record[5]));
$record[6] = str_replace("''",'"',stripslashes($record[6]));
$record[7] = str_replace("''",'"',stripslashes($record[7]));
$sql_command->update($database_package_extras,"product_name='".addslashes($array[4])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_extras,"cost='".addslashes($array[5])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_extras,"iw_cost='".addslashes($array[6])."'","id='".addslashes($array[0])."'");
$sql_command->update($database_package_extras,"notes='".addslashes($array[7])."'","id='".addslashes($array[0])."'");
}

}
fclose($handle);
}
	
unlink("$base_directory/oos/csvimportbackup/$one_newimage_name");
	

$get_template->topHTML();
?>
<h1>Package Extras Updated</h1>

<p>The package extras database has now been update</p>

<p><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/update-extra-prices.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();


} elseif($_GET["a"] == "export") {
	
$result = $sql_command->select("$database_package_extras,$database_category_extras,$database_supplier_details,$database_navigation","
							   $database_package_extras.id,
							   $database_package_extras.product_name,
							   $database_package_extras.cost,
							   $database_package_extras.iw_cost,
							   $database_package_extras.notes,
							   $database_category_extras.category_name,
							   $database_supplier_details.company_name,
							   $database_navigation.page_name","
							   WHERE 
							   $database_package_extras.category_id=$database_category_extras.id AND
							   $database_package_extras.supplier_id=$database_supplier_details.id AND
							   $database_package_extras.island_id=$database_navigation.id 
							   ORDER BY $database_navigation.page_name, $database_category_extras.category_name, $database_supplier_details.company_name, $database_package_extras.product_name");
$row = $sql_command->results($result);


$csv_string .= '"ID","Island","Supplier","Category","Extra Name","Cost","IW Cost","Notes"'; 	
$csv_string .= "\n";

foreach($row as $record) {
$record[0] = str_replace('"',"''",stripslashes($record[0]));
$record[1] = str_replace('"',"''",stripslashes($record[1]));
$record[2] = str_replace('"',"''",stripslashes($record[2]));
$record[3] = str_replace('"',"''",stripslashes($record[3]));
$record[4] = str_replace('"',"''",stripslashes($record[4]));
$record[5] = str_replace('"',"''",stripslashes($record[5]));
$record[6] = str_replace('"',"''",stripslashes($record[6]));
$record[7] = str_replace('"',"''",stripslashes($record[7]));

$csv_string .= '"' .$record[0]. '","' .$record[7] . '","' .$record[6] . '","' .$record[5]. '","' .$record[1]. '","' .$record[2]. '","' .$record[3].  '","' .$record[4]. '"'; 	
$csv_string .= "\n";
}

header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"export-extra_details.csv\";");
header("Content-length: " . strlen($csv_string));



echo $csv_string;

$sql_command->close();


} else {

$get_template->topHTML();
?>
<h1>Update Extra Prices</h1>


<h3>Export CSV File</h3>
<p><a href="<?php $site_url; ?>/oos/update-extra-prices.php?a=export">Download the .CSV file.</a></p>
<p>Editable fields are <strong>Extra Name</strong> / <strong>Cost</strong> / <strong>IW Cost</strong> and  <strong>Notes</strong></p>

<form action="<?php echo $site_url; ?>/oos/update-extra-prices.php" method="POST" enctype="multipart/form-data">

<h3>Import the updated CSV File</h3>
<p><input type="file" name="import_file"/></p> 
<p><input type="submit" name="action" value="Import"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

}
?>