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
header("Location: $site_url/oos/hmrc-report-2.php?from=".$_POST["from"]."&to=".$_POST["to"]);
$sql_command->close();
}


if($_GET["from"] and $_GET["to"]) {
list($start_day,$start_month,$start_year) = explode("-",$_GET["from"]);
list($end_day,$end_month,$end_year) = explode("-",$_GET["to"]);


$start = mktime(0, 0, 0, $start_month, $start_day, $start_year);
$end = mktime(0, 0, 0, $end_month, $end_day, $start_year);


//$result = $sql_command->select($database_customer_invoices,"order_id,cost,iw_cost,vat,updated_timestamp,type,included_package","WHERE updated_timestamp > $start and updated_timestamp < $end and status='Paid'");
$result = $sql_command->select("$database_clients,$database_order_details,$database_customer_invoices",
								"$database_customer_invoices.order_id,
								$database_customer_invoices.cost,
								$database_customer_invoices.iw_cost,
								$database_customer_invoices.vat,
								$database_clients.wedding_date,
								$database_customer_invoices.type,
								$database_customer_invoices.included_package",
								"WHERE	$database_clients.id					=	$database_order_details.client_id
								and		$database_order_details.id				=	$database_customer_invoices.order_id
								and		$database_clients.wedding_date			>=	".$start."
								and		$database_clients.wedding_date			<	".$end."
								and		$database_customer_invoices.status		=	'Paid'");
$rows = $sql_command->results($result);

foreach($rows as $record) {
	
$remove_deposit_value = 0;

if($record[6] == "Yes") {
$deposit_check_result = $sql_command->select($database_customer_invoices,"order_id,cost,iw_cost","WHERE status='Paid' and type='Deposit' and order_id='".addslashes($record[0])."'");
$deposit_check_record = $sql_command->result($deposit_check_result);

$remove_deposit_value = $deposit_check_record[2];
}
	
	
$cost = $record[1];
$iwcost = $record[2] - $remove_deposit_value;
$vat = ($iwcost / 100) * $record[3];
$profit = $iwcost - $cost;
$date = date("d-m-Y",$record[4]);

$html .= "<div style=\"float:left; width:150px; margin:5px;\">".$date."</div>
<div style=\"float:left; width:100px; margin:5px;\">".$record[0]."</div>
<div style=\"float:left; width:100px; margin:5px;\">&pound; ".number_format($cost,2)."</div>
<div style=\"float:left; width:100px; margin:5px;\">&pound; ".number_format($iwcost,2)."</div>
<div style=\"float:left; width:100px; margin:5px;\">&pound; ".number_format($vat,2)."</div>
<div style=\"float:left; width:100px; margin:5px;\">&pound; ".number_format($profit,2)."</div>
<div style=\"clear:left;\"></div>";
}


if(!$html) { $html .= "<p>No results found for the date ".$_GET["from"]." to ".$_GET["to"]."</p>"; }
} else {
 $html .= "<p>Please select a date to view transaction history</p>";
}

$get_template->topHTML();
?>
<h1>HMRC Report Per Billing Month</h1>

<form action="<?php echo $site_url; ?>/oos/hmrc-report-2.php" method="post" name="report">
<div style="float:left; width:100px; margin:5px;"><strong>From Date</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="from"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'report',
		// input name
		'controlname': 'from'
	});

	</script></div>
<div style="clear:left;"></div>
<div style="float:left; width:100px; margin:5px;"><strong>To Date</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="to"/>
	<script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'report',
		// input name
		'controlname': 'to'
	});

	</script></div>
<div style="clear:left;"></div>


<p><input type="submit" name="action" value="Update" /> - <a href="<?php echo $site_url; ?>/oos/hmrc-report-2-download.php?from=<?php echo $_GET["from"]; ?>&to=<?php echo $_GET["to"]; ?>">Download Report</a></a>
</form>

<?php if($_GET["from"] and $_GET["to"]) { ?>
<p><hr /></p>
<p><strong>From:</strong> <?php echo $_GET["from"]; ?><br />
<strong>To:</strong> <?php echo $_GET["to"]; ?></p>


<div style="float:left; width:150px; margin:5px; margin-top:20px;"><strong>Wedding Date</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>Order No</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>Cost</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>IW Cost</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>VAT</strong></div>
<div style="float:left; width:100px; margin:5px; margin-top:20px;"><strong>Profit</strong></div>
<div style="clear:left;"></div>
<hr /></p>
<?php } ?>
<?php echo $html; ?>
<?
$get_template->bottomHTML();
$sql_command->close();


?>