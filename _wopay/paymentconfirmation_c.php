<?php //Worldpay 

// class definition
class WorldPay_Response {
    // define properties
    public $transaction_id = null;
    public $transaction_status = null;
    public $transaction_time = null;
    public $authorisation_amount = null;
    public $authorisation_currency = null;
    public $authorisation_amount_string = null;
    public $raw_auth_message = null;
    public $raw_auth_code = null;
    public $callback_password = null;
    public $card_type = null;
    public $authentication = null;
    public $ip_address = null;
    public $character_encoding = null;
    public $description = null;
    public $email = null;
    public $cart = null;
	
    //custom properties not included by worldpay
    public $mc_custom_property = null;

    // constructor
    public function __construct() {
        $this->transaction_id = $_POST['transId'];
        $this->transaction_status = $_POST['transStatus']; //should be either Y (successful) or C (cancelled)
        $this->transaction_time = $_POST['transTime'];
        $this->authorisation_amount = $_POST['authAmount'];
        $this->authorisation_currency = $_POST['authCurrency'];
        $this->authorisation_amount_string = $_POST['authAmountString'];
        $this->raw_auth_message = $_POST['rawAuthMessage'];
        $this->raw_auth_code = $_POST['rawAuthCode'];
        $this->callback_password = $_POST['callbackPW'];
        $this->card_type = $_POST['cardType'];
        $this->country_match = $_POST['countryMatch']; //Y - Match, N - Mismatch, B - Not Available, I - Country not supplied, S - Issue Country not available
        $this->waf_merchant_message = $_POST['wafMerchMessage'];
        $this->authentication = $_POST['authentication'];
        $this->ip_address = $_POST['ipAddress'];
        $this->character_encoding = $_POST['charenc'];
        $this->description = $_POST['desc'];
        $this->email = $_POST['email'];
		$this->cart = $_POST['cartId'];

        //custom properties
        $this->mc_custom_property = $_POST['MC_custom_property'];

    }
}

//Response from Worldpay
$wp_response = new WorldPay_Response();

if (!$_POST['transId']) {
	header("Location: http://www.ionianweddings.co.uk/invoice-payment/");
}


if ($wp_response->callback_password==="ionianwe2008") {
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=http://www.ionianweddings.co.uk/payment/?ti=".$wp_response->transaction_id."&ts=".$wp_response->transaction_status."&tt=".$wp_response->transaction_time."&aa=".$wp_response->authorisation_amount."&ac=".$wp_response->authorisation_currency."&au=".$wp_response->authentication."&ip=".$wp_response->ip_address."&ds=".$wp_response->description."&em=".$wp_response->email."&ci=".$wp_response->cart."&ct=".$wp_response->card_type."\">";
	}
else {
header ('Location: http://www.ionianweddings.co.uk');
}
?>