<?php
	if (($_REQUEST["PTID"]) == 7) {
		if (($_REQUEST["PPID"]) == 2) {
			$strXML		=	"xml_package_aegean_2.xml";
		} else if (($_REQUEST["PPID"]) == 7) {
			$strXML		=	"xml_package_aegean_7.xml";
		} else if (($_REQUEST["PPID"]) == 8) {
			$strXML		=	"xml_package_aegean_8.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 8) {
		if (($_REQUEST["PPID"]) == 1) {
			$strXML		=	"xml_package_ionian_1.xml";
		} else if (($_REQUEST["PPID"]) == 2) {
			$strXML		=	"xml_package_ionian_2.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 9) {
		if (($_REQUEST["PPID"]) == 1) {
			$strXML		=	"xml_package_cyprus_1.xml";
		} else if (($_REQUEST["PPID"]) == 2) {
			$strXML		=	"xml_package_cyprus_2.xml";
		} else if (($_REQUEST["PPID"]) == 4) {
			$strXML		=	"xml_package_cyprus_4.xml";
		} else if (($_REQUEST["PPID"]) == 5) {
			$strXML		=	"xml_package_cyprus_5.xml";
		} else if (($_REQUEST["PPID"]) == 6) {
			$strXML		=	"xml_package_cyprus_6.xml";
		} else if (($_REQUEST["PPID"]) == 7) {
			$strXML		=	"xml_package_cyprus_7.xml";
		} else if (($_REQUEST["PPID"]) == 8) {
			$strXML		=	"xml_package_cyprus_8.xml";
		} else if (($_REQUEST["PPID"]) == 10) {
			$strXML		=	"xml_package_cyprus_10.xml";
		} else if (($_REQUEST["PPID"]) == 12) {
			$strXML		=	"xml_package_cyprus_12.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 12) {
		if (($_REQUEST["PPID"]) == 1) {
			$strXML		=	"xml_destination_aegean_1.xml";
		} else if (($_REQUEST["PPID"]) == 2) {
			$strXML		=	"xml_destination_aegean_2.xml";
		} else if (($_REQUEST["PPID"]) == 3) {
			$strXML		=	"xml_destination_aegean_3.xml";
		} else if (($_REQUEST["PPID"]) == 4) {
			$strXML		=	"xml_destination_aegean_4.xml";
		} else if (($_REQUEST["PPID"]) == 5) {
			$strXML		=	"xml_destination_aegean_5.xml";
		} else if (($_REQUEST["PPID"]) == 6) {
			$strXML		=	"xml_destination_aegean_6.xml";
		} else if (($_REQUEST["PPID"]) == 7) {
			$strXML		=	"xml_destination_aegean_7.xml";
		} else if (($_REQUEST["PPID"]) == 8) {
			$strXML		=	"xml_destination_aegean_8.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 13) {
		if (($_REQUEST["PPID"]) == 1) {
			$strXML		=	"xml_destination_ionian_1.xml";
		} else if (($_REQUEST["PPID"]) == 2) {
			$strXML		=	"xml_destination_ionian_2.xml";
		} else if (($_REQUEST["PPID"]) == 3) {
			$strXML		=	"xml_destination_ionian_3.xml";
		} else if (($_REQUEST["PPID"]) == 4) {
			$strXML		=	"xml_destination_ionian_4.xml";
		} else if (($_REQUEST["PPID"]) == 6) {
			$strXML		=	"xml_destination_ionian_6.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 14) {
		if (($_REQUEST["PPID"]) == 1) {
			$strXML		=	"xml_destination_cyprus_1.xml";
		} else {
			$strXML		=	"xml_awaiting.xml";
		}
	} else if (($_REQUEST["PTID"]) == 22) {
		$strXML		=	"xml_aboutus.xml";
	} else {
		$strXML		=	"xml_awaiting.xml";
	}
?>
<script type="text/javascript">
	$(document).ready(function() {
		var tn1 = $('.hero').tn3({
			skinDir:"skins",
			width:516,
			height:462,
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
			url:"<?php echo $strXML ?>"
			}]
		});
	});
</script>
<div class="hero"></div>