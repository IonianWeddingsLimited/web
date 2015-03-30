<?

	if(!$_GET["id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","Missing Prospect ID","Link","oos/manage-prospect.php");
$get_template->bottomHTML();
$sql_command->close();
}

if(!$_GET["invoice_id"]) {
$get_template->topHTML();
$get_template->errorHTML("Manage Prospect","Oops!","Missing Order ID","Link","oos/manage-prospect.php?a=history&id=222");
$get_template->bottomHTML();
$sql_command->close();
}


$notes_q=$sql_command->SELECT("notes,supplier_details","notes.note,notes.extra,notes.notes_id,supplier_details.company_name,notes.userid,notes.timestamp","WHERE notes.note_primary_reference = '".addslashes($_GET['invoice_id'])."' AND notes.note_secondary_reference = supplier_details.id AND notes.note_type = 'SupplierComment' and notes.extra != 'Cancelled'");
$notes_r = $sql_command->results($notes_q);

$sc_script = "CKEDITOR.replace( 'cSupplier',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";
$sc_script .= "CKEDITOR.replace( 'cClient',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";

foreach ($notes_r as $nr) {
	list($dateS,$timeS) = explode(" ",$nr[5]);
	list($yy,$mm,$dd) = explode("-",$dateS);
	$userID = ($nr[4]==0) ? "Unknown" : $sql_command->maxid("users where id='".$nr[4]."'","username");
	$sc_output .= "<div style=\"float:left; width:160px; margin:5px;\">Supplier: ".$nr[3]."<br />Posted:   ".$dd."/".$mm."/".$yy."<br />User: ".$userID."<br /><br />Delete Comment: <input type=\"checkbox\" name=\"delete[]\" id=\"dComment\" value=\"".$nr[2]."\" /></div><div style=\" min-height:90px;\" id=\"textarea_wrap\"><div class=\"textarea\" style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"ucSupplier_".$nr[2]."\" id=\"cSupplier".$nr[2]."\" placeholder=\"Please enter your comments here.\" >".$nr[0]."</textarea></div></div><div style=\"clear:left;\"></div>";

	$sc_script .= "CKEDITOR.replace( 'cSupplier".$nr[2]."',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";

}

$notes_q=$sql_command->SELECT("notes","notes.note,notes.extra,notes.notes_id,notes.note_secondary_reference,notes.timestamp,userid","WHERE notes.note_primary_reference = '".addslashes($_GET['invoice_id'])."' AND notes.note_type = 'ProformaComment' and notes.extra != 'Cancelled'");
$notes_r = $sql_command->results($notes_q);
foreach ($notes_r as $nr) {
	$check_inv = $sql_command->count_nrow("quotation_proforma_history","id","status!='Cancelled' AND invoice_id = '".addslashes($nr[3])."'");
//	if ($check_inv>0) {
		list($dateS,$timeS) = explode(" ",$nr[4]);
		list($yy,$mm,$dd) = explode("-",$dateS);
		$userID = ($nr[5]==0) ? "Unknown" : $sql_command->maxid("users where id='".$nr[5]."'","username");
		$checkedc = ($nr[1]=="Yes") ? "checked=\"checked\"" :  "";
//		<input type=\"checkbox\" value=\"Yes\" $checkedc /> : Show on proforma.<br /><br />
		$cc_output .= "<div style=\"float:left; width:160px; margin:5px;\"><input type=\"hidden\" name=\"clientsCShow\" id=\"clientsCShow\" value=\"Yes\" />Proforma ID:  ".$nr[3]."<br />Posted:   ".$dd."/".$mm."/".$yy."<br />User: ".$userID."<br /><br />Delete Comment: <input type=\"checkbox\" name=\"delete[]\" id=\"dComment\" value=\"".$nr[2]."\" /></div><div style=\" min-height:90px;\" id=\"textarea_wrap\"><div class=\"textarea\" style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"ucClient_".$nr[2]."\" id=\"cClient".$nr[2]."\" placeholder=\"Please enter your comments here.\" >".$nr[0]."</textarea></div></div><div style=\"clear:left;\"></div>";
		
		$sc_script .= "CKEDITOR.replace( 'cClient".$nr[2]."',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";
		
//	}
}


$supplier_list ="";
$supplier_textarea ="";
$nr_output ="";
$d_note = "";


$supplier_orderid = $sql_command->SELECT("quotation_history","type_id,item_type","WHERE quotation_history.order_id = '".addslashes($_GET['invoice_id'])."' GROUP BY type_id");
$orderid_row = $sql_command->results($supplier_orderid);
$test_q=array();
foreach($orderid_row as $or) {
	if ($or[1]=="Menu") {
		$supplier_result = $sql_command->SELECT("menu, menu_options, supplier_details","supplier_details.id,supplier_details.company_name","WHERE menu_options.id = '".addslashes($or[0])."' AND menu_options.menu_id = menu.id AND menu.supplier_id = supplier_details.id");
		$supplier_record = $sql_command->result($supplier_result);	
		$queryo .= "Menu - SELECT supplier_details.id,supplier_details.company_name FROM menu, menu_options, supplier_details WHERE menu_options.id = '".addslashes($or[0])."' AND menu_options.menu_id = menu.id AND menu.supplier_id = supplier_details.id;<br />";
	}
	elseif ($or[1]=="Extra") {
		$supplier_result = $sql_command->SELECT("package_extras, supplier_details","supplier_details.id,supplier_details.company_name","WHERE package_extras.id = '".addslashes($or[0])."' AND package_extras.supplier_id = supplier_details.id");
		$supplier_record = $sql_command->result($supplier_result);	
		$queryo .= "Extra - SELECT supplier_details.id,supplier_details.company_name FROM package_extras, supplier_details WHERE package_extras.id = '".addslashes($or[0])."' AND package_extras.supplier_id = supplier_details.id;<br />";
	}
	else {
		$supplier_result = $sql_command->SELECT("package_info, package_names, supplier_details","supplier_details.id,supplier_details.company_name","WHERE package_info.id = '".addslashes($or[0])."' AND package_info.package_id = package_names.id AND package_names.supplier_id = supplier_details.id");
		$supplier_record = $sql_command->result($supplier_result);	
		$queryo .= "Package - SELECT supplier_details.id,supplier_details.company_name FROM package_info, package_names, supplier_details WHERE package_info.id = '".addslashes($or[0])."' AND package_info.package_id = package_names.id AND package_names.supplier_id = supplier_details.id;<br />";
	}
	//$debug_i = "select supplier_details.id,supplier_details.company_name from package_extras,supplier_details,quotation_history WHERE quotation_history.order_id = '".addslashes($_GET['invoice_id'])."' AND quotation_history.type_id = package_extras.id AND package_extras.supplier_id = supplier_details.id GROUP BY supplier_details.id ORDER BY supplier_details.company_name";
	$existq = $sql_command->select("notes","notes_id,note,count(note)","WHERE note_primary_reference='".$_GET['invoice_id']."' AND note_secondary_reference='".$supplier_record[0]."' and note_type='SupplierComment'");
	$exists = $sql_command->result($existq);
	if ($exists[2]==0 and $supplier_record[0]!=0 and !in_array($supplier_record[1],$test_q)) { 
	$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\" $selectedyes>".stripslashes(str_replace("'","&#39",$supplier_record[1]))."</option>";
	
	$supplier_textarea .= ($supplier_textarea!="") ? "" : "<div id=\"textarea_".$supplier_record[0]."\" style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"cSupplier\" id=\"cSupplier\" placeholder=\"Please enter your comments here.\">".$exists[1]."</textarea>".$d_note."</div>"; 
	
	}
	$test_q[] = $supplier_record[1];
}

$total_pound_cost = 0;
$total_iw_pound_cost = 0;
$total_euro_cost = 0;
$total_iw_euro_cost = 0;
	

$orderdetail_info_result = $sql_command->select("quotation_details","exchange_rate","WHERE id='".addslashes($_GET["invoice_id"])."'");
$orderdetail_info_record = $sql_command->result($orderdetail_info_result);


$package_info_result = $sql_command->select("quotation_history","id,name,cost,iw_cost,currency,d_value,d_type,d_,status","WHERE order_id='".addslashes($_GET["invoice_id"])."' and item_type='Package'");
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
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and quotation_history.type='Extra'");

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
										   quotation_history.status,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and quotation_history.type='Extra'");
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
	
	
$total_count = $sql_command->count_rows("quotation_history,$database_menu_options","quotation_history.id","quotation_history.type_id=$database_menu_options.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Menu' and 
										   $database_menu_options.menu_id='".addslashes($menu_record[0])."' and 
										   quotation_history.type='Extra' and
										   $database_menu_options.deleted='No'");

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
										   quotation_history.status,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_menu_options.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Menu' and 
										   $database_menu_options.menu_id='".addslashes($menu_record[0])."' and 
										   quotation_history.type='Extra' and 
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
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and quotation_history.type='Extra'");

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
										   quotation_history.status,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and quotation_history.type='Extra' ");
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
	
$total_count = $sql_command->count_rows("quotation_history,$database_package_extras","quotation_history.id","quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'  and quotation_history.type='Extra'");

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
										   quotation_history.status,
										   quotation_history.d_value,
										   quotation_history.d_type,
										   quotation_history.d_
										   ","WHERE quotation_history.type_id=$database_package_extras.id and 
										   quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and
										   quotation_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' and quotation_history.type='Extra' ");
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




$include_result = $sql_command->select("quotation_history","quotation_history.id,
										   quotation_history.name,
										   quotation_history.qty","WHERE quotation_history.order_id='".addslashes($_GET["invoice_id"])."' and quotation_history.type='Included' ");
$include_row = $sql_command->results($include_result);

foreach($include_row as $include_record) {
$paragraph = $include_record[1];
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$includes .= stripslashes($include_record[2])."x ".stripslashes($paragraph)."<br>";	
}



$prev_result = $sql_command->select("quotation_proformas","id","WHERE order_id='".addslashes($_GET["invoice_id"])."' and status!='Cancelled' and type='Order'");
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
/*$add_header .= "
<script language='JavaScript'>
$(function() {
	var currentSelect = $(\"#supplier_l\").val();
	   $(\"#supplier_l\").change(function() {
			$(\"#textarea_\" + currentSelect).toggle();
			currentSelect = $(this).val();
			var myElem = $(\"#textarea_\" + currentSelect).length;
			if (myElem == 0) {
				$(\"#textarea_wrap\").append(\"<div id='textarea_\" + currentSelect + \"' style='float:left; margin:5px;'><textarea style='width:400px; height:75px;' name='cSupplier_\" + currentSelect + \"' id='cSupplier' placeholder='Please enter your comments here.'></textarea></div>\");
			}
			else {
				$(\"#textarea_\" + currentSelect).show();	
			}
	  });
});
</script>";
*/
$get_template->topHTML();
?>

    
<h1>Manage Prospect</h1>

<?php echo $menu_line; ?>

<h2>Create Proforma Details</h2>

<h3 style="color:#000; margin-top:10px; margin-bottom:10px;">Please tick the boxes for the items you want to create a proforma for.</h3>
<h5 style="margin-top:10px; margin-bottom:10px;">If you want the description of an item hidden from the proforma, tick the checkbox next to the items you are including in the proforma.</h5>

<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="post" id="myform">
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
<p>Please enter the current exchange rate so the proforma can be created in UK Pound Stirling</p>
<div style="float:left; width:160px; margin:5px;"><strong>Exchange Rate</strong></div>
<div style="float:left; margin:5px;"><input type="text" name="exchange_rate" id="exchange_rate" value="<?php echo $orderdetail_info_record[0]; ?>"></div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><strong>GBP / Euros</strong></div>
<div style="float:left; margin:5px;"><select name="cbased" id="cbased"><option value="uk" selected>GBP</option><option value="continental">Euro</option></select></div>
<div style="clear:left;"></div>


<?php 
//<input type=\"checkbox\" name=\"clientsCShow\" id=\"clientsCShow\" value=\"Yes\" checked/> : Show on Proforma</div>
echo "<div style=\"float:left; width:160px; margin:5px;\"><strong>Add new Prospect comment</strong><br /><input type=\"hidden\" name=\"clientsCShow\" id=\"clientsCShow\" value=\"Yes\" /></div>
<div style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"cClient\" id=\"cClient\" placeholder=\"Please enter your comments here.\"></textarea></div>
<div style=\"clear:left;\"></div>";

if ($supplier_textarea) {
echo "<div style=\"float:left; width:160px; margin:5px;\"><select id=\"supplier_l\" name=\"supplier_l\" style=\"width:150px;\">".$supplier_list."</select></div><div style=\" min-height:90px;\" id=\"textarea_wrap\">".$supplier_textarea."</div><div style=\"clear:left;\"></div>";
}
?>


<div style="float:left; margin-top:10px;"><input type="submit" name="action" value="Create Proforma" onclick="return checkexchange();"/></div>
<div style="float:right; margin-top:10px; margin-right:10px;"><input type="button" name="" value="Back" onclick="window.location='<?php echo $site_url; ?>/oos/manage-prospect.php?a=history&id=<?php echo $_POST["client_id"]; ?>'"></div>
<div style="clear:both;"></div>
</form>

<br />

<form action="<?php echo $site_url; ?>/oos/manage-prospect.php" method="post" id="comments" name="updatecomments">
<input type="hidden" name="order_id" value="<?php echo $_GET['invoice_id']; ?>" />
<input type="hidden" name="client_id" value="<?php echo $_GET['id']; ?>" />

<div>
<br />
<?php 
if ($_GET['cu']) { echo "<center><h3>Comments Updated</h3></center>"; }

$get_inv = $sql_command->select("quotation_proforma_history","DISTINCT invoice_id","WHERE status!='Cancelled' AND order_id = '".addslashes($_GET['invoice_id'])."'");
$get_res = $sql_command->results($get_inv);
$inv = 0;
foreach ($get_res as $gr) {
	$c_inv = $sql_command->count_nrow("notes","notes_id","note_secondary_reference='".addslashes($gr[0])."' and note_type='ProformaComment' and extra != 'Cancelled'");
	$invoices_l .= "<option value=\"".$gr[0]."\">".$gr[0]."</option>";
	$inv++;
	
}
if ($inv>0) {
//	print_r($orderid_row);
//	echo $queryo;
//	print_r($supplier_orderid);
//print_r($test_q);
	echo "<h1>Add New Comments</h1>";
	$i_select = "<select name=\"addInvoice\">".$invoices_l."</select>";	
	
	echo "<div style=\"float:left; width:160px; margin:5px;\"><strong>Add comment to existing proforma: </strong><br />
	Proforma ID: ".$i_select."<br /><input type=\"hidden\" name=\"clientsCShow\" id=\"clientsCShow\" value=\"Yes\" /></div>
	<div style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"aClient\" id=\"aClient\" placeholder=\"Please enter your comments here.\"></textarea></div>
	<div style=\"clear:left;\"></div>";
	$sc_script .= "CKEDITOR.replace( 'aClient',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";

	echo "<div style=\"float:left; width:160px; margin:5px;\"><select id=\"supplier_l\" name=\"supplier_l\" style=\"width:150px;\">".$supplier_list."</select></div><div style=\" min-height:90px;\" id=\"textarea_wrap\">
	<div id=\"textarea\" style=\"float:left; margin:5px;\"><textarea style=\"width:400px; height:75px;\" name=\"aSupplier\" id=\"aSupplier\" placeholder=\"Please enter your comments here.\"></textarea></div></div><div style=\"clear:left;\"></div>";
	$sc_script .= "CKEDITOR.replace( 'aSupplier',{skin : 'kama',toolbar : 'Note',width: 460,height: 100,});";
}
echo "<h1>Update Existing Comments</h1>";

if ($cc_output) {
	echo "<h2>Proforma Comments</h2>";
	echo $cc_output;
}
if ($sc_output) {
	echo "<h2>Supplier Comments</h2>";
	echo $sc_output;
}

echo "</div><div style=\"clear:both;\"></div>";

if ($sc_output || $cc_output || $inv>0) {

echo "<center><input type=\"button\" name=\"Back\" value=\"Back\" onclick=\"window.location='".$site_url."/oos/manage-prospect.php?a=history&id=".$_POST["client_id"]."'\" /><input type=\"submit\" name=\"action\" value=\"Update Comments\" /></center>";

}
echo "<script type=\"text/javascript\">";
echo $sc_script;
echo "</script>";
echo $debug_i;
?>
</form></div>
<?php 
$get_template->bottomHTML();
$sql_command->close();

?>