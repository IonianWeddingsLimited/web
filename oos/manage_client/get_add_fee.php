<?


if(!$_POST["exchange_rate"]) { $error .= "Missing exchange rate<br>"; }
if($_POST["exchange_rate"] <= 0) { $error .= "Please enter an exchange rate greater than 0<br>"; }
if(!$_POST["gross_cost"]) { $error .= "Missing deposit rate<br>"; }
if($_POST["gross_cost"] <= 0) { $error .= "Please enter a deposit greater than 0<br>"; }


if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php?a=deposit&id=".$_POST["client_id"]."&invoice_id=".$_POST["invoice_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

$sql_command->update($database_order_details,"exchange_rate='".addslashes($_POST["exchange_rate"])."'","id='".addslashes($_POST["invoice_id"])."'");


$cols = "order_id,cost,iw_cost,vat,status,timestamp,type,updated_timestamp,exchange_rate";


$poundcost = $_POST["gross_cost"];

if($_POST["gross_currency"] == "Pound") {
$total_due = $poundcost;
} else {
$total_due = $poundcost / $_POST["exchange_rate"];
}

$extra_result = $sql_command->select($database_package_extras,"product_name,code","WHERE id='".addslashes($_POST["feetype_id"])."'");
$extra_record = $sql_command->result($extra_result);

$sql_command->insert($database_customer_invoices,$cols,"'".addslashes($_POST["invoice_id"])."','".$_POST["net_cost"]."','".addslashes($total_due)."','20.00','Pending','$time','".addslashes($extra_record[0])."','".$time."','".addslashes($_POST["exchange_rate"])."'");
$maxid = $sql_command->maxid($database_customer_invoices,"id","");

$sql_command->update($database_order_history,"iw_cost='".addslashes($_POST["gross_cost"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='".addslashes($extra_record[0])."'");
$sql_command->update($database_order_history,"currency='".addslashes($_POST["gross_currency"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='".addslashes($extra_record[0])."'");
$sql_command->update($database_order_history,"exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='".addslashes($extra_record[0])."'");
$sql_command->update($database_order_history,"status='Pending'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='".addslashes($extra_record[0])."'");

$order_result = $sql_command->select($database_order_history,"*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type='".addslashes($extra_record[0])."'");
$order_record = $sql_command->result($order_result);

$order_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,code,invoice_id";
$order_history_vals = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($_POST["feetype_id"])."',
'".addslashes($extra_record[0])."',
'1',
'".addslashes($_POST["net_cost"])."',
'".addslashes($_POST["gross_cost"])."',
'0.00',
'0',
'0.00',
'".addslashes($_POST["gross_currency"])."',
'Deposit',
'Extra',
'Invoice Issued',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'0',
'',
'".addslashes($extra_record[1])."',
'".$maxid."'";

$sql_command->insert($database_order_history,$order_history_cols,$order_history_vals);

$invoice_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,code,invoice_id";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($_POST["feetype_id"])."',
'".addslashes($extra_record[0])."',
'1',
'".addslashes($_POST["net_cost"])."',
'".addslashes($_POST["gross_cost"])."',
'0.00',
'0',
'0.00',
'".addslashes($_POST["gross_currency"])."',
'Deposit',
'Extra',
'Invoice Issued',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".addslashes($order_record[16])."',
'".addslashes($order_record[17])."',
'".addslashes($extra_record[1])."',
'".$maxid."'";

$sql_command->insert($database_invoice_history,$invoice_history_cols,$values);


$supplier_invoices_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp,invoice_id";
$supplier_invoices_vals = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($_POST["supplier"])."',
'".addslashes($extra_record[0])."',
'1',
'".addslashes($_POST["net_cost"])."',
'".addslashes($_POST["gross_cost"])."',
'".addslashes($_POST["net_currency"])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".$maxid."'";
$sql_command->insert($database_supplier_invoices,$supplier_invoices_cols,$supplier_invoices_vals);

$supplier_invoices_main_cols	=	"order_id,supplier_id,invoice_id,timestamp,status,exchange_rate";
$supplier_invoices_main_vals	=	"'".addslashes($_POST["invoice_id"])."',
									'".addslashes($_POST["supplier"])."',
									'".$maxid."',
									'".$time."',
									'Outstanding',
									'".addslashes($_POST["exchange_rate"])."'";
$sql_command->insert($database_supplier_invoices_main,$supplier_invoices_main_cols,$supplier_invoices_main_vals);

$maxmainid = $sql_command->maxid($database_supplier_invoices_main,"id","");

$supplier_invoice_details_cols	=	"main_id, name, qty, cost, iw_cost, currency, code, order_id, invoice_id";
$supplier_invoice_details_vals	=	"'".addslashes($maxmainid)."',
'".addslashes($extra_record[0])."',
'1',
'".addslashes($_POST["net_cost"])."',
'".addslashes($_POST["gross_cost"])."',
'".addslashes($_POST["net_currency"])."',
'".addslashes($extra_record[1])."',
'".addslashes($_POST["invoice_id"])."',
'".addslashes($maxid)."'";
echo $supplier_invoice_details_cols."<br />";
echo $supplier_invoice_details_vals."<br />";
$sql_command->insert($database_supplier_invoices_details,$supplier_invoice_details_cols,$supplier_invoice_details_vals);


$maxid = $sql_command->maxid($database_invoice_history,"id","");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','". $extra_record[0] ." Invoice Created (# ".$maxid.")','".$time."'");


header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();
?>