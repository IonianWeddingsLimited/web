<?
require ("_includes/settings.php");

$_SESSION["subject"] = $_GET["subject"];

header("Location: $site_url/contact-us/");
exit();
?>
	   