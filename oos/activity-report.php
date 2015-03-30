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


if($_POST["action"] == "Download CSV") {
	
if(strlen($_POST["date_from"]) > 5) {

	list($day,$month,$year) = explode("-",$_POST["date_from"]);
	$from = mktime(0, 0, 0, $month, $day, $year);


	if(strlen($_POST["date_to"]) > 5) {
		list($day,$month,$year) = explode("-",$_POST["date_to"]);
		$to = mktime(23, 59, 59, $month, $day, $year);
	} else {
		$to = $time;	
	}
	$where_line = " and $database_client_historyinfo.timestamp >= ".addslashes($from)." and $database_client_historyinfo.timestamp <= ".addslashes($to);

}



	$date = date("d-m-Y",$time);
	$file_name = "activity-report-".$date.".csv";
	
	
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$file_name);
	
	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');
	
	// output the column headings
	fputcsv($output, array('User','Client','IWCUID','Wedding Date', 'Action', 'Island', 'Package', 'Date'));


if($_POST["user_id"] != "all") {
$result = $sql_command->select("$database_client_historyinfo,$database_users","$database_client_historyinfo.comment,
								   $database_client_historyinfo.timestamp,
								   $database_users.username,
								   $database_client_historyinfo.client_id,
								   $database_client_historyinfo.package_id,
								   $database_client_historyinfo.island_id
								   ","WHERE 
								   $database_client_historyinfo.user_id=$database_users.id and 
								   $database_client_historyinfo.user_id='".addslashes($_POST["user_id"])."' 
								   $where_line ORDER BY $database_client_historyinfo.timestamp DESC");
$row = $sql_command->results($result);
} else {
$result = $sql_command->select("$database_client_historyinfo,$database_users","$database_client_historyinfo.comment,
								   $database_client_historyinfo.timestamp,
								   $database_users.username,
								   $database_client_historyinfo.client_id,
								   $database_client_historyinfo.package_id,
								   $database_client_historyinfo.island_id
								   ","WHERE 
								   $database_client_historyinfo.user_id=$database_users.id  
								   $where_line ORDER BY $database_client_historyinfo.timestamp DESC");
$row = $sql_command->results($result);	
}

	foreach($row as $record) {
		$record = str_replace("\"","'",$record);
		$record = str_replace("\'","'",$record);

$package = "";
$island = "";

if($record[4] > 0) {
	
$p_result = $sql_command->select("$database_packages,$database_package_info","$database_packages.package_name,
									$database_package_info.iw_name
									","WHERE $database_package_info.id='".$record[4]."' and
									$database_packages.id=$database_package_info.package_id");
$p_record = $sql_command->result($p_result);	
$p_record = str_replace("\"","'",$p_record);
$p_record = str_replace("\'","'",$p_record);
$package = $p_record[0];
}

if($record[5] > 0) {
$i_result = $sql_command->select($database_navigation,"page_name","WHERE id='".$record[5]."'");
$i_record = $sql_command->result($i_result);	
$i_record = str_replace("\"","'",$i_record);
$i_record = str_replace("\'","'",$i_record);
$island = $i_record[0];
}


if($record[3] > 0) {
$client_result = $sql_command->select("$database_clients","title,
								   firstname,
								   lastname,
								   iwcuid,
								   wedding_date
								   ","WHERE 
								   $database_clients.id='".$record[3]."'");
$client_record = $sql_command->result($client_result);	
$client_record = str_replace("\"","'",$client_record);
$client_record = str_replace("\'","'",$client_record);


$name = $client_record[0]." ".$client_record[1]." ".$client_record[2];
$iwcuid = $client_record[3];
$weddingdate = @date("d-m-Y",$client_record[4]);
} else {
$name = "";	
$iwcuid = "";
$weddingdate = "";
}

$date = @date("d-m-Y",$record[1]);
$date .= " at ";
$date .= @date("g:i A",$record[1]);


$record[0] = str_replace("&nbsp;"," ",$record[0]);
$record[0] = str_replace("&amp;","&",$record[0]);
$record[0] = str_replace("&#39;","'",$record[0]);
$record[0] = str_replace("&rsquo;","'",$record[0]);

		fputcsv($output, array(stripslashes($record[2]),utf8_encode($name),utf8_encode($iwcuid),utf8_encode($weddingdate),utf8_encode(stripslashes($record[0])),utf8_encode(stripslashes($island)),utf8_encode(stripslashes($package)),$date));
	}




	
	
} else {

$result = $sql_command->select($database_users,"id,username","WHERE account_option!='Super Admin' ORDER BY username");
$row = $sql_command->results($result);

$list .= "<option value=\"all\" style=\"font-size:10px;\">All Users</option>\n";

foreach($row as $record) {
$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}


$get_template->topHTML();
?>
<h1>Activity Report</h1>
<form method="post" action="<?php echo $site_url; ?>/oos/activity-report.php" name="getcsvdata">
<input type="hidden" name="data" value="client" />
<div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_from"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_from'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
<div style="float:left; margin:5px;"><input type="text" name="date_to"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'getcsvdata',
		// input name
		'controlname': 'date_to'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:140px; margin:5px;"><strong>User</strong></div>
<div style="float:left;  margin:5px;"><select name="user_id" size="10" style="width:500px;">
<?php echo $list; ?>
</select>
</div>
<div style="clear:left;"></div>
<input type="submit" name="action" value="Download CSV" />
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
}

?>