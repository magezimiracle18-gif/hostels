<?php
/* ================================================
   dashboard.php - Student Dashboard
   ================================================ */
require_once 'config.php';
requireLogin();

$db      = getDB();
$user_id = (int)$_SESSION['user_id'];

// Fetch user profile
$stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    // Build user from session
    $user = ['id'=>$user_id,'fullname'=>$_SESSION['fullname'],'email'=>$_SESSION['email'],'phone'=>'','role'=>$_SESSION['role'],'created_at'=>date('Y-m-d')];
}

// Fetch bookings
$bk = $db->prepare("
    SELECT b.*, h.hostel_name, h.location, h.room_type, h.price
    FROM bookings b
    JOIN hostels h ON b.hostel_id = h.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");
$bk->bind_param('i', $user_id);
$bk->execute();
$bookings = $bk->get_result()->fetch_all(MYSQLI_ASSOC);

$active_count    = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
$cancelled_count = count(array_filter($bookings, fn($b) => $b['status'] === 'cancelled'));

// Active tab
$tab = $_GET['tab'] ?? 'overview';

// Handle profile update
$profile_errors = [];
$profile_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone']    ?? '');

    if (empty($fullname))                          $profile_errors[] = 'Full name is required.';
    if (!preg_match('/^[0-9+\s\-]{10,15}$/', $phone)) $profile_errors[] = 'Enter a valid phone number.';

    if (empty($profile_errors)) {
        $upd = $db->prepare("UPDATE users SET fullname=?, phone=? WHERE id=?");
        $upd->bind_param('ssi', $fullname, $phone, $user_id);
        $upd->execute();
        $_SESSION['fullname'] = $fullname;
        $user['fullname']     = $fullname;
        $user['phone']        = $phone;
        $profile_success      = true;
    }
}

$page_title = 'My Dashboard';
$initials   = implode('', array_map(fn($w) => strtoupper($w[0]), explode(' ', $user['fullname'])));
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <div class="dashboard-layout">

        <!-- =====================
             SIDEBAR NAV
             ===================== -->
        <aside class="dashboard-nav">
            <div class="dashboard-nav-user">
                <div class="dashboard-avatar"><?= htmlspecialchars(substr($initials, 0, 2)) ?></div>
                <div style="font-weight:700;font-size:0.92rem;"><?= sanitize($user['fullname']) ?></div>
                <div style="font-size:0.78rem;color:var(--text-light);"><?= sanitize($user['email']) ?></div>
                <div class="tag" style="margin-top:6px;"><?= sanitize(ucfirst($user['role'])) ?></div>
            </div>
            <div class="dashboard-nav-links">
                <a href="dashboard.php?tab=overview" class="<?= $tab==='overview' ? 'active' : '' ?>">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Overview"> Overview
                </a>
                <a href="dashboard.php?tab=bookings" class="<?= $tab==='bookings' ? 'active' : '' ?>">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Bookings"> My Bookings
                </a>
                <a href="dashboard.php?tab=profile"  class="<?= $tab==='profile'  ? 'active' : '' ?>">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Profile"> Profile
                </a>
                <a href="hostels.php">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="Browse Hostels"> Browse Hostels
                </a>
                <div style="height:1px;background:var(--border);margin:8px 0;"></div>
                <a href="logout.php" style="color:#EF4444 !important;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/logout.svg" alt="Logout"> Logout
                </a>
            </div>
        </aside>

        <!-- =====================
             MAIN CONTENT
             ===================== -->
        <main class="dashboard-content">
            <?php renderFlash(); ?>

            <?php if ($tab === 'overview'): ?>
            <!-- OVERVIEW TAB -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Active"></div>
                    <div class="stat-value"><?= $active_count ?></div>
                    <div class="stat-label">Active Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/clipboard.svg" alt="Total"></div>
                    <div class="stat-value"><?= count($bookings) ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/lock.svg" alt="Cancelled"></div>
                    <div class="stat-value"><?= $cancelled_count ?></div>
                    <div class="stat-label">Cancelled</div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="dash-card">
                <div class="dash-card-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Recent"> Recent Bookings</div>
                <?php if (empty($bookings)): ?>
                    <div class="empty-state" style="padding:30px;">
                        <div class="empty-state-icon"><img src="<?= APP_URL ?>/assets/svg/building.svg" alt="No bookings"></div>
                        <h3>No bookings yet</h3>
                        <p>You haven't booked any hostels. Start exploring available options!</p>
                        <a href="hostels.php" class="btn btn-primary">Browse Hostels</a>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($bookings, 0, 3) as $b): ?>
                    <div class="booking-card">
                        <div class="booking-card-icon"><img src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel"></div>
                        <div class="booking-card-info">
                            <div class="booking-card-title"><?= sanitize($b['hostel_name']) ?></div>
                            <div class="booking-card-meta">
                                <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($b['location']) ?> &nbsp;·&nbsp;
                                <?= sanitize(ucwords(str_replace('-', ' ', $b['room_type']))) ?> &nbsp;·&nbsp;
                                <?= date('M j, Y', strtotime($b['booking_date'])) ?>
                            </div>
                        </div>
                        <div class="booking-card-actions">
                            <span class="badge <?= 'status-' . $b['status'] ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                            <?php if ($b['status'] === 'confirmed'): ?>
                                <a href="cancel-booking.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-danger">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (count($bookings) > 3): ?>
                        <div class="text-center mt-16">
                            <a href="dashboard.php?tab=bookings" class="btn btn-outline btn-sm">View All Bookings →</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="dash-card">
                <div class="dash-card-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Quick Actions"> Quick Actions</div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="hostels.php" class="btn btn-primary"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="Find a Hostel"> Find a Hostel</a>
                    <a href="hostels.php?available=1" class="btn btn-outline"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Available"> Available Now</a>
                    <a href="dashboard.php?tab=profile" class="btn btn-ghost"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Profile"> Update Profile</a>
                </div>
            </div>

            <?php elseif ($tab === 'bookings'): ?>
            <!-- ALL BOOKINGS TAB -->
            <div class="dash-card">
                <div class="dash-card-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="All Bookings"> All Bookings</div>
                <?php if (empty($bookings)): ?>
                    <div class="empty-state" style="padding:30px;">
                        <div class="empty-state-icon"><img src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="No bookings"></div>
                        <h3>No bookings yet</h3>
                        <p>Start by browsing available hostels near campus.</p>
                        <a href="hostels.php" class="btn btn-primary">Browse Hostels</a>
                    </div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hostel</th>
                                    <th>Room Type</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td style="color:var(--text-light);">#<?= (int)$b['id'] ?></td>
                                    <td>
                                        <div style="font-weight:600;"><?= sanitize($b['hostel_name']) ?></div>
                                        <div style="font-size:0.78rem;color:var(--text-light);"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($b['location']) ?></div>
                                    </td>
                                    <td><?= sanitize(ucwords(str_replace('-',' ',$b['room_type']))) ?></td>
                                    <td><?= formatPrice($b['price']) ?></td>
                                    <td><?= date('M j, Y', strtotime($b['booking_date'])) ?></td>
                                    <td><span class="badge <?= 'status-' . $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                                    <td>
                                        <?php if ($b['status'] === 'confirmed'): ?>
                                            <a href="cancel-booking.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-danger">Cancel</a>
                                        <?php else: ?>
                                            <span style="color:var(--text-light);font-size:0.8rem;">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <?php elseif ($tab === 'profile'): ?>
            <!-- PROFILE TAB -->
            <div class="dash-card">
                <div class="dash-card-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Profile"> Update Profile</div>

                <?php if ($profile_success): ?>
                    <div class="alert alert-success"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Success"> Profile updated successfully!</div>
                <?php endif; ?>

                <?php if (!empty($profile_errors)): ?>
                    <div class="alert alert-error"><?= implode('<br>', array_map('sanitize', $profile_errors)) ?></div>
                <?php endif; ?>

                <form method="POST" action="dashboard.php?tab=profile">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control"
                                value="<?= sanitize($user['fullname']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" class="form-control"
                                value="<?= sanitize($user['phone']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" class="form-control"
                            value="<?= sanitize($user['email']) ?>" readonly
                            style="background:var(--cream);">
                        <div class="form-hint">Email cannot be changed. Contact admin if needed.</div>
                    </div>
                    <div class="form-group">
                        <label>Member Since</label>
                        <input type="text" class="form-control"
                            value="<?= date('F j, Y', strtotime($user['created_at'] ?? 'now')) ?>"
                            readonly style="background:var(--cream);">
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Save"> Save Changes
                    </button>
                </form>
            </div>

            <?php endif; ?>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
