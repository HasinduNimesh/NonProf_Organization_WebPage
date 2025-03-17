<?php
// Add this at the top of your contact.php file after the opening <?php tag
// Database Connection
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    // We'll continue without the database connection and show placeholder content
    $databaseError = true;
} else {
    $databaseError = false;
}
?>
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
    <style>
      @media (max-width: 991.98px) {
        .navbar-collapse.show {
          display: block !important;
        }
        
        #ftco-nav {
          background-color: #000;
          padding: 20px;
          position: absolute;
          top: 100%;
          left: 0;
          right: 0;
          z-index: 1030;
          transition: all 0.3s ease;
          box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        #ftco-nav ul {
          padding-left: 0;
        }
        
        #ftco-nav.show {
          max-height: 400px;
          opacity: 1;
          visibility: visible;
        }
        
        #ftco-nav:not(.show) {
          max-height: 0;
          opacity: 0;
          visibility: hidden;
          overflow: hidden;
        }
      }
    </style>
    <style>
      .heading-underline {
        width: 80px;
        height: 3px;
        background-color: #f86f2d;
        margin-top: 15px;
        margin-bottom: 30px;
      }
      
      .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(248, 111, 45, 0.1);
      }
      
      .icon-circle .icon {
        font-size: 28px;
      }
      
      .contact-card {
        transition: all 0.3s ease;
        border: none;
      }
      
      .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
      }
      
      .contact-form .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 12px 15px;
        font-size: 16px;
        transition: all 0.3s ease;
      }
      
      .contact-form .form-control:focus {
        box-shadow: 0 0 0 3px rgba(248, 111, 45, 0.2);
        border-color: #f86f2d;
      }
      
      .contact-form label {
        font-weight: 500;
        margin-bottom: 5px;
      }
      
      .contact-form button {
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(248, 111, 45, 0.3);
        transition: all 0.3s ease;
      }
      
      .contact-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(248, 111, 45, 0.4);
      }
      
      @media (max-width: 767px) {
        .col-md-6 {
          margin-bottom: 30px;
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
        <!-- Updated toggle button with both Bootstrap 4 and 5 data attributes -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse"
                data-target="#ftco-nav" data-bs-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
          <li class="nav-item"><a href="donate.php" class="nav-link">Donate</a></li>
          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
          
          <li class="nav-item active"><a href="contact.php" class="nav-link">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
    <!-- END nav -->
    
    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php">Home</a></span> <span>Contact</span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Contact Us</h1>
          </div>
        </div>
      </div>
    </div>

    
    <section class="ftco-section contact-section ftco-degree-bg">
      <div class="container">
        <!-- Contact Information Cards -->
        <div class="row mb-5">
          <div class="col-md-12 mb-4 text-center">
            <h2 class="h2 font-weight-bold">Get in Touch</h2>
            <p class="text-muted">We're here to answer your questions about WingsLanka Foundation</p>
            <div class="heading-underline mx-auto"></div>
          </div>
          
          <!-- Contact Info Cards -->
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm rounded contact-card">
              <div class="card-body text-center">
                <div class="icon-circle mb-3">
                  <span class="icon icon-map-marker text-primary"></span>
                </div>
                <h5 class="card-title">Address</h5>
                <p class="card-text">NO. 7/1, Pragathi Mawatha,<br>Homagama, Sri Lanka</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm rounded contact-card">
              <div class="card-body text-center">
                <div class="icon-circle mb-3">
                  <span class="icon icon-phone text-primary"></span>
                </div>
                <h5 class="card-title">Phone</h5>
                <p class="card-text"><a href="tel://+94714618664" class="text-body">+94 71 461 8664</a></p>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm rounded contact-card">
              <div class="card-body text-center">
                <div class="icon-circle mb-3">
                  <span class="icon icon-envelope text-primary"></span>
                </div>
                <h5 class="card-title">Email</h5>
                <p class="card-text"><a href="mailto:wings@wingslanka.com" class="text-body">wings@wingslanka.com</a></p>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm rounded contact-card">
              <div class="card-body text-center">
                <div class="icon-circle mb-3">
                  <span class="icon icon-globe text-primary"></span>
                </div>
                <h5 class="card-title">Website</h5>
                <p class="card-text"><a href="https://wingslanka.com" class="text-body">wingslanka.com</a></p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Form and Map Row -->
        <div class="row block-9">
          <!-- Contact Form -->
          <div class="col-md-6 pr-md-5">
            <div class="card shadow-sm p-4 rounded">
              <h4 class="mb-4 font-weight-bold">Send Us a Message</h4>
              
              <?php if(isset($_GET['success'])): ?>
              <div class="alert alert-success">
                <i class="icon icon-check mr-2"></i> Thank you for your message! We'll get back to you shortly.
              </div>
              <?php endif; ?>
              
              <?php 
              if(isset($_GET['error'])) {
                $errors = explode(',', $_GET['error']);
              ?>
              <div class="alert alert-danger">
                <i class="icon icon-warning mr-2"></i><strong>Please fix the following errors:</strong>
                <ul>
                  <?php if(in_array('name', $errors)): ?>
                    <li>Please enter your name</li>
                  <?php endif; ?>
                  <?php if(in_array('email', $errors)): ?>
                    <li>Please enter a valid email address</li>
                  <?php endif; ?>
                  <?php if(in_array('subject', $errors)): ?>
                    <li>Please enter a subject</li>
                  <?php endif; ?>
                  <?php if(in_array('message', $errors)): ?>
                    <li>Please enter a message</li>
                  <?php endif; ?>
                  <?php if(in_array('db', $errors)): ?>
                    <li>There was a problem processing your message. Please try again later.</li>
                  <?php endif; ?>
                </ul>
              </div>
              <?php } ?>
              
              <form action="process_contact.php" method="post" class="contact-form">
                <div class="form-group">
                  <label for="name" class="small text-muted">Your Name</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                  <label for="email" class="small text-muted">Your Email</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                  <label for="subject" class="small text-muted">Subject</label>
                  <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="form-group">
                  <label for="message" class="small text-muted">Your Message</label>
                  <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Write your message here..." required></textarea>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary py-3 px-5 rounded-pill">
                    <i class="icon icon-paper-plane mr-2"></i> Send Message
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Google Maps Embed -->
          <div class="col-md-6">
            <div class="card shadow-sm p-2 rounded h-100">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.4488748028293!2d80.004329!3d6.836662899999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2516d33ff554b%3A0x53623f6bd1d5f1dc!2sWings%20Lanka%20Solidarity%20Foundation!5e0!3m2!1sen!2slk!4v1742240321943!5m2!1sen!2slk" 
                width="100%" height="100%" style="border:0; min-height: 450px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>  

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
              <!-- In the footer section replace the blog query section with this: -->
              <div class="col-md-4">
                  <div class="ftco-footer-widget mb-4">
                      <h2 class="ftco-heading-2">Recent Blog</h2>
                      <?php
                      // Only try to query database if connection was successful
                      if (!isset($databaseError) || !$databaseError) {
                          // Query recent blog posts for footer (limit to 2)
                          $footerBlogQuery = "SELECT id, title, post_date, image FROM blog_posts 
                                            WHERE status = 'published' 
                                            ORDER BY post_date DESC LIMIT 2";
                          $footerBlogResult = $conn->query($footerBlogQuery);
                          
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
                      <?php 
                          }
                      } else {
                          // Show placeholder content if database connection failed
                      ?>
                      <div class="block-21 mb-4 d-flex">
                          <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
                          <div class="text">
                              <h3 class="heading"><a href="blog.php">Visit our blog</a></h3>
                              <div class="meta">
                                  <div><a href="blog.php"><span class="icon-calendar"></span> See latest posts</a></div>
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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
  <script>
    $(document).ready(function() {
      // Fix mobile navigation toggle button
      $('.navbar-toggler').on('click', function() {
        $('#ftco-nav').toggleClass('show');
      });
      
      // Close menu when clicking a link (optional but improves UX)
      $('#ftco-nav .nav-link').on('click', function() {
        if ($(window).width() < 992) {
          $('#ftco-nav').removeClass('show');
        }
      });
    });
  </script>
  </body>
</html>