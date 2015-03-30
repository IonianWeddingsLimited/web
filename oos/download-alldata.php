<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

//start old
$clients_q = "select c.id, c.iwcuid,COALESCE(wq.id,\"N/A\") as wedding,
COALESCE(pc.id,\"N/A\") as personal,
COALESCE(cu.id,\"N/A\") as contactus,
COALESCE(cb.id,\"N/A\") as callback 
FROM clients as c
LEFT OUTER JOIN info_wedding_questionnaire as wq 
	ON (wq.bride_firstname = c.firstname 
		AND wq.bride_lastname = c.lastname 
		AND wq.bride_email = c.email) 
	OR (wq.groom_firstname = c.groom_firstname 
		AND wq.groom_lastname = c.groom_surname 	
		AND wq.groom_email = c.groom_email) 
LEFT OUTER JOIN	info_personal_consultations as pc 
	ON (pc.firstname = c.firstname 
		AND pc.lastname = c.lastname 
		AND pc.email = c.email) 
	OR (pc.firstname = c.groom_firstname 
		AND pc.lastname = c.groom_surname 
		AND pc.email = c.groom_email) 
LEFT OUTER JOIN info_contactus as cu 
	ON (cu.firstname = c.firstname 
		AND cu.lastname = c.lastname 
		AND cu.email = c.email) 
	OR (cu.firstname = c.groom_firstname 
		AND cu.lastname = c.groom_surname 
		AND cu.email = c.groom_email) 
LEFT OUTER JOIN info_bookacallback as cb 
	ON (cb.firstname = c.firstname 
		AND cb.lastname = c.lastname 
		AND cb.email = c.email) 
	OR (cb.firstname = c.groom_firstname 
		AND cb.lastname = c.groom_surname 
		AND cb.email = c.groom_email)";
	
$clients_r = mysql_query($clients_q);

$wedding_ids = array();
$personal_ids = array();
$contact_ids = array();
$callback_ids = array();
$iwcuids_id = array();
$clients_id = array();
$awq_id = array();
$apc_id = array();
$acu_id = array();
$acb_id = array();
		
while ($row = @mysql_fetch_row($clients_r)) {
	$client_id = $row[0];
	$iwcuid_id = $row[1];
	$wid = $row[2];
	$pid = $row[3];
	$cid = $row[4];
	$bid = $row[5];
	if ($row[2]!="N/A") { 
		$wedding_ids[] = $row[2]; 
		$clients_id["wedding"][$wid] = $client_id; 
		$iwcuids_id["wedding"][$wid] = $iwcuid_id; 
	}
	if ($row[3]!="N/A") { 
		$personal_ids[] = $row[3]; 
		$clients_id["personal"][$pid] = $client_id; 
		$iwcuids_id["personal"][$pid] = $iwcuid_id;
	}
	if ($row[4]!="N/A") { 
		$contact_ids[] = $row[4]; 
		$clients_id["contact"][$cid] = $client_id;
		$iwcuids_id["contact"][$cid] = $iwcuid_id;
	}
	if ($row[5]!="N/A") { 
		$callback_ids[] = $row[5]; 
		$clients_id["callback"][$bid] = $client_id; 
		$iwcuids_id["callback"][$bid] = $iwcuid_id;
	}
	if ($_GET["amfrom"]>0 && $_GET["amto"]>0) {
		$amount_q = "SELECT DISTINCT count(order_details.client_id) FROM order_details,order_history WHERE order_details.id = order_history.order_id and order_details.client_id = '".addslashes($client_id)."' HAVING sum(order_history.iw_cost) >= '".$_GET["amfrom"]."' and sum(order_history.iw_cost) <= '".$_GET["amto"]."'";
			
		$amount_r = mysql_query($amount_q) or die(mysql_error());
		$amount_c = @mysql_fetch_row($amount_r);
		if ($amount_c>0&&$row[2]!="N/A") { $awq_id[] = $row[2]; }
		if ($amount_c>0&&$row[3]!="N/A") { $awq_id[] = $row[3]; }
		if ($amount_c>0&&$row[4]!="N/A") { $awq_id[] = $row[4]; }
		if ($amount_c>0&&$row[5]!="N/A") { $awq_id[] = $row[5]; }
	}
}

$wq_ids = implode(",",$wedding_ids);
$pc_ids = implode(",",$personal_ids);
$cu_ids = implode(",",$contact_ids);
$cb_ids = implode(",",$callback_ids);

$wq_filter_line = $pc_filter_line = $cu_filter_line = $cb_filter_line = "";
	

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

$search_c = "";
$destination = $_GET["destination"];
$location = $_GET["location"];
$amfrom = $_GET["amfrom"];
$amto = $_GET["amto"];
$option_filter = "";
$location_filter .= "";
$destination_filter .= "";
$amount_filter .= "";	
$add_des_line = "";
$count_c = 0;
	
		
if ($_GET["s"]!="" && $_GET["stype"]=="email") {
	$wfilter = "($database_info_wedding_questionnaire.bride_email LIKE '%".$_GET["s"]."%' OR $database_info_wedding_questionnaire.groom_email LIKE '%".$_GET["s"]."%') AND ";	
	$nfilter = "(email LIKE '%".$_GET["s"]."%') AND ";
	$search_c = "<span style=\"color:#000;\">Email:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
	$count_c++;
	$search_c .= "<span style=\"color:#000;\">Email:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
}
elseif ($_GET["s"]!="" && $_GET["stype"]=="name") {
	list($first_n,$last_n) = explode(" ",$_GET["s"]);
	$wfilter = ($last_n!="") ? 
	"(($database_info_wedding_questionnaire.groom_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.groom_lastname = '".addslashes($last_n)."') OR ($database_info_wedding_questionnaire.bride_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.bride_lastname = '".addslashes($last_n)."')) AND " : 
	"($database_info_wedding_questionnaire.groom_firstname like '".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.groom_lastname like '".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_firstname LIKE '".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_lastname LIKE '".addslashes($first_n)."%') AND ";
		
	$nfilter = ($last_n!="") ? "(firstname = '".addslashes($first_n)."' AND lastname = '".addslashes($last_n)."')" : "(firstname LIKE '".addslashes($_GET["s"])."%' OR lastname LIKE '".addslashes($_GET["s"])."%') AND ";		
			
	$count_c++;
	$search_c .= "<span style=\"color:#000;\">Name:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
}
		
if ($location!="") {
	$locationwq_filter = "$database_info_wedding_questionnaire.county LIKE '%".$_GET["location"]."%' AND ";
	$location_filter = "county LIKE '%".$_GET["location"]."%' AND ";
	$count_c++;
	$search_c .= "<span style=\"color:#000;\">County:</span> <a href=\"search-submissions.php?location=".$_GET["location"]."\">".$_GET["Location"]."</a>";
}
if($destination>0) {
	$destination_filter = "$database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id AND $database_questionaire_destinations.island_id='".addslashes($destination)."' AND ";
	$add_des_line = ",$database_questionaire_destinations";
	$count_c++;
	$search_c .= "<span style=\"color:#000;\">Destination:</span> <a href=\"search-submissions.php?destination=".$_GET["destination"]."\">".$_GET["destination"]."</a>";
}
if ($amfrom > 0 && $amto > 0) {
	$amount_wq = (count($awq_id)>0) ? implode(",",$awq_id) : 0;
	$amount_pc = (count($apc_id)>0) ? implode(",",$apc_id) : 0;			
	$amount_cu = (count($acu_id)>0) ? implode(",",$acu_id) : 0;
	$amount_cb = (count($acb_id)>0) ? implode(",",$acb_id) : 0;
	$awq_filter = "$database_info_wedding_questionnaire.id in (".addslashes($amount_wq).") AND ";
	$apc_filter = "id in (".addslashes($amount_pc).") AND ";
	$acu_filter = "id in (".addslashes($amount_cu).") AND ";
	$acb_filter = "id in (".addslashes($amount_cb).") AND ";
	$count_c++;
	$search_c .= "<span style=\"color:#000;\">Amount from:</span> <a href=\"search-submissions.php?amfrom=".$_GET["amfrom"]."&amto=".$_GET["amto"]."\">".number_format($_GET["amfrom"],2)." to ".number_format($_GET["amto"],2)."</a>";
}
		
if ($_GET["date_from"]!="" && $_GET[""]!="date_to") {
	$filter_va = array("/"," ",":","-");
	$sdate = str_replace($filter_va,"-",$_GET["date_from"]);
	$tdate = str_replace($filter_va,"-",$_GET["date_to"]);
	
	$sdate = ($sdate=="") ? "01-01-1900" : $sdate;
	$tdate = ($tdate=="") ? date("d-m-Y") : $tdate;
	
	$dates = ($sdate!=""&&$tdate!="") ? $_GET["date_from"]." > ".$tdate : "";
	list($sd,$sm,$sy) = explode("-",$sdate);
	list($td,$tm,$ty) = explode("-",$tdate);
	$td++;
	$sdate = strtotime("".$sm."/".$sd."/".$sy."");
	$tdate = strtotime("".$tm."/".$td."/".$ty."");
		
	$datefilter = ($dates!="") ? ($_GET["s"]!="") ? "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "";

	$wq_datefilter = ($dates!="") ? ($_GET["s"]!="") ? "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "";


	$search_c .= "<span style=\"color:#000;\">Date:</span> <a href=\"search-submissions.php?date_from=".$_GET["date_from"]."&date_to=".$_GET["date_to"]."\">$dates</a>";
}
	
$where = ($count_c>0) ? "WHERE " : "";
	
$where_wq = $wq_datefilter.$destination_filter.$locationwq_filter.$awq_filter.$wfilter;
$where_wq = preg_replace('/ AND $/', ' ', $where_wq);
$where = ($count_c>0) ? "WHERE " : "";
$where_cb = $datefilter.$location_filter.$acb_filter.$nfilter;
$where_cu = $datefilter.$location_filter.$acu_filter.$nfilter;
$where_pc = $datefilter.$location_filter.$apc_filter.$nfilter;
		
			
$where_cb = preg_replace('/ AND $/', ' ', $where_cb);
$where_cu = preg_replace('/ AND $/', ' ', $where_cu);
$where_pc = preg_replace('/ AND $/', ' ', $where_pc);

$result_w = $sql_command->select("$database_info_wedding_questionnaire$add_des_line",
							   "$database_info_wedding_questionnaire.id,
							   $database_info_wedding_questionnaire.timestamp,
							   'wedding' as TableName,
							   $database_info_wedding_questionnaire.bride_firstname,
							   $database_info_wedding_questionnaire.bride_lastname,
							   $database_info_wedding_questionnaire.bride_email,
							   $database_info_wedding_questionnaire.bride_telephone,
							   $database_info_wedding_questionnaire.address_1,
							   $database_info_wedding_questionnaire.address_2,
							   $database_info_wedding_questionnaire.address_3,
							   $database_info_wedding_questionnaire.town,
							   $database_info_wedding_questionnaire.country,
							   $database_info_wedding_questionnaire.postcode,
							   $database_info_wedding_questionnaire.timestamp,
							   $database_info_wedding_questionnaire.getlatestoffers,
							   $database_info_wedding_questionnaire.county,
							   $database_info_wedding_questionnaire.callback_date,
							   $database_info_wedding_questionnaire.callback,
							   $database_info_wedding_questionnaire.hearaboutus,
							   $database_info_wedding_questionnaire.recommended_us,
							   $database_info_wedding_questionnaire.bride_nationality,
							   $database_info_wedding_questionnaire.bride_country,
							   $database_info_wedding_questionnaire.groom_firstname,
							   $database_info_wedding_questionnaire.groom_lastname,
							   $database_info_wedding_questionnaire.groom_email,
							   $database_info_wedding_questionnaire.groom_telephone,
							   $database_info_wedding_questionnaire.groom_nationality,
							   $database_info_wedding_questionnaire.groom_country,
							   $database_info_wedding_questionnaire.estimated_date_of_wedding,
							   $database_info_wedding_questionnaire.anticipated_number_of_guests,
							   $database_info_wedding_questionnaire.type_of_ceremony,
							   $database_info_wedding_questionnaire.prefered_ceremony_setting,
							   $database_info_wedding_questionnaire.prefered_reception_setting,
							   $database_info_wedding_questionnaire.estimated_budget",
							   "$where $where_wq ORDER BY $database_info_wedding_questionnaire.bride_lastname,
							   $database_info_wedding_questionnaire.groom_lastname");
		
	
$row_w = $sql_command->results($result_w);	
		
if ($destination==0){
	$result_cb = $sql_command->select($database_info_bookacallback,
										   "id,
											  timestamp,
											  'callback' as TableName,
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
											  ' ' as dateT,
											  getlatestoffers,
											  county,
											  preferred_day,
											  ' ' as callback,
											  ' ' as hearaboutus,
											  ' ' as recommended_us,
											  ' ' as bride_nationality,
											  ' ' as bride_country,
											  ' ' as groom_firstname,
											  ' ' as groom_lastname,
											  ' ' as groom_email,
											  ' ' as groom_telephone,
											  ' ' as groom_nationality,
											  ' ' as groom_country,
											  ' ' as estimated_date_of_wedding,
											  ' ' as anticipated_number_of_guests,
											  ' ' as type_of_ceremony,
											  ' ' as prefered_ceremony_setting,
											  ' ' as prefered_reception_setting,
											  ' ' as estimated_budget",
											  "$where $where_cb ORDER BY lastname");
	$row_cb = $sql_command->results($result_cb);
			
		
	$result_c = $sql_command->select($database_info_contactus,
											 "id,
											 timestamp,
											 'contact' as TableName,
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
											 ' ' as dateT,
											 getlatestoffers,
											 county,
											 ' ' as callback_date,
											 ' ' as callback,
											 ' ' as hearaboutus,
											 ' ' as recommended_us,
											 ' ' as bride_nationality,
											 ' ' as bride_country,
											 ' ' as groom_firstname,
											 ' ' as groom_lastname,
											 ' ' as groom_email,
											 ' ' as groom_telephone,
											 ' ' as groom_nationality,
											 ' ' as groom_country,
											 ' ' as estimated_date_of_wedding,
											 ' ' as anticipated_number_of_guests,
											 ' ' as type_of_ceremony,
											 ' ' as prefered_ceremony_setting,
											 ' ' as prefered_reception_setting,
											 ' ' as estimated_budget",
											 "$where $where_cu ORDER BY lastname");
	$row_c = $sql_command->results($result_c);
			
	
	$result_p = $sql_command->select($database_info_personal_consultations,
											 "id,
											 timestamp,
											 'personal' as TableName,
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
											 ' ' as dateT,
											 getlatestoffers,
											 county,
											 preferred_day,
											 callback,
											 ' ' as hearaboutus,
											 ' ' as recommended_us,
											 ' ' as bride_nationality,
											 ' ' as bride_country,
											 ' ' as groom_firstname,
											 ' ' as groom_lastname,
											 ' ' as groom_email,
											 ' ' as groom_telephone,
											 ' ' as groom_nationality,
											 ' ' as groom_country,
											 ' ' as estimated_date_of_wedding,
											 ' ' as anticipated_number_of_guests,
											 ' ' as type_of_ceremony,
											 ' ' as prefered_ceremony_setting,
											 ' ' as prefered_reception_setting,
											 ' ' as estimated_budget",
										   "$where $where_pc ORDER BY lastname");
	$row_p = $sql_command->results($result_p);
	
	
}
		
else {	
	
$result_w = $sql_command->select($database_info_wedding_questionnaire,
									   "id,
									   timestamp,
									   'wedding' as TableName,
									   bride_firstname,
									   bride_lastname,
									   bride_email,
									   bride_telephone,
									   address_1,
									   address_2,
									   address_3,
									   town,
									   country,
									   postcode,
									   ' ' as dateT,
									   getlatestoffers,
									   county,
									   callback_date,
									   callback,
									   hearaboutus,
									   recommended_us,
									   bride_nationality,
									   bride_country,
									   groom_firstname,
									   groom_lastname,
									   groom_email,
									   groom_telephone,
									   groom_nationality,
									   groom_country,
									   estimated_date_of_wedding,
									   anticipated_number_of_guests,
									   type_of_ceremony,
									   prefered_ceremony_setting,
									   prefered_reception_setting,
									   estimated_budget",
									   "ORDER BY timestamp DESC");
	$row_w = $sql_command->results($result_w);
		
	$result_cb = $sql_command->select($database_info_bookacallback,
										  "id,
										  timestamp,
										  'callback' as TableName,
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
										  ' ' as dateT,
										  getlatestoffers,
										  county,
										  preferred_day,
										  ' ' as callback,
										  ' ' as hearaboutus,
										  ' ' as recommended_us,
										  ' ' as bride_nationality,
										  ' ' as bride_country,
										  ' ' as groom_firstname,
										  ' ' as groom_lastname,
										  ' ' as groom_email,
										  ' ' as groom_telephone,
										  ' ' as groom_nationality,
										  ' ' as groom_country,
										  ' ' as estimated_date_of_wedding,
										  ' ' as anticipated_number_of_guests,
										  ' ' as type_of_ceremony,
										  ' ' as prefered_ceremony_setting,
										  ' ' as prefered_reception_setting,
										  ' ' as estimated_budget",
										  "ORDER BY timestamp DESC");
	$row_cb = $sql_command->results($result_cb);	
		
	$result_c = $sql_command->select($database_info_contactus,
										 "id,
										 timestamp,
										 'contact' as TableName,
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
										 ' ' as dateT,
										 getlatestoffers,
										 county,
										 ' ' as callback_date,
										 ' ' as callback,
										 ' ' as hearaboutus,
										 ' ' as recommended_us,
										 ' ' as bride_nationality,
										 ' ' as bride_country,
										 ' ' as groom_firstname,
										 ' ' as groom_lastname,
										 ' ' as groom_email,
										 ' ' as groom_telephone,
										 ' ' as groom_nationality,
										 ' ' as groom_country,
										 ' ' as estimated_date_of_wedding,
										 ' ' as anticipated_number_of_guests,
										 ' ' as type_of_ceremony,
										 ' ' as prefered_ceremony_setting,
										 ' ' as prefered_reception_setting,
										 ' ' as estimated_budget",
										 "ORDER BY timestamp DESC");
	$row_c = $sql_command->results($result_c);
		
	$result_p = $sql_command->select($database_info_personal_consultations,
										 "id,
										 timestamp,
										 'personal' as TableName,
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
										 ' ' as dateT,
										 getlatestoffers,
										 county,
										 preferred_day,
										 callback,
										 ' ' as hearaboutus,
										 ' ' as recommended_us,
										 ' ' as bride_nationality,
										 ' ' as bride_country,
										 ' ' as groom_firstname,
										 ' ' as groom_lastname,
										 ' ' as groom_email,
										 ' ' as groom_telephone,
										 ' ' as groom_nationality,
										 ' ' as groom_country,
										 ' ' as estimated_date_of_wedding,
										 ' ' as anticipated_number_of_guests,
										 ' ' as type_of_ceremony,
										 ' ' as prefered_ceremony_setting,
										 ' ' as prefered_reception_setting,
										 ' ' as estimated_budget",
										 "ORDER BY timestamp DESC");
	$row_p = $sql_command->results($result_p);
		
}

$testing = array();
foreach($row_w as $record) {
	$testing[] = $record;
}
	
foreach($row_cb as $record) {
	$testing[] = $record;
}
	
foreach($row_c as $record) {
	$testing[] = $record;
}
foreach($row_p as $record) {
	$testing[] = $record;
}
	
foreach ($testing as $key => $row) {
   	$timeSt[$key] = $row[1];
	$names[$key] = $row[5];		
}

// end old 
	
	if($_GET["s"]!="" ||
	$_GET["date_from"]!="" ||
	$_GET["destination"]>0 ||
	$_GET["location"]!="" ||
	$_GET["b_client"]>1 ||
	$_GET["amounts"]!=0) {
		$search_c = "";
		$destination = $_GET["destination"];
		$location = $_GET["location"];
		$amounts = $_GET["amounts"];
		$option_filter = "";
		$location_filter .= "";
		$destination_filter .= "";
		$amount_filter .= "";	
		$add_des_line = "";
		$count_c = 0;
		
		$bclient=$_GET["b_client"];
		$select_q = "";
		$email_list = array();
		$client_id = array();
		
		if ($_GET["amounts"]!=0) {
			switch ($_GET["amounts"]){
				case "1":
					$amtext = "£0 to £5k";
					$amfrom = 0;
					$amto = 5000;
					break;
				case "2":
					$amtext = "£5 to £10k";
					$amfrom = 5000;
					$amto = 10000;
					break;
				case "3":
					$amtext = "£10 to £15k";
					$amfrom = 10000;
					$amto = 15000;
					break;
				case "4":
					$amtext = "£15k+";
					$amfrom = 15000;
					$amto = 5000000;
					break;
			}
			$amount_q = "SELECT order_details.client_id FROM order_details,order_history WHERE order_details.id = order_history.order_id group by order_details.id HAVING sum(order_history.iw_cost) >= '".$amfrom."' and sum(order_history.iw_cost) <= '".$amto."'";
			
			$amount_r = mysql_query($amount_q) or die(mysql_error());
			$am_c = $sql_command->results($amount_r);
			foreach ($am_c as $am) {
				$amount_c[] = $am[0];	
			}

		}
			if ($_GET["s"]!="" && $_GET["stype"]=="email") {
				$wfilter = "($database_info_wedding_questionnaire.bride_email LIKE '%".$_GET["s"]."%' OR $database_info_wedding_questionnaire.groom_email LIKE '%".$_GET["s"]."%') AND ";	
				$wfilter = "($database_clients.email LIKE '%".$_GET["s"]."%' OR $database_clients.groom_email LIKE '%".$_GET["s"]."%') AND ";	
				$nfilter = "(email LIKE '%".$_GET["s"]."%') AND ";
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">Email:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
			}
			elseif ($_GET["s"]!="" && $_GET["stype"]=="name") {
				list($first_n,$last_n) = explode(" ",$_GET["s"]);
				$wfilter = ($last_n!="") ? 
				"(($database_info_wedding_questionnaire.groom_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.groom_lastname = '".addslashes($last_n)."') OR ($database_info_wedding_questionnaire.bride_firstname = '".addslashes($first_n)."' AND $database_info_wedding_questionnaire.bride_lastname = '".addslashes($last_n)."')) AND " : 
				"($database_info_wedding_questionnaire.groom_firstname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.groom_lastname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_firstname LIKE '%".addslashes($first_n)."%' OR $database_info_wedding_questionnaire.bride_lastname LIKE '%".addslashes($first_n)."%') AND ";
	
				$cfilter = ($last_n!="") ? 
				"(($database_clients.groom_firstname = '".addslashes($first_n)."' AND $database_clients.groom_surname = '".addslashes($last_n)."') OR ($database_clients.firstname = '".addslashes($first_n)."' AND $database_clients.lastname = '".addslashes($last_n)."')) AND " : 
				"($database_clients.groom_firstname LIKE '%".addslashes($first_n)."%' OR $database_clients.groom_surname LIKE '%".addslashes($first_n)."%' OR $database_clients.firstname LIKE '%".addslashes($first_n)."%' OR $database_clients.lastname LIKE '%".addslashes($first_n)."%') AND ";
	
	
				$nfilter = ($last_n!="") ? "(firstname = '".addslashes($first_n)."' AND lastname = '".addslashes($last_n)."')" : "(firstname LIKE '%".addslashes($_GET["s"])."%' OR lastname LIKE '%".addslashes($_GET["s"])."%') AND ";		
				
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">Name:</span> <a href=\"search-submissions.php?s=".$_GET["s"]."\">".$_GET["s"]."</a>";
			}
			
			if ($location!="") {
				$locationwq_filter = "$database_info_wedding_questionnaire.county LIKE '%".$_GET["location"]."%' AND ";
				$locationcl_filter = "$database_clients.county LIKE '%".$_GET["location"]."%' AND ";
				$location_filter = "county LIKE '%".$_GET["location"]."%' AND ";
				$count_c++;
				$search_c .= "<span style=\"color:#000;\">County:</span> <a href=\"search-submissions.php?location=".$_GET["location"]."\">".$_GET["location"]."</a>";
		}
		if($destination>0) {
			$destination_filter = "$database_questionaire_destinations.questionaire_id=$database_info_wedding_questionnaire.id AND $database_questionaire_destinations.island_id='".addslashes($destination)."' AND ";
			$add_des_line = ",$database_questionaire_destinations";
			$cdestination_filter = "$database_clients.destination='".addslashes($destination)."' AND ";
			$count_c++;
			$search_c .= "<span style=\"color:#000;\">Destination:</span> <a href=\"search-submissions.php?destination=".$_GET["destination"]."\">".$d_name."</a>";
		}
		if ($_GET["amounts"]!=0) {
			$amount_cl = (count($amount_c)>0) ? implode(",",$amount_c) : 0;
			$cl_filter_line = "clients.id IN (".$amount_cl.") AND ";
			$count_c++;
			$search_c .= "<span style=\"color:#000;\">Amounts:</span> <a href=\"search-submissions.php?amounts=".$_GET["amounts"]."\">".$amtext."</a>";
		}
		
		if ($_GET["date_from"]!="" && $_GET[""]!="date_to") {
			$filter_va = array("/"," ",":","-");
			$sdate = str_replace($filter_va,"-",$_GET["date_from"]);
			$tdate = str_replace($filter_va,"-",$_GET["date_to"]);
			
			$sdate = ($sdate=="") ? "01-01-1900" : $sdate;
			$tdate = ($tdate=="") ? date("d-m-Y") : $tdate;
			
			$dates = ($sdate!=""&&$tdate!="") ? $_GET["date_from"]." > ".$tdate : "";
			list($sd,$sm,$sy) = explode("-",$sdate);
			list($td,$tm,$ty) = explode("-",$tdate);
			$td++;
			$sdate = strtotime("".$sm."/".$sd."/".$sy."");
			$tdate = strtotime("".$tm."/".$td."/".$ty."");
			
			$datefilter = ($dates!="") ? ($_GET["s"]!="") ? "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "(timestamp >= ".$sdate." and timestamp < ".$tdate.") AND ": "";

			$wq_datefilter = ($dates!="") ? ($_GET["s"]!="") ? "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "($database_info_wedding_questionnaire.timestamp >= ".$sdate." and $database_info_wedding_questionnaire.timestamp < ".$tdate.") AND ": "";

$cl_datefilter = ($dates!="") ? ($_GET["s"]!="") ? "($database_clients.wedding_date >= ".$sdate." and $database_clients.wedding_date < ".$tdate.") AND ": "($database_clients.wedding_date >= ".$sdate." and $database_clients.wedding_date < ".$tdate.") AND ": "";

			$search_c .= "<span style=\"color:#000;\">Date:</span> <a href=\"search-submissions.php?date_from=".$_GET["date_from"]."&date_to=".$_GET["date_to"]."\">$dates</a>";
		}
	
		if ($bclient==3) {
			$cl_filter_line = "clients_options.option_value != 'Active' AND ";
			$count_c++;
			$search_c .= "Prospects Only";
		}
		elseif ($bclient==2) { 	$count_c++;
			$count_c++;
			$search_c .= "Clients Only";
		 }
		
		$where = ($count_c>0) ? "WHERE " : "";
		
		$where_cl = "$database_clients.deleted='No' AND ".$cl_datefilter.$cdestination_filter.$locationcl_filter.$acl_filter.$cl_filter_line.$cfilter;
		$where_wq = $wq_datefilter.$destination_filter.$locationwq_filter.$awq_filter.$wfilter;
		$where_cb = $datefilter.$location_filter.$acb_filter.$nfilter;
		$where_cu = $datefilter.$location_filter.$acu_filter.$nfilter;
		$where_pc = $datefilter.$location_filter.$apc_filter.$nfilter;

		$where_cl = preg_replace('/ AND $/', ' ', $where_cl);			
		$where_wq = preg_replace('/ AND $/', ' ', $where_wq);
		$where_cb = preg_replace('/ AND $/', ' ', $where_cb);
		$where_cu = preg_replace('/ AND $/', ' ', $where_cu);
		$where_pc = preg_replace('/ AND $/', ' ', $where_pc);
	
		$query_cl = "select $database_clients.id,
				   $database_clients.wedding_date,
							   COALESCE(clients_options.option_value,\"Active\") as opt_val,
				   			   $database_clients.firstname, $database_clients.lastname, $database_clients.email, $database_clients.tel, $database_clients.address_1, $database_clients.address_2, $database_clients.address_3, $database_clients.town, $database_clients.country, $database_clients.postcode, '' as timestamp, $database_clients.mailing_list, $database_clients.county,  $database_clients.wedding_date,  ' ' as callback,
											  ' ' as hearaboutus,
											  ' ' as recommended_us,
											  ' ' as bride_nationality,
											  ' ' as bride_country,
											  $database_clients.groom_firstname,
											  $database_clients.groom_surname,
											  $database_clients.groom_email,
											  $database_clients.groom_tel, 
											  ' ' as groom_nationality,
											  ' ' as groom_country,
											  ' ' as estimated_date_of_wedding,
											  ' ' as anticipated_number_of_guests,
											  ' ' as type_of_ceremony,
											  ' ' as prefered_ceremony_setting,
											  ' ' as prefered_reception_setting,
											  ' ' as estimated_budget
 FROM $database_clients LEFT OUTER JOIN clients_options ON clients_options.client_id = $database_clients.id AND clients_options.client_option = 'client_type' $where $where_cl";
		$result_cl = mysql_query($query_cl);				   
		$row_cl = $sql_command->results($result_cl);



if ($amounts>0) { $bclient = 2; }
		$result_w = $sql_command->select("$database_info_wedding_questionnaire$add_des_line",
							   "$database_info_wedding_questionnaire.id,
							   $database_info_wedding_questionnaire.timestamp,
							   'wedding' as TableName,
							   $database_info_wedding_questionnaire.bride_firstname,
							   $database_info_wedding_questionnaire.bride_lastname,
							   $database_info_wedding_questionnaire.bride_email,
							   $database_info_wedding_questionnaire.bride_telephone,
							   $database_info_wedding_questionnaire.address_1,
							   $database_info_wedding_questionnaire.address_2,
							   $database_info_wedding_questionnaire.address_3,
							   $database_info_wedding_questionnaire.town,
							   $database_info_wedding_questionnaire.country,
							   $database_info_wedding_questionnaire.postcode,
							   $database_info_wedding_questionnaire.timestamp,
							   $database_info_wedding_questionnaire.getlatestoffers,
							   $database_info_wedding_questionnaire.county,
							   $database_info_wedding_questionnaire.callback_date,
							   $database_info_wedding_questionnaire.callback,
							   $database_info_wedding_questionnaire.hearaboutus,
							   $database_info_wedding_questionnaire.recommended_us,
							   $database_info_wedding_questionnaire.bride_nationality,
							   $database_info_wedding_questionnaire.bride_country,
							   $database_info_wedding_questionnaire.groom_firstname,
							   $database_info_wedding_questionnaire.groom_lastname,
							   $database_info_wedding_questionnaire.groom_email,
							   $database_info_wedding_questionnaire.groom_telephone,
							   $database_info_wedding_questionnaire.groom_nationality,
							   $database_info_wedding_questionnaire.groom_country,
							   $database_info_wedding_questionnaire.estimated_date_of_wedding,
							   $database_info_wedding_questionnaire.anticipated_number_of_guests,
							   $database_info_wedding_questionnaire.type_of_ceremony,
							   $database_info_wedding_questionnaire.prefered_ceremony_setting,
							   $database_info_wedding_questionnaire.prefered_reception_setting,
							   $database_info_wedding_questionnaire.estimated_budget",
							   "$where $where_wq ORDER BY $database_info_wedding_questionnaire.bride_lastname,
							   $database_info_wedding_questionnaire.groom_lastname");
		
	
$row_w = $sql_command->results($result_w);	
		
	$result_cb = $sql_command->select($database_info_bookacallback,
										   "id,
											  timestamp,
											  'callback' as TableName,
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
											  ' ' as dateT,
											  getlatestoffers,
											  county,
											  preferred_day,
											  ' ' as callback,
											  ' ' as hearaboutus,
											  ' ' as recommended_us,
											  ' ' as bride_nationality,
											  ' ' as bride_country,
											  ' ' as groom_firstname,
											  ' ' as groom_lastname,
											  ' ' as groom_email,
											  ' ' as groom_telephone,
											  ' ' as groom_nationality,
											  ' ' as groom_country,
											  ' ' as estimated_date_of_wedding,
											  ' ' as anticipated_number_of_guests,
											  ' ' as type_of_ceremony,
											  ' ' as prefered_ceremony_setting,
											  ' ' as prefered_reception_setting,
											  ' ' as estimated_budget",
											  "$where $where_cb ORDER BY lastname");
	$row_cb = $sql_command->results($result_cb);
			
		
	$result_c = $sql_command->select($database_info_contactus,
											 "id,
											 timestamp,
											 'contact' as TableName,
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

											 ' ' as dateT,
											 getlatestoffers,
											 county,
											 ' ' as callback_date,
											 ' ' as callback,
											 ' ' as hearaboutus,
											 ' ' as recommended_us,
											 ' ' as bride_nationality,
											 ' ' as bride_country,
											 ' ' as groom_firstname,
											 ' ' as groom_lastname,
											 ' ' as groom_email,
											 ' ' as groom_telephone,
											 ' ' as groom_nationality,
											 ' ' as groom_country,
											 ' ' as estimated_date_of_wedding,
											 ' ' as anticipated_number_of_guests,
											 ' ' as type_of_ceremony,
											 ' ' as prefered_ceremony_setting,
											 ' ' as prefered_reception_setting,
											 ' ' as estimated_budget",
											 "$where $where_cu ORDER BY lastname");
	$row_c = $sql_command->results($result_c);
			
	
	$result_p = $sql_command->select($database_info_personal_consultations,
											 "id,
											 timestamp,
											 'personal' as TableName,
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
											 ' ' as dateT,
											 getlatestoffers,
											 county,
											 preferred_day,
											 callback,
											 ' ' as hearaboutus,
											 ' ' as recommended_us,
											 ' ' as bride_nationality,
											 ' ' as bride_country,
											 ' ' as groom_firstname,
											 ' ' as groom_lastname,
											 ' ' as groom_email,
											 ' ' as groom_telephone,
											 ' ' as groom_nationality,
											 ' ' as groom_country,
											 ' ' as estimated_date_of_wedding,
											 ' ' as anticipated_number_of_guests,
											 ' ' as type_of_ceremony,
											 ' ' as prefered_ceremony_setting,
											 ' ' as prefered_reception_setting,
											 ' ' as estimated_budget",
										   "$where $where_pc ORDER BY lastname");
	$row_p = $sql_command->results($result_p);
		}
		
		else {
		
		$result_cl = $sql_command->select("$database_clients","$database_clients.id,
				   $database_clients.wedding_date,
							   COALESCE(clients_options.option_value,\"Active\") as opt_val,
				   			   $database_clients.firstname, $database_clients.lastname, $database_clients.email, $database_clients.tel, $database_clients.address_1, $database_clients.address_2, $database_clients.address_3, $database_clients.town, $database_clients.country, $database_clients.postcode, '' as timestamp, $database_clients.mailing_list, $database_clients.county,  $database_clients.wedding_date,  ' ' as callback,
											  ' ' as hearaboutus,
											  ' ' as recommended_us,
											  ' ' as bride_nationality,
											  ' ' as bride_country,
											  $database_clients.groom_firstname,
											  $database_clients.groom_surname,
											  $database_clients.groom_email,
											  $database_clients.groom_tel, 
											  ' ' as groom_nationality,
											  ' ' as groom_country,
											  ' ' as estimated_date_of_wedding,
											  ' ' as anticipated_number_of_guests,
											  ' ' as type_of_ceremony,
											  ' ' as prefered_ceremony_setting,
											  ' ' as prefered_reception_setting,
											  ' ' as estimated_budget","LEFT OUTER JOIN clients_options ON clients_options.client_id = $database_clients.id AND clients_options.client_option = 'client_type' WHERE $database_clients.deleted='No'");
$row_cl = $sql_command->results($result_cl);

		$result_w = $sql_command->select($database_info_wedding_questionnaire,
									   "id,
									   timestamp,
									   'wedding' as TableName,
									   bride_firstname,
									   bride_lastname,
									   bride_email,
									   bride_telephone,
									   address_1,
									   address_2,
									   address_3,
									   town,
									   country,
									   postcode,
									   ' ' as dateT,
									   getlatestoffers,
									   county,
									   callback_date,
									   callback,
									   hearaboutus,
									   recommended_us,
									   bride_nationality,
									   bride_country,
									   groom_firstname,
									   groom_lastname,
									   groom_email,
									   groom_telephone,
									   groom_nationality,
									   groom_country,
									   estimated_date_of_wedding,
									   anticipated_number_of_guests,
									   type_of_ceremony,
									   prefered_ceremony_setting,
									   prefered_reception_setting,
									   estimated_budget",
									   "ORDER BY timestamp DESC");
	$row_w = $sql_command->results($result_w);
		
	$result_cb = $sql_command->select($database_info_bookacallback,
										  "id,
										  timestamp,
										  'callback' as TableName,
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
										  ' ' as dateT,
										  getlatestoffers,
										  county,
										  preferred_day,
										  ' ' as callback,
										  ' ' as hearaboutus,
										  ' ' as recommended_us,
										  ' ' as bride_nationality,
										  ' ' as bride_country,
										  ' ' as groom_firstname,
										  ' ' as groom_lastname,
										  ' ' as groom_email,
										  ' ' as groom_telephone,
										  ' ' as groom_nationality,
										  ' ' as groom_country,
										  ' ' as estimated_date_of_wedding,
										  ' ' as anticipated_number_of_guests,
										  ' ' as type_of_ceremony,
										  ' ' as prefered_ceremony_setting,
										  ' ' as prefered_reception_setting,
										  ' ' as estimated_budget",
										  "ORDER BY timestamp DESC");
	$row_cb = $sql_command->results($result_cb);	
		
	$result_c = $sql_command->select($database_info_contactus,
										 "id,
										 timestamp,
										 'contact' as TableName,
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
										 ' ' as dateT,
										 getlatestoffers,
										 county,
										 ' ' as callback_date,
										 ' ' as callback,
										 ' ' as hearaboutus,
										 ' ' as recommended_us,
										 ' ' as bride_nationality,
										 ' ' as bride_country,
										 ' ' as groom_firstname,
										 ' ' as groom_lastname,
										 ' ' as groom_email,
										 ' ' as groom_telephone,
										 ' ' as groom_nationality,
										 ' ' as groom_country,
										 ' ' as estimated_date_of_wedding,
										 ' ' as anticipated_number_of_guests,
										 ' ' as type_of_ceremony,
										 ' ' as prefered_ceremony_setting,
										 ' ' as prefered_reception_setting,
										 ' ' as estimated_budget",
										 "ORDER BY timestamp DESC");
	$row_c = $sql_command->results($result_c);
		
	$result_p = $sql_command->select($database_info_personal_consultations,
										 "id,
										 timestamp,
										 'personal' as TableName,
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
										 ' ' as dateT,
										 getlatestoffers,
										 county,
										 preferred_day,
										 callback,
										 ' ' as hearaboutus,
										 ' ' as recommended_us,
										 ' ' as bride_nationality,
										 ' ' as bride_country,
										 ' ' as groom_firstname,
										 ' ' as groom_lastname,
										 ' ' as groom_email,
										 ' ' as groom_telephone,
										 ' ' as groom_nationality,
										 ' ' as groom_country,
										 ' ' as estimated_date_of_wedding,
										 ' ' as anticipated_number_of_guests,
										 ' ' as type_of_ceremony,
										 ' ' as prefered_ceremony_setting,
										 ' ' as prefered_reception_setting,
										 ' ' as estimated_budget",
										 "ORDER BY timestamp DESC");
	$row_p = $sql_command->results($result_p);
		
	}
	$testing = array();
	
	if ($email_list != "") {
	
		foreach($row_cl as $record) {
			if ((!in_array($record[10],$email_list)&&!in_array($record[11],$email_list)) || ($record[10]=="" && $record[11]=="")) {
				$testing[] = $record;
				$email_list[] = $record[10]; $email_list[] = $record[11]; 
			}
		}
		
		foreach($row_w as $record) {
			if ((!in_array($record[9],$email_list)&&!in_array($record[10],$email_list) && $bclient!=2) || ($record[9]=="" && $record[10]==""))							 {
				$testing[] = $record; 
				$email_list[] = $record[7]; 
			}
		}
	
	}
	
	foreach($row_p as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}

	foreach($row_cb as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}
	
	foreach($row_c as $record) {
		if (($destinations==0&&$bclient!=2)||(!in_array($record[7],$email_list)&&$record[7]=="")) {
			$testing[] = $record; 
			$email_list[] = $record[7]; 
		}
	}
	
	
	// end new

array_multisort($timeSt, SORT_DESC, $names, SORT_ASC);
/*$survey_id = array();
	$dates_stamp = array();
	$survey_type = array();
	$firstname = array();
	$lastname = array();
	$email = array();
	$telephone = array();
	$address_1 = array();
	$address_2 = array();
	$address_3town = array();
	$country = array();
	$postcode = array();
	$commentsdate = array();
	$getlatestoffers = array();
	$county = array();
	$preferred_day = array();
	$preferred_time = array();
	$callback = array();
	$subject = array();
	$hearaboutus = array();
	$recommended_us = array();
	$bride_nationality = array();
	$bride_country = array();
	$groom_firstname = array();
	$groom_lastname = array();
	$groom_email = array();
	$groom_telephone = array();
	$groom_nationality = array();
	$groom_country = array();
	$estimated_date_of_wedding = array();
	$anticipated_number_of_guests = array();
	$type_of_ceremony = array();
	$prefered_ceremony_setting = array();
	$prefered_reception_setting = array();
	$estimated_budget = array();
	*/
	
$date = date("d-m-Y",$time);
$file_name = "data-capture".$date.".csv";


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_name);

// create a file pointer connected to the output stream

$output = fopen('php://output', 'w');






//output the column headings
$csvheadings = array('#','IWCUID','Enquiry date','Type','firstname','lastname','email','telephone','address_1','address_2','address_3','town','postcode','county','country','date','getlatestoffers','preferred_day','callback','hearaboutus','recommended_us','bride_nationality','bride_country','groom_firstname','groom_lastname','groom_email','groom_telephone','groom_nationality','groom_country','estimated_date_of_wedding','anticipated_number_of_guests','type_of_ceremony','prefered_ceremony_setting','prefered_reception_setting','estimated_budget');
fputcsv($output, $csvheadings);

//$record = str_replace("\"","'",$record);
//$record = str_replace("\'","'",$record);
	
	
foreach ($testing as $record) {
	$surveyid = stripslashes($record[0]);
	
	
	$dateline = date("jS F Y",$record[1]);
	$type = stripslashes($record[2]);
	$destination = ($destination) ? $destination : "Unknown";
	
	$firstname = stripslashes($record[3]);
	$lastname = stripslashes($record[4]);
	$email = stripslashes($record[5]);
	$telephone = stripslashes($record[6]);
	$address_1 = stripslashes($record[7]);
	$address_2 = stripslashes($record[8]);
	$address_3town = stripslashes($record[9]);
	$town = stripslashes($record[10]);
	$postcode = stripslashes($record[12]);
	$county = stripslashes($record[15]);
	$country = stripslashes($record[11]);
	
	$getlatestoffers = stripslashes($record[14]);
	$preferred_day = stripslashes($record[16]);
	
	$callback = stripslashes($record[17]);
	$hearaboutus = stripslashes($record[18]);
	$recommended_us = stripslashes($record[19]);
	$bride_nationality = stripslashes($record[20]);
	$bride_country = stripslashes($record[21]);
	$groom_firstname = stripslashes($record[22]);
	$groom_lastname = stripslashes($record[23]);
	$groom_email = stripslashes($record[24]);
	$groom_telephone = stripslashes($record[25]);
	$groom_nationality = stripslashes($record[26]);
	$groom_country = stripslashes($record[27]);
	$estimated_date_of_wedding = stripslashes($record[28]);
	$anticipated_number_of_guests = stripslashes($record[29]);
	$type_of_ceremony = stripslashes($record[30]);
	$prefered_ceremony_setting = stripslashes($record[31]);
	$prefered_reception_setting = stripslashes($record[32]);
	$estimated_budget = stripslashes($record[33]);		
		
	switch($record[2]) {
		case "wedding":
			$clientid = (!isset($clients_id["contact"][$surveyid])) ? 0 : $clients_id['wedding'][$surveyid];
			$iwcuid_id = (!isset($iwcuids_id["wedding"][$surveyid])) ? 0 : $iwcuids_id["wedding"][$surveyid];
			$destinations = $sql_command->maxid("menu_navigation,questionaire_dest WHERE questionaire_dest.id='".addslashes($surveyid)."' AND menu_navigation.id = questionaire_dest.destination_id","menu_navigation.page_name");
			break;
		
		case "callback":
			$client_id = (!isset($clients_id["callback"][$surveyid])) ? 0 : $clients_id["callback"][$surveyid];
			$iwcuid_id = (!isset($iwcuids_id["callback"][$surveyid])) ? 0 : $iwcuids_id["callback"][$surveyid];
			
			$destinations = "Unknown";
			break;
		
		case "contact":	
			$client_id = (!isset($clients_id["contact"][$surveyid])) ? 0 : $clients_id["contact"][$surveyid];
			$iwcuid_id = (!isset($iwcuids_id["contact"][$surveyid])) ? 0 : $iwcuids_id["contact"][$surveyid];
			$destinations = "Unknown";
			break;
		
		case "personal":	
			$client_id = (!isset($clients_id["personal"][$surveyid])) ? 0 : $clients_id["personal"][$surveyid];
			$iwcuid_id = (!isset($iwcuids_id["personal"][$surveyid])) ? 0 : $iwcuids_id["personal"][$surveyid];
			$destinations = "Unknown";
			break;			
	}
	

$csvoutput = array("".$surveyid."","".$iwcuid_id."","".$dateline."","".$type."","".$firstname."","".$lastname."","".$email."","".$telephone."","".$address_1."","".$address_2."","".$address_3town."","".$town."","".$postcode."","".$county."","".$country."","".$date."","".$getlatestoffers."","".$preferred_day."","".$callback."","".$hearaboutus."","".$recommended_us."","".$bride_nationality."","".$bride_country."","".$groom_firstname."","".$groom_lastname."","".$groom_email."","".$groom_telephone."","".$groom_nationality."","".$groom_country."","".$estimated_date_of_wedding."","".$anticipated_number_of_guests."","".$type_of_ceremony."","".$prefered_ceremony_setting."","".$prefered_reception_setting."","".$estimated_budget."");	
fputcsv($output, $csvoutput);		
	
}


	
	/*
	$survey_id[] = $surveyid;
	$dates_stamp[] = $dateline;
	$survey_type[] = $record[2];
	$firstname[] = $record[5];
	$lastname[] = $record[6];
	$email[] = $record[7];
	$telephone[] = $record[8];
	$address_1[] = $record[9];
	$address_2[] = $record[10];
	$address_3town[] = $record[11];
	$country[] = $record[12];
	$postcode[] = $record[13];
	$commentsdate[] = $record[14];
	$getlatestoffers[] = $record[15];
	$county[] = $record[16];
	$preferred_day[] = $record[17];
	$preferred_time[] = $record[18];
	$callback[] = $record[19];
	$subject[] = $record[20];
	$hearaboutus[] = $record[21];
	$recommended_us[] = $record[22];
	$bride_nationality[] = $record[23];
	$bride_country[] = $record[24];
	$groom_firstname[] = $record[25];
	$groom_lastname[] = $record[26];
	$groom_email[] = $record[27];
	$groom_telephone[] = $record[28];
	$groom_nationality[] = $record[29];
	$groom_country[] = $record[30];
	$estimated_date_of_wedding[] = $record[31];
	$anticipated_number_of_guests[] = $record[32];
	$type_of_ceremony[] = $record[33];
	$prefered_ceremony_setting[] = $record[34];
	$prefered_reception_setting[] = $record[35];
	$estimated_budget[] = $record[36];						
	*/
?>

