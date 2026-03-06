<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current balance
$user_query = $conn->query("SELECT fullname, wallet_balance FROM users WHERE id = $user_id");
$user_data = $user_query->fetch_assoc();

// Handle Dummy Payment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    
    if ($amount > 0) {
        $update = $conn->query("UPDATE users SET wallet_balance = wallet_balance + $amount WHERE id = $user_id");
        if ($update) {
            echo "<script>
                    alert('Payment Successful! $$amount added to your wallet.');
                    window.location.href='wallet.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wallet | Careem Pay</title>
    <style>
        :root { --primary: #47A73E; --dark: #1d1d1d; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; display: flex; justify-content: center; padding-top: 50px; }
        .wallet-card { background: white; width: 450px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); overflow: hidden; }
        .balance-section { background: var(--primary); padding: 40px; color: white; text-align: center; }
        .balance-label { font-size: 14px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px; }
        .balance-amount { font-size: 48px; font-weight: 800; margin-top: 10px; }
        .payment-section { padding: 30px; }
        .input-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; box-sizing: border-box; font-size: 16px; }
        .btn-pay { width: 100%; background: var(--dark); color: white; border: none; padding: 15px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-pay:hover { background: #333; transform: translateY(-2px); }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #888; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="wallet-card">
    <div class="balance-section">
        <div class="balance-label">Careem Pay Balance</div>
        <div class="balance-amount">$<?php echo number_format($user_data['wallet_balance'], 2); ?></div>
        <p>Welcome, <?php echo $user_data['fullname']; ?></p>
    </div>

    <div class="payment-section">
        <h3>Recharge Wallet</h3>
        <form method="POST">
            <div class="input-group">
                <label>Amount (USD)</label>
                <input type="number" name="amount" placeholder="Enter amount" required min="1">
            </div>
            <div class="input-group">
                <label>Card Number (Dummy)</label>
                <input type="text" placeholder="xxxx xxxx xxxx 4444" maxlength="19">
            </div>
            <div style="display: flex; gap: 10px;">
                <div style="flex: 2;">
                    <label>Expiry</label>
                    <input type="text" placeholder="MM/YY">
                </div>
                <div style="flex: 1;">
                    <label>CVV</label>
                    <input type="text" placeholder="123">
                </div>
            </div>
            <br>
            <button type="submit" class="btn-pay">Add Funds Securely</button>
        </form>
        <a href="index.php" class="back-link">← Back to Services</a>
    </div>
</div>

</body>
</html>
