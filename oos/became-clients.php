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
	
	$get_template->topHTML();
	$clients_q = "select COALESCE(wq.id,\"N/A\") as wedding,COALESCE(pc.id,\"N/A\") as personal,COALESCE(cu.id,\"N/A\") as contactus,COALESCE(cb.id,\"N/A\") as callback, COALESCE(wq.timestamp,\"\") as weddingt,COALESCE(pc.timestamp,\"\") as personalt,COALESCE(cu.timestamp,\"\") as contactust,COALESCE(cb.timestamp,\"\") as callbackt, c.id, c.iwcuid, c.title, c.firstname, c.lastname, c.wedding_date, c.tel FROM clients as c LEFT OUTER JOIN info_wedding_questionnaire as wq ON (wq.bride_firstname = c.firstname AND wq.bride_lastname = c.lastname AND wq.bride_email = c.email) OR (wq.groom_firstname = c.groom_firstname AND wq.groom_lastname = c.groom_surname AND wq.groom_email = c.groom_email) LEFT OUTER JOIN	info_personal_consultations as pc ON (pc.firstname = c.firstname AND pc.lastname = c.lastname AND pc.email = c.email) OR (pc.firstname = c.groom_firstname AND pc.lastname = c.groom_surname AND pc.email = c.groom_email) LEFT OUTER JOIN info_contactus as cu ON (cu.firstname = c.firstname AND cu.lastname = c.lastname AND cu.email = c.email) OR (cu.firstname = c.groom_firstname AND cu.lastname = c.groom_surname AND cu.email = c.groom_email) LEFT OUTER JOIN info_bookacallback as cb ON (cb.firstname = c.firstname AND cb.lastname = c.lastname AND cb.email = c.email) OR (cb.firstname = c.groom_firstname AND cb.lastname = c.groom_surname AND cb.email = c.groom_email) ORDER BY c.id DESC";

	$clients_r = mysql_query($clients_q) or die(mysql_error());
	
	$wedding_ids = array();
	$personal_ids = array();
	$contact_ids = array();
	$callback_ids = array();
	$iwcuid_ids = array();
	$client_ids = array();
		
	$output_p = "<table width=\"100%\"><tr><td colspan=\"3\"><center><h4>Client Details</h4></center></td><td colspan=\"4\"><center><h4>Forms</h4></center></td></tr><tr><td>Firstname</td><td>Lastname</td><td>Wedding Date</td><td><center>Wedding</center></td><td><center>Personal</center></td><td><center>Call back</center></td><td><center>Contact Us</center></td></tr>";
	while ($row = @mysql_fetch_row($clients_r)) {
		$ci=0;
		$clientid = $row[8];
		$iwcuid = $row[9];
		$title = $row[10];
		$firstname = $row[11];
		$lastname = $row[12];
		$weddingd = date("d/m/Y",$row[13]);
		$tel = $row[14];
		
		if ($row[0]!="N/A") { $wedding_ids[] = $row[0]; $ci++; }
		if ($row[1]!="N/A") { $personal_ids[] = $row[1]; $ci++; }
		if ($row[2]!="N/A") { $contact_ids[] = $row[2]; $ci++; }
		if ($row[3]!="N/A") { $callback_ids[] = $row[3]; $ci++; }
		$wqdate = "<a href=\"$site_url/oos/wedding-questionnaire.php?action=Continue&id=$row[0]\">".date("d/m/Y",$row[4])."</a>";
		$pcdate = "<a href=\"$site_url/oos/personal_consultations.php?action=Continue&id=$row[1]\">".date("d/m/Y",$row[5])."</a>";
		$cudate = "<a href=\"$site_url/oos/contact-us.php?action=Continue&id=$row[2]\">".date("d/m/Y",$row[6])."</a>";
		$cbdate = "<a href=\"$site_url/oos/book-a-callback.php?action=Continue&id=$row[3]\">".date("d/m/Y",$row[7])."</a>";
		$row[0] = str_replace("N/A","",$row[0]);
		$row[1] = str_replace("N/A","",$row[1]);
		$row[2] = str_replace("N/A","",$row[2]);
		$row[3] = str_replace("N/A","",$row[3]);
		if ($ci>0) { 
		$fn_address = "<a href=\"$site_url/oos/manage-client.php?a=history&id=$clientid\">$firstname</a>";
		$ln_address = "<a href=\"$site_url/oos/manage-client.php?a=history&id=$clientid\">$lastname</a>";
		$wd_address = "<a href=\"$site_url/oos/manage-client.php?a=history&id=$clientid\">$weddingd</a>";
		
			$output_p .= (!in_array($clientid,$ids)) ? "<tr><td>$fn_address</td><td>$ln_address</td><td>$wd_address</td><td><center>$wqdate</center></td><td><center>$pcdate</center></td><td><center>$cbdate</center></td><td><center>$cudate</center></td></tr>" : "<tr><td colspan=\"3\"><td><center>$wqdate</center></td><td><center>$pcdate</center></td><td><center>$cbdate</center></td><td><center>$cudate</center></td></tr>"; 
		}
		$ids[] = $clientid;
	}
	$output_p .= "</table>";
	$bclient=$_POST["bclient"];
	
	$wq_ids = implode(",",$wedding_ids);
	$pc_ids = implode(",",$personal_ids);
	$cu_ids = implode(",",$contact_ids);
	$cb_ids = implode(",",$callback_ids);
	
	$wq_filter_line = $pc_filter_line = $cu_filter_line = $cb_filter_line = "";
	
	if ($bclient==2) {
		$wq_filter_line = " AND $database_info_wedding_questionnaire.id IN ('".addslashes($wq_ids)."')";
		$pc_filter_line = " AND $database_info_wedding_questionnaire.id IN ('".addslashes($pc_ids)."')";
		$cu_filter_line = " AND $database_info_wedding_questionnaire.id IN ('".addslashes($cu_ids)."')";
		$cb_filter_line = " AND $database_info_wedding_questionnaire.id IN ('".addslashes($cb_ids)."')";
	}
	if ($bclient==3) {
		$wq_filter_line = " AND $database_info_wedding_questionnaire.id NOT IN ('".addslashes($wq_ids)."')";
		$pc_filter_line = " AND $database_info_wedding_questionnaire.id NOT IN ('".addslashes($pc_ids)."')";
		$cu_filter_line = " AND $database_info_wedding_questionnaire.id NOT IN ('".addslashes($cu_ids)."')";
		$cb_filter_line = " AND $database_info_wedding_questionnaire.id NOT IN ('".addslashes($cb_ids)."')";
	}
	
	//echo $wq_filter_line."<br />";
	//echo $pc_filter_line."<br />";
	//echo $cu_filter_line."<br />";
	//echo $cb_filter_line."<br />";
	//echo $wq_ids."<br />";
	//echo $pc_ids."<br />";
	//echo $cu_ids."<br />";
	//echo $cb_ids."<br />";
//echo "hi"."<br />";
	//print($row)."<br />";
	echo $output_p;


$get_template->bottomHTML();
$sql_command->close();


?>