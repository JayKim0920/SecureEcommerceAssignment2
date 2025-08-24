<?php
// Dummy Mastercard Gateway Simulation

// Get POST data
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$amount = $_POST['amount'];
$card_number = $_POST['card_number'];
$expiry = $_POST['expiry'];
$cvv = $_POST['cvv'];

// Dummy validation
if ($card_number === "5555555555554444" && !empty($expiry) && !empty($cvv)) {
    // Payment success → redirect
    header("Location: sucess.php?status=success&method=mastercard&item=" . urlencode($item_name) . "&amount=" . urlencode($amount));
    exit();
} else {
    // Payment failed → redirect
    header("Location: cancel.php?status=failed&method=mastercard");
    exit();
}
?>
