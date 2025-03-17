<?php
// Database Connection
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
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
    <!-- Add this to your existing <style> section in the head -->
<style>
  /* Modern card styling */
  .about-card {
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
    background: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .about-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
  }
  
  /* Modern typography */
  h2.section-title {
    font-size: 2.2rem;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 15px;
    color: #f86f2d;
  }
  
  h2.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 60px;
    background-color: #4e9525;
    border-radius: 2px;
  }
  
  /* Improved list styling */
  .objectives-list li {
    padding: 8px 0;
    position: relative;
    padding-left: 25px;
  }
  
  .objectives-list li:before {
    content: '✓';
    color: #4e9525;
    position: absolute;
    left: 0;
    font-weight: bold;
  }
  
  /* Improved background for sections */
  .about-section {
    background-color: #f8f9fa;
    padding: 60px 0;
  }
  
  .vision-mission {
    background-color: rgba(78, 149, 37, 0.05);
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
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
        <!-- Update these data attributes to newer Bootstrap syntax -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item active"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
          <li class="nav-item"><a href="donate.php" class="nav-link">Donate</a></li>
          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
          <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
          
          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->
    
  <div class="hero-wrap" style="background-image: url('images/bg_7.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
        <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
          <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
            <span class="mr-2"><a href="index.php">Home</a></span> <span>About</span>
          </p>
          <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">About Us</h1>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Merge the section with the container to avoid extra spacing -->
  <section class="ftco-section about-section" style="background-color: #f8f9fa;">
<!-- Replace the "Reason for our existence" section with this code: -->
    <div class="container mt-5">
      <div class="row d-flex">
        <!-- Left column with an image -->
        <div class="col-md-6 d-flex ftco-animate">
          <div class="about-card">
            <div class="img img-about align-self-stretch" style="width: 100%; height: 400px; overflow: hidden;">
              <img src="images/image_5.jpg" alt="Our Purpose" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
        </div>
        
        <!-- Right column with description -->
        <div class="col-md-6 pl-md-5 ftco-animate">
          <h2 class="section-title">Reason for our existence</h2>
          <p>
            The marginalized people and special needs children in Sri Lanka need support and
            resources to overcome the challenges and barriers hindering their development and
            opportunities for a brighter future. The Wings Lanka provides specialized care, education,
            and resources to these exceptional community. We strongly believe that every child,
            regardless of their abilities, deserves a chance to thrive and fulfill their potential.
          </p>
          <p>
            Our aim is to help these children and people to overcome their limitations and develop
            crucial skills that will empower them to become independent and active members of
            society. Your collaboration will make a lasting difference in the lives of these children,
            providing them with opportunities for growth, education, and inclusion. Together, we can
            break down barriers, promote acceptance, and create a more inclusive society for special
            needs children in Sri Lanka.
          </p>
        </div>
      </div>
    </div>
    <br>
    <br>
  <!-- New content section continues below with same background -->
    <div class="container">
      <div class="row d-flex">
        <div class="col-md-12 ftco-animate">
          <h2 class="mb-4">Reason for our existence</h2>
          <p>
            The marginalized people and special needs children in Sri Lanka need support and
            resources to overcome the challenges and barriers hindering their development and
            opportunities for a brighter future. The Wings Lanka provides specialized care, education,
            and resources to these exceptional community. We strongly believe that every child,
            regardless of their abilities, deserves a chance to thrive and fulfill their potential.
            Our aim is to help these children and people to overcome their limitations and develop
            crucial skills that will empower them to become independent and active members of
            society. Your collaboration will make a lasting difference in the lives of these children,
            providing them with opportunities for growth, education, and inclusion. Together, we can
            break down barriers, promote acceptance, and create a more inclusive society for special
            needs children in Sri Lanka.
          </p>
          <br>

          <div class="container mt-5">
            <div class="row">
              <div class="col-md-12">
                <h2 class="section-title">OUR VISION & MISSION</h2>
                <div class="row">
                  <div class="col-md-6">
                    <div class="vision-mission">
                      <h4 class="mb-3">VISION</h4>
                      <p>
            To cultivate an environmentally conscious and resilient generation, fostering equal rights
            and developmental opportunities for marginalized individuals and children with special needs
            within our society.
          </p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="vision-mission">
          <h4 class="mb-3">MISSION</h4>
          <p>
            Our mission is to create an inclusive and sustainable society by promoting environmental
            awareness and resilience among all individuals. We strive to ensure equal rights and provide
            developmental opportunities for marginalized individuals and children with special needs,
            empowering them to thrive and contribute to a brighter future.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- For the Objectives section -->
<div class="container mt-5">
<div class="row">
  <div class="col-md-12">
    <h2 class="section-title">OUR OBJECTIVES</h2>
    <ul class="objectives-list">
            <li>a) Supporting the National Development Agenda through different programs on poverty alleviation.</li>
            <li>b) Assisting marginalized groups in society, with a special emphasis on children with special needs.</li>
            <li>c) Ensuring the welfare and well-being of individuals facing various health issues.</li>
            <li>d) Assisting the returnee labor migrants from Sri Lanka in successfully reintegrating into society.</li>
            <li>e) Engaging in environmentally friendly activities that contribute to the development of climate-resilient services.</li>
          </ul>
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
                $footerBlogResult = $conn->query($footerBlogQuery);
                
                if (!$footerBlogResult) {
                    // Log error but don't display to users
                    error_log("Blog query error: " . $conn->error);
                }
                
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
                        <li><a href="about.php" class="py-2 d-block">About</a></li>
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
  <div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
    </svg>
  </div>

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
    $(document).ready(function() {
      // Fix for mobile navigation toggle button
      $('.navbar-toggler').on('click', function() {
        $('#ftco-nav').toggleClass('show');
      });
    });
  </script>
  </body>
</html>