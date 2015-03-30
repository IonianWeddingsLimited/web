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

$add_header .= "<script type=\"text/javasccript\">

$(function() {

$(\"#e_download\").on(\"click\", function(e){
    e.preventDefault();
    $('#download_email').attr('action', \"$site_url/oos/download-purchase-order.php\").submit();
});
$(\"#d_download\").on(\"click\", function(e){
    e.preventDefault();
    $('#download_email').attr('action', \"$site_url/oos/manage-supplier-po-v2.php\").submit();
});

});

</script>
";

$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

if($_GET["id"]) {
$_GET["id"] = $_GET["id"];
$_GET["action"] = "Continue";
}

if ($_GET['a']==="partpay") {
include("manage_client/get_partpay-po.php");
$get_template->bottomHTML();
$sql_command->close();
}
if ($_GET['a']==="email") {
include("manage_client/get_email_po.php");
$get_template->bottomHTML();
$sql_command->close();
}
if($_POST["action"] == "Add Payment") {
include("manage_client/get_add_payment-po.php");
}
if($_POST["action"] == "Modify Payment") {
include("manage_client/get_add_payment-po.php");
}

if($_POST["action"] == "Approve Payments") {
include("manage_client/modify_payments-po.php");
}
if($_POST["action"] == "Delete Payments") {
include("manage_client/modify_payments-po.php");
}
if($_POST["action"] == "Email Selected Purchase Orders") {
include("manage_client/get_email_supplier_po.php");
}
if ($_POST["action"]=="Email PO to Suppliers") {
include("manage_client/get_email_supplier_po.php");
}


if($_GET["action"] == "Continue" or $_GET["action"] == "Update" or $_GET["a"] == "Update Status") {
	
if($_GET["a"] == "Update Status") {
$sql_command->update($database_supplier_invoices_main,"status='".addslashes($_GET["status"])."'","id='".addslashes($_GET["po_id"])."'");
$message = "<h3>Status Updated</h3>";
}


switch ($_GET["FilterIWCUID"]) {
	case "":
		$FilterIWCUID			=	$_GET["FilterIWCUID"];
		$FilterIWCUIDSQL		=	"";
		$FilterIWCUIDQS			=	"";
		break;
	default:
		$FilterIWCUID			=	$_GET["FilterIWCUID"];
		$FilterIWCUIDSQL		=	"and $database_clients.iwcuid like '%".$FilterIWCUID."%' ";
		$FilterIWCUIDQS			=	"FilterIWCUID=".$FilterIWCUID."&";
		break;
}
switch ($_GET["FilterOrderNo"]) {
	case "":
		$FilterOrderNo			=	$_GET["FilterOrderNo"];
		$FilterOrderNoSQL		=	"";
		$FilterOrderNoQS		=	"";
		break;
	default:
		$FilterOrderNo			=	$_GET["FilterOrderNo"];
		$FilterOrderNoSQL		=	"and $database_supplier_invoices_main.order_id = '".$FilterOrderNo."' ";
		$FilterOrderNoQS		=	"FilterOrderNo=".$FilterOrderNo."&";
		break;
}
switch ($_GET["FilterStatus"]) {
	case "":
		$FilterStatus			=	$_GET["FilterStatus"];
		$FilterStatusSQL		=	"";
		$FilterStatusQS			=	"";
		break;
	default:
		$FilterStatus			=	$_GET["FilterStatus"];
		$FilterStatusSQL		=	"and $database_supplier_invoices_main.status like '".$FilterStatus."' ";
		$FilterStatusQS			=	"FilterStatus=".$FilterStatus."&";
		break;
}
switch ($_GET["Dir"]) {
	case "Asc":
		$OrderByDirection		=	"ASC";
		break;
	default:
		$OrderByDirection		=	"DESC";
		break;
}

switch ($_GET["OrderBy"]) {
	case "Date":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_clients.wedding_date ".$OrderByDirection;
		break;
	case "IWCUID":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_clients.iwcuid ".$OrderByDirection;
		break;
	case "OrdNum":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_invoices_main.order_id ".$OrderByDirection;
		break;
	case "Status":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_invoices_main.status ".$OrderByDirection;
		break;
	default:
		$OrderBy				=	"Date";
		$OrderBySQL				=	"$database_clients.wedding_date ".$OrderByDirection;
		break;
}
$StatusResult = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","DISTINCT
								$database_supplier_invoices_main.status",
								"WHERE $database_clients.deleted='No' 
								and $database_supplier_details.deleted='No' 
								and $database_supplier_invoices_main.supplier_id=$database_supplier_details.id 
								and ($database_supplier_invoices_main.status = 'Outstanding' 
								or $database_supplier_invoices_main.status = 'Pending') 
								and $database_order_details.id=$database_supplier_invoices_main.order_id 
								and $database_supplier_invoices_main.supplier_id='".addslashes($_GET["id"])."'
								and $database_order_details.client_id=$database_clients.id
								ORDER BY $database_supplier_invoices_main.status");
$StatusRow = $sql_command->results($StatusResult);

foreach($StatusRow as $StatusRecord) {
	switch ($FilterStatus) {
			case	$StatusRecord[0];
				$StatusSelected		=	" selected";	
			break;
			default:
				$StatusSelected		=	"";
			break;
	}
	$StatusList .= "<option value=\"".$StatusRecord[0]."\"".$StatusSelected.">". $StatusRecord[0] ."</option>";
}

if ($the_username =="u1") {
	$result = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices_main.id,
								   $database_supplier_invoices_main.order_id,
								   $database_supplier_invoices_main.supplier_id,
								   $database_supplier_invoices_main.invoice_id,
								   $database_supplier_invoices_main.status,
								   $database_clients.wedding_date,
								   $database_supplier_details.id,
								   $database_supplier_details.company_name,
								   $database_clients.id,
								   $database_clients.title,
								   $database_clients.firstname,
								   $database_clients.lastname,
								   $database_clients.iwcuid","WHERE 
								   $database_supplier_details.deleted='No' and 
								   $database_clients.deleted='No' and 
								   $database_supplier_invoices_main.supplier_id='".addslashes($_GET["id"])."' and 
								   $database_supplier_invoices_main.supplier_id=$database_supplier_details.id  and
								   $database_order_details.id=$database_supplier_invoices_main.order_id and 
								   $database_order_details.client_id=$database_clients.id ".$FilterIWCUIDSQL.$FilterOrderNoSQL.$FilterSupplierIDSQL.$FilterStatusSQL."
								   ORDER BY ".$OrderBySQL);
} else {
	$result = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices_main.id,
								   $database_supplier_invoices_main.order_id,
								   $database_supplier_invoices_main.supplier_id,
								   $database_supplier_invoices_main.invoice_id,
								   $database_supplier_invoices_main.status,
								   $database_clients.wedding_date,
								   $database_supplier_details.id,
								   $database_supplier_details.company_name,
								   $database_clients.id,
								   $database_clients.title,
								   $database_clients.firstname,
								   $database_clients.lastname,
								   $database_clients.iwcuid","WHERE 
								   $database_supplier_details.deleted='No' and 
								   $database_clients.deleted='No' and 
								   $database_supplier_invoices_main.supplier_id='".addslashes($_GET["id"])."' and 
								   $database_supplier_invoices_main.supplier_id=$database_supplier_details.id  and
								   $database_order_details.id=$database_supplier_invoices_main.order_id and 
								   $database_order_details.client_id=$database_clients.id 
								   ORDER BY $database_supplier_invoices_main.timestamp DESC");
}
$row = $sql_command->results($result);

foreach($row as $record) {
$cost = 0;
$supplier_name = stripslashes($record[7]);

$dateline = date("d-m-Y",$record[5]);

/*

$result2 = $sql_command->select($database_supplier_invoices_details,"qty,cost,currency","WHERE main_id='".$record[0]."'");
$row2 = $sql_command->results($result2);


$payval = $sql_command->select("supplier_payments,supplier_transactions",
							   "sum(supplier_payments.p_amount)",
							   "WHERE supplier_transactions.p_id = supplier_payments.pay_no
							   AND supplier_payments.order_id='".addslashes($record[1])."'
							   
							   AND supplier_transactions.status = 'Paid'");	
$payvalr = $sql_command->result($payval);


foreach($row2 as $record2) {
if($record2[2] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}
$cost += $record2[1] * $record2[0];
}

$cost = $cost - $payvalr[0];
*/

$purchase_order = $record[0];
include("purchase-order_calc.php");

$cost = number_format($payment_total,2);
$cost = ($cost==0) ? number_format(0,2) : $cost;

$s1 = "";
$s2 = "";
$s3 = "";
$s4 = "";
$s5 = "";
$checkbox_add = "No";


if($record[4] == "Outstanding") { $s1 = "selected=\"selected\""; }
if($record[4] == "Pending") { $s4 = "selected=\"selected\"";  }
if($record[4] == "Cancelled") { $s5 = "selected=\"selected\""; }
if($record[4] == "Refunded") { $s3 = "selected=\"selected\""; }
if($record[4] == "Paid") { $s2 = "selected=\"selected\""; }

if ($payment_total <> 0) {
$list2 .= "<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
<div style=\"float:left; width:150px; margin:5px;\">$p_curreny ".$cost."</div>
<div style=\"float:left;  margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".stripslashes($record[9])." ".stripslashes($record[10])." ".stripslashes($record[11])."</a></div>
<div style=\"clear:left;\"></div>";
}
if ($payment_total <> 0) {
$list .= "
<div style=\"float:left; width:80px; margin:5px;  font-size:11px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".$record[12]."</a></div>
<div style=\"float:left; width:80px; margin:5px;  font-size:11px;\">".$record[1]."</div>
<div style=\"float:left; width:60px; margin:5px 3px; font-size:11px;\">$p_curreny ".$cost."</div>
<div style=\"float:left; width:30px; margin:5px; font-size:11px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
<div style=\"float:left; width:140px; margin:5px 3px; font-size:11px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".stripslashes($record[9])." ".stripslashes($record[10])." ".stripslashes($record[11])."</a></div>
<div style=\"float:left; width:60px; margin:5px; font-size:11px;\">".$dateline."</div>


<form action=\"$site_url/oos/manage-supplier-po-v2.php\" method=\"get\">
<input type=\"hidden\" name=\"po_id\" value=\"".$record[0]."\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET["id"]."\">
<div style=\"float:left; margin:5px;\">
<select name=\"status\">
<option value=\"Outstanding\" $s1>Outstanding</option>
<option value=\"Paid\" $s2>Paid</option>
<option value=\"Refunded\" $s3>Refunded</option>
<option value=\"Pending\" $s4>Pending</option>
<option value=\"Cancelled\" $s5>Cancelled</option>
</select> </div>
<div style=\"float:left; margin:5px;\"><input type=\"submit\" name=\"a\" value=\"Update\" /></div>
</form>
<div style=\"clear:left;\"></div>
";	
}
}

$get_template->topHTML();
?>
<?
$FormURL	=	$site_url."/oos/manage-supplier-po-v2.php";
$OrderURL	=	$site_url."/oos/manage-supplier-po-v2.php?action=Continue&id=".addslashes($_GET["id"])."&".$FilterIWCUIDQS.$FilterOrderNoQS.$FilterSupplierIDQS.$FilterStatusQS."OrderBy=";
?>
<h1>Supplier Purchase Orders</h1>
<h2><?php echo $supplier_name; ?></h2>
<?php echo $message; ?>
<?
if ($the_username =="u1") { 
?>
<form action="<?php echo $FormURL; ?>" class="pageform" id="posearch" method="get" name="posearch" >
	<div class="formrow">
		<label class="formlabel">
			Enter IWCUID
		</label>
		<div class="formelement">

			<input class="formtextfieldlong" id="FilterIWCUID" name="FilterIWCUID" type="text" value="<?php echo $FilterIWCUID;?>"/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel">
			Enter Order Number
		</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="FilterOrderNo" name="FilterOrderNo" type="text" value="<?php echo $FilterOrderNo;?>"/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel">
			Order Status
		</label>
		<div class="formelement">
			<select class="formselectlong" id="FilterStatus" name="FilterStatus">
				<option value="">Select	order status...</option>
				<?php echo $StatusList; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label for="FilterSubmit" class="formlabel">&nbsp;</label>
		<div class="formelement">
			<input id="action" name="action" type="hidden" value="Continue" />
			<input id="id" name="id" type="hidden" value="<?php echo addslashes($_GET["id"]); ?>" />
			<input id="OrderBy" name="OrderBy" type="hidden" value="<?php echo $OrderBy; ?>" />
			<input id="OrderBy" name="OrderBy" type="hidden" value="<?php echo $OrderBy; ?>" />
			<input id="Dir" name="Dir" type="hidden" value="<?php echo $OrderByDirection; ?>" />
			<input id="FilterSubmit" name="FilterSubmit" class="formsubmit" type="submit" value="Submit" />
			<input id="FilterReset" name="FilterReset" class="formreset" type="reset"  value="Reset" />
		</div>
		<div class="clear"></div>
	</div>
</form>
<?
}
?>
<div style="float:left; width:80px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>IWCUID&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> IWCUID <a class="listheaderbutton" href="<?php echo $OrderURL;?>IWCUID&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left; width:80px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>OrdNum&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Order # <a class="listheaderbutton" href="<?php echo $OrderURL;?>OrdNum&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left; width:60px; margin:5px 3px;"><strong>Amount</strong></div>
<div style="float:left; width:30px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; width:140px; margin:5px;"><strong>Client</strong></div>
<div style="float:left;	width:60px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>Date&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Date <a class="listheaderbutton" href="<?php echo $OrderURL;?>Date&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>Status&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Status <a class="listheaderbutton" href="<?php echo $OrderURL;?>Status&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>

<?php if($list2) {
//if ($the_username =="u1") { 
?>

<!--<h3>Download / Email Multiple Purchase Orders</h3>

<form method="post" action="<?php echo $site_url; ?>/oos/manage-supplier-po-v2.php" name="download" id="download_email">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left;  margin:5px;"><strong>Client</strong></div>
<div style="clear:left;"></div>
<?php echo $list2; ?>

<p><input type="submit" name="action" value="Download Selected Purchase Orders" id="d_download"><input type="submit" name="action" value="Email Selected Purchase Orders" id="e_download"></p>
</form>-->

<?
//} else { ?>

<h3>Download Multiple Purchase Orders</h3>

<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order.php" name="download" id="download_email">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left;  margin:5px;"><strong>Client</strong></div>
<div style="clear:left;"></div>
<?php echo $list2; ?>

<p><input type="submit" name="action" value="Download Selected Purchase Orders" id="d_download"></p>
</form>
<?php 
//} 
}
	$result2 = $sql_command->select("supplier_transactions",
									"p_id,
									p_amount,
									transaction_id,
									ip_add,
									status,
									cardtype,
									timestamp,currency",
									"WHERE supplier_id=".$_GET['id']." 
									and status!='Refused' 
									and status!='Cancelled'");
	
	$row2 = $sql_command->results($result2);
	$payment_html = "";
	
	foreach($row2 as $record2) {
		
		
		
		$pcurrency = $sql_command->select("currency_conversion","currency_symbol","WHERE currency_name='".$record2[7]."'");
		$currencyp = $sql_command->result($pcurrency);	
		
		$payr = $sql_command->select("supplier_payments",
									 "SUM(supplier_payments.p_amount)",
									 "WHERE supplier_payments.pay_no='".addslashes($record2[0])."'");
		$payv = $sql_command->result($payr);
		$alloc = (!$payv[0]) ? 0 : $payv[0];
		$unall = ($record2[1] - $alloc !=0) ? false : ($record2[4]=="Paid") ? false : true;
		$difference = $record2[1] - $alloc;
		
		list($dateS,$timeS) = explode(" ",$record2[6]);
		list($yr,$mt,$dy) = explode("-",$dateS);
		$dateS = $dy."/".$mt."/".$yr;
		
$payment_html .= "<tr>";
$payment_html .= "<td>".$record2[0]."</td>";
$payment_html .= "<td>".$currencyp[0]." ".number_format($record2[1],2)."</td>";
$payment_html .= "<td>".$record2[5]."</td>";
$payment_html .= "<td>".$record2[2]."</td>";
$payment_html .= "<td>".ucwords($record2[3])."</td>";
$payment_html .= "<td>".$dateS."</td>";
$payment_html .= "<td><a href=\"$site_url/oos/manage-supplier-po-v2.php?a=partpay&id=".$_GET['id']."&pid=".$record2[0]."&f=mod\">View Payment</a></td>";
$payment_html .= "<td>".$record2[4]."</td>";
$payment_html .= "<td><input type=\"checkbox\" name=\"payall[]\" value=\"".$record2[0]."\" class=\"payall\" /></td>";
$payment_html .= "</tr>";
}

echo "<h2 style=\"margin-top:10px;\">Payment History</h2>";
echo "<form method=\"post\" action=\"manage-supplier-po-v2.php\" name=\"paymentp\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
echo "<tr>";
echo "<th scope=\"col\">Payment ID</th>";
echo "<th scope=\"col\">Paid</th>";
echo "<th scope=\"col\">Payment Type</th>";
echo "<th scope=\"col\">Trans-ref</th>";
echo "<th scope=\"col\">Paid by</th>";
echo "<th scope=\"col\">Date</th>";
echo "<th scope=\"col\">View</th>";
echo "<th scope=\"col\">Status</th>";
echo "<th scope=\"col\">Select</th>";
echo "</tr>";
echo "<tr><td colspan=\"7\">&nbsp;</td></tr>";
echo $payment_html; 
echo "</table>";
echo "<br />";
echo "<input type=\"hidden\" name=\"cli_id\" value=\"".$_GET["id"]."\" ?>";
echo "<input type=\"hidden\" id=\"reason\" name=\"reason\" value=\"\" ?>";
echo "[ <a href=\"$site_url/oos/manage-supplier-po-v2.php?a=partpay&id=".$_GET["id"]."&f=add\">Add Payment</a> | <input class=\"paymentb\" type=\"submit\" name=\"action\" value=\"Approve Payments\" onclick=\"return checkApprove();\" /> | <input class=\"paymentb\" type=\"submit\" name=\"action\" value=\"Delete Payments\" onclick=\"return checkVoid();\" /> ]</form>";
//echo $respa . "<br />" . $respb."<br />hi ";

//switch($the_username) {
//	case "u1":
//		echo "<h2 style=\"margin-top:10px;\">Email History</h2>";
//		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
//		echo "<tr>";
//		echo "<th scope=\"col\">Email ID</th>";
//		echo "<th scope=\"col\">Status</th>";
//		echo "<th scope=\"col\">View</th>";
//		echo "<th scope=\"col\">PDF</th>";
//		echo "<th scope=\"col\">Author</th>";
//		echo "<th scope=\"col\">Creation Date</th>";
//		echo "<th scope=\"col\">Date Sent</th>";
//		echo "</tr>";
//		echo "</table>";
//		echo "[ <a href=\"$site_url/oos/manage-supplier-po-v2.php?a=email&id=".$_GET["id"]."&f=add\">Create Email</a> ]";
//	break;
//	default:
//	break;
//}

$get_template->bottomHTML();
$sql_command->close();

} else {

$result = $sql_command->select($database_supplier_details,"id,company_name","ORDER BY company_name");
$row = $sql_command->results($result);

foreach($row as $record) {

$list .= "<option value=\"".stripslashes($record[0])."\" style=\"font-size:10px;\">".stripslashes($record[1])."</option>\n";
}

$get_template->topHTML();
?>
<h1>Update Supplier</h1>

<form action="<?php echo $site_url; ?>/oos/manage-supplier-po-v2.php" method="get">
<input type="hidden" name="action" value="Continue" />
<select name="id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();
	
}
?>