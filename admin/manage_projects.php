<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM project WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_projects.php");
    exit;
}

// Replace your existing POST handler with this updated version
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $last_donation = trim($_POST['last_donation']);
    $progress = (int)$_POST['progress'];
    $raised = trim($_POST['raised']);
    $target = trim($_POST['target']);
    
    // Handle image upload or use existing image
    $image = ""; // Default empty image name
    
    // If editing and checkbox is checked, keep the current image
    if ($action == 'edit' && isset($_GET['id']) && isset($_POST['keep_current_image'])) {
        if (isset($projectData) && !empty($projectData['image'])) {
            $image = $projectData['image'];
        }
    }
    
    // Check if a new image was uploaded
    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0 && !empty($_FILES['image_upload']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image_upload']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validate file type
        if (in_array($ext, $allowed)) {
            // Create a unique filename with timestamp
            $new_filename = 'project_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
            
            // Set upload directory
            $upload_dir = "../images/Blog_Projects";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $upload_path = $upload_dir . "/" . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $upload_path)) {
                // Update image name for database
                $image = $new_filename;
                
                // If editing and replacing image, delete the old one
                if ($action == 'edit' && isset($projectData['image']) && !empty($projectData['image']) && $projectData['image'] != $new_filename) {
                    $old_image_path = "../images/Blog_Projects/" . $projectData['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path); // Delete old file
                    }
                }
            } else {
                // REPLACE THIS SECTION with your improved error handling:
                $_SESSION['upload_status'] = [
                    'type' => 'danger',
                    'message' => "Failed to upload image. Please try again."
                ];
                error_log("Failed to move uploaded file: " . $_FILES['image_upload']['error']);
            }
        } else {
            // Log error for debugging
            error_log("Invalid file type: " . $ext);
        }
    }

    // Database operations
    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO project (title, description, image, last_donation, progress, raised, target, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssiss", $title, $description, $image, $last_donation, $progress, $raised, $target);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_projects.php");
        exit;
    } elseif ($action == 'edit' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE project SET title = ?, description = ?, image = ?, last_donation = ?, progress = ?, raised = ?, target = ? WHERE id = ?");
        $stmt->bind_param("ssssissi", $title, $description, $image, $last_donation, $progress, $raised, $target, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_projects.php");
        exit;
    }
}

if ($action == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM project WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $projectData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
-pages\admin\manage_projects.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - WingsLanka Admin</title>
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
        
        /* Sidebar (same as dashboard) */
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
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Action Buttons */
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
        
        /* Progress Bar */
        .progress {
            height: 8px;
            margin-bottom: 0;
        }
        
        .progress-bar {
            background: linear-gradient(to right, var(--secondary), var(--primary));
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
                <h1 class="h3 mb-0">Manage Projects</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler mb-2">
                    <i class="fas fa-bars"></i>
                </button>
                <?php if ($action == 'list'): ?>
                <a href="manage_projects.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Project
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action == 'list'): ?>
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Progress</th>
                            <th>Raised/Target</th>
                            <th>Last Donation</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM project ORDER BY created_at DESC");
                        while ($row = $result->fetch_assoc()):
                            $progress = (int)$row['progress'];
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($row['image'])): ?>
                                    <!-- In the project listing section, update this line -->
                                    <div class="me-3" style="width:50px;height:50px;background-image:url('../images/Blog_Projects/<?php echo htmlspecialchars($row['image']); ?>');background-size:cover;background-position:center;border-radius:4px;"></div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($row['title']); ?></h6>
                                        <small class="text-muted"><?php echo mb_substr(htmlspecialchars($row['description']), 0, 50) . '...'; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="width:100px">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span><?php echo $progress; ?>%</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold">$<?php echo htmlspecialchars($row['raised']); ?></span>
                                <small class="text-muted"> / $<?php echo htmlspecialchars($row['target']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['last_donation']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="manage_projects.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="manage_projects.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this project?');">
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
                    <h5 class="card-title"><?php echo ucfirst($action); ?> Project</h5>
                    
                    <!-- Single form with proper enctype for file uploads -->
                    <form method="post" action="" class="row g-3" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Project Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($projectData) ? htmlspecialchars($projectData['title']) : ''; ?>" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo isset($projectData) ? htmlspecialchars($projectData['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="image_upload" class="form-label">Project Image</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                <input type="file" class="form-control" id="image_upload" name="image_upload" accept="image/*">
                            </div>
                            <?php if(isset($projectData) && !empty($projectData['image'])): ?>
                            <div class="mt-2">
                                <div class="d-flex align-items-center">
                                    <img src="../images/Blog_Projects/<?php echo htmlspecialchars($projectData['image']); ?>" alt="Current project image" class="img-thumbnail" style="max-height: 100px;">
                                    <div class="ms-3">
                                        <p class="mb-0">Current image: <?php echo htmlspecialchars($projectData['image']); ?></p>
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
                            <small class="text-muted">Recommended size: 800×600 pixels, JPG or PNG format</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="last_donation" class="form-label">Last Donation</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="text" class="form-control" id="last_donation" name="last_donation" value="<?php echo isset($projectData) ? htmlspecialchars($projectData['last_donation']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="progress" class="form-label">Progress (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" value="<?php echo isset($projectData) ? (int)$projectData['progress'] : ''; ?>" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="raised" class="form-label">Amount Raised</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="raised" name="raised" value="<?php echo isset($projectData) ? htmlspecialchars($projectData['raised']) : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="target" class="form-label">Target Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="target" name="target" value="<?php echo isset($projectData) ? htmlspecialchars($projectData['target']) : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <?php echo ucfirst($action); ?> Project
                            </button>
                            <a href="manage_projects.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </div>

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
        
        // ADD THIS NEW CODE - Image preview functionality
        document.getElementById('image_upload')?.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.innerHTML = `
                        <div class="mt-2">
                            <p><strong>Preview:</strong></p>
                            <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    `;
                    const container = document.querySelector('#image_upload').closest('.col-md-6');
                    const existingPreview = container.querySelector('.preview-container');
                    if (existingPreview) container.removeChild(existingPreview);
                    preview.className = 'preview-container';
                    container.appendChild(preview);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
