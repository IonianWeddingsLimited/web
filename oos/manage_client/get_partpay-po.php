<?
$type=(isset($_GET['f'])) ? $_GET['f']:"add";

$script = $_SERVER['SCRIPT_NAME'];
$count = explode("/",$script);
$filter=array("-","_","po.php",".php");

if(!$_GET["id"] || ($type!='add' && !isset($_GET["pid"]))) {
$get_template->topHTML();
$get_template->errorHTML($titleP,"Oops!","Missing Client or PaymentID","Link",$script);
$get_template->bottomHTML();
$sql_command->close();
}
$add_header .= "<script type=\"text/javascript\">
function checkexchange() {
	

var rate = $('#exchange_rate').val();
if(!rate) { rate = 0; }
var answer = confirm('You have set the exchange rate to ' + rate + ', select OK to confirm');
if (answer){ return true; } else {
return false;  
}
}

</script>";

$add_header .= "
<script type=\"application/javascript\">
$(function() {

$(\".allocate\").bind('input', function(){
	var inp = $(this).attr(\"name\");
	inp = inp.replace(\"-alloc\",\"\");
	
	$(\"#chk-\"+inp).attr('checked', true);
});

});

</script>

";

if ($type=="add") {
	$get_template->topHTML();
?>
<h1>Manage Supplier</h1>

	<?php echo $menu_line; ?>
	<h2>Add Payment</h2>

	<form action="<?php echo $site_url; echo $script; ?>" method="POST" name="paymentT">
	<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
	<input type="hidden" name="pftype" value="add" />
    <script>
		new tcal ({
			// form name
			'formname': 'paymentT',
			// input name
			'controlname': 'dateT'
		});
	</script>
	<div style="float:left; width:160px; margin:5px;"><strong>Date:</strong></div>
	<div style="float:left; margin:5px;">
    	<input type="text" name="dateT" id="dateT" value="<?php echo $dateL = date("d-m-Y"); $dateL; ?>" />
    </div>
<div style="clear:left;"></div>
<script>
	new tcal ({
		// form name
		'formname': 'paymentT',
		// input name
		'controlname': 'dateT'
	});
	
	$().ready(function() {
		
		$("#client_search").autocomplete("get_client_list.php", {
			width: 400,
			matchContains: true,
			mustMatch: true,
			//minChars: 0,
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
		
		$("#client_search").result(function(event, data, formatted) {
			$("#customer_id").val(data[1]);
			$("#iwcuid").val(data[2]);
		});
	});
</script>
<div style="float:left; width:160px; margin:5px;"><strong>Client Search:</strong></div>
<div style="float:left; margin:5px;">
	<input id="client_search" name="client_search" type="text"  />
</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>IWCUID Selected:</strong></div>
<div style="float:left; margin:5px;">
	<input id="iwcuid" name="iwcuid" type="text"   disabled  />
	<input id="customer_id" name="customer_id" type="hidden" />
</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Payment Status:</strong></div>
<div style="float:left; margin:5px;">
<select name="pstatus">
<option value="Pending">Pending</option>
<option value="Paid">Paid</option>
<option value="Cancelled">Cancelled</option>
<option value="Refused">Refused</option>
</select>
</div>	<div style="clear:left;"></div>

	<div style="float:left; width:160px; margin:5px;"><strong>Payment Amount:</strong></div>
	<div style="float:left; margin:5px;"><input type="text" name="payment" value="0"></div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><strong>Payment Type:</strong></div>
	<div style="float:left; margin:5px;">
	<select class="select" name="paymentT">
		<option value="BACS">BACS</option>
		<option value="Cash">Cash</option>
		<option value="GR Account">GR Account</option>
		<option value="HiFX">HiFX</option>
		<option value="IndigoFX">IndigoFX</option>
    </select>
    
    
    </div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><strong>Transaction Reference:<br /><span class="optional">(This is optional if you want to link a cheque number etc.. to this payment)</span></strong></div>
	<div style="float:left; margin:5px;"><input type="text" name="transid" value="n/a"></div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><strong>Currency:</strong></div>
	<div style="float:left; margin:5px;">
    <select name="currency">
		<option value="Euro">Euro</option>
		<option value="Pound">Pound</option>
	</select>
	</div>
	<div style="clear:left;"></div>
	<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate:</strong></div>
	<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="1.000000"></div>
	<div style="clear:left;"></div>
		
	<br />
	<h3>Allocate Payment</h3>
	<div class="formlist">
		<div class="formlisttable">
			<div class="formlisttr desktopcart">
				<div class="formlistth">Order ID</div>
				<div class="formlistth">Date</div>
				<div class="formlistth">Status</div>
				<div class="formlistth">Amount</div>
				<div class="formlistth">View PDF</div>
				<div class="formlistth">Amount to Allocate</div>
			</div>
	  <?php 
	  
	 $result = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices_main.id,
							   $database_supplier_invoices_main.order_id,
							   $database_supplier_invoices_main.supplier_id,
							   $database_supplier_invoices_main.invoice_id,
							   $database_supplier_invoices_main.status,
							   $database_supplier_invoices_main.timestamp,
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
$row = $sql_command->results($result);

foreach($row as $record) {
	$cost = 0;
	$supplier_name = stripslashes($record[7]);
	
	$dateline = date("d-m-Y",$record[5]);

	$purchase_order = $record[0];
	include("./purchase-order_calc.php");

	$filterva = array(" ",",","€","£","$","-");

$payment_total = ($payment_total==0) ? 0 : $payment_total;
	$new_total_gbp = number_format($payment_total,2);
	$filterva = array(" ",",","€","£","$","-");
	$totalin = str_replace($filterva,"",number_format($payment_total,2));

	$list .= "
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlistth\">Order ID</div>
			<div class=\"formlistth\">Date</div>	
			<div class=\"formlistth\">Status</div>
		</div>
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlisttd\">".$record[1]."</div>
			<div class=\"formlisttd\">".$dateline."</div>
			<div class=\"formlisttd\">".$record[4]."</div>
		</div>
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlistth\">Amount</div>
			<div class=\"formlistth\">View PDF</div>
			<div class=\"formlistth\">Confirm Selection</div>
		</div>
		<div class=\"formlisttr\">
			<div class=\"formlisttd desktopcart\">".$record[1]."</div>
			<div class=\"formlisttd desktopcart\">".$dateline."</div>
			<div class=\"formlisttd desktopcart\">".$record[4]."</div>
			<div class=\"formlisttd\">$p_curreny ".$new_total_gbp."</div>
			<div class=\"formlisttd\">
				<a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">Click here</a>
			</div>
				<input id=\"chk-".$record[1]."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$record[1]."\" checked>
				<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[1])."\">
				<input type=\"hidden\" name=\"".$record[1]."-invid\" value=\"".$record[3]."\">
				<input type=\"hidden\" name=\"".$record[1]."-date\" value=\"".$dateline."\">
				<input type=\"hidden\" name=\"".$record[1]."-total\" value=\"".$totals."\">
				<input type=\"hidden\" name=\"".$record[1]."-orderid\" value=\"".stripslashes($record[2])."\">
				<input type=\"hidden\" name=\"".$record[1]."-mainid\" value=\"".stripslashes($record[0])."\">
			<div class=\"formlisttd\">
				<input type=\"number\" step=\"any\" name=\"".$record[1]."-alloc\" class=\"allocate\"/>
			</div>
		</div>
		<div class=\"formlisttrspacer\"></div>";
		$total_prev	=	$total_gbp;
}

echo $list; ?></div></div>

<div style="float:right; margin-top:10px;"><input type="submit" name="action" value="Add Payment" onclick="return checkexchange();"></div>
</form>
<form action="<?php echo $site_url; echo $script; ?>" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<div style="clear:left;"></div>
<?
}
else {
	$get_template->topHTML();
	?>
    
<h1>Manage Supplier</h1>

<?php 
echo $menu_line; 
$result2 = $sql_command->select("supplier_transactions","p_id,p_amount,transaction_id,ip_add,status,cardtype,timestamp,currency, client_id",
								"WHERE p_id=".$_GET['pid']);
$record2 = $sql_command->result($result2);

$pcurrency = $sql_command->select("currency_conversion","currency_symbol","WHERE currency_name='".$record2[7]."'");
$currencyp = $sql_command->result($pcurrency);	
?>
<form action="<?php echo $site_url; echo $script; ?>" method="POST" name="paymentT">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="payment_id" value="<?php echo $_GET["pid"]; ?>" />
<input type="hidden" name="dateDef" value="<?php echo $record2[6]; ?>" />
<input type="hidden" name="pftype" value="modify" />
<h1>Modify Payment</h1>	
<?


$payment_html = "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment Status:</strong></div>
<div style=\"float:left; margin:5px;\"><select name=\"pstatus\">";

$a = "";
$b = "";
$c = "";
$d = "";
switch($record2[4]) {
	case "Paid":
		$a = "selected";
		break;
	case "Pending":
		$b = "selected";
		break;
	case "Cancelled":
		$c = "selected";
		break;
	case "Refused":
		$d = "selected";
		break;
}

$payment_html .= "<option value=\"Pending\" $b>Pending</option>";
$payment_html .= "<option value=\"Paid\" $a>Paid</option>";
$payment_html .= "<option value=\"Cancelled\" $c>Cancelled</option>";
$payment_html .= "<option value=\"Refused\" $d>Refused</option>";

$payment_html .= "</select></div><div class=\"clearleft\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment ID:</strong></div>
<div style=\"float:left; margin:5px;\">".$record2[0]."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>";


$pclientr = $sql_command->select("clients","title, firstname, lastname, groom_title, groom_firstname, groom_surname","WHERE id='".$record2[8]."'");
$pclientv = $sql_command->result($pclientr);
if($pclientv) {
	$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Client(s):</strong></div>
					<div style=\"float:left; margin:5px;\">".$pclientv[0]." ".$pclientv[1]." ".$pclientv[2]."<br />".$pclientv[3]." ".$pclientv[4]." ".$pclientv[5]."</div>
					<div class=\"clearleft\" style=\"clear:left;\"></div>";
}

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment Amount:</strong></div>
<div style=\"float:left; margin:5px;\">".$currencyp[0]." ".number_format($record2[1],2)."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>";

$result2 = $sql_command->select("supplier_payments","SUM(p_amount)","WHERE pay_no=".$_GET['pid']);
$row2 = $sql_command->result($result2);
$alloc = (!$row2[0]) ? 0 : $row2[0];
$difference = $record2[1] - $alloc;
list($dateL,$timeL) = explode(" ",$record2[6]);
$explodeva = array("-","/",".");
$dateL= str_replace($explodeva,"-",$dateL);
list($yy,$mm,$dd) = explode("-",$dateL);
$dateL = $dd."-".$mm."-".$yy;



$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Total Allocated:</strong></div>
<div style=\"float:left; margin:5px;\">".$currencyp[0]." ".number_format($alloc,2)."</div>
<div class=\"clear\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Total Unallocated:</strong></div>
<div style=\"float:left; margin:5px;\">".$currencyp[0]." ".number_format($difference,2)."</div>
<div class=\"clear\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment Type:</strong></div>
<div style=\"float:left; margin:5px;\">".$record2[5]."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>
<div style=\"float:left; width:160px; margin:5px;\"><strong>Transaction Reference:</strong></div>
<div style=\"float:left; margin:5px;\">".$record2[2]."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>
<div style=\"float:left; width:160px; margin:5px;\"><strong>Made by:</strong></div>
<div style=\"float:left; margin:5px;\">".$record2[3]."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>
<script>
	new tcal ({
		// form name
		'formname': 'paymentT',
		// input name
		'controlname': 'dateT'
	});s
</script>
<div style=\"float:left; width:160px; margin:5px;\"><strong>Date:</strong></div>
<div style=\"float:left; margin:5px;\"><input type=\"text\" name=\"dateT\" id=\"dateT\" value=\"".$dateL."\" /></div>
<div class=\"clearleft\" style=\"clear:left;\"></div>";

echo $payment_html; 
?>


<h3>Allocate Payment</h3>
<p>Selecting option's below will automatically allocate payments to these invoices and marking them as paid if the amount entered is equal.</p>
<div class="formlist">
	<div class="formlisttable">
		<div class="formlisttr desktopcart">
			<div class="formlistth">Order ID</div>
			<div class="formlistth">Date</div>
			<div class="formlistth">Status</div>
			<div class="formlistth">Amount</div>
			<div class="formlistth">View PDF</div>
			<div class="formlistth">Amount to Allocate</div>
		</div>
  <?
 
 	 $result = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices_main.id,
							   $database_supplier_invoices_main.order_id,
							   $database_supplier_invoices_main.supplier_id,
							   $database_supplier_invoices_main.invoice_id,
							   $database_supplier_invoices_main.status,
							   $database_supplier_invoices_main.timestamp,
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
$row = $sql_command->results($result);
foreach($row as $record) {
/*	$cost = 0;
	$supplier_name = stripslashes($record[7]);
	
	$dateline = date("d-m-Y",$record[5]);

	$result2 = $sql_command->select($database_supplier_invoices_details,"qty,cost,currency","WHERE main_id='".$record[0]."'");
	$row2 = $sql_command->results($result2);
	
	foreach($row2 as $record2) {
		if($record2[2] == "Pound") { 
			$p_curreny = "&pound;"; 
		} else {
			$p_curreny = "&euro;"; 
		}
		$cost += $record2[1] * $record2[0];
	}	

	
	$total_prev	=	0;
	

	
	foreach($row as $recordt) {
		$resp = $sql_command->select("supplier_payments,supplier_transactions",
							 "sum(supplier_payments.p_amount)",
							 "WHERE supplier_transactions.p_id = supplier_payments.pay_no 
							 AND supplier_transactions.status = 'Paid'
							 AND supplier_payments.status != 'Unpaid'
							 AND supplier_payments.order_id = '".addslashes($record[1])."'");

		$respr = $sql_command->result($resp);
		$totalpp = $cost - $respr[0];
		
		$new_total_gbp = number_format($totalpp,2);
		$totalin =	str_replace(",","",number_format($totalpp,2));
		$totals = str_replace(",","",number_format($totalpp-$paidam,2));
		$total_prev	=	$total_gbp;
	}

*/

	$cost = 0;
	$supplier_name = stripslashes($record[7]);
	
	$dateline = date("d-m-Y",$record[5]);

	$purchase_order = $record[0];
	include("./purchase-order_calc.php");

	$filterva = array(" ",",","€","£","$","-");
$payment_total = ($payment_total==0) ? 0 : $payment_total;
	$new_total_gbp = str_replace("-","",number_format($payment_total,2));
	$filterva = array(" ",",","€","£","$","-");
	$totalin = str_replace($filterva,"",number_format($payment_total,2));

	$result2 = $sql_command->select("supplier_payments","p_id,p_amount","WHERE pay_no=".$_GET['pid']." AND order_id=".$record[1]);
	$rowsa = $sql_command->result($result2);
	$curr = ($rowsa[1]>0) ? $rowsa[1]: 0;
	$pid = $rowsa[0];
	
	
	$list .= "
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlistth\">Order ID</div>
			<div class=\"formlistth\">Date</div>	
			<div class=\"formlistth\">Status</div>
		</div>
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlisttd\">".$record[1]."</div>
			<div class=\"formlisttd\">".$dateline."</div>
			<div class=\"formlisttd\">".$record[4]."</div>
		</div>
		<div class=\"formlisttr mobilecart\">
			<div class=\"formlistth\">Amount</div>
			<div class=\"formlistth\">View PDF</div>
			<div class=\"formlistth\">Confirm Selection</div>
		</div>
		<div class=\"formlisttr\">
			<div class=\"formlisttd desktopcart\">".$record[1]."</div>
			<div class=\"formlisttd desktopcart\">".$dateline."</div>
			<div class=\"formlisttd desktopcart\">".$record[4]."</div>
			<div class=\"formlisttd\">$p_curreny ".$new_total_gbp."</div>
			<div class=\"formlisttd\">
				<a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">Click here</a>
			</div>
				<input id=\"chk-".$record[1]."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$record[1]."\" checked>
				<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[1])."\">
				<input type=\"hidden\" name=\"".$record[1]."-invid\" value=\"".$record[3]."\">
				<input type=\"hidden\" name=\"".$record[1]."-date\" value=\"".$dateline."\">
				<input type=\"hidden\" name=\"".$record[1]."-total\" value=\"".$totals."\">
				<input type=\"hidden\" name=\"".$record[1]."-orderid\" value=\"".stripslashes($record[1])."\">
			<div class=\"formlisttd\">
				<input type=\"number\" step=\"any\" name=\"".$record[1]."-alloc\" class=\"allocate\" value=\"".$curr."\"/>
				<input type=\"hidden\" name=\"".$record[1]."-current\" class=\"allocate\" value=\"" . $curr . "\" />
				<input type=\"hidden\" name=\"".$record[1]."-pid\" class=\"allocate\" value=\"" . $pid ."\" />
				<input type=\"hidden\" name=\"".$record[1]."-mainid\" value=\"".stripslashes($record[0])."\">
			</div>
		</div>
		<div class=\"formlisttrspacer\"></div>";
		$total_prev	=	$total_gbp;
}echo $list; 
?></div></div>

<div style="float:right; margin-top:10px;"><input type="submit" name="action" value="Modify Payment"></div>
</form>
<form action="<?php echo $site_url; echo $script; ?>" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<?
} 

$get_template->bottomHTML();
$sql_command->close();

?>