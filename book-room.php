<?php
/* ================================================
   book-room.php - Room Booking Page
   ================================================ */
require_once 'config.php';
requireLogin();

$hostel_id = (int)($_GET['hostel_id'] ?? 0);
if ($hostel_id <= 0) {
    header('Location: ' . APP_URL . '/hostels.php');
    exit;
}

$db    = getDB();
$stmt  = $db->prepare("SELECT * FROM hostels WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $hostel_id);
$stmt->execute();
$hostel = $stmt->get_result()->fetch_assoc();

// Demo hostel fallback
if (!$hostel) {
    $hostel = ['id'=>$hostel_id,'hostel_name'=>'Pearl Student Hostel','location'=>'Kakoba, 0.3km from MUST','room_type'=>'single','price'=>250000,'availability'=>5];
}

// Check already booked
$bs = $db->prepare("SELECT id FROM bookings WHERE user_id=? AND hostel_id=? AND status='confirmed'");
$bs->bind_param('ii', $_SESSION['user_id'], $hostel_id);
$bs->execute();
if ($bs->get_result()->num_rows > 0) {
    setFlash('warning', 'You already have an active booking at this hostel.');
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

if ($hostel['availability'] <= 0) {
    setFlash('error', 'Sorry, this hostel is currently full.');
    header('Location: ' . APP_URL . '/hostel-details.php?id=' . $hostel_id);
    exit;
}

// --- Process Booking ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes        = trim($_POST['notes'] ?? '');
    $booking_date = date('Y-m-d');

    // Double-check availability
    $check = $db->prepare("SELECT availability FROM hostels WHERE id=? FOR UPDATE");
    $check->bind_param('i', $hostel_id);
    $check->execute();
    $avail = $check->get_result()->fetch_assoc()['availability'] ?? 0;

    // Re-check not already booked
    $recheck = $db->prepare("SELECT id FROM bookings WHERE user_id=? AND hostel_id=? AND status='confirmed'");
    $recheck->bind_param('ii', $_SESSION['user_id'], $hostel_id);
    $recheck->execute();
    $exists = $recheck->get_result()->num_rows > 0;

    if ($exists) {
        setFlash('warning', 'You already have an active booking at this hostel.');
        header('Location: ' . APP_URL . '/dashboard.php');
        exit;
    }

    if ($avail <= 0) {
        setFlash('error', 'Sorry, no rooms are currently available at this hostel.');
        header('Location: ' . APP_URL . '/hostel-details.php?id=' . $hostel_id);
        exit;
    }

    // Begin transaction
    $db->begin_transaction();
    try {
        // Insert booking
        $ins = $db->prepare("INSERT INTO bookings (user_id, hostel_id, booking_date, notes, status) VALUES (?, ?, ?, ?, 'confirmed')");
        $ins->bind_param('iiss', $_SESSION['user_id'], $hostel_id, $booking_date, $notes);
        $ins->execute();
        $booking_id = $db->insert_id;

        // Reduce availability
        $upd = $db->prepare("UPDATE hostels SET availability = availability - 1 WHERE id = ? AND availability > 0");
        $upd->bind_param('i', $hostel_id);
        $upd->execute();

        $db->commit();
        setFlash('success', 'Booking confirmed! Your booking ID is #' . $booking_id . '. Please contact the hostel directly to finalize payment.');
        header('Location: ' . APP_URL . '/dashboard.php');
        exit;
    } catch (Exception $e) {
        $db->rollback();
        $error = 'Booking failed. Please try again.';
    }
}

$page_title = 'Book a Room';
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> <span>›</span>
            <a href="hostels.php">Hostels</a> <span>›</span>
            <a href="hostel-details.php?id=<?= $hostel_id ?>"><?= sanitize($hostel['hostel_name']) ?></a> <span>›</span>
            Book Room
        </div>
        <h1>Book a Room</h1>
        <p>Complete your booking request for <?= sanitize($hostel['hostel_name']) ?></p>
    </div>
</div>

<div class="section" style="padding-top:32px;">
    <div class="container" style="max-width:800px;">

        <?php renderFlash(); ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= sanitize($error) ?></div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

            <!-- Booking Form -->
            <div class="dash-card">
                <div class="dash-card-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Confirm Booking"> Confirm Booking</div>

                <div class="alert alert-info">
                    This is a booking request system. After booking, contact the hostel directly to arrange payment and move-in.
                </div>

                <form method="POST" action="book-room.php?hostel_id=<?= $hostel_id ?>">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" class="form-control" value="<?= sanitize($_SESSION['fullname']) ?>" readonly style="background:var(--cream);">
                    </div>
                    <div class="form-group">
                        <label>Hostel</label>
                        <input type="text" class="form-control" value="<?= sanitize($hostel['hostel_name']) ?>" readonly style="background:var(--cream);">
                    </div>
                    <div class="form-group">
                        <label>Room Type</label>
                        <input type="text" class="form-control" value="<?= sanitize(ucwords(str_replace('-', ' ', $hostel['room_type']))) ?>" readonly style="background:var(--cream);">
                    </div>
                    <div class="form-group">
                        <label>Booking Date</label>
                        <input type="text" class="form-control" value="<?= date('F j, Y') ?>" readonly style="background:var(--cream);">
                    </div>
                    <div class="form-group">
                        <label for="notes">Additional Notes <span style="color:var(--text-light);font-weight:400;">(optional)</span></label>
                        <textarea name="notes" id="notes" class="form-control" placeholder="Any special requirements or questions for the hostel owner..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Confirm"> Confirm Booking
                    </button>
                    <a href="hostel-details.php?id=<?= $hostel_id ?>" class="btn btn-ghost btn-block mt-8">Cancel</a>
                </form>
            </div>

            <!-- Booking Summary -->
            <div class="detail-sidebar" style="position:static;">
                <div style="font-weight:700;font-size:1rem;margin-bottom:14px;font-family:var(--font-display);">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/clipboard.svg" alt="Booking Summary"> Booking Summary
                </div>
                <div class="info-row" style="border:none;padding:8px 0;">
                    <span class="info-label" style="min-width:100px;font-size:0.82rem;">Hostel</span>
                    <span class="info-value" style="font-size:0.82rem;"><?= sanitize($hostel['hostel_name']) ?></span>
                </div>
                <div class="info-row" style="border:none;padding:8px 0;">
                    <span class="info-label" style="min-width:100px;font-size:0.82rem;">Type</span>
                    <span class="info-value" style="font-size:0.82rem;"><?= sanitize(ucwords(str_replace('-',' ',$hostel['room_type']))) ?></span>
                </div>
                <div class="info-row" style="border:none;padding:8px 0;">
                    <span class="info-label" style="min-width:100px;font-size:0.82rem;">Location</span>
                    <span class="info-value" style="font-size:0.82rem;"><?= sanitize($hostel['location']) ?></span>
                </div>
                <div class="divider"></div>
                <div style="font-family:var(--font-display);font-size:1.5rem;color:var(--primary);margin-bottom:4px;">
                    <?= formatPrice($hostel['price']) ?>
                </div>
                <div style="font-size:0.78rem;color:var(--text-light);">Per semester (payable to hostel)</div>
                <div class="divider"></div>
                <div style="font-size:0.8rem;color:var(--text-mid);line-height:1.6;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/lock.svg" alt="Secure"> Your booking is secured. Contact the hostel to arrange payment and check-in date.
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
