<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);





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
fputcsv($output, array('#', 'Enquiry date', 'Brides Name', 'Groom Name', 'Email', 'Bride Telephone', 'Groom Telephone', 'Island', 'Wedding date', 'Source', 'Budget', 'Comments', 'Callback', 'Residence'));




if($_GET["destination"] > 0) {
if($where_line) {
$where_line .= " and $database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id ";
} else {
$where_line = " WHERE $database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id ";	
}

$add_des_line = ",$database_questionaire_destinations";
$where_line .= "  AND $database_questionaire_destinations.island_id='".addslashes($_GET["destination"])."'";	
}



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
							   $database_info_wedding_questionnaire.groom_telephone",$where_line."  GROUP BY $database_info_wedding_questionnaire.id ORDER BY $database_info_wedding_questionnaire.id DESC");
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

fputcsv($output, array($record[0], $date2, $couple_names, $couple_names2, $record[4], $record[16], $record[17], $island_html, $date, stripslashes($record[9]), stripslashes($record[10]), stripslashes($record[11]), $callback, stripslashes($record[3])));	
}




} elseif($_GET["data"] == "personal") {




$date = date("d-m-Y",$time);
$file_name = "personal-consultation-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers', 'Preferred Day', 'Preferred Time', 'Callback'));



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
county",$where_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0],$date, $name,stripslashes($record[3]),stripslashes($record[4]),stripslashes($record[5]),stripslashes($record[6]),stripslashes($record[7]),stripslashes($record[8]),stripslashes($record[17]),stripslashes($record[9]),stripslashes($record[10]),stripslashes($record[11]),stripslashes($record[13]),stripslashes($record[14]),stripslashes($record[15]),stripslashes($record[16])));
}
}elseif($_GET["data"] == "bookacallback") {




$date = date("d-m-Y",$time);
$file_name = "bookacallback-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers', 'Preferred Day', 'Preferred Time'));



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
county",$where_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0],$date, $name,stripslashes($record[3]),stripslashes($record[4]),stripslashes($record[5]),stripslashes($record[6]),stripslashes($record[7]),stripslashes($record[8]),stripslashes($record[17]),stripslashes($record[9]),stripslashes($record[10]),stripslashes($record[11]),stripslashes($record[13]),stripslashes($record[14]),stripslashes($record[15])));
}
}elseif($_GET["data"] == "contactus") {




$date = date("d-m-Y",$time);
$file_name = "contactus-data-".$date.".csv";



header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('#', 'Enquiry date', 'Subject', 'Name', 'Email', 'Telephone', 'A1', 'A2', 'A3', 'Town', 'County', 'Country', 'Postcode', 'Comments', 'Latest Offers'));



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
county",$where_line." ORDER BY id DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
	

$record = str_replace("\"","'",$record);
$record = str_replace("\'","'",$record);

$name = stripslashes($record[1])." ".stripslashes($record[2]);
$date = @date("d-m-Y",$record[12]);
fputcsv($output, array($record[0], $date, stripslashes($record[14]), $name, stripslashes($record[3]), stripslashes($record[4]), stripslashes($record[5]), stripslashes($record[6]), stripslashes($record[7]), stripslashes($record[8]), stripslashes($record[17]), stripslashes($record[9]), stripslashes($record[10]), stripslashes($record[11]), stripslashes($record[13])));
}
}

?>