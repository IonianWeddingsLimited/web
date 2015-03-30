<?


if(!$_POST["order_id"]) { $error .= "Missing Order ID<br>"; }
if($_POST["order_id"] <= 0) { $error .= "Invalid Order ID<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","$error","Link","oos/manage-prospect.php?a=create-invoice&id=".$_POST["client_id"]."&invoice_id=".$_POST["order_id"]);
$get_template->bottomHTML();
$sql_command->close();
}

$the_username = $_SESSION["admin_area_username"];
$the_password = $_SESSION["admin_area_password"];
$login_result = $sql_command->select($database_users,"account_option,id","WHERE username='".addslashes($the_username)."' and password='".addslashes($the_password)."'");
$login_record = $sql_command->result($login_result);
$login_id = $login_record[1];


$inv_id = $_POST['addInvoice'];
$suppliers_select = $_POST['supplier_l'];
$suppliers_listing = $_POST['suppliers'];
$note_text = $_POST['aClient'];
$client_show = (isset($_POST['clientsCShow'])) ? $_POST['clientsCShow'] : "No";
$cols = "note_primary_reference, note_secondary_reference, note_type, note, extra, userid";

$vals = "'".$_POST["order_id"]."',
	'".$inv_id."',
	'ProformaComment',
	'".$note_text."',
	'".$client_show."',
	'".addslashes($login_id)."'";

$supplier_query	= (strlen($note_text)>3) ? $sql_command->insert("notes",$cols,$vals) : "";

$note_text = $_POST['aSupplier'];
$vals = "'".addslashes($_POST['order_id'])."',
'".$suppliers_select."',
'SupplierComment',
'".$note_text."',
'Yes',
'".addslashes($login_id)."'";
$supplier_query	= (strlen($note_text)>3) ? $sql_command->insert("notes",$cols,$vals) : "";















$notes_q=$sql_command->SELECT("notes,supplier_details","notes.note,notes.extra,notes.notes_id,supplier_details.company_name,notes.userid,notes.timestamp","WHERE notes.note_primary_reference = '".addslashes($_POST['order_id'])."' AND notes.note_secondary_reference = supplier_details.id AND notes.note_type = 'SupplierComment' AND extra!='Cancelled'");
$notes_r = $sql_command->results($notes_q);

foreach ($notes_r as $nr) {
	$s_check = "ucSupplier_".$nr[2];
	$ds_check = "dcSupplier_".$nr[2];
	$supplier = $_POST[$s_check];
	$dsupplier = $_POST[$ds_check];
	if ($supplier) {
		$supplier_query	= $sql_command->update("notes","note='".$supplier."',userid='".$login_id."'","notes_id='".addslashes($nr[2])."'");
	}
}

$notes_q=$sql_command->SELECT("notes","notes.note,notes.extra,notes.notes_id,notes.note_secondary_reference,notes.timestamp,userid","WHERE notes.note_primary_reference = '".addslashes($_POST['order_id'])."' AND notes.note_type = 'ProformaComment' AND extra!='Cancelled'");
$notes_r = $sql_command->results($notes_q);
foreach ($notes_r as $nr) {
	$i_check = "ucClient_".$nr[2];
	$invoice = $_POST[$i_check];
	$di_check = "dcClient_".$nr[2];
	$dinvoice = $_POST[$di_check];
	if ($invoice) {
		$invoice_query	= $sql_command->update("notes","note='".$invoice."',userid='".$login_id."'","notes_id='".addslashes($nr[2])."'");

	}
}

$del_c = $_POST['delete'];
foreach ($del_c as $dc) {
	$invoice_query	= $sql_command->update("notes","extra='Cancelled'","notes_id='".addslashes($dc)."'");	
}


header("Location: $site_url/oos/manage-prospect.php?a=create-invoice&id=".$_POST["client_id"]."&invoice_id=".$_POST["order_id"]."&cu=true");
//header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();

?>