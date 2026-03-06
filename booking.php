<?php 
include 'db.php';
session_start(); 
if(!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Logic to save booking via AJAX (simulated via POST here for simplicity)
if(isset($_POST['ajax_book'])) {
    $uid = $_SESSION['user_id'];
    $p = $_POST['pickup'];
    $d = $_POST['dropoff'];
    $f = $_POST['fare'];
    $conn->query("INSERT INTO bookings (user_id, service_type, pickup_loc, drop_loc, fare, status) VALUES ('$uid', 'ride', '$p', '$d', '$f', 'pending')");
    echo "success";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Ride | Careem Clone</title>
    <style>
        :root { --primary: #47A73E; --dark: #1a1a1a; --gray: #f8f9fa; }
        body { margin: 0; display: flex; height: 100vh; font-family: 'Segoe UI', sans-serif; background: var(--gray); overflow: hidden; }
        
        .sidebar { width: 400px; background: white; padding: 30px; box-shadow: 5px 0 25px rgba(0,0,0,0.1); z-index: 10; display: flex; flex-direction: column; }
        .logo { color: var(--primary); font-size: 24px; font-weight: 800; margin-bottom: 20px; }
        
        .input-box { width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 12px; margin-bottom: 15px; box-sizing: border-box; font-size: 16px; }
        .fare-card { background: var(--gray); padding: 15px; border-radius: 12px; text-align: center; margin-bottom: 20px; border: 1px solid #ddd; }
        
        /* Status Tracking UI */
        #tracking-ui { display: none; margin-top: 20px; padding: 20px; border-radius: 15px; background: #fff; border: 1px solid var(--primary); animation: slideUp 0.5s ease; }
        .status-badge { background: var(--primary); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 10px; display: inline-block; }
        
        .btn-main { width: 100%; background: var(--dark); color: white; padding: 18px; border: none; border-radius: 12px; font-weight: bold; font-size: 18px; cursor: pointer; transition: 0.3s; }
        .btn-main:hover { background: #333; }

        #map { flex-grow: 1; background: #e0e0e0 url('https://user-images.githubusercontent.com/1705030/104100657-30310200-5290-11eb-896e-5776d65a88c4.png'); background-size: cover; position: relative; }
        .car { position: absolute; font-size: 40px; transition: all 4s linear; left: -50px; top: 50%; display: none; }
        
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">Careem</div>
        <div id="booking-form">
            <h2>Your Ride</h2>
            <input type="text" id="p" class="input-box" placeholder="Pickup Location" oninput="updateFare()">
            <input type="text" id="d" class="input-box" placeholder="Drop-off Location" oninput="updateFare()">
            <div class="fare-card">
                <small>Estimated Fare</small>
                <div id="fare-val" style="font-size: 28px; font-weight: 800;">$0.00</div>
            </div>
            <button class="btn-main" id="book-btn" onclick="startBooking()">Confirm Booking</button>
        </div>

        <div id="tracking-ui">
            <div id="status-tag" class="status-badge">Finding Captain...</div>
            <h3 id="driver-name" style="margin: 5px 0;">Searching for Captain...</h3>
            <p id="eta" style="color: #666; margin: 5px 0;">Estimating arrival time...</p>
            <hr>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 40px; height: 40px; background: #ddd; border-radius: 50%;"></div>
                <div>
                    <strong>Captain Ahmed</strong><br>
                    <small>White Toyota Corolla (ABC-123)</small>
                </div>
            </div>
        </div>
    </div>

    <div id="map">
        <div id="car-icon" class="car">🚗</div>
    </div>

    <script>
        function updateFare() {
            let p = document.getElementById('p').value;
            let d = document.getElementById('d').value;
            if(p.length > 2 && d.length > 2) {
                let fare = (p.length + d.length) * 0.5 + 5;
                document.getElementById('fare-val').innerText = "$" + fare.toFixed(2);
            }
        }

        function startBooking() {
            document.getElementById('booking-form').style.display = 'none';
            document.getElementById('tracking-ui').style.display = 'block';
            
            // Step 1: Requesting
            setTimeout(() => {
                // Step 2: Accepted
                document.getElementById('status-tag').innerText = "Captain On The Way";
                document.getElementById('driver-name').innerText = "Captain Ahmed is coming";
                document.getElementById('eta').innerText = "Arriving in 2 mins";
                
                let car = document.getElementById('car-icon');
                car.style.display = 'block';
                car.style.left = '40%'; // Move to middle of map
                
                // Step 3: Arrived
                setTimeout(() => {
                    document.getElementById('status-tag').innerText = "Captain Arrived";
                    document.getElementById('status-tag').style.background = "#000";
                    document.getElementById('driver-name').innerText = "Your Captain is here!";
                    document.getElementById('eta').innerText = "Captain is waiting at pickup point";
                    car.style.left = '10%'; // Car stops at "User location"
                    
                    // Ring a notification sound or alert
                    alert("🔔 Captain Ahmed has arrived!");
                }, 5000); // 5 seconds later it shows "Arrived"

            }, 2000);
        }
    </script>
</body>
</html>
