<?php
if (!isset($_REQUEST["PTID"])) {
	$_REQUEST["PTID"] = 1;
}
if (!isset($_REQUEST["PPID"])) {
	$_REQUEST["PPID"] = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ionian Weddings</title>
<link href="css/iw.css" rel="stylesheet" type="text/css" />
<link href="css/ddlevelsmenu-base.css" rel="stylesheet" type="text/css" />
<link href="css/ddlevelsmenu-topbar.css" rel="stylesheet" type="text/css" />
<link href="skins/tn3/tn3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.tn3.min.js"></script>
<script src="js/ddlevelsmenu.js" type="text/javascript">

/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript" src="js/js_ddaccordion.js">
/***********************************************
* Accordion Content script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
* This notice must stay intact for legal use
***********************************************/
</script>
<script type="text/javascript" src="js/js_ddaccordion_config.js"></script>
</head>
<body>
<div class="site">
	<?php include("nav_header.php") ?>
	<div class="main">
		<?php include("nav_dropdown.php") ?>
		<?php
			if (($_REQUEST["PTID"]) == 1) {
				include("tmp_home.php");
			}
			if (($_REQUEST["PTID"]) == 2) {
				include("frm_weddingquestionnaire.php");
			}
			if (($_REQUEST["PTID"]) == 3) {
				include("frm_consultation.php");
			}
			if (($_REQUEST["PTID"]) == 4) {
				include("frm_callback.php");
			}
			if (($_REQUEST["PTID"]) == 5) {
				include("lst_testimonial.php");
			}
			if (($_REQUEST["PTID"]) == 6) {
				include("lst_inspirationandideas.php");
			}
			if (($_REQUEST["PTID"]) == 7) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_packages_aegean.php");
				} else {
					include("tmp_packages_aegean.php");
				}
			}
			if (($_REQUEST["PTID"]) == 8) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_packages_ionian.php");
				} else {
					include("tmp_packages_ionian.php");
				}
			}
			if (($_REQUEST["PTID"]) == 9) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_packages_cyprus.php");
				} else {
					include("tmp_packages_cyprus.php");
				}
			}
			if (($_REQUEST["PTID"]) == 10) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_weddingshowcase.php");
				} else {
					include("tmp_weddingshowcase.php");
				}
			}
			if (($_REQUEST["PTID"]) == 11) {
				include("lst_destination.php");
			}
			if (($_REQUEST["PTID"]) == 12) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_destination_aegean.php");
				} else {
					include("tmp_destination_aegean.php");
				}
			}
			if (($_REQUEST["PTID"]) == 13) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_destination_ionian.php");
				} else {
					include("tmp_destination_ionian.php");
				}
			}
			if (($_REQUEST["PTID"]) == 14) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_destination_cyprus.php");
				} else {
					include("tmp_destination_cyprus.php");
				}
			}
			if (($_REQUEST["PTID"]) == 15) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_typesofceremony.php");
				} else {
					include("tmp_typesofceremony.php");	
				}
			}
			if (($_REQUEST["PTID"]) == 16) {
				include("lst_news.php");
			}
			if ((($_REQUEST["PTID"]) == 17) or (($_REQUEST["PTID"]) == 18)) {
				include("lst_offers.php");
			}
			if (($_REQUEST["PTID"]) == 19) {
				include("lst_packages.php");
			}
			if (($_REQUEST["PTID"]) == 20) {
				if (($_REQUEST["PPID"]) == 0) {
					include("lst_planningadvice.php");
				} else {
					include("tmp_planningadvice.php");
				}
			}
			if (($_REQUEST["PTID"]) == 21) {
				include("tmp_whychooseus.php");
			}
			if (($_REQUEST["PTID"]) == 22) {
				include("tmp_aboutus.php");
			}
			if (($_REQUEST["PTID"]) == 23) {
				include("tmp_faqs.php");
			}
			if (($_REQUEST["PTID"]) == 24) {
				include("frm_contactus.php");
			}			
			if (($_REQUEST["PTID"]) == 25) {
				include("tmp_help.php");
			}
			if (($_REQUEST["PTID"]) == 26) {
				include("tmp_sitemap.php");
			}
			if (($_REQUEST["PTID"]) == 27) {
				include("tmp_privacypolicy.php");
			}
			if (($_REQUEST["PTID"]) == 28) {
				include("tmp_termsandconditions.php");
			}
			if (($_REQUEST["PTID"]) == 29) {
				include("tmp_charity.php");
			}
			if (($_REQUEST["PTID"]) == 30) {
				include("lst_packages_santorini.php");
			}
			if (($_REQUEST["PTID"]) == 31) {
				include("lst_kws.php");
			}
			if (($_REQUEST["PTID"]) == 100) {
				include("frm_invoicepayment.php");
			}
			
		?>
	</div>
	<?php include("nav_footer.php") ?>
</div>
</body>
</html>
