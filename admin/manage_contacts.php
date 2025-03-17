<?php
// filepath: d:\ClapTac\Wingslanka\welfare-gh-pages\admin\manage_contacts.php
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

// Update status if needed
if (isset($_POST['update_status'])) {
    $message_id = $_POST['message_id'];
    $new_status = $_POST['status'];
    
    $updateQuery = "UPDATE contact_messages SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $new_status, $message_id);
    
    if ($stmt->execute()) {
        $statusMessage = "Message status updated successfully";
    } else {
        $statusMessage = "Error updating status: " . $conn->error;
    }
    $stmt->close();
}

// Delete message if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $message_id = $_GET['delete'];
    
    $deleteQuery = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $message_id);
    
    if ($stmt->execute()) {
        $statusMessage = "Message deleted successfully";
    } else {
        $statusMessage = "Error deleting message: " . $conn->error;
    }
    $stmt->close();
}

// Get filter values
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Build query based on filters
$query = "SELECT * FROM contact_messages WHERE 1=1";
if (!empty($status_filter)) {
    $query .= " AND status = '$status_filter'";
}
if (!empty($search_term)) {
    $query .= " AND (name LIKE '%$search_term%' OR email LIKE '%$search_term%' OR subject LIKE '%$search_term%')";
}
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact Messages - WingsLanka Admin</title>
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
        
        /* Status badges */
        .badge-new {
            background-color: #ffc107;
        }
        
        .badge-read {
            background-color: #17a2b8;
        }
        
        .badge-replied {
            background-color: #28a745;
        }
        
        .badge-archived {
            background-color: #6c757d;
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
                <h1 class="h3 mb-0 text-gray-800">Manage Contact Messages</h1>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <?php if(isset($statusMessage)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $statusMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="status">Filter by Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Messages</option>
                                <option value="new" <?php echo $status_filter == 'new' ? 'selected' : ''; ?>>New</option>
                                <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Read</option>
                                <option value="replied" <?php echo $status_filter == 'replied' ? 'selected' : ''; ?>>Replied</option>
                                <option value="archived" <?php echo $status_filter == 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search by name, email or subject" value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Messages List -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Contact Form Messages</h5>
                    
                    <?php if($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($message = $result->fetch_assoc()): ?>
                                <tr class="<?php echo $message['status'] == 'new' ? 'table-warning' : ''; ?>">
                                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                                    <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $message['status'] == 'new' ? 'warning' : 
                                                ($message['status'] == 'read' ? 'info' : 
                                                ($message['status'] == 'replied' ? 'success' : 'secondary')); 
                                        ?>">
                                            <?php echo ucfirst($message['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $message['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $message['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this message?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?php echo $message['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Message Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>From: <?php echo htmlspecialchars($message['name']); ?></h6>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                                                <p><strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?></p>
                                                <p><strong>Date:</strong> <?php echo date('F j, Y g:i a', strtotime($message['created_at'])); ?></p>
                                                <div class="card mt-3">
                                                    <div class="card-body">
                                                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo htmlspecialchars($message['subject']); ?>" class="btn btn-primary">Reply by Email</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal<?php echo $message['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Message Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="status<?php echo $message['id']; ?>" class="form-label">Status</label>
                                                        <select name="status" id="status<?php echo $message['id']; ?>" class="form-select">
                                                            <option value="new" <?php echo $message['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                                                            <option value="read" <?php echo $message['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
                                                            <option value="replied" <?php echo $message['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
                                                            <option value="archived" <?php echo $message['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                        <h5>No messages found</h5>
                        <p class="text-muted">No contact form messages match your current filters.</p>
                    </div>
                    <?php endif; ?>
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
        
        // Auto-mark as read when viewing
        document.querySelectorAll('[data-bs-target^="#viewModal"]').forEach(function(button) {
            button.addEventListener('click', function() {
                // If message is new, mark as read
                var row = this.closest('tr');
                if (row.classList.contains('table-warning')) {
                    // Could use AJAX here to update status without page refresh
                    // For now, just visual indication
                    row.classList.remove('table-warning');
                }
            });
        });
    </script>
</body>
</html>