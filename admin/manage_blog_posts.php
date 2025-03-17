<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug settings for file uploads
ini_set('log_errors', 1);
ini_set('error_log', 'php-errors.log');

// Debug file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST request received");
    error_log("POST data: " . print_r($_POST, true));
    if (!empty($_FILES)) {
        error_log("Files in request: " . print_r($_FILES, true));
    } else {
        error_log("No files uploaded");
    }
}

// Database connection
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_blog_posts.php");
    exit;
}


if ($action == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $postData = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle image deletion if requested
if ($action == 'delete_image' && isset($_GET['id']) && isset($_GET['post_id'])) {
    $image_id = (int)$_GET['id'];
    $post_id = (int)$_GET['post_id'];
    
    // Get the image path first to delete the file
    $stmt = $conn->prepare("SELECT image_path FROM blog_images WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Change this line
        $image_path = "../images/Blog_Projects/" . $row['image_path'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the physical file
        }
    }
    
    // Delete the record
    $stmt = $conn->prepare("DELETE FROM blog_images WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back to edit page
    header("Location: manage_blog_posts.php?action=edit&id=$post_id");
    exit;
}

// Handle AJAX image reordering (to be called via fetch/Ajax)
if (isset($_POST['update_image_order'])) {
    $image_id = (int)$_POST['image_id'];
    $new_order = (int)$_POST['order'];
    
    $stmt = $conn->prepare("UPDATE blog_images SET display_order = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_order, $image_id);
    $result = $stmt->execute();
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_image_order'])) {
    // Retrieve and sanitize inputs
    $slug = trim($_POST['slug']);
    $post_date = trim($_POST['post_date']);
    $author = trim($_POST['author']);
    $excerpt = trim($_POST['excerpt']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $status = trim($_POST['status']);
    
    // Handle main featured image upload
    $image = ""; // Default empty value
    
    // If editing and checkbox is checked, keep the current image
    if ($action == 'edit' && isset($_GET['id']) && isset($_POST['keep_current_image'])) {
        // Fetch existing image if available
        if (isset($postData) && !empty($postData['image'])) {
            $image = $postData['image'];
        }
    }
    
    // Check if a new image was uploaded (ensure 'name' is not empty to prevent processing when no file selected)
if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0 && !empty($_FILES['image_upload']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['image_upload']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Validate file type
    if (in_array($ext, $allowed)) {
        // Create a unique filename with timestamp to prevent overwriting
        $new_filename = 'blog_main_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
        
        // Change this line - Update upload directory
        $upload_dir = "../images/Blog_Projects";
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $_SESSION['upload_status'] = [
                    'type' => 'danger',
                    'message' => "Failed to create directory: $upload_dir"
                ];
                error_log("Failed to create directory: $upload_dir");
            }
        }
        
        if (is_writable($upload_dir)) {
            $upload_path = $upload_dir . "/" . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $upload_path)) {
                // If successful, update image field for database
                $image = $new_filename;
                $_SESSION['upload_status'] = [
                    'type' => 'success',
                    'message' => "Main image uploaded successfully."
                ];
                error_log("Image uploaded successfully: " . $upload_path);
                
                // ADD THE CODE RIGHT HERE:
                // If editing and replacing an image, delete the old one
                if ($action == 'edit' && isset($postData['image']) && !empty($postData['image']) && $postData['image'] != $new_filename) {
                    $old_image_path = "../images/Blog_Projects/" . $postData['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path); // Delete old file
                        error_log("Deleted old image: " . $old_image_path);
                    }
                }
            } else {
                // Debug message for failed upload
                $_SESSION['upload_status'] = [
                    'type' => 'danger',
                    'message' => "Failed to move uploaded file. Error code: " . $_FILES['image_upload']['error']
                ];
                error_log("Failed to move uploaded file. Error code: " . $_FILES['image_upload']['error']);
            }
        } else {
            $_SESSION['upload_status'] = [
                'type' => 'danger',
                'message' => "Upload directory is not writable: $upload_dir"
            ];
            error_log("Upload directory is not writable: $upload_dir");
        }
    } else {
        $_SESSION['upload_status'] = [
            'type' => 'danger',
            'message' => "Invalid file type: $ext. Allowed types: jpg, jpeg, png, gif."
        ];
        error_log("Invalid file type: " . $ext);
    }
}
    
    // Database operations for main blog post data
    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO blog_posts (slug, post_date, author, excerpt, title, content, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $slug, $post_date, $author, $excerpt, $title, $content, $status, $image);
        $stmt->execute();
        $new_post_id = $conn->insert_id; // Get the new post ID for additional images
        $stmt->close();
        
        // Handle additional images if a new post was created successfully
        if ($new_post_id) {
            handleAdditionalImages($conn, $new_post_id);
        }
        
        header("Location: manage_blog_posts.php");
        exit;
    } elseif ($action == 'edit' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE blog_posts SET slug = ?, post_date = ?, author = ?, excerpt = ?, title = ?, content = ?, status = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssssssi", $slug, $post_date, $author, $excerpt, $title, $content, $status, $image, $id);
        $stmt->execute();
        $stmt->close();
        
        // Handle additional images for this existing post
        handleAdditionalImages($conn, $id);
        
        header("Location: manage_blog_posts.php");
        exit;
    }
}

// Function to handle upload of additional images// In the handleAdditionalImages() function:
function handleAdditionalImages($conn, $post_id) {
    // Check if additional images were uploaded
    if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name']) && !empty($_FILES['additional_images']['name'][0])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $image_count = count($_FILES['additional_images']['name']);
        
        // Get the current highest display order for this post
        $result = $conn->query("SELECT MAX(display_order) as max_order FROM blog_images WHERE post_id = $post_id");
        $row = $result->fetch_assoc();
        $current_max_order = $row['max_order'] ?? 0;
        
        // Change this line - Update directory path
        $upload_dir = "../images/Blog_Projects";
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                error_log("Failed to create directory: $upload_dir");
                return;
            }
        }
        
        // Process each uploaded image
        for ($i = 0; $i < $image_count; $i++) {
            if ($_FILES['additional_images']['error'][$i] == 0 && !empty($_FILES['additional_images']['name'][$i])) {
                $filename = $_FILES['additional_images']['name'][$i];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                // Validate file type
                if (in_array($ext, $allowed)) {
                    // Create unique filename with timestamp and order
                    $current_max_order++;
                    $new_filename = 'blog_' . $post_id . '_' . date('YmdHis') . '_' . $current_max_order . '.' . $ext;
                    $upload_path = $upload_dir . "/" . $new_filename;
                    
                    // Move uploaded file
                    if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $upload_path)) {
                        // Insert into blog_images table
                        $caption = basename($_FILES['additional_images']['name'][$i]); // Use original filename as caption
                        $stmt = $conn->prepare("INSERT INTO blog_images (post_id, image_path, caption, display_order) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("issi", $post_id, $new_filename, $caption, $current_max_order);
                        
                        if ($stmt->execute()) {
                            error_log("Successfully added image to database: $new_filename");
                        } else {
                            error_log("Failed to add image to database: " . $stmt->error);
                        }
                        $stmt->close();
                    } else {
                        error_log("Failed to move uploaded additional image: " . $_FILES['additional_images']['error'][$i]);
                    }
                } else {
                    error_log("Invalid file type for additional image: $ext");
                }
            }
        }
    }
}

// Get admin username if available
$admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog Posts - WingsLanka Admin</title>
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
        
        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .table thead th {
            border-bottom: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }
        
        .table tbody tr:hover {
            background-color: rgba(248, 111, 45, 0.03);
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        /* Card Styling */
        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .content-card .card-title {
            margin-bottom: 1rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        /* Form Styling */
        .form-control, .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0.75rem 1rem;
            height: auto;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(248, 111, 45, 0.15);
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 0.5rem;
        }
        
        /* Button Styling */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.5rem 1.5rem;
        }
        
        .btn-primary:hover {
            background-color: #e85a1a;
            border-color: #e85a1a;
        }
        
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
            transition: all 0.2s;
        }
        
        .btn-edit {
            background-color: rgba(0, 123, 255, 0.1);
            color: #0d6efd;
            border: none;
        }
        
        .btn-edit:hover {
            background-color: #0d6efd;
            color: white;
        }
        
        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: none;
        }
        
        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        
        /* Status Badge */
        .badge-status {
            padding: 0.35em 0.65em;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.8em;
        }
        
        .badge-published {
            background: rgba(78, 149, 37, 0.1);
            color: var(--secondary);
        }
        
        .badge-draft {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        /* Text Editor */
        .editor-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 250px;
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
                <a href="manage_contacts.php" class="active">
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
                <a href="settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h1 class="h3 mb-0">Manage Blog Posts</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Blog Posts</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler mb-2">
                    <i class="fas fa-bars"></i>
                </button>
                <?php if ($action == 'list'): ?>
                <a href="manage_blog_posts.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Post
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action == 'list'): ?>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">Title</th>
                            <th width="15%">Author</th>
                            <th width="15%">Date</th>
                            <th width="10%">Status</th>
                            <th width="10%">Views</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM blog_posts ORDER BY post_date DESC");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($row['image'])): ?>
                                    <!-- Change this line -->
                                    <div class="me-3" style="width:50px;height:50px;background-image:url('../images/Blog_Projects/<?php echo htmlspecialchars($row['image']); ?>');background-size:cover;background-position:center;border-radius:4px;"></div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($row['title']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($row['slug'], 0, 40) . (strlen($row['slug']) > 40 ? '...' : '')); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['post_date'])); ?></td>
                            <td>
                                <?php if ($row['status'] == 'published'): ?>
                                    <span class="badge-status badge-published">Published</span>
                                <?php else: ?>
                                    <span class="badge-status badge-draft">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo number_format($row['views']); ?>
                                <i class="fas fa-eye ms-1 text-muted"></i>
                            </td>
                            <td>
                                <a href="manage_blog_posts.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="manage_blog_posts.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
        <?php elseif ($action == 'add' || $action == 'edit'): ?>
            <div class="content-card">
                <h5 class="card-title"><?php echo ucfirst($action); ?> Blog Post</h5>
                
                <form method="post" action="" class="row g-3" enctype="multipart/form-data">
                    <div class="col-md-8">
                        <label for="title" class="form-label">Title</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($postData) ? htmlspecialchars($postData['title']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="slug" class="form-label">Slug</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo isset($postData) ? htmlspecialchars($postData['slug']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="post_date" class="form-label">Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" class="form-control" id="post_date" name="post_date" value="<?php echo isset($postData) ? date('Y-m-d', strtotime($postData['post_date'])) : date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="author" class="form-label">Author</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo isset($postData) ? htmlspecialchars($postData['author']) : $admin_username; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                            <select class="form-select" id="status" name="status" required>
                                <option value="published" <?php echo (isset($postData) && $postData['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($postData) && $postData['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2" required><?php echo isset($postData) ? htmlspecialchars($postData['excerpt']) : ''; ?></textarea>
                        <small class="text-muted">A short summary of the post that appears on the blog index page</small>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="8" required><?php echo isset($postData) ? htmlspecialchars($postData['content']) : ''; ?></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="image_upload" class="form-label">Featured Image</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                            <input type="file" class="form-control" id="image_upload" name="image_upload" accept="image/*">
                        </div>
                        <?php if(isset($postData) && !empty($postData['image'])): ?>
                        <div class="mt-2">
                            <div class="d-flex align-items-center">
                                <!-- Change this line - still using old path -->
                                <img src="../images/Blog_Projects/<?php echo htmlspecialchars($postData['image']); ?>" alt="Current featured image" class="img-thumbnail" style="max-height: 100px;">
                                <div class="ms-3">
                                    <p class="mb-0">Current image: <?php echo htmlspecialchars($postData['image']); ?></p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="keep_current_image" id="keep_current_image" value="1" checked>
                                        <label class="form-check-label" for="keep_current_image">
                                            Keep current image if no new image is uploaded
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <small class="text-muted">Recommended size: 1200×800 pixels, JPG or PNG format</small>
                    </div>
                    
                    <!-- Add section for additional blog images -->
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Additional Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="additional_images" class="form-label">Upload additional images</label>
                                    <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                    <small class="text-muted">You can select multiple images at once (max 5 files)</small>
                                </div>
                                
                                <?php 
                                // Display existing additional images if in edit mode
                                if ($action == 'edit' && isset($_GET['id'])):
                                    $post_id = (int)$_GET['id'];
                                    $additionalImages = $conn->query("SELECT * FROM blog_images WHERE post_id = $post_id ORDER BY display_order ASC");
                                    if ($additionalImages && $additionalImages->num_rows > 0):
                                ?>
                                <div class="mt-4">
                                    <h6>Current Additional Images</h6>
                                    <div class="row g-2">
                                    <?php while ($img = $additionalImages->fetch_assoc()): ?>
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="card h-100">
                                                <!-- Change this line -->
                                                <img src="../images/Blog_Projects/<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top" alt="Blog image">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted"><?php echo htmlspecialchars($img['caption'] ?? ''); ?></small>
                                                        <a href="manage_blog_posts.php?action=delete_image&id=<?php echo $img['id']; ?>&post_id=<?php echo $post_id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this image?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-footer p-2 bg-light">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Order</span>
                                                        <select class="form-select form-select-sm image-order" data-image-id="<?php echo $img['id']; ?>">
                                                            <?php for($i=1; $i<=$additionalImages->num_rows; $i++): ?>
                                                            <option value="<?php echo $i; ?>" <?php echo ($img['display_order'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php endif; endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <?php echo ucfirst($action); ?> Post
                        </button>
                        <a href="manage_blog_posts.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    </div>
                    
                    <!-- Add this upload status message section -->
                    <div class="col-12" id="uploadStatus">
                        <?php if (isset($_SESSION['upload_status'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['upload_status']['type']; ?> mt-3">
                                <?php echo $_SESSION['upload_status']['message']; ?>
                            </div>
                            <?php unset($_SESSION['upload_status']); ?>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-toggler')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Auto-generate slug from title
        document.getElementById('title')?.addEventListener('blur', function() {
            const slugField = document.getElementById('slug');
            if (slugField && !slugField.value) {
                slugField.value = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .substring(0, 50);
            }
        });
        
        // Handle image order changes
        document.querySelectorAll('.image-order').forEach(select => {
            select.addEventListener('change', function() {
                const imageId = this.dataset.imageId;
                const newOrder = this.value;
                
                // Send Ajax request to update order
                fetch('manage_blog_posts.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_image_order=1&image_id=${imageId}&order=${newOrder}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Could add a success notification here
                        window.location.reload(); // Reload to reflect new order
                    }
                })
                .catch(error => {
                    console.error('Error updating image order:', error);
                });
            });
        });
        
        // Preview uploaded image (optional enhancement)
        document.getElementById('image_upload')?.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if it doesn't exist
                    let preview = document.getElementById('image_preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image_preview';
                        preview.className = 'mt-2';
                        this.parentElement.parentElement.appendChild(preview);
                    }
                    
                    preview.innerHTML = `
                        <div class="card">
                            <div class="card-body p-2">
                                <p class="mb-1"><strong>Preview:</strong></p>
                                <img src="${e.target.result}" alt="Image preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                    `;
                }.bind(this);
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>