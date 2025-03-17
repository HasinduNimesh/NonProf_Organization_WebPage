<!DOCTYPE html>
<html lang="en">
  <head>
    <title>WingsLanka</title>
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

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Add these styles to the existing <style> tag -->
<style>
  /* Prevent horizontal scrolling */
  html, body {
    overflow-x: hidden;
    width: 100%;
    position: relative;
  }
  
  /* Fixed mobile navigation */
  @media (max-width: 991.98px) {
    .ftco-navbar-light {
      position: relative;
      background: #000 !important;
    }
    
    .ftco-navbar-light .navbar-collapse {
      max-height: 80vh;
      overflow-y: auto;
      padding: 20px;
      background: #000;
      z-index: 1000;
    }
    
    .ftco-navbar-light .navbar-nav > .nav-item > .nav-link {
      padding: 0.7rem 0;
      color: #fff !important;
    }
    
    .navbar-toggler {
      outline: none !important;
      border: none !important;
      padding: 10px;
    }
    
    .navbar-toggler:focus {
      outline: none !important;
      box-shadow: none !important;
    }
    
    /* Make hamburger icon more visible */
    .navbar-toggler span {
      color: white;
      font-size: 24px;
    }
    
    /* Ensure all content stays within the container */
    .container {
      width: 100%;
      padding-left: 15px;
      padding-right: 15px;
    }
    
    /* Fix for donor cards on mobile */
    .staff {
      width: 100%;
    }
  }
</style>
    <!-- Added responsive styles -->
    <style>
      /* Fixed floating labels z-index */
      .form-group.position-relative label {
        z-index: 5;
      }
      
      /* Improved form focus states */
      .form-control:focus {
        border-color: #ff9100 !important;
        box-shadow: 0 0 15px rgba(249, 109, 0, 0.2) !important;
        outline: none;
      }
      
      /* Button hover effects */
      .donate-btn:hover {
        background: linear-gradient(90deg, #ff9100, #F96D00) !important;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(249, 109, 0, 0.6) !important;
      }
      
      /* Form container animation */
      .animated-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      
      .animated-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
      }
      
      /* Button ripple effect */
      @keyframes ripple {
        0% {
          width: 0;
          height: 0;
          opacity: 1;
        }
        100% {
          width: 400px;
          height: 400px;
          opacity: 0;
        }
      }
      
      /* Ensure original functionality is preserved */
      #method_bank:checked ~ #bank_receipt_upload {
        display: block;
      }
      
      /* Responsive styles for mobile */
      @media (max-width: 767px) {
        .animated-box {
          padding: 25px !important;
          margin: 0 10px;
        }
        
        .payment-options {
          flex-direction: column !important;
          gap: 10px !important;
        }
        
        .form-group {
          margin-bottom: 25px;
        }
        
        .form-control, .input-group .form-control {
          font-size: 14px !important;
        }
        
        .input-group-text {
          padding: 0 10px !important;
        }
        
        .donate-btn {
          min-width: 100% !important;
          padding: 12px 20px !important;
        }
        
        /* Larger touch targets for mobile */
        label[for="donor_image"],
        label[for="receipt"] {
          padding: 15px !important;
        }
        
        /* Fix for floating labels on mobile */
        .form-group.position-relative label {
          background: white !important;
          padding: 0 5px !important;
          font-size: 12px !important;
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
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span style="color: white; font-size: 24px;">☰</span> <span style="color: white; font-size: 16px; margin-left: 5px;">Menu</span>
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
          <li class="nav-item active"><a href="donate.php" class="nav-link">Donate</a></li>
          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
          
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->
    
  <div class="hero-wrap" style="background-image: url('images/bg_6.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
        <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
           <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php">Home</a></span> <span>Donate</span></p>
          <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Donations</h1>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Form section with enhanced UI -->
  <section class="ftco-section" style="background: linear-gradient(135deg, #f9f9f9, #e0f7fa); padding: 60px 0;">
    <div class="container">
      <div class="row justify-content-center mb-5 pb-3">
        <div class="col-md-7 heading-section ftco-animate text-center">
          <h2 class="mb-4" style="color: #F96D00; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Make a Donation</h2>
          <p style="color: #555; font-style: italic;">Your small contribution can make a huge impact.</p>
        </div>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-md-8">
          <!-- Animated box wrapper -->
          <div class="animated-box animate__animated animate__zoomIn" style="border: none; padding: 40px; border-radius: 20px; background: linear-gradient(145deg, #ffffff, #f1f8ff); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);">
            <form action="process_donation.php" method="POST" class="donation-form" enctype="multipart/form-data">
              
              <!-- Name field with floating label effect -->
              <div class="form-group position-relative">
                <label for="donor_name" style="color: #F96D00; font-weight: 600; position: absolute; left: 15px; top: -10px; background: white; padding: 0 8px; font-size: 14px; border-radius: 4px; z-index: 5;">Your Name</label>
                <input
                  type="text"
                  id="donor_name"
                  name="donor_name"
                  class="form-control"
                  placeholder="Enter your full name"
                  required
                  style="border: 2px solid #F96D00; border-radius: 12px; padding: 15px; transition: all 0.3s ease; height: auto; font-size: 16px;"
                >
              </div>
            
              <!-- Image upload with preview -->
              <div class="form-group">
                <label for="donor_image" style="color: #F96D00; font-weight: 600; display: block; margin-bottom: 8px;">Upload Your Image (Optional)</label>
                <div style="background: #fff3e6; border: 2px dashed #F96D00; border-radius: 12px; padding: 20px; text-align: center;">
                  <input
                    type="file"
                    id="donor_image"
                    name="donor_image"
                    onchange="previewImage(event)"
                    style="opacity: 0; position: absolute;"
                  >
                  <label for="donor_image" style="cursor: pointer; display: block; margin-bottom: 10px;">
                    <i class="icon-upload" style="font-size: 24px; color: #F96D00;"></i>
                    <span style="display: block; margin-top: 8px; color: #F96D00;">Click to browse</span>
                  </label>
                  <img
                    id="image_preview"
                    src="#"
                    alt="Image Preview"
                    style="display: none; max-width: 120px; max-height: 120px; margin: 15px auto 0; border-radius: 10px; border: 2px solid #F96D00; box-shadow: 0 5px 15px rgba(249, 109, 0, 0.2);"
                  >
                </div>
              </div>
            
              <!-- Email field with floating label -->
              <div class="form-group position-relative">
                <label for="donor_email" style="color: #F96D00; font-weight: 600; position: absolute; left: 15px; top: -10px; background: white; padding: 0 8px; font-size: 14px; border-radius: 4px; z-index: 5;">Your Email</label>
                <input
                  type="email"
                  id="donor_email"
                  name="donor_email"
                  class="form-control"
                  placeholder="Enter your email address"
                  required
                  style="border: 2px solid #F96D00; border-radius: 12px; padding: 15px; transition: all 0.3s ease; height: auto; font-size: 16px;"
                >
              </div>
            
              <!-- Amount field with currency symbol -->
              <div class="form-group position-relative">
                <label for="amount" style="color: #F96D00; font-weight: 600; position: absolute; left: 15px; top: -10px; background: white; padding: 0 8px; font-size: 14px; border-radius: 4px; z-index: 5;">Donation Amount</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" style="background: #F96D00; color: white; border: none; border-radius: 12px 0 0 12px; padding: 0 15px; font-weight: bold;">LKR</span>
                  </div>
                  <input
                    type="number"
                    id="amount"
                    name="amount"
                    class="form-control"
                    placeholder="Enter amount"
                    required
                    style="border: 2px solid #F96D00; border-left: none; border-radius: 0 12px 12px 0; padding: 15px; transition: all 0.3s ease; height: auto; font-size: 16px; position: relative;"
                  >
                </div>
              </div>
            
              <!-- Message field with floating label -->
              <div class="form-group position-relative">
                <label for="message" style="color: #F96D00; font-weight: 600; position: absolute; left: 15px; top: -10px; background: white; padding: 0 8px; font-size: 14px; border-radius: 4px; z-index: 5;">Your Message</label>
                <textarea
                  id="message"
                  name="message"
                  class="form-control"
                  rows="4"
                  placeholder="Share your thoughts or wishes..."
                  style="border: 2px solid #F96D00; border-radius: 12px; padding: 15px; resize: none; transition: all 0.3s ease; font-size: 16px;"
                ></textarea>
              </div>
            
              <!-- Payment methods with better styling -->
              <div class="form-group">
                <label style="color: #F96D00; font-weight: 600; display: block; margin-bottom: 15px;">Select Payment Method</label>
                <div class="payment-options" style="display: flex; gap: 15px;">
                  <div class="payment-option" style="flex: 1; position: relative;">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="payment_method"
                      id="method_card"
                      value="card"
                      checked
                      style="position: absolute; opacity: 0;"
                    >
                    <label class="form-check-label" for="method_card" style="display: block; text-align: center; padding: 15px; border: 2px solid #F96D00; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #fff;">
                      <i class="icon-credit-card" style="display: block; font-size: 24px; margin-bottom: 8px; color: #F96D00;"></i>
                      Credit/Debit Card
                    </label>
                  </div>
                  <div class="payment-option" style="flex: 1; position: relative;">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="payment_method"
                      id="method_bank"
                      value="bank"
                      style="position: absolute; opacity: 0;"
                    >
                    <label class="form-check-label" for="method_bank" style="display: block; text-align: center; padding: 15px; border: 2px solid #ccc; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #fff;">
                      <i class="icon-bank" style="display: block; font-size: 24px; margin-bottom: 8px; color: #ccc;"></i>
                      Bank Transfer
                    </label>
                  </div>
                </div>
              </div>
            
              <!-- Bank receipt upload (initially hidden) -->
              <div class="form-group" id="bank_receipt_upload" style="display: none; margin-top: 20px;">
                <label for="receipt" style="color: #F96D00; font-weight: 600; display: block; margin-bottom: 8px;">Upload Transfer Receipt</label>
                <div style="background: #fff3e6; border: 2px dashed #F96D00; border-radius: 12px; padding: 20px; text-align: center;">
                  <input
                    type="file"
                    id="receipt"
                    name="receipt"
                    style="opacity: 0; position: absolute;"
                  >
                  <label for="receipt" style="cursor: pointer; display: block; margin-bottom: 0;">
                    <i class="icon-upload" style="font-size: 24px; color: #F96D00;"></i>
                    <span style="display: block; margin-top: 8px; color: #F96D00;">Upload receipt (required)</span>
                  </label>
                  <small id="receipt_name" style="display: none; margin-top: 10px; color: #333;"></small>
                </div>
              </div>
            
              <!-- Submit button with improved styling -->
              <div class="form-group text-center mt-5">
                <button
                  type="submit"
                  class="btn donate-btn py-3 px-5"
                  style="background: linear-gradient(90deg, #F96D00, #ff9100); color: #fff; border: none; border-radius: 50px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(249, 109, 0, 0.4); min-width: 200px; position: relative; overflow: hidden;"
                >
                  <span style="position: relative; z-index: 2;">Donate Now</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- JavaScript for enhanced UI interactions -->
  <script>
    function previewImage(event) {
      const imagePreview = document.getElementById('image_preview');
      imagePreview.style.display = 'block';
      imagePreview.src = URL.createObjectURL(event.target.files[0]);
    }

    // Enhanced payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach((elem) => {
      elem.addEventListener('change', function () {
        // Reset all labels
        document.querySelectorAll('.payment-option label').forEach(label => {
          label.style.borderColor = '#ccc';
          label.querySelector('i').style.color = '#ccc';
        });
        
        // Highlight selected
        const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
        selectedLabel.style.borderColor = '#F96D00';
        selectedLabel.querySelector('i').style.color = '#F96D00';
        
        // Show/hide receipt upload
        const bankReceipt = document.getElementById('bank_receipt_upload');
        bankReceipt.style.display = this.value === 'bank' ? 'block' : 'none';
        document.getElementById('receipt').required = this.value === 'bank';
      });
    });
    
    // Show filename when receipt is selected
    document.getElementById('receipt').addEventListener('change', function() {
      const fileNameDisplay = document.getElementById('receipt_name');
      if (this.files.length > 0) {
        fileNameDisplay.textContent = "Selected: " + this.files[0].name;
        fileNameDisplay.style.display = 'block';
      }
    });
    
    // Initialize payment method highlighting
    document.querySelector('input[name="payment_method"]:checked')
      .dispatchEvent(new Event('change'));
      
    // Add ripple effect to button
    document.querySelector('.donate-btn').addEventListener('click', function(e) {
      let ripple = document.createElement('span');
      ripple.style.cssText = `
        position: absolute;
        background: rgba(255,255,255,0.7);
        transform: translate(-50%, -50%);
        pointer-events: none;
        border-radius: 50%;
        animation: ripple 0.8s linear;
      `;
      ripple.style.left = e.offsetX + 'px';
      ripple.style.top = e.offsetY + 'px';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 700);
    });
  </script>

  <!-- Rest of the page content - donor listing section -->
  <?php
// Retrieve latest donations from the database
$hostname = "sql107.epizy.com";
$username = "if0_38374977";
$password = "GIHTEk7Qu0Nu";
$dbname   = "if0_38374977_wingslanka_db";
$port     = 3306;

$mysqli = new mysqli($hostname, $username, $password, $dbname, $port);
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$query = "SELECT donor_name, donor_image, amount, message, payment_method, created_at 
          FROM donations 
          ORDER BY created_at DESC 
          LIMIT 3";

$result = $mysqli->query($query);
?>
<section class="ftco-section bg-light">
  <div class="container">
    <div class="row">
      <?php while($donor = $result->fetch_assoc()): ?>
      <div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
        <div class="staff">
          <div class="d-flex mb-4">
            <div class="img" style="background-image: url('<?php echo !empty($donor['donor_image']) ? $donor['donor_image'] : 'images/default-donor.jpg'; ?>');"></div>
            <div class="info ml-4">
              <h3><a href="#"><?php echo htmlspecialchars($donor['donor_name']); ?></a></h3>
              <span class="position"><?php echo date("M d, Y", strtotime($donor['created_at'])); ?></span>
              <div class="text">
                <p>Donated <span>LKR <?php echo number_format($donor['amount'], 2); ?></span><?php if(!empty($donor['message'])): ?> for <a href="#"><?php echo htmlspecialchars($donor['message']); ?></a><?php endif; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<?php
$mysqli->close();
?>

  <!-- Footer -->
  <footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">About WingsLanka</h2>
                    <p>WingsLanka is committed to empowering children across Sri Lanka through education, healthcare, and community development initiatives.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="https://youtube.com/@wingslankafoundation-ke6dq?si=ub7luS0F3AnZnfMe" target="_blank"><span class="icon-youtube"></span></a></li>
                        <li class="ftco-animate"><a href="https://www.facebook.com/wingslankafoundation/" target="_blank"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="https://instagram.com/wingslanka" target="_blank"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Recent Blog</h2>
                    <?php
                      // Query recent blog posts for footer (limit to 2)
                      $footerBlogQuery = "SELECT id, title, post_date, image FROM blog_posts 
                                        WHERE status = 'published' 
                                        ORDER BY post_date DESC LIMIT 2";
                      // Use the existing mysqli connection
                      $footerBlogResult = $mysqli->query($footerBlogQuery);

                      if ($footerBlogResult && $footerBlogResult->num_rows > 0) {
                          while ($post = $footerBlogResult->fetch_assoc()) {
                              $postId = (int)$post['id'];
                              $postImage = !empty($post['image']) ? 'images/Blog_Projects/' . $post['image'] : 'images/image_1.jpg';
                              $postDate = date('M d, Y', strtotime($post['post_date']));
                      ?>
                    <div class="block-21 mb-4 d-flex">
                        <a class="blog-img mr-4" style="background-image: url(<?php echo $postImage; ?>);"></a>
                        <div class="text">
                            <h3 class="heading"><a href="blog-single.php?id=<?php echo $postId; ?>"><?php echo htmlspecialchars(substr($post['title'], 0, 50)); echo (strlen($post['title']) > 50) ? '...' : ''; ?></a></h3>
                            <div class="meta">
                                <div><a href="blog-single.php?id=<?php echo $postId; ?>"><span class="icon-calendar"></span> <?php echo $postDate; ?></a></div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                    ?>
                    <div class="block-21 mb-4 d-flex">
                        <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
                        <div class="text">
                            <h3 class="heading"><a href="blog.php">Check our blog for updates</a></h3>
                            <div class="meta">
                                <div><a href="blog.php"><span class="icon-calendar"></span> Coming Soon</a></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="ftco-footer-widget mb-4 ml-md-4">
                    <h2 class="ftco-heading-2">Site Links</h2>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="py-2 d-block">Home</a></li>
                        <li><a href="about.html" class="py-2 d-block">About</a></li>
                        <li><a href="donate.php" class="py-2 d-block">Donate</a></li>
                        <li><a href="ongoingProj.php" class="py-2 d-block">Projects</a></li>
                        <li><a href="gallery.php" class="py-2 d-block">Gallery</a></li>
                        <li><a href="blog.php" class="py-2 d-block">Blog</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Contact Us</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">NO. 7/1, Pragathi Mawatha, Homagama, Sri Lanka</span></li>
                            <li><a href="tel:+94112345678"><span class="icon icon-phone"></span><span class="text">+94 71 461 8664</span></a></li>
                            <li><a href="mailto:info@wingslanka.lk"><span class="icon icon-envelope"></span><span class="text">wings@wingslanka.com</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | ClapTac</p>
            </div>
        </div>
    </div>
</footer>
  
  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
  <script>
  // Fix mobile navigation toggle
  document.addEventListener('DOMContentLoaded', function() {
    // Ensure Bootstrap's collapse is working properly
    var navbarToggler = document.querySelector('.navbar-toggler');
    var navbarCollapse = document.getElementById('ftco-nav');
    
    if (navbarToggler && navbarCollapse) {
      // Manually handle the toggle click
      navbarToggler.addEventListener('click', function(e) {
        // Prevent any default behavior that might interfere
        e.preventDefault();
        e.stopPropagation();
        
        // Toggle the 'show' class on the collapse element
        navbarCollapse.classList.toggle('show');
      });
      
      // Close the menu when clicking outside
      document.addEventListener('click', function(e) {
        if (navbarCollapse.classList.contains('show') && 
            !navbarCollapse.contains(e.target) && 
            !navbarToggler.contains(e.target)) {
          navbarCollapse.classList.remove('show');
        }
      });
    }
    
    // Fix any potential horizontal scrolling issues
    function preventHorizontalScroll() {
      document.body.style.overflow = 'hidden';
      setTimeout(function() {
        document.body.style.overflowY = 'auto';
        document.body.style.overflowX = 'hidden';
      }, 100);
    }
    
    // Run once on page load
    preventHorizontalScroll();
    
    // Run on orientation change
    window.addEventListener('orientationchange', preventHorizontalScroll);
  });
</script>
  </body>
</html>