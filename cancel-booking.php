<?php
/* ================================================
   cancel-booking.php - Cancel a Booking
   ================================================ */
require_once 'config.php';
requireLogin();

$booking_id = (int)($_GET['id'] ?? 0);
if ($booking_id <= 0) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

$db   = getDB();
$user_id = (int)$_SESSION['user_id'];

// Fetch the booking (ensure it belongs to logged-in user)
$stmt = $db->prepare("
    SELECT b.*, h.hostel_name, h.location, h.room_type, h.price
    FROM bookings b
    JOIN hostels h ON b.hostel_id = h.id
    WHERE b.id = ? AND b.user_id = ? LIMIT 1
");
$stmt->bind_param('ii', $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    setFlash('error', 'Booking not found or you do not have permission to cancel it.');
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

if ($booking['status'] !== 'confirmed') {
    setFlash('info', 'This booking has already been cancelled.');
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

// --- Process Cancellation ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_cancel'])) {
    $db->begin_transaction();
    try {
        // Update booking status
        $upd = $db->prepare("UPDATE bookings SET status='cancelled' WHERE id=? AND user_id=?");
        $upd->bind_param('ii', $booking_id, $user_id);
        $upd->execute();

        // Restore availability
        $restore = $db->prepare("UPDATE hostels SET availability = availability + 1 WHERE id = ?");
        $restore->bind_param('i', $booking['hostel_id']);
        $restore->execute();

        $db->commit();
        setFlash('success', 'Your booking for ' . $booking['hostel_name'] . ' has been cancelled successfully.');
        header('Location: ' . APP_URL . '/dashboard.php');
        exit;
    } catch (Exception $e) {
        $db->rollback();
        $error = 'Cancellation failed. Please try again.';
    }
}

$page_title = 'Cancel Booking';
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> <span>›</span>
            <a href="dashboard.php">Dashboard</a> <span>›</span>
            Cancel Booking
        </div>
        <h1>Cancel Booking</h1>
        <p>Review your booking details before cancelling</p>
    </div>
</div>

<div class="section" style="padding-top:32px;">
    <div class="container" style="max-width:600px;">

        <?php if (isset($error)): ?>
            <div class="alert alert-error">❌ <?= sanitize($error) ?></div>
        <?php endif; ?>

        <div class="dash-card">
            <div class="dash-card-title">⚠️ Confirm Cancellation</div>

            <div class="alert alert-warning">
                Are you sure you want to cancel this booking? This action cannot be undone.
            </div>

            <!-- Booking Details -->
            <div style="background:var(--cream);border-radius:var(--radius-md);padding:20px;margin-bottom:24px;">
                <div class="info-row" style="padding:8px 0;">
                    <span class="info-label">Booking ID</span>
                    <span class="info-value" style="font-weight:700;">#<?= (int)$booking['id'] ?></span>
                </div>
                <div class="info-row" style="padding:8px 0;">
                    <span class="info-label">Hostel</span>
                    <span class="info-value"><?= sanitize($booking['hostel_name']) ?></span>
                </div>
                <div class="info-row" style="padding:8px 0;">
                    <span class="info-label">Location</span>
                    <span class="info-value"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($booking['location']) ?></span>
                </div>
                <div class="info-row" style="padding:8px 0;">
                    <span class="info-label">Room Type</span>
                    <span class="info-value"><?= sanitize(ucwords(str_replace('-',' ',$booking['room_type']))) ?></span>
                </div>
                <div class="info-row" style="padding:8px 0;">
                    <span class="info-label">Price</span>
                    <span class="info-value"><?= formatPrice($booking['price']) ?>/semester</span>
                </div>
                <div class="info-row" style="padding:8px 0;border:none;">
                    <span class="info-label">Booked On</span>
                    <span class="info-value"><?= date('F j, Y', strtotime($booking['booking_date'])) ?></span>
                </div>
            </div>

            <form method="POST" action="cancel-booking.php?id=<?= $booking_id ?>">
                <div style="display:flex;gap:12px;">
                    <button type="submit" name="confirm_cancel" value="1" class="btn btn-danger btn-lg" style="flex:1;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/trash.svg" alt="Cancel"> Yes, Cancel Booking
                    </button>
                    <a href="dashboard.php" class="btn btn-ghost btn-lg" style="flex:1;text-align:center;">
                        ← Keep Booking
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
