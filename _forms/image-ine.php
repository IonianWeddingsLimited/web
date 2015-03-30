<body>
<div class="masthead">
	<div class="logo">
		<!--<h3><img src="/images/interface/i-logo-iw-image-ine.png" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></h3>
		<h2><img src="/images/interface/i-logo-image-ine.png" alt="Image-ine - Design your dream wedding" border="0" title="Image-ine - Design your dream wedding" /></h2>-->
	</div>
	<div class="strapline">
		<h1>Picture your perfect wedding and create a mood board</h1>
		<h1>Share it. Mail it. Tweet it. Pin it. Love it.</h1>
		<p>Click the heart to save your favourite images to your personal gallery. Click 'X' to remove images.</p>
		<p>Create your own personal wedding vision and share it via Facebook, Twitter, Pinterest and Email.</p>
		<p>Or click on the Contact Us button to send us your chosen images and our experienced wedding consultants will help you to find your dream wedding venue.</p>
	</div>
	<div class="clear"></div>
</div>
<div id="dragable" class="header">
<?
	$imgSocial = false;
	
	function getLength($number) {
		$length = 0;
		if ($number == 0){
			$length = 1;
		} else {
			$length = (int) log10($number)+1;
		}
		return $length;
	}

	if ((isset($_POST['type']) && $_POST['type']=="swatch") OR strlen($level2_name)>9 OR isset($_POST['title'])) {
		if (isset($_POST['title'])) {
			include("_includes/imageine.php");
			if ($active) {  
				$level2_name = $link; 
				$imgSocial = true;
				$responsetxt = "Thank you for expressing an interest, please feel free to share your IMAGE-INE using the links on the bottom of the screen.";
?>
		<input type="hidden" name="frun" id="frun" value="false" />
		<script>	
			$(window).ready(function() {
				if ($("#frun").val() === false) {
					$.colorbox({href: '.sformheader',inline:true, width:"300px", fixed:true});
					$("#frun").val(true);	
				}
			});
		</script>
<?
		} else {
?>
		<script>	
			$(window).ready(function() {
				$.colorbox({href: '.sformheader',inline:true, width:"300px", fixed:true});
			});
		</script>
<?
		}
		
	}
	
	if (isset($_SESSION['ioid'])) { include("_includes/imageine.php"); $level2_name = $link; $imgSocial = true; }
		
		$mason = $sql_command->count_nrow("$database_gallery_mason",
										  "id",
										  "meta_id = 10 AND meta_value = \"".$level2_name."\"");
		if ($mason>0) {
			$result = $sql_command->select("$database_gallery_mason","meta_desc","WHERE meta_id = 10 AND meta_value = \"".$level2_name."\"");
			$imageid = $sql_command->result($result);
			$imgids = unserialize($imageid[0]);
			$imgSocial = true;
		} elseif (!isset($_POST['type'])) {
			Header("Location: http://www.ionianweddings.co.uk/image-ine/");
		} else {
			$imgids = str_replace("\"","",$_POST['imgids']);
		}
	
		/*echo "<script src=\"".$site_url."/js/main.php?t=2&p=".$imgids."\"></script>"; */
		$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
									   $database_gallery_pics.id,
									   $database_gallery_pics.gallery_id,
									   $database_gallery_pics.imagename,
									   $database_gallery_pics.displayorder,
									   $database_gallery_pics.title,
									   $database_gallery_pics.description,
									   $database_gallery_pics.link",
									   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.img_id IN (".$imgids.") AND $database_gallery_mason.meta_id != 10 ORDER BY $database_gallery_pics.weight DESC");
	
	
		$images = str_replace("\"","",$_POST['imgids']); 
		$rec = $sql_command->results($imgres);
		$recnum = mysql_num_rows($imgres);
		$recnum = $recnum * 8;
		$i=0;
		
		date_default_timezone_set('Europe/London');
		$today = getdate();
		$link = $today[0];
		$cols = "img_id,meta_id,meta_value,meta_desc,timestamp";
		$imgids = serialize(addslashes($_POST["imgids"])); 
		$values = "'".addslashes($ioid)."',10,'".addslashes($link)."','".addslashes($imgids)."','".addslashes($link)."'";	
		$sql_command->insert($database_gallery_mason,$cols,$values);
		
		$level2_name = addslashes($link);
		
		/*echo "<script type='text/javascript' src=\"".$site_url."/js/main.php?t=2&p=".$imgids."\"></script>";*/
		echo "<div id=\"gallery\" class=\"gallery ui-helper-reset ui-helper-clearfix is-scrollable imageine sharelist\">";
		foreach($rec as $row) {
		
			list($width,$height) = explode("x",$row[0]);
			
			$width = $width/320;
			$height = $height/320;
			$dimension = array($width,$height);
			$dimension = implode("x",$dimension);
				
				
			echo "<div id=\"pic".$i."\" data-imgid=\"".$row[1]."\" class=\"block item tile".$dimension."\">	
					<div class=\"box-container\">
						<img id=\"".$row[1]."\" class=\"lazy\" src=\"".$site_url."/images/gallery/".$row[0]."/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" /> 
					</div>
				</div>";
				$i++;
		}
?>	
  </div>
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
<?
	//if (!$imgSocial) {  
?>
  <!--<div id="sharenav" class="headersm launch">
  	<h2>If you love it - Share it!</h2>
    <ul>
        <li class="headersmitem">
            <a id="facebook" title="facebook" href="#">
                <img border="0" title="facebook" alt="facebook" src="<?php echo $site_url;?>/images/interface/b-image-ine-facebook.gif" />
            </a>
        </li>
        <li class="headersmitem">
            <a id="twitter" title="twitter" href="#">
                <img border="0" title="twitter" alt="twitter" src="<?php echo $site_url;?>/images/interface/b-image-ine-twitter.gif" />
            </a>
        </li>
		<li class="headersmitem">
            <a id="google" title="google" href="#">
                <img border="0" title="google" alt="google" src="<?php echo $site_url;?>/images/interface/b-image-ine-google-plus.gif" />
            </a>
        </li>
        <li class="headersmitem">
            <a id="pinterest" title="Pinterest" href="#">
                <img border="0" title="Pinterest" alt="Pinterest" src="<?php echo $site_url;?>/images/interface/b-image-ine-pinterest.gif" />
            </a>
        </li>
        <li class="headersmitem">
            <a id="tumblr" title="tumblr" href="#">
                <img border="0" title="tumblr" alt="tumblr" src="<?php echo $site_url;?>/images/interface/b-image-ine-tumblr.gif" />
            </a>
        </li>
		 <li class="headersmitem">
            <a id="email" title="email" href="#">
                <img border="0" title="email" alt="email" src="<?php echo $site_url;?>/images/interface/b-image-ine-email.gif" />
            </a>
        </li>
        <div class="clear"></div>
    </ul>
</div>
<div id="popnav" class="headersm" style="display: none;">
	<h2>If you love it - Share it!</h2>
	<ul class="addthis_toolbox addthis_default_style addthis_32x32_style"
        addthis:url="http://www.ionianweddings.co.uk/image-ine/<?php echo $link; ?>/#"
        addthis:title="Ionian Weddings - IMAGE-INE"
        addthis:description="Ionian Weddings - IMAGE-INE, imagine your Greek Wedding"> 
		<li class="headersmitem">
			<a class="addthis_button_facebook"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_twitter"></a>
		</li>	
		<li class="headersmitem">
			<a class="addthis_button_google_plusone_share"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_pinterest_share"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_tumblr"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_email"></a>
		</li>
	</ul>
	<script type="text/javascript">
		var addthis_config = {
			image_exclude: "at_exclude",

			image_container: ".header",
			"data_track_addressbar":true
		}
	</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54c25eb96e7f9ae5"></script>
</div>-->
<?
	//} else {  
?>  
<div id="popnav" class="headersm">
	<h2>If you love it - Share it!</h2>
	<ul class="addthis_toolbox addthis_default_style addthis_32x32_style"
        addthis:url="http://www.ionianweddings.co.uk/image-ine/<?php echo $level2_name; ?>/#"
        addthis:title="Ionian Weddings - IMAGE-INE"
        addthis:description="Ionian Weddings - IMAGE-INE, imagine your Greek Wedding"> 
		<li class="headersmitem">
			<a class="addthis_button_facebook"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_twitter"></a>
		</li>	
		<li class="headersmitem">
			<a class="addthis_button_google_plusone_share"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_pinterest_share"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_tumblr"></a>
		</li>
		<li class="headersmitem">
			<a class="addthis_button_email"></a>
		</li>
		<li class="headersmitem" id="contactus" >
			<a title="Contact us">Contact us</a>
		</li>
	</ul>
	<script type="text/javascript">
		var addthis_config = {
			image_exclude: "at_exclude",

			image_container: ".header",
			"data_track_addressbar":true
		}
	</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54c25eb96e7f9ae5"></script>
</div>
<!-- AddThis Button END -->
<?
	//}
?>
</div>
<div class="sformwrap" id="sformwrap">
	<div class="sformheader">
		<script>
			$(function() {
				$( "#weddingd" ).datepicker({ dateFormat: 'dd/mm/yy' });
			});
		</script>
        <?
			if ($active) {
				echo "<h2 id=\"interest\">".$responsetxt."</h2>";
			} else {
		?>
        <form name="socialcollect" action="/image-ine/" id="socialcollect" method="post">
			<h1>Please fill out this short form and our expert wedding consultants will be in touch with ideas for your dream wedding based on your chosen images.</h1>
            <p class="error_box"><?php $responsetxt = !empty($responsetxt) ? $responsetxt: " "; echo $responsetxt; ?></p>
	        <p>
	            <label for="title">Title:<span>*</span></label>
				<select id="title" name="title">
					<option value="Dr">Dr</option>
					<option value="Mr">Mr</option>
					<option value="Miss" selected>Miss</option>
					<option value="Ms">Ms</option>
					<option value="Mrs">Mrs</option>							
				</select>
            </p>
            <p>
			<label for="firstname">First Name:<span>*</span></label>
			<input type="text" id="firstname" name="firstname" required placeholder="Enter your first name."/>
            </p>
            <p>
			<label for="lastname">Last Name:<span>*</span></label>						
			<input type="text" id="lastname" name="lastname" required placeholder="Enter your last name."/>
            </p>
            <p>
			<label for="email">Email:<span>*</span></label>						
			<input type="email" id="ema" name="email" required placeholder="Enter a valid email."/>
            </p>
            <p>
			<label for="telephone">Telephone:<span>*</span></label>						
			<input type="tel" id="telephone" name="telephone" required placeholder="Enter a valid contact number."/>
            </p>
            <p>
			<label for="weddingd">Wedding Date:<span></span></label>						
			<input type="text" id="weddingd" name="weddingd" />
            </p>
            <?php 
			echo "<input type=\"hidden\" id=\"imgids\" name=\"imgids\" value=\"".$images."\"/>"; 
			?>					
			<p>
			<input type="submit" id="subnshare" name="subnshare" value="Share"/>
            </p>
		</form>				
        <?
			}
		?>
	</div>
</div>
<?
	} else {
		
		echo "<div id=\"gallery\" class=\"gallery ui-helper-reset ui-helper-clearfix is-scrollable imageine\">";

	
		$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
									   $database_gallery_pics.id,
									   $database_gallery_pics.gallery_id,
									   $database_gallery_pics.imagename,
									   $database_gallery_pics.displayorder,
									   $database_gallery_pics.title,
									   $database_gallery_pics.description,
									   $database_gallery_pics.link",
									   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10 and $database_gallery_pics.weight = 1");
		$rec = $sql_command->results($imgres);
		shuffle($rec);
		$i=0;
		$c=0;
		$p=0;
	
		$recnum = $sql_command->count_nrow("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.id","$database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10");
		$recnum = $recnum/8;
	?>
		<script type="text/javascript" src="<?php echo $site_url;?>/js/jquery.draganddrop.js"></script>
	<?
	$p++;
	foreach($rec as $row) {
		list($width,$height) = explode("x",$row[0]);
		$width = $width/320;
		$height = $height/320;
		$dimension = array($width,$height);
		$dimension = implode("x",$dimension);
		if ($c<=10) {		
			echo "<div id=\"pic".$i."\" data-imgid=\"".$row[1]."\" class=\"block item tile".$dimension."\">	
		    	<div class=\"box-container\">
		        	<img id=\"".$row[1]."\" class=\"lazy\" src=\"".$site_url."/images/gallery/".$row[0]."/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
			        <a href=\"\" title=\"Love\" class=\"ui-icon ui-icon-trash\">&nbsp;</a> 
				</div>
		    </div>";
			$i++;
			$c++;
		}
		else {
			$c=0;
			
			$p++;
		}
	}
	
	
	$imgres = $sql_command->select("$database_gallery_mason, $database_gallery_pics","$database_gallery_mason.meta_value,
								   $database_gallery_pics.id,
								   $database_gallery_pics.gallery_id,
								   $database_gallery_pics.imagename,
								   $database_gallery_pics.displayorder,
								   $database_gallery_pics.title,
								   $database_gallery_pics.description,
								   $database_gallery_pics.link",
								   "WHERE $database_gallery_mason.img_id = $database_gallery_pics.id AND $database_gallery_mason.meta_id != 10 and $database_gallery_pics.weight = 0");
	$rec = $sql_command->results($imgres);
	shuffle($rec);
	foreach($rec as $row) {
		list($width,$height) = explode("x",$row[0]);
		$width = $width/320;
		$height = $height/320;
		$dimension = array($width,$height);
		$dimension = implode("x",$dimension);
		if ($c<=10) {		
			echo "<div id=\"pic".$i."\" data-imgid=\"".$row[1]."\" class=\"block item tile".$dimension."\">	
		    	<div class=\"box-container\">
		        	<img id=\"".$row[1]."\" class=\"lazy\" src=\"".$site_url."/images/gallery/".$row[0]."/".$row[3]."\" title=\"".$row[5]."\" alt=\"".$row[6]."\" />
			        <a href=\"\" title=\"Love\" class=\"ui-icon ui-icon-trash\">&nbsp;</a> 
				</div>
		    </div>";
			$i++;
			$c++;
		}
		else {
			$c=0;
			
			$p++;
		}
	}

	?>
  </div>
</div>
<div class="wrapper">
  <div class="jcarousel-wrapper">
    <div id="basket" class="jcarousel">
      <div class="itemrow gallery">
        &nbsp;
      </div>
      <a href="#" class="jcarousel-control-prev">&lsaquo;</a> 
      <a href="#" class="jcarousel-control-next">&rsaquo;</a>
      <p class="jcarousel-pagination"></p>
		
    </div>
      <form id="continue" name="continue1" method="post" action="<?php echo $site_url;?>/image-ine/">
    <input type="hidden" name="imgids" id="imgids" value="" />
    <input type="hidden" name="type" value="swatch" />
    <div id="complete"><a href="#">Preview and share</a></div>
    </form> 
  </div>
</div>
<div id="modalContent" style="display: none">
	<div class="imaginepopup">
		<div class="imageinepopupcontent">
			<div class="imageinepopupheader">
				<h3><img src="/images/interface/i-logo-iw-image-ine.png" alt="Ionian Weddings" border="0" title="Ionian Weddings" /></h3>
				<h2><img src="/images/interface/i-logo-image-ine.png" alt="Image-ine - Design your dream wedding" border="0" title="Image-ine - Design your dream wedding" /></h2>
			</div>
			<div class="imageinepopupcopy">
				<p>Over the last 8 years we've searched out the very best destinations and venues for our dream weddings in the Mediterranean. Now you can create your own wedding mood board and share it with your  friends and family.</p>
				<p>Select the images you love and send us your mood board. Once we know what you like we can show you wedding destinations and venues that you'll love as much as that special someone you want to spend the rest of your life with...</p>
				<p class="imaginepopupbutton"><a href="javascript:void(0)" onClick="$.pgwModal('close');" title="Make my mood board">Make my mood board</a></p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="imaginepopupbg"><img src="/images/interface/bg-image-ine.jpg" alt="Welcome to Image-ine" border="0" title="Welcome to Image-ine" /></div>
	</div>
</div>
<script>
$(document).ready(function() {
	$.pgwModal({
		target: '#modalContent',
		//title: 'Modal title 2',
		maxWidth: 965
	});
});
</script>
<?
}
?>

<div id="currentimg" data-totimg="<?php $p=$p>1 ? $p-1 : $p; echo $p; ?>" data-currimg="1" style="display:none;"></div>
</body>
</html>