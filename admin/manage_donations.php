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
    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_donations.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = trim($_POST['donor_name']);
    $donor_email = trim($_POST['donor_email']);
    $amount = trim($_POST['amount']);
    $message = trim($_POST['message']);
    $payment_method = trim($_POST['payment_method']);
    $receipt = trim($_POST['receipt']);
    $donor_image = trim($_POST['donor_image']);

    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO donations (donor_name, donor_email, amount, message, payment_method, receipt, donor_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssissss", $donor_name, $donor_email, $amount, $message, $payment_method, $receipt, $donor_image);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_donations.php");
        exit;
    } elseif ($action == 'edit' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE donations SET donor_name = ?, donor_email = ?, amount = ?, message = ?, payment_method = ?, receipt = ?, donor_image = ? WHERE id = ?");
        $stmt->bind_param("ssissssi", $donor_name, $donor_email, $amount, $message, $payment_method, $receipt, $donor_image, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_donations.php");
        exit;
    }
}

if ($action == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM donations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $donationData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donations - WingsLanka Admin</title>
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
        
        /* Donation Amount */
        .donation-amount {
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Payment Method Badge */
        .payment-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            background-color: #e9ecef;
            color: #495057;
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
                <h1 class="h3 mb-0">Manage Donations</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Donations</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="d-lg-none btn btn-sm btn-outline-primary sidebar-toggler mb-2">
                    <i class="fas fa-bars"></i>
                </button>
                <?php if ($action == 'list'): ?>
                <a href="manage_donations.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Donation
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
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM donations ORDER BY created_at DESC");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($row['donor_image'])): ?>
                                    <div class="me-3" style="width:40px;height:40px;background-image:url('<?php echo htmlspecialchars($row['donor_image']); ?>');background-size:cover;background-position:center;border-radius:50%;"></div>
                                    <?php else: ?>
                                    <div class="me-3" style="width:40px;height:40px;background-color:#f0f0f0;color:#aaa;display:flex;align-items:center;justify-content:center;border-radius:50%;"><i class="fas fa-user"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($row['donor_name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['donor_email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="donation-amount">$<?php echo number_format($row['amount'], 2); ?></span>
                            </td>
                            <td>
                                <?php
                                $paymentMethod = strtolower($row['payment_method']);
                                $badgeClass = 'bg-secondary';
                                $icon = 'fa-credit-card';
                                
                                if (strpos($paymentMethod, 'paypal') !== false) {
                                    $badgeClass = 'bg-primary';
                                    $icon = 'fa-paypal';
                                } elseif (strpos($paymentMethod, 'card') !== false || strpos($paymentMethod, 'visa') !== false || strpos($paymentMethod, 'master') !== false) {
                                    $badgeClass = 'bg-success';
                                    $icon = 'fa-credit-card';
                                } elseif (strpos($paymentMethod, 'bank') !== false || strpos($paymentMethod, 'transfer') !== false) {
                                    $badgeClass = 'bg-info';
                                    $icon = 'fa-university';
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <i class="fas <?php echo $icon; ?> me-1"></i>
                                    <?php echo htmlspecialchars($row['payment_method']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="manage_donations.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="manage_donations.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this donation?');">
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
                <h5 class="card-title"><?php echo ucfirst($action); ?> Donation</h5>
                
                <form method="post" action="" class="row g-3">
                    <div class="col-md-6">
                        <label for="donor_name" class="form-label">Donor Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="donor_name" name="donor_name" value="<?php echo isset($donationData) ? htmlspecialchars($donationData['donor_name']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="donor_email" class="form-label">Donor Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="donor_email" name="donor_email" value="<?php echo isset($donationData) ? htmlspecialchars($donationData['donor_email']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="amount" name="amount" value="<?php echo isset($donationData) ? htmlspecialchars($donationData['amount']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="PayPal" <?php echo (isset($donationData) && $donationData['payment_method'] == 'PayPal') ? 'selected' : ''; ?>>PayPal</option>
                                <option value="Credit Card" <?php echo (isset($donationData) && $donationData['payment_method'] == 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
                                <option value="Bank Transfer" <?php echo (isset($donationData) && $donationData['payment_method'] == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                                <option value="Cash" <?php echo (isset($donationData) && $donationData['payment_method'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="Other" <?php echo (isset($donationData) && $donationData['payment_method'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="receipt" class="form-label">Receipt Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                            <input type="text" class="form-control" id="receipt" name="receipt" value="<?php echo isset($donationData) ? htmlspecialchars($donationData['receipt']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3"><?php echo isset($donationData) ? htmlspecialchars($donationData['message']) : ''; ?></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="donor_image" class="form-label">Donor Image URL</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                            <input type="text" class="form-control" id="donor_image" name="donor_image" value="<?php echo isset($donationData) ? htmlspecialchars($donationData['donor_image']) : ''; ?>">
                        </div>
                        <small class="text-muted">Enter the URL of the donor's profile image</small>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <?php echo ucfirst($action); ?> Donation
                        </button>
                        <a href="manage_donations.php" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
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
    </script>
</body>
</html>