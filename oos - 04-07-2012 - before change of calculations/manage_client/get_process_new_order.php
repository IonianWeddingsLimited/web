<?

$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp","'".addslashes($_POST["client_id"])."','".$login_record[1]."','Order Created','".$time."'");

$package_info_result = $sql_command->select("$database_package_info,$database_packages","$database_package_info.cost,
											$database_package_info.iw_cost,
											$database_package_info.currency,
											$database_package_info.iw_name,
											$database_packages.package_name,
											$database_package_info.code
											","WHERE $database_package_info.id='".addslashes($_POST["package_id"])."' and  $database_package_info.deleted='No' and $database_package_info.package_id=$database_packages.id");
$package_info_record = $sql_command->result($package_info_result);

$cols = "client_id,package_id,package_cost,package_iw_cost,package_currency,total_paid,total_refunded,total_cost,total_iw_cost,timestamp,exchange_rate,user_id,vat";
$sql_command->insert($database_order_details,$cols,"'".addslashes($_POST["client_id"])."','".addslashes($_POST["package_id"])."','".addslashes($package_info_record[0])."','".addslashes($package_info_record[1])."','".addslashes($package_info_record[2])."','0','0','0','0','$time','0','".$login_record[1]."','20.00'");		
$maxid = $sql_command->maxid($database_order_details,"id","");	


$invoice_cols = "order_id,supplier_id,name,qty,cost,iw_cost,currency,status,exchange_rate,timestamp,code,invoice_id";
$cols = "order_id,type_id,name,qty,cost,iw_cost,local_tax_percent,discount_at,discount_percent,currency,item_type,type,status,exchange_rate,timestamp,d_value,d_type,d_,code,invoice_id";

$values = "'$maxid',
'".addslashes($_POST["package_id"])."',
'".addslashes($package_info_record[4])." > ".addslashes($package_info_record[3])."',
'1',
'".addslashes($package_info_record[0])."',
'".addslashes($package_info_record[1])."',
'0',
'0',
'0',
'".addslashes($package_info_record[2])."',
'Package',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST["package_d_value"])."',
'".addslashes($_POST["package_d_type"])."',
'Net',
'".addslashes($package_info_record[5])."',
''";
$sql_command->insert($database_order_history,$cols,$values);

$values = "'$maxid',
'".addslashes($_POST["package_id"])."',
'Deposit',
'1',
'0',
'0',
'0',
'0',
'0',
'".addslashes($package_info_record[2])."',
'Deposit',
'Extra',
'Cancelled',
'0',
'$time',
'',
'',
'',
'',
''";
$sql_command->insert($database_order_history,$cols,$values);


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
$values = "'$maxid',
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
'',
'".addslashes($menu_option_record[12])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}


$qty_extra = "menu_qty_".$menu_option_record[0];
$d_value_extra = "menu_d_value_".$menu_option_record[0];
$d_type_extra = "menu_d_type_".$menu_option_record[0];
$d_extra = "menu_d_".$menu_option_record[0];

if(abs($_POST[$qty_extra]) != 0) {
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($menu_option_record[0])."',
'".addslashes($menu_option_record[8])." > ".addslashes($menu_option_record[1])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($menu_option_record[2])."',
'".addslashes($menu_option_record[3])."',
'".addslashes($menu_option_record[9])."',
'".addslashes($menu_option_record[10])."',
'".addslashes($menu_option_record[11])."',
'".addslashes($menu_option_record[4])."',
'Menu',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."',
'".addslashes($_POST[$d_extra])."',
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
$values = "'$maxid',
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
'',
'".addslashes($package_extra_record[11])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}

$qty_extra = "extra_qty_".$package_extra_record[0];
$d_value_extra = "extra_d_value_".$package_extra_record[0];
$d_type_extra = "extra_d_type_".$package_extra_record[0];
$d_extra = "extra_d_".$package_extra_record[0];

if(abs($_POST[$qty_extra]) != 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Extra',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."',
'".addslashes($_POST[$d_extra])."',
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

$values = "'$maxid',
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
'',
'".addslashes($package_extra_record[11])."',
''";
$sql_command->insert($database_order_history,$cols,$values);	
}


$qty_extra = "service_qty_".$package_extra_record[0];
$d_value_extra = "service_d_value_".$package_extra_record[0];
$d_type_extra = "service_d_type_".$package_extra_record[0];
$d_extra = "service_d_".$package_extra_record[0];

if(abs($_POST[$qty_extra]) != 0) { 
$add_supplier = "Yes";
$values = "'$maxid',
'".addslashes($package_extra_record[0])."',
'".addslashes($package_extra_record[4])."',
'".addslashes($_POST[$qty_extra])."',
'".addslashes($package_extra_record[6])."',
'".addslashes($package_extra_record[7])."',
'0',
'0',
'0',
'".addslashes($package_extra_record[5])."',
'Service Fee',
'Extra',
'Outstanding',
'0',
'$time',
'".addslashes($_POST[$d_value_extra])."',
'".addslashes($_POST[$d_type_extra])."',
'".addslashes($_POST[$d_extra])."',
'".addslashes($package_extra_record[11])."',
''";
$sql_command->insert($database_order_history,$cols,$values);		
}
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type","WHERE order_id='".addslashes($maxid)."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

if($package_info_record[6] == "Amount" and $package_info_record[5] >= 1) { 
$new_package_cost = $package_info_record[3] - $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] >= 1) { 
$percent_value = ($package_info_record[3] / 100);
$new_package_cost = $package_info_record[3] - ($percent_value * $package_info_record[5]);
} else { 
$new_package_cost = $package_info_record[3];
}





if($package_info_record[5] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[2];
$total_iw_pound_cost += $new_package_cost;
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[2];
$total_iw_euro_cost += $new_package_cost;
}


$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($maxid)."' and
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
										   $database_order_history.d_type,
										   $database_order_history.d_ 
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($maxid)."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Net") { 
$iw_cost = $menu_option_record[4] - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Net") { 
$percent_value = ($menu_option_record[4] / 100);
$iw_cost = $menu_option_record[4] - ($percent_value * $menu_option_record[11]);
} else {
$iw_cost = $menu_option_record[4];
}

$the_cost = $menu_option_record[2] * $menu_option_record[3];
$total_iw_cost_orig = $menu_option_record[2] * $iw_cost;

if($menu_option_record[12] == "Amount" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $menu_option_record[11];
} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] >= 1 and $menu_option_record[13] == "Gross") { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $menu_option_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}



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
										   $database_order_history.order_id='".addslashes($maxid)."' and
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
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($maxid)."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}



if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$iw_cost = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$percent_value = ($extra_record[4] / 100);
$iw_cost = $extra_record[4] - ($percent_value * $extra_record[11]);
} else {
$iw_cost = $extra_record[4];
}

$the_cost = $extra_record[2] * $extra_record[3];
$total_iw_cost_orig = $extra_record[2] * $iw_cost;

if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $extra_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}



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
										   $database_order_history.order_id='".addslashes($maxid)."' and
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
										   $database_order_history.d_type,
										   $database_order_history.d_ 
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($maxid)."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}



if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$iw_cost = $extra_record[4] - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1 and $extra_record[13] == "Net") { 
$percent_value = ($extra_record[4] / 100);
$iw_cost = $extra_record[4] - ($percent_value * $extra_record[11]);
} else {
$iw_cost = $extra_record[4];
}

$the_cost = $extra_record[2] * $extra_record[3];
$total_iw_cost_orig = $extra_record[2] * $iw_cost;

if($extra_record[12] == "Amount" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$total_iw_cost = $total_iw_cost_orig - $extra_record[11];
} elseif($extra_record[12] == "Percent" and $extra_record[11] >= 1 and $extra_record[13] == "Gross") { 
$percent_value = ($total_iw_cost_orig / 100);
$total_iw_cost = $total_iw_cost_orig - ($percent_value * $extra_record[11]);
} else {
$total_iw_cost = $total_iw_cost_orig;
}




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


$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($maxid)."'");
$package_exchange_record = $sql_command->result($package_exchange_result);

if($package_exchange_record[0] < 1) {
$package_exchange_record[0] = 1;
}

$euro_pound = $total_euro_cost / $package_exchange_record[0]; 
$total_cost_save = $euro_pound + $total_pound_cost;


$euro_pound = $total_iw_euro_cost / $package_exchange_record[0]; 
$total_iw_save = $euro_pound + $total_iw_pound_cost;


$sql_command->update($database_order_details,"total_cost='".$total_cost_save."'","id='".addslashes($maxid)."'");
$sql_command->update($database_order_details,"total_iw_cost='".$total_iw_save."'","id='".addslashes($maxid)."'");


	
header("Location: $site_url/oos/manage-client.php?a=history&id=".$_POST["client_id"]);
$sql_command->close();

?>