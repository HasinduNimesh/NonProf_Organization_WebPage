<?php
// Connect to the database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/********************************************
 * Pagination Settings
 ********************************************/
$limit = 9; // Number of blog posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$start_from = ($page - 1) * $limit;

/********************************************
 * Fetch total blog post count (only published posts)
 ********************************************/
$sqlCount = "SELECT COUNT(*) AS total FROM blog_posts WHERE status = 'published'";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$total_records = $rowCount['total'];
$total_pages = ceil($total_records / $limit);

/********************************************
 * Fetch blog posts for this page (only published posts)
 * Using subquery to count approved comments
 ********************************************/
$sqlBlog = "
SELECT
  p.id,
  p.title,
  p.slug,
  p.post_date,
  p.author,
  p.excerpt,
  p.image,
  p.views,
  (
    SELECT COUNT(*)
    FROM blog_comments c
    WHERE c.post_id = p.id
      AND c.status = 'approved'
  ) AS comments_count
FROM blog_posts p
WHERE p.status = 'published'
ORDER BY p.post_date DESC
LIMIT $start_from, $limit
";
$resultBlog = $conn->query($sqlBlog);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>WingsLanka - Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
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
    
    <!-- Additional styling -->
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
      .blog-meta {
        font-size: 0.9rem;
        color: #777;
      }
      .blog-meta .icon-calendar,
      .blog-meta .icon-user,
      .blog-meta .icon-chat {
        margin-right: 4px;
      }
      .blog-meta span:not(:last-child) {
        margin-right: 15px;
      }
    </style>
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
        
        /* Add these new rules for mobile menu */
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
      
      /* Rest of your existing CSS stays the same */
      .blog-meta {
        font-size: 0.9rem;
        color: #777;
      }
      .blog-meta .icon-calendar,
      .blog-meta .icon-user,
      .blog-meta .icon-chat {
        margin-right: 4px;
      }
      .blog-meta span:not(:last-child) {
        margin-right: 15px;
      }
    </style>
  </head>
  <body>
    
    <!-- START: Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="icons/favicon/android-chrome-512x512.png" alt="Logo" style="height: 50px; margin-right: 10px;">
          WingsLanka
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse"
                data-target="#ftco-nav" data-bs-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            <li class="nav-item"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
            <li class="nav-item"><a href="donate.php" class="nav-link">Donate</a></li>
            <li class="nav-item active"><a href="blog.php" class="nav-link">Blog</a></li>
            <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- END: Navbar -->
    
    <!-- START: Hero Section -->
    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
              <span class="mr-2"><a href="index.php">Home</a></span>
              <span>Blog</span>
            </p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Our Blog</h1>
          </div>
        </div>
      </div>
    </div>
    <!-- END: Hero Section -->

    <!-- START: Blog Posts Section -->
    <section class="ftco-section">
      <div class="container">
        <div class="row d-flex">
          <?php
          if ($resultBlog && $resultBlog->num_rows > 0) {
              while ($row = $resultBlog->fetch_assoc()) {
                  // Use a fallback image if none is provided
                  $imagePath = (!empty($row['image'])) 
                  ? 'images/Blog_Projects/' . htmlspecialchars($row['image']) 
                  : 'images/default.jpg';
                  
                  // Format the post date nicely (e.g. Jan 28, 2024)
                  $formattedDate = date('M d, Y', strtotime($row['post_date']));
                  
                  // Prepare values
                  $postId         = (int)$row['id'];
                  $title          = htmlspecialchars($row['title']);
                  $author         = htmlspecialchars($row['author']);
                  $excerpt        = htmlspecialchars($row['excerpt']);
                  $commentsCount  = (int)$row['comments_count']; // from subquery
          ?>
                  <div class="col-md-4 d-flex ftco-animate">
                    <div class="blog-entry align-self-stretch">
                      <!-- Featured image / fallback -->
                      <a href="blog-single.php?id=<?php echo $postId; ?>" 
                         class="block-20" 
                         style="background-image: url('<?php echo $imagePath; ?>');">
                      </a>
                      <!-- Blog text/content -->
                      <div class="text p-4 d-block">
                        <!-- Meta info with date, author, comment count -->
                        <div class="blog-meta mb-3">
                          <span><i class="icon-calendar"></i> <?php echo $formattedDate; ?></span>
                          <span><i class="icon-user"></i> <?php echo $author; ?></span>
                          <span><i class="icon-chat"></i> <?php echo $commentsCount; ?></span>
                        </div>
                        <!-- Blog title -->
                        <h3 class="heading mt-3">
                          <a href="blog-single.php?id=<?php echo $postId; ?>">
                            <?php echo $title; ?>
                          </a>
                        </h3>
                        <!-- Blog excerpt -->
                        <p><?php echo $excerpt; ?></p>
                      </div>
                    </div>
                  </div>
          <?php
              } // end while
          } else {
              echo '<div class="col-md-12"><p>No blog posts found.</p></div>';
          }
          ?>
        </div>

        <!-- START: Pagination -->
        <div class="row mt-5">
          <div class="col text-center">
            <div class="block-27">
              <ul>
                <!-- Previous Page Link -->
                <?php if ($page > 1): ?>
                  <li><a href="?page=<?php echo $page - 1; ?>">&lt;</a></li>
                <?php else: ?>
                  <li class="disabled"><span>&lt;</span></li>
                <?php endif; ?>

                <!-- Page Number Links -->
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<li class="active"><span>' . $i . '</span></li>';
                    } else {
                        echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                ?>

                <!-- Next Page Link -->
                <?php if ($page < $total_pages): ?>
                  <li><a href="?page=<?php echo $page + 1; ?>">&gt;</a></li>
                <?php else: ?>
                  <li class="disabled"><span>&gt;</span></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
        <!-- END: Pagination -->

      </div>
    </section>
    <!-- END: Blog Posts Section -->

    <!-- START: Footer -->
    <footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">About WingsLanka</h2>
                    <p>WingsLanka is committed to empowering children across Sri Lanka through education, healthcare, and community development initiatives.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="https://twitter.com/wingslanka" target="_blank"><span class="icon-twitter"></span></a></li>
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
    <!-- END: Footer -->

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
        
        // Make sure menu closes when clicking a link (optional but good UX)
        $('#ftco-nav .nav-link').on('click', function() {
          if ($(window).width() < 992) {
            $('#ftco-nav').removeClass('show');
          }
        });
      });
    </script>
  </body>
</html>
<?php
$conn->close();
?>
