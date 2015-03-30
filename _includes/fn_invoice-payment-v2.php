<?php
$getvat         = "20.00";

$result         = $sql_command->select("$database_customer_invoices,
									   $database_order_details,
									   $database_clients",
									   "$database_customer_invoices.order_id,
									   $database_customer_invoices.exchange_rate",
									   "WHERE $database_customer_invoices.id='" . $invoiceno . "'
									   AND $database_customer_invoices.order_id=$database_order_details.id 
									   AND $database_order_details.client_id=$database_clients.id");
$invrecord      = $sql_command->result($result);

$invoice_result = $sql_command->select($database_invoice_history,
									   "name,
									   qty,
									   cost,
									   iw_cost,
									   local_tax_percent,
									   discount_at,
									   discount_percent,
									   currency,
									   exchange_rate,
									   timestamp,
									   d_value,
									   d_type,
									   code,
									   d_,
									   item_type",
									   "WHERE invoice_id='" . $invoiceno . "' 
									   and order_id='" . addslashes($invrecord[0]) . "' 
									   and currency='Euro' 
									   and item_type='Package'");
$invoice_row    = $sql_command->results($invoice_result);

$package_exists = "No";
$total_invoice  = 0;
$the_cost = $total_iw_cost = $the_cost_before = $total_iw_cost_before = $total_payment_pound = $total_payment_pound_before = $total_iw_cost_before = $total_payment_euro = $total_payment_euro_before = $line_iw_euro = $total_gbp = $outstanding_pounds  = $minum_deposit = 0;

$brk = "<br />";

//$exchange_rate	=	1.16;

//Creates a total of Euro items

foreach ($invoice_row as $invoice_record) {
		$testcode	=	"test1";
		$CC1		=	$invrecord[1];
		$package_exists = "Yes";
		$total_invoice++;
		$adjustment_iw = $invoice_record[3];
		$adjustment    = $invoice_record[2];
		// Work out adjustments
		if ($invoice_record[1] > 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		} elseif ($invoice_record[1] < 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		}
		if ($invoice_record[10] == 0 and $invoice_record[13] == "Net") {
				$adjustment_iw     = $invoice_record[2];
				$invoice_record[3] = $invoice_record[2];
		}
		$the_cost				=	$invoice_record[1] * $adjustment;
		$total_iw_cost			=	$invoice_record[1] * $adjustment_iw;
		$the_cost_before		=	$invoice_record[1] * $invoice_record[2];
		$total_iw_cost_before	=	$invoice_record[1] * $invoice_record[3];
		$total_payment_euro		+=	$total_iw_cost;
		$total_payment_euro_before += $total_iw_cost_before;
		if ($total_iw_cost_before > $total_iw_cost) {
				$line_iw_euro = $total_iw_cost_before;
		} else {
				$line_iw_euro = $total_iw_cost;
		}
}

$invoice_result  = $sql_command->select($database_invoice_history, "name, qty, cost, iw_cost, local_tax_percent, discount_at, discount_percent, currency, exchange_rate, timestamp, d_value, d_type, code, d_, item_type", "WHERE invoice_id='" . $invoiceno . "' and order_id='" . addslashes($invrecord[0]) . "' and currency='Pound' and item_type='Package'");
$invoice_row     = $sql_command->results($invoice_result);

$show_additional = "No";
$total_payment   = 0;

foreach ($invoice_row as $invoice_record) {
		$testcode	=	"test2";
		$CC2		=	$invrecord[1];
		$package_exists  = "Yes";
		$show_additional = "Yes";
		$adjustment_iw   = $invoice_record[3];
		$adjustment      = $invoice_record[2];
		// Work out adjustments
		if ($invoice_record[1] > 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		} elseif ($invoice_record[1] < 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		}
		if ($invoice_record[10] == 0 and $invoice_record[13] == "Net") {
				$adjustment_iw     = $invoice_record[2];
				$invoice_record[3] = $invoice_record[2];
		}
		$the_cost             = $invoice_record[1] * $adjustment;
		$total_iw_cost        = $invoice_record[1] * $adjustment_iw;
		$the_cost_before      = $invoice_record[1] * $invoice_record[2];
		$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
		$total_payment_pound += $total_iw_cost;
		$total_payment_pound_before += $total_iw_cost_before;
		if ($total_iw_cost_before > $total_iw_cost) {
				$euro_amount_discount += $total_iw_cost_before - $total_iw_cost;
				$line_iw_euro = $total_iw_cost_before;
		} else {
				$line_iw_euro = $total_iw_cost;
		}
		
}

$invoice_result = $sql_command->select($database_invoice_history, "name, qty, cost, iw_cost, local_tax_percent, discount_at, discount_percent, currency, exchange_rate, timestamp, d_value, d_type, code, d_, item_type", "WHERE invoice_id='" . $invoiceno . "' and order_id='" . addslashes($invrecord[0]) . "' and currency='Euro' and item_type!='Package'");
$invoice_row    = $sql_command->results($invoice_result);

foreach ($invoice_row as $invoice_record) {
		$testcode	=	"test3";
		$CC3		=	$invrecord[1];
		$total_invoice++;
		$adjustment_iw = $invoice_record[3];
		$adjustment    = $invoice_record[2];
		// Work out adjustments
		if ($invoice_record[1] > 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		} elseif ($invoice_record[1] < 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		}
		if ($invoice_record[10] == 0 and $invoice_record[13] == "Net") {
				$adjustment_iw     = $invoice_record[2];
				$invoice_record[3] = $invoice_record[2];
		}
		$the_cost				=	$invoice_record[1] * $adjustment;
		
		$total_iw_cost			=	$invoice_record[1] * $adjustment_iw;
		$the_cost_before		=	$invoice_record[1] * $invoice_record[2];
		
		$total_iw_cost_before	=	$invoice_record[1] * $invoice_record[3];
		
		$total_payment_euro		+=	$total_iw_cost;
		$total_payment_euro_before += $total_iw_cost_before;
		
		if ($total_iw_cost_before > $total_iw_cost) {
				$line_iw_euro = $total_iw_cost_before;
		} else {
				$line_iw_euro = $total_iw_cost;
		}
}

if ($total_invoice > 0) {
		$outstanding_euros        = $total_payment_euro;
		$outstanding_euros_before = $total_payment_euro_before;
		$euro_discount            = $outstanding_euros_before - $outstanding_euros;
		$minum_deposit            = 0;
		$minum_deposit2           = 0;
		if ($package_exists == "Yes") {
				$invoice_result = $sql_command->select($database_invoice_history, "name, qty, cost, iw_cost, currency, timestamp, exchange_rate", "WHERE order_id='" . addslashes($invrecord[0]) . "' and item_type='Deposit' and status='Paid' and currency='Euro'");
				$invoice_record = $sql_command->result($invoice_result);
				if ($invoice_record[0]) {
						$total_deposit_paid = eregi_replace(",", "", number_format($invoice_record[3] / $invoice_record[6], 2));
						$minum_deposit      = $total_deposit_paid;
						$minum_deposit2     = $invoice_record[3];
				}
		}
}

//// There is an issue here with the currency conversion using the variable $exchange_rate.
//// When set to 1, it returns the Euro amount correctly.
//// When I hard code the value to the correct currency rate for that invoice, it id correct.
//// Yet when using the dynamic value ($exchange_rate) it is inaccurate.

$outstanding_pounds			=	$outstanding_euros / $invrecord[1];
$outstanding_pounds_before	=	$outstanding_euros_before / $invrecord[1];


// Unsure of Purpose -- Think it might be intended to get any extra codes or discounts associated with pound but obviously all euros so looks abandoned.

$invoice_result	=	$sql_command->select($database_invoice_history, "name, qty, cost, iw_cost, local_tax_percent, discount_at, discount_percent, currency, exchange_rate, timestamp, d_value, d_type, code, d_, item_type", "WHERE invoice_id='" . $invoiceno . "' and order_id='" . addslashes($invrecord[0]) . "' and currency='Pound' and item_type!='Package'");
$invoice_row				=	$sql_command->results($invoice_result);

foreach ($invoice_row as $invoice_record) {
		$testcode	=	"test4";
		$CC4		=	$invrecord[1];
		$show_additional = "Yes";
}

$checktotal      = $outstanding_pounds - $minum_deposit;
$show_additional = "No";
$total_payment   = 0;

//Creates a list of GBP items

foreach ($invoice_row as $invoice_record) {
		$testcode	=	"test5";
		$CC5		=	$invrecord[1];
		$show_additional = "Yes";
		$adjustment_iw   = $invoice_record[3];
		$adjustment      = $invoice_record[2];
		// Work out adjustments
		if ($invoice_record[1] > 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
							$adjustment_iw = $invoice_record[3] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] + $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] + ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		} elseif ($invoice_record[1] < 0) {
				if ($invoice_record[13] == "Gross") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment_iw = $invoice_record[3] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[3] / 100);
								$adjustment_iw = $invoice_record[3] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Net") {
						if ($invoice_record[11] == "Amount" and $invoice_record[10] != 0) {
								$adjustment = $invoice_record[2] - $invoice_record[10];
						} elseif ($invoice_record[11] == "Percent" and $invoice_record[10] != 0) {
								$percent_value = ($invoice_record[2] / 100);
								$adjustment    = $invoice_record[2] - ($percent_value * $invoice_record[10]);
						}
				} elseif ($invoice_record[13] == "Absolute Gross") {
						$adjustment_iw     = $invoice_record[10];
						$invoice_record[3] = $invoice_record[10];
				} elseif ($invoice_record[13] == "Absolute Net") {
						$adjustment        = $invoice_record[10];
						$invoice_record[2] = $invoice_record[10];
				}
		}
		if ($invoice_record[10] == 0 and $invoice_record[13] == "Net") {
				$adjustment_iw     = $invoice_record[2];
				$invoice_record[3] = $invoice_record[2];
		}
		$the_cost             = $invoice_record[1] * $adjustment;
		$total_iw_cost        = $invoice_record[1] * $adjustment_iw;
		$the_cost_before      = $invoice_record[1] * $invoice_record[2];
		$total_iw_cost_before = $invoice_record[1] * $invoice_record[3];
		$total_payment_pound += $total_iw_cost;
		$total_payment_pound_before += $total_iw_cost_before;
		if ($total_iw_cost_before > $total_iw_cost) {
				$amount_discount += $total_iw_cost_before - $total_iw_cost;
				$line_iw_euro = $total_iw_cost_before;
		} else {
				$line_iw_euro = $total_iw_cost;
		}
		$total_additional += $line_iw_euro;

}

if ($package_exists == "Yes") {
		$invoice_result = $sql_command->select($database_invoice_history, "name, qty, cost, iw_cost, currency, timestamp, exchange_rate", "WHERE order_id='" . addslashes($invrecord[0]) . "' and item_type='Deposit' and status='Paid' and currency='Pound'");
		$invoice_record = $sql_command->result($invoice_result);
		if ($invoice_record[0]) {
				$total_deposit_paid = eregi_replace(",", "", $invoice_record[3]);
				// There is an issue here with the deposit paid.
				// It seems to add the deposit to the next invoice once removed from the first.
				$minum_deposit      = $total_deposit_paid;
		}
}

/*		This isn't used
$discount_amount_calc = ($outstanding_pounds_before - $outstanding_pounds) + ($total_payment_pound_before - $total_payment_pound);


if ($amount_discount != 0) {
		$total_gbp = $outstanding_pounds_before + $total_payment_pound_before - $minum_deposit;
} else {
		$discount_amount_calc = 0;
}
*/
$total_gbp = $outstanding_pounds + $total_payment_pound - $minum_deposit;
?>