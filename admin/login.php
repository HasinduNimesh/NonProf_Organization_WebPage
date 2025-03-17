<?php
session_start();
$error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication - you should replace with proper auth
    // This is just a placeholder for the UI improvements
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WingsLanka Admin Login</title>
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
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e0f7fa 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            width: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 0;
        }
        
        .login-header {
            background: linear-gradient(to right, var(--primary), #e85a1a);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: white;
            color: var(--primary);
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .login-title {
            margin: 0;
            font-weight: 700;
        }
        
        .login-subtitle {
            margin: 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #444;
        }
        
        .form-control {
            height: 50px;
            padding-left: 45px;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(248, 111, 45, 0.25);
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            bottom: 15px;
            color: #aaa;
            transition: all 0.3s;
        }
        
        .form-control:focus + .input-icon {
            color: var(--primary);
        }
        
        .btn-login {
            background: linear-gradient(to right, var(--primary), #e85a1a);
            color: white;
            border: none;
            height: 50px;
            border-radius: 5px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: linear-gradient(to right, #e85a1a, var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 111, 45, 0.4);
            color: white;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 5px;
        }
        
        .forgot-password {
            text-align: right;
        }
        
        .forgot-password a {
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .forgot-password a:hover {
            color: var(--primary);
        }
        
        .login-footer {
            text-align: center;
            padding: 15px 30px;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            border: none;
        }
        
        .alert-danger {
            background-color: #fff2f2;
            color: #d63031;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-logo">
                            <i class="fas fa-dove"></i>
                        </div>
                        <h4 class="login-title">WingsLanka Admin</h4>
                        <p class="login-subtitle">Sign in to your account</p>
                    </div>
                    
                    <div class="login-form">
                        <?php if (!empty($error)) { ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php } ?>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-4">
                                <div class="remember-me">
                                    <input type="checkbox" id="remember" name="remember">
                                    <label for="remember">Remember me</label>
                                </div>
                                <div class="forgot-password">
                                    <a href="forgot-password.php">Forgot password?</a>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100">Sign In</button>
                        </form>
                    </div>
                    
                    <div class="login-footer">
                        &copy; <?php echo date('Y'); ?> WingsLanka. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>