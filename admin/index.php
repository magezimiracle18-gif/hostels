<?php
/* ================================================
   admin/index.php - Admin Dashboard
   ================================================ */
require_once '../config.php';
requireAdmin();

$db = getDB();

// Stats
$total_users    = $db->query("SELECT COUNT(*) as c FROM users WHERE role='student'")->fetch_assoc()['c'] ?? 0;
$total_hostels  = $db->query("SELECT COUNT(*) as c FROM hostels")->fetch_assoc()['c'] ?? 0;
$total_bookings = $db->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'] ?? 0;
$active_bookings= $db->query("SELECT COUNT(*) as c FROM bookings WHERE status='confirmed'")->fetch_assoc()['c'] ?? 0;
$messages       = $db->query("SELECT COUNT(*) as c FROM messages WHERE is_read=0")->fetch_assoc()['c'] ?? 0;

// Recent bookings
$recent_res  = $db->query("SELECT b.*,u.fullname,u.email,h.hostel_name FROM bookings b JOIN users u ON b.user_id=u.id JOIN hostels h ON b.hostel_id=h.id ORDER BY b.booking_date DESC LIMIT 8");
$recent_bk   = $recent_res ? $recent_res->fetch_all(MYSQLI_ASSOC) : [];

$page_title = 'Admin Dashboard';
include '../includes/head.php';
?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Admin"> Admin Panel</div>
        <nav class="admin-nav">
            <a href="index.php" class="active"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Dashboard"> Dashboard</a>
            <a href="hostels.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Manage Hostels"> Manage Hostels</a>
            <a href="bookings.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Bookings"> Bookings</a>
            <a href="users.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Users"> Users</a>
            <a href="messages.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Messages"> Messages <?= $messages > 0 ? "($messages)" : '' ?></a>
            <div style="height:1px;background:rgba(255,255,255,0.1);margin:10px 0;"></div>
            <a href="../index.php">← View Site</a>
            <a href="../logout.php" style="color:#FCA5A5;"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/logout.svg" alt="Logout"> Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-topbar">
            <div class="admin-page-title">Dashboard Overview</div>
            <a href="hostels.php?action=add" class="btn btn-primary btn-sm">+ Add Hostel</a>
        </div>

        <?php renderFlash(); ?>

        <!-- Stats Cards -->
        <div class="admin-stats">
            <div class="admin-stat-card">
                <div class="admin-stat-icon blue"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Students"></div>
                <div>
                    <div class="admin-stat-value"><?= (int)$total_users ?></div>
                    <div class="admin-stat-label">Registered Students</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon green"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostels"></div>
                <div>
                    <div class="admin-stat-value"><?= (int)$total_hostels ?></div>
                    <div class="admin-stat-label">Listed Hostels</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon yellow"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Bookings"></div>
                <div>
                    <div class="admin-stat-value"><?= (int)$active_bookings ?></div>
                    <div class="admin-stat-label">Active Bookings</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon red"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Messages"></div>
                <div>
                    <div class="admin-stat-value"><?= (int)$messages ?></div>
                    <div class="admin-stat-label">Unread Messages</div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="dash-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <div class="dash-card-title" style="margin-bottom:0;border:none;"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Recent Bookings"> Recent Bookings</div>
                <a href="bookings.php" class="btn btn-ghost btn-sm">View All →</a>
            </div>
            <?php if (empty($recent_bk)): ?>
                <div class="empty-state" style="padding:24px;">
                    <div class="empty-state-icon"><img src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="No bookings"></div>
                    <h3>No bookings yet</h3>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Hostel</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_bk as $b): ?>
                            <tr>
                                <td>#<?= (int)$b['id'] ?></td>
                                <td>
                                    <div style="font-weight:600;"><?= sanitize($b['fullname']) ?></div>
                                    <div style="font-size:0.78rem;color:var(--text-light);"><?= sanitize($b['email']) ?></div>
                                </td>
                                <td><?= sanitize($b['hostel_name']) ?></td>
                                <td><?= date('M j, Y', strtotime($b['booking_date'])) ?></td>
                                <td><span class="badge <?= 'status-' . $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>
