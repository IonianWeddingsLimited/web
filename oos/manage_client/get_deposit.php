<?

	if(!$_GET["id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","Missing Client ID","Link","oos/manage-client.php");
$get_template->bottomHTML();
$sql_command->close();
}

if(!$_GET["invoice_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Client","Oops!","Missing Order ID","Link","oos/manage-client.php?a=history&id=222");
$get_template->bottomHTML();
$sql_command->close();
}


$orderdetail_info_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);

$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

if($package_info_record[6] == "Amount") { 
$new_package_cost = number_format($package_info_record[3] - $package_info_record[5],2);
} else { 
$percent_value = ($package_info_record[3] / 100);
$new_package_cost = number_format($package_info_record[3] - ($percent_value * $package_info_record[5]) ,2);
}

if($package_info_record[4] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}
	
$deposit_info_result = $sql_command->select($database_order_history,"status","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Deposit'");
$deposit_info_record = $sql_command->result($deposit_info_result);

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

$get_template->topHTML();
?>
<h1>Manage Client</h1>

<?php echo $menu_line; ?>

<h2>Add Deposit</h2>

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Iw Cost</strong></div>
<div style="float:left; margin:5px;"><?php echo $p_curreny; ?> <?php echo str_replace(",","",$new_package_cost); ?></div>
<div style="clear:left;"></div>

<h3 style="margin-top:10px;">Deposit Amount</h3>


<?php if($deposit_info_record[0] == "Cancelled") { ?>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<div style="float:left; width:160px; margin:5px;"><strong>Deposit</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="deposit" value="0"></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Currency</strong></div>
<div style="float:left; margin:5px;"><select name="currency">
<option value="Euro">Euro</option>
<option value="Pound" selected="selected">Pound</option>
</select>
</div>
<div style="clear:left;"></div>

<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>

<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Add Deposit" onclick="return checkexchange();"></div>
</form>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<?php } else { ?>
<p>Please cancel the existing deposit before you set a new one</p>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<p><input type="submit" name="" value="Back"></p>
</form>
<?php } ?>



<?
$get_template->bottomHTML();
$sql_command->close();

?>