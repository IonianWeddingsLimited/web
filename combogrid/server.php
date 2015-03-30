<?php
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");

$page		=	$_GET['page']; // get the requested page
$limit		=	$_GET['rows']; // get how many rows we want to have into the grid
$sidx		=	$_GET['sidx']; // get index row - i.e. user click to sort
$sord		=	$_GET['sord']; // get the direction
$searchTerm	=	$_GET['searchTerm'];

if(!$sidx) $sidx =1;

if ($searchTerm=="") {
	$searchTerm="%";
} else {
	$searchTerm = "%" . $searchTerm . "%";
}

//$dbhost		=	$database_host;
//$dbuser		=	$database_username;
//$dbpassword	=	$database_password;
//$database	=	$database_name;

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

// connect to the database
//$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());

//mysql_select_db($database) or die("Error conecting to db.");
//$result = mysql_query("SELECT COUNT(*) AS count FROM title WHERE name like '$searchTerm'");
//$row = mysql_fetch_array($result,MYSQL_ASSOC);
$result = $sql_command->select("$database_clients,clients_options",
							   "count(*)",
							   "WHERE $database_clients.deleted='No'
							    AND (
								   $database_clients.firstname = '$searchTerm' OR
								   $database_clients.lastname = '$searchTerm' OR
								   $database_clients.groom_firstname = '$searchTerm' OR
								   $database_clients.groom_surname = '$searchTerm' OR
								   $database_clients.iwcuid = '$searchTerm'
								   )
								AND $database_clients.id = clients_options.client_id 
								AND clients_options.client_option = 'client_type'
								AND clients_options.option_value!='Prospect'
								AND clients_options.option_value!='Imageine' 
								ORDER BY $database_clients.firstname,$database_clients.lastname");
$row = $sql_command->results($result);

$count = $row['count'];

if( $count > 0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
//if($total_pages!=0) {
//	$SQL = "SELECT * FROM title WHERE name like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
//} else {
//	$SQL = "SELECT * FROM title WHERE name like '$searchTerm'  ORDER BY $sidx $sord";
//}
//$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
if($total_pages != 0) {
	$result = $sql_command->select("$database_clients,clients_options",
								   "$database_clients.id,
								   $database_clients.title,
								   $database_clients.firstname,
								   $database_clients.lastname,
								   $database_clients.destination,
								   $database_clients.groom_title,
								   $database_clients.groom_firstname,
								   $database_clients.groom_surname,
								   $database_clients.iwcuid,
								   $database_clients.wedding_date",
								   "WHERE $database_clients.deleted='No'
								   AND (
								   $database_clients.firstname = '$searchTerm' OR
								   $database_clients.lastname = '$searchTerm' OR
								   $database_clients.groom_firstname = '$searchTerm' OR
								   $database_clients.groom_surname = '$searchTerm' OR
								   $database_clients.iwcuid = '$searchTerm'
								   )
									AND $database_clients.id = clients_options.client_id 
									AND clients_options.client_option = 'client_type'
									AND clients_options.option_value!='Prospect'
									AND clients_options.option_value!='Imageine'
									ORDER BY $sidx $sord LIMIT $start , $limit");
									//ORDER BY $database_clients.firstname,$database_clients.lastname");
} else {
	$result = $sql_command->select("$database_clients,clients_options",
							   "$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.destination,
							   $database_clients.groom_title,
							   $database_clients.groom_firstname,
							   $database_clients.groom_surname,
							   $database_clients.iwcuid,
							   $database_clients.wedding_date",
							   "WHERE $database_clients.deleted='No'
								AND (
								   $database_clients.firstname = '$searchTerm' OR
								   $database_clients.lastname = '$searchTerm' OR
								   $database_clients.groom_firstname = '$searchTerm' OR
								   $database_clients.groom_surname = '$searchTerm' OR
								   $database_clients.iwcuid = '$searchTerm'
								   )
								AND $database_clients.id = clients_options.client_id 
								AND clients_options.client_option = 'client_type'
								AND clients_options.option_value!='Prospect'
								AND clients_options.option_value!='Imageine' 
								ORDER BY $sidx $sord");
								//ORDER BY $database_clients.firstname,$database_clients.lastname");
}

$row = $sql_command->results($result);

$response->page = $page;
$response->total = $total_pages;
$response->records = $count;
$i=0;
while($row = mysql_fetch_array($result)) {
/*
    $response->rows[$i]['id']=$row[id];
    $response->rows[$i]['cell']=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
*/
    $response->rows[$i]['id']=$row['id'];
    $response->rows[$i]['name']=$row['name'];
    $response->rows[$i]['author']=$row['author'];
    //$response->rows[$i]=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
    $i++;
}        
echo json_encode($response);

?>
