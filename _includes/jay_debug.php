<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?

require ("settings.php");
require ("function.templates.php");
include ("function.database.php");
include ("function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$success = $sql_command->select("clients","id,mailing_list,deleted,imageine","ORDER BY id ASC");
$suc = $sql_command->results($success);

$id=1;
foreach ($suc as $s){
	$active = ($s[2]==='Yes') ? 'Cancelled' :  'Active';

	$ins = $sql_command->update("clients_options",
								"client_id='".addslashes($s[0])."',
								client_option='client_type',
								value='".addslashes($active)."'",
								"id='".addslashes($id)."'"); 	
	echo $ins."<br />";
	$id++;

	$ins = $sql_command->update("clients_options",
								"client_id='".addslashes($s[0])."',
								client_option='default_currency',
								value='Not Applicable'",
								"id='".addslashes($id)."'"); 	
	echo $ins."<br />";
	$id++;

	$ins = $sql_command->update("clients_options",
								"client_id='".addslashes($s[0])."',
								client_option='mailing_pref',
								value='".addslashes($s[1])."'",
								"id='".addslashes($id)."'"); 	
	echo $ins."<br />";
	$id++;
	
	$ins = $sql_command->update("clients_options",
								"client_id='".addslashes($s[0])."',
								client_option='imageine',
								value='".addslashes($s[3])."'",
								"id='".addslashes($id)."'"); 	
	echo $ins."<br />";
	$id++;
}

?>
</body>
</html>