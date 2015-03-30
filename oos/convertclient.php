<?
$updateurl = "manage-client.php";
if ($_POST["ctype"]==0&&$_POST["ftype"]=="Prospect") {
	$clientu = $sql_command->update("clients_options","client_option='client_type',option_value='Active'","client_id='".addslashes($_POST["client_id"])."' AND client_option='prospect'");
	
	$orderd_c = "client_id,
				package_id,
				package_cost,
				package_iw_cost,
				package_currency,
				total_paid,
				total_refunded,
				total_cost,
				total_iw_cost,
				exchange_rate,
				user_id,
				vat,
				reception_id,
				ceremony_id";
	// 14
	$orderh_c = "order_id,
				type_id,
				name,
				qty,
				cost,
				iw_cost,
				local_tax_percent,
				discount_at,
				discount_percent,
				currency,
				item_type,
				type,
				exchange_rate,
				d_value,
				d_type,
				d_,
				code";
	// 18
	$orderd_q = $sql_command->select("quotation_details","id,$orderd_c","WHERE client_id='".addslashes($_POST["client_id"])."'");
	$orderd_r = $sql_command->results($orderd_q);

	foreach ($orderd_r as $od) {
		$vals = "'".addslashes($od[1])."',
		'".addslashes($od[2])."',
		'".addslashes($od[3])."',
		'".addslashes($od[4])."',
		'".addslashes($od[5])."',
		'".addslashes($od[6])."',
		'".addslashes($od[7])."',
		'".addslashes($od[8])."',
		'".addslashes($od[9])."',
		'".addslashes($od[10])."',
		'".addslashes($od[11])."',
		'".addslashes($od[12])."',
		'".addslashes($od[13])."',
		'".addslashes($od[14])."'";
		//$debug .= "insert into $database_order_history ($orderd_c) VALUES($vals)";
		$od_i = $sql_command->insert($database_order_details,$orderd_c,$vals);
		$maxid = $sql_command->maxid($database_order_details,"id");
		$orderh_q = $sql_command->select("quotation_history",$orderh_c,"WHERE order_id='".addslashes($od[0])."'");
		$orderh_r = $sql_command->results($orderh_q);
		foreach ($orderh_r as $oh) {
			$debug .= 
			$vals = "'".addslashes($maxid)."',
					'".addslashes($oh[1])."',
					'".addslashes($oh[2])."',
					'".addslashes($oh[3])."',
					'".addslashes($oh[4])."',
					'".addslashes($oh[5])."',
					'".addslashes($oh[6])."',
					'".addslashes($oh[7])."',
					'".addslashes($oh[8])."',
					'".addslashes($oh[9])."',
					'".addslashes($oh[10])."',
					'".addslashes($oh[11])."',
					'".addslashes($oh[13])."',
					'".addslashes($oh[14])."',
					'".addslashes($oh[15])."',
					'".addslashes($oh[16])."',
					'".addslashes($oh[17])."'";
					
			//$debug .= "insert into $database_order_history ($orderh_c) VALUES($vals)";
			$oh_i = $sql_command->insert($database_order_history,$orderh_c,$vals);
		}
	
	}
	$test	=	"true";
} elseif ($_POST["ctype"]==1&&$_POST["ftype"]=="Client") {
	$updateurl = "manage-prospect.php";
	$clientu = $sql_command->update("clients_options","client_option='Prospect',option_value=''","client_id='".addslashes($_POST["client_id"])."' AND client_option='client_type'");
	
	$client_search = $sql_command->select("order_details","id","WHERE client_id='".addslashes($_POST["client_id"])."'");
	$client_results = $sql_command->results($client_search);
	foreach ($client_results as $clr) {
		$sql_command->update($database_supplier_invoices,"status='Cancelled',updated_timestamp='".$time."'","order_id='".addslashes($clr[0])."' and status!='Paid'");
		$sql_command->update($database_customer_invoices,"status='Cancelled',updated_timestamp='".$time."'","order_id='".addslashes($clr[0])."' and status!='Paid'");
	
	}
	$test	=	"false";
}
//print($test);
//header("Location: http://www.ionianweddings.co.uk/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
//$sql_command->close();
?>