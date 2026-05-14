<?php
/* ================================================
   hostels.php - Browse & Filter Hostels
   ================================================ */
require_once 'config.php';
$page_title = 'Browse Hostels';

$db = getDB();

// --- Filter parameters (all from GET form) ---
$search    = trim($_GET['search']    ?? '');
$type      = trim($_GET['type']      ?? '');
$min_price = (int)($_GET['min_price'] ?? 0);
$max_price = (int)($_GET['max_price'] ?? 0);
$available = isset($_GET['available']) && $_GET['available'] === '1';

// --- Build WHERE clause with prepared statement ---
$where      = ['1=1'];
$bind_types = '';
$bind_vals  = [];

if ($search !== '') {
    $where[]     = '(hostel_name LIKE ? OR location LIKE ?)';
    $bind_types .= 'ss';
    $like         = "%$search%";
    $bind_vals[]  = $like;
    $bind_vals[]  = $like;
}

if ($type !== '') {
    $where[]     = 'room_type = ?';
    $bind_types .= 's';
    $bind_vals[]  = $type;
}

if ($min_price > 0) {
    $where[]     = 'price >= ?';
    $bind_types .= 'i';
    $bind_vals[]  = $min_price;
}

if ($max_price > 0) {
    $where[]     = 'price <= ?';
    $bind_types .= 'i';
    $bind_vals[]  = $max_price;
}

if ($available) {
    $where[]     = 'availability > 0';
}

$where_sql = implode(' AND ', $where);
$sql       = "SELECT * FROM hostels WHERE $where_sql ORDER BY availability DESC, created_at DESC";

$stmt = $db->prepare($sql);
if (!empty($bind_vals)) {
    $stmt->bind_param($bind_types, ...$bind_vals);
}
$stmt->execute();
$result  = $stmt->get_result();
$hostels = $result->fetch_all(MYSQLI_ASSOC);

// Demo data when DB is empty
$use_demo = count($hostels) === 0 && empty($search) && empty($type) && !$available;
$demo_hostels = [
    ['id'=>1,'hostel_name'=>'Pearl Student Hostel','location'=>'Kakoba, 0.3km from MUST','room_type'=>'single','price'=>850000,'image'=>'image1.jpg','description'=>'Modern single rooms with study area, 24/7 security.','availability'=>5],
    ['id'=>2,'hostel_name'=>'Unity Residence','location'=>'Ruharo, 0.5km from MUST','room_type'=>'shared','price'=>900000,'image'=>'image2.jpg.png','description'=>'Affordable shared rooms in a friendly student community.','availability'=>8],
    ['id'=>3,'hostel_name'=>'Comfort Suites','location'=>'Kakoba, 0.4km from MUST','room_type'=>'self-contained','price'=>1200000,'image'=>'image3.jpg.png','description'=>'Fully self-contained suites with private bathroom and kitchen.','availability'=>3],
    ['id'=>4,'hostel_name'=>'Nile View Hostel','location'=>'Kamukuzi, 0.6km from MUST','room_type'=>'single','price'=>950000,'image'=>'image4.jpeg','description'=>'Clean single rooms with Wi-Fi and study lounge.','availability'=>0],
    ['id'=>5,'hostel_name'=>'Campus Gate Residence','location'=>'Kakoba, 0.2km from MUST','room_type'=>'shared','price'=>880000,'image'=>'image5.jpg','description'=>'Very close to campus with affordable shared accommodation.','availability'=>12],
    ['id'=>6,'hostel_name'=>'Green Valley Hostel','location'=>'Biharwe, 0.8km from MUST','room_type'=>'self-contained','price'=>1150000,'image'=>'image6.jpg','description'=>'Spacious self-contained rooms in a quiet neighbourhood.','availability'=>6],
    ['id'=>7,'hostel_name'=>'Sunrise Quarters','location'=>'Ruharo, 0.7km from MUST','room_type'=>'single','price'=>920000,'image'=>'image7.jpg','description'=>'Bright, airy single rooms with reliable electricity.','availability'=>4],
    ['id'=>8,'hostel_name'=>'Lake View Hostel','location'=>'Kakoba, 1.0km from MUST','room_type'=>'shared','price'=>860000,'image'=>'image8.jpg','description'=>'Comfortable shared rooms with great community atmosphere.','availability'=>10],
];
if ($use_demo) $hostels = $demo_hostels;

include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> <span>›</span> Hostels
        </div>
        <h1>Browse Hostels</h1>
        <p>
            <?= count($hostels) ?> hostel<?= count($hostels) !== 1 ? 's' : '' ?> found
            <?php if ($search) echo ' for "<strong>' . sanitize($search) . '</strong>"'; ?>
        </p>
    </div>
</div>

<div class="section" style="padding-top:32px;">
    <div class="container">
        <div class="page-layout">

            <!-- =====================
                 FILTER SIDEBAR
                 ===================== -->
            <aside class="filter-sidebar">
                <div class="filter-title">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Filter"> Filter Hostels
                </div>
                <form method="GET" action="hostels.php">

                    <!-- Search -->
                    <div class="filter-group">
                        <div class="filter-group-label">Search</div>
                        <input type="text" name="search" class="form-control"
                            placeholder="Name or location..."
                            value="<?= sanitize($search) ?>">
                    </div>

                    <!-- Room Type -->
                    <div class="filter-group">
                        <div class="filter-group-label">Room Type</div>
                        <label class="filter-radio">
                            <input type="radio" name="type" value="" <?= $type==='' ? 'checked' : '' ?>> All Types
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="type" value="single" <?= $type==='single' ? 'checked' : '' ?>> Single Room
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="type" value="shared" <?= $type==='shared' ? 'checked' : '' ?>> Shared Room
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="type" value="self-contained" <?= $type==='self-contained' ? 'checked' : '' ?>> Self-Contained
                        </label>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <div class="filter-group-label">Price Range (UGX)</div>
                        <div class="price-range-inputs">
                            <input type="number" name="min_price" placeholder="Min"
                                value="<?= $min_price > 0 ? $min_price : '' ?>" min="0">
                            <input type="number" name="max_price" placeholder="Max"
                                value="<?= $max_price > 0 ? $max_price : '' ?>" min="0">
                        </div>
                    </div>

                    <!-- Quick price buttons -->
                    <div class="filter-group">
                        <div class="filter-group-label">Quick Price</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <a href="hostels.php?max_price=900000<?= $type ? '&type='.$type : '' ?>" class="badge badge-blue" style="cursor:pointer;">Under 900k</a>
                            <a href="hostels.php?max_price=1100000<?= $type ? '&type='.$type : '' ?>" class="badge badge-blue" style="cursor:pointer;">Under 1.1M</a>
                            <a href="hostels.php?max_price=1300000<?= $type ? '&type='.$type : '' ?>" class="badge badge-blue" style="cursor:pointer;">Under 1.3M</a>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="filter-group">
                        <div class="filter-group-label">Availability</div>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="available" value="1" <?= $available ? 'checked' : '' ?>>
                            Available rooms only
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    <a href="hostels.php" class="btn btn-ghost btn-block mt-8">Clear Filters</a>
                </form>
            </aside>

            <!-- =====================
                 HOSTEL LISTINGS
                 ===================== -->
            <main>
                <?php if (empty($hostels)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><img src="<?= APP_URL ?>/assets/svg/building.svg" alt="No hostels"></div>
                        <h3>No Hostels Found</h3>
                        <p>Try adjusting your filters or search terms to find available hostels.</p>
                        <a href="hostels.php" class="btn btn-primary">Clear All Filters</a>
                    </div>
                <?php else: ?>
                    <div class="hostels-grid">
                        <?php foreach ($hostels as $h): ?>
                        <div class="hostel-card">
                            <div class="hostel-card-img">
                                <?php if (!empty($h['image']) && file_exists('uploads/hostels/' . $h['image'])): ?>
                                    <img src="<?= APP_URL ?>/uploads/hostels/<?= sanitize($h['image']) ?>" alt="<?= sanitize($h['hostel_name']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                <?php elseif (!empty($h['image']) && file_exists('assets/img/hostels/' . $h['image'])): ?>
                                    <img src="<?= APP_URL ?>/assets/img/hostels/<?= sanitize($h['image']) ?>" alt="<?= sanitize($h['hostel_name']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                <?php else: ?>
                                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel" style="width: 3.5rem; height: auto;">
                                <?php endif; ?>
                            </div>
                            <div class="hostel-card-body">
                                <div class="hostel-card-badges">
                                    <span class="badge badge-blue"><?= sanitize(ucwords(str_replace('-', ' ', $h['room_type']))) ?></span>
                                    <?php if ($h['availability'] > 0): ?>
                                        <span class="badge badge-green">
                                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Available"> <?= (int)$h['availability'] ?> rooms left
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-red">Full</span>
                                    <?php endif; ?>
                                </div>
                                <div class="hostel-card-title"><?= sanitize($h['hostel_name']) ?></div>
                                <div class="hostel-card-location">
                                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($h['location']) ?>
                                </div>
                                <?php if (!empty($h['description'])): ?>
                                    <p style="font-size:0.82rem;color:var(--text-mid);margin-bottom:12px;line-height:1.5;">
                                        <?= sanitize(substr($h['description'], 0, 80)) ?>…
                                    </p>
                                <?php endif; ?>
                                <div class="hostel-card-footer">
                                    <div class="hostel-price"><?= formatPrice($h['price']) ?> <span>/ sem</span></div>
                                    <a href="hostel-details.php?id=<?= (int)$h['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
