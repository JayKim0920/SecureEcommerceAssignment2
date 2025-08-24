<?php
// PayPal configuration
define('PAYPAL_ID', 'hlinhlinh131313@gmail.com');

// Set this to TRUE for Sandbox mode
define('PAYPAL_SANDBOX', TRUE);

// PayPal URL based on sandbox mode
define('PAYPAL_URL', (PAYPAL_SANDBOX == true) 
    ? "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr" 
    : "https://ipnpb.paypal.com/cgi-bin/webscr");

// Payment return and cancel URLs
define('PAYPAL_RETURN_URL', 'http://localhost/Assigment2/SecureEcommerceAssignment2/sucess.php');
define('PAYPAL_CANCEL_URL', 'http://localhost/Assigment2/SecureEcommerceAssignment2/cancel.php');
define('PAYPAL_NOTIFY_URL', '	
http://127.0.0.1/Assigment2/SecureEcommerceAssignment2/ipn_listener.php');

// Currency
define('PAYPAL_CURRENCY', 'AUD');
?>
