<?php 
include 'db.php';
session_start(); 

// Security: Redirect to login if not authenticated
if(!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Parcel | Careem Delivery</title>
    <style>
        :root { --primary: #47A73E; --dark: #1a1a1a; --gray: #f4f4f4; --white: #ffffff; }
        
        body { margin: 0; display: flex; height: 100vh; font-family: 'Segoe UI', sans-serif; background: var(--gray); overflow: hidden; }

        /* Sidebar UI */
        .sidebar { width: 420px; background: var(--white); padding: 30px; box-shadow: 5px 0 30px rgba(0,0,0,0.1); z-index: 10; display: flex; flex-direction: column; overflow-y: auto; }
        .logo { color: var(--primary); font-size: 26px; font-weight: 800; margin-bottom: 5px; }
        .sub-logo { color: #888; font-size: 14px; margin-bottom: 30px; }

        h2 { font-size: 22px; margin-bottom: 20px; color: var(--dark); }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-size: 12px; font-weight: 700; color: #555; margin-bottom: 8px; text-transform: uppercase; }
        
        input, select { width: 100%; padding: 14px; border: 2px solid #eee; border-radius: 12px; font-size: 15px; outline: none; box-sizing: border-box; transition: 0.3s; }
        input:focus { border-color: var(--primary); background: #f9fff9; }

        /* Parcel Type Selection */
        .type-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
        .type-item { border: 2px solid #eee; padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; font-size: 14px; transition: 0.2s; }
        .type-item:hover { border-color: var(--primary); }
        .type-item.active { border-color: var(--primary); background: #f0fff0; color: var(--primary); font-weight: bold; }

        .btn-send { width: 100%; background: var(--dark); color: white; padding: 18px; border: none; border-radius: 12px; font-weight: bold; font-size: 18px; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-send:hover { background: #333; transform: translateY(-2px); }

        /* Real-time Tracking Panel */
        #tracking-panel { display: none; margin-top: 20px; padding: 20px; border-radius: 15px; border: 2px solid var(--primary); background: #fff; animation: fadeInUp 0.5s ease; }
        .pulse { width: 10px; height: 10px; background: var(--primary); border-radius: 50%; display: inline-block; margin-right: 10px; box-shadow: 0 0 0 rgba(71, 167, 62, 0.4); animation: pulse-red 2s infinite; }
        
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(71, 167, 62, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(71, 167, 62, 0); } 100% { box-shadow: 0 0 0 0 rgba(71, 167, 62, 0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Map Area */
        #map { flex-grow: 1; background: #e0e0e0 url('https://user-images.githubusercontent.com/1705030/104100657-30310200-5290-11eb-896e-5776d65a88c4.png'); background-size: cover; position: relative; }
        .bike-icon { position: absolute; font-size: 45px; transition: all 3s cubic-bezier(0.4, 0, 0.2, 1); left: -100px; top: 50%; filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3)); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">Careem <span style="color:#333">Delivery</span></div>
    <div class="sub-logo">Reliable parcel delivery in minutes</div>
    
    <div id="booking-step">
        <h2>Send a Package</h2>
        <div class="input-group">
            <label>Pickup Location</label>
            <input type="text" id="pickup" placeholder="Building, Street, Area">
        </div>
        <div class="input-group">
            <label>Drop-off Location</label>
            <input type="text" id="dropoff" placeholder="Recipient's Address">
        </div>
        <div class="input-group">
            <label>Parcel Type</label>
            <div class="type-grid">
                <div class="type-item active" onclick="selectType(this)">📄 Documents</div>
                <div class="type-item" onclick="selectType(this)">🎁 Gift/Food</div>
                <div class="type-item" onclick="selectType(this)">💻 Electronics</div>
                <div class="type-item" onclick="selectType(this)">📦 Box/Other</div>
            </div>
        </div>
        <button class="btn-send" onclick="initiateDelivery()">Send Now</button>
        <a href="dashboard_user.php" style="text-align:center; display:block; margin-top:15px; color:#888; text-decoration:none; font-size:14px;">Back to Home</a>
    </div>

    <div id="tracking-panel">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div class="pulse"></div>
            <strong id="status-heading">Searching for Captain...</strong>
        </div>
        <div id="captain-card" style="background: #f9f9f9; padding: 15px; border-radius: 12px; margin-top: 10px;">
            <p id="eta-text" style="margin: 0; color: #555; font-size: 14px;">We are assigning the nearest delivery bike to your location.</p>
        </div>
        <div id="captain-details" style="margin-top: 15px; display: none; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">👨‍✈️</div>
            <div>
                <strong style="display:block;">Captain Zeeshan</strong>
                <small style="color:#777;">Honda 125 (LED-4492)</small>
            </div>
        </div>
    </div>
</div>

<div id="map">
    <div id="bike" class="bike-icon">🏍️</div>
    <div style="position: absolute; bottom: 20px; right: 20px; background: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">Delivery Mode Active</div>
</div>

<script>
    function selectType(element) {
        document.querySelectorAll('.type-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
    }

    function initiateDelivery() {
        const p = document.getElementById('pickup').value;
        const d = document.getElementById('dropoff').value;

        if (p === "" || d === "") {
            alert("Please fill in both pickup and drop-off locations.");
            return;
        }

        // Switch UI
        document.getElementById('booking-step').style.display = 'none';
        document.getElementById('tracking-panel').style.display = 'block';

        // Simulation Step 1: Assigned
        setTimeout(() => {
            document.getElementById('status-heading').innerText = "Captain Assigned";
            document.getElementById('eta-text').innerText = "Captain Zeeshan is moving towards your pickup point.";
            document.getElementById('captain-details').style.display = 'flex';
            
            const bike = document.getElementById('bike');
            bike.style.left = '40%';
            bike.style.top = '30%';

            // Simulation Step 2: Arrived
            setTimeout(() => {
                document.getElementById('status-heading').innerText = "Captain has Arrived!";
                document.getElementById('status-heading').style.color = "#47A73E";
                document.getElementById('eta-text').innerText = "Please hand over the parcel to the Captain.";
                bike.style.left = '10%';
                bike.style.top = '50%';
                
                alert("🔔 Your Delivery Captain is outside!");
            }, 4000);

        }, 2000);
    }
</script>

</body>
</html>
