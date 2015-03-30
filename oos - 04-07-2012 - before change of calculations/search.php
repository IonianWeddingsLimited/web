<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$get_template = new main_template();
include("run_login.php");

// Get Templates
$get_template = new oos_template();


$meta_title = "Admin";
$meta_description = "";
$meta_keywords = "";




if($_GET["action"] == "keyword") {
	
	
if($_GET["key_type"] == "iwcuid") {
$where = "WHERE $database_clients.iwcuid like '%".addslashes($_GET["keyword"])."%' ";
$orderby = "ORDER BY $database_clients.iwcuid";
} elseif($_GET["key_type"] == "firstname") {
$where = "WHERE $database_clients.firstname like '%".addslashes($_GET["keyword"])."%' ";
$orderby = "ORDER BY $database_clients.firstname";
} elseif($_GET["key_type"] == "lastname") {
$where = "WHERE $database_clients.lastname like '%".addslashes($_GET["keyword"])."%' ";
$orderby = "ORDER BY $database_clients.lastname";
}
	
	
$result = $sql_command->select("$database_clients,$database_navigation,$database_order_details","$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.wedding_date,
							   $database_clients.iwcuid,
							   $database_navigation.page_name,
							   $database_order_details.id","$where and $database_clients.destination=$database_navigation.id and 
							   $database_clients.id=$database_order_details.client_id $orderby");
$row = $sql_command->results($result);

foreach($row as $record) {
$dateline = "";
if($record[4]) { $dateline = date("d-m-Y",$record[4]); }
$list .= "

<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[5])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[1])." ".stripslashes($record[2])." ".stripslashes($record[3])."</div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[6])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($dateline)."</div>
<div style=\"clear:left;\"></div>";
}



$get_template->topHTML();
?>
<h1>Search Results</h1>

<p><strong>Keyword</strong> <?php echo $_GET["keyword"]; ?></p>

<?php if($list) { ?>

<div style="float:left; width:90px; margin:5px;"><strong>Order ID</strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Name</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Wedding Date</strong></div>

<div style="clear:left;"></div>
<?php echo $list; ?>
<?php } else { ?>
<p>No results found</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_GET["action"] == "wedding_date") {


$result = $sql_command->select("$database_clients,$database_navigation,$database_order_details","$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.wedding_date,
							   $database_clients.iwcuid,
							   $database_navigation.page_name,
							   $database_order_details.id","WHERE $database_clients.destination=$database_navigation.id and 
							   $database_clients.id=$database_order_details.client_id");
$row = $sql_command->results($result);


foreach($row as $record) {
if($record[4]) {
$dateline = date("d-m-Y",$record[4]);
if($dateline == $_GET["wedding_date"]) {
$list .= "
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[5])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[1])." ".stripslashes($record[2])." ".stripslashes($record[3])."</div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[6])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($dateline)."</div>
<div style=\"clear:left;\"></div>";
}
}
}


$get_template->topHTML();
?>
<h1>Search Results</h1>

<p><strong>Wedding Date</strong> <?php echo $_GET["wedding_date"]; ?></p>

<?php if($list) { ?>
<div style="float:left; width:90px; margin:5px;"><strong>Order ID</strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Name</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Wedding Date</strong></div>
<?php echo $list; ?>
<?php } else { ?>
<p>No results found</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();
	
	
} elseif($_GET["action"] == "destination") {


$result = $sql_command->select("$database_clients,$database_navigation,$database_order_details","$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.wedding_date,
							   $database_clients.iwcuid,
							   $database_navigation.page_name,
							   $database_order_details.id","WHERE $database_clients.destination='".addslashes($_GET["destination_id"])."' and  
							   $database_clients.id=$database_order_details.client_id and 
							   $database_clients.destination=$database_navigation.id");
$row = $sql_command->results($result);


foreach($row as $record) {
$dateline = "";
if($record[4]) { $dateline = date("d-m-Y",$record[4]); }
$list .= "
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[5])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[1])." ".stripslashes($record[2])." ".stripslashes($record[3])."</div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[6])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($dateline)."</div>
<div style=\"clear:left;\"></div>";
}



$level1_result = $sql_command->select($database_navigation,"id,page_name","WHERE id='".addslashes($_GET["destination_id"])."'");
$level1_row = $sql_command->result($level1_result);

$get_template->topHTML();
?>
<h1>Search Results</h1>

<p><strong>Destination</strong> <?php echo stripslashes($level1_row[1]); ?></p>

<?php if($list) { ?>
<div style="float:left; width:90px; margin:5px;"><strong>Order ID</strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Name</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Wedding Date</strong></div>
<?php echo $list; ?>
<?php } else { ?>
<p>No results found</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();
	
} elseif($_GET["action"] == "orderid") {



$result = $sql_command->select("$database_order_details,$database_clients,$database_navigation","$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.wedding_date,
							   $database_clients.iwcuid,
							   $database_navigation.page_name,
							   $database_order_details.id","WHERE $database_order_details.id='".addslashes($_GET["order_id"])."' and 
							   $database_order_details.client_id=$database_clients.id and 
							   $database_clients.destination=$database_navigation.id");
$row = $sql_command->results($result);



foreach($row as $record) {
$dateline = "";
if($record[4]) { $dateline = date("d-m-Y",$record[4]); }
$list .= "
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[7])."</a></div>
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[5])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[1])." ".stripslashes($record[2])." ".stripslashes($record[3])."</div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[6])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($dateline)."</div>
<div style=\"clear:left;\"></div>";
}


$get_template->topHTML();
?>
<h1>Search Results</h1>

<p><strong>Order ID</strong> <?php echo $_GET["order_id"]; ?></p>

<?php if($list) { ?>
<div style="float:left; width:90px; margin:5px;"><strong>Order ID</strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Name</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Wedding Date</strong></div>
<?php echo $list; ?>
<?php } else { ?>
<p>No results found</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();		
		
} elseif($_GET["action"] == "supplier") {



$result = $sql_command->select("$database_order_details,$database_clients,$database_navigation,$database_supplier_details,$database_supplier_invoices","$database_clients.id,
							   $database_clients.title,
							   $database_clients.firstname,
							   $database_clients.lastname,
							   $database_clients.wedding_date,
							   $database_clients.iwcuid,
							   $database_navigation.page_name,
							   $database_supplier_details.id,
							   $database_supplier_details.company_name,
							   $database_order_details.id","WHERE $database_supplier_details.company_name like '%".addslashes($_GET["supplier_name"])."%' and 
							   $database_supplier_invoices.supplier_id=$database_supplier_details.id and 
							   $database_supplier_invoices.order_id=$database_order_details.id and
							   $database_order_details.client_id=$database_clients.id and 
							   $database_clients.destination=$database_navigation.id");
$row = $sql_command->results($result);



foreach($row as $record) {
$dateline = "";
if($record[4]) { $dateline = date("d-m-Y",$record[4]); }
$list .= "
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[9])."</a></div>
<div style=\"float:left; width:90px; margin:5px;\"><a href=\"$site_url/oos/manage-client.php?a=history&id=".$record[0]."\">".stripslashes($record[5])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\"><a href=\"$site_url/oos/manage-supplier-po.php?id=".$record[7]."\">".stripslashes($record[8])."</a></div>
<div style=\"float:left; width:200px; margin:5px;\">".stripslashes($record[6])."</div>
<div style=\"float:left; width:80px; margin:5px;\">".stripslashes($dateline)."</div>
<div style=\"clear:left;\"></div>";
}


$get_template->topHTML();
?>
<h1>Search Results</h1>

<p><strong>Suppier Name</strong> <?php echo $_GET["supplier_name"]; ?></p>

<?php if($list) { ?>
<div style="float:left; width:90px; margin:5px;"><strong>Order ID</strong></div>
<div style="float:left; width:90px; margin:5px;"><strong>IWCUID</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Supplier</strong></div>
<div style="float:left; width:200px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left; width:80px; margin:5px;"><strong>Wedding Date</strong></div>
<?php echo $list; ?>
<?php } else { ?>
<p>No results found</p>
<?php } ?>
<?
$get_template->bottomHTML();
$sql_command->close();		
	
} else {



$add_header = "<script language=\"JavaScript\" src=\"$site_url/js/calendar_eu.js\"></script>
<link rel=\"stylesheet\" href=\"$site_url/css/calendar.css\">";


$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}



$get_template->topHTML();
?>
<h1>Search Clients</h1>

<form action="<?php echo $site_url; ?>/oos/search.php" method="get" >
<input type="hidden" name="action" value="orderid">
<div style="float:left; width:100px; margin:5px;"><strong>Order Id</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="order_id" style="width:100px;"/></div>
<div style="clear:left;"></div>
<input type="submit" name="name" value="Search" />
</form>

<p><hr /></p>

<form action="<?php echo $site_url; ?>/oos/search.php" method="get">
<input type="hidden" name="action" value="keyword">
<div style="float:left; width:100px; margin:5px;"><strong>Keyword</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="keyword" style="width:200px;"/>
</div>
<div style="clear:left;"></div>
<div style="float:left; width:100px; margin:5px;"><strong>Search In</strong></div>
<div style="float:left;  margin:5px;"><select name="key_type">
<option value="iwcuid">IWCUID</option>
<option value="firstname">First name</option>
<option value="lastname">Last Name</option>
</select>
</div>
<div style="clear:left;"></div>
<input type="submit" name="name" value="Search" />
</form>
<p><hr /></p>

<form action="<?php echo $site_url; ?>/oos/search.php" method="get">
<input type="hidden" name="action" value="supplier">
<div style="float:left; width:100px; margin:5px;"><strong>Supplier Name</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="supplier_name" style="width:200px;"/>
</div>
<div style="clear:left;"></div>
<input type="submit" name="name" value="Search" />
</form>
<p><hr /></p>

<form action="<?php echo $site_url; ?>/oos/search.php" method="get" name="search">
<input type="hidden" name="action" value="wedding_date">
<div style="float:left; width:100px; margin:5px;"><strong>Wedding Date</strong></div>
<div style="float:left;  margin:5px;"><input type="text" name="wedding_date" id="wedding_date" value="" style="width:100px;"/> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'search',
		// input name
		'controlname': 'wedding_date'
	});

	</script>
</div>
<div style="clear:left;"></div>
<input type="submit" name="name" value="Search" />
</form>

<p><hr /></p>

<form action="<?php echo $site_url; ?>/oos/search.php" method="get">
<input type="hidden" name="action" value="destination">
<div style="float:left; width:100px; margin:5px;"><strong>Destination</strong></div>
<div style="float:left;  margin:5px;"><select name="destination_id" size="20" style="width:500px;">
<?php echo $nav_list; ?>
</select>
</div>
<div style="clear:left;"></div>
<input type="submit" name="name" value="Search" />
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

}


?>