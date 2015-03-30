<?
require ("_includes/settings.php");
include ("_includes/function.database.php");
include ("_includes/function.genpass.php");

// Connect to sql database
$sql_command = new sql_db();
$sql_command->connect($database_host,$database_name,$database_username,$database_password);

list($blank,$level1_name,$level2_name,$level3_name,$level4_name) = explode("/", $_SERVER["REQUEST_URI"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Step 1</title>
<link rel="stylesheet" type="text/css" href="/css/iw-image-ine-dev.css" />
<link rel="stylesheet" type="text/css" href="/css/jcarousel.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="/js/jquery.touch-punch.min.js"></script>
<script type="text/javascript" src="/freewall-master/freewall.js"></script>
<script src="/js/jquery.draganddrop.js"></script>
<script type="text/javascript" src="/js/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="/js/jcarousel/jcarousel.responsive.js"></script>
<script>
$(document).ready(function() {
	$('.headersmitem a').click(function() {
		$click = $(this);
		var linkfield = "<?php echo $level2_name; ?>";
		$.ajax({
			type: "GET",
			url:"_includes/imageine.php",
			data:({link: linkfield}),
			dataType: 'json',
			success:function(result){
								
			//	if (result.active==true) {
					alert(result.active);
					alert(socialmedia);
					alert(socialm);
					//window.open(socialmedia, socialm, "height=300,width=500");
			//	}
			}
		});						
	});
	
<?php 
	
if (!isset($_POST['type'])) {	
	
?>	
	$("#complete").click(function(){
		var elements = [];

		//iterates through each input field and pushes the name to the array
		$(".jcarousel li img").each(function() {
		    var masonaryID = $(this).attr("id");
		    elements.push(masonaryID);
		});
		var masonaryID = elements.join(",");
		  $('input[name="imgids"]').val(masonaryID);
		  if (masonaryID.length>0) {
		  	$( "#continue" ).submit();
		  }
		  else {
			alert("Please add an image to the image basket by dragging an image to the bottom of the screen or clicking the love symbol on the desired image.");
			return false;  
		  }
	});

   //$('#gallery').html(iOrder);
	// initialize
	
	
<?
	   }
	   else {
 ?>

	//Converts RGB color to HEX (including #)
	$.cssHooks.backgroundColor = {
		get: function(elem) {
			if (elem.currentStyle)
				var bg = elem.currentStyle["background-color"];
			else if (window.getComputedStyle)
				var bg = document.defaultView.getComputedStyle(elem,
					null).getPropertyValue("background-color");
			if (bg.search("rgb") == -1)
				return bg;
			else {
				bg = bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
				function hex(x) {
					return ("0" + parseInt(x).toString(16)).slice(-2);
				}
				return "#" + hex(bg[1]) + hex(bg[2]) + hex(bg[3]);
			}
		}
	};

		var colorclass = '';
		
		$('.swatchitem').click(function() {
			
			var colorclass = $(this).css('backgroundColor');
			
			colorclass	=	colorclass.replace('#', '');
			
			$(".box-container img.imageswapitem").fadeOut('slow');
			//$(".imageswap img.imageswapitem").css('display','none');
			$(".box-container img.imageswapitem."+colorclass).fadeIn('slow');
			//$(".imageswap img.imageswapitem."+colorclass).css('display','block');
			
		});
		$('.swatchreset').click(function() {
			$(".box-container img.imageswapitem").fadeOut('slow');
		});
/*		
		var SearchString = window.location.search.substring(1);
	    var VariableArray = SearchString.split(',');
		var valuearray = VariableArray.length;
		var newHtml = '';
		for (var $i=0; $i<valuearray; $i++) {
		newHtml += "<p id='pic"+ VariableArray[$i] +"' class='item'><h1>" + VariableArray[$i] + "</h1><div class='box-container'><img src='/images/swatch/1.jpg' class='imageswapitem 900000' /><img src='/images/swatch/2.jpg' class='imageswapitem 993300' /><img src='/images/swatch/3.jpg' class='imageswapitem 996600' /><img src='/images/swatch/4.jpg' class='imageswapitem 999900' /><img src='/images/swatch/5.jpg' class='imageswapitem 99cc00' /><img src='/images/swatch/6.jpg' class='imageswapitem 99ff00' /><img src='/images/swatch/0.jpg' /></div></p>";
		}
		
		$('.gallery').append(newHtml); */
<?
}
?>
	
	});

</script>
</head>
<body>
<div class="masthead">
  <div class="logo"> <img src="/images/interface/i-image-ine-logo.png" alt="IMAGE-INE : Picture your perfect wedding" botrder="0" title="IMAGE-INE : Picture your perfect wedding" /> </div>
</div>
<div class="header">
  <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">

<?
if (isset($_POST['type']) && $_POST['type']=="swatch") {

  	$imgids = str_replace("\"","",$_POST['imgids']);
  	$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.img_id IN (".$imgids.")");
	$rec = $sql_command->results($imgres);
	shuffle($rec);
	$i=0;
	foreach($rec as $row) {
		
	list($width,$height) = explode("x",$row[0]);
	
	$width = $width/320;
	$height = $height/320;
	
	switch	($height)	{
			case	1:
			$height		=	"one";
			case	2:
			$height		=	"two";
			case	3:
			$height		=	"three";
			case	4:
			$height		=	"four";
	}
	
	switch	($width)	{
			case	1:
			$width		=	"one";
			case	2:
			$width		=	"two";
			case	3:
			$width		=	"three";
			case	4:
			$width		=	"four";
	}
	
	$dimension = array($width,$height);
	$dimension = implode("x",$dimension);
	
	function convert_number_to_words($number) {
		
	}
		
	echo "<li id=\"pic".$i."\" class=\"item tile".$dimension."\">	
	    	<div class=\"box-container\">
	        	<img id=\"".$row[1]."\" src=\"/images/gallery/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" /> 
			</div>
	    </li>";
		$i++;
	}
	
	
	
/*
  var SearchString = window.location.search.substring(1);
	    var VariableArray = SearchString.split(',');
		var valuearray = VariableArray.length;
		var newHtml = '';
		for (var $i=0; $i<valuearray; $i++) {
		newHtml += "<p id='pic"+ VariableArray[$i] +"' class='item'><h1>" + VariableArray[$i] + "</h1><div class='box-container'><img src='/images/swatch/1.jpg' class='imageswapitem 900000' /><img src='/images/swatch/2.jpg' class='imageswapitem 993300' /><img src='/images/swatch/3.jpg' class='imageswapitem 996600' /><img src='/images/swatch/4.jpg' class='imageswapitem 999900' /><img src='/images/swatch/5.jpg' class='imageswapitem 99cc00' /><img src='/images/swatch/6.jpg' class='imageswapitem 99ff00' /><img src='/images/swatch/0.jpg' /></div></p>";
		*/
?>  
  </ul>
</div>
<div class="footer">
  <ul class="swatch">
    <li class="swatchreset"></li>
    <li class="swatchitem" style="background-color: #900000;"></li>
    <li class="swatchitem" style="background-color: #993300;"></li>
    <li class="swatchitem" style="background-color: #996600;"></li>
    <li class="swatchitem" style="background-color: #999900;"></li>
    <li class="swatchitem" style="background-color: #99cc00;"></li>
    <li class="swatchitem" style="background-color: #99ff00;"></li>
    <li class="clear"></li>
  </ul>
  
  <div class="headersm">

    <ul>
        <li class="headersmitem">
            <a title="facebook" href="#">
                <img border="0" title="facebook" alt="facebook" src="/images/interface/b_header_facebook.gif"></img>
            </a>
        </li>
        <li class="headersmitem">
            <a title="twitter" href="#">
                <img border="0" title="twitter" alt="twitter" src="/images/interface/b_header_twitter.gif"></img>
            </a>
        </li>
        <li class="headersmitem">
            <a title="Pinterest" href="#">
                <img border="0" title="Pinterest" alt="Pinterest" src="/images/interface/b_header_pinterest.gif"></img>
            </a>
        </li>
        <li class="headersmitem">
            <a title="tumblr" href="#">
                <img border="0" title="tumblr" alt="tumblr" src="/images/interface/b_header_tumblr.gif"></img>
            </a>
        </li>
        <div class="clear"></div>
    </ul>

</div>
</div>
<?
}

else {
	

	$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_pics.id != 0");
	$rec = $sql_command->results($imgres);
	shuffle($rec);
	$i=0;
	foreach($rec as $row) {
		
	list($width,$height) = explode("x",$row[0]);
	
	$width = $width/320;
	$height = $height/320;
	
	switch	($height)	{
			case	1:
			$height		=	"one";
			case	2:
			$height		=	"two";
			case	3:
			$height		=	"three";
			case	4:
			$height		=	"four";
	}
	
	switch	($width)	{
			case	1:
			$width		=	"one";
			case	2:
			$width		=	"two";
			case	3:
			$width		=	"three";
			case	4:
			$width		=	"four";
	}

	$dimension = array($width,$height);
	$dimension = implode("x",$dimension);
		
		
	echo "<li id=\"pic".$i."\" class=\"item tile".$dimension."\">	
	    	<div class=\"box-container\">
	        	<img id=\"".$row[1]."\" src=\"/images/gallery/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
		        <a href=\"link/to/trash/script/when/we/have/js/off\" title=\"Love\" class=\"ui-icon ui-icon-trash\">Love</a> 
			</div>
	    </li>";
		$i++;
	}

	?>
  </ul>
</div>
<ul id="fillers">
<?
	$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_pics.id != 0");
	$rec = $sql_command->results($imgres);
	shuffle($rec);
	$i=0;
	foreach($rec as $row) {
		
	list($width,$height) = explode("x",$row[0]);
	
	$width = $width/320;
	$height = $height/320;
	
	switch	($height)	{
			case	1:
			$height		=	"one";
			case	2:
			$height		=	"two";
			case	3:
			$height		=	"three";
			case	4:
			$height		=	"four";
	}
	
	switch	($width)	{
			case	1:
			$width		=	"one";
			case	2:
			$width		=	"two";
			case	3:
			$width		=	"three";
			case	4:
			$width		=	"four";
	}

	$dimension = array($width,$height);
	$dimension = implode("x",$dimension);
		
		
	echo "<li id=\"fill".$i."\" class=\"fillerBox tile".$dimension."\">	
	    	<div class=\"box-container\">
	        	<img id=\"".$row[1]."\" src=\"/images/gallery/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
		        <a href=\"link/to/trash/script/when/we/have/js/off\" title=\"Love\" class=\"ui-icon ui-icon-trash\">Love</a> 
			</div>
	    </li>";
		$i++;
	}

?>
</ul>
<div class="wrapper">
  <div class="jcarousel-wrapper">
    <div id="basket" class="jcarousel">
      <ul class="gallery">
        &nbsp;
      </ul>
    </div>
      <a href="#" class="jcarousel-control-prev">&lsaquo;</a> <a href="#" class="jcarousel-control-next">&rsaquo;</a>
    <p class="jcarousel-pagination"></p>
    <form id="continue" name="continue1" method="post" action="image-ine.php">
    <input type="hidden" name="imgids" id="imgids" value="" />
    <input type="hidden" name="type" value="swatch" />
    <div id="complete"><a href="#">Continue with selected images</a></div>
    </form>
  </div>
</div>
<?
}
?>
</body>
</html>