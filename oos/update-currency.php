<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);
	
function updateExchangerate($ffrom,$fto) {
	$exc = $sql_command->select("currency_conversion","currency_acro,currency_rate,currency_adjustment",
							"WHERE currency_id='".$fto."' 
							ORDER BY currency_acro ASC");
	$exrate = $sql_command->result($exc);

	if ($ffrom!=$exrate[0]) {
		$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1&s='.$ffrom.$exrate[0].'=X';
		$handle = fopen($url, 'r');
		
		if ($handle) {
			$results = fgetcsv($handle);	
			fclose($handle);
		}
		
		if ($results) {
			$exchrate = number_format($results[0]/$exrate[2],2);
			$sql_q = $sql_command->update("currency_conversion",
										  "currency_rate='".addslashes($results[0])."',
										  currency_erate='".addslashes($exchrate)."'",
										  "currency_id='".addslashes($fto)."'");
			$cols = "currency_id,
			action,
			user,
			comment";	
			$vals = "'".addslashes($fto)."',
			'Update',
			'System',
			'Rate change from ".$exrate[1]." to ".$results[0].".'";
			$sql_q = $sql_command->insert("currency_history",$cols,$vals);			
		}	
	}
	else {
		$sql_q = $sql_command->update("currency_conversion",
									  "currency_rate='".addslashes($results[0])."',
									  currency_erate='".addslashes($exchrate)."'",
									  "currency_id='".addslashes($fto)."'");
		$cols = "currency_id,
		action,
		user,
		comment";	
		$vals = "'".addslashes($fto)."',
		'Update',
		'System',
		'Rate change from ".$exrate[1]." to ".$results[0].".'";
		$sql_q = $sql_command->insert("currency_history",$cols,$vals);		
	}
}

$get_template = new main_template();
include("run_login.php");

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";


$cur = $sql_command->select("currency_conversion",
							"currency_id,
							currency_acro",
							"WHERE currency_default='Yes' 
							ORDER BY currency_acro ASC");
$curr = $sql_command->result($cur);

$fromid = $curr[0];
$from = $curr[1];

$cur = $sql_command->select("currency_conversion",
							"currency_id,
							currency_acro,
							currency_adjustment,
							currency_name,
							currency_rate,
							currency_status,
							timestamp,
							currency_erate,
							currency_symbol",
							"ORDER BY currency_acro ASC");
$currs = $sql_command->results($cur);

if ($_GET['action'] || $_POST['action']) {
	
	if ($_POST['action']=="Save Changes") {
		
		$cols = "currency_id,
				action,
				user,
				comment";
				
		
		if ($_POST['c_default']!=$curr[0]&&$_POST['c_default']>0) {
			$rowc_q = $sql_command->count_nrow("currency_conversion","currency_id","currency_id='".addslashes($_POST['c_default'])."'");
			
			if ($rowc_q>0) {
				$default_q = $sql_command->update("currency_conversion","currency_default='No'","currency_default='Yes'");
				$default_q = $sql_command->update("currency_conversion",
												  "currency_default='Yes'",
												  "currency_id='".addslashes($_POST['c_default'])."'");
				
				$dcur = $sql_command->select("currency_conversion","currency_acro","WHERE currency_default='Yes'");
				$dcurr = $sql_command->result($dcur);
				$ofrom = $from;
				$from = $dcurr[0];
				$vals = "'".addslashes($fromid)."',
						'Update',
						'".$the_username."',
						'Default currency changed from ".$ofrom." to ".$from.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);

				if(!$sql_q && !$default_q) {
					$errormsg .= "<div style=\"width:100%; float:left;\">
							<center><p>An Error occured when changing default currency, please try again.</p></center>
						<div>
						<div style=\"clear:left;\"></div>";
				}
				
				foreach ($currs as $c){ 
					$to = $c[0];
					updateExchangerate($from,$to);	
				} 
			}
		}

		foreach ($currs as $c) {
			$updatecount =0;
			$cid = $c[0];
			$cacro = $c[1];
			$csymbol = $c[8];
			$cname = $c[3];
			$cadjust = $c[2];
			$cstatus = $c[5];
			
			$rate = $c[4];

			$update_acro = ($_POST[$cid."-acro"]<=3) ? $_POST[$cid."-acro"] : $acro;
			$update_symbol = (strlen($_POST[$cid."-symbol"])<0) ? $csymbol : (strlen($_POST[$cid."-symbol"])<=3) ? $_POST[$cid."-symbol"] : $csymbol;
			$update_name = (strlen($_POST[$cid."-name"])<0) ? $cname : (strlen($_POST[$cid."-name"])<=35) ? $_POST[$cid."-name"] : $cname;
			$update_adjust = ($_POST[$cid."-adjust"]>=0) ? $_POST[$cid."-adjust"] : $cadjust;
			$update_status = ($_POST[$cid."-status"]=="Active") ? "Active" : "Cancelled";

			if ($update_acro!=$cacro) {
				$acro_q = $sql_command->update("currency_conversion",
											   "currency_acro='".$update_acro."'",
											   "currency_id='".addslashes($cid)."'");
				
				$vals = "'".addslashes($c[0])."',
					'Name Change',
					'".$the_username."',
					'Acronym changed from ".$cacro." to ".$update_acro.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);

			}
			if ($update_symbol!=$csymbol) {
				$symbol_q = $sql_command->update("currency_conversion",
											   "currency_symbol='".$update_symbol."'",
											   "currency_id='".addslashes($cid)."'");
				$vals = "'".addslashes($c[0])."',
					'Update',
					'".$the_username."',
					'Symbol changed from ".$csymbol." to ".$update_symbol.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);

			}
			if ($update_name!=$cname) {
				$name_q = $sql_command->update("currency_conversion",
											   "currency_name='".$update_name."'",
											   "currency_id='".addslashes($cid)."'");
				$vals = "'".addslashes($c[0])."',
					'Name Change',
					'".$the_username."',
					'Name changed from ".$cname." to ".$update_name.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);
		
			}
			if ($update_adjust!=$cadjust) {
				$adjust_q = $sql_command->update("currency_conversion",
											   "currency_adjustment='".$update_adjust."'",
											   "currency_id='".addslashes($cid)."'");
				$vals = "'".addslashes($c[0])."',
					'Rate Change',
					'".$the_username."',
					'Adjustment Rate changed from ".$cadjust." to ".$update_adjust.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);
				
				if($from!=$cacro) {
					updateExchangerate($from,$to);	
				}
				else {
					$sql_q = $sql_command->update("currency_conversion","currency_rate=1,currency_erate=1","currency_id='".addslashes($cid)."'");	
				}
			}
			if ($update_status!=$cstatus) {
				$status_q = $sql_command->update("currency_conversion",
											   "currency_status='".$update_status."'",
											   "currency_id='".addslashes($cid)."'");
				$vals = "'".addslashes($c[0])."',
					'Status Change',
					'".$the_username."',
					'Status changed from ".$cstatus." to ".$update_status.".'";
				$sql_q = $sql_command->insert("currency_history",$cols,$vals);
			
			}
			
			$updatecount += ($update_acro == $_POST[$cid."-acro"]) ? 0 : 1;
			$updatecount += ($update_symbol == $_POST[$cid."-symbol"]) ? 0 : 1;
			$updatecount += ($update_name == $_POST[$cid."-name"]) ? 0 : 1;
			$updatecount += ($update_adjust == $_POST[$cid."-adjust"]) ? 0 : 1;
			$updatecount += ($update_status == $_POST[$cid."-status"]) ? 0 : 1;

			if ($updatecount>0) {
				$errormsg .= "<div style=\"width:100%; float:left;\">
							<center><p>An Error occured when changing some of the currency values, these were reverted back please check and try again.</p></center>
						<div>
						<div style=\"clear:left;\"></div>";
			}

		
		}header("Location: $site_url/oos/update-currency.php?action=view"); 

		
	}
	if ($_POST['action']=="Add Currency") {
		header("Location: $site_url/oos/update-currency.php?action=view");
	}
	
	$history_q = $sql_command->select("currency_history",
								"currency_history.currency_id,
								currency_history.action,
								currency_history.user,
								currency_history.comment,
								currency_history.timestamp,
								currency_conversion.currency_acro",
								"LEFT OUTER JOIN currency_conversion
								ON currency_conversion.currency_id = currency_history.currency_id
								ORDER BY timestamp DESC 
								LIMIT 1500");
	$history = $sql_command->results($history_q);

	$get_template->topHTML();
	
	$lista = "";
	$listc = "";
	foreach ($currs as $c) {
		list($date,$time) = explode(" ",$c[6]);
		list($yy,$mm,$dd) = explode("-",$date);
		$timestamp = $dd."/".$mm."/".$yy."<br />".$time;
		$default = ($c[1]==$from) ?  "Yes":  "No";
		$status = ($c[5]=="Active") ? 
			"<input type=\"radio\" name=\"".$c[0]."-status\" value=\"Active\" checked /> Active<br />
			<input type=\"radio\" name=\"".$c[0]."-status\" value=\"Cancelled\" /> Cancelled " :
			"<input type=\"radio\" name=\"".$c[0]."-status\" value=\"Active\" /> Active<br />
			<input type=\"radio\" name=\"".$c[0]."-status\" value=\"Cancelled\" checked /> Cancelled" ;

		$styling = ($c[1]==$from) ?
			 "style=\"background:#C93; color:#FFF; border-bottom:1px solid #000;\"":
			 "style=\"background:#fff; color:#000; border-bottom:1px solid #000;\"";		 
		$in_style = ($c[1]==$from) ?
			"border:0; background:0; color:#fff;":
			"border:0; background:0;";
		$cvalues = "<tr>
					<td $styling>
						<input style=\"width: 30px; $in_style\" type=\"text\" name=\"currencies[]\" value=\"".$c[0]."\" >
					</td>
					<td $styling>
						<input style=\"width: 50px; $in_style\" type=\"text\" name=\"".$c[0]."-acro\" value=\"".$c[1]."\" />
					</td>
					<td $styling>
						<input style=\"width: 20px; $in_style\" type=\"text\" name=\"".$c[0]."-symbol\" value=\"".$c[8]."\" />
					</td>
					<td $styling>
						<input style=\"width: 150px; $in_style\" type=\"text\" name=\"".$c[0]."-name\" value=\"".$c[3]."\" />
					</td>
					
					<td $styling>".$c[4]."</td>
					<td $styling>
						<input style=\"width: 50px; $in_style\" type=\"text\" name=\"".$c[0]."-adjust\" value=\"".$c[2]."\" />
					</td>
					<td $styling>
						<input style=\"width: 50px; $in_style\" type=\"text\" name=\"".$c[0]."-effect\" value=\"".$c[7]."\" />
					</td>
					<td $styling>".$status."</td>
					<td $styling>".$timestamp."</td>
				 </tr>";

		if ($c[5]=="Active") {
			$lista .= $cvalues;
		}
		else {
			$listc .= $cvalues;
		}
		if ($c[1]==$from) { $select .= "<option value=\"".$c[0]."\" selected>".$c[3]."</option>"; }
		else { $select .= "<option value=\"".$c[0]."\">".$c[3]."</option>"; }
		
	}
	
	$h_item = "<div class=\"header_actions\">
						<div style=\"float:left; width:60px; margin:5px 20px 5px 2px;\"><h3>Currency</h3></div>
						<div style=\"float:left; width:120px; margin:5px 7px 5px 2px;\"><h3>Action</h3></div>
						<div style=\"float:left; width:60px; margin:5px 7px 5px 2px;\"><h3>User</h3></div>
						<div style=\"float:left; width:270px; margin:5px 7px 5px 2px;\"><h3>Comment</h3></div>
						<div style=\"float:left; width:125px; margin:5px 7px 5px 2px;\"><h3>Date/Time</h3></div>
					</div>
					<div style=\"clear:left;\"></div>
					<div style=\"float:left; width:100%; max-height:400px; overflow:auto; overflow-x: hidden;\">";
	$h_i = 0;
	$actions = array();
	foreach($history as $h) {
		$currency_id = $h[5];
		$action = $h[1];
		$user = $h[2];
		$comment = $h[3];
		
		list($date,$time) = explode(" ",$h[4]);
		list($yy,$mm,$dd) = explode("-",$date);
		$timestamp = $dd."/".$mm."/".$yy." ".$time;

		$actions[$i] = $action;
		$h_item .= "<div class=\"".str_replace(" ","_",$action)." actions\">
						<div style=\"float:left; width:60px; margin:5px 20px 5px 2px;\">".$currency_id."</div>
						<div style=\"float:left; width:120px; margin:5px 7px 5px 2px;\">".$action."</div>
						<div style=\"float:left; width:60px; margin:5px 7px 5px 2px;\">".$user."</div>
						<div style=\"float:left; width:270px; margin:5px 7px 5px 2px;\">".$comment."</div>
						<div style=\"float:left; width:125px; margin:5px 2px 5px 2px;\">".$timestamp."</div>
					</div>
					<div style=\"clear:left;\"></div>";
		$h_i++;
	}
	
	$headers = 	"<th scope=\"col\">ID</th>
				<th scope=\"col\">Acro</th>
				<th scope=\"col\">Symbol</th>
				<th scope=\"col\">Name</th>
				<th scope=\"col\">Exch Rate</th>
				<th scope=\"col\">Adjustment</th>
				<th scope=\"col\">Effect Rate</th>
				<th scope=\"col\">Status</th>
				<th scope=\"col\">Updated</th>";
	
	$output = "<h1>Currency Exchange Rates</h1>
				<form action=\"update-currency.php\" method=\"post\" name=\"currency\">
				<div style=\"float:left; width:160px; margin:5px;\"><b>Currency Default:</b></div>
				<div style=\"float:left; margin:5px;\">
					<select name=\"c_default\" style=\"width:200px;\">
					".$select."
					</select>
				</div>
				<div style=\"float:right; margin:5px;\">
					<input type=\"submit\" name=\"action\" value=\"Save Changes\" />
				</div>
				<div style=\"clear:left;\"></div>
				<br /><h2>Active</h2>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				  <tr>
					".$headers."
				  </tr>
				  ".$lista."
				</table><br />
				<br /><h2>Cancelled</h2>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				  <tr>
					".$headers."
				  </tr>
				  ".$listc."				  
				</table>
				<div style=\"float:right; margin:5px;\">
					<input type=\"submit\" name=\"action\" value=\"Save Changes\" />
				</div>
				<div style=\"clear:left;\"></div>
				<br /><br /><br />
				<h2>Add Currency</h2>";	
	$output .= "<div style=\"float:left; width:160px; margin:5px;\">
					<b>Currency Acronym</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"text\" name=\"acronym\" style=\"width:200px;\" value=\"\"/>
                </div>
				<div style=\"clear:left;\"></div>
				
				
				<div style=\"float:left; width:160px; margin:5px;\">
					<b>Currency Symbol</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"text\" name=\"symbol\" style=\"width:200px;\" value=\"\"/>
                </div>
				<div style=\"clear:left;\"></div>
								
				<div style=\"float:left; width:160px; margin:5px;\">
					<b>Currency Name</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"text\" name=\"name\" style=\"width:200px;\" value=\"\"/>
                </div>
				<div style=\"clear:left;\"></div>
				
				<div style=\"float:left; width:160px; margin:5px;\">
					<b>Adjustment</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"text\" name=\"adjustment\" style=\"width:200px;\" value=\"1.04\"/>
                </div>
				<div style=\"clear:left;\"></div>
				
				<div style=\"float:left; width:160px; margin:5px;\">
					<b>Status</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"radio\" name=\"status\" value=\"Active\" checked /> Active<br />
					<input type=\"radio\" name=\"status\" value=\"Cancelled\" /> Cancelled
                </div>
				<div style=\"clear:left;\"></div>
				
				<div style=\"float:left; width:160px; margin:5px;\">
					<b>Set as Default</b>
				</div>
				<div style=\"float:left; margin:5px;\">
					<input type=\"radio\" name=\"default\" value=\"Yes\" /> Yes<br />
					<input type=\"radio\" name=\"default\" value=\"No\" checked/> No
                </div>
				<div style=\"clear:left;\"></div>
				<div style=\"float:right; margin:5px;\">
					<input type=\"submit\" name=\"action\" value=\"Add Currency\" />
				</div>
				<div style=\"clear:left;\"></div>";
				
	if ($h_i>0) { $output .= "<br /><br /><br />
				<h2>History</h2>
				".$h_item."</div>";
}
				
				
echo $output;

//print_r($currs);

$get_template->bottomHTML();
$sql_command->close();
}

else {
	foreach ($currs as $c){
		$to = $c[1];
		if($from!=$to) {
			updateExchangerate($from,$to);	
		}
		else {
			$sql_q = $sql_command->update("currency_conversion","currency_rate=1,currency_erate=1","currency_id='".addslashes($c[0])."'");	
		}
	}
}

?>