<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careem Clone | Rides & Delivery</title>
    <style>
        :root { --primary: #47A73E; --dark: #1a1a1a; --light: #f8f9fa; --white: #ffffff; }
        
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; background: var(--light); color: var(--dark); }
        
        /* Navigation */
        nav { 
            background: var(--white); 
            padding: 15px 8%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo { font-size: 30px; font-weight: 800; color: var(--primary); letter-spacing: -1px; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .user-greet { font-weight: 600; color: #555; }
        
        .btn { 
            background: var(--primary); 
            color: white; 
            padding: 12px 28px; 
            border: none; 
            border-radius: 50px; 
            cursor: pointer; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s;
            display: inline-block;
        }
        .btn:hover { background: #3a8a32; transform: scale(1.05); }
        .btn-outline { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-logout { color: #ff4444; text-decoration: none; font-size: 14px; font-weight: bold; }

        /* Hero Section */
        .hero { 
            height: 75vh; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1350&q=80'); 
            background-size: cover; 
            background-position: center;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            text-align: center; 
        }
        .hero h1 { font-size: 3.5rem; margin-bottom: 10px; font-weight: 800; }
        .hero p { font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px; }

        /* Services Grid */
        .services { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            padding: 60px 8%; 
            gap: 30px; 
            margin-top: -80px; /* Overlap effect */
        }
        .card { 
            background: var(--white); 
            padding: 40px 30px; 
            border-radius: 20px; 
            text-align: center; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            cursor: pointer; 
            border: 1px solid transparent;
        }
        .card:hover { 
            transform: translateY(-15px); 
            border-color: var(--primary);
        }
        .card-icon { font-size: 60px; margin-bottom: 20px; display: block; }
        .card h2 { margin-bottom: 15px; font-size: 24px; }
        .card p { color: #666; line-height: 1.6; }

        /* Footer */
        footer { background: #111; color: #888; text-align: center; padding: 40px 0; margin-top: 40px; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.2rem; }
            .services { margin-top: 20px; padding: 20px; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">Careem</div>
        <div class="nav-right">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="user-greet">Hi, <?php echo explode(' ', $_SESSION['fullname'])[0]; ?>!</span>
                <a href="wallet.php" class="btn btn-outline" style="padding: 8px 20px;">$ Wallet</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login / Signup</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero">
        <div>
            <h1>Go anywhere,<br><span style="color: var(--primary);">Deliver anything.</span></h1>
            <p>The everyday everything app for your daily needs.</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="signup.php" class="btn" style="font-size: 18px; padding: 15px 40px;">Get Started</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="services">
        <div class="card" onclick="window.location.href='booking.php'">
            <span class="card-icon">🚗</span>
            <h2>Book a Ride</h2>
            <p>Fast, reliable rides in minutes. Choose from Economy, Business, or Bikes.</p>
            <span style="color: var(--primary); font-weight: bold;">Book Now &rarr;</span>
        </div>

        <div class="card" onclick="window.location.href='parcel.php'">
            <span class="card-icon">📦</span>
            <h2>Send Parcel</h2>
            <p>Need to send a gift or documents? Our delivery captains are ready to help.</p>
            <span style="color: var(--primary); font-weight: bold;">Send Now &rarr;</span>
        </div>

        <div class="card" onclick="window.location.href='wallet.php'">
            <span class="card-icon">💳</span>
            <h2>Careem Pay</h2>
            <p>Top up your wallet for seamless cashless transactions and rewards.</p>
            <span style="color: var(--primary); font-weight: bold;">View Balance &rarr;</span>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Careem Clone. All rights reserved.</p>
    </footer>

</body>
</html>
