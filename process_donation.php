<?php
// Database configuration
$hostname = "sql107.epizy.com";
$username = "if0_38374977";
$password = "GIHTEk7Qu0Nu";
$dbname   = "if0_38374977_wingslanka_db";
$port     = 3306;

// 1. Capture form data
$donorName     = $_POST['donor_name']   ?? '';
$donorEmail    = $_POST['donor_email']  ?? '';
$amount        = $_POST['amount']       ?? 0;
$message       = $_POST['message']      ?? '';
$paymentMethod = $_POST['payment_method'] ?? 'card';

// Generate orderId for PayHere (this is not stored in DB, since your table has no order_id column)
$orderId  = 'DONATION_' . time();
$currency = 'LKR';

// Handle optional donor image upload
$donorImagePath = "";
if (!empty($_FILES['donor_image']['name'])) {
    $timeStamp = date("YmdHis");
    $ext = pathinfo($_FILES['donor_image']['name'], PATHINFO_EXTENSION);
    $donorImageName = 'donorimg_' . $timeStamp . '.' . $ext;
    $donorImagePath = 'uploads/donors/' . $donorImageName;
    move_uploaded_file($_FILES['donor_image']['tmp_name'], $donorImagePath);
}

// Process based on payment method
if ($paymentMethod === 'bank') {
    // --- BANK TRANSFER ---
    // Ensure a receipt file is provided
    if (empty($_FILES['receipt']['name'])) {
        exit("Receipt is required for bank transfer.");
    }
    // Handle receipt upload
    $receiptPath = "";
    if (!empty($_FILES['receipt']['name'])) {
        $timeStamp = date("YmdHis");
        $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
        $receiptName = 'receipt_' . $timeStamp . '.' . $ext;
        $receiptPath = 'uploads/receipts/' . $receiptName;
        move_uploaded_file($_FILES['receipt']['tmp_name'], $receiptPath);
    }
    
    // Insert donation immediately into the database (without order_id column)
    try {
        $mysqli = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }
        $mysqli->autocommit(false);
        
        $amountFormatted = number_format($amount, 2, '.', '');
        $sql = "INSERT INTO donations
                (donor_name, donor_email, amount, message, payment_method, donor_image, receipt, created_at)
                VALUES
                ('$donorName', '$donorEmail', '$amountFormatted', '$message', 'bank', '$donorImagePath', '$receiptPath', NOW())";
        $mysqli->query($sql);
        if ($mysqli->error) {
            $mysqli->rollback();
            exit("Database error: " . $mysqli->error);
        }
        $mysqli->commit();
        $mysqli->autocommit(true);
        $mysqli->close();
    } catch (Exception $e) {
        exit($e->getMessage());
    }
    
    // Show beautiful thank you page for bank transfer
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <title>WingsLanka - Donation Confirmation</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      
      <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">
      <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
      <link rel="stylesheet" href="css/animate.css">
      <link rel="stylesheet" href="css/owl.carousel.min.css">
      <link rel="stylesheet" href="css/owl.theme.default.min.css">
      <link rel="stylesheet" href="css/magnific-popup.css">
      <link rel="stylesheet" href="css/aos.css">
      <link rel="stylesheet" href="css/ionicons.min.css">
      <link rel="stylesheet" href="css/flaticon.css">
      <link rel="stylesheet" href="css/icomoon.css">
      <link rel="stylesheet" href="css/style.css">
      
      <style>
        .success-checkmark {
          width: 80px;
          height: 80px;
          margin: 0 auto;
          margin-bottom: 20px;
        }
        .success-checkmark .check-icon {
          width: 80px;
          height: 80px;
          position: relative;
          border-radius: 50%;
          box-sizing: content-box;
          border: 4px solid #F96D00;
        }
        .success-checkmark .check-icon::before {
          top: 3px;
          left: -2px;
          width: 30px;
          transform-origin: 100% 50%;
          border-radius: 100px 0 0 100px;
        }
        .success-checkmark .check-icon::after {
          top: 0;
          left: 30px;
          width: 60px;
          transform-origin: 0 50%;
          border-radius: 0 100px 100px 0;
          animation: rotate-circle 4.25s ease-in;
        }
        .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
          content: "";
          height: 100px;
          position: absolute;
          background: #FFFFFF;
          transform: rotate(-45deg);
        }
        .success-checkmark .check-icon .icon-line {
          height: 5px;
          background-color: #F96D00;
          display: block;
          border-radius: 2px;
          position: absolute;
          z-index: 10;
        }
        .success-checkmark .check-icon .icon-line.line-tip {
          top: 46px;
          left: 14px;
          width: 25px;
          transform: rotate(45deg);
          animation: icon-line-tip 0.75s;
        }
        .success-checkmark .check-icon .icon-line.line-long {
          top: 38px;
          right: 8px;
          width: 47px;
          transform: rotate(-45deg);
          animation: icon-line-long 0.75s;
        }
        .success-checkmark .check-icon .icon-circle {
          top: -4px;
          left: -4px;
          z-index: 10;
          width: 80px;
          height: 80px;
          border-radius: 50%;
          position: absolute;
          box-sizing: content-box;
          border: 4px solid rgba(249, 109, 0, 0.3);
        }
        .success-checkmark .check-icon .icon-fix {
          top: 8px;
          width: 5px;
          left: 26px;
          z-index: 1;
          height: 85px;
          position: absolute;
          transform: rotate(-45deg);
          background-color: #FFFFFF;
        }

        @keyframes rotate-circle {
          0% { transform: rotate(-45deg); }
          5% { transform: rotate(-45deg); }
          12% { transform: rotate(-405deg); }
          100% { transform: rotate(-405deg); }
        }
        @keyframes icon-line-tip {
          0% { width: 0; left: 1px; top: 19px; }
          54% { width: 0; left: 1px; top: 19px; }
          70% { width: 50px; left: -8px; top: 37px; }
          84% { width: 17px; left: 21px; top: 48px; }
          100% { width: 25px; left: 14px; top: 46px; }
        }
        @keyframes icon-line-long {
          0% { width: 0; right: 46px; top: 54px; }
          65% { width: 0; right: 46px; top: 54px; }
          84% { width: 55px; right: 0px; top: 35px; }
          100% { width: 47px; right: 8px; top: 38px; }
        }
        .confirmation-card {
          background: white;
          border-radius: 15px;
          box-shadow: 0 10px 30px rgba(0,0,0,0.1);
          padding: 40px;
          margin: 30px auto;
          max-width: 600px;
          text-align: center;
        }
        .confirmation-card h2 {
          color: #F96D00;
          font-size: 2.5rem;
          margin-bottom: 20px;
        }
        .confirmation-details {
          background: #f8f9fa;
          border-radius: 10px;
          padding: 20px;
          margin: 25px 0;
          text-align: left;
        }
        .confirmation-details p {
          margin-bottom: 10px;
          display: flex;
          justify-content: space-between;
          border-bottom: 1px dashed #e9ecef;
          padding-bottom: 10px;
        }
        .confirmation-details p:last-child {
          border-bottom: none;
          padding-bottom: 0;
          margin-bottom: 0;
        }
        .confirmation-details strong {
          color: #495057;
        }
        .btn-return {
          background: linear-gradient(45deg, #F96D00, #ff9100);
          border: none;
          color: white;
          padding: 15px 30px;
          border-radius: 50px;
          font-size: 16px;
          font-weight: bold;
          letter-spacing: 1px;
          margin-top: 20px;
          transition: all 0.3s ease;
          box-shadow: 0 5px 15px rgba(249, 109, 0, 0.3);
        }
        .btn-return:hover {
          transform: translateY(-3px);
          box-shadow: 0 8px 20px rgba(249, 109, 0, 0.4);
        }
        /* Responsive adjustments */
        @media (max-width: 767px) {
          .confirmation-card {
            padding: 20px;
            margin: 20px;
          }
          .confirmation-card h2 {
            font-size: 2rem;
          }
        }
      </style>
    </head>
    <body>
      <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
          <a class="navbar-brand" href="index.php">
            <img src="icons/favicon/android-chrome-512x512.png" alt="*" style="height: 50px; margin-right: 10px;">
            WingsLanka
          </a>
        </div>
      </nav>
      
      <section class="ftco-section" style="background: linear-gradient(135deg, #f9f9f9, #e0f7fa); padding: 80px 0;">
        <div class="container">
          <div class="confirmation-card animate__animated animate__fadeInUp">
            <div class="success-checkmark">
              <div class="check-icon">
                <span class="icon-line line-tip"></span>
                <span class="icon-line line-long"></span>
                <div class="icon-circle"></div>
                <div class="icon-fix"></div>
              </div>
            </div>
            
            <h2>Thank You for Your Support!</h2>
            <p class="lead" style="color: #6c757d;">Your bank transfer donation has been successfully recorded.</p>
            
            <div class="confirmation-details">
              <p><strong>Reference ID:</strong> <span><?php echo htmlspecialchars($orderId); ?></span></p>
              <p><strong>Donation Amount:</strong> <span>LKR <?php echo number_format($amount, 2); ?></span></p>
              <p><strong>Donor Name:</strong> <span><?php echo htmlspecialchars($donorName); ?></span></p>
              <p><strong>Date:</strong> <span><?php echo date("F j, Y, g:i a"); ?></span></p>
              <?php if (!empty($message)): ?>
              <p><strong>Message:</strong> <span><?php echo htmlspecialchars($message); ?></span></p>
              <?php endif; ?>
            </div>
            
            <p style="margin-top: 20px;">A confirmation email has been sent to <strong><?php echo htmlspecialchars($donorEmail); ?></strong></p>
            <p style="color: #6c757d; font-style: italic;">Your generosity helps us make a difference.</p>
            
            <a href="index.php" class="btn btn-return">Return to Homepage</a>
          </div>
        </div>
      </section>
      
      <script src="js/jquery.min.js"></script>
      <script src="js/jquery-migrate-3.0.1.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
    exit;
} else {
    // --- CREDIT/DEBIT CARD ---
    // For card payments, redirect the user to the PayHere portal.
    $merchant_id     = '1229628';
    $merchant_secret = 'ODUxNTQwNDYwMTM4OTY0NjI1NzIxNTUzMDMxMzI0MDc2NjcxODAw';
    $hashedSecret    = strtoupper(md5($merchant_secret));
    $amountFormatted = number_format($amount, 2, '.', '');
    $hash            = strtoupper(md5($merchant_id . $orderId . $amountFormatted . $currency . $hashedSecret));
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <title>WingsLanka - Processing Payment</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      
      <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">
      <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
      <link rel="stylesheet" href="css/animate.css">
      <link rel="stylesheet" href="css/style.css">
      
      <style>
        .processing-container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          background: linear-gradient(135deg, #f9f9f9, #e0f7fa);
          text-align: center;
          padding: 20px;
        }
        .processing-card {
          background: white;
          border-radius: 15px;
          box-shadow: 0 10px 30px rgba(0,0,0,0.1);
          padding: 40px;
          max-width: 500px;
          width: 100%;
        }
        .processing-card h2 {
          color: #F96D00;
          margin-bottom: 20px;
        }
        .loader {
          border: 5px solid #f3f3f3;
          border-radius: 50%;
          border-top: 5px solid #F96D00;
          width: 60px;
          height: 60px;
          margin: 30px auto;
          animation: spin 2s linear infinite;
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        .donation-detail {
          background: #f8f9fa;
          border-radius: 10px;
          padding: 15px;
          margin: 20px 0;
          text-align: left;
        }
        .donation-detail p {
          display: flex;
          justify-content: space-between;
          margin-bottom: 8px;
          border-bottom: 1px dashed #e9ecef;
          padding-bottom: 8px;
        }
        .donation-detail p:last-child {
          border-bottom: none;
          margin-bottom: 0;
          padding-bottom: 0;
        }
      </style>
    </head>
    <body>
      <div class="processing-container">
        <div class="processing-card animate__animated animate__fadeIn">
          <h2>Processing Your Donation</h2>
          <p>You're being redirected to our secure payment gateway.</p>
          
          <div class="loader"></div>
          
          <div class="donation-detail">
            <p><strong>Donor:</strong> <span><?php echo htmlspecialchars($donorName); ?></span></p>
            <p><strong>Amount:</strong> <span>LKR <?php echo number_format($amount, 2); ?></span></p>
            <p><strong>Reference:</strong> <span><?php echo htmlspecialchars($orderId); ?></span></p>
          </div>
          
          <p style="font-size: 14px; color: #6c757d;">Please do not close this window.</p>
          
          <form id="payhere_form" method="post" action="https://sandbox.payhere.lk/pay/checkout">
            <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>">
            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
            <input type="hidden" name="items" value="Donation">
            <input type="hidden" name="currency" value="<?php echo $currency; ?>">
            <input type="hidden" name="amount" value="<?php echo $amountFormatted; ?>">
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($donorName); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($donorEmail); ?>">
            <input type="hidden" name="hash" value="<?php echo $hash; ?>">
            <!-- Add return_url and cancel_url -->
            <input type="hidden" name="return_url" value="https://yoursite.com/payment_success.php">
            <input type="hidden" name="cancel_url" value="https://yoursite.com/payment_cancelled.php">
          </form>
        </div>
      </div>
      
      <script>
        // Submit the form after a short delay to show the processing page
        setTimeout(function() {
          document.getElementById('payhere_form').submit();
        }, 2000);
      </script>
    </body>
    </html>
    <?php
    exit;
}
?>