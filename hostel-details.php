<?php
/* ================================================
   hostel-details.php - Single Hostel Detail Page
   ================================================ */
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . APP_URL . '/hostels.php');
    exit;
}

$db    = getDB();
$stmt  = $db->prepare("SELECT * FROM hostels WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$h     = $stmt->get_result()->fetch_assoc();

// Demo hostel if DB empty
if (!$h) {
    $demo_data = [
        1=>['id'=>1,'hostel_name'=>'Pearl Student Hostel','location'=>'Kakoba, 0.3km from MUST Gate','room_type'=>'single','price'=>250000,'image'=>'','description'=>'Pearl Student Hostel offers clean, modern single rooms ideal for focused students. Each room comes with a study desk, wardrobe, and secure lock. 24/7 security guard on site. Located just a 5-minute walk from the main MUST gate.','availability'=>5,'contact'=>'+256 700 111 222'],
        2=>['id'=>2,'hostel_name'=>'Unity Residence','location'=>'Ruharo, 0.5km from MUST','room_type'=>'shared','price'=>180000,'image'=>'','description'=>'Unity Residence offers affordable shared rooms for two students. A great community feel with common sitting area, shared bathrooms, and reliable water supply. Perfect for students on a budget.','availability'=>8,'contact'=>'+256 700 333 444'],
        3=>['id'=>3,'hostel_name'=>'Comfort Suites','location'=>'Kakoba, 0.4km from MUST','room_type'=>'self-contained','price'=>450000,'image'=>'','description'=>'Fully self-contained suites with en-suite bathroom, private kitchen space, and living area. Ideal for students who want independence and privacy. Includes electricity and water.','availability'=>3,'contact'=>'+256 701 555 666'],
    ];
    $h = $demo_data[$id] ?? $demo_data[1];
    $h['contact'] = $h['contact'] ?? '+256 700 000 000';
    $h['created_at'] = date('Y-m-d');
}

// Check if this user already has an active booking for this hostel
$already_booked = false;
if (isLoggedIn()) {
    $bs = $db->prepare("SELECT id FROM bookings WHERE user_id=? AND hostel_id=? AND status='confirmed' LIMIT 1");
    $bs->bind_param('ii', $_SESSION['user_id'], $id);
    $bs->execute();
    $already_booked = $bs->get_result()->num_rows > 0;
}

$page_title = sanitize($h['hostel_name']);
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> <span>›</span>
            <a href="hostels.php">Hostels</a> <span>›</span>
            <?= sanitize($h['hostel_name']) ?>
        </div>
        <h1><?= sanitize($h['hostel_name']) ?></h1>
        <p><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($h['location']) ?></p>
    </div>
</div>

<div class="section" style="padding-top:32px;">
    <div class="container">

        <?php renderFlash(); ?>

        <div class="detail-layout">

            <!-- Main Content -->
            <div>
                <!-- Image -->
                <div class="detail-hero">
                    <div class="detail-img">
                        <?php if (!empty($h['image']) && file_exists('uploads/hostels/' . $h['image'])): ?>
                            <img src="<?= APP_URL ?>/uploads/hostels/<?= sanitize($h['image']) ?>" alt="<?= sanitize($h['hostel_name']) ?>">
                        <?php else: ?>
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel">
                        <?php endif; ?>
                    </div>
                    <div class="detail-body">
                        <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
                            <span class="badge badge-blue"><?= sanitize(ucwords(str_replace('-', ' ', $h['room_type']))) ?></span>
                            <?php if ($h['availability'] > 0): ?>
                                <span class="badge badge-green">
                                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Available"> <?= (int)$h['availability'] ?> rooms available
                                </span>
                            <?php else: ?>
                                <span class="badge badge-red">Currently Full</span>
                            <?php endif; ?>
                        </div>
                        <h2 style="font-family:var(--font-display);font-size:1.4rem;margin-bottom:12px;">
                            <?= sanitize($h['hostel_name']) ?>
                        </h2>
                        <p style="color:var(--text-mid);line-height:1.75;font-size:0.95rem;">
                            <?= sanitize($h['description'] ?? 'No description provided.') ?>
                        </p>
                    </div>
                </div>

                <!-- Details Table -->
                <div class="dash-card">
                    <div class="dash-card-title">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Room Information"> Room Information
                </div>
                    <div class="info-row">
                        <span class="info-label">Hostel Name</span>
                        <span class="info-value"><?= sanitize($h['hostel_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Location</span>
                        <span class="info-value"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($h['location']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Room Type</span>
                        <span class="info-value"><?= sanitize(ucwords(str_replace('-', ' ', $h['room_type']))) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Price</span>
                        <span class="info-value" style="font-weight:700;color:var(--primary);"><?= formatPrice($h['price']) ?> per semester</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Available Rooms</span>
                        <span class="info-value"><?= (int)$h['availability'] ?> rooms</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact</span>
                        <span class="info-value"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Contact"> <?= sanitize($h['contact'] ?? 'Contact admin for details') ?></span>
                    </div>
                </div>

                <!-- What's Included (static) -->
                <div class="dash-card">
                    <div class="dash-card-title">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Amenities"> What's Typically Included
                </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <?php
                        $amenities = match($h['room_type']) {
                            'self-contained' => ['Private Bathroom','Kitchen Space','Bed & Mattress','Private Door Lock','Electricity','Piped Water','Study Desk','Chair & Wardrobe'],
                            'shared'         => ['Shared Bathrooms','Bed & Mattress','Room Lock','Electricity','Piped Water','Study Desk','Chair','Common Room'],
                            default          => ['Single Bed & Mattress','Room Lock','Electricity','Piped Water','Study Desk','Chair & Wardrobe','Shared Bathrooms','24/7 Security'],
                        };
                        foreach ($amenities as $a):
                        ?>
                        <div class="amenity-item">
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Amenity">
                            <span><?= sanitize($a) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <aside class="detail-sidebar">
                <div class="detail-price-display">
                    <?= formatPrice($h['price']) ?>
                    <span>/ semester</span>
                </div>
                <div style="color:var(--text-mid);font-size:0.85rem;margin-bottom:20px;">
                    <?= (int)$h['availability'] ?> rooms left · <?= sanitize(ucwords(str_replace('-', ' ', $h['room_type']))) ?>
                </div>

                <div class="divider"></div>

                <?php if ($h['availability'] <= 0): ?>
                    <div class="alert alert-error">This hostel is currently full.</div>
                    <a href="hostels.php" class="btn btn-outline btn-block">Browse Other Hostels</a>

                <?php elseif (!isLoggedIn()): ?>
                    <div class="alert alert-info">You must be logged in to book a room.</div>
                    <a href="login.php?redirect=<?= urlencode(APP_URL . '/hostel-details.php?id=' . $id) ?>" class="btn btn-primary btn-block btn-lg">Login to Book</a>
                    <div class="divider"></div>
                    <a href="signup.php" class="btn btn-outline btn-block">Create Account</a>

                <?php elseif ($already_booked): ?>
                    <div class="alert alert-warning">You already have an active booking at this hostel.</div>
                    <a href="dashboard.php" class="btn btn-outline btn-block">View My Bookings</a>

                <?php else: ?>
                    <a href="book-room.php?hostel_id=<?= (int)$h['id'] ?>" class="btn btn-primary btn-block btn-lg">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Book"> Book This Room
                    </a>
                    <p style="font-size:0.78rem;color:var(--text-light);text-align:center;margin-top:10px;">
                        No payment required now. Confirm with hostel directly.
                    </p>
                <?php endif; ?>

                <div class="divider"></div>

                <div style="font-size:0.83rem;color:var(--text-mid);">
                    <div style="font-weight:700;color:var(--text-dark);margin-bottom:10px;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Contact"> Contact Hostel
                    </div>
                    <div style="margin-bottom:6px;"><?= sanitize($h['contact'] ?? '+256 700 000 000') ?></div>
                    <div style="font-size:0.78rem;color:var(--text-light);">Call or visit the hostel directly</div>
                </div>
            </aside>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
