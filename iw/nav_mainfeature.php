<?php
	if (($_REQUEST["PTID"]) == 22) {
		$strMainFeatureImage		=	"i_feature_about_us.jpg";
		$strMainFeatureImageAlt		=	"The happy couple today";
		$strMainFeatureImageTitle	=	"The happy couple today";
		$strMainFeatureCopy			=	"<h1>Above: Andreas &amp; Jane on their wedding day in Corfu, right, the happy couple today</h1>";
		
	}
	if (($_REQUEST["PTID"]) == 15) {
		if (($_REQUEST["PPID"]) == 1) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"Zante Private Island Package";
			$strMainFeatureImageTitle	=	"Zante Private Island Package";
			$strMainFeatureCopy			=	"<h1>Zante Private Island Package</h1><p>What could be more romantic than getting married on a tiny island in the Ionian Sea?</p><p>The beach on this private island is available to hire exclusively for your wedding, the only place in Greece where this is possible.</p>";
		}
		if (($_REQUEST["PPID"]) == 2) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 3) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 4) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 5) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 6) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 7) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 8) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 9) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 10) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
		if (($_REQUEST["PPID"]) == 11) {
			$strMainFeatureImage		=	"i_feature_types_of_ceremony.jpg";
			$strMainFeatureImageAlt		=	"";
			$strMainFeatureImageTitle	=	"";
			$strMainFeatureCopy			=	"";
		}
	}
?>
<div class="mainfeature">
	<div class="mainfeatureimage"><img src="images/page/feature/<?php echo $strMainFeatureImage ?>" alt="<?php echo $strMainFeatureImageAlt ?>" border="0" title="<?php echo $strMainFeatureImageTitle ?>" /></div>
	<div class="mainfeaturecopy">
		<?php echo $strMainFeatureCopy ?>
	</div>
	<div class="clear"></div>
</div>