<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$iDestinationID = 0;

if(strlen($_GET["from"]) > 5) {

	list($day,$month,$year) = explode("-",$_GET["from"]);
	$from = mktime(0, 0, 0, $month, $day, $year);


	if(strlen($_GET["to"]) > 5) {
		list($day,$month,$year) = explode("-",$_GET["to"]);
		$to = mktime(23, 59, 59, $month, $day, $year);
	} else {
		$to = $time;	
	}
	
	$strSQL1	=	"wedding_date >= ".addslashes($from)." and wedding_date <= ".addslashes($to)." and";
}

$iDestinationID	=	$_GET["destination"];

if ($iDestinationID <> 0) {
	$strSQL2	=	" destination = '" .addslashes($iDestinationID). "' and";
}

if($_GET["data"] == "client") {

	$where_line = "WHERE ".$strSQL1." ".$strSQL2." deleted = 'no'";

	$date = date("d-m-Y",$time);
	$file_name = "client-data-".$date.".csv";
	
	
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$file_name);
	
	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');
	
	// output the column headings
	//fputcsv($output, array('#','IWCUID','Name', 'Date Of Birth', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Mobile', 'Fax', 'Wedding Date', 'Mailing List'));
	fputcsv($output, array('#', 'IWCUID', 'Bride First Name', 'Bride Last Name', 'Groom First Name', 'Groom Last Name', 'Bride Email', 'Bride Telephone', 'Groom Email', 'Groom Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Mobile', 'Fax', 'Wedding Date', 'Mailing List', 'Destination'));



	$result = $sql_command->select($database_clients,"*",$where_line);
	$row = $sql_command->results($result);


	foreach($row as $record) {
		$record = str_replace("\"","'",$record);
		$record = str_replace("\'","'",$record);
		
		$result = $sql_command->select($database_clients,"*",$where_line);
		$row = $sql_command->results($result);
	
		$BrideFirstName	=	stripslashes($record[2]);
		$BrideLastName	=	stripslashes($record[3]);
		$GroomFirstName	=	stripslashes($record[22]);
		$GroomLastName	=	stripslashes($record[23]);
		$dobdate = @date("d-m-Y",$record[15]);
		$weddingdate = @date("d-m-Y",$record[17]);
		fputcsv($output, array($record[0],$record[19],$BrideFirstName,$BrideLastName,$GroomFirstName,$GroomLastName,$record[11],$record[12],$record[26],$record[27],$record[4],$record[5],$record[6],$record[7],$record[8],$record[9],$record[10],$record[13],$record[14],$weddingdate,$record[16],$record[18]));
	}
}
?>