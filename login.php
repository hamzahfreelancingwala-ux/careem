<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizing inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    
    if(!empty($email) && !empty($pass)) {
        // Query to check user
        $res = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$pass'");
        
        if ($res->num_rows > 0) {
            $user = $res->fetch_assoc();
            
            // Setting Session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // This must be 'user' or 'driver'
            $_SESSION['fullname'] = $user['fullname'];

            // JavaScript Redirection for speed and reliability
            if ($user['role'] == 'driver') {
                echo "<script>
                        alert('Welcome Captain " . $user['fullname'] . "');
                        window.location.href='dashboard_driver.php';
                      </script>";
            } else {
                // CHANGED: Redirects to User Dashboard instead of Index
                echo "<script>
                        alert('Welcome " . $user['fullname'] . "');
                        window.location.href='dashboard_user.php';
                      </script>";
            }
        } else {
            $error = "Invalid Email or Password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Careem Clone</title>
    <style>
        :root { --primary: #47A73E; --dark: #1a1a1a; }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 380px; }
        .logo-area { text-align: center; margin-bottom: 30px; }
        .logo-area h2 { color: var(--primary); margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -1px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 16px; outline: none; }
        input:focus { border-color: var(--primary); }
        .btn-login { width: 100%; background: var(--primary); color: white; border: none; padding: 14px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; margin-top: 10px; }
        .btn-login:hover { background: #3a8a32; box-shadow: 0 5px 15px rgba(71, 167, 62, 0.3); }
        .error-msg { background: #fee7e7; color: #d93025; padding: 12px; border-radius: 8px; text-align: center; margin-bottom: 15px; font-size: 14px; border: 1px solid #fabdbe; }
        .signup-link { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
        .signup-link a { color: var(--primary); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo-area">
        <h2>Careem</h2>
        <p style="color: #888;">Enter details to continue</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="signup-link">
        New to Careem? <a href="signup.php">Create Account</a>
    </div>
</div>

</body>
</html>
