<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);



$result = $sql_command->select($database_supplier_details,"id,company_name","");
$row = $sql_command->results($result);

foreach($row as $record) {
$record[1] = str_replace("<p>", "", $record[1]);
$record[1] = str_replace("</p>", "", $record[1]);
$record[1] = ereg_replace("[^A-Za-z]", "", $record[1]);


$totalcharacters = strlen($record[1]);
$middleend = $totalcharacters / 2;

$first = $record[1][0];
$middle = $record[1][$middleend];
$last = $record[1][$totalcharacters-1];

if($record[0] < 10) {
$record[0] = "0".$record[0];
}

$code =  $record[0] . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "S";

$sql_command->update($database_supplier_details,"code='".addslashes($code)."'","id='".addslashes($record[0])."'");

}


$result = $sql_command->select($database_package_info,"id,iw_name","");
$row = $sql_command->results($result);

foreach($row as $record) {
$record[1] = str_replace("<p>", "", $record[1]);
$record[1] = str_replace("</p>", "", $record[1]);
$record[1] = ereg_replace("[^A-Za-z]", "", $record[1]);


$totalcharacters = strlen($record[1]);
$middleend = $totalcharacters / 2;

$first = $record[1][0];
$middle = $record[1][$middleend];
$last = $record[1][$totalcharacters-1];

if($record[0] < 10) {
$record[0] = "0".$record[0];
}

$code =  $record[0] . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "P";

$sql_command->update($database_package_info,"code='".addslashes($code)."'","id='".addslashes($record[0])."'");

}




$result = $sql_command->select($database_package_extras,"id,product_name","");
$row = $sql_command->results($result);

foreach($row as $record) {
$record[1] = str_replace("<p>", "", $record[1]);
$record[1] = str_replace("</p>", "", $record[1]);
$record[1] = ereg_replace("[^A-Za-z]", "", $record[1]);


$totalcharacters = strlen($record[1]);
$middleend = $totalcharacters / 2;

$first = $record[1][0];
$middle = $record[1][$middleend];
$last = $record[1][$totalcharacters-1];

if($record[0] < 10) {
$record[0] = "0".$record[0];
}

$code =  $record[0] . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "E";

$sql_command->update($database_package_extras,"code='".addslashes($code)."'","id='".addslashes($record[0])."'");

}


$result = $sql_command->select($database_menu_options,"id,menu_name","");
$row = $sql_command->results($result);

foreach($row as $record) {

$record[1] = str_replace("<p>", "", $record[1]);
$record[1] = str_replace("</p>", "", $record[1]);
$record[1] = ereg_replace("[^A-Za-z]", "", $record[1]);


$totalcharacters = strlen($record[1]);
$middleend = $totalcharacters / 2;

$first = $record[1][0];
$middle = $record[1][$middleend];
$last = $record[1][$totalcharacters-1];

if($record[0] < 10) {
$record[0] = "0".$record[0];
}

$code =  $record[0] . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "M";

$sql_command->update($database_menu_options,"code='".addslashes($code)."'","id='".addslashes($record[0])."'");

}


?>