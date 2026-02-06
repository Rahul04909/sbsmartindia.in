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
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body {
            background-color: #f0f0f1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid #dcdcde;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            font-size: 24px;
            color: #1d2327;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1d2327;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #135e96;
        }
        .error-msg {
            background-color: #fce8e8;
            color: #d63638;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #d63638;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2>Admin Login</h2>
            <p style="color: #646970;">Please sign in to continue</p>
        </div>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Log In</button>
        </form>
    </div>
</body>
</html>
