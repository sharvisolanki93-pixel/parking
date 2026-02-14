<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch stats
$total_slots = $pdo->query("SELECT COUNT(*) FROM slots")->fetchColumn();
$occupied_slots = $pdo->query("SELECT COUNT(*) FROM slots WHERE status = 'occupied'")->fetchColumn();
$available_slots = $total_slots - $occupied_slots;
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM bookings WHERE status = 'completed'")->fetchColumn() ?? 0;

// Fetch all slots
$stmt = $pdo->query("SELECT * FROM slots");
$slots = $stmt->fetchAll();

// Fetch recent bookings
$stmt = $pdo->query("SELECT b.*, u.name as user_name, s.slot_number FROM bookings b JOIN users u ON b.user_id = u.id JOIN slots s ON b.slot_id = s.id ORDER BY b.entry_time DESC LIMIT 10");
$recent_bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ParkSmart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .stat-card {
            padding: 2rem;
            text-align: center;
        }
        .stat-card h3 {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-card p {
            font-size: 2rem;
            font-weight: 800;
            color: var(--secondary);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        th, td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid var(--glass-border);
        }
        th {
            color: var(--text-gray);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>
    
    <nav>
        <div class="logo">ParkSmart Admin</div>
        <div class="nav-links">
            <a href="admin_dashboard.php">Overview</a>
            <a href="logout.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Logout</a>
        </div>
    </nav>

    <main class="container">
        <header style="margin-bottom: 3rem;">
            <h1>Admin Overview</h1>
            <p style="color: var(--text-gray);">System-wide statistics and monitoring.</p>
        </header>

        <section class="slot-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 4rem;">
            <div class="glass-card stat-card">
                <h3>Total Slots</h3>
                <p><?php echo $total_slots; ?></p>
            </div>
            <div class="glass-card stat-card">
                <h3>Occupied</h3>
                <p style="color: var(--danger);"><?php echo $occupied_slots; ?></p>
            </div>
            <div class="glass-card stat-card">
                <h3>Available</h3>
                <p style="color: var(--success);"><?php echo $available_slots; ?></p>
            </div>
            <div class="glass-card stat-card">
                <h3>Total Revenue</h3>
                <p>$<?php echo number_format($total_revenue, 2); ?></p>
            </div>
        </section>

        <section style="margin-bottom: 4rem;">
            <h2>Recent Bookings</h2>
            <div class="glass-card" style="padding: 0; overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Vehicle</th>
                            <th>Slot</th>
                            <th>Entry</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['user_name']; ?></td>
                            <td><?php echo $booking['vehicle_number']; ?></td>
                            <td><?php echo $booking['slot_number']; ?></td>
                            <td><?php echo $booking['entry_time']; ?></td>
                            <td>
                                <span style="color: <?php echo $booking['status'] == 'active' ? 'var(--success)' : 'var(--text-gray)'; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
