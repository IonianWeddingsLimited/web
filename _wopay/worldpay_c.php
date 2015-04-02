<?php //Worldpay 

if (!$_GET['ti']) {
	header("Location: http://www.ionianweddings.co.uk/invoice-payment/");
}



// class definition
class WorldP_Response {
    // define properties
    public $trans_id = null;
    public $trans_status = null;
    public $trans_time = null;
    public $auth_amount = null;
    public $auth_currency = null;
    public $auth = null;
    public $ip_add = null;
    public $desc = null;
    public $emails = null;
    public $cartID = null;
	public $card_t = null;

    // constructor
    public function __construct() {
        $this->trans_id = $_GET['ti'];
        $this->trans_status = $_GET['ts']; //should be either Y (successful) or C (cancelled)
        $this->trans_time = ((int) $_GET['tt'])/1000;
        $this->auth_amount = $_GET['aa'];
        $this->auth_currency = $_GET['ac'];
        $this->auth = $_GET['au'];
        $this->ip_add = $_GET['ip'];
        $this->desc = $_GET['ds'];
        $this->emails = $_GET['em'];
        $this->cartID = urldecode($_GET['ci']);
		$this->card_t = $_GET['ct'];
    }
}

//Response from Worldpay
$wp_response = new WorldP_Response();

$today = getdate();
$todays_date = $today[0];
$diff = $todays_date - $wp_response->trans_time;

if ($diff > 30 or isset($_COOKIE['just-paid'])) {
include("_forms/invoice-payment.php");
}
else {
	$cartid = $wp_response->cartID;
 	$transid = $wp_response->trans_id;
	$amount = $wp_response->auth_amount;
	$currency = $wp_response->auth_currency;
	$ip_addy = $wp_response->ip_add;
	$desc = $wp_response->desc;
	$email = $wp_response->emails;
	$ctype = $wp_response->card_t;
	
	$status = $wp_response->trans_status;
	$statusa = str_replace("Y","Pending",$status);
	$statusa = str_replace("C","Cancelled",$statusa);
	$statusa = (!$statusa) ? "Refused": $statusa;
	
	$arr_filter = array("£","€",","," ","$");
	$desc = str_replace($arr_filter,"",$desc);
	list($desca,$totala) = explode("Total",$desc);
	$totals = str_replace($arr_filter,"",$totala);
	$totalt = (float) $totals;
	$totalt = str_replace($arr_filter,"",number_format($totalt,2));
	$iwid = str_replace("IW-","",$cartid);
	$desc = str_replace("Invoice ","",$desca);
	$invoices = explode(",",str_replace(" and ",",",$desc));
	$ctype = str_replace("/","",$ctype);
	
	
	$sql_command->update("customer_transactions","p_amount='".addslashes($totalt)."',
						 transaction_id='".addslashes($transid)."',
						 ip_add=\"".$ip_addy."\",
						 status=\"".$statusa."\",
						 cardtype='".$ctype."',
						 status = 'Pending'",
						 "p_id = '".addslashes($iwid)."'");
	
	$iwidu = $sql_command->select("customer_transactions","customer_id","WHERE p_id = '" . addslashes($iwid) . "'");
		
	$iwcustomerid = $sql_command->result($iwidu);
	
	
	$iwcu = $sql_command->select("clients","id, title, firstname, lastname, address_1, address_2, address_3, town, postcode, email, tel, mob, wedding_date, mailing_list","WHERE id = '" . addslashes($iwcustomerid[0]) . "'");
		
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

	
    if($status === "Y"){ 

		foreach($invoices as $inv) {
			list($invno,$ordno,$tamount) = explode(":",$inv);
			$invno = (int) $invno;
			$ordno = (int) $ordno;
			$tamount = (float) $tamount;
			
			
			
			$tamount = (count($invoices)>1) ? ($tamount<=$totalt) ? str_replace($arr_filter,"",number_format($tamount,2)) : str_replace($arr_filter,"",number_format($totalt,2)) : str_replace($arr_filter,"",number_format($totalt,2));
			
//			$listi .= $tamount."<br />";
			
			$cols = "pay_no,invoice_id,order_id,p_amount,status";
			$vals = "'".addslashes($iwid)."','".addslashes($invno)."','".addslashes($ordno)."','".addslashes($tamount)."','Paid'";
			$sql_command->insert("customer_payments",$cols,$vals);
			//$sql_command->update($database_customer_invoices,"status='Pending'","id='".addslashes($invno)."'");

		}
		
		setcookie("just-paid", true, time()+30);
	
	?>
<div class="pageform">
  <div class="formheader">
    <h1>Transaction Pending</h1>
    <p>Thank you for submitting a payment</p>
    <p>We are now awaiting confirmation from World Pay regarding this payment.</p>
  </div>
  <div class="formheader">
<?php // echo $listi; ?>
					<h1>Contact details</h1>
				</div>
					<div class="formrow">
						<div class="formlabel">Name:</div>
						<div class="formelement"><?php echo $fname; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Address:</div>
						<div class="formelement">
							<?php echo $add1; if($add1) echo "<br />"; ?>
							<?php echo $add2; if($add2) echo "<br />"; ?>
							<?php echo $add3; if($add3) echo "<br />"; ?>
                            <?php echo $town; if($town) echo "<br />"; ?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Post code:</div>
						<div class="formelement"><?php echo $pcode; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Wedding Date:</div>
						<div class="formelement"><?php echo $wdates; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Telephone:</div>
						<div class="formelement"><?php echo $contactno; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Mobile:</div>
						<div class="formelement"><?php echo $mobno; ?></div>
						<div class="clear"></div>
					</div>
					<div class="formrow">
						<div class="formlabel">Email:</div>
						<div class="formelement"><a href="mailto:<?php echo $emaila; ?>" title="<?php echo $emaila; ?>"><?php echo $emaila; ?></a></div>
						<div class="clear"></div>
					</div>
  <?
  

	echo "<div class=\"formrow\"><div class=\"formlabel\">Transaction ID:</div><div class=\"formelement\">".$transid."</div><div class=\"clear\"></div></div>";

	echo "<div class=\"formrow\"><div class=\"formlabel\">Cart ID:</div><div class=\"formelement\">".$cartid."</div><div class=\"clear\"></div></div>";
$surcharge = number_format($amount-$totalt,2);
$surcharge = ($surcharge>0) ? " - (Includes £ ".number_format($surcharge,2)." credit card fee).": "";
	echo "<div class=\"formrow\"><div class=\"formlabel\">Transaction Amount:</div><div class=\"formelement\">£ ".number_format($amount,2).$surcharge."</div><div class=\"clear\"></div></div>";




?>
  
</div>
<?php
}
else if($status === "C") {
?>
<div class="pageform">
  <div class="formheader">
    <h1>Payment Cancelled</h1>
    <p>Worldpay informs us that you cancelled the payment process, if this is incorrect please try again by clicking this link <a href="/invoice-payment/" title="Ionian Weddings">Invoice Payment</a>. </p>
  </div>
</div>
<?
} 
else {
	?>
<div class="pageform">
  <div class="formheader">
    <h1>Payment Error</h1>
    <p>Sorry something went wrong with your transaction, please try again by clicking this link <a href=\"/invoice-payment/\" title=\"Ionian Weddings\">Invoice Payment</a>. </p>
  </div>
</div>
<?
}

	$sql_command->insert($database_client_historyinfo,
						 "client_id,user_id,comment,timestamp",
						 "'".addslashes($clid)."',7,'Payment added through worldpay (# ".$iwid.") - ".$statusa."','".addslashes($time)."'");

} 
 ?>
