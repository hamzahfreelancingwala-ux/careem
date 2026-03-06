<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$name', '$email', '$pass', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Registration Successful! Redirecting to Login...');
                window.location.href='login.php';
              </script>";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup | Careem Clone</title>
    <style>
        :root { --primary: #47A73E; --dark: #1a1a1a; }
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .signup-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: var(--primary); text-align: center; margin-bottom: 30px; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        .btn-signup { width: 100%; background: var(--primary); color: white; border: none; padding: 14px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 16px; margin-top: 10px; }
        .btn-signup:hover { background: #3a8a32; box-shadow: 0 5px 15px rgba(71, 167, 62, 0.3); }
        .footer-link { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .footer-link a { color: var(--primary); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Account</h2>
        <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <select name="role">
                <option value="user">I want to ride / send parcel</option>
                <option value="driver">I want to drive (Captain)</option>
            </select>
            <button type="submit" class="btn-signup">Register Now</button>
        </form>
        <div class="footer-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
