<?php

// filepath: /d:/ClapTac/Wingslanka/welfare-gh-pages/process_donation.php
// 1. Capture form data (from donate.html)
$donorName   = $_POST['donor_name']   ?? 'John';
$donorEmail  = $_POST['donor_email']  ?? 'john@example.com';
$amount      = $_POST['amount']       ?? 1000; 
$orderId     = 'DONATION_' . time(); // Example unique ID
$currency    = 'LKR';                // Currency used

// 2. PayHere credentials
$merchant_id     = '1229628';        // e.g. 121XXXX
$merchant_secret = 'MTI5NjE5OTI5MDY1MTE2NjY1NzEyMjY2NjEyMDQyNDU5MDk5MzE5'; // Provided secret

// 3. Generate the required hash (server-side only)
$hashedSecret = strtoupper(md5($merchant_secret));            // hashed merchant_secret
$amountFormatted = number_format($amount, 2, '.', '');        // e.g. 1000.00
$hash = strtoupper(md5($merchant_id . $orderId . $amountFormatted . $currency . $hashedSecret));

// 4. Render a hidden form to PayHere with mandatory fields
?>
<!DOCTYPE html>
<html>
  <body>
    <h3>Please wait while we redirect you to PayHere...</h3>
    <form id="payhere_form" method="post" action="https://sandbox.payhere.lk/pay/checkout">
      <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>">
      <input type="hidden" name="return_url" value="https://yourdomain.com/return.php">
      <input type="hidden" name="cancel_url" value="https://yourdomain.com/cancel.php">
      <input type="hidden" name="notify_url" value="https://yourdomain.com/notify.php">

      <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
      <input type="hidden" name="items" value="Donation">
      <input type="hidden" name="currency" value="<?php echo $currency; ?>">
      <input type="hidden" name="amount" value="<?php echo $amountFormatted; ?>">

      <!-- Donor info -->
      <input type="hidden" name="first_name" value="<?php echo $donorName; ?>">
      <input type="hidden" name="last_name" value="">
      <input type="hidden" name="email" value="<?php echo $donorEmail; ?>">
      <input type="hidden" name="phone" value="0771234567">
      <input type="hidden" name="address" value="No.1, Galle Road">
      <input type="hidden" name="city" value="Colombo">
      <input type="hidden" name="country" value="Sri Lanka">

      <!-- The crucial hash field -->
      <input type="hidden" name="hash" value="<?php echo $hash; ?>">
    </form>

    <script>
      // Auto-submit the PayHere checkout form
      document.getElementById('payhere_form').submit();
    </script>
  </body>
</html>