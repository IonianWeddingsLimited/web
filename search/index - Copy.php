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
	
list($blank,$blank,$blank,$year,$page) = explode("/", $_SERVER["REQUEST_URI"]);

if(!$year) { $year = date("Y",$time); }

if($page) { $page = $page; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;


$result = $sql_command->select($database_navigation,"page_name,
	   MATCH (page_name, html) AGAINST ('".addslashes($_GET["term"])."' in boolean mode) AS relevance,
       MATCH (page_name) AGAINST ('".addslashes($_GET["term"])."' in boolean mode) AS title_relevance,
	   MATCH (meta_title) AGAINST ('".addslashes($_GET["term"])."' in boolean mode) AS meta_relevance","WHERE MATCH (page_name, html, meta_title) AGAINST ('".addslashes($_GET["term"])."' in boolean mode)
ORDER BY title_relevance DESC, relevance DESC, meta_relevance DESC");
		



		  
$row = $sql_command->results($result);

foreach($row as $record) {

$html .= "<p>".stripslashes($record[0])." | ".stripslashes($record[1])." ".stripslashes($record[2])."</p>";
}


$get_template->topHTML();
?><div class="maincopy">
<h1>Search Results</h1>
<p><strong>Term:</strong> <?php echo stripslashes($_GET["term"]); ?></p>

<?php echo $html; ?>

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