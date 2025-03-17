<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin username if available
$admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';

// Fetch statistics for dashboard
// 1. Total donations count and amount
$donationStats = $conn->query("SELECT COUNT(*) as total_count, SUM(amount) as total_amount FROM donations");
$donationData = $donationStats->fetch_assoc();
$totalDonations = $donationData['total_count'] ?? 0;
$totalDonationAmount = $donationData['total_amount'] ?? 0;

// 2. Active projects count
$projectStats = $conn->query("SELECT COUNT(*) as total FROM project");
$projectData = $projectStats->fetch_assoc();
$activeProjects = $projectData['total'] ?? 0;

// 3. Published blog posts count
$blogStats = $conn->query("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'");
$blogData = $blogStats->fetch_assoc();
$publishedPosts = $blogData['total'] ?? 0;

// 4. Total blog post views
$blogViewsStats = $conn->query("SELECT SUM(views) as total_views FROM blog_posts");
$blogViewsData = $blogViewsStats->fetch_assoc();
$totalBlogViews = $blogViewsData['total_views'] ?? 0;

// 5. Volunteer applications stats
$volunteerStats = $conn->query("SELECT COUNT(*) as total FROM volunteers");
$volunteerData = $volunteerStats->fetch_assoc();
$totalVolunteers = $volunteerData['total'] ?? 0;

// Active volunteers count
$activeVolunteersStats = $conn->query("SELECT COUNT(*) as active_count FROM volunteers WHERE status = 'active'");
$activeVolunteersData = $activeVolunteersStats->fetch_assoc();
$activeVolunteers = $activeVolunteersData['active_count'] ?? 0;
?>
<!-- rest of your HTML code remains the same -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WingsLanka Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #f86f2d;
            --secondary: #4e9525;
            --dark: #343a40;
            --light: #f8f9fa;
            --sidebar-width: 280px;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(to bottom, var(--primary), #e85a1a);
            padding: 1.5rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
        }
        
        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            border-top: 4px solid var(--primary);
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: var(--light);
            color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggler {
                display: block;
            }
        }
        
        /* User profile */
        .user-profile {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-top: auto;
            margin-bottom: 1rem;
            background: rgba(0,0,0,0.1);
            border-radius: 8px;
            color: white;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .logout-btn {
            padding: 0.3rem 0.7rem;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Stats */
        .stat-card {
            border-radius: 8px;
            padding: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="../icons/favicon/android-chrome-192x192.png" alt="Logo" style="width: 40px; margin-right: 10px;">
            WingsLanka Admin
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="manage_blog_posts.php">
                    <i class="fas fa-blog"></i> Blog Posts
                </a>
            </li>
            <li>
                <a href="manage_projects.php">
                    <i class="fas fa-project-diagram"></i> Projects
                </a>
            </li>
            <li>
                <a href="manage_donations.php">
                    <i class="fas fa-hand-holding-heart"></i> Donations
                </a>
            </li>
            <li>
                <a href="manage_volunteers.php">
                    <i class="fas fa-hands-helping"></i> Volunteers
                </a>
            </li>
            <li>
                <a href="manage_contacts.php">
                    <i class="fas fa-envelope"></i> Messages
                    <?php
                    // Count new messages
                    $newMessagesQuery = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'new'";
                    $newMessagesResult = $conn->query($newMessagesQuery);
                    $newMessagesCount = $newMessagesResult->fetch_assoc()['count'] ?? 0;
                    
                    if($newMessagesCount > 0):
                    ?>
                    <span class="badge bg-danger ms-2"><?php echo $newMessagesCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>

        </ul>
        
        <!-- User profile -->
        <div class="user-profile mt-auto">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <p class="user-name"><?php echo htmlspecialchars($admin_username); ?></p>
                <p class="user-role">Administrator</p>
            </div>
            <a href="logout.php" class="btn logout-btn ms-2"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="background: linear-gradient(to right, #f86f2d, #e85a1a);">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div>
                            <h3 class="stat-number"><?php echo number_format($totalDonations); ?></h3>
                            <p class="stat-label">Total Donations</p>
                            <small class="text-white-50">$<?php echo number_format($totalDonationAmount, 2); ?> raised</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="background: linear-gradient(to right, #4e9525, #3b7e12);">
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <h3 class="stat-number"><?php echo number_format($activeProjects); ?></h3>
                            <p class="stat-label">Active Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="background: linear-gradient(to right, #2d8bf8, #1a73e8);">
                        <div class="stat-icon">
                            <i class="fas fa-blog"></i>
                        </div>
                        <div>
                            <h3 class="stat-number"><?php echo number_format($publishedPosts); ?></h3>
                            <p class="stat-label">Blog Posts</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="background: linear-gradient(to right, #9c27b0, #7b1fa2);">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <h3 class="stat-number"><?php echo number_format($totalBlogViews); ?></h3>
                            <p class="stat-label">Blog Views</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card" style="background: linear-gradient(to right, #ff9800, #f57c00);">
                    <div class="stat-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div>
                        <h3 class="stat-number"><?php echo number_format($totalVolunteers); ?></h3>
                        <p class="stat-label">Volunteer Applications</p>
                        <small class="text-white-50"><?php echo number_format($activeVolunteers); ?> active volunteers</small>
                    </div>
                </div>
            </div>
            
            <!-- Admin Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-blog"></i>
                        </div>
                        <h5>Manage Blog Posts</h5>
                        <p>Create, edit, and publish blog posts to keep your audience engaged and informed.</p>
                        <a href="manage_blog_posts.php" class="btn btn-primary">Access Blog Manager</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h5>Manage Projects</h5>
                        <p>Create and manage projects, update progress, and track contributions.</p>
                        <a href="manage_projects.php" class="btn btn-primary">Access Project Manager</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h5>Manage Donations</h5>
                        <p>Track donations, generate reports, and manage donor information.</p>
                        <a href="manage_donations.php" class="btn btn-primary">Access Donation Manager</a>
                    </div>
                </div>
                <!-- User Management Card - Commented out
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5>Manage Users</h5>
                        <p>Manage user accounts, permissions, and roles for the admin panel.</p>
                        <a href="manage_users.php" class="btn btn-primary">Access User Manager</a>
                    </div>
                </div>
                -->
                
                <!-- Settings Card - Commented out
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5>System Settings</h5>
                        <p>Configure system settings, backups, and site preferences.</p>
                        <a href="settings.php" class="btn btn-primary">Access Settings</a>
                    </div>
                </div>
                -->
                <div class="col-lg-4 col-md-6">
                    <div class="dashboard-card h-100">
                        <div class="card-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5>Analytics</h5>
                        <p>View site analytics, user engagement, and donation statistics.</p>
                        <a href="analytics.php" class="btn btn-primary">View Analytics</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-toggler')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>