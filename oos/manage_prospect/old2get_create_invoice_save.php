<?

$order_details_result = $sql_command->select("quotation_details","exchange_rate","WHERE id='".addslashes($_POST["invoice_id"])."'");
$order_details_record = $sql_command->result($order_details_result);

$_POST["exchange_rate"] = $order_details_record[0];

if($_POST["exchange_rate"] < 1) {
$_POST["exchange_rate"] = 1;
}

$order_result = $sql_command->select("quotation_history","*","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type!='Deposit'");
$order_row = $sql_command->results($order_result);


$total_due = 0;

$create_invoice = "No";
foreach($order_row as $order_record) {
$theitem = "id_".$order_record[0];	
if($_POST[$theitem] == "Yes") { 
$create_invoice = "Yes";
}
}

if($create_invoice == "Yes") {
$cols = "order_id,cost,iw_cost,vat,status,timestamp,type,updated_timestamp,included_package";
$invoice_history_cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,d_,code,invoice_id";

$sql_command->insert(quotation_proformas,$cols,"'".addslashes($_POST["invoice_id"])."','','','20.00','Quotation','$time','Order','','No'");
$maxid = $sql_command->maxid("quotation_proformas","id","");
}

$cols = "invoice_id,history_id";


foreach($order_row as $order_record) {

$theitem = "id_".$order_record[0];	

if($_POST[$theitem] == "Yes") { 

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

}

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

}

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

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Invoice Created (# ".$maxid.")','".$time."'");




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
										   quotation_history.d_","WHERE 
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

}

}

	
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[8])."',
'".addslashes($menu_option_record[1])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($menu_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($menu_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	

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

}

}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record2[8])."',
'".addslashes($package_option_record2[1])."',
'".addslashes($package_option_record2[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record2[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($package_option_record2[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
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

}

}

	
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record[8])."',
'".addslashes($package_option_record[1])."',
'".addslashes($package_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($package_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
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

}

}

	
$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[8])."',
'".addslashes($menu_option_record[1])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($menu_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($menu_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
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

}

}

$invoice_values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_option_record[8])."',
'".addslashes($package_option_record[1])."',
'".addslashes($package_option_record[2])."',
'".addslashes($adjustment)."',
'".addslashes($adjustment_iw)."',
'".addslashes($package_option_record[5])."',
'Outstanding',
'0',
'$time',
'".addslashes($package_option_record[7])."',
'".$maxid."',
''";
$sql_command->insert($database_supplier_invoices,$invoice_cols,$invoice_values);	
}	
}








header("Location: $site_url/oos/manage-prospect.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();

?>