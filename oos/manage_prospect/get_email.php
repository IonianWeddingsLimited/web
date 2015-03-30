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
$get_template->errorHTML("Manage Prospect","Oops!","$error","Link","oos/manage-prospect.php");
$get_template->bottomHTML();
$sql_command->close();
}

$id=(!$_GET['id']) ? $_POST['id'] : $_GET['id'];



if ($_POST['etarget']=="emailpass" || $_GET['a']=="emailpass") {
	$ioiddet = $sql_command->select("$database_clients","title,firstname,lastname,email,wedding_date,iwcuid,cli_pass","WHERE id = '".addslashes($id)."'");
	$details = $sql_command->result($ioiddet);

	$name = $details[0]." ".$details[1]." ".$details[2];
	$femail = $details[3];
	$weddingd = date('d/m/Y',$details[4]);
	$userid = $details[5];
	$pass = $details[6];

$emailtype = "quotationpassword";

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
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\"><br /> Thank you for your enquiry for your perfect wedding with <a href=\"http://Ionianweddings.co.uk\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">Ionianweddings.co.uk</a>.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">You can view your quotations on our website by clicking the link and using the password below along with your wedding date.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">View your quotations here - <a href=\"http://www.ionianweddings.co.uk/quotations\" title=\"link\" target=\"_blank\" style=\"color: #c08827;\">http://www.ionianweddings.co.uk/quotations</a>.</p>";
$message .="<ul style=\"margin: 0px 0px 0px 20px; padding: 0px 0px 10px 0px;\">";
$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">Password : <strong>$pass</strong></li>";
$message .="</ul>";
$message .="<br />";
$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
$message .="<br />";
$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";

//	header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$id);

}

if ($_POST['etarget']=="emailinv" || $_GET['a']=="emailinv") {
	
$ioiddet = $sql_command->select("$database_clients","title,firstname,lastname,email,wedding_date,iwcuid","WHERE id = '".addslashes($id)."'");
$details = $sql_command->result($ioiddet);

$name = $details[0]." ".$details[1]." ".$details[2];
$femail = $details[3];
$weddingd = date('d/m/Y',$details[4]);
$userid = $details[5];



$result = $sql_command->select("quotation_proformas,quotation_details","quotation_proformas.id,
							   quotation_proformas.iw_cost,
							   quotation_proformas.status,
							   quotation_proformas.timestamp,
							   quotation_proformas.type,
							   quotation_proformas.order_id,
							   quotation_proformas.included_package","WHERE 
							   quotation_proformas.order_id=quotation_details.id AND (quotation_proformas.status!='Outstanding' OR quotation_proformas.status!='Pending') AND quotation_details.client_id='".addslashes($id)."'
							   ORDER BY quotation_proformas.id DESC");
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
}
else {
	$invoiceno = $record[0];
	include("../_includes/fn_proforma-payment.php");
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
$emailtype = "quotationlink";
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
$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Your Quotation is ready</h1>";
$message .="<br />";
$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $name,</h2>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Thank you for your enquiry for your perfect wedding with <a href=\"http://Ionianweddings.co.uk\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">Ionianweddings.co.uk</a>.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">You can view your quotations on our website by clicking the link and using the details below along with your password.</p>";
$message .="<br />";
$message .="<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">View your quotations here - <a href=\"http://www.ionianweddings.co.uk/quotations\" title=\"link\" target=\"_blank\" style=\"color: #c08827;\">http://www.ionianweddings.co.uk/quotations</a>.</p>";
$message .="<ul style=\"margin: 0px 0px 0px 20px; padding: 0px 0px 10px 0px;\">";
$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">User-ID : <strong>$userid</strong></li>";
$message .="<li style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 5px 0px;\">Wedding Date : <strong>$weddingd</strong></li>";
$message .="</ul>";
$message .="<br />";
$message .="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"margin: 0px 0px 15px 0px;\">";
$message .="<tr>";
$message .="<th align=\"left\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Quote ID</th>";
$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Quote Type</th>";
$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Quote Date</th>";
$message .="<th align=\"center\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Quote Status</th>";
$message .="<th align=\"right\" style=\"background-color: #faf1df; border-bottom: solid 1px #c08827; color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\">Quote Amount</th>";
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
	
	$mail->Subject = $name.", Your Ionian Wedding dream wedding quotations are ready to be viewed.";
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
	    <form class=\"pageform\" method=\"post\" action=\"$site_url/oos/manage-prospect.php\" name=\"emails\">
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
			<a href=\"$site_url/oos/manage-prospect.php?a=history&id=".$id."\" style=\"text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 7px 2px 7px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;\"/>Back</a>
			<input type=\"submit\" name=\"action\" value=\"Send Email\" />
			</center>
		</form>
	</div>
</div>";
	$result = $sql_command->select("$database_emails,$database_users","Distinct $database_emails.reference_id,
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
		<div style=\"float:left; width:170px; margin:5px;\">".stripslashes($record[2])." <a href=\"mailto:".stripslashes($record[1])."\">".stripslashes($record[1])."</a></div>
		<!--<div style=\"float:left; margin:5px;\"><a href=\"/_po".stripslashes($record[4])."\" target=\"_blank\">View PO</a></div>
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