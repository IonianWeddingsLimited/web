<?

if($_POST) {
	print_r($_POST);
	exit();
} else {
	
	
echo "<form method=\"post\" action=\"formtest.php\">";
$counter=0;
while($counter < 2000) {
$counter++;
echo "<input type=\"text\" name=\"form_$counter\" value=\"field $counter\"><br>";
}
	

echo "<p><input type=\"submit\" name=\"a\" value=\"Submit\"></p></form>";
	
}