<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slot_id'])) {
    $slot_id = $_POST['slot_id'];
    $vehicle_number = $_POST['vehicle_number'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Check if slot is still available
        $stmt = $pdo->prepare("SELECT status FROM slots WHERE id = ? FOR UPDATE");
        $stmt->execute([$slot_id]);
        $slot = $stmt->fetch();

        if ($slot['status'] == 'available') {
            // Update slot status
            $stmt = $pdo->prepare("UPDATE slots SET status = 'occupied' WHERE id = ?");
            $stmt->execute([$slot_id]);

            // Create booking
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, slot_id, vehicle_number) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $slot_id, $vehicle_number]);

            $pdo->commit();
            header("Location: dashboard.php?success=booked");
        } else {
            $pdo->rollBack();
            header("Location: dashboard.php?error=already_occupied");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: dashboard.php?error=system_error");
    }
}
?>
