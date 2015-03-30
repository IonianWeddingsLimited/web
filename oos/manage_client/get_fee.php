<?

if (!$_GET["id"]) {
    $get_template->topHTML();
    $get_template->errorHTML("Manage Client", "Oops!", "Missing Client ID", "Link", "oos/manage-client.php");
    $get_template->bottomHTML();
    $sql_command->close();
}

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
	if ($supplier_record[0] == 21) {
		$strSupplierSelect	=	" selected";
	} else {
		$strSupplierSelect	=	"";
	}
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $$strSupplierSelect>".stripslashes($supplier_record[1])."</option>";
}


$orderdetail_info_result = $sql_command->select($database_order_details, "exchange_rate", "WHERE id='" . addslashes($_GET["invoice_id"]) . "'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);

$package_info_result = $sql_command->select($database_order_history, "id,name,cost,iw_cost,currency,d_value,d_type", "WHERE order_id='" . addslashes($_GET["invoice_id"]) . "' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

if ($package_info_record[6] == "Amount") {
    $new_package_cost = number_format($package_info_record[3] - $package_info_record[5], 2);
} else {
    $percent_value    = ($package_info_record[3] / 100);
    $new_package_cost = number_format($package_info_record[3] - ($percent_value * $package_info_record[5]), 2);
}

if ($package_info_record[4] == "Pound") {
    $p_curreny = "&pound;";
} else {
    $p_curreny = "&euro;";
}

$deposit_info_result = $sql_command->select($database_order_history, "status", "WHERE order_id='" . addslashes($_GET["invoice_id"]) . "' and item_type='Deposit'");
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

<?
echo $menu_line;
?>

<h2>Add Consultation / Recce Fee</h2>

<div style="float:left; width:120px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?
echo stripslashes($package_info_record[1]);
?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;"><?
echo $p_curreny;
?> <?
echo stripslashes($package_info_record[2]);
?></div>
<div style="clear:left;"></div>
<div style="float:left; width:120px; margin:5px;"><strong>Package Iw Cost</strong></div>
<div style="float:left; margin:5px;"><?
echo $p_curreny;
?> <?
echo str_replace(",", "", $new_package_cost);
?></div>
<div style="clear:left;"></div>

<h3 style="margin-top:10px;">Amount</h3>
<?
//if ($deposit_info_record[0] == "Cancelled") {
?>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<div style="float:left; width:160px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; margin:5px;">
	<select name="feetype_id">
		<option value="3453">Consultation Fee</option>
		<option value="3452" selected="selected">Recce Fee</option>
	</select>
</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Gross Cost</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="gross_cost" value="0"></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Gross Currency</strong></div>
<div style="float:left; margin:5px;">
	<select name="gross_currency">
		<option value="Euro">Euro</option>
		<option value="Pound" selected="selected">Pound</option>
	</select>
</div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left; margin:5px;">
	<select name="supplier" style="width:300px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Net Cost</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="net_cost" value="0"></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Net Currency</strong></div>
<div style="float:left; margin:5px;">
	<select name="net_currency">
		<option value="Euro" selected>Euro</option>
		<option value="Pound"="selected">Pound</option>
	</select>
</div>
<div style="clear:left;"></div>
<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>
<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Add Fee" onclick="return checkexchange();"></div>
</form>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="submit" name="" value="Back"></div>
<div style="clear:both;"></div>
</form>
<?
//} else {
?>
<!--<p>Please cancel the existing deposit before you set a new one</p>
<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="get">
<input type="hidden" name="a" value="history" />
<input type="hidden" name="id" value="<?php echo $_POST["client_id"]; ?>" />
<p><input type="submit" name="" value="Back"></p>
</form>-->
<?
//}
?>
<?
$get_template->bottomHTML();
$sql_command->close();

?>