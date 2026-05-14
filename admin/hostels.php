<?php
/* ================================================
   admin/hostels.php - Manage Hostels
   ================================================ */
require_once '../config.php';
requireAdmin();

$db     = getDB();
$action = $_GET['action'] ?? 'list';
$edit_id= (int)($_GET['id'] ?? 0);
$errors = [];

// --- DELETE ---
if ($action === 'delete' && $edit_id > 0) {
    // Restore availability doesn't matter since deleting hostel
    $del = $db->prepare("DELETE FROM hostels WHERE id=?");
    $del->bind_param('i', $edit_id);
    $del->execute();
    setFlash('success', 'Hostel deleted successfully.');
    header('Location: ' . APP_URL . '/admin/hostels.php');
    exit;
}

// --- LOAD FOR EDIT ---
$hostel = null;
if ($action === 'edit' && $edit_id > 0) {
    $s = $db->prepare("SELECT * FROM hostels WHERE id=? LIMIT 1");
    $s->bind_param('i', $edit_id);
    $s->execute();
    $hostel = $s->get_result()->fetch_assoc();
}

// --- SAVE (Add / Edit) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    $name         = trim($_POST['hostel_name']  ?? '');
    $location     = trim($_POST['location']     ?? '');
    $room_type    = trim($_POST['room_type']     ?? '');
    $price        = (int)($_POST['price']        ?? 0);
    $availability = (int)($_POST['availability'] ?? 0);
    $description  = trim($_POST['description']  ?? '');
    $contact      = trim($_POST['contact']       ?? '');

    if (empty($name))        $errors[] = 'Hostel name is required.';
    if (empty($location))    $errors[] = 'Location is required.';
    if (empty($room_type))   $errors[] = 'Room type is required.';
    if ($price <= 0)         $errors[] = 'Price must be greater than 0.';

    // --- Handle image upload ---
    $image_name = $hostel['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $ext       = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Image must be JPG, PNG or WebP.';
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Image must be under 2MB.';
        } else {
            $image_name = uniqid('hostel_') . '.' . $ext;
            $upload_dir = __DIR__ . '/../uploads/hostels/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                $errors[] = 'Image upload failed.';
                $image_name = $hostel['image'] ?? '';
            }
        }
    }

    if (empty($errors)) {
        if ($action === 'edit' && $edit_id > 0) {
            $upd = $db->prepare("UPDATE hostels SET hostel_name=?,location=?,room_type=?,price=?,availability=?,description=?,contact=?,image=? WHERE id=?");
            $upd->bind_param('sssiiissi', $name, $location, $room_type, $price, $availability, $description, $contact, $image_name, $edit_id);
            $upd->execute();
            setFlash('success', 'Hostel updated successfully.');
        } else {
            $ins = $db->prepare("INSERT INTO hostels (hostel_name,location,room_type,price,availability,description,contact,image,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
            $ins->bind_param('sssiiiss', $name, $location, $room_type, $price, $availability, $description, $contact, $image_name);
            $ins->execute();
            setFlash('success', 'Hostel added successfully!');
        }
        header('Location: ' . APP_URL . '/admin/hostels.php');
        exit;
    }
    // Re-populate hostel for form
    $hostel = compact('name','location','room_type','price','availability','description','contact') + ['hostel_name'=>$name,'image'=>$image_name];
}

// --- LIST ---
$hostels_res = $db->query("SELECT * FROM hostels ORDER BY created_at DESC");
$hostels     = $hostels_res ? $hostels_res->fetch_all(MYSQLI_ASSOC) : [];

$page_title  = 'Manage Hostels';
include '../includes/head.php';
?>
<?php include '../includes/navbar.php'; ?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Admin"> Admin Panel</div>
        <nav class="admin-nav">
            <a href="index.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Dashboard"> Dashboard</a>
            <a href="hostels.php" class="active"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Manage Hostels"> Manage Hostels</a>
            <a href="bookings.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Bookings"> Bookings</a>
            <a href="users.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Users"> Users</a>
            <a href="messages.php"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Messages"> Messages</a>
            <div style="height:1px;background:rgba(255,255,255,0.1);margin:10px 0;"></div>
            <a href="../index.php">← View Site</a>
            <a href="../logout.php" style="color:#FCA5A5;"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/logout.svg" alt="Logout"> Logout</a>
        </nav>
    </aside>

    <main class="admin-main">
        <?php if ($action === 'list'): ?>
        <!-- LIST VIEW -->
        <div class="admin-topbar">
            <div class="admin-page-title"><img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Manage Hostels"> Manage Hostels</div>
            <a href="hostels.php?action=add" class="btn btn-primary btn-sm">+ Add New Hostel</a>
        </div>

        <?php renderFlash(); ?>

        <div class="dash-card">
            <?php if (empty($hostels)): ?>
                <div class="empty-state" style="padding:40px;">
                    <div class="empty-state-icon"><img src="<?= APP_URL ?>/assets/svg/building.svg" alt="No hostels"></div>
                    <h3>No hostels yet</h3>
                    <p>Add your first hostel to get started.</p>
                    <a href="hostels.php?action=add" class="btn btn-primary">Add Hostel</a>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hostel</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hostels as $h): ?>
                            <tr>
                                <td><?= (int)$h['id'] ?></td>
                                <td style="font-weight:600;"><?= sanitize($h['hostel_name']) ?></td>
                                <td style="font-size:0.83rem;"><?= sanitize($h['location']) ?></td>
                                <td><span class="badge badge-blue"><?= sanitize(ucwords(str_replace('-',' ',$h['room_type']))) ?></span></td>
                                <td><?= formatPrice($h['price']) ?></td>
                                <td>
                                    <span class="badge <?= $h['availability'] > 0 ? 'badge-green' : 'badge-red' ?>">
                                        <?= (int)$h['availability'] ?> rooms
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="hostels.php?action=edit&id=<?= (int)$h['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                                        <a href="hostels.php?action=delete&id=<?= (int)$h['id'] ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this hostel permanently?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <?php else: ?>
        <!-- ADD / EDIT FORM -->
        <div class="admin-topbar">
            <div class="admin-page-title"><?= $action === 'edit' ? '<img class="icon-svg" src="'.APP_URL.'/assets/svg/settings.svg" alt="Edit"> Edit Hostel' : '+ Add New Hostel' ?></div>
            <a href="hostels.php" class="btn btn-ghost btn-sm">← Back to List</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?= implode('<br>', array_map('sanitize', $errors)) ?></div>
        <?php endif; ?>

        <div class="dash-card">
            <form method="POST" action="hostels.php?action=<?= $action ?><?= $edit_id ? '&id='.$edit_id : '' ?>" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Hostel Name *</label>
                        <input type="text" name="hostel_name" class="form-control"
                            value="<?= sanitize($hostel['hostel_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" class="form-control"
                            value="<?= sanitize($hostel['location'] ?? '') ?>"
                            placeholder="e.g. Kakoba, 0.3km from MUST" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Room Type *</label>
                        <select name="room_type" class="form-control" required>
                            <option value="">Select type...</option>
                            <?php foreach (['single'=>'Single Room','shared'=>'Shared Room','self-contained'=>'Self-Contained'] as $val=>$lbl): ?>
                            <option value="<?= $val ?>" <?= ($hostel['room_type'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" class="form-control"
                            value="<?= sanitize($hostel['contact'] ?? '') ?>"
                            placeholder="+256 700 000 000">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Price per Semester (UGX) *</label>
                        <input type="number" name="price" class="form-control"
                            value="<?= (int)($hostel['price'] ?? 0) ?>"
                            min="1" placeholder="e.g. 250000" required>
                    </div>
                    <div class="form-group">
                        <label>Available Rooms *</label>
                        <input type="number" name="availability" class="form-control"
                            value="<?= (int)($hostel['availability'] ?? 0) ?>"
                            min="0" placeholder="e.g. 5" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe the hostel, amenities, rules..."><?= sanitize($hostel['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Hostel Image <span style="color:var(--text-light);font-weight:400;">(JPG/PNG, max 2MB)</span></label>
                    <?php if (!empty($hostel['image'])): ?>
                        <div style="margin-bottom:8px;font-size:0.82rem;color:var(--text-mid);">Current: <?= sanitize($hostel['image']) ?></div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>
                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?= $action === 'edit' ? '<img class="icon-svg" src="'.APP_URL.'/assets/svg/check.svg" alt="Update"> Update Hostel' : '+ Add Hostel' ?>
                    </button>
                    <a href="hostels.php" class="btn btn-ghost btn-lg">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
