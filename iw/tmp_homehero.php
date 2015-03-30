<div class="homehero">
	<?php include("nav_sitefeature.php") ?>
	<script type="text/javascript">
		$(document).ready(function() {
			var tn1 = $('.hero').tn3({
				skinDir:"skins",
				width:935,
				height:433,
				autoplay:true,
				//delay: 2000,
				image:{
					crop:false,
					transitions:[{
						//type:"grid",
						//duration: 1000,
						//gridX:15,
						//gridY:10,
						//diagonalStart:"tl"
						type: "fade",
						easing: "easeInQuad",
						duration: 900
					}]
				},
				external:[{
				origin:"xml",
				url:"xml_homehero.xml"
				}]
			});
		});
	</script>
	<div class="hero"></div>
</div>