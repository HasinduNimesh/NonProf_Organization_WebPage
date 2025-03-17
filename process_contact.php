<?php
// Database connection
$mysqli = new mysqli("sql107.epizy.com", "if0_38374977", "GIHTEk7Qu0Nu", "if0_38374977_wingslanka_db", 3306);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $subject = $mysqli->real_escape_string($_POST['subject']);
    $message = $mysqli->real_escape_string($_POST['message']);
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "name";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "email";
    }
    
    if (empty($subject)) {
        $errors[] = "subject";
    }
    
    if (empty($message)) {
        $errors[] = "message";
    }
    
    // If there are errors, redirect back with error message
    if (count($errors) > 0) {
        header("Location: contact.php?error=" . implode(",", $errors));
        exit;
    }
    
    // Insert into database
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        // Optional: Send email notification
        $to = "wings@wingslanka.com";
        $email_subject = "New Contact Form Message: $subject";
        $email_body = "You have received a new message from your website contact form.\n\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Subject: $subject\n" .
                     "Message:\n$message\n";
        $headers = "From: noreply@wingslanka.com\n";
        $headers .= "Reply-To: $email";
        
        mail($to, $email_subject, $email_body, $headers);
        
        // Redirect with success message
        header("Location: contact.php?success=true");
        exit;
    } else {
        // Redirect with error message
        header("Location: contact.php?error=db");
        exit;
    }
    
    $stmt->close();
}

// If not a POST request, redirect to contact page
header("Location: contact.php");
exit;

$mysqli->close();
?>