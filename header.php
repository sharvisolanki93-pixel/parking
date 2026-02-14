<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkSmart | Modern Parking System</title>
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
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="admin_dashboard.php">Admin Panel</a>
                <?php else: ?>
                    <a href="dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>
