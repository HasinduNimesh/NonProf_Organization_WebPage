<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the blog post id from the query string
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id <= 0) {
    die("Invalid blog post id.");
}

// Fetch the blog post (only published posts)
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ? AND status = 'published'");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$resultPost = $stmt->get_result();
if ($resultPost->num_rows === 0) {
    die("Blog post not found.");
}
$post = $resultPost->fetch_assoc();
$stmt->close();

// Increment the view count
$conn->query("UPDATE blog_posts SET views = views + 1 WHERE id = $post_id");

// Optionally update the local $post variable to reflect the new view count immediately:
$post['views'] = $post['views'] + 1;


// Fetch associated blog images, ordered by display_order
$stmt2 = $conn->prepare("SELECT * FROM blog_images WHERE post_id = ? ORDER BY display_order ASC");
$stmt2->bind_param("i", $post_id);
$stmt2->execute();
$resultImages = $stmt2->get_result();
$stmt2->close();

// Fetch approved comments for this post (ordered oldest first)
$stmt3 = $conn->prepare("SELECT * FROM blog_comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at ASC");
$stmt3->bind_param("i", $post_id);
$stmt3->execute();
$resultComments = $stmt3->get_result();
$stmt3->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars($post['title']); ?> - Blog Details</title>
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
    
    <!-- Additional responsive styling -->
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
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" 
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            <li class="nav-item"><a href="ongoingProj.php" class="nav-link">Ongoing Projects</a></li>
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
    <div class="hero-wrap" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
              <span class="mr-2"><a href="index.php">Home</a></span>
              <span class="mr-2"><a href="blog.php">Blog</a></span>
              <span>Blog Details</span>
            </p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
              <?php echo htmlspecialchars($post['title']); ?>
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- END Hero Section -->

    <!-- Blog Single Section -->
    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row">
          <!-- Main Blog Content -->
          <div class="col-md-8 ftco-animate">
            <h2 class="mb-4 blog-title"><?php echo htmlspecialchars($post['title']); ?></h2>
            <div class="meta mb-4 pb-3 border-bottom">
              <span class="meta-item">
                <i class="icon-calendar mr-2"></i>
                <?php echo date('M d, Y', strtotime($post['post_date'])); ?>
              </span>
              <span class="meta-item">
                <i class="icon-user mr-2"></i>
                <?php echo htmlspecialchars($post['author']); ?>
              </span>
              <span class="meta-item">
                <i class="icon-eye mr-2"></i>
                <?php echo (int)$post['views']; ?> views
              </span>
            </div>
            <!-- Featured Image -->
            <?php if (!empty($post['image'])): ?>
              <div class="featured-image mb-5">
                <!-- Change this line -->
                <img src="images/Blog_Projects/<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image" class="img-fluid rounded shadow">
              </div>
            <?php endif; ?>

            <!-- Blog Content -->
            <div class="content mb-5">
              <?php echo $post['content']; ?>
            </div>
            
            <!-- Additional Blog Images -->
            <?php if ($resultImages && $resultImages->num_rows > 0): ?>
              <div class="blog-images mt-5 pt-4 border-top">
                <h3 class="mb-4">Project Gallery</h3>
                <div class="row">
                  <?php while ($img = $resultImages->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                      <!-- Change both of these image paths -->
                      <a href="images/Blog_Projects/<?php echo htmlspecialchars($img['image_path']); ?>" class="image-popup">
                        <img src="images/Blog_Projects/<?php echo htmlspecialchars($img['image_path']); ?>" 
                            alt="<?php echo htmlspecialchars($img['caption']); ?>" 
                            class="img-fluid rounded">
                      </a>
                      <?php if (!empty($img['caption'])): ?>
                        <p class="mt-2 text-center">
                          <small><?php echo htmlspecialchars($img['caption']); ?></small>
                        </p>
                      <?php endif; ?>
                    </div>
                  <?php endwhile; ?>
                </div>
              </div>
            <?php endif; ?>
            
            <!-- Comments Section -->
            <div class="pt-5 mt-5 border-top">
              <h3 class="mb-4 comment-title">
                <?php echo $resultComments ? $resultComments->num_rows : 0; ?> Comments
              </h3>
              <ul class="comment-list p-0">
                <?php if ($resultComments && $resultComments->num_rows > 0): ?>
                  <?php while ($comment = $resultComments->fetch_assoc()): ?>
                    <li class="comment bg-white p-4 rounded shadow-sm mb-4">
                      <div class="row">
                        <div class="col-md-2 col-sm-3">
                          <div class="vcard bio text-center">
                            <img src="images/default-avatar.png" alt="User Avatar" 
                                 class="rounded-circle img-fluid" 
                                 style="max-width:80px;">
                          </div>
                        </div>
                        <div class="col-md-10 col-sm-9">
                          <h4 class="mb-1">
                            <?php echo htmlspecialchars($comment['author_name']); ?>
                          </h4>
                          <div class="meta text-muted mb-2">
                            <small>
                              <?php echo date('M d, Y h:i A', strtotime($comment['created_at'])); ?>
                            </small>
                          </div>
                          <p class="mb-3">
                            <?php echo htmlspecialchars($comment['comment']); ?>
                          </p>
                          <p>
                            <a href="#comment-form" class="reply btn btn-sm btn-outline-primary rounded-pill px-3">
                              Reply
                            </a>
                          </p>
                        </div>
                      </div>
                    </li>
                  <?php endwhile; ?>
                <?php else: ?>
                  <li class="no-comments text-center p-4 bg-white rounded shadow-sm">
                    <p class="mb-0">No comments yet. Be the first to comment!</p>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
            
            <!-- Comment Form (NO website field) -->
            <div id="comment-form" class="comment-form-wrap pt-5">
              <h3 class="mb-4 comment-title">Leave a Comment</h3>
              <form action="submit_comment.php" method="post" class="p-5 bg-white rounded shadow-sm">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label for="author_name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="author_name" name="author_name" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="author_email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="author_email" name="author_email" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="comment">Comment <span class="text-danger">*</span></label>
                  <textarea name="comment" id="comment" cols="30" rows="7" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                    Post Comment
                  </button>
                </div>
              </form>
            </div>
          </div>
          
          <!-- Sidebar -->
          <div class="col-md-4 sidebar ftco-animate">
            <div class="sidebar-box shadow-sm">
              <form action="#" class="search-form">
                <div class="form-group position-relative">
                  <input type="text" class="form-control rounded-pill py-3 px-4" placeholder="Search articles...">
                  <button type="submit" class="btn position-absolute" style="top: 5px; right: 15px;">
                    <span class="icon fa fa-search text-muted"></span>
                  </button>
                </div>
              </form>
            </div>
            <div class="sidebar-box ftco-animate">
              <h3 class="sidebar-heading position-relative pb-2 mb-4">Recent Articles</h3>
              <div class="recent-post d-flex align-items-center bg-white p-3 rounded shadow-sm mb-3">
                <a href="#" class="recent-post-img mr-3">
                  <img src="images/image_1.jpg" alt="Blog Thumbnail" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                </a>
                <div class="text">
                  <h4 class="heading-sidebar mb-0"><a href="#">Placeholder Blog Title</a></h4>
                  <div class="meta mt-2">
                    <small><span class="icon-calendar mr-1"></span> July 12, 2023</small>
                  </div>
                </div>
              </div>
              <div class="recent-post d-flex align-items-center bg-white p-3 rounded shadow-sm">
                <a href="#" class="recent-post-img mr-3">
                  <img src="images/image_2.jpg" alt="Blog Thumbnail" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                </a>
                <div class="text">
                  <h4 class="heading-sidebar mb-0"><a href="#">Another Placeholder Blog Title</a></h4>
                  <div class="meta mt-2">
                    <small><span class="icon-calendar mr-1"></span> July 12, 2023</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="sidebar-box ftco-animate">
              <h3 class="sidebar-heading position-relative pb-2 mb-4">About WingsLanka</h3>
              <div class="bg-white p-4 rounded shadow-sm">
                <p>Our mission is to empower communities through sustainable development projects and initiatives that create lasting positive change.</p>
                <a href="about.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">Learn More</a>
              </div>
            </div>
          </div>
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
    
    <!-- Loader -->
    <div id="ftco-loader" class="show fullscreen">
      <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" 
                stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" 
                stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
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
    <!-- If using Google Maps, reinsert your key below -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&sensor=false"></script> -->
    <script src="js/google-map.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
<?php
$conn->close();
?>
