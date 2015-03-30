<?

$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp,code,invoice_id";
$cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,code,invoice_id";

$package_info_result = $sql_command->select("$database_package_info,$database_packages","$database_package_info.cost,
											$database_package_info.iw_cost,
											$database_package_info.currency,
											$database_package_info.iw_name,
											$database_packages.package_name,
											$database_package_info.code
											","WHERE $database_package_info.id='".addslashes($_POST["package_id"])."' and  $database_package_info.deleted='No' and $database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);

$sql_command->update($database_order_history,"type_id='".addslashes($_POST["package_id"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
$sql_command->update($database_order_history,"name='".addslashes($package_info_record[4])." > ".addslashes($package_info_record[3])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
$sql_command->update($database_order_history,"cost='".addslashes($package_info_record[0])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
$sql_command->update($database_order_history,"iw_cost='".addslashes($package_info_record[1])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");


$sql_command->update($database_order_details,"package_cost='".addslashes($package_info_record[0])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_order_details,"package_iw_cost='".addslashes($package_info_record[1])."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_order_details,"package_id='".addslashes($_POST["package_id"])."'","id='".addslashes($_POST["invoice_id"])."'");

if($_POST["package_d_value"] and $_POST["package_d_type"]) {
$sql_command->update($database_order_history,"d_value='".addslashes($_POST["package_d_value"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
$sql_command->update($database_order_history,"d_type='".addslashes($_POST["package_d_type"])."'","order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
}

$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Menu' and type='Included'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Extra' and type='Included'");
$sql_command->delete($database_order_history,"order_id='".addslashes($_POST["invoice_id"])."' and item_type='Service Fee' and type='Included'");







$menu_option_result = $sql_command->select("$database_menu_options,$database_menus","$database_menu_options.id,
										   $database_menu_options.menu_name,
										   $database_menu_options.cost,
										   $database_menu_options.cost_iw,
										   $database_menu_options.currency,
										   $database_menus.supplier_id,
										   $database_menus.venue_id,
										   $database_menus.island_id,
										   $database_menus.menu_name_iw,
										   $database_menus.local_tax_percent,
										   $database_menus.discount_at,
										   $database_menus.discount_percent,
										   $database_menu_options.code","WHERE $database_menu_options.menu_id=$database_menus.id and $database_menu_options.deleted='No'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
$add_supplier = "No";

$qty = "menu_included_qty_".$menu_option_record[0];	
		 
if(abs($_POST[$qty]) != 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty])."',
'".addslashes($menu_option_record[2])."',
'0',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Included',
'Outstanding',
'0',
'$time',
'',
'',
'".addslashes($menu_option_record[12])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}
}	
	

	
	
	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name,
									$database_package_extras.code
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Extra'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "extra_included_qty_".$package_extra_record[0];	
			 
if(abs($_POST[$qty]) != 0) { 
$add_supplier = "Yes";
$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time',
'',
'',
'".addslashes($package_extra_record[11])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}

}	
	


	
$package_extra_result = $sql_command->select("$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_extras.supplier_id,
									$database_package_extras.category_id,
									$database_package_extras.island_id,
									$database_package_extras.product_name,
									$database_package_extras.currency,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.type,
									$database_package_extras.notes,
									$database_category_extras.category_name,
									$database_package_extras.code
									","WHERE $database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.type='Service'");
$package_extra_row = $sql_command->results($package_extra_result);

foreach($package_extra_row as $package_extra_record) {
$add_supplier = "No";

$qty = "service_included_qty_".$package_extra_record[0];	
			 
if(abs($_POST[$qty]) != 0) { 
$add_supplier = "Yes";

$values = "'".addslashes($_POST["invoice_id"])."',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty])."',
'".addslashes($package_extra_record[6])."',
'0',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Included',
'Outstanding',
'0',
'$time',
'".addslashes($package_extra_record[11])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}
}	
	
$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".addslashes($login_record[1])."','Package Changed','".$time."'");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type,d_","WHERE order_id='".addslashes($_POST["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);


$adjustment = $package_info_record[2];
$adjustment_iw = $package_info_record[3];

if($package_info_record[7] == "Gross") { 

if($package_info_record[6] == "Amount" and $package_info_record[5] >= 0.01) {
$adjustment_iw = $package_info_record[3] + $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] >= 0.01) {
$percent_value = ($package_info_record[3] / 100);
$adjustment_iw = $package_info_record[3] + ($percent_value * $package_info_record[5]);
} 

} elseif($package_info_record[7] == "Net") { 

if($package_info_record[6] == "Amount" and $package_info_record[5] >= 0.01) {
$adjustment = $package_info_record[2] + $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] >= 0.01) {
$percent_value = ($package_info_record[2] / 100);
$adjustment = $package_info_record[2] + ($percent_value * $package_info_record[5]);
}

}

$new_package_cost = $adjustment;
$new_package_cost_iw = $adjustment_iw;



if($package_info_record[5] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $new_package_cost;
$total_iw_pound_cost += $new_package_cost_iw;
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $new_package_cost;
$total_iw_euro_cost += $new_package_cost_iw;
}


$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");

if($total_count > 0) {

$menu_option_result = $sql_command->select("$database_order_history,$database_menu_options","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}


$adjustment_iw = $menu_option_record[4];
$adjustment = $menu_option_record[3];


// Work out adjustments
if($menu_option_record[2] > 0) {
	
if($menu_option_record[13] == "Gross") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 0.01) {
$adjustment_iw = $menu_option_record[4] + $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 0.01) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] + ($percent_value * $menu_option_record[11]);
} 

} elseif($menu_option_record[13] == "Net") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 0.01) {
$adjustment = $menu_option_record[3] + $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 0.01) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] + ($percent_value * $menu_option_record[11]);
}

}
	
} elseif($menu_option_record[2] < 0) {
	
if($menu_option_record[13] == "Gross") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 0.01) {
$adjustment_iw = $menu_option_record[4] - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 0.01) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] - ($percent_value * $menu_option_record[11]);
} 

} elseif($menu_option_record[13] == "Net") { 

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 0.01) {
$adjustment = $menu_option_record[3] - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 0.01) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] - ($percent_value * $menu_option_record[11]);
} 

}

}

$the_cost = $menu_option_record[2] * $adjustment;
$total_iw_cost = $menu_option_record[2] * $adjustment_iw;




if($menu_option_record[8] == "Pound") { 
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

}
}
}












$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


$adjustment_iw = $extra_record[4];
$adjustment = $extra_record[3];


// Work out adjustments
if($extra_record[2] > 0) {
	
if($extra_record[13] == "Gross") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment_iw = $extra_record[4] + $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[11]);
} 

} elseif($extra_record[13] == "Net") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment = $extra_record[3] + $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] + ($percent_value * $extra_record[11]);
}

}
	
} elseif($extra_record[2] < 0) {
	
if($extra_record[13] == "Gross") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment_iw = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[11]);
} 

} elseif($extra_record[13] == "Net") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment = $extra_record[3] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] - ($percent_value * $extra_record[11]);
} 

}

}

$the_cost = $extra_record[2] * $adjustment;
$total_iw_cost = $extra_record[2] * $adjustment_iw;



if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

}	
}
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	

$extra_result = $sql_command->select("$database_order_history,$database_package_extras","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty,
										   $database_order_history.cost,
										   $database_order_history.iw_cost,
										   $database_order_history.local_tax_percent,
										   $database_order_history.discount_at,
										   $database_order_history.discount_percent,
										   $database_order_history.currency,
										   $database_order_history.item_type,
										   $database_order_history.type,
										   $database_order_history.d_value,
										   $database_order_history.d_type
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_POST["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


$adjustment_iw = $extra_record[4];
$adjustment = $extra_record[3];


// Work out adjustments
if($extra_record[2] > 0) {
	
if($extra_record[13] == "Gross") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment_iw = $extra_record[4] + $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[11]);
} 

} elseif($extra_record[13] == "Net") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment = $extra_record[3] + $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] + ($percent_value * $extra_record[11]);
}

}
	
} elseif($extra_record[2] < 0) {
	
if($extra_record[13] == "Gross") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment_iw = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[11]);
} 

} elseif($extra_record[13] == "Net") { 

if($extra_record[12] == "Amount" and $extra_record[11] >= 0.01) {
$adjustment = $extra_record[3] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 0.01) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] - ($percent_value * $extra_record[11]);
} 

}

}

$the_cost = $extra_record[2] * $adjustment;
$total_iw_cost = $extra_record[2] * $adjustment_iw;



if($extra_record[8] == "Pound") {
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

}	
}
}


$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_POST["invoice_id"])."'");
$package_exchange_record = $sql_command->result($package_exchange_result);

if($package_exchange_record[0] < 1) {
$package_exchange_record[0] = 1;
}

$euro_pound = $total_euro_cost / $package_exchange_record[0]; 
$total_cost_save = $euro_pound + $total_pound_cost;


$euro_pound = $total_iw_euro_cost / $package_exchange_record[0]; 
$total_iw_save = $euro_pound + $total_iw_pound_cost;


$sql_command->update($database_order_details,"total_cost='".$total_cost_save."'","id='".addslashes($_POST["invoice_id"])."'");
$sql_command->update($database_order_details,"total_iw_cost='".$total_iw_save."'","id='".addslashes($_POST["invoice_id"])."'");

header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();	





?>