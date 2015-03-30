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



if($_POST["action"] == "Add") {
	
$_POST["local_tax"] = ereg_replace("[^0-9\.]", "", $_POST["local_tax"]);
$_POST["apply_discount"] = ereg_replace("[^0-9\.]", "", $_POST["apply_discount"]);
$_POST["discount"] = ereg_replace("[^0-9\.]", "", $_POST["discount"]);


if(!$_POST["iw_menu_name"]) { $error .= "Missing IW Menu Name<br>"; }

if(!$_POST["menu_name_1"]) { $error .= "Missing Menu Name<br>"; }
if(!$_POST["cost_1"]) { $error .= "Missing Net<br>"; }
if(!$_POST["iw_cost_1"]) { $error .= "Missing Gross<br>"; }

if($error) {
$get_template->topHTML();
$get_template->errorHTML("Add Menu","Oops!","$error","Link","oos/add-menu.php");
$get_template->bottomHTML();
$sql_command->close();
}

$cols = "supplier_id,venue_id,island_id,menu_name_iw,local_tax_percent,discount_at,discount_percent,timestamp,deleted";

$values = "'".addslashes($_POST["supplier_id"])."',
'".addslashes($_POST["venue_id"])."',
'".addslashes($_POST["island_id"])."',
'".addslashes($_POST["iw_menu_name"])."',
'".addslashes($_POST["local_tax"])."',
'".addslashes($_POST["apply_discount"])."',
'".addslashes($_POST["discount"])."',
'$time',
'No'";

$sql_command->insert($database_menus,$cols,$values);
$maxid = $sql_command->maxid($database_menus,"id");


$cols = "menu_id,menu_name,cost,cost_iw,currency,note,timestamp,code";

$current_row = 1;
$end_value = $_POST["theValue"] + 1;

while($current_row < $end_value) {
$menu_name = "menu_name_".$current_row;
$cost = "cost_".$current_row;
$iw_cost = "iw_cost_".$current_row;
$currency = "currency_".$current_row;
$note = "note_".$current_row;

if($_POST[$menu_name]) { 
$values = "'".$maxid."',
'".addslashes($_POST[$menu_name])."',
'".addslashes($_POST[$cost])."',
'".addslashes($_POST[$iw_cost])."',
'".addslashes($_POST[$currency])."',
'".addslashes($_POST[$note])."',
'$time',
''";
$sql_command->insert($database_menu_options,$cols,$values);		
$maxid2 = $sql_command->maxid($database_menu_options,"id");

$totalcharacters = strlen($_POST[$menu_name]);
$middleend = $totalcharacters / 2;

$_POST[$menu_name] = str_replace("<p>", "", $_POST[$menu_name]);
$_POST[$menu_name] = str_replace("</p>", "", $_POST[$menu_name]);
$_POST[$menu_name] = ereg_replace("[^A-Za-z]", "", $_POST[$menu_name]);

$totalcharacters = strlen($_POST[$menu_name]);
$middleend = $totalcharacters / 2;

$first = $_POST[$menu_name][0];
$middle = $_POST[$menu_name][$middleend];
$last = $_POST[$menu_name][$totalcharacters-1];

$code =  $maxid2 . strtoupper($first) . strtoupper($middle) . strtoupper($last) . "M";

$sql_command->update($database_menu_options,"code='".addslashes($code)."'","id='".$maxid2."'");

}

$current_row++;
}


$sql_command->insert($database_client_historyinfo,"client_id,user_id,comment,timestamp,package_id,island_id","'','".$login_record[1]."','Menu Added (".addslashes($_POST["iw_menu_name"]).")','".$time."','','".addslashes($_POST["island_id"])."'");

$get_template->topHTML();
?>
<h1>Menu Added</h1>

<p>The menu has now been added</p>

<p><input type="button" name="" value="Back"  onclick="window.location='<?php echo $site_url; ?>/oos/add-menu.php'"></p>
<?
$get_template->bottomHTML();
$sql_command->close();

} elseif($_POST["action"] == "Continue") {
	

$supplier_result = $sql_command->select($database_supplier_details,"id,company_name","WHERE deleted='No' ORDER BY company_name");
$supplier_row = $sql_command->results($supplier_result);

foreach($supplier_row as $supplier_record) {
$supplier_list .= "<option value=\"".stripslashes($supplier_record[0])."\">".stripslashes($supplier_record[1])."</option>";
}

$venue_result = $sql_command->select($database_venue_names,"id,venue_name","WHERE deleted='No' and island_id='".addslashes($_POST["island_id"])."' ORDER BY venue_name");
$venue_row = $sql_command->results($venue_result);

$venue_list .= "<option value=\"0\">None</option>";


foreach($venue_row as $venue_record) {
$venue_list .= "<option value=\"".stripslashes($venue_record[0])."\">".stripslashes($venue_record[1])."</option>";
}

$get_template->topHTML();
?>
<h1>Add Menu</h1>

<script language="JavaScript">
function addElement() {
  var ni = document.getElementById('add_item');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<div style="float:left; width:220px; margin:5px;"><input type="text" name="menu_name_' + num +'" style="width:220px;"/></div>'
+ '<div style="float:left; width:60px; margin:5px;"><input type="text" name="cost_' + num +'" style="width:60px;"/></div>'
+ '<div style="float:left; width:60px; margin:5px;"><input type="text" name="iw_cost_' + num +'" style="width:60px;"/></div>'
+ '<div style="float:left; width:100px; margin:5px;"><select name="currency_' + num +'" style="width:100px;">'
+ '<option value="Euro">Euro</option>'
+ '<option value="Pound">Pound</option>'
+ '</select></div>'
+ '<div style="float:left; width:180px; margin:5px;"><input type="text" name="note_' + num +'" style="width:180px;"/></div>'
+ '<div style="clear:left;"></div>';
  ni.appendChild(newdiv);
}
</script>
    
<form action="<?php echo $site_url; ?>/oos/add-menu.php" method="POST">
<input type="hidden" name="island_id" value="<?php echo $_POST["island_id"]; ?>" />

<div style="float:left; width:160px; margin:5px;"><b>Supplier</b></div>
<div style="float:left; margin:5px;"><select name="supplier_id" style="width:300px;">
<?php echo $supplier_list; ?>
</select></div>
<div style="clear:left;"></div>
<div style="float:left; width:160px; margin:5px;"><b>Venue</b></div>
<div style="float:left; margin:5px;"><select name="venue_id" style="width:300px;">
<?php echo $venue_list; ?>
</select></div>
<div style="clear:left;"></div>

<div style="float:left; width:160px; margin:5px;"><b>Internal Package Name</b></div>
<div style="float:left; margin:5px;">	<input type="text" name="iw_menu_name" style="width:350px;"/> *</div>
<div style="clear:left;"></div>

<p><hr /></p>

<p>Please enter the cost Per Person</p>

<div style="float:left; width:220px; margin:5px;"><b>External Package Name</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Net</b></div>
<div style="float:left; width:60px; margin:5px;"><b>Gross</b></div>
<div style="float:left; width:100px; margin:5px;"><b>Currency</b></div>
<div style="float:left; width:180px; margin:5px;"><b>Note</b></div>
<div style="clear:left;"></div>


<div style="float:left; width:220px; margin:5px;"><input type="text" name="menu_name_1" style="width:220px;"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="cost_1" style="width:60px;"/></div>
<div style="float:left; width:60px; margin:5px;"><input type="text" name="iw_cost_1" style="width:60px;"/></div>
<div style="float:left; width:100px; margin:5px;"><select name="currency_1" style="width:100px;">
<option value="Euro">Euro</option>
<option value="Pound">Pound</option>
</select></div>
<div style="float:left; width:180px; margin:5px;"><input type="text" name="note_1" style="width:180px;"/></div>
<div style="clear:left;"></div>

<div id="add_item" style="width:680px; padding:0; margin:0;"> </div>

<p>* - Required Fields</p>


<input type="hidden" value="1" id="theValue" name="theValue">

<div style="float:left; width:100px; margin:5px;"><input type="submit" name="action" value="Add"></div>
<div style="float:right; width:180px; margin:5px;"><input type="button" value="Add Another Menu" onclick="addElement();"></div>
<div style="clear:borth;"></div>


</form>
<?
$get_template->bottomHTML();
$sql_command->close();
} else {
	
$level1_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='0'");
$level1_row = $sql_command->results($level1_result);
	
foreach($level1_row as $level1_record) {
	
if($level1_record[1] == "Destinations") {

$level2_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level1_record[0])."' ORDER BY displayorder");
$level2_row = $sql_command->results($level2_result);

foreach($level2_row as $level2_record) {	


$nav_list .= "<option value=\"".stripslashes($level2_record[0])."\" style=\"font-size:11px;color:#F00; font-weight:bold;\">".stripslashes($level2_record[1])."</option>\n";

$level3_result = $sql_command->select($database_navigation,"id,page_name,page_link,external_link,external_url","WHERE deleted='No' and parent_id='".addslashes($level2_record[0])."' ORDER BY displayorder");
$level3_row = $sql_command->results($level3_result);

foreach($level3_row as $level3_record) {
$nav_list .= "<option value=\"".stripslashes($level3_record[0])."\" style=\"font-size:11px;\">".stripslashes($level3_record[1])."</option>\n";
}
}
} 
}


$get_template->topHTML();
?>
<h1>Add Menu</h1>

<form action="<?php echo $site_url; ?>/oos/add-menu.php" method="POST">
<input type="hidden" name="action" value="Continue" />
<select name="island_id" class="inputbox_town" size="30" style="width:700px;" onclick="this.form.submit();"><?php echo $nav_list; ?></select>

<p style="margin-top:10px;"><input type="submit" name="action" value="Continue"></p>
</form>
<?
$get_template->bottomHTML();
$sql_command->close();

} 


?>