<?
$type=(isset($_GET['f'])) ? $_GET['f']:"add";

$script = $_SERVER['SCRIPT_NAME'];
$count = explode("/",$script);
$i = count(count)-1;
$pageP = $count[$i];
$filter=array("-","_","po.php",".php");
$titleP = ucwords(str_replace($filter,$pageP));

if(!$_GET["id"] || ($type!='add' && !isset($_GET["pid"]))) {
$get_template->topHTML();
$get_template->errorHTML($titleP,"Oops!","Missing Prospect or PaymentID","Link",$script);
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

if ($type=="add") {
	$get_template->topHTML();
?>
<h1>Manage Prospect</h1>

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
<div style="float:left; width:160px; margin:5px;"><strong>Payment Status:</strong></div>
<div style="float:left; margin:5px;">
<select name="pstatus">
<?
	switch($login_record[0]) {
		case	"Super Admin User";
			echo "<option value=\"Pending\">Pending</option>";
			echo "<option value=\"Paid\">Paid</option>";
			echo "<option value=\"Cancelled\">Cancelled</option>";
			echo "<option value=\"Refused\">Refused</option>";
		break;
		default:
			echo "<option value=\"Pending\">Pending</option>";
			echo "<option value=\"Cancelled\">Cancelled</option>";
			echo "<option value=\"Refused\">Refused</option>";
		break;
	}
?>
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
		<option value="Streamline">Streamline</option>
		<option value="Worldpay">Worldpay</option>
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
		<option value="Pound" selected="selected">Pound</option>
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
				<div class="formlistth">Invoice ID</div>
				<div class="formlistth">Type</div>
				<div class="formlistth">Date</div>
				<div class="formlistth">Status</div>
				<div class="formlistth">Amount</div>
				<div class="formlistth">View PDF</div>
				<div class="formlistth">Select</div>
				<div class="formlistth">Amount to Allocate</div>
			</div>
	  <?php 
	  
	  $result = $sql_command->select("quotation_proformas,quotation_details","quotation_proformas.id,
							   quotation_proformas.iw_cost,
							   quotation_proformas.status,
							   quotation_proformas.timestamp,
							   quotation_proformas.type,
							   quotation_proformas.order_id,
							   quotation_proformas.included_package",
							   "WHERE quotation_proformas.order_id=quotation_details.id AND quotation_details.client_id='".$_GET["id"]."' AND quotation_proformas.status != 'Paid' ORDER BY quotation_proformas.id DESC");
	$row = $sql_command->results($result);
	$list = "";
	
	$total_prev	=	0;
	
	
	
	foreach($row as $record) {
		$dateline = date("d-m-Y",$record[3]);
		/*if($record[4] == "Deposit") { $link = "oos/invoice"; } else { $link= "oos/invoice"; }
		if($record[6] == "Yes") {
			$result_cost = $sql_command->select("quotation_proformas","iw_cost","WHERE order_id='".addslashes($record[5])."' and type='Deposit' and status='Paid'");
			$record_cost = $sql_command->result($result_cost);
			$total_invoice_cost = $record[1] - $record_cost[0];	
		} 
		else {
			$total_invoice_cost = $record[1];	
		}
	
		if ($record[2]!='Outstanding') { $paystatus = "disabled"; } else { $paystatus = ""; } 
		*/
$invoiceno = $record[0];
$currency_name="";
$c_symbol ="£";

	$num_rows = $sql_command->count_nrow("clients_options","id","client_id='".addslashes($invoiceno)."' and client_option='continental' and option_value='Yes'");
	
	if ($num_rows>0) {
$currency_q = $sql_command->select("quotation_history 
										   LEFT OUTER JOIN $db_currency_conversion ON 
										   $db_currency_conversion.currency_name = quotation_history.currency",
										   "quotation_history.currency,$db_currency_conversion.currency_symbol",
										   "WHERE quotation_history.invoice_id = '".addslashes($invoiceno)."'
										   GROUP BY quotation_history.currency
										   ORDER BY $db_currency_conversion.currency_name ASC");
		$currency_i = $sql_command->results($currency_q);

		foreach($currency_i as $ci) {	
			$statusform	=	"";
			$c_symbol = $ci[1];
			$currency_name=$ci[0];
			$p_id = $record[0] . $currency_name;
			if ($record[4]=="Deposit") {
				$curr_i = "&currency=".$currency_name;
			}
//			$currency_name = "Euro";
//			else { 
			include("../_includes/fn_invoice-payment-v3.php");
			
			$totalin		=	str_replace(",","",number_format($total_gbp,2));
			$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
//			}


							
			$list .= "<div class=\"formlisttr mobilecart\">
					<div class=\"formlistth\">Invoice ID</div>
					<div class=\"formlistth\">Type</div>
					<div class=\"formlistth\">Date</div>
					<div class=\"formlistth\">Status</div>
				</div>
				<div class=\"formlisttr mobilecart\">
					<div class=\"formlisttd\">".$record[0]."</div>
					<div class=\"formlisttd\">".stripslashes($record[4])."</div>
					<div class=\"formlisttd\">".$dateline."</div>
					<div class=\"formlisttd\">".$record[2]."</div>
				</div>
				<div class=\"formlisttr mobilecart\">
					<div class=\"formlistth\">Amount</div>
					<div class=\"formlistth\">View PDF</div>
					<div class=\"formlistth\">Select</div>
					<div class=\"formlistth\">Confirm Selection</div>
				</div>
				<div class=\"formlisttr\">
					<div class=\"formlisttd desktopcart\">".$record[0]."</div>
					<div class=\"formlisttd desktopcart\">".stripslashes($record[4])."</div>
					<div class=\"formlisttd desktopcart\">".$dateline."</div>
					<div class=\"formlisttd desktopcart\">".$record[2]."</div>
					<div class=\"formlisttd\">".$new_total_gbp."</div>
					<div class=\"formlisttd\">
						<a href=\"$site_url/oos/invoice.php?invoice=".$record[0].$curr_i."\" target=\"_blank\">Click here</a>
					</div>
					<div class=\"formlisttd\">
						<input id=\"chk-".$p_id."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$p_id."\" checked>
						<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
						<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">
						<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">
						<input type=\"hidden\" name=\"".$p_id."-currency\" value=\"".$currency_name."\">
						<input type=\"hidden\" name=\"".$p_id."-invid\" value=\"".$record[0]."\">
						<input type=\"hidden\" name=\"".$p_id."-date\" value=\"".$dateline."\">
						<input type=\"hidden\" name=\"".$p_id."-type\" value=\"".stripslashes($record[4])."\">
						<input type=\"hidden\" name=\"".$p_id."-total\" value=\"".$totalin."\">
						<input type=\"hidden\" name=\"".$record[0]."-invtotal[]\" value=\"".$totalin."\">
						<input type=\"hidden\" name=\"".$p_id."-orderid\" value=\"".stripslashes($record[5])."\">
					</div>
					<div class=\"formlisttd\">
						<input type=\"number\" step=\"any\" name=\"".$p_id."-alloc\" class=\"allocate\" value=\"0\"/>
					</div>
				</div>
				<div class=\"formlisttrspacer\"></div>";
				
			$c_symbol = $c_symbolold;
			$curr_i = "";
		}
	}
	else {
		$invoicer_result = $sql_command->select("quotation_proforma_history 
												LEFT OUTER JOIN $db_currency_conversion 
												ON $db_currency_conversion.currency_name = quotation_proforma_history.currency",
												"quotation_proforma_history.currency,
												$db_currency_conversion.currency_symbol",
											   "WHERE invoice_id='".addslashes($invoiceno)."'");
		$invoice_rowr = $sql_command->result($invoicer_result);
		if ($record[4]=="Deposit") {
			$currency_name = $invoice_rowr[0];
			$c_symbol = $invoice_rowr[1];
			$curr_i = "&currency=".$currency_name;
			$p_id = $record[0] . $invoice_rowr[0];
		}
		
		
		include("../_includes/fn_invoice-payment-v3.php");
		
		$p_id = $record[0] . "Pound";
		$currency_name = ($currency_name) ? $currency_name : "Pound";
		$totalin		=	number_format($total_gbp,2);
		$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
//		}
					$list .= "<div class=\"formlisttr mobilecart\">
					<div class=\"formlistth\">Invoice ID</div>
					<div class=\"formlistth\">Type</div>
					<div class=\"formlistth\">Date</div>
					<div class=\"formlistth\">Status</div>
				</div>
				<div class=\"formlisttr mobilecart\">
					<div class=\"formlisttd\">".$record[0]."</div>
					<div class=\"formlisttd\">".stripslashes($record[4])."</div>
					<div class=\"formlisttd\">".$dateline."</div>
					<div class=\"formlisttd\">".$record[2]."</div>
				</div>
				<div class=\"formlisttr mobilecart\">
					<div class=\"formlistth\">Amount</div>
					<div class=\"formlistth\">View PDF</div>
					<div class=\"formlistth\">Select</div>
					<div class=\"formlistth\">Confirm Selection</div>
				</div>
				<div class=\"formlisttr\">
					<div class=\"formlisttd desktopcart\">".$record[0]."</div>
					<div class=\"formlisttd desktopcart\">".stripslashes($record[4])."</div>
					<div class=\"formlisttd desktopcart\">".$dateline."</div>
					<div class=\"formlisttd desktopcart\">".$record[2]."</div>
					<div class=\"formlisttd\">".$new_total_gbp."</div>
					<div class=\"formlisttd\">
						<a href=\"$site_url/oos/invoice.php?invoice=".$record[0].$curr_i."\" target=\"_blank\">Click here</a>
					</div>
					<div class=\"formlisttd\">
						<input id=\"chk-".$p_id."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$p_id."\" checked>
						<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">
						<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">
						<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">
						<input type=\"hidden\" name=\"".$p_id."-currency\" value=\"".$currency_name."\">
						<input type=\"hidden\" name=\"".$p_id."-invid\" value=\"".$record[0]."\">
						<input type=\"hidden\" name=\"".$p_id."-date\" value=\"".$dateline."\">
						<input type=\"hidden\" name=\"".$p_id."-type\" value=\"".stripslashes($record[4])."\">
						<input type=\"hidden\" name=\"".$p_id."-total\" value=\"".$totalin."\">
						<input type=\"hidden\" name=\"".$record[0]."-invtotal[]\" value=\"".$totalin."\">			
						<input type=\"hidden\" name=\"".$p_id."-orderid\" value=\"".stripslashes($record[5])."\">
					</div>
					<div class=\"formlisttd\">
						<input type=\"number\" step=\"any\" name=\"".$p_id."-alloc\" class=\"allocate\"/>
					</div>
				</div>
				<div class=\"formlisttrspacer\"></div>";
			$c_symbol = $c_symbolold;
			$curr_i = "";
	}
	}
echo $list; 

//var_dump($currency_i);
?></div></div>

<div style="float:right; margin-top:10px;"><input type="submit" name="action" value="Add Payment" onclick="return checkexchange();"></div>
</form>
<form action="<?php echo $site_url; echo $script; ?>" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<?
}
else {
	$get_template->topHTML();
	?>
    
<h1>Manage Prospect</h1>

<?php echo $menu_line; 
$result2 = $sql_command->select("customer_transactions 
								LEFT OUTER JOIN $db_currency_conversion 
								ON $db_currency_conversion.currency_name = customer_transactions.currency",
								"customer_transactions.p_id,
								customer_transactions.p_amount,
								customer_transactions.transaction_id,
								customer_transactions.ip_add,
								customer_transactions.status,
								customer_transactions.cardtype,
								customer_transactions.timestamp,
								customer_transactions.payment_exchange,
								customer_transactions.currency,
								$db_currency_conversion.currency_symbol",
								"WHERE p_id=".$_GET['pid']);
$record2 = $sql_command->result($result2);
?>
<form action="<?php echo $site_url; echo $script; ?>" method="POST" name="paymentT">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="payment_id" value="<?php echo $_GET["pid"]; ?>" />
<input type="hidden" name="dateDef" value="<?php echo $record2[6]; ?>" />
<input type="hidden" name="pftype" value="modify" />
<h1>Modify Payment</h1>	
<?
$c_symbol = $record2[9];

$payment_html = "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment Status:</strong></div>
<div style=\"float:left; margin:5px;\">";

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
switch($login_record[0]) {
	case	"Super Admin User";
		$payment_html .= "<select name=\"pstatus\">";
		$payment_html .= "<option value=\"Pending\" $b>Pending</option>";
		$payment_html .= "<option value=\"Paid\" $a>Paid</option>";
		$payment_html .= "<option value=\"Cancelled\" $c>Cancelled</option>";
		$payment_html .= "<option value=\"Refused\" $d>Refused</option>";
		$payment_html .= "</select>";
	break;
	default:
		switch($record2[4]) {
			case	"Paid";
				$payment_html .=  $record2[4] . "<input type=\"hidden\" name=\"pstatus\" value=\"". $record2[4] ."\" />";	
			break;
			default:
				$payment_html .= "<select name=\"pstatus\">";
				$payment_html .= "<option value=\"Pending\" $b>Pending</option>";
				$payment_html .= "<option value=\"Cancelled\" $c>Cancelled</option>";
				$payment_html .= "<option value=\"Refused\" $d>Refused</option>";
				$payment_html .= "</select>";	
			break;
		}
	break;
}

$payment_html .= "</div><div class=\"clearleft\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment ID:</strong></div>
<div style=\"float:left; margin:5px;\">".$record2[0]."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Payment Amount:</strong></div>
<div style=\"float:left; margin:5px;\">".$c_symbol." ".number_format($record2[1],2)."</div>
<div class=\"clearleft\" style=\"clear:left;\"></div>";

$payment_html .=  "<input type=\"hidden\" name=\"exchange_rate\" value=\"". $record2[7] ."\" />";

$exchange_rate = $record2[7];
$currency =  $record2[8];

$result2 = $sql_command->select("customer_payments","SUM(p_amount)","WHERE pay_no=".$_GET['pid']);
$row2 = $sql_command->result($result2);
$alloc = (!$row2[0]) ? 0 : $row2[0];
$filterva = array("$"," ","-");
$difference = (($record2[1] - $alloc)<0) ? 0 : $record2[1] - $alloc;
list($dateL,$timeL) = explode(" ",$record2[6]);
$explodeva = array("-","/",".");
$dateL= str_replace($explodeva,"-",$dateL);
list($yy,$mm,$dd) = explode("-",$dateL);
$dateL = $dd."-".$mm."-".$yy;
$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Total Allocated:</strong></div>
<div style=\"float:left; margin:5px;\">".$c_symbol." ".number_format($alloc,2)."</div>
<div class=\"clear\" style=\"clear:left;\"></div>";

$payment_html .= "<div style=\"float:left; width:160px; margin:5px;\"><strong>Total Unallocated:</strong></div>
<div style=\"float:left; margin:5px;\">".$c_symbol." ".number_format($difference,2)."</div>
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
	});
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
			<div class="formlistth">Invoice ID</div>
			<div class="formlistth">Type</div>
			<div class="formlistth">Date</div>
			<div class="formlistth">Status</div>
			<div class="formlistth">Amount</div>
			<div class="formlistth">View PDF</div>
			<div class="formlistth">Select</div>
			<div class="formlistth">Amount to Allocate</div>
		</div>
  <?php $result = $sql_command->select("quotation_proformas,quotation_details","quotation_proformas.id,
							   quotation_proformas.iw_cost,
							   quotation_proformas.status,
							   quotation_proformas.timestamp,
							   quotation_proformas.type,
							   quotation_proformas.order_id,
							   quotation_proformas.included_package","WHERE 
							   quotation_proformas.order_id=quotation_details.id AND quotation_details.client_id='".$_GET["id"]."' ORDER BY quotation_proformas.id DESC");
$row = $sql_command->results($result);
$list = "";
$total_prev	= 0;
foreach($row as $record) {
	
	
	$invoiceno = $record[0];	
	$num_rows = $sql_command->count_nrow("clients_options","id","client_id='".addslashes($invoiceno)."' and client_option='continental' and option_value='Yes'");
	
	$currency_name="";
	if ($num_rows>0) {
$currency_q = $sql_command->select("quotation_history 
										   LEFT OUTER JOIN $db_currency_conversion ON 
										   $db_currency_conversion.currency_name = quotation_history.currency",
										   "quotation_history.currency,$db_currency_conversion.currency_symbol",
										   "WHERE quotation_history.invoice_id = '".addslashes($invoiceno)."'
										   GROUP BY quotation_history.currency
										   ORDER BY $db_currency_conversion.currency_name ASC");
		$currency_i = $sql_command->results($currency_q);
		
		foreach($currency_i as $ci) {	
			$statusform	=	"";
			$c_symbol = $ci[1];
			$currency_name=$ci[0];
			$p_id = $record[0] . $currency_name;
			if ($record[4]=="Deposit") {
				$curr_i = "&currency=".$currency_name;
			}
//			else { 
			include("../_includes/fn_invoice-payment-v3.php");
			$totalin		=	str_replace(",","",number_format($total_gbp,2));
			$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
			
			$result2 = $sql_command->select("customer_payments",
											"p_id,
											p_amount,payment_currency",
											"WHERE pay_no=".$_GET['pid']." 
											AND invoice_id=".$record[0]."
											AND payment_currency = '".$currency_name."'
											GROUP BY payment_currency");
			$rowsa = $sql_command->results($result2);
			
/*			$list .= "select p_id, p_amount,payment_currency from customer_payments WHERE pay_no=".$_GET['pid']." 
											AND invoice_id=".$record[0]."
											AND payment_currency = '".$currency_name."'
											GROUP BY payment_currency";
*/										
			$rvcurr = array();
			$rvpid = array();
			foreach ($rowsa as $rv) {
				$rvid = $rv[2];
				$rvcurr[$rvid] = ($rv[1]>0) ? $rv[1]: 0;
				$rvpid[$rvid] = $rv[0];	
			}
			
			$p_id = $record[0] . $currency_name;
							
			$curr = $rvcurr[$currency_name];
			
			$curr = str_replace(",","",number_format($curr,2));
			
			$pid = $rvpid[$currency_name];
			if ($record[2]!=="Paid" || !empty($pid)) {
			
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlistth\">Invoice ID</div>";
				$list .= "<div class=\"formlistth\">Type</div>";
				$list .= "<div class=\"formlistth\">Date</div>";
				$list .= "<div class=\"formlistth\">Status</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlisttd\">".$record[0]."</div>";
				$list .= "<div class=\"formlisttd\">".stripslashes($record[4])."</div>";
				$list .= "<div class=\"formlisttd\">".$dateline."</div>";
				$list .= "<div class=\"formlisttd\">".$record[2]."</div>";	
				$list .= "</div>";
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlistth\">Amount</div>";
				$list .= "<div class=\"formlistth\">View PDF</div>";
				$list .= "<div class=\"formlistth\">Select</div>";
				$list .= "<div class=\"formlistth\">Confirm Selection</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttr\">";
				$list .= "<div class=\"formlisttd desktopcart\">".$record[0]."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".stripslashes($record[4])."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".$dateline."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".$record[2]."</div>";
				$list .= "<div class=\"formlisttd\">".$new_total_gbp."</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<a href=\"$site_url/oos/invoice.php?invoice=".$record[0]."\" target=\"_blank\">Click here</a>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<input id=\"chk-".$p_id."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$p_id."\" checked>";
				$list .= "<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">";
				$list .= "<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">";
				$list .= "<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-invid\" value=\"".$record[0]."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-currency\" value=\"".$currency_name."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-date\" value=\"".$dateline."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-type\" value=\"".stripslashes($record[4])."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-total\" value=\"".$totalin."\">";
				$list .= "<input type=\"hidden\" name=\"".$record[0]."-invtotal[]\" value=\"".$totalin."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-orderid\" value=\"".stripslashes($record[5])."\">";
				$list .= "</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<input type=\"number\" step=\"any\" name=\"".$p_id."-alloc\" class=\"allocate\" value=\"" . $curr . "\" />";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-current\" class=\"allocate\" value=\"" . $curr . "\" />";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-pid\" class=\"allocate\" value=\"" . $pid ."\" />";
				$list .= "</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttrspacer\"></div>";
				
			}
		}
	}
	
	
	else {
		$statusform	=	"";
			$dateline = date("d-m-Y",$record[3]);
			$invoicer_result = $sql_command->select("quotation_proforma_history 
													LEFT OUTER JOIN $db_currency_conversion 
													ON $db_currency_conversion.currency_name = quotation_proforma_history.currency",
													"quotation_proforma_history.currency,
													$db_currency_conversion.currency_symbol",
												   "WHERE invoice_id='".addslashes($invoiceno)."'");
			$invoice_rowr = $sql_command->result($invoicer_result);
			$c_symbol = "£";
			$p_id = $record[0];
			if ($record[4]=="Deposit") {
				$currency_name = $invoice_rowr[0];
				$c_symbol = $invoice_rowr[1];
				$curr_i = "&currency=".$currency_name;
			}
		//	else { 
				include("../_includes/fn_invoice-payment-v3.php");
				$currency_name = "Pound";
				$totalin		=	str_replace(",","",number_format($total_gbp,2));
				$new_total_gbp	=	$c_symbol." ".str_replace("-","",number_format($total_gbp,2));
		//	}

	
			$result2 = $sql_command->select("customer_payments",
											"p_id,
											p_amount,payment_currency",
											"WHERE pay_no=".$_GET['pid']." 
											AND invoice_id=".$record[0]);
			$rowsa = $sql_command->result($result2);
//			$list .= "selectp_id, p_amount,payment_currency from customer_payments WHERE pay_no=".$_GET['pid']." 											AND invoice_id=".$record[0];
			$currency_name = $rowsa[2];
			$currency_name = (empty($currency_name)) ? "Pound" : $currency_name;
			$curr = str_replace(",","",number_format($rowsa[1],2));
			$pid = $rowsa[0];
			if ($record[2]!=="Paid" || !empty($pid)) {
			//$list .= "SELECT p_i, p_amount, payment_currency FROM customer_payments WHERE pay_no=".$_GET['pid']." 
				//							AND invoice_id=".$record[0];
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlistth\">Invoice ID</div>";
				$list .= "<div class=\"formlistth\">Type</div>";
				$list .= "<div class=\"formlistth\">Date</div>";
				$list .= "<div class=\"formlistth\">Status</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlisttd\">".$record[0]."</div>";
				$list .= "<div class=\"formlisttd\">".stripslashes($record[4])."</div>";
				$list .= "<div class=\"formlisttd\">".$dateline."</div>";
				$list .= "<div class=\"formlisttd\">".$record[2]."</div>";	
				$list .= "</div>";
				$list .= "<div class=\"formlisttr mobilecart\">";
				$list .= "<div class=\"formlistth\">Amount</div>";
				$list .= "<div class=\"formlistth\">View PDF</div>";
				$list .= "<div class=\"formlistth\">Select</div>";
				$list .= "<div class=\"formlistth\">Confirm Selection</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttr\">";
				$list .= "<div class=\"formlisttd desktopcart\">".$record[0]."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".stripslashes($record[4])."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".$dateline."</div>";
				$list .= "<div class=\"formlisttd desktopcart\">".$record[2]."</div>";
				$list .= "<div class=\"formlisttd\">".$new_total_gbp."</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<a href=\"$site_url/oos/invoice.php?invoice=".$record[0]."\" target=\"_blank\">Click here</a>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<input id=\"chk-".$p_id."\" type=\"checkbox\" name=\"payinv[]\" value=\"".$p_id."\" checked>";
				$list .= "<input type=\"hidden\" name=\"invoice_id\" value=\"".$record[0]."\">";
				$list .= "<input type=\"hidden\" name=\"invoice_type\" value=\"".stripslashes($record[4])."\">";
				$list .= "<input type=\"hidden\" name=\"order_id\" value=\"".stripslashes($record[5])."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-invid\" value=\"".$record[0]."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-currency\" value=\"".$currency_name."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-date\" value=\"".$dateline."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-type\" value=\"".stripslashes($record[4])."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-total\" value=\"".$totalin."\">";	
				$list .= "<input type=\"hidden\" name=\"".$record[0]."-invtotal[]\" value=\"".$totalin."\">";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-orderid\" value=\"".stripslashes($record[5])."\">";
				$list .= "</div>";
				$list .= "<div class=\"formlisttd\">";
				$list .= "<input type=\"number\" step=\"any\" name=\"".$p_id."-alloc\" class=\"allocate\" value=\"" . $curr . "\" />";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-current\" class=\"allocate\" value=\"" . $curr . "\" />";
				$list .= "<input type=\"hidden\" name=\"".$p_id."-pid\" class=\"allocate\" value=\"" . $pid ."\" />";
				$list .= "</div>";
				$list .= "</div>";
				$list .= "<div class=\"formlisttrspacer\"></div>";
				
			}
		}
	}
echo $list; 
//var_dump($rowsa);
//var_dump($pid);
//var_dump($curr);
?></div></div>

<div style="float:right; margin-top:10px;"><input type="submit" name="action" value="Modify Payment"></div>
</form>
<form action="<?php echo $site_url; echo $script; ?>" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<?
}

$get_template->bottomHTML();
$sql_command->close();

?>