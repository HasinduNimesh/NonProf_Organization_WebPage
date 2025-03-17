<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize POST data
$post_id      = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$author_name  = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
$author_email = isset($_POST['author_email']) ? trim($_POST['author_email']) : '';
$website      = isset($_POST['website']) ? trim($_POST['website']) : '';
$comment      = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($post_id <= 0 || empty($author_name) || empty($author_email) || empty($comment)) {
    die("Please fill in all required fields.");
}

// Prepare a statement for secure insertion
$stmt = $conn->prepare("INSERT INTO blog_comments (post_id, author_name, author_email, website, comment, created_at, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$status = 'approved'; // Or use 'pending' if moderation is required
$created_at = date('Y-m-d H:i:s');
$stmt->bind_param("issssss", $post_id, $author_name, $author_email, $website, $comment, $created_at, $status);

if ($stmt->execute()) {
    // success -> close resources first
    $stmt->close();
    $conn->close();
    
    // Then redirect and exit
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "blog-single.php?id=" . $post_id;
    header("Location: http://$host$uri/$extra");
    exit;
} else {
    // failure -> close resources first
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    
    die("Error inserting comment: " . $error);
}

$stmt->close();
$conn->close();
?>
