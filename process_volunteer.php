<?php
// Connect to the database
$conn = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
 // After line 14, update the code to:
 $name = $conn->real_escape_string($_POST['name']);
 $email = $conn->real_escape_string($_POST['email']);
 $country = $conn->real_escape_string($_POST['country'] ?? 'Sri Lanka');
 $phone = $conn->real_escape_string($_POST['phone'] ?? '');
 $message = $conn->real_escape_string($_POST['message']);
 
 // Validate inputs
 if (empty($name) || empty($email)) {
     // Redirect with error message
     header("Location: index.php?volunteer_error=missing_fields#volunteer-section");
     exit;
 }
 
 // Validate email format
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     header("Location: index.php?volunteer_error=invalid_email#volunteer-section");
     exit;
 }
 
 // Validate phone format if provided - adjusted for Sri Lanka numbers
 if (!empty($phone)) {
     // For Sri Lanka, typically formats like 7XXXXXXXX
    if ($country == "Sri Lanka" && !preg_match("/^[0-9]{9,10}$/", $phone)) {
        header("Location: index.php?volunteer_error=invalid_phone_sl#volunteer-section");
        exit;
    }
     // For other countries, more flexible validation
     elseif ($country != "Sri Lanka" && !preg_match("/^[0-9+\(\)\s.-]{7,20}$/", $phone)) {
         header("Location: index.php?volunteer_error=invalid_phone#volunteer-section");
         exit;
     }
 }
 
 // Insert into database
 $sql = "INSERT INTO volunteers (name, email, country, phone, message, created_at, status) 
         VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";
 
 // Use prepared statement for security
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("sssss", $name, $email, $country, $phone, $message);
    
 if ($stmt->execute()) {
    // Success - redirect with success message
    header("Location: index.php?volunteer_submitted=true#volunteer-section");
    exit;
} else {
    // Database error
    header("Location: index.php?volunteer_error=db_error#volunteer-section");
    exit;
}

$stmt->close();
} else {
// Not a POST request, redirect to homepage
header("Location: index.php");
exit;
}

$conn->close();
?>