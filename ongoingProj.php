<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all projects ordered by created_at descending
$sqlProjects = "SELECT * FROM project ORDER BY created_at DESC";
$resultProjects = $conn->query($sqlProjects);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>WingsLanka - Ongoing Projects</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Google Fonts - Updated with more modern fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">
    
    <!-- CSS Files -->
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
    
    <!-- Additional responsive styling for the navbar and projects -->
    <style>
      .navbar-toggler {
        border: none;
      }
      .navbar-toggler:focus {
        outline: none;
      }
      @media (max-width: 991px) {
        .navbar-collapse {
          background: #343a40;
        }
        .navbar-nav .nav-link {
          color: #fff;
          padding: 10px 15px;
        }
      }
      /* Project Card Styling */
      .cause-entry {
        border: 1px solid #f1f1f1;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
      }
      .cause-entry:hover {
        transform: translateY(-5px);
      }
      .cause-entry .img {
        height: 250px;
        background-size: cover;
        background-position: center;
      }
      .cause-entry .text {
        padding: 20px;
      }
      .progress {
        height: 10px;
        margin-top: 10px;
      }
    </style>
        <style>
      :root {
        --primary: #f86f2d;
        --secondary: #4e9525;
        --dark: #343a40;
        --light: #f8f9fa;
        --light-gray: #eaeaea;
        --medium-gray: #6c757d;
      }
      
      /* Navbar styling - keeping your existing code */
      .navbar-toggler {
        border: none;
      }
      .navbar-toggler:focus {
        outline: none;
      }
      @media (max-width: 991px) {
        .navbar-collapse {
          background: #343a40;
        }
        .navbar-nav .nav-link {
          color: #fff;
          padding: 10px 15px;
        }
      }
      
      /* Enhanced Project Card Styling */
      .cause-entry {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 30px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      }
      
      .cause-entry:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
      }
      
      .cause-entry .img {
        height: 220px;
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
      }
      
      .cause-entry .img::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
        opacity: 0;
        transition: all 0.3s;
      }
      
      .cause-entry:hover .img::before {
        opacity: 1;
      }
      
      .cause-entry .text {
        padding: 25px;
      }
      
      .cause-entry h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 1.4;
      }
      
      .cause-entry h3 a {
        color: var(--dark);
        transition: all 0.3s;
      }
      
      .cause-entry h3 a:hover {
        color: var(--primary);
        text-decoration: none;
      }
      
      .cause-entry p {
        color: var(--medium-gray);
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      
      /* Enhanced Progress Bar */
      .donation-time {
        font-size: 0.9rem;
        color: var(--medium-gray);
        font-style: italic;
        margin-bottom: 15px;
        display: block;
      }
      
      .progress {
        height: 8px;
        border-radius: 10px;
        background-color: var(--light-gray);
        margin-bottom: 12px;
        overflow: hidden;
      }
      
      .progress-bar {
        background: linear-gradient(to right, var(--secondary), var(--primary));
        border-radius: 10px;
      }
      
      .fund-raised {
        font-size: 1rem;
        color: var(--dark);
        font-weight: 500;
      }
      
      .donation-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
      }
      
      .donate-now {
        display: inline-block;
        background: var(--primary);
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        margin-top: 10px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
      }
      
      .donate-now:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        color: white;
        text-decoration: none;
      }
      
      /* Empty state styling */
      .empty-state {
        text-align: center;
        padding: 50px 20px;
      }
      
      .empty-state h3 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: var(--dark);
      }
      
      .empty-state p {
        color: var(--medium-gray);
        max-width: 500px;
        margin: 0 auto;
      }
    </style>
        <!-- Additional styling to fix project card layout -->
        <style>
      /* Fix for image overlapping text issue */
      .cause-entry {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 30px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column; /* Ensure proper stacking */
      }
      
      .cause-entry .img {
        height: 220px;
        background-size: cover;
        background-position: center;
        position: relative;
        width: 100%; /* Ensure full width */
        display: block; /* Prevent inline behavior */
        flex: 0 0 auto; /* Don't allow flex to resize this */
      }
      
      .cause-entry .text {
        padding: 25px;
        position: relative; /* Ensure proper stacking context */
        z-index: 1; /* Place above the image */
        background: white; /* Ensure text background is opaque */
        flex: 1; /* Take remaining space */
      }
      
      /* Keep your category badge positioned properly */
      .badge-category {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary);
        color: white;
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 0.8rem;
        z-index: 2; /* Ensure it's above the image */
      }
      
      /* Ensure progress bar displays properly */
      .progress {
        background-color: var(--light-gray);
        clear: both; /* Ensure it clears any floats */
        width: 100%; /* Ensure full width */
      }

            /* Add or update these navbar mobile styles */
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
      <a class="navbar-brand" href="index.php">
          <img src="icons/favicon/android-chrome-512x512.png" alt="Logo" style="height: 50px; margin-right: 10px;">
          WingsLanka
        </a>
        <!-- Updated button with both data-toggle and data-bs-toggle for compatibility -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse"
                data-target="#ftco-nav" data-bs-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            <li class="nav-item active"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
            <li class="nav-item"><a href="donate.php" class="nav-link">Donate</a></li>
            <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
            <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- END Navbar -->

    <!-- Hero Section -->
    <div class="hero-wrap" style="background-image: url('images/bg_5.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
              <span class="mr-2"><a href="index.php">Home</a></span> 
              <span>Ongoing Projects</span>
            </p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Ongoing Projects</h1>
          </div>
        </div>
      </div>
    </div>
    <!-- END Hero Section -->

     <!-- Projects Section - Updated card design only -->
     <section class="ftco-section bg-light py-5">
      <div class="container">
        <div class="row">
          <?php
          if ($resultProjects && $resultProjects->num_rows > 0) {
              while ($project = $resultProjects->fetch_assoc()) {
                  // Extract fields from project row
                  $project_id   = $project['id'];
                  $title        = htmlspecialchars($project['title']);
                  $description  = htmlspecialchars($project['description']);
                  $image = !empty($project['image']) ? 'images/Blog_Projects/' . htmlspecialchars($project['image']) : 'images/default-project.jpg';
                  $last_donation = htmlspecialchars($project['last_donation']);
                  $progress     = (int)$project['progress'];
                  $raised       = number_format($project['raised']);
                  $target       = number_format($project['target']);
                  $created_at   = date('M d, Y', strtotime($project['created_at']));
                  
                  // Category - optional, use if you have it in DB
                  $category = "Community"; // Default category, replace with DB field if available
          ?>
          <div class="col-md-4 ftco-animate">
            <div class="cause-entry">
              <a href="project-details.php?id=<?php echo $project_id; ?>" class="img" style="background-image: url('<?php echo $image; ?>');">
                <!-- Category badge remains unchanged -->
                <span class="badge-category">
                  <?php echo $category; ?>
                </span>
              </a>
              <div class="text p-3 p-md-4">
                <h3><a href="project-details.php?id=<?php echo $project_id; ?>"><?php echo $title; ?></a></h3>
                <p><?php echo $description; ?></p>
                <!-- Add view count display here -->
                <div class="meta-info d-flex justify-content-between mb-3">
                  <span class="donation-time"><i class="icon-calendar mr-2"></i>Last donation: <?php echo $last_donation; ?></span>
                  <span class="view-count"><i class="icon-eye mr-2"></i><?php echo isset($project['views']) ? (int)$project['views'] : 0; ?> views</span>
                </div>
                
                <div class="progress custom-progress-success">
                  <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="donation-info">
                  <span class="fund-raised">$<?php echo $raised; ?> raised</span>
                  <span class="text-right">Goal: $<?php echo $target; ?></span>
                </div>
                
                <a href="donate.php?project=<?php echo $project_id; ?>" class="donate-now">Donate Now</a>
              </div>
            </div>
          </div>
          <?php
              }
          } else {
          ?>
              <div class="col-md-12">
                <div class="empty-state">
                  <h3>No Projects Found</h3>
                  <p>We don't have any ongoing projects at the moment. Please check back soon as we're always working on new initiatives!</p>
                </div>
              </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
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
                      // Replace $mysqli with $conn
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
    <!-- END Footer -->

    <!-- Loader -->
    <div id="ftco-loader" class="show fullscreen">
      <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
      </svg>
    </div>
    
    <!-- JS Files -->
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
        // Fix mobile navigation toggle button
        $('.navbar-toggler').on('click', function() {
          $('#ftco-nav').toggleClass('show');
        });
        
        // Animate progress bars on page load
        $('.progress-bar').each(function() {
          var width = $(this).attr('aria-valuenow') + '%';
          $(this).css('width', '0%').animate({width: width}, 1000);
        });
        
        // IMPORTANT: Hide the loader explicitly after a short delay
        setTimeout(function() {
          $('#ftco-loader').removeClass('show');
        }, 500);
      });
    </script>
  </body>
</html>
<?php
$conn->close();
?>
