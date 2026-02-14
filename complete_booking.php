<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Fetch booking and slot info
        $stmt = $pdo->prepare("SELECT b.*, s.price_per_hour, s.id as slot_id FROM bookings b JOIN slots s ON b.slot_id = s.id WHERE b.id = ? AND b.user_id = ?");
        $stmt->execute([$booking_id, $user_id]);
        $booking = $stmt->fetch();

        if ($booking && $booking['status'] == 'active') {
            $exit_time = date('Y-m-d H:i:s');
            $entry_time = new DateTime($booking['entry_time']);
            $exit_time_dt = new DateTime($exit_time);
            
            $interval = $entry_time->diff($exit_time_dt);
            $hours = $interval->h + ($interval->days * 24);
            if ($interval->i > 0) $hours += 1; // Round up to next hour

            $total_amount = $hours * $booking['price_per_hour'];

            // Update booking
            $stmt = $pdo->prepare("UPDATE bookings SET exit_time = ?, total_amount = ?, status = 'completed' WHERE id = ?");
            $stmt->execute([$exit_time, $total_amount, $booking_id]);

            // Update slot
            $stmt = $pdo->prepare("UPDATE slots SET status = 'available' WHERE id = ?");
            $stmt->execute([$booking['slot_id']]);

            $pdo->commit();
            header("Location: dashboard.php?success=completed&amount=$total_amount");
        } else {
            $pdo->rollBack();
            header("Location: dashboard.php?error=invalid_booking");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: dashboard.php?error=system_error");
    }
}
?>
