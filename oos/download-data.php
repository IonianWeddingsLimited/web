<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$bclient=$_GET["bclient"];
//if($bclient!=1) {
	$clients_q = "select c.id, c.iwcuid,COALESCE(wq.id,\"N/A\") as wedding,COALESCE(pc.id,\"N/A\") as personal,COALESCE(cu.id,\"N/A\") as contactus,COALESCE(cb.id,\"N/A\") as callback FROM clients as c LEFT OUTER JOIN info_wedding_questionnaire as wq ON (wq.bride_firstname = c.firstname AND wq.bride_lastname = c.lastname AND wq.bride_email = c.email) OR (wq.groom_firstname = c.groom_firstname AND wq.groom_lastname = c.groom_surname AND wq.groom_email = c.groom_email) LEFT OUTER JOIN	info_personal_consultations as pc ON (pc.firstname = c.firstname AND pc.lastname = c.lastname AND pc.email = c.email) OR (pc.firstname = c.groom_firstname AND pc.lastname = c.groom_surname AND pc.email = c.groom_email) LEFT OUTER JOIN info_contactus as cu ON (cu.firstname = c.firstname AND cu.lastname = c.lastname AND cu.email = c.email) OR (cu.firstname = c.groom_firstname AND cu.lastname = c.groom_surname AND cu.email = c.groom_email) LEFT OUTER JOIN info_bookacallback as cb ON (cb.firstname = c.firstname AND cb.lastname = c.lastname AND cb.email = c.email) OR (cb.firstname = c.groom_firstname AND cb.lastname = c.groom_surname AND cb.email = c.groom_email)";
	
	$clients_r = mysql_query($clients_q) or die(mysql_error());
	
	$wedding_ids = array();
	$personal_ids = array();
	$contact_ids = array();
	$callback_ids = array();
	$iwcuid_ids = array();
	$client_ids = array();
		
	while ($row = @mysql_fetch_row($clients_r)) {
		$client_ids = $row[0];
		$iwcuid_ids = $row[1];
		if ($row[2]!="N/A") { $wedding_ids[] = $row[2]; }
		if ($row[3]!="N/A") { $personal_ids[] = $row[3]; }
		if ($row[4]!="N/A") { $contact_ids[] = $row[4]; }
		if ($row[5]!="N/A") { $callback_ids[] = $row[5]; }
	}
	
	$wq_ids = implode(",",$wedding_ids);
	$pc_ids = implode(",",$personal_ids);
	$cu_ids = implode(",",$contact_ids);
	$cb_ids = implode(",",$callback_ids);
	
	$wq_filter_line = $pc_filter_line = $cu_filter_line = $cb_filter_line = "";
	

	if ($bclient==1) {
		$wq_filter_line = "$database_info_wedding_questionnaire.id IN (".addslashes($wq_ids).") OR $database_info_wedding_questionnaire.id NOT IN (".addslashes($wq_ids).")";
		$pc_filter_line = "$database_info_personal_consultations.id IN (".addslashes($pc_ids).") OR $database_info_personal_consultations.id NOT IN (".addslashes($pc_ids).")";
		$cu_filter_line = "$database_info_contactus.id IN (".addslashes($cu_ids).") OR $database_info_contactus.id NOT IN (".addslashes($cu_ids).")";
		$cb_filter_line = "$database_info_bookacallback.id IN (".addslashes($cb_ids).") OR $database_info_bookacallback.id NOT IN (".addslashes($cb_ids).")";
	}
	if ($bclient==2) {
		$wq_filter_line = "$database_info_wedding_questionnaire.id IN (".addslashes($wq_ids).")";
		$pc_filter_line = "$database_info_personal_consultations.id IN (".addslashes($pc_ids).")";
		$cu_filter_line = "$database_info_contactus.id IN (".addslashes($cu_ids).")";
		$cb_filter_line = "$database_info_bookacallback.id IN (".addslashes($cb_ids).")";
	}
	if ($bclient==3) {
		$wq_filter_line = "$database_info_wedding_questionnaire.id NOT IN (".addslashes($wq_ids).")";
		$pc_filter_line = "$database_info_personal_consultations.id NOT IN (".addslashes($pc_ids).")";
		$cu_filter_line = "$database_info_contactus.id NOT IN (".addslashes($cu_ids).")";
		$cb_filter_line = "$database_info_bookacallback.id NOT IN (".addslashes($cb_ids).")";
	}
//}
if(strlen($_GET["from"]) > 5) {

list($day,$month,$year) = explode("-",$_GET["from"]);
$from = mktime(0, 0, 0, $month, $day, $year);


if(strlen($_GET["to"]) > 5) {
list($day,$month,$year) = explode("-",$_GET["to"]);
$to = mktime(23, 59, 59, $month, $day, $year);
} else {
$to = $time;	
}
$where_line = "WHERE timestamp > ".addslashes($from)." and timestamp < ".addslashes($to);
}


if($_GET["data"] == "questionnaire") {




$date = date("d-m-Y",$time);
$file_name = "wedding-questionnaire-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'Bride First Name', 'Bride Last Name', 'Groom First Name', 'Groon Last Name', 'Bride Email', 'Bride Telephone', 'Groom Email', 'Groom Telephone', 'Island', 'Wedding date', 'Source', 'Budget', 'Comments', 'Callback', 'Residence'));




if($_GET["destination"] > 0) {
if($where_line) {
$where_line .= " and $database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id ";
} else {
$where_line = " WHERE $database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id ";	
}

$add_des_line = ",$database_questionaire_destinations";
$where_line .= "  AND $database_questionaire_destinations.island_id='".addslashes($_GET["destination"])."'";	
}
$wq_filter_line = (strlen($where_line)>1) ? " AND ".$wq_filter_line : "WHERE ".$wq_filter_line;
$result = $sql_command->select("$database_info_wedding_questionnaire$add_des_line","$database_info_wedding_questionnaire.id,
							   $database_info_wedding_questionnaire.bride_firstname,
							   $database_info_wedding_questionnaire.bride_lastname,
							   $database_info_wedding_questionnaire.bride_country,
							   $database_info_wedding_questionnaire.bride_email,
							   $database_info_wedding_questionnaire.groom_firstname,
							   $database_info_wedding_questionnaire.groom_lastname,
							   $database_info_wedding_questionnaire.destination_other,
							   $database_info_wedding_questionnaire.estimated_date_of_wedding,
							   $database_info_wedding_questionnaire.hearaboutus,
							   $database_info_wedding_questionnaire.estimated_budget,
							   $database_info_wedding_questionnaire.comments,
							   $database_info_wedding_questionnaire.callback,
							   $database_info_wedding_questionnaire.callback_date,
							   $database_info_wedding_questionnaire.callback_time,
							   $database_info_wedding_questionnaire.timestamp,
							   $database_info_wedding_questionnaire.bride_telephone,
							   $database_info_wedding_questionnaire.groom_telephone,
							   $database_info_wedding_questionnaire.groom_email",$where_line.$wq_filter_line."  GROUP BY $database_info_wedding_questionnaire.id ORDER BY $database_info_wedding_questionnaire.id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$date = $record[8];
if(!$date) { $date = ""; }
$date2 = @date("d-m-Y",$record[15]);
if(!$date2) { $date2 = ""; }

$couple_names = stripslashes($record[1])." ".stripslashes($record[2]);
$couple_names2 = stripslashes($record[5])." ".stripslashes($record[6]);

$BrideFirstName	=	stripslashes($record[1]);
$BrideLastName	=	stripslashes($record[2]);
$GroomFirstName	=	stripslashes($record[5]);
$GroomLastName	=	stripslashes($record[6]);


if($record[12] == "Yes") {
$callback = stripslashes($record[12])." - ".stripslashes($record[13])." ".stripslashes($record[14]);
} else {
$callback = "No";
}



$destination_check_result = $sql_command->select("$database_questionaire_destinations,$database_navigation","$database_questionaire_destinations.id,
												 $database_navigation.page_name
												 ","WHERE $database_questionaire_destinations.questionaire_id='".addslashes($record[0])."' AND 
												 $database_questionaire_destinations.island_id=$database_navigation.id");
$destination_check_row = $sql_command->results($destination_check_result);

$count_row=0;
$island_html = "";
foreach($destination_check_row as $destination_record) {
if($count_row == 0) {
$island_html .= $destination_record[1];
} else {
$island_html .= " - ".$destination_record[1];
}
$count_row++;
}


if($record[7] and $island_html) {
$island_html .= " - ".$record[7];
} elseif($record[7]) {
$island_html .= $record[7];	
}

if(!$island_html) {  $island_html = " - "; }

fputcsv($output, array($record[0], $date2, $BrideFirstName,$BrideLastName,$GroomFirstName,$GroomLastName, $record[4], $record[16], $record[18], $record[17], $island_html, $date, stripslashes($record[9]), stripslashes($record[10]), stripslashes($record[11]), $callback, stripslashes($record[3])));	
}




} elseif($_GET["data"] == "personal") {




$date = date("d-m-Y",$time);
$file_name = "personal-consultation-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'First Name', 'Last Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers', 'Preferred Day', 'Preferred Time', 'Callback'));


$pc_filter_line = (strlen($where_line)>1) ? " AND ".$pc_filter_line : "WHERE ".$pc_filter_line;
$result = $sql_command->select($database_info_personal_consultations,"id,
firstname,
lastname,
email,
telephone,
address_1,
address_2,
address_3,
town,
country,
postcode,
comments,
timestamp,
getlatestoffers,
preferred_day,
preferred_time,
callback,
county",$where_line.$pc_filter_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$firstname	=	stripslashes($record[1]);
$lastname	=	stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0],$date,$firstname,$lastname,stripslashes($record[3]),stripslashes($record[4]),stripslashes($record[5]),stripslashes($record[6]),stripslashes($record[7]),stripslashes($record[8]),stripslashes($record[17]),stripslashes($record[9]),stripslashes($record[10]),stripslashes($record[11]),stripslashes($record[13]),stripslashes($record[14]),stripslashes($record[15]),stripslashes($record[16])));
}
}elseif($_GET["data"] == "bookacallback") {




$date = date("d-m-Y",$time);
$file_name = "bookacallback-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'First Name', 'Last Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers', 'Preferred Day', 'Preferred Time'));


$cb_filter_line = (strlen($where_line)>1) ? " AND ".$cb_filter_line : "WHERE ".$cb_filter_line;
$result = $sql_command->select($database_info_bookacallback,"id,
firstname,
lastname,
email,
telephone,
address_1,
address_2,
address_3,
town,
country,
postcode,
comments,
timestamp,
getlatestoffers,
preferred_day,
preferred_time,
county",$where_line.$cb_filter_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$firstname	=	stripslashes($record[1]);
$lastname	=	stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0],$date, $firstname,$lastname,stripslashes($record[3]),stripslashes($record[4]),stripslashes($record[5]),stripslashes($record[6]),stripslashes($record[7]),stripslashes($record[8]),stripslashes($record[17]),stripslashes($record[9]),stripslashes($record[10]),stripslashes($record[11]),stripslashes($record[13]),stripslashes($record[14]),stripslashes($record[15])));
}
}elseif($_GET["data"] == "contactus") {




$date = date("d-m-Y",$time);
$file_name = "contactus-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'Subject', 'First Name', 'Last Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers'));


$cu_filter_line = (strlen($where_line)>1) ? " AND ".$cu_filter_line : "WHERE ".$cu_filter_line;
$result = $sql_command->select($database_info_contactus,"id,
firstname,
lastname,
email,
telephone,
address_1,
address_2,
address_3,
town,
country,
postcode,
comments,
timestamp,
getlatestoffers,
subject,
county",$where_line.$cu_filter_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
	

$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$firstname	=	stripslashes($record[1]);
$lastname	=	stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0], $date, stripslashes($record[14]), $firstname, $lastname, stripslashes($record[3]), stripslashes($record[4]), stripslashes($record[5]), stripslashes($record[6]), stripslashes($record[7]), stripslashes($record[8]), stripslashes($record[17]), stripslashes($record[9]), stripslashes($record[10]), stripslashes($record[11]), stripslashes($record[13])));
}
}

?>