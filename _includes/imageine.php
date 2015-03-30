<?
require ("function.smtp.php");
$active = false;
$imgemail = false;
$link = $ftitle = $ffname = $flname = $femail = $ftel = $responsetxt = "";
$ioid = isset($_SESSION['ioid']) ? $_SESSION['ioid'] : "";

if (isset($_SESSION['ioid'])) { 
$ioiddet = $sql_command->select("$database_clients","title,firstname,lastname,email,tel,wedding_date","WHERE id = '".addslashes($ioid)."'");
$details = $sql_command->result($ioiddet);

$ftitle = $details[0];
$ffname = $details[1];
$flname = $details[2];
$femail = $details[3];
$ftel = $details[4];
$wdate = $details[5];

}


if (isset($_POST['title'])) {

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$Err = false;
$ftitle = test_input($_POST["title"]);
$ffname = test_input($_POST["firstname"]);
$flname = test_input($_POST["lastname"]);
$femail = test_input($_POST["email"]);
$ftel = test_input($_POST["telephone"]);

if (empty($ffname)) { $Err = true; }
if (empty($flname)) { $Err = true; }
$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
if (empty($femail) OR !preg_match($regex,$femail)) { $Err = true; }
if (empty($ftel) OR !is_numeric($ftel)) { $Err = true; }

if (!$Err) {	
	$wdate = empty($_POST['weddingd']) ? "" : htmlspecialchars($_POST['weddingd']);
	$imgemail = true;
	list($day,$month,$year) = explode("/",$wdate);
	$dateval = mktime(0, 0, 0, $month, $day, $year);
	$mason = "";

	$mason = $sql_command->count_nrow("$database_clients","id","email = '".$femail."'");
	if ($mason>0) {
		$result = $sql_command->select("$database_clients","id","WHERE email = '".$femail."'");
		$ioclid = $sql_command->result($result);
		$ioid = $ioclid[0];
		$responsetxt = "Welcome back, ".$ftitle." ".$ffname." ".$flname.".";
	}

	else {
		$cols = "title,firstname,lastname,email,tel,mailing_list,wedding_date,country,imageine";
		$values = "'".$ftitle."','".$ffname."','".$flname."','".$femail."','".$ftel."','Yes','".$dateval."','United Kingdom','Yes'";	
		$sql_command->insert($database_clients,$cols,$values);
		$ioid = $sql_command->maxid("$database_clients","id");
		$responsetxt = "Thank you for Registering an interest.";
	}
}

date_default_timezone_set('Europe/London');
$today = getdate();
$link = $today[0];
$cols = "img_id,meta_id,meta_value,meta_desc,timestamp";
$imgids = serialize(addslashes($_POST["imgids"])); 
$values = "'".addslashes($ioid)."',10,'".addslashes($link)."','".addslashes($imgids)."','".addslashes($link)."'";	
$sql_command->insert($database_gallery_mason,$cols,$values);
$imgemail = true;
$active=true;
}

if($imgemail) {

$EmailLink = "/".$link."/#";

$imgname = $ffname." ".$flname;
$message = "<table border=\"0\"cellpadding=\"0\"cellspacing=\"0\"width=\"625\">";
$message .= "<tr>";
$message .= "<td colspan=\"2\" width=\"50%\" style=\"padding: 20px 0px 20px 0px;\"><img src=\"http://www.ionianweddings.co.uk/images/interface/i_logo_ionian_weddings.gif\"alt=\"Ionian Weddings - Exclusively Mediterranean Weddings\"border=\"0\"title=\"Ionian Weddings - Exclusively Mediterranean Weddings\"style=\"display: block; margin: 0px 0px 5px 0px;\"/> <img src=\"http://www.ionianweddings.co.uk/images/interface/i_exclusively_mediterranean_weddings.gif\"border=\"0\"title=\"Exclusively Mediterranean Weddings\"alt=\"Exclusively Mediterranean Weddings\"style=\"display: block;\"/></td>";
$message .= "<td colspan=\"2\" width=\"50%\" style=\"padding: 0px 0px 0px 0px; text-align: right;\"><p style=\"color: #8b6934; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\"> Call us today on 020 8894 1991 / 020 8898 9917 </p>";
$message .= "<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\"> 10 Crane Mews, 32 Gould Road, Twickenham, TW2 6RS, England </p></td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td colspan=\"4\"style=\"border-top: solid 1px #c08827; padding: 20px 0px 5px 0px;\"><h1 style=\"color: #c08827; font-family:Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\"> IMAGE-INE </h1>";
$message .= "<br><p style=\"color: #595959; font-family:Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 0px 0px 15px 0px;\"> Someone showed an interest on IMAGE-INE.<br>";
$message .= "<br>To view their IMAGE-INE please login to the OOS control panel or click this link.</p>";
$message .= "<table width=\"70%\"cellspacing=\"0\"cellpadding=\"0\"border=\"0\"style=\"margin: 0px 0px 15px 0px;\">";
$message .= "<tbody>";
$message .= "<tr>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\"> Name  :</td>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\">".$imgname."</td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\"> Email  :</td>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\"><a href=".$femail." style=\"color: #c08827;\">".$femail."</a></td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\"> Telephone  :</td>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\">".$ftel."</td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\"> Wedding Date  :</td>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;\"> ".$wdate." </td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td align=\"left\"style=\"border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 5px;\"> Image-ine link  :</td>";
$message .= '<td align="left" style="border-bottom: dotted 1px #c08827; color: #595959; font-family: Arial, Helvetica, sans-serif; font-size: 11px; margin: 0px 0px 0px 0px; padding: 5px 5px 5px 0px;"><a href="http://www.ionianweddings.co.uk/image-ine'.$EmailLink.'" target="_blank" title="IMAGE-INE" style="color: #c08827;">http://www.ionianweddings.co.uk/image-ine'.$EmailLink.'</a></td>';
$message .= "</tr>";
$message .= "</tbody>";
$message .= "</table></td>";
$message .= "</tr>";
$message .= "<tr>";
$message .= "<td colspan=\"4\" width=\"400\"style=\"background-color: #faf1df; padding: 20px 10px 20px 0px; text-align: right;\"><p style=\"color: #c08827; font-family: Arial, Helvetica, sans-serif; font-size: 10px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 10px 0px;\">This email is brought to you by <a href=\"http://www.ionianweddings.co.uk/\"target=\"_blank\"title=\"Ionian Weddings Limited\"style=\"color: #c08827;\">Ionian Weddings Limited</a>.<br />";
$message .= "The only site you need to make your wedding dreams come true.</p>";
$message .= "<p style=\"color: #878787; font-family: Arial, Helvetica, sans-serif; font-size: 9px; line-height: 1.2em; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">Copyright &copy; 2014. All rights reserved</p></td>";
$message .= "</tr>";
$message .= "</table>";

$emailadd = " weddings@ionianweddings.co.uk"; 
$emailname = "<Ionian Weddings Ltd>";



$global_from = "<{$emailadd}> \"{$emailname}\"";
$mail = new smtp_email;
$mail->Username = $smtp_email;
$mail->Password = $smtp_password;
$mail->SetFrom($smtp_email,"Ionian Weddings - IMAGE-INE");	// Name is optional
$mail->AddTo($emailadd,"Ionian Weddings Ltd");	// Name is optional
//$mail->AddTo("apexofthecurve@hotmail.com","Ionian Weddings Ltd");	// Name is optional

$mail->Subject = $imgname." showed an interest on IMAGE-INE.";
$mail->Message = $message;
$mail->ConnectTimeout = 30;		// Socket connect timeout (sec)
$mail->ResponseTimeout = 8;		// CMD response timeout (sec)

$success = $mail->Send();
}
?>