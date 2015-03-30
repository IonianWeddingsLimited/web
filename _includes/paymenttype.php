<?
header('Content-Type: application/json');
require ("settings.php");
include ("function.database.php");
include ("function.genpass.php");
require ("function.smtp.php");
require("../_wopay/worldpayconfig.php");

$active = false;
$amm = isset($_POST['ptamount']) ? $_POST['ptamount'] : 0;
$def = isset($_POST['defam']) ? $_POST['defam'] : 0;
$cardCh = isset($_POST['cardch']) ? $_POST['cardch'] : 0;
$cardT = isset($_POST['ptcard']) ? $_POST['ptcard'] : "";
$lastam = isset($_POST['lastAmount']) ? $_POST['lastAmount'] : 0;
$amountc = ($amm <= 0) ?  : ($amm>$def) ? $def : $amm;

$filterv = array("£","$","€"," ",",","-");	
$chamount = str_replace($filterv,"",$chamount);
$amountc = str_replace($filterv,"",$amountc);
$cardCh = str_replace($filterv,"",$cardCh);

$chamount = round(($amountc*($cardCh/100)),2);
$amountc = round($amountc,2);


$amount = $amountc + $chamount;
$amount = ($def>=$payLimit) ? $payLimit : $amount;
$amount = str_replace($filterv,"",number_format($amount,2));

$chamount = ($def>=$payLimit) ? round(($payLimit-($payLimit/(1+($cardCh/100)))),2) : $chamount;
$amountc = ($def>=$payLimit) ? round(($payLimit/(1+($cardCh/100))),2) : $amountc;

$lastam = ($def>=$payLimit) ? $lastam - $chamount : $lastam;

$fsamount = $amountc;
$fstotal = ($def>=$payLimit) ? $amountc - $chamount : $amountc; 
$ftotal = $amount;
$fcharge = $chamount;



//$fstotal = number_format($amountc,2,',',' '); 
//$ftotal = number_format($amount,2,',',' ');
//$fcharge = number_format($chamount,2,',',' ');

$inid = $installationID;
$instid = $installationID;
$instid1 = $installationID1;
$instid2 = $installationID2;

switch($cardCh) {
	// Visa Debit
	case 0:
		$inid = $instid;
		break;
	//Maestro
	case 2:
		$inid = $instid1;
		break;
	//Mastercard
	case 3:
		$inid = $instid2;
		break;
	}

$sigf = $cardT.":".$currencyCode.":".$amount.":".$testMode.":".$inid;
$deb = $amount."a-c".$cardT;
$sig = md5($MD5secretKey.":".$sigf);

$js_value = array("signat" => $sig,
				  "inst_id" => $inid,
				  "tamount" => $amount,
				  "samount" => str_replace($filterv,"",number_format($fsamount,2)),
				  "fsamount" => number_format($fsamount,2),
  				  "eamount" => number_format($lastam,2),
				  "fccharge" => number_format($fcharge,2),
				  "ftotal" => number_format($ftotal,2),
				  "debug" => $deb);
echo json_encode($js_value);
?>