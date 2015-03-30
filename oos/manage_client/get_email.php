<?
include("../_includes/function.smtp.php");

function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
}


if(!$_GET['id'] && !$_POST['id']) { $error .= "Missing ID<br>"; }
if(!is_numeric($_GET['id']) && !is_numeric($_POST['id'])) { $error .= "Invalid ID<br>"; }
$message = "";

$etarget = $_POST['etarget'].$_GET['a'];

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","$error","Link","oos/manage-client.php");
$get_template->bottomHTML();
$sql_command->close();
}

$id=(!$_GET['id']) ? $_POST['id'] : $_GET['id'];

	$ioiddet = $sql_command->select("$database_clients,$database_navigation","$database_clients.title,$database_clients.firstname,$database_clients.lastname,$database_clients.email,$database_clients.wedding_date,$database_clients.iwcuid,$database_clients.cli_pass,$database_navigation.page_name","WHERE $database_clients.id = '".addslashes($id)."' and $database_navigation.id = $database_clients.destination");
	$details = $sql_command->result($ioiddet);

	$name = $details[0]." ".$details[1]." ".$details[2];
	$femail = $details[3];
	$weddingd = date('d/m/Y',$details[4]);
	$userid = $details[5];
	$pass = $details[6];
	$destination = $details[7];

if ($_POST['etarget']=="emailpass" || $_GET['a']=="emailpass") {

$emailtype	=	"paymentpassword";
$emailsubject = "Your Ionian Weddings payment password.";
$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
$message .="<tr>";
$message .="<td colspan=\"2\" style=\"padding: 20px 0px 20px 0px;\">";
$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
$message .="</td>";
$message .="<td colspan=\"2\" style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
$message .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
$message .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
$message .="</td>";
$message .="</tr>";
$message .="<tr>";
$message .="<td colspan=\"4\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Account Password</h1>";
$message .="<br />";
$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $name,</h2>";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\"><br /> Thank you for placing your order for your perfect wedding with <a href=\"http://Ionianweddings.co.uk\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">Ionianweddings.co.uk</a>.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">You can view and pay your outstanding invoices on our website by clicking the link and using the password below along with your Ionian Weddings ID and wedding date.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">View and pay your invoices here - <a href=\"http://www.ionianweddings.co.uk/invoice-payment\" title=\"link\" target=\"_blank\" style=\"color: #c08827;\">http://www.ionianweddings.co.uk/invoice-payment</a>.</p>";
$message .="<ul style=\"margin: 0px 0px 0px 20px; padding: 0px 0px 10px 0px;\">";
$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">Password : <strong>$pass</strong></li>";
$message .="</ul>";
$message .="<br />";
$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
$message .="<br />";
$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";

//	header("Location: $site_url/oos/manage-client.php?a=history&id=".$id);

}

if ($_POST['etarget']=="emailconfirm" || $_GET['a']=="emailconfirm") {
	
	$ioiddet = $sql_command->select("$database_clients","title,firstname,lastname,email,wedding_date,iwcuid","WHERE id = '".addslashes($id)."'");
	$details = $sql_command->result($ioiddet);
	
	$name = $details[0]." ".$details[1]." ".$details[2];
	$femail = $details[3];
	$datestring		=	$details[4];
	$weddingd = date('d/m/Y',$datestring);
	
	$depositdate = strtotime ( '-3 month' , $datestring ) ;
	//$depositdate = strtotime($datestring.' -90 day');
	$depositdate = date('d/m/Y', $depositdate);
	$userid = $details[5];

	$result = $sql_command->select("$database_customer_invoices,$database_order_details","$database_customer_invoices.id,
								   $database_customer_invoices.iw_cost,
								   $database_customer_invoices.status,
								   $database_customer_invoices.timestamp,
								   $database_customer_invoices.type,
								   $database_customer_invoices.order_id,
								   $database_customer_invoices.included_package","WHERE 
								   $database_customer_invoices.order_id=$database_order_details.id AND ($database_customer_invoices.status='Outstanding' OR $database_customer_invoices.status='Pending') AND $database_order_details.client_id='".addslashes($id)."'
								   ORDER BY $database_customer_invoices.id DESC");
	$row = $sql_command->results($result);
	$list = "";
	
	$total_prev	=	0;

	foreach($row as $record) {
	
		$dateline = date("d-m-Y",$record[3]);
		
		if ($record[4]=="Deposit") {
			$resp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.invoice_id = '".addslashes($record[0])."'");
		
			$respr = $sql_command->result($resp);
			$totalpp = $record[1] - $respr[0];
			$new_total_gbp = "£ ".number_format($totalpp,2);
			$totalin = $totalpp;
			$DepositAmount = "<strong>".$new_total_gbp."</strong>";
		} else {
			$invoiceno = $record[0];
			include("../_includes/fn_invoice-payment.php");
				$resp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.invoice_id = '".addslashes($record[0])."'");
		
			$respr = $sql_command->result($resp);
			
			$new_total_gbp	=	$total_gbp - $total_prev;
			$new_total_gbp	=	$new_total_gbp - $respr[0];
			$totalin		=	$new_total_gbp;
			$new_total_gbp	=	"£ ".number_format($new_total_gbp,2);
		}
		$list .= "<tr>";
		
		$list .= "<td align=\"left\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$record[0]."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".stripslashes($record[4])."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$dateline."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$record[2]."</td>";
		
		$list .= "<td align=\"right\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\">".$new_total_gbp."</td>";
		
		$list .= "</tr>";
		
		$total_prev	= $total_gbp;
	}
	$emailtype	=	"paymentconfirm";
	$emailsubject = "Your Ionian Weddings payment confirmation.";
	$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
	$message .="<tr>";
	$message .="<td colspan=\"2\" style=\"padding: 20px 0px 20px 0px;\">";
	$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
	$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
	$message .="</td>";
	$message .="<td colspan=\"2\" style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
	$message .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
	$message .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
	$message .="</td>";
	$message .="</tr>";
	$message .="<tr>";
	$message .="<td colspan=\"4\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
	$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Confirmation</h1>";
	$message .="<br />";
	$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $name,</h2>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">I am pleased to advise that your wedding in <strong>". $destination ."</strong> on <strong>".$weddingd."</strong> has been confirmed. Please note that the venue does reserve the right to make changes to dates and times, although it is rare that this would happen.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">We received the ".$DepositAmount." deposit for the wedding thank you.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">After that you will only need to pay the balance 3 months before your wedding date. The final price of your package may vary slightly for 2014, (the venues may decide to increase their prices in the new year and taxes in Cyprus may also change) but this will all be confirmed nearer the time.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Please note that the deposit is non-refundable.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Final payment is due by <strong>".$depositdate."</strong>.</p>";
	$message .="<br />";
	$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">What do you need to do now?</h1>";
	$message .="<ul>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">We recommend that you take out insurance for your wedding abroad as soon as possible to benefit from cancellation, wedding attire, deposits cover, supplier failure etc. - you can find more information on our FAQ page <a href=\"http://www.ionianweddings.co.uk/faqs/\" target=\"_blank\">http://www.ionianweddings.co.uk/faqs/</a></li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Bookmark our wedding travel page....we have a link to our hotel site with special rates at hotels all over Cyprus and a best rate guarantee. You can also send the link to your guests so they can book their accommodation. <a href=\"http://www.ionianweddings.co.uk/planning-advice/wedding-travel/\" target=\"_blank\">http://www.ionianweddings.co.uk/planning-advice/wedding-travel/</a></li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Please also send me a photo of you two via email which goes on our files and it's useful when we brief suppliers and before your meetings in Cyprus with them.</li>";
	$message .="</ul>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">I also wanted to outline some important timescales as to when we'll be in touch with you for certain elements of your wedding:.</p>";
	$message .="<br />";
	$message .="<ul>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">4 months before your wedding - we need to finalise your package so at this point you should advise us of final guest numbers, menu choices, flower colours, cake flavour etc so we can confirm your wedding package and reception arrangements and final price.  Please also let us know if you'd like us to carry out the paperwork for you so we can include this in the invoice</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">3 months before your wedding - we need to receive your final balance</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">2 months before your wedding – if we are doing your paperwork, please send it to us as soon as you have the certificates of non-impediment (approx 2.5 months before your wedding and not less than 2 months before your wedding)</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">1 month before your wedding - you should send us your table / seating plans, and we'll confirm details of your pre-wedding meeting with your wedding co-ordinator which will take place when you arrive in Cyprus.  Please also send us:</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Flight details outgoing (date, time, flight number)</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Flight details return (date, time, flight number)</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Place of stay</li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Mobile phone number where we can reach you in Cyprus in case of anything urgent</li>";
	$message .="</ul>";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">We don't want to bombard you with emails so once initial arrangements are made we'll usually only contact you to arrange the above, however part of our service is to help you in the months and weeks leading up to your wedding.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Please note: we always try to reply to emails within 24 – 48 hours but sometimes, especially during peak season and Christmas, we get delays from our suppliers in Greece so it may take longer.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">In the meantime if you have any questions at all, please feel free to give us a call on 0208 894 1991 or email us.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">You can view and pay your outstanding invoices on our website by clicking the link and using the details below along with your password.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">View and pay your invoices here - <a href=\"http://www.ionianweddings.co.uk/invoice-payment\" title=\"link\" target=\"_blank\" style=\"color: #c08827;\">http://www.ionianweddings.co.uk/invoice-payment</a>.</p>";
	$message .="<ul style=\"margin: 0px 0px 0px 20px; padding: 0px 0px 10px 0px;\">";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">User-ID : <strong>$userid</strong></li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">Wedding Date : <strong>$weddingd</strong></li>";
	$message .="</ul>";
	$message .="<br />";
	$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin: 0px 0px 15px 0px;\">";
	$message .="<tr>";
	$message .="<th align=\"left\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice ID</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Type</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Date</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Status</th>";
	$message .="<th align=\"right\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Amount</th>";
	$message .="</tr>";
	$message .= $list;
	$message .="</table>";
	$message .="<br />";
	$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
	$message .="<br />";
	$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";
}


if ($_POST['etarget']=="emailinv" || $_GET['a']=="emailinv") {

	$ioiddet = $sql_command->select("$database_clients","title,firstname,lastname,email,wedding_date,iwcuid","WHERE id = '".addslashes($id)."'");
	$details = $sql_command->result($ioiddet);
	
	$name = $details[0]." ".$details[1]." ".$details[2];
	$femail = $details[3];
	$weddingd = date('d/m/Y',$details[4]);
	$userid = $details[5];

	$result = $sql_command->select("$database_customer_invoices,$database_order_details","$database_customer_invoices.id,
								   $database_customer_invoices.iw_cost,
								   $database_customer_invoices.status,
								   $database_customer_invoices.timestamp,
								   $database_customer_invoices.type,
								   $database_customer_invoices.order_id,
								   $database_customer_invoices.included_package","WHERE 
								   $database_customer_invoices.order_id=$database_order_details.id AND ($database_customer_invoices.status='Outstanding' OR $database_customer_invoices.status='Pending') AND $database_order_details.client_id='".addslashes($id)."'
								   ORDER BY $database_customer_invoices.id DESC");
	$row = $sql_command->results($result);
	$list = "";
	
	$total_prev	=	0;

	foreach($row as $record) {

		$dateline = date("d-m-Y",$record[3]);

		if ($record[4]=="Deposit") {
			$resp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.invoice_id = '".addslashes($record[0])."'");
		
			$respr = $sql_command->result($resp);
			$totalpp = $record[1] - $respr[0];
			$new_total_gbp = "£ ".number_format($totalpp,2);
			$totalin = $totalpp;
		} else {
			$invoiceno = $record[0];
			include("../_includes/fn_invoice-payment.php");
				$resp = $sql_command->select("customer_payments,customer_transactions",
									 "sum(customer_payments.p_amount)",
									 "WHERE customer_transactions.p_id = customer_payments.pay_no 
									 AND customer_transactions.status = 'Paid'
									 AND customer_payments.status != 'Unpaid'
									 AND customer_payments.invoice_id = '".addslashes($record[0])."'");
		
			$respr = $sql_command->result($resp);
			
			$new_total_gbp	=	$total_gbp - $total_prev;
			$new_total_gbp	=	$new_total_gbp - $respr[0];
			$totalin		=	$new_total_gbp;
			$new_total_gbp	=	"£ ".number_format($new_total_gbp,2);
		}
		$list .= "<tr>";
		
		$list .= "<td align=\"left\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$record[0]."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".stripslashes($record[4])."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$dateline."</td>";
		
		$list .= "<td align=\"center\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">".$record[2]."</td>";
		
		$list .= "<td align=\"right\" style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\">".$new_total_gbp."</td>";
		
		$list .= "</tr>";
		
		$total_prev	= $total_gbp;
	}
	$emailtype	=	"paymentlink";
	$emailsubject = "Your Ionian Weddings dream wedding invoices are ready to be viewed.";
	$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"625\" style=\"margin:0 auto;\">";
	$message .="<tr>";
	$message .="<td colspan=\"2\" style=\"padding: 20px 0px 20px 0px;\">";
	$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif\" alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\" border=\"0\" title=\"Ionian Weddings - Exclusively Mediterranean Weddings\" style=\"display: block; margin: 0px 0px 5px 0px;\" />";
	$message .="<img src=\"http://www.ionianweddings.co.uk/images/interface/i_exclusively_mediterranean_weddings.gif\" border=\"0\" title=\"Exclusively Mediterranean Weddings\" alt=\"Exclusively Mediterranean Weddings\" style=\"display: block;\" />";
	$message .="</td>";
	$message .="<td colspan=\"2\" style=\"padding: 0px 0px 0px 0px; text-align: right;\">";
	$message .="<p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">Call us today on 020 8894 1991 / 020 8898 9917</p>";
	$message .="<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England</p>";
	$message .="</td>";
	$message .="</tr>";
	$message .="<tr>";
	$message .="<td colspan=\"4\" style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\">";
	$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Your Invoice is ready</h1>";
	$message .="<br />";
	$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $name,</h2>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Thank you for placing your order for your perfect wedding with <a href=\"http://Ionianweddings.co.uk\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">Ionianweddings.co.uk</a>.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">You can view and pay your outstanding invoices on our website by clicking the link and using the details below along with your password.</p>";
	$message .="<br />";
	$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">View and pay your invoices here - <a href=\"http://www.ionianweddings.co.uk/invoice-payment\" title=\"link\" target=\"_blank\" style=\"color: #c08827;\">http://www.ionianweddings.co.uk/invoice-payment</a>.</p>";
	$message .="<ul style=\"margin: 0px 0px 0px 20px; padding: 0px 0px 10px 0px;\">";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">User-ID : <strong>$userid</strong></li>";
	$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">Wedding Date : <strong>$weddingd</strong></li>";
	$message .="</ul>";
	$message .="<br />";
	$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin: 0px 0px 15px 0px;\">";
	$message .="<tr>";
	$message .="<th align=\"left\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice ID</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Type</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Date</th>";
	$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Status</th>";
	$message .="<th align=\"right\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Invoice Amount</th>";
	$message .="</tr>";
	$message .= $list;
	$message .="</table>";
	$message .="<br />";
	$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
	$message .="<br />";
	$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";
}



$get_template->topHTML();


if ($_POST['action']=="Send Email") {
	
	$pename = test_input($_POST["emailname"]);
	$pemail = test_input($_POST["emailadd"]);

	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 	
	
	$name = (empty($pename)) ? $name : $pename;
	$femail = (empty($pemail)) ? $femail : (!preg_match($regex,$pemail)) ? $femail : $pemail;
	
	$emailadd = "payments@ionianweddings.co.uk"; 
	$emailname = "<Ionian Weddings Ltd>";



	$global_from = "<{$emailadd}> \"{$emailname}\"";

	$mail = new smtp_email;
	$mail->Username = $smtp_email;
	$mail->Password = $smtp_password;
	$mail->SetFrom($smtp_email,$tagline);	// Name is optional
	$mail->AddTo($femail,$name);	// Name is optional
	
	$mail->Subject = $name.", ".$emailsubject;
	$mail->Message = $message;
	$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
	$mail->ResponseTimeout = 8;		// CMD response timeout (sec)
	
	$e_date = date("Y-m-d h:m:s");
	
	$cols = "reference_id, reference_type, user_id, email, addressee";
	$vals = "
				'".addslashes($id)."',
				'".addslashes($emailtype)."',
				'".addslashes($login_id)."',
				'".addslashes($femail)."',
				'".addslashes($name)."',
				'".addslashes($e_date)."'";
	$e_insert = $sql_command->insert("emails","$cols,created",$vals);

	$success = $mail->Send();
}

echo "<div style=\"background:#FFFFFF;\">";
if ($success) { echo "<center><h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Email sent successfully.</h1></center>"; }

echo $message;
print_r($etarget);
echo "</div>";

echo "
<div style=\"clear:both;\"></div>
<div style=\"margin:0 auto; width:50%;\">
	<div style=\"float:left;\">
	    <form class=\"pageform\" method=\"post\" action=\"$site_url/oos/manage-client.php\" name=\"emails\">
        	<table>
			<tr>
			<td><label style=\"float:left;\" for=\"emailname\">Addresse:</label></td>
			<td><input type=\"text\" id=\"emailname\" name=\"emailname\" value=\"".$name."\" /></td>
			</tr>
			<tr>
			<td><label style=\"float:left;\" for=\"emailadd\">Email Address:</label></td>
			<td><input type=\"text\" id=\"emailadd\" name=\"emailadd\" value=\"".$femail."\" /></td>
			</tr>
			</table>
            <input type=\"hidden\" name=\"etarget\" value=\"".$etarget."\" />
			<input type=\"hidden\" name=\"id\" value=\"".$id."\" />
			<center>
			<a href=\"$site_url/oos/manage-client.php?a=history&id=".$id."\" style=\"text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 7px 2px 7px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;\"/>Back</a>
			<input type=\"submit\" name=\"action\" value=\"Send Email\" />
			</center>
		</form>
	</div>
</div>";
	$result = $sql_command->select("$database_emails,$database_users","$database_emails.reference_id,
								   $database_emails.email,
								   $database_emails.addressee,
								   $database_emails.message,
								   $database_emails.filename,
								   $database_emails.sent,
								   $database_emails.timestamp,
								   $database_users.username",
								   "WHERE $database_emails.reference_id='".$id."' and
								   $database_emails.reference_type = '".$emailtype."' and
								   $database_emails.user_id=$database_users.id 
								   ORDER BY $database_emails.email_id DESC");
	$row = $sql_command->results($result);
	
	foreach($row as $record) {
		$emaildate = date("d-m-Y h:i A",$record[6]);
		//$emailfilename	=	stripslashes($record[10])."/".stripslashes($record[1])."-".strtotime($record[6]).".pdf";
		$emailtransaction_html .= "
		<div style=\"float:left; width:140px; margin:5px;\">".$record[6]."</div>
		<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($record[7])."</div>
		<div style=\"float:left; margin:5px;\">".stripslashes($record[2])." <a href=\"mailto:".stripslashes($record[1])."\">".stripslashes($record[1])."</a></div>
		<!--<div style=\"float:left; width:60px; margin:5px;\"><a href=\"/_po".stripslashes($record[4])."\" target=\"_blank\">View PO</a></div>
		<div style=\"float:left; margin:5px;\">".stripslashes($record[3])."</div>-->
		<div style=\"clear:left;\"></div>
		";
	}
	
	if ($emailtransaction_html) {
?>

<div style="clear:left;"></div>
<h2 style="margin-top:10px;">Email History</h2>
<div style="float:left; width:140px; margin:5px;"><strong>Date</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Sent By</strong></div>
<div style="float:left; width:170px; margin:5px;"><strong>Recipient</strong></div>
<!--<div style="float:left; width:60px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; margin:5px;"><strong>message</strong></div>-->
<div style="clear:left;"></div>
<?
		echo $emailtransaction_html;
	}

$get_template->bottomHTML();
$sql_command->close();




?>
