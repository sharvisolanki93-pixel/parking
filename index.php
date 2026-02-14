<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkSmart | Modern Parking Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-gradient"></div>
    <div class="sphere sphere-1"></div>
    <div class="sphere sphere-2"></div>

    <nav>
        <div class="logo">ParkSmart</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="container">
        <section style="text-align: center; padding: 4rem 0;">
            <h1 style="font-size: 4rem; margin-bottom: 1.5rem; line-height: 1.2;">
                Park Your Vehicle <br>
                <span style="color: var(--secondary)">With Ease & Security</span>
            </h1>
            <p style="color: var(--text-gray); font-size: 1.25rem; max-width: 600px; margin: 0 auto 3rem;">
                Experience the next generation of parking management. Real-time slot tracking, easy bookings, and secure payments.
            </p>
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <a href="register.php" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2.5rem;">Book a Slot Now</a>
                <a href="#features" class="btn" style="border: 1px solid var(--glass-border);">View Features</a>
            </div>
        </section>

        <section id="features" class="slot-grid" style="margin-top: 5rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="glass-card" style="padding: 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚡</div>
                <h3 style="margin-bottom: 1rem;">Real-time Update</h3>
                <p style="color: var(--text-gray);">See available slots in real-time before you even reach the destination.</p>
            </div>
            <div class="glass-card" style="padding: 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔒</div>
                <h3 style="margin-bottom: 1rem;">Secure Booking</h3>
                <p style="color: var(--text-gray);">Your parking spot is reserved and secured the moment you book it.</p>
            </div>
            <div class="glass-card" style="padding: 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🧾</div>
                <h3 style="margin-bottom: 1rem;">Auto Billing</h3>
                <p style="color: var(--text-gray);">Transparent pricing and automated billing for your peace of mind.</p>
            </div>
        </section>
    </main>
</body>
</html>
