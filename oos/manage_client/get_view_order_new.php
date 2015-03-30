<?php
	$reportorder_id			=	$_GET["invoice_id"];
	
	$total_pound_cost		=	0;
	$total_iw_pound_cost	=	0;
	$total_euro_cost		=	0;
	$total_iw_euro_cost		=	0;
	$package_info_result = $sql_command->select($database_order_history,"id,name,cost,iw_cost,currency,d_value,d_type,d_","WHERE order_id='".addslashes($reportorder_id)."' and item_type='Package'");
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
		} elseif($package_info_record[5] == 0) {
			$adjustment_iw = $package_info_record[2];
		}

	} elseif($package_info_record[7] == "Absolute Gross") {
		$adjustment_iw = $package_info_record[5];
		$package_info_record[3] = $package_info_record[5];
	} elseif($package_info_record[7] == "Absolute Net") {
		$adjustment = $package_info_record[5];
		$package_info_record[2] = $package_info_record[5];
	}

	$new_package_cost = $adjustment;
	$new_package_cost_iw = $adjustment_iw;
	
	if($package_info_record[5] == 0 and $package_info_record[7] == "Net") {
		$new_package_cost_iw = $package_info_record[2];
		$package_info_record[3] = $package_info_record[2];
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
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
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
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."' ");
			$extra_row = $sql_command->results($extra_result);
			foreach($extra_row as $extra_record) {
				
				if($extra_record[10] == "Included") {
					$extra_record[4] = 0;
				}

				$adjustment_iw = $extra_record[4];
				$adjustment = $extra_record[3];
				
				if($extra_record[2] > 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] + ($percent_value * $extra_record[11]);
						}

					}

					elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					}

					elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}

				}

				elseif($extra_record[2] < 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] - $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] - $extra_record[11];
						}

						elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] - ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					} elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}

				}

				
				if($extra_record[4] <> 0 and $extra_record[11] == 0 and $extra_record[13] == "Net") {
					$adjustment_iw = $extra_record[3];
					$extra_record[4] = $extra_record[3];
				}

				$the_cost = $extra_record[2] * $adjustment;
				$total_iw_cost = $extra_record[2] * $adjustment_iw;
				
				if($extra_record[8] == "Pound") {
					$curreny = "&pound;";
					$total_pound_cost += $the_cost;
					$total_iw_pound_cost += $total_iw_cost;
					$total_pound_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_pound_cost_before += $extra_record[2] * $extra_record[4];
				} else {
					$curreny = "&euro;";
					$total_euro_cost += $the_cost;
					$total_iw_euro_cost += $total_iw_cost;
					$total_euro_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_euro_cost_before += $extra_record[2] * $extra_record[4];
				}

				$paragraph = $extra_record[1];
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
				$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);
				$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
				$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);
				$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
				$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);
				$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
				$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);
			}

		}

	}

	$menu_result = $sql_command->select($database_menus,"id,menu_name_iw","ORDER BY menu_name_iw");
	$menu_row = $sql_command->results($menu_result);
	foreach($menu_row as $menu_record) {
		$total_count = $sql_command->count_rows("$database_order_history,$database_menu_options","$database_order_history.id","$database_order_history.type_id=$database_menu_options.id and 
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
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
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Menu' and $database_menu_options.menu_id='".addslashes($menu_record[0])."'");
			$menu_option_row = $sql_command->results($menu_option_result);
			foreach($menu_option_row as $menu_option_record) {
				
				if($menu_option_record[10] == "Included") {
					$menu_option_record[4] = 0;
				}

				$adjustment_iw = $menu_option_record[4];
				$adjustment = $menu_option_record[3];
				
				if($menu_option_record[2] > 0) {
					
					if($menu_option_record[13] == "Gross") {
						
						if($menu_option_record[12] == "Amount" and $menu_option_record[11] != 0) {
							$adjustment_iw = $menu_option_record[4] + $menu_option_record[11];
						} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] != 0) {
							$percent_value = ($menu_option_record[4] / 100);
							$adjustment_iw = $menu_option_record[4] + ($percent_value * $menu_option_record[11]);
						}

					} elseif($menu_option_record[13] == "Net") {
						
						if($menu_option_record[12] == "Amount" and $menu_option_record[11] != 0) {
							$adjustment = $menu_option_record[3] + $menu_option_record[11];
						} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] != 0) {
							$percent_value = ($menu_option_record[3] / 100);
							$adjustment = $menu_option_record[3] + ($percent_value * $menu_option_record[11]);
						}

					} elseif($menu_option_record[13] == "Absolute Gross") {
						$adjustment_iw = $menu_option_record[11];
						$menu_option_record[4] = $menu_option_record[11];
					} elseif($menu_option_record[13] == "Absolute Net") {
						$adjustment = $menu_option_record[11];
						$menu_option_record[3] = $menu_option_record[11];
					}

				} elseif($menu_option_record[2] < 0) {
					
					if($menu_option_record[13] == "Gross") {
						
						if($menu_option_record[12] == "Amount" and $menu_option_record[11] != 0) {
							$adjustment_iw = $menu_option_record[4] - $menu_option_record[11];
						} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] != 0) {
							$percent_value = ($menu_option_record[4] / 100);
							$adjustment_iw = $menu_option_record[4] - ($percent_value * $menu_option_record[11]);
						}

					} elseif($menu_option_record[13] == "Net") {
						
						if($menu_option_record[12] == "Amount" and $menu_option_record[11] != 0) {
							$adjustment = $menu_option_record[3] - $menu_option_record[11];
						} elseif($menu_option_record[12] == "Percent" and $menu_option_record[11] != 0) {
							$percent_value = ($menu_option_record[3] / 100);
							$adjustment = $menu_option_record[3] - ($percent_value * $menu_option_record[11]);
						}

					} elseif($menu_option_record[13] == "Absolute Gross") {
						$adjustment_iw = $menu_option_record[11];
						$menu_option_record[4] = $menu_option_record[11];
					} elseif($menu_option_record[13] == "Absolute Net") {
						$adjustment = $menu_option_record[11];
						$menu_option_record[3] = $menu_option_record[11];
					}

				}

				
				if($menu_option_record[11] == 0 and $menu_option_record[13] == "Net") {
					$adjustment_iw = $menu_option_record[3];
					$menu_option_record[4] = $menu_option_record[3];
				}

				$the_cost = $menu_option_record[2] * $adjustment;
				$total_iw_cost = $menu_option_record[2] * $adjustment_iw;
				
				if($menu_option_record[8] == "Pound") {
					$curreny = "&pound;";
					$total_pound_cost += $the_cost;
					$total_iw_pound_cost += $total_iw_cost;
					$total_pound_cost_before += $menu_option_record[2] * $menu_option_record[3];
					$total_iw_pound_cost_before += $menu_option_record[2] * $menu_option_record[4];
				} else {
					$curreny = "&euro;";
					$total_euro_cost += $the_cost;
					$total_iw_euro_cost += $total_iw_cost;
					$total_euro_cost_before += $menu_option_record[2] * $menu_option_record[3];
					$total_iw_euro_cost_before += $menu_option_record[2] * $menu_option_record[4];
				}

				$paragraph = $menu_option_record[1];
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
				$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);
				$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
				$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);
				$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
				$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);
				$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
				$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);
			}

		}

	}

	$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","where id!=59 ORDER BY category_name");
	$extra_cat_row = $sql_command->results($extra_cat_result);
	foreach($extra_cat_row as $extra_cat_record) {
		$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
		
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
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Extra' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
			$extra_row = $sql_command->results($extra_result);
			foreach($extra_row as $extra_record) {
				
				if($extra_record[10] == "Included") {
					$extra_record[4] = 0;
				}

				$adjustment_iw=$extra_record[4];
				$adjustment=$extra_record[3];
				
				if($extra_record[2] > 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] + ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					} elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}

				} elseif($extra_record[2] < 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] - $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] - $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] - ($percent_value * $extra_record[11]);
						}

					} elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					} elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}
				}
				if($extra_record[4] <> 0 and $extra_record[11] == 0 and $extra_record[13] == "Net") {
					$adjustment_iw = $extra_record[3];
					$extra_record[4] = $extra_record[3];
				}
				$the_cost = $extra_record[2] * $adjustment;
				$total_iw_cost = $extra_record[2] * $adjustment_iw;
				
				if($extra_record[8] == "Pound") {
					$curreny = "&pound;";
					$total_pound_cost += $the_cost;
					$total_iw_pound_cost += $total_iw_cost;
					$total_pound_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_pound_cost_before +=  $extra_record[2] * $extra_record[4];
				} else {
					$curreny = "&euro;";
					$total_euro_cost += $the_cost;
					$total_iw_euro_cost += $total_iw_cost;
					$total_euro_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_euro_cost_before += $extra_record[2] * $extra_record[4];
				}

				$paragraph = $extra_record[1];
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
				$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);
				$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
				$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);
				$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
				$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);
				$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
				$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);
			}
		}
	}

	$extra_cat_result = $sql_command->select($database_category_extras,"id,category_name","ORDER BY category_name");
	$extra_cat_row = $sql_command->results($extra_cat_result);
	foreach($extra_cat_row as $extra_cat_record) {
		$total_count = $sql_command->count_rows("$database_order_history,$database_package_extras","$database_order_history.id","$database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
		
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
										   $database_order_history.d_value,
										   $database_order_history.d_type,
										   $database_order_history.d_
										   ","WHERE $database_order_history.type_id=$database_package_extras.id and 
										   $database_order_history.order_id='".addslashes($reportorder_id)."' and
										   $database_order_history.item_type='Service Fee' and $database_package_extras.category_id='".addslashes($extra_cat_record[0])."'");
			$extra_row = $sql_command->results($extra_result);

			foreach($extra_row as $extra_record) {
				
				if($extra_record[10] == "Included") {
					$extra_record[4] = 0;
				}

				$adjustment_iw = $extra_record[4];
				$adjustment = $extra_record[3];
				
				if($extra_record[2] > 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] + ($percent_value * $extra_record[11]);
						}
					} elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] + $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] + ($percent_value * $extra_record[11]);
						}
					} elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					} elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}
				} elseif($extra_record[2] < 0) {
					
					if($extra_record[13] == "Gross") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment_iw = $extra_record[4] - $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[4] / 100);
							$adjustment_iw = $extra_record[4] - ($percent_value * $extra_record[11]);
						}
					}

					elseif($extra_record[13] == "Net") {
						
						if($extra_record[12] == "Amount" and $extra_record[11] != 0) {
							$adjustment = $extra_record[3] - $extra_record[11];
						} elseif($extra_record[12] == "Percent" and $extra_record[11] != 0) {
							$percent_value = ($extra_record[3] / 100);
							$adjustment = $extra_record[3] - ($percent_value * $extra_record[11]);
						}
					} elseif($extra_record[13] == "Absolute Gross") {
						$adjustment_iw = $extra_record[11];
						$extra_record[4] = $extra_record[11];
					} elseif($extra_record[13] == "Absolute Net") {
						$adjustment = $extra_record[11];
						$extra_record[3] = $extra_record[11];
					}
				}
				
				if($extra_record[4] <> 0 and $extra_record[11] == 0 and $extra_record[13] == "Net") {
					$adjustment_iw = $extra_record[3];
					$extra_record[4] = $extra_record[3];
				}

				$the_cost = $extra_record[2] * $adjustment;
				$total_iw_cost = $extra_record[2] * $adjustment_iw;
				
				if($extra_record[8] == "Pound") {
					$curreny = "&pound;";
					$total_pound_cost += $the_cost;
					$total_iw_pound_cost += $total_iw_cost;
					$total_pound_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_pound_cost_before += $extra_record[2] * $extra_record[4];
				} else {
					$curreny = "&euro;";
					$total_euro_cost += $the_cost;
					$total_iw_euro_cost += $total_iw_cost;
					$total_euro_cost_before += $extra_record[2] * $extra_record[3];
					$total_iw_euro_cost_before += $extra_record[2] * $extra_record[4];
				}

				$paragraph = $extra_record[1];
				$paragraph = str_replace("<p>", "", $paragraph);
				$paragraph = str_replace("</p>", "", $paragraph);
				$display_payment = $curreny."&nbsp;".number_format($adjustment,2);
				$display_payment = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment);
				$display_payment_iw = $curreny."&nbsp;".number_format($adjustment_iw,2);
				$display_payment_iw = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_iw);
				$display_payment_total = $curreny."&nbsp;".number_format($total_iw_cost,2);
				$display_payment_total = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total);
				$display_payment_total_cost = $curreny."&nbsp;".number_format($the_cost,2);
				$display_payment_total_cost = eregi_replace($curreny."&nbsp;-","- $curreny ",$display_payment_total_cost);
			}

		}

	}

	$package_exchange_result = $sql_command->select($database_order_details,"exchange_rate,reception_id,ceremony_id","WHERE id='".addslashes($reportorder_id)."'");
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
	//$get_template->topHTML();
	$total_euro = $total_iw_euro_cost_before -  $total_euro_cost_before;
	$total_pound = $total_iw_pound_cost_before - $total_pound_cost_before;
	$euro_pound = $total_iw_euro_cost_before / $package_exchange_record[0];
	$display_t_euro_before = "&euro; ".number_format($total_euro_cost_before,2);
	
	//$display_t_euro_before_pound = "&pound; ".number_format($total_euro_cost_before / $package_exchange_record[0],2);
	$display_t_euro_before_pound = round($total_euro_cost_before / $package_exchange_record[0],2);
	
	$display_t_iw_euro_before = "&euro; ".number_format($total_iw_euro_cost_before,2);
	
	//$display_t_iw_euro_before_pound = "&pound; ".number_format($total_iw_euro_cost_before / $package_exchange_record[0],2);
	$display_t_iw_euro_before_pound = round($total_iw_euro_cost_before / $package_exchange_record[0],2);
	
	$display_t_profit_euro_before = "&euro; ".number_format($total_euro,2);
	$display_t_profit_euro_before_pound = "&pound; ".number_format($total_euro / $package_exchange_record[0],2);
	
	//$display_t_pound = "&pound; ".number_format($total_pound_cost_before,2);
	$display_t_pound = round($total_pound_cost_before,2);
	
	//$display_t_iw_pound = "&pound; ".number_format($total_iw_pound_cost_before,2);
	$display_t_iw_pound = round($total_iw_pound_cost_before,2);
	
	$display_t_profit_pound = "&pound; ".number_format($total_pound,2);
	$display_overall_pound = "&pound; ".number_format($euro_pound + $total_iw_pound_cost_before,2);
	$display_t_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_euro_before);
	$display_t_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_euro_before_pound);
	$display_t_iw_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_iw_euro_before);
	$display_t_iw_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_iw_euro_before_pound);
	$display_t_profit_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_profit_euro_before);
	$display_t_profit_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_profit_euro_before_pound);
	$display_t_pound = eregi_replace("&pound; -","- &pound; ",$display_t_pound);
	$display_t_iw_pound = eregi_replace("&pound; -","- &pound; ",$display_t_iw_pound);
	$display_t_profit_pound = eregi_replace("&pound; -","- &pound; ",$display_t_profit_pound);
	$display_overall_pound = eregi_replace("&pound; -","- &pound; ",$display_overall_pound);
	
	if($total_euro_cost != $total_euro_cost_before or $total_iw_euro_cost != $total_iw_euro_cost_before or $total_pound_cost != $total_pound_cost_before or $total_iw_pound_cost != $total_iw_pound_cost_before) {
		$display_t_euro_before_pound;
		$display_t_pound;
		$display_t_iw_euro_before_pound;
		$display_t_iw_pound;
	}
	
	$total_euro = $total_iw_euro_cost -  $total_euro_cost;
	$total_pound = $total_iw_pound_cost - $total_pound_cost;
	$euro_pound = $total_iw_euro_cost / $package_exchange_record[0];
	$display_t_euro_before = "&euro; ".number_format($total_euro_cost,2);
	
	//$display_t_euro_before_pound = "&pound; ".number_format($total_euro_cost / $package_exchange_record[0],2);
	$display_t_euro_before_pound = round($total_euro_cost / $package_exchange_record[0],2);
	
	$display_t_iw_euro_before = "&euro; ".number_format($total_iw_euro_cost,2);
	
	//$display_t_iw_euro_before_pound = "&pound; ".number_format($total_iw_euro_cost / $package_exchange_record[0],2);
	$display_t_iw_euro_before_pound = round($total_iw_euro_cost / $package_exchange_record[0],2);
	
	$display_t_profit_euro_before = "&euro; ".number_format($total_euro,2);
	$display_t_profit_euro_before_pound = "&pound; ".number_format($total_euro / $package_exchange_record[0],2);
	
	//$display_t_pound = "&pound; ".number_format($total_pound_cost,2);
	$display_t_pound = round($total_pound_cost,2);
	
	//$display_t_iw_pound = "&pound; ".number_format($total_iw_pound_cost,2);
	$display_t_iw_pound = round($total_iw_pound_cost,2);
	
	$display_t_profit_pound = "&pound; ".number_format($total_pound,2);
	$display_overall_pound = "&pound; ".number_format($euro_pound + $total_iw_pound_cost,2);
	$display_t_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_euro_before);
	$display_t_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_euro_before_pound);
	$display_t_iw_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_iw_euro_before);
	$display_t_iw_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_iw_euro_before_pound);
	$display_t_profit_euro_before = eregi_replace("&euro; -","- &euro; ",$display_t_profit_euro_before);
	$display_t_profit_euro_before_pound = eregi_replace("&pound; -","- &pound; ",$display_t_profit_euro_before_pound);
	$display_t_pound = eregi_replace("&pound; -","- &pound; ",$display_t_pound);
	$display_t_iw_pound = eregi_replace("&pound; -","- &pound; ",$display_t_iw_pound);
	$display_t_profit_pound = eregi_replace("&pound; -","- &pound; ",$display_t_profit_pound);
	$display_overall_pound = eregi_replace("&pound; -","- &pound; ",$display_overall_pound);
	
	echo $display_t_euro_before_pound."<br />";
	echo $display_t_pound."<br />";
	echo $display_t_iw_euro_before_pound."<br />";
	echo $display_t_iw_pound."<br />";
	
	$total_cost	=	$display_t_euro_before_pound + $display_t_pound;
	
	echo $total_cost."<br />";
?>
<?php //$get_template->bottomHTML();$sql_command->close(); ?>