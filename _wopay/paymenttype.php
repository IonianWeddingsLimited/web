<?
header('Content-Type: application/json');
require ("settings.php");
include ("function.database.php");
include ("function.genpass.php");
require ("function.smtp.php");
require("worldpayconfig.php");

$active = false;
$amount = isset($_POST['ptamount']) ? isset($_POST['ptamount']) : 0;
$cardT = isset($_POST['ptamount']) ? isset($_POST['ptamount']) : "";

$inid = $installationID;

switch($cardT) {
	// Visa Debit
	case "VISD":
		$inid = 307221;
		break;
	//Maestro
	case "MAES":
		$inid = 307221;
		break;
	// Debit Mastercard
	case "DMC":
		$inid = 307221;
		break;		
	//Visa Electron	
	case "VIED":
		$inid = 307221;
		break;
	// Visa Debit
	case "VISA":
		$inid = 314618;
		break;
	//Mastercard
	case "MSCD":
		$inid = 314618;
		break;
	// JCB
	case "JCB":
		$inid = 314618;
		break;
	// Diners Club
	case "DINS":
		$inid = 314618;
		break;
	// American Express
	case "AMEX":
		$inid = 314619;
		break;
	// Elektronisches Lastschriftverfahren
	case "ELV":
		$inid = 314619;
		break;
	// Laser
	case "LASR":
		$inid = 314619;
		break;
	
}


$inid = $installationID;

$sig = $currencyCode.":".$amount.":".$testMode.":".$inid;

$js_value = array("signat" => $sig, "inst_id" => $inid);
echo json_encode($js_value);
?>