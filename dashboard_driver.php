<?php
include 'db.php';
session_start();

// Security check: Only allow logged-in drivers
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$driver_id = $_SESSION['user_id'];

// Fetch Driver Stats
$query = $conn->query("SELECT fullname, wallet_balance FROM users WHERE id = $driver_id AND role = 'driver'");
$driver = $query->fetch_assoc();

if (!$driver) {
    echo "<script>alert('Access Denied: Drivers Only'); window.location.href='index.php';</script>";
    exit();
}

// Fetch Pending Requests (Simulated logic for the pro-look)
$requests = $conn->query("SELECT b.*, u.fullname as customer FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.status = 'pending' ORDER BY b.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Captain Dashboard | Careem Clone</title>
    <style>
        :root { --careem-green: #47A73E; --dark-bg: #121212; --card-bg: #1E1E1E; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--dark-bg); color: white; margin: 0; }
        
        /* Sidebar/Header */
        header { background: var(--card-bg); padding: 20px 5%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
        .status-toggle { background: #333; padding: 5px 15px; border-radius: 20px; display: flex; align-items: center; gap: 10px; }
        .dot { width: 10px; height: 10px; background: var(--careem-green); border-radius: 50%; box-shadow: 0 0 10px var(--careem-green); }

        .container { padding: 30px 5%; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--card-bg); padding: 25px; border-radius: 15px; text-align: center; border: 1px solid #333; }
        .stat-card h3 { margin: 0; font-size: 14px; color: #888; text-transform: uppercase; }
        .stat-card p { margin: 10px 0 0; font-size: 28px; font-weight: bold; color: var(--careem-green); }

        /* Job Requests */
        .jobs-section h2 { margin-bottom: 20px; font-weight: 600; }
        .job-card { background: var(--card-bg); padding: 20px; border-radius: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid var(--careem-green); }
        .job-info p { margin: 5px 0; color: #bbb; font-size: 14px; }
        .job-info strong { color: white; font-size: 16px; }
        
        .btn-accept { background: var(--careem-green); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-accept:hover { transform: scale(1.05); background: #3a8a32; }
        
        .logout-btn { color: #ff4444; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<header>
    <div style="font-size: 24px; font-weight: 900; color: var(--careem-green);">Careem <span style="color:white">Captain</span></div>
    <div style="display: flex; align-items: center; gap: 20px;">
        <div class="status-toggle"><div class="dot"></div> Online</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<div class="container">
    <h1>Welcome back, <?php echo $driver['fullname']; ?>!</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Earnings</h3>
            <p>$<?php echo number_format($driver['wallet_balance'], 2); ?></p>
        </div>
        <div class="stat-card">
            <h3>Rides Today</h3>
            <p>0</p>
        </div>
        <div class="stat-card">
            <h3>Rating</h3>
            <p>4.9 ★</p>
        </div>
    </div>

    <div class="jobs-section">
        <h2>Available Requests</h2>
        
        <?php if($requests->num_rows > 0): ?>
            <?php while($row = $requests->fetch_assoc()): ?>
                <div class="job-card">
                    <div class="job-info">
                        <strong><?php echo strtoupper($row['service_type']); ?> - Request from <?php echo $row['customer']; ?></strong>
                        <p>📍 <strong>Pickup:</strong> <?php echo $row['pickup_loc']; ?></p>
                        <p>🏁 <strong>Drop:</strong> <?php echo $row['drop_loc']; ?></p>
                        <p>💰 <strong>Estimated Fare:</strong> $<?php echo $row['fare']; ?></p>
                    </div>
                    <div>
                        <button class="btn-accept" onclick="acceptJob(<?php echo $row['id']; ?>)">Accept Ride</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #666;">
                <p>Scanning for nearby rides...</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function acceptJob(id) {
    if(confirm('Do you want to accept this request?')) {
        // In a real app, you would use AJAX to update the DB
        alert('Ride #' + id + ' Accepted! Navigate to pickup point.');
        window.location.reload();
    }
}
</script>

</body>
</html>
