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
$get_template->errorHTML("Manage Supplier","Oops!","$error","Link","oos/manage-supplier-po.php");
$get_template->bottomHTML();
$sql_command->close();
}

$id=(!$_GET['id']) ? $_POST['id'] : $_GET['id'];

	
$ioiddet = $sql_command->select("$database_supplier_details","contact_title,contact_firstname,contact_surname,contact_email","WHERE id = '".addslashes($id)."'");
$details = $sql_command->result($ioiddet);

$name = $details[0]." ".$details[1]." ".$details[2];
$femail = $details[3];
$weddingd = date('d/m/Y',$details[4]);
$userid = $details[5];
$pass = $details[6];



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
$message .="<h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Purchase Orders</h1>";
$message .="<br />";
$message .="<h2 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px 0px 0px 0px; padding: 0px 0px 7px 0px;\">Dear $name,</h2>";
//get the text area to display here the below code doesn't display
$Message .="<p><textarea name=\"\" cols=\"\" rows=\"20\"></textarea></p>";
$message .="<br />";
$message .= "<p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">If you have any issues or queries relating to this email and its contents, please feel free to contact us using our contact form <a href=\"http://Ionianweddings.co.uk/contact-us/\" target=\"_blank\" title=\"link\" style=\"color: #c08827;\">CONTACT US</a>.</p>";
$message .="<br />";
$message .= "</td></tr><tr><td colspan=\"4\" style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\" target=\"_blank\" title=\"Ionian Weddings Limited\" style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />The only site you need to make your wedding dreams come true.</p><p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td></tr></table><p></p>";

//	header("Location: $site_url/oos/manage-client.php?a=history&id=".$id);


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
	$mail->Server =  $smpt_server;
	$mail->Port =  $smpt_port;	
	$mail->SetFrom($smtp_email,$tagline);	// Name is optional
	$mail->AddTo($femail,$name);	// Name is optional
	
	$mail->Subject = $name.", Your Ionian Wedding dream wedding invoices are ready to be viewed.";
	$mail->Message = $message;
	$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
	$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

	$success = $mail->Send();

}

echo "<div style=\"background:#FFFFFF;\">";
if ($success) { echo "<center><h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\">Email sent successfully.</h1></center>"; }

echo $message;
print_r($etarget);
echo "</div>";

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


$list2 .= "<div style=\"float:left; width:40px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>
<div style=\"float:left; width:50px; margin:5px;\">".$record[1]."</div>
<div style=\"float:left; width:150px; margin:5px;\">$p_curreny ".$cost."</div>
<div style=\"float:left;  margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".stripslashes($record[9])." ".stripslashes($record[10])." ".stripslashes($record[11])."</a></div>
<div style=\"clear:left;\"></div>";
}

if($list2) { ?>
<br />
<h3>Attach Multiple Purchase Orders</h3>
<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order.php" name="download">
<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
<input type="hidden" name="action2" value="download" />
<div style="float:left; width:40px; margin:5px;">&nbsp;</div>
<div style="float:left; width:50px; margin:5px;"><strong>Order #</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left;  margin:5px;"><strong>Client</strong></div>
<div style="clear:left;"></div>
<?php echo $list2;
}
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
			<a href=\"$site_url/oos/manage-supplier-po.php?id=".$id."\" style=\"text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 7px 2px 7px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;\"/>Back</a>
			<input type=\"button\" name=\"action\" value=\"Save Email\" />
			<input type=\"submit\" name=\"action\" value=\"Send Email\" />
			</center>
		</form>
	</div>
</div>";

$get_template->bottomHTML();
$sql_command->close();




?>