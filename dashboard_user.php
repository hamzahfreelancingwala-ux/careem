<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | Careem Clone</title>
    <style>
        :root { --primary: #47A73E; --bg: #f4f7f6; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; }
        nav { background: white; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .container { padding: 40px 5%; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn { background: var(--primary); color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: bold; }
        .wallet-val { font-size: 32px; font-weight: bold; color: var(--primary); }
    </style>
</head>
<body>
    <nav>
        <div style="font-size: 24px; font-weight: bold; color: var(--primary);">Careem User</div>
        <a href="logout.php" style="color: red; text-decoration: none;">Logout</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Welcome, <?php echo $user['fullname']; ?>!</h2>
            <p>Ready to go somewhere? Book a ride or send a parcel instantly.</p>
            <br>
            <a href="booking.php" class="btn">Book a Ride Now</a>
        </div>

        <div class="card">
            <h3>Careem Pay</h3>
            <div class="wallet-val">$<?php echo number_format($user['wallet_balance'], 2); ?></div>
            <p>Available Balance</p>
            <a href="wallet.php" class="btn" style="background: #333;">Add Funds</a>
        </div>
    </div>
</body>
</html>
