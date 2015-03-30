<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";

switch ($_GET["FilterIWCUID"]) {
	case "":
		$FilterIWCUID			=	$_GET["FilterIWCUID"];
		$FilterIWCUIDSQL		=	"";
		$FilterIWCUIDQS			=	"";
		break;
	default:
		$FilterIWCUID			=	$_GET["FilterIWCUID"];
		$FilterIWCUIDSQL		=	"and $database_clients.iwcuid like '%".$FilterIWCUID."%' ";
		$FilterIWCUIDQS			=	"FilterIWCUID=".$FilterIWCUID."&";
		break;
}
switch ($_GET["FilterOrderNo"]) {
	case "":
		$FilterOrderNo			=	$_GET["FilterOrderNo"];
		$FilterOrderNoSQL		=	"";
		$FilterOrderNoQS		=	"";
		break;
	default:
		$FilterOrderNo			=	$_GET["FilterOrderNo"];
		$FilterOrderNoSQL		=	"and $database_supplier_invoices_main.order_id = '".$FilterOrderNo."' ";
		$FilterOrderNoQS		=	"FilterOrderNo=".$FilterOrderNo."&";
		break;
}
switch ($_GET["FilterSupplierID"]) {
	case "":
		$FilterSupplierID		=	$_GET["FilterSupplierID"];
		$FilterSupplierIDSQL	=	"";
		$FilterSupplierIDQS		=	"";
		break;
	default:
		$FilterSupplierID		=	$_GET["FilterSupplierID"];
		$FilterSupplierIDSQL	=	"and $database_supplier_details.id = '".$FilterSupplierID."' ";
		$FilterSupplierIDQS		=	"FilterSupplierID=".$FilterSupplierID."&";
		break;
}
switch ($_GET["FilterStatus"]) {
	case "":
		$FilterStatus			=	$_GET["FilterStatus"];
		$FilterStatusSQL		=	"";
		$FilterStatusQS			=	"";
		break;
	default:
		$FilterStatus			=	$_GET["FilterStatus"];
		$FilterStatusSQL		=	"and $database_supplier_invoices_main.status like '".$FilterStatus."' ";
		$FilterStatusQS			=	"FilterStatus=".$FilterStatus."&";
		break;
}
switch ($_GET["Dir"]) {
	case "Asc":
		$OrderByDirection		=	"ASC";
		break;
	default:
		$OrderByDirection		=	"DESC";
		break;
}

switch ($_GET["OrderBy"]) {
	case "Date":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_invoices_main.timestamp ".$OrderByDirection;
		break;
	case "IWCUID":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_clients.iwcuid ".$OrderByDirection.", $database_supplier_invoices_main.order_id ".$OrderByDirection;
		break;
	case "Supplier":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_details.company_name ".$OrderByDirection;
		break;
	case "Status":
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_invoices_main.status ".$OrderByDirection;
		break;
	default:
		$OrderBy				=	$_GET["OrderBy"];
		$OrderBySQL				=	"$database_supplier_invoices_main.timestamp ".$OrderByDirection;
		break;
}

$SupplierResult = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","DISTINCT
								$database_supplier_details.id,
								$database_supplier_details.company_name",
								"WHERE $database_clients.deleted='No' 
								and $database_supplier_details.deleted='No' 
								and $database_supplier_invoices_main.supplier_id=$database_supplier_details.id 
								and ($database_supplier_invoices_main.status != 'Outstanding' 
								and $database_supplier_invoices_main.status != 'Pending') 
								and $database_order_details.id=$database_supplier_invoices_main.order_id 
								and $database_order_details.client_id=$database_clients.id
								ORDER BY $database_supplier_details.company_name");
$SupplierRow = $sql_command->results($SupplierResult);

foreach($SupplierRow as $SupplierRecord) {
	switch ($FilterSupplierID) {
			case	$SupplierRecord[0];
				$SupplierSelected	=	" selected";	
			break;
			default:
				$SupplierSelected	=	"";
			break;
	}
	$SupplierList .= "<option value=\"".$SupplierRecord[0]."\"".$SupplierSelected.">". $SupplierRecord[1] ."</option>";
}

$StatusResult = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","DISTINCT
								$database_supplier_invoices_main.status",
								"WHERE $database_clients.deleted='No' 
								and $database_supplier_details.deleted='No' 
								and $database_supplier_invoices_main.supplier_id=$database_supplier_details.id 
								and ($database_supplier_invoices_main.status != 'Outstanding' 
								and $database_supplier_invoices_main.status != 'Pending') 
								and $database_order_details.id=$database_supplier_invoices_main.order_id 
								and $database_order_details.client_id=$database_clients.id
								ORDER BY $database_supplier_invoices_main.status");
$StatusRow = $sql_command->results($StatusResult);

foreach($StatusRow as $StatusRecord) {
	switch ($FilterStatus) {
			case	$StatusRecord[0];
				$StatusSelected		=	" selected";	
			break;
			default:
				$StatusSelected		=	"";
			break;
	}
	$StatusList .= "<option value=\"".$StatusRecord[0]."\"".$StatusSelected.">". $StatusRecord[0] ."</option>";
}

$result = $sql_command->select("$database_supplier_invoices_main,$database_supplier_details,$database_order_details,$database_clients","$database_supplier_invoices_main.id,
							   $database_supplier_invoices_main.order_id,
							   $database_supplier_invoices_main.supplier_id,
							   $database_supplier_invoices_main.invoice_id,
							   $database_supplier_invoices_main.status,
							   $database_supplier_invoices_main.timestamp,
							   $database_supplier_details.id,
							   $database_supplier_details.company_name,
							   $database_clients.id,
							   $database_clients.iwcuid","WHERE 
							   $database_clients.deleted='No' and 
							   $database_supplier_details.deleted='No' and 
							   $database_supplier_invoices_main.supplier_id=$database_supplier_details.id and 
							   ($database_supplier_invoices_main.status != 'Outstanding' and $database_supplier_invoices_main.status != 'Pending') and 
							   $database_order_details.id=$database_supplier_invoices_main.order_id and 
							   $database_order_details.client_id=$database_clients.id ".$FilterIWCUIDSQL.$FilterOrderNoSQL.$FilterSupplierIDSQL.$FilterStatusSQL."
							   ORDER BY ".$OrderBySQL);
$row = $sql_command->results($result);

foreach($row as $record) {

$dateline = date("d-m-Y",$record[5]);

$result2 = $sql_command->select($database_supplier_invoices_details,"qty,cost,currency","WHERE main_id='".$record[0]."'");
$row2 = $sql_command->results($result2);

foreach($row2 as $record2) {
if($record2[2] == "Pound") { 
$p_curreny = "&pound;"; 
} else {
$p_curreny = "&euro;"; 
}
$cost += $record[1];
}
if ($FilterOrderNo != "") {
	$list .= "<div style=\"float:left; width:20px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>";
} elseif ($FilterSupplierID != 0) {
	$list .= "<div style=\"float:left; width:20px; margin:5px;\"><input type=\"checkbox\" name=\"download_".$record[0]."\" value=\"Yes\"></div>";
}
$list .= "<div style=\"float:left; width:80px; margin:5px;\">".$dateline."</div>
<div style=\"float:left; width:150px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[8]."\">".$record[9]."/".$record[1]."</a></div>
<div style=\"float:left; width:90px; margin:5px;\">$p_curreny ".number_format($cost,2)."</div>
<div style=\"float:left; width:40px; margin:5px;\"><a href=\"$site_url/oos/purchase-order.php?purchase_order=".$record[0]."\">View</a></div>
<div style=\"float:left; width:170px; margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[6]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; margin:5px;\">".stripslashes($record[4])."</div>
<div style=\"clear:left;\"></div>
";
}

$get_template->topHTML();
?>
<h1>Archived Supplier Purchase Orders</h1>
<?
$OrderURL	=	$site_url."/oos/archived-supplier-purchase-orders.php?".$FilterIWCUIDQS.$FilterOrderNoQS.$FilterSupplierIDQS.$FilterStatusQS."OrderBy=";
?>
<script>
jQuery(function($) { // onDomReady

    // reset handler that clears the form
    $('form[name="posearch"] input:reset').click(function () {
        $('form[name="posearch"]')
            .find(':radio, :checkbox').removeAttr('checked').end()
            .find('textarea, :text, select').val('')
        return false;
    });

});
</script>
<form class="pageform" id="posearch" method="get" name="posearch" >
	<div class="formrow">
		<label class="formlabel">
			Enter IWCUID
		</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="FilterIWCUID" name="FilterIWCUID" type="text" value="<?php echo $FilterIWCUID;?>"/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel">
			Enter Order Number
		</label>
		<div class="formelement">
			<input class="formtextfieldlong" id="FilterOrderNo" name="FilterOrderNo" type="text" value="<?php echo $FilterOrderNo;?>"/>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel">
			Supplier
		</label>
		<div class="formelement">
			<select class="formselectlong" id="FilterSupplierID" name="FilterSupplierID">
				<option value="">Select	supplier...</option>
				<?php echo $SupplierList; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label class="formlabel">
			Order Status
		</label>
		<div class="formelement">
			<select class="formselectlong" id="FilterStatus" name="FilterStatus">
				<option value="">Select	order status...</option>
				<?php echo $StatusList; ?>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="formrow">
		<label for="FilterSubmit" class="formlabel">&nbsp;</label>
		<div class="formelement">
			<input id="OrderBy" name="OrderBy" type="hidden" value="<?php echo $OrderBy; ?>" />
			<input id="Dir" name="Dir" type="hidden" value="<?php echo $OrderByDirection; ?>" />
			<input id="FilterSubmit" name="FilterSubmit" class="formsubmit" type="submit" value="Submit" />
			<input id="FilterReset" name="FilterReset" class="formreset" type="reset"  value="Reset" />
		</div>
		<div class="clear"></div>
	</div>
</form>
<?
	if ($FilterOrderNo != "") {
?>
	<script language='JavaScript'>
		checked = false;
		function checkedAll () {
			if (checked == false) {
				checked = true;
			} else {
				checked = false;
			}
			for (var i = 0; i < document.getElementById('downloadPDF').elements.length; i++) {
				var chk = document.getElementById('downloadPDF').elements[i].name;
				if (chk.match(/download_.*/)) {
					document.getElementById('downloadPDF').elements[i].checked = checked;
				}
			}
		}
	</script>
	<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order-by-order.php" name="download" id="downloadPDF">
	<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
	<input type="hidden" name="action2" value="download" />
	<div style="float:left; width:20px; margin:5px 5px 5px 5px;"></div>
<?
	} elseif ($FilterSupplierID != 0) {
?>
	<script language='JavaScript'>
		checked = false;
		function checkedAll () {
			if (checked == false) {
				checked = true;
			} else {
				checked = false;
			}
			for (var i = 0; i < document.getElementById('downloadPDF').elements.length; i++) {
				var chk = document.getElementById('downloadPDF').elements[i].name;
				if (chk.match(/download_.*/)) {
					document.getElementById('downloadPDF').elements[i].checked = checked;
				}
			}
		}
	</script>
	<form method="post" action="<?php echo $site_url; ?>/oos/download-purchase-order-by-order.php" name="download" id="downloadPDF">
	<input type="hidden" name="supplier_id" value="<?php echo $record[6]; ?>" />
	<input type="hidden" name="action2" value="download" />
	<div style="float:left; width:20px; margin:5px 5px 5px 5px;"></div>
<?
	}
?>
<div style="float:left; width:80px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>Date&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Date <a class="listheaderbutton" href="<?php echo $OrderURL;?>Date&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left; width:150px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>IWCUID&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> IWCUID/Order # <a class="listheaderbutton" href="<?php echo $OrderURL;?>IWCUID&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>Amount</strong></div>
<div style="float:left; width:40px; margin:5px;"><strong>PDF</strong></div>
<div style="float:left; width:170px; margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>Supplier&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Supplier <a class="listheaderbutton" href="<?php echo $OrderURL;?>Supplier&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="float:left;  margin:5px;"><strong><a class="listheaderbutton" href="<?php echo $OrderURL;?>Status&Dir=Asc"><img src="/images/interface/b-ascend.png" alt="Ascending" border="0" title="Ascending" /></a> Status <a class="listheaderbutton" href="<?php echo $OrderURL;?>Status&Dir=Desc"><img src="/images/interface/b-descend.png" alt="Descending" border="0" title="Descending" /></a></strong></div>
<div style="clear:left;"></div>

<?php echo $list; ?>
<?
	if ($FilterOrderNo != "") {
?>
	<div style="float:left; width:20px; margin:5px 5px 5px 5px;"><input type='checkbox' name='checkall' onclick='checkedAll();'></div>
	<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
	<div style="clear:left;"></div>
	<p><input type="submit" name="action" value="Download Selected Purchase Orders"></p>
	</form>
<?
	} elseif ($FilterSupplierID != 0) {
?>
	<div style="float:left; width:20px; margin:5px 5px 5px 5px;"><input type='checkbox' name='checkall' onclick='checkedAll();'></div>
	<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
	<div style="clear:left;"></div>
	<p><input type="submit" name="action" value="Download Selected Purchase Orders"></p>
	</form>
<?
	}
?>

<?
$get_template->bottomHTML();
$sql_command->close();



?>