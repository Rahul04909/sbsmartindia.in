<?php
session_start();
require_once '../database/db_config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;
                
                // Update last login
                $update = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                $update->bind_param("i", $id);
                $update->execute();

                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sbsmart Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2271b1;
            --primary-hover: #135e96;
            --text-main: #3c434a;
            --text-muted: #646970;
            --border-color: #dcdcde;
            --bg-color: #f0f0f1;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: var(--text-main);
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-logo img {
            max-height: 60px;
            width: auto;
        }

        .login-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1d2327;
        }

        .login-header p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #8c8f94;
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 42px; /* Left padding for icon */
            border: 1px solid #8c8f94;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 1px var(--primary-color);
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
        }

        .error-msg {
            background-color: #fce8e8;
            color: #d63638;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 25px;
            border-left: 4px solid #d63638;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links {
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <!-- Ensure correct path to logo -->
            <img src="../asstes/logo/logo.png" alt="Sbsmart India">
        </div>

        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your admin account</p>
            </div>
            
            <?php if($error): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">Log In</button>
            </form>
            
            <div class="footer-links">
                <a href="../index.php">&larr; Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
