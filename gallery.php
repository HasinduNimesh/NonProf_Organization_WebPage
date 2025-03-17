<?php
// Connect to the database
$mysqli = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to fetch images from blog_images table
$galleryQuery = "SELECT 
                    id, 
                    post_id,
                    image_path,
                    caption
                FROM blog_images
                ORDER BY display_order, id";
                
// Execute query with error handling
$galleryResult = $mysqli->query($galleryQuery);

// Store all images in an array
$allImages = array();

if (!$galleryResult) {
    echo "<div class='container mt-5'>";
    echo "<div class='alert alert-danger'>";
    echo "<h4>Database Error:</h4>";
    echo "<p>" . $mysqli->error . "</p>";
    echo "</div></div>";
} else {
    // Process results
    if ($galleryResult->num_rows > 0) {
        while ($row = $galleryResult->fetch_assoc()) {
            // Add image path to array
            if (!empty($row['image_path'])) {
                $allImages[] = $row['image_path'];
            }
        }
    }
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
          <li class="nav-item active"><a href="gallery.php" class="nav-link">Gallery</a></li>
          
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
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
             <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.php">Home</a></span> <span>Gallery</span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Galleries</h1>
          </div>
        </div>
      </div>
    </div>
    <section class="ftco-section ftco-gallery">
      <div class="container">
          <?php
          // Check if we have images
          if (count($allImages) > 0) {
              // Chunk the images into rows of 4
              $imageRows = array_chunk($allImages, 4);
              
              // Loop through each row
              foreach ($imageRows as $row) {
                  echo '<div class="d-md-flex">';
                  
                  // Loop through each image in row
                  foreach ($row as $image) {
                    // Ensure image path is correct - adjust this based on how paths are stored
                    $imagePath = (strpos($image, 'http') === 0) ? $image : // If it's a full URL
                               ((strpos($image, 'images/') === 0) ? $image : 'images/Blog_Projects/' . $image);
                    ?>
                    <a href="<?php echo $imagePath; ?>" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url('<?php echo $imagePath; ?>');">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <span class="icon-search"></span>
                        </div>
                    </a>
                    <?php
                }
                  
                  // If less than 4 images in this row, add empty placeholders to maintain layout
                  for ($i = count($row); $i < 4; $i++) {
                      ?>
                      <div class="gallery d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/placeholder.jpg);">
                      </div>
                      <?php
                  }
                  
                  echo '</div>';
              }
          } else {
              // Fallback for no images
              ?>
              <div class="row justify-content-center">
                  <div class="col-md-8 text-center">
                      <h2>No gallery images found</h2>
                      <p>Check back soon for new galleries of our projects and events.</p>
                  </div>
              </div>
              <?php
          }
          ?>
      </div>
  </section>

    <section class="ftco-section-3 img" style="background-image: url(images/bg_3.jpg);">
    	<div class="overlay"></div>
    	<div class="container">
    		<div class="row d-md-flex">
    		<div class="col-md-6 d-flex ftco-animate">
    			<div class="img img-2 align-self-stretch" style="background-image: url(images/bg_4.jpg);"></div>
    		</div>
    		<div class="col-md-6 volunteer pl-md-5 ftco-animate">
        <h3 class="mb-3">Be a volunteer</h3>
          <?php if(isset($_GET['volunteer_submitted'])): ?>
          <div class="alert alert-success">
              Thank you for your interest in volunteering! We will contact you soon.
          </div>
          <?php elseif(isset($_GET['volunteer_error'])): ?>
          <div class="alert alert-danger">
              <?php 
              $error = $_GET['volunteer_error'];
              if ($error === 'missing_fields') {
                  echo "Please fill in all required fields.";
              } elseif ($error === 'invalid_email') {
                  echo "Please enter a valid email address.";
              } elseif ($error === 'invalid_phone') {
                  echo "Please enter a valid phone number.";
              } elseif ($error === 'invalid_phone_sl') {
                  echo "Please enter a valid Sri Lankan phone number (9 digits without leading zero).";
              } else {
                  echo "An error occurred. Please try again.";
              }
              ?>
          </div>
          <?php endif; ?>
          <form action="process_volunteer.php" method="post" class="volunter-form">
              <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required>
              </div>
              <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Your Email" required>
              </div>
              <div class="form-group">
                  <select name="country" class="form-control">
                      <option value="Sri Lanka" selected>Sri Lanka</option>
                      <option value="Other">Other</option>
                  </select>
              </div>
              <div class="form-group">
                  <textarea name="message" cols="30" rows="3" class="form-control" placeholder="Message"></textarea>
              </div>
              <div class="form-group">
                  <input type="submit" value="Send Message" class="btn btn-white py-3 px-5">
              </div>
          </form>
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
            <div class="col-md-4">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Recent Blog</h2>
                    <?php
                    // Query recent blog posts for footer (limit to 2)
                    $footerBlogQuery = "SELECT id, title, post_date, image FROM blog_posts 
                                      WHERE status = 'published' 
                                      ORDER BY post_date DESC LIMIT 2";
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