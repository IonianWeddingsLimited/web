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

if($_POST["action"] == "Update") {
header("Location: $site_url/oos/destination-report-2.php?island_id=".$_POST["island_id"]."&year=".$_POST["year"]);
$sql_command->close();
}

if($_GET["island_id"] and $_GET["year"]) {
$_POST["action"] = "Continue";
$_POST["year"] = $_GET["year"];
$_POST["island_id"] = $_GET["island_id"];
}


if($_POST["action"] == "Continue") {
$_POST["action"] = "Continue";
$_GET["year"] = $_POST["year"];
$_GET["island_id"] = $_POST["island_id"];


$current_year = 2011;
$end_year = date("Y",$time);
$end_year++;
$end_year2 = $end_year + 15;
while($current_year < $end_year2) {
if($_GET["year"] == $current_year) { $selected = "selected=\"selected\""; } else { $selected = ""; }
$list_years .= "<option value=\"".$current_year."\" $selected>".$current_year."</option>";	
$current_year++;
}

if(!$_GET["year"]) { $_GET["year"] = $end_year-1; }

$april_start = mktime(0, 0, 0, 4, 1, $_GET["year"]);
$may_start = mktime(0, 0, 0, 5, 1, $_GET["year"]);
$june_start = mktime(0, 0, 0, 6, 1, $_GET["year"]);
$july_start = mktime(0, 0, 0, 7, 1, $_GET["year"]);
$august_start = mktime(0, 0, 0, 8, 1, $_GET["year"]);
$september_start = mktime(0, 0, 0, 9, 1, $_GET["year"]);
$october_start = mktime(0, 0, 0, 10, 1, $_GET["year"]);
$november_start = mktime(0, 0, 0, 11, 1, $_GET["year"]);
$december_start = mktime(0, 0, 0, 12, 1, $_GET["year"]);
$january_start = mktime(0, 0, 0, 1, 1, $_GET["year"]+1);
$february_start = mktime(0, 0, 0, 2, 1, $_GET["year"]+1);
$march_start = mktime(0, 0, 0, 3, 1, $_GET["year"]+1);
$april2_start = mktime(0, 0, 0, 4, 1, $_GET["year"]+1);


$april_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $april_start and 
									 $database_clients.wedding_date < $may_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$april_rows = $sql_command->results($april_result);

foreach($april_rows as $april_record) {
$april_cost += $april_record[1];
$april_iwcost += $april_record[2];
$april_vat += ($april_record[2] / 100) * $april_record[4];
}
$april_profit = $april_iwcost - $april_cost;


$may_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $may_start and 
									 $database_clients.wedding_date < $june_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$may_rows = $sql_command->results($may_result);

foreach($may_rows as $may_record) {
$may_cost += $may_record[1];
$may_iwcost += $may_record[2];
$may_vat += ($may_record[2] / 100) * $may_record[4];
}
$may_profit = $may_iwcost - $may_cost;


$june_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $june_start and 
									 $database_clients.wedding_date < $july_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$june_rows = $sql_command->results($june_result);

foreach($june_rows as $june_record) {
$june_cost += $june_record[1];
$june_iwcost += $june_record[2];
$june_vat += ($june_record[2] / 100) * $june_record[4];
}
$june_profit = $june_iwcost - $june_cost;


$july_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $july_start and 
									 $database_clients.wedding_date < $august_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$july_rows = $sql_command->results($july_result);

foreach($july_rows as $july_record) {
$july_cost += $july_record[1];
$july_iwcost += $july_record[2];
$july_vat += ($july_record[2] / 100) * $july_record[4];
}
$july_profit = $july_iwcost - $july_cost;


$august_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $august_start and 
									 $database_clients.wedding_date < $september_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$august_rows = $sql_command->results($august_result);

foreach($august_rows as $august_record) {
$august_cost += $august_record[1];
$august_iwcost += $august_record[2];
$august_vat += ($august_record[2] / 100) * $august_record[4];
}
$august_profit = $august_iwcost - $august_cost;



$september_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $september_start and 
									 $database_clients.wedding_date < $october_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$september_rows = $sql_command->results($september_result);

foreach($september_rows as $september_record) {
$september_cost += $september_record[1];
$september_iwcost += $september_record[2];
$september_vat += ($september_record[2] / 100) * $september_record[4];
}
$september_profit = $september_iwcost - $september_cost;



$october_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $october_start and 
									 $database_clients.wedding_date < $november_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$october_rows = $sql_command->results($october_result);

foreach($october_rows as $october_record) {
$october_cost += $october_record[1];
$october_iwcost += $october_record[2];
$october_vat += ($october_record[2] / 100) * $october_record[4];
}
$october_profit = $october_iwcost - $october_cost;



$november_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $november_start and 
									 $database_clients.wedding_date < $december_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$november_rows = $sql_command->results($november_result);

foreach($november_rows as $november_record) {
$november_cost += $november_record[1];
$november_iwcost += $november_record[2];
$november_vat += ($november_record[2] / 100) * $november_record[4];
}
$november_profit = $november_iwcost - $november_cost;



$december_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $december_start and 
									 $database_clients.wedding_date < $january_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$december_rows = $sql_command->results($december_result);

foreach($december_rows as $december_record) {
$december_cost += $december_record[1];
$december_iwcost += $december_record[2];
$december_vat += ($december_record[2] / 100) * $december_record[4];
}
$december_profit = $december_iwcost - $december_cost;



$january_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $january_start and 
									 $database_clients.wedding_date < $february_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$january_rows = $sql_command->results($january_result);

foreach($january_rows as $january_record) {
$january_cost += $january_record[1];
$january_iwcost += $january_record[2];
$january_vat += ($january_record[2] / 100) * $january_record[4];
}
$january_profit = $january_iwcost - $january_cost;


$february_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $february_start and 
									 $database_clients.wedding_date < $march_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$february_rows = $sql_command->results($february_result);

foreach($february_rows as $february_record) {
$february_cost += $february_record[1];
$february_iwcost += $february_record[2];
$february_vat += ($february_record[2] / 100) * $february_record[4];
}
$february_profit = $february_iwcost - $february_cost;


$march_result = $sql_command->select("$database_order_details,$database_clients,$database_package_info,$database_packages","$database_order_details.id,
									 $database_order_details.total_cost,
									 $database_order_details.total_iw_cost,
									 $database_order_details.user_id,
									 $database_order_details.vat,
									 $database_clients.wedding_date									 
									 ","WHERE $database_clients.wedding_date > $march_start and 
									 $database_clients.wedding_date < $april2_start and 
									 $database_order_details.client_id=$database_clients.id and 
									 $database_order_details.package_id=$database_package_info.id and 
									 $database_package_info.package_id=$database_packages.id and 
									 $database_packages.island_id='".addslashes($_GET["island_id"])."'");
$march_rows = $sql_command->results($march_result);

foreach($march_rows as $march_record) {
$march_cost += $march_record[1];
$march_iwcost += $march_record[2];
$march_vat += ($march_record[2] / 100) * $march_record[4];
}
$march_profit = $march_iwcost - $march_cost;



$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE id='".addslashes($_GET["island_id"])."'");
$level1_record = $sql_command->result($level1_result);
	
	
$get_template->topHTML();
?>
<h1>Revenue of Bookings by Wedding Date</h1>

<form action="<?php echo $site_url; ?>/oos/destination-report-2.php" method="POST" >
<input type="hidden" name="island_id" value="<?php echo $_GET["island_id"]; ?>" />
<div style="float:left; width:100px; margin:5px;"><strong>Year of Weddings</strong></div>
<div style="float:left;  margin:5px;"><select name="year">
<?php echo $list_years; ?>
</select> <input type="submit" name="action" value="Update" /> - <a href="<?php echo $site_url; ?>/oos/destination-report-download-2.php?island_id=<?php echo $_GET["island_id"]; ?>&year=<?php echo $_GET["year"]; ?>">Download Report</a></div>
<div style="clear:left;"></div>
</form>
<p><hr /></p>
<p><strong>Current Year:</strong> <?php echo $_GET["year"]; ?><br />
<strong>Destination:</strong> <?php echo $level1_record[1]; ?></p>


<div style="float:left; width:150px; margin:5px; margin-top:20px;">&nbsp;</div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>Cost</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>IW Cost</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>VAT</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>Profit</strong></div>
<div style="clear:left;"></div>
<hr /></p>
<div style="float:left; width:150px; margin:5px;"><strong>April <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>May <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($may_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($may_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($may_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($may_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>June <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($june_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($june_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($june_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($june_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>July <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>August <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($august_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($august_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($august_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($august_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>September <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($september_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($september_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($september_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($september_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>October <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>November <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($november_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($november_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($november_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($november_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>December <?php echo $_GET["year"]; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($december_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($december_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($december_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($december_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>January <?php echo $_GET["year"]+1; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($jauary_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>February <?php echo $_GET["year"]+1; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($february_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($february_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($february_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($february_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>March <?php echo $_GET["year"]+1; ?></strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($march_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($march_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($march_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($march_profit,2); ?></div>
<div style="clear:left;"></div>
<p><hr /></p>
<div style="float:left; width:150px; margin:5px;"><strong>First Quarter</strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_cost+$may_cost+$june_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_iwcost+$may_iwcost+$june_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_vat+$may_vat+$june_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_profit+$may_profit+$june_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Second Quarter</strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_cost+$august_cost+$september_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_iwcost+$august_iwcost+$september_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_vat+$august_vat+$september_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($july_profit+$august_profit+$september_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Third Quarter</strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_cost+$november_cost+$december_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_iwcost+$november_iwcost+$december_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_vat+$november_vat+$december_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($october_profit+$november_profit+$december_profit,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Fourth Quarter</strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_cost+$february_cost+$march_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_iwcost+$february_iwcost+$march_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_vat+$february_vat+$march_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($january_profit+$february_profit+$march_profit,2); ?></div>
<div style="clear:left;"></div>

<p><hr /></p>
<div style="float:left; width:150px; margin:5px;"><strong>Total</strong></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_cost+$may_cost+$june_cost+$july_cost+$august_cost+$september_cost+$october_cost+$november_cost+$december_cost+$january_cost+$february_cost+$march_cost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_iwcost+$may_iwcost+$june_iwcost+$july_iwcost+$august_iwcost+$september_iwcost+$october_iwcost+$november_iwcost+$december_iwcost+$january_iwcost+$february_iwcost+$march_iwcost,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_vat+$may_vat+$june_vat+$july_vat+$august_vat+$september_vat+$october_vat+$november_vat+$december_vat+$january_vat+$february_vat+$march_vat,2); ?></div>
<div style="float:left; width:100px; margin:5px;">&pound; <?php echo number_format($april_profit+$may_profit+$june_profit+$july_profit+$august_profit+$september_profit+$october_profit+$november_profit+$december_profit+$january_profit+$february_profit+$march_profit,2); ?></div>
<div style="clear:left;"></div>

<?
$get_template->bottomHTML();
$sql_command->close();

} else {
	
	
	
	
	$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}


$get_template->topHTML();
?>
<h1>Revenue of Bookings by Wedding Date</h1>

<form action="<?php echo $site_url; ?>/oos/destination-report-2.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
	
	
	
}

?>