<?

$order_details_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_POST["invoice_id"])."'");
$order_details_record = $sql_command->result($order_details_result);

$_POST["exchange_rate"] = $order_details_record[0];

if($_POST["exchange_rate"] < 1) {
$_POST["exchange_rate"] = 1;
}

$order_result = $sql_command->select($database_order_history,"*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type!='Deposit'");
$order_row = $sql_command->results($order_result);


$total_due = 0;

$cols = "order_id,cost,iw_cost,vat,status,timestamp,type,updated_timestamp,included_package";
$invoice_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,d_,code,invoice_id";

$sql_command->insert($database_customer_invoices,$cols,"'".addslashes($_POST["invoice_id"])."','','','20.00','Pending','$time','Order','','No'");
$maxid = $sql_command->maxid($database_customer_invoices,"id","");

$cols = "invoice_id,history_id";


foreach($order_row as $order_record) {

$theitem = "id_".$order_record[0];	

if($_POST[$theitem] == "Yes") { 

if($order_record[17] == "Amount" and $order_record[16] >= 1 and $order_record[18] == "Net") { 
$iw_cost = $order_record[6] - $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] >= 1) { 
$percent_value = ($order_record[6] / 100);
$iw_cost = $order_record[6] - ($percent_value * $order_record[16]);
} else {
$iw_cost = $order_record[6];
}

if($order_record[17] == "Amount" and $order_record[16] >= 1 and $order_record[18] == "Gross") { 
$iw_cost = $iw_cost - abs($order_record[17] / $order_record[4]);
} 

$total_iw_cost_orig = $order_record[4] * $order_record[6];



if($order_record[17] == "Amount" and $order_record[16] >= 1 and $order_record[18] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] >= 1) { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $order_record[16]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}


if($order_record[10] == "Pound") {
$total_due_package += $total_iw_cost;
} else {
$total_due_package += $total_iw_cost / $_POST["exchange_rate"];
}

$total_cost_package += $order_record[5] * $order_record[4];

if($order_record[11] == "Package") {
$sql_command->update($database_customer_invoices,"included_package='Yes'","id='".$maxid."'");
}


$values = "'".addslashes($order_record[1])."',
'".addslashes($order_record[2])."',
'".addslashes($order_record[3])."',
'".addslashes($order_record[4])."',
'".addslashes($order_record[5])."',
'".addslashes($order_record[6])."',
'".addslashes($order_record[7])."',
'".addslashes($order_record[8])."',
'".addslashes($order_record[9])."',
'".addslashes($order_record[10])."',
'".addslashes($order_record[11])."',
'".addslashes($order_record[12])."',
'Invoice Issued',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".addslashes($order_record[16])."',
'".addslashes($order_record[17])."',
'".addslashes($order_record[18])."',
'".addslashes($order_record[19])."',
'".$maxid."'";

$sql_command->insert($database_invoice_history,$invoice_history_cols,$values);
$sql_command->update($database_order_history,"status='Invoice Issued'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
$sql_command->update($database_order_history,"exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
$sql_command->update($database_order_history,"invoice_id='".$maxid."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");

}
}



$sql_command->update($database_customer_invoices,"cost='".stripslashes($total_cost_package)."'","id='".$maxid."'");
$sql_command->update($database_customer_invoices,"iw_cost='".stripslashes($total_due_package)."'","id='".$maxid."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Invoice Created (# ".$maxid.")','".$time."'");











$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp,code,invoice_id,updated_timestamp";



$menu_option_result = $sql_command->select("$database_order_history,$database_menus,$database_menu_options","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.currency,
										   $database_order_history.status,
										   $database_menu_options.code,
										   $database_menus.supplier_id","WHERE 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   $database_order_history.item_type='Menu' and
										   $database_order_history.type_id=$database_menu_options.id and 
										   $database_menu_options.menu_id=$database_menus.id and 
										   $database_menu_options.deleted='No'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[8])."',
'".addslashes($menu_option_record[1])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[4])."',
'".addslashes($menu_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($menu_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	




$package_option_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.currency,
										   $database_order_history.status,
										   $database_package_extras.code,
										   $database_package_extras.supplier_id","WHERE 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   ($database_order_history.item_type='Extra' or $database_order_history.item_type='Service Fee') and
										   $database_order_history.type_id=$database_package_extras.id");
$package_option_row = $sql_command->results($package_option_result);

foreach($package_option_row as $package_option_record) {
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record[8])."',
'".addslashes($package_option_record[1])."',
'".addslashes($package_option_record[2])."',
'".addslashes($package_option_record[3])."',
'".addslashes($package_option_record[4])."',
'".addslashes($package_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($package_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	









header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();

?>