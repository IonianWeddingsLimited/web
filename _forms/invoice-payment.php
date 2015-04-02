<script type="application/javascript">
$(function() {
var payinvcheck = 0;
var submitenabled = false;
var payLimit = <? echo $payLimit; ?>;
var thisChk = 0;
var thisChkA = 0;
var totalA = 0;
var myForm = $("input[name='payinv[]']");
	
	$("#allinv").click(function() {
	
	for( var i=0; i < myForm.length; i++ ) {
			var thisChk = $("input[name='payinv[]']").eq(i).val();
			var thisChkA = $("#"+thisChk+"-total").val();
			var totalA = $("#cart-total").val();
			if (totalA<payLimit) {
				totalA = parseFloat(totalA,12) + parseFloat(thisChkA,12);
				$("#cart-total").val(totalA); 
			$("input[name='payinv[]']").eq(i).attr("checked","checked");
				if (totalA>=payLimit) {
					alert("There is a £"+payLimit+" payment limit, exceeding this limit will cause a part payment to be allocated to the bottom invoice. \n\n\nIf you have selected multiple invoices, please feel free to make multiple payments."); 
					$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
				}
		}
			else { break; }
		}

		payinvcheck = $("input[name='payinv[]']:checked").length;
		$('input[type="submit"][name="payinvoices"]').removeAttr('disabled');
	});

	$("#noinv").click(function() {
	$("input[name='payinv[]']:checked").removeAttr("checked");
	$("input[name='payinv[]']:disabled").removeAttr("disabled");
		$('input[type="submit"][name="payinvoices"]').attr('disabled','disabled');
		$("#cart-total").val(0); 
	});

	$("input[name='payinv[]']").change(function() {
		currentchk = payinvcheck;										
		payinvcheck = $("input[name='payinv[]']:checked").length;
		thisChk = $(this).val();
		thisChkA = $("#"+thisChk+"-total").val();
		totalA = $("#cart-total").val();
		
		if (payinvcheck>currentchk) {
			totalA = parseFloat(totalA,12) + parseFloat(thisChkA,12);
			if (totalA>=payLimit) { 
				alert("There is a £"+payLimit+" payment limit, exceeding this limit will cause a part payment to be allocated to the bottom invoice. \n\n\nIf you have selected multiple invoices, please feel free to make multiple payments."); 
				$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
			}
		}
		else {
			totalA = parseFloat(totalA,12) - parseFloat(thisChkA,12);
			if (totalA>=payLimit) { 
				$("input[name='payinv[]']:not(:checked)").attr("disabled","disabled"); 
			}
			else {
				$("input[name='payinv[]']").removeAttr("disabled");
			}
		}
		if (payinvcheck>0) {
			switch(submitenabled) {
				case true:
					break;
				case false:
					$('input[type="submit"][name="payinvoices"]').removeAttr('disabled');
					submitenabled=true;
					break;
			}
		}
		else {
			$('input[type="submit"][name="payinvoices"]').attr('disabled','disabled');
			submitenabled=false;
		}
		$("#cart-total").val(totalA);

	});
});
</script>
<?

	require("_wopay/worldpayconfig.php"); 
	
function j_check_date($date) {
 if (strlen($date) > 10) {
  return FALSE;
 }
 else {
  $pieces = explode('/', $date);
  if (count($pieces) != 3) {
   return FALSE;
  }
  else {
   $day = $pieces[0];
   $month = $pieces[1];
   $year = $pieces[2];
   return checkdate($month, $day, $year); // this bit makes the function British - RULE BRITANNIA!!
  }
 }
}


$errorso = 0;
$errori = "";

if (isset($_POST['wedding-d'])) {
	$wdate = $_POST['wedding-d'];	
} else {
	$wdate = "DD/MM/YYYY";
}
if (isset($_POST['customer-id'])) { $cuid = $_POST['customer-id']; } else { $cuid = $level2_name; }
if ($cuid=="unset") { unset($_SESSION['iwcid']); $cuid = ""; } 

if ($wdate <> "DD/MM/YYYY") {
	$searchd = array('.', '-');
	$wdate = str_replace($searchd,"/",$wdate);
	$datevalid = j_check_date($wdate);
	list($day,$month,$year) = explode("/",$wdate);
	$dateval = mktime(0, 0, 0, $month, $day, $year);	
}

if(isset($_POST['viewinvoice']) && $_POST['customer-id']=="") { $errorso++; $errori = "Please enter a valid Ionian Wedding ID."; }
if(isset($_POST['viewinvoice']) && $datevalid==false) { $errorso++; $errord = "Please enter a valid date."; }
if(isset($_POST['viewinvoice']) && $errorso==0) {
	$iwcur = $sql_command->count_rows("clients","*","iwcuid = '" . addslashes($_POST['customer-id']) . "' AND wedding_date = '". $dateval ."' AND cli_pass = '".$_POST['password']."'");
	if($iwcur>0) {
		$_SESSION['iwcid'] = $_POST['customer-id'];		
	}
	else {
		$errori = "The information you provided is incorrect, please check and try again.";	
	}
}
if (isset($_SESSION['iwcid'])) {
		$iwcu = $sql_command->select("clients","id, title, firstname, lastname, address_1, address_2, address_3, town, postcode, email, tel, mob, wedding_date, mailing_list, country","WHERE iwcuid = '" . addslashes($_SESSION['iwcid']) . "' and deleted='No'");

		$iwcustomer = $sql_command->result($iwcu);
		$clid = $iwcustomer[0];
		$fname = $iwcustomer[1] . " " . $iwcustomer[2] . " " . $iwcustomer[3];
		$cname = $iwcustomer[2];
		$add1 = $iwcustomer[4];
		$add2 = $iwcustomer[5];
		$add3 = $iwcustomer[6];
		$town = $iwcustomer[7];
		$pcode = strtoupper($iwcustomer[8]);
		$emaila = $iwcustomer[9];
		$contactno = $iwcustomer[10];
		$wdates = date('d/m/Y',$iwcustomer[12]);
		$mlist = $iwcustomer[13];
		$mobno = $iwcustomer[11];
		$country = $iwcustomer[14];

?>
<div class="pageform">
				<div class='formheader'>
					<h1>Hello <? echo $cname; ?> <span class="notme">(<a href="/invoice-payment/unset/" title="exit" nofollow>Not you? click here</a>)</span></h1>
					<p>You are able to view and pay all your invoices marked as outstanding, if you have a query or an invoice is listed or is not listed below <a href="/contact-us/" title="Contact Us" target="_blank">please contact us.</a></p>
				</div>
				<div class="formheader">
					<h1>Contact details</h1>
				</div>
					<div class="formrow">
						<div class="formlabel">Name:</div>
						<div class="formelement"><? echo $fname; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Address:</div>
						<div class="formelement">
							<? echo $add1; if($add1) echo "<br />"; ?>
							<? echo $add2; if($add2) echo "<br />"; ?>
							<? echo $add3; if($add3) echo "<br />"; ?>
              <? echo $town; if($town) echo "<br />"; ?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Post code:</div>
						<div class="formelement"><? echo $pcode; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Wedding Date:</div>
						<div class="formelement"><? echo $wdates; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Telephone:</div>
						<div class="formelement"><? echo $contactno; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Mobile:</div>
						<div class="formelement"><? echo $mobno; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Email:</div>
						<div class="formelement"><a href="mailto:<? echo $emaila; ?>" title="<? echo $emaila; ?>"><? echo $emaila; ?></a></div>
						<div class="clear"></div>
					</div>
 <? 
if(isset($_POST['payinvoices']) || isset($_POST['payinvoice'])) {

	$desc=""; 
	if(isset($_POST['payinvoice'])) {
		$filterva = array(",","£","$","€"," ","-");
		$p = $_POST['payinvoice'];
		$link = "client_invoice";
		$totaltopay=array();
		$list="";
		$dateline = $_POST[$p.'-date'];
		$itype = $_POST[$p.'-type'];
		$itotal = str_replace($filterva,"",$itotal);
		$itotal = "£ ".number_format($_POST[$p.'-total'],2);
		$itotals = $_POST[$p.'-total'];
		$iorderid = $_POST[$p.'-orderid'];

			$itotals = str_replace($filterva,"",$itotals);
//			$amount = str_replace($filterva,"",$amount);


		$desc .= "Invoice ".$p.":".$iorderid.":".$itotals;
		$list .=	"<div class=\"formlisttr\">
					<div class=\"formlisttd\">".$p."</div>
					<div class=\"formlisttd\">".$itype."</div>
					<div class=\"formlisttd\">".$dateline."</div>
					<div class=\"formlisttd\">
						<a href=\"$site_url/".$link.".php?invoice=".$p."\" target=\"_blank\">Click here</a>
					</div>
					<div id=\"etotal\" data-desc=\"".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2))."\" data-amount=\"".str_replace($filterva,"",number_format($itotals,2))."\" class=\"formlisttd\">".$itotal."</div>
					</div>";

		$totaltopay[$i] = $itotals;		
		
	} else {
		$payinv = $_POST['payinv'];
		$link = "client_invoice";
		$totaltopay=array();
		$list="";
		$i=0;
		$newarray = implode(",", $payinv);
		
		$filterva = array(",","£","$","€"," ","-");
		foreach ($payinv as $p_amount) {
			$pay_a[$p_amount] = number_format($_POST[$p_amount.'-total'],2);
		}
		$total_pay = str_replace($filterva,"",number_format(($payLimit - array_sum($pay_a)),2));
		
		$filterinv = end(array_filter($pay_a,function ($value) use($total_pay) { return ($value >= $total_pay); } ));
		
		foreach ($payinv as $p) {
			$dateline = $_POST[$p.'-date'];
			$itype = $_POST[$p.'-type'];
			

			$itotal = "£ ".number_format($_POST[$p.'-total'],2);
			$itotals = str_replace($filterva,"",$itotal);
			$iorderid = $_POST[$p.'-orderid'];

	
		//	$itotal = str_replace($filterva,"",$itotal);
			$itotals = str_replace($filterva,"",$itotals);
		//	$amount = str_replace($filterva,"",$amount);
			$paycheck = $sql_command->count_nrow("$database_invoice_history",
												"*",
												"invoice_id IN (".$newarray.")
												and order_id='".addslashes($iorderid)."' 
												and currency='Euro' and item_type='Package'");

			
//			$paycheck = $sql_command->count_rows("$database_customer_invoices","*","$database_customer_invoices.order_id='".$iorderid."' AND $database_customer_invoices.id IN (".$newarray.") AND $database_customer_invoices.id!='".addslashes($p)."'");
			if ($itype==="Deposit" && $paycheck>0) {
				
			
				$list .=	"<div class=\"formlisttr\">
								<div class=\"formlisttd\">
								".$p." - Deposit Invoice removed due to selecting to pay main invoice(s).
								</div>
								<div class=\"formlisttd\">".$itype."</div>
								<div class=\"formlisttd\">".$dateline."</div>
								<div class=\"formlisttd\">n/a</div>
								<div class=\"formlisttd\">£ 0.00</div>
							</div>
							";
			}
			else{
				$filterva = array(",","£","$","€"," ","-");
				$curr_total = str_replace($filterva,"",number_format(array_sum($totaltopay),2));
				$totaltopay[$p] = str_replace($filterva,"",number_format($itotals,2));
				
				$itotals = (array_sum($totaltopay) >= $payLimit) ?  $payLimit-$curr_total:  $itotals;
				$payID = (array_sum($totaltopay) >= $payLimit) ?  "etotal":  count($p)>1 ?  "": "etotal";				
				if ($curr_total<=$payLimit) {
					if ($i === 0) { 
						$desc = "Invoice ".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2)); 
						$i++; 
					}
					else { 
						$desc .= " and ".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2));
						$i++; 
					}
					$list .=	"<div class=\"formlisttr\">
								<div class=\"formlisttd\">".$p."</div>
								<div class=\"formlisttd\">".$itype."</div>
								<div class=\"formlisttd\">".$dateline."</div>
								<div class=\"formlisttd\">
									<a href=\"$site_url/".$link.".php?invoice=".$p."\" target=\"_blank\">Click here</a>
								</div>";				
					$list .= "<div id=\"".$payID."\" data-desc=\"".$p.":".$iorderid.":".str_replace($filterva,"",number_format($itotals,2))."\" data-amount=\"".str_replace($filterva,"",number_format($itotals,2))."\" class=\"formlisttd\">£ ".number_format($itotals,2)."</div></div>";
					$totaltopay[$p] = str_replace($filterva,"",number_format($itotals,2));
				}
				else {
					$totaltopay[$p] = 0;	
				}
			}
		}
		$itotals = str_replace(",","",number_format(array_sum($totaltopay),2));
	}
	require("_wopay/paymentToken.php");
?>
	<div class="formheader">
		<h1>Confirm invoices below</h1>
	</div>
	<div class="pageform">
		<div class="formrow">
			<div class="formlist">
				<div class="formlisttable">
					<div class="formlisttr">
						<div class="formlistth">Invoice ID</div>
						<div class="formlistth">Type</div>
						<div class="formlistth">Date</div>
						<div class="formlistth">View PDF</div>
						<div class="formlistth">Amount</div>
					</div>
					<?	echo $list; ?>
					<div id="ccload" class="formlisttr">
						<div class="formlisttd">&nbsp;</div>
						<div class="formlisttd">&nbsp;</div>
						<div class="formlisttd">&nbsp;</div>
						<div class="formlisttd">Total:</div>
						<div id="stotal" class="formlisttd"><strong><? echo "£ ".number_format(array_sum($totaltopay),2); ?></strong></div>
					</div>
				</div>
				<div class="formlisttable">
					<div class="formlisttr">
						<div class="formlisttd"></label><input class="formsubmit" name="invoice_submit" type="submit" value="Go to WorldPay &raquo;"/></div>
					</div>
				</div>
			</div>
		</div>
	</div>	
 </form></div>
 <?
}
else {
	?>
 <div class='formheader'>
  <h1>Invoices, Quotations and Deposits</h1>
 </div>
 <?	echo "<form class=\"pageform\" name=\"payin\" action=\"$site_url/invoice-payment/\" method=\"post\">"; ?>
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
			<div class="formlistth">Pay</div>
		</div>
 <? 
 $result = $sql_command->select("$database_customer_invoices,$database_order_details","$database_customer_invoices.id,
							$database_customer_invoices.iw_cost,
							$database_customer_invoices.status,
							$database_customer_invoices.timestamp,
							$database_customer_invoices.type,
							$database_customer_invoices.order_id,
							$database_customer_invoices.included_package","WHERE 
							$database_customer_invoices.order_id=$database_order_details.id AND 
							$database_order_details.client_id='".addslashes($clid)."' AND 
							($database_customer_invoices.status='Outstanding' OR 
							$database_customer_invoices.status='Quotation')
							ORDER BY $database_customer_invoices.id DESC");
$row = $sql_command->results($result);
$list = "";
/*$list = "SELECT $database_customer_invoices.id,
							$database_customer_invoices.iw_cost,
							$database_customer_invoices.status,
							$database_customer_invoices.timestamp,
							$database_customer_invoices.type,
							$database_customer_invoices.order_id,
							$database_customer_invoices.included_package FROM $database_customer_invoices,$database_order_details WHERE 
							$database_customer_invoices.order_id=$database_order_details.id AND 
							$database_order_details.client_id='".addslashes($clid)."' AND 
							($database_customer_invoices.status='Outstanding' OR 
							$database_customer_invoices.status='Quotation')
							ORDER BY $database_customer_invoices.id DESC"; */ 
foreach($row as $record) {

$dateline = date("d-m-Y",$record[3]);

if($record[4] == "Deposit") { $link = "client_invoice"; } else { $link= "client_invoice"; }

/*
if($record[6] == "Yes") {
$result_cost = $sql_command->select($database_customer_invoices,"iw_cost","WHERE order_id='".addslashes($record[5])."' and type='Deposit' and status='Paid'");
$record_cost = $sql_command->result($result_cost);
$total_invoice_cost = $record[1] - $record_cost[0];	
} else {
$total_invoice_cost = $record[1];	
}
*/

if($record[2]!='Outstanding' AND $record[2]!='Quotation') { $paystatus = "disabled"; } else { $paystatus = ""; } 

if ($record[4]=="Deposit") {
	$resp = $sql_command->select("customer_payments,customer_transactions",
							"sum(customer_payments.p_amount)",
							"WHERE customer_transactions.p_id = customer_payments.pay_no 
							AND customer_payments.status != 'Unpaid'
							AND customer_payments.invoice_id = '".addslashes($record[0])."' AND 
							customer_transactions.status = 'Paid'");

	$respr = $sql_command->result($resp);
	$totalpp = $record[1] - $respr[0];
	$new_total_gbp = "£ ".number_format($totalpp,2);
	$totalin = number_format($totalpp,2);
}
else {
	$invoiceno = $record[0];
	include("_includes/fn_invoice-payment-v3.php");
	$totalin		=	number_format($total_gbp,2);
	$new_total_gbp	=	"£ ".number_format($total_gbp,2);
}
$filterva = array(",","£","$","€"," ","-");
$totalin = str_replace($filterva,"",$totalin);
$list .= "
<div class=\"formlisttr mobilecart\">
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
	<div class=\"formlistth\">Confirm</div>
</div>
<div class=\"formlisttr\">
	<div class=\"formlisttd desktopcart\">".$record[0]."</div>
	<div class=\"formlisttd desktopcart\">".stripslashes($record[4])."</div>
	<div class=\"formlisttd desktopcart\">".$dateline."</div>
	<div class=\"formlisttd desktopcart\">".$record[2]."</div>
	<div class=\"formlisttd\">".$new_total_gbp."</div>
	<div class=\"formlisttd\">
		<a href=\"$site_url/".$link.".php?invoice=".$record[0]."\" target=\"_blank\">Click here</a>
	</div>
	<div class=\"formlisttd\">
		<input type=\"checkbox\" name=\"payinv[]\" value=\"".$record[0]."\" ".$paystatus.">
		<input type=\"hidden\" name=\"client_id\" value=\"".$clid."\">
		<input type=\"hidden\" name=\"".$record[0]."-invoice_id\" value=\"".$record[0]."\">
		<input type=\"hidden\" name=\"".$record[0]."-invoice_type\" value=\"".stripslashes($record[4])."\">
		<input type=\"hidden\" name=\"".$record[0]."-order_id\" value=\"".stripslashes($record[5])."\">
		<input type=\"hidden\" name=\"".$record[0]."-invid\" value=\"".$record[0]."\">
		<input type=\"hidden\" name=\"".$record[0]."-date\" value=\"".$dateline."\">
		<input type=\"hidden\" name=\"".$record[0]."-type\" value=\"".stripslashes($record[4])."\">
		<input type=\"hidden\" id=\"".$record[0]."-total\" name=\"".$record[0]."-total\" value=\"".$totalin."\">
		<input type=\"hidden\" name=\"".$record[0]."-orderid\" value=\"".stripslashes($record[5])."\">
	</div>
	<div class=\"formlisttd\">
		<button name=\"payinvoice\" type=\"submit\" value=\"".$record[0]."\" ".$paystatus.">Pay</button>
	</div>
</div>
<div class=\"formlisttrspacer\"></div>
";
}

echo $list; ?></div></div>
 <? if($list) { ?>
		<div class="formlist">
			<div class="formlisttable">
				<div class="formlisttr">
					<div class="formlisttd">Select <a id="allinv" href="#payin">All</a> | <a id="noinv" href="#payin">None</a> <span>(Please note, only Outstanding invoices will be checked).</span></div>
					<div class="formlisttd"><input type="submit" name="payinvoices" value="Pay All" disabled></div>
				</div>
			</div>		
		</div>
   	<input type="hidden" id="cart-total" name="cart-total" value="0">
	</form>
  </div>

<? 
}
else {
?>
		<div class="formlist">
			<div class="formlisttable">
				<div class="formlisttr">
					<div class="formlisttd">No invoices outstanding.</div>
					<div class="formlisttd">&nbsp;</div>
				</div>
			</div>		
		</div>
	</form>
  </div>
<?	
}
}
}
else {
?>
<script>
	$(function() {
		$( "#wedding-d" ).datepicker({ dateFormat: 'dd/mm/yy' });
	});
	</script>
<form action="<? echo $site_url; ?>/invoice-payment/" class="pageform" id="invoices" method="post" name="invoices">
 <input type="hidden" name="page" value="invoice">
 <div class="formheader">
  <h1>Pay an Invoice</h1>
  <p> If you have an outstanding invoice you wish to make a payment against, please type your Ionian Weddings Customer ID and wedding date in the boxes below.</p>
 </div>
 <div class="formrow">
  <label class="formlabel" for="customer-id">Ionian Weddings ID:</label>
  <div class="formelement">
   <input class="formtextfieldlong" id="customer-id" name="customer-id" type="text" value="<? echo $cuid; ?>" />
  </div>
  <div class="clear"></div>
 </div>
 <div class="formrow">
  <label class="formlabel" for="wedding-d">Date of Wedding:</label>
  <div class="formelement">
   <input class="formtextfieldlong forminput" id="wedding-d" name="wedding-d" type="text" value="<? echo $wdate; ?>" title="DD/MM/YYYY" />
  </div>
  <div class="clear"></div>
 </div>
 <div class="formrow">
  <label class="formlabel" for="password">Password:</label>
  <div class="formelement">
   <input class="formtextfieldlong forminput" id="password" name="password" type="password" value="" title="Password" />
  </div>
  <div class="clear"></div>
 </div>
 <div class="formrow">
  <label class="formlabel" for="viewinvoice">&nbsp;</label>
  <div class="formelement">
   <input id="viewinvoice" name="viewinvoice" type="submit" value="View Invoices" />
  </div>
  <div class="clear"></div>
 </div>
 <div class="formrow">
 <label class="formlabel" for="viewinvoice">&nbsp;</label>
 <div class="formelement">
  <p><? echo $errori ; ?></p>
  <p><? echo $errord ; ?></p>
 </div>
 <div class="clear"></div>
 </div>
</form>
<? } ?>
<div class="cardlogorow">
 <!-- Payment Methods Displayed -->
 <!--
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=307221"></script><br /><br />
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=314618"></script><br /><br />
<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=314618"></script>-->
 <ul>
	<!-- Powered by WorldPay logo-->
	<li class="cardlogoitem floatleft"><a href="http://www.worldpay.com/" target="_blank" title="Payment Processing - WorldPay - Opens in new browser window"><img src="http://www.worldpay.com/images/poweredByWorldPay.gif" border="0" alt="WorldPay Payments Processing"></a></li>
	<li class="cardlogoitem floatright"><? echo $displayVISD; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayVISE; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayMaestro; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayVisa; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayMastercard; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayJCB; ?></li>
	<li class="cardlogoitem floatright"><? echo $displayELV; ?></li>
	<li class="clear"></li>
 </ul>
</div>
