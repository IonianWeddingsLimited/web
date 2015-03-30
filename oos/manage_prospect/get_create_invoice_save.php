<?


if(!$_POST["exchange_rate"]) { $error .= "Missing exchange rate<br>"; }
if($_POST["exchange_rate"] <= 0) { $error .= "Please enter an exchange rate greater than 0<br>"; }



$order_result = $sql_command->select("quotation_history","*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type!='Deposit'");
$order_row = $sql_command->results($order_result);


$total_due = 0;

$create_invoice = "No";
foreach($order_row as $order_record) {
$theitem = "id_".$order_record[0];
//$itemhide = "hide_".$order_record[0];
if($_POST[$theitem] == "Yes") { 
$create_invoice = "Yes";
}
}

if($create_invoice == "No") { $error .= "Please select an item to invoice<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","$error","Link","oos/manage-prospect.php?a=create-invoice&id=".$_POST["client_id"]."&invoice_id=".$_POST["invoice_id"]);
$get_template->bottomHTML();
$sql_command->close();
}


$sql_command->update("quotation_details","exchange_rate='".addslashes($_POST["exchange_rate"])."'","id='".addslashes($_POST["invoice_id"])."'");

if($create_invoice == "Yes") {
	
$cols = "order_id,cost,iw_cost,vat,status,timestamp,type,updated_timestamp,included_package,exchange_rate";
$invoice_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,d_,code,invoice_id";

$sql_command->insert(quotation_proformas,$cols,"'".addslashes($_POST["invoice_id"])."','','','20.00','Quotation','$time','Order','','No','".addslashes($_POST["exchange_rate"])."'");
$maxid = $sql_command->maxid("quotation_proformas","id","");
}

$cols = "invoice_id,history_id";


foreach($order_row as $order_record) {

$theitem = "id_".$order_record[0];	

if($_POST[$theitem] == "Yes") { 


//$hideitem = ($_POST[$itemhide] == "Yes") ?  $sql_command->insert("clients_options","client_id,client_option,option_value","'".addslashes($maxid)."','hideItem','".$order_record[0]."'") :  false;


$adjustment_iw = $order_record[6];
$adjustment = $order_record[5];


// Work out adjustments
if($order_record[4] > 0) {
	
if($order_record[18] == "Gross") { 

if($order_record[17] == "Amount" and $order_record[16] != 0) {
$adjustment_iw = $order_record[6] + $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] != 0) { 
$percent_value = ($order_record[6] / 100);
$adjustment_iw = $order_record[6] + ($percent_value * $order_record[16]);
} 

} elseif($order_record[18] == "Net") { 

if($order_record[17] == "Amount" and $order_record[16] != 0) {
$adjustment = $order_record[5] + $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] != 0) { 
$percent_value = ($order_record[5] / 100);
$adjustment = $order_record[5] + ($percent_value * $order_record[16]);
}

} elseif($order_record[18] == "Absolute Gross") { 
$adjustment_iw = $order_record[16];
} elseif($order_record[18] == "Absolute Net") { 
$adjustment = $order_record[16];
}
	
} elseif($order_record[4] < 0) {
	
if($order_record[18] == "Gross") { 

if($order_record[17] == "Amount" and $order_record[16] != 0) {
$adjustment_iw = $order_record[6] - $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] != 0) { 
$percent_value = ($order_record[6] / 100);
$adjustment_iw = $order_record[6] - ($percent_value * $order_record[16]);
} 

} elseif($order_record[18] == "Net") { 

if($order_record[17] == "Amount" and $order_record[16] != 0) {
$adjustment = $order_record[5] - $order_record[16];
} elseif($order_record[17] == "Percent" and $order_record[16] != 0) { 
$percent_value = ($order_record[5] / 100);
$adjustment = $order_record[5] - ($percent_value * $order_record[16]);
} 

} elseif($order_record[18] == "Absolute Gross") { 
$adjustment_iw = $order_record[16];
} elseif($order_record[18] == "Absolute Net") { 
$adjustment = $order_record[16];
}

}

if($order_record[16] == 0 and $order_record[18] == "Net") {
$adjustment_iw = $order_record[5];
}

$the_cost = $order_record[4] * $adjustment;
$total_iw_cost = $order_record[4] * $adjustment_iw;


if($order_record[10] == "Pound") {
$total_iw_pound_cost += $total_iw_cost;
} else { 
$total_iw_euro_cost += $total_iw_cost;
}

$total_cost_package += $the_cost;



$values = "'".addslashes($order_record[1])."',
'".addslashes($order_record[2])."',
'".addslashes($order_record[3])."',
'".addslashes($order_record[4])."',
'".addslashes($order_record[5])."',
'".addslashes($order_record[6])."',
'".addslashes($order_record[7])."',
'".addslashes($order_record[8])."',
'".addslashes($order_record[9])."',
'".addslashes($order_record[10])."',
'".addslashes($order_record[11])."',
'".addslashes($order_record[12])."',
'Invoice Issued',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".addslashes($order_record[16])."',
'".addslashes($order_record[17])."',
'".addslashes($order_record[18])."',
'".addslashes($order_record[19])."',
'".$maxid."'";

$sql_command->insert("quotation_proforma_history",$invoice_history_cols,$values);
$sql_command->update("quotation_history","status='Invoice Issued'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
$sql_command->update("quotation_history","exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");
$sql_command->update("quotation_history","invoice_id='".$maxid."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record[0]."'");

if($order_record[11] == "Package") {
$sql_command->update("quotation_proformas","included_package='Yes'","id='".$maxid."'");

$order_result2 = $sql_command->select("quotation_history","*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and type='Included'");
$order_row2 = $sql_command->results($order_result2);



foreach($order_row2 as $order_record2) {

$adjustment_iw = $order_record2[6];
$adjustment = $order_record2[5];

// Work out adjustments
if($order_record2[4] > 0) {
	
if($order_record2[18] == "Gross") { 

if($order_record2[17] == "Amount" and $order_record2[16] != 0) {
$adjustment_iw = $order_record2[6] + $order_record2[16];
} elseif($order_record2[17] == "Percent" and $order_record2[16] != 0) { 
$percent_value = ($order_record2[6] / 100);
$adjustment_iw = $order_record2[6] + ($percent_value * $order_record2[16]);
} 

} elseif($order_record2[18] == "Net") { 

if($order_record2[17] == "Amount" and $order_record2[16] != 0) {
$adjustment = $order_record2[5] + $order_record2[16];
} elseif($order_record2[17] == "Percent" and $order_record2[16] != 0) { 
$percent_value = ($order_record2[5] / 100);
$adjustment = $order_record2[5] + ($percent_value * $order_record2[16]);
}

} elseif($order_record2[18] == "Absolute Gross") { 
$adjustment_iw = $order_record2[16];
} elseif($order_record2[18] == "Absolute Net") { 
$adjustment = $order_record2[16];
}
	
} elseif($order_record2[4] < 0) {
	
if($order_record2[18] == "Gross") { 

if($order_record2[17] == "Amount" and $order_record2[16] != 0) {
$adjustment_iw = $order_record2[6] - $order_record2[16];
} elseif($order_record2[17] == "Percent" and $order_record2[16] != 0) { 
$percent_value = ($order_record2[6] / 100);
$adjustment_iw = $order_record2[6] - ($percent_value * $order_record2[16]);
} 

} elseif($order_record2[18] == "Net") { 

if($order_record2[17] == "Amount" and $order_record2[16] != 0) {
$adjustment = $order_record2[5] - $order_record2[16];
} elseif($order_record2[17] == "Percent" and $order_record2[16] != 0) { 
$percent_value = ($order_record2[5] / 100);
$adjustment = $order_record2[5] - ($percent_value * $order_record2[16]);
} 

} elseif($order_record2[18] == "Absolute Gross") { 
$adjustment_iw = $order_record2[16];
} elseif($order_record2[18] == "Absolute Net") { 
$adjustment = $order_record2[16];
}

}

if($order_record2[16] == 0 and $order_record2[18] == "Net") {
$adjustment_iw = $order_record2[5];
}

$the_cost = $order_record2[4] * $adjustment;
$total_iw_cost = $order_record2[4] * $adjustment_iw;


if($order_record2[10] == "Pound") {
$total_iw_pound_cost += $total_iw_cost;
} else { 
$total_iw_euro_cost += $total_iw_cost;
}

$total_cost_package += $the_cost;



$values = "'".addslashes($order_record2[1])."',
'".addslashes($order_record2[2])."',
'".addslashes($order_record2[3])."',
'".addslashes($order_record2[4])."',
'".addslashes($order_record2[5])."',
'".addslashes($order_record2[6])."',
'".addslashes($order_record2[7])."',
'".addslashes($order_record2[8])."',
'".addslashes($order_record2[9])."',
'".addslashes($order_record2[10])."',
'".addslashes($order_record2[11])."',
'".addslashes($order_record2[12])."',
'Invoice Issued',
'".addslashes($_POST["exchange_rate"])."',
'".$time."',
'".addslashes($order_record2[16])."',
'".addslashes($order_record2[17])."',
'".addslashes($order_record2[18])."',
'".addslashes($order_record2[19])."',
'".$maxid."'";

$sql_command->insert("quotation_proforma_history",$invoice_history_cols,$values);
$sql_command->update("quotation_history","status='Invoice Issued'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record2[0]."'");
$sql_command->update("quotation_history","exchange_rate='".addslashes($_POST["exchange_rate"])."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record2[0]."'");
$sql_command->update("quotation_history","invoice_id='".$maxid."'","order_id='".addslashes($_POST["invoice_id"])."' and id='".$order_record2[0]."'");

}
}



}
}


$euro_pound = $total_iw_euro_cost / $_POST["exchange_rate"]; 
$total_due_package = $euro_pound + $total_iw_pound_cost;


$sql_command->update("quotation_proformas","cost='".stripslashes($total_cost_package)."'","id='".$maxid."'");
$sql_command->update("quotation_proformas","iw_cost='".stripslashes($total_due_package)."'","id='".$maxid."'");

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Proforma Created (# ".$maxid.")','".$time."'");




$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp,code,invoice_id,updated_timestamp";



$package_option_result = $sql_command->select("quotation_history,$database_package_info,$database_packages","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.currency,
										   quotation_history.status,
										   $database_packages.id,
										   $database_packages.supplier_id,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_,quotation_history.code","WHERE 
										   quotation_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   quotation_history.item_type='Package' and
										   quotation_history.type_id=$database_package_info.id and 
										   $database_package_info.package_id=$database_packages.id and 
										   $database_packages.deleted='No'");
$package_option_record = $sql_command->result($package_option_result);

$theitem = "id_".$package_option_record[0];	



if($_POST[$theitem] == "Yes" and $package_option_record[0]) { 


















$menu_option_result = $sql_command->select("quotation_history,$database_menus,$database_menu_options","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.currency,
										   quotation_history.status,
										   $database_menu_options.code,
										   $database_menus.supplier_id,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_","WHERE 
										   quotation_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   quotation_history.item_type='Menu' and
										   quotation_history.type_id=$database_menu_options.id and 
										   $database_menu_options.menu_id=$database_menus.id and 
										   $database_menu_options.deleted='No' and 
										   quotation_history.type='Included'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
$adjustment_iw = $menu_option_record[4];
$adjustment = $menu_option_record[3];


// Work out adjustments
if($menu_option_record[2] > 0) {
	
if($menu_option_record[11] == "Gross") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment_iw = $menu_option_record[4] + $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] + ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Net") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment = $menu_option_record[3] + $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] + ($percent_value * $menu_option_record[9]);
}

} elseif($menu_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[9];
} elseif($menu_option_record[11] == "Absolute Net") { 
$adjustment = $menu_option_record[9];
}
	
} elseif($menu_option_record[2] < 0) {
	
if($menu_option_record[11] == "Gross") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[9] != 0) {
$adjustment_iw = $menu_option_record[4] - $menu_option_record[9];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] - ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Net") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment = $menu_option_record[3] - $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] - ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[9];
} elseif($menu_option_record[11] == "Absolute Net") { 
$adjustment = $menu_option_record[9];
}

}

if($menu_option_record[9] == 0 and $menu_option_record[11] == "Net") {
$adjustment_iw = $menu_option_record[3];
}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[8])."',
'".addslashes($menu_option_record[1])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($menu_option_record[5])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'$time',
'".addslashes($menu_option_record[7])."',
'".$maxid."',
''";
//$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	

}



$package_option_result2 = $sql_command->select("quotation_history,$database_package_extras","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.currency,
										   quotation_history.status,
										   $database_package_extras.code,
										   $database_package_extras.supplier_id,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_","WHERE 
										   quotation_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   (quotation_history.item_type='Extra' or quotation_history.item_type='Service Fee') and
										   quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.type='Included'");
$package_option_row2 = $sql_command->results($package_option_result2);

foreach($package_option_row2 as $package_option_record2) {
	

$adjustment_iw = $package_option_record2[4];
$adjustment = $package_option_record2[3];


// Work out adjustments
if($package_option_record2[2] > 0) {
	
if($package_option_record2[11] == "Gross") { 

if($package_option_record2[10] == "Amount" and $package_option_record2[9] != 0) {
$adjustment_iw = $package_option_record2[4] + $package_option_record2[9];
} elseif($package_option_record2[10] == "Percent" and $package_option_record2[9] != 0) {
$percent_value = ($package_option_record2[4] / 100);
$adjustment_iw = $package_option_record2[4] + ($percent_value * $package_option_record2[9]);
} 

} elseif($package_option_record2[11] == "Net") { 

if($package_option_record2[10] == "Amount" and $package_option_record2[9] != 0) {
$adjustment = $package_option_record2[3] + $package_option_record2[9];
} elseif($package_option_record2[10] == "Percent" and $package_option_record2[9] != 0) {
$percent_value = ($package_option_record2[3] / 100);
$adjustment = $package_option_record2[3] + ($percent_value * $package_option_record2[9]);
}

} elseif($package_option_record2[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record2[9];
} elseif($package_option_record2[11] == "Absolute Net") { 
$adjustment = $package_option_record2[9];
}
	
} elseif($package_option_record2[2] < 0) {
	
if($package_option_record2[11] == "Gross") { 

if($package_option_record2[12] == "Amount" and $package_option_record2[9] != 0) {
$adjustment_iw = $package_option_record2[4] - $package_option_record2[9];
} elseif($package_option_record2[12] == "Percent" and $package_option_record2[9] != 0) {
$percent_value = ($package_option_record2[4] / 100);
$adjustment_iw = $package_option_record2[4] - ($percent_value * $package_option_record2[9]);
} 

} elseif($package_option_record2[11] == "Net") { 

if($package_option_record2[10] == "Amount" and $package_option_record2[9] != 0) {
$adjustment = $package_option_record2[3] - $package_option_record2[9];
} elseif($package_option_record2[10] == "Percent" and $package_option_record2[9] != 0) {
$percent_value = ($package_option_record2[3] / 100);
$adjustment = $package_option_record2[3] - ($percent_value * $package_option_record2[9]);
} 

} elseif($package_option_record2[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record2[9];
} elseif($package_option_record2[11] == "Absolute Net") { 
$adjustment = $package_option_record2[9];
}

}

if($package_option_record2[9] == 0 and $package_option_record2[11] == "Net") {
$adjustment_iw = $package_option_record2[3];
}


$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record2[8])."',
'".addslashes($package_option_record2[1])."',
'".addslashes($package_option_record2[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record2[5])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'$time',
'".addslashes($package_option_record2[7])."',
'".$maxid."',
''";
//$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}























$adjustment_iw = $package_option_record[4];
$adjustment = $package_option_record[3];


// Work out adjustments
if($package_option_record[2] > 0) {
	
if($package_option_record[11] == "Gross") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment_iw = $package_option_record[4] + $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[4] / 100);
$adjustment_iw = $package_option_record[4] + ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Net") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment = $package_option_record[3] + $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[3] / 100);
$adjustment = $package_option_record[3] + ($percent_value * $package_option_record[9]);
}

} elseif($package_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record[9];
} elseif($package_option_record[11] == "Absolute Net") { 
$adjustment = $package_option_record[9];
}
	
} elseif($package_option_record[2] < 0) {
	
if($package_option_record[11] == "Gross") { 

if($package_option_record[12] == "Amount" and $package_option_record[9] != 0) {
$adjustment_iw = $package_option_record[4] - $package_option_record[9];
} elseif($package_option_record[12] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[4] / 100);
$adjustment_iw = $package_option_record[4] - ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Net") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment = $package_option_record[3] - $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[3] / 100);
$adjustment = $package_option_record[3] - ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record[9];
} elseif($package_option_record[11] == "Absolute Net") { 
$adjustment = $package_option_record[9];
}
	

}

if($package_option_record[9] == 0 and $package_option_record[11] == "Net") {
$adjustment_iw = $package_option_record[3];
}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record[8])."',
'".addslashes($package_option_record[1])."',
'".addslashes($package_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record[5])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'$time',
'".addslashes($package_option_record[12])."',
'".$maxid."',
''";
//$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	






$menu_option_result = $sql_command->select("quotation_history,$database_menus,$database_menu_options","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.currency,
										   quotation_history.status,
										   $database_menu_options.code,
										   $database_menus.supplier_id,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_","WHERE 
										   quotation_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   quotation_history.item_type='Menu' and
										   quotation_history.type_id=$database_menu_options.id and 
										   $database_menu_options.menu_id=$database_menus.id and 
										   $database_menu_options.deleted='No'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
$theitem = "id_".$menu_option_record[0];	

if($_POST[$theitem] == "Yes" and $menu_option_record[0]) { 


$adjustment_iw = $menu_option_record[4];
$adjustment = $menu_option_record[3];


// Work out adjustments
if($menu_option_record[2] > 0) {
	
if($menu_option_record[11] == "Gross") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment_iw = $menu_option_record[4] + $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] + ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Net") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment = $menu_option_record[3] + $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] + ($percent_value * $menu_option_record[9]);
}

} elseif($menu_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[9];
} elseif($menu_option_record[11] == "Absolute Net") { 
$adjustment = $menu_option_record[9];
}
	
	
} elseif($menu_option_record[2] < 0) {
	
if($menu_option_record[11] == "Gross") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[9] != 0) {
$adjustment_iw = $menu_option_record[4] - $menu_option_record[9];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] - ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Net") { 

if($menu_option_record[10] == "Amount" and $menu_option_record[9] != 0) {
$adjustment = $menu_option_record[3] - $menu_option_record[9];
} elseif($menu_option_record[10] == "Percent" and $menu_option_record[9] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] - ($percent_value * $menu_option_record[9]);
} 

} elseif($menu_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[9];
} elseif($menu_option_record[11] == "Absolute Net") { 
$adjustment = $menu_option_record[9];
}
	

}

if($menu_option_record[9] == 0 and $menu_option_record[11] == "Net") {
$adjustment_iw = $menu_option_record[3];
}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[8])."',
'".addslashes($menu_option_record[1])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($menu_option_record[5])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'$time',
'".addslashes($menu_option_record[7])."',
'".$maxid."',
''";
//$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	
}



$package_option_result = $sql_command->select("quotation_history,$database_package_extras","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.currency,
										   quotation_history.status,
										   $database_package_extras.code,
										   $database_package_extras.supplier_id,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_","WHERE 
										   quotation_history.order_id='".addslashes($_POST["invoice_id"])."' and 
										   (quotation_history.item_type='Extra' or quotation_history.item_type='Service Fee') and
										   quotation_history.type_id=$database_package_extras.id");
$package_option_row = $sql_command->results($package_option_result);

foreach($package_option_row as $package_option_record) {
	
$theitem = "id_".$package_option_record[0];	

if($_POST[$theitem] == "Yes" and $package_option_record[0]) { 


$adjustment_iw = $package_option_record[4];
$adjustment = $package_option_record[3];


// Work out adjustments
if($package_option_record[2] > 0) {
	
if($package_option_record[11] == "Gross") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment_iw = $package_option_record[4] + $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[4] / 100);
$adjustment_iw = $package_option_record[4] + ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Net") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment = $package_option_record[3] + $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[3] / 100);
$adjustment = $package_option_record[3] + ($percent_value * $package_option_record[9]);
}

} elseif($package_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record[9];
} elseif($package_option_record[11] == "Absolute Net") { 
$adjustment = $package_option_record[9];
}
	
	
} elseif($package_option_record[2] < 0) {
	
if($package_option_record[11] == "Gross") { 

if($package_option_record[12] == "Amount" and $package_option_record[9] != 0) {
$adjustment_iw = $package_option_record[4] - $package_option_record[9];
} elseif($package_option_record[12] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[4] / 100);
$adjustment_iw = $package_option_record[4] - ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Net") { 

if($package_option_record[10] == "Amount" and $package_option_record[9] != 0) {
$adjustment = $package_option_record[3] - $package_option_record[9];
} elseif($package_option_record[10] == "Percent" and $package_option_record[9] != 0) {
$percent_value = ($package_option_record[3] / 100);
$adjustment = $package_option_record[3] - ($percent_value * $package_option_record[9]);
} 

} elseif($package_option_record[11] == "Absolute Gross") { 
$adjustment_iw = $package_option_record[9];
} elseif($package_option_record[11] == "Absolute Net") { 
$adjustment = $package_option_record[9];
}
	
}

if($package_option_record[9] == 0 and $package_option_record[11] == "Net") {
$adjustment_iw = $package_option_record[3];
}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record[8])."',
'".addslashes($package_option_record[1])."',
'".addslashes($package_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record[5])."',
'Outstanding',
'".addslashes($_POST["exchange_rate"])."',
'$time',
'".addslashes($package_option_record[7])."',
'".$maxid."',
''";
//$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	
}








//$supplier_result2 = $sql_command->select($database_supplier_invoices,"supplier_id,status,exchange_rate,timestamp","WHERE order_id='".addslashes($_POST["invoice_id"])."' and invoice_id='".$maxid."' GROUP BY supplier_id");
//$supplier_row2 = $sql_command->results($supplier_result2);

/*
foreach($supplier_row2 as $supplier_record2) {
	
	
$sql_command->insert($database_supplier_invoices_main,"order_id,supplier_id,invoice_id,timestamp,status,exchange_rate","'".addslashes($_POST["invoice_id"])."',
																																				   '".addslashes($supplier_record2[0])."',
																																				   '".$maxid."',
																																				   '".addslashes($supplier_record2[3])."',
																																				   '".addslashes($supplier_record2[1])."',
																																				   '".addslashes($supplier_record2[2])."'");
$main_maxid = $sql_command->maxid($database_supplier_invoices_main,"id","");


$supplier_result = $sql_command->select($database_supplier_invoices," id,
							   order_id,
							   supplier_id,
							   name,
							   qty,
							   cost,
							   iw_cost,
							   currency,
							   status,
							   exchange_rate,
							   timestamp,
							   code,
							   invoice_id,
							   updated_timestamp","WHERE supplier_id='".$supplier_record2[0]."' and order_id='".addslashes($_POST["invoice_id"])."' and invoice_id='".$maxid."'");
$supplier_row = $sql_command->results($supplier_result);


foreach($supplier_row as $supplier_record) {
$sql_command->insert($database_supplier_invoices_details,"main_id,name,qty,cost,iw_cost,currency,code,order_id,invoice_id","'".$main_maxid."',
																																				   '".addslashes($supplier_record[3])."',
																																				   '".$supplier_record[4]."',
																																				   '".addslashes($supplier_record[5])."',
																																				   '".addslashes($supplier_record[6])."',
																																				   '".addslashes($supplier_record[7])."',
																																				   '".addslashes($supplier_record[11])."',
																																				   '".addslashes($_POST["invoice_id"])."',
																																				   '".$maxid."'");
}

}
*/
if ($_POST['cbased']==="continental") { 

	$success = $sql_command->insert("clients_options","client_id,client_option,option_value","'".$maxid."','continental','Yes'");

}

$the_username = $_SESSION["admin_area_username"];
$the_password = $_SESSION["admin_area_password"];

$login_result = $sql_command->select($database_users,"account_option,id","WHERE username='".addslashes($the_username)."' and password='".addslashes($the_password)."'");
$login_record = $sql_command->result($login_result);
$login_id = $login_record[1];

/*$suppliers_select = $_POST['supplier_l'];
$suppliers_listing = $_POST['suppliers'];
$note_text = $_POST['cClient'];
$client_show = (isset($_POST['clientsCShow'])) ? $_POST['clientsCShow'] : "No";
$cols = "note_primary_reference, note_secondary_reference, note_type, note, extra, userid";

$vals = "'".$_POST["invoice_id"]."',
	'".$maxid."',
	'ProformaComment',
	'".$note_text."',
	'".$client_show."',
	'".addslashes($login_id)."'";

$supplier_query	= $sql_command->insert("notes",$cols,$vals);


$supplier_result = $sql_command->SELECT("order_history, package_extras, supplier_details","supplier_details.id,supplier_details.company_name","WHERE order_history.order_id = '".addslashes($_POST['invoice_id'])."' AND order_history.type_id = package_extras.id AND package_extras.supplier_id = supplier_details.id GROUP BY supplier_details.id ORDER BY supplier_details.company_name");
$supplier_row = $sql_command->results($supplier_result);
foreach($supplier_row as $sl) {
	$note_search = "cSupplier_".$sl[0];
	$note_text = $_POST[$note_search];
	$note_exists = "note_e_".$sl[0];
	$note = $_POST[$note_exists];
	if ($note_text && !$note) {
		$vals = "'".addslashes($_POST['invoice_id'])."',
		'".$sl[0]."',
		'SupplierComment',
		'".$note_text."',
		'No',
		'".addslashes($login_id)."'";
		$supplier_query	= (strlen($note_text)>3) ? $sql_command->insert("notes",$cols,$vals) : false;
	}
	else {
		$supplier_query	= $sql_command->update("notes","note='".$note_text."'","notes_id='".addslashes($note)."'");
	}
}
*/

$suppliers_select = $_POST['supplier_l'];
$suppliers_listing = $_POST['suppliers'];
$note_text = $_POST['cClient'];
$client_show = (isset($_POST['clientsCShow'])) ? $_POST['clientsCShow'] : "No";
$cols = "note_primary_reference, note_secondary_reference, note_type, note, extra, userid";

$vals = "'".$_POST["invoice_id"]."',
	'".$maxid."',
	'ProformaComment',
	'".$note_text."',
	'".$client_show."',
	'".addslashes($login_id)."'";

$supplier_query	= (strlen($note_text)>3) ? $sql_command->insert("notes",$cols,$vals) : "";

$note_text = $_POST['cSupplier'];
$vals = "'".addslashes($_POST['invoice_id'])."',
'".$suppliers_select."',
'SupplierComment',
'".$note_text."',
'Yes',
'".addslashes($login_id)."'";
$supplier_query	= (strlen($note_text)>3) ? $sql_command->insert("notes",$cols,$vals) : "";

header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();

?>