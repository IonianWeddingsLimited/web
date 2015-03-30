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
$lenght = strlen($term);

list($blank,$blank,$blank,$year,$page) = explode("/", $_SERVER["REQUEST_URI"]);

if(!$year) { $year = date("Y",$time); }

if($page) { $page = $page; } else { $page = 1; }
$limitbottom=($page-1)*$per_page;

$term1 = strtolower($term);

$count = 0;
$keyword_array = explode(" ",trim($term));

while($count < 10) {
$term3 = ucfirst($keyword_array[$count]);
$term2 .= $term3." ";
$count++;
}

$term2 = trim($term2);

$where .= "(((LENGTH(page_name) - LENGTH(REPLACE(page_name, '".$term1."', ''))) / $lenght) + ((LENGTH(html) - LENGTH(REPLACE(html, '".$term1."', ''))) / $lenght))";
$where .= " + (((LENGTH(page_name) - LENGTH(REPLACE(page_name, '".$term2."', ''))) / $lenght) + ((LENGTH(html) - LENGTH(REPLACE(html, '".$term2."', ''))) / $lenght))";

$result = $sql_command->select($database_navigation,"parent_id,page_name,page_link,html, ( $where ) AS occurrences","WHERE page_name LIKE '%".$term1."%' or html LIKE '%".$term1."%' ORDER BY occurrences DESC");
$row = $sql_command->results($result);

$total_result = $sql_command->select($database_navigation,"( $where ) AS occurrences","WHERE page_name LIKE '%".$term1."%' or html LIKE '%".$term1."%' ORDER BY occurrences DESC LIMIT 1");
$total_record = $sql_command->result($total_result);

$percent = $total_record[0];


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

$percent2 = ($record[3] / $percent) * 100;

$paragraph = "";

if($record[1] == "Our Story") {
$start = strpos($record[3], '<p>',2);
} else {
$start = strpos($record[3], '<p>');
}

$end = strpos($record[3], '</p>', $start);
$paragraph = substr($record[3], $start, $end-$start+4);
$paragraph = str_replace("<p>", "", $paragraph);
$paragraph = str_replace("</p>", "", $paragraph);
$paragraph = (strlen($paragraph) > 200) ? substr($paragraph, 0, 200) . '...' : $paragraph;

if($record[1] == "Wedding Travel") {
$paragraph = "Ionian Weddings has negotiated special rates at 1000&#39;s of hotels all over Greece and Cyprus, from small family-run hotels to luxury resorts.";
}

if($record[1] != "Contact Us") {
if(strlen($paragraph)>10) { $c_html = "<p>".stripslashes($paragraph)."</p>"; } else { $c_html = ""; }

$html .= "<div class=\"accordianlistitem\">
		<div class=\" accordianlink\">
			<h1 style=\"margin-left:-12px;\"><a href=\"".$site_url.$add_link."/".$record[2]."/\">".stripslashes($record[1])."</a></h1>
			<div class=\"clear\"></div>
		</div>
		<div class=\"\">
			<div class=\"maincopy\">
				$c_html
			</div>
		</div>

	</div>\n";
}
}


		
	
	
$get_template->topHTML();
?><div class="maincopy">
<h1>Search Results</h1>
<p><strong>Term:</strong> <?php echo stripslashes($_GET["term"]); ?></p>
<div class="accordianlist">
<?php echo $html; ?>
</div>

</div><?
$get_template->bottomHTML();
$sql_command->close();



} else {

$get_template->topHTML();
?><div class="maincopy">
<h1>Search</h1>
<form action="<?php echo $site_url; ?>/search/index.php" class="pageform" id="search" method="post" name="search">
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