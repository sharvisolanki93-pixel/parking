<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch available slots
$stmt = $pdo->query("SELECT * FROM slots");
$slots = $stmt->fetchAll();

// Fetch active bookings for this user
$stmt = $pdo->prepare("SELECT b.*, s.slot_number FROM bookings b JOIN slots s ON b.slot_id = s.id WHERE b.user_id = ? AND b.status = 'active'");
$stmt->execute([$_SESSION['user_id']]);
$active_bookings = $stmt->fetchAll();

include 'includes/header.php';
?>

    <main class="container">
        <header style="margin-bottom: 3rem;">
            <h1>Dashboard</h1>
            <p style="color: var(--text-gray);">Manage your parking slots and active bookings.</p>
        </header>

        <section style="margin-bottom: 4rem;">
            <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.5rem;">🚗</span> Available Slots
            </h2>
            <div class="slot-grid">
                <?php foreach ($slots as $slot): ?>
                    <div class="slot <?php echo strtolower($slot['status']); ?>" 
                         onclick="<?php echo $slot['status'] == 'available' ? "openBookingModal({$slot['id']}, '{$slot['slot_number']}', {$slot['price_per_hour']})" : ""; ?>">
                        <span class="slot-number"><?php echo $slot['slot_number']; ?></span>
                        <span class="slot-type"><?php echo $slot['type']; ?></span>
                        <span style="font-size: 0.7rem; margin-top: 0.5rem;">$<?php echo $slot['price_per_hour']; ?>/hr</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!empty($active_bookings)): ?>
        <section>
            <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.5rem;">🕒</span> Active Bookings
            </h2>
            <div class="slot-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                <?php foreach ($active_bookings as $booking): ?>
                    <div class="glass-card" style="padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="font-weight: 800; color: var(--secondary);">SLOT: <?php echo $booking['slot_number']; ?></span>
                            <span style="color: var(--success);">● Active</span>
                        </div>
                        <p style="font-size: 0.9rem; color: var(--text-gray); margin-bottom: 0.5rem;">Vehicle: <?php echo $booking['vehicle_number']; ?></p>
                        <p style="font-size: 0.9rem; color: var(--text-gray); margin-bottom: 1rem;">Entry: <?php echo $booking['entry_time']; ?></p>
                        <a href="complete_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary" style="width: 100%; padding: 0.5rem;">Release Slot</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Booking Modal -->
    <div id="bookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 100; justify-content: center; align-items: center;">
        <div class="glass-card" style="width: 90%; max-width: 400px; position: relative;">
            <button onclick="closeBookingModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
            <h2 style="margin-bottom: 1.5rem;">Book Slot <span id="modalSlotNumber" style="color: var(--primary);"></span></h2>
            <form action="book_slot.php" method="POST">
                <input type="hidden" name="slot_id" id="modalSlotId">
                <div class="input-group">
                    <label>Vehicle Number</label>
                    <input type="text" name="vehicle_number" placeholder="ABC-1234" required>
                </div>
                <div style="margin-bottom: 1.5rem; color: var(--text-gray);">
                    Rate: <span id="modalSlotRate" style="color: white; font-weight: 600;"></span> / hour
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Confirm Booking</button>
            </form>
        </div>
    </div>

    <script>
        function openBookingModal(id, number, rate) {
            document.getElementById('modalSlotId').value = id;
            document.getElementById('modalSlotNumber').innerText = number;
            document.getElementById('modalSlotRate').innerText = '$' + rate;
            document.getElementById('bookingModal').style.display = 'flex';
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }
    </script>

<?php include 'includes/footer.php'; ?>
