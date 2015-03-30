<?php
	session_start();
	ob_start();

	require_once "config.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<title>jQuery Autocomplete Plugin</title>
<script type="text/javascript" src="jquery.js"></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
</head>
<body>
<div id="content">
	<form autocomplete="off">
		<script type="text/javascript">
			$().ready(function() {
				
				$("#client_search").autocomplete("get_client_list.php", {
					width: 400,
					matchContains: true,
					mustMatch: true,
					//minChars: 0,
					//multiple: true,
					//highlight: false,
					//multipleSeparator: ",",
					selectFirst: false
				});
				
				$("#client_search").result(function(event, data, formatted) {
					$("#client_id").val(data[1]);
					$("#iwcuid").val(data[2]);
				});
			});
		</script>
		<p>
			<label for="client_search">Client Search :</label>
			<input id="client_search" name="client_search" type="text"  /><br />
			<label for="client_search">IWCUID :</label>
			<input id="iwcuid" name="iwcuid" type="text"  /><br />
			<input id="client_id" name="client_id" type="hidden" />
			<!--<input type="button" value="Get Value" />-->
		</p>
		<input type="submit" value="Submit" />
	</form>
</div>
</body>
</html>
