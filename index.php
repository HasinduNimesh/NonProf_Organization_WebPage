<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Database connection details
$hostname = "sql107.epizy.com";
$username = "if0_38374977";
$password = "GIHTEk7Qu0Nu"; // Make sure the special characters are handled correctly
$dbname   = "if0_38374977_wingslanka_db";
$port     = 3306;

$mysqli = new mysqli($hostname, $username, $password, $dbname, $port);
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query the causes
$query = "SELECT * FROM project ORDER BY created_at DESC"; // Adjust the query as needed
$result = $mysqli->query($query);
if (!$result) {
    die("Query error: " . $mysqli->error);
}

// Query recent blog posts for the blog section
$blogQuery = "
SELECT
  p.id,
  p.title,
  p.post_date,
  p.author,
  p.excerpt,
  p.image,
  (
    SELECT COUNT(*)
    FROM blog_comments c
    WHERE c.post_id = p.id
      AND c.status = 'approved'
  ) AS comments_count
FROM blog_posts p
WHERE p.status = 'published'
ORDER BY p.post_date DESC
LIMIT 3
";
$recentBlogPosts = $mysqli->query($blogQuery);

// Query latest donations
$donationsQuery = "
SELECT 
  donor_name, 
  donor_image, 
  amount, 
  message, 
  created_at 
FROM donations 
ORDER BY created_at DESC 
LIMIT 3
";
$recentDonations = $mysqli->query($donationsQuery);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>WingsLanka</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Overpass:300,400,400i,600,700" rel="stylesheet">
    <!-- Add this in your <head> section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
        /* Responsive styles */
        @media (max-width: 991.98px) {
          /* Fix mobile navigation */
          .ftco-navbar-light {
            background: #000 !important;
            position: relative;
            top: 0;
          }
          
          .ftco-navbar-light .navbar-collapse {
            background: #000;
            padding: 20px;
            z-index: 1000;
          }
          
          .ftco-navbar-light .navbar-nav > .nav-item > .nav-link {
            padding: 0.7rem 0;
            color: #fff !important;
            font-size: 16px;
          }
          
          .navbar-toggler {
            border: 2px solid #fff;
            padding: 8px 10px;
          }
          
          /* Ensure mobile menu appears correctly */
          .navbar-collapse.show, 
          .navbar-collapse.collapsing {
            display: block;
            height: auto !important;
          }
        }
        
        /* General responsive improvements */
        .img {
          background-size: cover;
          background-position: center;
        }
        
        img {
          max-width: 100%;
          height: auto;
        }
        
        .hero-wrap h1 {
          font-size: clamp(2rem, 5vw, 3.5rem);
        }
        
        @media (max-width: 767px) {
          .ftco-counter .block-18 {
            padding: 20px 15px;
            margin-bottom: 20px;
          }
          
          .ftco-section {
            padding: 4em 0;
          }
          
          .ftco-footer-widget {
            margin-bottom: 30px;
          }
        }
        .ftco-counter .block-18 {
            transition: all 0.4s ease;
            border-radius: 15px;
            overflow: hidden;
            padding: 2.5rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            height: 100%;
        }
        
        .ftco-counter .block-18:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Updated color schemes with gradient backgrounds */
        .ftco-counter .color-1 {
            background: linear-gradient(135deg, #ffffff, #f1f8e9);
            border-bottom: 5px solid #4e9525;
        }
        
        .ftco-counter .color-2 {
            background: linear-gradient(135deg, #ffffff, #fff3e0);
            border-bottom: 5px solid #f86f2d;
        }
        
        .ftco-counter .color-3 {
            background: linear-gradient(135deg, #ffffff, #e8f0fe);
            border-bottom: 5px solid #3e64ff;
        }
        
        /* Different colored icons for each section */
        .ftco-counter .color-1 .icon span {
            color: #4e9525 !important;
        }
        
        .ftco-counter .color-2 .icon span {
            color: #f86f2d !important;
        }
        
        .ftco-counter .color-3 .icon span {
            color: #3e64ff !important;
        }
        
        /* Enhanced icon hover effects */
        .ftco-counter .icon {
            display: inline-block;
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }
        
        .ftco-counter .block-18:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Enhanced button styles */
        .btn-primary {
            background-color: #4e9525;
            border-color: #4e9525;
            box-shadow: 0 4px 15px rgba(78, 149, 37, 0.3);
            transition: all 0.4s ease;
        }
        
        .btn-secondary {
            background-color: #f86f2d;
            border-color: #f86f2d;
            box-shadow: 0 4px 15px rgba(248, 111, 45, 0.3);
            transition: all 0.4s ease;
        }
        
        /* More dramatic button hover effects */
        .btn-primary:hover, .btn-primary:focus {
            background-color: #3d7a1d;
            border-color: #3d7a1d;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(78, 149, 37, 0.4);
        }
        
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: #e55b1a;
            border-color: #e55b1a;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(248, 111, 45, 0.4);
        }
      #volunteer-section .form-control {
          color: #fff !important;
          background-color: rgba(255, 255, 255, 0.2) !important;
          border: 1px solid rgba(255, 255, 255, 0.3);
          border-radius: 4px;
          padding: 12px 15px;
          backdrop-filter: blur(5px);
          -webkit-backdrop-filter: blur(5px);
          transition: all 0.3s ease;
          text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
      }

      #volunteer-section .form-control::placeholder {
          color: rgba(255, 255, 255, 0.7);
      }

      #volunteer-section .form-control:focus {
          background-color: rgba(255, 255, 255, 0.3) !important;
          box-shadow: 0 0 10px rgba(248, 111, 45, 0.3);
          border-color: rgba(248, 111, 45, 0.5);
      }

      #volunteer-section select.form-control {
          color: #fff !important; 
          background-color: rgba(255, 255, 255, 0.2) !important;
          appearance: auto;
      }

      #volunteer-section select.form-control option {
          color: #000 !important;
          background-color: #fff;
      }

      #volunteer-section .input-group-text {
          color: #fff;
          background-color: rgba(248, 111, 45, 0.4);
          border: 1px solid rgba(255, 255, 255, 0.3);
      }

      #volunteer-section .form-text {
          color: rgba(255, 255, 255, 0.8) !important;
          margin-top: 0.25rem;
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
      <!-- Replace the existing navbar-toggler button with this one -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span style="color: white; font-size: 24px;">☰</span>
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
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
    <!-- END nav -->
    
    <div class="hero-wrap" style="background-image: url('images/bg_8.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
            <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Doing Nothing is Not An Option of Our Life</h1>
            <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><a href="https://vimeo.com/45830194" class="btn btn-white btn-outline-white px-4 py-3 popup-vimeo"><span class="icon-play mr-2"></span>Watch Video</a></p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-counter ftco-intro" id="section-counter">
    <div class="container">
        <div class="row no-gutters">
            <!-- Statistics Counter -->
            <div class="col-md-4 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 color-1 align-items-stretch shadow-sm rounded">
                    <div class="text text-center p-4">
                    <div class="icon mb-3">
                        <i class="fas fa-hands-helping" style="font-size: 3rem;"></i>
                    </div>
                        <span class="d-block mb-2 text-uppercase">Served Over</span>
                        <strong class="number d-block mb-2" data-number="1500" style="font-size: 3.5rem; font-weight: 700; color: #4e9525;">0</strong>
                        <span class="d-block text-uppercase">Children in Sri Lanka</span>
                    </div>
                </div>
            </div>
            
            <!-- Donate Block -->
            <div class="col-md-4 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 color-2 align-items-stretch shadow-sm rounded">
                    <div class="text text-center p-4">
                    <div class="icon mb-3">
                        <i class="fas fa-hand-holding-heart" style="font-size: 3rem;"></i>
                    </div>
                        <h3 class="mb-3">Donate Money</h3>
                        <p class="mb-4">Support our mission to help children in need across Sri Lanka.</p>
                        <p><a href="donate.php" class="btn btn-primary px-4 py-3 mt-2 rounded-pill">Donate Now</a></p>
                    </div>
                </div>
            </div>
            
            <!-- Volunteer Block -->
            <div class="col-md-4 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 color-3 align-items-stretch shadow-sm rounded">
                    <div class="text text-center p-4">
                    <div class="icon mb-3">
                        <i class="fas fa-users" style="font-size: 3rem;"></i>
                    </div>
                        <h3 class="mb-3">Be a Volunteer</h3>
                        <p class="mb-4">Join our team and help make a difference in children's lives.</p>
                        <p><a href="#volunteer-section" class="btn btn-secondary px-4 py-3 mt-2 rounded-pill">Join Us Today</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 d-flex services p-3 py-4 d-block">
              <div class="icon d-flex mb-3"><span class="flaticon-donation-1"></span></div>
              <div class="media-body pl-4">
                <h3 class="heading">Make Donation</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 d-flex services p-3 py-4 d-block">
              <div class="icon d-flex mb-3"><span class="flaticon-charity"></span></div>
              <div class="media-body pl-4">
                <h3 class="heading">Become A Volunteer</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 d-flex services p-3 py-4 d-block">
              <div class="icon d-flex mb-3"><span class="flaticon-donation"></span></div>
              <div class="media-body pl-4">
                <h3 class="heading">Sponsorship</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
            </div>    
          </div>
        </div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container-fluid">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-5 heading-section ftco-animate text-center">
            <h2 class="mb-4">Our On Going Projects</h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 ftco-animate">
            <div class="carousel-cause owl-carousel">
              <?php while($row = $result->fetch_assoc()){ ?>
                <div class="item">
                  <div class="cause-entry">
                  <a href="#" class="img" style="background-image: url(images/Blog_Projects/<?php echo htmlspecialchars($row['image']); ?>);"></a>
                    <div class="text p-3 p-md-4">
                      <h3><a href="#"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                      <p><?php echo htmlspecialchars($row['description']); ?></p>
                      <span class="donation-time mb-3 d-block">Last donation <?php echo htmlspecialchars($row['last_donation']); ?> ago</span>
                      <div class="progress custom-progress-success">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo (int)$row['progress']; ?>%" aria-valuenow="<?php echo (int)$row['progress']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="fund-raised d-block">Rs.<?php echo number_format($row['raised']); ?> raised of Rs.<?php echo number_format($row['target']); ?></span>
                    </div>
                  </div>
                </div>
              <?php } // end while ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Latest Donations</h2>
            <p>We're grateful to everyone who contributes to our mission of making a difference.</p>
          </div>
        </div>
        <div class="row">
          <?php
          if ($recentDonations && $recentDonations->num_rows > 0) {
            while ($donation = $recentDonations->fetch_assoc()) {
              // Use fallback image if none exists
              $donorImage = !empty($donation['donor_image']) ? $donation['donor_image'] : 'images/person_1.jpg';
              $donationTime = date("M d, Y", strtotime($donation['created_at']));
              $amount = number_format($donation['amount']); 
          ?>
          <div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
            <div class="staff">
              <div class="d-flex mb-4">
                <div class="img" style="background-image: url(<?php echo $donorImage; ?>);"></div>
                <div class="info ml-4">
                  <h3><a href="#"><?php echo htmlspecialchars($donation['donor_name']); ?></a></h3>
                  <span class="position">Donated on <?php echo $donationTime; ?></span>
                  <div class="text">
                    <p>Donated <span>$<?php echo $amount; ?></span>
                    <?php if(!empty($donation['message'])): ?>
                      for <a href="#"><?php echo htmlspecialchars($donation['message']); ?></a>
                    <?php endif; ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
            }
          } else {
          ?>
          <!-- Fallback for no donations -->
          <div class="col-lg-4 d-flex mb-sm-4 ftco-animate">
            <div class="staff">
              <div class="d-flex mb-4">
                <div class="img" style="background-image: url(images/person_1.jpg);"></div>
                <div class="info ml-4">
                  <h3><a href="#">Be the first donor</a></h3>
                  <span class="position">Make a difference today</span>
                  <div class="text">
                    <p>Your support can help our community projects <span>succeed</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </section>

    <section class="ftco-gallery">
    	<div class="d-md-flex">
	    	<a href="images/cause-2.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-2.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/cause-3.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-3.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/cause-4.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-4.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/cause-5.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-5.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
    	</div>
    	<div class="d-md-flex">
	    	<a href="images/cause-6.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/cause-6.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_3.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_3.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_1.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_1.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    	<a href="images/image_2.jpg" class="gallery image-popup d-flex justify-content-center align-items-center img ftco-animate" style="background-image: url(images/image_2.jpg);">
	    		<div class="icon d-flex justify-content-center align-items-center">
	    			<span class="icon-search"></span>
	    		</div>
	    	</a>
	    </div>
    </section>

    <!-- Recent from Blog Section -->
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Recent from blog</h2>
            <p>Stay updated with our latest articles about our projects, events, and impact stories.</p>
          </div>
        </div>
        <div class="row d-flex">
          <?php
          if ($recentBlogPosts && $recentBlogPosts->num_rows > 0) {
            while ($post = $recentBlogPosts->fetch_assoc()) {
              // Use fallback image if none exists
              $imagePath = !empty($post['image']) ? 'images/Blog_Projects/' . htmlspecialchars($post['image']) : 'images/image_1.jpg';
              $postDate = date('M d, Y', strtotime($post['post_date']));
              $postId = (int)$post['id'];
          ?>
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry align-self-stretch">
              <a href="blog-single.php?id=<?php echo $postId; ?>" class="block-20" style="background-image: url('<?php echo $imagePath; ?>');">
              </a>
              <div class="text p-4 d-block">
                <div class="meta mb-3">
                  <div><a href="blog-single.php?id=<?php echo $postId; ?>"><span class="icon-calendar"></span> <?php echo $postDate; ?></a></div>
                  <div><a href="blog-single.php?id=<?php echo $postId; ?>"><span class="icon-person"></span> <?php echo htmlspecialchars($post['author']); ?></a></div>
                  <div><a href="blog-single.php?id=<?php echo $postId; ?>" class="meta-chat"><span class="icon-chat"></span> <?php echo (int)$post['comments_count']; ?></a></div>
                </div>
                <h3 class="heading mt-3"><a href="blog-single.php?id=<?php echo $postId; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
              </div>
            </div>
          </div>
          <?php
            }
          } else {
          ?>
          <!-- Fallback for no blog posts -->
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry align-self-stretch">
              <a href="#" class="block-20" style="background-image: url('images/image_1.jpg');"></a>
              <div class="text p-4 d-block">
                <div class="meta mb-3">
                  <div><a href="#"><span class="icon-calendar"></span> Coming Soon</a></div>
                </div>
                <h3 class="heading mt-3"><a href="#">New blog posts coming soon</a></h3>
                <p>Check back later for updates on our projects and initiatives.</p>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </section>

<!-- Add id attribute to the section and update the form around line 636 -->
<section id="volunteer-section" class="ftco-section-3 img" style="background-image: url(images/bg_3.jpg);">
    <div class="overlay"></div>
    <div class="container">
        <div class="row d-md-flex">
            <div class="col-md-6 d-flex ftco-animate">
                <div class="img img-2 align-self-stretch" style="background-image: url(images/bg_4.jpg);"></div>
            </div>
            <div class="col-md-6 volunteer pl-md-5 ftco-animate">
                <!-- Replace the existing alert message code in the volunteer section with this complete version -->
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
                <!-- Replace the existing form fields with these -->
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <select name="country" class="form-control">
                        <option value="Sri Lanka" selected>Sri Lanka</option>
                        <option value="India">India</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Australia">Australia</option>
                        <option value="Canada">Canada</option>
                        <option value="Germany">Germany</option>
                        <option value="France">France</option>
                        <option value="Japan">Japan</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+94</span>
                        </div>
                        <input type="tel" name="phone" class="form-control" placeholder="Phone Number (Optional)">
                    </div>
                    <small class="form-text text-muted">Format: 7XXXXXXXX (without leading zero)</small>
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
  // Fix mobile navigation toggle
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle the mobile menu when the button is clicked
    document.querySelector('.navbar-toggler').addEventListener('click', function(e) {
      e.preventDefault();
      const navbarCollapse = document.getElementById('ftco-nav');
      
      // Toggle the 'show' class
      if(navbarCollapse.classList.contains('show')) {
        navbarCollapse.classList.remove('show');
        navbarCollapse.classList.add('collapse');
      } else {
        navbarCollapse.classList.add('show');
        navbarCollapse.classList.remove('collapse');
      }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
      const navbarCollapse = document.getElementById('ftco-nav');
      const toggleButton = document.querySelector('.navbar-toggler');
      
      if(navbarCollapse.classList.contains('show') && 
         !navbarCollapse.contains(e.target) && 
         !toggleButton.contains(e.target)) {
        navbarCollapse.classList.remove('show');
        navbarCollapse.classList.add('collapse');
      }
    });
    
    // Close navigation when clicking on a menu item on mobile
    if(window.innerWidth < 992) {
      document.querySelectorAll('.navbar-nav .nav-item').forEach(item => {
        item.addEventListener('click', function() {
          document.getElementById('ftco-nav').classList.remove('show');
          document.getElementById('ftco-nav').classList.add('collapse');
        });
      });
    }
  });
  
  // Ensure responsive behavior on resize
  window.addEventListener('resize', function() {
    if(window.innerWidth >= 992) {
      document.getElementById('ftco-nav').classList.remove('show');
    }
  });
</script>
  </body>
</html>