<?
header('Content-Type: application/json');
require ("settings.php");
include ("function.database.php");
include ("function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);


$cur = $sql_command->select("currency_conversion",
							"currency_id,
							currency_acro",
							"WHERE currency_default='Yes' 
							ORDER BY currency_acro ASC");
$curr = $sql_command->result($cur);

$from = $curr[1];

$cur = $sql_command->select("currency_conversion",
							"currency_id,
							currency_acro,
							currency_adjustment,
							currency_name,
							currency_rate,
							currency_status,
							timestamp,
							currency_erate",
							"ORDER BY currency_acro ASC");
$curr = $sql_command->results($cur);


if ($_GET['action'] || $_POST['action']) {

}

else {
	
	foreach ($curr as $c){
	
		$to = $c[1];
		if($from!=$to) {
			$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1&s='.$from.$to.'=X';
			$handle = fopen($url, 'r');
			
			if ($handle) {
				$result = fgetcsv($handle);	
				fclose($handle);
			}
			
			if ($result) {
				$rate = number_format($result[0]/$c[2],2);
				
				$sql_q = $sql_command->update("currency_conversion",
											  "currency_rate='".addslashes($result[0])."',
											  currency_erate='".addslashes($rate)."'",
											  "currency_id='".addslashes($c[0])."'");
			}
		}
		else {
			$sql_q = $sql_command->update("currency_conversion",
										  "currency_rate=1,
										  currency_erate=1",
										  "currency_id='".addslashes($c[0])."'");	
		}
	}
	

}

?>