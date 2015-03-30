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

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	

$orderdetail_info_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);


$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type,d_,status","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);


$adjustment = $package_info_record[2];
$adjustment_iw = $package_info_record[3];

if($package_info_record[7] == "Gross") { 

if($package_info_record[6] == "Amount" and $package_info_record[5] != 0) {
$adjustment_iw = $package_info_record[3] + $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] != 0) {
$percent_value = ($package_info_record[3] / 100);
$adjustment_iw = $package_info_record[3] + ($percent_value * $package_info_record[5]);
} 

} elseif($package_info_record[7] == "Net") { 

if($package_info_record[6] == "Amount" and $package_info_record[5] != 0) {
$adjustment = $package_info_record[2] + $package_info_record[5];
} elseif($package_info_record[6] == "Percent" and $package_info_record[5] != 0) {
$percent_value = ($package_info_record[2] / 100);
$adjustment = $package_info_record[2] + ($percent_value * $package_info_record[5]);
}

} elseif($package_info_record[7] == "Absolute Gross") { 
$adjustment_iw = $package_info_record[5];
} elseif($package_info_record[7] == "Absolute Net") { 
$adjustment = $package_info_record[5];
}

$new_package_cost = $adjustment;
$new_package_cost_iw = $adjustment_iw;

if($package_info_record[5] == 0 and $package_info_record[7] == "Net") {
$new_package_cost_iw = 	$package_info_record[2];
}

if($package_info_record[5] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $new_package_cost;
$total_iw_pound_cost += $new_package_cost_iw;
$total_pound_cost_before += $package_info_record[2];
$total_iw_pound_cost_before += $package_info_record[3];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $new_package_cost;
$total_iw_euro_cost += $new_package_cost_iw;
$total_euro_cost_before += $package_info_record[2];
$total_iw_euro_cost_before += $package_info_record[3];
}




$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id=59 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and $database_order_history.type='Extra'");

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
										   $database_order_history.status,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and $database_order_history.type='Extra'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


if($extra_record[11] != "Invoice Issued") {
$option_status = "<input type=\"checkbox\" name=\"id_$extra_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($extra_record[11]);	
}


$adjustment_iw = $extra_record[4];
$adjustment = $extra_record[3];


// Work out adjustments
if($extra_record[2] > 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] + ($percent_value * $extra_record[12]);
}

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}
	
} elseif($extra_record[2] < 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}

}

if($extra_record[12] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Alteration: $extra_record[12] $extra_record[13] $extra_record[14] )</span>";
} else { $discount_html = ""; }

if($extra_record[12] == 0 and $extra_record[14] == "Net") {
$adjustment_iw = $extra_record[3];
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

$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);

$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);

$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);

$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);

$paragraph = $extra_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_extra2 .= "<div style=\"float:left; width:40px; margin:5px 1px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:300px; margin:5px;\">".stripslashes($paragraph)."  ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_iw."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total_cost."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total."</div>
<div style=\"clear:left;\"></div>";

}	
}
}

$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and 
										   $database_menu_options.menu_id='".addslashes($menu_record[0])."' and 
										   $database_order_history.type='Extra' and
										   $database_menu_options.deleted='No'");

if($total_count > 0) {
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
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
										   $database_order_history.status,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Menu' and 
										   $database_menu_options.menu_id='".addslashes($menu_record[0])."' and 
										   $database_order_history.type='Extra' and 
										   $database_menu_options.deleted='No'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
$menu_option_record[3] = 0;
$menu_option_record[4] = 0;
}


if($menu_option_record[11] != "Invoice Issued") {
$option_status = "<input type=\"checkbox\" name=\"id_$menu_option_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($menu_option_record[11]);	
}



$adjustment_iw = $menu_option_record[4];
$adjustment = $menu_option_record[3];


// Work out adjustments
if($menu_option_record[2] > 0) {
	
if($menu_option_record[14] == "Gross") { 

if($menu_option_record[13] == "Amount" and $menu_option_record[12] != 0) {
$adjustment_iw = $menu_option_record[4] + $menu_option_record[12];
} elseif($menu_option_record[13] == "Percent" and $menu_option_record[12] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] + ($percent_value * $menu_option_record[12]);
} 

} elseif($menu_option_record[14] == "Net") { 

if($menu_option_record[13] == "Amount" and $menu_option_record[12] != 0) {
$adjustment = $menu_option_record[3] + $menu_option_record[12];
} elseif($menu_option_record[13] == "Percent" and $menu_option_record[12] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] + ($percent_value * $menu_option_record[12]);
}

} elseif($menu_option_record[14] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[12];
} elseif($menu_option_record[14] == "Absolute Net") { 
$adjustment = $menu_option_record[12];
}

} elseif($menu_option_record[2] < 0) {
	
if($menu_option_record[14] == "Gross") { 

if($menu_option_record[13] == "Amount" and $menu_option_record[12] != 0) {
$adjustment_iw = $menu_option_record[4] - $menu_option_record[12];
} elseif($menu_option_record[13] == "Percent" and $menu_option_record[12] != 0) {
$percent_value = ($menu_option_record[4] / 100);
$adjustment_iw = $menu_option_record[4] - ($percent_value * $menu_option_record[12]);
} 

} elseif($menu_option_record[14] == "Net") { 

if($menu_option_record[13] == "Amount" and $menu_option_record[12] != 0) {
$adjustment = $menu_option_record[3] - $menu_option_record[12];
} elseif($menu_option_record[13] == "Percent" and $menu_option_record[12] != 0) {
$percent_value = ($menu_option_record[3] / 100);
$adjustment = $menu_option_record[3] - ($percent_value * $menu_option_record[12]);
} 

} elseif($menu_option_record[14] == "Absolute Gross") { 
$adjustment_iw = $menu_option_record[12];
} elseif($menu_option_record[14] == "Absolute Net") { 
$adjustment = $menu_option_record[12];
}
	

}
if($menu_option_record[12] == 0 and $menu_option_record[14] == "Net") {
$adjustment_iw = $menu_option_record[3];
}

$the_cost = $menu_option_record[2] * $adjustment;
$total_iw_cost = $menu_option_record[2] * $adjustment_iw;


if($menu_option_record[12] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Alteration: $menu_option_record[12] $menu_option_record[13] $menu_option_record[14] )</span>";
} else { $discount_html = ""; }


if($menu_option_record[8] == "Pound") { 
$curreny = "&pound;"; 
$total_pound_cost += $the_cost;
$total_iw_pound_cost += $total_iw_cost;
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
}

$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);

$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);

$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);


$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);

$paragraph = $menu_option_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_menu .= "<div style=\"float:left; width:40px; margin:5px 1px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($menu_option_record[2])." x</div>
<div style=\"float:left; width:300px; margin:5px;\">".stripslashes($paragraph)."  ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_iw."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total_cost."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total."</div>
<div style=\"clear:left;\"></div>";
}
}
}












$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id!=59 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and $database_order_history.type='Extra'");

if($total_count > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

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
										   $database_order_history.status,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and $database_order_history.type='Extra' ");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
$extra_record[3] = 0;
$extra_record[4] = 0;
}


if($extra_record[11] != "Invoice Issued") {
$option_status = "<input type=\"checkbox\" name=\"id_$extra_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($extra_record[11]);	
}


$adjustment_iw = $extra_record[4];
$adjustment = $extra_record[3];


// Work out adjustments
if($extra_record[2] > 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] + ($percent_value * $extra_record[12]);
}

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}
	
	
} elseif($extra_record[2] < 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}
	

}
if($extra_record[12] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Alteration: $extra_record[12] $extra_record[13] $extra_record[14] )</span>";
} else { $discount_html = ""; }

if($extra_record[12] == 0 and $extra_record[14] == "Net") {
$adjustment_iw = $extra_record[3];
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

$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);

$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);

$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);

$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);

$paragraph = $extra_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_extra .= "<div style=\"float:left; width:40px; margin:5px 1px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:300px; margin:5px;\">".stripslashes($paragraph)."  ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_iw."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total_cost."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total."</div>
<div style=\"clear:left;\"></div>";

}	
}
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and $database_order_history.type='Extra'");

if($total_count > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

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
										   $database_order_history.status,
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and $database_order_history.type='Extra' ");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {

if($extra_record[11] != "Invoice Issued") {
$option_status = "<input type=\"checkbox\" name=\"id_$extra_record[0]\" value=\"Yes\">";
} else {
$option_status = stripslashes($extra_record[11]);	
}



$adjustment_iw = $extra_record[4];
$adjustment = $extra_record[3];


// Work out adjustments
if($extra_record[2] > 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] + $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] + ($percent_value * $extra_record[12]);
}

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}
	
	
} elseif($extra_record[2] < 0) {
	
if($extra_record[14] == "Gross") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment_iw = $extra_record[4] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[4] / 100);
$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Net") { 

if($extra_record[13] == "Amount" and $extra_record[12] != 0) {
$adjustment = $extra_record[3] - $extra_record[12];
} elseif($extra_record[13] == "Percent" and $extra_record[12] != 0) {
$percent_value = ($extra_record[3] / 100);
$adjustment = $extra_record[3] - ($percent_value * $extra_record[12]);
} 

} elseif($extra_record[14] == "Absolute Gross") { 
$adjustment_iw = $extra_record[12];
} elseif($extra_record[14] == "Absolute Net") { 
$adjustment = $extra_record[12];
}
	

}
if($extra_record[12] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Alteration: $extra_record[12] $extra_record[13] $extra_record[14] )</span>";
} else { $discount_html = ""; }

if($extra_record[12] == 0 and $extra_record[14] == "Net") {
$adjustment_iw = $extra_record[3];
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

$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);

$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);

$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);

$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);

$paragraph = $extra_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_service .= "<div style=\"float:left; width:40px; margin:5px 1px;\">$option_status</div>
<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:300px; margin:5px;\">".stripslashes($paragraph)."  ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_iw."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total_cost."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$display_payment_total."</div>
<div style=\"clear:left;\"></div>";

}	
}
}




$include_result = $sql_command->select("$database_order_history","$database_order_history.id,
										   $database_order_history.name,
										   $database_order_history.qty","WHERE $database_order_history.order_id='".addslashes($_GET["invoice_id"])."' and $database_order_history.type='Included' ");
$include_row = $sql_command->results($include_result);

foreach($include_row as $include_record) {
$paragraph = $include_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$includes .= stripslashes($include_record[2])."x ".stripslashes($paragraph)."<br>";	
}



$prev_result = $sql_command->select($database_customer_invoices,"id","WHERE order_id='".addslashes($_GET["invoice_id"])."' and status!='Cancelled' and type='Order'");
$prev_record = $sql_command->results($prev_result);

$add_header = " <script language='JavaScript'>
      checked = false;
      function checkedAll () {
        if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
		var chk = document.getElementById('myform').elements[i].name;
		if (chk.match(/id_.*/)) {
			document.getElementById('myform').elements[i].checked = checked;
		}
	}
}
  </script>";

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

<h2>Create Invoice Details</h2>

<h3 style="color:#000; margin-top:10px; margin-bottom:10px;">Please tick the boxes for the items you want to create an invoice for.</h3>
<h5 style="margin-top:10px; margin-bottom:10px;">If you want the description of an item hidden from the invoice, tick the checkbox next to the items you are including in the invoice.</h5>

<form action="<?php echo $sit_url; ?>/oos/manage-client.php" method="post" id="myform">
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<input type="hidden" name="package_amount" value="<?php echo $package_info_record[3]; ?>">


<div style="float:left; width:30px; margin:5px 1px;"><strong>Inc</strong></div>
<div style="float:left; width:340px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Total Net</strong></div>
<div style="float:left; width:70px; margin:5px 1px;"><strong>Total Gross</strong></div>
<div style="clear:left;"></div>

<h2 style="margin-top:10px; margin-bottom:10px;">Package</h2>
<?php if($package_info_record[8]  != "Invoice Issued") { ?>
<div style="float:left; width:30px; margin:5px 1px;"><input type="checkbox" name="id_<?php echo stripslashes($package_info_record[0]); ?>" value="Yes"></div>
<?php } else { ?>
<div style="float:left; width:40px; margin:5px;"><?php echo stripslashes($package_info_record[8]); ?></div>
<?php } ?>
<div style="float:left; width:30px; margin:5px;">1 x</div>
<div style="float:left; width:300px; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?><?php if($includes) { ?><br /><strong>Includes</strong><br /><?php } ?><?php echo $includes; ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo number_format($package_info_record[2],2); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo number_format($package_info_record[3],2); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo number_format($new_package_cost,2); ?></div>
<div style="float:left; width:60px; margin:5px;"><?php echo $p_curreny; ?> <?php echo number_format($new_package_cost_iw,2); ?></div>
<div style="clear:left;"></div>



<?php if($html_extra2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Category Extras</h2><div style="float:left; width:30px; margin:5px 1px;"><strong>Inc</strong></div>
<div style="clear:left;"></div><?php } ?>

<?php echo $html_extra2; ?>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><div style="float:left; width:30px; margin:5px 1px;"><strong>Inc</strong></div>
<div style="clear:left;"></div><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><div style="float:left; width:30px; margin:5px 1px;"><strong>Inc</strong></div>
<div style="clear:left;"></div><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><div style="float:left; width:30px; margin:5px 1px;"><strong>Inc</strong></div>
<div style="clear:left;"></div><?php } ?>

<?php echo $html_service; ?>


<br />
<div style="float:left; width:40px; margin:10px 5px 5px 5px;"><input type='checkbox' name='checkall' onclick='checkedAll();'></div>
<div style="float:left;  margin:10px 5px 5px 5px;"><strong>Select all items</strong></div>
<div style="clear:left;"></div>
<br />
<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><strong>GBP / Euros</strong></div>
<div style="float:left; margin:5px;"><select name="cbased" id="cbased"><option value="uk" selected>GBP</option><option value="continental">Euro</option></select></div>
<div style="clear:left;"></div>


<?php 

/* echo "<div style=\"float:left; width:160px; margin:5px;\"><strong>Comments for Client Invoice</strong><br /><input type=\"checkbox\" name=\"clientsCShow\" id=\"clientsCShow\" value=\"Yes\" checked/> : Show on Invoice</div>
<div style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"cClient\" id=\"cClient\" placeholder=\"Please enter your comments here.\"></textarea></div>
<div style=\"clear:left;\"></div>";
*/
?>



<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Create Invoice" onclick="return checkexchange();"/></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></div>
<div style="clear:both;"></div>
</form>
<?php 
$get_template->bottomHTML();
$sql_command->close();

?>