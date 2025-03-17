<?php
// This file increments the view count when a blog post is viewed

// Get database connection
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get blog post ID from the request
$blog_post_id = isset($_POST['blog_post_id']) ? (int)$_POST['blog_post_id'] : 0;

if ($blog_post_id > 0) {
    // Update the view count for this blog post
    $stmt = $conn->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
    $stmt->bind_param("i", $blog_post_id);
    $stmt->execute();
    $stmt->close();
    
    // Return the updated view count
    $stmt = $conn->prepare("SELECT views FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $blog_post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $views = $row ? $row['views'] : 0;
    $stmt->close();
    
    echo json_encode(['success' => true, 'views' => $views]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid blog post ID']);
}

$conn->close();
?>