<?php
// WorldPay Hosted Payment Page (HTML Redirect) - Select Junior Integration - paymentToken.php
// Copyright (C) 2013 WorldPay
// Support: support@worldpay.com

// ------------------------------------------------------

// Created:		18/02/2013
// Created By:	Sam Robbins, WorldPay
// Version:		1.0
// Language: 	PHP

// ------------------------------------------------------

// Terms of Use:

// These terms are supplemental to your relevant Merchant Services Agreement with WorldPay and apply to your use of the attached software, code, scripts documentation and files (the "Code"). 

// The Code may be modified without limitation by WorldPay.

// The Code is provided solely for the purpose of integrating the Customer's system with the relevanht WorldPay Gateway and must not be used or modified in any way to allow it to work with any other gateway/payment system other than that which is provided by the WorldPay group of companies.

// ------------------------------------------------------

// Disclaimer: 
// The Code is provided 'as is' without warranty of any kind, either express or implied, including, but not limited to, the implied warranties of fitness for a purpose, or the warranty of non-infringement. Without limiting the foregoing,WorldPay makes no warranty that:
// i.the Code will meet your requirements
// ii.the Code will be uninterrupted, timely, secure or error-free
// iii any errors in the Code obtained will be corrected.

// WorldPay assumes no responsibility for errors or ommissions in the Code.

// In no event shall WorldPay be liable to the Customer or any third parties for any special, punitive, incidental, indirect or consequential damages of any kind, or any damages whatsoever, including, without limitation, those resulting from loss of use, data or profits, whether or not WorldPay has been advised of the possibility of such damages, and on any theory of liability, arising out of or in connection with the use of the Code.

// The use of the Code is at the Customer's own discretion and risk and with agreement that the Customer will be solely responsible for any damage to its computer system or loss of data that results from such activities. No advice or information, whether oral or written, obtained by the Customer from WorldPay shall create any warranty for the Code.
// This code is provided on an "as is" basis and no warranty express or implied is provided. It is the responsibility of the customer to test its implementation and function.

// Any use of the Code shall be deemed to be confirmation of the Customer's agreement to these supplemental terms.

// ------------------------------------------------------
$amount = round($itotals,2);
$paymentType = "MAES";
$SignatureFields = $paymentType.":".$currencyCode.":".$amount.":".$testMode.":".$installationID;

$sql_command->insert("customer_transactions","customer_id,status","'".addslashes($clid)."','Cancelled'");
$iwid = $sql_command->maxid("customer_transactions","p_id");
?>
<form name="paymentToken" method="post" action="<?php echo $urlLink; ?>">
<script>
$(function() {
// Assign Variables
var totalAmount = $('#amount').val();
var sCard = 0;
var cardCharge = false;	
var selectedVal = $("#payType").val();
var selectedPay = 0;
var pmsg = "";
var totalDUE = 0;
var amountPAY = $('#amount').val();
var amount2 = Math.round((amountPAY*0.02)*100)/100;
var amount3 = Math.round((amountPAY*0.03)*100)/100;
var total2 = Number(amountPAY)+Number(amount2);
var total3 = Number(amountPAY)+Number(amount3);	
var description = $("#desc").val();
var lastAm = $("#etotal").attr("data-amount");
var lastDesc = $("#etotal").attr("data-desc");
$("#desc").val(description+" Total "+totalAmount);


function isNumeric(id){
   var numeric = document.getElementById(id);
   var regex  = /^\d+(?:\.\d{0,2})$/;
   if (regex.test(numeric)){
     return true;
   }else{
     return false;
  }
}; 

function priceCheck() {	
	totalDUE = 0;
	amountPAY = $('#damount').val();
	
	$(".totalDUE").text("£ " + totalDUE);
	$.ajax({
		url: "http://www.ionianweddings.co.uk/_includes/paymenttype.php",
		type: "POST",
		data:{ defam: totalAmount, ptamount: amountPAY, lastAmount: lastAm, ptcard: selectedVal, cardch: sCard },
		dataType: 'json',
		success:function(result){
			var signat = result.signat;
			var inst = result.inst_id;
			var fcharge = result.fccharge;
			var samount = result.samount;
			var fstotal = result.fsamount;
			var lasttotal = result.eamount;
			var ftotal = result.ftotal;
			var totalDUE = result.tamount;
			
			
			$("#etotal").text("£ "+lasttotal);
			
			lasttotal = lasttotal.replace(",","");
			
			var changeD = lastDesc.replace(lastAm,lasttotal);
			
			changeDesc = description.replace(lastDesc,changeD);
			
			$('#instId').val(inst);
			$('#signature').val(signat);
			$('#damount').val(samount);
			$("#desc").val(changeDesc+" Total "+samount);
			$("#amount").val(totalDUE);
			$(".totalAmount").text("£ "+fstotal);
			$("#stotal").html("<strong>£ "+fstotal+"</strong>");			
			
			if (cardCharge) {
				$(".cardC").remove();
				$('<div class="formlisttr cardC"><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">'+sCard+'% Card Transaction Surcharge</div><div class="formlisttd cardC"><strong>£ '+fcharge+'</strong></div></div><div class="formlisttr cardC"><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">&nbsp;</div><div class="formlisttd cardC">Grand Total</div><div class="formlisttd cardC"><strong>£ '+ftotal+'</strong></div></div>').insertAfter($('#ccload'));	
			}
			
		}  
	});	
	return this;
};


	$('input[type="radio"][name="amountch"]').change(function() {
			$('#amountrow').toggle();
			if ($(this).val()=="full") {
				$("#damount").val(totalAmount);
				priceCheck();
			}
	});

	$("#damount").blur(function() {
		priceCheck();				
	});
	
	$("#updatep").click(function() {
		priceCheck();				
		return false;
	});
	
	$(".formsubmit").click(function() {
		priceCheck();
	});
	
	
	$("#payType").change(function() {
		var payArray = new Array("VISA", "MSCD", "AMEX", "JCB","DINS","ELV","LASR");
		selectedVal = $(this).val();
		selectedPay = $.inArray(selectedVal, payArray);
		if (selectedPay!=-1) {		
			switch (selectedPay) {
				case 0:
					sCard = 2;
					break;
				case 1:
					sCard = 2;
					break;
				case 2:
					sCard = 3;
					break;
				case 3:	
					sCard = 2;
					break;
				case 4:	
					sCard = 2;
					break;
				case 5:
					sCard = 3;
					break;
				case 6:
					sCard = 3;
					break;
			}
			$("#ptype-msg").hide(); 
			pmsg = "<span class='bold'>Please note:</span> there will be a "+ sCard +"% charge for the use of this card which will be added to the total below automatically.";
			$("#ptype-msg").html(pmsg); 
			$("#ptype-msg").toggle(); 
			cardCharge = true;
			priceCheck();	
			
			//$(".formlist").append('<div class="formelement1 cardC">N\A</div><div class="formelement2 cardC">'+sCard+'% Card Surcharge</div><div class="formelement3 cardC">'+tDate+'</div><div class="formelement4 cardC">'+cardC+'</div><div class="formelement5 cardC">&nbsp;</div><div class=\"clearleft\"></div>');
		//$(".formlist").append('<div class="formlisttr cardC"><div class="formlisttd cardC">'+ sCard+'% Card Surcharge</div><div class="formlisttd cardC">£ '+cardC+'</div></div>');
	
	}
	else { 
		if (cardCharge) { $("#ptype-msg").hide(); $(".cardC").remove(); cardCharge = false; cardCharge = false; sCard=0; }
		priceCheck(); 
	}
}); 
});
</script>
<div class="formheader">
  <h1>Payment</h1>
</div>
<!-- Mandatory Parameters -->
<input type="hidden" name="instId" id="instId" value="<?php echo $installationID; ?>"/>
<!-- Signature Parameter - DO NOT CHANGE -->
<input type="hidden" name="signature" id="signature" value="<?php echo md5($MD5secretKey.":".$SignatureFields); ?>"/>
<input type="hidden" name="hideCurrency" value="true"/>
<div class="formrow">
  <label class="formlabel" for="cartid">Sale ID:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="cartId" readonly="readonly" style="color: #FFFFFF; background:none;border:none;" value="<?php echo $cartPrefix.$iwid; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="paymentType">Payment Method:<span class="required">*</span></label>
  <div class="formelement">
    <select class="formtextfieldlong" id="payType" name="paymentType">
        <option value="" disabled="disabled">--- Debit Cards ---</option>
		<option value="MAES">Maestro</option>
		<option value="DMC">Mastercard Debit</option>
		<option value="VISD">Visa Debit</option>
		<option value="VIED">Visa Electron</option>
        <option value="" disabled="disabled">--- Credit Cards ---</option>
		<option value="VISA">Visa</option>
		<option value="MSCD">Master Card</option>
		<option value="JCB">JCB</option>
<?php 
//<option value="DINS">Diners</option>
 //       <option value="" disabled="disabled">--- Non UK Card ---</option>
//		<option value="AMEX">American Express</option>
//		<option value="ELV">ELV</option>
//		<option value="LASR">Laser</option>

// <option value="VISP">Visa Purchasing</option> 
//<option value="VME">V.me</option> 
?>   
    </select>
  </div>
  <div class="clear"></div>
</div>
<div class="formheader">
      <p id="ptype-msg" style="display:none;">&nbsp;</p>
</div>
<div class="formrow">
  <label class="formlabel" for="amount">Payment Amount:<span class="required">*</span></label>
  <div class="formelement">
  
  <?php 
    if (isset($_POST['payinvoice'])) { 
		echo "<input type=\"radio\" name=\"amountch\" value=\"full\" checked/>";
		echo "Pay in full (<span class=\"totalAmount\">£ ".number_format($amount,2)."</span>)<br />";
	    echo "<input type=\"radio\" name=\"amountch\" value=\"part\" />Part Pay <br /><br /></div><div class=\"clear\"></div></div>";
		echo "<div class=\"formrow\" id=\"amountrow\" style=\"display:none;\"><label class=\"formlabel\" for=\"amount\">Please enter the amount you would like to pay:<span class=\"required\">*</span></label><div class=\"formelement\">";	
		echo "<input type=\"text\" name=\"amount\" id=\"amount\" value=\"".round($itotals, 2)."\" style=\"display:none;\"/>";
	    echo "<input type=\"text\" name=\"damount\" id=\"damount\" value=\"".round($itotals, 2)."\" /><button name=\"update\" id=\"updatep\" value=\"Update\">Update</button></div><div class=\"clear\"></div>";
		
	    echo "</div>";
	
	}
	else {
		echo "Pay in full (<span class=\"totalAmount\">£ ".number_format($amount,2)."</span>)<br /></div><div class=\"clear\"></div></div>";
		echo "<input type=\"text\" name=\"amount\" id=\"amount\" value=\"".round($itotals, 2)."\" style=\"display:none;\"/>";
	}
  ?>
<div class="formheader">
  <h1>Billing Address:</h1>
</div>
<!-- Billing Address Details. By default it will be hidden -->
<?php echo $billingDetails; ?>
<div class="formrow">
  <label class="formlabel" for="name">Name:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="name" value="<?php echo $fname; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="address1">Address 1:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="address1" value="<?php echo $add1; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="address2">Address 2:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="address2" value="<?php echo $add2; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="address3">Address 3:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="address3" value="<?php echo $add3; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="town">Town:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="town" value="<?php echo $town; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="postcode">Postcode:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="postcode" value="<?php echo $pcode; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="tel">Contact Telephone:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="tel" value="<?php echo $contactno; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<div class="formrow">
  <label class="formlabel" for="country">Country:<span class="required">*</span></label>
  <div class="formelement">
<select class="formtextfieldlong" name="country" id="country">
							<option value="GB" selected>United Kingdom</option>
							<option value="US">United States</option>
							<option value="DE">Germany</option>
							<option value="FR">France</option>
							<option value="IE">Ireland</option>
							<option disabled="disabled" value="">----------------------------</option>					
                            <option value="AF" >Afghanistan</option>
                            <option value="AX" >Åland Islands</option>
                            <option value="AL" >Albania</option>
                            <option value="DZ" >Algeria</option>
                            <option value="AS" >American Samoa</option>
                            <option value="AD" >Andorra</option>
                            <option value="AO" >Angola</option>
                            <option value="AI" >Anguilla</option>
                            <option value="AQ" >Antarctica</option>
                            <option value="AG" >Antigua And Barbuda</option>
                            <option value="AR" >Argentina</option>
                            <option value="AM" >Armenia</option>
                            <option value="AW" >Aruba</option>
                            <option value="AU" >Australia</option>
                            <option value="AT" >Austria</option>
                            <option value="AZ" >Azerbaijan</option>
                            <option value="BS" >Bahamas</option>
                            <option value="BH" >Bahrain</option>
                            <option value="BD" >Bangladesh</option>
                            <option value="BB" >Barbados</option>
                            <option value="BY" >Belarus</option>
                            <option value="BE" >Belgium</option>
                            <option value="BZ" >Belize</option>
                            <option value="BJ" >Benin</option>
                            <option value="BM" >Bermuda</option>
                            <option value="BT" >Bhutan</option>
                            <option value="BO" >Bolivia</option>
                            <option value="BA" >Bosnia And Herzegovina</option>
                            <option value="BW" >Botswana</option>
                            <option value="BV" >Bouvet Island</option>
                            <option value="BR" >Brazil</option>
                            <option value="IO" >British Indian Ocean Territory</option>
                            <option value="BN" >Brunei Darussalam</option>
                            <option value="BG" >Bulgaria</option>
                            <option value="BF" >Burkina Faso</option>
                            <option value="BI" >Burundi</option>
                            <option value="KH" >Cambodia</option>
                            <option value="CM" >Cameroon</option>
                            <option value="CA" >Canada</option>
                            <option value="CV" >Cape Verde</option>
                            <option value="KY" >Cayman Islands</option>
                            <option value="CF" >Central African Republic</option>
                            <option value="TD" >Chad</option>
                            <option value="CL" >Chile</option>
                            <option value="CN" >China</option>
                            <option value="CX" >Christmas Island</option>
                            <option value="CC" >Cocos (Keeling) Islands</option>
                            <option value="CO" >Colombia</option>
                            <option value="KM" >Comoros</option>
                            <option value="CG" >Congo</option>
                            <option value="CD" >Congo, The Democratic Republic Of The</option>
                            <option value="CK" >Cook Islands</option>
                            <option value="CR" >Costa Rica</option>
                            <option value="CI" >Côte D'Ivoire</option>
                            <option value="HR" >Croatia</option>
                            <option value="CU" >Cuba</option>
                            <option value="CY" >Cyprus</option>
                            <option value="CZ" >Czech Republic</option>
                            <option value="DK" >Denmark</option>
                            <option value="DJ" >Djibouti</option>
                            <option value="DM" >Dominica</option>
                            <option value="DO" >Dominican Republic</option>
                            <option value="EC" >Ecuador</option>
                            <option value="EG" >Egypt</option>
                            <option value="SV" >El Salvador</option>
                            <option value="GQ" >Equatorial Guinea</option>
                            <option value="ER" >Eritrea</option>
                            <option value="EE" >Estonia</option>
                            <option value="ET" >Ethiopia</option>
                            <option value="FK" >Falkland Islands (Malvinas)</option>
                            <option value="FO" >Faroe Islands</option>
                            <option value="FJ" >Fiji</option>
                            <option value="FI" >Finland</option>
                            <option value="FR" >France</option>
                            <option value="GF" >French Guiana</option>
                            <option value="PF" >French Polynesia</option>
                            <option value="TF" >French Southern Territories</option>
                            <option value="GA" >Gabon</option>
                            <option value="GM" >Gambia</option>
                            <option value="GE" >Georgia</option>
                            <option value="DE" >Germany</option>
                            <option value="GH" >Ghana</option>
                            <option value="GI" >Gibraltar</option>
                            <option value="GR" >Greece</option>
                            <option value="GL" >Greenland</option>
                            <option value="GD" >Grenada</option>
                            <option value="GP" >Guadeloupe</option>
                            <option value="GU" >Guam</option>
                            <option value="GT" >Guatemala</option>
                            <option value="GG" >Guernsey</option>
                            <option value="GN" >Guinea</option>
                            <option value="GW" >Guinea-Bissau</option>
                            <option value="GY" >Guyana</option>
                            <option value="HT" >Haiti</option>
                            <option value="HM" >Heard Island And Mcdonald Islands</option>
                            <option value="VA" >Holy See (Vatican City State)</option>
                            <option value="HN" >Honduras</option>
                            <option value="HK" >Hong Kong</option>
                            <option value="HU" >Hungary</option>
                            <option value="IS" >Iceland</option>
                            <option value="IN" >India</option>
                            <option value="ID" >Indonesia</option>
                            <option value="IR" >Iran, Islamic Republic Of</option>
                            <option value="IQ" >Iraq</option>
                            <option value="IE" >Ireland</option>
                            <option value="IL" >Israel</option>
                            <option value="IT" >Italy</option>
                            <option value="JM" >Jamaica</option>
                            <option value="JP" >Japan</option>
                            <option value="JE" >Jersey</option>
                            <option value="JO" >Jordan</option>
                            <option value="KZ" >Kazakhstan</option>
                            <option value="KE" >Kenya</option>
                            <option value="KI" >Kiribati</option>
                            <option value="KP" >Korea, Democratic People'S Republic Of</option>
                            <option value="KR" >Korea, Republic Of</option>
                            <option value="KW" >Kuwait</option>
                            <option value="KG" >Kyrgyzstan</option>
                            <option value="LA" >Lao People'S Democratic Republic</option>
                            <option value="LV" >Latvia</option>
                            <option value="LB" >Lebanon</option>
                            <option value="LS" >Lesotho</option>
                            <option value="LR" >Liberia</option>
                            <option value="LY" >Libyan Arab Jamahiriya</option>
                            <option value="LI" >Liechtenstein</option>
                            <option value="LT" >Lithuania</option>
                            <option value="LU" >Luxembourg</option>
                            <option value="MO" >Macao</option>
                            <option value="MK" >Macedonia, The Former Yugoslav Republic Of</option>
                            <option value="MG" >Madagascar</option>
                            <option value="MW" >Malawi</option>
                            <option value="MY" >Malaysia</option>
                            <option value="MV" >Maldives</option>
                            <option value="ML" >Mali</option>
                            <option value="MT" >Malta</option>
                            <option value="MH" >Marshall Islands</option>
                            <option value="MQ" >Martinique</option>
                            <option value="MR" >Mauritania</option>
                            <option value="MU" >Mauritius</option>
                            <option value="YT" >Mayotte</option>
                            <option value="MX" >Mexico</option>
                            <option value="FM" >Micronesia, Federated States Of</option>
                            <option value="MD" >Moldova, Republic Of</option>
                            <option value="MC" >Monaco</option>
                            <option value="MN" >Mongolia</option>
                            <option value="MS" >Montserrat</option>
                            <option value="MA" >Morocco</option>
                            <option value="MZ" >Mozambique</option>
                            <option value="MM" >Myanmar</option>
                            <option value="NA" >Namibia</option>
                            <option value="NR" >Nauru</option>
                            <option value="NP" >Nepal</option>
                            <option value="NL" >Netherlands</option>
                            <option value="AN" >Netherlands Antilles</option>
                            <option value="NC" >New Caledonia</option>
                            <option value="NZ" >New Zealand</option>
                            <option value="NI" >Nicaragua</option>
                            <option value="NE" >Niger</option>
                            <option value="NG" >Nigeria</option>
                            <option value="NU" >Niue</option>
                            <option value="NF" >Norfolk Island</option>
                            <option value="MP" >Northern Mariana Islands</option>
                            <option value="NO" >Norway</option>
                            <option value="OM" >Oman</option>
                            <option value="PK" >Pakistan</option>
                            <option value="PW" >Palau</option>
                            <option value="PS" >Palestinian Territory, Occupied</option>
                            <option value="PA" >Panama</option>
                            <option value="PG" >Papua New Guinea</option>
                            <option value="PY" >Paraguay</option>
                            <option value="PE" >Peru</option>
                            <option value="PH" >Philippines</option>
                            <option value="PN" >Pitcairn</option>
                            <option value="PL" >Poland</option>
                            <option value="PT" >Portugal</option>
                            <option value="PR" >Puerto Rico</option>
                            <option value="QA" >Qatar</option>
                            <option value="RE" >Réunion</option>
                            <option value="RO" >Romania</option>
                            <option value="RU" >Russian Federation</option>
                            <option value="RW" >Rwanda</option>
                            <option value="BL" >Saint Barthelemy</option>
                            <option value="SH" >Saint Helena</option>
                            <option value="KN" >Saint Kitts And Nevis</option>
                            <option value="LC" >Saint Lucia</option>
                            <option value="PM" >Saint Pierre And Miquelon</option>
                            <option value="VC" >Saint Vincent And The Grenadines</option>
                            <option value="WS" >Samoa</option>
                            <option value="SM" >San Marino</option>
                            <option value="ST" >Sao Tome And Principe</option>
                            <option value="SA" >Saudi Arabia</option>
                            <option value="SN" >Senegal</option>
                            <option value="CS" >Serbia And Montenegro</option>
                            <option value="SC" >Seychelles</option>
                            <option value="SL" >Sierra Leone</option>
                            <option value="SG" >Singapore</option>
                            <option value="SK" >Slovakia</option>
                            <option value="SI" >Slovenia</option>
                            <option value="SB" >Solomon Islands</option>
                            <option value="SO" >Somalia</option>
                            <option value="ZA" >South Africa</option>
                            <option value="GS" >South Georgia And The South Sandwich Islands</option>
                            <option value="ES" >Spain</option>
                            <option value="LK" >Sri Lanka</option>
                            <option value="SD" >Sudan</option>
                            <option value="SR" >Suriname</option>
                            <option value="SJ" >Svalbard And Jan Mayen</option>
                            <option value="SZ" >Swaziland</option>
                            <option value="SE" >Sweden</option>
                            <option value="CH" >Switzerland</option>
                            <option value="SY" >Syrian Arab Republic</option>
                            <option value="TW" >Taiwan, Province Of China</option>
                            <option value="TJ" >Tajikistan</option>
                            <option value="TZ" >Tanzania, United Republic Of</option>
                            <option value="TH" >Thailand</option>
                            <option value="TL" >Timor-Leste</option>
                            <option value="TG" >Togo</option>
                            <option value="TK" >Tokelau</option>
                            <option value="TO" >Tonga</option>
                            <option value="TT" >Trinidad And Tobago</option>
                            <option value="TN" >Tunisia</option>
                            <option value="TR" >Turkey</option>
                            <option value="TM" >Turkmenistan</option>
                            <option value="TC" >Turks And Caicos Islands</option>
                            <option value="TV" >Tuvalu</option>
                            <option value="UG" >Uganda</option>
                            <option value="UA" >Ukraine</option>
                            <option value="AE" >United Arab Emirates</option>
                            <option value="GB" >United Kingdom</option>
                            <option value="US" >United States</option>
                            <option value="UM" >United States Minor Outlying Islands</option>
                            <option value="UY" >Uruguay</option>
                            <option value="UZ" >Uzbekistan</option>
                            <option value="VU" >Vanuatu</option>
                            <option value="VA" >Vatican City State - Refer To Holy See</option>
                            <option value="VE" >Venezuela</option>
                            <option value="VN" >Viet Nam</option>
                            <option value="VG" >Virgin Islands, British</option>
                            <option value="VI" >Virgin Islands, U.S.</option>
                            <option value="WF" >Wallis And Futuna</option>
                            <option value="EH" >Western Sahara</option>
                            <option value="YE" >Yemen</option>
                            <option value="ZM" >Zambia</option>
                            <option value="ZW" >Zimbabwe</option>
							</select>  
  </div>
  <div class="clear"></div>
</div>
<!--<div class="formrow">
  <label class="formlabel" for="fax">Fax:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="fax" value=""/>
  </div>
  <div class="clear"></div>
</div>-->
<div class="formrow">
  <label class="formlabel" for="email">Email:<span class="required">*</span></label>
  <div class="formelement">
    <input class="formtextfieldlong" type="text" name="email" value="<?php echo $emaila; ?>"/>
  </div>
  <div class="clear"></div>
</div>
<?php echo $billingClose; ?>
<!-- Delivery Address Details -->
<input type="hidden" name="withDelivery" value="<?php echo $delivery; ?>" />
<?php echo $deliveryDetails; ?>
<input type="hidden" name="delvName" value="Delivery Address line 1"/>
<input type="hidden" name="delvAddress1" value="Delivery Address line 1"/>
<input type="hidden" name="delvAddress2" value="Delivery Address line 2"/>
<input type="hidden" name="delvAddress3" value="Delivery Address line 3"/>
<input type="hidden" name="delvTown" value="Delivery Town"/>
<input type="hidden" name="delvPostcode" value="Delivery Postcode"/>
<input type="hidden" name="delvCountry" value="GB"/>
<?php echo $deliveryClose; ?>
<!-- Fix and Hide contact details -->
<input type="hidden" name="fixContact" value="<?php echo $fixContact; ?>"/>
<input type="hidden" name="hideContact" value="<?php echo $hideContact; ?>"/>
<input type="hidden" name="tdate" id="tdate" value="<?php date_default_timezone_set('GMT'); echo date("d-m-Y"); ?>" />
<input type="hidden" name="currency" value="<?php echo $currencyCode; ?>"/>
<input type="hidden" name="testMode" value="<?php echo $testMode; ?>"/>
<input id="desc" type="hidden" name="desc" value="<?php echo $desc; ?>"/>
<input type="hidden" name="authMode" value="<?php echo $authMode; ?>"/>
<input type="hidden" name="accId1" value="<?php echo $preferredMerchantAccount1; ?>"/>

