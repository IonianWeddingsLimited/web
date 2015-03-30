<?
require ("../_includes/settings.php");
require ("../_includes/function.templates.php");
include ("../_includes/function.database.php");
include ("../_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

$per_page = 20;

$get_template = new main_template();


if($_POST["term"]) {

header("Location: $site_url/search/index.php?term=".$_POST["term"]);
$sql_command->close();


	
} elseif($_GET["term"]) {
	
$term = addslashes($_GET["term"]);

list($blank,$blank,$blank,$year,$page) = explode("/", $_SERVER["REQUEST_URI"]);

if(!$year) { $year = date("Y",$time); }

if($page) { $page = $page; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;


$result = $sql_command->select($database_navigation,"parent_id,page_name,page_link, ((LENGTH(page_name) - LENGTH(REPLACE(page_name, '".$term."', ''))) +  (LENGTH(html) - LENGTH(REPLACE(html, '".$term."', '')))) AS occurrences","WHERE page_name LIKE '%".$term."%' or html LIKE '%".$term."%' ORDER BY occurrences DESC");
$row = $sql_command->results($result);


foreach($row as $record) {
$add_link = "";

$result_3 = $sql_command->select($database_navigation,"parent_id,page_name,page_link","WHERE id='".addslashes($record[0])."'");
$record_3 = $sql_command->result($result_3);

if($record_3[2]) {
$add_link = "/".$record_3[2];
}

$result_2 = $sql_command->select($database_navigation,"parent_id,page_name,page_link","WHERE id='".addslashes($record_3[0])."'");
$record_2 = $sql_command->result($result_2);


if($record_2[2]) {
$add_link = "/".$record_2[2].$add_link;
}

$result_1 = $sql_command->select($database_navigation,"parent_id,page_name,page_link","WHERE id='".addslashes($record_2[0])."'");
$record_1 = $sql_command->result($result_1);


if($record_1[2]) {
$add_link = "/".$record_1[2].$add_link;
}


$html .= "<a href=\"".$site_url.$add_link."/".$record[2]."/\">".stripslashes($record[1])."</a> | ".stripslashes($record[3])."<br />";
}


$get_template->topHTML();
?><div class="maincopy">
<h1>Search Results</h1>
<p><strong>Term:</strong> <?php echo stripslashes($_GET["term"]); ?></p>

<p><?php echo $html; ?></p>

</div><?
$get_template->bottomHTML();
$sql_command->close();



} else {

$get_template->topHTML();
?><div class="maincopy">
<h1>Search</h1>
<form action="http://83.149.102.2/~ionianwe/search/index.php" class="pageform" id="search" method="post" name="search">
    <input type="hidden" name="page" value="contactus">

<div class="formheader">
		<h1>Search by Keyword</h1>
	</div>
	<div class="formrow">
		<label class="formlabel" for="term">Keyword<span class="required">*</span></label>
		<div class="formelement">
		<input class="formtextfieldlong" id="term" name="term" type="text" />

		</div>
		<div class="clear"></div>
	</div>
    
 	<div class="formrow">
		<label class="formlabel" for="submit">&nbsp;</label>
		<div class="formelement">
			<input class="formsubmit" id="_submit" name="action" type="submit" value="Submit" />

			<input class="formreset" type="reset" value="Reset" />
		</div>
		<div class="clear"></div>
	</div>   
  </form>  
	</div><?
$get_template->bottomHTML();
$sql_command->close();

}

?>