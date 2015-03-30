<?

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	
$package_info_result = $sql_command->select("quotation_history","id,name,cost,iw_cost,currency,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
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
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	
$extra_result = $sql_command->select("quotation_history,$database_package_extras","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.local_tax_percent,
										   quotation_history.discount_at,
										   quotation_history.discount_percent,
										   quotation_history.currency,
										   quotation_history.item_type,
										   quotation_history.type,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {

if($extra_record[10] == "Included") {
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
$total_pound_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_pound_cost_before += abs($extra_record[2]) * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
$total_euro_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_euro_cost_before += abs($extra_record[2]) * $extra_record[4];
}

if($extra_record[11] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Discount: $extra_record[11] $extra_record[12] $extra_record[13] )</span>";
} else { $discount_html = ""; }

$end = strpos($extra_record[1], '</p>', $start);
$paragraph = substr($extra_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);


$html_extra2 .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($paragraph)." ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment_iw,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
<div style=\"clear:left;\"></div>";

}	
}
}
$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
	
$total_count = $sql_command->count_rows("quotation_history,$database_menu_options","quotation_history.id","quotation_history.type_id=$database_menu_options.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");

if($total_count > 0) {
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>";
	
$menu_option_result = $sql_command->select("quotation_history,$database_menu_options","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.local_tax_percent,
										   quotation_history.discount_at,
										   quotation_history.discount_percent,
										   quotation_history.currency,
										   quotation_history.item_type,
										   quotation_history.type,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_menu_options.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
	
if($menu_option_record[10] == "Included") {
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
$total_pound_cost_before += abs($menu_option_record[2]) * $menu_option_record[3];
$total_iw_pound_cost_before += abs($menu_option_record[2]) * $menu_option_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
$total_euro_cost_before += abs($menu_option_record[2]) * $menu_option_record[3];
$total_iw_euro_cost_before += abs($menu_option_record[2]) * $menu_option_record[4];
}

if($menu_option_record[11] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Discount: $menu_option_record[11] $menu_option_record[12] $menu_option_record[13] )</span>";
} else { $discount_html = ""; }

$end = strpos($menu_option_record[1], '</p>', $start);
$paragraph = substr($menu_option_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_menu .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($menu_option_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($paragraph)." ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment_iw,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
<div style=\"clear:left;\"></div>";
}
}
}









$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id!=59 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("quotation_history,$database_package_extras","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.local_tax_percent,
										   quotation_history.discount_at,
										   quotation_history.discount_percent,
										   quotation_history.currency,
										   quotation_history.item_type,
										   quotation_history.type,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
	
if($extra_record[10] == "Included") {
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
$total_pound_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_pound_cost_before +=  abs($extra_record[2]) * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
$total_euro_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_euro_cost_before += abs($extra_record[2]) * $extra_record[4];
}

if($extra_record[11] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Discount: $extra_record[11] $extra_record[12] $extra_record[13] )</span>";
} else { $discount_html = ""; }

$end = strpos($extra_record[1], '</p>', $start);
$paragraph = substr($extra_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);

$html_extra .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($paragraph)." ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment_iw,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
<div style=\"clear:left;\"></div>";

}	
}
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");

if($total_count > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>";

$extra_result = $sql_command->select("quotation_history,$database_package_extras","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty,
										   quotation_history.cost,
										   quotation_history.iw_cost,
										   quotation_history.local_tax_percent,
										   quotation_history.discount_at,
										   quotation_history.discount_percent,
										   quotation_history.currency,
										   quotation_history.item_type,
										   quotation_history.type,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[10] == "Included") {
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
$total_pound_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_pound_cost_before += abs($extra_record[2]) * $extra_record[4];
} else { 
$curreny = "&euro;"; 
$total_euro_cost += $the_cost;
$total_iw_euro_cost += $total_iw_cost;
$total_euro_cost_before += abs($extra_record[2]) * $extra_record[3];
$total_iw_euro_cost_before += abs($extra_record[2]) * $extra_record[4];
}

if($extra_record[11] != 0) {
$discount_html = "<span style=\"font-size:11px;\">( Discount: $extra_record[11] $extra_record[12] $extra_record[13] )</span>";
} else { $discount_html = ""; }

$end = strpos($extra_record[1], '</p>', $start);
$paragraph = substr($extra_record[1], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);


$html_service .= "<div style=\"float:left; width:30px; margin:5px;\">".stripslashes($extra_record[2])." x</div>
<div style=\"float:left; width:450px; margin:5px;\">".stripslashes($paragraph)." ".$discount_html."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($adjustment_iw,2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">".$curreny."&nbsp;".number_format($total_iw_cost,2)."</div>
<div style=\"clear:left;\"></div>";

}	
}
}


$package_exchange_result = $sql_command->select("quotation_details","exchange_rate,reception_id,ceremony_id","WHERE id='".addslashes($_GET["invoice_id"])."'");
$package_exchange_record = $sql_command->result($package_exchange_result);

if($package_exchange_record[0] < 1) {
$package_exchange_record[0] = 1;
}


$ceremony_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE id='".addslashes($package_exchange_record[2])."' ORDER BY ceremony_name");
$ceremony_record = $sql_command->result($ceremony_result);

$ceremony_item = stripslashes($ceremony_record[1]);



$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE id='".addslashes($package_exchange_record[1])."' ORDER BY venue_name");
$venue_record = $sql_command->result($venue_result);

$venue_item = stripslashes($venue_record[1]);




$get_template->topHTML();
?>
<h1>Manage Prospect</h1>

<?php echo $menu_line; ?>

<h2>Order Details</h2>

<div style="float:left; width:160px; margin:5px;"><strong>Package</strong></div>
<div style="float:left; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Package Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($new_package_cost,2); ?> / &pound; <?php echo number_format($new_package_cost / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Package IW Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($new_package_cost_iw,2); ?> / &pound; <?php echo number_format($new_package_cost_iw / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Ceremony Location</strong></div>
<div style="float:left; margin:5px;"><?php echo $ceremony_item; ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><strong>Reception Location</strong></div>
<div style="float:left; margin:5px;"><?php echo $venue_item; ?></div>
<div style="clear:left;"></div>


<h3 style="color:#000; margin-top:10px; margin-bottom:10px;">Package Options</h3>

<div style="float:left; width:30px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:450px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>Cost</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>IW Cost</strong></div>
<div style="float:left; width:60px; margin:5px;"><strong>IW Total</strong></div>
<div style="clear:left;"></div>

<?php if($html_extra2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2><?php } ?>

<?php echo $html_extra2; ?>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>



<?
if($total_euro_cost != $total_euro_cost_before or $total_iw_euro_cost != $total_iw_euro_cost_before or $total_pound_cost != $total_pound_cost_before or $total_iw_pound_cost != $total_iw_pound_cost_before) {
?>
<h3 style="margin-top:10px;">Before Discount Package Costs</h3>
<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_euro_cost_before,2); ?> / &pound; <?php echo number_format($total_euro_cost_before / $package_exchange_record[0],2); ?> </div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_iw_euro_cost_before,2); ?> / &pound; <?php echo number_format($total_iw_euro_cost_before / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Profit</strong></div>
<div style="float:left; margin:5px;">&euro; <?php $total_euro = $total_iw_euro_cost_before -  $total_euro_cost_before; echo number_format($total_euro,2); ?> / &pound; <?php echo number_format($total_euro / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_pound_cost_before,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_iw_pound_cost_before,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Profile</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $total_pound = $total_iw_pound_cost_before - $total_pound_cost_before; echo number_format($total_pound,2); ?></div>
<div style="clear:left;"></div>

<div style="float:left; width:150px; margin:5px;"><strong>Total Overall Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $euro_pound = $total_iw_euro_cost_before / $package_exchange_record[0]; echo number_format($euro_pound + $total_iw_pound_cost_before,2); ?></div>
<div style="clear:left;"></div>
<?php } ?>


<h3 style="margin-top:10px;">Package Costs</h3>
<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_euro_cost,2); ?> / &pound; <?php echo number_format($total_euro_cost / $package_exchange_record[0],2); ?> </div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Euro Cost</strong></div>
<div style="float:left; margin:5px;">&euro; <?php echo number_format($total_iw_euro_cost,2); ?> / &pound; <?php echo number_format($total_iw_euro_cost / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Euro Profit</strong></div>
<div style="float:left; margin:5px;">&euro; <?php $total_euro = $total_iw_euro_cost -  $total_euro_cost; echo number_format($total_euro,2); ?> / &pound; <?php echo number_format($total_euro / $package_exchange_record[0],2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_pound_cost,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Iw Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php echo number_format($total_iw_pound_cost,2); ?></div>
<div style="clear:left;"></div>
<div style="float:left; width:150px; margin:5px;"><strong>Total Pound Profile</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $total_pound = $total_iw_pound_cost - $total_pound_cost; echo number_format($total_pound,2); ?></div>
<div style="clear:left;"></div>

<div style="float:left; width:150px; margin:5px;"><strong>Total Overall Pound Cost</strong></div>
<div style="float:left; margin:5px;">&pound; <?php $euro_pound = $total_iw_euro_cost / $package_exchange_record[0]; echo number_format($euro_pound + $total_iw_pound_cost,2); ?></div>
<div style="clear:left;"></div>

<div style="margin-top:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></div>
<?
$get_template->bottomHTML();
$sql_command->close();

?>