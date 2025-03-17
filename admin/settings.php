<?php
// filepath: d:\ClapTac\Wingslanka\welfare-gh-pages\admin\settings.php
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
$admin_id = $_SESSION['admin_id'] ?? 1;

// Get current settings
$settingsQuery = "SELECT * FROM site_settings WHERE id = 1";
$settingsResult = $conn->query($settingsQuery);
$settings = $settingsResult->fetch_assoc();

// Handle form submissions
$successMessage = "";
$errorMessage = "";

// Update Site Settings
if (isset($_POST['update_site_settings'])) {
    $site_name = $conn->real_escape_string($_POST['site_name']);
    $site_tagline = $conn->real_escape_string($_POST['site_tagline']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $contact_phone = $conn->real_escape_string($_POST['contact_phone']);
    $contact_address = $conn->real_escape_string($_POST['contact_address']);
    
    $updateQuery = "UPDATE site_settings SET 
                    site_name = ?, 
                    site_tagline = ?, 
                    contact_email = ?, 
                    contact_phone = ?, 
                    contact_address = ?,
                    updated_at = NOW() 
                    WHERE id = 1";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssss", $site_name, $site_tagline, $contact_email, $contact_phone, $contact_address);
    
    if ($stmt->execute()) {
        $successMessage = "Site settings updated successfully!";
    } else {
        $errorMessage = "Error updating site settings: " . $conn->error;
    }
    $stmt->close();
}

// Update Social Media Links
if (isset($_POST['update_social_media'])) {
    $facebook = $conn->real_escape_string($_POST['facebook']);
    $twitter = $conn->real_escape_string($_POST['twitter']);
    $instagram = $conn->real_escape_string($_POST['instagram']);
    $youtube = $conn->real_escape_string($_POST['youtube']);
    $linkedin = $conn->real_escape_string($_POST['linkedin']);
    
    $updateQuery = "UPDATE site_settings SET 
                    facebook_url = ?, 
                    twitter_url = ?, 
                    instagram_url = ?, 
                    youtube_url = ?, 
                    linkedin_url = ?,
                    updated_at = NOW() 
                    WHERE id = 1";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssss", $facebook, $twitter, $instagram, $youtube, $linkedin);
    
    if ($stmt->execute()) {
        $successMessage = "Social media links updated successfully!";
    } else {
        $errorMessage = "Error updating social media links: " . $conn->error;
    }
    $stmt->close();
}

// Update Email Settings
if (isset($_POST['update_email_settings'])) {
    $smtp_host = $conn->real_escape_string($_POST['smtp_host']);
    $smtp_port = intval($_POST['smtp_port']);
    $smtp_user = $conn->real_escape_string($_POST['smtp_user']);
    $smtp_password = !empty($_POST['smtp_password']) ? $conn->real_escape_string($_POST['smtp_password']) : $settings['smtp_password'];
    $smtp_encryption = $conn->real_escape_string($_POST['smtp_encryption']);
    $email_from = $conn->real_escape_string($_POST['email_from']);
    $email_from_name = $conn->real_escape_string($_POST['email_from_name']);
    
    $updateQuery = "UPDATE site_settings SET 
                    smtp_host = ?, 
                    smtp_port = ?, 
                    smtp_user = ?, 
                    smtp_password = ?, 
                    smtp_encryption = ?,
                    email_from = ?,
                    email_from_name = ?,
                    updated_at = NOW() 
                    WHERE id = 1";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sisssss", $smtp_host, $smtp_port, $smtp_user, $smtp_password, $smtp_encryption, $email_from, $email_from_name);
    
    if ($stmt->execute()) {
        $successMessage = "Email settings updated successfully!";
    } else {
        $errorMessage = "Error updating email settings: " . $conn->error;
    }
    $stmt->close();
}

// Change Admin Password
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // First verify current password
    $adminQuery = "SELECT password FROM admin_users WHERE id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
    
    if (password_verify($current_password, $admin['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $updateQuery = "UPDATE admin_users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $hashed_password, $admin_id);
            
            if ($stmt->execute()) {
                $successMessage = "Password changed successfully!";
            } else {
                $errorMessage = "Error changing password: " . $conn->error;
            }
            $stmt->close();
        } else {
            $errorMessage = "New passwords do not match!";
        }
    } else {
        $errorMessage = "Current password is incorrect!";
    }
}

// Handle logo upload
if (isset($_POST['update_logo']) && isset($_FILES['logo'])) {
    $target_dir = "../icons/";
    $file_extension = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
    $new_filename = "site_logo." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is a actual image
    $check = getimagesize($_FILES["logo"]["tmp_name"]);
    if ($check !== false) {
        // Check file size (2MB max)
        if ($_FILES["logo"]["size"] <= 2000000) {
            // Allow only certain file formats
            if ($file_extension == "jpg" || $file_extension == "png" || $file_extension == "jpeg" || $file_extension == "gif") {
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                    // Update database with new logo path
                    $logo_path = "icons/" . $new_filename;
                    $updateQuery = "UPDATE site_settings SET logo_path = ?, updated_at = NOW() WHERE id = 1";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("s", $logo_path);
                    
                    if ($stmt->execute()) {
                        $successMessage = "Logo uploaded successfully!";
                    } else {
                        $errorMessage = "Error updating logo in database: " . $conn->error;
                    }
                    $stmt->close();
                } else {
                    $errorMessage = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $errorMessage = "Sorry, your file is too large (max 2MB).";
        }
    } else {
        $errorMessage = "File is not an image.";
    }
}

// Handle database backup - placeholder function
function backup_database() {
    // This would be implemented with actual database backup functionality
    return true;
}

// Create database backup
if (isset($_POST['create_backup'])) {
    if (backup_database()) {
        $backupFile = 'wingslanka_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $successMessage = "Database backup created successfully: $backupFile";
    } else {
        $errorMessage = "Error creating database backup.";
    }
}

// Refresh settings after any updates
$settingsResult = $conn->query($settingsQuery);
$settings = $settingsResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - WingsLanka Admin</title>
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
        
        /* Settings specific styles */
        .settings-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .settings-header {
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }
        
        .settings-header h5 {
            margin-bottom: 0;
            color: var(--dark);
            display: flex;
            align-items: center;
        }
        
        .settings-header h5 i {
            margin-right: 10px;
            color: var(--primary);
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .nav-pills .nav-link {
            color: var(--dark);
            border-radius: 8px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary);
        }
        
        .nav-pills .nav-link i {
            margin-right: 8px;
        }
        
        .system-info {
            font-size: 0.9rem;
        }
        
        .system-info td {
            padding: 0.5rem;
        }
        
        .system-info tr:nth-child(odd) {
            background-color: rgba(0,0,0,0.02);
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
            <li>
                <a href="manage_users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="settings.php" class="active">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="analytics.php">
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
                <h1 class="h3 mb-0 text-gray-800">Settings</h1>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <?php if(!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $successMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="settings-card">
                        <div class="nav flex-column nav-pills" id="settings-tab" role="tablist">
                            <button class="nav-link active" id="site-settings-tab" data-bs-toggle="pill" data-bs-target="#site-settings" type="button" role="tab">
                                <i class="fas fa-globe"></i> Site Settings
                            </button>
                            <button class="nav-link" id="social-media-tab" data-bs-toggle="pill" data-bs-target="#social-media" type="button" role="tab">
                                <i class="fas fa-share-alt"></i> Social Media
                            </button>
                            <button class="nav-link" id="email-settings-tab" data-bs-toggle="pill" data-bs-target="#email-settings" type="button" role="tab">
                                <i class="fas fa-envelope"></i> Email Settings
                            </button>
                            <button class="nav-link" id="appearance-tab" data-bs-toggle="pill" data-bs-target="#appearance" type="button" role="tab">
                                <i class="fas fa-palette"></i> Appearance
                            </button>
                            <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-lock"></i> Security
                            </button>
                            <button class="nav-link" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backup" type="button" role="tab">
                                <i class="fas fa-database"></i> Backup & Restore
                            </button>
                            <button class="nav-link" id="system-info-tab" data-bs-toggle="pill" data-bs-target="#system-info" type="button" role="tab">
                                <i class="fas fa-info-circle"></i> System Information
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Site Settings -->
                        <div class="tab-pane fade show active" id="site-settings" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-globe"></i> Site Settings</h5>
                                </div>
                                
                                <form method="post" action="">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="site_name" class="form-label">Site Name</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'WingsLanka Foundation'); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="site_tagline" class="form-label">Site Tagline</label>
                                            <input type="text" class="form-control" id="site_tagline" name="site_tagline" value="<?php echo htmlspecialchars($settings['site_tagline'] ?? 'Empowering Children Across Sri Lanka'); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'info@wingslanka.lk'); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="contact_phone" class="form-label">Contact Phone</label>
                                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? '+94 71 461 8664'); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="contact_address" class="form-label">Contact Address</label>
                                        <textarea class="form-control" id="contact_address" name="contact_address" rows="2"><?php echo htmlspecialchars($settings['contact_address'] ?? 'NO. 7/1, Pragathi Mawatha, Homagama, Sri Lanka'); ?></textarea>
                                    </div>
                                    
                                    <button type="submit" name="update_site_settings" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="tab-pane fade" id="social-media" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-share-alt"></i> Social Media Links</h5>
                                </div>
                                
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="facebook" class="form-label">
                                            <i class="fab fa-facebook text-primary me-2"></i> Facebook
                                        </label>
                                        <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? 'https://www.facebook.com/wingslankafoundation/'); ?>" placeholder="https://facebook.com/yourpage">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="twitter" class="form-label">
                                            <i class="fab fa-twitter text-info me-2"></i> Twitter
                                        </label>
                                        <input type="url" class="form-control" id="twitter" name="twitter" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/yourhandle">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="instagram" class="form-label">
                                            <i class="fab fa-instagram text-danger me-2"></i> Instagram
                                        </label>
                                        <input type="url" class="form-control" id="instagram" name="instagram" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? 'https://instagram.com/wingslanka'); ?>" placeholder="https://instagram.com/yourprofile">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="youtube" class="form-label">
                                            <i class="fab fa-youtube text-danger me-2"></i> YouTube
                                        </label>
                                        <input type="url" class="form-control" id="youtube" name="youtube" value="<?php echo htmlspecialchars($settings['youtube_url'] ?? 'https://youtube.com/@wingslankafoundation-ke6dq'); ?>" placeholder="https://youtube.com/yourchannel">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="linkedin" class="form-label">
                                            <i class="fab fa-linkedin text-primary me-2"></i> LinkedIn
                                        </label>
                                        <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/company/yourcompany">
                                    </div>
                                    
                                    <button type="submit" name="update_social_media" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Email Settings -->
                        <div class="tab-pane fade" id="email-settings" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-envelope"></i> Email Settings</h5>
                                </div>
                                
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="email_from" class="form-label">From Email</label>
                                        <input type="email" class="form-control" id="email_from" name="email_from" value="<?php echo htmlspecialchars($settings['email_from'] ?? 'noreply@wingslanka.lk'); ?>" required>
                                        <div class="form-text">This is the email address that will be used to send emails from your site.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email_from_name" class="form-label">From Name</label>
                                        <input type="text" class="form-control" id="email_from_name" name="email_from_name" value="<?php echo htmlspecialchars($settings['email_from_name'] ?? 'WingsLanka Foundation'); ?>" required>
                                        <div class="form-text">This is the name that will appear as the sender for emails from your site.</div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h6 class="mb-3">SMTP Configuration</h6>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_host" class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com'); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_port" class="form-label">SMTP Port</label>
                                        <input type="number" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? '587'); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_encryption" class="form-label">Encryption</label>
                                        <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                            <option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'tls') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                            <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                            <option value="none" <?php echo ($settings['smtp_encryption'] ?? '') == 'none' ? 'selected' : ''; ?>>None</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_user" class="form-label">SMTP Username</label>
                                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="smtp_password" class="form-label">SMTP Password</label>
                                        <input type="password" class="form-control" id="smtp_password" name="smtp_password" placeholder="Leave empty to keep current password">
                                        <div class="form-text">Leave empty to keep the current password.</div>
                                    </div>
                                    
                                    <button type="submit" name="update_email_settings" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Appearance -->
                        <div class="tab-pane fade" id="appearance" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-palette"></i> Appearance</h5>
                                </div>
                                
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="mb-4">
                                        <label class="form-label">Current Logo</label>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="../<?php echo htmlspecialchars($settings['logo_path'] ?? 'icons/favicon/android-chrome-192x192.png'); ?>" alt="Current Logo" class="img-thumbnail me-3" style="max-width: 100px;">
                                            <div>
                                                <p class="text-muted mb-0">Current logo file: <?php echo htmlspecialchars($settings['logo_path'] ?? 'Default logo'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Upload New Logo</label>
                                        <input class="form-control" type="file" id="logo" name="logo">
                                        <div class="form-text">Recommended size: 192x192px. Max file size: 2MB. Allowed formats: JPG, PNG, GIF.</div>
                                    </div>
                                    
                                    <button type="submit" name="update_logo" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i> Upload Logo
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <h6 class="mb-3">Theme Colors</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Theme color settings are coming soon.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-lock"></i> Security Settings</h5>
                                </div>
                                
                                <form method="post" action="">
                                    <h6 class="mb-3">Change Admin Password</h6>
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    
                                    <button type="submit" name="change_password" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i> Change Password
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <h6 class="mb-3">Security Recommendations</h6>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Use a strong, unique password
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Change your password regularly
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        Enable two-factor authentication (coming soon)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Backup & Restore -->
                        <div class="tab-pane fade" id="backup" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-database"></i> Backup & Restore</h5>
                                </div>
                                
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i> Regular backups are essential to protect your data. We recommend creating a backup at least once a week.
                                </div>
                                
                                <form method="post" action="" class="mb-4">
                                    <h6 class="mb-3">Create Backup</h6>
                                    <p>This will create a backup of your database including all site content, settings, and user data.</p>
                                    
                                    <button type="submit" name="create_backup" class="btn btn-primary">
                                        <i class="fas fa-download me-2"></i> Create Database Backup
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <h6 class="mb-3">Restore From Backup</h6>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Restoring from a backup will overwrite all current data. This action cannot be undone.
                                </div>
                                
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="restore_file" class="form-label">Select Backup File</label>
                                        <input class="form-control" type="file" id="restore_file" name="restore_file">
                                    </div>
                                    
                                    <button type="submit" name="restore_backup" class="btn btn-danger" disabled>
                                        <i class="fas fa-upload me-2"></i> Restore From Backup
                                    </button>
                                    <div class="form-text">Restore functionality is currently disabled for safety reasons.</div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- System Information -->
                        <div class="tab-pane fade" id="system-info" role="tabpanel">
                            <div class="settings-card">
                                <div class="settings-header">
                                    <h5><i class="fas fa-info-circle"></i> System Information</h5>
                                </div>
                                
                                <table class="table system-info">
                                    <tbody>
                                        <tr>
                                            <td width="30%"><strong>PHP Version</strong></td>
                                            <td><?php echo phpversion(); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>MySQL Version</strong></td>
                                            <td>
                                                <?php 
                                                $tempConn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
                                                echo $tempConn->server_info; 
                                                $tempConn->close();
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Server Software</strong></td>
                                            <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Server OS</strong></td>
                                            <td><?php echo php_uname('s') . ' ' . php_uname('r'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Max Upload Size</strong></td>
                                            <td><?php echo ini_get('upload_max_filesize'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Post Max Size</strong></td>
                                            <td><?php echo ini_get('post_max_size'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Memory Limit</strong></td>
                                            <td><?php echo ini_get('memory_limit'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Max Execution Time</strong></td>
                                            <td><?php echo ini_get('max_execution_time'); ?> seconds</td>
                                        </tr>
                                        <tr>
                                            <td><strong>WingsLanka Version</strong></td>
                                            <td>1.0.0</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Admin Panel Version</strong></td>
                                            <td>1.0.0</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="mt-3">
                                    <h6>PHP Extensions</h6>
                                    <div class="row">
                                        <?php 
                                        $extensions = ['mysqli', 'curl', 'gd', 'json', 'mbstring', 'xml', 'zip'];
                                        foreach($extensions as $ext):
                                        ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="d-flex align-items-center">
                                                <?php if(extension_loaded($ext)): ?>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <?php else: ?>
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                <?php endif; ?>
                                                <?php echo $ext; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggler for mobile view
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.querySelector('.sidebar-toggler');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggler && sidebar) {
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992) {
                    if (!sidebar.contains(event.target) && !sidebarToggler.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
        
        // Password strength check
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        if (newPasswordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity("Passwords don't match");
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            });
        }
    </script>
</body>
</html>