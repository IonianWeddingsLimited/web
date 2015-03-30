<?

$cols = "order_id,cost,iw_cost,vat,status,timestamp,type,updated_timestamp";


$poundcost = $_POST["deposit"];


$sql_command->insert($database_customer_invoices,$cols,"'".addslashes($_POST["invoice_id"])."','','".addslashes($poundcost)."','20.00','Pending','$time','Deposit','".$time."'");
$maxid = $sql_command->maxid($database_customer_invoices,"id","");

$sql_command->update($database_order_history,"iw_cost='".addslashes($_POST["deposit"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Deposit'");
$sql_command->update($database_order_history,"currency='".addslashes($_POST["currency"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Deposit'");
$sql_command->update($database_order_history,"exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Deposit'");
$sql_command->update($database_order_history,"status='Pending'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Deposit'");

$order_result = $sql_command->select($database_order_history,"*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type='Deposit'");
$order_record = $sql_command->result($order_result);

$invoice_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,code,invoice_id";

$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($order_record[2])."',
'".addslashes($order_record[3])."',
'1',
'0',
'".addslashes($_POST["deposit"])."',
'".addslashes($order_record[7])."',
'".addslashes($order_record[8])."',
'".addslashes($order_record[9])."',
'".addslashes($order_record[10])."',
'".addslashes($order_record[11])."',
'Deposit',
'Pending',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".addslashes($order_record[16])."',
'".addslashes($order_record[17])."',
'".addslashes($order_record[18])."',
'".$maxid."'";

$sql_command->insert($database_invoice_history,$invoice_history_cols,$values);
$maxid = $sql_command->maxid($database_invoice_history,"id","");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Deposit Invoice Created (# ".$maxid.")','".$time."'");


header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();



?>