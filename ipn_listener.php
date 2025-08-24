<?php
// Include configuration file
include_once 'config.php';

// STEP 1: Read POST data from PayPal
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = [];
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}

// Build request string
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $req .= "&$key=" . urlencode($value);
}

// STEP 2: Send back POST data to PayPal for validation
$ch = curl_init(PAYPAL_URL);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$res = curl_exec($ch);
if (!$res) {
    curl_close($ch);
    exit;
}
curl_close($ch);

// STEP 3: Inspect PayPal response
if (strcmp($res, "VERIFIED") == 0) {
    // Payment is successful — validate details
    $item_name        = $_POST['item_name'];
    $item_number      = $_POST['item_number'];
    $payment_status   = $_POST['payment_status'];
    $payment_amount   = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id           = $_POST['txn_id'];
    $receiver_email   = $_POST['receiver_email'];
    $payer_email      = $_POST['payer_email'];

    // Check payment status and receiver email
    if ($payment_status == "Completed" && $receiver_email == PAYPAL_ID) {
        // TODO: Update database, mark order as paid
        file_put_contents("payments.log", "SUCCESS: $txn_id | $payment_amount $payment_currency | Buyer: $payer_email\n", FILE_APPEND);
    } else {
        file_put_contents("payments.log", "PENDING or WRONG RECEIVER: $txn_id\n", FILE_APPEND);
    }
} else if (strcmp($res, "INVALID") == 0) {
    // Invalid IPN — log it
    file_put_contents("payments.log", "INVALID IPN\n", FILE_APPEND);
}
?>
