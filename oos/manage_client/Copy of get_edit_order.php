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

	
	
$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type,status,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
$package_info_record = $sql_command->result($package_info_result);

$order_info_result = $sql_command->select($database_order_details,"package_id,reception_id,ceremony_id","WHERE id='".addslashes($_GET["invoice_id"])."'");
$order_info_record = $sql_command->result($order_info_result);

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
	
	
$island_result = $sql_command->select("$database_order_details,$database_packages,$database_package_info","$database_order_details.package_id,
									$database_packages.island_id
									","WHERE $database_order_details.id='".addslashes($_GET["invoice_id"])."' and
									$database_order_details.package_id=$database_package_info.id and 
									$database_package_info.package_id=$database_packages.id");
$island_record = $sql_command->result($island_result);


if($package_info_record[4] == "Pound") { 
$p_curreny = "&pound;"; 
$total_pound_cost += $package_info_record[1];
$total_iw_pound_cost += $package_info_record[2];
} else {
$p_curreny = "&euro;"; 
$total_euro_cost += $package_info_record[1];
$total_iw_euro_cost += $package_info_record[2];
}



$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id=59 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($island_record[1])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra2 .= "<div style=\"float:left; width:30px; margin:5px;\"><strong>Hide</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:290px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 island_id='".addslashes($island_record[1])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra' and 
									 deleted='No' 
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Extra' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}


$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}


} else {
$qty = "0";
$d_type = "0";
$selected = "";

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";
}

$iw_cost = $extra_record[4];

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
if($supplier_record[1] == "Ionian Weddings") { $selectedyes = "selected=\"selected\""; } else {$selectedyes = ""; }
	$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selectedyes>".stripslashes(str_replace("'","&#39",$supplier_record[1]))."</option>";
}


$html_extra2 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Amount\">Amount</option>
<option value=\"Percent\"  $selected>Percent</option>
</select><select name=\"extra_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}
}


$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id=58 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($island_record[1])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra3 .= "
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,category_id","WHERE 
									 island_id='".addslashes($island_record[1])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Extra' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}

} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

}

$iw_cost = $extra_record[4];

if($extra_record[7] == 58) {
if($get_checked_record[0] != 0) {
$html_extra3 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Amount\" >Amount</option>
<option value=\"Percent\" $selected>Percent</option>
</select><select name=\"extra_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}
} else {
$html_extra3 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select><select name=\"extra_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}
}	
}
}






$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id=58 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($island_record[1])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra4 .= "
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,category_id","WHERE 
									 island_id='".addslashes($island_record[1])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Included' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}

} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

}

$iw_cost = $extra_record[4];

if($extra_record[7] == 58) {
if($get_checked_record[0] != 0) {
$html_extra4 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"include_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"include_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"include_d_type_$extra_record[0]\">
<option value=\"Amount\" >Amount</option>
<option value=\"Percent\" $selected>Percent</option>
</select><select name=\"include_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Net\"$selected_d>Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}
} else {
$html_extra4 .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"include_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"include_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"include_d_type_$extra_record[0]\">
<option value=\"Percent\">Percent</option>
<option value=\"Amount\"  $selected>Amount</option>
</select><select name=\"include_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}
}	
}
}


$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","WHERE island_id='".addslashes($island_record[1])."' and deleted='No'  ORDER BY menu_name_iw");
$menu_row = $sql_command->results($menu_result);

foreach($menu_row as $menu_record) {
	
$html_menu .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[1])."</h3>
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$menu_option_result = $sql_command->select($database_menu_options,"id,menu_id,menu_name,cost,cost_iw,currency","WHERE menu_id='".addslashes($menu_record[0])."' ORDER BY menu_name");
$menu_option_row = $sql_command->results($menu_option_result);

foreach($menu_option_row as $menu_option_record) {
if($menu_option_record[4] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($menu_option_record[0])."' and item_type='Menu' and type='Extra' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);


if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}

} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

}

$iw_cost = $menu_option_record[4];

$html_menu .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_qty_$menu_option_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_option_record[2])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($menu_option_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"menu_d_value_$menu_option_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"menu_d_type_$menu_option_record[0]\">
<option value=\"Amount\" >Amount</option>
<option value=\"Percent\" $selected>Percent</option>
</select><select name=\"menu_d_$menu_option_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}

}




$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id!=59 and id!=58 ORDER BY category_name");
$extra_cat_row = $sql_command->results($extra_cat_result);

foreach($extra_cat_row as $extra_cat_record) {
	
$total_rows = $sql_command->count_rows($database_package_extras,"id","island_id='".addslashes($island_record[1])."' AND category_id='".addslashes($extra_cat_record[0])."' and type='Extra'");

if($total_rows > 0) {
	
$html_extra .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$extra_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes,category_id","WHERE 
									 island_id='".addslashes($island_record[1])."' AND 
									 category_id=".addslashes($extra_cat_record[0])." AND 
									 type='Extra'
									 ORDER BY product_name");
$extra_row = $sql_command->results($extra_result);

foreach($extra_row as $extra_record) {
if($extra_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }


$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($extra_record[0])."' and item_type='Extra' and type='Extra'  and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);
if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}

} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";
}

$iw_cost = $extra_record[4];


$html_extra .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_qty_$extra_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($extra_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($extra_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"extra_d_value_$extra_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"extra_d_type_$extra_record[0]\">
<option value=\"Amount\" >Amount</option>
<option value=\"Percent\" $selected>Percent</option>
</select><select name=\"extra_d_$extra_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";
}
	
}


$total_rows2 = $sql_command->count_rows($database_package_extras,"id","category_id='".addslashes($extra_cat_record[0])."' and type='Service'");

if($total_rows2 > 0) {
	
$html_service .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($extra_cat_record[1])."</h3>
<div style=\"float:left; width:30px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"float:left; width:30px; margin:5px;\"><strong>VLE</strong></div>
<div style=\"clear:left;\"></div>";
	
$service_result = $sql_command->select($database_package_extras,"id,product_name,currency,cost,iw_cost,type,notes","WHERE 
									 category_id=".addslashes($extra_cat_record[0])." and 
									 type='Service'
									 ORDER BY product_name");
$service_row = $sql_command->results($service_result);

foreach($service_row as $service_record) {
if($service_record[2] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($service_record[0])."' and item_type='Service Fee'  and type='Extra' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if($get_checked_record[0]) {
$qty = stripslashes($get_checked_record[0]);
$d_type = stripslashes($get_checked_record[1]);

if($get_checked_record[2] == "Percent") { 
$selected = "selected=\"selected\""; 
} else { 
$selected = ""; 
}

$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";

if($get_checked_record[3] == "Net") { 
$selected_d = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Gross") { 
$selected_d3 = "selected=\"selected\"";
} elseif($get_checked_record[3] == "Absolute Net") { 
$selected_d2 = "selected=\"selected\"";
}

} else {
$qty = "0";
$d_type = "0";
$selected = "";
$selected_d = "";
$selected_d2 = "";
$selected_d3 = "";
}

$iw_cost = $service_record[4];

$html_service .= "
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_qty_$service_record[0]\" style=\"width:30px;\" value=\"$qty\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($service_record[1])."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($service_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">".$curreny."&nbsp;".number_format($iw_cost,2)."</div>
<div style=\"float:left; width:30px; margin:5px;\"><input type=\"text\" name=\"service_d_value_$service_record[0]\" value=\"$d_type\" style=\"width:30px;\"></div>
<div style=\"float:left; margin:5px;\"><select name=\"service_d_type_$service_record[0]\">
<option value=\"Amount\"  >Amount</option>
<option value=\"Percent\" $selected>Percent</option>
</select><select name=\"service_d_$service_record[0]\" style=\"width:80px;\">
<option value=\"Gross\" >Gross</option>
<option value=\"Absolute Gross\" $selected_d3>Absolute Gross</option>
<option value=\"Net\" $selected_d>Net</option>
<option value=\"Absolute Net\" $selected_d2>Absolute Net</option>
</select></div>
<div style=\"clear:left;\"></div>";

}	
}

}




$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($order_info_record[0])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.category_id=59 and
									$database_package_includes.type='Extra' and
									$database_package_extras.deleted='No' and 
									$database_category_extras.deleted='No' and 
									$database_package_includes.included='Yes'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included2 .= "<div style=\"float:left; width:40px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($package_extra_record[0])."' and item_type='Extra' and type='Included' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if(!$get_checked_record[0]) { $get_checked_record[0] = 0; }
	
$html_extra_included2 .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($get_checked_record[0])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".number_format($package_extra_record[3],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$menu_result = $sql_command->select("$database_package_includes,$database_menus,$database_menu_options","$database_menu_options.id,
									$database_package_includes.qty,
									$database_menus.menu_name_iw,
									$database_menus.local_tax_percent,
									$database_menus.discount_at,
									$database_menus.discount_percent,
									$database_menu_options.menu_name,
									$database_menu_options.cost,
									$database_menu_options.cost_iw,
									$database_menu_options.currency
									","WHERE $database_package_includes.package_id='".addslashes($order_info_record[0])."' and
									$database_package_includes.type_id=$database_menu_options.id and
									$database_menu_options.menu_id=$database_menus.id and
									$database_package_includes.type='Menu' and
									$database_menus.deleted='No' and 
									$database_menu_options.deleted='No' and 
									$database_package_includes.included='Yes'");
$menu_row = $sql_command->results($menu_result);

$added = "No";

foreach($menu_row as $menu_record) {
if($menu_record[9] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_menu_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($menu_record[2])."</h3>
<div style=\"float:left; width:40px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($menu_record[0])."' and item_type='Menu' and type='Included' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if(!$get_checked_record[0]) { $get_checked_record[0] = 0; }
	

$html_menu_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"menu_included_qty_$menu_record[0]\" style=\"width:30px;\" value=\"".stripslashes($get_checked_record[0])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($menu_record[6])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".number_format($menu_record[7],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}



$package_extra_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($order_info_record[0])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_extras.category_id!=59 and
									$database_package_includes.type='Extra' and
									$database_package_extras.deleted='No' and 
									$database_category_extras.deleted='No' and 
									$database_package_includes.included='Yes'");
$package_extra_row = $sql_command->results($package_extra_result);

$added = "No";

foreach($package_extra_row as $package_extra_record) {
if($package_extra_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_extra_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_extra_record[6])."</h3><div style=\"float:left; width:40px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($package_extra_record[0])."' and item_type='Extra' and type='Included' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if(!$get_checked_record[0]) { $get_checked_record[0] = 0; }
	

$html_extra_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"extra_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($get_checked_record[0])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_extra_record[2])."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny ".number_format($package_extra_record[3],2)."</div>
<div style=\"float:left; width:60px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}

$package_service_result = $sql_command->select("$database_package_includes,$database_package_extras,$database_category_extras","$database_package_extras.id,
									$database_package_includes.qty,
									$database_package_extras.product_name,
									$database_package_extras.cost,
									$database_package_extras.iw_cost,
									$database_package_extras.currency,
									$database_category_extras.category_name
									","WHERE $database_package_includes.package_id='".addslashes($order_info_record[0])."' and
									$database_package_includes.type_id=$database_package_extras.id and 
									$database_package_extras.category_id=$database_category_extras.id and
									$database_package_includes.type='Service Fee' and
									$database_package_extras.deleted='No' and 
									$database_category_extras.deleted='No' and 
									$database_package_includes.included='Yes'");
$package_service_row = $sql_command->results($package_service_result);

$added = "No";

foreach($package_service_row as $package_service_record) {
if($package_service_record[5] == "Pound") { $curreny = "&pound;"; } else { $curreny = "&euro;"; }

if($added == "No") {
$html_service_included .= "<h3 style=\"margin-top:10px; margin-bottom:10px;\">".stripslashes($package_service_record[6])."</h3>
<div style=\"float:left; width:40px; margin:5px;\"><strong>QTY</strong></div>
<div style=\"float:left; width:320px; margin:5px;\"><strong>Item</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Net</strong></div>
<div style=\"float:left; width:50px; margin:5px;\"><strong>Gross</strong></div>
<div style=\"clear:left;\"></div>";
$added = "Yes";
}

$get_checked_result = $sql_command->select($database_order_history,"qty,d_value,d_type,d_","WHERE order_id='".addslashes($_GET["invoice_id"])."' and type_id='".addslashes($package_service_record[0])."' and item_type='Service Fee' and type='Included' and (status='Pending' or status='Outstanding')");
$get_checked_record = $sql_command->result($get_checked_result);

if(!$get_checked_record[0]) { $get_checked_record[0] = 0; }
	

$html_service_included .= "
<div style=\"float:left; width:40px; margin:5px;\"><input type=\"text\" name=\"service_included_qty_$package_extra_record[0]\" style=\"width:30px;\" value=\"".stripslashes($get_checked_record[0])."\"></div>
<div style=\"float:left; width:320px; margin:5px;\">".stripslashes($package_service_record[2])."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny ".number_format($package_service_record[3],2)."</div>
<div style=\"float:left; width:50px; margin:5px;\">$curreny 0</div>
<div style=\"clear:left;\"></div>";
}


$orderdetail_info_result = $sql_command->select($database_order_details,"exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
if($supplier_record[1] == "Ionian Weddings") { $selectedyes = "selected=\"selected\""; } else {$selectedyes = ""; }
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selectedyes>".stripslashes(str_replace("'","&#39",$supplier_record[1]))."</option>";
}

$ceremony_result = $sql_command->select($database_ceremonies,"id,ceremony_name","WHERE deleted='No' and island_id='".addslashes($island_record[1])."' ORDER BY ceremony_name");
$ceremony_row = $sql_command->results($ceremony_result);

foreach($ceremony_row as $ceremony_record) {
if($order_info_record[2] == $ceremony_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$ceremony_list .= "<option value=\"".stripslashes($ceremony_record[0])."\" $selected>".stripslashes($ceremony_record[1])."</option>";
}


$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE deleted='No' and island_id='".addslashes($island_record[1])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

foreach($venue_row as $venue_record) {
if($order_info_record[1] == $venue_record[0]) { $selected = "selected=\"selected\"";  } else { $selected = "";  }
$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\" $selected>".stripslashes($venue_record[1])."</option>";
}

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
<script language="JavaScript">
function addElement() {
  var ni = document.getElementById('add_item');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_extra_qty_' + num +'" style="width:20px;"/></div>'
+ '<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_extra_name_' + num +'" style="width:220px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;"><select name="bespoke_extra_currency_' + num +'" style="width:70px;">'
+ '<option value="Euro">Euro</option>'
+ '<option value="Pound">Pound</option>'
+ '</select></div>'
+ '<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_cost_' + num +'" style="width:70px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_iw_cost_' + num +'" style="width:70px;"/></div>'
+ '<div style="float:left; width:150px; margin:5px;"><select name="bespoke_extra_supplier_' + num +'" style="width:150px;">'
+ '<?php echo $supplier_list; ?>'
+ '</select></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>
<script language="JavaScript">
function addElement2() {
  var ni = document.getElementById('add_item2');
  var numi = document.getElementById('theValue2');
  var num = (document.getElementById('theValue2').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_include_qty_' + num +'" style="width:20px;"/></div>'
+ '<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_include_name_' + num +'" style="width:220px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;"><select name="bespoke_include_currency_' + num +'" style="width:70px;">'
+ '<option value="Euro">Euro</option>'
+ '<option value="Pound">Pound</option>'
+ '</select></div>'
+ '<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_include_cost_' + num +'" style="width:70px;"/></div>'
+ '<div style="float:left; width:70px; margin:5px;">&pound; 0</div>'
+ '<div style="float:left; width:150px; margin:5px;"><select name="bespoke_include_supplier_' + num +'" style="width:150px;">'
+ '<?php echo $supplier_list; ?>'
+ '</select></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>


<?php echo $menu_line; ?>

<h2>Edit Order</h2>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $island_record[1]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $package_info_record[0]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<div style="float:left; width:160px; margin:5px;"><b>Ceremony Location</b></div>
<div style="float:left; margin:5px;"><select name="ceremony_id" size="10" style="width:300px;">
<?php echo $ceremony_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Reception Location</b></div>
<div style="float:left; margin:5px;"><select name="venue_id" size="10" style="width:300px;">
<?php echo $venue_list; ?>
</select></div>
<div style="clear:left;"></div>


<div style="float:left; width:40px; margin:5px;">1 x</div>
<div style="float:left; width:280px; margin:5px;"><?php echo stripslashes($package_info_record[1]); ?></div>
<div style="float:left; width:45px; margin:5px 1px;"><?php echo $p_curreny; ?> <?php echo stripslashes($package_info_record[2]); ?></div>
<div style="float:left; width:65px; margin:5px 1px;"><?php echo $p_curreny; ?> <?php echo "<input name=\"gross_package_cost\" value=\"".stripslashes($package_info_record[3])."\" style=\"width:55px;\">"; ?></div>
<?php if($package_info_record[7] != "Invoice Issued") { ?>
<div style="float:left; width:30px; margin:5px;"><input type="text" name="package_d_value" value="<?php echo stripslashes($package_info_record[5]); ?>" style="width:30px;"></div>
<div style="float:left; margin:5px;"><select name="package_d_type">
<option value="Percent" <?php if($package_info_record[6] == "Percent") { echo "selected=\"selected\""; } ?>>Percent</option>
<option value="Amount" <?php if($package_info_record[6] == "Amount") { echo "selected=\"selected\""; } ?>>Amount</option>
</select></div>
<div style="float:left; margin:5px;">
<select name="package_d" style="width:80px;">
<option value="Gross"  <?php if($package_info_record[8] == "Gross") { echo "selected=\"selected\""; } ?>>Gross</option>
<option value="Absolute Gross"  <?php if($package_info_record[8] == "Absolute Gross") { echo "selected=\"selected\""; } ?>>Absolute Gross</option>
<option value="Net"  <?php if($package_info_record[8] == "Net") { echo "selected=\"selected\""; } ?>>Net</option>
<option value="Absolute Net"  <?php if($package_info_record[8] == "Absolute Net") { echo "selected=\"selected\""; } ?>>Absolute Net</option>
</select></div>
<?php } ?>
<div style="clear:left;"></div>

<?php if($package_info_record[7] == "Outstanding" or $package_info_record[7] == "Cancelled" or $package_info_record[7] == "Refunded") { ?>
<div style=" margin:5px;">[ <a href="<?php echo $site_url; ?>/oos/manage-client.php?a=change-package&id=<?php echo $_POST["client_id"]; ?>&invoice_id=<?php echo $_GET["invoice_id"]; ?>">Change Package</a> ]</div>
<?php } ?>

<?php if($html_menu_included or $html_extra_included or $html_service_included or $html_extra_included2 or $html_extra4) { ?><h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Options Included in Package</h1><?php } ?>

<?php if($package_info_record[7] == "Invoice Issued") { ?>
<p>The package has already been invoiced</p>
<?php } else { ?>
<?php if($html_extra_included2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2><?php } ?>

<?php echo $html_extra_included2; ?>

<?php if($html_menu_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu_included; ?>

<?php if($html_extra_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra_included; ?>

<?php if($html_service_included) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service_included; ?>

<h2 style="margin-top:10px; margin-bottom:10px;">Bespoke Include</h2>

<?php echo $html_extra4; ?>

<div style="float:left; width:20px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:220px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Currency</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Supplier</strong></div>
<div style="clear:left;"></div>

<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_include_qty_1" style="width:20px;"/></div>
<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_include_name_1" style="width:220px;"/></div>
<div style="float:left; width:70px; margin:5px;"><select name="bespoke_include_currency_1" style="width:70px;">
<option value="Euro">Euro</option>
<option value="Pound">Pound</option>
</select></div>
<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_include_cost_1" style="width:70px;"/></div>
<div style="float:left; width:70px; margin:5px;">&pound; 0</div>
<div style="float:left; width:150px; margin:5px;"><select name="bespoke_include_supplier_1" style="width:150px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>


<div id="add_item2" style="width:680px; padding:0; margin:0;"> </div>
<input type="hidden" value="1" id="theValue2" name="theValue2">


<p style="margin-top:20px;"><input type="button" value="Add Another Include" onclick="addElement2();"></p>
<?php } ?>

<h1 style="color:#000; margin-top:10px; margin-bottom:10px;">Add Additional Options to Package</h1>

<?php if($html_extra2) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Ceremony Packages</h2><?php } ?>

<?php echo $html_extra2; ?>

<?php if($html_menu) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Menus</h2><?php } ?>

<?php echo $html_menu; ?>

<?php if($html_extra) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Extras</h2><?php } ?>

<?php echo $html_extra; ?>

<?php if($html_service) { ?><h2 style="margin-top:10px; margin-bottom:10px;">Service Fees</h2><?php } ?>

<?php echo $html_service; ?>

<h2 style="margin-top:10px; margin-bottom:10px;">Bespoke Extra</h2>

<?php echo $html_extra3; ?>

<div style="float:left; width:20px; margin:5px;"><strong>QTY</strong></div>
<div style="float:left; width:220px; margin:5px;"><strong>Item</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Currency</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Net</strong></div>
<div style="float:left; width:70px; margin:5px;"><strong>Gross</strong></div>
<div style="float:left; width:150px; margin:5px;"><strong>Supplier</strong></div>
<div style="clear:left;"></div>

<div style="float:left; width:20px; margin:5px;"><input type="text" name="bespoke_extra_qty_1" style="width:20px;"/></div>
<div style="float:left; width:220px; margin:5px;"><input type="text" name="bespoke_extra_name_1" style="width:220px;"/></div>
<div style="float:left; width:70px; margin:5px;"><select name="bespoke_extra_currency_1" style="width:70px;">
<option value="Euro">Euro</option>
<option value="Pound">Pound</option>
</select></div>
<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_cost_1" style="width:70px;"/></div>
<div style="float:left; width:70px; margin:5px;"><input type="text" name="bespoke_extra_iw_cost_1" style="width:70px;"/></div>
<div style="float:left; width:150px; margin:5px;"><select name="bespoke_extra_supplier_1" style="width:150px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>


<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>
<input type="hidden" value="1" id="theValue" name="theValue">


<p style="margin-top:20px;"><input type="button" value="Add Another Extra" onclick="addElement();"></p>


<p>Please enter the current exchange rate so the invoice can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>


<div style="float:left;"><p><input type="submit" name="action" value="Update Order" onclick="return checkexchange();"></p></div>
<div style="float:left; margin-left:200px;"><p><input type="button" name="" value="Back"   onclick="window.location='<?php echo $site_url; ?>/oos/manage-client.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></p></div>
</form>

<form action="<?php echo $site_url; ?>/oos/manage-client.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $island_record[1]; ?>" />
<input type="hidden" name="package_id" value="<?php echo $package_info_record[0]; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET["id"]; ?>" />
<input type="hidden" name="invoice_id" value="<?php echo $_GET["invoice_id"]; ?>" />
<script language="javascript" type="text/javascript">

function deletechecked()
{
    var answer = confirm("Confirm Delete")
    if (answer){
        document.messages.submit();
    }
    
    return false;  
}  

</script>
<div style="float:right;"><p><input type="submit" name="action" value="Delete Order" onclick="return deletechecked();"></p></div>
</form>
<div style="clear:both;"></div>
<?
$get_template->bottomHTML();
$sql_command->close();

?>