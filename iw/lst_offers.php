<?php
	if (($_REQUEST["PTID"]) == 17) {
		$strMainCopy		=	"<h1>Special Offers</h1>";
	}
	if (($_REQUEST["PTID"]) == 18) {
		$strMainCopy		=	"<h1>5 Star Packages</h1>";
	}
?>

<div class="maincontent">
	<div class="maincopy"> <?php echo $strMainCopy ?> </div>
</div>
<?php include("nav_pagination.php") ?>
<div class="accordianlist">
	<?php
		if (($_REQUEST["PTID"]) == 17) {
	?>
	<div class="accordianlistitem">
		<div class="accordianheader accordianlink">
			<h1>Kefalonia Spring Specials</h1>
			<h3>Read more &gt;</h3>
			<div class="clear"></div>
			<h2>See our latest spring special offer for Kefalonia</h2>
			<div class="clear"></div>
		</div>
		<div class="accordiancontent">
			<div class="maincopy">
				<p>Content to be supplied...</p>
			</div>
		</div>
	</div>
	
	<div class="accordianlistitem">
		<div class="accordianheader accordianlink">
			<h1>Zakynthos Beach Weddings</h1>
			<h3>Read more &gt;</h3>
			<div class="clear"></div>
			<h2>See the latest offer for a beach wedding in Zakynthos</h2>
			<div class="clear"></div>
		</div>
		<div class="accordiancontent">
			<div class="maincopy">
				<p>Content to be supplied...</p>
			</div>
		</div>
	</div>
	<?php
		}
		if (($_REQUEST["PTID"]) == 18) {
	?>
	<div class="accordianlistitem">
		<div class="accordianheader accordianlink">
			<h1>Cyprus 5 Star Luxury Package</h1>
			<h3>Read more &gt;</h3>
			<div class="clear"></div>
			<h2>See the latest 5 Star package in Cyprus</h2>
			<div class="clear"></div>
		</div>
		<div class="accordiancontent">
			<div class="maincopy">
				<p>Content to be supplied...</p>
			</div>
		</div>
	</div>
	<div class="accordianlistitem">
		<div class="accordianheader accordianlink">
			<h1>Zante 5 Star Luxury Package</h1>
			<h3>Read more &gt;</h3>
			<div class="clear"></div>
			<h2>See the latest 5 Star package in Zante</h2>
			<div class="clear"></div>
		</div>
		<div class="accordiancontent">
			<div class="maincopy">
				<p>Content to be supplied...</p>
			</div>
		</div>
	</div>
	<?php
		}
	?>
</div>
<div class="maincontent">
	<?php include("nav_sitefeature.php") ?>
</div>
