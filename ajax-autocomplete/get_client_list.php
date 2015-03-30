<?php
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$q = strtolower($_GET["q"]);
if (!$q) return;

$result = $sql_command->select("$database_clients,clients_options",
								   "DISTINCT $database_clients.id,
								   $database_clients.title,
								   $database_clients.firstname,
								   $database_clients.lastname,
								   $database_clients.destination,
								   $database_clients.groom_title,
								   $database_clients.groom_firstname,
								   $database_clients.groom_surname,
								   $database_clients.iwcuid,
								   $database_clients.wedding_date",
								   "WHERE $database_clients.deleted			=		'No'
								   	AND (
										$database_clients.title				like	'%".addslashes($q)."%' OR
										$database_clients.firstname			like	'%".addslashes($q)."%' OR
										$database_clients.lastname			like	'%".addslashes($q)."%' OR
										$database_clients.groom_title		like	'%".addslashes($q)."%' OR
										$database_clients.groom_firstname	like	'%".addslashes($q)."%' OR
										$database_clients.groom_surname		like	'%".addslashes($q)."%' OR
										$database_clients.iwcuid			like	'%".addslashes($q)."%'
									)
									AND $database_clients.id				=	clients_options.client_id 
									AND clients_options.client_option		=	'client_type'
									AND (clients_options.option_value		!=	'Prospect'
									OR clients_options.option_value			!=	'Imageine')");
$row = $sql_command->results($result);

foreach($row as $record) {
	$cid	=	$record[0];
	$iwcuid	=	$record[8];
	$cname	=	$record[8]." - ".$record[1]." ".$record[2]." ".$record[3]." & ".$record[5]." ".$record[6]." ".$record[7];
	echo	"$cname|$cid|$iwcuid\n";
}
?>
No more clients found
