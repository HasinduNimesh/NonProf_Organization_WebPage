<?php
// filepath: d:\ClapTac\Wingslanka\welfare-gh-pages\admin\analytics.php
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

// First, check which date column exists in your tables
$checkColumnsQuery = "SHOW COLUMNS FROM donations";
$columnsResult = $conn->query($checkColumnsQuery);
$dateColumnExists = false;
$possibleDateColumns = ['created_at', 'date', 'donation_date', 'timestamp', 'created_date'];
$dateColumn = '';

if ($columnsResult) {
    while ($column = $columnsResult->fetch_assoc()) {
        if (in_array($column['Field'], $possibleDateColumns)) {
            $dateColumn = $column['Field'];
            $dateColumnExists = true;
            break;
        }
    }
}

// If no date column found, use a default one (this will still throw an error, but better error handling)
if (!$dateColumnExists) {
    $dateColumn = 'donation_date'; // Change this to match your actual column name
}

// Get donation analytics data
$donationAnalyticsQuery = "
    SELECT 
        DATE_FORMAT($dateColumn, '%Y-%m') AS month,
        COUNT(*) AS donation_count,
        SUM(amount) AS total_amount
    FROM donations
    WHERE $dateColumn >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY month
    ORDER BY month ASC
";

// Try the query, but with error handling
try {
    $donationAnalytics = $conn->query($donationAnalyticsQuery);
    $donationChartData = [];
    $donationLabels = [];
    $donationCounts = [];
    $donationAmounts = [];
    
    if ($donationAnalytics) {
        while ($row = $donationAnalytics->fetch_assoc()) {
            $donationLabels[] = date("M Y", strtotime($row['month'] . "-01"));
            $donationCounts[] = $row['donation_count'];
            $donationAmounts[] = $row['total_amount'];
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Check blog posts table structure as well
$checkBlogColumnsQuery = "SHOW COLUMNS FROM blog_posts";
$blogColumnsResult = $conn->query($checkBlogColumnsQuery);
$blogDateColumn = '';
$blogDateColumnExists = false;

if ($blogColumnsResult) {
    while ($column = $blogColumnsResult->fetch_assoc()) {
        if (in_array($column['Field'], ['created_at', 'post_date', 'date', 'published_date'])) {
            $blogDateColumn = $column['Field'];
            $blogDateColumnExists = true;
            break;
        }
    }
}

if (!$blogDateColumnExists) {
    $blogDateColumn = 'post_date'; // Change this to match your actual column name
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - WingsLanka Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Copy your admin panel styles here -->
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
        
        /* Analytics cards */
        .analytics-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            border-top: 4px solid var(--primary);
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
                <a href="index.php">
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
                </a>
            </li>
            <li>
                <a href="manage_users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="analytics.php" class="active">
                    <i class="fas fa-chart-bar"></i> Analytics
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
                <h1 class="h3 mb-0 text-gray-800">Analytics Dashboard</h1>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <?php if(isset($error)): ?>
            <div class="alert alert-danger">
                <h4>Database Error</h4>
                <p><?php echo $error; ?></p>
                
                <p><strong>Table structure issue detected:</strong> The analytics page is looking for certain date columns that don't exist in your tables.</p>
                
                <h5>Quick fix options:</h5>
                <ul>
                    <li>For donations table: Found columns - <?php echo implode(', ', $columnsResult->fetch_all(MYSQLI_ASSOC)); ?></li>
                    <li>For blog posts table: Found columns - <?php echo implode(', ', $blogColumnsResult->fetch_all(MYSQLI_ASSOC)); ?></li>
                </ul>
                
                <p>Please update the Analytics page to use the correct column names or add the missing columns to your tables.</p>
            </div>
            <?php else: ?>
            
            <!-- Donations Analytics Section -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="analytics-card">
                        <h5 class="mb-4">Donation Trends (Last 6 Months)</h5>
                        <div style="height: 300px;">
                            <canvas id="donationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Traffic and Engagement Analytics -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="analytics-card">
                        <h5 class="mb-4">Popular Blog Posts</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Post Title</th>
                                        <th>Views</th>
                                        <th>Engagement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get popular blog posts
                                    $popularPostsQuery = "SELECT title, views FROM blog_posts ORDER BY views DESC LIMIT 5";
                                    $popularPosts = $conn->query($popularPostsQuery);
                                    
                                    if ($popularPosts && $popularPosts->num_rows > 0) {
                                        while ($post = $popularPosts->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td><?php echo $post['views']; ?></td>
                                        <td>
                                            <div class="progress">
                                                <?php $percentage = min(100, ($post['views'] / 100) * 100); ?>
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No blog posts found</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="analytics-card">
                        <h5 class="mb-4">Donation Sources</h5>
                        <div style="height: 250px;">
                            <canvas id="donationSourcesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart Initialization -->
    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-toggler')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        <?php if(!isset($error)): ?>
        // Donations Chart
        const donationCtx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(donationCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($donationLabels); ?>,
                datasets: [
                    {
                        label: 'Donation Count',
                        data: <?php echo json_encode($donationCounts); ?>,
                        backgroundColor: 'rgba(78, 149, 37, 0.7)',
                        borderColor: 'rgba(78, 149, 37, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Donation Amount ($)',
                        data: <?php echo json_encode($donationAmounts); ?>,
                        type: 'line',
                        fill: false,
                        backgroundColor: 'rgba(248, 111, 45, 0.7)',
                        borderColor: 'rgba(248, 111, 45, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Number of Donations'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    }
                }
            }
        });
        
        // Donation Sources Chart
        const sourcesCtx = document.getElementById('donationSourcesChart').getContext('2d');
        const sourcesChart = new Chart(sourcesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Website', 'Mobile App', 'Social Media', 'Email Campaign', 'Direct'],
                datasets: [{
                    data: [45, 25, 15, 10, 5],
                    backgroundColor: [
                        '#f86f2d',
                        '#4e9525',
                        '#2d8bf8',
                        '#9c27b0',
                        '#ff9800'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>