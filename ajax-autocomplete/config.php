<?php
	require ("../_includes/settings.php");
	require ("../_includes/function.templates.php");
	include ("../_includes/function.database.php");
	
	$conn			=	mysql_connect($database_host, $database_username, $database_password) or die ('Error connecting to mysql');
	mysql_select_db($database_name);
?>